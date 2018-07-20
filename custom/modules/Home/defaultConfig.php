<?php 
global $db; 
# Getting all the users
$user_array = get_user_array();
# Fetching all the product categories
$query = "SELECT pc.id, pc.name 
    FROM aos_product_categories as pc
    LEFT JOIN aos_product_categories_cstm as pcc ON pc.id = pcc.id_c
    WHERE pcc.include_in_routing_list_c = 1 ";
$productCat = $db->query($query, false);
$productCatResult = [];
# Looping through the result array to save all values in $productCatResult
while (($row = $db->fetchByAssoc($productCat)) != null) {
        # Adding all the values in an array to use in dropdown.
        $productCatResult[$row['id']] = $row['name'];
        }
//echo "<pre>"; print_r($productCatResult); die;
$query_usr = "SELECT u.id, u.first_name, u.last_name 
        FROM users as u
        LEFT JOIN users_cstm as uc ON u.id = uc.id_c
        WHERE uc.assign_lead_c = 1 ORDER BY first_name";
        $result_usr = $db->query($query_usr, false);
$users = [];
while (($row = $db->fetchByAssoc($result_usr)) != null) {
    $users[$row['id']] = $row['first_name'] .' '.$row['last_name'];            
        }
	require_once('custom/ax/DistribLead.php');
    /* Added by Hk on 9Apr2018 to make the json data to pass as filters and data*/
    $distribDefaultUser = DistribLead::getDistribDefaultData(true);
        ?>