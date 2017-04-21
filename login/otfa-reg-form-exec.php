<?php 
	
	/*echo("<pre>");
	print_r($_POST);
	echo("</pre>");
	exit();*/

	include_once(dirname(__FILE__)."/../lib/include_js_css.php");
	include_once("../lib/session_manager.php");
	include_once("../database/config.php");
	include_once("../database/mcat_db.php");
	include_once("../lib/user_manager.php");
	include_once("../lib/site_config.php") ;
	include_once("../lib/utils.php") ;
	
	$captcha_value = CSessionManager::Get(CSessionManager::INT_CAPTCH_VALUE);
	$verif_code = trim($_POST['VERIF_CODE']);
	
	CSessionManager::UnsetSessVar(CSessionManager::INT_CAPTCH_VALUE);
	if ($captcha_value != $verif_code)
	{
		// What happens when the CAPTCHA was entered incorrectly
		CSessionManager::SetErrorMsg("Dear User,<br/><br/> Your registration process had been failed due to incorrect captcha value, please re-fill the form.<BR/><BR/>-Team ".CConfig::SNC_SITE_NAME);
		header('Location: '.$_SERVER['HTTP_REFERER']);
		exit();
	}
	else
	{
		// Your code here to handle a successful verification
	}
	
	$tschd_id = null;
	if(isset($_POST['tschd_id']))
	{
		$tpin				= trim($_POST['tpin']);
		$fname				= trim($_POST['fname']);
		$lname				= trim($_POST['lname']);
		$contact_no			= trim($_POST['contact']);
		$email				= trim($_POST['email']);
		$password			= $_POST['password'];
		$gender				= trim($_POST['gender']);
		$city				= trim($_POST['city']);
		$state				= trim($_POST['state']);
		$country			= trim($_POST['country']);
		$year				= trim($_POST['birthyear']);
		$day				= trim($_POST['day']);
		$month				= trim($_POST['month']);
		$question			= trim($_POST['question']);
		$security_answer	= trim($_POST['answer']);
		$dob				= sprintf("%s-%s-%s",$year,$month,$day);
		
		$qualification	= $_POST['qualification'];
		$area			= $_POST['area'];
		$stream			= $_POST['stream'];
		$percent		= $_POST['percent'];
		$institute		= $_POST['institute'];
		$board			= $_POST['board'];
		$passing_year	= $_POST['passing_year'];
		$qual_count 	= $_POST['qual_count'];
		
		$tschd_id = trim($_POST['tschd_id']);
		
		$objUM = new CUserManager();
		$objDB = new CMcatDB();
		
		$scheduled_test_ary = $objDB->GetScheduledTest($tschd_id);
		$user_id = $objUM->GetFieldValueByEmail($email, CUser::FIELD_USER_ID);
		$user_type = $objUM->GetFieldValueByEmail($email, CUser::FIELD_USER_TYPE);
		
		if($user_type == CConfig::UT_CORPORATE)
		{
			CSessionManager::SetErrorMsg("Dear User,<br/><br/> Your registration process had been failed because this email id is already registered as a corporate user, please re-fill the form.<BR/><BR/>-Team ".CConfig::SNC_SITE_NAME);
			header('Location: '.$_SERVER['HTTP_REFERER']);
			exit();
		}
		else if($user_type == CConfig::UT_COORDINATOR)
		{
			CSessionManager::SetErrorMsg("Dear User,<br/><br/> Your registration process had been failed because this email id is already registered as a coordinator of some organization, please re-fill the form.<BR/><BR/>-Team ".CConfig::SNC_SITE_NAME);
			header('Location: '.$_SERVER['HTTP_REFERER']);
			exit();
		}
		
		if($scheduled_test_ary["schedule_type"] == CConfig::TST_OTFA_EMAIL)
		{
			$pos = strpos($scheduled_test_ary['user_list'], $user_id);
			
			if($pos !== false)
			{
				$user_data = array();
				$user_data[CUser::FIELD_FIRST_NAME] = $fname;
				$user_data[CUser::FIELD_LAST_NAME] = $lname;
				$user_data[CUser::FIELD_PASSWORD] = $password;
				
				if(isset($_POST['contact']))
				{
					$user_data[CUser::FIELD_CONTACT_NO] = $contact_no;
				}
				
				if(isset($_POST['gender']))
				{
					$user_data[CUser::FIELD_GENDER] = $gender;
				}
				
				if(isset($_POST['city']))
				{
					$user_data[CUser::FIELD_CITY] = $city;
				}
				
				if(isset($_POST['state']))
				{
					$user_data[CUser::FIELD_STATE] = $state;
				}
				
				if(isset($_POST['country']))
				{
					$user_data[CUser::FIELD_COUNTRY] = $country;
				}
				
				if(isset($_POST['birthyear']))
				{
					$user_data[CUser::FIELD_DOB] = $dob;
				}
				
				$objUM->UpdateUser($user_id, $user_data);
				
				if(isset($_POST['qual_count']))
				{
					for ($aryIndex = 0; $aryIndex < $qual_count; $aryIndex++)
					{
						$qualification_details = $objUM->IsQualificationAlreadyExist($user_id, $qualification[$aryIndex]);
						
						if(!empty($qualification_details))
						{
							$objUM->UpdateQualification($user_id, $qualification[$aryIndex], $area[$aryIndex],
								$stream[$aryIndex], $percent[$aryIndex],
								$institute[$aryIndex], $board[$aryIndex], $passing_year[$aryIndex]);
						}
						else 
						{
							$objUM->InsertIntoUserCV($user_id, $qualification[$aryIndex], $area[$aryIndex],
									$stream[$aryIndex], $percent[$aryIndex],
									$institute[$aryIndex], $board[$aryIndex], $passing_year[$aryIndex]);
						}
					}
				}
				$objUM->ActivateAccount(md5($email));
				session_regenerate_id() ;
				$objUser = $objUM->GetUserByEmail($email) ;
				CSessionManager::Set(CSessionManager::STR_USER_ID, $objUser->GetUserID()) ;
				CSessionManager::Set(CSessionManager::STR_EMAIL_ID, $email);
				CSessionManager::Set(CSessionManager::BOOL_LOGIN, true) ;
				CSessionManager::Set(CSessionManager::INT_USER_TYPE, $objUser->GetUserType()) ;
				CSessionManager::Set(CSessionManager::STR_USER_NAME, $objUser->GetFirstName()." ".$objUser->GetLastName()) ;
				CSessionManager::Set(CSessionManager::STR_LOGIN_NAME, $objUser->GetLoginName());
				CSessionManager::Set(CSessionManager::INT_TEST_SCHEDULE_ID, $tschd_id);
				$objUM->SetOnline($objUser->GetUserID());//online
				session_write_close() ;
				printf("<script language='javascript'> window.parent.location = '../core/dashboard.php?noisrev=%d' ;</script>", time());
			}
			else 
			{
				CSessionManager::SetErrorMsg("Dear User,<br/><br/> Your registration process had been failed due to invalid email id. This email id is not relevant to the test scheduled, please re-fill the form.<BR/><BR/>-Team ".CConfig::SNC_SITE_NAME);
				header('Location: '.$_SERVER['HTTP_REFERER']);
				exit();
			}
		}
		else if($scheduled_test_ary["schedule_type"] == CConfig::TST_OTFA_TPIN)
		{
			$pos = strpos($scheduled_test_ary['user_list'], $tpin);
			
			if($pos !== false)
			{
				$OTFA_from_data = $objDB->GetOTFAFormByTestSchdId($tschd_id);
				
				if(!empty($OTFA_from_data['tpin_list']))
				{
					$tpin_list_ary = json_decode($OTFA_from_data['tpin_list'], true);
					
					if(strtolower($tpin_list_ary[$tpin]["fname"]) != strtolower($fname) || strtolower($tpin_list_ary[$tpin]["lname"]) != strtolower($lname) || (isset($_POST['birthyear']) && $tpin_list_ary[$tpin]["dob"] != $_POST['birthyear'].$_POST['month'].$_POST['day']))
					{
						CSessionManager::SetErrorMsg("Dear User,<br/><br/> Your registration process had been failed due to invalid TPIN. This TPIN is not relevant to the information associated with it, please re-fill the form or contact your test administrator.<BR/><BR/>-Team ".CConfig::SNC_SITE_NAME);
						header('Location: '.$_SERVER['HTTP_REFERER']);
						exit();
					}
				}
				
				if($user_id != -1)
				{
					$user_data = array();
					$user_data[CUser::FIELD_FIRST_NAME] = $fname;
					$user_data[CUser::FIELD_LAST_NAME] = $lname;
					$user_data[CUser::FIELD_PASSWORD] = $password;
					
					if(isset($_POST['contact']))
					{
						$user_data[CUser::FIELD_CONTACT_NO] = $contact_no;
					}
					
					if(isset($_POST['gender']))
					{
						$user_data[CUser::FIELD_GENDER] = $gender;
					}
					
					if(isset($_POST['city']))
					{
						$user_data[CUser::FIELD_CITY] = $city;
					}
					
					if(isset($_POST['state']))
					{
						$user_data[CUser::FIELD_STATE] = $state;
					}
					
					if(isset($_POST['country']))
					{
						$user_data[CUser::FIELD_COUNTRY] = $country;
					}
					
					if(isset($_POST['birthyear']))
					{
						$user_data[CUser::FIELD_DOB] = $dob;
					}
					
					$objUM->UpdateUser($user_id, $user_data);
					
					if(isset($_POST['qual_count']))
					{
						for ($aryIndex = 0; $aryIndex < $qual_count; $aryIndex++)
						{
							$qualification_details = $objUM->IsQualificationAlreadyExist($user_id, $qualification[$aryIndex]);
						
							if(!empty($qualification_details))
							{
								$objUM->UpdateQualification($user_id, $qualification[$aryIndex], $area[$aryIndex],
										$stream[$aryIndex], $percent[$aryIndex],
										$institute[$aryIndex], $board[$aryIndex], $passing_year[$aryIndex]);
							}
							else
							{
								$objUM->InsertIntoUserCV($user_id, $qualification[$aryIndex], $area[$aryIndex],
								$stream[$aryIndex], $percent[$aryIndex],
								$institute[$aryIndex], $board[$aryIndex], $passing_year[$aryIndex]);
							}
						}
					}
					
					$status = "";
					
					$isEmailExists = $objDB->IsEmailExists($email, $scheduled_test_ary["scheduler_id"], $status, $OTFA_from_data['batch_id']);
										 
					if($status == 0)
					{
						$cand_batch_id_ary = $objDB->GetCandidateBatches($user_id);
					
						$batch_array = $objDB->GetBatches($scheduled_test_ary["scheduler_id"]);
							 
						$batch_id_array = array_keys($batch_array);
					
						$common_ary = array_intersect($batch_id_array, $cand_batch_id_ary);
					
						if(count($common_ary) > 0)
						{
							foreach($common_ary as $common_batch_id)
							{
								$objDB->ChangeCandidateBatch($common_batch_id, $OTFA_from_data['batch_id'], $scheduled_test_ary["scheduler_id"], "'".$user_id."'");
							}
						}
					}
					$objDB->UpdateTPINInTestScheduleUserList($tschd_id, $tpin, $user_id);
				}
				else 
				{
					$batch				= json_encode(array(intval($OTFA_from_data['batch_id'])));
					if($OTFA_from_data['batch_id'] != CConfig::CDB_ID)
					{
						$batch			= json_encode(array(CConfig::CDB_ID, intval($OTFA_from_data['batch_id'])));
					}
					
					$objUser = new CUser();
					//----------------Add User------------------------
					$objUser->SetOwnerID($scheduled_test_ary["scheduler_id"]);
					$objUser->SetBatch($batch);
					$objUser->SetUserType(CConfig::UT_INDIVIDAL);
					$objUser->SetOrganizationId(null);
					$objUser->SetLoginName(uniqid());
					$objUser->SetFirstName(ucwords(strtolower($fname)));
					$objUser->SetLastName(ucwords(strtolower($lname)));
					$objUser->SetPassword($password);
					$objUser->SetContactNo($contact_no);
					$objUser->SetEmail(strtolower($email)); //only E-mail in lower case
					$objUser->SetGender($gender);
					$objUser->SetCity(ucwords(strtolower($city)));
					$objUser->SetState(ucwords(strtolower($state)));
					$objUser->SetCountry(ucwords(strtolower($country)));
					$objUser->SetDOB($dob);
					
					$result = $objUM->AddUser($objUser);
					
					if(isset($_POST['qual_count']))
					{
						for ($aryIndex = 0; $aryIndex < $qual_count; $aryIndex++)
						{
							$objUM->InsertIntoUserCV($result, $qualification[$aryIndex], $area[$aryIndex],
								$stream[$aryIndex], $percent[$aryIndex],
								$institute[$aryIndex], $board[$aryIndex], $passing_year[$aryIndex]);
						}
						
					}
					$objDB->UpdateTPINInTestScheduleUserList($tschd_id, $tpin, $result);
				}
				$objUM->ActivateAccount(md5($email));
				session_regenerate_id() ;
				$objUserUpdated = $objUM->GetUserByEmail($email) ;
				CSessionManager::Set(CSessionManager::STR_USER_ID, $objUserUpdated->GetUserID()) ;
				CSessionManager::Set(CSessionManager::STR_EMAIL_ID, $email);
				CSessionManager::Set(CSessionManager::BOOL_LOGIN, true) ;
				CSessionManager::Set(CSessionManager::INT_USER_TYPE, $objUserUpdated->GetUserType()) ;
				CSessionManager::Set(CSessionManager::STR_USER_NAME, $objUserUpdated->GetFirstName()." ".$objUserUpdated->GetLastName()) ;
				CSessionManager::Set(CSessionManager::STR_LOGIN_NAME, $objUserUpdated->GetLoginName());
				CSessionManager::Set(CSessionManager::INT_TEST_SCHEDULE_ID, $tschd_id);
				$objUM->SetOnline($objUserUpdated->GetUserID());//online
				session_write_close() ;
				printf("<script language='javascript'> window.parent.location = '../core/dashboard.php?noisrev=%d' ;</script>", time());
			}
			else 
			{
				CSessionManager::SetErrorMsg("Dear User,<br/><br/> Your registration process had been failed due to invalid TPIN. This TPIN is not relevant to the test scheduled, please re-fill the form.<BR/><BR/>-Team ".CConfig::SNC_SITE_NAME);
				header('Location: '.$_SERVER['HTTP_REFERER']);
				exit();
			}
		}
	}
?>