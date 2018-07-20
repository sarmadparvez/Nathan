<?php

	global $db;
	
	$date = new DateTime();
	$date->modify('+1 month');
	$start_date = $date->format('Y-m-01');
	$end_date = $date->format('Y-m-t');

	//$test_opp_id = 'b727c7bd-7659-9749-60bf-5637f87d0a32';
	//$test_policy_id = '7962e127-5793-6e1b-8a47-5637f2f5c53c';

	$sql = " SELECT a.id, a.name, a.end_date, o.suret_policy_id_c ";
	$sql .= " FROM aos_contracts as a ";
	$sql .= " LEFT JOIN opportunities_cstm as o ON o.suret_policy_id_c = a.id ";
	$sql .= " WHERE a.deleted = 0 AND a.end_date >= '{$start_date}' AND a.end_date <= '{$end_date}' AND o.suret_policy_id_c IS NULL  ORDER BY a.end_date ASC; ";//
	
	$res = $db->query($sql);
	while( $row = $db->fetchByAssoc($res) ){
		echo '<pre>';
		print_r($row);
		echo '</pre>';
	}
	
	die('<br/><br/>R<br/><br/>');

	$res = $db->query($sql);
	while( $row = $db->fetchByAssoc($res) ){
		$policy = BeanFactory::getBean('AOS_Contracts', $row['id']);
		if( !empty($policy->id) ){
			//echo '<br/>----------------';
			//echo '<br/>PolicyID: '.$policy->id;
			//echo '<br/>PolicyNum: '.$policy->name;
			//echo '<br/>end_date: '.$policy->end_date;
			//echo '<br/>contract_account: '.$policy->contract_account;
			//echo '<br/>total_contract_value: '.$policy->total_contract_value;
			//echo '<br/>oldOppID: '.$policy->opportunity_id;
			//echo '<br/>'.$row['end_date'].' '.$row['name'];
			
			$opp = BeanFactory::newBean('Opportunities');
			$opp->name = $policy->contract_account;
			$opp->account_id = $policy->contract_account_id;
			$opp->assigned_user_id = $policy->assigned_user_id;
			//$opp->assigned_user_id = '3c4e92c9-c147-2849-0c10-54f4b2c4a466';
			$opp->amount = $policy->total_contract_value;
			$opp->date_closed = $policy->end_date;
			$opp->sales_stage = 'Proposal/Price Quote';
			$opp->opportunity_type = 'Existing Business';
			$opp->suret_policy_id_c = $policy->id;
			$opp->save(false);
			
			echo '<br/><b>newOppID: '.$opp->id.'</b>';
			//$policy->opportunity_id = $opp->id;
		}
	}
