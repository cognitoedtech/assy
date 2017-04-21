<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	include_once(dirname(__FILE__)."/../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../lib/site_config.php");
	include_once(dirname(__FILE__)."/../../lib/utils.php");
	include_once(dirname(__FILE__)."/../../database/config.php");
	$page_id = CSiteConfig::HF_FAQ;
	$login = CSessionManager::Get(CSessionManager::BOOL_LOGIN);
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Terms of Contribution</title>
		<style>
			#overlay { position: fixed; left: 0px; top: 0px; width: 100%; height: 100%; z-index: 100; background-color:white;}
			a.anchor:link {color:GhostWhite;}    /* unvisited link */
			a.anchor:visited {color:GhostWhite;} /* visited link */
			a.anchor:hover {color:GhostWhite;}   /* mouse over link */
			a.anchor:active {color:GhostWhite;}  /* selected link */
			a:focus {outline: none;}
		</style>
		<link rel="stylesheet" type="text/css" href="../../css/mipcat.css" />
		<link rel="stylesheet" type="text/css" href="../../3rd_party/bootstrap/css/bootstrap.css" />
		<script type="text/javascript" src="../../js/jquery.js"></script>
		<script type="text/javascript" src="../../3rd_party/bootstrap/js/bootstrap.js"></script>
	</head>
	<body style="font: 75% 'Trebuchet MS', sans-serif; margin: 5px;">
		<!-- Header -->
		<?php
			include(dirname(__FILE__)."/../../lib/header.php");
		?>
		
		<div style="margin:10px">
			<h3 style="text-align:center;color:steelblue;">Terms of Contribution</h3><br/>
			<p>
			By using the <?php echo(CConfig::SNC_SITE_NAME);?>.com contributor feature, contributor agrees to comply with all of the terms and conditions hereof. The right to use the <?php echo(CConfig::SNC_SITE_NAME);?>.com is personal to contributor and is not transferable to any other person or entity. Contributor is responsible for all of the data/information uploaded to Contributor's Account (under any screen name or password) and for ensuring that all use of Contributor's Account complies fully with the provisions of this Agreement. Contributor(s) shall be responsible for protecting the confidentiality of thier password(s).<br/><br/>
		
			The Mastishka Intellisys Private Limited, parent company (owner) of the <?php echo(CConfig::SNC_SITE_NAME);?>.com shall have the right at any time to change or discontinue any aspect or feature of <?php echo(CConfig::SNC_SITE_NAME);?>.com, including, but not limited to, content, hours of availability payment module.<br/><br/>	
		
			The Mastishka Intellisys Pvt LTD shall have the right at any time to change or modify the terms and conditions applicable to Contributor's use of the <?php echo(CConfig::SNC_SITE_NAME);?>.com, or any part thereof, or to impose new conditions, including but not limited to, adding fees and charges for use.<br/><br/>
		
			Such changes, modifications, additions or deletions shall be effective immediately upon notice thereof, which may be given by means including but not limited to, posting on <?php echo(CConfig::SNC_SITE_NAME);?>.com, or by electronic or conventional mail, or by any other means by which Contributor obtains notice thereof. Any use of the <?php echo(CConfig::SNC_SITE_NAME);?>.com by Contributor after such notice shall be deemed to constitute acceptance by Contributor of such changes, modifications or additions.<br/><br/>
	
			Following are the Terms and conditions for contribution in <?php echo(CConfig::SNC_SITE_NAME);?>.com knowledgebase:
			
			<ol>
				<li>Uploading a question/test does not mean that you have earned point. The point will be reflected only when question/test is veryfied and accepted by <?php echo(CConfig::SNC_SITE_NAME);?> team.</li>
				<li>Our test model is dynamic so we won't take any responsiblity that your question will apear in any institute/corporate test. It may or may not.</li>
				<li>The <?php echo(CConfig::SNC_SITE_NAME);?> knowledge base is sole property of <?php echo(CConfig::SNC_SITE_NAME);?>.</li>
				<li>Accepting your Contribution is sole discretion of <?php echo(CConfig::SNC_SITE_NAME);?>.</li>
				<li>Tentetive time of questions varification is 30 days, how ever it may be increased as per actuall flow of questions per day.</li>
				<li>By opening contributor account you are accepting that you are going to contribute in <?php echo(CConfig::SNC_SITE_NAME);?> knowledge base, however, <?php echo(CConfig::SNC_SITE_NAME);?> knowledge base is sole property of <?php echo(CConfig::SNC_SITE_NAME);?>.</li>
				<li>After varification, if we found that your question already exist in the <?php echo(CConfig::SNC_SITE_NAME);?> database than it won't be accepted by <?php echo(CConfig::SNC_SITE_NAME);?>.com.</li>
				<li>Any disputes can be resolved under Indore's (Madhya Pradesh, India) juridiction only.</li>
			</p>
		</div>
	</body>
</html>