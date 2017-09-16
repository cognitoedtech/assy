<?php 
	include_once(dirname(__FILE__)."/../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../database/config.php");
	include_once(dirname(__FILE__)."/../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/../../lib/utils.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	 $user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);	
	 $objDB = new CMcatDB();	
	 echo json_encode($objDB->FetchUsersTest($user_id));

?>