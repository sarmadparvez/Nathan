<?php

	if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

	global $db, $timedate, $app_list_strings;
	
	$categories = array();
	//$sql = " SELECT id, name FROM aos_product_categories WHERE deleted = 0 ORDER BY name ASC; ";
	//$res = $db->query($sql);
	//while($row = $db->fetchByAssoc($res)){
	//	$categories[$row['id']] = $row['name'];
	//}

	$user_arr = get_user_array($add_blank = false, $status = '', $user_id='', $use_real_name=false, $user_name_filter = '', $portal_filter ='', $from_cache = true);
	
	$cond = '';
	$cond_html = '';
	
	if(	!empty($_REQUEST['user_id']) ){
		$value = $db->quote($_REQUEST['user_id']);
		$cond .= ' AND a.assigned_user_id = "'.$value.'" ';
		$cond_html .= ' <b>User:</b> '.$user_arr[$value];
	}
	$users = @$_REQUEST['users'];
	if( isset($_REQUEST['users']) && !empty($users) ){
		$u_str = implode("','",$users);
		$cond .= " AND a.assigned_user_id IN ('{$u_str}') AND (a.assigned_user_id <> '' AND a.assigned_user_id IS NOT NULL) ";
		
		$cond_html .= ' <b>Users:</b> ';
		foreach($users as $i => $user_id){
			$cond_html .= $user_arr[$user_id].', ';
		}
	}
	
	
	if(	!empty($_REQUEST['status']) ){
		$value = $db->quote($_REQUEST['status']);
		$cond .= ' AND a.sales_stage = "'.$value.'" ';
		$cond_html .= ' <b>Sales stage:</b> '.$app_list_strings['sales_stage_dom'][$value];
	}
	$date_cond = '';
	if(	!empty($_REQUEST['from']) ){
		$value = $db->quote($_REQUEST['from']);
		$cond .= " AND a.date_entered >= ".db_convert("'".$value." 00:00:00'", 'date');
		$cond_html .= ' <b>From Date:</b> '.$value;
	}
	if(	!empty($_REQUEST['till']) ){
		$value = $db->quote($_REQUEST['till']);
		$cond .= " AND a.date_entered <= ".db_convert("'".$value." 23:59:59'", 'date');
		$cond_html .= ' <b>Till Date:</b> '.$value;
	}
	
	//if( isset($_REQUEST['source_id']) && !empty($_REQUEST['source_id'])  ){
	//	$value = $db->quote($_REQUEST['source_id']);
	//	$cond .= " AND a.lead_source = '{$value}' ";
	//	$cond_html .= ' <b>Lead Source:</b> '.$value;
	//}
		
	$category_id = '';	
	//if( isset($_REQUEST['category_id']) && !empty($_REQUEST['category_id'])  ){
	//	$category_id = $_REQUEST['category_id'];
	//	$cond .= " AND c.aos_product_categories_id_c = '{$category_id}' ";
	//	$cond_html .= ' <b>Category:</b> '.$categories[$category_id];
	//}
	
	if( !empty($cond_html) ){
		echo '<h3>Filter:</h3> '.$cond_html.'<br/>';
	}
	
	//$sql = " SELECT a.date_entered, a.id, a.last_name, a.first_name, a.assigned_user_id, a.status, u.last_name as ulast, u.first_name  as ufirst, c.aos_product_categories_id_c, a.lead_source FROM leads as a 
	//LEFT JOIN users as u ON u.id = a.assigned_user_id
	//LEFT JOIN leads_cstm as c ON c.id_c = a.id
	//WHERE a.deleted = 0 $cond  ";
	
	$sql = " SELECT a.id, a.name, a.date_entered, a.sales_stage, a.assigned_user_id FROM opportunities as a WHERE a.deleted = 0 $cond  ";
	
	//if($_SERVER['REMOTE_ADDR'] == '109.251.117.69'){
	//	echo $sql;
	//}
	
	$res = $db->query($sql);
	$i = 0;
	$tbl_rows = '';
	while($row = $db->fetchByAssoc($res)){
		
		if( !in_array($row['sales_stage'], $app_list_strings['sales_stage_dom']) ){
			continue;
		}
		
		$i++;
		$tbl_rows .= '<tr>';
			$tbl_rows .= '<td>';
			$tbl_rows .= $i;
			$tbl_rows .= '</td>';
				$tbl_rows .= '<td>';
				$tbl_rows .= $app_list_strings['sales_stage_dom'][$row['sales_stage']];
				$tbl_rows .= '</td>';			
			$tbl_rows .= '<td>';
			$tbl_rows .= '<a href="index.php?module=Opportunities&action=DetailView&record='.$row['id'].'">'.$row['name'].'</a>';
			$tbl_rows .= '</td>';
				$tbl_rows .= '<td>';
				$tbl_rows .= $user_arr[$row['assigned_user_id']];
				$tbl_rows .= '</td>';
			$tbl_rows .= '<td>';
			$tbl_rows .= $timedate->to_display_date_time($row['date_entered']);
			$tbl_rows .= '</td>';
				//$tbl_rows .= '<td>';
				//$tbl_rows .= $categories[$row['aos_product_categories_id_c']];
				//$tbl_rows .= '</td>';
			//$tbl_rows .= '<td>';
			//$tbl_rows .= $app_list_strings['lead_source_dom'][$row['lead_source']];
			//$tbl_rows .= '</td>';
		$tbl_rows .= '</tr>';
	}

	//echo '<a href="index.php?module=AOR_Reports&action=">back</a>';
	
	$tbl = '<table  cellpadding="0" cellspacing="0" border="0" class="list">';
	$tbl .= '<tr>';
		$tbl .= '<th>#</th>';
		$tbl .= '<th>Sales stage</th>';
		$tbl .= '<th>Opportunity</th>';
		$tbl .= '<th>Assigned User</th>';
		$tbl .= '<th>Created On</th>';
		//$tbl .= '<th>Policy Category</th>';
		//$tbl .= '<th>Lead Source</th>';
	$tbl .= '</tr>';
	$tbl .= $tbl_rows;
	$tbl .= '</table>';

	echo '<br/><br/>'.$tbl;

	