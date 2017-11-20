<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php 
	include_once(dirname(__FILE__)."/../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/../../lib/utils.php");
	include_once(dirname(__FILE__)."/../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../lib/include_js_css.php");
	include_once(dirname(__FILE__)."/../../lib/site_config.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	$user_type = CSessionManager::Get(CSessionManager::INT_USER_TYPE);
	$time_zone = CSessionManager::Get(CSessionManager::FLOAT_TIME_ZONE);
	
	$objDB = new CMcatDB();
	
	
	$objIncludeJsCSS = new IncludeJSCSS();
	
	$menu_id = CSiteConfig::UAMM_EXPORT_TEST_RESULT;
	
	$candStatusAry = $objDB->PopulateCandidatesWithTestStatus();
	
	$bTestStartedByAdmin = $objDB->IsTestStartedByAdmin();
	
	$notStarted = $candStatusAry['total'] - ($candStatusAry['finished'] + $candStatusAry['unfinished']);
?>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="Generator" content="Mastishka Intellisys Private Limited">
<meta name="Author" content="Mastishka Intellisys Private Limited">
<meta name="Keywords" content="">
<meta name="Description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo(CConfig::SNC_SITE_NAME);?> Offline: Export Test Result</title>
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
$objIncludeJsCSS->IncludeJqueryFormJS("../../");
?>
<style type="text/css">
	.js-responsive-table{margin: 50px auto}
	.js-responsive-table thead{font-weight: bold}	
	.js-responsive-table td{ -moz-box-sizing: border-box; -webkit-box-sizing: border-box;-o-box-sizing: border-box;-ms-box-sizing: border-box;box-sizing: border-box;padding: 20px;}
	.js-responsive-table td span{display: none}		
	
	@media all and (max-width:767px){
		.js-responsive-table{width: 100%;max-width: 400px;}
		.js-responsive-table thead{display: none}
		.js-responsive-table td{width: 100%;display: block}
		.js-responsive-table td span{float: left;font-weight: bold;display: block}
		.js-responsive-table td span:after{content:' : '}
		.js-responsive-table td{border:0px;border-bottom:1px solid #ff0000}	
		.js-responsive-table tr:last-child td:last-child{border: 0px}		
	}
	
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
			<div id="tab1">
			<button type='button' onclick='Refresh();' class='btn btn-default'>Refresh</button>
			<button type='button' onclick='ShowConfirmationModal();' class='btn btn-primary'>Export Results</button><br />
			<table align="center"  style="font: 100% 'Trebuchet MS', sans-serif;border-collapse:collapse;" class="js-responsive-table table table-bordered table-hover">
				<thead>
					<tr>
						<th>Test Name</th>
						<th>Total Candidates</th>
						<th>Candidates Finished</th>
						<th>Candidates Unfinished</th>
						<th>Candidates Not Started</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?php echo($candStatusAry['test_name']);?></td>
						<td><?php echo($candStatusAry['total']);?></td>
						<td><?php echo($candStatusAry['finished']);?></td>
						<td><?php echo($candStatusAry['unfinished']);?></td>
						<td><?php echo($notStarted);?></td>
					</tr>
				</tbody>
			</table>
			<hr/>
			<p style="color:DarkBlue;text-align:center;"><b>Candidates List: </b></p>
			<div id='TableToolsPlacement'>
			</div><br />
		    <div class="form-inline">
		        <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
		        	<thead>
						<tr>
							<th data-class="expand"><font color="#000000">Candidate Name</font></th>
							<th><font color="#000000">Candidate Email</font></th>
							<th data-hide="phone"><font color="#000000">Test Status</font></th>
						</tr>
					</thead>
					<?php
						unset($candStatusAry['total']);
						unset($candStatusAry['finished']);
						unset($candStatusAry['unfinished']);
						unset($candStatusAry['test_name']);
						foreach($candStatusAry as $cands)
						{
							printf("<tr>");
							printf("<td>%s</td>", $cands['name']);
							printf("<td>%s</td>", $cands['email']);
							printf("<td>%s</td>", $cands['status']);
							printf("</tr>");
						}
					?>
					<tfoot>
						<tr>
							<th data-class="expand"><font color="#000000">Candidate Name</font></th>
							<th><font color="#000000">Candidate Email</font></th>
							<th data-hide="phone"><font color="#000000">Test Status</font></th>
						</tr>
					</tfoot>
		        </table>
		    </div><br /><br />
			<div class="modal" id="export_result_modal">
				  <div class="modal-dialog">
				    <div class="modal-content">
				      <div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				        <h4 class="modal-title">Export Confirmation</h4>
				      </div>
				      <div class="modal-body">
				      	<p>Do you really want to export test results? This step will end unfinished tests and conclude results.</p><br />
						<label class="checkbox inline">
							<input type="checkbox" id="export_confirm" onclick="ConfirmExport();"> Yes I want to export the test results. 
						</label>
				      </div>
				      <div class="modal-footer">
				        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				        <input id="export" type="button" onclick="ExportResult();" data-dismiss="modal" class="btn btn-primary" value="Export" disabled/>
				      </div>
				    </div>
				  </div>
			</div>
			
			<div class="modal" id="export_result_failed">
				  <div class="modal-dialog">
				    <div class="modal-content">
				      <div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				        <h4 class="modal-title">Empty Results</h4>
				      </div>
				      <div class="modal-body">
				      	Currently there is no result to export.
				      </div>
				      <div class="modal-footer">
				        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				      </div>
				    </div>
				  </div>
			</div>
			
		</div>
		<div id="process_msg" style="color: red; margin: 10px;display: none;"><b>Please do not refresh page or press back button until the test is terminated. This process will take about 30 to 45 Seconds.</b></div>
		<?php
		include_once (dirname ( __FILE__ ) . "/../../lib/footer.php");
		?>
	</div>
</div>
	<script type="text/javascript">
	
		$(document).ready(function () {
			'use strict';

			var table;
			var tableElement;
			var responsiveHelper = undefined;
			var breakpointDefinition = {
			        tablet: 1024,
			        phone : 480
			    };
			$.fn.dataTable.TableTools.defaults.sSwfPath = "<?php $objIncludeJsCSS->IncludeDatatablesCopy_CSV_XLS_PDF("../../");?>";
			$(document).ready(function () {
			    tableElement = $('#example');
			    table = tableElement.dataTable({
			    	"sDom": 'T<"clear">lfrtip<"clear spacer">T',
			    	"bPaginate": true,
			    	"bFilter": true,
					"oTableTools": {
			            "aButtons": [
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
			        }
			    });
			});
			responsiveTable();
		});

		function Refresh()
		{
			window.location = window.location; 
		}

		function responsiveTable(){
		    var array = new Array();
		    $('table.js-responsive-table').each(function(){
		        $(this).find('thead th').each(function(i){
		        array[i] = $(this).html();
		        })
		        $(this).find('tbody tr').each(function(){
		            var attrInt =0;
		            $(this).find('td').each(function(i){
		                 var attr = $(this).attr('colspan');
		                 if (typeof attr !== 'undefined' && attr !== false){
		                     $(this).prepend('<span>' + array[attrInt] + '</span>')
		                     var attrInt1 = parseInt(attr)-1;
		                     attrInt = attrInt + attrInt1;
		                 }
		                 else{
		                     $(this).prepend('<span>' + array[attrInt] + '</span>')
		                 }
		                 attrInt++;
		             })
		        })
		  })

		}

		function ShowConfirmationModal()
		{
			$("#export_confirm").prop("checked", false);
			$("#export").attr("disabled", "disabled");
			$("#export_result_modal").modal("show");
		}

		function ConfirmExport()
		{
			if ($("#export_confirm").is(':checked')) 
			{
			    $("#export").removeAttr("disabled");
			}
			else {
			    $("#export").attr("disabled", "disabled");
			}
		} 

		function ExportResult()
		{
			$("#export_result_modal").modal("hide");
			$(".modal1").show();
			$.ajax({
				url : 'ajax/ajax_export_result.php',
				data : {'force_kill' : 1},
				async : false,
				type : 'POST',
				success : function(){
					
					
					$.ajax({
						url : 'ajax/ajax_export_result.php',
						data : {'end_exam' : 1},
						async : false,
						type : 'POST',
						success : function(data){
							$(".modal1").hide();
							if(data == 1)
							{
								window.open('ajax/ajax_export_result.php');	
							}
							else if(data == 0)
							{
								$("#export_result_failed").modal("show");
							}
						}
					});
				}
			});
		}
	</script>
</body>
</html>