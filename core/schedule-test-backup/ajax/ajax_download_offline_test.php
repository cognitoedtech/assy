<?php 

	include_once(dirname(__FILE__)."/../../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/../../../export/export_offline_test.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	
	if(isset($_GET['schd_id']))
	{
		$schd_id = $_GET['schd_id'];
		
		$objDB = new CMcatDB();
		
		$test_id = $objDB->IsValidOfflineSchedule($schd_id, $user_id);
		
		unset($objDB);
		
		if(!empty($test_id))
		{
			$objExportOfflineTest = new CExportOfflineTest();

			$objExportOfflineTest->ExportData($test_id, $schd_id, $user_id);
		}
	}
?>