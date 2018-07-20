<?php
// created: 2015-08-19 12:59:44
$dictionary["AOS_Invoices"]["fields"]["aos_invoices_aos_contracts_1"] = array (
  'name' => 'aos_invoices_aos_contracts_1',
  'type' => 'link',
  'relationship' => 'aos_invoices_aos_contracts_1',
  'source' => 'non-db',
  'module' => 'AOS_Contracts',
  'bean_name' => 'AOS_Contracts',
  'vname' => 'LBL_AOS_INVOICES_AOS_CONTRACTS_1_FROM_AOS_CONTRACTS_TITLE',
  'id_name' => 'aos_invoices_aos_contracts_1aos_contracts_idb',
);
$dictionary["AOS_Invoices"]["fields"]["aos_invoices_aos_contracts_1_name"] = array (
  'name' => 'aos_invoices_aos_contracts_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_AOS_INVOICES_AOS_CONTRACTS_1_FROM_AOS_CONTRACTS_TITLE',
  'save' => true,
  'id_name' => 'aos_invoices_aos_contracts_1aos_contracts_idb',
  'link' => 'aos_invoices_aos_contracts_1',
  'table' => 'aos_contracts',
  'module' => 'AOS_Contracts',
  'rname' => 'name',
);
$dictionary["AOS_Invoices"]["fields"]["aos_invoices_aos_contracts_1aos_contracts_idb"] = array (
  'name' => 'aos_invoices_aos_contracts_1aos_contracts_idb',
  'type' => 'link',
  'relationship' => 'aos_invoices_aos_contracts_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'left',
  'vname' => 'LBL_AOS_INVOICES_AOS_CONTRACTS_1_FROM_AOS_CONTRACTS_TITLE',
);
