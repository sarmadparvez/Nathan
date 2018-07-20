<?php
// created: 2015-08-19 12:59:44
$dictionary["AOS_Contracts"]["fields"]["aos_invoices_aos_contracts_1"] = array (
  'name' => 'aos_invoices_aos_contracts_1',
  'type' => 'link',
  'relationship' => 'aos_invoices_aos_contracts_1',
  'source' => 'non-db',
  'module' => 'AOS_Invoices',
  'bean_name' => 'AOS_Invoices',
  'vname' => 'LBL_AOS_INVOICES_AOS_CONTRACTS_1_FROM_AOS_INVOICES_TITLE',
  'id_name' => 'aos_invoices_aos_contracts_1aos_invoices_ida',
);
$dictionary["AOS_Contracts"]["fields"]["aos_invoices_aos_contracts_1_name"] = array (
  'name' => 'aos_invoices_aos_contracts_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_AOS_INVOICES_AOS_CONTRACTS_1_FROM_AOS_INVOICES_TITLE',
  'save' => true,
  'id_name' => 'aos_invoices_aos_contracts_1aos_invoices_ida',
  'link' => 'aos_invoices_aos_contracts_1',
  'table' => 'aos_invoices',
  'module' => 'AOS_Invoices',
  'rname' => 'name',
);
$dictionary["AOS_Contracts"]["fields"]["aos_invoices_aos_contracts_1aos_invoices_ida"] = array (
  'name' => 'aos_invoices_aos_contracts_1aos_invoices_ida',
  'type' => 'link',
  'relationship' => 'aos_invoices_aos_contracts_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'left',
  'vname' => 'LBL_AOS_INVOICES_AOS_CONTRACTS_1_FROM_AOS_INVOICES_TITLE',
);
