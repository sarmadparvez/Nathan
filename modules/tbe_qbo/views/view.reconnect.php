<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/MVC/View/views/view.detail.php');
class Viewreconnect extends ViewDetail{
	
	function Viewreconnect() {
		parent::ViewDetail();
	}
	
    public function display() {
		echo '<br/>Reconnect Action<br/>';
		
		
		echo '<br/><a href="index.php?module=tbe_qbo&action=connection">Back</a><br/>';
		
		$tbe_qbo = BeanFactory::getBean('tbe_qbo');
		
		if(!$tbe_qbo->isAllowedQBO()){
			echo 'Access Restricted';
			die;
		}
		
		$tbe_qbo->retrieveSetting();

		require_once($tbe_qbo->sdk_path.'config.php');
		require_once(PATH_SDK_ROOT . 'Core/ServiceContext.php');
		require_once(PATH_SDK_ROOT . 'DataService/DataService.php');
		require_once(PATH_SDK_ROOT . 'PlatformService/PlatformService.php');

		$requestValidator = new OAuthRequestValidator($tbe_qbo->access_token, $tbe_qbo->access_token_secret, $tbe_qbo->consumer_key, $tbe_qbo->consumer_secret);
		$serviceContext = new ServiceContext($tbe_qbo->realmid, IntuitServicesType::QBO, $requestValidator);
		if (!$serviceContext) exit("Problem while initializing ServiceContext.\n");
		$dataService = new DataService($serviceContext);
		if (!$dataService) exit("Problem while initializing DataService.\n");		

		$platformService = new PlatformService($serviceContext);
		$Respxml = $platformService->Reconnect();
		if ($Respxml->ErrorCode != '0'){
			echo "Error! Reconnection failed..";
			if ($Respxml->ErrorCode  == '270')	{
				echo "OAuth Access Token Rejected! <br />";
			}	else if($Respxml->ErrorCode  == '212')	{
				echo "Token Refresh Window Out of Bounds! The request is made outside the 30-day window bounds. <br />";
			}	else if($Respxml->ErrorCode  == '24')	{
				echo "Invalid App Token! <br />";
			}
		}else{
			echo "Reconnect successful!<br />";
			//TODO: save new data credentials
		}
		echo "ResponseXML: ";
		var_dump( $Respxml);		
		
	}
}

?>