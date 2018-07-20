<?php

if( $_SERVER['REMOTE_ADDR'] == '109.251.117.69'){
	echo $_SERVER['SERVER_ADDR'];
	
/*	try{
		$qb_path = 'qb/v3-sdk-2.3.0/';
		//$qb_path = 'v3-sdk-2.3.0/';

		require_once($qb_path.'config.php');
		require_once(PATH_SDK_ROOT . 'Core/ServiceContext.php');
		require_once(PATH_SDK_ROOT . 'DataService/DataService.php');
		require_once(PATH_SDK_ROOT . 'PlatformService/PlatformService.php');
		require_once(PATH_SDK_ROOT . 'Utility/Configuration/ConfigurationManager.php');

		$requestValidator = new OAuthRequestValidator(ConfigurationManager::AppSettings('AccessToken'), ConfigurationManager::AppSettings('AccessTokenSecret'), ConfigurationManager::AppSettings('ConsumerKey'), ConfigurationManager::AppSettings('ConsumerSecret'));
		
		$realmId = ConfigurationManager::AppSettings('RealmID');
		if (!$realmId) exit("Please add realm to App.Config before running this sample.\n");

		$serviceContext = new ServiceContext($realmId, IntuitServicesType::QBO, $requestValidator);
		if (!$serviceContext) exit("Problem while initializing ServiceContext.\n");

		$dataService = new DataService($serviceContext);
		if (!$dataService) exit("Problem while initializing DataService.\n");

	} catch (Exception $e) {
		echo 'Caught exception: ',  $e->getMessage(), "\n";
	}
	*/
}

echo '<br/>+<br/>';
if( $_SERVER['REMOTE_ADDR'] == '109.251.117.69'){
	//echo '<pre>';
	//print_r($_SERVER);
	//echo '</pre>';
	
/*
Array
(
    [USER] => http
    [HOME] => /var/services/web
    [FCGI_ROLE] => RESPONDER
    [REDIRECT_MOD_X_SENDFILE_ENABLED] => yes
    [REDIRECT_HANDLER] => php5-fastcgi
    [REDIRECT_STATUS] => 200
    [MOD_X_SENDFILE_ENABLED] => yes
    [HTTP_HOST] => crm.bondsurety.ca
    [HTTP_CONNECTION] => keep-alive
    [HTTP_CACHE_CONTROL] => max-age=0
    [HTTP_ACCEPT] => text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,/;q=0.8
    [HTTP_UPGRADE_INSECURE_REQUESTS] => 1
    [HTTP_USER_AGENT] => Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.111 Safari/537.36
    [HTTP_ACCEPT_ENCODING] => gzip, deflate, sdch
    [HTTP_ACCEPT_LANGUAGE] => en-US,en;q=0.8,ru;q=0.6
    [HTTP_COOKIE] => __utma=65846717.1388539435.1446560969.1449771703.1452518207.5; __utmz=65846717.1446560969.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); Leads_divs=activities_v%3D%23history_v%3D%23; PHPSESSID=l384g3jq9uqssog2dm8rac13p2; sugar_user_theme=Suite7; ck_login_id_20=3c4e92c9-c147-2849-0c10-54f4b2c4a466; ck_login_language_20=en_us
    [PATH] => /bin:/sbin:/usr/bin:/usr/sbin:/usr/syno/bin:/usr/syno/sbin:/usr/local/bin:/usr/local/sbin
    [SERVER_SIGNATURE] => 
    [SERVER_SOFTWARE] => Apache
    [SERVER_NAME] => crm.bondsurety.ca
    [SERVER_ADDR] => 192.168.0.10
    [SERVER_PORT] => 80
    [REMOTE_ADDR] => 109.251.117.69
    [DOCUMENT_ROOT] => /var/services/web/crm
    [SERVER_ADMIN] => admin
    [SCRIPT_FILENAME] => /var/services/web/crm/wat.php
    [REMOTE_PORT] => 54249
    [REDIRECT_URL] => /wat.php
    [GATEWAY_INTERFACE] => CGI/1.1
    [SERVER_PROTOCOL] => HTTP/1.1
    [REQUEST_METHOD] => GET
    [QUERY_STRING] => 
    [REQUEST_URI] => /wat.php
    [SCRIPT_NAME] => /wat.php
    [ORIG_SCRIPT_FILENAME] => /php-fpm-handler
    [ORIG_PATH_INFO] => /wat.php
    [ORIG_PATH_TRANSLATED] => /var/services/web/crm/wat.php
    [ORIG_SCRIPT_NAME] => /php-fpm-handler.fcgi
    [PHP_SELF] => /wat.php
    [REQUEST_TIME_FLOAT] => 1453735628.0703
    [REQUEST_TIME] => 1453735628
)
*/	
	//phpinfo();
}
