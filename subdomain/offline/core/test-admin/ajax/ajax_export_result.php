<?php 
	include_once(dirname(__FILE__)."/../../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/../../../lib/utils.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	if(isset($_POST['force_kill']) || isset($_POST['end_exam']))
	{
		include_once(dirname(__FILE__)."/../../../test/lib/test_helper.php");
		
		$objDB = new CMcatDB();
		
		$remainingSessionAry =  $objDB->GetUnfinishedTestSessions();
		
		$objTH = new CTestHelper();
		
		if(isset($_POST['force_kill']) && $_POST['force_kill'] == 1)
		{
			foreach($remainingSessionAry as $remainingSession)
			{
				$objTH->TerminateTestSession($remainingSession['tsession_id']);
			}	
		}
		else if(isset($_POST['end_exam']) && $_POST['end_exam'] == 1)
		{
			foreach($remainingSessionAry as $remainingSession)
			{
				$isTestSessionExists = $objTH->IsTestPending($remainingSession['user_id'], $remainingSession['test_id'], $remainingSession['tschd_id']);
				if(!empty($isTestSessionExists))
				{
					$objTH->EndExam($remainingSession['user_id'], $remainingSession['test_id'], $remainingSession['tschd_id']);
				}
			}
			$exportedDataArray = $objDB->GetTestResults();
			
			$validResult = 0;
			if(!empty($exportedDataArray))
			{
				$validResult = 1;
			}
			echo $validResult;
		}
	}
	else
	{
		$objDB = new CMcatDB();
		
		$remainingSessionAry =  $objDB->GetUnfinishedTestSessions();
		
		$bTestStartedByAdmin = $objDB->IsTestStartedByAdmin();
		//print_r($remainingSessionAry);
		
		if(empty($remainingSessionAry) && $bTestStartedByAdmin == 1)
		{
			$active_test_name = $objDB->GetActiveTestName();
			
			$exportedDataArray = array();
			$exportedDataArray['result'] = $objDB->GetTestResults();
			
			$exportedDataArray['users'] = $objDB->GetFinishedUsersData();
			$exportedDataArray['user_cv'] = $objDB->GetFinishedUserCVData();
			
			
			
			unset($objDB);
			
			if(!empty($exportedDataArray['result']))
			{
				include_once(dirname(__FILE__)."/../../../import/import_new_test.php");
				
				$file = tempnam("tmp", "zip");
				$zip = new ZipArchive();
				$zip->open($file, ZipArchive::OVERWRITE);
				
				// Stuff with content
				$zip->addFromString($active_test_name."_results.json", json_encode($exportedDataArray));
				
				// Close and send to users
				$zip->close();
				header('Content-Type: application/zip');
				header('Content-Length: ' . filesize($file));
				header('Content-Disposition: attachment; filename="'.$active_test_name.'_results.zip"');
				readfile($file);
				unlink($file);
				
				$objImportNewTest = new CImportNewTest();
				$objImportNewTest->CleanExistingTest(false);
				unset($objImportNewTest);
			}
		}
	}
?>