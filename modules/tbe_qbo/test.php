<?php

echo '+';


global $timedate;

echo $_item_rate = (float) '8.05';

$crm_invoice = BeanFactory::getBean('AOS_Invoices', '3557ec9f-0010-1b78-4acc-56afd20a5727');

$invoice_date = $crm_invoice->invoice_date;
echo '<br/>invoice_date:'.$invoice_date;
echo '<br/>invoice_date DB:'.$timedate->to_db_date($invoice_date);

$due_date = $crm_invoice->due_date;
echo '<br/>due_date:'.$due_date;
echo '<br/>due_date DB:'.$timedate->to_db_date($due_date);


//global $current_user;
//var_dump($current_user->is_admin);

//$tbe_qbo = BeanFactory::getBean('tbe_qbo');
//$d = $tbe_qbo->isAllowedQBO();
//var_dump($d);

/*
	$tbe_qbo = BeanFactory::getBean('tbe_qbo');
	$tbe_qbo->retrieveSetting();
	
	require_once($tbe_qbo->sdk_path.'config.php');
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

	$i = 1;
	while(1){
		$allItems = $dataService->FindAll('TaxCode', $i, 100);
		if( !$allItems || (0==count($allItems)) ){break;}
		
		foreach($allItems as $oneItem){
			$name = '';
			$name .= '<br/>['.$oneItem->Id.'] '.$oneItem->Name;
			//$name .= ' | '.$oneItem->SalesTaxRateList->TaxRateDetail->TaxRateRef->value;
			//$name .= ' | '.$oneItem->SalesTaxRateList->TaxRateDetail->TaxRateRef->name;
			echo $name;
		}
	}
*/
	
/*
echo 'edit:';
if(ACLController::checkAccess('tbe_qbo', 'edit', true)){
	echo '-ok';
}else{
	echo '-nope';
}

echo 'list:';
if(ACLController::checkAccess('tbe_qbo', 'list', true)){
	echo '-ok';
}else{
	echo '-nope';
}



//java exception error handler
try {
	service.findAll(customer);
} catch (FMSException e) {
	if(e instanceof AuthenticationException){
		 System.out.println("Authentication Exception occurred");
		 //perform required operation when this exception occurs
	} else if(e instanceof ServiceException){
		 System.out.println("Service Exception occurred");
		 //perform required operation when this exception occurs
	} else if(e instanceof AuthenticationException){
		 System.out.println("Authenticatin Exception occurred");
		 //perform required operation when this exception occurs
	} else if(e instanceof AuthorizationException){
		 System.out.println("Authorization Exception occurred");
		 //perform required operation when this exception occurs
	} else {
		 System.out.println("Some other Exception in SDK or network happened");
		 //perform required operation when this exception occurs
	}
}

*/