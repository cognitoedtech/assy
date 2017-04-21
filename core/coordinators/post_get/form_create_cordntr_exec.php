<?php
	//Start session
	include_once("../../../lib/session_manager.php");
	include_once("../../../database/config.php");
	include_once("../../../lib/user_manager.php");
	include_once("../../../lib/utils.php");
	include_once("../../../database/mcat_db.php");
	include_once("../../../lib/billing.php");
    include_once("../../../lib/new-email.php");
	
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$objDB = new CMcatDB();
	$objBilling = new CBilling();
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	 
	$currency = $objBilling->GetCurrencyType($user_id);
	$owner_type   = $objDB->GetUserType($user_id); // coordinator owner user_id
	
	//Function to sanitize values received from the form. Prevents SQL injection
	function clean($str) 
	{
		/*if(!get_magic_quotes_gpc()) 
		{
			$str = trim(mysql_real_escape_string($str));
		}
		else*/ 
		{
			$str = trim($str);
		}

		return $str;
	}
	
	
	//Sanitize the POST values
	//$pan_no			= clean($_POST['PAN']);
	$amount				= clean($_POST['RECHARGE_AMOUNT']);	
	$address			= clean($_POST['ADDRESS']);
	$phone				= clean($_POST['PHONE']);
	$fname				= clean($_POST['FNAME']);
	$lname				= clean($_POST['LNAME']);
	$department			= clean($_POST['DEPARTMENT']);
	$email				= clean($_POST['EMAIL']);
	$gender				= clean($_POST['GENDER']);
	$city				= clean($_POST['CITY']);
	$state				= clean($_POST['STATE']);
	$country			= clean($_POST['COUNTRY']);
	$year				= clean($_POST['BIRTHYEAR']);
	$day				= clean($_POST['DAY']);
	$month				= clean($_POST['MONTH']);
	$permissions_array	= $_POST['PERMISSIONS'];
	$permitted_all		= true;
	$dob	= sprintf("%s-%s-%s",$year,$month,$day);

	//print_r($permissions_array);
	//Check for duplicate Mgoos-login ID
	$objUM = new CUserManager();
	
	if($objUM->IsUserExists($email)) 
	{
		CSessionManager::SetErrorMsg("Email-ID already in use.");
		CUtils::Redirect("../create.php");				
		exit();
	}
	else
	{
		$objUser = new CUser();
		//----------------Add User------------------------
		$objUser->SetUserType(CConfig::UT_COORDINATOR);
		$objUser->SetAddress($address);
		//$objUser->SetPANNo($pan_no);
		$objUser->SetOrganizationId($objUM->GetUserById($user_id)->GetOrganizationId());//me
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
		$objUM->AddOwnerId($result,$user_id);// $result is coordinator user id in user table for type 7
		$objUM->AddCoordinator($result,$department);//add values in coordinator table
		/*if(count($permissions_array) < 10)
		{
			$objUM->AddCoordinatorPermissions($permissions_array,$result);
		}*/
		if($permitted_all == true)
		{
			$objUM->UpdateCoordinatorPermissions($permissions_array,$result,$permitted_all);
		}
				
		//Check whether the query was successful or not
		if($result != false) 
		{
			/*CSessionManager::Logout() ;
			CUtils::Redirect("register-success.php?umail=".urlencode($email)); */
			//$objBilling = new CBilling();
			$objBilling->ApplyPlan($result, $owner_type, $currency);
			$objBilling->AddBalance($result, $amount); // add amount in coordinotor account
			$objBilling->AddProjectedBalance($result, $amount);
			$objBilling->SubBalance($user_id, $amount);    //substract amount from owner acc
			$objBilling->SubProjectedBalance($user_id, $amount);
			
			if($amount != 0)
			{
				$objBilling->AddCoordinatorBillingHistory($result, $amount, CConfig::CTT_RECHARGE);
			}
			
			$result_id = $objUM->ActivateAccount(md5($email));
			if($result_id)
			{
				//$bResult = true;
				//$objUser = $objUM->GetUserByID($result_id);

				// Send welcome mail.
				$coordinatorOwner  = $objUM->GetUserById($user_id);
			
			    $owner_org_id	   = $coordinatorOwner->GetOrganizationId();
				$owner_org         =  $objDB->GetOrganizationName($owner_org_id);
				$title = '';
				if($objUser->GetGender() != 0)
				{
						$title = "Mr.";
				}
				else
				{
					$title = "Mrs.";
				}
				$subject = "Your Coordinator account is activated" ;
				$body = "Dear  ".$fname."&nbsp;".$lname." , <br/>".$title."&nbsp;".$coordinatorOwner->GetFirstName()."(".$coordinatorOwner->GetEmail()."," .$owner_org.")"." has registered you as his coordinator at http://www.".strtolower(CConfig::SNC_SITE_NAME).".com.<br/> Your login Details are as : <br/> Your Email-Id :"."&nbsp;".$objUser->GetEmail()."<br/> Your Password : ".$objUser->GetLoginName();
				
				$result_email=$objUser->GetEmail();
				$objMail = new CEMail(CConfig::OEI_SUPPORT, $objDB->GetPasswordFromOfficialEMail(CConfig::OEI_SUPPORT));
				$objMail->Send($result_email, $subject, $body);
				//CEMail::Send($result_email, CConfig::OEI_SUPPORT, $subject, $body);
				CUtils::Redirect("../create.php?coordinator=1");
				exit();
			}
		}
		else 
		{
			//die("Query failed: ".mysql_error());
			CSessionManager::SetErrorMsg("Dear User,<BR/><BR/> Your registration process had been failed, please re-fill the form.<BR/><BR/>-Team ".CConfig::SNC_SITE_NAME);
			CUtils::Redirect("../create.php");
			exit();
		}
	}
	CSessionManager::Logout() ;
?>