<?php
	include_once(dirname(__FILE__)."/../../../database/mcat_db.php");
	include_once("../../../lib/billing.php");
	include_once("../../../lib/new-email.php");
	include_once("../../../lib/utils.php");
	
	$objBilling = new CBilling();
	
	$xaction_id = $_POST['xaction_info'];
	$user_info = $_POST['user_info'];
	$amount = $_POST['recharge_amount'];
	
	$user_array = explode("(",$user_info);
	
	$name = $user_array[0];
	$email = trim(substr($user_array[1], stripos($user_array[1],", ")+1,-1));
	
	$objDB = new CMcatDB();
	$objMail = new CEMail(CConfig::OEI_FINANCE, $objDB->GetPasswordFromOfficialEMail(CConfig::OEI_FINANCE));
	
	$user_id  = $objBilling->GetUserIdByXactionId($xaction_id);
	$currency = $objBilling->GetCurrencyType($user_id);
	$currency_symbol = "$";
	if($currency == "INR")
	{
		$currency_symbol = "Rs.";
	}
	
	if($_POST['realize_choice'] == "yes")
	{	
		$objBilling->RealizeTransaction($xaction_id, $amount);	
		
		$objMail->PrepAndSendRealizePaymentMail($email, $name, $xaction_id, $amount, $currency_symbol);
		//CEMail::PrepAndSendRealizePaymentMail($email, $name, $xaction_id, $amount);
		CUtils::Redirect("../realize_payment.php?realized=1");
	}
	else
	{
		$void_reason = $_POST['void_reasons'];
		if($void_reason == "other")
		{
			$void_reason = $_POST['other_void_reason'];
		}
		
		$objBilling->VoidTransaction($xaction_id, $void_reason);
	
		$objMail->PrepAndSendVoidPaymentMail($email, $name, $xaction_id, $void_reason);
		//CEMail::PrepAndSendVoidPaymentMail($email, $name, $xaction_id, $void_reason);
		CUtils::Redirect("../realize_payment.php?voided=1");	
	}
?>