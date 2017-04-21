<?php 
	include_once(dirname(__FILE__)."/../../lib/free_user_manager.php");
	include_once(dirname(__FILE__)."/../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../lib/new_email.php");
	include_once(dirname(__FILE__)."/../lib/create_result_pdf.php");
	include_once(dirname(__FILE__)."/../../database/mcat_db.php");
	
	$response_array = array();
	if(isset($_POST['new_free_user']) && $_POST['new_free_user'] == 1)
	{
		$captcha_value = CSessionManager::Get(CSessionManager::INT_CAPTCH_VALUE);
		
		if($captcha_value == trim($_POST['captch_value']))
		{
			$objFreeUM = new CFreeUserManager();
			$objFreeUser = $objFreeUM->GetFreeUserByEmail(trim($_POST['email']));
			$free_user_id = $objFreeUser->GetFreeUserId();
			if(!empty($free_user_id))
			{
				$org_id 	  = $objFreeUM->GetOrgIdByTestId(trim($_POST['test_id']));
				$objFreeUM->AddFreeUserTest($free_user_id, trim($_POST['test_id']), $_POST['test_pnr'], $org_id);
				
				$candidate_name = $objFreeUser->GetName();
				$candidate_email = $objFreeUser->GetEmail();
				
				$file_name_prefix = uniqid();
				$objCreatePDF = new CCreateResultPDF();
				$objCreatePDF->GenerateTestDNAPDF($_POST['test_pnr'], $file_name_prefix."_test_dna.pdf", $candidate_name, $candidate_email, trim($_POST['time_zone']));
				$objCreatePDF->GenerateResultInspectionPDF($_POST['test_pnr'], $file_name_prefix."_inspect_result.pdf", $candidate_name, $candidate_email, trim($_POST['time_zone']));
				
				$objDB = new CMcatDB();
				$objMail = new CEMail(CConfig::OEI_FREE, $objDB->GetPasswordFromOfficialEMail(CConfig::OEI_FREE));
				$objMail->PrepAndSendFreeUserResult(trim($_POST['email']),$candidate_name, $file_name_prefix."_test_dna.pdf", $file_name_prefix."_inspect_result.pdf");
				
				$response_array['success'] = "<span style='color:green;'>Detailed result has been sent on provided EMail.</span>";
				
				unlink($file_name_prefix."_test_dna.pdf");
				unlink($file_name_prefix."_inspect_result.pdf");
			}
			else 
			{	
				$objFreeUser->SetEmail(trim($_POST['email']));
				$objFreeUser->SetPhone(trim($_POST['phone']));
				$objFreeUser->SetName(trim($_POST['name']));
				$objFreeUser->SetCity(trim($_POST['city']));
				
				$free_user_id = $objFreeUM->AddFreeUser($objFreeUser);
				
				$org_id 	  = $objFreeUM->GetOrgIdByTestId(trim($_POST['test_id']));
				$objFreeUM->AddFreeUserTest($free_user_id, trim($_POST['test_id']), $_POST['test_pnr'], $org_id);
				
				$file_name_prefix = uniqid();
				
				$objCreatePDF = new CCreateResultPDF();
				$objCreatePDF->GenerateTestDNAPDF($_POST['test_pnr'], $file_name_prefix."_test_dna.pdf", trim($_POST['name']), trim($_POST['email']), trim($_POST['time_zone']));
				$objCreatePDF->GenerateResultInspectionPDF($_POST['test_pnr'], $file_name_prefix."_inspect_result.pdf", trim($_POST['name']), trim($_POST['email']), trim($_POST['time_zone']));
				
				$objDB = new CMcatDB();
				$objMail = new CEMail(CConfig::OEI_FREE, $objDB->GetPasswordFromOfficialEMail(CConfig::OEI_FREE));
				$objMail->PrepAndSendFreeUserResult(trim($_POST['email']), trim($_POST['name']), $file_name_prefix."_test_dna.pdf", $file_name_prefix."_inspect_result.pdf");
				
				$response_array['success'] = "<span style='color:green;'>Detailed result has been sent on provided EMail.</span>";
				
				unlink($file_name_prefix."_test_dna.pdf");
				unlink($file_name_prefix."_inspect_result.pdf");
			}
		}
		else 
		{
			$response_array['error'] = "<span style='color:red;'>Code enterd was incorrect!</span>";
		}
	}
	else if(isset($_POST['existing_free_user']) && $_POST['existing_free_user'] == 1)
	{
		$objFreeUM = new CFreeUserManager();
		$objFreeUser = $objFreeUM->GetFreeUserByEmail(trim($_POST['email']));
		
		$free_user_id = $objFreeUser->GetFreeUserId();
		if(!empty($free_user_id))
		{	
			$org_id 	  = $objFreeUM->GetOrgIdByTestId(trim($_POST['test_id']));
			$objFreeUM->AddFreeUserTest($free_user_id, trim($_POST['test_id']), $_POST['test_pnr'], $org_id);
			
			$candidate_name = $objFreeUser->GetName();
			$candidate_email = $objFreeUser->GetEmail();
			
			$file_name_prefix = uniqid();
			$objCreatePDF = new CCreateResultPDF();
			$objCreatePDF->GenerateTestDNAPDF($_POST['test_pnr'], $file_name_prefix."_test_dna.pdf", $candidate_name, $candidate_email, trim($_POST['time_zone']));
			$objCreatePDF->GenerateResultInspectionPDF($_POST['test_pnr'], $file_name_prefix."_inspect_result.pdf", $candidate_name, $candidate_email, trim($_POST['time_zone']));
			
			$objDB = new CMcatDB();
			$objMail = new CEMail(CConfig::OEI_FREE, $objDB->GetPasswordFromOfficialEMail(CConfig::OEI_FREE));
			$objMail->PrepAndSendFreeUserResult(trim($_POST['email']),$candidate_name, $file_name_prefix."_test_dna.pdf", $file_name_prefix."_inspect_result.pdf");
			
			$response_array['success'] = "<span style='color:green;'>Detailed result has been sent on provided EMail.</span>";
			
			unlink($file_name_prefix."_test_dna.pdf");
			unlink($file_name_prefix."_inspect_result.pdf");
		}
		else 
		{
			$response_array['error'] = "<span style='color:red;'>EMail does not exist. Please provide the whole information!</span>";
		}
	}
	CSessionManager::UnsetSessVar(CSessionManager::INT_CAPTCH_VALUE);
	echo(json_encode($response_array));
?>