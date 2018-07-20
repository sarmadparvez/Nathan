<?php

	if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
	
	global $app_list_strings;
	


echo '
<link type="text/css" href="custom/ax/jquery/ui_theme/cupertino/jquery-ui-1.8.14.custom.css" rel="Stylesheet" />
<script type="text/javascript" src="custom/ax/jquery/jquery162.min.js" ></script>
<script type="text/javascript" src="custom/ax/jquery/jquery-ui-1.8.14.light.min.js" ></script>
<script type="text/javascript" src="custom/ax/jquery/jquery.datepick-ru.js" ></script>
  <script>
  $(function() {
    $("#listDeletedTabs").tabs();
  });
  </script>
';

	
	global $db, $timedate;
	
	$user_list = get_user_array(false, '', '', false, null, '');
	
	$beans = array(
	'opportunities',
	'accounts',
	'contacts',
	'leads',
	'tasks',
	'calls',
	'meetings',
	);
	$TD_STYLE = 'vertical-align: middle; padding: 3px 8px 5px 5px!important; border-bottom: 1px solid #cbdae6; white-space: normal;word-wrap: break-word;';
	$tbl_head = '<table style="text-align: center;"><thead><tr><th width="5px">#</th><th width="200px">Name</th><th width="150px">Assigned User</th><th width="150px">Modified On</th><th width="150px">Modified By</th></tr>
		</thead>
		<tbody>';
	$tbl_footer = '</tbody></table>';
	$nn = 0;
	$tabs_ul = '';
	$tabs_content = '';
	foreach($beans as $tbl_bean){
		$tab_name = $app_list_strings['moduleList'][ucfirst($tbl_bean)];
		$tabs_ul .= '<li><a href="#'.$tbl_bean.'">'.$tab_name.'</a></li>';
		$nn = 0;
		$name_fieldname = 'name';
		if($tbl_bean == 'contacts' || $tbl_bean == 'leads'){
			$name_fieldname = 'last_name';
		}
		$table_content = '';
		$sql = "SELECT id, $name_fieldname as name, assigned_user_id, date_modified, modified_user_id FROM $tbl_bean WHERE deleted = '1' ORDER BY date_modified DESC LIMIT 0, 250";
		$res = $db->query($sql);
		while($row = $db->fetchByAssoc($res)){
			$nn++;
			$table_content .= "<tr>";
			$table_content .= '<td style="'.$TD_STYLE.'text-align: left;">'.$nn.'</td>';
			$table_content .= '<td style="'.$TD_STYLE.'text-align: left;">'.$row['name'].'</td>';
			$table_content .= '<td style="'.$TD_STYLE.'">'.$user_list[$row['assigned_user_id']].'</td>';
			//$table_content .= '<td style="'.$TD_STYLE.'">'.$timedate->to_display_date($row['date_modified']).'</td>';
			$table_content .= '<td style="'.$TD_STYLE.'">'.$timedate->to_display_date_time($row['date_modified']).'</td>';
			$table_content .= '<td style="'.$TD_STYLE.'">'.$user_list[$row['modified_user_id']].'</td>';
			$table_content .= '</tr>';
		}
		$tabs_content .= '<div id="'.$tbl_bean.'">';
		if(!empty($table_content)){
			$tabs_content .= $tbl_head.$table_content.$tbl_footer;
		}else{
			$tabs_content .= 'No deleted records';
		}
		$tabs_content .= '</div>';
	}
	
	echo '<div id="listDeletedTabs"><ul>'.$tabs_ul.'</ul>'.$tabs_content.'</div>';

	
	

?>