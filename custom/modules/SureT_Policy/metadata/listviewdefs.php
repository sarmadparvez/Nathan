<?php
$module_name = 'SureT_Policy';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'TEMP_ACCOUNT_C' => 
  array (
    'type' => 'varchar',
    'default' => true,
    'label' => 'LBL_TEMP_ACCOUNT',
    'width' => '10%',
  ),
  'TEMP_INSURER_C' => 
  array (
    'type' => 'varchar',
    'default' => true,
    'label' => 'LBL_TEMP_INSURER',
    'width' => '10%',
  ),
  'INSRR_INSURERS_SURET_POLICY_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_INSRR_INSURERS_SURET_POLICY_FROM_INSRR_INSURERS_TITLE',
    'id' => 'INSRR_INSURERS_SURET_POLICYINSRR_INSURERS_IDA',
    'width' => '10%',
    'default' => true,
  ),
  'EXPIRATION_DATE' => 
  array (
    'type' => 'date',
    'label' => 'LBL_EXPIRATION_DATE',
    'width' => '10%',
    'default' => true,
  ),
  'PREMIUM_C' => 
  array (
    'type' => 'currency',
    'default' => true,
    'label' => 'LBL_PREMIUM',
    'currency_format' => true,
    'width' => '10%',
  ),
  'TEMP_PR_C' => 
  array (
    'type' => 'varchar',
    'default' => true,
    'label' => 'LBL_TEMP_PR',
    'width' => '10%',
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'width' => '9%',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => true,
  ),
);
?>
