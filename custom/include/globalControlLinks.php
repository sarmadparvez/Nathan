<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

//include("modules/ACLRoles/ACLRole.php");
global $app_strings, $current_user;
global $sugar_config, $sugar_version, $sugar_flavor, $server_unique_key, $current_language, $action;


//$global_control_links['training'] = array(
  //  'linkinfo' => array($app_strings['LBL_TRAINING'] => 'javascript:void(window.open(\'http://suitecrm.com/forum/index\'))'),
   // 'submenu' => ''
//);

//$global_control_links = array();
//$report_sub_menu = array();
//$report_sub_menu[] = array('Deleted'=>'index.php?module=Home&action=listDeleted');

if(is_admin($current_user)){
	
	$global_control_links['reports'] = array(
		'linkinfo' => array('Deleted'=>'index.php?module=Home&action=listDeleted'),
		//'submenu' => $report_sub_menu,
	);	
}

//$user_roles = $acl_role_obj->getUserRoles($current_user->id);
$user_roles = ACLRole::getUserRoleNames($current_user->id);

if (in_array('Virtual Assistant',$user_roles) == false){
	$global_control_links['employees'] = array(
	'linkinfo' => array($app_strings['LBL_EMPLOYEES']=> 'index.php?module=Employees&action=index&query=true'),
	'submenu' => ''
	);
	$global_control_links['training'] = array(
	'linkinfo' => array($app_strings['LBL_TRAINING'] => 'javascript:void(window.open(\'http://suitecrm.com/forum/index\'))'),
	'submenu' => ''
	 );
}

