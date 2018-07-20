<?php
function getUsers(){
    static $users = null;
    if(!$users){
        global $db;
        $query = "SELECT u.id, u.first_name, u.last_name 
        FROM users as u
        LEFT JOIN users_cstm as uc ON u.id = uc.id_c
        WHERE uc.assign_lead_c = 1 ORDER BY first_name";
        $result = $db->query($query, false);

        $users = array();
        $users[''] = '';
        $users['could_not_patch'] = 'Could Not Patch';

        while (($row = $db->fetchByAssoc($result)) != null) {
            $users[$row['id']] = $row['first_name'] .' '.$row['last_name'];
        }
    }
    return $users;
}
