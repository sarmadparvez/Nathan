<?php
 // created: 2015-04-06 17:34:37
$layout_defs["insrr_Insurers"]["subpanel_setup"]['insrr_insurers_contacts'] = array (
  'order' => 100,
  'module' => 'Contacts',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_INSRR_INSURERS_CONTACTS_FROM_CONTACTS_TITLE',
  'get_subpanel_data' => 'insrr_insurers_contacts',
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
