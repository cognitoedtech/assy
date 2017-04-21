<?php
	include_once("../../database/mcat_db.php");
	include_once("../../lib/session_manager.php");
	include_once("../../lib/utils.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -

	$user_id   = CSessionManager::Get(CSessionManager::STR_USER_ID);
	
	$parsAry = parse_url(CUtils::curPageURL());
	$qry = split("[=&]", $parsAry["query"]);
	
	$objDB = new CMcatDB();
	if ($qry[0] == "term")
	{
		echo json_encode($objDB->GetQuestionTags($qry[1]));
	}
	else if(!empty($_POST["ques_source"]))
	{
		//echo "hello";
		printf("<option value=''>--Select Set--</option>");
		if($_POST["ques_source"] == "personal")
		{
			$objDB->PopulateTagList($user_id);
		}
		else
		{
			$objDB->PopulateTagList(NULL);
		}
	}
?>