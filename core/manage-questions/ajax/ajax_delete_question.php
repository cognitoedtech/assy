<?php 
	include(dirname(__FILE__)."/../../../lib/utils.php");
	include_once(dirname(__FILE__)."/../../../lib/session_manager.php");
	include(dirname(__FILE__)."/../../../database/mcat_db.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$objDB = new CMcatDB();
	
	$resp = $objDB->AJXProcessQuestionRow($_POST);
	echo json_encode($resp);
?>