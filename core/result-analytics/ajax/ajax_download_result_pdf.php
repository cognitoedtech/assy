<?php 
	include_once(dirname(__FILE__)."/../../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../../test/lib/create_result_pdf.php");
	include_once(dirname(__FILE__)."/../../../test/lib/tbl_result.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$time_zone = CSessionManager::Get(CSessionManager::FLOAT_TIME_ZONE);
	
	if(isset($_GET['from_free']) && $_GET['from_free'] == 1)
	{
		if(isset($_GET['name']) && isset($_GET['email']) && isset($_GET['test_pnr']) && !empty($_GET['name']) && !empty($_GET['email']) && !empty($_GET['test_pnr']))
		{
			$objCreatePDF = new CCreateResultPDF();
			
			if(isset($_GET['test_dna']) && $_GET['test_dna'] == CConfig::PRV_DETAILED)
			{
				$objCreatePDF->GenerateTestDNAPDF($_GET['test_pnr'], '', urldecode($_GET['name']), urldecode($_GET['email']), $time_zone);
			}
			else if(isset($_GET['inspect_result']) && $_GET['inspect_result'] == 1)
			{
				$objCreatePDF->GenerateResultInspectionPDF($_GET['test_pnr'], '', urldecode($_GET['name']), urldecode($_GET['email']), $time_zone);
			}
		}
	}
	else 
	{
		$objResult = new CResult();
		
		$userInfoAry = $objResult->GetUserInfoByTestPNR($_GET['test_pnr']);
		
		if(!empty($userInfoAry))
		{
			$objCreatePDF = new CCreateResultPDF();
			
			$objResultParams = $objResult->GetUnpreparedResultFromPNR($_GET['test_pnr']);
			
			$schdld_test_ary = $objResult->GetScheduledTest($objResultParams['tschd_id']);
			
			$isTestFromAssignedTestPackage  = false;
			if(!empty($schdld_test_ary))
			{
				$isTestFromAssignedTestPackage = $objResult->IsTestFromAssignedTestPackage($objResultParams['test_id'], $schdld_test_ary['scheduler_id']);
				if($isTestFromAssignedTestPackage)
				{
					$packageResultView = $objResult->GetPackageTestResultView($objResultParams['test_id'], $schdld_test_ary['scheduler_id']);
					
					if(($packageResultView & $_GET['test_dna']) == CConfig::PRV_DETAILED)
					{
						$objCreatePDF->GenerateTestDNAPDF($_GET['test_pnr'], '', $userInfoAry['firstname']." ".$userInfoAry['lastname'], $userInfoAry['email'], $time_zone);
					}
					else if(($packageResultView & $_GET['test_dna']) == CConfig::PRV_HOLISTIC)
					{
						$objCreatePDF->GenerateHolisticViewPDF($_GET['test_pnr'], '', $userInfoAry['firstname']." ".$userInfoAry['lastname'], $userInfoAry['email'], $time_zone);
					}
					else if(($packageResultView & $_GET['test_dna']) == CConfig::PRV_IQ)
					{
						$objCreatePDF->GenerateIQViewPDF($_GET['test_pnr'], '', $userInfoAry['firstname']." ".$userInfoAry['lastname'], $userInfoAry['email'], $time_zone);
					}
					else if(($packageResultView & $_GET['test_dna']) == CConfig::PRV_EQ)
					{
						$objCreatePDF->GenerateEQViewPDF($_GET['test_pnr'], '', $userInfoAry['firstname']." ".$userInfoAry['lastname'], $userInfoAry['email'], $time_zone);
					}
				}
				else 
				{
					if(isset($_GET['test_dna']) && $_GET['test_dna'] == CConfig::PRV_DETAILED)
					{
						$objCreatePDF->GenerateTestDNAPDF($_GET['test_pnr'], '', $userInfoAry['firstname']." ".$userInfoAry['lastname'], $userInfoAry['email'], $time_zone);
					}
					else if(isset($_GET['test_dna']) && $_GET['test_dna'] == CConfig::PRV_HOLISTIC)
					{
						$objCreatePDF->GenerateHolisticViewPDF($_GET['test_pnr'], '', $userInfoAry['firstname']." ".$userInfoAry['lastname'], $userInfoAry['email'], $time_zone);
					}
					else if(isset($_GET['inspect_result']) && $_GET['inspect_result'] == 1)
					{
						$objCreatePDF->GenerateResultInspectionPDF($_GET['test_pnr'], '', $userInfoAry['firstname']." ".$userInfoAry['lastname'], $userInfoAry['email'], $time_zone);
					}
				}
			}
			else 
			{
				
				if(isset($_GET['test_dna']) && $_GET['test_dna'] == CConfig::PRV_DETAILED)
				{
					$objCreatePDF->GenerateTestDNAPDF($_GET['test_pnr'], '', $userInfoAry['firstname']." ".$userInfoAry['lastname'], $userInfoAry['email'], $time_zone);
				}
				else if(isset($_GET['test_dna']) && $_GET['test_dna'] == CConfig::PRV_HOLISTIC)
				{
					$objCreatePDF->GenerateHolisticViewPDF($_GET['test_pnr'], '', $userInfoAry['firstname']." ".$userInfoAry['lastname'], $userInfoAry['email'], $time_zone);
				}
				else if(isset($_GET['inspect_result']) && $_GET['inspect_result'] == 1)
				{
					$objCreatePDF->GenerateResultInspectionPDF($_GET['test_pnr'], '', $userInfoAry['firstname']." ".$userInfoAry['lastname'], $userInfoAry['email'], $time_zone);
				}	
			}
		}	
	}
?>