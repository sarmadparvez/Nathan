<?php
// created: 2015-04-01 15:33:18
$dictionary["suret_policy_users"] = array (
  'true_relationship_type' => 'one-to-many',
  'relationships' => 
  array (
    'suret_policy_users' => 
    array (
      'lhs_module' => 'Users',
      'lhs_table' => 'users',
      'lhs_key' => 'id',
      'rhs_module' => 'SureT_Policy',
      'rhs_table' => 'suret_policy',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'suret_policy_users_c',
      'join_key_lhs' => 'suret_policy_usersusers_ida',
      'join_key_rhs' => 'suret_policy_userssuret_policy_idb',
    ),
  ),
  'table' => 'suret_policy_users_c',
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
      'name' => 'suret_policy_usersusers_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'suret_policy_userssuret_policy_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'suret_policy_usersspk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'suret_policy_users_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'suret_policy_usersusers_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'suret_policy_users_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'suret_policy_userssuret_policy_idb',
      ),
    ),
  ),
);