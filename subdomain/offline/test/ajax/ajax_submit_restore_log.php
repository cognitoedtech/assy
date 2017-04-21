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
	
	if(isset($_GET['reason']))
	{
		$reason = urldecode($_GET['reason']);
	
		if(isset($_GET['tsession_id']))
		{
			$tsession_id = urldecode($_GET['tsession_id']);
		}
	
		if(isset($_GET['time_zone']))
		{
			$time_zone = $_GET['time_zone'];
		}
	}
	
	$RetAry = array("Result" => $objTH->TestRestoreLog($reason, $tsession_id, $time_zone));
	
	echo json_encode($RetAry);
?>