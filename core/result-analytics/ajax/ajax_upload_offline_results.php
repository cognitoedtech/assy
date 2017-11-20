<?php 
	include_once(dirname(__FILE__)."/../../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../../lib/billing.php");
	include_once(dirname(__FILE__)."/../../../test/lib/tbl_result.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	$nUserType = CSessionManager::Get(CSessionManager::INT_USER_TYPE);
	$time_zone = CSessionManager::Get(CSessionManager::FLOAT_TIME_ZONE);
	
	$objTR = new CResult();
	
	if($_FILES['zip']['size'] > 0 && isset($_POST['test_date']))
	{
		$zip_file = $_FILES['zip'];
	
		$zip = new ZipArchive();
	
		$zipFileTmp = $zip_file['tmp_name'];
	
		$actualResultToBeInserted = array();
		$alreadyExistResult = 0;
		$newResultInserted = 0;
		if ($zip->open($zipFileTmp) === TRUE) {
				
			$resultAry = json_decode($zip->getFromIndex(0), true);
			
			
			$dtZone = new DateTimeZone($objTR->tzOffsetToName($time_zone));
			$date = new DateTime($_POST['test_date'], $dtZone);
			$scheduleDate = $date->format('Y-m-d H:i:s');
			$date->setTimezone(new DateTimeZone('UTC'));
			$completedDate = $date->format('Y-m-d H:i:s');
			$testScheduleId = "";
			$testId = 0;
			
			$resultOnly = $resultAry["result"];
			//echo "<pre>"; print_r($resultAry); echo "</pre>";
			foreach($resultOnly as $result)
			{
				$isResultExist = $objTR->IsResultAlreadyExist($result['user_id'], $result['test_id'], $result['tschd_id']);
				
				if($testId == 0)
				{
					$testId = $result['test_id'];
				}
				
				if($isResultExist)
				{
					$alreadyExistResult++;
				}
				else 
				{
					if(trim($testScheduleId) =="")
					{
						$testScheduleId = $result['tschd_id'];
					}
					$value_string = sprintf("('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')", $result['test_pnr'], $result['tschd_id'], $result['user_id'], $result['test_id'], mysql_real_escape_string($result['ques_map']), $result['marks'], mysql_real_escape_string($result['section_marks']), $result['time_taken'], $result['visibility'], $completedDate, mysql_real_escape_string($result['attempt_history']), $result['paid']);
					//echo $value_string. "<br/>";
					//echo "<pre>"; print_r($result); echo "</pre>";
					array_push($actualResultToBeInserted, $value_string);
					$newResultInserted++;
				}
			}
			if(!empty($actualResultToBeInserted))
			{
				$objTR->InsertOfflineResult($actualResultToBeInserted, $testScheduleId, $scheduleDate, $time_zone);
				
				$objBilling = new CBilling();
				
				$isTestFromAssignedPackage = $objBilling->IsTestAssignedFromPackage($testId, $user_id);
				
				$assignedPackageTestRate = 0;
				if($isTestFromAssignedPackage)
				{
					$assignedPackageTestRate = $objBilling->GetAssignedPackageTestRate($test_id, $user_id);
				}
				
				$offlineVersionRate = $objBilling->GetOfflineVersionRate($user_id);
				$rate	 	= $objBilling->GetPersonalQuesRate($user_id);
				$objBilling->SubBalance($user_id, (($rate * $newResultInserted) + ($assignedPackageTestRate * $newResultInserted) + ($offlineVersionRate * $newResultInserted)));
			}
			$zip->close();
			echo "<h3>Total Results: ".($newResultInserted+$alreadyExistResult)."</h3>";
			echo "<h3>Inserted Results : ".$newResultInserted."</h3>";
			echo "<h3>Duplicate Results: ".$alreadyExistResult."</h3>";
		}
	}
?>