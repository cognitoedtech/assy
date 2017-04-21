<?php
	/*echo("<pre>");
	print_r($_POST);
	echo("</pre>");
	exit();*/
	
	include_once(dirname(__FILE__)."/../../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../../database/config.php");
	include_once(dirname(__FILE__)."/../../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/../../../lib/utils.php");
	include_once(dirname(__FILE__)."/../../../lib/billing.php");
	include_once(dirname(__FILE__)."/../../../lib/new-email.php");
	
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
	
	$objDB = new CMcatDB();
	
	$email = $objDB->GetUserEmail($user_id);
	$name = $objDB->GetUserName($user_id);
	
	$payment_mode		= null;
	$recharge_amount 	= null;
    $payment_ordinal 	= null;
    $payment_date 		= null;
    $payment_agent		= null;
	
	$currency	= $_POST['currency'];
	if($currency == "INR" || $currency == "USD")
	{
    	$payment_mode = $_POST['payment_mode_inr'];
    	
    	if($payment_mode == CConfig::PAYMENT_MODE_CHEQUE || $payment_mode == CConfig::PAYMENT_MODE_DD)
    	{
    		$recharge_amount 	= $_POST['recharge_amount_chq'];
		    $payment_ordinal 	= $_POST['payment_ordinal_chq'];
		    $payment_date 		= $_POST['payment_date_chq'];
		    $payment_agent 		= $_POST['payment_agent_chq'];
    	}
    	else if($payment_mode == CConfig::PAYMENT_MODE_NEFT)
    	{
    		$recharge_amount 	= $_POST['recharge_amount_neft'];
		    $payment_ordinal 	= $_POST['payment_ordinal_neft'];
		    $payment_date 		= $_POST['payment_date_neft'];
		    $payment_agent	 	= $_POST['payment_agent_neft'];
    	}
	}
	else 
	{
		$payment_mode = $_POST['payment_mode_usd'];
		
		$recharge_amount 	= null;
	    $payment_ordinal 	= null;
	    $payment_date 		= null;
	    $payment_agent		= null;	
	}
    
	$payment_date = date("Y-m-d", strtotime($payment_date));
	$objBilling = new CBilling();
	
	$ba_commission_percent = $objBilling->GetBACommissionRate($user_id);
	
	$xaction_id = $objBilling->InsertReceivedPayment($user_id, $payment_mode, $payment_agent, $payment_ordinal, $payment_date, 
									$recharge_amount, $ba_commission_percent);
	
	$objMail = new CEMail(CConfig::OEI_FINANCE, $objDB->GetPasswordFromOfficialEMail(CConfig::OEI_FINANCE));
	$objMail->PrepAndSendAccountRechargeMail($email, $name, $payment_mode, $recharge_amount, $payment_ordinal, $payment_date, $payment_agent, $currency);
	//CEMail::PrepAndSendAccountRechargeMail($email, $name, $payment_mode, $recharge_amount, $payment_ordinal, $payment_date, $payment_agent);
	
	CUtils::Redirect("../billing.php?success=1");
?>