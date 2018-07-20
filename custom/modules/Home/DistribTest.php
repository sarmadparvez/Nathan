<?php

error_reporting(E_ALL ^E_STRICT);
ini_set('display_errors', true);

echo '+';

	global $current_user;

	echo ($current_user->is_admin)?"IS admin":"NOT admin";
	
	//require_once('custom/ax/DistribLead.php');

	//$lead = BeanFactory::getBean('Leads', 'dc7c2fc0-b6f8-575d-a045-560d634b0069');//Test John
	//$d = DistribLead::sendAssignNotify($lead);
	//var_dump($d);

	
	//echo '<pre>';
	//print_r($data);
	//echo '</pre>';
	
	//$d = getDistribData($data, 'BidBond', 'ON');
	//echo '<pre>';
	//print_r($d);
	//echo '</pre>';
/*
	$d = DistribLead::sendAssignNotify($data, $bean)
	
	
	$d = array();
	$d = DistribLead::extractData($data, 'MortgageLenderBond', 'International');
	echo '<pre>';
	print_r($d);
	echo '</pre>';	
	
	$d = DistribLead::getExtractedDistribData('MortgageLenderBond', 'International');
	echo '<pre>';
	print_r($d);
	echo '</pre>';	

	
	echo '<br/>primaryUser: ';
	if( $d['primaryUser'] ){
		$user = BeanFactory::getBean('Users', $d['primaryUser']);
		echo $user->name;
		echo ' - '.$primary_email = $user->emailAddress->getPrimaryAddress($user);
	}	
	echo '<br/>secondaryUsers:';
	if( !empty($d['secondaryUsers']) ){
		foreach($d['secondaryUsers'] as $i => $user_id){
			$user = BeanFactory::getBean('Users', $user_id);
			echo '<br/>'. $user->name;
			echo ' - '.$primary_email = $user->emailAddress->getPrimaryAddress($user);
		}
	}	
	echo '<br/>reminderOne:';
	if( !empty($d['reminderOne']) ){
		foreach($d['reminderOne'] as $i => $user_id){
			$user = BeanFactory::getBean('Users', $user_id);
			echo '<br/>'. $user->name;
			echo ' - '.$primary_email = $user->emailAddress->getPrimaryAddress($user);
		}	
	}	
	echo '<br/>reminderTwo:';
	if( !empty($d['reminderTwo']) ){
		foreach($d['reminderTwo'] as $i => $user_id){
			$user = BeanFactory::getBean('Users', $user_id);
			echo '<br/>'.$user->name;
			echo ' - '.$primary_email = $user->emailAddress->getPrimaryAddress($user);
		}
	}
	
	
	function sendNotify($what, $whom){
		require_once('include/SugarPHPMailer.php');
		$mail = new SugarPHPMailer();
		$mail->setMailerForSystem();
		$mail->From     = "no-reply@bondsurety.ca";
		$mail->FromName = "no-reply";
		$mail->ContentType="text/html";
		$mail->IsHTML(false);
		
	}
*/	
	
//	$d = getDistribData($data, 'PropertyInsurance', 'BC');
//	echo '<pre>';
//	print_r($d);
//	echo '</pre>';	
	
//	$d = getDistribData($data, 'PropertyInsurance', '');
//	echo '<pre>';
//	print_r($d);
//	echo '</pre>';
	
echo '+';

/*
function getDistribData($data, $type, $state){
	foreach($data as $i => $p){
		if( !empty($state) ){
			if( ($p['type'] == $type) && ($p['state'] == $state) ){
				return $p;
			}
		}else{
			if( $p['type'] == $type ){
				return $p;
			}
		}
	}
	return false;
}*/