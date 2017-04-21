<?php
	include_once(dirname(__FILE__)."/../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../database/config.php");
	include_once(dirname(__FILE__)."/../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/../../lib/utils.php");

	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	
	//$parsAry = parse_url(CUtils::curPageURL());
	//$qry = split("[=&]", $parsAry["query"]);
	
	$objDB = new CMcatDB();
	
	if( isset($_POST["mipcat"]) )
	{
		if($_POST["mipcat"] == 0)
		{
			$objDB->PrepareSubjectCombo($user_id, $_POST["tag_id"], $_POST["lang"], $_POST['mcq_type']);
		}
		else 
		{
			$objDB->PrepareSubjectCombo(null, $_POST["tag_id"], $_POST["lang"], $_POST['mcq_type']);
		}
	}
	else if ( isset($_GET["term"]) )
	{
		echo json_encode($objDB->GetSubjects($user_id, null, null, true, $_GET["term"]));
	}
?>