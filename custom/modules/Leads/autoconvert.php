<?php

if( isset($_REQUEST['record']) && !empty($_REQUEST['record']) ){
	require_once('custom/hooks/leadfunc.php');
	$rez_arr = leadfunc::do_convert($_REQUEST['record']);
	ob_clean();
	header('Location: /index.php?module='.$rez_arr['module'].'&action=DetailView&record='.$rez_arr['id']);
	exit;
}
//echo 'OK';//we will return id?