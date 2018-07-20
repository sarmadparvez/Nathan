<?php
//header('Content-type: application/json');

global $app_list_strings, $current_user;

if(!$current_user->is_admin){
	echo json_encode(array('msg'=>'AdminOnly'));
}else{

	if(isset($_REQUEST['do']) && $_REQUEST['do'] == 'saveData'){
		//print_r($json); die;
		$json = file_get_contents('php://input');
		$user_id = $_POST['user_id']; 
		sugar_cache_clear('distrib'); 
		if($user_id != ""){
		require_once('custom/ax/DistribLead.php');
		$success = DistribLead::saveDistribDefaultData($user_id);
		if($success){
			$msg = 'Good';
		}else{
			$msg = 'Bad'; 
		}
		}else{
			$msg = 'Empty';
		}
		echo json_encode(array('msg'=>$msg));
	}

}
