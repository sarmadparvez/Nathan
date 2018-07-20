<?php
class Hook_sum {
function do_sum($bean, $event, $args) {
	global $db;
	if (!empty($bean->patched_to)) {
		$patch = "SELECT first_name, last_name FROM users where id = '$bean->patched_to'";
     $result_patch = $db->query($patch, false);
     if ($result_patch->num_rows > 0) {
       $row = $db->fetchByAssoc($result_patch);
       $name = $row['first_name'] .' '.$row['last_name'];
     }
     $bean->patched_to = $name;
 }
 if (!empty($bean->product_inquired)) {
    $cate =  "SELECT name FROM aos_product_categories where id = '$bean->product_inquired'";
    $result_cate = $db->query($cate, false);
    if ($result_cate->num_rows > 0) {
       $row1 = $db->fetchByAssoc($result_cate);
       $catname = $row1['name'];
     }
	$bean->product_inquired = $catname;
	}
}
} 

