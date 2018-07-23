<?php
$assignedUserChanged = false;
class lead_distrib_hook
{
    function fix_type(&$bean, $event, $arguments)
    {
        if (!empty($bean->date_entered) && $bean->date_entered == $bean->date_modified) {
            if (empty($bean->coverage_type_c) && !empty($bean->aos_product_categories_id_c)) {
                if ($bean->aos_product_categories_id_c == '58969f58-2d7f-6eb7-9b9e-5669c1f53421') {
                    $bean->coverage_type_c = 'TradeCreditWebsite';
                } elseif ($bean->aos_product_categories_id_c == 'a4613fed-0529-e7f8-446a-5453b0387baf') {
                    $bean->coverage_type_c = 'ErrorsOmissionsInsurance';
                }
            }
            if (!array_key_exists($bean->coverage_type_c, $GLOBALS['app_list_strings']['lead_coverage_type_list'])) {
                $detected_key = self::tryDetectListKey($bean->coverage_type_c, 'lead_coverage_type_list');
                if (!empty($detected_key)) {
                    $bean->coverage_type_c = $detected_key;
                }
            }
        }
    }
    function disableNotifyCheck($bean, $event, $arguments)
    {
        $admin = BeanFactory::getBean("Administration");
        $admin->retrieveSettings();
        $admin->saveSetting("notify", "on", false);
        global $current_user;
        global $assignedUserChanged;
        global $timedate;

        //override_lead_assignment is set from editview to detect if request is coming from UI or not
        // Automatic lead assignment should not apply if request is coming from UI.
        if ($bean->fetched_row['assigned_user_id'] != $bean->assigned_user_id && 
            empty($_REQUEST['override_lead_assignment'])) {
            $assignedUserChanged = true;
            $bean->aos_product_categories_id_c = str_replace("%2D", "-", $bean->aos_product_categories_id_c);
            $bean->primary_address_state       = str_replace("%20", " ", $bean->primary_address_state);
            
            if (empty($bean->lead_source)) {
                $bean->lead_source = $this->remove_http($_SERVER['HTTP_ORIGIN']);
            }
            if (empty($bean->phone_work)) {
                $bean->phone_work = $bean->phone_mobile;
            }
            require_once('custom/ax/DistribLead.php');
            $data = DistribLead::getExtractedDistribData($bean->aos_product_categories_id_c, $bean->primary_address_state);
            
            if (!empty($data['primaryUser'])) {
                $bean->assigned_user_id        = $data['primaryUser'];
                $bean->user_id_c               = $data['primaryUser'];
                $bean->first_assignment_time_c = $timedate->nowDb();
            }
        } else if (!empty($bean->assigned_user_id) && 
            $bean->fetched_row['assigned_user_id'] != $bean->assigned_user_id &&
            !empty($_REQUEST['override_lead_assignment']) && empty($bean->first_assignment_time_c)) {
            // if assigned user is changed manually, second, thrid, fourth assignment 
            //should happen automatically
            $bean->first_assignment_time_c = $timedate->nowDb();
        }
    }
    function do_distrib(&$bean, $event, $arguments)
    {
        global $current_user;
        global $assignedUserChanged;
        global $timedate;
        if ($assignedUserChanged == true) {
            require_once('custom/ax/DistribLead.php');
            $data = DistribLead::getExtractedDistribData($bean->aos_product_categories_id_c, $bean->primary_address_state);       
            
            if (!empty($data['primaryUser'])) {
                DistribLead::sendAssignNotify($bean, $data);
            }
        }
        $admin = BeanFactory::getBean("Administration");
        $admin->retrieveSettings();
        $admin->saveSetting("notify", "on", true);
    }
    function remove_http($url)
    {
        $disallowed = array(
            'http://',
            'https://'
        );
        foreach ($disallowed as $d) {
            if (strpos($url, $d) === 0) {
                return str_replace($d, '', $url);
            }
        }
        return $url;
    }
    static public function tryDetectListKey($input_value, $list)
    {
        if (!is_array($list)) {
            global $app_list_strings;
            if (isset($app_list_strings[$list])) {
                $list = $app_list_strings[$list];
            } else {
                return false;
            }
        }
        if (!empty($list)) {
            $list_k = array_map('strtolower', $list);
            if (array_key_exists($input_value, $list)) {
                return $input_value;
            }
            $key = array_search($input_value, $list);
            if (!empty($key)) {
                return $key;
            }
            $key = array_search(strtolower($input_value), $list_k);
            if (!empty($key)) {
                return $key;
            }
            $input_value = str_ireplace(" ", "", $input_value);
            if (array_key_exists($input_value, $list)) {
                return $input_value;
            }
            $key = array_search($input_value, $list);
            if (!empty($key)) {
                return $key;
            }
            $key = array_search(strtolower($input_value), $list_k);
            if (!empty($key)) {
                return $key;
            }
            $input_value = str_ireplace("&", "and", $input_value);
            if (array_key_exists($input_value, $list)) {
                return $input_value;
            }
            $key = array_search($input_value, $list);
            if (!empty($key)) {
                return $key;
            }
            $key = array_search(strtolower($input_value), $list_k);
            if (!empty($key)) {
                return $key;
            }
            $input_value = str_ireplace("Insurance", "", $input_value);
            if (array_key_exists($input_value, $list)) {
                return $input_value;
            }
            $key = array_search($input_value, $list);
            if (!empty($key)) {
                return $key;
            }
            $key = array_search(strtolower($input_value), $list_k);
            if (!empty($key)) {
                return $key;
            }
        }
        return false;
    }
}