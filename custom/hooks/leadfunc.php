<?php

class leadfunc{

	static public function processActivities($lead_obj, $acc_obj){
		global $app_list_strings, $sugar_config, $app_strings;

		if( ( $sugar_config['lead_conv_activity_opt'] != 'copy' ) && ( $sugar_config['lead_conv_activity_opt'] != 'move' ) ){
			return false;//do nothing
		}

		$accountParentInfo = array('id' => $acc_obj->id, 'type' => 'Accounts');

		$activities = self::getActivitiesFromLead($lead_obj);

		foreach($activities as $activity){
			if( $sugar_config['lead_conv_activity_opt'] == 'copy' ){
				self::copyActivityAndRelateToBean($activity, $acc_obj, $accountParentInfo);
			}elseif($sugar_config['lead_conv_activity_opt'] == 'move'){
				self::moveActivity($activity, $acc_obj);//Move only to one module at once!
			}
		}

		return true;
	}


	static function copyActivityAndRelateToBean($activity, $bean, $parentArr = array() ){
		global $beanList;

		$newActivity = clone $activity;
		$newActivity->id = create_guid();
		$newActivity->new_with_id = true;

        //set the parent id and type if it was passed in, otherwise use blank to wipe it out
        $parentID = '';
        $parentType = '';
        if(!empty($parentArr)){
            if(!empty($parentArr['id'])){
                $parentID = $parentArr['id'];
            }

            if(!empty($parentArr['type'])){
                $parentType = $parentArr['type'];
            }

        }

		//Special case to prevent duplicated tasks from appearing under Contacts multiple times
    	if ($newActivity->module_dir == "Tasks" && $bean->module_dir != "Contacts")
    	{
            $newActivity->contact_id = $newActivity->contact_name = "";
    	}

		if ($rel = self::findRelationship($newActivity, $bean))
        {
            if (isset($newActivity->$rel))
            {
                // this comes form $activity, get rid of it and load our own
                $newActivity->$rel = '';
            }

            $newActivity->load_relationship ($rel) ;
            $relObj = $newActivity->$rel->getRelationshipObject();
            if ( $relObj->relationship_type=='one-to-one' || $relObj->relationship_type == 'one-to-many' )
            {
                $key = $relObj->rhs_key;
                $newActivity->$key = $bean->id;
            }

            //parent (related to field) should be blank unless it is explicitly sent in
            //it is not sent in unless the account is being created as well during lead conversion
            $newActivity->parent_id =  $parentID;
            $newActivity->parent_type = $parentType;

	        $newActivity->update_date_modified = false; //bug 41747
	        $newActivity->save();
            $newActivity->$rel->add($bean);
            if ($newActivity->module_dir == "Notes" && $newActivity->filename) {
	        	UploadFile::duplicate_file($activity->id, $newActivity->id,  $newActivity->filename);
	        }
         }
	}

	static function moveActivity($activity, $bean){
        global $beanList;

        $lead = null;
        if (!empty($_REQUEST['record']))
        {
            $lead = new Lead();
            $lead->retrieve($_REQUEST['record']);
        }

        // delete the old relationship to the old parent (lead)
        if ($rel = self::findRelationship($activity, $lead)) {
            $activity->load_relationship ($rel) ;

            if ($activity->parent_id && $activity->id) {
                $activity->$rel->delete($activity->id, $activity->parent_id);
            }
        }

        // add the new relationship to the new parent (contact, account, etc)
        if ($rel = self::findRelationship($activity, $bean)) {
            $activity->load_relationship ($rel) ;

            $relObj = $activity->$rel->getRelationshipObject();
            if ( $relObj->relationship_type=='one-to-one' || $relObj->relationship_type == 'one-to-many' )
            {
                $key = $relObj->rhs_key;
                $activity->$key = $bean->id;
            }
            $activity->$rel->add($bean);
        }

        // set the new parent id and type
        $activity->parent_id = $bean->id;
        $activity->parent_type = $bean->module_dir;

        $activity->save();
    }

	static public function getActivitiesFromLead($lead){
		if (!$lead) return;

		global $beanList, $db;

		$activitesList = array("Calls", "Tasks", "Meetings", "Emails", "Notes");
		$activities = array();

		foreach($activitesList as $module){
			$beanName = $beanList[$module];
			$activity = new $beanName();
			$query = "SELECT id FROM {$activity->table_name} WHERE parent_id = '{$lead->id}' AND parent_type = 'Leads' AND deleted = 0";
			$result = $db->query($query,true);
            while($row = $db->fetchByAssoc($result)){
            	$activity = new $beanName();
				$activity->retrieve($row['id']);
				$activity->fixUpFormatting();
				$activities[] = $activity;
            }
		}

		return $activities;
	}

	static function findRelationship($from, $to){
    	global $dictionary;
    	require_once("modules/TableDictionary.php");
    	foreach ($from->field_defs as $field=>$def){
            if (isset($def['type']) && $def['type'] == "link" && isset($def['relationship']))
			{
                $rel_name = $def['relationship'];
                $rel_def = "";
                if (isset($dictionary[$from->object_name]['relationships']) && isset($dictionary[$from->object_name]['relationships'][$rel_name]))
                {
                    $rel_def = $dictionary[$from->object_name]['relationships'][$rel_name];
                }
                else if (isset($dictionary[$to->object_name]['relationships']) && isset($dictionary[$to->object_name]['relationships'][$rel_name]))
                {
                    $rel_def = $dictionary[$to->object_name]['relationships'][$rel_name];
                }
                else if (isset($dictionary[$rel_name]) && isset($dictionary[$rel_name]['relationships'])
                        && isset($dictionary[$rel_name]['relationships'][$rel_name]))
                {
                	$rel_def = $dictionary[$rel_name]['relationships'][$rel_name];
                }
                if (!empty($rel_def)) {
                    if ($rel_def['lhs_module'] == $from->module_dir && $rel_def['rhs_module'] == $to->module_dir )
                    {
                    	return $field;
                    }
                    else if ($rel_def['rhs_module'] == $from->module_dir && $rel_def['lhs_module'] == $to->module_dir )
                    {
                    	return $field;
                    }
                }
            }
        }
        return false;
    }
	//-----


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

			self::processActivities($lead, $account);

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
