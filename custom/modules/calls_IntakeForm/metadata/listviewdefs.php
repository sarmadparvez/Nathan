<?php
$module_name = 'calls_IntakeForm';
$listViewDefs [$module_name] = 
array (
  'LAST_NAME' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_LAST_NAME',
    'width' => '10%',
    'default' => true,
  ),
  'PATCHED_TO' => 
  array (
    'type' => 'enum',
    'studio' => 
    array (
      'listview' => true,
      'detailview' => true,
      'editview' => true,
    ),
    'default' => true,
    'label' => 'LBL_PATCHED_TO',
    'width' => '10%',
  ),
  'PRODUCT_INQUIRED' => 
  array (
    'type' => 'enum',
    'studio' => 
    array (
      'listview' => true,
      'detailview' => true,
      'editview' => true,
    ),
    'label' => 'LBL_PRODUCT_INQUIRED',
    'width' => '10%',
    'default' => true,
  ),
  'CALLER' => 
  array (
    'type' => 'phone',
    'label' => 'LBL_CALLER',
    'width' => '10%',
    'default' => true,
  ),
);
?>
