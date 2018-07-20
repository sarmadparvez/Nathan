<?php
 // created: 2015-04-06 17:34:37
$layout_defs["insrr_Insurers"]["subpanel_setup"]['insrr_insurers_suret_policy'] = array (
  'order' => 100,
  'module' => 'SureT_Policy',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_INSRR_INSURERS_SURET_POLICY_FROM_SURET_POLICY_TITLE',
  'get_subpanel_data' => 'insrr_insurers_suret_policy',
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
