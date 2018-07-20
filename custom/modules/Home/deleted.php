<?php

echo '+';

global $db;

$sql = " SELECT * FROM leads WHERE deleted = 1 ORDER BY date_entered DESC ";
$res = $db->query($sql);
while($row = $db->fetchByAssoc($res)){
	echo '<pre>';print_r($row);echo '</pre>';
}
