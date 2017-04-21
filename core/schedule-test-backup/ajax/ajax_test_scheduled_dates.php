<?php
	include_once(dirname(__FILE__)."/../../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/../../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../../lib/utils.php");
	
	$parsAry = parse_url(CUtils::curPageURL());
	$qry = split("[=&]", $parsAry["query"]);
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
		
	if($qry[0] == "test_id")
	{
		$objDB = new CMcatDB();
	
		if($qry[2] == "tschd_id")
		{
			$objDB->PrepareScheduledTestDateCombo($qry[1], $user_id, $qry[3]);
		}
	}
?>