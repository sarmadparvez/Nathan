<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

class LoadConnector 
{
	function echoJavaScript() 
	{
		 if ((!isset($_REQUEST['sugar_body_only']) || $_REQUEST['sugar_body_only'] != true) && $_REQUEST['action'] != 'modulelistmenu' &&
             $_REQUEST['action'] != "favorites" && $_REQUEST['action'] != 'Popup' && empty($_REQUEST['to_pdf']) &&
            (!empty($_REQUEST['module']) && $_REQUEST['module'] != 'ModuleBuilder') && empty($_REQUEST['to_csv']) && $_REQUEST['action'] != 'Login' &&
            $_REQUEST['module'] != 'Timesheets') 
            {
			
				$xml = simplexml_load_file('custom/modules/Asterisk/AsteriskProperties.xml');
				$username = $xml->AsteriskProperty->username;
				$password = $xml->AsteriskProperty->password;
				


				$PhoneExtension=$GLOBALS['current_user']->phoneextension_c ;
				$AsteriskIP=$GLOBALS['current_user']->asteriskname_c ;
				$DialoutPrefix=$GLOBALS['current_user']->dialout_prefix_c ;
				$DialPlan=$GLOBALS['current_user']->dial_plan_c ;
				
				//Loading all variables and js,css files

				
				echo '<script type="text/javascript">window.AsteriskIP = ' . "'$AsteriskIP'" . ';</script>';//
				echo '<script type="text/javascript">window.PhoneExtension =' . "'$PhoneExtension'" . ';</script>';
				echo '<script type="text/javascript">window.DialoutPrefix = ' . "'$DialoutPrefix'" . ';</script>';
				echo '<script type="text/javascript">window.DialPlan = ' . "'$DialPlan'" . ';</script>';
				echo '<script type="text/javascript">window.username = ' . "'$username'" . ';</script>';
				echo '<script type="text/javascript">window.password = ' . "'$password'" . ';</script>';
				
	//echo "<script>alert(window.AsteriskIP);</script>";
				echo '<script type="text/javascript" src="custom/modules/Asterisk/include/js/dialout.js"></script>';

			}
		
	}
              
}


?>
