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
		<title>Business Associate : Frequently Asked Questions</title>
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
		
		<div style="margin-left:40px;margin-right:40px;font: 120% 'Trebuchet MS', sans-serif;">
			<h3 style="text-align:center;color:steelblue;">Business Associate : Frequently Asked Questions</h3><br/>
			<p>
				<b style="color:steelblue;">Qus 01: What services does <?php echo(CConfig::SNC_SITE_NAME);?>.com provide?</b><br/><br/>
				<b>Ans:</b> <?php echo(CConfig::SNC_SITE_NAME);?>.com is unique in terms of features and services it provides for smart assessment of candidate. It&rsquo;s a tool to conduct online assessment tests and generate detailed &lsquo;Test DNA Analysis&rsquo; report for candidates. The unique phenomenon about <?php echo(CConfig::SNC_SITE_NAME);?> is that it is too generic, and applicable almost in all the areas where &lsquo;Students&rsquo; or &lsquo;Job Applicants&rsquo; are required to be accessed based on objective type test. It&rsquo;s growth prospects are unlimited.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 02: What is your business model?</b><br/><br/>
				<b>Ans:</b> Our business model is based on pre-paid recharge system.  For both institutes and corporate users, we provide subscription plans (Please look at our plan details). On every successful test conduction we charge/deduct fixed amount (depending upon subscription plan) from user&rsquo;s account. 
			</p><hr style="width:25%;"/>
<p>
				<b style="color:steelblue;">Qus 03: What are the benefits involves in being a Business Associate of <?php echo(CConfig::SNC_SITE_NAME);?>.com?</b><br/><br/>
				<b>Ans:</b> We believe in &ldquo;Grow and let Grow&rdquo; philosophy of business. We understand that without proper association and revenue sharing, business can&rsquo;t grow at the pace it should be. So, for our <b>Business Associates</b> we provide 20% of the revenue as premium for new clientele through them. However, it is not limited to getting new client only. If the client regitered through &lsquo;Business Associate&rsquo; keep continue using our product and recharges for new balance, 10% of every recharge goes to &lsquo;Business Associate&rsquo;. Thus &lsquo;Business Associate&rsquo; will get recurring income from the clientele it regiseterd (This percentage is from pricing plan of opt-in services and exclusive of taxes).

			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 04: What if client registered through my efforts, recharges amount directly using your online payment services?</b><br/><br/>
				<b>Ans:</b> We offer fair business practices. Even if client recharges using our online payment methods, we will still share 10% of revenue on rechage with you - as client is channeled through your efforts.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 05: What if clientele from my nearby region directly contacts you and registers themselves using <?php echo(CConfig::SNC_SITE_NAME);?>.com?</b><br/><br/>
				<b>Ans:</b> In that case those clients are termed as our direct clients, although we may re-assign that clientele to you and you may get 10% revenue on their recurring recharges. Reassigning direct clientele to any &lsquo;Business Associate&rsquo; will depend upon their performance in a particular region. However, <?php echo(CConfig::SNC_SITE_NAME);?> holds complete authority on reassigning direct clients, we will be using our discretionary power which will be final and no further appeal will be entertained.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 06: Will you be having multiple &lsquo;Business Associate&rsquo; in same region?</b><br/><br/>
				<b>Ans:</b> It all depends upon your performance, if you are performing well and doing good business than we won&rsquo;t prefer creating unnassary competition. Although, having or not - multiple &lsquo;Business Associate&rsquo; in same region, falls under strategic business decision that&rsquo;s why <?php echo(CConfig::SNC_SITE_NAME);?> holds complete authority of having multiple &lsquo;Business Associate&rsquo; in same region.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 07: Who are the potential clients of your <?php echo(CConfig::SNC_SITE_NAME);?>.com?</b><br/><br/>
				<b>Ans:</b> The product is generic in nature, so organizations which requires analyzing and accessing individual with the help of objective tests can be our potential client.  So our Institutional &lsquo;Clients&rsquo;/&lsquo;End Users&rsquo; can be schools, coaching institutes, training institutes, colleges who want to assess students. <br/><br/> On the other hand corporate, who need to analyze fresher using campus tests or existing employees for their knowledge updates. HR/Job Consultants and Placement Agencies can also use our services for initial screening of candidates.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 08: Do you provide saparate login for &lsquo;Business Associate&rsquo; to monitor their client activities?</b><br/><br/>
				<b>Ans:</b> Yes, after signing up the &lsquo;Business Associate&rsquo; agreement, we will provide you your separate login. You can then view client list which are registered through your efforts, also their billing and contact details.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 09: Do I need to pay service tax on my earning through <?php echo(CConfig::SNC_SITE_NAME);?>.com?</b><br/><br/>
				<b>Ans:</b> No, our subscription plans are exclusive of service tax, so clients themself will pay tax based on plan. You need to collect and forward amount prescribed through our plans + 12.36% service tax. We will take care of tax details (in the form of Demand Draft or Pay Cheque). However the income you earn from us as your commission, will be under tax category and we will cut TDS as per government norms. We will issue you TDS certificate on deducted amount (TDS).
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 10: Once I become <?php echo(CConfig::SNC_SITE_NAME);?>&rsquo;s &lsquo;Business Associate&rsquo; then will you provide product training to us?</b><br/><br/>
				<b>Ans:</b> Yes, we will provide you proper training so that you can understand and market the product. Apart from that, we will provide you help documents as well.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 11: Will you provide us assistance if our client needs more clarification on technical/usage part?</b><br/><br/>
				<b>Ans:</b> Yes, we will support you always in that case. Any help in this regard will be entertained in short amount of time.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 12: How many payment modes you support?</b><br/><br/>
				<b>Ans:</b> We support payment using Demand Draft, Pay Cheque, Online transfer (Net Banking, Credit/Debit Cards) and also through NEFT/RTGS transfer to our account.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 13: Does <?php echo(CConfig::SNC_SITE_NAME);?>.com supports International Clients as well?</b><br/><br/>
				<b>Ans:</b> Yes, <?php echo(CConfig::SNC_SITE_NAME);?>.com is a product of International Level and we do support International Clients as well. The payment collection method for International Clients will be (proposed) using Paypal.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 14: What is the exit/termination scenario for existing &lsquo;Business Associate&rsquo;?</b><br/><br/>
				<b>Ans:</b> We believe in long term relationship, but if in any case you will terminate the association then all of your clients will become our direct clients. You will then loose 10% revenue (after your termination) from recurring recharge of your clients. The termination will be executed as per &lsquo;Termination/Exit Clause&rsquo; mentioned in &lsquo;Business Associate&rsquo; agreement.
			</p><hr style="width:25%;"/>
		</div>
	</body>
</html>