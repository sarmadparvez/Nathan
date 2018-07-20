<?php
// created: 2015-07-30 14:20:49
$dictionary["ax_PCommission"]["fields"]["ax_pcommission_aos_contracts"] = array (
  'name' => 'ax_pcommission_aos_contracts',
  'type' => 'link',
  'relationship' => 'ax_pcommission_aos_contracts',
  'source' => 'non-db',
  'module' => 'AOS_Contracts',
  'bean_name' => 'AOS_Contracts',
  'vname' => 'LBL_AX_PCOMMISSION_AOS_CONTRACTS_FROM_AOS_CONTRACTS_TITLE',
  'id_name' => 'ax_pcommission_aos_contractsaos_contracts_ida',
);
$dictionary["ax_PCommission"]["fields"]["ax_pcommission_aos_contracts_name"] = array (
  'name' => 'ax_pcommission_aos_contracts_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_AX_PCOMMISSION_AOS_CONTRACTS_FROM_AOS_CONTRACTS_TITLE',
  'save' => true,
  'id_name' => 'ax_pcommission_aos_contractsaos_contracts_ida',
  'link' => 'ax_pcommission_aos_contracts',
  'table' => 'aos_contracts',
  'module' => 'AOS_Contracts',
  'rname' => 'name',
);
$dictionary["ax_PCommission"]["fields"]["ax_pcommission_aos_contractsaos_contracts_ida"] = array (
  'name' => 'ax_pcommission_aos_contractsaos_contracts_ida',
  'type' => 'link',
  'relationship' => 'ax_pcommission_aos_contracts',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_AX_PCOMMISSION_AOS_CONTRACTS_FROM_AX_PCOMMISSION_TITLE',
);
