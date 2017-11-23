<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	include_once(dirname(__FILE__)."/../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../test/lib/tbl_result.php");
	include_once(dirname(__FILE__)."/../../lib/include_js_css.php");
	include_once(dirname(__FILE__)."/../../lib/site_config.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$objTR = new CResult();
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	$nUserType = CSessionManager::Get(CSessionManager::INT_USER_TYPE);
	$time_zone = CSessionManager::Get(CSessionManager::FLOAT_TIME_ZONE);
	
	$TNameAry = $objTR->GetCompletedTestNames($user_id, $nUserType);
	
	$TestInfoAry = $objTR->GetCompletedTestInfo($user_id, $time_zone);
	
	$ResultAry = $objTR->PopulateBriefResultList($user_id, $nUserType, $time_zone);
	
	$name_contxt = "";
	if($nUserType == CConfig::UT_INDIVIDAL)
	{
		$name_contxt = "Scheduled By";
	}
	else
	{
		$name_contxt = "Candidate Name";
	}
	
	$plan_type = CSessionManager::Get ( CSessionManager::INT_APPLIED_PLAN ); 
	
	$objIncludeJsCSS = new IncludeJSCSS();
	
	$menu_id = CSiteConfig::UAMM_RESULT_ANALYTICS;
	$page_id = CSiteConfig::UAP_BRIEF_RESULT;
?>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="Generator" content="Mastishka Intellisys Private Limited">
<meta name="Author" content="Mastishka Intellisys Private Limited">
<meta name="Keywords" content="">
<meta name="Description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo(CConfig::SNC_SITE_NAME);?>: Brief Result</title>
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
<script type="text/javascript">
		var aryTestInfo = new Array();
		<?php
			foreach ($TestInfoAry as $test_id => $test_info)
			{
				$index = 0;
				$demo = 0;
				printf("aryTestInfo[%d] = new Array();",$test_id);
				
				foreach ($test_info['tschd_id'] as $key => $tschd_id) 
				{
					if($tschd_id == -100)
					{
						if($demo == 0)
						{
							printf("aryTestInfo[%d][%d] = \"<option value='%s'>Demo Test</option>\";", $test_id, $index, $tschd_id);
							$demo++;
						}
					}
					else 
					{
						printf("aryTestInfo[%d][%d] = \"<option value='%s'>%s (xID: %s)</option>\";", $test_id, $index, $tschd_id, $test_info['scheduled_on'][$key], $tschd_id);
					}
					$index++;
				}
			}
			
		?>
</script>
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
			<?php 
			if($nUserType != CConfig::UT_INDIVIDAL)
			{
			?>
			<div class="row-fluid">
				<div class="col-lg-3 col-md-3 col-sm-3">
					<select class="form-control input-sm" id="test_id" name="test_id" onkeyup="OnTestChange();" onkeydown="OnTestChange();" onchange="OnTestChange();">
						<?php
							echo "<option value=''>--Choose Test--</option>";
							
							foreach ($TNameAry as $test_id => $test_info)
							{
								printf("<option value='%s'>%s</option>", $test_id, $test_info);
							}
						?>
					</select>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3">
					<select class="form-control input-sm col-lg-3 col-md-3 col-sm-3" id="tschd_id" name="tschd_id" style="display:none;" onkeyup="OnTestDateChange();" onkeydown="OnTestDateChange();" onchange="OnTestDateChange();">	
					</select>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3">
					<select class="form-control input-sm col-lg-3 col-md-3 col-sm-3" style="display:none;" id="batch" name="batch" onkeyup="OnBatchChange();" onkeydown="OnBatchChange();" onchange="OnBatchChange();">
					</select>
				</div>
			</div>
			<br /><br /><br />
			<div id="dt_result" style="display:none">
			<?php 
			}
			?>
			<div id='TableToolsPlacement'>
			</div><br />
		    <div class="form-inline">
		    	<table class="table table-striped table-bordered" cellpadding="0" cellspacing="0" border="0" width="100%" id="example">
					<thead>
						<tr>
							<th data-class="expand"><font color="#000000">Test Name</font></th>
							<?php 
							if($nUserType == CConfig::UT_INDIVIDAL)
							{
							?>
							<th data-hide="phone"><font color="#000000">Scheduled On</font></th>
							<?php 
							}
							?>
							<th data-hide="phone"><font color="#000000">Completed On</font></th>
							<th data-hide="phone,tablet"><font color="#000000"><?php echo($name_contxt);?></font></th>
							<th data-hide="phone,tablet"><font color="#000000">Location</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Marks Obtained</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Result &frasl; Rank</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Time Taken (MM:SS)</font></th>
							<?php 
							if($nUserType != CConfig::UT_INDIVIDAL)
							{
							?>
							<th data-hide="phone,tablet"><font color="#000000">Visibility</font></th>
							<?php 
							}
							?>
							<th data-hide="phone,tablet"><font color="#000000">Activity Log</font></th>		
						</tr>
					</thead>
					<tbody id='tbody_id'>
					</tbody>
					<tfoot>
						<tr>
							<th data-class="expand"><font color="#000000">Test Name</font></th>
							<?php 
							if($nUserType == CConfig::UT_INDIVIDAL)
							{
							?>
							<th data-hide="phone"><font color="#000000">Scheduled On</font></th>
							<?php 
							}
							?>
							<th data-hide="phone"><font color="#000000">Completed On</font></th>
							<th data-hide="phone,tablet"><font color="#000000"><?php echo($name_contxt);?></font></th>
							<th data-hide="phone,tablet"><font color="#000000">Location</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Marks Obtained</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Result &frasl; Rank</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Time Taken (MM:SS)</font></th>
							<?php 
							if($nUserType != CConfig::UT_INDIVIDAL && ($plan_type == CConfig::SPT_ENTERPRISE || $plan_type == CConfig::SPT_PROFESSIONAL || $nUserType == CConfig::UT_SUPER_ADMIN))
							{
							?>
							<th data-hide="phone,tablet"><font color="#000000">Visibility</font></th>
							<?php 
							}
							?>
							<th data-hide="phone,tablet"><font color="#000000">Activity Log</font></th>	
						</tr>
					</tfoot>
				</table>
		    </div><br /><br /><br />
		    <?php 
			if($nUserType != CConfig::UT_INDIVIDAL)
			{
			?>
			</div>
			<?php 
			}
			?>
				<div class="modal" id="activity_modal">
				  	<div class="modal-dialog">
				    	<div class="modal-content">
				      		<div class="modal-header">
				       		 	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				        		<h4 class="modal-title">Activity Log</h4>
				      		</div>
					      	<div class="modal-body">
					      		<table class="table table-bordered table-striped js-responsive-table">
									<tr class="info">
										<td><b>Activity TimeStamp</b></td>
										<td><b>Reason</b></td>
									</tr>
									<tbody id="activity_tbody">
									</tbody>
								</table>
					      	</div>
				      		<div class="modal-footer">
					        	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				      		</div>
				    	</div>
				  	</div>
				</div>
				<div class="modal" id="sec_perfrmnc_modal">
				  	<div class="modal-dialog">
				    	<div class="modal-content">
				      		<div class="modal-header">
				       		 	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				        		<h4 class="modal-title">Section-Wise Performance</h4>
				      		</div>
					      	<div class="modal-body">
					      		<table class="table table-bordered table-striped js-responsive-table">
									<tr class="info">
										<td><b>Section Name</b></td>
										<td><b>Marks</b></td>
										<td><b>Minimum Passing Marks</b></td>
										<td><b>Maximum Passing Marks</b></td>
										<td><b>Result</b></td>
									</tr>
									<tbody id="sec_perfrmnc_tbody">
									
									</tbody>
								</table>
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
	</div>
	<script type="text/javascript">
			var time_zone_val = get_time_zone_offset();
			var aryResultInfo = new Array();

			<?php 
			if($nUserType != CConfig::UT_INDIVIDAL)
			{
			?>
				var sheetColumns = [ 0, 1, 2, 3, 4, 5, 6];
			<?php
			}
			else 
			{
			?>
				var sheetColumns = [ 0, 1, 2, 3, 4, 5, 6, 7];
			<?php	
			}
			?>

			$(window).on('load', function(){
				$(".modal1").show();
				$.ajax({
		            type: 'GET',
		            url: 'ajax/ajax_get_brief_result.php',
		            data: {
		                time_zone: time_zone_val,
		            },
		            dataType: 'json',
		            success: function (data) {
			            $.each(data, function (test_pnr, result_info_ary){
			            	aryResultInfo[test_pnr] = new Array();
			            	
			            	$.each(result_info_ary, function (key, value){
			            		aryResultInfo[test_pnr][key] = value;
				            });
				        });

			            <?php 
			    		if($nUserType == CConfig::UT_INDIVIDAL)
			    		{
			    		?>
			    			for (index in aryResultInfo)
			    			{
			    				$("#tbody_id").append(aryResultInfo[index]['tr_open']+aryResultInfo[index]['test_name']+aryResultInfo[index]['scheduled_on']+aryResultInfo[index]['completed_on']+aryResultInfo[index]['name']+aryResultInfo[index]['location']+aryResultInfo[index]['marks']+aryResultInfo[index]['result']+aryResultInfo[index]['time_taken']+aryResultInfo[index]['btn_activity_log']+aryResultInfo[index]['tr_close']);
			    			}
			    			$("#dt_result").show();
			    		<?php 
			    		}
			    		?>
			    		'use strict';
						 var responsiveHelper = undefined;
						 var breakpointDefinition = {
						     tablet: 1024,
						     phone : 480
						 };
			    		$.fn.dataTable.TableTools.defaults.sSwfPath = "<?php $objIncludeJsCSS->IncludeDatatablesCopy_CSV_XLS_PDF("../../");?>";
						tableElement = $('#example');
						table = tableElement.dataTable({
						"sDom": 'T<"clear">lfrtip<"clear spacer">T',
						"bPaginate": true,
						"bFilter": true,
						"oTableTools": {
					    	"aButtons": [
								{
									"sExtends": "csv",
									"mColumns": sheetColumns
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
						 }
					   });
						$(".modal1").hide();
		            }
		        });
			});
			
			<?php 
			if($nUserType != CConfig::UT_INDIVIDAL)
			{
			?>
			function OnTestChange()
			{
				var test_id = $("#test_id").val();
				$("#batch").hide();
				if(test_id)
				{
					$("#tschd_id").empty();
					$("#tschd_id").append("<option value=''>--Choose Schedule Date--</option>");
					for (index in aryTestInfo[test_id])
					{
						$("#tschd_id").append(aryTestInfo[test_id][index]);
					}

					$('#tschd_id').show();
				}
				else
				{
					$("#tschd_id").hide();
				}
				$("#dt_result").hide();
				$("#tbody_id").empty();
			}

			var bTblInit = true;
			function OnTestDateChange()
			{
				var tschd_id = $("#tschd_id").val();

				$("#batch").empty();
				$("#batch").hide();
				if(bTblInit == true)
				{
					bTblInit = false;
					$("#example").dataTable().fnDestroy();
					$('#tbody_id').empty();
				}

				if(tschd_id)
				{
					$("#dt_result").show();
					$("#tbody_id").empty();

					var batchArray 	 = new Array();
					var firstOption  = "<option value=''>--Select Specific Batch--</option>";
					var batchOptions = "";

					if(tschd_id != -100)
					{
						for (index in aryResultInfo)
						{
							if(aryResultInfo[index]['schd_id'] == tschd_id && batchArray.indexOf(aryResultInfo[index]['batch']) == -1 && aryResultInfo[index]['schd_id'] != -100 && aryResultInfo[index]['batch'] != "")
							{
								batchArray.push(aryResultInfo[index]['batch']);
								batchOptions += "<option value='"+aryResultInfo[index]['batch']+"'>"+aryResultInfo[index]['batch']+"</option>";
							}
						}
					}

					if(batchOptions != "")
					{
						$("#batch").html(firstOption+batchOptions);
						$("#batch").show();
					}

					for (index in aryResultInfo)
					{
						if(aryResultInfo[index]['schd_id'] == tschd_id && aryResultInfo[index]['test_id'] == $("#test_id").val())
						{
							<?php 
							if($nUserType != CConfig::UT_INDIVIDAL)
							{
							?>
							$("#tbody_id").append(aryResultInfo[index]['tr_open']+aryResultInfo[index]['test_name']+aryResultInfo[index]['completed_on']+aryResultInfo[index]['name']+aryResultInfo[index]['location']+aryResultInfo[index]['marks']+aryResultInfo[index]['result']+aryResultInfo[index]['time_taken']+aryResultInfo[index]['visibility']+aryResultInfo[index]['btn_activity_log']+aryResultInfo[index]['tr_close']);
							<?php 
							}
							else 
							{
							?>
							$("#tbody_id").append(aryResultInfo[index]['tr_open']+aryResultInfo[index]['test_name']+aryResultInfo[index]['completed_on']+aryResultInfo[index]['name']+aryResultInfo[index]['location']+aryResultInfo[index]['marks']+aryResultInfo[index]['result']+aryResultInfo[index]['time_taken']+aryResultInfo[index]['btn_activity_log']+aryResultInfo[index]['tr_close']);
							<?php 
							}
							?>
						}
					}

					if(bTblInit == false)
					{
						bTblInit = true;
						'use strict';
						 var responsiveHelper = undefined;
						 var breakpointDefinition = {
						     tablet: 1024,
						     phone : 480
						 };
						$.fn.dataTable.TableTools.defaults.sSwfPath = "<?php $objIncludeJsCSS->IncludeDatatablesCopy_CSV_XLS_PDF("../../");?>";
						tableElement = $('#example');
						table = tableElement.dataTable({
						"sDom": 'T<"clear">lfrtip<"clear spacer">T',
						"bPaginate": true,
						"bFilter": true,
						"oTableTools": {
					    	"aButtons": [
								{
									"sExtends": "csv",
									"mColumns": sheetColumns
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
						 }
					   });
					}
				}
				else
				{
					$("#dt_result").hide();
				}
			}

			function OnBatchChange()
			{
				var tschd_id = $("#tschd_id").val();
				$("#example").dataTable().fnDestroy();
				$('#tbody_id').empty();
				if($("#batch").val() != '')
				{
					if(tschd_id != -100)
					{
						for (index in aryResultInfo)
						{
							if(aryResultInfo[index]['schd_id'] == tschd_id && aryResultInfo[index]['test_id'] == $("#test_id").val() && $("#batch").val() == aryResultInfo[index]['batch'])
							{
								<?php 
								if($nUserType != CConfig::UT_INDIVIDAL)
								{
								?>
								$("#tbody_id").append(aryResultInfo[index]['tr_open']+aryResultInfo[index]['test_name']+aryResultInfo[index]['completed_on']+aryResultInfo[index]['name']+aryResultInfo[index]['location']+aryResultInfo[index]['marks']+aryResultInfo[index]['result']+aryResultInfo[index]['time_taken']+aryResultInfo[index]['visibility']+aryResultInfo[index]['btn_activity_log']+aryResultInfo[index]['tr_close']);
								<?php 
								}
								else 
								{
								?>
								$("#tbody_id").append(aryResultInfo[index]['tr_open']+aryResultInfo[index]['test_name']+aryResultInfo[index]['completed_on']+aryResultInfo[index]['name']+aryResultInfo[index]['location']+aryResultInfo[index]['marks']+aryResultInfo[index]['result']+aryResultInfo[index]['time_taken']+aryResultInfo[index]['btn_activity_log']+aryResultInfo[index]['tr_close']);
								<?php 
								}
								?>
							}
						}
					}
				}
				else
				{
					for (index in aryResultInfo)
					{
						if(aryResultInfo[index]['schd_id'] == tschd_id && aryResultInfo[index]['test_id'] == $("#test_id").val())
						{
							<?php 
							if($nUserType == CConfig::UT_INDIVIDAL)
							{
							?>
							$("#tbody_id").append(aryResultInfo[index]['tr_open']+aryResultInfo[index]['test_name']+aryResultInfo[index]['completed_on']+aryResultInfo[index]['name']+aryResultInfo[index]['location']+aryResultInfo[index]['marks']+aryResultInfo[index]['result']+aryResultInfo[index]['time_taken']+aryResultInfo[index]['visibility']+aryResultInfo[index]['btn_activity_log']+aryResultInfo[index]['tr_close']);
							<?php 
							}
							else 
							{
							?>
							$("#tbody_id").append(aryResultInfo[index]['tr_open']+aryResultInfo[index]['test_name']+aryResultInfo[index]['completed_on']+aryResultInfo[index]['name']+aryResultInfo[index]['location']+aryResultInfo[index]['marks']+aryResultInfo[index]['result']+aryResultInfo[index]['time_taken']+aryResultInfo[index]['btn_activity_log']+aryResultInfo[index]['tr_close']);
							<?php 
							}
							?>
						}
					}
				}

				'use strict';
				 var responsiveHelper = undefined;
				 var breakpointDefinition = {
				     tablet: 1024,
				     phone : 480
				 };
				$.fn.dataTable.TableTools.defaults.sSwfPath = "<?php $objIncludeJsCSS->IncludeDatatablesCopy_CSV_XLS_PDF("../../");?>";
				tableElement = $('#example');
				table = tableElement.dataTable({
				"sDom": 'T<"clear">lfrtip<"clear spacer">T',
				"bPaginate": true,
				"bFilter": true,
				"oTableTools": {
			    	"aButtons": [
						{
							"sExtends": "csv",
							"mColumns": sheetColumns
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
				 }
			   });
			}

			function OnVisibilityChange(obj)
			{
				var objNameAry = obj.name.split(";");

				$(".modal1").show();

				$.post('ajax/ajax_change_result_visibility.php',{test_pnr : objNameAry[0], visibility : obj.value}, function(data) {

					var none_checked = (obj.value == <?php echo(CConfig::RV_NONE);?>)?"checked":"";
					var min_checked = (obj.value == <?php echo(CConfig::RV_MINIMAL);?>)?"checked":"";
					var det_checked = (obj.value == <?php echo(CConfig::RV_DETAILED);?>)?"checked":"";
					
					aryResultInfo[objNameAry[0]]['visibility'] =  "<td><label class='radio inline'><input type='radio' value='<?php echo(CConfig::RV_NONE);?>' name='"+objNameAry[0]+";visibility' onchange='OnVisibilityChange(this);' "+none_checked+"> None </label><br /><label class='radio inline'><input type='radio' value='<?php echo(CConfig::RV_MINIMAL);?>' name='"+objNameAry[0]+";visibility' onchange='OnVisibilityChange(this);' "+min_checked+"> Minimal </label><br /><label class='radio inline'><input type='radio' value='<?php echo(CConfig::RV_DETAILED);?>' name='"+objNameAry[0]+";visibility' onchange='OnVisibilityChange(this);' "+det_checked+"> Detailed </label></td>";
					$(".modal1").hide();
				});
			}
			<?php 
			}
			?>

			function ShowActivityLog(obj)
			{
				var objIdAry = obj.id.split(";");

				$('#activity_tbody').empty();
				if(aryResultInfo[objIdAry[0]]['activity_log_details'] != "" && aryResultInfo[objIdAry[0]]['activity_log_details'] != null)
				{
					var rowData = aryResultInfo[objIdAry[0]]['activity_log_details'].split(";");
					var colData = new Array();
					for(var rowIndex = 0; rowIndex < rowData.length - 1; rowIndex++)
					{
						colData[rowIndex] = rowData[rowIndex].split("#");
					} 

					for(var colIndex = 0; colIndex < colData.length; colIndex++)
					{
						var dateFormatAry 	= colData[colIndex][1].split(" ");
						var dateObj			= new Date(dateFormatAry[0]);

						$('#activity_tbody').append("<tr><td>"+dateObj.toDateString()+" "+dateFormatAry[1]+"</td><td>"+colData[colIndex][0]+"</td></tr>");		
					}
				}
				else
				{
					$('#activity_tbody').append("<tr><td>Test completed in first attempt</td><td>Not Applicable</td></tr>");
				} 
				
				$("#activity_modal").modal("show");
			}

			function ShowSectionWisePerformance(obj)
			{
				var objIdAry = obj.id.split(";");

				$('body').on({
				    ajaxStart: function() { 
				    	$(this).addClass("loading"); 
				    },
				    ajaxStop: function() { 
				    	$(this).removeClass("loading"); 
				    }    
				});

				$(".modal1").show();
				$('#sec_perfrmnc_tbody').load('ajax/ajax_get_sectionwise_result.php',{test_pnr : objIdAry[0]}, function(){
					$('#sec_perfrmnc_modal').modal("show");
					$(".modal1").hide();
				});
			}

			function get_time_zone_offset() 
			{
			    var current_date = new Date();
			    return -current_date.getTimezoneOffset() / 60;
			}
				
		</script>
</body>
</html>