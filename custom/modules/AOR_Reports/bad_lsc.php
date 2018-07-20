<?php

	//error_reporting(E_ALL);
	//ini_set('display_errors', true);

	if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

	global $db, $app_list_strings;

	echo '<h3>Report: Leads by Categories:</h3><br/><br/>';
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

	$categories = array();
	$sql = " SELECT id, name FROM aos_product_categories WHERE deleted = 0 ORDER BY name ASC; ";
	$res = $db->query($sql);
	while($row = $db->fetchByAssoc($res)){
		$categories[$row['id']] = $row['name'];
	}

	$user_arr = get_user_array($add_blank = false, $status = 'Active', $user_id='', $use_real_name=false, $user_name_filter = '', $portal_filter ='', $from_cache = true);
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
<input type="hidden" name="action" id="action" value="lsc">

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


//-------------------------	

	$_datefield = 'a.date_entered';
	$date_cond = '';
	if(isset($from)&&!empty($from)){
		$date_cond = " $_datefield >= ".db_convert("'".$from." 00:00:00'", 'date');
	}
	if(isset($till)&&!empty($till)){
		if( !empty($date_cond) ) $date_cond .= " AND ";
		$date_cond .= " $_datefield <= ".db_convert("'".$till." 23:59:59'", 'date');
	}

	$tcategory = array();	
	$user_blank_data = array();
	foreach($categories as $key => $val){
		$user_blank_data[$key] = 0;
		$tcategory[$key] = 0;
	}
	
	$d = array();
	$tuser = array();
	foreach($user_arr as $key => $val){
		$tuser[$key] = 0;
		$d[$key] = $user_blank_data;
	}
	
	
	$sql = " SELECT a.assigned_user_id, c.aos_product_categories_id_c, COUNT(a.id) as ecnt
	FROM leads as a
	LEFT JOIN leads_cstm as c ON c.id_c = a.id
	WHERE a.deleted = 0 AND (c.aos_product_categories_id_c <> '' AND c.aos_product_categories_id_c IS NOT NULL ) ";
	if( !empty($date_cond) ){ $sql .= " AND $date_cond ";}
	if( !empty($users) ){
		$u_str = implode("','",$users);
		$sql .= " AND a.assigned_user_id IN ('{$u_str}')";
	}
	

	$sql .= " GROUP BY a.assigned_user_id, c.aos_product_categories_id_c ; ";//ORDER BY a.assigned_user_id desc
	
	//$sql = 'SHOW COLUMNS FROM leads_cstm;';
	$res = $db->query($sql);
	while($row = $db->fetchByAssoc($res)){
		if( in_array($row['assigned_user_id'], $user_arr) ){
			$tuser[$row['assigned_user_id']] += $row['ecnt'];
			$tcategory[$row['aos_product_categories_id_c']] += $row['ecnt'];
			$d[$row['assigned_user_id']][$row['aos_product_categories_id_c']] += $row['ecnt'];
		}
	}
	
	$tbl_head = '';
	$tbl_head .= '<table  cellpadding="0" cellspacing="0" border="0" class="list">';
	$tbl_head .= '<tr>';
	$tbl_head .= '<th>Assigned User</th>';
	$tbl_head .= '<th>Total</th>';
	foreach($categories as $key => $val){
		if( !empty($key) ){
			$tbl_head .= '<th>'.$val.'</th>';
		}
	}
	$tbl_head .= '</tr>';
	
	
	foreach($d as $user_id => $stats){
		$tbl_head .= '<tr>';
		$tbl_head .= '<td>'.$user_arr[$user_id].'</td>';
		$cnt = $tuser[$user_id];
		if($cnt > 0){
			//$tbl_head .= '<td><a target="_blank" href="index.php?module=AOR_Reports&action=lscDetail&user_id='.$user_id.'&from='.$from.'&till='.$till.'">'.$cnt.'</a></td>';
			$tbl_head .= $cnt;
		}else{
			$tbl_head .= '<td>-</td>';
		}
		foreach($stats as $_key => $_cnt){
			//$tbl_head .= '<td><a target="_blank" href="index.php?module=AOR_Reports&action=leadSummaryDetail&user_id='.$user_id.'&from='.$from.'&till='.$till.'&status=Converted">'.$stats['Converted'].$percent_str.'</a></td>';
			//$tbl_head .= '<td><a target="_blank" href="index.php?module=AOR_Reports&action=lscDetail&user_id='.$user_id.'&from='.$from.'&till='.$till.'&key='.$_key.'">'.$_cnt.'</a></td>';
			$tbl_head .= '<td>'.$_cnt.'</td>';
		}
		$tbl_head .= '</tr>';
	}
	
	$tbl_head .= '<tr>';
	$tbl_head .= '<th>TOTAL:</th>';
	foreach($tcategory as $_key => $_cnt){
		$tbl_head .= '<td>'.$_cnt.'</td>';
	}
	$tbl_head .= '</tr>';
	echo $tbl_head .= '</table>';
	
	
echo '<pre>';
print_r($d);
//print_r($users);
echo '</pre>';
	
	