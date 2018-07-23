<?php

class DistribLead{
/*
	static public function sendRemindNotify($bean, $subj, $body, $data = array()){
		if( empty($data) ){
			$data = self::getExtractedDistribData($bean->coverage_type_c, $bean->primary_address_state);
		}
		if( empty($data) ){
			return false;
		}
		
	}*/

	static public function sendAssignNotify($bean, $data = array()){
		if( empty($data) ){
			$data = self::getExtractedDistribData($bean->coverage_type_c, $bean->primary_address_state);
		}
		if( empty($data) ){
			return false;
		}
//echo '<pre>';print_r($data);echo '</pre>';
		global $sugar_config;
		global $current_user;
		global $beanList;
		global $locale;
		$OBCharset = $locale->getPrecedentPreference('default_email_charset');
		
		$notify_address = '';
		$notify_name = '';
		
        if(empty($_SESSION['authenticated_user_language'])) {
            $current_language = $sugar_config['default_language'];
        } else {
            $current_language = $_SESSION['authenticated_user_language'];
        }		
		$xtpl = new XTemplate(get_notify_template_file($current_language)); 
		$xtpl = $bean->set_notification_body($xtpl, $bean); 
        //$xtpl->assign("ASSIGNED_USER", $bean->new_assigned_user_name);
        /*if( !empty($data['primaryUser']) ){
    	$userDetails = BeanFactory::getBean('Users', $data['primaryUser']);
    	$xtpl->assign("ASSIGNED_USER", $userDetails->full_name); 
        }else{
        $xtpl->assign("ASSIGNED_USER", $bean->assigned_user_name);
    	}*/
    	if (!empty($bean->assigned_user_id)) {
    		$userDetails = BeanFactory::getBean('Users', $bean->assigned_user_id);
    		$xtpl->assign("ASSIGNED_USER", $userDetails->full_name);
    	} else {
    		return false;
    	}
        $xtpl->assign("ASSIGNER", $current_user->name);	
		$template_name = $beanList[$bean->module_dir]; 
		
		$parsedSiteUrl = parse_url($sugar_config['site_url']);
        $host = $parsedSiteUrl['host'];
        if(!isset($parsedSiteUrl['port'])) {
            $parsedSiteUrl['port'] = 80;
        }

        $port		= ($parsedSiteUrl['port'] != 80) ? ":".$parsedSiteUrl['port'] : '';
        $path		= !empty($parsedSiteUrl['path']) ? $parsedSiteUrl['path'] : "";
        $cleanUrl	= "{$parsedSiteUrl['scheme']}://{$host}{$port}{$path}";
        $xtpl->assign("LEAD_SOURCE", $bean->lead_source);
        $xtpl->assign("URL", $cleanUrl."/index.php?module={$bean->module_dir}&action=DetailView&record={$bean->id}");
        $xtpl->parse($template_name);
        $xtpl->parse($template_name . "_Subject");

		require_once('include/SugarPHPMailer.php');
		$mail = new SugarPHPMailer();
		$mail->setMailerForSystem();
		$mail->From = "no-reply@bondsurety.ca";
		$mail->FromName = "no-reply";
		$mail->ContentType="text/html";
		$mail->IsHTML(true);		
		$mail->ClearAllRecipients();
		
		$main_user_id = '';
		//if( !empty($data['primaryUser']) ){
		//	$main_user_id = $data['primaryUser'];
		//}else{
			$main_user_id = $bean->assigned_user_id;
		//} 
		if( !empty($main_user_id) ){
			$user = BeanFactory::getBean('Users', $main_user_id); 
			if( $user->status == 'Inactive' ){return false;}
			$notify_address = $user->emailAddress->getPrimaryAddress($user);	
			//echo '+TO:'.$notify_address;
			$mail->AddAddress($notify_address, $locale->translateCharsetMIME(trim($user->name), 'UTF-8', $OBCharset));
		}else{
			return false;//missed main user
		}
/*		if(!empty($data['secondaryUsers']) && is_array($data['secondaryUsers']) ){
			foreach($data['secondaryUsers'] as $i => $user_id){
				$user = BeanFactory::getBean('Users', $user_id);
				if( $user->status == 'Inactive' ){continue;}
				$u_address = $user->emailAddress->getPrimaryAddress($user);
				if( !empty($u_address) ){
					//echo '+CC:'.$u_address;
					$mail->AddCC($u_address, $locale->translateCharsetMIME(trim($user->name), 'UTF-8', $OBCharset));
				}
			}
		}*/
		$newline = "<br/>";
        //echo '</br>'.
		$mail->Body = from_html(trim($xtpl->text($template_name)));
		$mail->Body .= $newline; 
		//$mail->Body .= $newline.'Accept';
		$accept  = rtrim($sugar_config['site_url'], '/') . '/index.php?entryPoint=ax&bid=' . $bean->id . '&a=accept&uid=' . $main_user_id;
		$decline = rtrim($sugar_config['site_url'], '/') . '/index.php?entryPoint=ax&bid=' . $bean->id . '&a=decline&uid=' . $main_user_id;
		$mail->Body .= $newline.'<a href="'.$accept.'" style="border: 1px solid #000;
padding: 1px 10px;
text-decoration: none;
color: #000;">Accept</a>';
		$mail->Body .= $newline;
		//$mail->Body .= $newline.'Decline';
		$mail->Body .= $newline.'<a href="'.$decline.'" style="border: 1px solid #000;
padding: 1px 10px;
text-decoration: none;
color: #000;">Decline</a>';
		$mail->Body .= $newline;
		$mail->Body .= $newline.'Thank you,';
		$mail->Body .= $newline; 
        //echo '</br>'.
		$mail->Subject = from_html($xtpl->text($template_name . "_Subject"));
		$success = false;
		$success = $mail->Send();
		if(!$success){
			$result = false;
			$GLOBALS['log']->fatal("HOT Lead Remainder FAILURE: ");
		} else {
			$GLOBALS['log']->debug("HOT Lead Remainder SUCCESS: ");
			$result = true;
		}
		if($mail->isError()){
			$result = false;
			$GLOBALS['log']->fatal("sendNotify error: ".$mail->ErrorInfo);
		}
		return $result;
	}

	static public function saveDistribData($raw_json){
		$result = false;
		if(!empty($raw_json)){
			$administration = BeanFactory::getBean('Administration');
			$administration->retrieveSettings('distrib');
			$administration->saveSetting('distrib', 'type_state', htmlspecialchars_decode($raw_json));
			$result = true;
		}
		return $result;
	}
	static public function saveDistribDefaultData($user){
		$result = false;
		if(!empty($user)){
			$administration = BeanFactory::getBean('Administration');
			$administration->retrieveSettings('distrib');
			$administration->saveSetting('distrib', 'default_user', $user);
			$result = true;
		}
		return $result;
	}
	static public function saveReminderData($raw_json){
		$result = false;
		if(!empty($raw_json)){
			$administration = BeanFactory::getBean('Administration');
			
			$administration->retrieveSettings('reminder');

			$administration->saveSetting('reminder', 'second', htmlspecialchars_decode($raw_json));
			$result = true;
		}
		return $result;
	}
	static public function getExtractedDistribData($type, $state = ''){
		$data = self::getDistribData();
		return self::extractData($data, $type, $state);
	}
	static public function getDistribData($raw_json = false){
		$administration = BeanFactory::getBean('Administration'); 
		$administration->retrieveSettings('distrib'); 
		$json = $administration->settings['distrib_type_state'];
		if($raw_json){
			return $json;
		}else{
			return json_decode(htmlspecialchars_decode($json), true);
		}
	}

	static public function getDistribDefaultData($raw_json = false){
		$administration = BeanFactory::getBean('Administration'); 
		$administration->retrieveSettings('distrib'); 
		$json = $administration->settings['distrib_default_user'];
		if($raw_json){
			return $json;
		}else{
			return json_decode(htmlspecialchars_decode($json), true);
		}
	}

	static public function extractData($data, $type, $state = ''){
		foreach($data as $key => $value){ 
		foreach($value['contacts'] as $i => $p){
			if( !empty($state) ){
				# Added by HK for province field changes (Multiple values are coming)
				if( ($p['type'] == $type) && in_array($state,$p['state'])){
				//if( ($p['type'] == $type) && ($p['state'] == $state) ){
					return $p;
				}
			}else{
				if( $p['type'] == $type ){
					return $p;
				}
			}
		}
		}
		//if no match select only by type
		foreach($data as $key => $value){ 
		foreach($value['contacts'] as $i => $p){
			if( $p['type'] == $type && empty($p['state']) ){
				return $p;
			}
		}
	}
		return false;
	}
	static public function getReminderData($raw_json = false){
		$administration = BeanFactory::getBean('Administration');
		$administration->retrieveSettings('reminder');
		$json = $administration->settings['reminder_second'];
		if($raw_json){
			return $json;
		}else{
			return json_decode(htmlspecialchars_decode($json), true);
		}
		
		
	}

}

?>