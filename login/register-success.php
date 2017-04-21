<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	// Send verification mail
	include_once(dirname(__FILE__)."/../lib/include_js_css.php");
	include_once("../lib/new-email.php");
	include_once("../lib/utils.php") ;
	include_once("../lib/site_config.php") ;
	include_once("../lib/session_manager.php");
	include_once("../database/mcat_db.php");
	
	$url = CUtils::curPageURL() ;
	$query_str = parse_url($url); // will return array of url components.
	parse_str($query_str["query"]) ; // the query string will be parsed here.
	
	$objDB = new CMcatDB();
	$secid = $objDB->MySQL_MD5(strtolower(urldecode($umail)));
	
	$subject = "Activate Account: ".CConfig::SNC_SITE_NAME."!" ;
	$body = sprintf("Dear User,<br/><br/>Please click on link <a href='%s/login/activate.php?secid=%s'> %s/login/activate.php?secid=%s</a> to activate your account on %s.<br/><br/>Regards,<br/>%s Tech Support", CSiteConfig::ROOT_URL, $secid, CSiteConfig::ROOT_URL, $secid, CSiteConfig::ROOT_URL, CConfig::SNC_SITE_NAME);
	
	$objIncludeJsCSS = new IncludeJSCSS();
	$objMail = new CEMail(CConfig::OEI_SUPPORT, $objDB->GetPasswordFromOfficialEMail(CConfig::OEI_SUPPORT));
	$objMail->Send($umail, $subject, $body);
?>

<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="Generator" content="Mastishka Intellisys Private Limited">
<meta name="Author" content="Mastishka Intellisys Private Limited">
<meta name="Keywords" content="">
<meta name="Description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo(CConfig::SNC_SITE_NAME);?>: Registration Pending</title>
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
			<h3 style="text-align:center;color:steelblue;">Registration Pending</h3><br/>
			<div class="drop-shadow raised">
				<P>Thank you for registering with <?php echo(CConfig::SNC_SITE_NAME);?>!<br/><br/> We have sent you an E-mail to activate your account. Please check your e-mail '<u style='color:blue;'><?php echo($umail); ?></u>' account and follow the instructions to activate your account.<br/><br/> <a href="<?php echo(CSiteConfig::ROOT_URL);?>">Click Here</a> to go to Login Page. <br/><br/><em><b>Happy <?php echo(CConfig::SNC_SITE_NAME);?>ing !!!</b></em></P>
			</div>
			<div class='col-lg-offset-1 col-md-offset-1 col-sm-offset-1'>
				<?php
					include_once (dirname ( __FILE__ ) . "/../lib/footer.php");
				?>
			</div>
		</div>
	</BODY>
</HTML>
