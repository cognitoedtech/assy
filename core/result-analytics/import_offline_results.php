<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	include_once("../../lib/session_manager.php");
	include_once('../../test/lib/tbl_result.php');
	include_once(dirname(__FILE__)."/../../lib/include_js_css.php");
	include_once(dirname(__FILE__)."/../../lib/site_config.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	$nUserType = CSessionManager::Get(CSessionManager::INT_USER_TYPE);
	
	$objIncludeJsCSS = new IncludeJSCSS();
	
	$menu_id = CSiteConfig::UAMM_RESULT_ANALYTICS;
	$page_id = CSiteConfig::UAP_IMPORT_OFFLINE_RESULTS;
?>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="Generator" content="Mastishka Intellisys Private Limited">
<meta name="Author" content="Mastishka Intellisys Private Limited">
<meta name="Keywords" content="">
<meta name="Description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo(CConfig::SNC_SITE_NAME);?>: Import Offline Results</title>
<?php 
$objIncludeJsCSS->CommonIncludeCSS ( "../../" );
$objIncludeJsCSS->IncludeIconFontCSS ( "../../" );
$objIncludeJsCSS->IncludeFuelUXCSS ( "../../" );
$objIncludeJsCSS->CommonIncludeJS ( "../../");
$objIncludeJsCSS->IncludeMetroCalenderJS("../../");
$objIncludeJsCSS->IncludeMetroDatepickerJS("../../");
$objIncludeJsCSS->IncludeJqueryValidateMinJS("../../");
?>
<style type="text/css">
	.modal1 {
		display:    none;
		position:   fixed;
		z-index:    1000;
		top:        50%;
		left:       60%;
		height:     100%;
		width:      100%;
	}
</style>
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
		<div class="col-lg-3 col-md-3 col-sm-3">
			<?php 
			include_once(dirname(__FILE__)."/../../lib/sidebar.php");
			?>
		</div>
		<div class="col-lg-9 col-md-9 col-sm-9" style="border-left: 1px solid #ddd; border-top: 1px solid #ddd;">
			<div class="fuelux modal1">
				<div class="preloader"><i></i><i></i><i></i><i></i></div>
			</div>
			<br />
			<form method="POST" action="ajax/ajax_upload_offline_results.php" id="upload_result" enctype="multipart/form-data" target="upload_status">
				<div class="row" id="test_date_div">
					<div class="col-lg-3 col-md-3 col-sm-3 col-lg-offset-1 col-md-offset-1 col-sm-offset-1">
						<label for="datepicker1_val"><b>Test Date:</b></label>
						<div class="metro">
							<div class="input-control text" id="datepicker1">
								<input id="datepicker1_val" name="test_date" type="text">
								<button class="btn-date" onclick="return false;"></button>
							</div>
						</div>
					</div>		    		
				</div>
				<div class="row">
					<div class="col-lg-4 col-md-4 col-sm-4 col-lg-offset-1 col-md-offset-1 col-sm-offset-1">
						<label><b>Select Result File(zip):</b></label>
						<div class="metro">
							<div class="input-control file">
							    <input name="zip" type="file" />
							    <button class="btn-file"></button>
						    </div>
						</div>
					</div>
				</div><br />
				<div>
					<div class="col-lg-offset-1 col-md-offset-1 col-sm-offset-1">
						<button class='btn btn-primary'>Submit</button>
					</div>
				</div><br /><br />
				<div class="row">
					<div class="col-lg-4 col-md-4 col-sm-4 col-lg-offset-1 col-md-offset-1 col-sm-offset-1">
						<div id='upload_error'>
						</div>
					</div>
				</div>
				<br /><br />
				<iframe name="upload_status" width="100%" height="400" src="" frameborder=0 style="border:1px solid #aaa;"></iframe>
			</form>
			<?php
			include_once (dirname ( __FILE__ ) . "/../../lib/footer.php");
			?>
		</div>
	</div>
	<script type="text/javascript">
		$("#datepicker1").datepicker({
			format: "dd mmmm yyyy"
		});

		$(document).ready(function () {
			$('#upload_result').validate({
				errorPlacement: function(error, element) {
					$('#upload_result div#upload_error').append(error);
				}, rules: {
					"test_date":	{required: true},
					'zip':			{required: true, accept: "zip"}
				}, messages: {
					"test_date":	{required: "<p style='color:red;font-weight:bold;'>Please select the test date!</p>"},
					'zip':			{required: "<p style='color:red;font-weight:bold;'>Please select a &lsquo;zip&rsquo; file to upload!</p>", accept: "<p style='color:red;font-weight:bold;'>Please select a valid &lsquo;zip&rsquo; file to upload!</p>"}
				}
			});
		});
	</script>
</body>
</html>