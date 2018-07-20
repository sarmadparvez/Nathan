<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/MVC/View/views/view.detail.php');
class Viewgrablists extends ViewDetail{
	function Viewgrablists() {
		parent::ViewDetail();
	}
	public function display() {
		
		$tbe_qbo = BeanFactory::getBean('tbe_qbo');
		$tbe_qbo->grabLists();
		
	}
}