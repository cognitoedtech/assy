<?php
	include_once("../../../database/mcat_db.php");
	include_once("../../../lib/new-email.php");
	include_once("../../../lib/utils.php");
	
	$objDB = new CMcatDB();
	
	$email_choice = $_POST['email_choice'];

	if($email_choice == "email")
	{
		$email_to_company	= $_POST['email_to_company'];
		$subject 			= $_POST['email_subject'];
		$email_body			= $_POST['email_body'];
		$email_ary = $objDB->GetEmailsForPromotion($email_to_company);
		$objMail = new CEMail("awareness@".strtolower(CConfig::SNC_SITE_NAME).".com", $objDB->GetPasswordFromOfficialEMail("awareness@".strtolower(CConfig::SNC_SITE_NAME).".com"));
		echo("Test 3<br/>");
		foreach($email_ary as $email => $name)
		{
			$objMail->PrepAndSendPromotionalMail($email, $name, $email_body, $subject);
			//CEMail::PrepAndSendPromotionalMail($email, $name, $email_body, $subject);
		}
		//printf("<pre>%s</pre>", print_r($email_ary, true));
		CUtils::Redirect("../promotional_email.php?email_success=1");	
	}
	else
	{
		$objDB->UnsubscribePromotionalEmail(trim($_POST['unsubscribe_email']));
		CUtils::Redirect("../promotional_email.php?unsubscribe_success=1");
	}
?>