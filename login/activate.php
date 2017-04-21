 <?php
 	include_once(dirname(__FILE__)."/../lib/include_js_css.php");
	include_once("../database/config.php");
	include_once("../database/mcat_db.php");
	include_once("../lib/user_manager.php");
	include_once("../lib/new-email.php");
	include_once("../lib/utils.php") ;
	include_once("../lib/site_config.php") ;
	include_once("../lib/session_manager.php");

	
	$url = CUtils::curPageURL() ;
	//echo($url."<br/>");
	$query_str = parse_url($url); // will return array of url components.
	/*echo("<pre>");
	print_r($query_str);
	echo("</pre>");*/
	parse_str($query_str["query"]) ; // the query string will be parsed here.
	//echo("secid: ".$secid);
	
	$objUM	= new CUserManager();
	$result_id = $objUM->ActivateAccount($secid);
	$bResult = false;
	
	if($result_id) 
	{
		$bResult = true;
		$objUser = $objUM->GetUserByID($result_id);

		// Send welcome mail.
		$subject = "You can get quality assessment with ".CConfig::SNC_SITE_NAME."!" ;
		$body = "Dear ".$objUser->GetFirstName()." ".$objUser->GetLastName().",<br/><br/> ".CConfig::SNC_SITE_NAME." welcomes Corporate, Institutes and Individuals to assess or get assessed from one of it's kind <A class='anchor' href=\"http://www.".strtolower(CConfig::SNC_SITE_NAME).".com\"><span class='boldfont'>Candidate Ability Test</span></A>.<br/><br/>You Matter,<br/>Team ".CConfig::SNC_SITE_NAME;
		//$from = "support@mipcat.com";
		$result_email=$objUser->GetEmail();
		
		$objDB = new CMcatDB();
		$objMail = new CEMail(CConfig::OEI_SUPPORT, $objDB->GetPasswordFromOfficialEMail(CConfig::OEI_SUPPORT));
		$objMail->Send($result_email, $subject, $body);
	}
	$objIncludeJsCSS = new IncludeJSCSS();
?>

<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="Generator" content="Mastishka Intellisys Private Limited">
<meta name="Author" content="Mastishka Intellisys Private Limited">
<meta name="Keywords" content="">
<meta name="Description" content="">
<title><?php echo(CConfig::SNC_SITE_NAME);?> - Activation</title>
<?php 
	$objIncludeJsCSS->CommonIncludeCSS("../");
	$objIncludeJsCSS->IncludeMipcatCSS("../");
	$objIncludeJsCSS->IncludeIconFontCSS("../");
	$objIncludeJsCSS->CommonIncludeJS("../");
?>
</head>
	<BODY>
		<!-- Header -->
		<?php
			include("../lib/header.php");
		?>
		<br /><br /><br />
		<div class="container">
			<h3 style="text-align:center;color:steelblue;">Activation Status</h3><br/>
			<div class="drop-shadow raised">
				<?php
					if($bResult)
					{
						echo("<P>Thank you for registering with ".CConfig::SNC_SITE_NAME."! Your account has been activated. You may now <A class='anchor' HREF=\"../index.php\">login</A> to your account.</P>") ;
					}
					else 
					{
						echo("<P>Activation Failed: Sorry, your account can not be activated. Please make sure you have not used dummy email-id for registration and copied correct hyperlink in the address bar.</P>") ;
					}
				?>
			</div>
			<div class='col-lg-offset-1'>
				<?php
					include_once (dirname ( __FILE__ ) . "/../lib/footer.php");
				?>
			</div>
		</div>
	</BODY>
</HTML>