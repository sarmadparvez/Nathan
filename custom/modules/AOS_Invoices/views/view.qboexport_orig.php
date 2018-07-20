<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

//#UPDATE aos_invoices_cstm SET qbo_id_c = '', qbo_bill_p_id_c = '', qbo_bill_v_id_c = '' WHERE id_c = '3557ec9f-0010-1b78-4acc-56afd20a5727';
//UPDATE aos_invoices_cstm as i SET i.qbo_id_c = '' , i.qbo_bill_v_id_c = '' , i.qbo_bill_p_id_c = '' , i.qbo_creditmemo_id_c = '' WHERE id_c = 'aa6b0c6d-4bf8-4037-2528-56b2726ad703';
require_once('include/MVC/View/views/view.detail.php');
class Viewqboexport extends ViewDetail{
	
	function Viewqboexport(){
		parent::ViewDetail();
	}

    public function display()
    {
		
		global $timedate, $current_user;
		
		echo '<br/>Back to <a href="index.php?module=AOS_Invoices&action=DetailView&record='.$_REQUEST['record'].'">Invoice</a><br/>';
		
		//if($current_user->id !== '3c4e92c9-c147-2849-0c10-54f4b2c4a466'){//ITsupport
		//	echo '<b>Export Function is Temporarily Unavailable</b>'; 
		//	exit;
		//}
		
        echo '<br/>QBO-Export Action:<br/>';
	
		
		$tbe_qbo = BeanFactory::getBean('tbe_qbo');
			
		if($tbe_qbo->isAllowedQBO()){
			
			$tbe_qbo->retrieveSetting();

			if(empty($tbe_qbo->access_token)){
				  echo '<br/>Seems you havent connected with QBO';
				  exit;
			}

			//echo '--D--';
			//exit;
			
			if( isset($_REQUEST['record']) && !empty($_REQUEST['record']) ){
				//$qb_path = 'qb/v3-sdk-2.3.0/';
				//require_once($qb_path.'config.php');
				require_once($tbe_qbo->sdk_path.'config.php');
				
				//require_once('custom/qb/v3-sdk-2.4.1/config.php');
				
				require_once(PATH_SDK_ROOT . 'Core/ServiceContext.php');
				require_once(PATH_SDK_ROOT . 'DataService/DataService.php');
				require_once(PATH_SDK_ROOT . 'PlatformService/PlatformService.php');
				//require_once(PATH_SDK_ROOT . 'Utility/Configuration/ConfigurationManager.php');
				if (empty($tbe_qbo->realmid)) exit("RealmID is not specified.\n");
				$requestValidator = new OAuthRequestValidator($tbe_qbo->access_token, $tbe_qbo->access_token_secret, $tbe_qbo->consumer_key, $tbe_qbo->consumer_secret);
				if (!$requestValidator) exit("Problem while initializing requestValidator.\n");
				$serviceContext = new ServiceContext($tbe_qbo->realmid, IntuitServicesType::QBO, $requestValidator);
				if (!$serviceContext) exit("Problem while initializing ServiceContext.\n");
				$dataService = new DataService($serviceContext);
				if (!$dataService) exit("Problem while initializing DataService.\n");	

				$qb_customer_id = '';
				$qb_invoice_id = '';
				$producer_class_key = '';
				$invoice_no = '';
				$policy_no = '';

				$producer_name = '';
				
				$vendor_qbo_id = '';
				
				$crm_invoice = BeanFactory::getBean('AOS_Invoices', $_REQUEST['record']);
				
				//TODO: add to Config
				$cnf_bill_producer_vendor_id = 636;//"Producer Commissions Payable" (prod name: Producer Commissions Accrued)
				$cnf_bill_producer_term_id = 1;//"Due on receipt"
				$cnf_bill_producer_dep_id = 3;//"Office"(Location)//OK
				$cnf_invoice_term_id = 1;//"Due on receipt"
				$cnf_invoice_dep_id =  1;//Trust (Location) //OK
				$cnf_invoice_dc_dep_id =  3;//(DirectCommision)General',//OK
				$cnf_bill_producer_item_acc_id = 19;//5100 Producer Expense//OK
				$cnf_bill_vendor_item_acc_commission_id = 1;//4050 "Commission Income"//OK
				$cnf_bill_vendor_item_acc_id = 12;//2310 Trust
				$cnf_bill_vendor_term_id = 1;
				$cnf_bill_vendor_dep_id = 1;//Trust (Location)
				
/* AI TaxCode
          "Id": "2",           "Name": "Exempt",
          "Id": "5",          "Name": "HST ON",
          "Id": "7",          "Name": "Out of Scope",
          "Id": "6",          "Name": "RST Ontario",
          "Id": "3",          "Name": "Zero-rated",
*/			
				


				if( !empty($crm_invoice->id) ){

					if( !empty($crm_invoice->is_creditmemo_c) ){
						echo '<br/>CreditMemo is not supported for "Export" operation.<br/>';
						echo '<br/><a href="index.php?module=AOS_Invoices&action=DetailView&record='.$crm_invoice->id.'">Go back</a>';
						exit;
					}
					
					$crmPolicy =  '';
					if( !empty($crm_invoice->aos_invoices_aos_contracts_1aos_contracts_idb) ){
						$crmPolicy =  BeanFactory::getBean('AOS_Contracts', $crm_invoice->aos_invoices_aos_contracts_1aos_contracts_idb);
						$policy_no = $crmPolicy->name;
						if( !empty($crmPolicy->assigned_user_id) ){
							$crmProducer =  BeanFactory::getBean('Users', $crm_invoice->assigned_user_id);
							$producer_name = $crmProducer->first_name.' '.$crmProducer->last_name;
							$producer_class_key = $crmProducer->qbo_class_c;
						}
					}
					if( empty($producer_class_key) ){
						echo '<br/>Policy Producer Class is not defined!';
						exit;
					}
					
					$invoice_no = $crm_invoice->name;

					if($crmPolicy->direct_commission_c){
//-----DIRECT COMMISSION-------BEGIN
						$vendor_name = '';
						$vendor_customer_id = '';
						if( !empty($crm_invoice->insrr_insurers_id_c) ){
							$crmInsurer =  BeanFactory::getBean('insrr_Insurers', $crm_invoice->insrr_insurers_id_c);//echo '<br/>Insurer-QBOid: '.$crmInsurer->qbo_id_c;
							$vendor_qbo_id = $crmInsurer->qbo_id_c;
							$vendor_name = $crmInsurer->name;
							$vendor_customer_id = $crmInsurer->qbo_customer_id_c;//qb_customer_id
						}
						
						if( empty($vendor_qbo_id) ){
							echo '<br/>';
							echo "Insurer/Vendor doesnt have QBO id, please update it to be able export bills.";
							echo '<br/>';
							exit;
						}
						if( empty($vendor_customer_id) ){
							echo '<br/>';
							echo "Insurer/Vendor doesnt have QBO Customer ID, please update it to be able export invoice.";
							echo '<br/>';
							exit;
						}
						$qb_customer_id = '';
						$qb_customer_name = '';
						if( !empty($crm_invoice->billing_account_id) ){
							$crm_account= BeanFactory::getBean('Accounts', $crm_invoice->billing_account_id);
							$qb_customer_name = $crm_account->name;
							$qb_customer_id = $crm_account->qbo_id_c;
						
							if( empty($qb_customer_id) ){
								$customerObj = new IPPCustomer();
								$customerObj->Name = $crm_account->name;
								
								$customerObj->Notes = $crm_account->account_code_c;
								
								$customerObj->CompanyName = $crm_account->name; 
								//$customerObj->GivenName = $crm_account->name;
								$customerObj->DisplayName = $crm_account->name;
									$BillAddr = new IPPPhysicalAddress();
									$BillAddr->Line1 = $crm_account->billing_address_street;
									//$BillAddr->Line2 = 'Suite D';	//$crm_account->billing_address_state
									$BillAddr->CountrySubDivisionCode = $crm_account->billing_address_state;//ROSS
									$BillAddr->City = $crm_account->billing_address_city;
									$BillAddr->PostalCode = $crm_account->billing_address_postalcode;
									//$BillAddr->Country = $crm_account->; // Country code per ISO 3166
									//$BillAddr->CountryCode = $crm_account->; //State for US, Province for Canada
								$customerObj->BillAddr = $BillAddr;
								try{
									$resultingCustomerObj = $dataService->Add($customerObj);
								} catch (Exception $e) {
									echo 'Error: Unable to add customer, probably he already exist.';
									echo 'Caught exception: ',  $e->getMessage(), "\n";
								}
								if(!empty($resultingCustomerObj)){
									$qb_customer_id = $resultingCustomerObj->Id;
									//echo '<br/>QBO Customer added: <a href="https://sandbox.qbo.intuit.com/app/customerdetail?nameId='.$qb_customer_id.'">'.$qb_customer_id.'</a>';
									//https://sandbox.qbo.intuit.com/app/customerdetail?nameId=
									$crm_account->qbo_id_c = $qb_customer_id;
									$crm_account->save(false);
								}
							}
						}
						
						if( empty($qb_customer_id) ){
							echo '<br/>Error not specified Customer ID ';
							exit;
						}
						
						$no_ids = array();
//-----DIRECT COMMISSION-------LINE-ITEMS-RETRIEVE--------BEGIN
						$line_arr = array();
						$crm_items = array();
						$sql = " SELECT c.name, p.qbo_id_c, c.product_unit_price, c.vat, cc.commission_rate_c, p.to_insurer_c, c.item_description FROM aos_products_quotes as c ";//
						$sql .= " LEFT JOIN aos_products_quotes_cstm as cc ON c.id = cc.id_c ";
						$sql .= " LEFT JOIN aos_products_cstm as p ON p.id_c = c.product_id ";
						$sql .= " WHERE c.parent_type = 'AOS_Invoices' AND c.parent_id = '".$crm_invoice->id."' AND c.deleted = 0;";
						$result = $crm_invoice->db->query($sql);
						while( $row = $crm_invoice->db->fetchByAssoc($result) ){
							$crm_items[] = array('rate' => $row['commission_rate_c'], 'qbo_id' => $row['qbo_id_c'] , 'amount' => $row['product_unit_price'] , 'name' => $row['name'] , 'to_insurer_c' => $row['to_insurer_c'] );
							if( !empty($row['qbo_id_c']) ){
								$oLine = new IPPLine() ;
								$oLine->Amount = $row['product_unit_price'];
								$oLine->Description =  $row['item_description'];//   Free form text description of the line item that appears in the printed record
								$oLine->DetailType = 'SalesItemLineDetail' ;
									$oSalesItemLineDetail = new IPPSalesItemLineDetail();
										//$oItemRef = new IPPReferenceType();
										//$oItemRef->value = $row['qbo_id_c'];
									$oSalesItemLineDetail->ItemRef = $row['qbo_id_c'];
									$oSalesItemLineDetail->ClassRef = $producer_class_key;
									$oSalesItemLineDetail->TaxCodeRef = 2;//"Id": "2", "Name": "Exempt",//TODO: add to conf
								$oLine->SalesItemLineDetail = $oSalesItemLineDetail;
								$line_arr[] = $oLine;
							}else{
								$no_ids[] = array('id' => $row['id'], 'name' => $row['name'] );
							}
						}
//-----DIRECT COMMISSION-----LINE-ITEMS-RETRIEVE--------END
						if( !empty($no_ids) ){
							echo '<br/>There is some products that have to be filled with QBO ID:';
							foreach($no_ids as $item){
								echo '<br/><a href="index.php?module=AOS_Products&action=DetailView&record='.$item['id'].'">'.$item['name'].'</a>';
							}
							echo '<br/>';
							exit;
						}
//-----DIRECT COMMISSION------INVOICE----BEGIN
						$invoice_date = $timedate->to_db_date($crm_invoice->invoice_date);
						$due_date = $timedate->to_db_date($crm_invoice->due_date);
						
						$qb_inv_id = $crm_invoice->qbo_id_c;
						if( empty($crm_invoice->qbo_id_c) ){
							$oInvoice = new IPPInvoice();
							$oInvoice->SalesTermRef = $cnf_invoice_term_id;
							$oInvoice->DueDate = $due_date;
							$oInvoice->TxnDate = $invoice_date;
							$oInvoice->DepartmentRef = $cnf_invoice_dc_dep_id;//General(Location)
							$oInvoice->DocNumber = $invoice_no;
							
								$customField = new IPPCustomField();
								$customField->Type = 'StringType';
								$customField->DefinitionId = 1;
								$customField->StringValue = $policy_no;
							$oInvoice->CustomField[] = $customField;
							
								$customField = new IPPCustomField();
								$customField->Type = 'StringType';
								$customField->DefinitionId = 3;
								$customField->StringValue = $producer_name;
							$oInvoice->CustomField[] = $customField;
							
							//$oInvoice->CustomerMemo = $vendor_name;//Ross
							//$oInvoice->PrivateNote = $vendor_name;//Ross
							$oInvoice->PrivateNote = $policy_no;//Ross
							//$oInvoice->PrivateNote = 'PrivateNote';	
							$oInvoice->CustomerRef = $vendor_customer_id;
							$oInvoice->GlobalTaxCalculation = "TaxExcluded";//TODO:Clarify
							$oInvoice->Line = $line_arr;
							try{
								$resultingCustomerObj = $dataService->Add($oInvoice);
								$qb_inv_id = $resultingCustomerObj->Id;
								if( !empty($qb_inv_id) ){
									$crm_invoice->qbo_id_c = $qb_inv_id;
									$crm_invoice->exists_qbo_c = 1;
									$crm_invoice->save(false);
								}							
							} catch (Exception $e) {
								echo 'Error: Unable to add invoice.';
								echo 'Caught exception: ',  $e->getMessage(), "\n";
							}

						}
						echo '<br/>QBO Invoice ID: '.$crm_invoice->qbo_id_c;
//-----DIRECT COMMISSION------INVOICE----END
//-----DIRECT COMMISSION------PRODUCER BILL--------------BEGIN--------------//
						echo '<br/>';
						echo '<br/>Producer Bill...';
						if(empty($crm_invoice->qbo_bill_p_id_c)){
							$rate = 35;//TODO: add to conf or make error msg
							//if(!empty($crmPolicy->c_rate_c)){
							//	$rate = $crmPolicy->c_rate_c;	
							//}
							$rate = $crmPolicy->c_rate_c;	
							if( empty($rate) ){ $rate = 0;}
							
							$oBill = new IPPBill();
							//$oBill->DueDate = $due_date;
							$oBill->TxnDate = $invoice_date;
							$oBill->DocNumber = $policy_no;
							$oBill->DepartmentRef = $cnf_bill_producer_dep_id; //"Producers"  (Location)
							$oBill->SalesTermRef = $cnf_bill_producer_term_id;//"Due on receipt" NOTE: check on moving to prod
							$oBill->VendorRef = $cnf_bill_producer_vendor_id;//"Producer Commissions Payable"   //Insurer ID
							//$oBill->GlobalTaxCalculation = "TaxExcluded";
							$oBill->PrivateNote = $policy_no;//$qb_customer_name;//Ross
							//$oBill->CustomerMemo = $qb_customer_name;//Ross
							$line_arr = array();
							foreach($crm_items as $i => $item){
								//$_item_rate = (float) $item['rate'];
								//if( $_item_rate > 0 ){
									$oLine = new IPPLine();
									//$oLine->Amount = ( ( ($item['amount'] * $item['rate']) / 100 ) * $rate) / 100;
									$oLine->Amount = ( $item['amount']   * $rate) / 100;
									$oLine->Description = " {$rate}% * ";
									$oLine->Description .= number_format($item['amount'], 2, '.', ',');
									//$oLine->Description .= " * {$_item_rate}%)";
									$oLine->DetailType = 'AccountBasedExpenseLineDetail';
										$oAccountBasedExpenseLineDetail = new IPPAccountBasedExpenseLineDetail();
										$oAccountBasedExpenseLineDetail->AccountRef =  $cnf_bill_producer_item_acc_id;// = 93;//5100 Producer Expense
										$oAccountBasedExpenseLineDetail->CustomerRef = $qb_customer_id;
										$oAccountBasedExpenseLineDetail->ClassRef  = $producer_class_key;
									$oLine->AccountBasedExpenseLineDetail = $oAccountBasedExpenseLineDetail ;		
									$line_arr[] = $oLine;
								//}
							}
							$oBill->Line = $line_arr;
							$qbo_id = '';
							try{
								$resultingCustomerObj = $dataService->Add($oBill);//Ross
								$qbo_id = $resultingCustomerObj->Id;
							}catch (Exception $e){
								echo '<br/>Failed to add Producer Bill';
								echo 'Caught exception: ',  $e->getMessage(), "\n";
							}
							$crm_invoice->qbo_bill_p_id_c = $qbo_id;
							if( !empty($crm_invoice->qbo_bill_p_id_c) ){
								$crm_invoice->save(false);
							}
						}
						echo '<br/>QBO Producer Bill ID: '.$crm_invoice->qbo_bill_p_id_c;
//-----DIRECT COMMISSION----------PRODUCER BILL---------END---------------//		
//-----DIRECT COMMISSION-------END
					}else{
	//---Customer-----BEGIN
						$qb_customer_id = '';
						$qb_customer_name = '';
						if( !empty($crm_invoice->billing_account_id) ){
							$crm_account= BeanFactory::getBean('Accounts', $crm_invoice->billing_account_id);
							$qb_customer_name = $crm_account->name;
							$qb_customer_id = $crm_account->qbo_id_c;
							if( !empty($crm_account->id) ){
								if( empty($qb_customer_id) ){
									$customerObj = new IPPCustomer();
									$customerObj->Name = $crm_account->name;
									
									$customerObj->Notes = $crm_account->account_code_c;
									
									$customerObj->CompanyName = $crm_account->name; 
									//$customerObj->GivenName = $crm_account->name;
									$customerObj->DisplayName = $crm_account->name;
										$BillAddr = new IPPPhysicalAddress();
										$BillAddr->Line1 = $crm_account->billing_address_street;
										//$BillAddr->Line2 = 'Suite D';	//$crm_account->billing_address_state
										$BillAddr->CountrySubDivisionCode = $crm_account->billing_address_state;//ROSS
										$BillAddr->City = $crm_account->billing_address_city;
										$BillAddr->PostalCode = $crm_account->billing_address_postalcode;
										//$BillAddr->Country = $crm_account->; // Country code per ISO 3166
										//$BillAddr->CountryCode = $crm_account->; //State for US, Province for Canada
									$customerObj->BillAddr = $BillAddr;
									try{
										$resultingCustomerObj = $dataService->Add($customerObj);//Ross
									} catch (Exception $e) {
										echo 'Error: Unable to add customer, probably he already exist.';
										echo 'Caught exception: ',  $e->getMessage(), "\n";
									}
									if(!empty($resultingCustomerObj)){
										$qb_customer_id = $resultingCustomerObj->Id;
										//echo '<br/>QBO Customer added: <a href="https://sandbox.qbo.intuit.com/app/customerdetail?nameId='.$qb_customer_id.'">'.$qb_customer_id.'</a>';
										//https://sandbox.qbo.intuit.com/app/customerdetail?nameId=
										$crm_account->qbo_id_c = $qb_customer_id;
										$crm_account->save(false);
									}
								}
							}else{
								echo 'Error Unable to retrieve Customer data';
							}
						}else{
							echo 'Error not specified Customer ID ';
						}
						//echo '<br/>QBO Customer exist : <a href="https://sandbox.qbo.intuit.com/app/customerdetail?nameId='.$qb_customer_id.'">'.$qb_customer_id.'</a>';
						echo '<br/>QBO Customer ID: '.$qb_customer_id;
	//---Customer-----END
						$vendor_name = '';
						if( !empty($crm_invoice->insrr_insurers_id_c) ){
							$crmInsurer =  BeanFactory::getBean('insrr_Insurers', $crm_invoice->insrr_insurers_id_c);//echo '<br/>Insurer-QBOid: '.$crmInsurer->qbo_id_c;
							$vendor_qbo_id = $crmInsurer->qbo_id_c;
							$vendor_name = $crmInsurer->name;
						}
						if( empty($vendor_qbo_id) ){
							echo '<br/>';
							echo "Insurer/Vendor doesnt have QBO id, please update it to be able export bills.";
							echo '<br/>';
							exit;
						}
						/*
						if( !empty($crm_invoice->assigned_user_id) ){
							$crmProducer =  BeanFactory::getBean('Users', $crm_invoice->assigned_user_id);//echo '<br/>Producer-QBO-Class-key: '.$crmProducer->qbo_class_c;
							$producer_class_key = $crmProducer->qbo_class_c;
							//TODO: add check if producer at police have class value
							//exit();
						}
						if( empty($producer_class_key) ){
							echo '<br/>Producer Class is not defined';
							exit;
						}*/

						$no_ids = array();
	//--LINE-ITEMS-RETRIEVE--------BEGIN
						$line_arr = array();
						$crm_items = array();
						$sql = " SELECT c.name, p.qbo_id_c, c.product_unit_price, c.vat, cc.commission_rate_c, p.to_insurer_c FROM aos_products_quotes as c ";
						$sql .= " LEFT JOIN aos_products_quotes_cstm as cc ON c.id = cc.id_c ";
						$sql .= " LEFT JOIN aos_products_cstm as p ON p.id_c = c.product_id ";
						$sql .= " WHERE c.parent_type = 'AOS_Invoices' AND c.parent_id = '".$crm_invoice->id."' AND c.deleted = 0;";
						$result = $crm_invoice->db->query($sql);
						while( $row = $crm_invoice->db->fetchByAssoc($result) ){
							$crm_items[] = array('rate' => $row['commission_rate_c'], 'qbo_id' => $row['qbo_id_c'] , 'amount' => $row['product_unit_price'] , 'name' => $row['name'] , 'to_insurer_c' => $row['to_insurer_c'] );
							if( !empty($row['qbo_id_c']) ){
								$oLine = new IPPLine() ;
								$oLine->Amount = $row['product_unit_price'];
								$oLine->Description =  $row['name'];//Free form text description of the line item that appears in the printed record
								$oLine->DetailType = 'SalesItemLineDetail' ;
									$oSalesItemLineDetail = new IPPSalesItemLineDetail();
										$oItemRef = new IPPReferenceType();
										$oItemRef->value = $row['qbo_id_c'];
									$oSalesItemLineDetail->ItemRef = $oItemRef;
									$oSalesItemLineDetail->ClassRef = $producer_class_key;
									$vat = (float) $row['vat'];
									if( $vat > 0 ){//TODO: possible other tax
										$oSalesItemLineDetail->TaxCodeRef = 6;//RST Ontario  TODO: add to config this
									}else{
										$oSalesItemLineDetail->TaxCodeRef = 2;//"Id": "2",           "Name": "Exempt",
									}
								$oLine->SalesItemLineDetail = $oSalesItemLineDetail;
								$line_arr[] = $oLine;
							}else{
								$no_ids[] = array('id' => $row['id'], 'name' => $row['name'] );
							}
						}
	//--LINE-ITEMS-RETRIEVE--------END
	//echo '<br/>Debug: Line items arr:';
	//echo '<pre>';print_r($crm_items);echo '</pre>';
						if( !empty($no_ids) ){
							echo '<br/>There is some products that have to be filled with QBO ID:';
							foreach($no_ids as $item){
								echo '<br/><a href="index.php?module=AOS_Products&action=DetailView&record='.$item['id'].'">'.$item['name'].'</a>';
							}
							echo '<br/>';
							exit;
						}
						if( empty($qb_customer_id) ){
							echo 'Error: NO CUSTOMER ID!';
							exit();
						}
						


						$invoice_date = $timedate->to_db_date($crm_invoice->invoice_date);//<<---------
						$due_date = $timedate->to_db_date($crm_invoice->due_date);
						
						$qb_inv_id = $crm_invoice->qbo_id_c;
						if( empty($crm_invoice->qbo_id_c) ){
							$oInvoice = new IPPInvoice();
							$oInvoice->SalesTermRef = $cnf_invoice_term_id;//"Due on receipt"
							//$emailA = new IPPEmailAddress();
							//$emailA->Address = "ross@tbecanada.com";
							//$oInvoice->BillEmail = $emailA;
							$oInvoice->DueDate = $due_date;
							$oInvoice->TxnDate = $invoice_date;
							$oInvoice->DepartmentRef = $cnf_invoice_dep_id;//Trust (Location)
							$oInvoice->DocNumber = $invoice_no;
							
								$customField = new IPPCustomField();
								$customField->Type = 'StringType';
								$customField->DefinitionId = 1;
								$customField->StringValue = $policy_no;
							$oInvoice->CustomField[] = $customField;
							
								$customField = new IPPCustomField();
								$customField->Type = 'StringType';
								$customField->DefinitionId = 3;
								$customField->StringValue = $producer_name;
							$oInvoice->CustomField[] = $customField;
							
							//$oInvoice->CustomerMemo = $vendor_name;//Ross
							$oInvoice->PrivateNote = $vendor_name;//Ross
							//$oInvoice->PrivateNote = 'PrivateNote';	
							$oInvoice->CustomerRef = $qb_customer_id;
							$oInvoice->GlobalTaxCalculation = "TaxExcluded";
							$oInvoice->Line = $line_arr;
							try{
								$resultingCustomerObj = $dataService->Add($oInvoice);
								$qb_inv_id = $resultingCustomerObj->Id;
								if( !empty($qb_inv_id) ){
									$crm_invoice->qbo_id_c = $qb_inv_id;
									$crm_invoice->exists_qbo_c = 1;
									$crm_invoice->save(false);
								}							
							} catch (Exception $e) {
								echo 'Error: Unable to add invoice.';
								echo 'Caught exception: ',  $e->getMessage(), "\n";
							}

						}
						echo '<br/>QBO Invoice ID: '.$crm_invoice->qbo_id_c;
	//BILLs - begin
						if( !empty($crm_items) ){
	//-------------------------VENDOR BILL-----------------BEGIN
							echo '<br/>';
							echo '<br/>Insurer Bill...';
							if(empty($crm_invoice->qbo_bill_v_id_c)){
								$oBill = new IPPBill();
								//$oBill->DueDate = $due_date;
								$oBill->TxnDate = $invoice_date;
								$oBill->DocNumber = $policy_no;//.'_16';
								$oBill->DepartmentRef = $cnf_bill_vendor_dep_id;
								$oBill->SalesTermRef = $cnf_bill_vendor_term_id;
								$oBill->VendorRef = $vendor_qbo_id;
								//$oBill->GlobalTaxCalculation = "TaxExcluded";
								
								$oBill->PrivateNote = $qb_customer_name;//Ross
								//$oBill->CustomerMemo = $qb_customer_name;//Ross
								
								//$oBill->APAccountRef = '';//What is this?
								//$crm_items[] = array('rate' => $row['commission_rate_c'], 'qbo_id' => $row['qbo_id_c'] , 'amount' => $row['product_total_price'] , 'name' => $row['name'] );
								$line_arr = array();
								foreach($crm_items as $i => $item){
									if($item['to_insurer_c']){
										//----------
										$oLine = new IPPLine();
										$oLine->Amount = $item['amount'];
										$oLine->DetailType = 'AccountBasedExpenseLineDetail';
										$oLine->Description =  $item['name'];
											$oAccountBasedExpenseLineDetail = new IPPAccountBasedExpenseLineDetail() ;
											$oAccountBasedExpenseLineDetail->AccountRef =  $cnf_bill_vendor_item_acc_id;
											$oAccountBasedExpenseLineDetail->CustomerRef = $qb_customer_id;
											$oAccountBasedExpenseLineDetail->ClassRef  = $producer_class_key;
										$oLine->AccountBasedExpenseLineDetail = $oAccountBasedExpenseLineDetail ;		
										$line_arr[] = $oLine;
										//----------
										$_item_rate = (float) $item['rate'];
										if( $_item_rate > 0 ){
											$amount_commission = ($item['amount'] * $_item_rate) / 100;
											$oLine = new IPPLine();
											//echo '<br/>Amt: '.
											$oLine->Amount = $amount_commission * -1;
											$oLine->DetailType = 'AccountBasedExpenseLineDetail';
											//echo '<br/>Desc: '.
											$oLine->Description =  $_item_rate.'%';
												$oAccountBasedExpenseLineDetail = new IPPAccountBasedExpenseLineDetail() ;
												$oAccountBasedExpenseLineDetail->AccountRef = $cnf_bill_vendor_item_acc_commission_id;
												$oAccountBasedExpenseLineDetail->CustomerRef = $qb_customer_id;
												$oAccountBasedExpenseLineDetail->ClassRef  = $producer_class_key;
											$oLine->AccountBasedExpenseLineDetail = $oAccountBasedExpenseLineDetail ;		
											$line_arr[] = $oLine;
										}
									}
								}
								//What about "MGA Fee"?
								//TODO: check if lineItems are not empty?
								$oBill->Line = $line_arr;//array($oLine1, $oLine2) ;	
								$qbo_id = '';
								try{
									$resultingCustomerObj = $dataService->Add($oBill);//Ross
									$qbo_id = $resultingCustomerObj->Id;
								}catch (Exception $e){
									echo '<br/>Failed to add Vendor Bill';
									echo 'Caught exception: ',  $e->getMessage(), "\n";
								}
								$crm_invoice->qbo_bill_v_id_c = $qbo_id;
								if(!empty($crm_invoice->qbo_bill_v_id_c)){
									$crm_invoice->save(false);
								}
							}
							echo '<br/>QBO Vendor Bill ID: '.$crm_invoice->qbo_bill_v_id_c;
	//-----------------------VENDOR BILL----------------END
	//-----------------------PRODUCER BILL--------------BEGIN--------------//
							echo '<br/>';
							echo '<br/>Producer Bill...';
							if(empty($crm_invoice->qbo_bill_p_id_c)){
								
								//$amount = 300;
								//$amount_description = '';//exp: 35% * ( amnt * 30% + 100 )
								$rate = 35;//Fill from Policy
								//if(!empty($crmPolicy->c_rate_c)){
								//	$rate = $crmPolicy->c_rate_c;	
								//}
								$rate = $crmPolicy->c_rate_c;	
								if( empty($rate) ){ $rate = 0;}								
								
								$oBill = new IPPBill();
								//$oBill->DueDate = $due_date;
								$oBill->TxnDate = $invoice_date;
								$oBill->DocNumber = $policy_no;
								$oBill->DepartmentRef = $cnf_bill_producer_dep_id; //"Producers"  (Location)
								$oBill->SalesTermRef = $cnf_bill_producer_term_id;//"Due on receipt" NOTE: check on moving to prod
								$oBill->VendorRef = $cnf_bill_producer_vendor_id;//"Producer Commissions Payable"   //Insurer ID
								//$oBill->GlobalTaxCalculation = "TaxExcluded";
								$oBill->PrivateNote = $qb_customer_name;//Ross
								//$oBill->CustomerMemo = $qb_customer_name;//Ross
								
								$line_arr = array();
								//echo '<br/>';
								foreach($crm_items as $i => $item){
									$_item_rate = (float) $item['rate'];
									if( $_item_rate > 0 ){
										//echo '<br/>Rate: '.$item['rate'].' | '.$rate;
										$oLine = new IPPLine();
										//echo '<br/>Amt: '.
										$oLine->Amount = ( ( ($item['amount'] * $item['rate']) / 100 ) * $rate) / 100;
										$oLine->Description = " {$rate}% * ( ";
										$oLine->Description .= number_format($item['amount'], 2, '.', ',');
										//echo '<br/>Desc: '.
										$oLine->Description .= " * {$_item_rate}%)";
										//TODO: remember about fee
										$oLine->DetailType = 'AccountBasedExpenseLineDetail';
											$oAccountBasedExpenseLineDetail = new IPPAccountBasedExpenseLineDetail();
											$oAccountBasedExpenseLineDetail->AccountRef =  $cnf_bill_producer_item_acc_id;// = 93;//5100 Producer Expense
											$oAccountBasedExpenseLineDetail->CustomerRef = $qb_customer_id;
											$oAccountBasedExpenseLineDetail->ClassRef  = $producer_class_key;
										$oLine->AccountBasedExpenseLineDetail = $oAccountBasedExpenseLineDetail ;		
										$line_arr[] = $oLine;
									}
								}
								//TODO: check if lineItems are not empty?
								$oBill->Line = $line_arr;//array($oLine1, $oLine2);
								$qbo_id = '';
								try{
									$resultingCustomerObj = $dataService->Add($oBill);//Ross
									$qbo_id = $resultingCustomerObj->Id;
								}catch (Exception $e){
									echo '<br/>Failed to add Producer Bill';
									echo 'Caught exception: ',  $e->getMessage(), "\n";
								}
								$crm_invoice->qbo_bill_p_id_c = $qbo_id;
								if( !empty($crm_invoice->qbo_bill_p_id_c) ){
									$crm_invoice->save(false);
								}
							}
							echo '<br/>QBO Producer Bill ID: '.$crm_invoice->qbo_bill_p_id_c;
	//-----------------------PRODUCER BILL---------END---------------//						
						}
	//BILLs - end
					}

				}else{
					echo 'Error: unable to retrieve Invoice data';
				}
			}else{
				echo 'Error: not enough parameters to accomplish request';
			}
		}else{
			echo 'You are not allowed to use QBO export function';
		}
 	}

}