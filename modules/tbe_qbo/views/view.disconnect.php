<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/MVC/View/views/view.detail.php');
class Viewdisconnect extends ViewDetail{
	
	function Viewdisconnect() {
		parent::ViewDetail();
	}
	
    public function display() {
		echo '<br/>Disconnect Action<br/>';
		
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
		$Respxml = $platformService->Disconnect();
		if ($Respxml->ErrorCode == '0'){
			echo "<br />Disconnect successful!<br />";
			$tbe_qbo->cleanSetting();
		}else{
			echo "<br />Error! Disconnect failed..<br />";
			if ($Respxml->ErrorCode  == '270')	{
				echo "OAuth Token Rejected!<br />";
			}
		}
		echo "ResponseXML: ";
		var_dump( $Respxml);
		
		$tbe_qbo->cleanSetting();
		
		
	}
}

?>