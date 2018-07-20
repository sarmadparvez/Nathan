<?php
//header('Content-type: application/json');

global $app_list_strings, $current_user;

if(!$current_user->is_admin){
	echo json_encode(array('msg'=>'AdminOnly'));
}else{

	if(isset($_REQUEST['do']) && $_REQUEST['do'] == 'save'){

		$json = file_get_contents('php://input');
		
		sugar_cache_clear('reminder');
		
		require_once('custom/ax/DistribLead.php');
		$success = DistribLead::saveReminderData($json);

		if($success){
			echo json_encode(array('msg'=>'Good'));
		}else{
			echo json_encode(array('msg'=>'Bad'));
		}
	}

}