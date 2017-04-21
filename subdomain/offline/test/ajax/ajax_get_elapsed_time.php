<?php
	include_once(dirname(__FILE__)."/../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../lib/utils.php");
	include_once(dirname(__FILE__)."/../lib/test_helper.php");
	
	$objTH = new CTestHelper();
	
	$parsAry = parse_url(CUtils::curPageURL());
	$qry = split("[=&]", $parsAry["query"]);
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	
	$test_id 	= null;
	$tschd_id 	= null;
	
	if(isset($_GET['test_id']))
	{
		$test_id = $_GET['test_id'];
		if(isset($_GET['tschd_id']))
		{
			$tschd_id = $_GET['tschd_id'];
		}
	}
	
	$RetAry = array("TestCurTime" => $objTH->GetElapsedTime($user_id, $test_id, $tschd_id));
	
	echo json_encode($RetAry);
?>