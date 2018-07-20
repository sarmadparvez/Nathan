<?php

	$tbe_qbo = BeanFactory::getBean('tbe_qbo'); 
	$tbe_qbo->retrieveSetting(); 
	$administration = BeanFactory::getBean('Administration');
	try { 
		$oauth = new OAuth($tbe_qbo->consumer_key, $tbe_qbo->consumer_secret, OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_URI);  
		$oauth->enableDebug();
		$oauth->disableSSLChecks();

		if( !isset( $_GET['oauth_token'] ) && empty($tbe_qbo->access_token) ){
			// step 1: get request token from Intuit
			$request_token = $oauth->getRequestToken( $tbe_qbo->oauth_request_url, $tbe_qbo->callback_url );
			$administration->saveSetting($tbe_qbo->settings_category, 'request_token_secret', $request_token['oauth_token_secret']);
			// step 2: send user to intuit to authorize 
			header('Location: '. $tbe_qbo->oauth_authorise_url .'?oauth_token='.$request_token['oauth_token']);
		}
		
		if ( isset($_GET['oauth_token']) && isset($_GET['oauth_verifier']) ){
			// step 3: request a access token from Intuit
			$oauth->setToken($_GET['oauth_token'], $tbe_qbo->request_token_secret);
			$access_token = $oauth->getAccessToken( $tbe_qbo->oauth_access_url );
			if($access_token !== false){//this is new
				$administration->saveSetting($tbe_qbo->settings_category, 'access_token_datetime', gmdate('Y-m-d H:i:s'));
				
				$encrypted = $administration->encrpyt_before_save($access_token['oauth_token']);
				$administration->saveSetting($tbe_qbo->settings_category, 'access_token', $encrypted);
				
				$encrypted = $administration->encrpyt_before_save($access_token['oauth_token_secret']);
				$administration->saveSetting($tbe_qbo->settings_category, 'access_token_secret', $encrypted);
				
				$encrypted = $administration->encrpyt_before_save($administration->db->quote($_REQUEST['realmId']));
				$administration->saveSetting($tbe_qbo->settings_category, 'realmid', $encrypted);
				echo 'Success, you may close this window.';
				echo '<script type="text/javascript">window.opener.location.href = window.opener.location.href; window.close();</script>';
			}
		}

	} catch(OAuthException $e) {
		echo "Got auth exception";
		echo '<pre>';
		print_r($e);
	}

?>