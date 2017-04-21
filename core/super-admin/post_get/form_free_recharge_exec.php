<?php
	include_once(dirname(__FILE__)."/../../../database/mcat_db.php");
	include_once("../../../lib/billing.php");
	include_once("../../../lib/new-email.php");
	include_once("../../../lib/utils.php");
	
	$objBilling = new CBilling();
	
	$objDB = new CMcatDB();
	$objMail = new CEMail(CConfig::OEI_FINANCE, $objDB->GetPasswordFromOfficialEMail(CConfig::OEI_FINANCE));
	
	$recharge_choice = $_POST['recharge_choice'];
	$user_info = $_POST['user_info'];
	
	$user_array = explode("(",$user_info);
	
	$name = $user_array[0];
	$email = trim(substr($user_array[1], stripos($user_array[1],", ")+1,-1));
	
	$user_id 		= NULL;
	$amount	 		= NULL;
	$currency_type	= NULL;
	$currency		= NULL;

	if($recharge_choice == CConfig::UT_INSTITUTE)
	{
		$user_id		= substr($_POST['inst_info'],3);
		$currency_type	= substr($_POST['inst_info'],0,3);
		$amount			= $_POST['recharge_inst_amount'];	
	}
	else
	{
		$user_id	= substr($_POST['corp_info'],3);
		$currency_type	= substr($_POST['corp_info'],0,3);
		$amount		= $_POST['recharge_corp_amount'];
	}
	
	if($currency_type == "USD")
	{
		$currency = '$';
	}
	else
	{
		$currency = 'Rs.';
	}
	
	$objBilling->ProcessFreeRecharge($user_id, $amount);
	
	$objMail->PrepAndSendFreeRechargeMail($email, $name, $amount, $currency);
	//CEMail::PrepAndSendFreeRechargeMail($email, $name, $amount, $currency);	
	
	CUtils::Redirect("../free_recharge.php?processed=1");
?>