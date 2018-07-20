<?php
// created: 2015-08-19 12:59:44
$dictionary["aos_invoices_aos_contracts_1"] = array (
  'true_relationship_type' => 'one-to-one',
  'from_studio' => true,
  'relationships' => 
  array (
    'aos_invoices_aos_contracts_1' => 
    array (
      'lhs_module' => 'AOS_Invoices',
      'lhs_table' => 'aos_invoices',
      'lhs_key' => 'id',
      'rhs_module' => 'AOS_Contracts',
      'rhs_table' => 'aos_contracts',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'aos_invoices_aos_contracts_1_c',
      'join_key_lhs' => 'aos_invoices_aos_contracts_1aos_invoices_ida',
      'join_key_rhs' => 'aos_invoices_aos_contracts_1aos_contracts_idb',
    ),
  ),
  'table' => 'aos_invoices_aos_contracts_1_c',
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
      'name' => 'aos_invoices_aos_contracts_1aos_invoices_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'aos_invoices_aos_contracts_1aos_contracts_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'aos_invoices_aos_contracts_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'aos_invoices_aos_contracts_1_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'aos_invoices_aos_contracts_1aos_invoices_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'aos_invoices_aos_contracts_1_idb2',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'aos_invoices_aos_contracts_1aos_contracts_idb',
      ),
    ),
  ),
);