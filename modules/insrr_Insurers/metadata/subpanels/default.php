<?php
$module_name='insrr_Insurers';
$subpanel_layout = array (
  'top_buttons' => 
  array (
    0 => 
    array (
      'widget_class' => 'SubPanelTopCreateButton',
    ),
    1 => 
    array (
      'widget_class' => 'SubPanelTopSelectButton',
      'popup_module' => 'insrr_Insurers',
    ),
  ),
  'where' => '',
  'list_fields' => 
  array (
    'name' => 
    array (
      'vname' => 'LBL_NAME',
      'widget_class' => 'SubPanelDetailViewLink',
      'width' => '45%',
      'default' => true,
    ),
    'industry' => 
    array (
      'vname' => 'LBL_INDUSTRY',
      'width' => '15%',
      'default' => true,
    ),
    'phone_office' => 
    array (
      'vname' => 'LBL_PHONE_OFFICE',
      'width' => '15%',
      'default' => true,
    ),
    'assigned_user_name' => 
    array (
      'name' => 'assigned_user_name',
      'vname' => 'LBL_ASSIGNED_USER',
      'widget_class' => 'SubPanelDetailViewLink',
      'target_record_key' => 'assigned_user_id',
      'target_module' => 'Employees',
      'width' => '10%',
      'default' => true,
    ),
    'edit_button' => 
    array (
      'vname' => 'LBL_EDIT_BUTTON',
      'widget_class' => 'SubPanelEditButton',
      'module' => 'insrr_Insurers',
      'width' => '4%',
      'default' => true,
    ),
    'remove_button' => 
    array (
      'vname' => 'LBL_REMOVE',
      'widget_class' => 'SubPanelRemoveButton',
      'module' => 'insrr_Insurers',
      'width' => '5%',
      'default' => true,
    ),
  ),
);