<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	include_once("../../lib/session_manager.php");
	include_once("../../lib/billing.php");
	include_once('../../database/mcat_db.php');
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$objDB = new CMcatDB();
	$objBilling = new CBilling();
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	
?>
<html>
	<head>
		<title>Business Assoc</title>
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
			body{font: 70% "Trebuchet MS", sans-serif; margin: 5px; overflow:hidden; }
		</style>
	</head>
	<body style="font: 75% 'Trebuchet MS', sans-serif; margin: 5px;">
		<div id="page_loading_box" style="position:absolute;top:100px;left:50%;zindex:200;">
			<img src="../../images/page_loading.gif" width="32" height="32"/>
		</div>
		<div id="ba_earning_history">
			<ul>
				<li><a href="#tab1">Earning Information</a></li>
			</ul>
			<div id="tab1">
				<table cellpadding="0" cellspacing="0" border="0" width="100%" class="display" id="example">
					<thead>
						<tr>
							<th><font color="#000000">Client</font></th>
							<th><font color="#000000">Payment Date</font></th>
							<th><font color="#000000">Realization Date</font></th>
							<th><font color="#000000">Commision</font></th>
						</tr>
					</thead>
					<?php
						$objBilling->PopulateBAEarningSourceHistory($user_id);
					?>
					<tfoot>
						<tr>
							<th><font color="#000000">Client</font></th>
							<th><font color="#000000">Payment Date</font></th>
							<th><font color="#000000">Realization Date</font></th>
							<th><font color="#000000">Commision</font></th>
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
			$('#ba_earning_history').show();
			$('#ba_earning_history').tabs();
				
			var page_hgt = objUtils.AdjustHeight("tab1");
			$('#platform', window.parent.document).height(page_hgt+200);
		});
	</script>
</html>
