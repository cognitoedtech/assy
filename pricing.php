<!doctype html>
<?php 
	include_once(dirname(__FILE__)."/lib/include_js_css.php");
	include_once(dirname(__FILE__)."/lib/session_manager.php");
	include_once(dirname(__FILE__)."/database/config.php");
	
	$objIncludeJsCSS = new IncludeJSCSS();

	$strCountryCode = "us";
?>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="Generator" content="Mastishka Intellisys Private Limited">
<meta name="Author" content="Mastishka Intellisys Private Limited">
<meta name="Keywords" content="">
<meta name="Description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo(CConfig::SNC_SITE_NAME);?> - Pricing</title>
<script src="//js.maxmind.com/js/apis/geoip2/v2.1/geoip2.js" type="text/javascript"></script>
<script>
	var strCountryCode = "us";
</script>

<?php 
$objIncludeJsCSS->CommonIncludeCSS("");
$objIncludeJsCSS->IncludeMipcatCSS("");
$objIncludeJsCSS->IncludeIconFontCSS("");
$objIncludeJsCSS->IncludePricingCSS("");
$objIncludeJsCSS->CommonIncludeJS("");
?>
</head>
<body>
	<?php 
	include_once(dirname(__FILE__)."/lib/header.php");
	$bShowCKEditor = FALSE;
	?>
	<!-- ********************************** -->
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-md-6">
				<div class="drop-shadow curved curved-vt-2"
					style="width: 91%; height: 500px; margin-top: 100px">
					<h3><i>Pay only when you are using our services!</i></h3><br/>
					<iframe width="100%" height="360" src="https://www.youtube.com/embed/HRbMDvo59g4?rel=0" frameborder="0" allowfullscreen></iframe>
				</div>
				<div style="width: 100%;"><b><i>Monthly plan will be billed every 30 days.</i></b></div>
			</div>
			<div class="col-xs-6 col-md-6">
				<div id="pricing-table" style="width: 100%;" class="clear">
					<div class="plan">
						<h3>
							Proprietary <span style="padding-top: 20px">&#x20B9;<?php echo(CConfig::SPR_BASIC_INR * CConfig::SPR_MINIMUM_TESTS);?> </span>
						</h3>
						<a class="signup" href="<?php echo CSiteConfig::ROOT_URL;?>/login/register-org.php?plan=basic">Sign up</a>
						<ul>
							<li class="alert-danger"><b>Credit Card not required for Sign-up</b></li>
							<li class="alert-warning"><b>Personal Questions Only</b></li>
							<li><b><?php echo(CConfig::SPR_MINIMUM_TESTS);?> tests for &#x20B9;<?php echo(CConfig::SPR_BASIC_INR * CConfig::SPR_MINIMUM_TESTS);?></b> Monthly Plan*</li>
							<li class="alert-info"><b>after 500 tests &#x20B9;<?php echo(CConfig::SPR_BASIC_INR)?></b> per test/user</li>
							<?php if(false){ ?>
							<li class="alert-info">OR <b>$1.00</b> per user monthly (unlimited)</li>
							<?php  } ?>
							<li></li>
							<li class="alert-danger">Candidate Management</li>
							<li class="text-success">Bulk Upload Candidate Information</li>
							<li class="text-success">Candidate Batches</li>
							<li class="text-success">Co-ordinator Management</li>
							<li class="alert-danger">Knowledge Base Management</li>
							<li class="text-success">Use Personal Questions</li>
							<li style="text-decoration:line-through">Use EZeeAssess's Practice Questions</li>						
							<li style="text-decoration:line-through">Use Authorized Publisher's Questions</li>
							<li class="alert-danger">Test Design &amp; Management</li>
							<li class="text-success">Design as per requirement</li>
							<!-- <li>Static Test Design</li> -->
							<li class="text-success">Dynamic Test Design</li>
							<li class="text-success">Test Failsafe Enablement</li>
							<li class="text-success">Cheating Prevention Algorithm</li>
							<li class="text-success">Active Test Monitoring </li>
							<li class="text-success">Result Visiblity Control</li>
							<li class="alert-danger">Test Scheduling</li>
							<li class="text-success">Time Zone Specific Test Scheduling</li>
							<li class="text-success">Test Rescheduling</li>
							<li class="text-success">Test Cancelation</li>
							<li class="alert-danger">Analytics</li>
							<li class="text-success">Result Analytics/Charts</li>
							<li class="text-success">Question Paper Inspection </li>
							<li class="text-success">Result Consolidation</li>
							<li class="alert-danger">Billing Management</li>
							<li class="text-success">Live Billing information</li>
							<li class="text-success">Online Account Recharge</li>
							<li class="alert-danger">Personalization</li>
							<li class="text-success">Personalized URL</li>
							<li class="text-success">Personalized LOGO</li>
						</ul>
					</div>
					<div class="plan" id="most-popular">
						<h3>
							Hybrid <span style="padding-top: 20px">&#x20B9;<?php echo(CConfig::SPR_PROFESSIONAL_INR * CConfig::SPR_MINIMUM_TESTS);?></span>
						</h3>
						<a class="signup" href="<?php echo CSiteConfig::ROOT_URL;?>/login/register-org.php?plan=professional">Sign up</a>
						<ul>
							<li class="alert-danger"><b>Credit Card not required for Sign-up</b></li>
							<li class="alert-warning"><b>Personal + Practice (from us) Questions Only</b></li>
							<li><b><?php echo(CConfig::SPR_MINIMUM_TESTS);?> tests for &#x20B9;<?php echo(CConfig::SPR_PROFESSIONAL_INR * CConfig::SPR_MINIMUM_TESTS);?></b> Monthly Plan*</li>
							<li class="alert-info"><b>after 500 tests &#x20B9;<?php echo(CConfig::SPR_PROFESSIONAL_INR)?></b> per test/user</li>
							<?php if(false){ ?>
							<li class="alert-info">OR <b>$1.70</b> per user monthly (unlimited)</li>
							<?php } ?>
							<li></li>
							<li class="alert-danger">Candidate Management</li>
							<li class="text-success">Bulk Upload Candidate Information</li>
							<li class="text-success">Candidate Batches </li>
							<li class="text-success">Co-ordinator Management</li>
							<li class="alert-danger">Knowledge Base Management</li>
							<li class="text-success">Use Personal Questions</li>
							<li class="text-success"><b>Use EZeeAssess's Practice Questions</b></li>						
							<li style="text-decoration:line-through">Use Authorized Publisher's Questions</li>
							<li class="alert-danger">Test Design &amp; Management</li>
							<li class="text-success">Design as per requirement</li>
							<!-- <li>Static Test Design</li> -->
							<li class="text-success">Dynamic Test Design</li>
							<li class="text-success">Test Failsafe Enablement</li>
							<li class="text-success">Cheating Prevention Algorithm</li>
							<li class="text-success">Active Test Monitoring</li>
							<li class="text-success">Result Visiblity Control</li>
							<li class="alert-danger">Test Scheduling</li>
							<li class="text-success">Time Zone Specific Test Scheduling</li>
							<li class="text-success">Test Rescheduling</li>
							<li class="text-success">Test Cancelation</li>
							<li class="alert-danger">Analytics</li>
							<li class="text-success">Result Analytics/Charts</li>
							<li class="text-success">Question Paper Inspection</li>
							<li class="text-success">Result Consolidation</li>
							<li class="alert-danger">Billing Management</li>
							<li class="text-success">Live Billing information</li>
							<li class="text-success">Online Account Recharge</li>
							<li class="alert-danger">Personalization</li>
							<li class="text-success">Personalized URL</li>
							<li class="text-success">Personalized LOGO</li>
						</ul>
					</div>
					<div class="plan">
						<h3>
							Pay per Use<span style="padding-top: 20px">&#x20B9;<?php echo(CConfig::SPR_PPU_INR * CConfig::SPR_MINIMUM_PPU_TESTS)?></span>
						</h3>
						<a class="signup" href="<?php echo CSiteConfig::ROOT_URL;?>/login/register-org.php?plan=ppu">Sign up</a>
						<ul>
							<li class="alert-info"><b>&#x20B9;<?php echo(CConfig::SPR_PPU_INR * 10)?> First time <u>free</u> recharge !</b></li>
							<li class="alert-danger"><b>Credit Card not required for Sign-up</b></li>
							<li class="alert-warning"><b>Personal Questions Only</b></li>
							<li><b><?php echo(CConfig::SPR_MINIMUM_PPU_TESTS);?> tests for &#x20B9;<?php echo(CConfig::SPR_PPU_INR * CConfig::SPR_MINIMUM_PPU_TESTS);?></b> Minimum Recharge</li>
							<li class="alert-info"><b>after 50 tests &#x20B9;<?php echo(CConfig::SPR_PPU_INR)?></b> per test/user</li>
							<?php if(false){ ?>
							<li class="alert-info">OR <b>$2.50</b> per user monthly (unlimited)</li>
							<?php } ?>
							<li></li>
							<li class="alert-danger">Candidate Management</li>
							<li class="text-success">Bulk Upload Candidate Information</li>
							<li class="text-success">Candidate Batches</li>
							<li class="text-success">Co-ordinator Management</li>
							<li class="alert-danger">Knowledge Base Management</li>
							<li class="text-success">Use Personal Questions</li>
							<li style="text-decoration:line-through">Use EZeeAssess's Practice Questions</li>						
							<li style="text-decoration:line-through">Use Authorized Publisher's Questions</li>
							<li class="alert-danger"><b>Test Design &amp; Management</b></li>
							<li class="text-success">Design as per requirement</li>
							<!-- <li>Static Test Design</li> -->
							<li class="text-success">Dynamic Test Design</li>
							<li class="text-success">Test Failsafe Enablement</li>
							<li class="text-success">Cheating Prevention Algorithm</li>
							<li class="text-success">Active Test Monitoring</li>
							<li class="text-success">Result Visiblity Control</li>
							<li class="alert-danger">Test Scheduling</li>
							<li class="text-success">Time Zone Specific Test Scheduling</li>
							<li class="text-success">Test Rescheduling</li>
							<li class="text-success">Test Cancelation</li>
							<li class="alert-danger">Analytics</li>
							<li class="text-success">Result Analytics/Charts</li>
							<li class="text-success">Question Paper Inspection</li>
							<li class="text-success">Result Consolidation</li>
							<li class="alert-danger">Billing Management</li>
							<li class="text-success">Live Billing information</li>
							<li class="text-success">Online Account Recharge</li>
							<li class="alert-danger">Personalization</li>
							<li class="text-success">Personalized URL</li>
							<li class="text-success">Personalized LOGO</li>
						</ul>
					</div>
					<?php 
					if (false)
					{
					?>
					<div class="plan">
						<h3>
							Enterprise<span style="padding-top: 20px">&#x20B9;<?php echo(CConfig::SPR_ENTERPRISE_INR * CConfig::SPR_MINIMUM_TESTS);?></span>
						</h3>
						<a class="signup" href="<?php echo CSiteConfig::ROOT_URL;?>/login/register-org.php?plan=enterprise">Sign up</a>
						<ul>
							<li class="alert-danger"><b>Credit Card not required for Sign-up</b></li>
							<li class="alert-warning"><b>Personal + Practice (from us) + Authorized (from publishers) Questions Only</b></li>
							<li><b><?php echo(CConfig::SPR_MINIMUM_TESTS);?> tests for &#x20B9;<?php echo(CConfig::SPR_ENTERPRISE_INR * CConfig::SPR_MINIMUM_TESTS);?></b> Monthly Plan*</li>
							<li class="alert-info"><b>after 500 tests &#x20B9;<?php echo(CConfig::SPR_ENTERPRISE_INR)?></b> per test/user</li>
							<?php if(false){ ?>
							<li class="alert-info">OR <b>$2.50</b> per user monthly (unlimited)</li>
							<?php } ?>
							<li></li>
							<li class="alert-danger">Candidate Management</li>
							<li class="text-success">Bulk Upload Candidate Information</li>
							<li class="text-success">Candidate Batches</li>
							<li class="text-success">Co-ordinator Management</li>
							<li class="alert-danger">Knowledge Base Management</li>
							<li class="text-success">Use Personal Questions</li>
							<li class="text-success">Use EZeeAssess's Practice Questions</li>						
							<li class="text-success"><b>Use Authorized Publisher's Questions</b></li>
							<li class="alert-danger"><b>Test Design &amp; Management</b></li>
							<li class="text-success">Design as per requirement</li>
							<!-- <li>Static Test Design</li> -->
							<li class="text-success">Dynamic Test Design</li>
							<li class="text-success">Test Failsafe Enablement</li>
							<li class="text-success">Cheating Prevention Algorithm</li>
							<li class="text-success">Active Test Monitoring</li>
							<li class="text-success">Result Visiblity Control</li>
							<li class="alert-danger">Test Scheduling</li>
							<li class="text-success">Time Zone Specific Test Scheduling</li>
							<li class="text-success">Test Rescheduling</li>
							<li class="text-success">Test Cancelation</li>
							<li class="alert-danger">Analytics</li>
							<li class="text-success">Result Analytics/Charts</li>
							<li class="text-success">Question Paper Inspection</li>
							<li class="text-success">Result Consolidation</li>
							<li class="alert-danger">Billing Management</li>
							<li class="text-success">Live Billing information</li>
							<li class="text-success">Online Account Recharge</li>
							<li class="alert-danger">Personalization</li>
							<li class="text-success">Personalized URL</li>
							<li class="text-success">Personalized LOGO</li>
						</ul>
					</div>
					<?php 
					}
					?>
				</div>
			</div>
		</div>
	</div>
	
	<script type="text/javascript">
		$(".icon-home").addClass("glyphicon");
		$(".icon-home").addClass("glyphicon-home");
	
		$(".icon-user").addClass("glyphicon");
		$(".icon-user").addClass("glyphicon-user");

		var redirect = (function () {
			var onSuccess = function (geoipResponse) {
				/* There's no guarantee that a successful response object
				 * has any particular property, so we need to code defensively. */
				if (!geoipResponse.country.iso_code) {
					strCountryCode = "us";
				}
		 
				/* ISO country codes are in upper case. */
				strCountryCode = geoipResponse.country.iso_code.toLowerCase();
			};
		 
			/* We don't really care what the error is, we'll send them
			 * to the default site. */
			var onError = function (error) {
				//redirectBrowser("world");
			};
		 
			return function () {
				geoip2.country( onSuccess, onError );
			};
		}());
	 
		redirect();
	</script>
</body>
</html>
