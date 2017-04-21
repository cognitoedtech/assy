<?php 
	include_once(dirname(__FILE__)."/../../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../../database/config.php");
	include_once(dirname(__FILE__)."/../../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/../../../lib/user_manager.php");
	include_once(dirname(__FILE__)."/../../../lib/utils.php");
	include_once(dirname(__FILE__)."/../../../lib/new-email.php");
	
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
	
	$ajax_response = "";
	
	if(isset($_POST['demo_request']) && $_POST['demo_request'] == 1)
	{
		$name		= ucfirst(clean($_POST['NAME']));
		$email		= clean($_POST['EMAIL']);
		$contact	= clean($_POST['CONTACT']);
		$org_type	= clean($_POST['ORG_TYPE']);
		$org_name	= clean($_POST['ORG_NAME']);
		$usage		= clean($_POST['USAGE']);
		$subject	= clean($_POST['SUBJECT']);
		$message	= clean($_POST['MESSAGE']);
		$verif_code = clean($_POST['VERIF_CODE']);
		
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
			
			$ack_messsage = "Dear ".$name.", <br/><br/> Thanks for showing interest in ".CConfig::SNC_SITE_NAME.".com. Your request is being submitted to ".CConfig::SNC_SITE_NAME." sales team, our Executive will contact you soon.<br/><br/>You shall be receiving a copy of this request at mentioned Email...<br/><br/>".$form_details."<br/><br/>You Matter,<br/>Team ".CConfig::SNC_SITE_NAME."<br/>Happy ".CConfig::SNC_SITE_NAME."ing !!!";
			
			$objMail->PrepAndSendDemoRequestAck($name, $email, $subject, $ack_messsage);
			//CEMail::PrepAndSendSubsPlanQuoteRequestAck($name, $email, $subject, $ack_messsage);
			
			$ajax_response = "Dear ".$name.", <br/><br/> Thanks for showing interest in ".CConfig::SNC_SITE_NAME.".com. Your request is being submitted to ".CConfig::SNC_SITE_NAME." sales team from IP Address ".$_SERVER['REMOTE_ADDR'].", our Executive will contact you soon.<br/><br/>You shall be receiving a copy of this request at mentioned Email...<br/><br/>You Matter,<br/>Team ".CConfig::SNC_SITE_NAME."<br/>Happy ".CConfig::SNC_SITE_NAME."ing !!!";
		
		}	
	}
	else if(isset($_POST['feedback_by_user']) && $_POST['feedback_by_user'] == 1)
	{
	
		$name			= ucfirst(clean($_POST['FEEDBACK_NAME']));
		$email			= clean($_POST['FEEDBACK_EMAIL']);
		$feedback		= clean($_POST['FEEDBACK_MESSAGE']);
	
		if ($captcha_value != trim($_POST['FEEDBACK_VERIF_CODE']))
		{
			$ajax_response = "<div style='color: red'>Dear User,<br/><br/> Your ".CConfig::SNC_SITE_NAME." feedback could not be received due to incorrect or empty captcha value, please re-fill the form.<BR/><BR/>-Team ".CConfig::SNC_SITE_NAME."</div>";
		}
		else
		{
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
	
			// Feedback
			$form_details .= "<tr>";
			$form_details .= "<td><b>Feedback:</b></td>";
			$form_details .= "<td>".$feedback."</td>";
			$form_details .= "</tr>";
				
			$form_details .= "</table>";
				
			$objDB = new CMcatDB();
			$objMail = new CEMail(CConfig::OEI_FREE, $objDB->GetPasswordFromOfficialEMail(CConfig::OEI_FREE));
			$objMail->PrepAndSendFreeUserFeedback($form_details);
	
			$ajax_response .= "Dear ".$name.",<br /><br />Thank you for spending time in filling feedback form, we appreciate your feedback and it is important for us to improve quality of our services. We will surly take appropriate action and will update you about it.";
			$ajax_response .= "<br /><br />Please keep posting to us and let us know how you feel about our services.";
			$ajax_response .= "<br /><br />You Matter,<br />Team EZeeAssess<br /><a href='".CSiteConfig::FREE_ROOT_URL."'>".CSiteConfig::FREE_ROOT_URL."</a>";
	
			$objMail->PrepAndSendFreeUserFeedbackAck($name, $email, $ajax_response);
		}
	}
	else if(isset($_POST['req_test']) && $_POST['req_test'] == 1)
	{
		
		/*$name			= clean($_POST['name']);
		$email			= clean($_POST['email']);
		$contact		= clean($_POST['contact']);
		$test_category	= clean($_POST['test_category']);
		
		$test_category_name = $test_category;
		if ($captcha_value != trim($_POST['verif_code']))
		{
			$ajax_response = "<div style='color: red'>Dear User,<br/><br/> Your ".CConfig::SNC_SITE_NAME." test request had been failed due to incorrect or empty captcha value, please re-fill the form.<BR/><BR/>-Team ".CConfig::SNC_SITE_NAME."</div>";
		}
		else 
		{
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
			
			if($test_category == "OTHER")
			{
				// Test Catagory
				$form_details .= "<tr>";
				$form_details .= "<td><b>Test Catagory:</b></td>";
				$form_details .= "<td>".clean($_POST['categoryname'])."</td>";
				$form_details .= "</tr>";
				
				// Test Pattern
				$form_details .= "<tr>";
				$form_details .= "<td><b>Test Pattern:</b></td>";
				$form_details .= "<td>".clean($_POST['test_pattern'])."</td>";
				$form_details .= "</tr>";
				
				$test_category_name = clean($_POST['categoryname']);
			}
			else 
			{
				// Test Catagory
				$form_details .= "<tr>";
				$form_details .= "<td><b>Test Catagory:</b></td>";
				$form_details .= "<td>".$test_category."</td>";
				$form_details .= "</tr>";
			}
			
			// Request IP Address
			$form_details .= "<tr>";
			$form_details .= "<td><b>IP Address:</b></td>";
			$form_details .= "<td>".$_SERVER['REMOTE_ADDR']."</td>";
			$form_details .= "</tr>";
			
			$form_details .= "</table>";
			
			$objDB = new CMcatDB();
			$objMail = new CEMail(CConfig::OEI_FREE, $objDB->GetPasswordFromOfficialEMail(CConfig::OEI_FREE));
			$objMail->PrepAndSendTestRequest($form_details);
			
			$ajax_response .= "Dear ".$name.",<br /><br />We are glad to receive a new test/assessment request from you, your request is an another good sign of community accepting our efforts of bringing help for candidates appearing in various exams. We will put ".$test_category_name." in our Roadmap and will update you as soon as it is available.";
			$ajax_response .= "<br /><br />Thank you for filling request form, please keep posting.";
			$ajax_response .= "<br /><br />You Matter,<br />Team EZeeAssess<br /><a href='".CSiteConfig::FREE_ROOT_URL."'>".CSiteConfig::FREE_ROOT_URL."</a>";
			
			$objMail->PrepAndSendTestRequestAck($name, $email, $ajax_response);
		}*/
	}
	CSessionManager::UnsetSessVar(CSessionManager::INT_CAPTCH_VALUE);
	echo ($ajax_response);
?>