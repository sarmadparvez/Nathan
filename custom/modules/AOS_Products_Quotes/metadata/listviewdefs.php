<?php
$module_name = 'AOS_Products_Quotes';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'PRODUCT_COST_PRICE' => 
  array (
    'width' => '10%',
    'label' => 'LBL_PRODUCT_COST_PRICE',
    'default' => true,
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'width' => '9%',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'default' => true,
  ),
  'ITEM_DESCRIPTION' => 
  array (
    'type' => 'text',
    'label' => 'LBL_PRODUCT_DESCRIPTION',
    'sortable' => false,
    'width' => '10%',
    'default' => false,
  ),
);
?>
