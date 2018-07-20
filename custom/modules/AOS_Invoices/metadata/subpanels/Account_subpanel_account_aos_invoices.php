<?php
// created: 2017-01-19 16:35:01
$subpanel_layout['list_fields'] = array (
  'billing_account' => 
  array (
    'width' => '10%',
    'vname' => 'LBL_BILLING_ACCOUNT',
    'default' => true,
  ),
  'invoice_date' => 
  array (
    'type' => 'date',
    'vname' => 'LBL_INVOICE_DATE',
    'width' => '8%',
    'default' => true,
  ),
  'aos_invoices_aos_contracts_1_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'vname' => 'LBL_AOS_INVOICES_AOS_CONTRACTS_1_FROM_AOS_CONTRACTS_TITLE',
    'id' => 'AOS_INVOICES_AOS_CONTRACTS_1AOS_CONTRACTS_IDB',
    'width' => '10%',
    'default' => true,
    'widget_class' => 'SubPanelDetailViewLink',
    'target_module' => 'AOS_Contracts',
    'target_record_key' => 'aos_invoices_aos_contracts_1aos_contracts_idb',
  ),
  'number' => 
  array (
    'width' => '8%',
    'vname' => 'LBL_LIST_NUM',
    'default' => true,
  ),
  'name' => 
  array (
    'vname' => 'LBL_NAME',
    'widget_class' => 'SubPanelDetailViewLink',
    'width' => '8%',
    'default' => true,
  ),
  'products_amount_c' => 
  array (
    'type' => 'currency',
    'default' => true,
    'vname' => 'LBL_PRODUCTS_AMOUNT',
    'currency_format' => true,
    'width' => '8%',
  ),
  'products_tax_c' => 
  array (
    'type' => 'currency',
    'default' => true,
    'vname' => 'LBL_PRODUCTS_TAX',
    'currency_format' => true,
    'width' => '5%',
  ),
  'total_amount' => 
  array (
    'type' => 'currency',
    'vname' => 'LBL_GRAND_TOTAL',
    'currency_format' => true,
    'width' => '8%',
    'default' => true,
  ),
  'payable_premium_c' => 
  array (
    'type' => 'currency',
    'default' => true,
    'vname' => 'LBL_PAYABLE_PREMIUM',
    'currency_format' => true,
    'width' => '8%',
  ),
  'insurer_c' => 
  array (
    'type' => 'relate',
    'default' => true,
    'studio' => 'visible',
    'vname' => 'LBL_INSURER',
    'id' => 'INSRR_INSURERS_ID_C',
    'link' => true,
    'width' => '8%',
    'widget_class' => 'SubPanelDetailViewLink',
    'target_module' => 'insrr_Insurers',
    'target_record_key' => 'insrr_insurers_id_c',
  ),
  'assigned_user_name' => 
  array (
    'name' => 'assigned_user_name',
    'vname' => 'LBL_ASSIGNED_USER',
    'width' => '8%',
    'default' => true,
  ),
  'status' => 
  array (
    'width' => '5%',
    'vname' => 'LBL_STATUS',
    'default' => true,
  ),
  'edit_button' => 
  array (
    'widget_class' => 'SubPanelEditButton',
    'module' => 'AOS_Invoices',
    'width' => '4%',
    'default' => true,
  ),
  'currency_id' => 
  array (
    'usage' => 'query_only',
  ),
);