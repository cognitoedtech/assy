<?php
	include_once("../lib/session_manager.php") ;
	include_once("../database/config.php") ;
	include_once("../lib/utils.php") ;
	include_once("../lib/site_config.php") ;
	include_once("../lib/user_manager.php");
	
	$email 			= strtolower($_POST['email']) ;
	$pass 			= $_POST['password'] ;
	$login_name 	= $_POST['login_name'] ;
	$redirect_url 	= $_POST['redirect_url'] ;
	$time_zone      = $_POST['time_zone'];
	
	if(!empty($login_name))
	{
		CSessionManager::Set(CSessionManager::STR_LOGIN_NAME, $login_name);
	}
	
	if(!empty($redirect_url))
	{
		CSessionManager::Set(CSessionManager::STR_REDIRECT_URL, $redirect_url);
	}
	
	if(!empty($time_zone))
	{
		CSessionManager::Set(CSessionManager::FLOAT_TIME_ZONE, $time_zone);
	}
		
	if(isset($_POST['googlelogin']))
	{
		echo "<script> top.location.href='".CSiteConfig::ROOT_URL."/login/dope_openid/login.php?from=1'</script>";
	}
	else
	{
		$bUserExist = "false" ;
		$bPassVerified = "false" ;
		$user = "" ;
		$status = 9 ;
		
		//Sanitize the value received from login field
		//to prevent SQL Injection
		if(!get_magic_quotes_gpc())
		{
			$user = mysql_real_escape_string($email) ;
		}
		else
		{
			$user = $email ;
		}
		
		$objUM = new CUserManager();
		$bEmptyUsersTable = $objUM->IsUsersTableEmpty();
		
		$result = 2;
		if(!$bEmptyUsersTable)
		{
			$result = $objUM->VerifyUser($email, $pass);
		}
		
		if($result == 2)
		{
			//Login Successful
			session_regenerate_id() ;
			
			$objUser = $objUM->GetUserByEmail($email) ;
			CSessionManager::Set(CSessionManager::STR_USER_ID, $objUser->GetUserID()) ;
			CSessionManager::Set(CSessionManager::STR_EMAIL_ID, $email);
			CSessionManager::Set(CSessionManager::BOOL_LOGIN, true) ;
			CSessionManager::Set(CSessionManager::INT_USER_TYPE, $objUser->GetUserType()) ;
			CSessionManager::Set(CSessionManager::STR_USER_NAME, $objUser->GetFirstName()." ".$objUser->GetLastName()) ;
			
			//if($objUser->GetUserType() == CConfig::UT_CORPORATE || $objUser->GetUserType() == CConfig::UT_INSTITUTE || $objUser->GetUserType() == CConfig::UT_SUPER_ADMIN)
			//{
				CSessionManager::Set(CSessionManager::STR_LOGIN_NAME, $objUser->GetLoginName());
			//}
			//$bUserVerified = "true" ;
			//$status = $member['reg_status'] ;
			// To set user is online
			//$objUM->SetOnline($objUser->GetUserID());//online
			
			session_write_close() ;
			
			/* noisrev is reverse of version, assigning a random time for version so that 
			page will reload and cached page should not use.*/
			if($objUser->GetUserType() != CConfig::UT_INDIVIDAL)
			{
				printf("<script language='javascript'> window.parent.location = '../core/test-admin/manage_test.php';</script>");
			}
			else 
			{
				printf("<script language='javascript'> window.parent.location = '../core/candidate/start_test.php';</script>");
			}
		}
		else if($result == 0)
		{
			// E-mail and password mismatch
			if(empty($redirect_url))
			{
				CSessionManager::SetErrorType(2);
				CSessionManager::SetErrorMsg("E-mail and password mismatch.");
				CUtils::Redirect("login_form.php");
			}
			else 
			{
				CUtils::Redirect($redirect_url."?err_msg=".urlencode("E-mail and password mismatch."));
			}
		}
	}
?>