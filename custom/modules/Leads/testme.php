<?php

error_reporting(E_ALL);
ini_set('display_errors', true);



die;

//Category "Construction Bonds" includes coverage type: Construction Bond, Performance Bond, Bid Bond, Final Bond(?), Labour and Material Bond(?)
//Category "Commercial Bonds": Appraisal Company Bond, Auto Dealer Bond,

//coverage_type_c

//Category "Construction Bonds"
$category_id = "b5048626-ce39-7fe4-0055-54e0a6412822";
$types = " 'ConstructionBond', 'PerformanceBond', 'BidBond' ";

//Category "Commercial Bonds"
//$category_id = "43a56884-2e6f-0ca6-2104-5453afbc5aab";
//$types = " 'AppraisalCompanyBond', 'AutoDealerBond' ";



global $db;

$sql = " SELECT b.aos_product_categories_id_c, b.lead_cost_c, b.lead_value_c, id_c, b.coverage_type_c  ";
$sql .= " FROM leads_cstm as b ";
$sql .= " LEFT JOIN leads as a ON b.id_c = a.id ";
//echo $sql .= " WHERE a.deleted = 0 AND (b.aos_product_categories_id_c == '' OR b.aos_product_categories_id_c IS NULL ) AND b.coverage_type_c IN ({$types}) ";
echo $sql .= " WHERE a.deleted = 0 AND b.aos_product_categories_id_c <> '{$category_id}'  AND b.coverage_type_c IN ({$types}) ";

$productCache = array();
$skip = true;
$res = $db->query($sql);
while( $row = $db->fetchByAssoc($res) ){
	$skip = true;
	$lead_id = $row['id_c'];
	$product_id = $category_id;//$row['aos_product_categories_id_c'];
	
	echo '<br/><br/>Type '.$row['coverage_type_c'];
	echo '  p_cat_id '.$row['aos_product_categories_id_c'];
	
	//echo ' VALUE '.$row['lead_value_c'];
	//echo ' productID '.$row['aos_product_categories_id_c'];
	//echo ' ID '.$row['id_c'];
	
	if( !empty($product_id) ){

		if( !in_array($product_id, $productCache) ){
			$productObj = BeanFactory::getBean('AOS_Product_Categories', $product_id);
			if( !empty($productObj->id) ){
				$skip = false;
				$productCache[$productObj->id] = array(
					'cost' => $productObj->lead_cost_c,
					'value' => $productObj->lead_value_c
				);
				//echo '-COST:'.$productObj->lead_cost_c;
				//echo '-VALUE:'.$productObj->lead_value_c;
			}else{
				echo '-SKIP-';
			}
		}
		
		if( !$skip ){
			/*
			if(
				(!empty($productCache[$product_id]['cost']) || !empty($productCache[$product_id]['value']) )
				  && ($productCache[$product_id]['cost'] !== $row['lead_cost_c'])
				  && ($productCache[$product_id]['value'] !== $row['lead_value_c'])
			){
			*/
				//echo 'NEW COST '.$productCache[$productObj->id]['cost'];
				//echo ' VALUE '.$productCache[$productObj->id]['value'];
				///echo '<br/>'.$upd = " UPDATE leads_cstm SET lead_cost_c = '{$productCache[$product_id]['cost']}', lead_value_c = '{$productCache[$product_id]['value']}', aos_product_categories_id_c = '{$category_id}' WHERE id_c = '{$lead_id}' ";
				echo '<br/>'.$upd = " UPDATE leads_cstm SET aos_product_categories_id_c = '{$category_id}' WHERE id_c = '{$lead_id}' ";
				//$db->query($upd);
			//}
		}

	}
	
}

//echo 'test-';

//require_once('custom/hooks/lead_hook.php');

//$lh = new lead_hook();
//$lh->autoconvert(&$bean, $event, $arguments);

/*
global $db;


		$upd = " UPDATE leads_cstm SET date_open_c = '', date_close_c = '', time2close_c = '' WHERE id_c = '901a5fc5-e031-32dc-3261-564509056b51'; ";
		$d = $db->query($upd);
		var_dump($d);
die;

//$sql = " SELECT  a.status, COUNT(a.id)  ";

$sql = " SELECT a.status, a.date_entered, a.date_modified, a.id  ";
$sql .= " FROM leads_cstm as c ";
$sql .= " LEFT JOIN leads as a ON c.id_c = a.id ";

$sql .= " WHERE a.id = '901a5fc5-e031-32dc-3261-564509056b51' ; ";
//$sql .= " WHERE a.deleted = 0 AND (c.date_open_c IS NULL OR c.date_open_c = '' )  AND a.status IN ('Converted', 'Dead ') GROUP BY status ";

$gi = 0;

$res = $db->query($sql);
while( $row = $db->fetchByAssoc($res) ){
	$gi++;	
	$diff = '';
	$date_open = '';
	$date_close = '';
	
	$sql_1 = " SELECT date_created FROM leads_audit WHERE parent_id = '{$row['id']}' AND field_name = 'status' AND after_value_string = '{$row['status']}' ORDER BY date_created DESC LIMIT 0, 1; ";
	$res_1 = $db->query($sql_1);
	while( $row_1 = $db->fetchByAssoc($res_1) ){
		$date_close = $row_1['date_created'];
	}

	//check if there was recycled status value
	$sql_1 = " SELECT date_created FROM leads_audit WHERE parent_id = '{$row['id']}' AND field_name = 'status' AND after_value_string = 'Recycled' ORDER BY date_created DESC LIMIT 0, 1; ";
	$res_1 = $db->query($sql_1);
	while( $row_1 = $db->fetchByAssoc($res_1) ){
		$date_open = $row_1['date_created'];
	}	
	if(empty($date_open)){
		$date_open = $row['date_entered'];
	}
	
	if( !empty($date_open) && !empty($date_close) ){
		$diff_d = get_time_difference($date_open, $date_close);
		if( isset($diff_d['days']) ){
			$diff = $diff_d['days'] +1;	
		}
		echo '<br/><br/>'.$upd = " UPDATE leads_cstm SET date_open_c = '{$date_open}', date_close_c = '{$date_close}', time2close_c = '{$diff}' WHERE id_c = '{$row['id']}'; ";
		//$d = $db->query($upd);
		//var_dump($d);
	}else{
		echo '<br/><br/>'.$gi.') OPEN '.$date_open.' CLOSE '.$date_close.' DIFF <b>'.$diff.'</b>   ID '.$row['id'] ;
	}
	//$bean->date_open_c = $bean->date_modified;
	//$bean->date_close_c = '';
	//$bean->time2close_c = '';
	
	
	//print_r($row);
}

function get_time_difference( $start, $end){
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
*/




//SET LEAD COST & VALUE FROM PRODUCT_CATALOG
/*
global $db;

$sql = " SELECT b.aos_product_categories_id_c, b.lead_cost_c, b.lead_value_c, id_c  ";
$sql .= " FROM leads_cstm as b ";
$sql .= " LEFT JOIN leads as a ON b.id_c = a.id ";
$sql .= " WHERE a.deleted = 0 AND b.aos_product_categories_id_c <> '' ";

$productCache = array();
$skip = true;
$res = $db->query($sql);
while( $row = $db->fetchByAssoc($res) ){
	$skip = true;
	$lead_id = $row['id_c'];
	$product_id = $row['aos_product_categories_id_c'];
	
	echo '<br/><br/>COST '.$row['lead_cost_c'];
	echo ' VALUE '.$row['lead_value_c'];
	//echo ' productID '.$row['aos_product_categories_id_c'];
	//echo ' ID '.$row['id_c'];
	
	if( !empty($product_id) ){

		if( !in_array($product_id, $productCache) ){
			$productObj = BeanFactory::getBean('AOS_Product_Categories', $product_id);
			if( !empty($productObj->id) ){
				$skip = false;
				$productCache[$productObj->id] = array(
					'cost' => $productObj->lead_cost_c,
					'value' => $productObj->lead_value_c
				);
			}
		}
		
		if( !$skip ){
			if(
				(!empty($productCache[$product_id]['cost']) || !empty($productCache[$product_id]['value']) )
				  && ($productCache[$product_id]['cost'] !== $row['lead_cost_c'])
				  && ($productCache[$product_id]['value'] !== $row['lead_value_c'])
			){
				
				//echo 'NEW COST '.$productCache[$productObj->id]['cost'];
				//echo ' VALUE '.$productCache[$productObj->id]['value'];
				echo '<br/>'.$upd = " UPDATE leads_cstm SET lead_cost_c = '{$productCache[$productObj->id]['cost']}', lead_value_c = '{$productCache[$productObj->id]['value']}' WHERE id_c = '{$lead_id}' ";
				//$db->query($upd);
			}
		}

	}
	
}
*/

echo '<br/><br/>by Mr.R<br/>';
