<?php


if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

//#UPDATE aos_invoices_cstm SET qbo_id_c = '', qbo_bill_p_id_c = '', qbo_bill_v_id_c = '' WHERE id_c = '3557ec9f-0010-1b78-4acc-56afd20a5727';
//UPDATE aos_invoices_cstm as i SET i.qbo_id_c = '' , i.qbo_bill_v_id_c = '' , i.qbo_bill_p_id_c = '' , i.qbo_creditmemo_id_c = '' WHERE id_c = 'aa6b0c6d-4bf8-4037-2528-56b2726ad703';
require_once('include/MVC/View/views/view.detail.php');


class Viewqboexport extends ViewDetail
{

    function Viewqboexport()
    {
        parent::ViewDetail();
    }

    public function display()
    {

        global $timedate, $current_user;

        echo '<br/>Back to <a href="index.php?module=AOS_Invoices&action=DetailView&record=' . $_REQUEST['record'] . '">Invoice</a><br/>';

        //if($current_user->id !== '3c4e92c9-c147-2849-0c10-54f4b2c4a466'){//ITsupport
        //    echo '<b>Export Function is Temporarily Unavailable</b>';
        //    exit;
        //}





        $tbe_qbo = BeanFactory::getBean('tbe_qbo');

        if ($tbe_qbo->isAllowedQBO()) {

            $tbe_qbo->retrieveSetting();

            if (empty($tbe_qbo->access_token)) {
                echo '<br/>Seems you havent connected with QBO';
                exit;
            }


            if (isset($_REQUEST['record']) && !empty($_REQUEST['record'])) {
                //$qb_path = 'qb/v3-sdk-2.3.0/';
                //require_once($qb_path.'config.php');

                //echo $tbe_qbo->sdk_path;
                require_once($tbe_qbo->sdk_path . 'config.php');

                //require_once('custom/qb/v3-sdk-2.4.1/config.php');

                require_once(PATH_SDK_ROOT . 'Core/ServiceContext.php');
                require_once(PATH_SDK_ROOT . 'DataService/DataService.php');
                require_once(PATH_SDK_ROOT . 'PlatformService/PlatformService.php');
                //require_once(PATH_SDK_ROOT . 'Utility/Configuration/ConfigurationManager.php');
                if (empty($tbe_qbo->realmid))
                    exit("RealmID is not specified.\n");
                $requestValidator = new OAuthRequestValidator($tbe_qbo->access_token, $tbe_qbo->access_token_secret, $tbe_qbo->consumer_key, $tbe_qbo->consumer_secret);
                if (!$requestValidator)
                    exit("Problem while initializing requestValidator.\n");
                $serviceContext = new ServiceContext($tbe_qbo->realmid, IntuitServicesType::QBO, $requestValidator);
                if (!$serviceContext)
                    exit("Problem while initializing ServiceContext.\n");
                $dataService = new DataService($serviceContext);
                if (!$dataService)
                    exit("Problem while initializing DataService.\n");

                $qb_customer_id     = '';
                $qb_invoice_id      = '';
                $producer_class_key = '';
                $invoice_no         = '';
                $policy_no          = '';

                $producer_name = '';

                $vendor_qbo_id = '';

                $crm_invoice = BeanFactory::getBean('AOS_Invoices', $_REQUEST['record']);

                //TODO: add to Config
                $cnf_bill_producer_vendor_id            = 636; //"Producer Commissions Payable" (prod name: Producer Commissions Accrued)
                $cnf_bill_producer_term_id              = 1; //"Due on receipt"
                $cnf_bill_producer_dep_id               = 3; //"Office"(Location)//OK
                $cnf_invoice_term_id                    = 1; //"Due on receipt"
                $cnf_invoice_dep_id                     = 1; //Trust (Location) //OK
                $cnf_invoice_dc_dep_id                  = 3; //(DirectCommision)General',//OK
                $cnf_bill_producer_item_acc_id          = 19; //5100 Producer Expense//OK
                $cnf_bill_vendor_item_acc_commission_id = 1; //4050 "Commission Income"//OK
                $cnf_bill_vendor_item_acc_id            = 12; //2310 Trust
                $cnf_bill_vendor_term_id                = 1;
                $cnf_bill_vendor_dep_id                 = 1; //Trust (Location)

                /* AI TaxCode
                "Id": "2",           "Name": "Exempt",
                "Id": "5",          "Name": "HST ON",
                "Id": "7",          "Name": "Out of Scope",
                "Id": "6",          "Name": "RST Ontario",
                "Id": "3",          "Name": "Zero-rated",
                */


                if (!empty($crm_invoice->id)) {

                    if (!empty($crm_invoice->is_creditmemo_c)) {
                        echo '<br/>CreditMemo is not supported for "Export" operation.<br/>';
                        echo '<br/><a href="index.php?module=AOS_Invoices&action=DetailView&record=' . $crm_invoice->id . '">Go back</a>';
                        exit;
                    }

                    $tbe_qbo->name             = 'Exporting Invoice ' . $crm_invoice->name;
                    $tbe_qbo->parent_id        = $crm_invoice->id;
                    $tbe_qbo->parent_type      = 'AOS_Invoices';
                    $tbe_qbo->assigned_user_id = $current_user->id;
                    //$tbe_qbo->error_msg = '';

                    $crmPolicy = '';
                    if (!empty($crm_invoice->aos_invoices_aos_contracts_1aos_contracts_idb)) {
                        $crmPolicy = BeanFactory::getBean('AOS_Contracts', $crm_invoice->aos_invoices_aos_contracts_1aos_contracts_idb);
                        $policy_no = $crmPolicy->name;
                        if (!empty($crmPolicy->assigned_user_id)) {
                            $crmProducer        = BeanFactory::getBean('Users', $crm_invoice->assigned_user_id);
                            $producer_name      = $crmProducer->first_name . ' ' . $crmProducer->last_name;
                            $producer_class     = $crmProducer->first_name . ' ' . $crmProducer->last_name;
                            $producer_class_key = $crmProducer->qbo_class_c;
                            $producer_class_qb  = $crmProducer->first_name;
                        }
                    }
                    if (empty($producer_class_key)) {
                        echo '<br/>Policy Producer Class is not defined!<br/>';
                        $tbe_qbo->error_msg = 'Policy Producer Class is not defined!';
                        $tbe_qbo->save(false);
                        exit;
                    }

                    $invoice_no = $crm_invoice->name;

                    if ($crmPolicy->direct_commission_c) {
                        //-----DIRECT COMMISSION-------BEGIN
                        $vendor_name        = '';
                        $vendor_customer_id = '';
                        if (!empty($crm_invoice->insrr_insurers_id_c)) {
                            $crmInsurer= BeanFactory::getBean('insrr_Insurers', $crm_invoice->insrr_insurers_id_c);
                            $vendor_qbo_id      = $crmInsurer->qbo_id_c;
                            $vendor_name        = $crmInsurer->name;
                            $vendor_customer_id = $crmInsurer->qbo_customer_id_c; //qb_customer_id
                        }

                        if (empty($vendor_qbo_id)) {
                            echo '<br/>';
                            echo "Insurer/Vendor doesnt have QBO id, please update it to be able export bills.";
                            echo '<br/>';
                            $tbe_qbo->error_msg = 'Insurer/Vendor doesnt have QBO id, please update it to be able export bills.';
                            $tbe_qbo->save(false);
                            exit;
                        }
                        if (empty($vendor_customer_id)) {
                            echo '<br/>';
                            echo "Insurer/Vendor doesnt have QBO Customer ID, please update it to be able export invoice.";
                            echo '<br/>';
                            $tbe_qbo->error_msg = 'Insurer/Vendor doesnt have QBO Customer ID, please update it to be able export invoice.';
                            $tbe_qbo->save(false);
                            exit;
                        }
                        $qb_customer_id   = '';
                        $qb_customer_name = '';
                        if (!empty($crm_invoice->billing_account_id)) {
                            $crm_account      = BeanFactory::getBean('Accounts', $crm_invoice->billing_account_id);
                            $qb_customer_name = $crm_account->name;
                            $qb_customer_id   = $crm_account->qbo_id_c;

                            if (empty($qb_customer_id)) {
                                $customerObj       = new IPPCustomer();
                                $customerObj->Name = $crm_account->name;

                                $customerObj->Notes = $crm_account->account_code_c;

                                $customerObj->CompanyName         = $crm_account->name;
                                //$customerObj->GivenName = $crm_account->name;
                                $customerObj->DisplayName         = $crm_account->name;
                                $BillAddr                         = new IPPPhysicalAddress();
                                $BillAddr->Line1                  = $crm_account->billing_address_street;
                                //$BillAddr->Line2 = 'Suite D';    //$crm_account->billing_address_state
                                $BillAddr->CountrySubDivisionCode = $crm_account->billing_address_state; //ROSS
                                $BillAddr->City                   = $crm_account->billing_address_city;
                                $BillAddr->PostalCode             = $crm_account->billing_address_postalcode;
                                //$BillAddr->Country = $crm_account->; // Country code per ISO 3166
                                //$BillAddr->CountryCode = $crm_account->; //State for US, Province for Canada
                                $customerObj->BillAddr            = $BillAddr;
                                try {
                                    $resultingCustomerObj = $dataService->Add($customerObj);

                                    if (is_array($resultingCustomerObj) && isset($resultingCustomerObj['error'])) {

                                        $tbe_qbo->name .= ' [add]';
                                        $tbe_qbo->description = 'ErrorMSG:' . strip_tags($resultingCustomerObj['error_msg']);
                                        $tbe_qbo->save(false);
                                        echo '<br/>Error: ' . $tbe_qbo->description . '<br/>';
                                        exit;

                                    }

                                }
                                catch (Exception $e) {
                                    echo '<br/>Error: Unable to add customer, probably he already exist.';
                                    echo '<br/> ' . $e->getMessage() . "<br/>";

                                    $tbe_qbo->name .= ' [add]E';
                                    $tbe_qbo->error_msg = $e->getMessage();
                                    $tbe_qbo->save(false);
                                    exit;

                                }
                                if (!empty($resultingCustomerObj)) {
                                    $qb_customer_id        = $resultingCustomerObj->Id;
                                    //echo '<br/>QBO Customer added: <a href="https://sandbox.qbo.intuit.com/app/customerdetail?nameId='.$qb_customer_id.'">'.$qb_customer_id.'</a>';
                                    //https://sandbox.qbo.intuit.com/app/customerdetail?nameId=
                                    $crm_account->qbo_id_c = $qb_customer_id;
                                    $crm_account->save(false);
                                }
                            }
                        }

                        if (empty($qb_customer_id)) {
                            echo '<br/>Error not specified Customer ID ';
                            $tbe_qbo->error_msg = 'Error not specified Customer ID';
                            $tbe_qbo->save(false);
                            exit;
                        }

                        $no_ids    = array();
                        //-----DIRECT COMMISSION-------LINE-ITEMS-RETRIEVE--------BEGIN
                        $line_arr  = array();
                        $crm_items = array();
                        $sql       = " SELECT c.name,  p.qbo_id_c, c.product_unit_price, c.vat, cc.commission_rate_c, p.to_insurer_c, c.item_description FROM aos_products_quotes as c "; //
                        $sql .= " LEFT JOIN aos_products_quotes_cstm as cc ON c.id = cc.id_c ";
                        $sql .= " LEFT JOIN aos_products_cstm as p ON p.id_c = c.product_id ";
                        $sql .= " WHERE c.parent_type = 'AOS_Invoices' AND c.parent_id = '" . $crm_invoice->id . "' AND c.deleted = 0;";
                        $result = $crm_invoice->db->query($sql);

                        while ($row = $crm_invoice->db->fetchByAssoc($result)) {

                            $crm_items[] = array(
                                'rate' => $row['commission_rate_c'],
                                'qbo_id' => $row['qbo_id_c'],
                                'amount' => $row['product_unit_price'],
                                'name' => $row['name'],
                                'description' => $row['item_description'],
                                'to_insurer_c' => $row['to_insurer_c']
                            );


                            if (!empty($row['qbo_id_c'])){
                                $oLine                            = new IPPLine();
                                $oLine->Amount                    = $row['product_unit_price'];
                                $oLine->Description               = $row['item_description']; //   Free form text description of the line item that appears in the printed record
                                $oLine->DetailType                = 'SalesItemLineDetail';
                                $oSalesItemLineDetail             = new IPPSalesItemLineDetail();

                                $oSalesItemLineDetail->ItemRef    = $row['qbo_id_c'];
                                $oSalesItemLineDetail->ClassRef   = $producer_class_key;
                                $oSalesItemLineDetail->TaxCodeRef = 2; //"Id": "2", "Name": "Exempt",//TODO: add to conf
                                $oLine->SalesItemLineDetail       = $oSalesItemLineDetail;
                                $line_arr[]                       = $oLine;
                            } else {
                                $no_ids[] = array(
                                    'id' => $row['id'],
                                    'name' => $row['name']
                                );
                            }


                        }
                        //-----DIRECT COMMISSION-----LINE-ITEMS-RETRIEVE--------END
                        if (!empty($no_ids)) {
                            echo '<br/>There are some products that have to be filled with QBO ID:';
                            foreach ($no_ids as $item) {
                                echo '<br/><a href="index.php?module=AOS_Products&action=DetailView&record=' . $item['id'] . '">' . $item['name'] . '</a>';
                            }
                            echo '<br/>';
                            $tbe_qbo->error_msg = 'There are some products that have to be filled with QBO ID'; //TODO: add list
                            $tbe_qbo->save(false);
                            exit;
                        }

                        if ($oLine->Amount > 0) {
                          //-----DIRECT COMMISSION------INVOICE----BEGIN
                          $invoice_date = $timedate->to_db_date($crm_invoice->invoice_date);
                          $due_date     = $timedate->to_db_date($crm_invoice->due_date);

                          $qb_inv_id = $crm_invoice->qbo_id_c;
                          if (empty($crm_invoice->qbo_id_c)) {
                              $oInvoice                = new IPPInvoice();
                              $oInvoice->SalesTermRef  = $cnf_invoice_term_id;
                              $oInvoice->DueDate       = $due_date;
                              $oInvoice->TxnDate       = $invoice_date;
                              $oInvoice->DepartmentRef = $cnf_invoice_dc_dep_id; //General(Location)
                              $oInvoice->DocNumber     = $invoice_no;
                              $oInvoice->ClassRef    = $producer_class_key;

                              $customField               = new IPPCustomField();
                              $customField->Type         = 'StringType';
                              $customField->DefinitionId = 1;
                              $customField->StringValue  = $policy_no;
                              $oInvoice->CustomField[]   = $customField;

                              $customField               = new IPPCustomField();
                              $customField->Type         = 'StringType';
                              $customField->DefinitionId = 3;
                              $customField->StringValue  = $producer_name;
                              $oInvoice->CustomField[]   = $customField;

                              //$oInvoice->CustomerMemo = $vendor_name;//Ross
                              //$oInvoice->PrivateNote = $vendor_name;//Ross
                              $oInvoice->PrivateNote          = $qb_customer_name; //Ross
                              //$oInvoice->PrivateNote = 'PrivateNote';
                              $oInvoice->CustomerRef          = $vendor_customer_id;
                              $oInvoice->GlobalTaxCalculation = "TaxExcluded"; //TODO:Clarify
                              $oInvoice->Line                 = $line_arr;


                              try {

                                  $resultingCustomerObj = $dataService->Add($oInvoice);

                                  if (is_array($resultingCustomerObj) && isset($resultingCustomerObj['error'])) {
                                      //echo '<br/>RossErrorMSG(begin):'; print_r($resultingCustomerObj); echo '<br/>RossErrorMSG(end): ';
                                      $tbe_qbo->name .= ' [addInvoice]';
                                      $tbe_qbo->description = strip_tags($resultingCustomerObj['error_msg']);
                                      $tbe_qbo->save(false);
                                      echo '<br/>Error: ' . $tbe_qbo->description . '<br/>';
                                      exit;
                                  }


                                  $qb_inv_id = $resultingCustomerObj->Id;
                                  if (!empty($qb_inv_id)) {
                                      $crm_invoice->qbo_id_c     = $qb_inv_id;
                                      $crm_invoice->exists_qbo_c = 1;
                                      $crm_invoice->save(false);
                                  }
                              }
                              catch (Exception $e) {
                                  echo 'Error: Unable to add invoice.';
                                  //echo '  ',  $e->getMessage(), "\n";

                                  $tbe_qbo->name .= ' [addInvoice]E';
                                  $tbe_qbo->error_msg = $e->getMessage();
                                  $tbe_qbo->save(false);
                                  exit;
                              }

                          }
                          echo '<br/>QBO Invoice ID: ' . $crm_invoice->qbo_id_c;
                          //-----DIRECT COMMISSION------INVOICE----END
                          //-----DIRECT COMMISSION------PRODUCER BILL--------------BEGIN--------------//
                          echo '<br/>';
                          echo '<br/>Producer Bill...';
                          if (empty($crm_invoice->qbo_bill_p_id_c)) {
                              $rate = 35; //TODO: add to conf or make error msg

                              $rate = $crmPolicy->c_rate_c;
                              if (empty($rate)) {
                                  $rate = 0;
                              }

                              $oBill                = new IPPBill();
                              //$oBill->DueDate = $due_date;
                              $oBill->TxnDate       = $invoice_date;
                              $oBill->DocNumber     = $policy_no;
                              $oBill->DepartmentRef = $cnf_bill_producer_dep_id; //"Producers"  (Location)
                              $oBill->SalesTermRef  = $cnf_bill_producer_term_id; //"Due on receipt" NOTE: check on moving to prod
                              $oBill->VendorRef     = $cnf_bill_producer_vendor_id; //"Producer Commissions Payable"   //Insurer ID
                              //$oBill->GlobalTaxCalculation = "TaxExcluded";
                              $oBill->PrivateNote   = $qb_customer_name; //$qb_customer_name;//Ross
                              $oBill->CustomerMemo = $qb_customer_name;//Ross
                              $line_arr             = array();
                              foreach ($crm_items as $i => $item) {
                                  $oLine              = new IPPLine();

                                  $oLine->Amount      = (abs($item['amount']) * $rate) / 100;
                                  $oLine->Description = " {$rate}% * ";
                                  $oLine->Description .= number_format($item['amount'], 2, '.', ',');
                                  $oLine->DetailType                           = 'AccountBasedExpenseLineDetail';
                                  $oAccountBasedExpenseLineDetail              = new IPPAccountBasedExpenseLineDetail();
                                  $oAccountBasedExpenseLineDetail->AccountRef  = $cnf_bill_producer_item_acc_id; // = 93;//5100 Producer Expense
                                  $oAccountBasedExpenseLineDetail->CustomerRef = $qb_customer_id;
                                  $oAccountBasedExpenseLineDetail->ClassRef    = $producer_class_key;
                                  $oLine->AccountBasedExpenseLineDetail        = $oAccountBasedExpenseLineDetail;
                                  $line_arr[]                                  = $oLine;

                              }
                              $oBill->Line = $line_arr;

                              $qbo_id      = '';
                              try {
                                  $resultingCustomerObj = $dataService->Add($oBill); //Ross
                                  if (is_array($resultingCustomerObj) && isset($resultingCustomerObj['error'])) {

                                      $tbe_qbo->name .= ' [addProducerBill]';
                                      $tbe_qbo->description = strip_tags($resultingCustomerObj['error_msg']);
                                      $tbe_qbo->save(false);
                                      echo '<br/>Error: ' . $tbe_qbo->description . '<br/>';
                                      exit;
                                  }

                                  $qbo_id = $resultingCustomerObj->Id;
                              }
                              catch (Exception $e) {
                                  echo '<br/>Failed to add Producer Bill';
                                  echo '  ', $e->getMessage(), "\n";
                                  $tbe_qbo->name .= ' [addProducerBill]E';
                                  $tbe_qbo->error_msg = $e->getMessage();
                                  $tbe_qbo->save(false);
                                  exit;
                              }
                              $crm_invoice->qbo_bill_p_id_c = $qbo_id;
                              if (!empty($crm_invoice->qbo_bill_p_id_c)) {
                                  $crm_invoice->save(false);
                              }
                          }
                          echo '<br/>QBO Producer Bill ID: ' . $crm_invoice->qbo_bill_p_id_c;
                          //-----DIRECT COMMISSION----------PRODUCER BILL---------END---------------//
                          //-----DIRECT COMMISSION-------END
                        }
                        else {
                          echo '<br/>Direct Commission for Negative amount<br/>';
                          //DIRECT COMMISSION - CreditMemo- BEGIN

                          $invoice_date = $timedate->to_db_date($crm_invoice->invoice_date);
                          if( empty($crm_creditmemo->qbo_creditmemo_id_c) ){
                            $oCreditMemo = new IPPCreditMemo();
                            $oCreditMemo->CustomerRef = $vendor_customer_id;//vendor_customer_id;//Not customer just vendor
                            $oCreditMemo->TxnDate = $invoice_date;//TODO: Clarify!
                            $oCreditMemo->DepartmentRef = $cnf_invoice_dc_dep_id;//General(Location)
                            $oCreditMemo->DocNumber = $invoice_no.'CR';
                            $oCreditMemo->ClassRef  = $producer_class_key;
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
                            $oCreditMemo->PrivateNote = $qb_customer_name;
                            $oCreditMemo->GlobalTaxCalculation = "TaxExcluded";//TODO: Clarify!
                            $line_arr = array();

                            foreach($crm_items as $i => $item){
                              if( !empty($item['qbo_id']) ){

                                $oLine = new IPPLine() ;
                                $oLine->Amount = abs($item['amount']);
                                $oLine->Description =  $item['description'];
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
                            try{
                              $resultingCustomerObj = $dataService->Add($oCreditMemo);
                              $qb_creditmemo_id = $resultingCustomerObj->Id;
                              if( !empty($qb_creditmemo_id) ){
                                $crm_invoice->qbo_creditmemo_id_c = $qb_creditmemo_id;
                                $crm_invoice->exists_qbo_c = 1;
                                $crm_invoice->save(false);
                              }

                            } catch (Exception $e) {
                              echo 'Error: Unable to add CreditMemo.';
                              echo 'Caught exception: ',  $e->getMessage();
                              exit;
                            }
                          }
                          echo '<br/>QBO CreditMemo ID: '.$crm_invoice->qbo_creditmemo_id_c;

                          //DIRECT COMMISSION - CreditMemo- END
                          //DIRECT COMMISSION - vendorCredit (aka PRODUCER BILL) - BEGIN
                            echo '<br/>';
                            echo '<br/>Producer VendorCredit...';
                            if(empty($crm_invoice->qbo_bill_p_id_c)){
                              $rate = 35;//TODO: add to conf or make error msg
                              //if(!empty($crm_policy->c_rate_c)){
                              //	$rate = $crm_policy->c_rate_c;
                              //}
                              $rate = $crmPolicy->c_rate_c;
                              if( empty($rate) ){ $rate = 0;}

                              $VendorCredit = new IPPVendorCredit();
                              $VendorCredit->TxnDate = $invoice_date;
                              $VendorCredit->DocNumber = $policy_no.'CR';
                              $VendorCredit->DepartmentRef = $cnf_bill_producer_dep_id; //"Producers"  (Location)
                              $VendorCredit->VendorRef = $cnf_bill_producer_vendor_id;//"Producer Commissions Payable"   //Insurer ID
                              $VendorCredit->PrivateNote = $qb_customer_name;
                              $line_arr = array();

                              foreach($crm_items as $i => $item){
                                $_item_rate = (float) $item['rate'];
                                $oLine = new IPPLine();
                                $oLine->Amount = ( abs($item['amount'])   * $rate) / 100;
                                $oLine->Description = " {$rate}% * ( ";
                                $oLine->Description .= number_format($item['amount'], 2, '.', ',');
                                $oLine->Description .= " * {$_item_rate}%)";
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
                              $crm_invoice->qbo_bill_p_id_c = $qbo_id;
                              if( !empty($crm_invoice->qbo_bill_p_id_c) ){
                                $crm_invoice->save(false);
                              }
                            }
                            echo '<br/>QBO Producer VendorCredit ID: '.$crm_invoice->qbo_bill_p_id_c;
                          //DIRECT COMMISSION - vendorCredit (aka PRODUCER BILL) - END
                        }


                    } else {
                        //---Customer-----BEGIN
                        $qb_customer_id   = '';
                        $qb_customer_name = '';
                        if (!empty($crm_invoice->billing_account_id)) {
                            $crm_account      = BeanFactory::getBean('Accounts', $crm_invoice->billing_account_id);
                            $qb_customer_name = $crm_account->name;
                            $qb_customer_id   = $crm_account->qbo_id_c;
                            if (!empty($crm_account->id)) {
                                if (empty($qb_customer_id)) {
                                    $customerObj       = new IPPCustomer();
                                    $customerObj->Name = $crm_account->name;

                                    $customerObj->Notes = $crm_account->account_code_c;

                                    $customerObj->CompanyName         = $crm_account->name;
                                    //$customerObj->GivenName = $crm_account->name;
                                    $customerObj->DisplayName         = $crm_account->name;
                                    $BillAddr                         = new IPPPhysicalAddress();
                                    $BillAddr->Line1                  = $crm_account->billing_address_street;
                                    //$BillAddr->Line2 = 'Suite D';    //$crm_account->billing_address_state
                                    $BillAddr->CountrySubDivisionCode = $crm_account->billing_address_state; //ROSS
                                    $BillAddr->City                   = $crm_account->billing_address_city;
                                    $BillAddr->PostalCode             = $crm_account->billing_address_postalcode;
                                    //$BillAddr->Country = $crm_account->; // Country code per ISO 3166
                                    //$BillAddr->CountryCode = $crm_account->; //State for US, Province for Canada
                                    $customerObj->BillAddr            = $BillAddr;
                                    try {
                                        $resultingCustomerObj = $dataService->Add($customerObj); //Ross

                                        echo '<pre>';
                                        print_r($resultingCustomerObj);
                                        echo '</pre>';

                                        if (is_array($resultingCustomerObj) && isset($resultingCustomerObj['error'])) {
                                            $tbe_qbo->name .= ' [addCustomer]';
                                            $tbe_qbo->description = strip_tags($resultingCustomerObj['error_msg']);
                                            $tbe_qbo->save(false);
                                            echo '<br/>Error: ' . $tbe_qbo->description . '<br/>';
                                            exit;
                                        }
                                    }
                                    catch (Exception $e) {
                                        echo '<br/>Error: Unable to add customer.<br/>';
                                        echo '<br/>' . $e->getMessage() . "<br/>";

                                        $tbe_qbo->name .= ' [addCustomer]E';

                                        $tbe_qbo->error_msg = strip_tags($e->getMessage());
                                        $tbe_qbo->save(false);
                                        exit;
                                    }
                                    if (!empty($resultingCustomerObj)) {
                                        $qb_customer_id        = $resultingCustomerObj->Id;

                                        $crm_account->qbo_id_c = $qb_customer_id;
                                        $crm_account->save(false);
                                    }
                                }
                            } else {
                                echo 'Error Unable to retrieve Customer data';
                            }
                        } else {
                            echo 'Error not specified Customer ID ';
                        }

                        echo '<br/>QBO Customer ID: ' . $qb_customer_id;
                        //---Customer-----END
                        $vendor_name = '';
                        if (!empty($crm_invoice->insrr_insurers_id_c)) {
                            $crmInsurer    = BeanFactory::getBean('insrr_Insurers', $crm_invoice->insrr_insurers_id_c); //echo '<br/>Insurer-QBOid: '.$crmInsurer->qbo_id_c;
                            $vendor_qbo_id = $crmInsurer->qbo_id_c;
                            $vendor_name   = $crmInsurer->name;
                        }
                        if (empty($vendor_qbo_id)) {
                            echo '<br/>';
                            echo "Insurer/Vendor doesnt have QBO id, please update it to be able export bills.";
                            echo '<br/>';

                            $tbe_qbo->error_msg = 'Insurer/Vendor doesnt have QBO id, please update it to be able export bills.';
                            $tbe_qbo->save(false);
                            exit;
                        }


                        $no_ids    = array();
                        //--LINE-ITEMS-RETRIEVE--------BEGIN
                        $line_arr  = array();
                        $crm_items = array();
                        $sql       = " SELECT c.name, p.qbo_id_c, c.product_unit_price, c.vat, cc.commission_rate_c, p.to_insurer_c, c.item_description FROM aos_products_quotes as c ";
                        $sql .= " LEFT JOIN aos_products_quotes_cstm as cc ON c.id = cc.id_c ";
                        $sql .= " LEFT JOIN aos_products_cstm as p ON p.id_c = c.product_id ";
                        $sql .= " WHERE c.parent_type = 'AOS_Invoices' AND c.parent_id = '" . $crm_invoice->id . "' AND c.deleted = 0;";
                        $result = $crm_invoice->db->query($sql);
                        while ($row = $crm_invoice->db->fetchByAssoc($result)) {
                            $crm_items[] = array(
                                'rate' => $row['commission_rate_c'],
                                'qbo_id' => $row['qbo_id_c'],
                                'amount' => $row['product_unit_price'],
                                'name' => $row['item_description'],
                                'to_insurer_c' => $row['to_insurer_c']
                            );
                            if (!empty($row['qbo_id_c'])) {
                                $oLine                          = new IPPLine();
                                $oLine->Amount                  = $row['product_unit_price'];
                                $oLine->Description             = $row['item_description']; //Free form text description of the line item that appears in the printed record
                                $oLine->DetailType              = 'SalesItemLineDetail';
                                $oSalesItemLineDetail           = new IPPSalesItemLineDetail();
                                $oItemRef                       = new IPPReferenceType();
                                $oItemRef->value                = $row['qbo_id_c'];
                                $oSalesItemLineDetail->ItemRef  = $oItemRef;
                                $oSalesItemLineDetail->ClassRef = $producer_class_key;
                                $vat                            = (float) $row['vat'];
                                if ($vat > 0) { //TODO: possible other tax
                                    $oSalesItemLineDetail->TaxCodeRef = 6; //RST Ontario  TODO: add to config this
                                } else {
                                    $oSalesItemLineDetail->TaxCodeRef = 2; //"Id": "2",           "Name": "Exempt",
                                }
                                $oLine->SalesItemLineDetail = $oSalesItemLineDetail;
                                $line_arr[]                 = $oLine;
                            } else {
                                $no_ids[] = array(
                                    'id' => $row['id'],
                                    'name' => $row['name']
                                );
                            }
                        }

                        if (!empty($no_ids)) {
                            echo '<br/>There are some products that have to be filled with QBO ID:';
                            foreach ($no_ids as $item) {
                                echo '<br/><a href="index.php?module=AOS_Products&action=DetailView&record=' . $item['id'] . '">' . $item['name'] . '</a>';
                            }
                            echo '<br/>';
                            $tbe_qbo->error_msg = 'There are some products that have to be filled with QBO ID';
                            $tbe_qbo->save(false);
                            exit;
                        }
                        if (empty($qb_customer_id)) {
                            echo '<br/>Error: NO CUSTOMER ID!<br/>';
                            $tbe_qbo->error_msg = 'NO CUSTOMER ID!';
                            $tbe_qbo->save(false);
                            exit;
                        }



                        $invoice_date = $timedate->to_db_date($crm_invoice->invoice_date);
                        $due_date     = $timedate->to_db_date($crm_invoice->due_date);

                        $qb_inv_id = $crm_invoice->qbo_id_c;
                        if (empty($crm_invoice->qbo_id_c)) {
                            $oInvoice                = new IPPInvoice();
                            $oInvoice->SalesTermRef  = $cnf_invoice_term_id; //"Due on receipt"

                            $oInvoice->DueDate       = $due_date;
                            $oInvoice->TxnDate       = $invoice_date;
                            $oInvoice->DepartmentRef = $cnf_invoice_dep_id; //Trust (Location)
                            $oInvoice->DocNumber     = $invoice_no;

                            $customField               = new IPPCustomField();
                            $customField->Type         = 'StringType';
                            $customField->DefinitionId = 1;
                            $customField->StringValue  = $policy_no;
                            $oInvoice->CustomField[]   = $customField;

                            $customField               = new IPPCustomField();
                            $customField->Type         = 'StringType';
                            $customField->DefinitionId = 3;
                            $customField->StringValue  = $producer_class;
                            $oInvoice->CustomField[]   = $customField;

                            //$oInvoice->CustomerMemo = $vendor_name;//Ross
                            $oInvoice->PrivateNote          = $vendor_name; //Ross
                            //$oInvoice->PrivateNote = 'PrivateNote';
                            $oInvoice->CustomerRef          = $qb_customer_id;
                            $oInvoice->ClassRef    = $producer_class_key;
                            $oInvoice->GlobalTaxCalculation = "TaxExcluded";
                            $oInvoice->Line                 = $line_arr;

                            $amount = $line_arr[0]->Amount;
                            if ($amount < 0) {
                                $amount              = abs($amount);
                                $line_arr[0]->Amount = $amount;
                                $oInvoice->Line      = $line_arr;

                                /** Code for Negative invoice taken from cancel button **/
                                global $db;

                                echo '<br/>Negative Invoice Action:<br/><br/>';

                                if (isset($_REQUEST['record']) && !empty($_REQUEST['record'])) {

                                    $crm_invoice = BeanFactory::getBean('AOS_Invoices', $_REQUEST['record']);
                                    if (empty($crm_invoice->id)) {
                                        echo '<br/>Unable to retrieve Invoice data<br/>';
                                        exit;
                                    }

                                    $crm_creditmemo_id = '';

                                    $qb_customer_id     = '';
                                    $qb_invoice_id      = '';
                                    $producer_class_key = '';
                                    $invoice_no         = '';
                                    $policy_no          = '';
                                    $producer_name      = '';
                                    $vendor_qbo_id      = '';
                                    $qb_customer_name   = '';

                                    //TODO: add to Config
                                    $cnf_bill_producer_vendor_id            = 636; //"Producer Commissions Payable" (prod name: Producer Commissions Accrued)
                                    $cnf_bill_producer_term_id              = 1; //"Due on receipt"
                                    $cnf_bill_producer_dep_id               = 3; //"Office"(Location)//OK
                                    $cnf_invoice_term_id                    = 1; //"Due on receipt"
                                    $cnf_invoice_dep_id                     = 1; //Trust (Location) //OK
                                    $cnf_invoice_dc_dep_id                  = 3; //(DirectCommision)General',//OK
                                    $cnf_bill_producer_item_acc_id          = 19; //5100 Producer Expense//OK
                                    $cnf_bill_vendor_item_acc_commission_id = 1; //4050 "Commission Income"//OK
                                    $cnf_bill_vendor_item_acc_id            = 12; //2310 Trust
                                    $cnf_bill_vendor_term_id                = 1;
                                    $cnf_bill_vendor_dep_id                 = 1; //Trust (Location)

                                    $invoice_no   = $crm_invoice->name;
                                    $invoice_date = $crm_invoice->name;

                                    $crm_policy = '';
                                    if (!empty($crm_invoice->aos_invoices_aos_contracts_1aos_contracts_idb)) {
                                        $crm_policy = BeanFactory::getBean('AOS_Contracts', $crm_invoice->aos_invoices_aos_contracts_1aos_contracts_idb);
                                        $policy_no  = $crm_policy->name;
                                        if (!empty($crm_policy->assigned_user_id)) {
                                            $crmProducer        = BeanFactory::getBean('Users', $crm_invoice->assigned_user_id);
                                            $producer_name      = $crmProducer->first_name . ' ' . $crmProducer->last_name;
                                            $producer_class_key = $crmProducer->qbo_class_c;
                                        }
                                    }


                                    if (!is_object($crm_policy)) {
                                        if (!empty($crm_creditmemo->aos_invoices_aos_contracts_1aos_contracts_idb)) {
                                            $crm_policy = BeanFactory::getBean('AOS_Contracts', $crm_creditmemo->aos_invoices_aos_contracts_1aos_contracts_idb);
                                            $policy_no  = $crm_policy->name;
                                            if (!empty($crm_policy->assigned_user_id)) {
                                                $crmProducer        = BeanFactory::getBean('Users', $crm_invoice->assigned_user_id);
                                                $producer_name      = $crmProducer->first_name . ' ' . $crmProducer->last_name;
                                                $producer_class_key = $crmProducer->qbo_class_c;
                                            }
                                        }
                                    }


                                    global $current_user;

                                    if (empty($producer_class_key)) {
                                        echo '<br/>Policy Producer Class is not defined!';
                                        exit;
                                    }

                                    if (!empty($crm_invoice->billing_account_id)) {
                                        $crm_account      = BeanFactory::getBean('Accounts', $crm_invoice->billing_account_id);
                                        $qb_customer_id   = $crm_account->qbo_id_c;
                                        $qb_customer_name = $crm_account->name;
                                    }
                                    if (empty($qb_customer_id)) {
                                        echo '<br/>QBO Customer ID is not defined!';
                                        exit;
                                    }
                                    if (!empty($crm_invoice->insrr_insurers_id_c)) {
                                        $crmInsurer    = BeanFactory::getBean('insrr_Insurers', $crm_invoice->insrr_insurers_id_c);
                                        $vendor_qbo_id = $crmInsurer->qbo_id_c;
                                        $vendor_name   = $crmInsurer->name;
                                    }
                                    if (empty($vendor_qbo_id)) {
                                        echo '<br/>';
                                        echo "Insurer/Vendor doesnt have QBO id, please update it to be able export bills.";
                                        echo '<br/>';
                                        exit;
                                    }
                                    $crm_creditmemo_id = $crm_invoice->creditmemo_id_c;

                                    $tbe_qbo = BeanFactory::getBean('tbe_qbo');
                                    if (!$tbe_qbo->isAllowedQBO()) {
                                        echo '<br/>You are not allowed to use QBO function<br/>';
                                        exit;
                                    }

                                    $tbe_qbo->retrieveSetting();
                                    require_once($tbe_qbo->sdk_path . 'config.php');
                                    require_once(PATH_SDK_ROOT . 'Core/ServiceContext.php');
                                    require_once(PATH_SDK_ROOT . 'DataService/DataService.php');
                                    require_once(PATH_SDK_ROOT . 'PlatformService/PlatformService.php');
                                    if (empty($tbe_qbo->realmid))
                                        exit("RealmID is not specified.\n");
                                    $requestValidator = new OAuthRequestValidator($tbe_qbo->access_token, $tbe_qbo->access_token_secret, $tbe_qbo->consumer_key, $tbe_qbo->consumer_secret);
                                    if (!$requestValidator)
                                        exit("Problem while initializing requestValidator.\n");
                                    $serviceContext = new ServiceContext($tbe_qbo->realmid, IntuitServicesType::QBO, $requestValidator);
                                    if (!$serviceContext)
                                        exit("Problem while initializing ServiceContext.\n");
                                    $dataService = new DataService($serviceContext);
                                    if (!$dataService)
                                        exit("Problem while initializing DataService.\n");

                                    //Get Line Items
                                    $crm_items = array();
                                    $sql       = " SELECT c.name, p.qbo_id_c, c.product_unit_price, c.vat, cc.commission_rate_c, p.to_insurer_c, c.item_description FROM aos_products_quotes as c "; //
                                    $sql .= " LEFT JOIN aos_products_quotes_cstm as cc ON c.id = cc.id_c ";
                                    $sql .= " LEFT JOIN aos_products_cstm as p ON p.id_c = c.product_id ";
                                    $sql .= " WHERE c.parent_type = 'AOS_Invoices' AND c.parent_id = '" . $crm_invoice->id . "' AND c.deleted = 0;";
                                    $result = $crm_invoice->db->query($sql);
                                    while ($row = $crm_invoice->db->fetchByAssoc($result)) {
                                        $crm_items[] = array(
                                            'rate' => $row['commission_rate_c'],
                                            'qbo_id' => $row['qbo_id_c'],
                                            'amount' => $row['product_unit_price'],
                                            'name' => $row['name'],
                                            'vat' => $row['vat'],
                                            'to_insurer_c' => $row['to_insurer_c'],
                                            'item_description' => $row['item_description']
                                        );
                                    }


                                    //End Line Items
                                    if ($crm_policy->direct_commission_c) {

                                  		if( empty($crm_invoice->qbo_creditmemo_id_c) ){
																			$oCreditMemo = new IPPCreditMemo();
																			$oCreditMemo->CustomerRef = $vendor_qbo_id;//vendor_customer_id;//Not customer just vendor
																			$oCreditMemo->TxnDate = $invoice_date;//TODO: Clarify!
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
																					$oLine->Amount = abs($item['amount']);
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

																						try{
																							$resultingCustomerObj = $dataService->Add($oCreditMemo);
																							$qb_creditmemo_id = $resultingCustomerObj->Id;
																							if( !empty($qb_creditmemo_id) ){
																								$crm_invoice->qbo_creditmemo_id_c = $qb_creditmemo_id;
                                                $crm_invoice->exists_qbo_c = 1;
																								$crm_invoice->save(false);
																							}

																						} catch (Exception $e) {
																							echo 'Error: Unable to add CreditMemo.';
																							echo 'Caught exception: ',  $e->getMessage();
																							exit;
																						}
																					}
																					echo '<br/>QBO CreditMemo ID: '.$crm_creditmemo->qbo_creditmemo_id_c;

                                        //DIRECT COMMISSION - vendorCredit (aka PRODUCER BILL) - BEGIN
                                        echo '<br/>';
                                        echo '<br/>Producer VendorCredit...';
                                        if (empty($crm_creditmemo->qbo_bill_p_id_c)) {
                                            $rate = 35; //TODO: add to conf or make error msg
                                            //if(!empty($crm_policy->c_rate_c)){
                                            //    $rate = $crm_policy->c_rate_c;
                                            //}
                                            $rate = $crmPolicy->c_rate_c;
                                            if (empty($rate)) {
                                                $rate = 0;
                                            }

                                            $VendorCredit                = new IPPVendorCredit();
                                            $VendorCredit->TxnDate = $invoice_date;
                                            $VendorCredit->DocNumber     = $policy_no . 'CR';
                                            $VendorCredit->DepartmentRef = $cnf_bill_producer_dep_id; //"Producers"  (Location)
                                            $VendorCredit->VendorRef     = $cnf_bill_producer_vendor_id; //"Producer Commissions Payable"   //Insurer ID
                                            $VendorCredit->PrivateNote   = $qb_customer_name;
                                            $line_arr                    = array();
                                            foreach ($crm_items as $i => $item) {
                                                $oLine              = new IPPLine();
                                                $oLine->Amount      = (abs($item['amount']) * $rate) / 100;
                                                $oLine->Description = " {$rate}% * ";
                                                $oLine->Description .= number_format($item['amount'], 2, '.', ',');
                                                $oLine->DetailType                           = 'AccountBasedExpenseLineDetail';
                                                $oAccountBasedExpenseLineDetail              = new IPPAccountBasedExpenseLineDetail();
                                                $oAccountBasedExpenseLineDetail->AccountRef  = $cnf_bill_producer_item_acc_id; // = 93;//5100 Producer Expense
                                                $oAccountBasedExpenseLineDetail->CustomerRef = $qb_customer_id;
                                                $oAccountBasedExpenseLineDetail->ClassRef    = $producer_class_key;
                                                $oLine->AccountBasedExpenseLineDetail        = $oAccountBasedExpenseLineDetail;
                                                $line_arr[]                                  = $oLine;
                                            }
                                            $VendorCredit->Line = $line_arr;
                                            $qbo_id             = '';
                                            try {
                                                $resultingCustomerObj = $dataService->Add($VendorCredit);
                                                $qbo_id               = $resultingCustomerObj->Id;
                                            }
                                            catch (Exception $e) {
                                                echo '<br/>Failed to add Producer VendorCredit';
                                                echo 'Caught exception: ', $e->getMessage(), "\n";
                                            }
                                            $crm_creditmemo->qbo_bill_p_id_c = $qbo_id;

                                            if (!empty($crm_creditmemo->qbo_bill_p_id_c)) {
                                                $crm_creditmemo->save(false);
                                            }
                                        }
                                        echo '<br/>QBO Producer VendorCredit ID: ' . $crm_creditmemo->qbo_bill_p_id_c;
                                        //DIRECT COMMISSION - vendorCredit (aka PRODUCER BILL) - END
                                    } else {

																      		//CreditMemo - BEGIN
																					if( empty($crm_invoice->qbo_creditmemo_id_c) ){
																						$line_arr = array();
																						foreach($crm_items as $i => $item){
																							if( !empty($item['qbo_id']) ){//qbo_id_c
																								$oLine = new IPPLine() ;
																								$oLine->Amount = abs($item['amount']);
																								$oLine->Description =  $item['item_description'];//Free form text description of the line item that appears in the printed record
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
																						$oCreditMemo->TxnDate = $invoice_date;
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
                                            $oCreditMemo->ClassRef    = $producer_class_key;
																						$oCreditMemo->Line = $line_arr;


																						try{
																							$resultingCustomerObj = $dataService->Add($oCreditMemo);
																							$qbo_id = $resultingCustomerObj->Id;
																							if( !empty($qbo_id) ){
																								$crm_invoice->qbo_creditmemo_id_c = $qbo_id;
                                                $crm_invoice->exists_qbo_c = 1;
																								$crm_invoice->save(false);
																							}
																						} catch (Exception $e) {
																							echo 'Error: Unable to add CreditMemo....';
																							echo 'Caught exception: ',  $e->getMessage(), "\n";
																						}
																					}
																					echo '<br/>QBO CreditMemo ID: '.$crm_invoice->qbo_creditmemo_id_c;
																					//CreditMemo - END

                                        //VendorCredit - BEGIN

                                        //-------------------------VENDOR -----------------BEGIN
                                        echo '<br/>';
                                        echo '<br/>Insurer VendorCredit...';
                                        if (empty($crm_creditmemo->qbo_bill_v_id_c)) {
                                            $VendorCredit        = new IPPVendorCredit();
                                            $VendorCredit->TxnDate = $invoice_date;
                                            $VendorCredit->DocNumber     = $policy_no; //.'_16';
                                            $VendorCredit->DepartmentRef = $cnf_bill_vendor_dep_id;
                                            $VendorCredit->VendorRef     = $vendor_qbo_id;
                                            //$VendorCredit->GlobalTaxCalculation = "TaxExcluded";
                                            $VendorCredit->PrivateNote   = $qb_customer_name;

                                            $line_arr = array();
                                            foreach ($crm_items as $i => $item) {
                                                if ($item['to_insurer_c']) {
                                                    //----------
                                                    $oLine                                    = new IPPLine();
                                                    $oLine->Amount                            = abs($item['amount']);
                                                    $oLine->DetailType                  = 'AccountBasedExpenseLineDetail';
                                                    $oLine->Description                          = $item['name'];
                                                    $oAccountBasedExpenseLineDetail=new IPPAccountBasedExpenseLineDetail();
                                                    $oAccountBasedExpenseLineDetail->AccountRef  = $cnf_bill_vendor_item_acc_id;
                                                    $oAccountBasedExpenseLineDetail->CustomerRef = $qb_customer_id;
                                                    $oAccountBasedExpenseLineDetail->ClassRef    = $producer_class_key;
                                                    $oLine->AccountBasedExpenseLineDetail = $oAccountBasedExpenseLineDetail;
                                                    $line_arr[]                                  = $oLine;
                                                    //----------
                                                    $_item_rate                      = (float) $item['rate'];

                                                    $amount_commission       = (abs($item['amount']) * $_item_rate) / 100;
                                                    $oLine                      = new IPPLine();
                                                    $oLine->Amount              = $amount_commission * -1;
                                                    $oLine->DetailType               = 'AccountBasedExpenseLineDetail';
                                                    $oLine->Description            = $_item_rate . '%';
                                                    $oAccountBasedExpenseLineDetail  = new IPPAccountBasedExpenseLineDetail();
                                                    $oAccountBasedExpenseLineDetail->AccountRef= $cnf_bill_vendor_item_acc_commission_id;
                                                    $oAccountBasedExpenseLineDetail->CustomerRef = $qb_customer_id;
                                                    $oAccountBasedExpenseLineDetail->ClassRef    = $producer_class_key;
                                                    $oLine->AccountBasedExpenseLineDetail        = $oAccountBasedExpenseLineDetail;
                                                    $line_arr[]                                  = $oLine;

                                                }
                                            }

                                            $VendorCredit->Line = $line_arr; //array($oLine1, $oLine2) ;

                                            $qbo_id             = '';
                                            try {
                                                $resultingCustomerObj = $dataService->Add($VendorCredit);
                                                $qbo_id               = $resultingCustomerObj->Id;
                                            }
                                            catch (Exception $e) {
                                                echo '<br/>Failed to add Insurer VendorCredit';
                                                echo 'Caught exception: ', $e->getMessage(), "\n";
                                            }

                                            $crm_invoice->qbo_bill_v_id_c = $qbo_id;
                                            if (!empty($crm_invoice->qbo_bill_v_id_c)) {
                                                $crm_invoice->save(false);
                                            }
                                        }
                                        echo '<br/>QBO Insurer VendorCredit ID: ' . $crm_invoice->qbo_bill_v_id_c;
                                        //-----------------------VENDOR ----------------END
                                        //-----------------------PRODUCER --------------BEGIN--------------//
                                        echo '<br/>';
                                        echo '<br/>Producer Bill...';
                                        if (empty($crm_invoice->qbo_bill_p_id_c)) {
                                            $rate = 35; //Fill from Policy

                                            $rate = $crmPolicy->c_rate_c;
                                            if (empty($rate)) {
                                                $rate = 0;
                                            }

                                            $VendorCredit                = new IPPVendorCredit();
                                            $VendorCredit->DocNumber     = $policy_no;
                                            $VendorCredit->DepartmentRef = $cnf_bill_producer_dep_id; //"Producers"  (Location)
                                            $VendorCredit->VendorRef=$cnf_bill_producer_vendor_id;//"Producer Commissions Payable"   //Insurer ID
                                            $VendorCredit->PrivateNote   = $qb_customer_name;

                                            $line_arr = array();
                                            foreach ($crm_items as $i => $item) {
                                                $_item_rate = (float) $item['rate'];
                                                //$_item_rate = 20;

                                                $oLine              = new IPPLine();
                                                //$item['amount'] = abs($item['amount']);
                                                $oLine->Amount   = (((abs($item['amount']) * $item['rate']) / 100) * $rate) / 100;
                                                $oLine->Description = " {$rate}% * ( ";
                                                $oLine->Description .= number_format($item['amount'], 2, '.', ',');
                                                $oLine->Description .= " * {$_item_rate}%)";
                                                //TODO: remember about fee
                                                $oLine->DetailType                  = 'AccountBasedExpenseLineDetail';
                                                $oAccountBasedExpenseLineDetail= new IPPAccountBasedExpenseLineDetail();
                                                $oAccountBasedExpenseLineDetail->AccountRef=$cnf_bill_producer_item_acc_id ;    // = 93;//5100 Producer Expense
                                                $oAccountBasedExpenseLineDetail->CustomerRef = $qb_customer_id;
                                                $oAccountBasedExpenseLineDetail->ClassRef    = $producer_class_key;
                                                $oLine->AccountBasedExpenseLineDetail = $oAccountBasedExpenseLineDetail;
                                                $line_arr[]                                  = $oLine;

                                            }


                                            $VendorCredit->Line = $line_arr; //array($oLine1, $oLine2);

                                            $qbo_id             = '';
                                            try {

                                                $resultingCustomerObj = $dataService->Add($VendorCredit); //Ross
                                                $qbo_id               = $resultingCustomerObj->Id;
                                            }
                                            catch (Exception $e) {
                                                echo '<br/>Failed to add Producer VendorCredit';
                                                echo 'Caught exception: ', $e->getMessage(), "\n";
                                            }
                                            $crm_invoice->qbo_bill_p_id_c = $qbo_id;
                                            if (!empty($crm_invoice->qbo_bill_p_id_c)) {
                                                $crm_invoice->save(false);
                                            }
                                        }
                                        echo '<br/>QBO Producer VendorCredit ID: ' . $crm_invoice->qbo_bill_p_id_c;
                                        //-----------------------PRODUCER--------END---------------//

                                        //VendorCredit - END
                                    }
                                    exit;

                                    // */
                                } else {
                                    echo 'Error: not enough parameters to accomplish request';
                                    exit;
                                }


                                /** Code ends here **/



                            } else {
                                $oInvoice->Line = $line_arr;
                            }

                            try {


                                $resultingCustomerObj = $dataService->Add($oInvoice);

                                if (is_array($resultingCustomerObj) && isset($resultingCustomerObj['error'])) {
                                    $tbe_qbo->name .= ' [addInvoice]';
                                    $tbe_qbo->description = strip_tags($resultingCustomerObj['error_msg']);
                                    $tbe_qbo->save(false);
                                    echo '<br/>Error: ' . $tbe_qbo->description . '<br/>';
                                    exit;
                                }

                                $qb_inv_id = $resultingCustomerObj->Id;
                                if (!empty($qb_inv_id)) {
                                    $crm_invoice->qbo_id_c     = $qb_inv_id;
                                    $crm_invoice->exists_qbo_c = 1;
                                    $crm_invoice->save(false);
                                }
                            }
                            catch (Exception $e) {
                                echo '<br/>Error: Unable to add invoice.<br/>';
                                echo '<br/> ' . $e->getMessage() . "<br/>";

                                $tbe_qbo->name .= ' [add]E';
                                $tbe_qbo->error_msg = $e->getMessage();
                                $tbe_qbo->save(false);
                                exit;
                            }

                        }
                        echo '<br/>QBO Invoice ID: ' . $crm_invoice->qbo_id_c;
                        //BILLs - begin
                        if (!empty($crm_items)) {
                            //-------------------------VENDOR BILL-----------------BEGIN
                            echo '<br/>';
                            echo '<br/>Insurer Bill...';
                            if (empty($crm_invoice->qbo_bill_v_id_c)) {
                                $oBill = new IPPBill();
                                //$oBill->DueDate = $due_date;
                                $oBill->TxnDate       = $invoice_date;
                                $oBill->DocNumber     = $policy_no; //.'_16';
                                $oBill->DepartmentRef = $cnf_bill_vendor_dep_id;
                                $oBill->SalesTermRef  = $cnf_bill_vendor_term_id;
                                $oBill->VendorRef     = $vendor_qbo_id;
                                //$oBill->GlobalTaxCalculation = "TaxExcluded";

                                $oBill->PrivateNote = $qb_customer_name; //Ross

                                $line_arr = array();

                                foreach ($crm_items as $i => $item) {
                                    if ($item['to_insurer_c']) {
                                        //----------
                                        $oLine                                       = new IPPLine();
                                        $oLine->Amount                               = $item['amount'];
                                        $oLine->DetailType                           = 'AccountBasedExpenseLineDetail';
                                        $oLine->Description                          = $item['name'];
                                        $oAccountBasedExpenseLineDetail              = new IPPAccountBasedExpenseLineDetail();
                                        $oAccountBasedExpenseLineDetail->AccountRef  = $cnf_bill_vendor_item_acc_id;
                                        $oAccountBasedExpenseLineDetail->CustomerRef = $qb_customer_id;
                                        $oAccountBasedExpenseLineDetail->ClassRef    = $producer_class_key;
                                        $oLine->AccountBasedExpenseLineDetail        = $oAccountBasedExpenseLineDetail;
                                        $line_arr[]                                  = $oLine;
                                        //----------
                                        $_item_rate                                  = (float) $item['rate'];
                                        if ($_item_rate > 0) {
                                            $amount_commission                           = ($item['amount'] * $_item_rate) / 100;
                                            $oLine                                       = new IPPLine();
                                            //echo '<br/>Amt: '.
                                            $oLine->Amount                               = $amount_commission * -1;
                                            $oLine->DetailType                           = 'AccountBasedExpenseLineDetail';
                                            //echo '<br/>Desc: '.
                                            $oLine->Description                          = $_item_rate . '%';
                                            $oAccountBasedExpenseLineDetail              = new IPPAccountBasedExpenseLineDetail();
                                            $oAccountBasedExpenseLineDetail->AccountRef  = $cnf_bill_vendor_item_acc_commission_id;
                                            $oAccountBasedExpenseLineDetail->CustomerRef = $qb_customer_id;
                                            $oAccountBasedExpenseLineDetail->ClassRef    = $producer_class_key;
                                            $oLine->AccountBasedExpenseLineDetail        = $oAccountBasedExpenseLineDetail;
                                            $line_arr[]                                  = $oLine;
                                        }
                                    }
                                }
                                //What about "MGA Fee"?
                                //TODO: check if lineItems are not empty?
                                $oBill->Line = $line_arr; //array($oLine1, $oLine2) ;

                                $qbo_id      = '';
                                try {
                                    $resultingCustomerObj = $dataService->Add($oBill); //Ross
                                    if (is_array($resultingCustomerObj) && isset($resultingCustomerObj['error'])) {
                                        $tbe_qbo->name .= ' [addVendorBill]';
                                        $tbe_qbo->description = strip_tags($resultingCustomerObj['error_msg']);
                                        $tbe_qbo->save(false);
                                        echo '<br/>Error: ' . $tbe_qbo->description . '<br/>';
                                        exit;
                                    }

                                    $qbo_id = $resultingCustomerObj->Id;
                                }
                                catch (Exception $e) {
                                    echo '<br/>Failed to add Vendor Bill<br/>';
                                    echo '<br/>' . $e->getMessage() . "<br/>";

                                    $tbe_qbo->name .= ' [addVendorBill]E';
                                    $tbe_qbo->error_msg = $e->getMessage();
                                    $tbe_qbo->save(false);
                                    exit;
                                }
                                $crm_invoice->qbo_bill_v_id_c = $qbo_id;
                                if (!empty($crm_invoice->qbo_bill_v_id_c)) {
                                    $crm_invoice->save(false);
                                }
                            }
                            echo '<br/>QBO Vendor Bill ID: ' . $crm_invoice->qbo_bill_v_id_c;
                            //-----------------------VENDOR BILL----------------END
                            //-----------------------PRODUCER BILL--------------BEGIN--------------//
                            echo '<br/>';
                            echo '<br/>Producer Bill...';
                            if (empty($crm_invoice->qbo_bill_p_id_c)) {

                                //$amount = 300;
                                //$amount_description = '';//exp: 35% * ( amnt * 30% + 100 )
                                $rate = 35; //Fill from Policy
                                //if(!empty($crmPolicy->c_rate_c)){
                                //    $rate = $crmPolicy->c_rate_c;
                                //}
                                $rate = $crmPolicy->c_rate_c;
                                if (empty($rate)) {
                                    $rate = 0;
                                }

                                $oBill                = new IPPBill();
                                //$oBill->DueDate = $due_date;
                                $oBill->TxnDate       = $invoice_date;
                                $oBill->DocNumber     = $policy_no;
                                $oBill->DepartmentRef = $cnf_bill_producer_dep_id; //"Producers"  (Location)
                                $oBill->SalesTermRef  = $cnf_bill_producer_term_id; //"Due on receipt" NOTE: check on moving to prod
                                $oBill->VendorRef     = $cnf_bill_producer_vendor_id; //"Producer Commissions Payable"   //Insurer ID
                                //$oBill->GlobalTaxCalculation = "TaxExcluded";
                                $oBill->PrivateNote   = $qb_customer_name; //Ross
                                $oBill->CustomerMemo = $qb_customer_name;//Ross

                                $line_arr = array();
                               
                                foreach ($crm_items as $i => $item) {
                                    $_item_rate = (float) $item['rate'];
                                    if ($_item_rate > 0) {
                                        //echo '<br/>Rate: '.$item['rate'].' | '.$rate;
                                        $oLine              = new IPPLine();
                                        //echo '<br/>Amt: '.
                                        $oLine->Amount      = ((($item['amount'] * $item['rate']) / 100) * $rate) / 100;
                                        $oLine->Description = " {$rate}% * ( ";
                                        $oLine->Description .= number_format($item['amount'], 2, '.', ',');
                                        //echo '<br/>Desc: '.
                                        $oLine->Description .= " * {$_item_rate}%)";
                                        //TODO: remember about fee
                                        $oLine->DetailType                           = 'AccountBasedExpenseLineDetail';
                                        $oAccountBasedExpenseLineDetail              = new IPPAccountBasedExpenseLineDetail();
                                        $oAccountBasedExpenseLineDetail->AccountRef  = $cnf_bill_producer_item_acc_id; // = 93;//5100 Producer Expense
                                        $oAccountBasedExpenseLineDetail->CustomerRef = $qb_customer_id;
                                        $oAccountBasedExpenseLineDetail->ClassRef    = $producer_class_key;
                                        $oLine->AccountBasedExpenseLineDetail        = $oAccountBasedExpenseLineDetail;
                                        $line_arr[]                                  = $oLine;
                                    }
                                }   //echo "<pre>"; print_r($line_arr); die;
                                //TODO: check if lineItems are not empty?
                                $oBill->Line = $line_arr; //array($oLine1, $oLine2);
                                $qbo_id      = '';
                                try {
                                    $resultingCustomerObj = $dataService->Add($oBill); //Ross
                                    if (is_array($resultingCustomerObj) && isset($resultingCustomerObj['error'])) {
                                        $tbe_qbo->name .= ' [addProducerBill]';
                                        $tbe_qbo->description = strip_tags($resultingCustomerObj['error_msg']);
                                        $tbe_qbo->save(false);
                                        echo '<br/>Error: ' . $tbe_qbo->description . '<br/>';
                                        exit;
                                    }
                                    $qbo_id = $resultingCustomerObj->Id;
                                }
                                catch (Exception $e) {
                                    echo '<br/>Failed to add Producer Bill';
                                    echo '<br/>' . $e->getMessage() . "<br/>";

                                    $tbe_qbo->name .= ' [addProducerBill]E';
                                    $tbe_qbo->error_msg = $e->getMessage();
                                    $tbe_qbo->save(false);

                                    exit;
                                }
                                $crm_invoice->qbo_bill_p_id_c = $qbo_id;
                                if (!empty($crm_invoice->qbo_bill_p_id_c)) {
                                    $crm_invoice->save(false);
                                }
                            }
                            echo '<br/>QBO Producer Bill ID: ' . $crm_invoice->qbo_bill_p_id_c;
                            //-----------------------PRODUCER BILL---------END---------------//
                        }
                        //BILLs - end
                    }

                } else {
                    echo 'Error: unable to retrieve Invoice data';
                }
            } else {
                echo 'Error: not enough parameters to accomplish request';
            }
        } else {
            echo 'You are not allowed to use QBO export function';
        }
    }

}
