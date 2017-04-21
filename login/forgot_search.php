<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	include_once(dirname(__FILE__)."/../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../lib/include_js_css.php");
	include_once(dirname(__FILE__)."/../lib/site_config.php");
	include_once(dirname(__FILE__)."/../lib/utils.php");
	include_once(dirname(__FILE__)."/../database/config.php") ;
	include_once(dirname(__FILE__)."/../lib/user_manager.php") ;
	include_once(dirname(__FILE__)."/../lib/new-email.php");
	include_once(dirname(__FILE__)."/../database/mcat_db.php");
	
	//$page_id = CSiteConfig::HF_FAQ;
	$login = CSessionManager::Get(CSessionManager::BOOL_LOGIN);
	
	$email = $_POST['email'];
	$email=	rtrim(strtolower($email));
	
	CSessionManager::Set(CSessionManager::STR_EMAIL_ID,$email) ;
	
	$objUM = new CUserManager() ;
	$sec_ques = $objUM->GetFieldValueByEmail($email, CUser::FIELD_SECURITY_QUES);

	$str_msg = "" ;
	
	if(array_search($email, CConfig::$reserved_emails) !== FALSE)
	{
		// Don't proceed.
		$str_msg = sprintf("<br/>Nice Try Champ!<br/><br/>The Email-ID (<u style='color:blue'>%s</u>) you provided is for demo login purpose only. This Email-ID is reserved for demo tour of %s.com and you are not allowed to play with its password. Happy Exploring!<br/><br/>Regards,<br/>%s Technical Support<br/><a href='%s'>www.%s.com</a><br/><b>Emperical natural selection happens here!</b><br/><br/>",$email, CConfig::SNC_SITE_NAME, CConfig::SNC_SITE_NAME, CSiteConfig::ROOT_URL, strtolower(CConfig::SNC_SITE_NAME));
	}
	else if ($sec_ques == -1)
	{
		$str_msg = sprintf("<br/>E-mail id  You porvide %s is not match with our records <br/> please register yourself. Please go through the following link <a href=\"register.php\">Register with %s</a> to register with us.",$email, CConfig::SNC_SITE_NAME);
	}
	else
	{
		$objDB = new CMcatDB();
		$objUser = $objUM->GetUserByEmail($email);
		$ip_addr = $_SERVER['REMOTE_ADDR'];
		$objMail = new CEMail(CConfig::OEI_SUPPORT, $objDB->GetPasswordFromOfficialEMail(CConfig::OEI_SUPPORT));
		$objMail->PrepAndSendPasswordChangeMail($email, $objUser->GetFirstName()." ".$objUser->GetLastName(), $objUser->GetUserID(), $objUser->GetPassword(), $ip_addr);
		$str_msg = sprintf("Dear %s %s,<br/><br/>We have sent password retrieval link to your email address ( <u style='color:blue;'>%s</u> ). Please check the email and follow instructions to restore your login with %s.com.<br/><br/>Regards,<br/>%s Technical Support<br/><a href='%s'>www.%s.com</a><br/><b>%s</b><br/><br/>", $objUser->GetFirstName(),$objUser->GetLastName(), $email, CConfig::SNC_SITE_NAME, CConfig::SNC_SITE_NAME, CSiteConfig::ROOT_URL, strtolower(CConfig::SNC_SITE_NAME), CConfig::SNC_PUNCH_LINE);
		//$str_msg = sprintf("<div class='container'><div class='row'><form class='form-horizontal' method=post action='forgot_result.php'><p class='text-center'>Please answer following security question to recover your password!</p><br/><div class='form-group'><label for='email' class='col-lg-4 col-md-4 col-sm-4 control-label'>E-Mail :</label><div class='col-lg-4 col-md-4 col-sm-4'><input class='form-control' value='%s' id='email' name='email' type='text' readonly/></div></div><div class='form-group'><label for='sec_ques' class='col-lg-4 col-md-4 col-sm-4 control-label'>Security Question : </label><div class='col-lg-4 col-md-4 col-sm-4'><span class='form-control' style='border: none;box-shadow: inset 0 0px 0px rgba(0, 0, 0, 0.075);'>%s</span></div></div><div class='form-group'><label for='ans' class='col-lg-4 col-md-4 col-sm-4 control-label'>Security Answer :</label><div class='col-lg-4 col-md-4 col-sm-4'><input class='form-control' id='ans' name='ans' type='text' /></div></div><div class='form-group'><div class='col-lg-10 col-md-10 col-sm-10 col-lg-offset-4 col-md-offset-4 col-sm-offset-4'><button type='submit' class='btn btn-primary'>Submit</button></div></div></form></div></div>",$email, $sec_ques);
	}
	
	$objIncludeJsCSS = new IncludeJSCSS();
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo(CConfig::SNC_SITE_NAME);?>: Password Recovery</title>
		<style>
			a.anchor:link {color:GhostWhite;}    /* unvisited link */
			a.anchor:visited {color:GhostWhite;} /* visited link */
			a.anchor:hover {color:GhostWhite;}   /* mouse over link */
			a.anchor:active {color:GhostWhite;}  /* selected link */
			a:focus {outline: none;}
		</style>
		<?php 
			$objIncludeJsCSS->CommonIncludeCSS("../");
			$objIncludeJsCSS->IncludeMipcatCSS("../");
			$objIncludeJsCSS->CommonIncludeJS("../");
		?>
	</head>
	<body>
		<!-- Header -->
		<?php
			include(dirname(__FILE__)."/../lib/header.php");
		?>
		<br /><br />
		<div class="container">
			<h3 style="text-align:center;color:steelblue;">Password Recovery : Step - 2/2</h3><br/>
			<div class="drop-shadow raised">
				<?php
					echo($str_msg) ;
				?>
			</div>
			<div class=' col-lg-offset-1 col-md-offset-1 col-sm-offset-1'>
				<?php
					include_once (dirname ( __FILE__ ) . "/../lib/footer.php");
				?>
			</div>
		</div>
	</body>
</html>
	
