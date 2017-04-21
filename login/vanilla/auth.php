<?php
	require_once dirname(__FILE__).'/functions.jsconnect.php';
	include_once (dirname(__FILE__)."/../../lib/session_manager.php");
	include_once (dirname(__FILE__)."/../../database/config.php");

	//file_put_contents("auth_op.txt", print_r($_REQUEST, true));
	
	// 1. Get your client ID and secret here. These must match those in your jsConnect settings.
	$clientID = 424313456;
	$secret = "6b71757397c7562cfc512d1ee4e04c2d";

	// 2. Grab the current user from your session management system or database here.
	$signedIn = true; // this is just a placeholder

	// 3. Fill in the user information in a way that Vanilla can understand.
	$user = array();
	
	$idReserved = array_search(CSessionManager::Get(CSessionManager::STR_EMAIL_ID), CConfig::$reserved_emails);
	if ($signedIn && $idReserved === FALSE) {
	   // CHANGE THESE FOUR LINES.
	   $user['uniqueid'] 	= CSessionManager::Get(CSessionManager::STR_USER_ID);
	   $user['name'] 		= CSessionManager::Get(CSessionManager::STR_USER_NAME);
	   $user['email'] 		= CSessionManager::Get(CSessionManager::STR_EMAIL_ID);
	   $user['photourl'] 	= '';
	}

	// 4. Generate the jsConnect string.

	// This should be true unless you are testing. 
	// You can also use a hash name like md5, sha1 etc which must be the name as the connection settings in Vanilla.
	$secure = true; 
	WriteJsConnect($user, $_GET, $clientID, $secret, $secure);
?>