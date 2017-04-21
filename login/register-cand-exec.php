<?php
	/*echo("<pre>");
	print_r($_POST);
	echo("</pre>");
	exit();*/
	
	//Start session
	include_once("../lib/session_manager.php");
	include_once("../database/config.php");
	include_once("../database/mcat_db.php");
	include_once("../lib/user_manager.php");
	include_once("../lib/utils.php");
	include_once("../lib/billing.php");
	
	$captcha_value = CSessionManager::Get(CSessionManager::INT_CAPTCH_VALUE);
        	                    
    $owner_id 			= clean($_POST['owner_id']);
    $owner_param = empty($owner_id) ? "" : "?owner=".$owner_id."&batch_id=".$_POST['batch_id'];
    $verif_code = clean($_POST['VERIF_CODE']);
    
    CSessionManager::UnsetSessVar(CSessionManager::INT_CAPTCH_VALUE);
 	if ($captcha_value != $verif_code) 
	{
   		// What happens when the CAPTCHA was entered incorrectly
		CSessionManager::SetErrorMsg("Dear User,<br/><br/> Your registration process had been failed due to incorrect captcha value, please re-fill the form.<BR/><BR/>-Team ".CConfig::SNC_SITE_NAME);
		CUtils::Redirect("register-cand.php".$owner_param);
		exit();
	} 
	else 
	{
		   // Your code here to handle a successful verification
 	}
	
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
	
	$objUM = new CUserManager();
	
	//Sanitize the POST values
	
	$plan				= clean($_POST['splan']);
	
	$batch				= json_encode(array(intval($_POST['batch_id'])));
	if($_POST['batch_id'] != CConfig::CDB_ID)
	{
		$batch			= json_encode(array(CConfig::CDB_ID, intval($_POST['batch_id'])));
	}
	
	
	$fname				= clean($_POST['fname']);
	$lname				= clean($_POST['lname']);
	$contact_no			= clean($_POST['contact']);
	$email				= clean($_POST['email']);
	$password			= $_POST['password'];
	$gender				= clean($_POST['gender']);
	$city				= clean($_POST['city']);
	$state				= clean($_POST['state']);
	$country			= clean($_POST['country']);
	$year				= clean($_POST['birthyear']);
	$day				= clean($_POST['day']);
	$month				= clean($_POST['month']);
	$question			= clean($_POST['question']);
	$security_answer	= clean($_POST['answer']);
	$dob				= sprintf("%s-%s-%s",$year,$month,$day);
	
	/*$qualification	= $_POST['qualification'];
	$area			= $_POST['area'];
	$stream			= $_POST['stream'];
	$percent		= $_POST['percent'];
	$institute		= $_POST['institute'];
	$board			= $_POST['board'];
	$passing_year	= $_POST['passing_year'];
	$qual_count 	= $_POST['qual_count'];*/
	
	if($objUM->IsUserExists($email)) 
	{
		CSessionManager::SetErrorMsg("Email-ID already in use.");
		CUtils::Redirect("register-cand.php".$owner_param);				
		exit();
	}
	else
	{
		$objUser = new CUser();
		//----------------Add User------------------------
		$objUser->SetOwnerID($owner_id);
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
		$objUser->SetSecQues(ucwords(strtolower($question)));
		$objUser->SetSecAns(ucwords(strtolower($security_answer)));

		$result = $objUM->AddUser($objUser);
		
		//Check whether the query was successful or not
		if($result != false) 
		{
			/*for ($aryIndex = 0; $aryIndex <$qual_count; $aryIndex++)
			{
				$objUM->InsertIntoUserCV($result, $qualification[$aryIndex], $area[$aryIndex],
										 $stream[$aryIndex], $percent[$aryIndex],
										 $institute[$aryIndex], $board[$aryIndex], $passing_year[$aryIndex]);
			}*/
			$objBilling = new CBilling();
			$objBilling->ApplyPlan($result, $plan);
			
			$objOwner = $objUM->GetUserById($owner_id);
			$owner_name = $objOwner->GetFirstName()." ".$objOwner->GetLastName();
			$owner_email = $objOwner->GetEmail();
			
			$objDB = new CMcatDB();
			$objMail = new CEMail(CConfig::OEI_SUPPORT, $objDB->GetPasswordFromOfficialEMail(CConfig::OEI_SUPPORT));
			$objMail->PrepAndSendNewCandRegistrationNotificationMail($owner_name, $owner_email, $fname." ".$lname, $email);
			
			CSessionManager::Logout() ;
			CUtils::Redirect("register-success.php?umail=".urlencode($email));
					
			exit();
		}
		else 
		{
			//die("Query failed: ".mysql_error());
			CSessionManager::SetErrorMsg("<BR/><BR/>Dear User,<BR/><BR/> [Insert Failed] Your registration process had been failed, please re-fill the form.<BR/><BR/>-Team ".CConfig::SNC_SITE_NAME);
			CUtils::Redirect("register-cand.php".$owner_param);
			exit();
		}
	}
	CSessionManager::Logout() ;
?>