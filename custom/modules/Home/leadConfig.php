<?php 
global $db;
// Getting Province values from Dropdown Editor
$province = $app_list_strings['Province']; 
$province_js = "";
# Converting province values into json to use in knockout js
if(!empty($province)){
foreach($province as $pro){
$province_js .= " { name: '{$pro}', id: '{$pro}'}, ";
}
} 
# Getting all the users
$user_array = get_user_array();
$query = "SELECT pc.id, pc.name 
    FROM aos_product_categories as pc
    LEFT JOIN aos_product_categories_cstm as pcc ON pc.id = pcc.id_c
    WHERE pcc.include_in_routing_list_c = 1 ";
        $result = $db->query($query, false);

/*code to change products in product category start end*/
$init_json = '';

$items_js = '';
while (($row = $db->fetchByAssoc($result)) != null) {
        # convert the single quote entity to single quote again as its giving problem when coming default.
        $name = str_replace("&#039;", "'", $row['name']); 
		$id = strtr($row['name'], array(" " => ""));
         $items_js .= " { name: \"{$name}\", id: '{$row['id']}'}, ";
        }

$query_usr = "SELECT u.id, u.first_name, u.last_name 
        FROM users as u
        LEFT JOIN users_cstm as uc ON u.id = uc.id_c
        WHERE uc.assign_lead_c = 1 ORDER BY first_name";
        $result_usr = $db->query($query_usr, false);
$user_js = '';
while (($row = $db->fetchByAssoc($result_usr)) != null) {
	$name = $row['first_name'] .' '.$row['last_name'];
	$user_js .= " { name: '{$name}', id: '{$row['id']}'}, ";
            
        }
       
    $filters_js = "{name: 'None', id: 'None'},{name: 'Model1', id: 'Model1'},{name: 'Model2', id: 'Model2'}"; 
	require_once('custom/ax/DistribLead.php');
    /* Added by Hk on 9Apr2018 to make the json data to pass as filters and data*/
	$distribArr = DistribLead::getDistribData(false);
    $models = DistribLead::getDistribData(true); 
    $filters = [];
    if(!empty($distribArr)){
    foreach($distribArr as $key=>$distrib){
    $filters[$key] = $distrib['Name'];
    // foreach($distrib['contacts'] as $ky=>$state){
    //   $distribArr[$key]['contacts'][$ky]['state'] = array($state['state'],'NWT'); 
    // }
    }
    }
    $filtersDataJson = [];
    $filtersDataJson['filters'] = $filters;
    $filtersDataJson['models'] = $distribArr; 
    $jsonData = json_encode($filtersDataJson);
	//echo "<pre>"; print_r($filtersDataJson);
        ?>