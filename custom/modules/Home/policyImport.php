<?php

die('<br/>Locked!<br/>');

//error_reporting(E_ALL);
//ini_set('display_errors', true);

$users = array(
	'Don'=>'d7d7d5b5-ba32-9779-e6d6-545039a192bd',
	'Frank'=>'e53f1bba-654b-51e1-3025-5501d20c28ce',
	'Dustin'=>'e33635e9-1b29-4e0a-ca38-54e0aab366a5',
	'SAM'=>'e53f1bba-654b-51e1-3025-5501d20c28ce',//frank
	'Naresh'=>'1fa39729-d01a-c9a4-7ccd-552eb6495366',
	'APP'=>'93f3830d-17dc-9bdd-2115-55bbfe732e05',//Darren
);

global $db;

$i = 0;

$sql = ' SELECT * FROM ax_import_policy WHERE processed = 0 LIMIT 0, 30; ';
$res = $db->query($sql);
while($row = $db->fetchByAssoc($res)){
	$user_id = '';
	$acc_id = '';
	$insurer_id = '';
	$eff_date = '';
	$exp_date = '';
	echo '<br/>'.(++$i).'----------------<br/>';
	
	echo '<br/>Date: '.date('Y-m-d',strtotime($row['ddate']));
	
	if(!empty(trim($row['eff_date']))){
		$date = DateTime::createFromFormat('d-m-y', $row['eff_date']);
		$eff_date = $date->format('Y-m-d');
	}
	echo '<br/>Eff: '.$eff_date;

	if(!empty(trim($row['exp_date']))){
		$date = DateTime::createFromFormat('d-m-y', $row['exp_date']);
		$exp_date = $date->format('Y-m-d');
	}
	echo '<br/>Exp: '.$exp_date;

	//echo '<br/>'.date('Y-m-d',strtotime($row['eff_date']));
	//echo '<br/>'.date('Y-m-d',strtotime($row['exp_date']));

	echo '<br/>Policy#: '.$row['policy_num'];
	echo '<br/>TotalAmount: '.$row['total_amount'];
	
	$clean_uid = trim($row['producer']);
	if(isset($users[$row['producer']])){
		$user_id = $users[$clean_uid];
	}
	echo '<br/>User: '.$row['producer'];
	echo '<br/>UserID: '.$user_id;
	
	
	$acc_id = retrieveByName($row['name']);
	if(empty($acc_id)){
		echo '<br/>NeedToCreate ACC';
		$acc = BeanFactory::newBean('Accounts');
		$acc->name = $row['name'];
		$acc->assigned_user_id = $user_id;
		$acc->save(false);
		$acc_id = $acc->id;
	}
	echo '<br/>AccNameID: '.$acc_id;
	echo '<br/>AccName: <b><a href="index.php?module=Accounts&action=DetailView&record='.$acc_id.'">'.$row['name'].'</a></b>';
	
	
	$insurer_id = getInsurerByName($row['insurer']);
	if(empty($insurer_id)){
		echo '<br/>NeedToCreate INS';
		$insurer = BeanFactory::newBean('insrr_Insurers');
		$insurer->name = $row['insurer'];
		$insurer->assigned_user_id = $user_id;
		$insurer->save(false);
		$insurer_id = $insurer->id;
	}
	echo '<br/>InsurerID: '.$insurer_id;
	echo '<br/>Insurer: <b><a href="index.php?module=insrr_Insurers&action=DetailView&record='.$insurer_id.'">'.$row['insurer'].'</a></b>';

	
	$policy = BeanFactory::newBean('AOS_Contracts');
	$policy->name = $row['policy_num'];
	$policy->assigned_user_id = $user_id;
	$policy->start_date = $eff_date;
	$policy->end_date = $exp_date;
	$policy->contract_account_id = $acc_id;
	$policy->insrr_insurers_id_c = $insurer_id;
	$policy->total_contract_value = $row['total_amount'];
	$policy->save(false);
	echo '<br/>Policy: <b><a href="index.php?module=AOS_Contracts&action=DetailView&record='.$policy->id.'">'.$policy->name.'</a></b>';
	
	echo '<br/>'.$sql = " UPDATE ax_import_policy SET processed = 1, policy_id = '{$policy->id}', account_id = '{$acc_id}', insurer_id = '{$insurer_id}'  WHERE id = '{$row['id']}'; ";
	echo '<br/>';
	if( !empty($policy->id) ){
		$db->query($sql);
	}
	
	
	//echo '<pre>';
	//print_r($row);
	//echo '</pre>';
}

function retrieveByName($name){
	$acc_id = '';
	global $db;
	$name = $db->quote(trim($name));
	$sql = " SELECT id FROM accounts WHERE name = '{$name}' AND deleted = 0 ; ";
	$res = $db->query($sql);
	if($row = $db->fetchByAssoc($res)){
		$acc_id = $row['id'];
	}
	
	return $acc_id;
}


function getInsurerByName($name){
	$bean_id = '';
	global $db;
	$name = $db->quote(trim($name));
	$sql = " SELECT id FROM insrr_insurers WHERE name = '{$name}' AND deleted = 0 ; ";
	$res = $db->query($sql);
	if($row = $db->fetchByAssoc($res)){
		$bean_id = $row['id'];
	}
	
	return $bean_id;
}



die('<br/>+<br/>');
