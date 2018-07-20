<?php
Global $conn;
 
$servername = "127.0.0.1";
$username = "root";
$password = "123";
$dbname = "nathan";
$pro_id = $_POST['product_id'];
$conn = new mysqli($servername, $username, $password, $dbname);
 
 
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    return;
}
else{
	//global $db;
	$query = "SELECT pay_not_to_producer_c FROM aos_products_cstm WHERE id_c = '$pro_id'";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    print_r($row['pay_not_to_producer_c']);
}
	


