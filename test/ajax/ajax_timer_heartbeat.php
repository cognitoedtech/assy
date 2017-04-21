<?php
	include_once(dirname(__FILE__)."/../../lib/session_manager.php");
	include_once("../lib/tbl_test_session.php");
	
	$user_id  		= $_POST['user_id'];
	$test_id  		= $_POST['test_id'];
	$timer    		= $_POST['timer'];
	$tschd_id 		= $_POST['tschd_id'];
	$langofchoice	= $_POST['langofchoice'];
	
	CSessionManager::Set(CSessionManager::BOOL_SEL_TEST_LANG, $langofchoice);
	
	$objTS = new CTestSession(null); // null means connect to DB

	//echo($timer);
	$RetAry = array("Result" => $objTS->SetTimeElapsed($user_id, $test_id, $tschd_id, $timer), "Timer" => $timer, "TSchdID" => $tschd_id);
	
	echo json_encode($RetAry);
?>