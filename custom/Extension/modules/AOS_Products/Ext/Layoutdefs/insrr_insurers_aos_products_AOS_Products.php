<?php
 // created: 2015-04-06 17:34:37
$layout_defs["AOS_Products"]["subpanel_setup"]['insrr_insurers_aos_products'] = array (
  'order' => 100,
  'module' => 'insrr_Insurers',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_INSRR_INSURERS_AOS_PRODUCTS_FROM_INSRR_INSURERS_TITLE',
  'get_subpanel_data' => 'insrr_insurers_aos_products',
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