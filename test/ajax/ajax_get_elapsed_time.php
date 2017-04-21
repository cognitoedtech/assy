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
	
	if($qry[0] == "test_id")
	{
		$test_id = $qry[1];
		if($qry[2] == "tschd_id")
		{
			$tschd_id = $qry[3];
		}
	}
	
	$RetAry = array("TestCurTime" => $objTH->GetElapsedTime($user_id, $test_id, $tschd_id));
	
	echo json_encode($RetAry);
?>