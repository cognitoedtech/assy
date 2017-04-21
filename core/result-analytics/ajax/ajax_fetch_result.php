<?php
	include_once(dirname(__FILE__)."/../../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../../database/config.php");
	include_once(dirname(__FILE__)."/../../../lib/utils.php");
	include_once(dirname(__FILE__)."/../../../test/lib/tbl_result.php");
	
	$objTR = new CResult();
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	$nUserType = CSessionManager::Get(CSessionManager::INT_USER_TYPE);
	
	$parsAry = parse_url(CUtils::curPageURL());
	$qry = split("[=&]", $parsAry["query"]);
	
	if($qry[0] == "test_pnr")
	{
		$ResultAry = array();
		if($_POST['resultView'] == CConfig::PRV_DETAILED)
		{
			$ResultAry = $objTR->GetResultFromPNR($qry[1]);
		}
		else if($_POST['resultView'] == CConfig::PRV_HOLISTIC)
		{
			$ResultParams = $objTR->GetUnpreparedResultFromPNR($qry[1]);
			
			$ResultAry = $objTR->GetHolisticMarks($ResultParams['ques_map'], $ResultParams['test_id'], $ResultParams['time_taken']);
		}
		else if($_POST['resultView'] == CConfig::PRV_IQ)
		{
			$ResultAry = $objTR->GetIQResult($qry[1], $user_id, $nUserType);
		}
		else if($_POST['resultView'] == CConfig::PRV_EQ)
		{
			$ResultAry = $objTR->GetEQResult($qry[1]);
		}
		
		echo(json_encode($ResultAry));
	}
?>