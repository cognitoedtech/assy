<?php 
	include_once(dirname(__FILE__)."/../../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../../database/mcat_db.php");

	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	
	if(isset($_POST['test_id']))
	{
		$objDB = new CMcatDB();
		
		
		$objDB->UpdateTestStaticQuestions($user_id, $_POST['test_id']);
	}
?>