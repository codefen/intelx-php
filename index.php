<?php

	// In order to use the application you must set up the API KEY and API URL

	$apiUrl = 'YOUR INTELX API URL';
	$apiKey = 'YOUR INTELX API KEY';

	// In order to use the application you must set up the API KEY and API URL

	if ($apiUrl == 'YOUR INTELX API URL' || $apiKey == 'YOUR INTELX API KEY') {
		header('Content-type: application/json');
		echo json_encode(['error' => 'API URL or API Key is not set. Please configure them before using the application.']);
		exit;
	}

	// HEADERS
	header('Access-Control-Allow-Origin: *');
	header('Content-type: application/json');


	// EXCEPTIONS
	ini_set('log_errors','on');
	
    // INITIALIZATIONS
	$feedback	    	= new class {};
	$inx				= new class {};
	$console			= new class {};	
	require_once("handler/functions.php");

	// INX HANDLER
	if ($_REQUEST['model'] == 'inx') {
		include_once('handler/inx.php');
	}
	
	else {
		$feedback->response = "error";
		$feedback->message = "Invalid model";
		return deliver($feedback);
	}
?>
