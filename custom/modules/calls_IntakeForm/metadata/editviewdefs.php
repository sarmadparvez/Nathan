<?php
$module_name = 'calls_IntakeForm';
$viewdefs [$module_name] = 
array (
  'EditView' => 
  array (
    'templateMeta' => 
    array (
      'maxColumns' => '2',
      'widths' => 
      array (
        0 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
        1 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
      ),
      'useTabs' => false,
      'tabDefs' => 
      array (
        'DEFAULT' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
    ),
    'panels' => 
    array (
      'default' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'last_name',
            'label' => 'LBL_LAST_NAME',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'caller',
            'label' => 'LBL_CALLER',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'patched_to',
            'studio' => 
            array (
              'listview' => true,
              'detailview' => true,
              'editview' => true,
            ),
            'label' => 'LBL_PATCHED_TO',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'product_inquired',
            'studio' => 'visible',
            'label' => 'LBL_PRODUCT_INQUIRED',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'email',
            'label' => 'LBL_EMAIL',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'call_body',
            'studio' => 'visible',
            'label' => 'LBL_CALL_BODY',
          ),
        ),
        6 => 
        array (
          0 => 'description',
        ),
        7 => 
        array (
          0 => '',
          1 => '',
        ),
      ),
    ),
  ),
);
?>
