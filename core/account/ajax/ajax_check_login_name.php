<?php
	include_once(dirname(__FILE__)."/../../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../../database/config.php");
	include_once(dirname(__FILE__)."/../../../lib/utils.php");
	include_once(dirname(__FILE__)."/../../../database/mcat_db.php");
	
	$objDB = new CMcatDB();
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	
	$parsAry = parse_url(CUtils::curPageURL());
	$qry = split("[=&]", $parsAry["query"]);
	
	if($qry[0] == "login_name")
	{
		$ResultAry = $objDB->CheckLoginName(urldecode($qry[1]), $user_id);
		
		echo(json_encode($ResultAry));
	}
?>