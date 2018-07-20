<?php

class lead_hook{
	
	function updPerfomanceInfo(&$bean, $event, $arguments) {
		
		//SET Lead Cost & Value
		$fetched_row = array_merge($bean->fetched_row, $bean->fetched_rel_row);
		if( $bean->aos_product_categories_id_c !== $fetched_row['aos_product_categories_id_c'] ){
			$catalog = BeanFactory::getBean('AOS_Product_Categories', $bean->aos_product_categories_id_c);//would be nice to cache
			$bean->lead_cost_c = $catalog->lead_cost_c;
			$bean->lead_value_c = $catalog->lead_value_c;
		}

		
		//if($_SERVER['REMOTE_ADDR'] == '109.251.117.69'){
			
			//TimeLength2Close
			require_once('custom/hooks/leadfunc.php');
			if(empty($bean->date_open_c)){
				$bean->date_open_c = $fetched_row['date_entered'];//$bean->date_entered;
				//$diff_d = leadfunc::get_time_difference($bean->date_open_c, date('Y-m-d H:i:s'));
				//if( isset($diff_d['days']) && !empty($diff_d['days']) ){
				//	$bean->time2close_c = $diff_d['days'] +1;	
				//}else{
				//	$bean->time2close_c = '';
				//}
			}
			if( $bean->status !== $fetched_row['status'] ){
				if( ($bean->status == 'Converted') || ($bean->status == 'Dead') ){
					if( empty($bean->date_modified) ){
						$bean->date_close_c = date('Y-m-d H:i:s');
					}else{
						$bean->date_close_c = $bean->date_modified;
					}
					$diff_d = leadfunc::get_time_difference($bean->date_open_c, $bean->date_close_c);
					if( isset($diff_d['days']) && !empty($diff_d['days']) ){
						$bean->time2close_c = $diff_d['days'] +1;	
					}else{
						$bean->time2close_c = 1;
					}
				}
				if( ($bean->status == 'Recycled') ){
					if( empty($bean->date_modified) ){
						$bean->date_open_c = date('Y-m-d H:i:s');
					}else{
						$bean->date_open_c = $bean->date_modified;
					}					
					$bean->date_close_c = '';
					$bean->time2close_c = '';
				}
			}

		//}
	}
	
	function autoconvert(&$bean, $event, $arguments) {
		if( isset($_REQUEST['autoconvert']) && $_REQUEST['autoconvert'] == true ){
			require_once('custom/hooks/leadfunc.php');
			$rez_arr = leadfunc::do_convert($bean->id);
			ob_clean();
			header('Location: /index.php?module='.$rez_arr['module'].'&action=DetailView&record='.$rez_arr['id']);
			exit;
		}
	}
	
}
