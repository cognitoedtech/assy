<?php 
	include_once(dirname(__FILE__)."/../database/config.php");
	include_once(dirname(__FILE__)."/site_config.php");
	include_once(dirname(__FILE__)."/session_manager.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	$menu_class_ary = array();
	$menu_class_ary[$menu_id] = "class='active stick bg-red'";
	
	$pages_class_ary = array();
	if(isset($page_id))
	{
		$pages_class_ary[$page_id] = "class='active stick2 bg-red'";
	}
	
	$user_type = CSessionManager::Get ( CSessionManager::INT_USER_TYPE );
?>
	<div id='sidebar'>
		<div class="metro">
			<div class="grid fluid">
				<div class="row">
					<div class="col-xs-12 col-sm-12">
						<nav class="sidebar light">
							<ul>
								<?php 
								if($user_type != CConfig::UT_INDIVIDAL)
								{
								?>
								<li class="title" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif;color: #317eac;"><b>Test Admin</b></li>
								<li <?php echo($menu_class_ary[CSiteConfig::UAMM_MANAGE_TEST]);?>><a href="<?php echo(CSiteConfig::ROOT_URL);?>/core/test-admin/manage_test.php"><i class="icon-tools fg-steel"></i>Manage Test</a></li>
								<li <?php echo($menu_class_ary[CSiteConfig::UAMM_MONITOR_ACTIVE_TESTS]);?>><a href="<?php echo(CSiteConfig::ROOT_URL);?>/core/test-admin/monitor_active_tests.php"><i class="icon-stats fg-steel"></i>Monitor Active Tests</a></li>
								<li <?php echo($menu_class_ary[CSiteConfig::UAMM_EXPORT_TEST_RESULT]);?>><a href="<?php echo(CSiteConfig::ROOT_URL);?>/core/test-admin/export_result.php"><i class="icon-file-zip fg-steel"></i>Export Test Result</a></li>
								<?php 
								}
								?>
							</ul>
						</nav>
					</div>
				</div>
			</div>
		</div>
	</div>