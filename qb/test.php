<?php


if( $_SERVER['REMOTE_ADDR'] == '109.251.117.69'){
	try{
		$qb_path = 'qb/v3-sdk-2.3.0/';
		$qb_path = 'v3-sdk-2.3.0/';

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

	} catch (Exception $e) {
		echo 'Caught exception: ',  $e->getMessage(), "\n";
	}
}

echo '<br/>+<br/>';