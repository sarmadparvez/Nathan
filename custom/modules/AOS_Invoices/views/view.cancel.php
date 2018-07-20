<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/MVC/View/views/view.detail.php');
class Viewcancel extends ViewDetail{

	function Viewcancel(){
		parent::ViewDetail();
	}

    public function display(){
		global $db;

		//parent::display();

        echo '<br/>Invoice Cancel Action:<br/><br/>';

			if( isset($_REQUEST['record']) && !empty($_REQUEST['record']) ){

				$crm_invoice = BeanFactory::getBean('AOS_Invoices', $_REQUEST['record']);
				if( empty($crm_invoice->id) ){
					echo '<br/>Unable to retrieve Invoice data<br/>';
					exit;
				}
				if( !empty($crm_invoice->is_creditmemo_c) ){
					echo '<br/>CreditMemo is not supported for "Cancel" operation.<br/>';
					echo '<br/><a href="index.php?module=AOS_Invoices&action=DetailView&record='.$crm_invoice->id.'">Go back</a>';
					exit;
				}

				//qbo_creditmemo_id_c
				//TODO remove this
				/*
				if( !empty($crm_invoice->creditmemo_id_c) && !empty($crm_invoice->qbo_creditmemo_id_c) ){//if crmside and qbo IDs exists
					echo '<br/>CreditMemo is already exist for this Invoice: <a href="index.php?module=AOS_Invoices&action=DetailView&record='.$crm_invoice->creditmemo_id_c.'">Go to Credit Memo</a>';
					echo '<br/><a href="index.php?module=AOS_Invoices&action=DetailView&record='.$crm_invoice->id.'">Go back to Invoice</a>';
					exit;
				}
				*/
				$crm_creditmemo_id = '';

				$qb_customer_id = '';
				$qb_invoice_id = '';
				$producer_class_key = '';
				$invoice_no = '';
				$policy_no = '';
				$producer_name = '';
				$vendor_qbo_id = '';
				$qb_customer_name = '';

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

				$invoice_no = $crm_invoice->name;
				$invoice_date  = $crm_invoice->name;

				$crm_policy =  '';
				if( !empty($crm_invoice->aos_invoices_aos_contracts_1aos_contracts_idb) ){
					$crm_policy =  BeanFactory::getBean('AOS_Contracts', $crm_invoice->aos_invoices_aos_contracts_1aos_contracts_idb);
					$policy_no = $crm_policy->name;
					if( !empty($crm_policy->assigned_user_id) ){
						$crmProducer =  BeanFactory::getBean('Users', $crm_invoice->assigned_user_id);
						$producer_name = $crmProducer->first_name.' '.$crmProducer->last_name;
						$producer_class_key = $crmProducer->qbo_class_c;
					}
				}


				if( empty($crm_invoice->creditmemo_id_c) ){//CRM side CREDIT MEMO
					$crm_creditmemo = BeanFactory::getBean('AOS_Invoices');

					$rawRow = $crm_invoice->fetched_row;
					$rawRow['id'] = '';
					$rawRow['template_ddown_c'] = ' ';
					$rawRow['invoice_date'] = date('Y-m-d');
					$crm_creditmemo->populateFromRow($rawRow);
					$crm_creditmemo->status = '';
					$crm_creditmemo->aos_invoices_aos_contracts_1aos_contracts_idb = $crm_invoice->aos_invoices_aos_contracts_1aos_contracts_idb;//Policy ID
					//$crm_creditmemo->assigned_user_id = $crm_invoice->assigned_user_id;
					$crm_creditmemo->process_save_dates =false;
					$crm_creditmemo->name = $invoice_no.'CR';
					$crm_creditmemo->qbo_id_c = '';
					//Adjust totals
					$crm_creditmemo->products_amount_c *= -1;
					$crm_creditmemo->products_tax_c  *= -1;
					$crm_creditmemo->products_total_c  *= -1;
					$crm_creditmemo->charges_amount_c  *= -1;
					$crm_creditmemo->charges_tax_c  *= -1;
					$crm_creditmemo->charges_total_c  *= -1;
					$crm_creditmemo->total_amt  *= -1;
					$crm_creditmemo->tax_amount  *= -1;
					$crm_creditmemo->total_amount  *= -1;
					$crm_creditmemo->aos_invoices_id1_c = $crm_invoice->id;

					$crm_creditmemo->qbo_bill_p_id_c = '';
					$crm_creditmemo->qbo_bill_v_id_c = '';
					$crm_creditmemo->qbo_id_c = '';

					$crm_creditmemo->is_creditmemo_c = 1;
					$crm_creditmemo->save(false);

					if( !empty($crm_creditmemo->id) ){
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
							//$crm_policy = BeanFactory::getBean('AOS_Contracts', $crm_invoice->aos_invoices_aos_contracts_1aos_contracts_idb);
							$crm_policy->aos_invoices_aos_contracts_1aos_invoices_ida = '';//free to allow create new
							$crm_policy->status = 'Accepted';//not Invoiced
							$crm_policy->save(false);
							echo '<br/>Policy updated (to allow create new invoice).';
							echo '&nbsp;&nbsp;<a href="index.php?module=AOS_Contracts&action=DetailView&record='.$crm_policy->id.'">LNK</a>';
						}

						//$crm_invoice->creditmemo_id_c = $crm_creditmemo->id;
						$crm_invoice->aos_invoices_id_c = $crm_creditmemo->id;
						$crm_invoice->save(false);

						echo '<br/>Successfully created CRM CreditMemo<br/>';

					}else{
						echo '<br/>Failed to create CRM CreditMemo<br/>';
						exit;
					}
				}else{
					$crm_creditmemo = BeanFactory::getBean('AOS_Invoices', $crm_invoice->aos_invoices_id_c);
				}

				if( !is_object($crm_policy) ){
					if( !empty($crm_creditmemo->aos_invoices_aos_contracts_1aos_contracts_idb) ){
						$crm_policy =  BeanFactory::getBean('AOS_Contracts', $crm_creditmemo->aos_invoices_aos_contracts_1aos_contracts_idb);
						$policy_no = $crm_policy->name;
						if( !empty($crm_policy->assigned_user_id) ){
							$crmProducer =  BeanFactory::getBean('Users', $crm_invoice->assigned_user_id);
							$producer_name = $crmProducer->first_name.' '.$crmProducer->last_name;
							$producer_class_key = $crmProducer->qbo_class_c;
						}
					}
				}

				if( !empty($crm_creditmemo->id) ){
					echo '<br/><br/>CRM creditMemo: <a href="index.php?module=AOS_Invoices&action=DetailView&record='.$crm_creditmemo->id.'">LNK</a>';
				}

				global $current_user;

				//if($current_user->id !== '3c4e92c9-c147-2849-0c10-54f4b2c4a466'){ exit; }//Onlt IT support user

				if( empty($crm_invoice->qbo_id_c ) ){
					echo "<br/>CreditMemo and CreditVendor can't be exported to QBO coz original invioce doesn't have QBO id.";
					exit;
				}


				if( empty($producer_class_key) ){
					echo '<br/>Policy Producer Class is not defined!';
					exit;
				}

				if( !empty($crm_invoice->billing_account_id) ){
					$crm_account = BeanFactory::getBean('Accounts', $crm_invoice->billing_account_id);
					$qb_customer_id = $crm_account->qbo_id_c;
					$qb_customer_name = $crm_account->name;
				}
				if( empty($qb_customer_id) ){
					echo '<br/>QBO Customer ID is not defined!';
					exit;
				}
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
				$crm_creditmemo_id = $crm_invoice->creditmemo_id_c;

				$tbe_qbo = BeanFactory::getBean('tbe_qbo');
				if( !$tbe_qbo->isAllowedQBO() ){
					echo '<br/>You are not allowed to use QBO function<br/>';
					exit;
				}

					$tbe_qbo->retrieveSetting();
					require_once($tbe_qbo->sdk_path.'config.php');
					require_once(PATH_SDK_ROOT . 'Core/ServiceContext.php');
					require_once(PATH_SDK_ROOT . 'DataService/DataService.php');
					require_once(PATH_SDK_ROOT . 'PlatformService/PlatformService.php');
					if (empty($tbe_qbo->realmid)) exit("RealmID is not specified.\n");
					$requestValidator = new OAuthRequestValidator($tbe_qbo->access_token, $tbe_qbo->access_token_secret, $tbe_qbo->consumer_key, $tbe_qbo->consumer_secret);
					if (!$requestValidator) exit("Problem while initializing requestValidator.\n");
					$serviceContext = new ServiceContext($tbe_qbo->realmid, IntuitServicesType::QBO, $requestValidator);
					if (!$serviceContext) exit("Problem while initializing ServiceContext.\n");
					$dataService = new DataService($serviceContext);
					if (!$dataService) exit("Problem while initializing DataService.\n");

				//Get Line Items
					$crm_items = array();
					$sql = " SELECT c.name, p.qbo_id_c, c.product_unit_price, c.vat, cc.commission_rate_c, p.to_insurer_c, c.item_description FROM aos_products_quotes as c ";//
					$sql .= " LEFT JOIN aos_products_quotes_cstm as cc ON c.id = cc.id_c ";
					$sql .= " LEFT JOIN aos_products_cstm as p ON p.id_c = c.product_id ";
					$sql .= " WHERE c.parent_type = 'AOS_Invoices' AND c.parent_id = '".$crm_invoice->id."' AND c.deleted = 0;";
					$result = $crm_invoice->db->query($sql);
					while( $row = $crm_invoice->db->fetchByAssoc($result) ){
						$crm_items[] = array('rate' => $row['commission_rate_c'], 'qbo_id' => $row['qbo_id_c'] , 'amount' => $row['product_unit_price'] , 'name' => $row['name'] , 'vat' => $row['vat'] , 'to_insurer_c' => $row['to_insurer_c'] , 'item_description' => $row['item_description'] );
					}


				//End Line Items
					if($crm_policy->direct_commission_c){
						echo '<br/>DC<br/>';
						//DIRECT COMMISSION - CreditMemo- BEGIN
						if( empty($crm_creditmemo->qbo_creditmemo_id_c) ){
							$oCreditMemo = new IPPCreditMemo();
							$oCreditMemo->CustomerRef = $vendor_qbo_id;//vendor_customer_id;//Not customer just vendor
							//$oCreditMemo->TxnDate = $invoice_date;//TODO: Clarify!
							$oCreditMemo->DepartmentRef = $cnf_invoice_dc_dep_id;//General(Location)
							$oCreditMemo->DocNumber = $invoice_no.'CR';
							//$oCreditMemo->DocNumber = 'test'.date('his');
								$customField = new IPPCustomField();
								$customField->Type = 'StringType';
								$customField->DefinitionId = 1;
								$customField->StringValue = $policy_no;
							$oCreditMemo->CustomField[] = $customField;
								$customField = new IPPCustomField();
								$customField->Type = 'StringType';
								$customField->DefinitionId = 3;
								$customField->StringValue = $producer_name;
							$oCreditMemo->CustomField[] = $customField;
							$oCreditMemo->PrivateNote = $policy_no;
							$oCreditMemo->GlobalTaxCalculation = "TaxExcluded";//TODO: Clarify!
							$line_arr = array();
							foreach($crm_items as $i => $item){
								if( !empty($item['qbo_id']) ){
									$oLine = new IPPLine() ;
									$oLine->Amount = $item['amount'];
									$oLine->Description =  $item['item_description'];
									$oLine->DetailType = 'SalesItemLineDetail' ;
										$oSalesItemLineDetail = new IPPSalesItemLineDetail();
										$oSalesItemLineDetail->ItemRef = $item['qbo_id'];//$oItemRef = new IPPReferenceType();//$oItemRef->value = $item['qbo_id_c'];
										$oSalesItemLineDetail->ClassRef = $producer_class_key;
										$oSalesItemLineDetail->TaxCodeRef = 2;//"Id": "2", "Name": "Exempt",//TODO: add to conf
									$oLine->SalesItemLineDetail = $oSalesItemLineDetail;
									$line_arr[] = $oLine;
								}
							}
							$oCreditMemo->Line = $line_arr;
				//print_r($oCreditMemo);
				//exit;
							try{
								$resultingCustomerObj = $dataService->Add($oCreditMemo);
								$qb_creditmemo_id = $resultingCustomerObj->Id;
								if( !empty($qb_creditmemo_id) ){
									$crm_creditmemo->qbo_creditmemo_id_c = $qb_creditmemo_id;
									$crm_creditmemo->exists_qbo_c = 1;
									$crm_creditmemo->save(false);
								}

							} catch (Exception $e) {
								echo 'Error: Unable to add CreditMemo.';
								echo 'Caught exception: ',  $e->getMessage();
								exit;
							}
						}
						echo '<br/>QBO CreditMemo ID: '.$crm_creditmemo->qbo_creditmemo_id_c;

						//DIRECT COMMISSION - CreditMemo- END
						//DIRECT COMMISSION - vendorCredit (aka PRODUCER BILL) - BEGIN
							echo '<br/>';
							echo '<br/>Producer VendorCredit...';
							if(empty($crm_creditmemo->qbo_bill_p_id_c)){
								$rate = 35;//TODO: add to conf or make error msg
								//if(!empty($crm_policy->c_rate_c)){
								//	$rate = $crm_policy->c_rate_c;
								//}
								$rate = $crm_policy->c_rate_c;

								if( empty($rate) ){ $rate = 0;}

								$VendorCredit = new IPPVendorCredit();
								//$VendorCredit->TxnDate = $invoice_date;
								$VendorCredit->DocNumber = $policy_no.'CR';
								$VendorCredit->DepartmentRef = $cnf_bill_producer_dep_id; //"Producers"  (Location)
								$VendorCredit->VendorRef = $cnf_bill_producer_vendor_id;//"Producer Commissions Payable"   //Insurer ID
								$VendorCredit->PrivateNote = $policy_no;
								$line_arr = array();
								foreach ($crm_items as $i => $item) {
									$oLine = new IPPLine();
									$oLine->Amount = ( $item['amount']   * $rate) / 100;
									$oLine->Description = " {$rate}% * ";
									$oLine->Description .= number_format($item['amount'], 2, '.', ',');
									$oLine->DetailType = 'AccountBasedExpenseLineDetail';
										$oAccountBasedExpenseLineDetail = new IPPAccountBasedExpenseLineDetail();
										$oAccountBasedExpenseLineDetail->AccountRef =  $cnf_bill_producer_item_acc_id;// = 93;//5100 Producer Expense
										$oAccountBasedExpenseLineDetail->CustomerRef = $qb_customer_id;
										$oAccountBasedExpenseLineDetail->ClassRef  = $producer_class_key;
									$oLine->AccountBasedExpenseLineDetail = $oAccountBasedExpenseLineDetail ;
									$line_arr[] = $oLine;
								}
								$VendorCredit->Line = $line_arr;
								$qbo_id = '';
								try{
									$resultingCustomerObj = $dataService->Add($VendorCredit);
									$qbo_id = $resultingCustomerObj->Id;
								}catch (Exception $e){
									echo '<br/>Failed to add Producer VendorCredit';
									echo 'Caught exception: ',  $e->getMessage(), "\n";
								}
								$crm_creditmemo->qbo_bill_p_id_c = $qbo_id;
								if( !empty($crm_creditmemo->qbo_bill_p_id_c) ){
									$crm_creditmemo->save(false);
								}
							}
							echo '<br/>QBO Producer VendorCredit ID: '.$crm_creditmemo->qbo_bill_p_id_c;
						//DIRECT COMMISSION - vendorCredit (aka PRODUCER BILL) - END
					}else{
						//CreditMemo - BEGIN
						if( empty($crm_creditmemo->qbo_creditmemo_id_c) ){
							$line_arr = array();
							foreach($crm_items as $i => $item){
								if( !empty($item['qbo_id']) ){//qbo_id_c
									$oLine = new IPPLine() ;
									$oLine->Amount = $item['amount'];
									$oLine->Description =  $item['name'];//Free form text description of the line item that appears in the printed record
									$oLine->DetailType = 'SalesItemLineDetail';
										$oSalesItemLineDetail = new IPPSalesItemLineDetail();
										$oSalesItemLineDetail->ItemRef = $item['qbo_id'];//$oItemRef = new IPPReferenceType();$oItemRef->value = $item['qbo_id_c'];
										$oSalesItemLineDetail->ClassRef = $producer_class_key;
										$vat = (float) $item['vat'];
										if( $vat > 0 ){//TODO: possible other tax
											$oSalesItemLineDetail->TaxCodeRef = 6;//RST Ontario  TODO: add to config this
										}else{
											$oSalesItemLineDetail->TaxCodeRef = 2;//"Id": "2",           "Name": "Exempt",
										}
									$oLine->SalesItemLineDetail = $oSalesItemLineDetail;
									$line_arr[] = $oLine;
								}
							}
							$oCreditMemo = new IPPCreditMemo();
							//$oCreditMemo->TxnDate = $invoice_date;
							$oCreditMemo->DepartmentRef = $cnf_invoice_dep_id;//Trust (Location)
							$oCreditMemo->DocNumber = $invoice_no.'CR';
								$customField = new IPPCustomField();
								$customField->Type = 'StringType';
								$customField->DefinitionId = 1;
								$customField->StringValue = $policy_no;
							$oCreditMemo->CustomField[] = $customField;
								$customField = new IPPCustomField();
								$customField->Type = 'StringType';
								$customField->DefinitionId = 3;
								$customField->StringValue = $producer_name;
							$oCreditMemo->CustomField[] = $customField;
							$oCreditMemo->PrivateNote = $vendor_name;
							$oCreditMemo->CustomerRef = $qb_customer_id;
							$oCreditMemo->GlobalTaxCalculation = "TaxExcluded";
							$oCreditMemo->Line = $line_arr;


							try{
								$resultingCustomerObj = $dataService->Add($oCreditMemo);
								$qbo_id = $resultingCustomerObj->Id;
								if( !empty($qbo_id) ){
									$crm_creditmemo->qbo_creditmemo_id_c = $qbo_id;
									$crm_creditmemo->exists_qbo_c = 1;
									$crm_creditmemo->save(false);
								}
							} catch (Exception $e) {
								echo 'Error: Unable to add CreditMemo.';
								echo 'Caught exception: ',  $e->getMessage(), "\n";
							}
						}
						echo '<br/>QBO CreditMemo ID: '.$crm_creditmemo->qbo_creditmemo_id_c;
						//CreditMemo - END
						//VendorCredit - BEGIN

//-------------------------VENDOR -----------------BEGIN
							echo '<br/>';
							echo '<br/>Insurer VendorCredit...';
							if(empty($crm_creditmemo->qbo_bill_v_id_c)){
								$VendorCredit = new IPPVendorCredit();
								//$VendorCredit->TxnDate = $invoice_date;
								$VendorCredit->DocNumber = $policy_no.'CR';//.'_16';
								$VendorCredit->DepartmentRef = $cnf_bill_vendor_dep_id;
								$VendorCredit->VendorRef = $vendor_qbo_id;
								//$VendorCredit->GlobalTaxCalculation = "TaxExcluded";
								$VendorCredit->PrivateNote = $qb_customer_name;

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
											$oLine->Amount = $amount_commission * -1;
											$oLine->DetailType = 'AccountBasedExpenseLineDetail';
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
								$VendorCredit->Line = $line_arr;//array($oLine1, $oLine2) ;
								$qbo_id = '';
								try{
									$resultingCustomerObj = $dataService->Add($VendorCredit);
									$qbo_id = $resultingCustomerObj->Id;
								}catch (Exception $e){
									echo '<br/>Failed to add Insurer VendorCredit';
									echo 'Caught exception: ',  $e->getMessage(), "\n";
								}
								$crm_creditmemo->qbo_bill_v_id_c = $qbo_id;
								if(!empty($crm_creditmemo->qbo_bill_v_id_c)){
									$crm_creditmemo->save(false);
								}
							}
							echo '<br/>QBO Insurer VendorCredit ID: '.$crm_creditmemo->qbo_bill_v_id_c;
							//-----------------------VENDOR ----------------END
							//-----------------------PRODUCER --------------BEGIN--------------//
							echo '<br/>';
							echo '<br/>Producer Bill...';
							if(empty($crm_creditmemo->qbo_bill_p_id_c)){
								$rate = 35;//Fill from Policy
								//if(!empty($crm_policy->c_rate_c)){
								//	$rate = $crm_policy->c_rate_c;
								//}

								$rate = $crm_policy->c_rate_c;
								if( empty($rate) ){ $rate = 0;}

								$VendorCredit = new IPPVendorCredit();
								//$VendorCredit->TxnDate = $invoice_date;
								$VendorCredit->DocNumber = $policy_no;
								$VendorCredit->DepartmentRef = $cnf_bill_producer_dep_id; //"Producers"  (Location)
								$VendorCredit->VendorRef = $cnf_bill_producer_vendor_id;//"Producer Commissions Payable"   //Insurer ID
								$VendorCredit->PrivateNote = $qb_customer_name;

								$line_arr = array();
								
								foreach($crm_items as $i => $item){

									$_item_rate = (float) $item['rate'];
									if( $_item_rate > 0 ){
										$oLine = new IPPLine();
										$oLine->Amount = ( ( ($item['amount'] * $item['rate']) / 100 ) * $rate) / 100;
										$oLine->Description = " {$rate}% * ( ";
										$oLine->Description .= number_format($item['amount'], 2, '.', ',');
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
								$VendorCredit->Line = $line_arr;//array($oLine1, $oLine2);
								$qbo_id = '';
								try{
									$resultingCustomerObj = $dataService->Add($VendorCredit);//Ross
									$qbo_id = $resultingCustomerObj->Id;
								}catch (Exception $e){
									echo '<br/>Failed to add Producer VendorCredit';
									echo 'Caught exception: ',  $e->getMessage(), "\n";
								}
								$crm_creditmemo->qbo_bill_p_id_c = $qbo_id;
								if( !empty($crm_creditmemo->qbo_bill_p_id_c) ){
									$crm_creditmemo->save(false);
								}
							}
							echo '<br/>QBO Producer VendorCredit ID: '.$crm_creditmemo->qbo_bill_p_id_c;
	//-----------------------PRODUCER--------END---------------//

						//VendorCredit - END
					}


// */
			}else{
				echo 'Error: not enough parameters to accomplish request';
			}
	}
}
