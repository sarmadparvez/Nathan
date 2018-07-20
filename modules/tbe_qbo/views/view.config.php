<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/MVC/View/views/view.detail.php');
class Viewconfig extends ViewDetail{
	
	function Viewconfig() {
		parent::ViewDetail();
	}
    public function display() {
		echo 'QBO config Page';
	}
}

?>