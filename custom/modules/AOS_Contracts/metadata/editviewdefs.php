<?php
$module_name = 'AOS_Contracts';
$viewdefs [$module_name] =
array (
  'EditView' =>
  array (
    'templateMeta' =>
    array (
      'includes' =>
      array (
        0 =>
        array (
          'file' => 'custom/modules/AOS_Contracts/f.js',
        ),
      ),
      'maxColumns' => '2',
      'widths' =>
      array (
        0 =>
        array (
          'label' => '10',
          'field' => '30',
        ),
        1 =>
        array (
          'label' => '10',
          'field' => '30',
        ),
      ),
      'useTabs' => false,
      'syncDetailEditViews' => false,
      'tabDefs' =>
      array (
        'DEFAULT' =>
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_LINE_ITEMS' =>
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
    ),
    'panels' =>
    array (
      'default' =>
      array (
        0 =>
        array (
          0 => 'name',
          1 =>
          array (
            'name' => 'contract_account',
            'label' => 'LBL_CONTRACT_ACCOUNT',
          ),
        ),
        1 =>
        array (
          0 =>
          array (
            'name' => 'coveragetype_c',
            'studio' => 'visible',
            'label' => 'LBL_COVERAGETYPE',
          ),
          1 =>
          array (
            'name' => 'opportunity',
            'label' => 'LBL_OPPORTUNITY',
          ),
        ),
        2 =>
        array (
          0 =>
          array (
            'name' => 'status',
            'studio' => 'visible',
            'label' => 'LBL_STATUS',
          ),
          1 =>
          array (
            'name' => 'assigned_user_name',
            'label' => 'LBL_ASSIGNED_TO_NAME',
          ),
        ),
        3 =>
        array (
          0 =>
          array (
            'name' => 'start_date',
            'label' => 'LBL_START_DATE',
          ),
          1 =>
          array (
            'name' => 'c_rate_c',
            'studio' => 'visible',
            'label' => 'LBL_C_RATE',
          ),
        ),
        4 =>
        array (
          0 =>
          array (
            'name' => 'end_date',
            'label' => 'LBL_END_DATE',
          ),
          1 =>
          array (
            'name' => 'direct_commission_c',
            'label' => 'LBL_DIRECT_COMMISSION',
          ),
        ),
        5 =>
        array (
          0 =>
          array (
            'name' => 'insurer_c',
            'studio' => 'visible',
            'label' => 'LBL_INSURER',
          ),
          1 =>
          array (
            'name' => 'payment_type_c',
            'studio' => 'visible',
            'label' => 'LBL_PAYMENT_TYPE',
          ),
        ),
        6 =>
        array (
          0 =>
          array (
            'name' => 'aos_invoices_aos_contracts_1_name',
          ),
        ),
        7 =>
        array (
          0 => '',
          1 => '',
        ),
      ),
      'lbl_line_items' =>
      array (
        0 =>
        array (
          0 =>
          array (
            'name' => 'line_items',
            'label' => 'LBL_LINE_ITEMS',
          ),
        ),
        1 =>
        array (
          0 =>
          array (
            'name' => 'products_amount_c',
            'label' => 'LBL_PRODUCTS_AMOUNT',
          ),
          1 =>
          array (
            'name' => 'premium_c',
            'label' => 'LBL_PREMIUM',
          ),
        ),
        2 =>
        array (
          0 =>
          array (
            'name' => 'products_tax_c',
            'label' => 'LBL_PRODUCTS_TAX',
          ),
          1 =>
          array (
            'name' => 'payable_premium_c',
            'label' => 'LBL_PAYABLE_PREMIUM',
          ),
        ),
        3 =>
        array (
          0 =>
          array (
            'name' => 'products_total_c',
            'label' => 'LBL_PRODUCTS_TOTAL',
          ),
          1 =>
          array (
            'name' => 'total_amt',
            'label' => 'LBL_TOTAL_AMT',
          ),
        ),
        4 =>
        array (
          0 => '',
          1 =>
          array (
            'name' => 'total_amount',
            'label' => 'LBL_GRAND_TOTAL',
          ),
        ),
        5 =>
        array (
          0 => '',
          1 => '',
        ),
        6 =>
        array (
          0 => '',
          1 => '',
        ),
      ),
    ),
  ),
);
?>
