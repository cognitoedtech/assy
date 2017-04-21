<?php
	include_once(dirname(__FILE__)."/../database/mcat_db.php");
	include_once("site_config.php");
	include_once(dirname(__FILE__)."/../3rd_party/xpm/MAIL.php");

	
	class CEMail
	{
		static function Send($to, $from_address, $sub, $msg)
		{
			// to add content type 
			$header = "MIME-Version: 1.0" . "\r\n";
			$header .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
			$header .= "From: " . $from_address . "\r\n";
						
			$msg = str_replace("\n.", "\n..", $msg);	// to replace blank space in.
			
			$msg = wordwrap($msg, 70) ;
			
			@mail($to, $sub, $msg, $header, "-f $from_address") ;
		}
		
		/*static function Send($to, $from_address, $sub, $msg)
		{	
			$retVal = false;
		
			$objDB = new CMcatDB();
			
			// initialize MAIL class
			$objMail = new MAIL;
			
			// set from address
			$objMail->from($from_address);
			
			// set from password
			$from_password = $objDB->GetPasswordFromOfficialEMail($from_address);
			
			// add to address
			$objMail->addto($to);

			// set subject
			$objMail->subject($sub);
			
			// set HTML message
			$objMail->html($msg);
			
			if(!empty($from_password))
			{
				// connect to MTA server 'smtp.gmail.com' port '465' via SSL ('tls' encryption) with authentication: 'username@gmail.com'/'password'
				// make sure you have OpenSSL module (extension) enable on your php configuration
				$objConnection = $objMail->connect('mail.mastishka.com', 465, $from_address, $from_password, 'tls') or die(print_r($objMail->Result));
	
				// send mail relay using the '$objConnection' resource connection
				$retVal = $objMail->send($objConnection); 
			}
			
			// disconnect from server
			$objMail->disconnect();
			
			unset($objDB);
			unset($objMail);
				
			return $retVal;
		}*/
		
		/*
		 * Inform candidate that he/she had been regitered for test.
		 */
		static function PrepAndSendRegMail($candidate_email, $candidate_name, $organization_name, $user_email, $user_name, $reg_url, $password)
		{
			//echo "EmailTo: ".$candidate_email."<br/>";
			
			$sub_for_candidate = "[MIp-CAT] ".$candidate_name." - ".$organization_name." registered you for ability test" ;
			$msg_for_candidate = "Dear <b>".$candidate_name."</b>,<br/><br/>Test administrator <b>".$user_name."</b> ( ".$user_email." ) has registered you to take ability test designed for you. To confirm the registration, please click on following link <a href='".$reg_url."'>".$reg_url."</a> or copy and paste above link to your browser.<br/><br/>Your Username: ".$candidate_email."<br/><br/>Your Password: ".$password."<br/><br/>Regards,<br/>MIp-CAT Technical Support<br/><a href='http://www.mipcat.com'>www.mipcat.com</a><br/><b>Emperical natural selection happens here!</b><br/><br/><br/>This is an auto generated Email. Please don't reply to this mail." ;
			
			CEMail::Send($candidate_email, "support@mipcat.com", $sub_for_candidate, $msg_for_candidate) ;
		}
		
		/*
		 * Inform candidate that a test has been scheduled for him/her.
		 */
		static function PrepAndSendTestScheduleMail($test_name, $candidate_email, $candidate_name, $organization_name, $user_email, $user_name, $date)
		{
			//echo "EmailTo: ".$candidate_email."<br/>";
			
			$sub_for_candidate = "[MIp-CAT] ".$candidate_name." - ".$organization_name." scheduled a test for you." ;
			$msg_for_candidate = "Dear <b>".$candidate_name."</b>,<br/><br/>Test administrator <b>".$user_name."</b> ( ".$user_email." ) has scheduled an ability test <b>".$test_name."</b> for you dated on <b>".$date."</b>. Please login to your account on said date to take the test.<br/><br/>Regards,<br/>MIp-CAT Technical Support<br/><a href='http://www.mipcat.com'>www.mipcat.com</a><br/><b>Emperical natural selection happens here!</b><br/><br/><br/>This is an auto generated Email. Please don't reply to this mail." ;
			
			CEMail::Send($candidate_email, "support@mipcat.com", $sub_for_candidate, $msg_for_candidate) ;
		}
		
		/*
		 * Retrieve Forgotten Password email.
		 */
		static function PrepAndSendPasswordChangeMail($candidate_email, $candidate_name, $candidate_id, $md5_pwd, $ip_addr)
		{
			//echo "EmailTo: ".$candidate_email."<br/>";
			
			$sub_for_candidate = "[MIp-CAT] ".$candidate_name." - password change request!" ;
			$msg_for_candidate = "Dear <b>".$candidate_name."</b>,<br/><br/>We have received a password retrieval request (via I.P. address - <b>".$ip_addr."</b>) from our &lsquo;Retrieve Forgotten Password&rsquo; section. Please click on following link <a href='".CSiteConfig::ROOT_URL."/login/forgot_done.php?loc=".$md5_pwd."&offset=".$candidate_id."&rand=".md5($candidate_email)."'>".CSiteConfig::ROOT_URL."/login/forgot_done.php?loc=".$md5_pwd."&offset=".$candidate_id."&rand=".md5($candidate_email)."</a> to change the existing password. If you won&rsquo;t act on this email your password will remain un-touched.<br/><br/>Regards,<br/>MIp-CAT Technical Support<br/><a href='http://www.mipcat.com'>www.mipcat.com</a><br/><b>Emperical natural selection happens here!</b><br/><br/><br/>This is an auto generated Email. Please don't reply to this mail." ;
			
			CEMail::Send($candidate_email, "support@mipcat.com", $sub_for_candidate, $msg_for_candidate) ;
		}
		
		/*
		 * Send Bussiness Associate enquiry email.
		 */
		static function PrepAndSendPwdChangedAckMail($candidate_email, $candidate_name, $ip_addr)
		{
			//echo "EmailTo: ".$candidate_email."<br/>";
			
			$sub_for_candidate = "[MIp-CAT] ".$candidate_name." - password change done!" ;
			$msg_for_candidate = "Dear <b>".$candidate_name."</b>,<br/><br/>We have changed your password (via I.P. address - <b>".$ip_addr."</b>). Welcome back, you are important to us - please feel free to use our services.<br/><br/>Regards,<br/>MIp-CAT Technical Support<br/><a href='http://www.mipcat.com'>www.mipcat.com</a><br/><b>Emperical natural selection happens here!</b><br/><br/><br/>This is an auto generated Email. Please don't reply to this mail." ;
			
			CEMail::Send($candidate_email, "support@mipcat.com", $sub_for_candidate, $msg_for_candidate) ;
		}
		
		/*
		 * Send Bussiness Associate enquiry email.
		 */
		static function PrepAndSendBAEnquiryMail($contact, $ba_email, $org_name, $subject, $msg, $ip_addr)
		{
			//echo "EmailTo: ".$ba_email."<br/>";
			
			$sub_for_receptor = "[MIpCAT - Business Associate Request] ".$subject ;
			$msg_for_receptor = "Dear Sir or Madam,<br/><br/>We have received &lsquo;Becoming Business Associate&rsquo; request (via I.P. address - <b>".$ip_addr."</b>) from <b>".$org_name."</b> (Email: ".$ba_email.", Contact #: ".$contact.").<br/><br/><hr/><b><u>Message :</u></b><br/><br/>".$msg.".<br/><hr/><br/>Our Business Development Executive will soon contact you, Thanks for your interest!.<br/><br/>Regards,<br/>MIpCAT Business Associate Support<br/><a href='http://www.mipcat.com'>www.mipcat.com</a><br/><b>Emperical natural selection happens here!</b><br/><br/><br/><b>Note:</b> This is an auto generated Email. You can reply to this email to know status of your request." ;
			
			// Email ACK to BA requestor.
			CEMail::Send($ba_email, "business_associate@mipcat.com", $sub_for_receptor, $msg_for_receptor) ;
			
			// Email to BA request receptors.
			foreach(CConfig::$ba_req_email_receptors as $rec_email)
			{
				CEMail::Send($rec_email, "business_associate@mipcat.com", $sub_for_receptor, $msg_for_receptor) ;
			}
		}
		
		static function PrepAndSendRealizePaymentMail($user_email, $user_name, $xaction_id, $amount, $currency = "Rs.")
		{
			$sub_for_user = "MIpCAT recharge successful" ;
			$sub_for_finance  = "One Transaction has been realized";
			
			$body = "Dear ".$user_name.",<br/><br/> Your MIpCAT account has been recharged successfully with amount ".$currency." ".$amount." whose transaction id is ".$xaction_id.". <br/><br/>You Matter,<br/>Team MIpCAT";
			
			CEMail::Send($user_email, CConfig::OEI_FINANCE, $sub_for_user, $body);
			
			CEMail::Send(CConfig::OEI_FINANCE, CConfig::OEI_FINANCE, $sub_for_finance, $body);
		}
		
		static function PrepAndSendVoidPaymentMail($user_email, $user_name, $xaction_id, $void_reason)
		{
			$sub_for_user 	 = "MIpCAT recharge failed";
			$sub_for_finance = "One Transaction has been made void";
			
			$body = "Dear ".$user_name.",<br/><br/> Your MIpCAT account could not be recharged successfully whose transaction id is ".$xaction_id." due to ".$void_reason." <br/><br/>You Matter,<br/>Team MIpCAT";
			
			CEMail::Send($user_email, CConfig::OEI_FINANCE, $sub_for_user, $body);
			
			CEMail::Send(CConfig::OEI_FINANCE, CConfig::OEI_FINANCE, $sub_for_finance, $body);
		}
		
		static function PrepAndSendFreeRechargeMail($user_email, $user_name, $amount, $currency)
		{
			$sub_for_user 	 = "MIpCAT Free Recharge" ;
			$sub_for_finance = "MIpCAT Free Recharge has been processed";
			
			$body_for_user = "Dear ".$user_name.",<br/><br/> You have got free MIpCAT account recharge of ".$currency." ".$amount." <br/><br/>You Matter,<br/>Team MIpCAT";
			
			$body_for_finance = "MIpCAT free recharge has been done with ".$currency." ".$amount." for one user with details as below: <br /><br />User Name: ".$user_name."<br />Email Id: ".$user_email;
			
			CEMail::Send($user_email, CConfig::OEI_FINANCE, $sub_for_user, $body_for_user);
			
			CEMail::Send(CConfig::OEI_FINANCE, CConfig::OEI_FINANCE, $sub_for_finance, $body_for_finance);
		}
		
		static function PrepAndSendEncashPointsRequestMail($contrib_email, $contrib_name, $points)
		{
			$sub_for_contrib = "MIpCAT encash points request has been received";
			$sub_for_finance = "One encash points request has been received";
			
			$body = "Dear ".$contrib_name.",<br/><br/> Your encash request has been received for encashing ".$points." points successfully, It will take minimum 7 working days to process.Thank you for your contribution.<br/><br/>You Matter,<br/>Team MIpCAT";
			
			CEMail::Send($contrib_email, CConfig::OEI_FINANCE, $sub_for_contrib, $body);
			
			CEMail::Send(CConfig::OEI_FINANCE, CConfig::OEI_FINANCE, $sub_for_finance, $body);
		}
		
		static function PrepAndSendContribPaymentMail($contrib_email, $contrib_name, $points, $amount, $cheque_no, $cheque_date, $drawn_bank, $xaction_id)
		{
			$sub_for_contrib = "MIpCAT contribution points encashed";
			$sub_for_finance = "One contributor payment has been processed";
			
			$body = "Dear ".$contrib_name.",<br/><br/> Your request regarding encashing ".$points." points has been processed successfully with amount Rs. ".$amount." by cheque with cheque number ".$cheque_no." having date ".$cheque_date." of ".$drawn_bank." bank whose MIpCAT transaction id is ".$xaction_id.". <br/><br/> It will take minimum 7 working days to be delivered by post or courier. <br/><br/>You Matter,<br/>Team MIpCAT";
		
			CEMail::Send($contrib_email, CConfig::OEI_FINANCE, $sub_for_contrib, $body);
			
			CEMail::Send(CConfig::OEI_FINANCE, CConfig::OEI_FINANCE, $sub_for_finance, $body);
		}
		
		static function PrepAndSendPromotionalMail($to_email, $recipient_name, $email_body, $subject)
		{
			$body = "Dear ".$recipient_name.",<br/><br/>".$email_body." <br/><br/>Regards,<br/>Manish Arora<br/>MIpCAT Awareness Team<br/>Contact No.: +919039579039";
			$body.="<br /><br /><br />Note: This is an awareness and demonstration request email. If you do not want to receive promotional emails from us, then please write us on unsubscribe@mipcat.com (from email id which should be unsubscribed) and we will unsubscribe you from our list. Please note that after unsubscribing from our list you will not be able to receive upcoming features and advancements from us regarding www.mipcat.com.";
			
			CEMail::Send($to_email, "manish.arora@mastishka.com", $subject, $body);
		}
		
		static function PrepAndSendAccountRechargeMail($user_email, $user_name, $payment_mode, $recharge_amount, $payment_ordinal, $payment_date, $payment_agent)
		{
			$sub_for_user	 = "MIpCAT account recharge request has been received successfully";
			$sub_for_finance = "MIpCAT account recharge request has been received";
			
			$body = NULL;
			
			if($payment_mode == CConfig::PAYMENT_MODE_CHEQUE || $payment_mode == CConfig::PAYMENT_MODE_DD)
			{
				$body = "Dear ".$user_name.",<br/><br/> Your MIpCAT account recharge request has been received successfully with details as below: <br /><br />Amount: Rs. ".$recharge_amount."<br />Cheque &frasl; DD Number: ".$payment_ordinal."<br />Date On Cheque &frasl; DD Number: ".$payment_date."<br />Drawn Bank Name: ".$payment_agent." <br/><br/>You Matter,<br/>Team MIpCAT";
			}
			else
			{
				$body = "Dear ".$user_name.",<br/><br/> Your MIpCAT account recharge request has been received successfully with details as below: <br /><br />Amount: Rs. ".$recharge_amount."<br />NEFT Transaction ID: ".$payment_ordinal."<br />Date of Payment: ".$payment_date."<br />Bank (who) Processed: ".$payment_agent." <br/><br/>You Matter,<br/>Team MIpCAT";
			}
			
			CEMail::Send($user_email, CConfig::OEI_FINANCE, $sub_for_user, $body);
			
			CEMail::Send(CConfig::OEI_FINANCE, CConfig::OEI_FINANCE, $sub_for_finance, $body);
		}
		
		static function PrepAndSendInvalidPaypalTransactionMail($user_email, $user_name)
		{
			$subject = "Invalid PayPal Transaction";
			
			$body	 = "Dear ".$user_name.",<br/><br/> Your last transaction regarding recharge your MIpCAT account has been rejected by PayPal. Please try again with proper information.<br/><br/>You Matter,<br/>Team MIpCAT";
		
			CEMail::Send($user_email, CConfig::OEI_FINANCE, $subject, $body);
			
			CEMail::Send(CConfig::OEI_FINANCE, CConfig::OEI_FINANCE, $subject, $body);
		}
		
		static function PrepAndSendBAPaymentMail($ba_email, $ba_name, $ba_org, $gross_commission, $net_commission, $service_tax_amount, $tds_amount, $payment_ordinal, $payment_date, $payment_agent)
		{
			$sub_for_ba		 = "MIpCAT Recharge Commission Processed";
			
			$sub_for_finance = "MIpCAT Recharge Commission Processed for Business Associate";
			
			$body			 = sprintf("Dear %s(%s),<br /><br />We have settled Rs. %1.2f(Your Share: Rs. %1.2f, Service Tax: Rs. %1.2f, TDS: Rs. %1.2f) and sent you cheque/NEFT payment on %s of %s with payment ordinal %s. You may receive your commission for within 10 working days. If you may not receive the mentioned amount, please contact your relationship Manager at MIpCAT.com.It is always pleasure doing business with you, looking forward to generate more business with your efforts.<br />Let's Grow !<br /><br />Kind Regards,<br />Finance Department @ MIpCAT.com<br /><br /><br />Note: You may find details of this transaction at your login under transaction statement navigation (left) menu or please contact us for more information.", $ba_name, $ba_org, $gross_commission, $net_commission, $service_tax_amount, $tds_amount, $payment_date, $payment_agent, $payment_ordinal);
		
			CEMail::Send($ba_email, CConfig::OEI_FINANCE, $sub_for_user, $body);
				
			CEMail::Send(CConfig::OEI_FINANCE, CConfig::OEI_FINANCE, $sub_for_ba, $body);
		}
		
		static function PrepAndSendSubsPlanQuoteRequest($subject, $form_details)
		{
			CEMail::Send(CConfig::OEI_SALES, CConfig::OEI_SUPPORT,  $subject, $form_details);
		}
		
		static function PrepAndSendSubsPlanQuoteRequestAck($name, $email, $subject, $ack_messsage)
		{
			CEMail::Send($email, CConfig::OEI_SUPPORT, $subject, $ack_messsage);
		}
		
		static function PrepAndSendEditTestScheduleMail($candidate_name, $candidate_email, $test_name, $tschd_id, $test_scheduled_date, $scheduled_by)
        {
            $sub_for_user     = "Scheduled test has been cancelled for you";
            $sub_for_support = "One Candidate has been removed from a scheduled test";
           
            $body_for_user = "Dear ".$candidate_name.",<br/><br/> Your name has been removed from a scheduled test with below information :<br /><br /><b>Test Name:</b> ".$test_name."<br /><b>Scheduled Date:</b> ".$test_scheduled_date."(xID : ".$tschd_id.")<br /><b>Scheduled by:</b> ".$scheduled_by."<br/><br/>You Matter,<br/>Team MIpCAT";
           
            $body_for_support = "One Candidate has been removed from a scheduled test with below information :<br /><br />Test Name: ".$test_name."<br />Scheduled Date: ".$test_scheduled_date."(xID : ".$tschd_id.")<br />Scheduled by: ".$scheduled_by;
           
            CEMail::Send($candidate_email, CConfig::OEI_SUPPORT, $sub_for_user, $body_for_user);
           
            CEMail::Send(CConfig::OEI_SUPPORT, CConfig::OEI_SUPPORT, $sub_for_support, $body_for_support);
        }
	}
?>