<?php
$popupMeta = array (
    'moduleMain' => 'AOS_Products',
    'varName' => 'AOS_Products',
    'orderBy' => 'aos_products.name',
    'whereClauses' => array (
  'name' => 'aos_products.name',
  'part_number' => 'aos_products.part_number',
  'price' => 'aos_products.price',
  'created_by' => 'aos_products.created_by',
  'insurer_c' => 'aos_products.insurer_c',
  'aos_product_category_name' => 'aos_products.aos_product_category_name',
),
    'searchInputs' => array (
  1 => 'name',
  4 => 'part_number',
  6 => 'price',
  7 => 'created_by',
  8 => 'insurer_c',
  9 => 'aos_product_category_name',
),
    'searchdefs' => array (
  'name' => 
  array (
    'name' => 'name',
    'width' => '10%',
  ),
  'part_number' => 
  array (
    'name' => 'part_number',
    'width' => '10%',
  ),
  'price' => 
  array (
    'name' => 'price',
    'width' => '10%',
  ),
  'insurer_c' => 
  array (
    'type' => 'relate',
    'studio' => 'visible',
    'label' => 'LBL_INSURER',
    'id' => 'INSRR_INSURERS_ID_C',
    'link' => true,
    'width' => '10%',
    'name' => 'insurer_c',
  ),
  'aos_product_category_name' => 
  array (
    'type' => 'relate',
    'studio' => 'visible',
    'label' => 'LBL_AOS_PRODUCT_CATEGORYS_NAME',
    'id' => 'AOS_PRODUCT_CATEGORY_ID',
    'link' => true,
    'width' => '10%',
    'name' => 'aos_product_category_name',
  ),
  'created_by' => 
  array (
    'name' => 'created_by',
    'label' => 'LBL_CREATED',
    'type' => 'enum',
    'function' => 
    array (
      'name' => 'get_user_array',
      'params' => 
      array (
        0 => false,
      ),
    ),
    'width' => '10%',
  ),
),
    'listviewdefs' => array (
  'NAME' => 
  array (
    'width' => '25%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
    'name' => 'name',
  ),
  'PART_NUMBER' => 
  array (
    'width' => '10%',
    'label' => 'LBL_PART_NUMBER',
    'default' => true,
    'name' => 'part_number',
  ),
  'PRICE' => 
  array (
    'width' => '10%',
    'label' => 'LBL_PRICE',
    'default' => true,
    'name' => 'price',
  ),
  'TAX_RATE_C' => 
  array (
    'type' => 'decimal',
    'default' => true,
    'label' => 'LBL_TAX_RATE',
    'width' => '10%',
    'name' => 'tax_rate_c',
  ),
  'COMMISSION_RATE_C' => 
  array (
    'type' => 'decimal',
    'default' => true,
    'label' => 'LBL_COMMISSION_RATE',
    'width' => '10%',
    'name' => 'commission_rate_c',
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
    'name' => 'aos_product_category_name',
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
    'name' => 'insurer_c',
  ),
  'QBO_ID_C' => 
  array (
    'type' => 'varchar',
    'default' => true,
    'label' => 'LBL_QBO_ID',
    'width' => '3%',
    'name' => 'qbo_id_c',
  ),
),
);
