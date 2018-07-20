<?php

class testJob{
	
	//require_once('custom/include/ax_jobs.php');
	static public function hotLead(){
		global $db;
		
		//$boss_email = 'don@bondsurety.ca';
		$boss_email = 'ross@tbcanada.com';
		
		$gmToday = gmdate('Y-m-d 00:00:00');
		$gmdate = gmdate('Y-m-d H:i:s');

		require_once('include/SugarPHPMailer.php');
		$mail = new SugarPHPMailer();
		$mail->setMailerForSystem();
		$mail->From     = "no-reply@bondsurety.ca";
		$mail->FromName = "no-reply";
		$mail->ContentType="text/html";
		$mail->IsHTML(false);
		$newline = "<br/>";//$newline = "\r\n";		
		
		$sql = " SELECT a.id, a.last_name, c.accept_status_c,  a.assigned_user_id, a.created_by, a.date_entered, TIMEDIFF('{$gmdate}', a.date_entered) as t, HOUR(TIMEDIFF('{$gmdate}', a.date_entered)) as h  ";
		$sql .= " FROM leads as a ";
		$sql .= " LEFT JOIN leads_cstm as c ON c.id_c = a.id ";
		$sql .= " WHERE  a.deleted = 0 AND  a.assigned_user_id <> a.created_by AND a.date_entered > '{$gmToday}' AND ( c.accept_status_c = 'none' OR c.accept_status_c = '' ) ";
		$sql .= " HAVING (HOUR(t) = 1 OR HOUR(t) = 2) AND MINUTE(t) = 0 ";
		$sql .= " ORDER BY a.date_entered DESC  ; ";
		$res = $db->query($sql);
		while( $arr = $db->fetchByAssoc($res) ){
			if($arr['h'] > 4){continue;}//test
			$user = BeanFactory::getBean('Users', $arr['assigned_user_id']);
			if( !empty($user->email1) ){
				$mail->ClearAllRecipients();
				$mail->AddAddress('ross@bondsurety.ca');//$mail->AddAddress($user->email1);
				if($arr['h'] > 1){
					$mail->AddAddress($boss_email);
				}
				$mail->Subject = 'Reminder: '.$arr['h'].' hour(s) past since lead assigment';
				$mail->Body = 'You may review this Lead at:
<http://crm.bondsurety.ca/index.php?module=Leads&action=DetailView&record='.$arr['id'].'>

Accept
<http://crm.bondsurety.ca/index.php?entryPoint=ax&bid='.$arr['id'].'&a=accept&uid='.$arr['assigned_user_id'].'>

Decline
<http://crm.bondsurety.ca/index.php?entryPoint=ax&bid='.$arr['id'].'&a=decline&uid='.$arr['assigned_user_id'].'>';
			}
			$success = $mail->Send();
			if(!$success){
				$GLOBALS['log']->fatal("HOT Lead Remainder FAILURE: ");
			} else {
				$GLOBALS['log']->debug("HOT Lead Remainder SUCCESS: ");
			}
			if($mail->isError()){
				$GLOBALS['log']->fatal("HOT Lead Remainder error: ".$mail->ErrorInfo);
			}

		}
		$mail->SMTPClose();
		
		return true;
	}
	
}
