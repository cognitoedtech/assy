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
	
	$user_type = CSessionManager::Get(CSessionManager::INT_USER_TYPE);
?>
<html>
	<head>
		<title> Super Admin </title>
		<style type="text/css" title="currentStyle">
			@import "../../css/redmond/jquery-ui-1.9.0.custom.min.css";
		</style>
		<link rel="stylesheet" type="text/css" href="../../3rd_party/bootstrap/css/bootstrap.css" />
		<script type="text/javascript" charset="utf-8" src="../../js/jquery.js"></script>
		<script type="text/javascript" charset="utf-8" src="../../js/jquery-ui-custom.min.js"></script>
		<script type="text/javascript" charset="utf-8" src="../../js/mipcat/utils.js"></script>
		<style type="text/css">
			/*demo page css*/
			body{ font: 70% "Trebuchet MS", sans-serif; margin: 5px; overflow:hidden;}
			.column { width: 300px; float: left; padding-bottom: 100px; float:left;}
			.portlet { margin: 0 1em 1em 0; }
			.portlet-header { margin: 0.3em; padding-bottom: 4px; padding-left: 0.2em; }
			.portlet-header .ui-icon { float: right; }
			.portlet-content { padding: 0.4em; }
			.ui-sortable-placeholder { border: 1px dotted black; visibility: visible !important; height: 50px !important; }
			.ui-sortable-placeholder * { visibility: hidden; }
		</style>
		<link rel="stylesheet" type="text/css" href="../../css/mipcat.css" />
		<script>
			$(function() {
				$( ".column" ).sortable({
					connectWith: ".column"
				});
		
				$( ".portlet" ).addClass( "ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" )
					.find( ".portlet-header" )
						.addClass( "ui-widget-header ui-corner-all" )
						.prepend( "<span class='ui-icon ui-icon-minusthick'></span>")
						.end()
					.find( ".portlet-content" );
		
				$( ".portlet-header .ui-icon" ).click(function() {
					$( this ).toggleClass( "ui-icon-minusthick" ).toggleClass( "ui-icon-plusthick" );
					$( this ).parents( ".portlet:first" ).find( ".portlet-content" ).toggle();
				});
		
				$( ".column" ).disableSelection();
			});
			
			function TestOver(div_id)
			{
				$("#"+div_id).empty();
			}
		</script>
	</head>
	<body>
		<div id="page_loading_box" style="position:absolute;top:100px;left:50%;zindex:200;">
			<img src="../../images/page_loading.gif" width="32" height="32"/>
		</div>
		<div id="page_title" style="display:none">
			<ul>
				<li><a href="#tab1">Scheduled Test Packages</a></li>
			</ul>
			<div id="tab1" style="font: 90% 'Trebuchet MS', sans-serif;">
				<div>
					<?php
						$objDB->ListTestPackages($user_id);
					?>
				</div>
			</div>
		</div>
		
		<script type="text/javascript">
			$(window).load(function(){
				$("#page_loading_box").hide();
				$("#page_title").show();
				$("#page_title").tabs();
				
				var page_hgt = objUtils.AdjustHeight("tab1");
				$('#platform', window.parent.document).height(page_hgt+200);
			});
		</script>
	</body>
</html>
