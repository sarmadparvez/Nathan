<?php

if( isset($_REQUEST['id']) && !empty($_REQUEST['id']) ){
	require_once('custom/hooks/leadfunc.php');
	$rez_arr = leadfunc::setDead($_REQUEST['id']);
	echo $rez_arr['msg'];
}else{
	echo 'Error: not enough parameters.';
}