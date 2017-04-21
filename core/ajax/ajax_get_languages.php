<?php
	include_once(dirname(__FILE__)."/../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../database/config.php");
	include_once(dirname(__FILE__)."/../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/../../lib/utils.php");

	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	
	$parsAry = parse_url(CUtils::curPageURL());
	$qry = split("[=&]", $parsAry["query"]);
	
	$objDB = new CMcatDB();
	
	if($qry[0] == "mipcat")
	{
		echo json_encode($objDB->GetDistLangFromQues());
	}
	else if($qry[0] == "user_id")
	{
		echo json_encode($objDB->GetDistLangFromQues($qry[1]));
	}
?>