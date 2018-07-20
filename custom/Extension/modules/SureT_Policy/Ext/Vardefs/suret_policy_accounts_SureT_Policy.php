<?php
// created: 2015-04-01 15:33:19
$dictionary["SureT_Policy"]["fields"]["suret_policy_accounts"] = array (
  'name' => 'suret_policy_accounts',
  'type' => 'link',
  'relationship' => 'suret_policy_accounts',
  'source' => 'non-db',
  'module' => 'Accounts',
  'bean_name' => 'Account',
  'vname' => 'LBL_SURET_POLICY_ACCOUNTS_FROM_ACCOUNTS_TITLE',
  'id_name' => 'suret_policy_accountsaccounts_ida',
);
$dictionary["SureT_Policy"]["fields"]["suret_policy_accounts_name"] = array (
  'name' => 'suret_policy_accounts_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_SURET_POLICY_ACCOUNTS_FROM_ACCOUNTS_TITLE',
  'save' => true,
  'id_name' => 'suret_policy_accountsaccounts_ida',
  'link' => 'suret_policy_accounts',
  'table' => 'accounts',
  'module' => 'Accounts',
  'rname' => 'name',
);
$dictionary["SureT_Policy"]["fields"]["suret_policy_accountsaccounts_ida"] = array (
  'name' => 'suret_policy_accountsaccounts_ida',
  'type' => 'link',
  'relationship' => 'suret_policy_accounts',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_SURET_POLICY_ACCOUNTS_FROM_SURET_POLICY_TITLE',
);
