<?php

class invoice_hook{

	function gen_name(&$bean, $event, $arguments){
		//$bean->name = 'N/a';
		if( !empty($bean->invoice_date) ){
			if(!$bean->is_creditmemo_c){
				$date = DateTime::createFromFormat('Y-m-d', $bean->invoice_date);
				$year = $date->format('y');
				$num = str_pad($bean->number, 5, "0", STR_PAD_LEFT);
				$bean->invoice_no_c = 'AI'.$year.$num;
				$bean->name = $bean->invoice_no_c;
			}
		}
		
		if( empty($bean->acc_code_c) && !empty($bean->billing_account_id) ){
			$res = $bean->db->query(" SELECT account_code_c FROM accounts_cstm WHERE id_c = '{$bean->billing_account_id}'; ");
			if($row = $bean->db->fetchByAssoc($res)){
				if(isset($row['account_code_c']) && !empty($row['account_code_c'])){
					$bean->acc_code_c = $row['account_code_c'];
				}
			}
		}
		
	}

}