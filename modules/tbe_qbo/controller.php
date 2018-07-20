<?php
 
require_once('include/MVC/Controller/SugarController.php');

class tbe_qboController extends SugarController {
	function action_qboexport() {
		$this->view = 'qboexport';
	}	
	function action_qboupdate() {
		$this->view = 'qboupdate';
	}	
	function action_cancel() {
		$this->view = 'cancel';
	}
	function action_connection() {
		$this->view = 'connection';
	}
	function action_config() {
		$this->view = 'config';
	}
	function action_grablists() {
		$this->view = 'grablists';
	}
	
	function action_reconnect() {
		$this->view = 'reconnect';
	}	
	function action_disconnect() {
		$this->view = 'disconnect';
	}
	
}

?> 
