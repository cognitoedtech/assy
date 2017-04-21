<?php
	//Start session
	include_once("../../lib/session_manager.php");
	include_once("../../database/config.php");
	include_once("../../database/mcat_db.php");
	include_once("../../lib/user_manager.php");
	include_once("../../lib/utils.php");
	include_once("../../lib/new-email.php");
	//require_once('../../3rd_party/recaptcha/recaptchalib.php');
	
	$captcha_value = CSessionManager::Get(CSessionManager::INT_CAPTCH_VALUE);
	
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
	
	$name		= clean($_POST['NAME']);
	$email		= clean($_POST['EMAIL']);
	$contact	= clean($_POST['CONTACT']);
	$org_type	= clean($_POST['ORG_TYPE']);
	$org_name	= clean($_POST['ORG_NAME']);
	$usage		= clean($_POST['USAGE']);
	$subject	= clean($_POST['SUBJECT']);
	$message	= clean($_POST['MESSAGE']);
	$verif_code = clean($_POST['VERIF_CODE']);
	
	/*$resp = recaptcha_check_answer (CConfig::CK_PRIVATE,
			$_SERVER["REMOTE_ADDR"],
			$_POST["recaptcha_challenge_field"],
			$_POST["recaptcha_response_field"]);*/
	
	$ajax_response = "";
	
	if ($captcha_value != $verif_code)
	{
		// What happens when the CAPTCHA was entered incorrectly
		$ajax_response = "<div style='color: red'>Dear User,<br/><br/> Your ".CConfig::SNC_SITE_NAME." demo request had been failed due to incorrect or empty captcha value, please re-fill the form.<BR/><BR/>-Team ".CConfig::SNC_SITE_NAME."</div>";
	}
	else if(empty($name))
	{
		$ajax_response = "<div style='color: red'>Dear User,<br/><br/> Your name is missing, please re-fill the form.<BR/><BR/>-Team ".CConfig::SNC_SITE_NAME."</div>";
	}
	else if(empty($email))
	{
		$ajax_response = "<div style='color: red'>Dear User,<br/><br/> Your Email-Id is missing, please re-fill the form.<BR/><BR/>-Team ".CConfig::SNC_SITE_NAME."</div>";
	}
	else if(empty($contact))
	{
		$ajax_response = "<div style='color: red'>Dear User,<br/><br/> Your contact number is missing, please re-fill the form.<BR/><BR/>-Team ".CConfig::SNC_SITE_NAME."</div>";
	}
	else if(empty($org_type))
	{
		$ajax_response = "<div style='color: red'>Dear User,<br/><br/> Your organization type is missing, please re-fill the form.<BR/><BR/>-Team ".CConfig::SNC_SITE_NAME."</div>";
	}
	else if(empty($org_name))
	{
		$ajax_response = "<div style='color: red'>Dear User,<br/><br/> Your organization name is missing, please re-fill the form.<BR/><BR/>-Team ".CConfig::SNC_SITE_NAME."</div>";
	}
	else if(empty($usage))
	{
		$ajax_response = "<div style='color: red'>Dear User,<br/><br/> Your monthly usage is missing, please re-fill the form.<BR/><BR/>-Team ".CConfig::SNC_SITE_NAME."</div>";
	}
	else if(empty($subject))
	{
		$ajax_response = "<div style='color: red'>Dear User,<br/><br/> Subject of the usage quotes request is missing, please re-fill the form.<BR/><BR/>-Team ".CConfig::SNC_SITE_NAME."</div>";
	}
	else if(empty($message))
	{
		$ajax_response = "<div style='color: red'>Dear User,<br/><br/> You have forgot to provide the message, please re-fill the form.<BR/><BR/>-Team ".CConfig::SNC_SITE_NAME."</div>";
	}
	else 
	{
		/*echo ("<pre>");
		print_r($_POST);
		echo ("</pre>");
		*/
		
		$final_org_type = $org_type;
		if($org_type == CConfig::$ORG_TYPE_ARY[CConfig::OT_OTHER])
		{
			$final_org_type = clean($_POST['OTHER_ORG']);
		}
		
		// Table Header
		$form_details = "<table border='1' rules='all'>";
	
		// Name
		$form_details .= "<tr>";
		$form_details .= "<td><b>Name:</b></td>";
		$form_details .= "<td>".$name."</td>";
		$form_details .= "</tr>";
		
		// Email
		$form_details .= "<tr>";
		$form_details .= "<td><b>Email:</b></td>";
		$form_details .= "<td>".$email."</td>";
		$form_details .= "</tr>";
		
		// Contact #
		$form_details .= "<tr>";
		$form_details .= "<td><b>Contact #:</b></td>";
		$form_details .= "<td>".$contact."</td>";
		$form_details .= "</tr>";
		
		// Organization Type
		$form_details .= "<tr>";
		$form_details .= "<td><b>Organization Type:</b></td>";
		$form_details .= "<td>".$final_org_type."</td>";
		$form_details .= "</tr>";
		
		// Organization Name
		$form_details .= "<tr>";
		$form_details .= "<td><b>Organization Name:</b></td>";
		$form_details .= "<td>".$org_name."</td>";
		$form_details .= "</tr>";
		
		// Usage
		$form_details .= "<tr>";
		$form_details .= "<td><b>Monthly Usage:</b></td>";
		$form_details .= "<td>".$usage."</td>";
		$form_details .= "</tr>";
		
		// Request Subject
		$form_details .= "<tr>";
		$form_details .= "<td><b>Request Subject:</b></td>";
		$form_details .= "<td>".$subject."</td>";
		$form_details .= "</tr>";
		
		// Request IP Address
		$form_details .= "<tr>";
		$form_details .= "<td><b>IP Address:</b></td>";
		$form_details .= "<td>".$_SERVER['REMOTE_ADDR']."</td>";
		$form_details .= "</tr>";
		
		$form_details .= "</table>";
		$objDB = new CMcatDB();
		$objMail = new CEMail(CConfig::OEI_SUPPORT, $objDB->GetPasswordFromOfficialEMail(CConfig::OEI_SUPPORT));
		$objMail->PrepAndSendDemoRequest($subject, $form_details);
		//CEMail::PrepAndSendSubsPlanQuoteRequest($subject, $form_details);
		
		$ack_messsage = "Dear ".$name.", <br/><br/> Thanks for showing interest in ".CConfig::SNC_SITE_NAME.".com. Your request is being submitted to ".CConfig::SNC_SITE_NAME." sales team, our Executive will contact you soon.<br/><br/>You shall be receiving a copy of this request at mentioned Email...<br/><br/>".$form_details."<br/><br/>You Matter,<br/>Team ".CConfig::SNC_SITE_NAME."<br/>Happy ".CConfig::SNC_SITE_NAME."ing !!!";
		
		$objMail->PrepAndSendDemoRequestAck($name, $email, $subject, $ack_messsage);
		//CEMail::PrepAndSendSubsPlanQuoteRequestAck($name, $email, $subject, $ack_messsage);
		
		$ajax_response = "Dear ".$name.", <br/><br/> Thanks for showing interest in ".CConfig::SNC_SITE_NAME.".com. Your request is being submitted to ".CConfig::SNC_SITE_NAME." sales team from IP Address ".$_SERVER['REMOTE_ADDR'].", our Executive will contact you soon.<br/><br/>You shall be receiving a copy of this request at mentioned Email...<br/><br/>You Matter,<br/>Team ".CConfig::SNC_SITE_NAME."<br/>Happy ".CConfig::SNC_SITE_NAME."ing !!!";
	
	}
	CSessionManager::UnsetSessVar(CSessionManager::INT_CAPTCH_VALUE);
	echo ($ajax_response);
?>