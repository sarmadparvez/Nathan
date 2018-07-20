<?php


//$credit_memo_id = '35ad3e40-c6ba-526a-8dc6-5717927aadd2';
//$cm =  BeanFactory::getBean('AOS_Invoices', $credit_memo_id);
//$cm->is_creditmemo_c = 1;
//$cm->save(false);

$tbe_qbo = BeanFactory::getBean('tbe_qbo');

$tbe_qbo->retrieveSetting();

echo 'sdk path: '.$tbe_qbo->sdk_path;



//$tbe_qbo = BeanFactory::getBean('tbe_qbo');

$tbe_qbo->name = 'Test';
$tbe_qbo->description = '';
$tbe_qbo->error_msg = '';
$tbe_qbo->parent_id = '';
$tbe_qbo->assigned_user_id = '';
$tbe_qbo->save(false);


echo '+';