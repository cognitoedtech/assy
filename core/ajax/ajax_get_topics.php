<?php
	include_once(dirname(__FILE__)."/../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../database/config.php");
	include_once(dirname(__FILE__)."/../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/../../lib/utils.php");

	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	
	//$parsAry = parse_url(CUtils::curPageURL());
	//$qry = split("[=&]", $parsAry["query"]);
	
	$objDB = new CMcatDB();
	
	if( isset($_POST["sub_id"]) )
	{
		if(isset($_POST["mipcat"]) )
		{
			if($_POST["mipcat"] == 1)
			{
				//echo("Test 1");
				$objDB->PrepareTopicCombo(null, $_POST["sub_id"], $_POST["tag_id"], $_POST['lang'], $_POST["mcq_type"]);
			}
			else 
			{
				//echo("Test 2");
				$objDB->PrepareTopicCombo($user_id, $_POST["sub_id"], $_POST["tag_id"], $_POST['lang'], $_POST["mcq_type"], -1, $_POST["reconcile"]);
			}
		}
	}
	else if ( $_GET["autocomp"] )
	{
		echo json_encode($objDB->GetTopics(null, $_GET["autocomp"], null, null, $_GET["term"]));
	}
?>