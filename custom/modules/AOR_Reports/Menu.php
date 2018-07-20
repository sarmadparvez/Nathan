<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point'); 


global $mod_strings, $app_strings, $sugar_config;
 
if(ACLController::checkAccess('AOR_Reports', 'edit', true))$module_menu[]=Array("index.php?module=AOR_Reports&action=EditView&return_module=AOR_Reports&return_action=DetailView", $mod_strings['LNK_NEW_RECORD'],"CreateAOR_Reports", 'AOR_Reports');
if(ACLController::checkAccess('AOR_Reports', 'list', true))$module_menu[]=Array("index.php?module=AOR_Reports&action=index&return_module=AOR_Reports&return_action=DetailView", $mod_strings['LNK_LIST'],"AOR_Reports", 'AOR_Reports');
if(ACLController::checkAccess('AOR_Reports', 'import', true))$module_menu[]=Array("index.php?module=Import&action=Step1&import_module=AOR_Reports&return_module=AOR_Reports&return_action=index", $app_strings['LBL_IMPORT'],"Import", 'AOR_Reports');
	
//	if(ACLController::checkAccess('AOR_Reports', 'list', true))$module_menu[]=Array("index.php?module=AOR_Reports&action=leadSummary", 'Lead Summary', "AOR_Reports", 'AOR_Reports');
	if(ACLController::checkAccess('AOR_Reports', 'list', true))$module_menu[]=Array("index.php?module=AOR_Reports&action=leadSummary", 'Report: Leads By Producers', "AOR_Reports", 'AOR_Reports');
	if(ACLController::checkAccess('AOR_Reports', 'list', true))$module_menu[]=Array("index.php?module=AOR_Reports&action=lsc", 'Report: Leads By Categories', "AOR_Reports", 'AOR_Reports');
	
	if(ACLController::checkAccess('AOR_Reports', 'list', true))$module_menu[]=Array("index.php?module=AOR_Reports&action=general", 'Report: L2S Ratio', "AOR_Reports", 'AOR_Reports');
