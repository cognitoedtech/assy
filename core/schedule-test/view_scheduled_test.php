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
	$user_type = CSessionManager::Get(CSessionManager::INT_USER_TYPE);
	$time_zone = CSessionManager::Get(CSessionManager::FLOAT_TIME_ZONE);
	
	$parsAry = parse_url(CUtils::curPageURL());
	$qry = explode("=", $parsAry["query"]);
	
	$sTestName = "";
	if($qry[0] == "test_name" && !empty($qry[1]))
	{
		echo "<script>save_success = 1; </script>";
		$sTestName = urldecode($qry[1]);
	}
	else 
	{
		echo "<script>save_success = 0; </script>";
	}
	
	
	$objIncludeJsCSS = new IncludeJSCSS();
	
	$menu_id = CSiteConfig::UAMM_SCHEDULE_TEST;
	$page_id = CSiteConfig::UAP_VIEW_SCHEDULED_TEST;
?>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="Generator" content="EditPlus">
<meta name="Author" content="">
<meta name="Keywords" content="">
<meta name="Description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo(CConfig::SNC_SITE_NAME);?>: View Scheduled Test</title>
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
			<div class="col-lg-9 col-md-9 col-sm-9" style="border-left: 1px solid #ddd; border-top: 1px solid #ddd;">
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
								<th data-class="expand"><font color="#000000">xID</font></th>
								<th><font color="#000000">Test Name</font></th>
								<th data-hide="phone"><font color="#000000">Scheduled On</font></th>
								<th data-hide="phone"><font color="#000000">Time Zone</font></th>
								<th data-hide="phone,tablet"><font color="#000000">Schedule Created</font></th>
								<th data-hide="phone,tablet"><font color="#000000">Candidates Details</font></th>
								<th data-hide="phone,tablet"><font color="#000000">Schedule Type</font></th>
							</tr>
						</thead>
						<?php
							$objDB->PopulateTableTestSchedule($user_id, $time_zone);
						?>
						<tfoot>
							<tr>
								<th data-class="expand"><font color="#000000">xID</font></th>
								<th><font color="#000000">Test Name</font></th>
								<th data-hide="phone"><font color="#000000">Scheduled On</font></th>
								<th data-hide="phone"><font color="#000000">Time Zone</font></th>
								<th data-hide="phone,tablet"><font color="#000000">Schedule Created</font></th>
								<th data-hide="phone,tablet"><font color="#000000">Candidates Details</font></th>
								<th data-hide="phone,tablet"><font color="#000000">Schedule Type</font></th>
							</tr>
						</tfoot>
			        </table>
			    </div><br /><br /><br />
			    <?php
				include_once (dirname ( __FILE__ ) . "/../../lib/footer.php");
				?>
			</div>
			
			<div class="modal" id="cand_test_status_modal">
			  	<div class="modal-dialog">
			    	<div class="modal-content">
			    		<div class="modal-header">
			       		 	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			        		<h4 class="modal-title">Candidate Details</h4>
			      		</div>
			    		<div class="modal-body" id="cand_test_status_modal_body">
			    			<div id='CandInfoTableToolsPlacement'>
							</div><br />
						    <div class="form-inline">
						        <table id="cand_info_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
						        	<thead>
										<tr id="thead_tr">
											<th data-class="expand"><font color="#000000">S. No.</font></th>
											<th data-class="phone"><font color="#000000">Name</font></th>
											<th data-hide="phone"><font color="#000000">Email &sol; TPIN</font></th>
											<th data-hide="phone" id="login_name_status_heading"></th>
										</tr>
									</thead>
									<tbody id="cand_test_status_table_tbody">
										
									</tbody>
						        </table>
						    </div><br />
			    		</div>
			    		<div class="modal-footer">
			      			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			    		</div>
			    	</div>
			  	</div>
			</div>
		</div>
	<script type="text/javascript">
	'use strict';
	
	$(document).ready(function () {
		
	    var responsiveHelper = undefined;
	    var breakpointDefinition = {
	        tablet: 1024,
	        phone : 480
	    };
	    $.fn.dataTable.TableTools.defaults.aButtons = [ "csv", "pdf" ];
	    $.fn.dataTable.TableTools.defaults.sSwfPath = "<?php $objIncludeJsCSS->IncludeDatatablesCopy_CSV_XLS_PDF("../../");?>";
	    var tableElement = $('#example');
	    var table = tableElement.dataTable({
	    	"dom": 'T<"clear">lfrtip',
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
	});

	function ShowCandidateDetails(obj)
	{
		$("#cand_info_table").dataTable().fnDestroy();
		$("#cand_test_status_table_tbody").empty();

		$(".modal1").show();
		$.ajax({
			url: "ajax/ajax_populate_scheduled_cands.php",
			data: {'schd_id' : $(obj).attr("schd_id"), 'schedule_type' : $(obj).attr("test_schedule_type")},
			type: 'POST',
			async: false,
			success: function(data) {
				if($(obj).attr("test_schedule_type") == "<?php echo(CConfig::TST_OFFLINE);?>")
				{
					$("#login_name_status_heading").html("<font color='#000000'>Login Name</font>");
				}
				else if($(obj).attr("test_schedule_type") == "<?php echo(CConfig::TST_ONLINE);?>")
				{
					$("#login_name_status_heading").html("<font color='#000000'>Status</font>");
				}
				
				$("#cand_test_status_table_tbody").html(data);
				var responsiveHelper2 = undefined;
			    var breakpointDefinition2 = {
			        tablet: 1024,
			        phone : 480
			    };
			    var tableElement = $('#cand_info_table');
				    var table = tableElement.dataTable({
				    	"dom": 'T<"clear">lfrtip',
				    	"oTableTools": {
				            "aButtons": [
				                {
				                    "sExtends": "csv",
				                    "sFileName": "Candidate-Details.csv"
				                },
				                {
							        "sExtends": "pdf",
							        "sFileName": "Candidate-Details.pdf"
							    }
				            ]
				        },
				        autoWidth      : false,
				        //ajax           : './arrays.txt',
				        preDrawCallback: function () {
				            // Initialize the responsive datatables helper once.
				            if (!responsiveHelper2) {
				            	responsiveHelper2 = new ResponsiveDatatablesHelper(tableElement, breakpointDefinition2);
				            }
				            var oTableTools = TableTools.fnGetInstance( 'cand_info_table' );
				            $('#CandInfoTableToolsPlacement').before( oTableTools.dom.container );
				        },
				        rowCallback    : function (nRow) {
				        	responsiveHelper2.createExpandIcon(nRow);
				        },
				        drawCallback   : function (oSettings) {
				        	responsiveHelper2.respond();
				        }
				    });
				    $(".modal1").hide();
				$("#cand_test_status_modal").modal("show");
			}
		});
	}
	</script>
</body>
</html>