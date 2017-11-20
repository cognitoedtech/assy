<?php
	include_once(dirname(__FILE__)."/../../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../../lib/utils.php");
	include_once(dirname(__FILE__)."/../../../import/import_new_test.php");
	include_once(dirname(__FILE__)."/../../../database/mcat_db.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire ();
	// - - - - - - - - - - - - - - - - -
	
	$email = CSessionManager::Get(CSessionManager::STR_EMAIL_ID);
	
	$zip_file = NULL;
	$test_name = "";
	
	if(isset($_POST['upload_start_test_choice']) && $_POST['upload_start_test_choice'] == 1)
	{
		$objDB = new CMcatDB();
		
		$test_name = $_POST['active_test'];
		
		$bTestStartedByAdmin = $objDB->IsTestStartedByAdmin();
		
		$val = 0;
		if($bTestStartedByAdmin == 0)
		{
			$objDB->StartCurrentActiveTest();
			$val = 1;
		}
		else 
		{
			$objDB->StopCurrentActiveTest();
		}
		
		CUtils::Redirect("../manage_test.php?test_started=".$val."&test_name=".urlencode($test_name));
	}
	else 
	{
		if($_FILES['zip']['size'] > 0)
		{
			$zip_file = $_FILES['zip'];
		
			$zip = new ZipArchive();
		
			$zipFileTmp = $zip_file['tmp_name'];
			//echo "Opening File....";
				
			if ($zip->open($zipFileTmp) === TRUE) {
					
				$objImportNewTest = new CImportNewTest();
				//echo "Uploading...";
				
				$test_name = $objImportNewTest->UploadNewTest(json_decode($zip->getFromIndex(0), true), $email);
				
				if(empty($test_name))
				{
					CSessionManager::Logout();
				}
					
				$zip->close();
			}
		}
		
		CUtils::Redirect("../manage_test.php?test_uploaded=1&test_name=".urlencode($test_name));
	}

?>