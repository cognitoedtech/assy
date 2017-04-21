<?php
	include_once(dirname(__FILE__)."/../../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../../lib/utils.php");
	include_once(dirname(__FILE__)."/../../../database/mcat_db.php");
	
	$owner_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	$owner_type = CSessionManager::Get(CSessionManager::INT_USER_TYPE);
	
	$objDB = new CMcatDB();
	
	echo json_encode($objDB->AJXProcessCandidateRow($_POST, $owner_id, $owner_type, $_POST['batch_id']));
?>