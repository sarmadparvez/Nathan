<?php

class acc_hook{
	function genCode(&$bean, $event, $arguments){
		
		if(empty($bean->account_code_c)){
			$_code = '';
			
			require_once('custom/hooks/accfunc.php');
			$code = genAccCode($bean->name);
			
			$bean->account_code_c = $code['base'].$code['n'];
			$bean->acode_base_c = $code['base'];
			$bean->acode_sequence_c = $code['n'];
		}
		
	}
	
	function updPoliciesAccCode(&$bean, $event, $arguments){
		if($bean->account_code_c){
			require_once('custom/hooks/policyfunc.php');
			updPoliciesAccCode($bean->id, $bean->account_code_c);
		}
	}

}