<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	include_once(dirname(__FILE__)."/../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../lib/site_config.php");
	include_once(dirname(__FILE__)."/../../lib/utils.php");
	include_once(dirname(__FILE__)."/../../database/config.php");
	$page_id =CSiteConfig::HF_FAQ;
	$login = CSessionManager::Get(CSessionManager::BOOL_LOGIN);
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Contribution : Frequently Asked Questions</title>
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
		
		<div style="margin:40px;margin-right:40px;font: 120% 'Trebuchet MS', sans-serif;">
			<h3 style="text-align:center;color:SteelBlue;">Contribution : Frequently Asked Questions</h3><br/>
			<p>
				<b style="color:steelblue;">Qus 01: Why should I contribute to <?php echo(CConfig::SNC_SITE_NAME);?>?</b><br/><br/>
				<b>Ans:</b> <?php echo(CConfig::SNC_SITE_NAME);?> has launched <b>Contribute to Earn!</b> model for Contributors who want to earn money by contributing to our knowlegde base.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 02: How many points will I earn by contributing questions?</b><br/><br/>
				<b>Ans:</b> You will earn 1 (one) point for contribution of each question accepted by <?php echo(CConfig::SNC_SITE_NAME);?>.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 03: Shall I be able to earn points if my contributed questions will appear in any test?</b><br/><br/>
				<b>Ans:</b> You will earn 1 (one) point if your question will appear 40 (forty) times in any paid test.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 04: I can see that I can design and contribute <b>tests</b> also, how can I earn points through that?</b><br/><br/>
				<b>Ans:</b> With <?php echo(CConfig::SNC_SITE_NAME);?>.com Institutes and Corporates, both can use your tests. You will earn 10 (ten) points if the test you designed will be taken by institutes. You will earn 25 (twenty five) points if the test you designed will be taken by corporate.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 05: How many points are equal to Rs. 1 /- (INR one) ?</b><br/><br/>
				<b>Ans:</b> For every 5 points, you will get Rs. 1 /- (INR one).
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 06: Is there any minimum number of points I need to collect before encashing?</b><br/><br/>
				<b>Ans:</b> Yes, you need asteelbluest 5,000 (five thousand) points in order to encash it.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 07: How can I encash my points?</b><br/><br/>
				<b>Ans:</b> In order to encash your points - goto <b>En-cash Points</b> navigation tab, you are required to provide your PAN (Personal Account Number from Income Tax Department) number for the first time.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 08: Is there any TAX liablility for money earned from <?php echo(CConfig::SNC_SITE_NAME);?> on me?</b><br/><br/>
				<b>Ans:</b> There will be deduction of taxes (as per government rules) on the money you earn from contribution to <?php echo(CConfig::SNC_SITE_NAME);?>.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 09: How will I get my money?</b><br/><br/>
				<b>Ans:</b> You can choose an option to have money via pay cheque or through online transffer (subject to availablity). In case of online transaffer, you are required to provide your bank details.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 10: Is there any varification process for my contribution ?</b><br/><br/>
				<b>Ans:</b> Yes, every question you contribute or every test you design, under go a verificatio process. It may take upto 30 days of time after you upload question.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 11: Can I upload single question one time instead of preparing csv sheet ?</b><br/><br/>
				<b>Ans:</b> Yes, you can! However varification of your questions will be started only when you have submitted atleast 100 questions. We won't varify single question after every submit.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 12: Can I submit my next set of question even though my previous one does not get varified?</b><br/><br/>
				<b>Ans:</b> Yes, you can! Submission does not depend upon previous varification.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 13: Is there any chance that during verification process my submitted question or test get rejected?</b><br/><br/>
				<b>Ans:</b> Yes.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 14: Will I be able to get reason for rejection?</b><br/><br/>
				<b>Ans:</b> Yes, we will provide you brief reason.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 15: Will I be able to appeal if I my submitted questions get rejected?</b><br/><br/>
				<b>Ans:</b> No - once rejected, no appeal will be entertained.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 16: Is there any refferal or MLM (Multi-Level-Marketing) system in <?php echo(CConfig::SNC_SITE_NAME);?> to earn points ?</b><br/><br/>
				<b>Ans:</b> No, we don't have this sort of system.
			</p><hr style="width:25%;"/>
		</div>
	</body>
</html>