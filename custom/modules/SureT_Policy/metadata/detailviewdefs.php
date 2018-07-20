<?php
$module_name = 'SureT_Policy';
$viewdefs [$module_name] = 
array (
  'DetailView' => 
  array (
    'templateMeta' => 
    array (
      'form' => 
      array (
        'buttons' => 
        array (
          0 => 'EDIT',
          1 => 'DUPLICATE',
          2 => 'DELETE',
          3 => 'FIND_DUPLICATES',
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
      'tabDefs' => 
      array (
        'DEFAULT' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL1' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
      'syncDetailEditViews' => true,
    ),
    'panels' => 
    array (
      'default' => 
      array (
        0 => 
        array (
          0 => 'name',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'effective_date',
            'label' => 'LBL_EFFECTIVE_DATE',
          ),
          1 => 
          array (
            'name' => 'expiration_date',
            'label' => 'LBL_EXPIRATION_DATE',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'suret_policy_accounts_name',
          ),
          1 => 
          array (
            'name' => 'temp_account_c',
            'label' => 'LBL_TEMP_ACCOUNT',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'insrr_insurers_suret_policy_name',
          ),
          1 => 
          array (
            'name' => 'temp_insurer_c',
            'label' => 'LBL_TEMP_INSURER',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'temp_mga_c',
            'label' => 'LBL_TEMP_MGA',
          ),
          1 => 
          array (
            'name' => 'mga',
            'studio' => 'visible',
            'label' => 'LBL_MGA',
          ),
        ),
        5 => 
        array (
          0 => 'assigned_user_name',
          1 => 
          array (
            'name' => 'temp_pr_c',
            'label' => 'LBL_TEMP_PR',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'status',
            'studio' => 'visible',
            'label' => 'LBL_STATUS',
          ),
          1 => 
          array (
            'name' => 'suret_insurer_suret_policy_name',
          ),
        ),
        7 => 
        array (
          0 => 'description',
        ),
      ),
      'lbl_editview_panel1' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'policy_type',
            'studio' => 'visible',
            'label' => 'LBL_POLICY_TYPE',
          ),
          1 => 
          array (
            'name' => 'subtype',
            'studio' => 'visible',
            'label' => 'LBL_SUBTYPE',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'bond_amount',
            'label' => 'LBL_BOND_AMOUNT',
          ),
          1 => 
          array (
            'name' => 'currency_id',
            'studio' => 'visible',
            'label' => 'LBL_CURRENCY',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'commission_rate',
            'label' => 'LBL_COMMISSION_RATE',
          ),
          1 => 
          array (
            'name' => 'mga_fee',
            'label' => 'LBL_MGA_FEE',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'filing_fees',
            'label' => 'LBL_FILING_FEES',
          ),
          1 => 
          array (
            'name' => 'filing_fees_tax',
            'label' => 'LBL_FILING_FEES_TAX',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'premium_c',
            'label' => 'LBL_PREMIUM',
          ),
          1 => 
          array (
            'name' => 'tax',
            'label' => 'LBL_TAX',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'payment_recieved',
            'label' => 'LBL_PAYMENT_RECIEVED',
          ),
          1 => 
          array (
            'name' => 'payment_type',
            'studio' => 'visible',
            'label' => 'LBL_PAYMENT_TYPE',
          ),
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
