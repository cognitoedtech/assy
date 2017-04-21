<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	include_once(dirname(__FILE__)."/../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../lib/billing.php");
	include_once(dirname(__FILE__).'/../../database/mcat_db.php');
	include_once(dirname(__FILE__)."/../../lib/include_js_css.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$objDB = new CMcatDB();
	$objBilling = new CBilling();
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	$time_zone = CSessionManager::Get(CSessionManager::FLOAT_TIME_ZONE);
	
	$org_id = $objDB->GetOrgIdByUserId($user_id);
	
	$personalQuesRate = $objBilling->GetPersonalQuesRate($user_id);
	
	$disabled_free_user_ids = $objDB->GetDisabledFreeUserIds($_POST['org_id']);
	
	$currency = $objBilling->GetCurrencyType($user_id);
	
	$currency_symbol = ($currency == "USD")?"$":"Rs."; 
	
	$candidate_list = $objDB->GetFreeUsersByOrgId($org_id);
	$objIncludeJsCSS = new IncludeJSCSS();
	
	$menu_id = CSiteConfig::UAMM_RESULT_ANALYTICS;
	$page_id = CSiteConfig::UAP_FREE_USER_RESULTS;
?>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="Generator" content="Mastishka Intellisys Private Limited">
<meta name="Author" content="Mastishka Intellisys Private Limited">
<meta name="Keywords" content="">
<meta name="Description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo(CConfig::SNC_SITE_NAME);?>: Free User Results</title>
<?php 
$objIncludeJsCSS->IncludeDatatablesBootstrapCSS("../../");
$objIncludeJsCSS->IncludeDatatablesResponsiveCSS("../../");
$objIncludeJsCSS->CommonIncludeCSS("../../");
$objIncludeJsCSS->IncludeIconFontCSS("../../");
$objIncludeJsCSS->IncludeFuelUXCSS ( "../../" );
$objIncludeJsCSS->CommonIncludeJS("../../");
$objIncludeJsCSS->IncludeJqueryDatatablesMinJS("../../");
$objIncludeJsCSS->IncludeDatatablesTabletoolsMinJS("../../");
$objIncludeJsCSS->IncludeDatatablesBootstrapJS("../../");
$objIncludeJsCSS->IncludeDatatablesResponsiveJS("../../");
?>
<style type="text/css">
	.modal, .modal.fade.in {
	    top: 15%;
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
		<div class='col-lg-9 col-md-9 col-sm-9' style="border-left: 1px solid #ddd; border-top: 1px solid #ddd;">
			<div class="fuelux modal1">
				<div class="preloader"><i></i><i></i><i></i><i></i></div>
			</div>
			<br />
			<div style="display: none;text-align: center;" id="error_div">
			</div><br /><br />
			<div id='TableToolsPlacement'>
			</div><br />
			<table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th data-class="expand" ><font color="#000000">Name</font></th>
						<th data-hide="phone,tablet"><font color="#000000">Email</font></th>
						<th data-hide="phone,tablet"><font color="#000000">Contact#</font></th>
						<th data-hide="phone,tablet"><font color="#000000">City</font></th>
						<th data-hide="phone,tablet"><font color="#000000">Test</font></th>
						<th data-hide="phone,tablet"><font color="#000000">Results</font></th>
						<th data-hide="phone,tablet"><font color="#000000"></font></th>
					</tr>
				</thead>
				<tbody id="tbl_free_user_tbody">
				<?php 
				foreach($candidate_list as $candidate_info)
				{
					echo "<tr>";
					echo $candidate_info['name'];
					echo $candidate_info['email'];
					echo $candidate_info['phone'];
					echo $candidate_info['city'];
					echo $candidate_info['test'];
					echo $candidate_info['result'];
					echo $candidate_info['enable_info'];
					echo "</tr>";
				}
				?>
				</tbody>
				<tfoot>
					<tr>
						<th data-class="expand" ><font color="#000000">Name</font></th>
						<th data-hide="phone,tablet"><font color="#000000">Email</font></th>
						<th data-hide="phone,tablet"><font color="#000000">Contact#</font></th>
						<th data-hide="phone,tablet"><font color="#000000">City</font></th>
						<th data-hide="phone,tablet"><font color="#000000">Test</font></th>
						<th data-hide="phone,tablet"><font color="#000000">Results</font></th>
						<th data-hide="phone,tablet"><font color="#000000"></font></th>
					</tr>
				</tfoot>
			</table><br /><br />
			
			<div class="modal" id="enable_cost_modal">
			  	<div class="modal-dialog">
			    	<div class="modal-content">
			      		<div class="modal-header">
			       		 	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			        		<h4 class="modal-title">Enable Information</h4>
			      		</div>
				      	<div class="modal-body" id="enable_info_modal_body">
				      	</div>
			      		<div class="modal-footer">
				        	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				        	<button type="button" class="btn btn-primary" onclick="EnableInfo();" id="btn_publish">Enable</button>
			      		</div>
			    	</div>
			  	</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
	'use strict';

	var disabled_free_users = <?php echo(count($disabled_free_user_ids));?>;
	var bEnableAll = false;
	var current_obj;

	 var table;
	$(document).ready(function () {
		DrawDataTable();
	});

	function DrawDataTable()
	{
		var responsiveHelper = undefined;
	    var breakpointDefinition = {
	        tablet: 1024,
	        phone : 480,
	    };

	    $.fn.dataTable.TableTools.buttons.custom_button = $.extend(
			    true,
			    $.fn.dataTable.TableTools.buttonBase,
			    {
			    	"sNewLine": "<br>",
			    }
		);
	    $.fn.dataTable.TableTools.defaults.sSwfPath = "<?php $objIncludeJsCSS->IncludeDatatablesCopy_CSV_XLS_PDF("../../");?>";
	    var tableElement = $('#example');
	    var table = tableElement.dataTable({
	    	"sDom": 'T<"clear">lfrtip<"clear spacer">T',
	    	"bPaginate": true,
	    	"bFilter": true,
			"aoColumns": [ 
				null,
				null,
				null,
				null,
				null,
				null,
				null
			],
	    	"oTableTools": {
	            "aButtons": [
	                {
					    "sExtends": "csv",
					    "mColumns": [ 0, 1, 2, 3, 4 ]
					},
					{
						"sExtends":    "custom_button",
						"sButtonText": "Enable All",
						"fnClick": function() {
							bEnableAll = true;
							$("#enable_info_modal_body").html("The amount worth <?php echo($currency_symbol);?><?php echo($personalQuesRate);?> per user information will be deducted from your account for getting information of all users.");
							$("#enable_cost_modal").modal('show');
						}
					}
	            ]
	        },
	        autoWidth      : false,
	        //ajax           : './arrays.txt',
	        preDrawCallback: function () {
	            // Initialize the responsive datatables helper once.
	            if (!responsiveHelper) {
	                responsiveHelper = new ResponsiveDatatablesHelper(tableElement, breakpointDefinition);
	            }
	            var oTableTools = TableTools.fnGetInstance( 'example' );
	            $('#TableToolsPlacement').before( oTableTools.dom.container );
	        },
	        rowCallback    : function (nRow) {
	            responsiveHelper.createExpandIcon(nRow);
	        },
	        drawCallback   : function (oSettings) {
	            responsiveHelper.respond();
	        }
	    });
	}

	function ShowInfoModal(obj, bAll)
	{
		current_obj = obj;	
		bEnableAll = false;
		$("#enable_info_modal_body").html("The amount worth <?php echo($currency_symbol);?><?php echo($personalQuesRate);?> will be deducted from your account for getting information of this user.");
		$("#enable_cost_modal").modal('show');
	}

	function EnableInfo()
	{
		$("#enable_cost_modal").modal('hide');
		if(!bEnableAll)
		{
			var free_user_id = $(current_obj).attr("free_user_id");

			$(".modal1").show();
			
			$.ajax({
				url: 'ajax/ajax_enable_free_users_info.php',
				data: {'free_user_id' : free_user_id, 'org_id' : '<?php echo($org_id);?>'}, 
				type: 'POST',
				dataType: 'json',
				success: function(data) {
					
					$("#example").dataTable().fnDestroy();
					$('#tbl_free_user_tbody').empty();

					var tbody_html = "";

					$("#error_div").hide();
					$.each(data, function(key,value){
						if(key != "error")
						{
							tbody_html += "<tr>";
							tbody_html += value["name"];
							tbody_html += value["email"];
							tbody_html += value["phone"];
							tbody_html += value["city"];
							tbody_html += value["test"];
							tbody_html += value["result"];
							tbody_html += value["enable_info"];
							tbody_html += "</tr>";
						}
						else
						{
							$("#error_div").html(value);
							$("#error_div").show();
						}
					});

					//alert(tbody_html);
					$('#tbl_free_user_tbody').html(tbody_html);
					DrawDataTable();
					$(".modal1").hide();
				} 
			});
		}
		else
		{
			$(".modal1").show();
			
			$.ajax({
				url: 'ajax/ajax_enable_free_users_info.php',
				data: {'bAll' : 1, 'org_id' : '<?php echo($org_id);?>'}, 
				type: 'POST',
				dataType: 'json',
				success: function(data) {
					
					$("#example").dataTable().fnDestroy();
					$('#tbl_free_user_tbody').empty();

					var tbody_html = "";

					$("#error_div").hide();
					$.each(data, function(key,value){
						if(key != "error")
						{
							tbody_html += "<tr>";
							tbody_html += value["name"];
							tbody_html += value["email"];
							tbody_html += value["phone"];
							tbody_html += value["city"];
							tbody_html += value["test"];
							tbody_html += value["result"];
							tbody_html += value["enable_info"];
							tbody_html += "</tr>";
						}
						else
						{
							$("#error_div").html(value);
							$("#error_div").show();
						}
					});

					//alert(tbody_html);
					$('#tbl_free_user_tbody').html(tbody_html);
					DrawDataTable();
					$(".modal1").hide();
				} 
			});
		}
	}
	</script>
</body>
</html>
<?php 
if(false)
{
?>
 <html>
	<head>
		<title> My Account </title>
		<style type="text/css" title="currentStyle">
			@import "../css/redmond/jquery-ui-1.9.0.custom.min.css";
			@import "media/css/demo_table.css";
			@import "media/css/TableTools.css";
			@import "media/css/dataTables.editor.css";
		</style>
		<link rel="stylesheet" type="text/css" href="../css/mipcat.css" />
		<link rel="stylesheet" type="text/css" href="../lib/bootstrap/css/bootstrap.css" />
		<script type="text/javascript" charset="utf-8" src="media/js/jquery.js"></script>
		<script type="text/javascript" charset="utf-8" src="media/js/jquery.dataTables.js"></script>
		<script type="text/javascript" charset="utf-8" src="media/js/ZeroClipboard.js"></script>
		<script type="text/javascript" charset="utf-8" src="media/js/TableTools.js"></script>
		<script type="text/javascript" src="../lib/wizard/js/jquery.validate.min.js"></script>
		<script type="text/javascript" charset="utf-8" src="../js/jquery-ui-custom.min.js"></script>
		<script type="text/javascript" charset="utf-8" src="../js/mipcat/utils.js"></script>
		<script type="text/javascript" charset="utf-8" src="../lib/bootstrap/js/bootstrap.js"></script>
		<style type="text/css">
			/*demo page css*/
			body{ font: 75% "Trebuchet MS", sans-serif; margin: 5px; overflow:hidden;}
			.modal1 {
			    display:    none;
			    position:   fixed;
			    z-index:    1000;
			    top:        0;
			    left:       0;
			    height:     100%;
			    width:      100%;
			    background: rgba( 255, 255, 255, .8 ) 
			                url('../images/page_loading.gif') 
			                50% 200px 
			                no-repeat;
			}
			body.loading {
			    overflow: hidden;   
			}
			body.loading .modal1 {
			    display: block;
			}
			#tbl_free_user_info th {
				text-align : left;	
			}
		</style>
	</head>
	<body>
		<div id="page_loading_box" style="position:absolute;top:100px;left:50%;zindex:200;">
			<img src="../images/page_loading.gif" width="32" height="32"/>
		</div>
		<div id="page_title" style="display:none">
			<ul>
				<li><a href="#tab1">Free User Results</a></li>
			</ul><br /><br />
			<div style="display: none;text-align: center;" id="error_div">
			</div><br /><br />
			<div id="tab1" style="font: 90% 'Trebuchet MS', sans-serif;">
				<table cellpadding="0" cellspacing="0" border="0" width="100%" class="display" id="tbl_free_user_info">
					<thead>
						<tr>
							<th><font color="#000000">Name</font></th>
							<th><font color="#000000">Email</font></th>
							<th><font color="#000000">Contact#</font></th>
							<th><font color="#000000">City</font></th>
							<th><font color="#000000">Test</font></th>
							<th><font color="#000000">Results</font></th>
							<th><font color="#000000"></font></th>
						</tr>
					</thead>
					<tbody id="tbl_free_user_tbody">
					<?php 
					foreach($candidate_list as $candidate_info)
					{
						echo "<tr>";
						echo $candidate_info['name'];
						echo $candidate_info['email'];
						echo $candidate_info['phone'];
						echo $candidate_info['city'];
						echo $candidate_info['test'];
						echo $candidate_info['result'];
						echo $candidate_info['enable_info'];
						echo "</tr>";
					}
					?>
					</tbody>
					<tfoot>
						<tr>
							<th><font color="#000000">Name</font></th>
							<th><font color="#000000">Email</font></th>
							<th><font color="#000000">Contact#</font></th>
							<th><font color="#000000">City</font></th>
							<th><font color="#000000">Test</font></th>
							<th><font color="#000000">Results</font></th>
							<th><font color="#000000"></font></th>
						</tr>
					</tfoot>
				</table>
				<div id="enable_cost_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
					<div class="modal-header">
						<h3>
							Enable Information
							<button type="button" class="close"  id="cancel" data-dismiss="modal" aria-hidden="true">&times;</button>
						</h3>
					</div>
					<div class="modal-body" id="enable_info_modal_body">
					</div>
					<div class="modal-footer">
						<button class="btn" id="cancel1" data-dismiss="modal" aria-hidden="true">Close</button>
						<button type="button" class="btn btn-primary" onclick="EnableInfo();" id="btn_publish">Enable</button>
					</div>
				</div>
			</div>
		</div>
		<script type="text/javascript">	

			var disabled_free_users = <?php echo(count($disabled_free_user_ids));?>;
			var bEnableAll = false;
			var current_obj;
			TableTools.BUTTONS.custom_button = $.extend( true, TableTools.buttonBase, {
				"sNewLine": "<br>",
				"sButtonText": "Enable All",
				"fnClick": function() {
					bEnableAll = true;
					$("#enable_info_modal_body").html("The amount worth <?php echo($currency_symbol);?><?php echo($personalQuesRate);?> per user information will be deducted from your account for getting information of all users.");
					$("#enable_cost_modal").modal('show');
				}
			} );
				
			objTbl = $('#tbl_free_user_info').dataTable( {
				"sDom": 'T<"clear">lfrtip<"clear spacer">T',
				"sPaginationType": "full_numbers",
				"bFilter": true,
				"aoColumns": [ 
					null,
					null,
					null,
					null,
					null,
					null,
					null
				],
				"oTableTools": {
		            "aButtons": [
		                {
						    "sExtends": "csv",
						    "mColumns": [ 0, 1, 2, 3, 4 ]
						},
						{
							"sExtends":    "custom_button",
							"sButtonText": "Enable All",
						}
		            ]
		        },
		        "fnDrawCallback": function( oSettings ) {
		        	var page_hgt = objUtils.AdjustHeight("tab1");
		        	$('#platform', window.parent.document).height(page_hgt+200);
			    }
			} );

			$(window).load(function(){
				$("#page_loading_box").hide();
				$("#page_title").show();
				$("#page_title").tabs();
				
				var page_hgt = objUtils.AdjustHeight("tbl_free_user_info");
				$('#platform', window.parent.document).height(page_hgt+300);
			});

			function ShowInfoModal(obj, bAll)
			{
				current_obj = obj;	
				bEnableAll = false;
				$("#enable_info_modal_body").html("The amount worth <?php echo($currency_symbol);?><?php echo($personalQuesRate);?> will be deducted from your account for getting information of this user.");
				$("#enable_cost_modal").modal('show');
			}

			function EnableInfo()
			{
				$("#enable_cost_modal").modal('hide');
				if(!bEnableAll)
				{
					var free_user_id = $(current_obj).attr("free_user_id");
	
					$('body').on({
					    ajaxStart: function() { 
					    	$(this).addClass("loading"); 
					    },
					    ajaxStop: function() { 
					    	$(this).removeClass("loading"); 
					    }    
					});
					
					$.ajax({
						url: 'ajax/ajax_enable_free_users_info.php',
						data: {'free_user_id' : free_user_id, 'org_id' : '<?php echo($org_id);?>'}, 
						type: 'POST',
						dataType: 'json',
						success: function(data) {
							
							$("#tbl_free_user_info").dataTable().fnDestroy();
							$('#tbl_free_user_tbody').empty();
	
							var tbody_html = "";
	
							$("#error_div").hide();
							$.each(data, function(key,value){
								if(key != "error")
								{
									tbody_html += "<tr>";
									tbody_html += value["name"];
									tbody_html += value["email"];
									tbody_html += value["phone"];
									tbody_html += value["city"];
									tbody_html += value["test"];
									tbody_html += value["result"];
									tbody_html += value["enable_info"];
									tbody_html += "</tr>";
								}
								else
								{
									$("#error_div").html(value);
									$("#error_div").show();
								}
							});
	
							//alert(tbody_html);
							$('#tbl_free_user_tbody').html(tbody_html);
							objTbl = $('#tbl_free_user_info').dataTable( {
								"sDom": 'T<"clear">lfrtip<"clear spacer">T',
								"sPaginationType": "full_numbers",
								"bFilter": true,
								"aoColumns": [ 
									null,
									null,
									null,
									null,
									null,
									null,
									null
								],
								"oTableTools": {
						            "aButtons": [
						                {
										    "sExtends": "csv",
										    "mColumns": [ 0, 1, 2, 3, 4 ]
										},
										{
											"sExtends":    "custom_button",
											"sButtonText": "Enable All",
										}
						            ]
						        },
						        "fnDrawCallback": function( oSettings ) {
						        	var page_hgt = objUtils.AdjustHeight("tab1");
						        	$('#platform', window.parent.document).height(page_hgt+200);
							    }
							} );
						} 
					});
				}
				else
				{
					$('body').on({
					    ajaxStart: function() { 
					    	$(this).addClass("loading"); 
					    },
					    ajaxStop: function() { 
					    	$(this).removeClass("loading"); 
					    }    
					});
					
					$.ajax({
						url: 'ajax/ajax_enable_free_users_info.php',
						data: {'bAll' : 1, 'org_id' : '<?php echo($org_id);?>'}, 
						type: 'POST',
						dataType: 'json',
						success: function(data) {
							
							$("#tbl_free_user_info").dataTable().fnDestroy();
							$('#tbl_free_user_tbody').empty();

							var tbody_html = "";

							$("#error_div").hide();
							$.each(data, function(key,value){
								if(key != "error")
								{
									tbody_html += "<tr>";
									tbody_html += value["name"];
									tbody_html += value["email"];
									tbody_html += value["phone"];
									tbody_html += value["city"];
									tbody_html += value["test"];
									tbody_html += value["result"];
									tbody_html += value["enable_info"];
									tbody_html += "</tr>";
								}
								else
								{
									$("#error_div").html(value);
									$("#error_div").show();
								}
							});

							//alert(tbody_html);
							$('#tbl_free_user_tbody').html(tbody_html);
							objTbl = $('#tbl_free_user_info').dataTable( {
								"sDom": 'T<"clear">lfrtip<"clear spacer">T',
								"sPaginationType": "full_numbers",
								"bFilter": true,
								"aoColumns": [ 
									null,
									null,
									null,
									null,
									null,
									null,
									null
								],
								"oTableTools": {
						            "aButtons": [
						                {
										    "sExtends": "csv",
										    "mColumns": [ 0, 1, 2, 3, 4 ]
										},
										{
											"sExtends":    "custom_button",
											"sButtonText": "Enable All",
										}
						            ]
						        },
						        "fnDrawCallback": function( oSettings ) {
						        	var page_hgt = objUtils.AdjustHeight("tab1");
						        	$('#platform', window.parent.document).height(page_hgt+200);
							    }
							} );
						} 
					});
				}
			}
		</script>
		<div class="modal1"></div>
	</body>
</html>
<?php 
}
?>
			