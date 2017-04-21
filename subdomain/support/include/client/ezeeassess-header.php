<?php
	include_once(dirname(__FILE__)."/../../../../database/config.php");
	include_once(dirname(__FILE__)."/../../../../lib/include_js_css.php");
	include_once(dirname(__FILE__)."/../../../../lib/user_manager.php");
	include_once(dirname(__FILE__)."/../../../../lib/site_config.php");
	include_once(dirname(__FILE__)."/../../../../lib/session_manager.php");
	

	
	$login_name = CSessionManager::Get(CSessionManager::STR_LOGIN_NAME);
	
	$logged_in = false;
	$logo_name  = CConfig::SNC_SITE_NAME;
	
	$objIncludeJsCSS = new IncludeJSCSS();
	
	if($login_name != null)
	{
		$logged_in = true;
	}
	$objIncludeJsCSS->CommonIncludeCSS("../../");
	$objIncludeJsCSS->IncludeTVCSS("../../");
	$objIncludeJsCSS->IncludeMipcatCSS("../../");
	$objIncludeJsCSS->CommonIncludeJS("../../");
?>
	<div class="navbar navbar-inverse navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse"
					data-target="navbar-responsive-collapse">
					<span class="icon-bar"></span> <span class="icon-bar"></span> <span
						class="icon-bar"></span>
				</button>
				<a class="navbar-brand" style="width: 200px;text-decoration: none;outline : none;" href="<?php echo (CSiteConfig::ROOT_URL.'/'.$login_name);?>"><b><?php echo($logo_name);?></b></a>
			</div>
			<div class="navbar-collapse collapse navbar-responsive-collapse">
				<ul class="nav navbar-nav">
					<li class="dropdown"><a href="#" class="dropdown-toggle"
						data-toggle="dropdown">Features <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="<?php echo CSiteConfig::ROOT_URL;?>/features/candidate-management.php">Candidate Management</a></li>
							<li><a href="<?php echo CSiteConfig::ROOT_URL;?>/features/knowledge-base-management.php">Knowledge-Base Management</a></li>
							<li><a href="<?php echo CSiteConfig::ROOT_URL;?>/features/test-design-and-managment.php">Test Design & Management</a></li>
							<li><a href="<?php echo CSiteConfig::ROOT_URL;?>/features/test-scheduling-and-monitoring.php">Test Scheduling & Monitoring</a></li>
							<li><a href="<?php echo CSiteConfig::ROOT_URL;?>/features/result-analytics.php">Result Analytics</a></li>
							<li><a href="<?php echo CSiteConfig::ROOT_URL;?>/features/batch-management.php">Batch Management</a></li>
							<li><a href="<?php echo CSiteConfig::ROOT_URL;?>/features/departments-coordinators.php">Departments</a></li>
							<li><a href="<?php echo CSiteConfig::ROOT_URL;?>/features/billing-and-account-management.php">Billing Management</a></li>
							<li><a href="<?php echo CSiteConfig::ROOT_URL;?>/features/personal-account-management.php">Personal Account Management</a></li>
						</ul></li>
					<li><a href="<?php echo CSiteConfig::ROOT_URL;?>/pricing.php">Pricing</a></li>
					<li><a href="<?php echo CSiteConfig::ROOT_URL;?>/subdomain/support/index.php">Support</a></li>
					<li class="dropdown"><a href="#" class="dropdown-toggle"
						data-toggle="dropdown">Help &amp; FAQ <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li class="dropdown-header">Help Manual</li>
							<li><a href="<?php echo CSiteConfig::ROOT_URL;?>/help-and-faq/help-manual/gs_help_inst.php">Institute / Learning Centers</a></li>
							<li><a href="<?php echo CSiteConfig::ROOT_URL;?>/help-and-faq/help-manual/gs_help_corp.php">Corporate / IT Companies</a></li>
							<li class="divider"></li>
							<li class="dropdown-header">Frequently Asked Questions</li>
							<li><a href="<?php echo CSiteConfig::ROOT_URL;?>/help-and-faq/faq/inst_faq.php">Institute / Learning Center</a></li>
							<li><a href="<?php echo CSiteConfig::ROOT_URL;?>/help-and-faq/faq/corp_faq.php">Corporate / IT Company</a></li>
							<li><a href="<?php echo CSiteConfig::ROOT_URL;?>/help-and-faq/faq/indv_faq.php">Candidate</a></li>
						</ul></li>
					<li class="dropdown"><a href="#" class="dropdown-toggle"
						data-toggle="dropdown">Terms of Use <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li class="dropdown-header">Terms &amp; Privacy</li>
							<li><a href="<?php echo CSiteConfig::ROOT_URL;?>/terms/terms-of-service.php">Terms of Service</a></li>
							<li><a href="<?php echo CSiteConfig::ROOT_URL;?>/terms/privacy_policy.php">Privacy Policy</a></li>
							<li class="divider"></li>
							<li class="dropdown-header">Dossier We Sign</li>
							<li><a target="_blank" href="<?php echo CSiteConfig::ROOT_URL;?>/terms/EZeeAssess-SLA-v2.pdf">Service Level Agreement <span class="badge"><i class="icon-file-pdf alert-danger"></i></span></a></li>
							<li class="disabled"><a href="<?php echo CSiteConfig::ROOT_URL;?>/terms/non-disclosure-agreement.php">Non-Disclosure Agreement (Client provided)</a></li>
						</ul></li>
					<li class="dropdown"><a href="#" class="dropdown-toggle"
						data-toggle="dropdown">Company <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="<?php echo CSiteConfig::ROOT_URL;?>/company/about-us.php">About Us</a></li>
							<li><a href="<?php echo CSiteConfig::ROOT_URL;?>/company/our-story.php">Our Story</a></li>
							<li class="divider"></li>
							<li><a href="<?php echo CSiteConfig::ROOT_URL;?>/company/contact-us.php">Contact Us</a></li>
						</ul></li>
				</ul>
				<!-- <form class="navbar-form navbar-left">
      <input type="text" class="form-control col-lg-8" placeholder="Search">
    </form> -->
    			<ul class="nav navbar-nav navbar-right">
    			<?php 
				if(!$logged_in)
				{
				?>
					<li><a href="<?php echo CSiteConfig::FREE_ROOT_URL;?>">Free for Students</a></li>
					<li><a href="#" class="btn btn-primary">Free Sign-up!</a></li>
					<li><a href="<?php echo CSiteConfig::ROOT_URL;?>/signin.php" class="btn btn-danger">Sign-in</a></li>
				<?php 
				}
				else 
				{
				?>
					<li><a href="<?php echo CSiteConfig::ROOT_URL;?>/core/dashboard.php"><span class="badge">My Home <i
								class="icon-home"></i></span></a></li>
					<li class="dropdown"><a href="#" class="dropdown-toggle"
						data-toggle="dropdown"><span class="badge">Logout <i
								class="icon-user"></i></span> <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li class="dropdown-header"><?php echo CSessionManager::Get(CSessionManager::STR_EMAIL_ID);?></li>
							<li><a href="<?php echo CSiteConfig::ROOT_URL;?>/login/logout.php">Logout</a></li>
						</ul></li>
				<?php 
				}
				?>
				</ul>
			</div>
		</div>
	</div>
