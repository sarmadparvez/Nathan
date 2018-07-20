<?php
$popupMeta = array (
    'moduleMain' => 'AOS_Product_Categories',
    'varName' => 'AOS_Product_Categories',
    'orderBy' => 'aos_product_categories.name',
    'whereClauses' => array (
  'name' => 'aos_product_categories.name',
  'parent_category_name' => 'aos_product_categories.parent_category_name',
  'assigned_user_id' => 'aos_product_categories.assigned_user_id',
  'is_parent' => 'aos_product_categories.is_parent',
),
    'searchInputs' => array (
  1 => 'name',
  4 => 'parent_category_name',
  5 => 'assigned_user_id',
  6 => 'is_parent',
),
    'searchdefs' => array (
  'name' => 
  array (
    'name' => 'name',
    'width' => '10%',
  ),
  'parent_category_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_PRODUCT_CATEGORYS_NAME',
    'id' => 'PARENT_CATEGORY_ID',
    'width' => '10%',
    'name' => 'parent_category_name',
  ),
  'assigned_user_id' => 
  array (
    'name' => 'assigned_user_id',
    'label' => 'LBL_ASSIGNED_TO',
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
  'is_parent' => 
  array (
    'type' => 'bool',
    'label' => 'LBL_IS_PARENT',
    'width' => '10%',
    'name' => 'is_parent',
  ),
),
);
