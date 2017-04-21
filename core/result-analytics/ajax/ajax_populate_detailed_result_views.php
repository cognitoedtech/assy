<?php 
	include_once(dirname(__FILE__)."/../../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../../database/config.php");
	include_once(dirname(__FILE__)."/../../../lib/utils.php");
	include_once(dirname(__FILE__)."/../../../test/lib/tbl_result.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	
	if(isset($_POST['test_pnr']))
	{
		$objTR = new CResult();
		
		$resultAry = $objTR->GetUnpreparedResultFromPNR($_POST['test_pnr']);
		
		$scheduled_test_ary = $objTR->GetScheduledTest($resultAry['tschd_id']);
		
		$isTestFromAssignedPackages = false;
		if(!empty($scheduled_test_ary))
		{
			$isTestFromAssignedPackages = $objTR->IsTestFromAssignedTestPackage($resultAry['test_id'], $scheduled_test_ary['scheduler_id']);
		}
		
		if($isTestFromAssignedPackages)
		{
			$result_view = $objTR->GetPackageTestResultView($resultAry['test_id'], $scheduled_test_ary['scheduler_id']);
			
			if((CConfig::PRV_DETAILED & $result_view) == CConfig::PRV_DETAILED)
			{
				printf("<option value='%s'>Detailed</option>", CConfig::PRV_DETAILED);
			}
			
			if((CConfig::PRV_HOLISTIC & $result_view) == CConfig::PRV_HOLISTIC)
			{
				printf("<option value='%s'>Holistic</option>", CConfig::PRV_HOLISTIC);
			}
			
			if((CConfig::PRV_IQ & $result_view) == CConfig::PRV_IQ)
			{
				printf("<option value='%s'>IQ</option>", CConfig::PRV_IQ);
			}
			
			if((CConfig::PRV_EQ & $result_view) == CConfig::PRV_EQ)
			{
				printf("<option value='%s'>EQ</option>", CConfig::PRV_EQ);
			}
		}
		else 
		{
			printf("<option value='%s'>Holistic</option>", CConfig::PRV_HOLISTIC);
			printf("<option value='%s'>Detailed</option>", CConfig::PRV_DETAILED);
		}
	}
?>