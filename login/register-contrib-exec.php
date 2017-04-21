<?php
	/*echo("<pre>");
	print_r($_POST);
	echo("</pre>");
	exit();*/
	
	//Start session
	include_once("../lib/session_manager.php");
	include_once("../database/config.php");
	include_once("../lib/user_manager.php");
	include_once("../lib/utils.php");
	include_once('../3rd_party/recaptcha/recaptchalib.php');
	
 	$resp = recaptcha_check_answer (CConfig::CK_PRIVATE,
    	                            $_SERVER["REMOTE_ADDR"],
        	                        $_POST["recaptcha_challenge_field"],
            	                    $_POST["recaptcha_response_field"]);
 	if (!$resp->is_valid) 
	{
   		// What happens when the CAPTCHA was entered incorrectly
		CSessionManager::SetErrorMsg("Dear User,<br/><br/> Your registration process has been failed due to incorrect or empty captcha value, please re-fill the form.<BR/><BR/>-Team ".CConfig::SNC_SITE_NAME);
		CUtils::Redirect("register-contrib.php");
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
	
	//Sanitize the POST values
	$pan_no		= clean($_POST['PAN']);
	$address	= clean($_POST['ADDRESS']);
	$phone		= clean($_POST['PHONE']);
	$fname		= clean($_POST['FNAME']);
	$lname		= clean($_POST['LNAME']);
	$email		= clean($_POST['EMAIL']);
	$password	= $_POST['PASSWORD'];
	$cpassword	= $_POST['CPASSWORD'];
	$gender		= clean($_POST['GENDER']);
	$city		= clean($_POST['CITY']);
	$state		= clean($_POST['STATE']);
	$country	= clean($_POST['COUNTRY']);
	$year		= clean($_POST['BIRTHYEAR']);
	$day		= clean($_POST['DAY']);
	$month		= clean($_POST['MONTH']);
	$question	= clean($_POST['QUESTION']);	
	$security_answer	= clean($_POST['ANSWER']);
	$dob	= sprintf("%s-%s-%s",$year,$month,$day);

	//Check for duplicate Mgoos-login ID
	$objUM = new CUserManager();
	
	if($objUM->IsUserExists($email)) 
	{
		CSessionManager::SetErrorMsg("Email-ID already in use.");
		CUtils::Redirect("register-contrib.php");				
		exit();
	}
	else
	{
		$objUser = new CUser();
		//----------------Add User------------------------
		$objUser->SetUserType(CConfig::UT_CONTRIBUTOR);
		$objUser->SetAddress($address);
		$objUser->SetPANNo($pan_no);
		$objUser->SetContactNo($phone);
		$objUser->SetLoginName(uniqid());
		$objUser->SetFirstName(ucwords(strtolower($fname)));
		$objUser->SetLastName(ucwords(strtolower($lname)));
		$objUser->SetPassword($password);
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
			CSessionManager::Logout() ;
			CUtils::Redirect("register-success.php?umail=".urlencode($email));
					
			exit();
		}
		else 
		{
			//die("Query failed: ".mysql_error());
			CSessionManager::SetErrorMsg("Dear User,<BR/><BR/> Your registration process has been failed, please re-fill the form.<BR/><BR/>-Team ".CConfig::SNC_SITE_NAME);
			CUtils::Redirect("register-contrib.php");
			exit();
		}
	}
	CSessionManager::Logout() ;
?>