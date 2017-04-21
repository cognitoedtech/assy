<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	include_once(dirname(__FILE__)."/../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../lib/include_js_css.php");
	include_once(dirname(__FILE__)."/../lib/site_config.php");
	include_once(dirname(__FILE__)."/../lib/utils.php");
	include_once(dirname(__FILE__)."/../lib/user_manager.php");
	include_once(dirname(__FILE__)."/../lib/new-email.php");
	include_once(dirname(__FILE__)."/../database/config.php");
	include_once(dirname(__FILE__)."/../database/mcat_db.php");
	
    //$page_id = CSiteConfig::HF_FAQ;
	$login = CSessionManager::Get(CSessionManager::BOOL_LOGIN);
	
	$ip_addr = $_SERVER['REMOTE_ADDR'];
	
	$objIncludeJsCSS = new IncludeJSCSS();
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo(CConfig::SNC_SITE_NAME); ?>: Password Recovery</title>
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
		
		<br />
		<br />
		<div class="container">
			<?php
					$UserM = new  CUserManager();
				
					$email = $_POST['email'];
					$secans= $_POST['ans'];
					
					$objUser = $UserM->GetUserByEmail($email);
					
					$value = $UserM->GetFieldValueByEmail($email,CUser::FIELD_SECURITY_ANS);
				
					$comp_result = strcasecmp($secans, $value);
					$step = $comp_result == 0 ? 3 : 2;
			?>
			<h3 class="text-center" style="color:steelblue;">Password Recovery : Step - <?php echo($step);?>/3</h3><br/>
			<div class="drop-shadow raised">
				<?php
					if ($comp_result == 0)
					{
						$objDB = new CMcatDB();
						$objMail = new CEMail(CConfig::OEI_SUPPORT, $objDB->GetPasswordFromOfficialEMail(CConfig::OEI_SUPPORT));
						$objMail->PrepAndSendPasswordChangeMail($email, $objUser->GetFirstName()." ".$objUser->GetLastName(), $objUser->GetUserID(), $objUser->GetPassword(), $ip_addr);
						printf("Dear %s %s,<br/><br/>We have sent password retrieval link to your email address ( <u style='color:blue;'>%s</u> ). Please check the email and follow instructions to restore your login with %s.com.<br/><br/>Regards,<br/>%s Technical Support<br/><a href='%s'>www.%s.com</a><br/><b>%s</b><br/><br/>", $objUser->GetFirstName(),$objUser->GetLastName(), $email, CConfig::SNC_SITE_NAME, CConfig::SNC_SITE_NAME, CSiteConfig::ROOT_URL, strtolower(CConfig::SNC_SITE_NAME), CConfig::SNC_PUNCH_LINE);
					}
					else
					{	
						$objUser = $UserM->GetUserByEmail($email);
						printf("<div class='container'><div class='row'><form class='form-horizontal' method=post action='forgot_result.php'><p class='text-center' style='color:red;'>The answer you provided does not match with our records. Please re-enter the answer!</p><br/><div class='form-group'><label for='email' class='col-lg-4 col-md-4 col-sm-4 control-label'>E-Mail :</label><div class='col-lg-4 col-md-4 col-sm-4'><input class='form-control' value='%s' id='email' name='email' type='text' readonly/></div></div><div class='form-group'><label for='sec_ques' class='col-lg-4 col-md-4 col-sm-4 control-label'>Security Question : </label><div class='col-lg-4 col-md-4 col-sm-4'><span class='form-control' style='border: none;box-shadow: inset 0 0px 0px rgba(0, 0, 0, 0.075);'>%s</span></div></div><div class='form-group'><label for='ans' class='col-lg-4 col-md-4 col-sm-4 control-label'>Security Answer :</label><div class='col-lg-4 col-md-4 col-sm-4'><input class='form-control' id='ans' name='ans' type='text' /></div></div><div class='form-group'><div class='col-lg-10 col-md-10 col-sm-10  col-lg-offset-4 col-md-offset-4 col-sm-offset-4'><button type='submit' class='btn btn-primary'>Submit</button></div></div></form></div></div>",$email,$objUser->GetSecQues());
					}
				?>
			</div>
			<div class=' col-lg-offset-1 col-md-offset-1 col-sm-offset-1'>
				<?php
					include_once (dirname ( __FILE__ ) . "/../lib/footer.php");
				?>
			</div>
		</div>
	</body>
	
	<script type="text/javascript">
		function CheckSimilarity()
		{
			var pass=document.getElementById('password').value;
			var cpass=document.getElementById('cpassword').value;
			if(pass == cpass)
			{
				document.getElementById('submitbutton').disabled=false;
			}
			else
			{
				document.getElementById('submitbutton').disabled=true;
			}
		}
	</script>
</html>