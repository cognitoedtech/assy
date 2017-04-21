<?php 

	include_once(dirname(__FILE__)."/../../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../../test/lib/tbl_result.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	$nUserType = CSessionManager::Get(CSessionManager::INT_USER_TYPE);
	
	$objTR = new CResult();
	
	$time_zone = $_GET['time_zone'];
	if(!empty($time_zone))
	{
		$ResultAry = $objTR->PopulateBriefResultList($user_id, $nUserType, $time_zone);
		echo json_encode($ResultAry);
	}
?>