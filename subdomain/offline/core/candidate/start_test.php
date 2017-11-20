<!doctype html>
<?php
include_once (dirname ( __FILE__ ) . "/../../lib/include_js_css.php");
include_once (dirname ( __FILE__ ) . "/../../lib/session_manager.php");
include_once (dirname ( __FILE__ ) . "/../../lib/site_config.php");
include_once (dirname ( __FILE__ ) . "/../../database/mcat_db.php");

// - - - - - - - - - - - - - - - - -
// On Session Expire Load ROOT_URL
// - - - - - - - - - - - - - - - - -
CSessionManager::OnSessionExpire ();
// - - - - - - - - - - - - - - - - -

$objDB = new CMcatDB ();

$params = $objDB->GetActiveTestStartParams();

$user_id = CSessionManager::Get ( CSessionManager::STR_USER_ID );

$user_type = CSessionManager::Get ( CSessionManager::INT_USER_TYPE );

$bAlreadyAttempted = $objDB->IsTestAlreadyAttempeted($user_id);

$objIncludeJsCSS = new IncludeJSCSS ();

$current_active_test = $objDB->GetActiveTestName();

?>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="Generator" content="Mastishka Intellisys Private Limited">
<meta name="Author" content="Mastishka Intellisys Private Limited">
<meta name="Keywords" content="">
<meta name="Description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo(CConfig::SNC_SITE_NAME);?> Offline: Start Test</title>
<?php
$objIncludeJsCSS->CommonIncludeCSS ( "../../" );
$objIncludeJsCSS->IncludeIconFontCSS ( "../../" );
$objIncludeJsCSS->IncludeFuelUXCSS ( "../../" );
$objIncludeJsCSS->CommonIncludeJS ("../../");
$objIncludeJsCSS->IncludeMipcatCSS("../../");
?>
<style type="text/css">
	#overlay { position: fixed; left: 0px; top: 0px; width: 100%; height: 100%; z-index: 100; background-color:white;}
	
	.modal, .modal.fade.in {
	    top: 10%;
	}
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
	<div id="overlay" style="display:none">
		<iframe id="overlay_frame" src="#" width="100%" height="100%"></iframe>
	</div>

	<?php
	include_once (dirname ( __FILE__ ) . "/../../lib/header.php");
	?>

	<!-- --------------------------------------------------------------- -->
	<br />
	<br />
	<br />
	<div class='row-fluid'>
		<?php 
		if($bAlreadyAttempted)
		{
		?>
		<div class='col-lg-6 col-lg-offset-3'>
			<div class="drop-shadow raised">
				<p class="text-center" id='msg'>
					<?php 
					if(isset($_GET['test_finished']) && $_GET['test_finished'] == 1)
					{
						printf("Thank You For Attempting Test : <span style='color:#2fa4e7;'>%s</span>", $current_active_test);
					}
					else
					{
						printf("<p class='text-center'>You have earlier attempted the test : <span style='color:#2fa4e7;'>%s</span>!</p>",$current_active_test);
						printf("<p class='text-center'>Currently you don't have any other test scheduled for you.</p>");
					}
					?>
				</p>
			</div>
		</div>
		<?php 
		}
		?>
	</div>
	<script type="text/javascript">
	var bTestStarted = false;
	function ShowOverlay(test_id, schd_id)
	{
		$("#header").hide();
		
		var current_date = new Date();
	    var time_zone = -current_date.getTimezoneOffset() / 60;

	    var url = "../../test/test.php?test_id="+test_id+"&tschd_id="+schd_id;
		var height	  = $(window).height();
		bTestStarted = true;
		$("#overlay_frame").attr("src",url+"&time_zone="+time_zone+"&height="+height).ready(function(){
			$("#overlay").show(800);
			$("body").css("overflow", "hidden");
		});
	}
	
	function HideOverlay()
	{
		$("#overlay").hide(500);
		$("#sidebar").show();
		$("#header").show();
		$("body").css("overflow", "auto");
		window.location = window.location+"?test_finished=1";
	}
	
	function RemoveTest()
	{
		
	}


	function TestOver(div_id)
	{
		//window.location = window.location;
	}
	<?php 
	if(!empty($params) && !$bAlreadyAttempted)
	{
	?>
	ShowOverlay(<?php echo($params['test_id']);?>, <?php echo($params['schd_id']);?>);
	<?php 
	}
	?>
	</script>
</body>
</html>
