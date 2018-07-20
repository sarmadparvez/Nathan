<?php

	if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point'); 

	global $mod_strings, $app_strings, $sugar_config;
	if(ACLController::checkAccess('Leads', 'edit', true))$module_menu[]=Array("index.php?module=Leads&action=EditView&return_module=Leads&return_action=DetailView", $mod_strings['LNK_NEW_LEAD'],"CreateLeads", 'Leads');
	if(ACLController::checkAccess('Leads', 'edit', true))$module_menu[]=Array("index.php?module=Leads&action=ImportVCard", $mod_strings['LNK_IMPORT_VCARD'],"CreateLeads", 'Leads');
	if(ACLController::checkAccess('Leads', 'list', true))$module_menu[]=Array("index.php?module=Leads&action=index&return_module=Leads&return_action=DetailView", $mod_strings['LNK_LEAD_LIST'],"Leads", 'Leads');
	if(ACLController::checkAccess('Leads', 'import', true))$module_menu[]=Array("index.php?module=Import&action=Step1&import_module=Leads&return_module=Leads&return_action=index", $mod_strings['LNK_IMPORT_LEADS'],"Import", 'Leads');

	if(ACLController::checkAccess('AOR_Reports', 'list', true))$module_menu[]=Array("index.php?module=AOR_Reports&action=leadSummary", 'Report: Leads By Producers', "AOR_Reports", 'AOR_Reports');
	if(ACLController::checkAccess('AOR_Reports', 'list', true))$module_menu[]=Array("index.php?module=AOR_Reports&action=lsc", 'Report: Leads By Categories', "AOR_Reports", 'AOR_Reports');

	
	?>