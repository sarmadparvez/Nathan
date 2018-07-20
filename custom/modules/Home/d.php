<?php
//header('Content-type: application/json');

global $app_list_strings, $current_user;

if(!$current_user->is_admin){
	echo json_encode(array('msg'=>'AdminOnly'));
}else{

	if(isset($_REQUEST['do']) && $_REQUEST['do'] == 'save'){
		//print_r($json); die;
		$json = file_get_contents('php://input');
		
		sugar_cache_clear('distrib'); 
		$decode_json = json_decode($json); 
		if(!empty($decode_json)){
		$count = 0;
		$allPanels = [];
		foreach($decode_json as $filters){
			$allPanels[] = $filters->Name;
			if($filters->Name == ""){
				$count++;
			}
		}
		} 
		$allPanelCount = count($allPanels); 
		$uniquePanelsCount = count(array_unique($allPanels)); 
		if(0 == $count && $allPanelCount == $uniquePanelsCount){
		require_once('custom/ax/DistribLead.php');
		$success = DistribLead::saveDistribData($json);
		if(0 == $count && $success){
			$msg = 'Good';
		}else{
			$msg = 'Bad'; 
		}
		}else{
			$msg = ($allPanelCount != $uniquePanelsCount)?'duplicate':'empty';
		}
		echo json_encode(array('msg'=>$msg));
	}

}
