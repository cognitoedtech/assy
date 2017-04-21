<?php 
	include_once (dirname ( __FILE__ ) . "/../../lib/session_manager.php");
	include_once (dirname ( __FILE__ ) . "/../../lib/site_config.php");
	include_once (dirname ( __FILE__ ) . "/../../database/mcat_db.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire ();
	// - - - - - - - - - - - - - - - - -
	
	$user_id = CSessionManager::Get ( CSessionManager::STR_USER_ID );
	
	$user_type = CSessionManager::Get ( CSessionManager::INT_USER_TYPE );
	
	if($user_type == CConfig::UT_INDIVIDAL)
	{
		$objDB = new CMcatDB ();
		
		$objDB->PopultateScheduledTest($user_id);
	}

?>