<?php
	include_once(dirname(__FILE__)."/../database/config.php");
	include_once(dirname(__FILE__)."/include_js_css.php");
	include_once(dirname(__FILE__)."/utils.php");
	include_once(dirname(__FILE__)."/user_manager.php");
	include_once(dirname(__FILE__)."/site_config.php");
	include_once(dirname(__FILE__)."/session_manager.php");
	
	$login_name = CSessionManager::Get(CSessionManager::STR_LOGIN_NAME);
	
	$logged_in = false;
	$logo_name  = CConfig::SNC_SITE_NAME;
	
	$punch_line = CConfig::SNC_PUNCH_LINE;
	
	$logo_image = "";
	
	if($login_name != null)
	{
		$objUM = new CUserManager();
		$OrgInfo = $objUM->GetOrgInfoFromLoginName($login_name);
		
		if($OrgInfo != -1 && !empty($OrgInfo['logo_image']))
		{
			$logo_image = sprintf("<img src='%s/test/lib/print_image.php?org_logo_img=%s' style='width: %spx; height: %spx;'/>", CSiteConfig::ROOT_URL, $OrgInfo['organization_id'], CConfig::OL_WIDTH, CConfig::OL_HEIGHT);
		}
		else if($OrgInfo != -1 && !empty($OrgInfo['logo_name']))
		{
			$logo_name  = $OrgInfo['logo_name'];
			//$punch_line = $OrgInfo['punch_line'];
		}
	}
	
	$login = CSessionManager::Get(CSessionManager::BOOL_LOGIN);
	if($login)
	{
		$logged_in = true;
	}
?>
	<div id='header' class="navbar navbar-inverse navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse"
					data-target=".navbar-responsive-collapse">
					<span class="icon-bar"></span> <span class="icon-bar"></span> <span
						class="icon-bar"></span>
				</button>
				<a class="navbar-brand" style="<?php echo(!empty($logo_image)?"padding-top: 5px;" : "");?>width: 200px;text-decoration: none;outline : none;" href="<?php echo (CSiteConfig::ROOT_URL.'/'.$login_name);?>"><?php echo(!empty($logo_image)?$logo_image : "<b>".$logo_name."</b>");?></a>
			</div>
			<div class="navbar-collapse collapse navbar-responsive-collapse">
				<!-- <form class="navbar-form navbar-left">
      <input type="text" class="form-control col-lg-8" placeholder="Search">
    </form> -->
    			<ul class="nav navbar-nav navbar-right">
				<?php 
				if($logged_in) 
				{
				?>
					<li class="dropdown"><a href="#" class="dropdown-toggle"
						data-toggle="dropdown"><span class="badge">Logout <i
								class="icon-user"></i></span> <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li class="dropdown-header"><?php echo CSessionManager::Get(CSessionManager::STR_EMAIL_ID);?></li>
							<li><a href="../../login/logout.php">Logout</a></li>
						</ul></li>
				<?php 
				}
				?>
				</ul>
			</div>
		</div>
	</div>
