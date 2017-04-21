<?php
	include_once(dirname(__FILE__)."/../../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../../database/config.php");
	include_once(dirname(__FILE__)."/../../../lib/utils.php");
	include_once(dirname(__FILE__)."/../../../test/lib/tbl_result.php");
	
	$objTR = new CResult();
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	$user_type = CSessionManager::Get(CSessionManager::INT_USER_TYPE);
	
	$parsAry = parse_url(CUtils::curPageURL());
	$qry = split("[=&]", $parsAry["query"]);
	
	if($qry[0] == "test_id")
	{
		$ResultAry = $objTR->GetCompletedTestCandidates($user_id, $user_type, $qry[1], $qry[3], $qry[5]);
		
		/*printf("<option value=''>-- Choose Candidate --</option>");
		foreach ($ResultAry as $userId => $cand_name)
		{
			$idAry = explode(":", $userId);
			printf("<option value='%s'>%s</option>", $idAry[0], $cand_name['result']);
			printf("<option value='%s'>%s</option>", $idAry[0], $cand_name['batch']);
		}*/
		echo json_encode($ResultAry);
	}
?>