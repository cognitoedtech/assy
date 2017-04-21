<?php
	include_once(dirname(__FILE__)."/../../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../../database/config.php");
	include_once(dirname(__FILE__)."/../../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/../../../lib/utils.php");
	include_once(dirname(__FILE__)."/../../../test/lib/test_helper.php");

	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	
	$parsAry = parse_url(CUtils::curPageURL());
	$qry = split("[=&]", $parsAry["query"]);
	
	$objTH = new CTestHelper();
	
	if($qry[0] == "test_id")
	{
		echo json_encode( array("ques_source" => $objTH->GetQuesSource($qry[1])) );
	}
	else 
	{
		echo json_encode( array("ques_source" => "null") );
	}
?>