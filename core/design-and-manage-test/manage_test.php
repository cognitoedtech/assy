<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	include_once("../../lib/session_manager.php");
	include_once("../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/../../lib/include_js_css.php");
	include_once(dirname(__FILE__)."/../../lib/site_config.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$objDB = new CMcatDB();
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	$time_zone = CSessionManager::Get(CSessionManager::FLOAT_TIME_ZONE);
	
	$objIncludeJsCSS = new IncludeJSCSS();
	
	$menu_id = CSiteConfig::UAMM_DESIGN_MANAGE_TEST;
	$page_id = CSiteConfig::UAP_MANAGE_TEST;
?>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="Generator" content="Mastishka Intellisys Private Limited">
<meta name="Author" content="Mastishka Intellisys Private Limited">
<meta name="Keywords" content="">
<meta name="Description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo(CConfig::SNC_SITE_NAME); ?>: Manage Test</title>
<?php 
$objIncludeJsCSS->IncludeDatatablesBootstrapCSS("../../");
$objIncludeJsCSS->IncludeDatatablesResponsiveCSS("../../");
$objIncludeJsCSS->CommonIncludeCSS("../../");
$objIncludeJsCSS->IncludeIconFontCSS("../../");
$objIncludeJsCSS->IncludeMipcatCSS("../../");
$objIncludeJsCSS->IncludeFuelUXCSS ( "../../" );
$objIncludeJsCSS->CommonIncludeJS("../../","1.8.2");
$objIncludeJsCSS->IncludeJqueryDatatablesMinJS("../../");
$objIncludeJsCSS->IncludeDatatablesTabletoolsMinJS("../../");
$objIncludeJsCSS->IncludeDatatablesBootstrapJS("../../");
$objIncludeJsCSS->IncludeDatatablesResponsiveJS("../../");
$objIncludeJsCSS->IncludeJqueryFormJS("../../");
$objIncludeJsCSS->IncludeJqueryValidateMinJS("../../");
$objIncludeJsCSS->IncludeClipboardJS ( "../../" );
$objIncludeJsCSS->IncludeMetroNotificationJS(CSiteConfig::ROOT_URL."/");
?>
<style type="text/css">

	#overlay { position: fixed; left: 0px; top: 0px; width: 100%; height: 100%; z-index: 100; background-color:white;}
	
	.modal, .modal.fade.in {
	    top: 15%;
	}
	.js-responsive-table thead{font-weight: bold}	
	.js-responsive-table td{ -moz-box-sizing: border-box; -webkit-box-sizing: border-box;-o-box-sizing: border-box;-ms-box-sizing: border-box;box-sizing: border-box;padding: 0px;}
	.js-responsive-table td span{display: none}		
	
	@media all and (max-width:767px){
		.js-responsive-table{width: 100%;max-width: 400px;}
		.js-responsive-table thead{display: none}
		.js-responsive-table td{width: 100%;display: block}
		.js-responsive-table td span{float: left;font-weight: bold;display: block}
		.js-responsive-table td span:after{content:' : '}
		.js-responsive-table td{border:0px;border-bottom:1px solid #ddd}	
		.js-responsive-table tr:last-child td:last-child{border: 0px}		
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
	include_once(dirname(__FILE__)."/../../lib/header.php");
	?>

	<!-- --------------------------------------------------------------- -->
	<br />
	<br />
	<br />
	<div class='row-fluid'>
		<div class="col-sm-3 col-md-3 col-lg-3">
			<?php 
			include_once(dirname(__FILE__)."/../../lib/sidebar.php");
			?>
		</div>
		<div class="col-sm-9 col-md-9 col-lg-9" style="border-left: 1px solid #ddd; border-top: 1px solid #ddd;">
			<div class="fuelux modal1">
				<div class="preloader"><i></i><i></i><i></i><i></i></div>
			</div>
			<br />
			<div id='TableToolsPlacement'>
			</div><br />
		    <div class="form-inline">
		        <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
		        	<thead>
						<tr>
							<th data-class="expand" ><font color="#000000">Test Name</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Test Nature</font></th>
							<th data-hide="phone,tablet"><font color="#000000"># Questions</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Question Source</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Create Date</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Last Modified</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Preview Test (free)</font></th>
							<th data-hide="phone,tablet"><font color="#000000">View Details</font></th>
							<!-- <th data-hide="phone,tablet"><font color="#000000">Publish Test</font></th> -->
						</tr>
					</thead>
					<?php
						$objDB->PopulateTests($user_id, $time_zone);
					?>
					<tfoot>
						<tr>
							<th data-class="expand" ><font color="#000000">Test Name</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Test Nature</font></th>
							<th data-hide="phone,tablet"><font color="#000000"># Questions</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Question Source</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Create Date</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Last Modified</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Preview Test (free)</font></th>
							<th data-hide="phone,tablet"><font color="#000000">View Details</font></th>
							<!-- <th data-hide="phone,tablet"><font color="#000000">Publish Test</font></th> -->
						</tr>
					</tfoot>
		        </table>
		    </div>
		    <div class="modal" id="test_details_modal">
			  	<div class="modal-dialog">
			    	<div class="modal-content">
			    		<div class="modal-body" id="test_details_modal_body">
			    		</div>
			    		<div class="modal-footer">
			      			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			    		</div>
			    	</div>
			  	</div>
			</div>
			<div class="modal" id="delete_test_modal">
			  	<div class="modal-dialog">
			    	<div class="modal-content">
			      		<div class="modal-header">
			       		 	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			        		<h4 class="modal-title">Delete Test</h4>
			      		</div>
				      	<div class="modal-body" id="delete_test_modal_body">
				      	</div>
			      		<div class="modal-footer">
				        	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				        	<button type="button" id="delete_btn" class="btn btn-primary" onclick="DeleteTest();">Delete</button>
			      		</div>
			    	</div>
			  	</div>
			</div>
			<?php
			include_once (dirname ( __FILE__ ) . "/../../lib/footer.php");
			?>
		</div>
	</div>
	<script type="text/javascript">
		var row_count = 0;
		var delete_test_id;
		var table;
		$(document).ready(function () {
			'use strict';
	
			var table;
			var tableElement;
			var responsiveHelper = undefined;
			var breakpointDefinition = {
			        tablet: 1024,
			        phone : 480
			    };

			TableTools.BUTTONS.custom_button = $.extend( true, TableTools.buttonBase, {
				"sNewLine": "<br>",
				"sButtonText": "Delete",
				"fnClick": function() {
					if(row_count != 0)
					{
						$("#delete_test_modal_body").html("Do you want to delete selected test?");
						$("#delete_btn").show();
						$("#delete_test_modal").modal("show");
					}
					else
					{
						$("#delete_test_modal_body").html("Please select the test to delete.");
						$("#delete_btn").hide();
						$("#delete_test_modal").modal("show");
					}
				}
			} );
			
			$.fn.dataTable.TableTools.defaults.sSwfPath = "<?php $objIncludeJsCSS->IncludeDatatablesCopy_CSV_XLS_PDF("../../");?>";
			    tableElement = $('#example');
			    table = tableElement.dataTable({
			    	"sDom": 'T<"clear">lfrtip<"clear spacer">T',
			    	"bPaginate": true,
			    	"bFilter": true,
			    	"oTableTools": {
			    		"sRowSelect": "single",
			            "aButtons": [
				            {
							    "sExtends": "csv",
							    "mColumns": [ 0, 1, 2, 3, 4, 5 ]
							},
							{
							    "sExtends": "pdf",
							    "mColumns": [ 0, 1, 2, 3, 4, 5 ]
							},
				            {
								"sExtends":    "custom_button",
								"sButtonText": "Delete",
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
				        //alert("hello");
			            responsiveHelper.respond();
			            $('#example tbody').on( 'click', 'tr', function () {
			            	if( $(this).hasClass('active') ) {
			            		row_count = 1;
			            		delete_test_id = $(this).attr("id");
			                }
			            	else
			            	{
			            		row_count = 0;
				            }
			            } );
			        }
			    });
		});

		function DeleteTest()
		{
			$(".modal1").show();
			
			$("#delete_test_modal").modal("hide");
			$.post("ajax/ajax_delete_test.php",{"action": "remove", "data": [delete_test_id]},function(){
				$("#example").dataTable().api().rows( ".active" )
		        .remove()
		        .draw();

				$(".modal1").hide();
			});
		}

		function OnTestDetails(test_id)
		{
			$(".modal1").show();
			
			$("#test_details_modal_body").load("../ajax/ajax_test_details.php?test_id="+test_id, function(){
				$("#test_details_modal").modal("show"); 
				$(".modal1").hide();
			});
		}

		function ShowOverlay(url, div_id)
		{
			$("#sidebar").hide();
			$("#header").hide();
			$("#minimized_ckeditor_panel").removeClass( "minimized-shown" ).addClass( "minimized-hidden" );
			
			var current_date = new Date();
		    var time_zone = -current_date.getTimezoneOffset() / 60;
		    
			var height	  = $(window).height();
			$("#overlay_frame").attr("src",url+"&time_zone="+time_zone+"&height="+height).ready(function(){
				$("#overlay").show(800);
				$("body").css("overflow", "hidden");
			});
			
			RemoveTest.div_id = div_id;
		}
		
		function HideOverlay()
		{
			$("#overlay").hide(500);
			$("#minimized_ckeditor_panel").removeClass( "minimized-hidden" ).addClass( "minimized-shown" );
			$("#sidebar").show();
			$("#header").show();
			$("body").css("overflow", "auto");
		}
		
		function RemoveTest()
		{
			console.log("Test Removed");
		}

		function showResponse(responseText, statusText, xhr, form)
		{
			$(".modal1").hide();
		}
	</script>
</body>
</html>