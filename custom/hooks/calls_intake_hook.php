<?php

class calls_intake_hook{

	function check_email(&$bean, $event, $arguments) { 
	 $email = $bean->email;
     $phone = $bean->caller;
     $lastname = $bean->last_name;

     global $db;
     /*$query = "SELECT id FROM email_addresses where email_address = '$email'";
     $result = $db->query($query, false);
     if ($result->num_rows > 0) {
       $row = $db->fetchByAssoc($result);
       $email_id = $row['id'];
     }*/
     $query_lastn_lead = "SELECT id FROM leads where last_name = '$lastname'";
     $result_lastn_lead = $db->query($query_lastn_lead, false);
     if ($result_lastn_lead->num_rows > 0) {
       $row = $db->fetchByAssoc($result_lastn_lead);
       $last_name_lead = $row['id'];
     }

     $query_lastn_ac = "SELECT id FROM accounts where name = '$lastname'";
     $result_lastn_ac = $db->query($query_lastn_ac, false);
     if ($result_lastn_ac->num_rows > 0) {
       $row = $db->fetchByAssoc($result_lastn_ac);
       $account_last = $row['id'];
     }

     $query_phone = "SELECT id FROM leads where phone_work = $phone";
     $result_phone = $db->query($query_phone, false);
     if ($result_phone->num_rows > 0) {
       $row = $db->fetchByAssoc($result_phone);
       $phone_lead_id = $row['id'];
     }

    $query_phone_account = "SELECT id FROM accounts where phone_office = $phone";
     $result_phone_account = $db->query($query_phone_account, false);
     if ($result_phone_account->num_rows > 0) {
       $row = $db->fetchByAssoc($result_phone_account);
       $phone_account_id = $row['id'];
     }

		 if (!empty($phone_account_id) || !empty($account_last)) {

		 	if(!empty($phone_account_id)){
		 	$id = $phone_account_id;
			 }
			 else{
			 	$id = $account_last;
			 }
			 
			$parent_type = 'Accounts';
			$parent_id = $id;
			/*code for email on assignment*/
		$assigned_user = "SELECT assigned_user_id FROM accounts where id = '$parent_id'";
		$result_assigned_user = $db->query($assigned_user, true);
		
	     if ($result_assigned_user->num_rows > 0) {
	       $row = $db->fetchByAssoc($result_assigned_user);
	       $assigned_user_id = $row['assigned_user_id'];
	     }
		$mail = new SugarPHPMailer();
		$mail->setMailerForSystem();
		$mail->From = "no-reply@bondsurety.ca";
		$mail->FromName = "no-reply";
		$mail->ContentType="text/html";
		$mail->IsHTML(true);		
		$mail->ClearAllRecipients();
		
		if( !empty($assigned_user_id) ){
			$main_user_id = $assigned_user_id;
		}
		if( !empty($main_user_id) ){
			$user = BeanFactory::getBean('Users', $main_user_id);
			//if( $user->status == 'Inactive' ){return false;}
			$notify_address = $user->emailAddress->getPrimaryAddress($user);
			$mail->AddAddress($notify_address, $user->name);			
		}else{
			return false;//missed main user
		}
	
		$newline = "<br/>";
		$mail->Body .= 'Hi '. $user->name;
		$mail->Body .= $newline;
		$mail->Body .= 'One call has been assigned to you. Please check your account in crm.';
		$mail->Body .= $newline;
		$mail->Body .= $newline.'Thank you,';
		$mail->Body .= $newline;
        //echo '</br>'.
		$mail->Subject = 'Call assigned';
		$mail->Send();
			/*end code for email on assignment*/	
				
		 } else if (empty($phone_account_id) && empty($account_last) && (!empty($last_name_lead) || !empty($phone_lead_id))) {
		 	if(!empty($last_name_lead)){
		 		$id = $last_name_lead;
		 	}
		 	else{
		 		$id = $phone_lead_id;
		 	}
				$parent_type = 'Leads';   
				$parent_id = $id;
		 } else if (empty($phone_account_id) && empty($phone_lead_id) && empty($last_name_lead) && empty($account_last)) {
				$lead = BeanFactory::newBean('Leads');
				$lead->status = 'New';
				
				//$lead->first_name = $bean->name;
				$lead->last_name = $bean->name.' '.$bean->last_name;
				
				$lead->lead_source = 'IncomingCall'; //!no space as its key
				$lead->phone_work = $phone;
				$lead->email1 = $email; 
				$lead->aos_product_categories_id_c = $bean->product_inquired;  
				if($bean->patched_to = 'could_not_patch'){ 
					require_once('custom/ax/DistribLead.php');
					$data = DistribLead::getExtractedDistribData($bean->product_inquired, '');
					/*Added by Hk on 22May2018 to add the default user if there is no user assignment for this category*/ 
					if(0 == count(array_filter($data))){
						$distribDefaultUser = DistribLead::getDistribDefaultData(true);
						$data = ['primaryUser' => $distribDefaultUser];
					}
					$lead->assigned_user_id = $data['primaryUser'];
				}
				else{
					$lead->assigned_user_id = $bean->patched_to;
				}

				
				
				$lead->set_created_by = false;
				$lead->save(false);
				$parent_type = 'Leads';
				$parent_id = $lead->id;
		 }
		 global $db;
        $query2 = "SELECT name FROM aos_product_categories WHERE id = '$bean->product_inquired'";
       $queryno = $db->query($query2, true,"Error reading tasks entry: ");
		$resultno = $db->fetchByAssoc($queryno);
			$call = BeanFactory::newBean('Calls'); 
			$call->name = $resultno['name'];
			$call->description = $bean->call_body;
			$call->date_start = $bean->date_time;
			$call->status = 'Planned';
			$call->assigned_user_id = $bean->patched_to;
			$call->direction = 'Inbound';
			$call->parent_type = $parent_type;
			$call->parent_id = $parent_id;
			$call->save(false); 

	}

	function do_redirect_after_login(){
		global $current_user;
		include("modules/ACLRoles/ACLRole.php"); 
    	$acl_role_obj = new ACLRole(); 
    	$user_roles = $acl_role_obj->getUserRoles($current_user->id);
    	if (in_array('Virtual Assistant',$user_roles)){
			$queryParams = array(
		    'module' => 'calls_IntakeForm',
		    'action' => 'EditView',
		    'return_module' => 'calls_IntakeForm',
		    'return_action' => 'EditView'
		);
		SugarApplication::redirect('index.php?' . http_build_query($queryParams));
		}
	}

}
