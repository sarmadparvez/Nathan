<?php
 // created: 2015-04-07 17:22:45
$layout_defs["KWRDS_Keywords"]["subpanel_setup"]['kwrds_keywords_leads_1'] = array (
  'order' => 100,
  'module' => 'Leads',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_KWRDS_KEYWORDS_LEADS_1_FROM_LEADS_TITLE',
  'get_subpanel_data' => 'kwrds_keywords_leads_1',
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
