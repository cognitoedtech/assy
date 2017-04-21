<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	include_once("../../lib/session_manager.php");
	include('../../database/mcat_db.php');
	include_once(dirname(__FILE__)."/../../lib/include_js_css.php");
	include_once(dirname(__FILE__)."/../../lib/site_config.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	$time_zone = CSessionManager::Get(CSessionManager::FLOAT_TIME_ZONE);
	$objDB = new CMcatDB();
	$batch_array = $objDB->GetBatches($user_id);
	
	$objIncludeJsCSS = new IncludeJSCSS();
	
	$menu_id = CSiteConfig::UAMM_REGISTER_CANDITATES;
	$page_id = CSiteConfig::UAP_REGISTERED_USERS;
?>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="Generator" content="Mastishka Intellisys Private Limited">
<meta name="Author" content="Mastishka Intellisys Private Limited">
<meta name="Keywords" content="">
<meta name="Description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo(CConfig::SNC_SITE_NAME); ?>: Registerd Users</title>
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
?>
<style type="text/css">
	.modal, .modal.fade.in {
	    top: 10%;
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
		<div class="col-sm-9 col-md-9 col-lg-9" style="border-left: 1px solid #ddd; border-top: 1px solid #ddd;">
			<div class="fuelux modal1">
				<div class="preloader"><i></i><i></i><i></i><i></i></div>
			</div>
			<br />
			<div class="row">
				<div class="col-sm-2 col-md-2 col-lg-2" style="text-align: right;">
					<label for="batch" class="control-label">Select Batch :</label>
				</div>
				<div class="col-sm-3 col-md-3 col-lg-3">
					<select class="form-control input-sm" id="batch" name="batch" onkeyup="OnBatchSelect();" onkeydown="OnBatchSelect();" onchange="OnBatchSelect();">
						<option value='<?php echo(CConfig::CDB_ID);?>'><?php echo(CConfig::CDB_NAME);?></option>
						<?php 
							foreach($batch_array as $batch_id=>$info)
								printf("<option value='%s'>%s</option>", $batch_id, $info['batch_name']);
						?>
					</select>
				</div>
			</div><br /><br />
			<div id='TableToolsPlacement'>
			</div><br />
		    <div class="form-inline">
		        <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
		        	<thead>
						<tr>
							<th data-class="expand"><font color="#000000">First Name</font></th>
							<th><font color="#000000">Last Name</font></th>
							<th data-hide="phone"><font color="#000000">Gender</font></th>
							<th data-hide="phone"><font color="#000000">Date of Birth</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Contact</font></th>
							<th data-hide="phone,tablet"><font color="#000000">E-mail (Unique)</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Location</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Activation</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Signup Date</font></th>
						</tr>
					</thead>
					<tbody id="cand_tbody">
					</tbody>
					<tfoot>
						<tr>
							<th data-class="expand"><font color="#000000">First Name</font></th>
							<th><font color="#000000">Last Name</font></th>
							<th data-hide="phone"><font color="#000000">Gender</font></th>
							<th data-hide="phone"><font color="#000000">Date of Birth</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Contact</font></th>
							<th data-hide="phone,tablet"><font color="#000000">E-mail (Unique)</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Location</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Activation</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Signup Date</font></th>
						</tr>
					</tfoot>
		        </table>
		    </div><br /><br />
		    <?php
			include_once (dirname ( __FILE__ ) . "/../../lib/footer.php");
			?>
		</div>
		<div class="modal" id="delete_cand_modal">
			  <div class="modal-dialog">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			        <h4 class="modal-title">Delete Candidates</h4>
			      </div>
			      <div class="modal-body" id="delete_cand_modal_body">
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			        <button type="button" id="delete_btn" class="btn btn-primary" onclick="DeleteUsers();">Delete</button>
			      </div>
			    </div>
			  </div>
			</div>
			
		</div>
	<script type="text/javascript">
		var row_count = 0;
		$(document).ready(function () {
			OnBatchSelect();
		});

		var delete_users_array = new Array();
	    var table;
		function OnBatchSelect()
		{
			row_count = 0;
			delete_users_array = new Array();
			$("#cand_tbody").empty();
			$("#example").dataTable().fnDestroy();
			'use strict';
			 var responsiveHelper = undefined;
			 var breakpointDefinition = {
			     tablet: 1024,
			     phone : 480
			 };
			$("#cand_tbody").load("../ajax/ajax_populate_cand_by_batch.php",{"batch_id" : $("#batch").val(), "time_zone" : <?php echo($time_zone);?>}, function(){
				var responsiveHelper = undefined;
			    var breakpointDefinition = {
			        tablet: 1024,
			        phone : 480,
			    };
			    TableTools.BUTTONS.custom_button = $.extend( true, TableTools.buttonBase, {
					"sNewLine": "<br>",
					"sButtonText": "Delete",
					"fnClick": function() {
						if(row_count != 0)
						{
							$("#delete_cand_modal_body").html("Do you want to delete "+row_count+" candidate(s)?");
							$("#delete_btn").show();
							$("#delete_cand_modal").modal("show");
						}
						else
						{
							$("#delete_cand_modal_body").html("Please select atleast one candidate to delete.");
							$("#delete_btn").hide();
							$("#delete_cand_modal").modal("show");
						}
					}
				} );
			    $.fn.dataTable.TableTools.defaults.sSwfPath = "<?php $objIncludeJsCSS->IncludeDatatablesCopy_CSV_XLS_PDF("../../");?>";
			    var tableElement = $('#example');
			    table = tableElement.dataTable({
			    	"dom": 'T<"clear">lfrtip',
			    	"bPaginate": true,
					"bFilter": true,
			    	"oTableTools": {
			    		"sRowSelect": "multi",
			            "aButtons": [
				            "csv",
				            "pdf",
				            {
								"sExtends":    "custom_button",
								"sButtonText": "Delete",
							},
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
			            /*$('#example tbody').on( 'click', 'tr', function () {
			            	if( $(this).hasClass('active') ) {
			            		row_count = row_count + 1;
			            		delete_users_array.push("\""+$(this).attr("id")+"\"");
			            		console.log("hello");
			                }
			            	else
			            	{
			            		row_count = row_count - 1;
			            		//console.log("hello"+row_count);
			            		var user_id_pos = delete_users_array.indexOf("\""+$(this).attr("id")+"\"");

			            		delete_users_array.splice(user_id_pos, 1);
				            }
			            } );*/
			        }
			    });
				    
			});
		}

		$('#example tbody').on( 'click', 'tr', function () {
        	if( $(this).hasClass('active') ) {
        		row_count = row_count - 1;
        		var user_id_pos = delete_users_array.indexOf("\""+$(this).attr("id")+"\"");
        		delete_users_array.splice(user_id_pos, 1);
            }
        	else
        	{
        		row_count = row_count + 1;
        		delete_users_array.push("\""+$(this).attr("id")+"\"");
            }
        } );

		function DeleteUsers()
		{
			$(".modal1").show();
			
			var batch_id = $("#batch").val();
			$("#delete_cand_modal").modal("hide");
			$.post("ajax/ajax_delete_user.php",{"action": "remove", "data": delete_users_array,"batch_id" : batch_id},function(){
				table.api().rows( ".active" )
		        .remove()
		        .draw();
				$(".modal1").hide();
			});
			row_count = 0;
			delete_users_array = new Array();
		}
	</script>
</body>
</html>