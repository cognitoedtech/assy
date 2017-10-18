<!doctype html>
<?php
include_once (dirname ( __FILE__ ) . "/lib/include_js_css.php");
include_once (dirname ( __FILE__ ) . "/lib/session_manager.php");
include_once (dirname ( __FILE__ ) . "/lib/site_config.php");
include_once (dirname ( __FILE__ ) . "/lib/utils.php");
include_once (dirname ( __FILE__ ) . "/database/config.php");
include_once (dirname ( __FILE__ ) . "/3rd_party/recaptcha/recaptchalib.php");

// Redirect to signin.php, if it's a DNS Redirect (i.e. client url)
if(strcasecmp($_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST'], CSiteConfig::STICKY_URL) != 0)
{
	CUtils::Redirect("signin.php");
}

$page_id = CSiteConfig::HF_INDEX_ID;
$login = CSessionManager::Get ( CSessionManager::BOOL_LOGIN );

$parsAry = parse_url ( CUtils::curPageURL () );
$qry = split ( "[=&]", $parsAry ["query"] );

/*
 * if($login) { CUtils::Redirect("core/dashboard.php"); } else
 * if(CSiteConfig::DEBUG_SITE == true && stristr($parsAry["host"],
 * strtolower(CConfig::SNC_SITE_NAME).".com") == FALSE) { if($qry[0] != "debug"
 * && $qry[1] != "639") { CUtils::Redirect(CSiteConfig::ROOT_URL, true); } }
 */

$login_name = $_GET ['ln'];
if (! empty ( $login_name )) {
	CSessionManager::Set ( CSessionManager::STR_LOGIN_NAME, $login_name );
} else if (! $login) {
	CSessionManager::UnsetSessVar ( CSessionManager::STR_LOGIN_NAME );
}

$objIncludeJsCSS = new IncludeJSCSS ();
?>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="Generator" content="Mastishka Intellisys Private Limited">
<meta name="Author" content="Mastishka Intellisys Private Limited">
<meta name="Keywords" content="">
<meta name="Description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo(CConfig::SNC_SITE_NAME." - ".CConfig::SNC_PUNCH_LINE);?></title>
<?php
$objIncludeJsCSS->CommonIncludeCSS ( "" );
$objIncludeJsCSS->IncludeTVCSS ( "" );
$objIncludeJsCSS->IncludeMipcatCSS ( "" );
$objIncludeJsCSS->IncludeIconFontCSS( "" );
$objIncludeJsCSS->CommonIncludeJS ( "" );
?>
</head>
<body>

	<?php
	include_once (dirname ( __FILE__ ) . "/lib/header.php");
	$bShowCKEditor = FALSE;
	?>
	<br />
	<br />
	<br />
	<br />
	<!-- ************************* -->
	<div class="container">
		<div class="row fluid">
			<div class="col-md-4">
				<div style="margin-top: 25px;">
					<div class="drop-shadow lifted">
						<div class="row">
							<div class="col-md-offset-1">
								<img src="images/clientele-sm.jpg" alt="" />
							</div>
						</div>
						<div class="row" style="margin-right: 20px;">
							<div class="col-md-offset-1 text-justify">
								<h1>&ldquo;</h1>
								<div class="well text-info">
									EZeeAssess&rsquo;s solution have broad horizon of acceptance, ranging
									from IT companies, covering Educational &amp; Training
									institutes to Universities and Professional Examination Board.<br />
									<br /> <a href="login/register-org.php" class="text-danger">Register now for free!</a>
									and explore features which are worth having in day to day
									business affairs.
								</div>
								<h1 class="text-right">&rdquo;</h1>
								<!-- <span class="badge alert-warning"><i
							class="glyphicon glyphicon-th"></i></span>&nbsp; I.T/Software
						Companies <br /> <br /> <span class="badge alert-danger"><i
							class="glyphicon glyphicon-globe"></i></span>&nbsp; Universities
						/ Education Boards <br /> <br /> <span class="badge alert-warning"><i
							class="glyphicon glyphicon-certificate"></i></span>&nbsp;
						Colleges / Education Institutes<br /> <br /> <span
							class="badge alert-danger"><i class="glyphicon glyphicon-book"></i></span>&nbsp;
						Learning Centers<br /> <br /> <span class="badge alert-warning"><i
							class="glyphicon glyphicon-briefcase"></i></span>&nbsp; Training
						Institutes<br /> <br /> <span class="badge alert-danger"><i
							class="glyphicon glyphicon-tower"></i></span>&nbsp; Civil
						Services Entrance<br /> <br /> <span class="badge alert-warning"><i
							class="glyphicon glyphicon-plane"></i></span>&nbsp; Engineering
						Entrance Examination Board<br /> <br /> <span
							class="badge alert-danger"><i class="glyphicon glyphicon-usd"></i></span>&nbsp;
						Banking Entrance Examination Board<br /> <br /> <span
							class="badge alert-warning"><i class="glyphicon glyphicon-phone"></i></span>&nbsp;
						Management Entrance Examination Board<br /> <br /> -->
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-8">
				<div id="TVcontainer">
					<div class="outerEdge">
						<div class="outerBox">
							<div class="TVscreen">
								<div id="theVideo" class="text-center">
									<iframe width="100%" height="100%"
										src="//www.youtube.com/embed/c7bGwsT1e0E?rel=0"
										frameborder="0" allowfullscreen></iframe>
								</div>
							</div>
							<h1 class="TVname">EZEEASSESS</h1>
						</div>
					</div>
					<div class="glare"></div>
					<div class="post"></div>
					<div class="base"></div>
				</div>
			</div>
		</div>
	</div>
	<!-- ************************* -->
	<div class="container">
		<div class="panel panel-warning">
			<div class="panel-heading">
				<h3 class="panel-title">Solutions Offered</h3>
			</div>
			<div class="panel-body">
				<div class="metro">
					<div class="tab-control" data-effect="fade" data-role="tab-control">
						<ul class="tabs">
							<li class="active"><a href="#_page_1">Fresher Recruitments</a></li>
							<li><a href="#_page_2">Training Assessment</a></li>
							<li><a href="#_page_3">Compliance Assessment</a></li>
							<li><a href="#_page_4">Lateral Hiring</a></li>
							<li><a href="#_page_5">Entrance Examination</a></li>
							<li><a href="#_page_6">Product Knowledge Test</a></li>
						</ul>

						<div class="frames">
							<div class="frame" id="_page_1">
								<div class="row">
									<div class="col-sm-4 col-md-4" style="margin-top: 30px">
										<span class="badge alert-warning"><i
											class="icon-checkmark"></i></span> Few clicks Test
										Creation <br /> <span class="badge alert-warning"><i
											class="icon-checkmark"></i></span> Easy Registraion
										with Emailing Link <br /> <span class="badge alert-warning"><i
											class="icon-checkmark"></i></span> Easy Registraion
										with Excel Sheet Upload<br /> <span
											class="badge alert-warning"><i class="icon-checkmark"></i></span>
										Unlimited Number of Registered Candidates<br /> <span
											class="badge alert-warning"><i class="icon-checkmark"></i></span>
										Design and Save Unlimited Tests<br /> <span
											class="badge alert-warning"><i class="icon-checkmark"></i></span>
										Shortlisting Candidates (Rank) <br /> <span
											class="badge alert-warning"><i class="icon-checkmark"></i></span>
										Detailed Result Analytics <br /> <span
											class="badge alert-warning"><i class="icon-checkmark"></i></span>
										Review Answers <br /> <span class="badge alert-warning"><i
											class="icon-checkmark"></i></span> Schedule Test in
										any TimeZone <br /> <span class="badge alert-warning"><i
											class="icon-checkmark"></i></span> Monitor Active
										Test <br /> <span class="badge alert-warning"><i
											class="icon-checkmark"></i></span> Department wise
										Segregation <br /> <span class="badge alert-warning"><i
											class="icon-checkmark"></i></span> Reschedule Test <br />
										<span class="badge alert-warning"><i
											class="icon-checkmark"></i></span> Apply Cheating
										Prevention Algorithm<br /> <span class="badge alert-warning"><i
											class="icon-checkmark"></i></span> Min/Max Cut-off<br />
										<span class="badge alert-warning"><i
											class="icon-checkmark"></i></span> Sectional Cut-off<br />
										<span class="badge alert-warning"><i
											class="icon-checkmark"></i></span> Unlimited
										Questions / Test<br /> <span class="badge alert-warning"><i
											class="icon-checkmark"></i></span> Fail-Safe (on
										Power Failuer or Web-Browser Crash)<br />
									</div>
									<div class="col-sm-8 col-md-8">
										<div class="drop-shadow curved curved-vt-2">
											<img src="images/solutions/recruitment.jpg"
												alt="Fresher Recruitments" />
										</div>
									</div>
								</div>
							</div>
							<div class="frame" id="_page_2">
								<div class="row">
									<div class="col-sm-4 col-md-4" style="margin-top: 30px">
										Training/Learning Assessment<br /> <span
											class="badge alert-warning"><i class="icon-checkmark"></i></span>
										Conduct Assessment/Test on Different Time <br /> <span
											class="badge alert-warning"><i class="icon-checkmark"></i></span>
										Shortlisting Candidates (Rank) <br /> <span
											class="badge alert-warning"><i class="icon-checkmark"></i></span>
										Detailed Assessment Analytics <br /> <span
											class="badge alert-warning"><i class="icon-checkmark"></i></span>
										Review Answer Sheet <br /> <span class="badge alert-warning"><i
											class="icon-checkmark"></i></span> Schedule Test in
										any TimeZone <br /> <span class="badge alert-warning"><i
											class="icon-checkmark"></i></span> Monitor Active
										Test <br />
									</div>
									<div class="col-sm-8 col-md-8">
										<div class="drop-shadow curved curved-vt-2">
											<img src="images/solutions/training-assessment.jpg"
												alt="Training Assessment" />
										</div>
									</div>
								</div>
							</div>
							<div class="frame" id="_page_3">
								<div class="row">
									<div class="col-sm-4 col-md-4" style="margin-top: 30px">
										Compliance Assessment<br /> <span class="badge alert-warning"><i
											class="icon-checkmark"></i></span> Conduct
										Assessment/Test on Different Time <br /> <span
											class="badge alert-warning"><i class="icon-checkmark"></i></span>
										Shortlisting Candidates (Rank) <br /> <span
											class="badge alert-warning"><i class="icon-checkmark"></i></span>
										Detailed Assessment Analytics <br /> <span
											class="badge alert-warning"><i class="icon-checkmark"></i></span>
										Review Answer Sheet <br /> <span class="badge alert-warning"><i
											class="icon-checkmark"></i></span> Schedule Test in
										any TimeZone <br /> <span class="badge alert-warning"><i
											class="icon-checkmark"></i></span> Monitor Active
										Test <br />
									</div>
									<div class="col-sm-8 col-md-8">
										<div class="drop-shadow curved curved-vt-2">
											<img src="images/solutions/compliance.jpg"
												alt="Compliance Assessment" />
										</div>
									</div>
								</div>
							</div>
							<div class="frame" id="_page_4">
								<div class="row">
									<div class="col-sm-4 col-md-4" style="margin-top: 30px">
										Lateral Hiring<br /> <span class="badge alert-warning"><i
											class="icon-checkmark"></i></span> Conduct
										Assessment/Test on Different Time <br /> <span
											class="badge alert-warning"><i class="icon-checkmark"></i></span>
										Shortlisting Candidates (Rank) <br /> <span
											class="badge alert-warning"><i class="icon-checkmark"></i></span>
										Detailed Assessment Analytics <br /> <span
											class="badge alert-warning"><i class="icon-checkmark"></i></span>
										Review Answer Sheet <br /> <span class="badge alert-warning"><i
											class="icon-checkmark"></i></span> Schedule Test in
										any TimeZone <br /> <span class="badge alert-warning"><i
											class="icon-checkmark"></i></span> Monitor Active
										Test <br />
									</div>
									<div class="col-sm-8 col-md-8">
										<div class="drop-shadow curved curved-vt-2">
											<img src="images/solutions/lateral-hiring.jpg"
												alt="Lateral Hiring" />
										</div>
									</div>
								</div>
							</div>
							<div class="frame" id="_page_5">
								<div class="row">
									<div class="col-sm-4 col-md-4" style="margin-top: 30px">
										Product Knowledge Assessment<br /> <span
											class="badge alert-warning"><i class="icon-checkmark"></i></span>
										Conduct Assessment/Test on Different Time <br /> <span
											class="badge alert-warning"><i class="icon-checkmark"></i></span>
										Shortlisting Candidates (Rank) <br /> <span
											class="badge alert-warning"><i class="icon-checkmark"></i></span>
										Detailed Assessment Analytics <br /> <span
											class="badge alert-warning"><i class="icon-checkmark"></i></span>
										Review Answer Sheet <br /> <span class="badge alert-warning"><i
											class="icon-checkmark"></i></span> Schedule Test in
										any TimeZone <br /> <span class="badge alert-warning"><i
											class="icon-checkmark"></i></span> Monitor Active
										Test <br />
									</div>
									<div class="col-sm-8 col-md-8">
										<div class="drop-shadow curved curved-vt-2">
											<img src="images/solutions/entrance-exam.jpg"
												alt="Entrance Examination" />
										</div>
									</div>
								</div>
							</div>
							<div class="frame" id="_page_6">
								<div class="row">
									<div class="col-sm-4 col-md-4" style="margin-top: 30px">
										Product Knowledge Assessment<br /> <span
											class="badge alert-warning"><i class="icon-checkmark"></i></span>
										Conduct Assessment/Test on Different Time <br /> <span
											class="badge alert-warning"><i class="icon-checkmark"></i></span>
										Shortlisting Candidates (Rank) <br /> <span
											class="badge alert-warning"><i class="icon-checkmark"></i></span>
										Detailed Assessment Analytics <br /> <span
											class="badge alert-warning"><i class="icon-checkmark"></i></span>
										Review Answer Sheet <br /> <span class="badge alert-warning"><i
											class="icon-checkmark"></i></span> Schedule Test in
										any TimeZone <br /> <span class="badge alert-warning"><i
											class="icon-checkmark"></i></span> Monitor Active
										Test <br />
									</div>
									<div class="col-sm-8 col-md-8">
										<div class="drop-shadow curved curved-vt-2">
											<img src="images/solutions/product-knowledge-assessment.jpg"
												alt="Product Knowledge Test" />
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
		<?php
		include ("lib/footer.php");
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
