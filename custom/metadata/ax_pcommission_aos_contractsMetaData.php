<?php
// created: 2015-07-30 14:20:49
$dictionary["ax_pcommission_aos_contracts"] = array (
  'true_relationship_type' => 'one-to-many',
  'relationships' => 
  array (
    'ax_pcommission_aos_contracts' => 
    array (
      'lhs_module' => 'AOS_Contracts',
      'lhs_table' => 'aos_contracts',
      'lhs_key' => 'id',
      'rhs_module' => 'ax_PCommission',
      'rhs_table' => 'ax_pcommission',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'ax_pcommission_aos_contracts_c',
      'join_key_lhs' => 'ax_pcommission_aos_contractsaos_contracts_ida',
      'join_key_rhs' => 'ax_pcommission_aos_contractsax_pcommission_idb',
    ),
  ),
  'table' => 'ax_pcommission_aos_contracts_c',
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
      'name' => 'ax_pcommission_aos_contractsaos_contracts_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'ax_pcommission_aos_contractsax_pcommission_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'ax_pcommission_aos_contractsspk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'ax_pcommission_aos_contracts_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'ax_pcommission_aos_contractsaos_contracts_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'ax_pcommission_aos_contracts_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'ax_pcommission_aos_contractsax_pcommission_idb',
      ),
    ),
  ),
);