<?php 
	include_once(dirname(__FILE__)."/../../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../../database/config.php");
	include_once(dirname(__FILE__)."/../../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/../../../lib/utils.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	
	if(isset($_POST['from_batch']))
	{
		$objDB = new CMcatDB();
		
		$objDB->ChangeCandidateBatch($_POST['from_batch'], $_POST['to_batch'], $user_id, $_POST['candidate_list']);
		
		CUtils::Redirect("../change_cand_batch.php?save_success=1");
	}
?>