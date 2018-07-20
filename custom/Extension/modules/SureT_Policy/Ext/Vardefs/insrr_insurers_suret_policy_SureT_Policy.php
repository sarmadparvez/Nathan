<?php
// created: 2015-04-06 17:34:37
$dictionary["SureT_Policy"]["fields"]["insrr_insurers_suret_policy"] = array (
  'name' => 'insrr_insurers_suret_policy',
  'type' => 'link',
  'relationship' => 'insrr_insurers_suret_policy',
  'source' => 'non-db',
  'module' => 'insrr_Insurers',
  'bean_name' => false,
  'vname' => 'LBL_INSRR_INSURERS_SURET_POLICY_FROM_INSRR_INSURERS_TITLE',
  'id_name' => 'insrr_insurers_suret_policyinsrr_insurers_ida',
);
$dictionary["SureT_Policy"]["fields"]["insrr_insurers_suret_policy_name"] = array (
  'name' => 'insrr_insurers_suret_policy_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_INSRR_INSURERS_SURET_POLICY_FROM_INSRR_INSURERS_TITLE',
  'save' => true,
  'id_name' => 'insrr_insurers_suret_policyinsrr_insurers_ida',
  'link' => 'insrr_insurers_suret_policy',
  'table' => 'insrr_insurers',
  'module' => 'insrr_Insurers',
  'rname' => 'name',
);
$dictionary["SureT_Policy"]["fields"]["insrr_insurers_suret_policyinsrr_insurers_ida"] = array (
  'name' => 'insrr_insurers_suret_policyinsrr_insurers_ida',
  'type' => 'link',
  'relationship' => 'insrr_insurers_suret_policy',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_INSRR_INSURERS_SURET_POLICY_FROM_SURET_POLICY_TITLE',
);
