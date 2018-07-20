<?php
function getProducts(){
    static $products = null;
    if(!$products){
        global $db;
        $query = "SELECT pc.id, pc.name 
    FROM aos_product_categories as pc
    LEFT JOIN aos_product_categories_cstm as pcc ON pc.id = pcc.id_c
    WHERE pcc.include_in_routing_list_c = 1 ";
        $result = $db->query($query, false);

        $products = array();
        $products[''] = '';

        while (($row = $db->fetchByAssoc($result)) != null) {
            $products[$row['id']] = $row['name'];
        }
    }
    return $products;
}
