<?php
	include_once(dirname(__FILE__)."/../../../database/mcat_db.php");
	include_once("../../../lib/billing.php");
	include_once("../../../lib/new-email.php");
	include_once("../../../lib/utils.php");
	
	$objBilling = new CBilling();
	$objDB		= new CMcatDB();
	
	$ba_id		 			 = $_POST['xaction_info'];
	$net_commission		 	 = $_POST['net_commission'];
	$gross_commission		 = $_POST['gross_commission'];
	$service_tax_amount		 = $_POST['service_tax_amount'];
	$tds_amount  			 = $_POST['tds_amount'];
	$payment_ordinal		 = $_POST['payment_ordinal'];
	$payment_date			 = $_POST['payment_date'];
	$payment_agent			 = $_POST['payment_agent'];
	$user_info   			 = $_POST['user_info'];
	$client_xaction_array	 = $_POST['payment_done'];
	
	$user_array = explode("(",$user_info);
	
	$ba_name	 	 = $user_array[0];
	$ba_email		 = trim(substr($user_array[1], stripos($user_array[1],", ")+1,-1));
	
	$ba_org_info	 = explode(",",$user_array[1]);
	$ba_org			 = trim($ba_org_info[0]);	
	/*echo $ba_id.'<br />';
	echo $net_commission.'<br />';
	echo $gross_commission.'<br />';
	echo $service_tax_amount.'<br />';
	echo $tds_amount.'<br />';
	echo $payment_ordinal.'<br />';
	echo $payment_date.'<br />';
	echo $payment_agent.'<br />';
	echo $ba_name.'<br />';
	echo $ba_email.'<br />';
	print_r($client_info);*/
	$objBilling->ProcessBAPayment($ba_id, $gross_commission, $net_commission, $service_tax_amount, $tds_amount, $payment_ordinal, $payment_date, $payment_agent);
	
	$objBilling->DoneBAClientPayment($client_xaction_array);
	
	$objMail = new CEMail(CConfig::OEI_FINANCE, $objDB->GetPasswordFromOfficialEMail(CConfig::OEI_FINANCE));
	$objMail->PrepAndSendBAPaymentMail($ba_email, $ba_name, $ba_org, $gross_commission, $net_commission, $service_tax_amount, $tds_amount, $payment_ordinal, $payment_date, $payment_agent);
	//CEMail::PrepAndSendBAPaymentMail($ba_email, $ba_name, $ba_org, $gross_commission, $net_commission, $service_tax_amount, $tds_amount, $payment_ordinal, $payment_date, $payment_agent);	
	
	CUtils::Redirect("../ba_payment_process.php?processed=1");
?>