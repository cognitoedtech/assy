<?php
	
	include_once(dirname(__FILE__)."/../../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../../database/config.php");
	include_once(dirname(__FILE__)."/../../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/../../../lib/utils.php");
	include_once(dirname(__FILE__)."/../../../lib/billing.php");
	include_once(dirname(__FILE__)."/../../../lib/new-email.php");
	include_once(dirname(__FILE__)."/../../../lib/user_manager.php");
	
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
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	
	$objDB 			  = new CMcatDB();
	$objBilling 	  = new CBilling();
	$objUM			  = new CUserManager();	
	$objMail 		  = new CEMail(CConfig::OEI_SUPPORT, $objDB->GetPasswordFromOfficialEMail(CConfig::OEI_SUPPORT));
	
	$coordinator_id   = $_POST['coord_id'];
	$recharge_amount  = $_POST['insert_recharge_amount'];
	$recliamed_amount = $_POST['insert_reclaim_amount'];
	$user_choice      = $_POST['edit_details'];
	
	if( $user_choice == "recharge")
	{
		$objBilling->AddBalance($coordinator_id, $recharge_amount);
		$objBilling->AddProjectedBalance($coordinator_id, $recharge_amount);
		$objBilling->SubBalance($user_id, $recharge_amount);    
		$objBilling->SubProjectedBalance($user_id, $recharge_amount);
		$objBilling->AddCoordinatorBillingHistory($coordinator_id, $recharge_amount, CConfig::CTT_RECHARGE);
		
		$coordinatorInfo  = $objUM->GetUserById($coordinator_id);
		$currency 		  = $objBilling->GetCurrencyType($coordinator_id);
		
		if($currency == "USD")
		{
			$currency = "$";
		}
		else
		{
			$currency =  "Rs.";
		}
		
		$subject = "Your Coordinator account is suceeefully recharged" ;
		$body = "Dear  ".$coordinatorInfo->GetFirstName()." ".$coordinatorInfo->GetLastName().",<br/><br/> Your ".CConfig::SNC_SITE_NAME." account has been recharged successfully with amount ".$currency." ".$recharge_amount." <br/><br/> You Matter,<br/>Team ".CConfig::SNC_SITE_NAME; 
		$result_email=$coordinatorInfo->GetEmail();
		$objMail->Send($result_email, $subject, $body);
		//CEMail::Send($result_email, CConfig::OEI_SUPPORT, $subject, $body);
		
		CUtils::Redirect("../manage.php?processed=2");
		
	}
	
	else if ($user_choice == "permissions")
	{
		$objUM = new CUserManager();

		$permitted_all		= $_POST['PERMIT_ALL'];
		$permissions_array	= $_POST['PERMISSIONS'];
		
		$result = $objUM->UpdateCoordinatorPermissions($permissions_array,$coordinator_id,$permitted_all);
		
		CUtils::Redirect("../manage.php?processed=1");
	}
	else if($user_choice == "reclaim")
	{
		$objBilling->SubBalance($coordinator_id, $recliamed_amount);    //substract amount from owner acc
		$objBilling->SubProjectedBalance($coordinator_id, $recliamed_amount);
		$objBilling->AddBalance($user_id, $recliamed_amount);
		$objBilling->AddProjectedBalance($user_id, $recliamed_amount);
		$objBilling->AddCoordinatorBillingHistory($coordinator_id, $recliamed_amount, CConfig::CTT_RECLAIM);
		
		$coordinatorInfo  = $objUM->GetUserById($coordinator_id);
		$ownerInfo        = $objUM->GetUserById($user_id);
		$currency 		  = $objBilling->GetCurrencyType($coordinator_id);
		
		if($currency == "USD")
		{
			$currency = "$";
		}
		else
		{
			$currency =  "Rs.";
		}
		
		$title = '';
		if($ownerInfo->GetGender() != 0)
		{
			$title = "Mr.";
		}
		else
		{
			$title = "Mrs.";
		}
		
		$subject = "Amount reclaimed from ".CConfig::SNC_SITE_NAME." account" ;
		$body = "Dear  ".$coordinatorInfo->GetFirstName()." ".$coordinatorInfo->GetLastName().",<br/><br/> Your ".CConfig::SNC_SITE_NAME." account has been reclaimed by amount ".$currency." ".$recliamed_amount." by your ".CConfig::SNC_SITE_NAME." owner ".$title." ".$ownerInfo->GetFirstName()." ".$ownerInfo->GetLastName().".<br/><br/> You Matter,<br/>Team ".CConfig::SNC_SITE_NAME;
		$result_email=$coordinatorInfo->GetEmail();
		echo $result_email."hello";
		$objMail->Send($result_email, $subject, $body);
		//CEMail::Send($result_email, CConfig::OEI_SUPPORT, $subject, $body);
		CUtils::Redirect("../manage.php?processed=3");
	}
	
?>
	
	
	