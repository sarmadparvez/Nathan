<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class LeadsViewEdit extends ViewEdit
{
 	public function __construct()
 	{
 		parent::ViewEdit();
 		$this->useForSubpanel = true;
 		$this->useModuleQuickCreateTemplate = true;
 	}

	function display() {
    require_once 'modules/ACLRoles/ACLRole.php';

    //Get the current user's role
    $objACLRole = new ACLRole();
    $roles = $objACLRole->getUserRoles($GLOBALS['current_user']->id);

    $lead_source_select = '';
    //check if they are in the Office Support role
    if(in_array('Office Support',$roles)){
      print '<style type="text/css">#create_link, #create_image{ display:none; }</style>';
      $lead_source_select = 'readOnly';
    }

    $this->ev->ss->assign('LEAD_SOURCE', $lead_source_select);

		$disable_save_and_convert_btn = true;
		if(!empty($this->bean->id)){
			$disable_save_and_convert_btn = false;
		}
		$this->ss->assign('DISABLE_SAVE_AND_CONVERT_BTN', $disable_save_and_convert_btn);
		parent::display();
	}
}
