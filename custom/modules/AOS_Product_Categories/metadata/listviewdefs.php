<?php
$module_name = 'AOS_Product_Categories';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'LEAD_VALUE_C' => 
  array (
    'type' => 'currency',
    'default' => true,
    'label' => 'LBL_LEAD_VALUE',
    'currency_format' => true,
    'width' => '10%',
  ),
  'LEAD_COST_C' => 
  array (
    'type' => 'currency',
    'default' => true,
    'label' => 'LBL_LEAD_COST',
    'currency_format' => true,
    'width' => '10%',
  ),
  'LEAD_CONV_RATE_C' => 
  array (
    'type' => 'int',
    'default' => true,
    'label' => 'LBL_LEAD_CONV_RATE',
    'width' => '10%',
  ),
  'OPP_VALUE_C' => 
  array (
    'type' => 'currency',
    'default' => true,
    'label' => 'LBL_OPP_VALUE',
    'currency_format' => true,
    'width' => '10%',
  ),
  'OPP_SALE_CONV_C' => 
  array (
    'type' => 'int',
    'default' => true,
    'label' => 'LBL_OPP_SALE_CONV',
    'width' => '10%',
  ),
  'OPP_COST_C' => 
  array (
    'type' => 'currency',
    'default' => true,
    'label' => 'LBL_OPP_COST',
    'currency_format' => true,
    'width' => '10%',
  ),
  'INCOME_PER_POLICY_C' => 
  array (
    'type' => 'currency',
    'default' => true,
    'label' => 'LBL_INCOME_PER_POLICY',
    'currency_format' => true,
    'width' => '10%',
  ),
  'PREMIUM_PER_POLICY_C' => 
  array (
    'type' => 'currency',
    'default' => true,
    'label' => 'LBL_PREMIUM_PER_POLICY',
    'currency_format' => true,
    'width' => '10%',
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'width' => '9%',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => false,
  ),
);
?>
