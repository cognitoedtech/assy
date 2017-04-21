<?php
	include_once(dirname(__FILE__)."/../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../database/config.php");
	include_once(dirname(__FILE__)."/../../lib/utils.php");
	include_once(dirname(__FILE__)."/../../test/lib/test_helper.php");
	
	$objTH = new CTestHelper();
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	
	$parsAry = parse_url(CUtils::curPageURL());
	$qry = split("[=&]", $parsAry["query"]);
	
	if($qry[0] == "test_id")
	{
		$TestDetailsHTML = $objTH->PrepareTestDetailsHTML($qry[1]);
		
		echo($TestDetailsHTML);
	}
?>