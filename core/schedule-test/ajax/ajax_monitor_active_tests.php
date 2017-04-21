<?php 
	include_once(dirname(__FILE__)."/../../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../../test/lib/test_helper.php");
	include_once(dirname(__FILE__)."/../../../lib/utils.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$tsession_id = $_POST['tsession_id'];
	$end_exam    = $_POST['end_exam'];
	$user_id 	 = $_POST['user_id'];
	$tschd_id 	 = $_POST['tschd_id'];
	$test_id	 = $_POST['test_id'];
	
	$objTH = new CTestHelper();
	if(empty($end_exam))
	{
		$objTH->TerminateTestSession($tsession_id);
	}
	else 
	{
		$isTestSessionExists = $objTH->IsTestPending($user_id, $test_id, $tschd_id);
		if(!empty($isTestSessionExists))
		{
			$objTH->EndExam($user_id, $test_id, $tschd_id);
		}
	}
?>