<?php
$dashletData['LeadsDashlet']['searchFields'] = array (
  'date_entered' => 
  array (
    'default' => '',
  ),
  'title' => 
  array (
    'default' => '',
  ),
  'primary_address_country' => 
  array (
    'default' => '',
  ),
  'assigned_user_id' => 
  array (
    'default' => '',
  ),
  'status' => 
  array (
    'default' => '',
  ),
);
$dashletData['LeadsDashlet']['columns'] = array (
  'name' => 
  array (
    'width' => '30',
    'label' => 'LBL_NAME',
    'link' => true,
    'default' => true,
    'related_fields' => 
    array (
      0 => 'first_name',
      1 => 'last_name',
      2 => 'salutation',
    ),
  ),
  'title' => 
  array (
    'width' => '20',
    'label' => 'LBL_TITLE',
    'default' => true,
  ),
  'lead_source' => 
  array (
    'width' => '10',
    'label' => 'LBL_LEAD_SOURCE',
  ),
  'status' => 
  array (
    'width' => '10',
    'label' => 'LBL_STATUS',
  ),
  'phone_work' => 
  array (
    'width' => '20',
    'label' => 'LBL_OFFICE_PHONE',
    'default' => true,
  ),
  'email1' => 
  array (
    'width' => '30',
    'label' => 'LBL_EMAIL_ADDRESS',
    'sortable' => false,
    'customCode' => '{$EMAIL1_LINK}{$EMAIL1}</a>',
    'default' => true,
  ),
  'account_name' => 
  array (
    'width' => '40',
    'label' => 'LBL_ACCOUNT_NAME',
  ),
  'phone_home' => 
  array (
    'width' => '10',
    'label' => 'LBL_HOME_PHONE',
  ),
  'phone_mobile' => 
  array (
    'width' => '10',
    'label' => 'LBL_MOBILE_PHONE',
  ),
  'phone_other' => 
  array (
    'width' => '10',
    'label' => 'LBL_OTHER_PHONE',
  ),
  'date_entered' => 
  array (
    'width' => '15',
    'label' => 'LBL_LIST_DATE_ENTERED',
  ),
  'date_modified' => 
  array (
    'width' => '15',
    'label' => 'LBL_DATE_MODIFIED',
  ),
  'created_by' => 
  array (
    'width' => '8',
    'label' => 'LBL_CREATED',
  ),
  'assigned_user_name' => 
  array (
    'width' => '8',
    'label' => 'LBL_LIST_ASSIGNED_USER',
  ),
);
