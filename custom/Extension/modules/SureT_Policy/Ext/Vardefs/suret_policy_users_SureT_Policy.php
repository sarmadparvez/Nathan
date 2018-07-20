<?php
// created: 2015-04-01 15:33:19
$dictionary["SureT_Policy"]["fields"]["suret_policy_users"] = array (
  'name' => 'suret_policy_users',
  'type' => 'link',
  'relationship' => 'suret_policy_users',
  'source' => 'non-db',
  'module' => 'Users',
  'bean_name' => 'User',
  'vname' => 'LBL_SURET_POLICY_USERS_FROM_USERS_TITLE',
  'id_name' => 'suret_policy_usersusers_ida',
);
$dictionary["SureT_Policy"]["fields"]["suret_policy_users_name"] = array (
  'name' => 'suret_policy_users_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_SURET_POLICY_USERS_FROM_USERS_TITLE',
  'save' => true,
  'id_name' => 'suret_policy_usersusers_ida',
  'link' => 'suret_policy_users',
  'table' => 'users',
  'module' => 'Users',
  'rname' => 'name',
);
$dictionary["SureT_Policy"]["fields"]["suret_policy_usersusers_ida"] = array (
  'name' => 'suret_policy_usersusers_ida',
  'type' => 'link',
  'relationship' => 'suret_policy_users',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_SURET_POLICY_USERS_FROM_SURET_POLICY_TITLE',
);
