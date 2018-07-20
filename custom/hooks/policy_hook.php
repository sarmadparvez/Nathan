<?php

class policy_hook{

	function copy_acc_code(&$bean, $event, $arguments){

		if($bean->object_name == 'AOS_Contracts'){
			if( !empty($bean->contract_account_id) && !empty($bean->id) ){
				require_once('custom/hooks/policyfunc.php');
				setPolicyAccCode($bean->contract_account_id, $bean->id);
			}
		}
	}

}
