<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/MVC/View/views/view.detail.php');
class Viewcancel extends ViewDetail{
	
	function Viewcancel(){
		parent::ViewDetail();
	}

    public function display()
    {
		//parent::display();
        echo '<br/>Invoice Cancel Action:<br/><br/>';

		if( isset($_REQUEST['record']) && !empty($_REQUEST['record']) ){
			
			global $db;
			
			$crm_invoice = BeanFactory::getBean('AOS_Invoices', $_REQUEST['record']);
			
			if( !empty($crm_invoice->id) ){

				$qb_path = 'qb/v3-sdk-2.3.0/';

				require_once($qb_path.'config.php');
				require_once(PATH_SDK_ROOT . 'Core/ServiceContext.php');
				require_once(PATH_SDK_ROOT . 'DataService/DataService.php');
				require_once(PATH_SDK_ROOT . 'PlatformService/PlatformService.php');
				require_once(PATH_SDK_ROOT . 'Utility/Configuration/ConfigurationManager.php');

				$requestValidator = new OAuthRequestValidator(ConfigurationManager::AppSettings('AccessToken'), ConfigurationManager::AppSettings('AccessTokenSecret'), ConfigurationManager::AppSettings('ConsumerKey'), ConfigurationManager::AppSettings('ConsumerSecret'));
				$realmId = '';
				$realmId = ConfigurationManager::AppSettings('RealmID');
				if (!$realmId) exit("Please add realm to App.Config before running this sample.\n");

				$serviceContext = new ServiceContext($realmId, IntuitServicesType::QBO, $requestValidator);
				if (!$serviceContext) exit("Problem while initializing ServiceContext.\n");

				$dataService = new DataService($serviceContext);
				if (!$dataService) exit("Problem while initializing DataService.\n");

				//--------------------------------------------------------------------------------------------------------

				$qb_customer_id = '';
				if( !empty($crm_invoice->billing_account_id) ){
					$crm_account = BeanFactory::getBean('Accounts', $crm_invoice->billing_account_id);
					$qb_customer_id = $crm_account->qbo_id_c;
					if( empty($qb_customer_id) ){
						//ADD CUSTOMER TO QBO
						try{
							$customerObj = new IPPCustomer();
							$customerObj->Name = $crm_account->name;
							$customerObj->CompanyName = $crm_account->name; 
							$customerObj->GivenName = $crm_account->name;
							$customerObj->DisplayName = $crm_account->name;
							$BillAddr = new IPPPhysicalAddress();
							$BillAddr->Line1 = $crm_account->billing_address_street;
							$BillAddr->City = $crm_account->billing_address_city;
							$BillAddr->PostalCode = $crm_account->billing_address_postalcode;
							//$BillAddr->Country = $crm_account->; // Country code per ISO 3166
							//$BillAddr->CountryCode = $crm_account->; //State for US, Province for Canada
							$customerObj->BillAddr = $BillAddr;
							
							$resultingCustomerObj = $dataService->Add($customerObj);
						} catch (Exception $e) {
							echo 'Error: Unable to add customer, probably he already exist.';
							echo 'Caught exception: ',  $e->getMessage();
						}
						if(!empty($resultingCustomerObj)){
							echo '<br/>QBO Customer added: '.$resultingCustomerObj->Id;
							$qb_customer_id = $resultingCustomerObj->Id;
							//https://sandbox.qbo.intuit.com/app/customerdetail?nameId=
							$crm_account->qbo_id_c = $qb_customer_id;
							$crm_account->save(false);
						}				
					}
				}
				
				
				
				//QBO side CREDIT MEMO
				if(!empty($crm_invoice->qbo_id_c)){
					$line_arr = array();
					
					$sql = " SELECT * FROM aos_products_quotes as c ";
					$sql .= " LEFT JOIN aos_products_quotes_cstm as cc ON c.id = cc.id_c ";
					$sql .= " LEFT JOIN aos_products_cstm as p ON p.id_c = c.product_id ";
					$sql .= " WHERE c.parent_type = 'AOS_Invoices' AND c.parent_id = '{$crm_invoice->id}' AND c.deleted = 0;";
					$result = $crm_invoice->db->query($sql);
					while( $row = $crm_invoice->db->fetchByAssoc($result) ){
				
						if( !empty($row['qbo_id_c']) ){// - crm item id//TODO add LEFT JOIN for products table (cstm to get QB id) qbo_id_c
							$oItemRef = new IPPReferenceType();
							$oItemRef->value = $row['qbo_id_c'];
						}else{
							//wat? add new items to QBO? Error\Warn\Info message?
							$oItemRef = new IPPReferenceType();
							$oItemRef->value = '14' ;//fill with product QBO id
						}
						//$oItemRef->name = 'Sod' ;
						$oSalesItemLineDetail = new IPPSalesItemLineDetail() ;
						$oSalesItemLineDetail->ItemRef = $oItemRef ;
						$oSalesItemLineDetail->TaxCodeRef = 'TAX';//Clarify this!		
						
						$oLine = new IPPLine() ;
						$oLine->Amount = $row['product_total_price'];
						$oLine->Description =  $row['name'];//Free form text description of the line item that appears in the printed record
					
						$oLine->DetailType = 'SalesItemLineDetail' ;
						$oLine->SalesItemLineDetail = $oSalesItemLineDetail ;		
						
						$line_arr[] = $oLine;
					}
					
					try{
						$CustomerRef = new IPPReferenceType();
						$CustomerRef->value = $qb_customer_id;
					
					
						$oCreditMemo = new IPPCreditMemo();
						$oCreditMemo->CustomerRef = $CustomerRef;
						$oCreditMemo->Line = $line_arr;		
				
						$resultingCustomerObj = $dataService->Add($oCreditMemo);
						
						echo 'QNO CreditMemo ID: '.$resultingCustomerObj->Id;//save this somewhere
						echo '&nbsp;&nbsp;<a href="https://sandbox.qbo.intuit.com/app/creditmemo?txnId='.$resultingCustomerObj->Id.'">LNK</a>';
						
					} catch (Exception $e) {
						echo 'Error: Unable to add CreditMemo.';
						echo 'Caught exception: ',  $e->getMessage();
					}
				}
				
				
				//CRM side CREDIT MEMO
				$crm_creditmemo = BeanFactory::getBean('AOS_Invoices');
				$rawRow = $crm_invoice->fetched_row;
				$rawRow['id'] = '';
				$rawRow['template_ddown_c'] = ' ';
				$rawRow['invoice_date'] = date('Y-m-d');
				$crm_creditmemo->populateFromRow($rawRow);
				$crm_creditmemo->status = '';
				
				$crm_creditmemo->aos_invoices_aos_contracts_1aos_contracts_idb = $policy->id;//!!!!Policy Relation
		/*		
				$crm_creditmemo->billing_account_id = $crm_invoice->billing_account_id;//Account Rel
				$crm_creditmemo->billing_address_street = $crm_invoice->billing_address_street;
				$crm_creditmemo->billing_address_city = $crm_invoice->billing_address_city;
				$crm_creditmemo->billing_address_state = $crm_invoice->billing_address_state;
				$crm_creditmemo->billing_address_postalcode = $crm_invoice->billing_address_postalcode;
				$crm_creditmemo->billing_address_country = $crm_invoice->billing_address_country;
					
				$crm_creditmemo->shipping_address_street = $crm_invoice->shipping_address_street;
				$crm_creditmemo->shipping_address_city = $crm_invoice->shipping_address_city;
				$crm_creditmemo->shipping_address_state = $crm_invoice->shipping_address_state;
				$crm_creditmemo->shipping_address_postalcode = $crm_invoice->shipping_address_postalcode;
				$crm_creditmemo->shipping_address_country = $crm_invoice->shipping_address_country;	
		*/
				//$crm_creditmemo->assigned_user_id = $crm_invoice->assigned_user_id;
				
				$crm_creditmemo->process_save_dates =false;
				$crm_creditmemo->name = 'Credit Memo';
				$crm_creditmemo->qbo_id_c = '';
				//TODO: ajust totals
				$crm_creditmemo->products_amount_c *= -1;
				$crm_creditmemo->products_tax_c  *= -1;
				$crm_creditmemo->products_total_c  *= -1;
				
				$crm_creditmemo->charges_amount_c  *= -1;
				$crm_creditmemo->charges_tax_c  *= -1;
				$crm_creditmemo->charges_total_c  *= -1;
				
				$crm_creditmemo->total_amt  *= -1;
				$crm_creditmemo->tax_amount  *= -1;
				$crm_creditmemo->total_amount  *= -1;
				
				$crm_creditmemo->save(false);
				
				echo '<br/><br/>CRM creditMemo: <a href="index.php?module=AOS_Invoices&action=DetailView&record='.$crm_creditmemo->id.'">LNK</a>';
				
				$sql = "SELECT * FROM aos_products_quotes as c
				LEFT JOIN aos_products_quotes_cstm as cc ON c.id = cc.id_c
				WHERE c.parent_type = 'AOS_Invoices' AND c.parent_id = '".$crm_invoice->id."' AND c.deleted = 0";
				$result = $db->query($sql);
				while ($row = $db->fetchByAssoc($result)) {
					$row['id'] = '';
					$row['parent_id'] = $crm_creditmemo->id;
					$row['parent_type'] = 'AOS_Invoices';
					if($row['product_cost_price'] != null){
						$row['product_cost_price'] = format_number($row['product_cost_price'] * -1);
					}
					$row['product_list_price'] = format_number($row['product_list_price'] * -1);
					if($row['product_discount'] != null){
						$row['product_discount'] = format_number($row['product_discount']);
						$row['product_discount_amount'] = format_number($row['product_discount_amount']);
					}
					$row['product_unit_price'] = format_number($row['product_unit_price'] * -1);
					
					$row['vat_amt'] = format_number($row['vat_amt']);
					$row['product_total_price'] = format_number($row['product_total_price'] * -1);
					
					$row['payable_premium_c'] = format_number($row['payable_premium_c'] * -1);
					$row['commission_c'] = format_number($row['commission_c'] * -1);

					$row['charges_amount_c'] = format_number($row['charges_amount_c'] * -1);
					$row['charges_tax_c'] = format_number($row['charges_tax_c']);
					$row['charges_total_c'] = format_number($row['charges_total_c'] * -1);

					$row['products_amount_c'] = format_number($row['products_amount_c']);
					$row['products_tax_c'] = format_number($row['products_tax_c']);
					$row['products_total_c'] = format_number($row['products_total_c'] * -1);

					$row['product_qty'] = 1;

					$prod_invoice = BeanFactory::getBean('AOS_Products_Quotes');
					$prod_invoice->populateFromRow($row);
					$prod_invoice->save(false);
				}
				
				//Policy Invoice Clear
				if( !empty($crm_invoice->aos_invoices_aos_contracts_1aos_contracts_idb) ){
					$crm_policy = BeanFactory::getBean('AOS_Contracts', $crm_invoice->aos_invoices_aos_contracts_1aos_contracts_idb);
					$crm_policy->aos_invoices_aos_contracts_1aos_invoices_ida = '';//free to allow create new
					$crm_policy->status = 'Accepted';//not Invoiced
					$crm_policy->save(false);
					echo '<br/><br/>Policy - OK';
					echo '&nbsp;&nbsp;<a href="index.php?module=AOS_Contracts&action=DetailView&record='.$crm_policy->id.'">LNK</a>';
				}		
				
			}
			
		}else{
			echo 'Error: not enough parameters to accomplish request';
		}	
		
	}

}