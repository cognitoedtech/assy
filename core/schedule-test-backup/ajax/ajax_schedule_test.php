<?php
	/*echo("<pre>");	
	print_r($_POST);
	echo("</pre>");
	exit(0);*/
	
	//Start session
	include_once(dirname(__FILE__)."/../../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../../database/config.php");
	include_once(dirname(__FILE__)."/../../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/../../../lib/user_manager.php");
	include_once(dirname(__FILE__)."/../../../lib/utils.php");
	include_once(dirname(__FILE__)."/../../../lib/billing.php");
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	
	//Function to sanitize values received from the form. Prevents SQL injection
	function clean($str) 
	{
		/*if(!get_magic_quotes_gpc()) 
		{
			$str = trim(mysql_real_escape_string($str));
		}
		else */
		{
			$str = trim($str);
		}

		return $str;
	}
	
	//$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	
	$objDB = new CMcatDB();
	
	// Sanitize the POST values
	$test_id 		= $_POST['test_id'];
	$scheduled_on 	= $_POST['scheduled_on'];
	$hours		 	= $_POST['hours'];
	$minutes	 	= $_POST['minutes'];
	$candidate_list = $_POST['candidate_list'];
	$pnr_list		= $_POST['pnr_list'];
	$time_zone		= $_POST['select_time_zone'];
	$cost			= $_POST['cost'];
	
	$test_name = "";
	
	if(isset($_POST['schedule_offline']) && $_POST['schedule_offline'] == CConfig::TST_OFFLINE)
	{
		$objBilling = new CBilling();
		
		$ques_source = $objBilling->GetQuesSource($test_id);
		
		$isTestFromAssignedPackage = $objDB->IsTestAssignedFromPackage($test_id, $user_id);
		
		$offlineVersionRate = $objBilling->GetOfflineVersionRate($user_id);
		
		$totalCandidates = count(explode(";", $candidate_list)) - 1;
		
		$assignedPackageTestRate = 0;
		if($isTestFromAssignedPackage)
		{
			$assignedPackageTestRate = $objBilling->GetAssignedPackageTestRate($test_id, $user_id);
		}
		
		if($ques_source == "personal" || $isTestFromAssignedPackage)
		{
			$test_name = $objDB->InsertOfflineTestSchedule($test_id, $user_id, $candidate_list);
			
			$objBilling->SubProjectedBalance($user_id, ($cost + ($assignedPackageTestRate * $totalCandidates)+ ($offlineVersionRate * $totalCandidates)));
		}
	}
	else if(!empty($candidate_list))
	{
		$test_name = $objDB->InsertIntoTestSchedule($test_id, $user_id, $scheduled_on, $hours, $minutes, $candidate_list, $time_zone);
		
		$objBilling = new CBilling();
		
		$assignedPackageTestRate = 0;
		if($isTestFromAssignedPackage)
		{
			$assignedPackageTestRate = $objBilling->GetAssignedPackageTestRate($test_id, $user_id);
		}
		
		$totalCandidates = count(explode(";", $candidate_list)) - 1;
		
		$objBilling->SubProjectedBalance($user_id, ($cost + ($assignedPackageTestRate * $totalCandidates)));
	
		$objDB->EmailTestScheduleNotification($user_id, $test_name, $candidate_list, $scheduled_on, $hours, $minutes, $time_zone);
	}
	
 	CUtils::Redirect("../schedule_new_test.php?test_name=".urlencode($test_name));
?>