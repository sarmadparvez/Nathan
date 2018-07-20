<?php

error_reporting(E_ALL ^E_STRICT);
ini_set('display_errors', true);


//sugar_cache_clear('distrib');//$administration->retrieveSettings('distrib');

require_once('custom/ax/DistribLead.php');
$ff = DistribLead::getDistribData();
echo '<pre>';print_r($ff);echo '</pre>';

echo '</br>Cache</br>';
$dd = sugar_cache_retrieve('distrib');
echo '<pre>';print_r($dd);echo '</pre>';

echo '-+-';

exit;

$type_list = $GLOBALS['app_list_strings']['lead_coverage_type_list'];
$type_list_k = array_keys($GLOBALS['app_list_strings']['lead_coverage_type_list']);
$type_list_v = array_values($GLOBALS['app_list_strings']['lead_coverage_type_list']);
		
echo htmlentities('E&O website');
echo '<br/>';
echo htmlspecialchars('E&O website');

$ddd = array(
		'ERRORS AND OMISSIONS INSURANCE',
		'errors and omissions insurance',
		//'errors&nbsp;and omissions insurance',
		'Errors and Omissions Insurance',
		'Professional Liability Insurance',
		'Cyber Liability',
		'IT Insurance',
		'ITInsurance',
		'Combine Liability and E&O Insurance',
		'Others',
		
		//http://trade-credit-insurance.ca/ WEB
		"Trade Credit insurance",
		"Contract Frustration Insurance",
		"Political Risk Insurance",
		"Letter of Guarantee Insurance",
		"Surety Bond Insurance",
);

foreach($ddd as $i => $type_value){
	
	$_key = '';
	
	echo '<br/><br/>InputValue: '.$type_value;
	
	if( !array_key_exists($type_value, $GLOBALS['app_list_strings']['lead_coverage_type_list']) ){
		$detected_key = tryDetectListKey($type_value, 'lead_coverage_type_list');
		if( !empty($detected_key) ){
			echo '___DETECTED_KEY: '.$_key = tryDetectListKey($type_value, $type_list);
		}
	}else{
		echo '-----EXISTS';
	}
	
	
	
/*	
	echo '<br/>|'.$_value1 = html_entity_decode($type_value);
	echo '___DETECTED_KEY: '.$_key = tryDetectListKey($_value1, $type_list);
	
	echo '<br/>|'.$_value1 = htmlspecialchars_decode ($type_value);
	echo '___DETECTED_KEY: '.$_key = tryDetectListKey($_value1, $type_list);
	
	echo '<br/>|'.$_value1 = str_replace("\xA0", ' ', html_entity_decode($type_value) );
	echo '___DETECTED_KEY: '.$_key = tryDetectListKey($_value1, $type_list);
*/
	//die;
	
	/*
	if( in_array($type_value, $type_list_k) ){
		$_key = $type_value;
	}
	if(empty($_key)){
		echo in_array($type_value, $type_list_v)?"+++":"---";
	}
	$type_value = str_ireplace(" ", "", $type_value);
	echo '<br/>'.$type_value;
	if(empty($_key)){if( in_array($type_value, $type_list_k) ){$_key = $type_value;}}
	if(empty($_key)){if(empty($_key)){echo in_array($type_value, $type_list_v)?"+++":"---";}

	$type_value = str_ireplace("&", "and", $type_value);
	echo '<br/>'.$type_value;
	if(empty($_key)){if( in_array($type_value, $type_list_k) ){$_key = $type_value;}}
	if(empty($_key)){echo in_array($type_value, $type_list_v)?"+++":"---";	}
	
	$type_value = str_ireplace("Insurance", "", $type_value);
	echo '<br/>'.$type_value;
	if(empty($_key)){if( in_array($type_value, $type_list_k) ){$_key = $type_value;}}
	if(empty($_key)){echo in_array($type_value, $type_list_v)?"+++":"---";}
*/
}

//echo '<pre>';print_r($type_list);echo '</pre>';


function tryDetectListKey($input_value, $list){
	if( !is_array($list) ){
		global $app_list_strings;
		if( isset($app_list_strings[$list]) ){
			$list = $app_list_strings[$list];
		}else{
			return false;
		}
	}
	if( !empty($list) ){
		$list_k = array_map('strtolower', $list);
		
		if( array_key_exists($input_value, $list) ){ return $input_value; }
		$key =  array_search($input_value, $list);
		if( !empty($key) ){return $key;}
		$key =  array_search(strtolower($input_value), $list_k);
		if( !empty($key) ){return $key;}
		
		$input_value = str_ireplace(" ", "", $input_value);
		
		if( array_key_exists($input_value, $list) ){ return $input_value; }
		$key =  array_search($input_value, $list);
		if( !empty($key) ){return $key;}
		$key =  array_search(strtolower($input_value), $list_k);
		if( !empty($key) ){return $key;}
		
		$input_value = str_ireplace("&", "and", $input_value);

		if( array_key_exists($input_value, $list) ){ return $input_value; }
		$key =  array_search($input_value, $list);
		if( !empty($key) ){return $key;}
		$key =  array_search(strtolower($input_value), $list_k);
		if( !empty($key) ){return $key;}
		
		$input_value = str_ireplace("Insurance", "", $input_value);

		if( array_key_exists($input_value, $list) ){ return $input_value; }
		$key =  array_search($input_value, $list);
		if( !empty($key) ){return $key;}
		$key =  array_search(strtolower($input_value), $list_k);
		if( !empty($key) ){return $key;}
		
	}
	return false;
}



function in_arrayi($needle, $haystack){
    return in_array(strtolower($needle), array_map('strtolower', $haystack));
}


/*
$value = '0.00';

echo '<br/>OLD way: ';
echo ( !empty($value) )?'+':'-';
echo '<br/>NEW way: ';
echo ( !empty((float)$value) )?'+':'-';
echo '<br/>NEWNEW way: ';
echo ( (float)$value > 0 )?'+':'-';
*/

//require_once('custom/include/ax_jobs.php');
//$result = ax_jobs::oppRenewal();
//die;
	
//a52271a5-e036-3b2f-b9b5-55e619c32e4e

//error_reporting(E_ALL);
//ini_set('display_errors', true);

//iptables -t mangle -A POSTROUTING -j TTL --ttl-set 64

die('<br/>------<br/>');


echo $mystring = 'Date/Time: 9/30/2015 2:09:02 PM
 Caller: 2505459062 
 Notes:
Name: Amandeep Singh 
 Person caller was forwarded to: Frank
 New Lead';
 
$pos = stripos($mystring, 'New Lead');
if ($pos !== false) {
	echo '<br/><b>F</b>';
}


echo '<br/>';
echo '<br/>';

echo $mystring = 'Date/Time: 9/30/2015 12:27:24 PM 
Caller: 2506343888 
Notes:
Name: Shirley 
Person caller was forwarded to:Dustin 
NEW LEAD ';
 
$pos = stripos($mystring, 'new lead');
if ($pos !== false) {
	echo '<br/><b>F</b>';
}

echo '<br/>';
echo '<br/>';

echo $mystring = 'Date/Time: 9/30/2015 12:27:24 PM 
Caller: 2506343888 
Notes:
Name: Shirley 
Person caller was forwarded to:Dustin';
 
$pos = stripos($mystring, 'new lead');
if ($pos !== false) {
	echo '<br/><b>F</b>';
}


//require_once('custom/include/ax/testJob.php');
//testJob::hotLead();
die('<br/>*<br/>');
//echo '<br/>'.$gmdate.'<br/>';
//echo '<br/>'.date('Y-m-d H:i:s').'<br/>';

global $db;

$gmToday = gmdate('Y-m-d 00:00:00');
echo $gmdate = gmdate('Y-m-d H:i:s');
	
	$db->query(" UPDATE leads SET date_entered = '2015-09-15 19:20:41' WHERE id = '2b802faf-65cb-f074-603e-55f8600fcc4a'; ");
	
	$sql = " SELECT a.id, a.last_name, c.accept_status_c,  a.assigned_user_id, a.created_by, a.date_entered, TIMEDIFF('{$gmdate}', a.date_entered) as t, HOUR( TIMEDIFF('{$gmdate}', a.date_entered)) as h ";
	$sql .= " FROM leads as a ";
	$sql .= " LEFT JOIN leads_cstm as c ON c.id_c = a.id ";
	$sql .= " WHERE  a.deleted = 0 AND  a.assigned_user_id <> a.created_by AND a.date_entered > '{$gmToday}' AND ( c.accept_status_c = 'none' OR c.accept_status_c = '' ) ";
	//$sql .= " WHERE  a.deleted = 0 AND  a.assigned_user_id <> a.created_by AND ( c.accept_status_c = 'none' OR c.accept_status_c = '' ) ";
	//$sql .= " HAVING (HOUR(t) = 1 OR HOUR(t) = 2) AND MINUTE(t) = 0 ";
	$sql .= "ORDER BY a.date_entered DESC  ; ";
	$res = $db->query($sql);
	while( $arr = $db->fetchByAssoc($res) ){
		echo '<br/>T is '.$arr['t'].'  <a href="index.php?module=Leads&action=DetailView&record='.$arr['id'].'">'.$arr['last_name'].'</a>';
	}

	
	

die('<br/>+<br/>');

/*
	require_once('custom/include/ax/axJob.php');

	$input_arr = array(
		'Don',
		'DON',
		'Frank',
		'Serge',
		'Dustin',
		'Darren',
		'Robert',
		'robert',
		'Glenn',
		'Andrew',
		'Ruba',
		'Jeffs roofing',
	);
	foreach($input_arr as $i => $msg){
		echo '<br/>'.$msg;
		$processed = axJob::detectUser($msg);
		var_dump($processed);
	}


*/
die('<br/>------------');

$message = '<span style="font-family:Verdana;color:#000000;font-size:10pt;"><br /><br /></span><blockquote id="sugar_text_replyBlockquote" style="border-left:2px solid #0000FF;margin-left:8px;padding-left:8px;font-size:10pt;color:#000000;font-family:verdana;">
<div id="sugar_text_wmQuoteWrapper">
-------- Original Message --------<br />
Subject: Call Information<br />
From: "Sonia Marsili" <<a href="mailto:vaughan.IA3@intelligentoffice.com">vaughan.IA3@intelligentoffice.com</a>><br />
Date: Fri, August 07, 2015 10:46 am<br />
To: <<a href="mailto:crm@aibrokers.ca">crm@aibrokers.ca</a>><br /><br /><div class="WordSection1"><div><span style="font-family:Calibri, sans-serif;">Date/Time: 07/08/2015 1:44:39 PM</span> <p></p></div><div><span style="font-family:Calibri, sans-serif;">Caller: 4161234567 </span><p></p></div><div><span style="font-family:Calibri, sans-serif;">Notes: Name:Ross Vol</span> <p></p></div><div><span style="font-family:Calibri, sans-serif;">Person caller was forwarded to:Ross </span><p></p></div><div><span style="font-family:Calibri, sans-serif;">New Lead/Existing Customer:Car insurance</span> – New Client<p></p></div><div class="MsoNormal" style="font-size:12pt;margin-bottom:12pt;"><br /><br /><br /><br /><p></p></div></div>
</div>
</blockquote>';

$message = 'Date/Time: 9/2/2015 11:35:27 AM

Caller: 4161234567

Notes: Name: Ros Vol

Person caller was forwarded to: Ross

New Lead/Existing Customer:';
	
	require_once('custom/include/ax/axJob.php');
	
	$processed = axJob::processEmailMsg($message);
	echo '<pre>'; print_r($processed); echo '</pre>';
	echo '<br/>';
	echo '<br/><a href="index.php?module=Calls&action=DetailView&record='.$processed['call_id'].'">Call</a>';
	echo '<br/>';

//$message = '<div class="WordSection1"><p><span style="font-family:Calibri, sans-serif;">Date/Time: 9/1/2015 10:08:08 AM</span> </p><p></p><p><span style="font-family:Calibri, sans-serif;">Caller: 4165902031 </span></p><p></p><p><span style="font-family:Calibri, sans-serif;">Notes: Name:</span> Francois</p><p></p><p><span style="font-family:Calibri, sans-serif;">Person caller was forwarded to:</span> Glenn</p><p></p><p><span style="font-family:Calibri, sans-serif;">New Lead/Existing Customer:</span> New lead</p><p></p><p class="MsoNormal" style="margin-bottom:12pt;"><br /><br /><br /><br /></p><p></p></div>';

//echo $message;

//$message = preg_replace('/\<br(\s*)?\/?\>/i', PHP_EOL, $message);
//$message = str_replace( "<p></p>", PHP_EOL, $message ); 
//$message = str_replace( "Notes: Name:", "Notes:".PHP_EOL."Name:", $message );
//$message = strip_tags($message);

 
//echo '<br/>---------<br/>';
//var_dump($message);
//echo '<br/>---------<br/>';
	
	//global $db;
	
	
	//if( !$db->fetchByAssoc($db->query(" SELECT email_id FROM ax_egrab WHERE email_id = '49cd349d-a690-ce87-2630-55e6170d1d30'; ")) ){
	//	echo 'nope';
	//}
	/*
echo '<br/>---------<br/>';		
	$res = $db->query(" SELECT email_id FROM ax_egrab WHERE email_id = '49cd349d-a690-ce87-2630-55e6170d1d39'; ");
	if($row = $db->fetchByAssoc($res)){
		var_dump($row);
	}
echo '<br/>---------<br/>';	
	$res = $db->query(" SELECT COUNT(email_id) as cnt FROM ax_egrab WHERE email_id = '49cd349d-a690-ce87-2630-55e6170d1d39'; ");
	if($row = $db->fetchByAssoc($res)){
		var_dump($row);
	}
echo '<br/>---------<br/>';
	$res = $db->query(" SELECT COUNT(email_id) as cnt FROM ax_egrab WHERE email_id = '49cd349d-a690-ce87-2630-55e6170d1d30'; ");
	if($row = $db->fetchByAssoc($res)){
		var_dump($row);
	}
	*/
	die('<br/>Ready?<br/>');
	
	//require_once('custom/include/ax/axJob.php');

	//$processed = axJob::processCallMailbox();
	//var_dump($processed);

	//global $db;
	//$sql = " SELECT description_html FROM emails_text WHERE email_id = '194b1218-c9c1-2d43-0d44-55e86f88e240'; ";
	//$res = $db->query($sql);
	//if($row = $db->fetchByAssoc($res)){
		//var_dump($row);
		//$msg = html_entity_decode($row['description_html']);
			//$msg = html_entity_decode($message);
			//if( !empty($msg) ){
			//	$processed = axJob::extractData($msg);
			//	echo '<pre>'; print_r($processed); echo '</pre>';	
			//}
	//}
	die('<br/>-----------<br/>');
	
	
	//$processed = axJob::processEmailMsg($message);
	//echo '<pre>'; print_r($processed); echo '</pre>';
	//echo '<br/>';
	//echo '<br/><a href="index.php?module=Calls&action=DetailView&record='.$processed['call_id'].'">Call</a>';
	//echo '<br/>';

/*
		$processed['Date/Time:'] = '07/08/2015 1:44:39 PM';
	
		$original_TZ = 'EDT';//Toronto
		//echo '<br/>'.$date_time = date('Y-m-d H:i:s', strtotime($processed['Date/Time:']));

		//$date = new DateTime( $processed['Date/Time:'], $original_TZ );
		//$date = DateTime::createFromFormat('d/m/Y h:i:s a', $processed['Date/Time:'], 'America/Toronto');
		$date = DateTime::createFromFormat('d/m/Y h:i:s a', $processed['Date/Time:'], new DateTimeZone('America/Toronto') );
		//$date = DateTime::createFromFormat('d/m/Y h:i:s a', $processed['Date/Time:'], new DateTimeZone('EDT') );
		echo '<br/>'.$date->format('Y-m-d H:i:s');
		
		$date->setTimezone( new DateTimeZone( "GMT" ) );
		echo '<br/>'.$date->format('Y-m-d H:i:s');
		
*/	
	
/*


	$processed = axJob::extractData($message);
echo '<pre>'; print_r($processed); echo '</pre>';
	//if( !empty($processed['Caller:']) ){}
	$date_time = date('Y-m-d H:i:s', strtotime($processed['Date/Time:']));
echo '<br/>'.$date_time;

	$bean_list = axJob::searchPhone($processed['Caller:']);
echo '<pre>'; print_r($d); echo '</pre>';

	foreach($bean_list as $bean_type => $items){
		echo '<br/>'.$bean_type;
		foreach($items as $item_id => $item_arr){
			echo '<br/>'.$item_id;
			echo '<br/>'.$item_arr['name'];
		}
	}
	
	$param = array(
		'parent_type' => '',
		'parent_id' => '',
		'description' => $processed['notes'],//.' DATEtime:'.$date_time,
		'date_start' => $date_time,
		'name' => $processed['Name:'],
	);
	
	if(!empty($d['Accounts'])){
		$param['parent_type'] = 'Accounts';
		$param['parent_id'] = $d['Accounts'][0]['id'];
	}
	if(!empty($d['Contacts'])){
		$param['parent_type'] = 'Contacts';
		$param['parent_id'] = $d['Contacts'][0]['id'];
	}

	$call_id = axJob::attachCall($param);

	if( !empty($call_id) ){
		echo '<br/><a href="index.php?module=Calls&action=DetailView&record='.$call_id.'">Call</a>';
	}

*/
	
//$d = axJob::searchPhone($processed['Caller:']);
//echo '<pre>';
//print_r($d);
//echo '</pre>';


//$separator = in_array($separator, array("\n", "\r", "\r\n", "\n\r", chr(30), chr(155), PHP_EOL)) ? $separator : PHP_EOL;
//preg_replace('/\<br(\s*)?\/?\>/i', $separator, $string);


die('<br/>Locked!<br/>');



// $sql = " SELECT * FROM emails_text WHERE to_addrs = 'calls@bondsurety.ca'; ";


		$hostname = '{imap.gmail.com:993/imap/ssl}INBOX';//TODO:addToSettings
		$username = 'calls@bondsurety.ca';//TODO:addToSettings
		$password = '7nfZrDYk';//TODO:addToSettings
//$last_run = 'Tue, 1 Sep 2015 01:58:04 -0400 (EDT)';
$last_run = 'Tue, 1 Sep 2015 01:58:04 -0400 (EDT)';
		$inbox = imap_open($hostname, $username, $password) or die('Cannot connect to Gmail: ' . imap_last_error());
		$current_run = '';
		$imap_obj = imap_check($inbox);
		$current_run = $imap_obj->Date;
		$criteria = '';
		if( !empty($last_run) ){
			$criteria = 'SINCE "'.$last_run.'"  UNDELETED';//UNSEEN
		}else{
			$criteria = 'ALL UNDELETED';
		}
		$GLOBALS['log']->fatal("grabEmail criteria:".$criteria);
		$emails = imap_search($inbox, $criteria, SE_UID);//, "UTF-8"
		if($emails){
			rsort($emails);
			foreach($emails as $email_number){
				$structure = imap_fetchstructure($inbox, $email_number, FT_UID);
echo '<br/>------------------------';
echo '<pre>'; print_r($structure); echo '</pre>';
echo '<br/><b>'.$email_number.'</b>';
				$overview = imap_fetch_overview($inbox, $email_number);
				$message = imap_fetchbody($inbox, $email_number, 1);
				//$r = imap_setflag_full($inbox, $email_number, '\\SEEN', ST_UID );//ST_UID for uid
echo '<br/>'.$message;
			}
		}


















//require_once('custom/include/ax/axJob.php');
//axJob::grabCallMailbox();
	
die('<br/>+<br/>');
	
	
$message = 'Date/Time: 8/20/2015 4:20:10 PM

Caller: 9051234567

Notes: Bla-bla-bla
this line will appear as the next release feature
third line

Time of Call - 3:20pm

Caller Number - 905-123-4567';

$processed = axJob::extractData(explode("\n", $message));
if(!empty($processed['Caller:'])){
	$date_time = date('Y-m-d H:i:s', strtotime($processed['Date/Time:']));

	$param = array(
		'parent_type' => 'Accounts',
		'parent_id' => 'a17b4457-b02c-8ff0-b1eb-5581a25b9256',
		'description' => $processed['notes'].' DATEtime:'.$date_time,
		'date_start' => $date_time,
		'name' => 'test',
	);

	echo '<pre>';print_r($param);echo '</pre>';

	/*$d = searchPhone($processed['Caller:']);
	if(!empty($d['Accounts'])){
		$param['parent_type'] = 'Accounts';
		$param['parent_id'] = $d['Accounts'][0]['id'];
	}
	if(!empty($d['Contacts'])){
		$param['parent_type'] = 'Contacts';
		$param['parent_id'] = $d['Contacts'][0]['id'];
	}*/

	//echo '<br/>callID:'.$call_id = axJob::attachCall($param);
}
					
					
die('<br/>+<br/>');					
					
global $db;

$input = array(
	'+1-647-969-0823',//Alandrino Verdillo
	'905-450-6799',//Vivid Signs and Graphics Inc.
	'1713-8184580',// Frank Salveski OA Silver Strea
	'(416) 997-1585'//The Right Way Customs Consultant And Brokerage
);

$input = array(
	'6479690823',
	'9054506799',
	'7138184580',
	'4169971585'
);


require_once('custom/include/ax/axJob.php');

foreach($input as $i => $phone){
	echo '<br/><br/>'.$phone;
	$data = axJob::searchPhone($phone);
	echo '<pre>';print_r($data);echo '</pre>';
}

die('<br/>+<br/>');

//SELECT '+1-234-5678' REGEXP '[+1]?[-]?2[-]?3[-]?4[-]?5[-]?6[-]?7[-]?8'
// [+]?
// [-]? 
// [[.space.]]*
//2[-]? 3[-]? 4[-]? 5[-]? 6[-]? 7[-]? 8


foreach($input as $i => $phone){
	echo '<br/><br/>'.$phone;
	$reg = '';
	//$reg .= '^';
	//$reg .= '([+]?|[1]?)?';//^(([+]?)|([+1]?))2$
	$ln = strlen($phone);
	
	$removed_first = false;
	
	$num = -1;
	//while($ln--){$num++;
	while($num++ <= $ln){
		
		//echo '<br/>'.$phone[$num];
		//if(!empty($phone[$num])){
			if(is_numeric($phone[$num])){
				if(!$removed_first){
					$removed_first = true;
					if($phone[$num] == 1 ){
						continue;//skipping first digit '1';
					}
				}
				$reg .= '[[.space.]]*[(]?[)]?[[.space.]]*[-]?[[.space.]]*'.$phone[$num];
				echo '<br/>'.$num.'/'.$ln.'|  '.$phone[$num];
			}
		//}
		
		//$len--;
	}
	
	/*
	foreach($phone as $in => $num){
		echo '<br/>'.$num;
		if(!empty($num)){
			if(is_numeric($num)){
				$reg .= '[[.space.]]*[-]?[[.space.]]*'.$num;
			}
		}
	}
	*/
	$reg .= '[[.space.]]*$';
	//echo '<br/> REGEXP '.$reg;
/*	
	echo '<br/>SQLacc:<br/>'.$sql = " SELECT id, name FROM accounts WHERE phone_office REGEXP '{$reg}' ;";
	$res = $db->query($sql);
	while($row = $db->fetchByAssoc($res)){
		echo '<pre>';print_r($row);echo '</pre>';
	}	
	echo '<br/>SQLcon:<br/>'.$sql = " SELECT id, last_name, first_name FROM contacts WHERE phone_mobile REGEXP '{$reg}' OR phone_work REGEXP '{$reg}' ;";
	$res = $db->query($sql);
	while($row = $db->fetchByAssoc($res)){
		echo '<pre>';print_r($row);echo '</pre>';
	}
*/	
	
}

	//$aPhoneNumber = '+1-888-888-8888';
	//echo '<br/>'.$aPhoneNumber = preg_replace('/\D/', '', $aPhoneNumber);
    //$regje = preg_replace('/(\d)/', '$1\[^\\d\]*', $aPhoneNumber);
    //echo '<br/>'.$regje = '(' . $regje . ')$';

die('<br/>+<br/>');

/*
	require_once('custom/include/ax_jobs.php');
	$result = false;
	$result = ax_jobs::runOpenLeadReminder();
	$output.=  '<br/>';
	var_dump($result);

	die('+');
*/

require_once('custom/include/ax/axJob.php');

$message = 'Date/Time: 8/20/2015 4:20:10 PM

Caller: 9051234567

Notes: Bla-bla-bla
this line will appear as the next release feature
third line

Time of Call - 3:20pm

Caller Number - 905-123-4567';


$input_arr = explode("\n", $message);

$processed = axJob::extractData($input_arr);

//$processed = axJob::searchPhone($input_arr);

echo '<pre>';
print_r($processed);
echo '</pre>';

//echo '<br/>Date: '.date('Y-m-d H:i:s', $processed['Date/Time:']);
echo '<br/>Date: '.date('Y-m-d H:i:s', strtotime($processed['Date/Time:']));
echo '<br/>Caller: '.$processed['Caller:'];

//array('Date/Time:', 'Caller:', 'Notes:', 'Time of Call -', 'Caller Number -');
/*
$call = BeanFactory::newBean('Calls');
$call->subject = 'debug graber "Call"';
$call->direction = 'Inbound';
$call->status = 'Held';

$call->parent_type = '';
$call->parent_id = '';

$call->description = '';
$call->assigned_user_id = '3c4e92c9-c147-2849-0c10-54f4b2c4a466';
$call->save(false);
*/

die('<br/>+<br/>');





$hostname = '{imap.gmail.com:993/imap/ssl}INBOX';//INBOX
$username = 'ross@bondsurety.ca';
$username = 'calls@bondsurety.ca';
$password = 'pe55xcO9uhbf9MqUCAFD';
$password = '7nfZrDYk';

$inbox = imap_open($hostname, $username, $password) or die('Cannot connect to Gmail: ' . imap_last_error());

$imap_obj = imap_check($inbox);
echo '<pre>';
print_r($imap_obj);
echo '</pre>';
echo '<br/>Next time check since '.$imap_obj->Date;

$emails = imap_search($inbox, 'SINCE "Mon, 24 Aug 2015 08:07:46 -0400 (EDT)" UNDELETED', SE_UID);//ALL  UNSEEN , "UTF-8"

if($emails) {
	$output = '';
	rsort($emails);

	foreach($emails as $email_number) {
		$overview = imap_fetch_overview($inbox, $email_number);
		$message = imap_fetchbody($inbox,$email_number,1);
		$output =  '<br/><br/>['.$email_number.']##############################################################';
		$output.=  	'###################################################################################################';
		$output.=  '<br/>';
		$output.=  '<br/>SUBJ:'.$overview[0]->subject;
		$output.=  '<br/>FROM:'.$overview[0]->from;
		$output.=  '<br/>DATE:'.$overview[0]->date;
		$output.=  '<br/>MSGNO:'.$overview[0]->msgno;
		$output.=  '<br/>UID:'.$overview[0]->uid;
		$output.=  '<br/>uDATE:'.$overview[0]->udate;
		$output.=  '<br/>'.gmdate('Y-m-d H:i:s',$overview[0]->udate);
		$output.=  '<br/>'.date('Y-m-d H:i:s',$overview[0]->udate);
		$output.=  '<br/>';
		$output.=  '==================================================================================================';
		$output.=  '<br/>'.nl2br($message);
		$output.= '<br/>=====================================================================================';
		
		$lines = explode("\n", $message);
		
		$processed = extractData($lines);
		
		if(!empty($processed['Date/Time:'])){
			echo '<br/><span style="color:green;"><b>VALID</b></span><br/>';
		}else{
			echo '<br/><b>NOT valid</b><br/>';
		}
		echo  '<pre>';
		print_r($processed);
		echo  '</pre>';		
		echo $output;
	}
} 

//$output.=  '<pre>';
//print_r($inbox);
//$output.=  '</pre>';

imap_close($inbox);
//imap_expunge($ieX->conn);
//imap_close($ieX->conn, CL_EXPUNGE);

//$storedOptions = unserialize(base64_decode($this->stored_options));
//$this->stored_options = base64_encode(serialize($storedOptions));
		
		
function extractData($input_arr){
	$data = array();
	$fields = array('Date/Time:', 'Caller:', 'Notes:', 'Time of Call -', 'Caller Number -');
	
	$tmp = '';
	foreach($input_arr as $line){
		foreach($fields as $itep){
			if(!empty($line)){
				if(strpos($line, $itep) !== false){
					$tmp = str_replace($itep ,'',$line);
					$output.=  '<br><b>'.$itep.'</b>:'.$tmp;
					$data[$itep] = $tmp;
				}
			}
		}
	}
	
	return $data;
	
}

//INVOICE NUMBER: AI150012


//global $db;

//require_once('custom/hooks/policyfunc.php');

$output.=  '<br/>+<br/>';

//$account_code = 'TESTRRR';
//$account_id = 'c38101b0-6066-0c95-07b8-55900b954d0f';
//updPoliciesAccCode($account_id, $account_code);

/*


$sql = " SELECT a.id_c as policy_id FROM aos_contracts_cstm as a
LEFT JOIN aos_contracts as b ON a.id_c = b.id
WHERE a.acc_code_c = '' OR a.acc_code_c IS NULL AND b.deleted = 0  ; ";
$res = $db->query($sql);
$i = 0;
while($row = $db->fetchByAssoc($res)){

	$policy = BeanFactory::getBean('AOS_Contracts', $row['policy_id']);
	$output.=  '<br/>'.$i++;
	$output.=  '---'.$policy->name.' Acc_id:'.$policy->contract_account_id;
	if(!empty($policy->contract_account_id)){
		setPolicyAccCode($policy->contract_account_id, $policy->id);
	}
}
*/

//print_r($row);

//setPolicyAccCode('6caee717-35a6-51ce-8049-54fdc0e20de4','bc900f2a-8d59-942c-6fe6-55ce3a2421b8');


/*
global $db;
require_once('custom/hooks/accfunc.php');

$i = 0;
$output.=  $sql = " SELECT a.id, a.date_entered, a.name 
FROM accounts as a
LEFT JOIN accounts_cstm as cc ON a.id = cc.id_c
WHERE a.deleted = 0 
AND cc.account_code_c IS NULL
ORDER BY a.date_entered ASC; ";
$res = $db->query($sql);
while($row = $db->fetchByAssoc($res)){
	$output.=  '<br/>';
	$output.=  ($i++).') '.$row['id'].' | '.$row['date_entered'];
	
	$code = genAccCode($row['name']);
	$d = updAccCode($row['id'], $row['name'], $code['base'], $code['n']);
	
	$output.=  ' | '.$row['name'].' | '.$code['base'].$code['n'];

}
*/
$output.=  '<br/>+<br/>';
