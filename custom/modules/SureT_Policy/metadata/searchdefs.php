<?php
$module_name = 'SureT_Policy';
$searchdefs [$module_name] = 
array (
  'layout' => 
  array (
    'basic_search' => 
    array (
      0 => 'name',
      1 => 
      array (
        'name' => 'current_user_only',
        'label' => 'LBL_CURRENT_USER_FILTER',
        'type' => 'bool',
      ),
    ),
    'advanced_search' => 
    array (
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'temp_pr_c' => 
      array (
        'type' => 'varchar',
        'default' => true,
        'label' => 'LBL_TEMP_PR',
        'width' => '10%',
        'name' => 'temp_pr_c',
      ),
      'temp_insurer_c' => 
      array (
        'type' => 'varchar',
        'default' => true,
        'label' => 'LBL_TEMP_INSURER',
        'width' => '10%',
        'name' => 'temp_insurer_c',
      ),
      'insrr_insurers_suret_policy_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_INSRR_INSURERS_SURET_POLICY_FROM_INSRR_INSURERS_TITLE',
        'id' => 'INSRR_INSURERS_SURET_POLICYINSRR_INSURERS_IDA',
        'width' => '10%',
        'default' => true,
        'name' => 'insrr_insurers_suret_policy_name',
      ),
      'assigned_user_id' => 
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
        'default' => true,
        'width' => '10%',
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
