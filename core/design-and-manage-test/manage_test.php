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
							<th data-hide="phone,tablet"><font color="#000000">Publish Test</font></th>
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
							<th data-hide="phone,tablet"><font color="#000000">Publish Test</font></th>
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
			
			<div id="publish_test_box" class="modal">
				<div class="modal-dialog">
			    	<div class="modal-content">
						<form class='form-horizontal' id="publish_test" name="form_publish_test">
							<div class="modal-header">
								<button type="button" class="close"  id="cancel" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h3 id="header_test_name"> </h3>
							</div>
							<div class="modal-body" id="request_modal_body">
								<div id="publish_test_form_content">
									<div class="form-group">
									    <label for="publish_keywords" class="col-lg-4 control-label">Keywords :</label>
									    <div class="col-lg-6">
									    	<input class="form-control" id="publish_keywords" name="publish_keywords" type="text" />
										</div>
									</div>
									<div class="form-group">
									    <label for="publish_test_desc" class="col-lg-4 control-label">Description :</label>
									    <div class="col-lg-6">
									    	<textarea class="form-control" rows="3" id="publish_test_desc" name="publish_test_desc"></textarea>
									    	<input type="hidden" id="publish_test_id" name="pub_test_id">
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button class="btn btn-default" id="cancel1" data-dismiss="modal" aria-hidden="true">Close</button>
								<button type="submit" class="btn btn-primary" id="btn_publish">Publish</button>
							</div>
						</form>
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

			    jQuery.validator.addMethod("ValidateKeyword", function(value, element) {
					if(/^,.*,$/.test(value) || value.trim() == "," || /^.*,$/.test(value) || /^,.*/.test(value))
					{
		    			return false;
					}
					else
					{
		    			return true;
					}
				}, "<span style='color:red;'>* Comma is not allowed in starting and ending!</style>");
				
				$("#publish_test").validate({
	        		rules: {
	        			publish_keywords: {
	                		required:true,
	           		 		'ValidateKeyword':true
	            		},
	            		publish_test_desc: {
	                		required:true,
	                		maxlength: 160
	                	}
	        		},
	        		messages: {
	        			publish_keywords: {	
	        				required:	"<span style='color:red'>* Please Enter Keywords!</span>",
	        				
	            		},
	            		publish_test_desc:{
	        				required:	"<span style='color:red'>* Please Provide test Description</span>",
	        				maxlength:	"<span style='color:red'>* Maximum Length of Description Should be 150</span>"
	    				}
	    	    	},
	    	    	submitHandler: function(form) {
	    				//$('#publish_test_box').modal('modal');
	    	    		$(".modal1").show();
	    				$('#publish_test').ajaxSubmit(options);
	    				$("#"+check_box_id).attr("made_publish", "1");
	    				var test_id = $("#"+check_box_id).attr('test_id');
	    				$("#"+test_id+"_keywords").html($("#publish_keywords").val());
	    				$("#"+test_id+"_description").html($("#publish_test_desc").val());
	    				$("#"+test_id+"_copy").show();
	    				$('#publish_test_box').modal('hide');
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

		var options = { 
	       	 	//target:        '',   // target element(s) to be updated with server response 
	       		// beforeSubmit:  showRequest,  // pre-submit callback 
	      	 	success:       showResponse,  // post-submit callback 
	 			
	        	// other available options: 
	        	url:      'ajax/ajax_publish_test.php',        // override for form's 'action' attribute
	        	
	        	type:      'POST',       // 'get' or 'post', override for form's 'method' attribute 

	        	data: {publish : '1'},
	        	//dataType:  null        // 'xml', 'script', or 'json' (expected server response type) 
	        	clearForm: true        // clear all form fields after successful submit 
	        	//resetForm: true        // reset the form after successful submit 
	 
	        	// $.ajax options can be used here too, for example: 
	        	//timeout:   3000 
	    	};

		var check_box_id = "";
		function OnPublish(obj)
		{
			if ($(obj).is(':checked',true)){
				var test_id = $(obj).attr('test_id');
				$("#publish_test").validate().resetForm();
				$("#publish_test_id").val($(obj).attr('test_id')+'');
				$("#header_test_name").text('Publish '+$(obj).attr('test_name')+'');
				$("#publish_keywords").val($("#"+test_id+"_keywords").html()+'');
				$("#publish_test_desc").val($("#"+test_id+"_description").html()+'');
				$('#publish_test_box').modal('show'); 		
			}
			else{

					$("#publish_test_id").val($(obj).attr('test_id')+'');
					var test_id= $("#publish_test_id").val();

					$(".modal1").show();
					$.post("ajax/ajax_publish_test.php",{'unpublish':0,'test_id':test_id},function(data){
						$(".modal1").hide();
						$("#"+check_box_id).attr("made_publish", "0");
						$("#"+test_id+"_copy").hide();
					});
				}
			check_box_id = $(obj).attr("id");
		}

		$('#publish_test_box').on('hidden.bs.modal', function () {
			 if($("#"+check_box_id).attr("made_publish") == "0")
			 {
			 	$("#"+check_box_id).prop("checked", false);
			 }
			 check_box_id ="";
		});

		

		var clipboard = new Clipboard('.btn-copy-link');

		clipboard.on('success', function(e) {
			$.Notify({
				 caption: "Test Link Copied",
				 content: "<b>"+$(e.trigger).attr("test_name")+"</b> URL <b>"+e.text+"</b> is copied to clipboard!",
				 style: {background: 'green', color: '#fff'}, 
				 timeout: 5000
				 });
		});
	</script>
</body>
</html>