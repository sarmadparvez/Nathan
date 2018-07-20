<?php

$admin_option_defs=array();

$admin_option_defs['the_qbo']['connect'] = array(	'ModuleLoader', 	'LBL_QBO_ADMIN_TITLE',	'LBL_QBO_ADMIN_TITLE_DESC',	'./index.php?module=tbe_qbo&action=connection' );

$admin_option_defs['the_qbo']['config'] = array(	'Administration', 	'LBL_QBO_SETTINGS_ADMIN_TITLE',	'LBL_QBO_SETTINGS_ADMIN_TITLE_DESC',	'./index.php?module=tbe_qbo&action=config' );

$admin_option_defs['the_qbo']['grablists'] = array(	'Dropdown', 	'LBL_QBO_LIST_GRAB_TITLE',	'LBL_QBO_LIST_GRAB_TITLE_DESC',	'./index.php?module=tbe_qbo&action=grablists' );

$admin_group_header[] = array('LBL_QBO_ADMIN_GROUP_TITLE', '', false, $admin_option_defs, 'LBL_QBO_ADMIN_GROUP_DESC');


?>