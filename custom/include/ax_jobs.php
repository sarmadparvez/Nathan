<?php

class ax_jobs {
	
	static public function leadTimeClose(){
		/*
		global $db;
		
		require_once('custom/hooks/leadfunc.php');
		
		$time2close_c = 1;
		
		$sql = " SELECT c.id_c, c.date_open_c ";
		$sql .= " FROM leads_cstm as c ";
		$sql .= " LEFT JOIN leads as a ";
		$sql .= " WHERE a.deleted = 0 AND c.date_clode_c = '' AND c.date_open_c <> '' ";//add condition to avoid today upd
		$res = $db->query($sql);
		while( $row = $db->fetchByAssoc($res) ){
			$diff_d = leadfunc::get_time_difference($bean->date_open_c, date('Y-m-d H:i:s'));
			if( isset($diff_d['days']) && !empty($diff_d['days']) ){
				$time2close_c = $diff_d['days'] +1;	
			}
			$upd = " UPDATE leads_cstm SET time2close_c = '{$time2close_c}' WHERE id_c = '{$row['id_c']}' ";//mark time upd
		}
		*/

	}
	
	static public function oppRenewal(){
		//http://crm.bondsurety.ca/index.php?module=Home&action=renewal
		global $db;
			
		$date = new DateTime();
		$date->modify('+1 month');
		$start_date = $date->format('Y-m-01');
		$end_date = $date->format('Y-m-t');

		$sql = " SELECT a.id ";//, a.name, a.end_date, o.suret_policy_id_c
		$sql .= " FROM aos_contracts as a ";
		$sql .= " LEFT JOIN opportunities_cstm as o ON o.suret_policy_id_c = a.id ";
		$sql .= " WHERE a.deleted = 0 AND a.end_date >= '{$start_date}' AND a.end_date <= '{$end_date}' AND o.suret_policy_id_c IS NULL ; ";//ORDER BY a.end_date ASC
		
		$res = $db->query($sql);
		while( $row = $db->fetchByAssoc($res) ){
			$policy = BeanFactory::getBean('AOS_Contracts', $row['id']);
			if( !empty($policy->id) ){
				$opp = BeanFactory::newBean('Opportunities');
				$opp->name = $policy->contract_account;
				$opp->account_id = $policy->contract_account_id;
				$opp->assigned_user_id = $policy->assigned_user_id;
				//$opp->assigned_user_id = '3c4e92c9-c147-2849-0c10-54f4b2c4a466';
				$opp->amount = $policy->total_contract_value;
				$opp->date_closed = $policy->end_date;
				$opp->sales_stage = 'Proposal/Price Quote';
				$opp->opportunity_type = 'Existing Business';
				$opp->suret_policy_id_c = $policy->id;
				$opp->save(false);
			}
		}
		return true;
	}

	static public function hotLead(){
		global $db;
		
		require_once('custom/ax/DistribLead.php');
		$json = DistribLead::getReminderData(true);
		$array = json_decode(htmlspecialchars_decode($json), true);	
		$second = $array[0]['second'];
		$third = $array[0]['third'];
		$randy = $array[0]['randy'];
		$randyplus = $randy + 1;
		$boss_email = 'don@bondsurety.ca';
		$purav_email = 'purav@bondsurety.ca';
		
		$gmToday = gmdate('Y-m-d 00:00:00');
		$gmdate = gmdate('Y-m-d H:i:s');

		require_once('include/SugarPHPMailer.php');
		
		
		$sql = " SELECT a.id, a.last_name, c.accept_status_c,  a.assigned_user_id, a.created_by, a.date_entered, TIMEDIFF('{$gmdate}', a.date_entered) as t, MINUTE(TIMEDIFF('{$gmdate}', a.date_entered)) as h, c.aos_product_categories_id_c, a.primary_address_state  ";
		$sql .= " FROM leads as a ";
		$sql .= " LEFT JOIN leads_cstm as c ON c.id_c = a.id ";
		$sql .= " WHERE  a.deleted = 0 AND  a.assigned_user_id <> a.created_by AND a.date_entered > '{$gmToday}' AND ( c.accept_status_c = 'none' OR c.accept_status_c = '' ) ";
		$sql .= " HAVING (HOUR(t) = 0) AND ((MINUTE(t) >= ".$second.") AND (MINUTE(t) <= ".$randyplus."))";
		$sql .= " ORDER BY a.date_entered DESC  ; ";
		$res = $db->query($sql);
		while( $arr = $db->fetchByAssoc($res) ){
			
			$data = DistribLead::getExtractedDistribData($arr['aos_product_categories_id_c'], '');
			
			//if($arr['h'] > 2){continue;}//test
			$leads = BeanFactory::getBean('Leads', $arr['id']);
			if( !empty($leads->accept_status_c) && $leads->accept_status_c != 'accept'){
				//$mail->ClearAllRecipients();
				
				//$mail->AddBCC('ross@bondsurety.ca');

				if($arr['h'] == $second || $arr['h'] == ($second + 1)) {
					//$set_default = true;
					if( !empty($data['secondaryUsers']) ){
						global $timedate;
						$leads->assigned_user_id = $data['secondaryUsers'];
						$leads->user_id1_c = $data['secondaryUsers'];
						$leads->second_assignment_time_c = $timedate->nowDb();
						$leads->save(false);
					}
					
				}
				elseif($arr['h'] == $third || $arr['h'] == ($third + 1)) {
					//$set_default = true;
					if( !empty($data['thirdUsers']) ){
						global $timedate;
						$leads->assigned_user_id = $data['thirdUsers'];
						$leads->user_id2_c = $data['thirdUsers'];
						$leads->third_assignment_time_c = $timedate->nowDb();
						$leads->save(false);
					}
					
				}

				elseif($arr['h'] == $randy || $arr['h'] == $randyplus){
					//if( !empty($data['reminderOne']) ){
						//foreach($data['reminderOne'] as $i => $user_id){
					global $timedate;
					
					$mail = new SugarPHPMailer();
					$mail->setMailerForSystem();
					$mail->From     = "crm@bondsurety.ca";
					$mail->FromName = "crm";
					$mail->ContentType="text/html";
					$mail->IsHTML(true);
					//$newline = "<br/>";
					$newline = "<br/>";
					$mail->ClearAllRecipients();
					$mail->Subject = 'Reminder: 10 minutes past since lead assigment';

						$user = BeanFactory::getBean('Users', $data['forthUsers']);
						$u_address = $user->emailAddress->getPrimaryAddress($user);
						if( !empty($u_address) ){
							$mail->AddAddress($u_address, $user->name);
							//$mail->AddCC($u_address, $user->name);
						}
						//$leads->assigned_user_id ='2e9923f8-ce83-2999-d542-55e5c76d0bfe';
						//}
					//}
					$view = 'http://crm.bondsurety.ca/index.php?module=Leads&action=DetailView&record='.$arr['id'];
					$accept = 'http://crm.bondsurety.ca/index.php?entryPoint=ax&bid='.$arr['id'].'&a=accept&uid='.$arr['assigned_user_id'];

					$decline = 'http://crm.bondsurety.ca/index.php?entryPoint=ax&bid='.$arr['id'].'&a=decline&uid='.$arr['assigned_user_id'];

					$mail->Body = 'Dear '.$user->first_name.',';
					$mail->Body .= $newline;
					$mail->Body .= $newline.'Please note the status of this lead has not changed for 10 minutes. Please attend to as soon as possible.';
					$mail->Body .= $newline;
					$mail->Body .= $newline.'You may review this Lead at:';
					$mail->Body .= $newline.'<a href="'.$view.'" style="border: 1px solid #000;
padding: 2px 10px;
text-decoration: none;
color: #000;">View</a>';
					$mail->Body .= $newline;
					//$mail->Body .= $newline.'Accept';
					$mail->Body .= $newline.'<a href="'.$accept.'" style="border: 1px solid #000;
padding: 2px 10px;
text-decoration: none;
color: #000;">Accept</a>';
					$mail->Body .= $newline;
					//$mail->Body .= $newline.'Decline';
					$mail->Body .= $newline.'<a href="'.$decline.'" style="border: 1px solid #000;
padding: 2px 10px;
text-decoration: none;
color: #000;">Decline</a>';
					$mail->Body .= $newline;
					$mail->Body .= $newline.'Thank you,';
					$mail->Body .= $newline;
					$mail->Body .= $newline.'Ai Automated CRM Manager.';
					$mail->Body .= $newline;
					$mail->Send();
					//assign lead to randy
					$leads->assigned_user_id = $data['forthUsers'];
					$leads->save(false);
				}
				
			}
			 
			/*if(!$success){
				$GLOBALS['log']->fatal("HOT Lead Remainder FAILURE: ");
			} else {
				$GLOBALS['log']->debug("HOT Lead Remainder SUCCESS: ");
			}
			if($mail->isError()){
				$GLOBALS['log']->fatal("HOT Lead Remainder error: ".$mail->ErrorInfo);
			}*/
		}
		$mail->SMTPClose();
		
		return true;
	}	
	
	static public function runOpenLeadReminder($debug = false){
		global $db, $sugar_config;

		require_once('include/SugarPHPMailer.php');
		$mail = new SugarPHPMailer();

		$mail->setMailerForSystem();
		$mail->From     = "no-reply@bondsurety.ca";
		$mail->FromName = "no-reply";
		$mail->ContentType="text/html";
		$mail->IsHTML(true);		
		
		$newline = "<br/>";//$newline = "\r\n";		
		
		$res = $db->query(" SELECT DISTINCT(assigned_user_id) FROM leads WHERE deleted = 0 AND status NOT IN ('Dead','Converted'); ");
		while($row = $db->fetchByAssoc($res)){
			$mail->ClearAllRecipients();
			
			$user = BeanFactory::getBean('Users', $row['assigned_user_id']);

			$email_text = $newline;

			$i = 0;
			$sql2 = " SELECT status, id, last_name, first_name FROM leads WHERE deleted = 0 AND  status NOT IN ('Dead','Converted') AND assigned_user_id = '{$row['assigned_user_id']}' ORDER BY date_entered DESC ; ";
			$res2 = $db->query($sql2);
			while($row2 = $db->fetchByAssoc($res2)){
				$i++;
				$email_text .= $newline.$newline.$i.') <a href="'.$sugar_config['site_url'].'/index.php?module=Leads&action=DetailView&record='.$row2['id'].'">'.$row2['last_name'].' '.$row2['first_name'].'</a>&nbsp;&nbsp;['.$row2['status'].']';
			}
			if( ($i > 0)&& !empty($user->email1) ){
				$pre_text = '';
				if($debug){
					$mail->AddAddress('ross@bondsurety.ca', 'Ross');
					$pre_text = $newline.'USER:'.$user->user_name.' EMAIL:'.$user->email1.$newline;
				}else{
					$mail->AddAddress($user->email1);
				}
				$mail->Subject = 'Reminder: You have '.$i.' open leads';
				$mail->Body = $pre_text.$email_text;
				//$mail->AltBody = $email_text;

				$success = $mail->Send();
				if(!$success){
					$GLOBALS['log']->fatal("Email Open Leads Remainder FAILURE: ");
				} else {
					$GLOBALS['log']->debug("Email Open Leads Remainder SUCCESS: ");
				}
				if($mail->isError()){
					$GLOBALS['log']->fatal("Email Open Leads Remainder error: ".$mail->ErrorInfo);
				}
			}
		}
		
		$mail->SMTPClose();
		
		return true;
	}

}