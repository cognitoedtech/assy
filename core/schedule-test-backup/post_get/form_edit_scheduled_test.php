<?php
	include_once(dirname(__FILE__)."/../../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/../../../database/config.php");
	include_once(dirname(__FILE__)."/../../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../../lib/billing.php");
	include_once(dirname(__FILE__)."/../../../lib/utils.php");
	include_once(dirname(__FILE__)."/../../../lib/user.php");
	include_once(dirname(__FILE__)."/../../../lib/user_manager.php");
	include_once(dirname(__FILE__)."/../../../lib/new-email.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	$user_type = CSessionManager::Get(CSessionManager::INT_USER_TYPE);
	
	$objDB = new CMcatDB();
	
	$test_id = $_POST['test_id'];
	
	$tschd_id  = $_POST['tschd_id'];
		
	$count = 0;
	
	$scheduled_test = "";
	
	if(!empty($_POST['candidate_list']))
	{
		$objUM = new CUserManager();
	
		$objUser = $objUM->GetUserById($user_id);

		$candidate_ary 	= explode(";", $_POST['candidate_list']);
		
		$numOfCandidates = count($candidate_ary)-1;
		
		$scheduled_by = $objDB->GetUserName($user_id)."(".$objDB->GetUserEmail($user_id).", ".$objDB->GetOrganizationName($objUser->GetOrganizationId()).")";
		
		$scheduled_test = $objDB->GetScheduledTest($tschd_id);
		
		$isOfflineTest = $objDB->IsValidOfflineSchedule($tschd_id, $user_id, false);
		
		$isTestFromAssignedPackage = $objDB->IsTestAssignedFromPackage($test_id, $user_id);
		
		$objMail = new CEMail(CConfig::OEI_SUPPORT, $objDB->GetPasswordFromOfficialEMail(CConfig::OEI_SUPPORT));
		
		for($index = 0; $index < count($candidate_ary); $index++)
		{
			if(!empty($candidate_ary[$index]))
			{
				$isTestSessionExists = $objDB->GetTestSession($candidate_ary[$index], $tschd_id);
				
				$isTestCompleted	 = $objDB->GetCandidateResult($candidate_ary[$index], $tschd_id);
				
				if(empty($isTestSessionExists) && empty($isTestCompleted))
				{
					$objDB->RemoveScheduledCandidate($candidate_ary[$index], $_POST['tschd_id']);
					
					$objMail->PrepAndSendEditTestScheduleMail($objDB->GetUserName($candidate_ary[$index]), $objDB->GetUserEmail($candidate_ary[$index]), $objDB->GetTestName($test_id), $tschd_id, date("F j, Y", strtotime($scheduled_test['scheduled_on'])), $scheduled_by);
					
					//CEMail::PrepAndSendEditTestScheduleMail($objDB->GetUserName($candidate_ary[$index]), $objDB->GetUserEmail($candidate_ary[$index]), $objDB->GetTestName($test_id), $tschd_id, date("F j, Y", strtotime($scheduled_test['scheduled_on'])), $scheduled_by);
					
					$count++;
				}
			}
		}
		
		if($count > 0)
		{
			$objBilling 		= new CBilling();
			$currency   		= $objBilling->GetCurrencyType($user_id);
			$ques_source		= $objBilling->GetQuesSource($test_id);
			$balanceToBeAdded	= 0;
			
			$offline_cost = 0;
			if(!empty($isOfflineTest))
			{
				$offline_cost = $objBilling->GetOfflineVersionRate($user_id);
			}
			
			$assignedPackageTestRate = 0;
			if($isTestFromAssignedPackage)
			{
				$assignedPackageTestRate = $objBilling->GetAssignedPackageTestRate($test_id, $user_id);
			}
				
			if($ques_source == "mipcat")
			{
				$rate_mipcat_ques 	= $objBilling->GetMIpCATQuesRate($user_id);
				$balanceToBeAdded	= ($rate_mipcat_ques + $offline_cost + $assignedPackageTestRate)  * $count;
			}
			else if($ques_source == "personal")
			{
				$rate_personal_ques = $objBilling->GetPersonalQuesRate($user_id);
				$balanceToBeAdded	= ($rate_personal_ques + $offline_cost + $assignedPackageTestRate) * $count;
			}
			
			$objBilling->AddProjectedBalance($user_id, $balanceToBeAdded);	
		}
	}
	$redirectURL = sprintf("../edit_scheduled_test.php?count=%d&tschd_id=%d&test_id=%d&candidates=%d&schdld_date=%s", $count, $tschd_id, $test_id, $numOfCandidates, strtotime($scheduled_test['scheduled_on']));
	 
	CUtils::Redirect($redirectURL);	
?>