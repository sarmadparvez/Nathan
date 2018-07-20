<?php

$job_strings[] = 'leadTimeClose';
function leadTimeClose(){
	require_once('custom/include/ax_jobs.php');
	$result = false;
	$result = ax_jobs::leadTimeClose();
	return $result;
}

$job_strings[] = 'oppRenewal';
function oppRenewal(){
	require_once('custom/include/ax_jobs.php');
	$result = false;
	$result = ax_jobs::oppRenewal();
	return $result;
}

$job_strings[] = 'runHotLeadReminder';
function runHotLeadReminder(){
	require_once('custom/include/ax_jobs.php');
	$result = false;
	$result = ax_jobs::hotLead();
	return $result;
}


$job_strings[] = 'runOpenLeadsReminder';
function runOpenLeadsReminder(){
	require_once('custom/include/ax_jobs.php');
	$result = false;
	$result = ax_jobs::runOpenLeadReminder();
	return $result;
}


$job_strings[] = 'grabCallMailbox';
function grabCallMailbox(){
	
	require_once('custom/include/ax/axJob.php');
	//axJob::grabCallMailbox();
	axJob::processCallMailbox();
	
	return true;
}
