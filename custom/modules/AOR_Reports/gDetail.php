<?php

	if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

	global $db, $app_list_strings;

	$user_arr = get_user_array($add_blank = false, $status = '', $user_id='', $use_real_name=false, $user_name_filter = '', $portal_filter ='', $from_cache = true);
	
	$cond = '';
	$cond_html = '';

	$module = '';
	if(	!empty($_REQUEST['m']) ){
		if($_REQUEST['m'] == 'o'){
			$module = 'Opportunities';
		}
		if($_REQUEST['m'] == 'l'){
			$module = 'Leads';
		}
	}
	
	$s = '';
	if(	!empty($_REQUEST['s']) ){
		$s = $_REQUEST['s'];
	}
	
	if(	!empty($_REQUEST['u']) ){
		if( is_array($_REQUEST['u']) ){
			$u_str = implode("','", $_REQUEST['u']);
			$cond .= " AND a.assigned_user_id IN ('{$u_str}') AND (a.assigned_user_id <> '' AND a.assigned_user_id IS NOT NULL) ";
			
			$cond_html .= ' <b>Users:</b> ';
			foreach($_REQUEST['u'] as $i => $user_id){
				$cond_html .= $user_arr[$user_id].', ';
			}			
		}else{
			$value = $db->quote($_REQUEST['u']);
			$cond .= ' AND a.assigned_user_id = "'.$value.'" ';
			$cond_html .= ' <b>User:</b> '.$user_arr[$value];
		}
	}

//----------------------
	
	if($module == 'Opportunities'){
		
		if($s == 'open'){
			$cond .= " AND a.sales_stage NOT IN ('Closed Lost', 'Closed Won') ";
			$cond_html .= ' <b>Sales stage:</b> "Open" ';
		}
		if($s == 'lost'){
			$cond .= " AND a.sales_stage = 'Closed Lost' ";
			$cond_html .= ' <b>Sales stage:</b> Closed Lost ';
		}
		if($s == 'won'){
			$cond .= " AND a.sales_stage = 'Closed Won' ";
			$cond_html .= ' <b>Sales stage:</b> Closed Won ';
		}
		if(	!empty($_REQUEST['f']) ){
			$value = $db->quote($_REQUEST['f']);
			$cond .= " AND a.date_entered >= ".db_convert("'".$value." 00:00:00'", 'date');
			$cond_html .= ' <b>From Date:</b> '.$value;
		}
		if(	!empty($_REQUEST['t']) ){
			$value = $db->quote($_REQUEST['t']);
			$cond .= " AND a.date_entered <= ".db_convert("'".$value." 23:59:59'", 'date');
			$cond_html .= ' <b>Till Date:</b> '.$value;
		}
		$cond .= " AND a.opportunity_type = 'New Business' ";
		$cond_html .= ' <b>Type:</b> New Business ';
		
		$sql = " SELECT a.id, a.name, a.date_entered, a.sales_stage, a.assigned_user_id FROM opportunities as a WHERE a.deleted = 0 {$cond}; ";

		$data = array();

		$res = $db->query($sql);
		while( $row = $db->fetchByAssoc($res) ){
			$data[] = $row;
		}
		
		$tbl_rows = '';
		foreach($data as $i => $item){
			$tbl_rows .= '<tr>';
				$tbl_rows .= '<td>';
				$tbl_rows .= ($i+1);
				$tbl_rows .= '</td>';
					$tbl_rows .= '<td>';
					$tbl_rows .= $app_list_strings['sales_stage_dom'][$item['sales_stage']];
					$tbl_rows .= '</td>';			
				$tbl_rows .= '<td>';
				$tbl_rows .= '<a href="index.php?module=Opportunities&action=DetailView&record='.$item['id'].'">'.$item['name'].'</a>';
				$tbl_rows .= '</td>';
					$tbl_rows .= '<td>';
					$tbl_rows .= $user_arr[$item['assigned_user_id']];
					$tbl_rows .= '</td>';
				$tbl_rows .= '<td>';
				$tbl_rows .= $timedate->to_display_date_time($item['date_entered']);
				$tbl_rows .= '</td>';
			$tbl_rows .= '</tr>';
		}
		
		$tbl = '<table  cellpadding="0" cellspacing="0" border="0" class="list">';
		$tbl .= '<tr>';
			$tbl .= '<th>#</th>';
			$tbl .= '<th>Sales stage</th>';
			$tbl .= '<th>Opportunity</th>';
			$tbl .= '<th>Assigned User</th>';
			$tbl .= '<th>Created On</th>';
		$tbl .= '</tr>';
		$tbl .= $tbl_rows;
		$tbl .= '</table>';		
		
		if( !empty($cond_html) ){
			echo '<h3>Filter:</h3> '.$cond_html.'<br/><br/>';
		}
		echo $tbl;
		
	}
	
	
//----------------------
	
	if($module == 'Leads'){
		
		$categories = array();
		$sql = " SELECT id, name FROM aos_product_categories WHERE deleted = 0 ORDER BY name ASC; ";
		$res = $db->query($sql);
		while($row = $db->fetchByAssoc($res)){
			$categories[$row['id']] = $row['name'];
		}
	
		if($s == 'open'){
			$cond .= " AND a.status NOT IN ('Converted', 'Dead') ";
			$cond_html .= ' <b>Status:</b> "Open" ';
		}
		if($s == 'dead'){
			$cond .= " AND a.status = 'Dead' ";
			$cond_html .= ' <b>Status:</b> Dead ';
		}
		if($s == 'conv'){
			$cond .= " AND a.status = 'Converted' ";
			$cond_html .= ' <b>Status:</b> Converted ';
		}		
		if(	!empty($_REQUEST['f']) ){
			$value = $db->quote($_REQUEST['f']);
			$cond .= " AND a.date_entered >= ".db_convert("'".$value." 00:00:00'", 'date');
			$cond_html .= ' <b>From Date:</b> '.$value;
		}
		if(	!empty($_REQUEST['t']) ){
			$value = $db->quote($_REQUEST['t']);
			$cond .= " AND a.date_entered <= ".db_convert("'".$value." 23:59:59'", 'date');
			$cond_html .= ' <b>Till Date:</b> '.$value;
		}
		
		if( !empty($cond_html) ){
			echo '<h3>Filter:</h3> '.$cond_html.'<br/><br/>';
		}
			
		$sql = " SELECT a.date_entered, a.id, a.last_name, a.first_name, a.assigned_user_id, a.status, c.aos_product_categories_id_c, a.lead_source FROM leads as a 
		LEFT JOIN leads_cstm as c ON c.id_c = a.id
		WHERE a.deleted = 0 $cond  ";
		$res = $db->query($sql);
		$tbl_rows = '';
		while($row = $db->fetchByAssoc($res)){
			$data[] = $row;
		}
		$tbl_rows = '';
		foreach($data as $i => $item){
			$tbl_rows .= '<tr>';
				$tbl_rows .= '<td>';
				$tbl_rows .= ($i+1);
				$tbl_rows .= '</td>';
					$tbl_rows .= '<td>';
					$tbl_rows .= $item['status'];
					$tbl_rows .= '</td>';			
				$tbl_rows .= '<td>';
				$tbl_rows .= '<a href="index.php?module=Leads&action=DetailView&record='.$item['id'].'">'.$item['first_name'].' '.$item['last_name'].'</a>';
				$tbl_rows .= '</td>';
					$tbl_rows .= '<td>';
					$tbl_rows .= $user_arr[$item['assigned_user_id']];
					$tbl_rows .= '</td>';				
				$tbl_rows .= '<td>';
				$tbl_rows .= $timedate->to_display_date_time($item['date_entered']);
				$tbl_rows .= '</td>';
					$tbl_rows .= '<td>';
					$tbl_rows .= $categories[$item['aos_product_categories_id_c']];
					$tbl_rows .= '</td>';
				$tbl_rows .= '<td>';
				$tbl_rows .= $app_list_strings['lead_source_dom'][$item['lead_source']];
				$tbl_rows .= '</td>';
			$tbl_rows .= '</tr>';
		}

		//echo '<a href="index.php?module=AOR_Reports&action=">back</a>';
		
		$tbl = '<table  cellpadding="0" cellspacing="0" border="0" class="list">';
		$tbl .= '<tr>';
			$tbl .= '<th>#</th>';
			$tbl .= '<th>Status</th>';
			$tbl .= '<th>Lead</th>';
			$tbl .= '<th>Assigned User</th>';
			$tbl .= '<th>Created On</th>';
			$tbl .= '<th>Policy Category</th>';
			$tbl .= '<th>Lead Source</th>';
		$tbl .= '</tr>';
		$tbl .= $tbl_rows;
		$tbl .= '</table>';

		echo '<br/><br/>'.$tbl;		
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	


