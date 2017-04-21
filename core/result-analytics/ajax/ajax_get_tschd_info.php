<?php
	include_once(dirname(__FILE__)."/../../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../../database/config.php");
	include_once(dirname(__FILE__)."/../../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/../../../lib/utils.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -

	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	
	$parsAry = parse_url(CUtils::curPageURL());
	$qry = split("[=&]", $parsAry["query"]);
	
	$objDB = new CMcatDB();
	
	if($qry[0] == "tschd_ids")
	{
		/*echo("<pre>");
		print_r($objDB->GetTestSchdResultDeatils($qry[1]));
		echo("</pre>");*/
		$batch_ids = "";
		if($qry[2] == "batch_ids")
		{
			$batch_ids = $qry[3];
		}
		echo json_encode($objDB->GetTestSchdResultDeatils($qry[1], $user_id, $batch_ids));
	}
?>