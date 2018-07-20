<?php
$module_name = 'AOS_Product_Categories';
$viewdefs [$module_name] = 
array (
  'EditView' => 
  array (
    'templateMeta' => 
    array (
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
    ),
    'panels' => 
    array (
      'default' => 
      array (
        0 => 
        array (
          0 => 'name',
          1 => 'assigned_user_name',
        ),
        1 => 
        array (
          0 => 'description',
          1 => '',
        ),
      ),
      'lbl_editview_panel1' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'lead_cost_c',
            'label' => 'LBL_LEAD_COST',
          ),
          1 => '',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'lead_value_c',
            'label' => 'LBL_LEAD_VALUE',
          ),
          1 => '',
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'lead_conv_rate_c',
            'label' => 'LBL_LEAD_CONV_RATE',
          ),
          1 => '',
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'opp_cost_c',
            'label' => 'LBL_OPP_COST',
          ),
          1 => '',
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'opp_value_c',
            'label' => 'LBL_OPP_VALUE',
          ),
          1 => '',
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'opp_sale_conv_c',
            'label' => 'LBL_OPP_SALE_CONV',
          ),
          1 => '',
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'premium_per_policy_c',
            'label' => 'LBL_PREMIUM_PER_POLICY',
          ),
          1 => '',
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'include_in_routing_list_c',
            'label' => 'LBL_INCLUDE_IN_ROUTING_LIST',
          ),
        ),
      ),
    ),
  ),
);
?>
