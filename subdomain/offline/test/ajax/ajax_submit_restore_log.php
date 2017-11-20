<?php 
	include_once(dirname(__FILE__)."/../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../lib/utils.php");
	include_once(dirname(__FILE__)."/../lib/test_helper.php");
	
	$objTH = new CTestHelper();
	
	$parsAry = parse_url(CUtils::curPageURL());
	$qry = split("[=&]", $parsAry["query"]);
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	
	$reason	= null;
	$tsession_id = null;
	$time_zone = null;
	if($qry[0] == "reason")
	{
		$reason = urldecode($qry[1]);
		
		if($qry[2] == "tsession_id")
		{
			$tsession_id = urldecode($qry[3]);
		}
		
		if($qry[4] == "time_zone")
		{
			$time_zone = $qry[5];
		}
	}
	
	$RetAry = array("Result" => $objTH->TestRestoreLog($reason, $tsession_id, $time_zone));
	
	echo json_encode($RetAry);
?>