<!DOCTYPE HTML>
<?php
	include_once(dirname(__FILE__)."/../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../lib/include_js_css.php");
	include_once(dirname(__FILE__)."/../../lib/site_config.php");	
	include_once(dirname(__FILE__)."/../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/../../lib/billing.php");

	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -

	$objBilling = new CBilling();

	$processed = 0;
	if(!empty($_GET['processed']))
	{
		$processed = $_GET['processed'];
	}

	printf("<script>save_success='%s'</script>",$processed);
	
	$objIncludeJsCSS = new IncludeJSCSS();
	
	$menu_id = CSiteConfig::UAMM_SUPER_ADMIN;
	$page_id = CSiteConfig:: UAP_FREE_EVALUATION_RECHARGE;
?>
<html lang="en">
	<head>
	<meta charset="UTF-8">
	<meta name="Generator" content="Mastishka Intellisys Private Limited">
	<meta name="Author" content="Mastishka Intellisys Private Limited">
	<meta name="Keywords" content="">
	<meta name="Description" content="">
	<title>Free Evalution Recharge </title>
	<?php
	$objIncludeJsCSS->IncludeBootstrap3_1_1Plus1CSS("../../");
	$objIncludeJsCSS->IncludeBootswatch3_1_1Plus1LessCSS("../../");
	$objIncludeJsCSS->IncludeMetroBootstrapCSS("../../");
	$objIncludeJsCSS->CommonIncludeCSS("../../");
	$objIncludeJsCSS->IncludeIconFontCSS("../../");
	$objIncludeJsCSS->IncludeMipcatCSS("../../");
	$objIncludeJsCSS->CommonIncludeJS("../../");
	$objIncludeJsCSS->IncludeMetroNotificationJS("../../");
	$objIncludeJsCSS->IncludeJqueryValidateMinJS("../../");
	?>
	</head>
	<body>

		<?php 
			include_once(dirname(__FILE__)."/../../lib/header.php");
		?>

		<!-- --------------------------------------------------------------- -->
		<br />
		<br />
		<br />
		<div class='row-fluid'>
			<div class="col-lg-3">
				<?php 
					include_once(dirname(__FILE__)."/../../lib/sidebar.php");
				?>
			</div>
			<div class="col-lg-9" style="border-left: 1px solid #ddd; border-top: 1px solid #ddd;"><br /><br />
				<form class="form-horizontal" id="free_test_credit_form" action="post_get/form_free_test_credit_exec.php" name="free_test_credit_form" method="post">
					<div class="form-group">
						<label for="user_info" class="col-lg-3 control-label">User Information :</label>
						<div class="col-lg-6">
							<select class="form-control input-sm" id="user_info" name="user_info">
								<option value=''>--Select User--</option>
								<?php
									$objBilling->PopulateUsersForFreeRecharge(CConfig::UT_CORPORATE);
								?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="free_tests" class="col-lg-3 control-label">Free Tests :</label>
						<div class="col-lg-3">
							<input type="text" class="form-control input-sm" name="free_tests" id="free_tests" />
						</div>
					</div>
					<div class="form-group">
						<div class="col-lg-2 col-lg-offset-3">
							<input id="process" class="btn btn-success" type="submit" value="Process >>" /><br/>
						</div>
					</div>			
				</form>
				<?php 
				include_once(dirname(__FILE__)."/../../lib/footer.php");
				?>
			</div>
		</div>
		<script type="text/javascript">

			$('#free_test_credit_form').validate({
				rules: {
					'user_info':		{required: true},
					'free_tests':		{required: true, digits: true}
				}, messages: {
					'user_info':		{required:  "<p style='color:red;'>* Please select a user!</p>"},
					'free_tests':		{required:  "<p style='color:red;'>* Please enter number of tests!</p>", digits: "<p style='color:red;'>* Only numbers are allowed!</p>"}
				}
			});

			$(document).ready(function () {

				if(save_success == 1)
				{
					var not = $.Notify({
	      				 caption: "Test Scheduled",
	      				 content: "The test <?php echo("<b>".$sTestName."</b>"); ?> has been scheduled successfully!",
	      				 style: {background: 'green', color: '#fff'}, 
	      				 timeout: 5000
	      				 });
				}

				OnRechargeOptChange();
			});
		</script>
	</body>
</html>