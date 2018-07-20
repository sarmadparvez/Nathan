<?php

$admin_option_defs=array();

$admin_option_defs['the_distro']['settings'] = array(	'Administration', 	'Settings',	'Set-up Lead Routing',	'./index.php?module=Home&action=Distrib' );

$admin_option_defs['the_distro']['default_settings'] = array(	'Administration', 	'General Settings',	'Set-up Default User For Lead Routing',	'./index.php?module=Home&action=DefaultSettings' );


$admin_group_header[] = array('Lead Routing', '', false, $admin_option_defs, '');


?>