<?php

	if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
	
	global $db;
	
	echo '<br/>Vendors:<br/>';
	
	$tbe_qbo = BeanFactory::getBean('tbe_qbo');
	$tbe_qbo->retrieveSetting();
	
	//if($tbe_qbo->isAllowedQBO()){
	try{
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

		//echo '<pre>';
		//print_r($dataService);
		//echo '</pre>';	

		$i = 1;
		while(1){
			$allItems = $dataService->FindAll('Vendor', $i, 100);
			if( !$allItems || (0==count($allItems)) ){break;}
			//echo '<pre>';
			//print_r($allItems);
			//echo '</pre>';
			foreach($allItems as $oneItem){
				$i++;
				$_name = empty($oneItem->CompanyName)?$oneItem->DisplayName:$oneItem->CompanyName;
				
				
				$_name = $db->quote( $_name);
				$sql = " SELECT id, name FROM insrr_Insurers WHERE name = '{$_name}' ; ";
				$res = $db->query($sql);
				while( $row = $db->fetchByAssoc($res) ){
					$_name .= ' ---------crmID:'.$row['id'];
					/*
					$crm_product = BeanFactory::getBean('AOS_Products', $row['id']);
					if(!$crm_product->qbo_id_c){
						$crm_product->qbo_id_c = $oneItem->Id;
						$crm_product->save(false);
					}else{
						$name .= '-------SKIP';
					}
					*/
				}
				
				$list_arr[$oneItem->Id] = $_name;
				
			}
		}
		
	}catch (Exception $e){
		echo 'Caught exception: ',  $e->getMessage(), "\n";
	}
	
	echo '<pre>';
	print_r($list_arr);
	echo '</pre>';