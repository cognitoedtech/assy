<?php
	include_once("../lib/session_manager.php") ;
	include_once("../lib/utils.php") ;
	include_once("../lib/site_config.php") ;
	include_once("../database/config.php") ;
	include_once("../lib/user_manager.php") ;
	
	$login_name = CSessionManager::Get(CSessionManager::STR_LOGIN_NAME);
	
	// Set User offline.
	$objQM = new CUserManager() ;
	$objQM->ResetOnline(CSessionManager::Get(CSessionManager::STR_USER_ID)) ;
	
	// Logout.
	CSessionManager::Logout() ;
	
	// Redirect to Home Page.
	CUtils::Redirect("../") ; //.$login_name) ;
?>