<?php

$dictionary["Lead"]["fields"]["zywave_c"]['massupdate'] = '1';

$dictionary['Lead']['fields']['policy_g'] = array (
	'required' => false,
	'name' => 'policy_g',
	'vname' => 'LBL_POLICY_G',
	'type' => 'EnumG',
	'dbType' => 'varchar',
	'len' => 100,
	'massupdate' => 0,
	'comments' => '',
	'help' => '',
	'importable' => 'true',
	'duplicate_merge' => 'disabled',
	'duplicate_merge_dom_value' => '0',
	'audited' => 0,
	'reportable' => true,
	'options' => 'ax_coverage_grouped_type',
	'default' => '',	
	'studio' => 'visible',
);
