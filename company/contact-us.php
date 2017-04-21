<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	include_once(dirname(__FILE__)."/../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../lib/include_js_css.php");
	include_once(dirname(__FILE__)."/../lib/site_config.php");
	include_once(dirname(__FILE__)."/../lib/utils.php");
	$page_id = CSiteConfig::HF_FAQ;
	$login = CSessionManager::Get(CSessionManager::BOOL_LOGIN);
	
	$objIncludeJsCSS = new IncludeJSCSS();
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Contact Us</title>		
		<?php 
			$objIncludeJsCSS->CommonIncludeCSS("../");
			$objIncludeJsCSS->CommonIncludeJS("../");
		?>
	</head>
	<body>
		<!-- Header -->
		<?php
			include(dirname(__FILE__)."/../lib/header.php");
		?>
		<div class="container">
			<h3 style="text-align:center;color:steelblue;">Contact Us</h3><br/>
			<b style="color:blue">Corporate Office</b><br/><br/>
			<p>
				<b>Mastishka Intellisys Private Limited</b><br/>WB - 96, Scheme Number 94 <br/> Behind Bombay Hospital <br/> Ring Road, Indore - 452010 <br/><br/> <b>Email:</b> <a href="mailto:<?php echo(CConfig::OEI_SUPPORT);?>"><?php echo(CConfig::OEI_SUPPORT);?></a><br/><br/>
				<b>Phone:</b> [ +91 982 660 0457 ]<br/><br/>
			</p>
			<hr />
			<b style="color:blue">Sales Office</b><br/><br/>
			<p>
				<b>South India:</b><br/>Hitechcity Level 2,<br />Oval Building ,ilabs centre,<br /> Madhapur ,Hyderabad - 500081, India<br /><br /> 
				<b>Phone:</b> [ +91 40 4433 4201 ]<br/><br/>
			</p>
			<b> We are assigning <b>Business Associate</b> on city basis in India, for <b>Business Associate</b> enquiry please send us your proposal at <a href="mailto:<?php echo(CConfig::OEI_BUSI_ASSOC);?>"><?php echo(CConfig::OEI_BUSI_ASSOC);?></a></b>
			<div class="col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
			<?php
			include ("../lib/footer.php");
			?>
			</div>
		</div>
		<script type="text/javascript">
			$(".icon-home").addClass("glyphicon");
			$(".icon-home").addClass("glyphicon-home");
		
			$(".icon-user").addClass("glyphicon");
			$(".icon-user").addClass("glyphicon-user");
		</script>
	</body>
</html>