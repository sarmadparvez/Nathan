<?php

	if (! defined('sugarEntry') || ! sugarEntry) die('Not A Valid Entry Point');

	$tbe_qbo = BeanFactory::getBean('tbe_qbo');
	$tbe_qbo->initialSetting();
	
	$administration = BeanFactory::getBean('Administration');
	$administration->retrieveSettings($tbe_qbo->settings_category);
	//prod
		$value = 'qyprdrlBKbznco2Fn8oxKi2yDVD50a';
		$value = $administration->encrpyt_before_save($value);
		$administration->saveSetting($tbe_qbo->settings_category, 'consumer_key', $value);
	$value = 'uHV9vTYFdWFyloa11bts7JGrWvwgU4iHejCIWgL9';
	$value = $administration->encrpyt_before_save($value);
	$administration->saveSetting($tbe_qbo->settings_category, 'consumer_secret', $value);		
	
	echo '-OK-';