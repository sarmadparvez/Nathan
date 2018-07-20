<?php


	global $db;
	
	//$sql = " INSERT INTO ax_egrab (email_id, ext_data, bean_type, bean_id, call_id) VALUES ('{$row['id']}', '{$params['description']}', '{$params['parent_type']}', '{$params['parent_id']}', '{$params['call_id']}');  ";
	
	echo '</br>Last 100 processed call2email records</br>';
	
	$rows = '';
	$sql = " SELECT * FROM  ax_egrab WHERE 1 ORDER BY num DESC LIMIT 0, 100; ";
	$res = $db->query($sql);
	while( $row = $db->fetchByAssoc($res) ){
		$rows .= '<tr>';
			$rows .= '<td>';
				$rows .= $row['num'];
			$rows .= '</td><td>';
				$rows .= '<a href="index.php?module=Emails&action=DetailView&record='.$row['email_id'].'">Email lnk</a>';
			$rows .= '</td><td>';
				$rows .= '<a href="index.php?module=Calls&action=DetailView&record='.$row['call_id'].'">Call lnk</a>';
			$rows .= '</td><td>';
				if(empty($row['bean_id'])){
					$rows .= 'No Lead';
				}else{
					$rows .= '<a href="index.php?module='.$row['bean_type'].'&action=DetailView&record='.$row['bean_id'].'">Bean lnk</a>';
				}
			$rows .= '</td><td>';
				$rows .= $row['ext_data'];
			$rows .= '</td>';
		$rows .= '</tr>';
	}
	
	echo '<table class="list view table" border="0" cellpadding="0" cellspacing="0" width="100%">'.$rows.'</table>';