<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	include_once("../../lib/session_manager.php");
	include_once('../../database/mcat_db.php');
	include_once(dirname(__FILE__)."/../../lib/include_js_css.php");
	include_once(dirname(__FILE__)."/../../lib/site_config.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$objDB = new CMcatDB();
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	
	$parsAry = parse_url(CUtils::curPageURL());
	$qry = explode("=", $parsAry["query"]);
	
	$sTestName = "";
	if($qry[0] == "test_name" && !empty($qry[1]))
	{
		echo "<script>save_success = 1; </script>";
		$sTestName = $qry[1];
	}
	else 
	{
		echo "<script>save_success = 0; </script>";
	}
	
	$objIncludeJsCSS = new IncludeJSCSS();
	
	$menu_id = CSiteConfig::UAMM_SUPER_ADMIN;
	$page_id = CSiteConfig::UAP_SCHEDULED_TEST;
?>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="Generator" content="EditPlus">
<meta name="Author" content="">
<meta name="Keywords" content="">
<meta name="Description" content="">
<title>Scheduled Test</title>
<?php 
$objIncludeJsCSS->CommonIncludeCSS("../../");
$objIncludeJsCSS->CommonIncludeJS("../../");
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
	<div class='container' style='width: 100%'>
		<div class='row-fluid'>
			<div class="col-lg-3">
				<?php 
				include_once(dirname(__FILE__)."/../../lib/sidebar.php");
				?>
			</div>
		</div>
	</div>
</body>
</html>
<?php 
if(false)
{
?>
<html>
	<head>
		<title> Super Admin </title>
		<style type="text/css" title="currentStyle">
			@import "../../css/redmond/jquery-ui-1.9.0.custom.min.css";
			@import "../media/css/demo_table.css";
			@import "../media/css/TableTools.css";
			@import "../media/css/dataTables.editor.css";
		</style>
		<link rel="stylesheet" type="text/css" href="../../css/mipcat.css" />
		<link href="../../css/notify.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" charset="utf-8" src="../media/js/jquery.js"></script>
		<script type="text/javascript" charset="utf-8" src="../media/js/jquery.dataTables.js"></script>
		<script type="text/javascript" charset="utf-8" src="../media/js/ZeroClipboard.js"></script>
		<script type="text/javascript" charset="utf-8" src="../media/js/TableTools.js"></script>
		<script type="text/javascript" charset="utf-8" src="../../js/jquery-ui-custom.min.js"></script>
		<script type="text/javascript" src="../../3rd_party/wizard/js/jquery.validate.min.js"></script>
		<script type="text/javascript" src="../../js/notification.js"></script>
		<script type="text/javascript" charset="utf-8" src="../../js/mipcat/utils.js"></script>
		<script type="text/javascript" charset="utf-8">
			window.onload = function() {
				$("#schedule_test").show();
			};
		</script>
		<style type="text/css">
			/*demo page css*/
			body{ font: 70% "Trebuchet MS", sans-serif; margin: 5px; overflow:hidden;}
		</style>
	</head>
	<body>
		<div id="page_loading_box" style="position:absolute;top:100px;left:50%;zindex:200;">
			<img src="../../images/page_loading.gif" width="32" height="32"/>
		</div>
		<div id="dt_scheduled_tests">
			<ul>
				<li><a href="#tab1">Scheduled Tests</a></li>
			</ul>
			<div id="tab1">
				<table cellpadding="0" cellspacing="0" border="0" width="100%" class="display" id="example">
					<thead>
						<tr>
							<th><font color="#000000">Test Name</font></th>
							<th><font color="#000000">Scheduled By</font></th>
							<th><font color="#000000">Scheduled On</font></th>
							<th><font color="#000000">Time Zone</font></th>
							<th><font color="#000000">Schedule Created</font></th>
							<th><font color="#000000">Candidates Scheduled</font></th>
							<th><font color="#000000">Candidates Finished</font></th>
						</tr>
					</thead>
					<?php
						$objDB->PopulateTableTestSchedule();
					?>
					<tfoot>
						<tr>
							<th><font color="#000000">Test Name</font></th>
							<th><font color="#000000">Scheduled By</font></th>
							<th><font color="#000000">Scheduled On</font></th>
							<th><font color="#000000">Time Zone</font></th>
							<th><font color="#000000">Schedule Created</font></th>
							<th><font color="#000000">Candidates Scheduled</font></th>
							<th><font color="#000000">Candidates Finished</font></th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
		<script type="text/javascript">
					
			objTbl = $('#example').dataTable( {
				"sDom": 'T<"clear">lfrtip<"clear spacer">T',
				"sPaginationType": "full_numbers",
				"oTableTools": {
		            "sRowSelect": "single",
		            "aButtons": [
		                "copy",
		                "csv",
		                "pdf"
		            ]
		        }
			} );
		</script>
	</body>
	<script type="text/javascript">
		$(window).load(function(){
			$("#page_loading_box").hide();
			$('#dt_scheduled_tests').show();
			$('#dt_scheduled_tests').tabs();
				
			var page_hgt = objUtils.AdjustHeight("tab1");
			$('#platform', window.parent.document).height(page_hgt+200);
		});
	</script>
</html>
<?php 
}
?>