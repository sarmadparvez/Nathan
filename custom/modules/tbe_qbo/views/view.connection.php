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
			echo '<h2>QBO Connect Page</h2><br><br>';
			//echo '<br/><br/><br/>[SOME INFO]<br/><br/><br/>';
			echo '<table cellpadding="0" cellspacing="0" border="0" width="100%" class="tabForm" id="mass_update_table">
			        <tbody><tr><td><table width="100%" border="0" cellspacing="0" cellpadding="0">
			        <tbody><tr>
			        <td>Connect to QBO:</td>';
			echo "<td><ipp:connectToIntuit></ipp:connectToIntuit></td>";
			echo ' <td></td></tr></tbody></table></td></tr></tbody></table>';
		}else{
			echo '<h2>QBO Connect Page</h2><br><br>';
			echo '<table cellpadding="0" cellspacing="0" border="0" width="100%" class="tabForm" id="mass_update_table">
			        <tbody><tr><td><table width="100%" border="0" cellspacing="0" cellpadding="0">
			        <tbody><tr>
			        <th>You are connected to QBO! To Disconnect, check the <a href="#disconnect">Disconnect From QBO</a> section.</th>';
	        echo '</tr></tbody></table></td></tr></tbody></table>';

	        echo "<br></br><br>";
	        echo "<div id='disconnect'>";
		echo '<table cellpadding="0" cellspacing="0" border="0" width="100%" class="tabForm" id="mass_update_table">
			        <tbody><tr><td><table width="100%" border="0" cellspacing="0" cellpadding="0">
			        <tbody><tr>
			        <td>Disconnect From QBO:</td>';
			echo '<td><a href="index.php?module=tbe_qbo&action=disconnect" class="button btn-danger">Disconnect</a></td>';
			echo ' <td></td></tr></tbody></table></td></tr>';
			 echo '<tr><td>Invalidates the OAuth access token in the request, thereby disconnecting the user from QuickBooks for this app.</td></tr></tbody></table></div>';
		}
		echo '<br/>';
		echo '<br/>';
		echo '<br/><br/>';echo '<br/><br/>';echo '<br/><br/>';echo '<br/><br/>';
		echo '<br/><br/>';
		echo '<br/><br/>';
		echo '<br/><br/>';
		echo '<br/><br/>';
		echo '<br/><br/>';
		echo '<br/><br/>';
		echo '<br/><br/>';
		//echo '<a href="index.php?module=tbe_qbo&action=reconnect">RECONNECT</a>';
        //echo '- Invalidates the OAuth access token used in the request and generates a new one, thereby extending the life span by 180 days. You can regenerate the tokens within 30 days prior to expiration!';
		//echo '<br/>';
		//echo '<br/>';
		
		
		
	}
}

?>