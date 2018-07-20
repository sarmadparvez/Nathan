<?php
 // created: 2015-04-06 17:34:37
$layout_defs["insrr_Insurers"]["subpanel_setup"]['insrr_insurers_aos_product_categories'] = array (
  'order' => 100,
  'module' => 'AOS_Product_Categories',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_INSRR_INSURERS_AOS_PRODUCT_CATEGORIES_FROM_AOS_PRODUCT_CATEGORIES_TITLE',
  'get_subpanel_data' => 'insrr_insurers_aos_product_categories',
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
