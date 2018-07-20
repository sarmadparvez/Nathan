<?php
// created: 2015-04-06 17:34:37
$dictionary["insrr_insurers_suret_policy"] = array (
  'true_relationship_type' => 'one-to-many',
  'relationships' => 
  array (
    'insrr_insurers_suret_policy' => 
    array (
      'lhs_module' => 'insrr_Insurers',
      'lhs_table' => 'insrr_insurers',
      'lhs_key' => 'id',
      'rhs_module' => 'SureT_Policy',
      'rhs_table' => 'suret_policy',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'insrr_insurers_suret_policy_c',
      'join_key_lhs' => 'insrr_insurers_suret_policyinsrr_insurers_ida',
      'join_key_rhs' => 'insrr_insurers_suret_policysuret_policy_idb',
    ),
  ),
  'table' => 'insrr_insurers_suret_policy_c',
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
      'name' => 'insrr_insurers_suret_policyinsrr_insurers_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'insrr_insurers_suret_policysuret_policy_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'insrr_insurers_suret_policyspk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'insrr_insurers_suret_policy_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'insrr_insurers_suret_policyinsrr_insurers_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'insrr_insurers_suret_policy_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'insrr_insurers_suret_policysuret_policy_idb',
      ),
    ),
  ),
);