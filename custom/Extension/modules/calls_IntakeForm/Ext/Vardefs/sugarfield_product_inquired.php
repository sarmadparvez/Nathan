<?php
 // created: 2017-07-26 02:11:07
 //$dictionary['calls_IntakeForm']['fields']['product_inquired']['options']='patched_to_list';

 $dictionary['calls_IntakeForm']['fields']['product_inquired'] = array (
    'name' => 'product_inquired',
    'vname' => 'LBL_PRODUCT_INQUIRED',
    'type' => 'enum',
    'len' => 100,
     //  'options'=>'mr_mrs_list',
    'function' => 'getProducts',
    'duplicate_merge' => 'disabled',
    'required' => false,
    'studio'=>array( 'listview' => true,
        'detailview' => true,
        'editview' => true),
);

 ?>
