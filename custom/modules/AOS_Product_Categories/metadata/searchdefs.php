<?php
$module_name = 'AOS_Product_Categories';
$searchdefs [$module_name] = 
array (
  'layout' => 
  array (
    'basic_search' => 
    array (
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'parent_category_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_PRODUCT_CATEGORYS_NAME',
        'id' => 'PARENT_CATEGORY_ID',
        'width' => '10%',
        'default' => true,
        'name' => 'parent_category_name',
      ),
      'is_parent' => 
      array (
        'type' => 'bool',
        'default' => true,
        'label' => 'LBL_IS_PARENT',
        'width' => '10%',
        'name' => 'is_parent',
      ),
    ),
    'advanced_search' => 
    array (
      0 => 'name',
      1 => 
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
      ),
    ),
  ),
  'templateMeta' => 
  array (
    'maxColumns' => '3',
    'maxColumnsBasic' => '4',
    'widths' => 
    array (
      'label' => '10',
      'field' => '30',
    ),
  ),
);
?>
