<?php

	if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

	global $db, $app_list_strings;

//$sql = " UPDATE opportunities SET sales_stage = 'Prospecting' WHERE sales_stage IN ('Id. Decision Makers', 'Needs Analysis', 'Negotiation/Review', 'Perception Analysis', 'Qualification') AND deleted = 0; ";
//$db->query($sql);
	
	echo '<h3>Lead To Sell Closing Ratio</h3><br/><br/>';
	
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


$user_arr = get_user_array($add_blank = false, $status = '', $user_id='', $use_real_name=false, $user_name_filter = '', $portal_filter ='', $from_cache = true);

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
<input type="hidden" name="action" id="action" value="general">

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

//Open Leads
//Open Opps

//print_r($users);echo $from;echo '--';echo $till;

$leadsData = getLeadsData($users, $from, $till);
//bla($leadsData);
//$pdata = prepLeads($leadsData);
//bla($pdata);

$oppsData = getOppsData($users, $from, $till);
//bla($oppsData);
//$odata = prepOpps($oppsData);
//bla($odata);


//$tbl_rows = preDisplay(array('lead'=>$pdata, 'opp'=>$odata), $users);

$ss = prepData($leadsData, $oppsData);

echo '<br/><br/>'.$result_tbl = genTable($ss, array('f'=>$from, 't'=>$till, 'u'=>$users) );
//bla($ss);

//bla($leadsData);
//bla($oppsData);


//echo $_SERVER['REMOTE_ADDR'] == '109.251.117.69'
//bla($ss);

/*
$_tb = '<table cellpadding="0" cellspacing="0" border="0" class="list">';
$_tb .= "<tr>";
$_tb .= "<th>Producer</th>";
$_tb .= "<th>Total Leads</th>";
$_tb .= "<th>Open Leads</th>";
$_tb .= "<th>Lost</th>";
$_tb .= "<th>Converted</th>";
$_tb .= "<th>Conv. Ratio</th>";

$_tb .= "<th>Open Opp.</th>";
$_tb .= "<th>Lost</th>";
$_tb .= "<th>Won</th>";

$_tb .= '<th title="Lead To Sell Closing Ratio" >L2S Ratio</th>';
$_tb .= "</tr>";
$_tb .= $tbl_rows;
$_tb .= "</table>";

echo '</br></br>'.$_tb;
*/

function genLink($cell_value, $params){
	$url = "index.php?module=AOR_Reports&action=gDetail";
	foreach($params as $key => $value){
		if(!empty($value)){
			if( is_array($value) ){
				foreach($value as $i => $v){
					$url .= '&'.$key.'%5B%5D='.$v;
				}
			}else{
				$url .= '&'.$key.'='.$value;
			}
		}
	}

	return '<a href="'.$url.'">'.$cell_value.'</a>';
}

function formatValue($value){
	if(empty($value)){
		$value = '-';
	}
	return $value;
}

function genTable($data, $filter){
	
	$rows_tbl = '';
	$i = 1;
	$user_arr = get_user_array($add_blank = false, $status = '', $user_id='', $use_real_name=false, $user_name_filter = '', $portal_filter ='', $from_cache = true);

	foreach($data['data'] as $user_id => $arr){
		if( empty($user_id) ){
			continue;
		}
		$rows_tbl .= (($i++ % 2) == 0)?'<tr class="evenListRowS1">':'<tr class="oddListRowS1">';
			$rows_tbl .= "<td>".$user_arr[$user_id]."</td>";
			$v = genLink( formatValue($arr['total_leads']), array('m'=>'l', 'f'=>$filter['f'], 't'=>$filter['t'], 'u'=>$user_id) );
			$rows_tbl .= "<td>".$v."</td>";
			
			$v = genLink( formatValue($arr['open_leads']), array('m'=>'l', 's'=>'open', 'f'=>$filter['f'], 't'=>$filter['t'], 'u'=>$user_id) );
			$rows_tbl .= "<td>".$v."</td>";
			
			$v = genLink( formatValue($arr['lost_leads']), array('m'=>'l', 's'=>'dead', 'f'=>$filter['f'], 't'=>$filter['t'], 'u'=>$user_id) );
			$rows_tbl .= "<td>".$v."</td>";
			
			$v = genLink( formatValue($arr['conv_cnt_leads']), array('m'=>'l', 's'=>'conv', 'f'=>$filter['f'], 't'=>$filter['t'], 'u'=>$user_id) );
			$rows_tbl .= "<td>".$v."</td>";
			
			$rows_tbl .= "<td>".formatValue($arr['conv_ratio_leads'])."</td>";
			
			$v = genLink( formatValue($arr['open_opps']), array('m'=>'o', 's'=>'open', 'f'=>$filter['f'], 't'=>$filter['t'], 'u'=>$user_id) );
			$rows_tbl .= "<td>".$v."</td>";
			
			$v = genLink( formatValue($arr['lost_opps']), array('m'=>'o', 's'=>'lost', 'f'=>$filter['f'], 't'=>$filter['t'], 'u'=>$user_id) );
			$rows_tbl .= "<td>".$v."</td>";
			
			$v = genLink( formatValue($arr['won_opps']), array('m'=>'o', 's'=>'won', 'f'=>$filter['f'], 't'=>$filter['t'], 'u'=>$user_id) );
			$rows_tbl .= "<td>".$v."</td>";
			
			$f = formatClosingRatio($arr['closing_rate']);
			$rows_tbl .= "<td>".$f."</td>";
		$rows_tbl .= "</tr>";
	}
	
	$_btotals = ' style="background: bisque !important;" ';
	
	$rows_tbl .= "<tr>";
		$rows_tbl .= "<th {$_btotals}>Totals</th>";
		
		$a = genLink($data['totals']['total_leads'], array('m'=>'l', 'f'=>$filter['f'], 't'=>$filter['t'], 'u'=>$filter['u']) );
		$rows_tbl .= "<th {$_btotals}>".$a."</th>";
		
		$a = genLink($data['totals']['open_leads'], array('m'=>'l', 's'=>'open', 'f'=>$filter['f'], 't'=>$filter['t'], 'u'=>$filter['u']) );
		$rows_tbl .= "<th {$_btotals}>".$a."</th>";
		
		$a = genLink($data['totals']['lost_leads'], array('m'=>'l', 's'=>'dead', 'f'=>$filter['f'], 't'=>$filter['t'], 'u'=>$filter['u']) );
		$rows_tbl .= "<th {$_btotals}>".$a."</th>";
		
		$rows_tbl .= "<th {$_btotals}>".$data['totals']['conv_cnt_leads']."</th>";
		
		$rows_tbl .= "<th {$_btotals}>".$data['totals']['conv_ratio_leads']."</th>";
		
		$a = genLink($data['totals']['open_opps'], array('m'=>'o', 's'=>'open', 'f'=>$filter['f'], 't'=>$filter['t'], 'u'=>$filter['u']) );
		$rows_tbl .= "<th {$_btotals}>".$a."</th>";
		
		$a = genLink($data['totals']['lost_opps'], array('m'=>'o', 's'=>'lost', 'f'=>$filter['f'], 't'=>$filter['t'], 'u'=>$filter['u']) );
		$rows_tbl .= "<th {$_btotals}>".$a."</th>";
		
		$a = genLink($data['totals']['won_opps'], array('m'=>'o', 's'=>'won', 'f'=>$filter['f'], 't'=>$filter['t'], 'u'=>$filter['u']) );
		$rows_tbl .= "<th {$_btotals}>".$a."</th>";
		
		$rows_tbl .= "<th {$_btotals}>".$data['totals']['closing_rate']."</th>";
	$rows_tbl .= "</tr>";
	
	$_tb = '<table cellpadding="0" cellspacing="0" border="0" class="list">';
	$_tb .= "<tr>";
	$_tb .= "<th>Producer</th>";
	$_tb .= "<th>Total Leads</th>";
	$_tb .= "<th>Open Leads</th>";
	$_tb .= "<th>Dead</th>";
	$_tb .= "<th>Converted</th>";
	$_tb .= "<th>Conv. Ratio</th>";

	$_tb .= "<th>Open Opp.</th>";
	$_tb .= "<th>Lost</th>";
	$_tb .= "<th>Won</th>";

	$_tb .= '<th title="Lead To Sell Closing Ratio" >L2S Ratio</th>';
	$_tb .= "</tr>";
	$_tb .= $rows_tbl;
	$_tb .= "</table>";		
	
	return $_tb;
}
function formatClosingRatio($val){
	$str = '';
	if($val <= 0){
		$str = '<span style="color:red;">--</span>';
	}
	if($val >= 26.4){
		$str = (number_format($val, 1, '.', ''));
		$str = '<b><span style="color:green;">'.$str.'%</span></b>';//text-shadow: 0.5px 0.5px #DFDFDF;
	}
	if($val >= 15 && $val < 26.4){
		$str = (number_format($val, 1, '.', ''));
		$str = '<b><span style="color:orange;">'.$str.'%</span></b>';//text-shadow: 0.5px 0.5px #999999;
	}
	if($val > 0 && $val < 15){
		$str = (number_format($val, 1, '.', ''));
		$str = '<b><span style="color:red;">'.$str.'%</span></b>';//text-shadow: 0.5px 0.5px #DFDFDF;
	}
	
	return $str;
}
function prepData($lead_data, $opp_data){
	$o_data = array();
	
	$u = array();
	foreach($lead_data as $user_id => $user_data){
		if(!in_array($user_id, $u)){
			$u[] = $user_id;
		}
	}
	foreach($opp_data as $user_id => $user_data){
		if(!in_array($user_id, $u)){
			$u[] = $user_id;
		}
	}	
	
	$base_struct = array(
		'total_leads' => 0,
		'open_leads' => 0,
		'lost_leads' => 0,
		'conv_cnt_leads' => 0,
		'conv_ratio_leads' => 0,
		'open_opps' => 0,
		'won_opps' => 0,
		'lost_opps' => 0,
		'closing_rate' => 0,
	);
	$_totals = $base_struct;
	foreach($u as $i => $user_id){
		$o_data[$user_id] = $base_struct;
	}


	foreach($lead_data as $user_id => $status_arr){
		foreach($status_arr as $status_key => $status_cnt){
			if( $status_key == 'Converted'){
				$o_data[$user_id]['conv_cnt_leads'] += $status_cnt;
			}elseif( $status_key == 'Dead' ){
				$o_data[$user_id]['lost_leads'] += $status_cnt;
			}else{
				$o_data[$user_id]['open_leads'] += $status_cnt;
			}
		}

		if( $o_data[$user_id]['conv_cnt_leads'] > 0 ){
			$tmp = ($o_data[$user_id]['conv_cnt_leads'] * 100) / ($o_data[$user_id]['conv_cnt_leads'] + $o_data[$user_id]['lost_leads']);
			$o_data[$user_id]['conv_ratio_leads'] = (number_format($tmp, 0, '', '')).'%';
		}
		
		$o_data[$user_id]['total_leads'] = $o_data[$user_id]['conv_cnt_leads'] + $o_data[$user_id]['lost_leads'] + $o_data[$user_id]['open_leads'];
	}
	
	
	
	foreach($opp_data as $user_id => $stage_arr){
		foreach($stage_arr as $stage_key => $stage_cnt){
			if( $stage_key == 'Closed Won'){
				$o_data[$user_id]['won_opps'] += $stage_cnt;
			}elseif( $stage_key == 'Closed Lost' ){
				$o_data[$user_id]['lost_opps'] += $stage_cnt;
			}else{
				$o_data[$user_id]['open_opps'] += $stage_cnt;
			}
		}
		
		if( $o_data[$user_id]['total_leads'] > 0 && $o_data[$user_id]['won_opps'] > 0 ){
			$tmp = (float) ( $o_data[$user_id]['won_opps'] * 100) / $o_data[$user_id]['total_leads'];
			$o_data[$user_id]['closing_rate'] = $tmp;//(number_format($tmp, 0, '', '')).'%';
		}
	}
	
	//totals
	foreach($u as $i => $user_id){
		foreach($base_struct as $key => $val){
			$_totals[$key] += $o_data[$user_id][$key];
		}
	}
	if( $_totals['conv_cnt_leads'] > 0 ){
		$tmp = ($_totals['conv_cnt_leads'] * 100) / ($_totals['conv_cnt_leads'] + $_totals['lost_leads']);
		$_totals['conv_ratio_leads'] = (number_format($tmp, 0, '', '')).'%';
	}	
	if( $_totals['total_leads'] > 0 && $_totals['won_opps'] > 0 ){
		$tmp =  ( $_totals['won_opps'] * 100) / $_totals['total_leads'];
		$_totals['closing_rate'] = (number_format($tmp, 0, '', '')).'%';
	}

	return array('data'=>$o_data, 'totals'=>$_totals);
}

function getLeadsData($users, $from, $till){
	global $db;
	
	$data = array();
	
	$_datefield = 'a.date_entered';
	$_cond = '';
	if( isset($from) && !empty($from) ){
		$_cond = " AND $_datefield >= ".db_convert("'".$from." 00:00:00'", 'date');
	}
	if( isset($till) && !empty($till) ){
		$_cond .= " AND $_datefield <= ".db_convert("'".$till." 23:59:59'", 'date');
	}
	
	if( !empty($users) ){
		$u_str = implode("','",$users);
		$_cond .= " AND a.assigned_user_id IN ('{$u_str}') AND (a.assigned_user_id <> '' AND a.assigned_user_id IS NOT NULL) ";
	}

	$sql = " SELECT a.assigned_user_id, a.status, COUNT(a.id) as ecnt ";
	$sql .= " FROM leads as a ";
	$sql .= " LEFT JOIN leads_cstm as c ON c.id_c = a.id ";
	$sql .= " WHERE a.deleted = 0 {$_cond} GROUP BY a.assigned_user_id, a.status; ";	
	$res = $db->query($sql);
	while( $row = $db->fetchByAssoc($res) ){
		$data[$row['assigned_user_id']][$row['status']] += $row['ecnt'];
	}
	return $data;
}

function getOppsData($users, $from, $till){
	global $db;
	
	$data = array();
	
	$_datefield = 'a.date_entered';
	$_cond = '';
	if( isset($from) && !empty($from) ){
		$_cond = " AND $_datefield >= ".db_convert("'".$from." 00:00:00'", 'date');
	}
	if( isset($till) && !empty($till) ){
		$_cond .= " AND $_datefield <= ".db_convert("'".$till." 23:59:59'", 'date');
	}
	
	if( !empty($users) ){
		$u_str = implode("','",$users);
		$_cond .= " AND a.assigned_user_id IN ('{$u_str}') AND (a.assigned_user_id <> '' AND a.assigned_user_id IS NOT NULL) ";
	}
	
	$_cond .= " AND a.opportunity_type = 'New Business' ";
	
	$sql = " SELECT a.assigned_user_id, a.sales_stage, COUNT(a.id) as ecnt ";
	$sql .= " FROM opportunities as a ";
	$sql .= " WHERE a.deleted = 0  {$_cond} GROUP BY a.assigned_user_id, a.sales_stage; ";
	$res = $db->query($sql);
	while( $row = $db->fetchByAssoc($res) ){
		$data[$row['assigned_user_id']][$row['sales_stage']] += $row['ecnt'];
	}
	return $data;
}











/*

function preDisplay($data, $show_user){
	
	$u = array();
	foreach($data['lead'] as $user_id => $user_data){
		if(!in_array($user_id, $u)){
			$u[] = $user_id;
		}
	}
	foreach($data['opp'] as $user_id => $user_data){
		if(!in_array($user_id, $u)){
			$u[] = $user_id;
		}
	}
	//print_r($u);
	
	$rows_tbl = '';
	$i = 1;
	$user_arr = get_user_array($add_blank = false, $status = '', $user_id='', $use_real_name=false, $user_name_filter = '', $portal_filter ='', $from_cache = true);
	//foreach($user_arr as $user_id => $user_name){
	foreach($u as $n => $user_id){
		if( empty($user_id) ){
			continue;
		}
		$i++;
		if(($i % 2) == 0){
			$rows_tbl .= '<tr class="oddListRowS1">';
		}else{
			$rows_tbl .= '<tr class="evenListRowS1">';
		}
			$rows_tbl .= "<td>";
			$rows_tbl .= $user_arr[$user_id];
			$rows_tbl .= "</td>";
			
			$rows_tbl .= "<td>";
			$rows_tbl .= @$data['lead'][$user_id]['total_leads'];
			$rows_tbl .= "</td>";

			$rows_tbl .= "<td>";
			$rows_tbl .= @$data['lead'][$user_id]['open_leads'];
			$rows_tbl .= "</td>";
				
			$rows_tbl .= "<td>";
			$rows_tbl .= @$data['lead'][$user_id]['lost_leads'];
			$rows_tbl .= "</td>";
			
			$rows_tbl .= "<td>";
			$rows_tbl .= @$data['lead'][$user_id]['conv_cnt_leads'];
			$rows_tbl .= "</td>";
			
			$rows_tbl .= "<td>";
			$rows_tbl .= @$data['lead'][$user_id]['conv_ratio_leads'];
			$rows_tbl .= "</td>";
				
			$rows_tbl .= "<td>";
			$rows_tbl .= @$data['opp'][$user_id]['open_opps'];
			$rows_tbl .= "</td>";
					
			$rows_tbl .= "<td>";
			$rows_tbl .= @$data['opp'][$user_id]['lost_opps'];
			$rows_tbl .= "</td>";

			$rows_tbl .= "<td>";
			$rows_tbl .= @$data['opp'][$user_id]['won_opps'];
			$rows_tbl .= "</td>";				
			
			$close_ratio = '';
			if( $data['lead'][$user_id]['conv_cnt_leads'] > 0 && $data['opp'][$user_id]['won_opps'] > 0 ){
				$tmp = ( $data['opp'][$user_id]['won_opps'] * 100) / $data['lead'][$user_id]['conv_cnt_leads'];
				//echo '<br/>('.$data['opp'][$user_id]['won_opps'].' * 100) / '.$data['lead'][$user_id]['conv_cnt_leads'].' = '.$tmp;
				//$tmp = ( $data['lead'][$user_id]['conv_cnt_leads'] * 100) / $data['opp'][$user_id]['won_opps'];
				$close_ratio = (number_format($tmp, 0, '', '')).'%';		
			}
			$rows_tbl .= "<td>";
			$rows_tbl .= $close_ratio;
			$rows_tbl .= "</td>";
		$rows_tbl .= "</tr>";
	}
	return $rows_tbl;
}

function prepOpps($data){
	$o_data = array();

	//$user_arr = get_user_array($add_blank = false, $status = '', $user_id='', $use_real_name=false, $user_name_filter = '', $portal_filter ='', $from_cache = true);
	//foreach($user_arr as $_key => $_val){
	//	$o_data[$_key] = array(
	//		'open_opps' => 0,
	//		'won_opps' => 0,
	//		'lost_opps' => 0,
	//	);
	//}
	
	foreach($data as $user_id => $stage_arr){
		$o_data[$_key] = array(
			'open_opps' => 0,
			'won_opps' => 0,
			'lost_opps' => 0,
		);		
		foreach($stage_arr as $stage_key => $stage_cnt){
			if( $stage_key == 'Closed Won'){
				$o_data[$user_id]['won_opps'] += $stage_cnt;
			}elseif( $stage_key == 'Closed Lost' ){
				$o_data[$user_id]['lost_opps'] += $stage_cnt;
			}else{
				$o_data[$user_id]['open_opps'] += $stage_cnt;
			}
		}

		//if( $o_data[$user_id]['conv_cnt_leads'] > 0 ){
		//	$tmp = ($o_data[$user_id]['conv_cnt_leads'] * 100) / ($o_data[$user_id]['conv_cnt_leads'] + $o_data[$user_id]['lost_leads']);
		//	$o_data[$user_id]['conv_ratio_leads'] = (number_format($tmp, 0, '', '')).'%';		
		//}
	}
	return $o_data;
}

function prepLeads($data){
	$o_data = array();
	//$user_arr = get_user_array($add_blank = false, $status = '', $user_id='', $use_real_name=false, $user_name_filter = '', $portal_filter ='', $from_cache = true);
	//foreach($user_arr as $_key => $_val){
	//	$o_data[$_key] = array(
	//		'open_leads' => 0,
	//		'lost_leads' => 0,
	//		'conv_cnt_leads' => 0,
	//		'conv_ratio_leads' => 0,
	//	);
	//}
	foreach($data as $user_id => $status_arr){
		$o_data[$_key] = array(
			'open_leads' => 0,
			'lost_leads' => 0,
			'conv_cnt_leads' => 0,
			'conv_ratio_leads' => 0,
		);		
		foreach($status_arr as $status_key => $status_cnt){
			if( $status_key == 'Converted'){
				$o_data[$user_id]['conv_cnt_leads'] += $status_cnt;
			}elseif( $status_key == 'Dead' ){
				$o_data[$user_id]['lost_leads'] += $status_cnt;
			}else{
				$o_data[$user_id]['open_leads'] += $status_cnt;
			}
		}

		if( $o_data[$user_id]['conv_cnt_leads'] > 0 ){
			$tmp = ($o_data[$user_id]['conv_cnt_leads'] * 100) / ($o_data[$user_id]['conv_cnt_leads'] + $o_data[$user_id]['lost_leads']);
			$o_data[$user_id]['conv_ratio_leads'] = (number_format($tmp, 0, '', '')).'%';		
		}
	}
	return $o_data;
}

*/

function bla($a){
	echo '<pre>';
	print_r($a);
	echo '</pre>';
}
