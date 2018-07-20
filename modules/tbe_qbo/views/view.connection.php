<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/MVC/View/views/view.detail.php');
class Viewconnection extends ViewDetail{
	
	function Viewconnection() {
		parent::ViewDetail();
	}
    public function display() {
		
		$tbe_qbo = BeanFactory::getBean('tbe_qbo');
		
		if(!$tbe_qbo->isAllowedQBO()){
			echo 'Access Restricted';
			die;
		}
		
		
		$tbe_qbo->retrieveSetting();
		
		if(empty($tbe_qbo->access_token)){
			$js = '<script type="text/javascript" src="https://appcenter.intuit.com/Content/IA/intuit.ipp.anywhere.js"></script>';
			$js .= '<script type="text/javascript" >';
			$js .= '$( document ).ready(function(){ intuit.ipp.anywhere.setup({menuProxy: "", grantUrl: "'.$tbe_qbo->callback_url.'"}); });';
			$js .= '</script>';
			echo $js;
			
			echo '<h2>QBO Connect Page</h2>';
			//echo '<br/><br/><br/>[SOME INFO]<br/><br/><br/>';
			echo "<br /> <ipp:connectToIntuit></ipp:connectToIntuit><br />";
			echo '<br/><br/><br/><br/>';
		}else{
			echo 'Seems u already connected!';
		}

		
		echo '<br/>';
		echo '<br/>';
		echo '<a href="index.php?module=tbe_qbo&action=disconnect">DISCONNECT</a>';
        echo '- Invalidates the OAuth access token in the request, thereby disconnecting the user from QuickBooks for this app.';
		echo '<br/>';
		echo '<br/>';
		
		//echo '<a href="index.php?module=tbe_qbo&action=reconnect">RECONNECT</a>';
        //echo '- Invalidates the OAuth access token used in the request and generates a new one, thereby extending the life span by 180 days. You can regenerate the tokens within 30 days prior to expiration!';
		//echo '<br/>';
		//echo '<br/>';
		
		
		
	}
}

?>