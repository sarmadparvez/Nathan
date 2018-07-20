<?php
// created: 2015-04-06 17:34:37
$dictionary["insrr_insurers_aos_products"] = array (
  'true_relationship_type' => 'many-to-many',
  'relationships' => 
  array (
    'insrr_insurers_aos_products' => 
    array (
      'lhs_module' => 'insrr_Insurers',
      'lhs_table' => 'insrr_insurers',
      'lhs_key' => 'id',
      'rhs_module' => 'AOS_Products',
      'rhs_table' => 'aos_products',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'insrr_insurers_aos_products_c',
      'join_key_lhs' => 'insrr_insurers_aos_productsinsrr_insurers_ida',
      'join_key_rhs' => 'insrr_insurers_aos_productsaos_products_idb',
    ),
  ),
  'table' => 'insrr_insurers_aos_products_c',
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
      'name' => 'insrr_insurers_aos_productsinsrr_insurers_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'insrr_insurers_aos_productsaos_products_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'insrr_insurers_aos_productsspk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'insrr_insurers_aos_products_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'insrr_insurers_aos_productsinsrr_insurers_ida',
        1 => 'insrr_insurers_aos_productsaos_products_idb',
      ),
    ),
  ),
);