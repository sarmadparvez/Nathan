<?php
$module_name = 'ax_PCommission';
$searchdefs [$module_name] = 
array (
  'layout' => 
  array (
    'basic_search' => 
    array (
      'current_user_only' => 
      array (
        'name' => 'current_user_only',
        'label' => 'LBL_CURRENT_USER_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
      ),
      'assigned_user_name' => 
      array (
        'link' => true,
        'type' => 'relate',
        'studio' => 'visible',
        'label' => 'LBL_ASSIGNED_TO_NAME',
        'id' => 'ASSIGNED_USER_ID',
        'width' => '10%',
        'default' => true,
        'name' => 'assigned_user_name',
      ),
      'producer2' => 
      array (
        'type' => 'relate',
        'studio' => 'visible',
        'label' => 'LBL_PRODUCER2',
        'id' => 'USER_ID_C',
        'link' => true,
        'width' => '10%',
        'default' => true,
        'name' => 'producer2',
      ),
      'ax_pcommission_aos_contracts_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_AX_PCOMMISSION_AOS_CONTRACTS_FROM_AOS_CONTRACTS_TITLE',
        'id' => 'AX_PCOMMISSION_AOS_CONTRACTSAOS_CONTRACTS_IDA',
        'width' => '10%',
        'default' => true,
        'name' => 'ax_pcommission_aos_contracts_name',
      ),
    ),
    'advanced_search' => 
    array (
      0 => 'name',
      1 => 
      array (
        'name' => 'assigned_user_id',
        'label' => 'LBL_ASSIGNED_TO',
        'type' => 'enum',
        'function' => 
        array (
          'name' => 'get_user_array',
          'params' => 
          array (
            0 => false,
          ),
        ),
      ),
    ),
  ),
  'templateMeta' => 
  array (
    'maxColumns' => '3',
    'maxColumnsBasic' => '4',
    'widths' => 
    array (
      'label' => '10',
      'field' => '30',
    ),
  ),
);
?>
