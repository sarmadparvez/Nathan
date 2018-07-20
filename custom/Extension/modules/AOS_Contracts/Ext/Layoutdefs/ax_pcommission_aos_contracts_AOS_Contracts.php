<?php
 // created: 2015-07-30 14:20:49
$layout_defs["AOS_Contracts"]["subpanel_setup"]['ax_pcommission_aos_contracts'] = array (
  'order' => 100,
  'module' => 'ax_PCommission',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_AX_PCOMMISSION_AOS_CONTRACTS_FROM_AX_PCOMMISSION_TITLE',
  'get_subpanel_data' => 'ax_pcommission_aos_contracts',
  'top_buttons' => 
  array (
    0 => 
    array (
      'widget_class' => 'SubPanelTopButtonQuickCreate',
    ),
    1 => 
    array (
      'widget_class' => 'SubPanelTopSelectButton',
      'mode' => 'MultiSelect',
    ),
  ),
);
