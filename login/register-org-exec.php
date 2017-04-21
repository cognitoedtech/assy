<?php
	/*echo("<pre>");
	print_r($_POST);
	echo("</pre>");
	exit();*/
	
	//Start session
	include_once("../lib/session_manager.php");
	include_once("../lib/new-email.php");
	include_once("../database/mcat_db.php");
	include_once("../lib/user_manager.php");
	include_once("../lib/utils.php");
	include_once("../lib/billing.php");
	
	$captcha_value = CSessionManager::Get(CSessionManager::INT_CAPTCH_VALUE);
	$verif_code = clean($_POST['VERIF_CODE']);
	
	$plan_ary = array(CConfig::SPT_BASIC=>"basic", CConfig::SPT_PROFESSIONAL=>"professional", CConfig::SPT_ENTERPRISE=>"enterprise", CConfig::SPT_ENTERPRISE=>"ppu");
		
	$plan = CConfig::UT_INSTITUTE;
	
	$param = "plan=".$plan_ary[$_POST['PLAN_TYPE']];
 	
	CSessionManager::UnsetSessVar(CSessionManager::INT_CAPTCH_VALUE);
	if ($captcha_value != $verif_code) 
	{
   		// What happens when the CAPTCHA was entered incorrectly
		CSessionManager::SetErrorMsg("Dear User,<br/><br/> Your registration process had been failed due to incorrect captcha value, please re-fill the form.<BR/><BR/>-Team ".CConfig::SNC_SITE_NAME);
		CUtils::Redirect("register-org.php?".$param);
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
	$currency		= "INR";
	$buss_assoc_id	= "";
	$org_name		= clean($_POST['ORG']);
	$org_size		= "";
	$org_url    	= "";
	$org_type    	= clean($_POST['ORG_TYPE']);;
	$fname			= clean($_POST['FNAME']);
	$lname			= clean($_POST['LNAME']);
	$email			= clean($_POST['EMAIL']);
	$password		= $_POST['PASSWORD'];
	$cpassword		= $_POST['CPASSWORD'];
	$gender			= 1;
	$address		= "";
	$city			= "";
	$state			= "";
	$country		= "";
	$year			= "";
	$day			= "";
	$month			= "";
	$question		= clean($_POST['QUESTION']);	
	$security_answer	= clean($_POST['ANSWER']);
	$phone			= clean($_POST['PHONE']);
	$dob			= sprintf("");
	//$dob			= sprintf("%s-%s-%s",$year,$month,$day);
	$showcast_public	= 0;
	$app_plan 		= $_POST['PLAN_TYPE'];
	
	$plan_rate		= CConfig::SPR_BASIC_INR;
	
	if($app_plan == CConfig::SPT_PROFESSIONAL)
	{
		$plan_rate = CConfig::SPR_PROFESSIONAL_INR;
	}
	else if($app_plan == CConfig::SPT_ENTERPRISE)
	{
		$plan_rate = CConfig::SPR_ENTERPRISE_INR;
	}
	else if($app_plan == CConfig::SPT_PPU)
	{
		$plan_rate = CConfig::SPR_PPU_INR;
	}
	
	$usage_type = $_POST["USAGE"];
	$payment_type = $_POST["PAY_TYPE"];

	//Check for duplicate Mgoos-login ID
	$objUM = new CUserManager();
	
	if($objUM->IsUserExists($email)) 
	{
		CSessionManager::SetErrorMsg("Email-ID already in use.");
		CUtils::Redirect("register-org.php?".$param);				
		exit();
	}
	else
	{
		$final_org_type = $org_type;
		if($org_type == CConfig::$ORG_TYPE_ARY[CConfig::OT_OTHER])
		{
			$final_org_type = clean($_POST['OTHER_ORG']);
		}
		
		$objUser = new CUser();
		//----------------Add User------------------------
		$objUser->SetUserType($plan); // $plan denotes user type.
		
		$objUser->SetOrganizationId($objUM->AddOrganization($org_name, $org_type, $org_size, $org_url, $showcast_public));
		$objUser->SetBusinessAssociateId($buss_assoc_id);
		$objUser->SetLoginName(uniqid());
		$objUser->SetFirstName(ucwords(strtolower($fname)));
		$objUser->SetLastName(ucwords(strtolower($lname)));
		$objUser->SetPassword($password);
		$objUser->SetEmail(strtolower($email)); //only E-mail in lower case
		$objUser->SetGender($gender);
		$objUser->SetAddress($address);
		$objUser->SetCity(ucwords(strtolower($city)));
		$objUser->SetState(ucwords(strtolower($state)));
		$objUser->SetCountry(ucwords(strtolower($country)));
		$objUser->SetDOB($dob);
		$objUser->SetSecQues(ucwords(strtolower($question)));
		$objUser->SetSecAns(ucwords(strtolower($security_answer)));
		$objUser->SetContactNo($phone);

		$result = $objUM->AddUser($objUser);
		
		//Check whether the query was successful or not
		if($result != false) 
		{
			$objBilling = new CBilling();
			$objBilling->ApplyPlan($result, $plan, $currency, $app_plan, $plan_rate, $usage_type, $payment_type);
			
			$plan_ary = array(CConfig::SPT_BASIC=>"Proprietary SaaS", CConfig::SPT_PROFESSIONAL=>"Hybrid SaaS", CConfig::SPT_ENTERPRISE=>"Enterprise SaaS", CConfig::SPT_PPU=>"Pay per Use SaaS");
			
			// Table Header
			$form_details = "<table border='1' rules='all'>";
			
			// Name
			$form_details .= "<tr>";
			$form_details .= "<td><b>Name:</b></td>";
			$form_details .= "<td>".$fname." ".$lname."</td>";
			$form_details .= "</tr>";
			
			// Email
			$form_details .= "<tr>";
			$form_details .= "<td><b>Email:</b></td>";
			$form_details .= "<td>".$email."</td>";
			$form_details .= "</tr>";
			
			// Contact #
			$form_details .= "<tr>";
			$form_details .= "<td><b>Contact #:</b></td>";
			$form_details .= "<td>".$phone."</td>";
			$form_details .= "</tr>";
			
			// Organization Name
			$form_details .= "<tr>";
			$form_details .= "<td><b>Organization Name:</b></td>";
			$form_details .= "<td>".$org_name."</td>";
			$form_details .= "</tr>";
			
			// Organization Type
			$form_details .= "<tr>";
			$form_details .= "<td><b>Organization Type:</b></td>";
			$form_details .= "<td>".$final_org_type."</td>";
			$form_details .= "</tr>";
			
			// Usage
			$form_details .= "<tr>";
			$form_details .= "<td><b>Subscribed Plan:</b></td>";
			$form_details .= "<td>".$plan_ary[$app_plan]."</td>";
			$form_details .= "</tr>";
			
			// Request IP Address
			$form_details .= "<tr>";
			$form_details .= "<td><b>IP Address:</b></td>";
			$form_details .= "<td>".$_SERVER['REMOTE_ADDR']."</td>";
			$form_details .= "</tr>";
			
			// Request URL
			$form_details .= "<tr>";
			$form_details .= "<td><b>Registration URL:</b></td>";
			$form_details .= "<td>".$_SERVER['HTTP_REFERER']."</td>";
			$form_details .= "</tr>";
			
			$form_details .= "</table>";
			
			$objDB = new CMcatDB();
			$objMail = new CEMail(CConfig::OEI_SUPPORT, $objDB->GetPasswordFromOfficialEMail(CConfig::OEI_SUPPORT));
			$objMail->PrepAndSendNewOrgRegistrationNotificationMail($email, $form_details);
			
			CSessionManager::Logout() ;
			CUtils::Redirect("register-success.php?umail=".urlencode($email));
					
			exit();
		}
		else 
		{
			//die("Query failed: ".mysql_error());
			CSessionManager::SetErrorMsg("Dear User,<BR/><BR/> [Insert Failed] Your registration process had been failed, please re-fill the form.<BR/><BR/>-Team ".CConfig::SNC_SITE_NAME);
			CUtils::Redirect("register-org.php?".$param);
			exit();
		}
	}
	//CSessionManager::Logout() ;
?>