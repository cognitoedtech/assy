<?php
	//Start session
	include_once("../../../lib/session_manager.php");
	include_once("../../../database/config.php");
	include_once("../../../database/mcat_db.php");
	include_once("../../../lib/user_manager.php");
	include_once("../../../lib/utils.php");
	include_once("../../../lib/new-email.php");
	
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
	$org_name	= clean($_POST['ORG']);
	$org_size	= clean($_POST['ORGSIZE']);
	$org_url    = clean($_POST['ORGURL']);
	$address	= clean($_POST['ADDRESS']);
	$phone		= clean($_POST['PHONE']);
	$fname		= clean($_POST['FNAME']);
	$lname		= clean($_POST['LNAME']);
	$email		= clean($_POST['EMAIL']);
	//$password	= $_POST['PASSWORD'];
	//$cpassword	= $_POST['CPASSWORD'];
	$gender		= clean($_POST['GENDER']);
	$city		= clean($_POST['CITY']);
	$state		= clean($_POST['STATE']);
	$country	= clean($_POST['COUNTRY']);
	$year		= clean($_POST['BIRTHYEAR']);
	$day		= clean($_POST['DAY']);
	$month		= clean($_POST['MONTH']);
	//$question	= clean($_POST['QUESTION']);	
	//$security_answer	= clean($_POST['ANSWER']);
	$dob	= sprintf("%s-%s-%s",$year,$month,$day);
	
	$payment_cycle	= $_POST['PAYMENTCYCLE'];
	$pref_pmnt_mode	= clean($_POST['PREFPMNTMODE']);
	
	$pan_no = NULL;
	$acc_name = NULL;
	$acc_num = NULL;
	$acc_type = NULL;
	$bank_name = NULL;
	$bank_ifsc = NULL;
	if($pref_pmnt_mode == 0)
	{
		$pay_favour	= clean($_POST['PAYFAVOUR']);
		if($pay_favour == "org")
		{
			$acc_name = $org_name;
		}
		else
		{
			$acc_name = $fname." ".$lname;
		}
	}
	else
	{
		$pan_no		= clean($_POST['PAN']);
		$acc_name	= clean($_POST['ACCNAME']);
		$acc_num	= clean($_POST['ACCNUM']);
		$acc_type	= $_POST['ACCTYPE'];
		$bank_name	= clean($_POST['BANKNAME']);
		$bank_ifsc	= clean($_POST['BANKIFSC']);
	}
	
	$showcast_public	= 1;

	//Check for duplicate Mgoos-login ID
	$objUM = new CUserManager();
	
	if($objUM->IsUserExists($email)) 
	{
		CSessionManager::SetErrorMsg("Email-ID already in use.");
		CUtils::Redirect("../register-ba.php");				
		exit();
	}
	else
	{
		$objUser = new CUser();
		//----------------Add User------------------------
		$objUser->SetUserType(CConfig::UT_BUSINESS_ASSOCIATE);
		$objUser->SetOrganizationId($objUM->AddOrganization($org_name, $org_size, $org_url, $showcast_public));
		$objUser->SetAddress($address);
		$objUser->SetPANNo($pan_no);
		$objUser->SetContactNo($phone);
		$objUser->SetLoginName(uniqid());
		$objUser->SetFirstName(ucwords(strtolower($fname)));
		$objUser->SetLastName(ucwords(strtolower($lname)));
		$objUser->SetPassword($objUser->GetLoginName());
		$objUser->SetEmail(strtolower($email)); //only E-mail in lower case
		$objUser->SetGender($gender);
		$objUser->SetCity(ucwords(strtolower($city)));
		$objUser->SetState(ucwords(strtolower($state)));
		$objUser->SetCountry(ucwords(strtolower($country)));
		$objUser->SetDOB($dob);
		//$objUser->SetSecQues(ucwords(strtolower($question)));
		//$objUser->SetSecAns(ucwords(strtolower($security_answer)));

		$result = $objUM->AddUser($objUser);
		
		//Check whether the query was successful or not
		if($result != false) 
		{
			/*CSessionManager::Logout() ;
			CUtils::Redirect("register-success.php?umail=".urlencode($email)); */
			$result_id = $objUM->ActivateAccount(md5($email));
			if($result_id)
			{
				//$bResult = true;
				//$objUser = $objUM->GetUserByID($result_id);
				$objUM->AddBusinessAssociate($result_id,$pref_pmnt_mode,$payment_cycle,$acc_name,$acc_num,$acc_type,$bank_name,$bank_ifsc);

				// Send welcome mail.
				$subject = "Your business associate account is activated" ;
				$body = "Dear ".$objUser->GetFirstName()." ".$objUser->GetLastName().",<br/><br/> ".CConfig::SNC_SITE_NAME." administrator has activated your business associate account. <br /><br /> Please login through your registered email, your password is : ".$objUser->GetLoginName();
				$result_email=$objUser->GetEmail();
				//CEMail::Send($result_email, CConfig::OEI_SUPPORT, $subject, $body);
				
				$objDB = new CMcatDB();
				$objMail = new CEMail(CConfig::OEI_SUPPORT, $objDB->GetPasswordFromOfficialEMail(CConfig::OEI_SUPPORT));
				$objMail->Send($result_email, $subject, $body);
				
				CUtils::Redirect("../register-ba.php?ba=1");
				exit();
			}
		}
		else 
		{
			//die("Query failed: ".mysql_error());
			CSessionManager::SetErrorMsg("Dear User,<BR/><BR/> Your registration process had been failed, please re-fill the form.<BR/><BR/>-Team ".CConfig::SNC_SITE_NAME);
			CUtils::Redirect("../register-ba.php");
			exit();
		}
	}
	CSessionManager::Logout() ;
?>