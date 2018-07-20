<?php

//error_reporting(E_ALL);
//ini_set('display_errors', true);

/*
$_id = '12de7d9a-3ad4-070b-774f-551c539fedf3';

$oldPolicy = BeanFactory::getBean('SureT_Policy', $_id);
$NEWpolicy = BeanFactory::getBean('AOS_Contracts');

echo '<br/>NAME:';
echo $oldPolicy->name;

echo '<br/>ACC:';
echo $oldPolicy->suret_policy_accountsaccounts_ida;//ACC

echo '<br/>INS:';
echo $oldPolicy->insrr_insurers_suret_policyinsrr_insurers_ida;


	$NEWpolicy->name = $oldPolicy->name;
	$NEWpolicy->start_date = $oldPolicy->effective_date;
	$NEWpolicy->end_date = $oldPolicy->expiration_date;
	$NEWpolicy->contract_account_id = $oldPolicy->suret_policy_accountsaccounts_ida;//ACC
	$NEWpolicy->insrr_insurers_id_c = $oldPolicy->insrr_insurers_suret_policyinsrr_insurers_ida;//INSURER


	$NEWpolicy->save(false);
	echo '<br/><br/><a href="index.php?module=AOS_Contracts&action=DetailView&record='.$NEWpolicy->id.'">'.$NEWpolicy->name.'</a>';

*/
die('R');
global $db;

$i = 0;

$sql = " SELECT id FROM suret_policy WHERE deleted = 0 ORDER BY date_entered ASC LIMIT 291, 50; ";

$res = $db->query($sql);

while($row = $db->fetchByAssoc($res)){
	$i++;
	echo '<br/>'.$i.') '.$row['id'];

	$oldPolicy = BeanFactory::getBean('SureT_Policy', $row['id']);
	$NEWpolicy = BeanFactory::getBean('AOS_Contracts');

	$NEWpolicy->name = $oldPolicy->name;
	$NEWpolicy->start_date = $oldPolicy->effective_date;
	$NEWpolicy->end_date = $oldPolicy->expiration_date;
	$NEWpolicy->contract_account_id = $oldPolicy->suret_policy_accountsaccounts_ida;//ACC
	$NEWpolicy->insrr_insurers_id_c = $oldPolicy->insrr_insurers_suret_policyinsrr_insurers_ida;//INSURER
	$NEWpolicy->mga_c = $oldPolicy->mga;
	$NEWpolicy->status = $oldPolicy->status;
	$NEWpolicy->description = $oldPolicy->description;
	$NEWpolicy->total_contract_value = $oldPolicy->premium_c;//!
	$NEWpolicy->currency_id = $oldPolicy->currency_id;
	$NEWpolicy->payment_type_c = $oldPolicy->payment_type;
	$NEWpolicy->assigned_user_id = $oldPolicy->assigned_user_id;
	$NEWpolicy->save(false);
	echo '---->>><a href="index.php?module=AOS_Contracts&action=DetailView&record='.$NEWpolicy->id.'">'.$NEWpolicy->name.'</a>';

}

