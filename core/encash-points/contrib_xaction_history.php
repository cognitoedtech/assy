<?php
	include_once("../../lib/session_manager.php");
	include_once("../../database/mcat_db.php");

	$objDB = new CMcatDB();
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title>Transaction History</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<style type="text/css" title="currentStyle">
					@import "../media/css/demo_table.css";
					@import "../media/css/TableTools.css";
					@import "../media/css/dataTables.editor.css";
					@import "../media/css/jquery-ui-1.8.21.custom.css";
					@import "../../css/redmond/jquery-ui-1.9.0.custom.min.css";
		</style>
		<link rel="stylesheet" type="text/css" href="../../css/mipcat.css" />
		<script type="text/javascript" charset="utf-8" src="../media/js/jquery.js"></script>
		<script type="text/javascript" charset="utf-8" src="../media/js/jquery.dataTables.js"></script>
		<script type="text/javascript" charset="utf-8" src="../media/js/ZeroClipboard.js"></script>
		<script type="text/javascript" charset="utf-8" src="../media/js/TableTools.js"></script>
		<script type="text/javascript" charset="utf-8" src="../media/js/dataTables.editor.js"></script>
		<script type="text/javascript" charset="utf-8" src="../../js/jquery-ui-custom.min.js"></script>
		<script type="text/javascript" charset="utf-8" src="../media/js/jquery.validate.js"></script>	
		<script type="text/javascript" charset="utf-8" src="../../js/mipcat/utils.js"></script>
	</head>
	<body style="font: 75% 'Trebuchet MS', sans-serif; margin: 5px;overflow:hidden;">
		<div id="page_loading_box" style="position:absolute;top:100px;left:50%;zindex:200;">
			<img src="../../images/page_loading.gif" width="32" height="32"/>
		</div>
		<div id="trans_history">
			<ul>
				<li><a href="#tab1">Transaction History</a></li>
			</ul>
			<div id="tab1">
				<table cellpadding="0" cellspacing="0" border="0" width="100%" class="display" id="example">
					<thead>
						<tr>
							<th><font color="#000000">Transaction Id</font></th>
							<th><font color="#000000">Points</font></th>
							<th><font color="#000000">Request Time</font></th>
							<th><font color="#000000">Request Timezone</font></th>
							<th><font color="#000000">Request IP</font></th>
							<th><font color="#000000">Status</font></th>
							<th><font color="#000000">Payment Time</font></th>
							<th><font color="#000000">Cheque/DD No.</font></th>
							<th><font color="#000000">Bank(Drawn)</font></th>
							<th><font color="#000000">Cheque/DD date</font></th>
						</tr>
					</thead>
						<?php
							$table = $objDB->GetContribEncashHistory($user_id);
							foreach($table as $key => $value)
							{
								printf("<tr>");
								printf("<td align='center'>%s</td>",$value['transaction_id']);
								printf("<td align='center'>%s</td>",$value['points']);
								printf("<td align='center'>%s</td>",$value['req_timestamp']);
								printf("<td align='center'>%s</td>",$value['req_timezone']);
								printf("<td align='center'>%s</td>",$value['req_ip_addr']);
								printf("<td align='center'>%s</td>",$value['status']);
								printf("<td align='center'>%s</td>",$value['last_edited']);
								printf("<td align='center'>%s</td>",$value['cheque_dd_no']);
								printf("<td align='center'>%s</td>",$value['drawn_bank']);
								printf("<td align='center'>%s</td>",$value['cheque_dd_date']);
								printf("</tr>");
							}
						?>
					<tfoot>
						<tr>
							<th><font color="#000000">Transaction Id</font></th>
							<th><font color="#000000">Points</font></th>
							<th><font color="#000000">Request Time</font></th>
							<th><font color="#000000">Request Timezone</font></th>
							<th><font color="#000000">Request IP</font></th>
							<th><font color="#000000">Status</font></th>
							<th><font color="#000000">Payment Time</font></th>
							<th><font color="#000000">Cheque/DD No.</font></th>
							<th><font color="#000000">Bank(Drawn)</font></th>
							<th><font color="#000000">Cheque/DD date</font></th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</body>
	<script type="text/javascript">
		$(window).load(function(){
			$("#page_loading_box").hide();
			$('#trans_history').show();
			$('#trans_history').tabs();
				
			var page_hgt = objUtils.AdjustHeight("tab1");
			$('#platform', window.parent.document).height(page_hgt+200);
		});
		
		$(document).ready( function () {
			$('#example').dataTable( 
				{"sPaginationType": "full_numbers"}
			);
			
		});
	</script>
</html>
