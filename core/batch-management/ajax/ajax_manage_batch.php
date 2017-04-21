<?php 
	include_once(dirname(__FILE__)."/../../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/../../../lib/session_manager.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$objDB = new CMcatDB();
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	
	if($_POST['action'] == "create")
	{
		$new_tr_id = $objDB->InsertBatch($_POST['data']['batch_name'], $user_id, $_POST['data']['description']);
		echo json_encode(array($new_tr_id));
	}
	else if($_POST['action'] == "edit" && $_POST['id'] != CConfig::CDB_ID)
	{
		$objDB->UpdateBatch($_POST['id'], $_POST['data']['batch_name'], $_POST['data']['description']);
		echo json_encode(array($_POST));
	}
	else if($_POST['action'] == "remove" && $_POST['data'][0] != CConfig::CDB_ID)
	{
		$objDB->DeleteBatch($_POST['data'][0], $user_id);
		echo json_encode(array($_POST));
	}
?>