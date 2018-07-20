<?php
// created: 2015-04-06 17:34:37
$dictionary["insrr_insurers_aos_product_categories"] = array (
  'true_relationship_type' => 'many-to-many',
  'relationships' => 
  array (
    'insrr_insurers_aos_product_categories' => 
    array (
      'lhs_module' => 'insrr_Insurers',
      'lhs_table' => 'insrr_insurers',
      'lhs_key' => 'id',
      'rhs_module' => 'AOS_Product_Categories',
      'rhs_table' => 'aos_product_categories',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'insrr_insurers_aos_product_categories_c',
      'join_key_lhs' => 'insrr_insurers_aos_product_categoriesinsrr_insurers_ida',
      'join_key_rhs' => 'insrr_insurers_aos_product_categoriesaos_product_categories_idb',
    ),
  ),
  'table' => 'insrr_insurers_aos_product_categories_c',
  'fields' => 
  array (
    0 => 
    array (
      'name' => 'id',
      'type' => 'varchar',
      'len' => 36,
    ),
    1 => 
    array (
      'name' => 'date_modified',
      'type' => 'datetime',
    ),
    2 => 
    array (
      'name' => 'deleted',
      'type' => 'bool',
      'len' => '1',
      'default' => '0',
      'required' => true,
    ),
    3 => 
    array (
      'name' => 'insrr_insurers_aos_product_categoriesinsrr_insurers_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'insrr_insurers_aos_product_categoriesaos_product_categories_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'insrr_insurers_aos_product_categoriesspk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'insrr_insurers_aos_product_categories_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'insrr_insurers_aos_product_categoriesinsrr_insurers_ida',
        1 => 'insrr_insurers_aos_product_categoriesaos_product_categories_idb',
      ),
    ),
  ),
);