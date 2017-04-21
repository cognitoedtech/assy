<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	include_once(dirname(__FILE__)."/../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../database/mcat_db.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$objDB = new CMcatDB();
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	$nUserType = CSessionManager::Get(CSessionManager::INT_USER_TYPE);
?>
<html>
	<head>
		<title> Super Admin </title>
		<style type="text/css" title="currentStyle">
			@import "../media/css/demo_table.css";
			@import "../media/css/TableTools.css";
			@import "../media/css/dataTables.editor.css";
			@import "../../css/redmond/jquery-ui-1.9.0.custom.min.css";
		</style>
		<link rel="stylesheet" type="text/css" href="../../css/mipcat.css" />
		<script type="text/javascript" charset="utf-8" src="../../js/jquery.js"></script>
		<script type="text/javascript" charset="utf-8" src="../../js/jquery-ui-custom.min.js"></script>
		<script type="text/javascript" charset="utf-8" src="../media/js/jquery.dataTables.js"></script>
		<script type="text/javascript" charset="utf-8" src="../media/js/ZeroClipboard.js"></script>
		<script type="text/javascript" charset="utf-8" src="../media/js/TableTools.js"></script>
		<script type="text/javascript" charset="utf-8" src="../media/js/dataTables.editor.js"></script>
		<script type="text/javascript" charset="utf-8" src="../../js/mipcat/utils.js"></script>
		<style type="text/css">
			body{ font: 75% "Trebuchet MS", sans-serif; margin: 5px; margin-left: 20px; margin-right: 20px; overflow:hidden;}
		</style>
	</head>
	<body>
		<div id="page_loading_box" style="position:absolute;top:100px;left:50%;zindex:200;">
			<img src="../../images/page_loading.gif" width="32" height="32"/>
		</div>
		
		<div id="result_analytics" style="display:none; margin-left: -15px; margin-right: -15px; margin-bottom: -10px;">
			<ul>
				<li><a href="#tab1">Question : Contribution Status</a></li>
			</ul>
			<div id="tab1">
				
			</div>
		</div>
		
		<table cellpadding="0" cellspacing="0" border="0" width="100%" style="display:none" class="display" id="dt_contrib_ques_status">
			<thead>
				<tr>
					<th><font color="#000000">Question</font></th>
					<th><font color="#000000">Subject</font></th>
					<th><font color="#000000">Status</font></th>
					<th><font color="#000000">Chances of Acceptance</font></th>
					<th><font color="#000000">Used in Paid Test</font></th>
					<th><font color="#000000">Points Earned</font></th>
				</tr>
			</thead>
			<?php
				$objDB->PopulateContributorQuestions($user_id);
			?>
			<tfoot>
				<tr>
					<th><font color="#000000">Question</font></th>
					<th><font color="#000000">Subject</font></th>
					<th><font color="#000000">Status</font></th>
					<th><font color="#000000">Chances of Acceptance</font></th>
					<th><font color="#000000">Used in Paid Test</font></th>
					<th><font color="#000000">Points Earned</font></th>
				</tr>
			</tfoot>
		</table>
		
		<script type="text/javascript">
			$(window).load(function(){
				$("#page_loading_box").hide();
				$("#result_analytics").show();
				$("#result_analytics").tabs();
				$("#dt_contrib_ques_status").show();
				
				var page_hgt = objUtils.AdjustHeight("dt_contrib_ques_status");
				$('#platform', window.parent.document).height(page_hgt+200);
			});
			
			objTbl = $('#dt_contrib_ques_status').dataTable( {
				"sPaginationType": "full_numbers",
				"oTableTools": {
		            "sRowSelect": "single"
		        },
		        "fnDrawCallback": function( oSettings ) {
		        	var page_hgt = objUtils.AdjustHeight("dt_contrib_ques_status");
		        	$('#platform', window.parent.document).height(page_hgt+200);
			    }
			} );
		</script>
	</body>
</html>