<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	include_once("../lib/session_manager.php");
	include_once("../database/config.php");
	include_once("../lib/site_config.php");
	include_once("../lib/utils.php");
	
	$page_id = CSiteConfig::HF_PLANS;
	$login 	 = CSessionManager::Get(CSessionManager::BOOL_LOGIN);
	
	$user_type  = CSessionManager::Get(CSessionManager::INT_USER_TYPE);
	$user_email	= CSessionManager::Get(CSessionManager::STR_EMAIL_ID);
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Subscription Plans - MIpCAT</title>
		<link rel="stylesheet" type="text/css" href="../css/mipcat.css" />
		<link rel="stylesheet" type="text/css" href="../css/jquery-jvert-tabs-1.1.4.css" />
		<link rel="stylesheet" type="text/css" href="../3rd_party/bootstrap/css/bootstrap.css" />
		<link rel="stylesheet" type="text/css" href="../3rd_party/guiders/guiders-1.2.8.css" />
		<link rel="stylesheet" type="text/css" href="../css/glossymenu.css" />
		<link rel="stylesheet" type="text/css" href="../css/stats_box.css" />
		
		<script type="text/javascript" src="../js/jquery.js"></script>
		<script type="text/javascript" src="../3rd_party/bootstrap/js/bootstrap.js"></script>
		<script type="text/javascript" src="../js/jquery-jvert-tabs-1.1.4.js"></script>
		<script type="text/javascript" src="../3rd_party/guiders/guiders-1.2.8.js"></script>
		<script type="text/javascript" src="../js/ddaccordion.js"></script>
		<script type="text/javascript" charset="utf-8" src="../js/mipcat/utils.js"></script>
		<style>
			html::-webkit-scrollbar{
			    width:12px;
			    height:12px;
			    background-color:#fff;
			    box-shadow: inset 1px 1px 0 rgba(0,0,0,.1),inset -1px -1px 0 rgba(0,0,0,.07);
			}
			html::-webkit-scrollbar:hover{
			    background-color:#eee;
			}
			html::-webkit-resizer{
			    -webkit-border-radius:4px;
			    background-color:#666;
			}
			html::-webkit-scrollbar-thumb{
			    min-height:0.8em;
			    min-width:0.8em;
			    background-color: rgba(0, 0, 0, .2);
			    box-shadow: inset 1px 1px 0 rgba(0,0,0,.1),inset -1px -1px 0 rgba(0,0,0,.07);
			}
			html::-webkit-scrollbar-thumb:hover{
			    background-color: #bbb;
			}
			html::-webkit-scrollbar-thumb:active{
			    background-color:#888;
			}
		</style>
	</head>
	<body style="font: 75% 'Trebuchet MS', sans-serif; margin: 5px;">
		<!-- Header -->
		<?php
			include("../lib/header.php");
		?>
		<br/><br/><br/>
		<table style="font:inherit;" class="table table-striped table-hover table-bordered">
			<thead>
				<tr style="background-color:#80804C;color:GhostWhite;font: 150% 'Trebuchet MS', sans-serif;font-weight:bold">
					<td style="padding:15px;text-align:center;">Features<br/></td>
					<td>Corporate&nbsp;<input style="position:fixed;" class="btn btn-warning" onClick="location.href='register-org.php?sub=corp';" type="button" value="Corporate"/></td>
					<td>Institutional&nbsp;<input style="position:fixed;" class="btn btn-success" onClick="location.href='register-org.php?sub=inst';" type="button" value="Institute"/></td>
					<td>Individual&nbsp;<input style="position:fixed;" class="btn btn-danger" onClick="location.href='register-cand.php?plan=silver';" type="button" value="Individual"/></td>
				</tr>
				<tr style="background-color:#A35200;color:GhostWhite">
					<td><b>Minimum Recharge (Inclusive of All Taxes)</b></td>
					<td><b>India: Rs. <?php echo(CConfig::$INR_SUBSCRIPTION_PLANS[CConfig::UT_CORPORATE]["MINIMUM_RECHARGE"]);?>* (INR)</b><br/><b>Overseas: $ <?php echo(CConfig::$USD_SUBSCRIPTION_PLANS[CConfig::UT_CORPORATE]["MINIMUM_RECHARGE"]);?>* (USD)</b></td>
					<td><b>India: Rs. <?php echo(CConfig::$INR_SUBSCRIPTION_PLANS[CConfig::UT_INSTITUTE]["MINIMUM_RECHARGE"]);?>* (INR)</b><br/><b>Overseas: $ <?php echo(CConfig::$USD_SUBSCRIPTION_PLANS[CConfig::UT_INSTITUTE]["MINIMUM_RECHARGE"]);?>* (USD)</b></td>
					<td><b>Free</b></td>
				</tr><?php echo("");?>
				<tr style="background-color:#B26B00;color:GhostWhite">
					<td><b>Cost Per Test &frasl; Candidate (Personal Question Source)</b></td>
					<td><b>India: Rs. <?php echo(CConfig::$INR_SUBSCRIPTION_PLANS[CConfig::UT_CORPORATE]["RATE_PERSONAL_QUESTION"]);?>* (INR)</b><br/><b>Overseas: $ <?php echo(CConfig::$USD_SUBSCRIPTION_PLANS[CConfig::UT_CORPORATE]["RATE_PERSONAL_QUESTION"]);?>* (USD)</b></td>
					<td><b>India: Rs. <?php echo(CConfig::$INR_SUBSCRIPTION_PLANS[CConfig::UT_INSTITUTE]["RATE_PERSONAL_QUESTION"]);?>* (INR)</b><br/><b>Overseas: $ <?php echo(CConfig::$USD_SUBSCRIPTION_PLANS[CConfig::UT_INSTITUTE]["RATE_PERSONAL_QUESTION"]);?>* (USD)</b></td>
					<td><b>Not Applicable</b></td>
				</tr>
				<tr style="background-color:#D68533;color:GhostWhite">
					<td><b>Cost Per Test &frasl; Candidate (MIpCAT Question Source)</b></td>
					<td><b>India: Rs. <?php echo(CConfig::$INR_SUBSCRIPTION_PLANS[CConfig::UT_CORPORATE]["RATE_MIPCAT_QUESTION"]);?>* (INR)</b><br/><b>Overseas: $ <?php echo(CConfig::$USD_SUBSCRIPTION_PLANS[CConfig::UT_CORPORATE]["RATE_MIPCAT_QUESTION"]);?>* (USD)</b></td>
					<td><b>India: Rs. <?php echo(CConfig::$INR_SUBSCRIPTION_PLANS[CConfig::UT_INSTITUTE]["RATE_MIPCAT_QUESTION"]);?>* (INR)</b><br/><b>Overseas: $ <?php echo(CConfig::$USD_SUBSCRIPTION_PLANS[CConfig::UT_INSTITUTE]["RATE_MIPCAT_QUESTION"]);?>* (USD)</b></td>
					<td><b>Not Applicable</b></td>
				</tr>
				<tr style="background-color:#80804C;color:GhostWhite;font: 150% 'Trebuchet MS', sans-serif;font-weight:bold">
					<td style="padding:15px;text-align:center;">Features<br/></td>
					<td>Corporate&nbsp;<input style="position:fixed;font-weight:bold;" class="btn btn-warning" onClick="location.href='register-org.php?sub=corp';" type="button" value="Sign Up!"/></td>
					<td>Institutional&nbsp;<input style="position:fixed;font-weight:bold;" class="btn btn-success" onClick="location.href='register-org.php?sub=inst';" type="button" value="Sign Up!"/></td>
					<td>Individual&nbsp;<input style="position:fixed;font-weight:bold;" class="btn btn-danger" onClick="location.href='register-cand.php?plan=silver';" type="button" value="Sign Up!"/></td>
				</tr>
			</thead>
			<tbody>
				<tr style="color:blue">
					<td style="background-color:#E6E68A;"><b>Candidate  Management</b></td>
					<td style="background-color:#E6E68A;">Free</td>
					<td style="background-color:#E6E68A;">Free</td>
					<td style="background-color:#E6E68A;">Not Applicable</td>
				</tr>
				<tr>
					<td>Unlimited Number of Registered Candidates</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
				</tr>
				<tr>
					<td>Bulk Registration through Single Click Upload (Excel)</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
				</tr>
				<tr>
					<td>Registration via Emailing URL</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
				</tr>
				<tr>
					<td>View Registered Candidate Details</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
				</tr>
				<tr>
					<td>Unregister Candidates</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
				</tr>
				<tr>
					<td>Export details for all registered Candidates in PDF</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
				</tr>
				<tr>
					<td>Export details for all registered Candidates in CSV</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
				</tr>
				<tr style="color:blue">
					<td style="background-color:#E6E68A;"><b>Knowledge Base Management</b></td>
					<td style="background-color:#E6E68A;">Free</td>
					<td style="background-color:#E6E68A;">Free</td>
					<td style="background-color:#E6E68A;">Not Applicable</td>
				</tr>
				<tr>
					<td>Unlimited Subjects</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
				</tr>
				<tr>
					<td>Unlimited Topics</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
				</tr>
				<tr>
					<td>Unlimited Qustions</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
				</tr>
				<tr>
					<td>Single Question Entry form</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
				</tr><br>
				<tr>
					<td>Bulk Question Upload via Excel</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
				</tr>
				<tr>
					<td>Question Reconciling</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
				</tr>
				<tr>
					<td>Option to set the difficutly level of question</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
				</tr>
				<tr>
					<td>Option to explain answer of question</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
				</tr>
				<tr>
					<td>PDF Export for all uploaded questions</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
				</tr>
				<tr>
					<td>CSV Export for all uploaded questions</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
				</tr>
				<tr style="color:blue">
					<td style="background-color:#E6E68A;"><b>Test Design &amp; Management</b></td>
					<td style="background-color:#E6E68A;">Free</td>
					<td style="background-color:#E6E68A;">Free</td>
					<td style="background-color:#E6E68A;">Not Applicable</td>
				</tr>
				<tr>
					<td>Design and Save Unlimited Tests</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
				</tr>
				<tr>
					<td>Passing Criteria based on Min&frasl;Max Cut-Off </td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
				</tr>
				<tr>
					<td>Passing Criteria based on Topper Candidates</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
				</tr>
				<tr>
					<td>Question Source (MIpCAT &frasl; Personal) choice while Designing Test</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
				</tr>
				<tr>
					<td>Result Analytics Visibility Control for End User (Candidate)</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
				</tr>
				<tr>
					<td>M.C.P.A. ( Mastishka Cheating Prevention Algorithm) Based Tests</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
				</tr>
				<tr>
					<td>Flash Question (MCPA Parameter)</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
				</tr>
				<tr>
					<td>Lock Question (MCPA Parameter)</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
				</tr>
				<tr>
					<td>Test Expiration Options</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
				</tr>
				<tr>
					<td>Automatic Creation of Sections based on number of Section</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
				</tr>
				<tr>
					<td>Automatic Distrubution of Subjects within Sections</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
				</tr>
				<tr>
					<td>Unlimited Subject Limit in Test</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
				</tr>
				<tr>
					<td>Unlimited Topic Limit in Test</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
				</tr>
				<tr>
					<td>Detailed View of Designed Test</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
				</tr>
				<tr>
					<td>Question Selection Based On Difficulty Level</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
				</tr>
				<tr>
					<td>Attempt &frasl; Preview Test before Scheduling</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
				</tr>
				<tr style="color:blue">
					<td style="background-color:#E6E68A;"><b>Test Scheduling</b></td>
					<td style="background-color:#E6E68A;">Free</td>
					<td style="background-color:#E6E68A;">Free</td>
					<td style="background-color:#E6E68A;">Not Applicable</td>
				</tr>
				<tr>
					<td>Test Scheduling for Unlimited Candidates</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
				</tr>
				<tr>
					<td>Test Details Preview Before Scheduling</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
				</tr>
				<tr>
					<td>View Scheduled Test</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
				</tr>
				<tr>
					<td>View Status of All Scheduled Tests</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
				</tr>
				<tr style="color:blue">
					<td style="background-color:#E6E68A;"><b>Result Analytics</b></td>
					<td style="background-color:#E6E68A;">Free</td>
					<td style="background-color:#E6E68A;">Free</td>
					<td style="background-color:#E6E68A;">Free</td>
				</tr>
				<tr>
					<td>Brief Result Viewing of all Candidates</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
				</tr>
				<tr>
					<td>Test DNA Analysis</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td>Conditional</td>
				</tr>
				<tr>
					<td>Result Inspection</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td>Conditional</td>
				</tr>
				<tr style="color:blue">
					<td style="background-color:#E6E68A;"><b>Test Packages</b></td>
					<td style="background-color:#E6E68A;">Not Applicable</td>
					<td style="background-color:#E6E68A;">Available</td>
					<td style="background-color:#E6E68A;">Available</td>
				</tr>
				<tr>
					<td>Design &amp; Save Unlimited Number of Test Packages</td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
				</tr>
				<tr>
					<td>Unlimited Number of Tests per Package</td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
				</tr>
				<tr>
					<td>Sell &frasl; Trade Test Packages</td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
				</tr>
				<tr>
					<td>Candidate Registration For Individual Package</td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
				</tr>
				<tr>
					<td>Options to choose test package among 15, 30, 45, 60, 90 days</td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
				</tr>
				<tr>
					<td>15 Days Subscription Cost</td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
					<td><b>India:</b> Rs. <?php echo(CConfig::INR_RATE_15_DAYS);?>* (INR)<br/> <b>Overseas:</b> $ <?php echo(CConfig::USD_RATE_15_DAYS);?>* (USD)</td>
					<td>Price will be associated with<br/>Test Package</td>
				</tr>
				<tr>
					<td>30 Days Subscription Cost</td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
					<td><b>India:</b> Rs. <?php echo(CConfig::INR_RATE_30_DAYS);?>* (INR)<br/> <b>Overseas:</b> $ <?php echo(CConfig::USD_RATE_30_DAYS);?>* (USD)</td>
					<td>Price will be associated with<br/>Test Package</td>
				</tr>
				<tr>
					<td>45 Days Subscription Cost</td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
					<td><b>India:</b> Rs. <?php echo(CConfig::INR_RATE_45_DAYS);?>* (INR)<br/> <b>Overseas:</b> $ <?php echo(CConfig::USD_RATE_45_DAYS);?>* (USD)</td>
					<td>Price will be associated with<br/>Test Package</td>
				</tr>
				<tr>
					<td>60 Days Subscription Cost</td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
					<td><b>India:</b> Rs. <?php echo(CConfig::INR_RATE_60_DAYS);?>* (INR)<br/> <b>Overseas:</b> $ <?php echo(CConfig::USD_RATE_60_DAYS);?>* (USD)</td>
					<td>Price will be associated with<br/>Test Package</td>
				</tr>
				<tr>
					<td>90 Days Subscription Cost</td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
					<td><b>India:</b> Rs. <?php echo(CConfig::INR_RATE_90_DAYS);?>* (INR)<br/> <b>Overseas:</b> $ <?php echo(CConfig::USD_RATE_90_DAYS);?>* (USD)</td>
					<td>Price will be associated with<br/>Test Package</td>
				</tr>
				<tr style="color:blue">
					<td style="background-color:#E6E68A;"><b>Billing Management</b></td>
					<td style="background-color:#E6E68A;">Available</td>
					<td style="background-color:#E6E68A;">Available</td>
					<td style="background-color:#E6E68A;">Available</td>
				</tr>
				<tr>
					<td>Live Billing information In My Account</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
				</tr>
				<tr>
					<td>Online Account Recharge</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
				</tr>
				<tr style="color:blue">
					<td style="background-color:#E6E68A;"><b>Attempt Test and Test Packages</b></td>
					<td style="background-color:#E6E68A;">Not Applicable</td>
					<td style="background-color:#E6E68A;">Not Applicable</td>
					<td style="background-color:#E6E68A;">Available</td>
				</tr>
				<tr>
					<td>Attempt Available Tests (Free &amp; Scheduled)</td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
				</tr>
				<tr>
					<td>Attempt Available Test Packages (Free, Scheduled &amp; Paid)</td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
				</tr>
				<tr>
					<td>Resume Test (Session is Preserved)</td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
				</tr>
				<tr>
					<td>Fail-Safe (on Power Failuer or Web-Browser Crash)</td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
				</tr>
				<tr style="color:blue">
					<td style="background-color:#E6E68A;"><b>Personal Account Management</b></td>
					<td style="background-color:#E6E68A;">Available</td>
					<td style="background-color:#E6E68A;">Available</td>
					<td style="background-color:#E6E68A;">Available</td>
				</tr>
				<tr>
					<td>Manage Personal Details</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
				</tr>
				<tr>
					<td>Account Security (Password Update/Reset)</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
				</tr>
			</tbody>
			<thead>
				<tr style="background-color:#80804C;color:GhostWhite;font: 150% 'Trebuchet MS', sans-serif;font-weight:bold">
					<th style="text-align:center;">Features</th>
					<th>Corporate</th>
					<th>Institutional</th>
					<th>Individual</th>
				</tr>
			</thead>
		</table>
		<ul style="color:red;font-weight:bold">
			<li>Price mentioned is inclusive of all taxes.</li>
			<li>Corporate &frasl; Institutes whose registered offices are in India are requested to pay in INR (Indian Rupees).</li> 
			<li>Overseas Clients (other than India) are requested to pay in USD (US Dollars).</li>
			<li>We will not accept recharge amount in INR (currency) from clients other than India.</li>
		</ul><br/><br/>
	</body>
</html>