<?php
$module_name = 'ax_PCommission';
$listViewDefs [$module_name] = 
array (
  'AX_PCOMMISSION_AOS_CONTRACTS_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_AX_PCOMMISSION_AOS_CONTRACTS_FROM_AOS_CONTRACTS_TITLE',
    'id' => 'AX_PCOMMISSION_AOS_CONTRACTSAOS_CONTRACTS_IDA',
    'width' => '10%',
    'default' => true,
  ),
  'NAME' => 
  array (
    'width' => '5%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'RATE' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_RATE',
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
  'SHARE' => 
  array (
    'type' => 'float',
    'label' => 'LBL_SHARE',
    'width' => '10%',
    'default' => true,
  ),
  'PRODUCER2' => 
  array (
    'type' => 'relate',
    'studio' => 'visible',
    'label' => 'LBL_PRODUCER2',
    'id' => 'USER_ID_C',
    'link' => true,
    'width' => '10%',
    'default' => true,
  ),
  'DATE_ENTERED' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_ENTERED',
    'width' => '10%',
    'default' => true,
  ),
);
?>
