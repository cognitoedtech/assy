<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	include_once("../../lib/session_manager.php");
	include_once('../../database/mcat_db.php');
	
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
			/*demo page css*/
			body{ font: 75% "Trebuchet MS", sans-serif; margin: 5px; margin-left: 20px; margin-right: 20px; overflow:hidden;}
		</style>
	</head>
	<body>
		<div id="page_loading_box" style="position:fixed;top:100px;left:50%;zindex:200;">
			<img src="../../images/page_loading.gif" width="32" height="32"/>
		</div>
		<div id="page_title" style="display:none; margin-left: -15px; margin-right: -15px; margin-bottom: -10px;">
			<ul>
				<li><a href="#tab1">Reconsile Questions</a></li>
			</ul>
			<div id="tab1">
				
			</div>
		</div>
		
		<table cellpadding="0" cellspacing="0" border="0" width="100%" class="display" style="display:none;" id="id_dt_ques">
			<thead>
				<tr>
					<th><font color="#000000">Question</font></th>
					<th><font color="#000000">Option 1</font></th>
					<th><font color="#000000">Option 2</font></th>
					<th><font color="#000000">Option 3</font></th>
					<th><font color="#000000">Option 4</font></th>
					<th><font color="#000000">Answer</font></th>
					<th><font color="#000000">Subject</font></th>
					<th><font color="#000000">Topic</font></th>
					<th><font color="#000000">Difficulty</font></th>
					<th><font color="#000000">Explanation</font></th>
				</tr>
			</thead>
			<?php
				$objDB->PopulateQuestionsForCitation($user_id, $nUserType);
			?>
			<tfoot>
				<tr>
					<th><font color="#000000">Question</font></th>
					<th><font color="#000000">Option 1</font></th>
					<th><font color="#000000">Option 2</font></th>
					<th><font color="#000000">Option 3</font></th>
					<th><font color="#000000">Option 4</font></th>
					<th><font color="#000000">Answer</font></th>
					<th><font color="#000000">Subject</font></th>
					<th><font color="#000000">Topic</font></th>
					<th><font color="#000000">Difficulty</font></th>
					<th><font color="#000000">Explanation</font></th>
				</tr>
			</tfoot>
		</table>
				
		<script type="text/javascript">
			$(window).load(function(){
				$("#page_loading_box").hide();
				$("#page_title").show();
				$("#page_title").tabs();
				$("#id_dt_ques").show();
				
				var page_hgt = objUtils.AdjustHeight("id_dt_ques");
				$('#platform', window.parent.document).height(page_hgt+300);
			});
			
			editor = new $.fn.dataTable.Editor( {
		        "ajaxUrl": "edit_ques.php",
		        "domTable": "#id_dt_ques",
		        "fields": [ {
		                "label": "Question:",
		                "name": "question",
		                "type": "textarea"
		            }, {
		                "label": "Option 1:",
		                "name": "option_1"
		            }, {
		                "label": "Option 2:",
		                "name": "option_2"
		            }, {
		                "label": "Option 3:",
		                "name": "option_3"
		            }, {
		                "label": "Option 4:",
		                "name": "option_4"
		            }, {
		                "label": "Answer:",
		                "name": "answer",
		                "type": "select",
		                "ipOpts": [
		                    { "label": "Option 1", "value": "1" },
		                    { "label": "Option 2", "value": "2" },
		                    { "label": "Option 3", "value": "3" },
		                    { "label": "Option 4", "value": "4" }
		                ]
		            }, {
		                "label": "Subject:",
		                "name": "subject_id",
		                "type": "select",
		                "ipOpts": [
		                    { "label": "C Programming", "value": "1" },
		                    { "label": "C++ Programming", "value": "2" },
		                    { "label": "Operating System", "value": "3" },
		                    { "label": "Data Structure", "value": "4" }
		                ]
		            }, {
		                "label": "Topic:",
		                "name": "topic_id",
		                "type": "select",
		                "ipOpts": [
		                    { "label": "Inheritance", "value": "1" },
		                    { "label": "Input - Output", "value": "2" },
		                    { "label": "Declaration & Syntax", "value": "3" },
		                    { "label": "File System", "value": "4" }
		                ]
		            }, {
		                "label": "Difficulty:",
		                "name": "difficulty_id",
		                "type": "select",
		                "ipOpts": [
		                    { "label": "Easy", "value": "1" },
		                    { "label": "Modarate", "value": "2" },
		                    { "label": "Difficult", "value": "3" }
		                ]
		            }, {
		                "label": "Explaination:",
		                "name": "explanation",
		                "type": "textarea"
		            }
		        ]
		    } );
		    
			objTbl = $('#id_dt_ques').dataTable( {
				"sDom": 'T<"clear">lfrtip<"clear spacer">T',
				"sPaginationType": "full_numbers",
				"aoColumns": [
		            { "mDataProp": "question" },
		            { "mDataProp": "option_1" },
		            { "mDataProp": "option_2" },
		            { "mDataProp": "option_3" },
		            { "mDataProp": "option_4" },
		            { "mDataProp": "answer" },
		            { "mDataProp": "subject_id" },
		            { "mDataProp": "topic_id"},
		            { "mDataProp": "difficulty_id", "bVisible": false },
		            { "mDataProp": "explanation", "bVisible": false }
		        ],
				"oTableTools": {
		            "sRowSelect": "single",
		            "aButtons": [
		                { "sExtends": "editor_edit",   "editor": editor },
		                { "sExtends": "editor_remove", "editor": editor },
		                "csv",
		                "pdf"
		            ]
		        },
		        "bAutoWidth": false,
		        "fnDrawCallback": function( oSettings ) {
		        	var page_hgt = objUtils.AdjustHeight("id_dt_ques");
		        	$('#platform', window.parent.document).height(page_hgt+200);
			    }
			} );
		</script>
	</body>
</html>