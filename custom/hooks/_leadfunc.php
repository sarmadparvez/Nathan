<?php

class leadfunc{
	static public function setDead($lead_id){
		$lead = BeanFactory::getBean('Leads', $lead_id);
		$lead->status = 'Dead';
		$lead->save(false);
	}
	static public function do_convert($lead_id){
		$result_id = $lead_id;
		$module = 'Leads';
		$lead = BeanFactory::getBean('Leads', $lead_id);
		if($lead->converted == false){
			$account = BeanFactory::getBean('Accounts');
			$account->name = empty($lead->account_name)?($lead->first_name.' '.$lead->last_name):$lead->account_name;//account_name
			$account->assigned_user_id = $lead->assigned_user_id;
			$account->phone_office = $lead->phone_work;
			$account->email1 = $lead->email1;//do we need this?
			$account->save(false);

			$contact = BeanFactory::getBean('Contacts');
			$contact->last_name = $lead->last_name;
			$contact->first_name = $lead->first_name;
			$contact->phone_work = $lead->phone_work;
			$contact->lead_source = $lead->lead_source;
			$contact->account_id = $account->id;
			$contact->email1 = $lead->email1;
			$contact->save(false);

			$opportunity = BeanFactory::getBean('Opportunities');
			$opportunity->name = $lead->last_name;
			$opportunity->assigned_user_id = $lead->assigned_user_id;
			$opportunity->account_id = $account->id;
			$opportunity->contact_id = $contact->id;//check this
			$opportunity->sales_stage = 'Prospecting';
			$opportunity->opportunity_type = 'New Business';
			$opportunity->save(false);

			$lead->converted = true;
			$lead->status = 'Converted';
			$lead->account_id = $account->id;
			$lead->contact_id = $contact->id;
			$lead->opportunity_id = $opportunity->id;
			$lead->save(false);
			
			$module = 'Opportunities';
			$result_id = $opportunity->id;
		}
		return array('module'=>$module,'id'=>$result_id);
	}
	static public function get_time_difference( $start, $end){
		$uts['start'] = strtotime($start);
		$uts['end'] = strtotime($end);
		if( $uts['start']!==-1 && $uts['end']!==-1 ){
			if( $uts['end'] >= $uts['start'] ){
				$diff =  $uts['end'] - $uts['start'];
				if( $days=intval((floor($diff/86400))) )
					$diff = $diff % 86400;
				if( $hours=intval((floor($diff/3600))) )
					$diff = $diff % 3600;
				if( $minutes=intval((floor($diff/60))) )
					$diff = $diff % 60;
				$diff    =    intval( $diff );            
				return( array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
			}
		}
		return ( false );
	}	
}