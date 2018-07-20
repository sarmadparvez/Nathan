<?php
// created: 2015-04-08 11:45:29
$dictionary["kwrds_keywords_campaigns_1"] = array (
  'true_relationship_type' => 'one-to-many',
  'from_studio' => true,
  'relationships' => 
  array (
    'kwrds_keywords_campaigns_1' => 
    array (
      'lhs_module' => 'KWRDS_Keywords',
      'lhs_table' => 'kwrds_keywords',
      'lhs_key' => 'id',
      'rhs_module' => 'Campaigns',
      'rhs_table' => 'campaigns',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'kwrds_keywords_campaigns_1_c',
      'join_key_lhs' => 'kwrds_keywords_campaigns_1kwrds_keywords_ida',
      'join_key_rhs' => 'kwrds_keywords_campaigns_1campaigns_idb',
    ),
  ),
  'table' => 'kwrds_keywords_campaigns_1_c',
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
      'name' => 'kwrds_keywords_campaigns_1kwrds_keywords_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'kwrds_keywords_campaigns_1campaigns_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'kwrds_keywords_campaigns_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'kwrds_keywords_campaigns_1_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'kwrds_keywords_campaigns_1kwrds_keywords_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'kwrds_keywords_campaigns_1_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'kwrds_keywords_campaigns_1campaigns_idb',
      ),
    ),
  ),
);