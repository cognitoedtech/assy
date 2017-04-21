<?php
	include_once(dirname(__FILE__)."/../../../database/mcat_db.php");
	include_once("../../../lib/billing.php");
	include_once("../../../lib/new-email.php");
	include_once("../../../lib/utils.php");
	
	$objBilling = new CBilling();
	
	$objDB = new CMcatDB();
	$objMail = new CEMail(CConfig::OEI_FINANCE, $objDB->GetPasswordFromOfficialEMail(CConfig::OEI_FINANCE));
	
	
	$selected_user_id		= substr($_POST['user_info'],3);
	$currency_type			= substr($_POST['user_info'],0,3);
	$free_tests				= $_POST['free_tests'];
	
	$name  = $objDB->GetUserName($selected_user_id);
	$email = $objDB->GetUserEmail($selected_user_id);
	
	$currency_type = NULL;
	if($currency_type == "USD")
	{
		$currency = '$';
	}
	else
	{
		$currency = 'Rs.';
	}
	
	$plan_rate	= $objBilling->GetPersonalQuesRate($selected_user_id);
	
	$amount =  $plan_rate * $free_tests;
	
	$objBilling->ProcessFreeRecharge($selected_user_id, $amount);
	
	$objMail->PrepAndSendFreeRechargeMail($email, $name, $amount, $currency, $free_tests);
	//CEMail::PrepAndSendFreeRechargeMail($email, $name, $amount, $currency);	
	
	CUtils::Redirect("../free_test_credit.php?processed=1");
?>