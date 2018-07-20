<?php
// created: 2016-04-10 17:15:35
$searchdefs['AOS_Invoices'] = array (
  'templateMeta' => 
  array (
    'maxColumns' => '3',
    'widths' => 
    array (
      'label' => '10',
      'field' => '30',
    ),
    'maxColumnsBasic' => '3',
  ),
  'layout' => 
  array (
    'basic_search' => 
    array (
      0 => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      1 => 
      array (
        'name' => 'current_user_only',
        'label' => 'LBL_CURRENT_USER_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
      ),
      2 => 
      array (
        'type' => 'bool',
        'default' => true,
        'label' => 'LBL_EXISTS_QBO_C',
        'width' => '10%',
        'name' => 'exists_qbo_c',
      ),
    ),
    'advanced_search' => 
    array (
      0 => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      1 => 
      array (
        'name' => 'billing_contact',
        'default' => true,
        'width' => '10%',
      ),
      2 => 
      array (
        'name' => 'billing_account',
        'default' => true,
        'width' => '10%',
      ),
      3 => 
      array (
        'name' => 'number',
        'default' => true,
        'width' => '10%',
      ),
      4 => 
      array (
        'name' => 'total_amount',
        'default' => true,
        'width' => '10%',
      ),
      5 => 
      array (
        'name' => 'due_date',
        'default' => true,
        'width' => '10%',
      ),
      6 => 
      array (
        'name' => 'status',
        'default' => true,
        'width' => '10%',
      ),
      7 => 
      array (
        'name' => 'assigned_user_id',
        'type' => 'enum',
        'label' => 'LBL_ASSIGNED_TO',
        'function' => 
        array (
          'name' => 'get_user_array',
          'params' => 
          array (
            0 => false,
          ),
        ),
        'default' => true,
        'width' => '10%',
      ),
      8 => 
      array (
        'type' => 'bool',
        'default' => true,
        'label' => 'LBL_EXISTS_QBO_C',
        'width' => '10%',
        'name' => 'exists_qbo_c',
      ),
    ),
  ),
);