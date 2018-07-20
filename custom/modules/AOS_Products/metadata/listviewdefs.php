<?php
$listViewDefs ['AOS_Products'] = 
array (
  'NAME' => 
  array (
    'width' => '15%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'PART_NUMBER' => 
  array (
    'width' => '10%',
    'label' => 'LBL_PART_NUMBER',
    'default' => true,
  ),
  'PRICE' => 
  array (
    'width' => '10%',
    'label' => 'LBL_PRICE',
    'currency_format' => true,
    'default' => true,
  ),
  'TAX_RATE_C' => 
  array (
    'type' => 'decimal',
    'default' => true,
    'label' => 'LBL_TAX_RATE',
    'width' => '10%',
  ),
  'COMMISSION_RATE_C' => 
  array (
    'type' => 'decimal',
    'default' => true,
    'label' => 'LBL_COMMISSION_RATE',
    'width' => '10%',
  ),
  'AOS_PRODUCT_CATEGORY_NAME' => 
  array (
    'type' => 'relate',
    'studio' => 'visible',
    'label' => 'LBL_AOS_PRODUCT_CATEGORYS_NAME',
    'id' => 'AOS_PRODUCT_CATEGORY_ID',
    'link' => true,
    'width' => '10%',
    'default' => true,
    'related_fields' => 
    array (
      0 => 'aos_product_category_id',
    ),
  ),
  'INSURER_C' => 
  array (
    'type' => 'relate',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_INSURER',
    'id' => 'INSRR_INSURERS_ID_C',
    'link' => true,
    'width' => '10%',
  ),
  'CREATED_BY_NAME' => 
  array (
    'width' => '10%',
    'label' => 'LBL_CREATED',
    'default' => true,
    'module' => 'Users',
    'link' => true,
    'id' => 'CREATED_BY',
  ),
  'DATE_ENTERED' => 
  array (
    'width' => '5%',
    'label' => 'LBL_DATE_ENTERED',
    'default' => true,
  ),
  'QBO_ID_C' => 
  array (
    'type' => 'varchar',
    'default' => false,
    'label' => 'LBL_QBO_ID',
    'width' => '10%',
  ),
  'TO_INSURER_C' => 
  array (
    'type' => 'bool',
    'default' => false,
    'label' => 'LBL_TO_INSURER',
    'width' => '10%',
  ),
);
?>
