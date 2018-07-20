<?php

header('Access-Control-Allow-Origin: *');  


              
				

               

	


	$asterisk_ip = str_replace('|', '', $_GET['AsteriskIP']);
	$asterisk_ip = trim($asterisk_ip);
	$ext = $_GET["ext"];
	
	$user = str_replace('|', '', $_GET['username']);
	$pass =  str_replace('|', '', $_GET['pass']);
	
	

	//$user = str_replace('|', '', $username);
	//$pass =  str_replace('|', '', $password);
	
	$num = $_GET["phone"];
	$DialoutPrefix = $_GET["DialoutPrefix"];
	$DialPlan = $_GET["DialPlan"];

	$num = trim($num);
	$num=str_replace( array( '-'," ","%","+","(",")"), '', $num);
	
	
	 
	
	 echo "Dialing $num\r\n";
	  echo "User Extension:".$ext ."\r\nAsterisk Server:".$asterisk_ip."\r\n";
	   echo "User DialPlan:".$DialPlan ."\r\nDialoutPrefix:".$DialoutPrefix."\r\n";
	 
	// echo "User".$user."........pwd".$pass."\r\n";
 
                               $timeout = 10;
              // $asterisk_ip = "192.168.1.100";
 
                $socket = fsockopen($asterisk_ip,"5038", $errno, $errstr, $timeout);
                fputs($socket, "Action: Login\r\n");
                fputs($socket, "UserName: $user\r\n");
                fputs($socket, "Secret: $pass\r\n\r\n");
 
                $wrets=fgets($socket,128);
 
                echo $wrets;
 
                fputs($socket, "Action: Originate\r\n" );
                fputs($socket, "Channel: $DialoutPrefix\r\n" );
                fputs($socket, "Exten: $num\r\n" );
                fputs($socket, "Context: $DialPlan\r\n" );
                fputs($socket, "Priority: 1\r\n" );
                fputs($socket, "Async: yes\r\n" );
                fputs($socket, "WaitTime: 15\r\n" );
                fputs($socket, "Callerid:$ext\r\n\r\n" );
                              fputs ($socket, "Action: Logoff\r\n\r\n");
                
				while (!feof($socket)) {
  $wrets .= fread($socket, 8192);
}
fclose($socket);

	
	 
                      
        

    






?>
