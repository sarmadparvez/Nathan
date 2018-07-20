<?php
 // created: 2015-04-01 15:33:18
$layout_defs["Users"]["subpanel_setup"]['suret_policy_users'] = array (
  'order' => 100,
  'module' => 'SureT_Policy',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_SURET_POLICY_USERS_FROM_SURET_POLICY_TITLE',
  'get_subpanel_data' => 'suret_policy_users',
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
