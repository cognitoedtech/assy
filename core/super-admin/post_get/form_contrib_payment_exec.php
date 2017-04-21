<?php
	include_once(dirname(__FILE__)."/../../../database/mcat_db.php");
	include_once("../../../lib/billing.php");
	include_once("../../../lib/new-email.php");
	include_once("../../../lib/utils.php");
	
	$objBilling = new CBilling();
	$objDB		= new CMcatDB();
	
	$xaction_id  = $_POST['xaction_info'];
	$points 	 = $_POST['points'];
	$amount		 = $_POST['amount'];
	$cheque_no	 = $_POST['cheque_num'];
	$cheque_date = $_POST['payment_date'];
	$drawn_bank  = $_POST['drawn_bank'];
	$user_info   = $_POST['user_info'];
	
	$user_array = explode("(",$user_info);
	
	$contrib_name = $user_array[0];
	$contrib_email = trim(substr($user_info, stripos($user_info,"(")+1,-1));
	
	$objBilling->ProcessContribPayment($xaction_id, $cheque_no, $drawn_bank, $cheque_date);
	
	$objMail = new CEMail(CConfig::OEI_FINANCE, $objDB->GetPasswordFromOfficialEMail(CConfig::OEI_FINANCE));
	$objMail->PrepAndSendContribPaymentMail($contrib_email, $contrib_name, $points, $amount, $cheque_no, $cheque_date, $drawn_bank, $xaction_id);
	//CEMail::PrepAndSendContribPaymentMail($contrib_email, $contrib_name, $points, $amount, $cheque_no, $cheque_date, $drawn_bank, $xaction_id);	
	
	CUtils::Redirect("../contrib_payment_process.php?processed=1");
?>