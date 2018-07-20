<?php

	//error_reporting(E_ALL);
	//ini_set('display_errors', true);

	if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

	global $db, $app_list_strings;

	//echo '<h3>Lead Summary Report:</h3><br/><br/>';
	echo '<h3>Report: Leads by Producers</h3><br/><br/>';
	//<script type="text/javascript" src="custom/include/js/jquery.datepick-ru.js" ></script>	
	$html = '';
	echo $js = <<<EOQ
<script type="text/javascript" src="custom/include/js/jquery-ui.datepicker.js" ></script>	
<link rel="stylesheet" type="text/css" href="custom/include/js/chosen/chosen.css" />
<script type="text/javascript" src="custom/include/js/chosen/chosen.jquery.min.js"></script>
<script type="text/javascript">
	$(function() {
			$(".chzn-select").chosen();
			
			var dates = $( "#from_date, #till_date" ).datepicker({
				showOn: "button",
			    buttonImage: "themes/Suite7/images/jscalendar.gif",
			    buttonImageOnly: true,
				//defaultDate: "-1w",
				changeMonth: true,
				numberOfMonths: 3,
				dateFormat: "yy-mm-dd",
				onSelect: function( selectedDate ) {
					var option = this.id == "from_date" ? "minDate" : "maxDate",
						instance = $( this ).data( "datepicker" ),
						date = $.datepicker.parseDate(
							instance.settings.dateFormat ||
							$.datepicker._defaults.dateFormat,
							selectedDate, instance.settings );
					dates.not( this ).datepicker( "option", option, date );
				},
			}
		);
	});
</script>
EOQ;
//-------------

	$user_arr = get_user_array($add_blank = false, $status = '', $user_id='', $use_real_name=false, $user_name_filter = '', $portal_filter ='', $from_cache = true);
	
	//if($_SERVER['REMOTE_ADDR'] == '109.251.117.69'){
	//		echo '<pre>';print_r($user_arr);echo '</pre>';
	//}
	
	$user_opts = '';
	foreach($user_arr as $uid => $uname){
		$selected = '';
		if(isset($_REQUEST['users'])){
			if(in_array($uid, $_REQUEST['users'])){
				$selected = 'selected="selected"';	
			}
		}
		$user_opts .= '<option '.$selected.' value="'.$uid.'">'.$uname.'</option>';
	}
	
	$users = @$_REQUEST['users'];

	$from = isset($_REQUEST['from_date'])?$_REQUEST['from_date']:'';
	$till = isset($_REQUEST['till_date'])?$_REQUEST['till_date']:'';
	
//-------------	
$html .= '<form>
<input type="hidden" name="module" id="module" value="AOR_Reports">
<input type="hidden" name="action" id="action" value="leadSummary">

<span style="display:block;">
Date:&nbsp;
<input type="text" size="11" id="from_date" name="from_date" value="'.$from.'">
 &nbsp; - &nbsp;
<input type="text"  size="11" id="till_date" name="till_date" value="'.$till.'">
</span>
&nbsp;&nbsp;&nbsp;';

$html .= '<br/><select data-placeholder="Select User" style="width:50%;" multiple id="users[]" name="users[]" class="chzn-select">'.$user_opts.'</select>';


$html .= '<br/>
<br/>
<button>Go!</button>
&nbsp;&nbsp;&nbsp;
<!--
<input title="Clean" accesskey="C" onclick="SUGAR.searchForm.clear_form(this.form); return false;" class="button" type="button" name="clear" id="search_form_clear" value="Clean"> 
-->
</form>';	

echo $html;

$category_id = '';
if( isset($_REQUEST['category_id']) && !empty($_REQUEST['category_id'])  ){
	$category_id = $_REQUEST['category_id'];
}

$dd = getLeadSummaryData($users, $from, $till, $category_id);

echo '<br/><br/>';
echo $dd['tbl'];
echo '<br/><br/>';

//echo '<pre>';
//print_r($dd);
//echo '</pre>';

//=========
function getLeadSummaryData($users, $from, $till, $category_id){
	global $db, $app_list_strings;
	//$data = array();
	
	$open_statuses = array('Converted',  'Dead');//'Recycled',
	$open_cnt = 0;
	$open_total_cnt = 0;
	
	$user_arr = get_user_array($add_blank = false, $status = '', $user_id='', $use_real_name=false, $user_name_filter = '', $portal_filter ='', $from_cache = true);
	
	$status_arr = array();
	foreach($app_list_strings['lead_status_dom'] as $key => $val){
		$status_arr[$key] = 0;
	}
	//init data schema
	$o_data = array();
	foreach($user_arr as $_key => $_val){
		$o_data[$_key] = $status_arr;
	}
	
	$_datefield = 'a.date_entered';
	$date_cond = '';
	if(isset($from)&&!empty($from)){
		$date_cond = " $_datefield >= ".db_convert("'".$from." 00:00:00'", 'date');
	}
	if(isset($till)&&!empty($till)){
		if( !empty($date_cond) ) $date_cond .= " AND ";
		$date_cond .= " $_datefield <= ".db_convert("'".$till." 23:59:59'", 'date');
	}
	

	$sql = " SELECT a.assigned_user_id, a.status, COUNT(a.id) as ecnt
	FROM leads as a
	LEFT JOIN leads_cstm as c ON c.id_c = a.id
	WHERE a.deleted = 0 ";
	if( !empty($date_cond) ){ $sql .= " AND $date_cond ";}
	if( !empty($users) ){
		$u_str = implode("','",$users);
		$sql .= " AND a.assigned_user_id IN ('{$u_str}') AND (a.assigned_user_id <> '' AND a.assigned_user_id IS NOT NULL) ";
	}

	if(!empty($category_id)){
		$sql .= " AND c.aos_product_categories_id_c = '{$category_id}' ";
	}

	$sql .= " GROUP BY a.assigned_user_id, a.status; ";//ORDER BY assigned_user_id desc
	$res = $db->query($sql);
	while($row = $db->fetchByAssoc($res)){
		$u_id = $row['assigned_user_id'];
		if( empty($u_id) ){
			continue;
		}
		
		// if($u_id == 'd7d7d5b5-ba32-9779-e6d6-545039a192bd'){//DonM
			// $u_id = '5b8ef14e-f3f3-764d-22a2-55c0c3c5acb1';//Zia
		// }

		//if( !isset($o_data[$u_id]) ){
		//	$o_data[$u_id] = $status_arr;
		//}
		//$data[] = $row;
		if(in_array($row['status'], $status_arr)){
			$o_data[$u_id][$row['status']] = $row['ecnt'];
		}else{
			echo '<br/>'.$u_id.' UNKNOWN STATUS '.$row['status'].'<br/>';
			//$o_data[$row['assigned_user_id']][''] += $row['ecnt'];
		}
	}

	$tbl_head = '';
	$tbl_head .= '<table  cellpadding="0" cellspacing="0" border="0" class="list">';
	$tbl_head .= '<tr>';
	$tbl_head .= '<th>Assigned User</th>';
	$tbl_head .= '<th>Total</th>';
	foreach($app_list_strings['lead_status_dom'] as $key => $val){
		if( in_array($key, array('Converted','Dead')) ){
			continue;
		}
		if( !empty($key) ){
			$tbl_head .= '<th>'.$val.'</th>';
		}
	}
	
	$tbl_head .= '<th>Open Leads</th>';
	$tbl_head .= '<th>Dead</th>';
	$tbl_head .= '<th>Converted</th>';
	$tbl_head .= '<th>Convertion Ratio</th>';
	
	
	$tbl_head .= '</tr>';
	$totals = $status_arr;
	
	foreach($o_data as $user_id => $stats){
		$open_cnt = 0;
		$cnt = 0;
		$cnt = sumUserTotal($stats);
		if($cnt > 0){
			$tbl_head .= '<tr>';
			$tbl_head .= '<td>'.$user_arr[$user_id].'</td>';
				
			$tbl_head .= '<td><a target="_blank" href="index.php?module=AOR_Reports&action=leadSummaryDetail&user_id='.$user_id.'&from='.$from.'&till='.$till.'">'.$cnt.'</a></td>';
			foreach($stats as $status_key => $status_cnt){
				$totals[$status_key] += $status_cnt;
				if( in_array($status_key, array('Converted','Dead')) ){
					continue;
				}
				if( !empty($status_key) ){
					
					if( !in_array($status_key, $open_statuses) ){
						$open_total_cnt += $status_cnt;
						$open_cnt += $status_cnt;
					}
					if($status_cnt > 0){
						$percent_str = '';
						//if($cnt > 0){
							//$tmp = ($status_cnt * 100) / $cnt;
							//$percent_str = '&nbsp;<span style="color:gray;">'.(number_format($tmp, 0, '', '')).'%</span>';
						//}
						$tbl_head .= '<td><a target="_blank" href="index.php?module=AOR_Reports&action=leadSummaryDetail&user_id='.$user_id.'&from='.$from.'&till='.$till.'&status='.$status_key.'">'.$status_cnt.$percent_str.'</a></td>';
					}else{
						$tbl_head .= '<td>-</td>';
					}
				}
			}
			
			//if($cnt > 0){
				$percent_str = '';
				//$tmp = ($open_cnt * 100) / $cnt;
				//$percent_str = '&nbsp;<span style="color:gray;">'.(number_format($tmp, 0, '', '')).'%</span>';
			//}
			$tbl_head .= '<td>'.$open_cnt.$percent_str.'</td>';
			
			
			
			
			
			$percent_str = '';
			//if($cnt2 > 0){
				//$tmp = ($stats['Dead'] * 100) / $cnt2;
				//$percent_str = '&nbsp;<span style="color:brown;">'.(number_format($tmp, 0, '', '')).'%</span>';
			//}
			$tbl_head .= '<td><a target="_blank" href="index.php?module=AOR_Reports&action=leadSummaryDetail&user_id='.$user_id.'&from='.$from.'&till='.$till.'&status=Dead">'.$stats['Dead'].$percent_str.'</a></td>';
			
			$cnt2 = $stats['Converted'] + $stats['Dead'];
			$percent_str = '';
			//if($cnt2 > 0){
				//$tmp = ($stats['Converted'] * 100) / $cnt2;
				//$percent_str = '&nbsp;<span style="color:brown;">'.(number_format($tmp, 0, '', '')).'%</span>';
			//}
			$tbl_head .= '<td><a target="_blank" href="index.php?module=AOR_Reports&action=leadSummaryDetail&user_id='.$user_id.'&from='.$from.'&till='.$till.'&status=Converted">'.$stats['Converted'].'</a></td>';

			$percent_str = '';
			if($cnt2 > 0){
				$tmp = ($stats['Converted'] * 100) / $cnt2;
				//$percent_str = '&nbsp;<span style="color:brown;">'.(number_format($tmp, 0, '', '')).'%</span>';
				$percent_str = (number_format($tmp, 0, '', '')).'%';
			}
			$tbl_head .= '<td>'.$percent_str.'</a></td>';
			
			$tbl_head .= '</tr>';
		}
	}

	$tbl_head .= '<tr>';
	$tbl_head .= '<th>TOTAL:</th>';
	$grand_total = sumUserTotal($totals);
	$tbl_head .= '<th>'.$grand_total.'</th>';
	foreach($totals as $status_key => $status_cnt){
		if( empty($status_key) || $status_key == 'Converted' || $status_key == 'Dead' ){
			continue;
		}
		if($grand_total > 0){
			$percent_str = '';
			//$tmp = ($status_cnt * 100) / $grand_total;
			//$percent_str = '&nbsp;<span style="color:gray;">'.(number_format($tmp, 0, '', '')).'%</span>';
		}
		$tbl_head .= '<th>'.$status_cnt.$percent_str.'</th>';
	}

	
	if($grand_total > 0){
		$percent_str = '';
		//$tmp = ($open_total_cnt * 100) / $grand_total;
		//$percent_str = '&nbsp;<span style="color:gray;">'.(number_format($tmp, 0, '', '')).'%</span>';
	}	
	$tbl_head .= '<th>'.$open_total_cnt.$percent_str.'</th>';//Open Leads  open_total_cnt


	$grand_total2 = $totals['Converted'] + $totals['Dead'];
	
	$percent_str = '';
	if($grand_total > 0){
		//$tmp = ($totals['Converted'] * 100) / $grand_total2;
		//$percent_str = '&nbsp;<span style="color:brown;">'.(number_format($tmp, 0, '', '')).'%</span>';
	}
	$tbl_head .= '<th>'.$totals['Converted'].$percent_str.'</th>';//Converted

	$percent_str = '';	
	//if($grand_total2 > 0){
		//$tmp = ($totals['Dead'] * 100) / $grand_total2;
		//$percent_str = '&nbsp;<span style="color:brown;">'.(number_format($tmp, 0, '', '')).'%</span>';
	//}
	$tbl_head .= '<th>'.$totals['Dead'].$percent_str.'</th>';//Dead


	if($grand_total2 > 0){
		$tmp = ($totals['Converted'] * 100) / $grand_total2;
		$percent_str = (number_format($tmp, 0, '', '')).'%';
	}
	$tbl_head .= '<th>'.$percent_str.'</th>';//Closing Ratio

	
	$tbl_head .= '</tr>';
	$tbl_head .= '</table>';
	
	return array('tbl'=>$tbl_head, 'sdata'=>$o_data);
}

function sumUserTotal($stats){
	$sum = 0;
	foreach($stats as $status_key => $status_cnt){
		$sum += $status_cnt;
	}
	return $sum;
}
