<?php

	if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

	if( !empty($_GET['bid']) && !empty($_GET['a']) && !empty($_GET['uid']) ){
	
		global $db, $sugar_config;
		
		$id = $db->quote($_GET['bid']);
		$user_id = $db->quote($_GET['uid']);
		$action = $db->quote($_GET['a']);
		
		if( !in_array($action, array('accept','decline')) ){
			$GLOBALS['log']->error("entryPoint:AX (LeadAcceptDecline) - Unknown Action");
			die('Unknown Action');
		}

		$params = '';
		foreach($_GET as $field => $value){
			$params .= '|'.$field.':'.$value;
		}
		
		if( !empty($id) ){
			$bean = BeanFactory::getBean('Leads', $id);
			if( !empty($bean->id) ){
				if( strcmp($user_id, $bean->assigned_user_id) == false){
					$bean->accept_status_c = $action;
					//$bean->accept_status_name = $action;
					$success = $bean->save(false);
					if($success){
						$GLOBALS['log']->error("entryPoint:AX (LeadAcceptDecline) params".$params);
						if($action == 'accept'){
							header('Location: '.$sugar_config['site_url'].'/index.php?module=Leads&action=DetailView&record='.$id);
							exit;
						}
						echo 'OK';
					}else{
						echo 'FAIL';
					}
				}else{
					echo "Hey, you don't own this record!";
				}
			}else{
				echo 'Hmmm...';
			}
		}else{
			echo 'Hm-m-m...';
		}
	}else{
		echo 'Hm m m...';
	}
	
	//http://crm.bondsurety.ca/index.php?entryPoint=ax&bid=121e3d5d-6976-a147-33b9-55d36e0cbaef&a=accept&uid=3c4e92c9-c147-2849-0c10-54f4b2c4a466
	//http://crm.bondsurety.ca/index.php?entryPoint=ax&bid=121e3d5d-6976-a147-33b9-55d36e0cbaef&a=accept&uid=3c4e92c9-c147-2849-0c10-54f4b2c4a466
	//SELECT * FROM leads WHERE id = '121e3d5d-6976-a147-33b9-55d36e0cbaef'
