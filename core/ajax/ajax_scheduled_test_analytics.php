<?php 
	include_once("../../lib/session_manager.php");
	include_once("../../database/mcat_db.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	
	$objDB = new CMcatDB();
	
	if(isset($_POST['from_date']) && isset($_POST['to_date']))
	{
		echo json_encode($objDB->GetScheduledTestAnalytics($_POST['from_date'], $_POST['to_date'], $_POST['test_id'], $user_id));
	}
?>