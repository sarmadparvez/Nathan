<?php

class axJob{
	//d7d7d5b5-ba32-9779-e6d6-545039a192bd //"Don" user
	public static $create_by_user_id = '81d17438-71ef-1412-fd3c-55fbdc58b911';//Call
	public static $default_user_id = 'c3cb11db-2d71-affe-67a7-556ddb378a64';//Purav
	
	static public function processCallMailbox(){
		global $db;
		
		$mailbox_id = '49cd349d-a690-ce87-2630-55e6170d1d39';
		
		//$q = "DELETE FROM email_cache WHERE imap_uid = {$uid} AND ie_id = '{$this->id}' AND mbox = '{$this->mailbox}'";
		$sql = " SELECT * FROM emails as e 
		LEFT JOIN emails_text as et ON et.email_id = e.id
		LEFT JOIN ax_egrab as eg ON eg.email_id = e.id
		WHERE e.mailbox_id = '{$mailbox_id}' AND e.deleted = 0 AND eg.email_id IS NULL; ";
		$res = $db->query($sql);
		while( $row = $db->fetchByAssoc($res) ){
			if( !empty($row['description_html']) ){
				if( !$db->fetchByAssoc($db->query(" SELECT email_id FROM ax_egrab WHERE email_id = '{$row['id']}'; ")) ){//Check if havent been already processed 
					$params = self::processEmailMsg($row['description_html']);
					if( !empty($params['call_id']) ){
						$sql = " INSERT INTO ax_egrab (email_id, ext_data, bean_type, bean_id, call_id) VALUES ('{$row['id']}', '{$params['description']}', '{$params['parent_type']}', '{$params['parent_id']}', '{$params['call_id']}');  ";
//$GLOBALS['log']->fatal("grabEmail SQL ".$sql);
						$db->query($sql);
					}else{
						$sql = " INSERT INTO ax_egrab (email_id, ext_data, bean_type, bean_id, call_id) VALUES ('{$row['id']}', '', '', '', '');  ";//disable for further
//$GLOBALS['log']->fatal("grabEmail SQL ".$sql);
						$db->query($sql);				
					}
				}
			}
		}
		
		return true;
	}
	
	static public function addLead($param){

		$lead = BeanFactory::newBean('Leads');
		$lead->status = 'New';
		
		$name_tmp = trim($param['name']);
		$name_arr = explode(" ", $name_tmp);
		if( isset($name_arr[1]) && !empty($name_arr[1]) ){
			$lead->first_name = $name_arr[0];
			$lead->last_name = $name_arr[1];
		}else{
			$lead->last_name = $name_tmp;
		}
		$lead->lead_source = 'IncomingCall';//!no space as its key
		$lead->phone_work = $param['phone'];//!
		$lead->description = $param['description'];
		//$lead->process_save_dates = false;
		$lead->assigned_user_id = $param['assigned_user_id'];
		if(empty($lead->assigned_user_id)){
			$lead->assigned_user_id =  self::$default_user_id; ;
		}
		$lead->set_created_by = false;
		$lead->created_by = self::$create_by_user_id;
		$lead->save(false);

		return $lead->id;
	}

	static public function processEmailMsg($message){
		global $sugar_config;
		
		$param = array(
			'parent_type' => '',
			'parent_id' => '',
			'description' => '',
			'date_time' => '',
			'g_date_time' => '',
			'name' => '',
			'call_id' => '',
			'phone' => '',
			'assigned_user_id' => '',
			'phone_match' => array(),
		);
		
		$processed = self::extractData($message);
//echo '<pre>'; print_r($processed); echo '</pre>';
		if( !empty($processed['Caller:']) && !empty($processed['Date/Time:']) ){
		//if(!empty($processed['Caller:'])){
		
			$param['phone'] = $processed['Caller:'];
			$param['name'] = $processed['Name:'];
			
			$date = DateTime::createFromFormat('m/d/Y h:i:s a', $processed['Date/Time:'], new DateTimeZone('America/Toronto') );//or 'EDT'
			$param['date_time'] = $date->format('Y-m-d H:i:s');
			
			$date->setTimezone( new DateTimeZone( "GMT" ) );
			$param['g_date_time'] = $date->format('Y-m-d H:i:s');
			
			$param['phone_match'] = self::searchPhone($param['phone']);
			
			//phone format - begin
			$phone_f = preg_replace("/\D*/", "", $processed['Caller:']);
			if( (strlen($phone_f) == 10 ) || (strlen($phone_f) == 11 && substr($phone_f, 0, 1) == '1') ){
				$param['phone'] = preg_replace("/1?(\d\d\d)(\d\d\d)(\d\d\d\d)/","+1-$1-$2-$3" , $phone_f);
			}
			//phone format - end			
			
			$param['assigned_user_id'] = axJob::detectUser($processed['Person caller was forwarded to:']);
			
			if(!empty($param['phone_match']['Accounts'])){
				$param['parent_type'] = 'Accounts';
				$param['parent_id'] = $param['phone_match']['Accounts'][0]['id'];
			}elseif(!empty($param['phone_match']['Contacts'])){
				$param['parent_type'] = 'Contacts';
				$param['parent_id'] = $param['phone_match']['Contacts'][0]['id'];
			}else{
				$param['parent_type'] = 'Leads';
				if($processed['isNewLead']){
					$param['parent_id'] = self::addLead($param);
				}
			}

			$related_beans_txt = '';
			$skip_first = true;
			foreach($param['phone_match'] as $bean_type => $items){
				foreach($items as $item_id => $item_arr){
					if($skip_first){ $skip_first = false; continue; }
					$related_beans_txt .= "\n".$bean_type.' ';
					$related_beans_txt .= $item_arr['name'];
					$related_beans_txt .= "\n".$sugar_config['site_url'].'/index.php?module='.$bean_type.'&action=DetailView&record='.$item_arr['id'];
				}
			}
			if( !empty($related_beans_txt) ){
				$related_beans_txt = "\n"."Additional phone match:".$related_beans_txt;
			}
			$param['description'] = $processed['notes'].$related_beans_txt;
			
			

			$param['call_id'] = self::attachCall($param);
		}

		return $param;
	}

	static public function detectUser($msg){//'Person caller was forwarded to:'
		global $db;
		$user_id = '';
		$name = preg_replace("/[^a-zA-Z]/", "", $msg);
		$name = $db->quote($name);
		if(!empty($name)){
			$sql = " SELECT id FROM users WHERE first_name like '{$name}%' AND deleted = 0 ORDER BY date_entered ASC ";
			$res = $db->query($sql);
			if($row = $db->fetchByAssoc($res)){
				if(!empty($row['id'])){
					$user_id = $row['id'];
				}
			}
		}
		$GLOBALS['log']->fatal("grabEmail detectUser:".$sql.' userID:'.$user_id);		
		return empty($user_id)?self::$default_user_id:$user_id;
	}
	
	static public function grabCallMailbox($debug = false){
		global $db, $sugar_config;
		
		//$GLOBALS['log']->fatal("grabEmail RUNTIME start:".date('Y-m-d H:i:s'));
		
		require_once('modules/Administration/Administration.php');
		$focus = new Administration();
		$category = 'email2call';
		$key = 'last_run';
		$setting_item = $category.'_'.$key;

		$focus->retrieveSettings($category);

		$last_run = $focus->settings[$setting_item];

		$hostname = '{imap.gmail.com:993/imap/ssl}INBOX';//TODO:addToSettings
		$username = 'calls@bondsurety.ca';//TODO:addToSettings
		$password = '7nfZrDYk';//TODO:addToSettings

		$inbox = imap_open($hostname, $username, $password) or die('Cannot connect to Gmail: ' . imap_last_error());
		$current_run = '';
		$imap_obj = imap_check($inbox);
		$current_run = $imap_obj->Date;
		$criteria = '';
		if( !empty($last_run) ){
			$criteria = 'SINCE "'.$last_run.'" UNSEEN UNDELETED';
		}else{
			$criteria = 'ALL UNDELETED';
		}
		//$GLOBALS['log']->fatal("grabEmail criteria:".$criteria);
		$emails = imap_search($inbox, $criteria, SE_UID);
		
		$focus->saveSetting($category, $key, $current_run);
		
		if($emails){
			rsort($emails);
			foreach($emails as $email_number){
				$overview = imap_fetch_overview($inbox, $email_number);
				$message = imap_fetchbody($inbox, $email_number, 1);
				
				$processed = self::processEmailMsg($message);
			}
		}

		imap_close($inbox);
		//imap_expunge($ieX->conn);
		//imap_close($ieX->conn, CL_EXPUNGE);		
		//$GLOBALS['log']->fatal("grabEmail RUNTIME stop:".date('Y-m-d H:i:s'));
	}


	static public function searchPhone($phone){
		//$GLOBALS['log']->fatal("grabEmail searchPhone: ".$phone);
		
		global $db;
		$reg = '';
		$phone = trim($phone);
		$ln = strlen($phone);
		if($ln >= 10){
			$removed_first = false;
			$num = 0;
			while($num < $ln){
				if(is_numeric($phone[$num])){
					if(!$removed_first){
						$removed_first = true;
						if($phone[$num] == 1 ){
							continue;//skipping first digit '1';
						}
					}
					$reg .= '[[.space.]]*[(]?[)]?[[.space.]]*[-]?[[.space.]]*'.$phone[$num];
				}
				$num++;
			}
			$reg .= '[[.space.]]*$';
		}
		if(!empty($reg)){
			$data = array();
			$sql = " SELECT id, name FROM accounts WHERE phone_office REGEXP '{$reg}' AND deleted = 0;";
			$res = $db->query($sql);
			while($row = $db->fetchByAssoc($res)){
				$data['Accounts'][] = array('id' => $row['id'], 'name' => $row['name']);
			}
			if(empty($data)){
				$sql = " SELECT id, last_name, first_name FROM contacts WHERE phone_mobile REGEXP '{$reg}' OR phone_work REGEXP '{$reg}' AND deleted = 0;";
				$res = $db->query($sql);
				while($row = $db->fetchByAssoc($res)){
					$data['Contacts'][] =  array('id' => $row['id'], 'name' => $row['first_name'].' '.$row['last_name']);
				}
			}
		}
		
		return $data;
	}

	static public function attachCall($param){
		//$GLOBALS['log']->fatal("grabEmail attachCall date_start:".$param['date_time']);
		
		$call = BeanFactory::newBean('Calls');
		if( isset($param['name']) && !empty($param['name']) ){
			$call->name = $param['name'];
		}else{
			$call->name = 'From "calls@"';
		}
		$call->direction = 'Inbound';
		$call->status = 'Held';
		
		//$call->date_start =  $param['date_time'];
		$call->date_start =  $param['g_date_time'];


		$call->parent_type = $param['parent_type'];
		$call->parent_id = $param['parent_id'];

		$call->description = $param['description'];
	
		$call->assigned_user_id = $param['assigned_user_id'];
		if(empty($call->assigned_user_id)){
			$call->assigned_user_id = self::$default_user_id;
		}
		$call->set_created_by = false;
		$call->created_by = self::$create_by_user_id;
		
		$call->process_save_dates = false;
		
		$call->save(false);
		
		return $call->id;
	}

	static public function extractData($message){
		//echo '##'.$message.'##';
		$message = html_entity_decode($message);
		$message = preg_replace('/\<br(\s*)?\/?\>/i', PHP_EOL, $message);
		$message = str_replace( "<p></p>", PHP_EOL, $message );//fix
		$message = str_replace( "Notes: Name:", "Notes:".PHP_EOL."Name:", $message );//fix
		$message = strip_tags($message);
		
		$input_arr = explode("\n", $message);

		
		$fields = array('Date/Time:', 'Caller:', 'Notes:', 'Time of Call -', 'Caller Number -', 'Name:', 'Person caller was forwarded to:', 'New Lead/Existing Customer:');
		$data = array();
		$data['isNewLead'] = false;
		$pos = stripos($message, 'new lead');
		if ($pos !== false) {
			$data['isNewLead'] = true;
		}
		$tmp = '';
		$note_start = 0;
		$note_lines = '';
		$begin_tag = 'Date/Time:';
		$end_tag = 'New Lead/Existing Customer:';
		foreach($input_arr as $line){
			if(!empty($line)){
				if(strpos($line, $begin_tag) !== false){$note_start = 1;}
				if($note_start){$note_lines .= $line."\r\n";}
				if(strpos($line, $end_tag) !== false){$note_start = 0;}
				foreach($fields as $itep){
					if(strpos($line, $itep) !== false){
						$tmp = str_replace($itep ,'', $line);
						$data[$itep] = trim($tmp);
					}
				}
			}
		}
		$data['notes'] = $note_lines;
		return $data;
	}

}