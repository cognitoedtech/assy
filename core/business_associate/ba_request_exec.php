<?php
	include_once(dirname(__FILE__)."/../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../database/config.php");
	include_once(dirname(__FILE__)."/../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/../../lib/utils.php");
	include_once(dirname(__FILE__)."/../../lib/new-email.php");
	include_once(dirname(__FILE__)."/../../test/lib/test_helper.php");
	
	$contact 	= $_POST['contact'] ;
	$ba_email 	= $_POST['email'] ;
	$org_name 	= $_POST['org_name'] ;
	$subject 	= $_POST['subject'] ;
	$msg 		= $_POST['message'] ;
	
	$ip_addr	= $_SERVER['REMOTE_ADDR'];
	
	$objDB		= new CMcatDB();
	
	$objMail = new CEMail(CConfig::OEI_BUSI_ASSOC, $objDB->GetPasswordFromOfficialEMail(CConfig::OEI_BUSI_ASSOC));
	$objMail->PrepAndSendBAEnquiryMail($contact, $ba_email, $org_name, $subject, $msg, $ip_addr);
	//CEMail::PrepAndSendBAEnquiryMail($contact, $ba_email, $org_name, $subject, $msg, $ip_addr);
	
	CUtils::Redirect("../../help-and-faq/faq/business_associate_faq.php");
?>