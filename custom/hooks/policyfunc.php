<?php

function setPolicyAccCode($account_id, $policy_id){
	global $db;
	
	if(!empty($account_id)&&!empty($policy_id)){
		$res = $db->query(" SELECT account_code_c FROM accounts_cstm WHERE id_c = '{$account_id}'; ");
		if($row = $db->fetchByAssoc($res)){
			if(isset($row['account_code_c']) && !empty($row['account_code_c'])){
				updPolicyAccCode($policy_id, $row['account_code_c']);
				//$db->query(" UPDATE aos_contracts_cstm SET acc_code_c = '{$row['account_code_c']}' WHERE id_c = '{$policy_id}'; ");
			}
		}
	}
}

function updPolicyAccCode($policy_id, $account_code){
	global $db;
	$account_code = $db->quote($account_code);
	$policy_id = $db->quote($policy_id);
	if(	!empty($account_code) && !empty($policy_id) ){
		$db->query(" UPDATE aos_contracts_cstm SET acc_code_c = '{$account_code}' WHERE id_c = '{$policy_id}'; ");
	}
}

//set account code for policies where its empty
function policyMassAccCode(){
	$sql = " SELECT a.id_c as policy_id FROM aos_contracts_cstm as a
		LEFT JOIN aos_contracts as b ON a.id_c = b.id
		WHERE a.acc_code_c = '' OR a.acc_code_c IS NULL AND b.deleted = 0  ; ";
	$res = $db->query($sql);
	$i = 0;
	while($row = $db->fetchByAssoc($res)){
		$policy = BeanFactory::getBean('AOS_Contracts', $row['policy_id']);
		echo '<br/>'.$i++;
		echo '---'.$policy->name.' Acc_id:'.$policy->contract_account_id;
		if(!empty($policy->contract_account_id)){
			setPolicyAccCode($policy->contract_account_id, $policy->id);
		}
	}
}

function updPoliciesAccCode($account_id, $account_code){
	global $db;
	$account_code = $db->quote($account_code);
	$account_id = $db->quote($account_id);
	if(!empty($account_id)){
		$res = $db->query(" SELECT id FROM aos_contracts WHERE  contract_account_id = '{$account_id}' AND deleted = 0 ;");
		while($row = $db->fetchByAssoc($res)){
			updPolicyAccCode($row['id'], $account_code);
		}
	}
}
