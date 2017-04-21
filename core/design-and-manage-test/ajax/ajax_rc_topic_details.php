<?php
	include_once(dirname(__FILE__)."/../../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../../database/config.php");
	include_once(dirname(__FILE__)."/../../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/../../../lib/utils.php");

	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	
	$parsAry = parse_url(CUtils::curPageURL());
	$qry = split("[=&]", $parsAry["query"]);
	
	$objDB = new CMcatDB();
	
	/*if($qry[0] == "topic_id")
	{
		//echo("<p>".$qry[1]." <br/><br/></p>");
		echo $objDB->PrepareRCDirectionsHTML($qry[1], urldecode($qry[3]));
	}*/
	
	if(isset($_POST['para_id']))
	{
		echo $objDB->PrepareRCDirectionsHTML($_POST['para_id'], $_POST['qtype']);
	}
?>