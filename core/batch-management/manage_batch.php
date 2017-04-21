<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	include_once(dirname(__FILE__)."/../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/../../lib/include_js_css.php");
	include_once(dirname(__FILE__)."/../../lib/site_config.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$objDB = new CMcatDB();
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	
	$objIncludeJsCSS = new IncludeJSCSS();
	
	$menu_id = CSiteConfig::UAMM_BATCH_MANAGEMENT;
	$page_id = CSiteConfig::UAP_MANAGE_BATCH;
?>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="Generator" content="Mastishka Intellisys Private Limited">
<meta name="Author" content="Mastishka Intellisys Private Limited">
<meta name="Keywords" content="">
<meta name="Description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo(CConfig::SNC_SITE_NAME); ?>: Manage Batch</title>
<?php 
$objIncludeJsCSS->IncludeDatatablesBootstrapCSS("../../");
$objIncludeJsCSS->IncludeDatatablesResponsiveCSS("../../");
$objIncludeJsCSS->CommonIncludeCSS("../../");
$objIncludeJsCSS->IncludeIconFontCSS("../../");
$objIncludeJsCSS->IncludeMipcatCSS("../../");
$objIncludeJsCSS->IncludeFuelUXCSS ( "../../" );
$objIncludeJsCSS->CommonIncludeJS("../../");
$objIncludeJsCSS->IncludeJqueryDatatablesMinJS("../../");
$objIncludeJsCSS->IncludeDatatablesTabletoolsMinJS("../../");
$objIncludeJsCSS->IncludeDatatablesBootstrapJS("../../");
$objIncludeJsCSS->IncludeDatatablesResponsiveJS("../../");
$objIncludeJsCSS->IncludeJqueryValidateMinJS("../../");
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
		<div class="col-sm-3 col-md-3 col-lg-3">
			<?php 
			include_once(dirname(__FILE__)."/../../lib/sidebar.php");
			?>
		</div>
		<div class='col-sm-9 col-md-9 col-lg-9' style="border-left: 1px solid #ddd; border-top: 1px solid #ddd;">
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
							<th data-class="expand" ><font color="#000000">Batch Name</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Number of Candidates</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Description</font></th>
						</tr>
					</thead>
					<tbody>
						<?php 
							$objDB->PopulateBatches($user_id);
						?>
					</tbody>
					<tfoot>
						<tr>
							<th data-class="expand" ><font color="#000000">Batch Name</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Number of Candidates</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Description</font></th>
						</tr>
					</tfoot>
				</table>
		    </div><br /><br />
		    <div class="modal" id="edit_new_modal">
			  	<div class="modal-dialog">
			    	<div class="modal-content">
			    		<form id="edit_new_form" class="form-horizontal">
				      		<div class="modal-header">
				       		 	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				        		<h4 id='modal-title' class="modal-title"></h4>
				      		</div>
					      	<div class="modal-body">
					      		<div class="row-fluid">
						      		<div class="form-group">
								      <label for="batch_name" class="col-sm-3 col-md-3 col-lg-3 control-label">Batch Name :</label>
								      <div class="col-sm-6 col-md-6 col-lg-6">
								        <input class="form-control" id="batch_name" name='batch_name' type="text">
								      </div>
								    </div>
								    <div class="form-group">
								      <label for="description" class="col-sm-3 col-md-3 col-lg-3 control-label">Description :</label>
								      <div class="col-sm-6 col-md-6 col-lg-6">
								        <textarea class="form-control" rows="3" id="description" name='description'></textarea>
								      </div>
								    </div>
							    </div>
					      	</div>
				      		<div class="modal-footer">
					        	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					        	<button type="submit" id="delete_btn" class="btn btn-primary">Save</button>
				      		</div>
			      		</form>
			    	</div>
			  	</div>
			</div>
			
			<div class="modal" id="delete_batch_modal">
			  	<div class="modal-dialog">
			    	<div class="modal-content">
			    		<div class="modal-header">
				       	 	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				        	<h4 id='modal-title' class="modal-title">Delete Batch</h4>
				      	</div>
			    		<div class="modal-body">
			    			<p style='color: red;'>WARNING : Deleting this batch will also remove all candidates of this batch.</p><p>Do you still want to delete this batch?</p>
			    		</div>
			    		<div class="modal-footer">
			      			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			      			<button type="button" class="btn btn-danger" onclick="DeleteBatch();">Delete</button>
			    		</div>
			    	</div>
			  	</div>
			</div>
			
			<div class="modal" id="select_row_msg_modal">
			  	<div class="modal-dialog">
			    	<div class="modal-content">
			    		<div class="modal-header">
				       	 	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				        	<h4 id='modal-title' class="modal-title">Select Batch</h4>
				      	</div>
			    		<div class="modal-body">
			    			Please select the batch first.
			    		</div>
			    		<div class="modal-footer">
			      			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			    		</div>
			    	</div>
			  	</div>
			</div>
			
			<div class="modal" id="default_batch_modal">
			  	<div class="modal-dialog">
			    	<div class="modal-content">
			    		<div class="modal-header">
				       	 	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				        	<h4 id='modal-title' class="modal-title">Default Batch</h4>
				      	</div>
			    		<div class="modal-body">
			    			You can not select the default batch.
			    		</div>
			    		<div class="modal-footer">
			      			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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

		var batch_id = ""; 
		var temp_batch_id = "";
		var action = "";
		jQuery.validator.addMethod("BatchNameExists", function(value, element) {
			return !CheckBatchName(batch_id);
		}, "<span style='color: red;'>* Batch name already exists!</span>");

		jQuery.validator.addMethod("ReservedBatchName", function(value, element) {
			if(value == "<?php echo(CConfig::CDB_NAME);?>")
			{
				return false;
			}
			else
			{
				return true;
			}
		}, "<span style='color: red;'>* Batch name is reserved!</span>");

		
		$(document).ready(function () {
			'use strict';
	
			var table;
			var tableElement;
			var responsiveHelper = undefined;
			var breakpointDefinition = {
			        tablet: 1024,
			        phone : 480
			    };

			$.fn.dataTable.TableTools.buttons.custom_button = $.extend(
				    true,
				    $.fn.dataTable.TableTools.buttonBase,
				    {
				    	"sNewLine": "<br>",
				    }
			);
			
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
								"sExtends":    "custom_button",
								"sButtonText": "New",
								"fnClick": function() {
									action = "create";
									$("#edit_new_form").validate().resetForm();
									$("#batch_name").val("");
									$("#description").val("");
									$("#modal-title").html("Add Batch");
									$("#edit_new_modal").modal("show");
								}
							},
							{
								"sExtends":    "custom_button",
								"sButtonText": "Edit",
								"fnClick": function() {
									$("#edit_new_form").validate().resetForm();
									if(temp_batch_id != "")
									{
										action = "edit";
										batch_id = temp_batch_id;
										$("#batch_name").val($('#'+batch_id+' td:first-child').text());
										$("#description").val($('#'+batch_id+' td:nth-child(3)').text());
										$("#modal-title").html("Edit Batch");
										$("#edit_new_modal").modal("show");
									}
									else
									{
										$("#select_row_msg_modal").modal("show");
									}
								}
							},
							{
								"sExtends":    "custom_button",
								"sButtonText": "Delete",
								"fnClick": function() {
									$("#edit_new_form").validate().resetForm();
									if(temp_batch_id != "")
									{
										action = "remove";
										batch_id = temp_batch_id;
										$("#delete_batch_modal").modal("show");
									}
									else
									{
										$("#select_row_msg_modal").modal("show");
									}
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
			            $('#example tbody').on( 'click', 'tr', function () {
				            if($(this).attr("id") == <?php echo(CConfig::CDB_ID);?>)
				            {
				            	$("#default_batch_modal").modal("show");
								temp_batch_id = "";
								$(this).removeClass('active');
					        }
				            else if( $(this).hasClass('active') ) {
			            		temp_batch_id = $(this).attr("id");
			                }
			            	else
			            	{
			            		temp_batch_id = "";
				            }
			            } );
			        }
			    });

			    $("#edit_new_form").validate({
		    		rules: {
		        		batch_name: {
		            		required:true,
		            		"BatchNameExists" : true,
		            		"ReservedBatchName" : true
		        		}
		    		},
		    		messages: {
		    			batch_name: {	
		    				required:	"<span style='color:red'>* Please provide the batch name</span>"
		        		}
			    	},
		    		submitHandler: function(form) {
		    			$("#edit_new_modal").modal("hide");
		    			
		    			$(".modal1").show();

		    			$.post("ajax/ajax_manage_batch.php", {"action" : action, "id" : batch_id, "data" : {"batch_name" : $("#batch_name").val(), "description" : $("#description").val()}}, function(data){
							if(action == "create")
							{
								var description = "Not Available";
								
								if($("#description").val() != "" && $("#description").val() != null)
								{
									description = $("#description").val();
								}
								var rowNode = $("#example").dataTable().api().row.add([$("#batch_name").val(), 0, description]).draw().node();
								var id = data.replace("[","").replace("]","");
								$(rowNode).attr("id",id);
							}
							else if(action == "edit")
							{
								var description = "Not Available";
								
								if($("#description").val() != "" && $("#description").val() != null)
								{
									description = $("#description").val();
								}
								var id = $("#example").dataTable().api().row( ".active" ).nodes().to$().attr("id");
								var cands = $('.active td:nth-child(2)').text();
								$("#example").dataTable().api().rows( ".active" )
						        .remove()
						        .draw();
								var rowNode = $("#example").dataTable().api().row.add([$("#batch_name").val(), cands, description]).draw().node();
								$(rowNode).attr("id",id);
							}
							$(".modal1").hide();
			    		});
		    		}
				});
			});
		function CheckBatchName(batch_id)
		{
			var bVal = false;
			
			$.ajax({
            	type: 'POST',
            	async: false,
	            url: 'ajax/ajax_check_batch_name.php',
	            data: {
	                'batch_name': $("#batch_name").val(),
	                'batch_id': batch_id
	            },
	            success: function(data){
		            if(data.trim() == 1)
		            {
			            bVal = true;
			        }
		        }
            });
            return bVal;
		}

		function DeleteBatch()
		{
			$("#delete_batch_modal").modal("hide");

			$(".modal1").show();
			
			$.post("ajax/ajax_manage_batch.php", {"action" : action, "data" : [batch_id]}, function(data){
				$("#example").dataTable().api().rows( ".active" )
		        .remove()
		        .draw();
				$(".modal1").hide();
    		});
		}

		function SetSelectedRowId(obj)
		{
			selectedRowId = $(obj).attr("id");
		}
	</script>
</body>
</html>