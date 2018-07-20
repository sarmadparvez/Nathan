<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');


require_once('include/MVC/View/views/view.detail.php');
class Viewqboexport extends ViewDetail{
	
	function Viewqboexport(){
		parent::ViewDetail();
	}

    public function display()
    {

        echo '<br/>QBO-Export Action<br/>';

		if( isset($_REQUEST['record']) && !empty($_REQUEST['record']) ){

			$qb_path = 'qb/v3-sdk-2.3.0/';

			require_once($qb_path.'config.php');
			require_once(PATH_SDK_ROOT . 'Core/ServiceContext.php');
			require_once(PATH_SDK_ROOT . 'DataService/DataService.php');
			require_once(PATH_SDK_ROOT . 'PlatformService/PlatformService.php');
			require_once(PATH_SDK_ROOT . 'Utility/Configuration/ConfigurationManager.php');

			$requestValidator = new OAuthRequestValidator(ConfigurationManager::AppSettings('AccessToken'), ConfigurationManager::AppSettings('AccessTokenSecret'), ConfigurationManager::AppSettings('ConsumerKey'), ConfigurationManager::AppSettings('ConsumerSecret'));
			
			$realmId = ConfigurationManager::AppSettings('RealmID');
			if (!$realmId) exit("Please add realm to App.Config before running this sample.\n");

			$serviceContext = new ServiceContext($realmId, IntuitServicesType::QBO, $requestValidator);
			if (!$serviceContext) exit("Problem while initializing ServiceContext.\n");

			$dataService = new DataService($serviceContext);
			if (!$dataService) exit("Problem while initializing DataService.\n");

			$qb_customer_id = '';
			$qb_invoice_id = '';
			$producer_class_key = '';
			$invoice_no = '';
			$policy_no = '';
			
			$vendor_qbo_id = '';
			
			$crm_invoice = BeanFactory::getBean('AOS_Invoices', $_REQUEST['record']);
			
			if( !empty($crm_invoice->id) ){

				if(!empty($crm_invoice->qbo_id_c)){
					echo '<br/>Invoice already at QBO: <a href="https://sandbox.qbo.intuit.com/app/invoice?txnId='.$crm_invoice->qbo_id_c.'">'.$crm_invoice->qbo_id_c.'</a>';
					exit();
				}
				
				$invoice_no = $crm_invoice->name;
				
				if( !empty($crm_invoice->assigned_user_id) ){
					$crmProducer =  BeanFactory::getBean('Users', $crm_invoice->assigned_user_id);//echo '<br/>Producer-QBO-Class-key: '.$crmProducer->qbo_class_c;
					$producer_class_key = $crmProducer->qbo_class_c;
				}
				
				if( !empty($crm_invoice->billing_account_id) ){
					$crm_account= BeanFactory::getBean('Accounts', $crm_invoice->billing_account_id);
					$qb_customer_id = $crm_account->qbo_id_c;
					if( !empty($crm_account->id) ){
						if( empty($qb_customer_id) ){
							$customerObj = new IPPCustomer();
							$customerObj->Name = $crm_account->name;
							$customerObj->CompanyName = $crm_account->name; 
							$customerObj->GivenName = $crm_account->name;
							$customerObj->DisplayName = $crm_account->name;
								$BillAddr = new IPPPhysicalAddress();
								$BillAddr->Line1 = $crm_account->billing_address_street;
								//$BillAddr->Line2 = 'Suite D';	//$crm_account->billing_address_state
								$BillAddr->City = $crm_account->billing_address_city;
								$BillAddr->PostalCode = $crm_account->billing_address_postalcode;
								//$BillAddr->Country = $crm_account->; // Country code per ISO 3166
								//$BillAddr->CountryCode = $crm_account->; //State for US, Province for Canada
							$customerObj->BillAddr = $BillAddr;
							try{
								$resultingCustomerObj = $dataService->Add($customerObj);
							} catch (Exception $e) {
								//echo 'Caught exception: ',  $e->getMessage(), "\n";
								echo 'Error: Unable to add customer, probably he already exist.';
							}
							if(!empty($resultingCustomerObj)){
								$qb_customer_id = $resultingCustomerObj->Id;
								echo '<br/>QBO Customer added: <a href="https://sandbox.qbo.intuit.com/app/customerdetail?nameId='.$qb_customer_id.'">'.$qb_customer_id.'</a>';
								//https://sandbox.qbo.intuit.com/app/customerdetail?nameId=
								$crm_account->qbo_id_c = $qb_customer_id;
								$crm_account->save(false);
							}
							//bla($resultingCustomerObj);
						}else{
							echo '<br/>QBO Customer exist: <a href="https://sandbox.qbo.intuit.com/app/customerdetail?nameId='.$qb_customer_id.'">'.$qb_customer_id.'</a>';
						}
					}else{
						echo 'Error Unable to retrieve Customer data';
					}
				}else{
					echo 'Error not specified Customer ID ';
				}
				
				
				if( empty($qb_customer_id) ){
					echo '  NO CUSTOMER ID!';
					exit();
					
				}
				
				$line_arr = array();
				
				$crm_items = array();
				
				$sql = " SELECT * FROM aos_products_quotes as c ";
				$sql .= " LEFT JOIN aos_products_quotes_cstm as cc ON c.id = cc.id_c ";
				$sql .= " LEFT JOIN aos_products_cstm as p ON p.id_c = c.product_id ";
				$sql .= " WHERE c.parent_type = 'AOS_Invoices' AND c.parent_id = '".$crm_invoice->id."' AND c.deleted = 0;";
				$result = $crm_invoice->db->query($sql);
				while( $row = $crm_invoice->db->fetchByAssoc($result) ){
					
					$crm_items[] = array('rate' => $row['commission_rate_c'], 'qbo_id' => $row['qbo_id_c'] , 'amount' => $row['product_total_price'] , 'name' => $row['name'] );
					
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
					$oSalesItemLineDetail->ClassRef = $producer_class_key ;
					//$oSalesItemLineDetail->TaxCodeRef = 'TAX';//Clarify this!		
					
					$oLine = new IPPLine() ;
					$oLine->Amount = $row['product_total_price'];
					$oLine->Description =  $row['name'];//Free form text description of the line item that appears in the printed record
				
					$oLine->DetailType = 'SalesItemLineDetail' ;
					$oLine->SalesItemLineDetail = $oSalesItemLineDetail ;		
				
					$line_arr[] = $oLine;
				}

				$oInvoice = new IPPInvoice();
				$oInvoice->SalesTermRef = 1;//"Due on receipt" NOTE: check on moving to prod
				//$emailA = new IPPEmailAddress();
				//$emailA->Address = "ross@tbecanada.com";
				//$oInvoice->BillEmail = $emailA;
				$oInvoice->DepartmentRef = 1;//Trust (Location)			TODO: add to Config
				$oInvoice->DocNumber = $invoice_no;

					$customField = new IPPCustomField();
					$customField->Type = 'StringType';
					$customField->DefinitionId = 1;
					$customField->StringValue = $policy_no;
				$oInvoice->CustomField[1] = $customField;

				//$oInvoice->CustomerMemo = 'CustomerMemo';
				//$oInvoice->PrivateNote = 'PrivateNote';				

				$oInvoice->CustomerRef = $qb_customer_id;
				$oInvoice->Line = $line_arr;

				try{
					$resultingCustomerObj = $dataService->Add($oInvoice);
				} catch (Exception $e) {
					//echo 'Caught exception: ',  $e->getMessage(), "\n";
					echo 'Error: Unable to add invoice.';
				}
				$qb_inv_id = '';
				$qb_inv_id = $resultingCustomerObj->Id;
				echo '<br/>QBO Invoice link: <a href="https://sandbox.qbo.intuit.com/app/invoice?txnId='.$qb_inv_id.'">'.$qb_inv_id.'</a>';		
				
				if(!empty($qb_inv_id)){
					$crm_invoice->qbo_id_c = $qb_inv_id;
					$crm_invoice->exists_qbo_c = 1;
					$crm_invoice->save(false);
				}

				//BILLs - begin
				
				if( !empty($crm_invoice->insrr_insurers_id_c) ){
					$crmInsurer =  BeanFactory::getBean('insrr_Insurers', $crm_invoice->insrr_insurers_id_c);//echo '<br/>Insurer-QBOid: '.$crmInsurer->qbo_id_c;
					$vendor_qbo_id = $crmInsurer->qbo_id_c;
				}
				if( empty($vendor_qbo_id) ){
					echo '<br/>';
					echo 'Insurer/Vendor doesnt have QBO id, please update it to be able export bills.';
					echo '<br/>';
					exit;
				}

				
				
				if( !empty($crm_items) ){
					
					//-------------------------VENDOR BILL-----------------BEGIN
					echo '<br/>';
					echo '<br/>Insurer Bill...';
					$oBill = new IPPBill();
					//$oBill->DueDate = '';
					$oBill->DocNumber = $policy_no;
					$oBill->DepartmentRef = 1;//Trust (Location)			TODO: add to Config
					$oBill->SalesTermRef = 1;//"Due on receipt" NOTE: check on moving to prod
					$oBill->VendorRef = $vendor_qbo_id;
					//$oBill->APAccountRef = '';//What is this?
					//$crm_items[] = array('rate' => $row['commission_rate_c'], 'qbo_id' => $row['qbo_id_c'] , 'amount' => $row['product_total_price'] , 'name' => $row['name'] );
					$line_arr = array();
					foreach($crm_items as $i => $item){
						//----------
						$oLine = new IPPLine();
						$oLine->Amount = $item['amount'];
						$oLine->DetailType = 'AccountBasedExpenseLineDetail';
						$oLine->Description =  '';//$item['name']
							$oAccountBasedExpenseLineDetail = new IPPAccountBasedExpenseLineDetail() ;
							$oAccountBasedExpenseLineDetail->AccountRef = 91;//2100 Trust                       TODO: add to Config
							$oAccountBasedExpenseLineDetail->CustomerRef = $qb_customer_id;
							$oAccountBasedExpenseLineDetail->ClassRef  = $producer_class_key;
						$oLine->AccountBasedExpenseLineDetail = $oAccountBasedExpenseLineDetail ;		
						$line_arr[] = $oLine;
						//----------
						if( !empty($item['rate']) ){
							$amount_commission = ($item['amount'] * $item['rate']) / 100;
							$oLine = new IPPLine();
							$oLine->Amount = -$amount_commission;
							$oLine->DetailType = 'AccountBasedExpenseLineDetail';
							$oLine->Description =  $item['rate'].'%';
								$oAccountBasedExpenseLineDetail = new IPPAccountBasedExpenseLineDetail() ;
								$oAccountBasedExpenseLineDetail->AccountRef = 94;//4050 "Commission Income"    TODO: add to Config
								$oAccountBasedExpenseLineDetail->CustomerRef = $qb_customer_id;
								$oAccountBasedExpenseLineDetail->ClassRef  = $producer_class_key;
							$oLine->AccountBasedExpenseLineDetail = $oAccountBasedExpenseLineDetail ;		
							$line_arr[] = $oLine;
						}
					}
					//What about "MGA Fee"?
					$oBill->Line = $line_arr;//array($oLine1, $oLine2) ;	
					try{
						$resultingCustomerObj = $dataService->Add($oBill);
						$qbo_id = '';
						$qbo_id = $resultingCustomerObj->Id;
						echo '<br/>DONE  QBO link: <a href="https://sandbox.qbo.intuit.com/app/bill?txnId='.$qbo_id.'">'.$qbo_id.'</a>';
					}catch (Exception $e){
						echo 'Caught exception: ',  $e->getMessage(), "\n";
					}
					
					//-----------------------VENDOR BILL-----------------END
					
					//-----------------------PRODUCER BILL----------BEGIN--------------//
					echo '<br/>';
					echo '<br/>Producer Bill...';
					
					if( !empty($crm_invoice->aos_invoices_aos_contracts_1aos_contracts_idb) ){
						$crmPolicy =  BeanFactory::getBean('AOS_Contracts', $crm_invoice->aos_invoices_aos_contracts_1aos_contracts_idb);
					}
					
					//$amount = 300;
					//$amount_description = '';//exp: 35% * ( amnt * 30% + 100 )
					$rate = 35;//Fill from Policy
					if(!empty($crmPolicy->c_rate_c)){
						$rate = $crmPolicy->c_rate_c;	
					}
						
					$oBill = new IPPBill();
					$oBill->DocNumber = $policy_no;
					$oBill->DepartmentRef = 2; //"Producers"  (Location) TODO: add to Config
					$oBill->SalesTermRef = 1;//"Due on receipt" NOTE: check on moving to prod
					$oBill->VendorRef = 85;//"Producer Commissions Payable" TODO: add to Config
					$line_arr = array();
					foreach($crm_items as $i => $item){
						if( !empty($item['rate']) ){
							$oLine = new IPPLine();
							//echo '<br/>Amnt: '.
							$oLine->Amount = ( ( ($item['amount'] * $item['rate']) / 100 ) * $rate) / 100;
							//echo '<br/>Desc: '.
							$oLine->Description =  "{$rate}% * ( {$item['amount']} * {$item['rate']}%)";
							//TODO: remember about fee
							$oLine->DetailType = 'AccountBasedExpenseLineDetail';
								$oAccountBasedExpenseLineDetail = new IPPAccountBasedExpenseLineDetail() ;
								$oAccountBasedExpenseLineDetail->AccountRef = 93;//5100 Producer Expense  			TODO: add to Config
								$oAccountBasedExpenseLineDetail->CustomerRef = $qb_customer_id;
								$oAccountBasedExpenseLineDetail->ClassRef  = $producer_class_key;
							$oLine->AccountBasedExpenseLineDetail = $oAccountBasedExpenseLineDetail ;		
							$line_arr[] = $oLine;
						}
						//----------
					}
					$oBill->Line = $line_arr;//array($oLine1, $oLine2);
					try{
						$resultingCustomerObj = $dataService->Add($oBill);
						$qbo_id = '';
						$qbo_id = $resultingCustomerObj->Id;
						echo '<br/>DONE   QBO link: <a href="https://sandbox.qbo.intuit.com/app/bill?txnId='.$qbo_id.'">'.$qbo_id.'</a>';
					}catch (Exception $e){
						echo 'Caught exception: ',  $e->getMessage(), "\n";
					}
					//-----------------------PRODUCER BILL---------END---------------//
					
					
				}
				

				//BILLs - end
				
				
				
				
			}else{
				echo 'Error: unable to retrieve Invoice data';
			}
		}else{
			echo 'Error: not enough parameters to accomplish request';
		}
		
		
 	}

}