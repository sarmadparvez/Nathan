<?php

class qbo{
	
	static function do_creditmemo($record_id){
		
		$crm_invoice = BeanFactory::getBean('AOS_Invoices', $record_id);
		
		if( empty($crm_invoice->id) ){
			echo 'Error: unable to retrieve Invoice data';
			return false;
		}
		
		if( !empty($crm_invoice->is_creditmemo_c) ){
			echo '<br/>CreditMemo is not supported for "Export" operation.<br/>';
			echo '<br/><a href="index.php?module=AOS_Invoices&action=DetailView&record='.$crm_invoice->id.'">Go back</a>';
			return false;
		}
	
		$tbe_qbo = BeanFactory::getBean('tbe_qbo');
			
		if($tbe_qbo->isAllowedQBO()){
			$tbe_qbo->retrieveSetting();
			if(empty($tbe_qbo->access_token)){
				  echo '<br/>Seems you havent connected with QBO';
				  exit;
			}
		}
		
		
		
		
		
		
		
	}//do_creditmemo end
	
}