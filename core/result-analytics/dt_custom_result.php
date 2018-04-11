<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	include_once(dirname(__FILE__)."/../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../test/lib/tbl_result.php");
	include_once(dirname(__FILE__)."/../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/../../lib/include_js_css.php");
	include_once(dirname(__FILE__)."/../../lib/site_config.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$objDB = new CMcatDB();
	$objTR = new CResult();
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	$nUserType = CSessionManager::Get(CSessionManager::INT_USER_TYPE);
	
	$TNameAry = $objTR->GetCompletedTestNames($user_id, $nUserType);
	
	$TestInfoAry = $objTR->GetScheduledTestInfo($user_id,true);
	
	/*echo("<pre>");
	print_r($TestInfoAry);
	echo("</pre>"); */
	
	$objIncludeJsCSS = new IncludeJSCSS();
	
	$menu_id = CSiteConfig::UAMM_RESULT_ANALYTICS;
	$page_id = CSiteConfig::UAP_PRODUCE_CUSTOM_RESULT;
?>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="Generator" content="EditPlus">
<meta name="Author" content="">
<meta name="Keywords" content="">
<meta name="Description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo(CConfig::SNC_SITE_NAME);?>: Produce Custom Result</title>
<?php
$objIncludeJsCSS->IncludeDatatablesBootstrapCSS("../../");
$objIncludeJsCSS->IncludeDatatablesResponsiveCSS("../../"); 
$objIncludeJsCSS->CommonIncludeCSS("../../");
$objIncludeJsCSS->IncludeIconFontCSS ( "../../" );
$objIncludeJsCSS->IncludeJqueryNouisliderCSS("../../");
$objIncludeJsCSS->IncludeJqueryStepyCSS("../../");
$objIncludeJsCSS->IncludeFuelUXCSS ( "../../" );
$objIncludeJsCSS->CommonIncludeJS("../../");
$objIncludeJsCSS->IncludeJqueryDatatablesMinJS("../../");
$objIncludeJsCSS->IncludeDatatablesTabletoolsMinJS("../../");
$objIncludeJsCSS->IncludeDatatablesBootstrapJS("../../");
$objIncludeJsCSS->IncludeDatatablesResponsiveJS("../../");
$objIncludeJsCSS->IncludeJqueryValidateMinJS("../../");
$objIncludeJsCSS->IncludeJQueryStepyMinJS("../../");
$objIncludeJsCSS->IncludeJQueryNouisliderMinJS("../../");
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
	
	.step {
		width: 100%;
	}
</style>
<script type="text/javascript">
	var aryTestInfo = new Array();
	var aryBatchInfo = new Array();
	
	<?php
		foreach ($TestInfoAry as $test_id => $test_info)
		{
			$index = 0;
			printf("aryTestInfo[%d] = new Array();",$test_id);
			printf("aryBatchInfo[%d] = new Array();",$test_id);
			
			foreach ($test_info['schd_id'] as $key => $tschd_id) 
			{
				printf("aryTestInfo[%d][%d] = \"<option value='%s'>%s (xID: %s)</option>\";", $test_id, $index, $tschd_id, $test_info['scheduled_on'][$key], $tschd_id);
				
				$index++;
			}
			
			foreach ($test_info['batch'] as $key=>$batch_ary)
			{
				printf("aryBatchInfo[%d][%d] = new Array();",$test_id, $key);
				
				foreach ($batch_ary as $batch_id=>$batch_name)
				{
					printf("aryBatchInfo[%d][%d][%d] = '%s';",$test_id, $key, $batch_id, $batch_name);
				}
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
	</div>
	<div class="col-lg-9 col-md-9 col-sm-9" style="border-left: 1px solid #ddd; border-top: 1px solid #ddd;">
		<div class="fuelux modal1">
			<div class="preloader"><i></i><i></i><i></i><i></i></div>
		</div>
		<br />
		<div style="font: 125% 'Trebuchet MS', sans-serif;color:#FFF;background-color:CornflowerBlue;text-align:center;padding:10px;-moz-border-radius: 10px;-webkit-border-radius: 10px;-khtml-border-radius: 10px;border-radius: 10px;">
			<b>Produce Custom Results (Short Listing) in few seconds !</b>
		</div>
		<form id="custom_results" method="post" action="">
			<fieldset style="padding: 12px;" title="Select Test">
				<legend>Select Test &amp; Scheduled Date&frasl;s</legend>
				<label for="test_id">Select Test:</label>
				<div class="row fluid">
					<div class="col-lg-3 col-md-3 col-sm-3">
						<select class="form-control input-sm" id="test_id" name="test_id" onchange="OnTestChange();">
							<?php
								echo "<option value=''>Select Test</option>";
								
								foreach ($TestInfoAry as $test_id => $test_info)
								{
									printf("<option value='%s'>%s</option>", $test_id, $test_info['test_name']);
								}
							?>
						</select><br /><br />
					</div>
				</div>
				<div class="row fluid" style="display: none;" id="tschd_table">
					<div class="col-lg-3 col-md-3 col-sm-3">
						<label for="tschd_id" id="tschd_id_label">Select Scheduled Date:</label>
						<select class="form-control input-sm" id="tschd_id" name="tschd_id" style="height: 150px;" onchange="OnScheduledTestChange();" multiple>
						</select>
						<br/><b>(Press &ldquo;CTRL &frasl; SHIFT Key&rdquo; to select more than one scheduled date &frasl; batches)</b>
					</div>
					<div class="col-lg-2 col-md-2 col-sm-2">
					</div>
					<div class="col-lg-3 col-md-3 col-sm-3">
						<label for="batch_id" id="batch_id_label">Select Batches:</label>
						<select class="form-control input-sm" id="batch_id" name="batch_id" style="height: 150px;" multiple>
						</select>
					</div>
				</div>
			</fieldset>
			
			<fieldset title="Range Selection" style="padding: 12px;">
				<legend>Crop Marks, Percent Scored or Top Candidates</legend>
				
				<div class="row fluid">
					<div class="col-lg-2 col-md-2 col-sm-2" style="float:right;">
						<input type="button" class="btn btn-primary" name="show_sec_details" style="text-decoration:none;" onclick="OnShowSecDetails(this);" value="Show Sectional Details" />
					</div>
				</div>
				<br /><br />
				
				<div id="sectionwise_range" style="width: 100%;display: none;"></div>
				
				<fieldset>
					<legend>Total Marks</legend>
					<p>
						<label for="marks">Marks Range:</label>
					  	<input type="text" id="marks" style="border: 0; color: #0099ff; background-color: white; font-weight: bold; font-size :11px;" readonly/>
					</p>
					 
					<div id="slider-range-marks" style="border:1px solid #ccc;"></div>
					<p>
						<label for="percent">Percent Range:</label>
					  	<input type="text" id="percent" style="border: 0; color: #0099ff; background-color: white; font-weight: bold;font-size :11px;" readonly/>
					</p>
					 
					<div id="slider-range-percent" style="border:1px solid #ccc;"></div><br />
				</fieldset><br />
				
				<fieldset>
					<legend>Top Candidates</legend>
					<p>
						<label for="candidate">Candidates:</label>
				  		<input type="text" id="candidate" style="border: 0; color: #0099ff; background-color: white; font-weight: bold;font-size :11px;" readonly/>
					</p>
					<div id="slider-range-candidate" style="border:1px solid #ccc;"></div>
					<br />
				</fieldset><br />
			</fieldset>
			
			<fieldset title="Final List" style="padding: 12px;">
				<legend>Final List of Candidates</legend>
				
				<div id='TableToolsPlacement'>
				</div><br />
				<table id="example" style="font: 12px verdana;" class="table table-striped table-bordered" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th data-class="expand" ><font color="#000000">Schedule ID</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Test Name</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Scheduled On</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Completed On</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Candidate Name</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Marks Obtained</font></th>
							<th data-hide="phone,tablet"><font color="#000000">%ile</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Rank</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Time Taken (MM:SS)</font></th>
						</tr>
					</thead>
					<tbody id='tbody_id'>
					</tbody>
					<tfoot>
						<tr>
							<th data-class="expand" ><font color="#000000">Schedule ID</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Test Name</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Scheduled On</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Completed On</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Candidate Name</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Marks Obtained</font></th>
							<th data-hide="phone,tablet"><font color="#000000">%ile</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Rank</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Time Taken (MM:SS)</font></th>
						</tr>
					</tfoot>
				</table><br /><br />
			</fieldset>
			
			<input type="hidden" class="finish" value="Finish!" />
			
			<div class="modal" id="sec_perfrmnc_modal">
			  	<div class="modal-dialog">
			    	<div class="modal-content">
			      		<div class="modal-header">
			       		 	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			        		<h4 class="modal-title">Section-Wise Performance</h4>
			      		</div>
				      	<div class="modal-body">
				      		<table class="js-responsive-table table table-bordered table-striped">
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
			
		</form><br />
		<?php
		include_once (dirname ( __FILE__ ) . "/../../lib/footer.php");
		?>
	</div>
	<script type="text/javascript">
			function OnScheduledTestChange()
			{
				var scheduled_test_ary = $("#tschd_id").val();
				var batch_id_ary	   = new Array();
				var test_id			   = $("#test_id").val()
				var batch_id_html	   = "";
				for(index in scheduled_test_ary)
				{
					for(batch_id in aryBatchInfo[test_id][scheduled_test_ary[index]])
					{
						if(batch_id_ary.indexOf(batch_id) == -1)
						{
							batch_id_ary.push(batch_id);
							batch_id_html += "<option value='"+batch_id+"'>"+aryBatchInfo[test_id][scheduled_test_ary[index]][batch_id]+"</option>";
						}
					}
				}
				$("#batch_id").html(batch_id_html);
			}

			var bShow = true;
			function OnShowSecDetails(obj)
			{
				if(bShow == true)
				{
					$("#sectionwise_range").show();
					$(obj).val("Hide Sectional Details");
					bShow = false;
				}
				else
				{
					$("#sectionwise_range").hide();
					$(obj).val("Show Sectional Details");
					bShow = true;
				}
			}
			
			function RevSort(data_A, data_B)
			{
			    return (data_B - data_A);
			}

			function GetSectionRangesBySecSlide(ary_sec_marks, UserInfo, sec_name)
			{
				var ary_sec_marks_range  = new Array();

				for(index in UserInfo[0])
				{
					if(index != "user_id" && index != "marks")
					{
						ary_sec_marks_range[index] = new Array();
					}
				} 

				for(user_index = 0; user_index < UserInfo.length; user_index++)
				{
					for(index in UserInfo[user_index])
					{
						if(index != "user_id" && index != "marks" && UserInfo[user_index][sec_name] >= ary_sec_marks[sec_name]['min_marks'] && UserInfo[user_index][sec_name] <= ary_sec_marks[sec_name]['max_marks'])
						{
							if(index != sec_name)
							{
								if(ary_sec_marks_range[index]['min_marks'] === undefined || ary_sec_marks_range[index]['min_marks'] === null || ary_sec_marks_range[index]['min_marks'] === "" || ary_sec_marks_range[index]['min_marks'] > UserInfo[user_index][index])
								{
									ary_sec_marks_range[index]['min_marks'] = UserInfo[user_index][index];
								}
	
								if(ary_sec_marks_range[index]['max_marks'] === undefined || ary_sec_marks_range[index]['max_marks'] === null || ary_sec_marks_range[index]['max_marks'] === "" || ary_sec_marks_range[index]['max_marks'] < UserInfo[user_index][index])
								{
									ary_sec_marks_range[index]['max_marks'] = UserInfo[user_index][index];
								}
							}
							else
							{
								ary_sec_marks_range[index]['min_marks'] = ary_sec_marks[sec_name]['min_marks'];
								ary_sec_marks_range[index]['max_marks'] = ary_sec_marks[sec_name]['max_marks'];
							}
						}
					}
				}
				return ary_sec_marks_range;
			}
			
			function GetSectionwiseCandidates(ary_sec_marks, UserInfo)
			{

				var ary_sec_marks_length = 0;
				var ret_ary = new Array(); 
				for(index in ary_sec_marks)
				{
					ary_sec_marks_length++;
				}
				
				var no_of_candidates = 0;
				var sec_count = 0;
				var total_marks = 0;
				
				for(var user_index = 0; user_index < UserInfo.length; user_index++)
				{
					sec_count = 0;
					total_marks = 0;
					for(index in ary_sec_marks)
					{
						if(UserInfo[user_index][index] >= ary_sec_marks[index]['min_marks'] && UserInfo[user_index][index] <= ary_sec_marks[index]['max_marks'])
						{
							sec_count++;
							total_marks += UserInfo[user_index][index];
						}
					}
					if(sec_count == ary_sec_marks_length)
					{
						if(ret_ary['total_min_marks'] !== null && ret_ary['total_min_marks'] !== "" && ret_ary['total_min_marks'] !== undefined)
						{
							if(total_marks < ret_ary['total_min_marks'])
							{
								ret_ary['total_min_marks'] = total_marks;
							}
							if(total_marks > ret_ary['total_max_marks'])
							{
								ret_ary['total_max_marks'] = total_marks;
							}
						}
						else
						{
							ret_ary['total_min_marks'] = total_marks;
							ret_ary['total_max_marks'] = total_marks;
						}
						no_of_candidates++;
					}	
					ret_ary['candidates'] = no_of_candidates;
				}
				return ret_ary;	
			}

			function GetSectionRanges(top_count, ary_marks, UserInfo)
			{
				var ary_sec_marks_range  = new Array();

				for(index in UserInfo[0])
				{
					if(index != "user_id" && index != "marks")
					{
						ary_sec_marks_range[index] = new Array();
					}
				} 
				
				for(top_index = 0; top_index < top_count; top_index++)
				{
					for(user_index = 0; user_index < UserInfo.length; user_index++)
					{
						if(ary_marks[top_index] == UserInfo[user_index]['marks'])
						{
							for(index in UserInfo[user_index])
							{
								if(index != "user_id" && index != "marks")
								{
									if(ary_sec_marks_range[index]['min_marks'] === undefined || ary_sec_marks_range[index]['min_marks'] === null || ary_sec_marks_range[index]['min_marks'] === "" || ary_sec_marks_range[index]['min_marks'] > UserInfo[user_index][index])
									{
										ary_sec_marks_range[index]['min_marks'] = UserInfo[user_index][index];
									}

									if(ary_sec_marks_range[index]['max_marks'] === undefined || ary_sec_marks_range[index]['max_marks'] === null || ary_sec_marks_range[index]['max_marks'] === "" || ary_sec_marks_range[index]['max_marks'] < UserInfo[user_index][index])
									{
										ary_sec_marks_range[index]['max_marks'] = UserInfo[user_index][index];
									}
								}
							} 
						}
					}	
				}
				return ary_sec_marks_range;
			}

			function GetCandCountByTotalMarks(min_marks, max_marks, UserInfo)
			{
				var cand_count = 0;
				var ret_ary    = new Array();

				for(index in UserInfo[0])
				{
					if(index != "user_id" && index != "marks")
					{
						ret_ary[index] = new Array();
					}
				} 
				
				for(user_index = 0; user_index < UserInfo.length; user_index++)
				{
					
					if(parseFloat(UserInfo[user_index]['marks']) >= parseFloat(min_marks) && parseFloat(UserInfo[user_index]['marks']) <= parseFloat(max_marks))
					{
						cand_count++;

						for(index in UserInfo[user_index])
						{
							if(index != "user_id" && index != "marks")
							{
								if(ret_ary[index]['min_marks'] === undefined || ret_ary[index]['min_marks'] === null || ret_ary[index]['min_marks'] === "" || ret_ary[index]['min_marks'] > UserInfo[user_index][index])
								{
									ret_ary[index]['min_marks'] = UserInfo[user_index][index];
									//console.log(ret_ary[index]['min_marks']+" ");
								}

								if(ret_ary[index]['max_marks'] === undefined || ret_ary[index]['max_marks'] === null || ret_ary[index]['max_marks'] === "" || ret_ary[index]['max_marks'] < UserInfo[user_index][index])
								{
									ret_ary[index]['max_marks'] = UserInfo[user_index][index];
									//console.log(ret_ary[index]['max_marks']+" ");
								}
							}
						}
					}
				}
				ret_ary['candidate_count'] = cand_count;
				return ret_ary;
			}
			
			function InitSliders(TestCriteria, TschdIDInfo, UserInfo)
			{
				var criteria   = TestCriteria['criteria'];
				var top_result = TestCriteria['top_result'];
				var sec_count  = 0;

				var ary_sec_marks = new Array();

				if(criteria == 0)
				{   
					top_result = UserInfo.length;
				}

				var ary_marks = new Array();

				for(var user_index=0; user_index < UserInfo.length; user_index++)
				{
					ary_marks[user_index] = UserInfo[user_index]['marks'];
				}

				ary_marks.sort(RevSort);

				var total_min_marks  = 0;
				var total_max_marks  = 0;
				
				// ----  ----  ----  ----  ----  ----  ----  ----  ----  ----
				// Sectionwise Percent
				// ----  ----  ----  ----  ----  ----  ----  ----  ----  ----
				$('div[id^=slider-range-percent-]').each(function(){

					var sec_name   = $(this).attr('id').slice(21);
					var min_marks  = TestCriteria[sec_name]['max_question'] * TestCriteria[sec_name]['negative_marks'] * -1;
					var max_marks  = TestCriteria[sec_name]['max_question'] * TestCriteria[sec_name]['marks_for_correct'];
					var cutoff_min = TestCriteria[sec_name]['cutoff_min'] * max_marks / 100;
					var cutoff_max = TestCriteria[sec_name]['cutoff_max'] * max_marks / 100;

					total_min_marks += min_marks;
					total_max_marks += max_marks;

					var min_percent = (min_marks/max_marks)*100;
					min_percent = Math.floor(min_percent);

					ary_sec_marks[sec_name] = new Array();

					ary_sec_marks[sec_name]['min_marks'] = min_marks;
					ary_sec_marks[sec_name]['max_marks'] = max_marks;

					if($(this).html() == "")
					{
						$(this).noUiSlider({
							start: [ TestCriteria[sec_name]['cutoff_min'], TestCriteria[sec_name]['cutoff_max'] ],
							step: 1,
							connect: true,
							range: {
								'min': [ min_percent ],
								'max': [ 100 ]
							}
						});
						
						$(this).on({
							slide: function() {
								
								var val_array = $(this).val();
								$( "#percent_"+sec_name ).val( val_array[ 0 ] + "% - " + val_array[ 1 ] + "%" );

								var min_offset = (val_array[ 0 ] * max_marks) / 100 ;
								var max_offset = (val_array[ 1 ] * max_marks) / 100 ;

								min_offset = Math.floor(min_offset);
							    max_offset = Math.ceil(max_offset);

							    ary_sec_marks[sec_name]['min_marks'] = min_offset;
								ary_sec_marks[sec_name]['max_marks'] = max_offset;

								var users_ary = GetSectionwiseCandidates(ary_sec_marks, UserInfo);
							  	
							    $( "#slider-range-candidate" ).val(users_ary['candidates']);
							    $( "#candidate" ).val( users_ary['candidates'] + " candidates in cropped range");

							    var total_min_offset = users_ary['total_min_marks'];
							    var total_max_offset = users_ary['total_max_marks'];
							    
							    var total_min_offset_pcnt = (total_min_offset* 100)/total_max_marks;
							    var total_max_offset_pcnt = (total_max_offset* 100)/total_max_marks;
							    
							    $( "#slider-range-marks" ).val( [total_min_offset, total_max_offset] );
							  	
							  	$( "#slider-range-percent" ).val([total_min_offset_pcnt.toFixed(2),total_max_offset_pcnt.toFixed(2)]  );
							    
							  	$( "#marks" ).val(total_min_offset + " - " + total_max_offset);
							  	$( "#percent" ).val(total_min_offset_pcnt.toFixed(2) + "% - " + total_max_offset_pcnt.toFixed(2) + "%" );

							  	var ary_sec_range = GetSectionRangesBySecSlide(ary_sec_marks, UserInfo, sec_name);

							  	$('div[id^=slider-range-percent-]').each(function(){
							    	var section_name   				= $(this).attr('id').slice(21);
								    var maximum_marks  				= TestCriteria[section_name]['max_question'] * TestCriteria[section_name]['marks_for_correct'];
								    var minimum_offset				= (ary_sec_range[section_name]['min_marks'] * 100)/maximum_marks ;
								    var maximum_offset 				= (ary_sec_range[section_name]['max_marks'] * 100)/maximum_marks ;
		
								    $(this).val( [minimum_offset.toFixed(2), maximum_offset.toFixed(2)] );
		
								    $( "#percent_"+section_name ).val(minimum_offset.toFixed(2) + "% - " + maximum_offset.toFixed(2) + "%" );
								});
							  	
							  }
						});
					}

					$( "#percent_"+sec_name ).val( TestCriteria[sec_name]['cutoff_min'] + "% - "+ TestCriteria[sec_name]['cutoff_max'] + "%");

					sec_count++;				
				});
				// ----  ----  ----  ----  ----  ----  ----  ----  ----  ----
				
				// ----  ----  ----  ----  ----  ----  ----  ----  ----  ----
				// Sections Weightage
				// ----  ----  ----  ----  ----  ----  ----  ----  ----  ----
				$('div[id^=slider-range-weight-]').each(function(){
					var sec_name = $(this).attr('id').slice(20);

					if($(this).html() == "")
					{
						$(this).noUiSlider({
							start: 1,
							step: 1,
							connect: "lower",
							range: {
								'min': [ 1 ],
								'max': [ sec_count ]
							}
						});

						$(this).on({
							slide: function() {
								if(!isNaN($(this).val()))
								{
									$("#weight_"+sec_name).val(parseInt($(this).val()));
								}
							}
						});
						$("#weight_"+sec_name).val(1);
					}
				});
				// ----  ----  ----  ----  ----  ----  ----  ----  ----  ----

				var total_cutoff_min = TestCriteria['cutoff_min'] * total_max_marks / 100;
				var total_cutoff_max = TestCriteria['cutoff_max'] * total_max_marks / 100;
				
				var total_min_percent = (total_min_marks/total_max_marks)*100;
				total_min_percent = Math.floor(total_min_percent);
				// ----  ----  ----  ----  ----  ----  ----  ----  ----  ----
				// Total Marks
				// ----  ----  ----  ----  ----  ----  ----  ----  ----  ----

				if($("#slider-range-marks").html() == "")
				{
					$("#slider-range-marks").noUiSlider({
						start: [ total_cutoff_min, total_cutoff_max ],
						step: 1,
						connect: true,
						range: {
							'min': [ total_min_marks ],
							'max': [ total_max_marks ]
						}
					});

					$("#slider-range-marks").on({
						slide: function() {
							var val_array = $(this).val();
							
							$( "#marks" ).val( val_array[ 0 ] + " - " + val_array[ 1 ] );

							var min_offset = (val_array[ 0 ] * 100)/total_max_marks ;
						    var max_offset = (val_array[ 1 ] * 100)/total_max_marks ;
						    $( "#slider-range-percent" ).val( [min_offset.toFixed(2), max_offset.toFixed(2)] );

						    var users_ary = GetCandCountByTotalMarks(val_array[ 0 ], val_array[ 1 ], UserInfo);

						    $( "#slider-range-candidate" ).val(users_ary['candidate_count']);
						    $( "#candidate" ).val( users_ary['candidate_count'] + " candidates in cropped range");
						    
						    $( "#percent" ).val(min_offset.toFixed(2) + "% - " + max_offset.toFixed(2) + "%" );

						    var ary_sec_range = GetSectionRanges(users_ary['candidate_count'], ary_marks, UserInfo);

						    $('div[id^=slider-range-percent-]').each(function(){
						    	var sec_name   		= $(this).attr('id').slice(21);
						    	var max_marks  		= TestCriteria[sec_name]['max_question'] * TestCriteria[sec_name]['marks_for_correct'];
						    	var min_offset		= (users_ary[sec_name]['min_marks'] * 100)/max_marks ;
						    	var max_offset 		= (users_ary[sec_name]['max_marks'] * 100)/max_marks ;

						    	$(this).val( [ min_offset.toFixed(2), max_offset.toFixed(2)] );

						    	$( "#percent_"+sec_name ).val(min_offset.toFixed(2) + "% - " + max_offset.toFixed(2) + "%" );
							});
						}
					});
				}
				
				$( "#marks" ).val( total_cutoff_min + " - " + total_cutoff_max );

				// ----  ----  ----  ----  ----  ----  ----  ----  ----  ----
				
				// ----  ----  ----  ----  ----  ----  ----  ----  ----  ----
				//Total Percent
				// ----  ----  ----  ----  ----  ----  ----  ----  ----  ----

				if($("#slider-range-percent").html() == "")
				{
					$("#slider-range-percent").noUiSlider({
						start: [ TestCriteria['cutoff_min'], TestCriteria['cutoff_max'] ],
						step: 1,
						connect: true,
						range: {
							'min': [ total_min_percent ],
							'max': [ 100 ]
						}
					});

					$("#slider-range-percent").on({
						slide: function() {
							var val_array = $(this).val();
							
							$( "#percent" ).val( val_array[ 0 ] + "% - " + val_array[ 1 ] + "%" );

							var min_offset = (val_array[ 0 ] * total_max_marks) / 100 ;
							var max_offset = (val_array[ 1 ] * total_max_marks) / 100 ;
							    
							min_offset = Math.floor(min_offset);
							max_offset = Math.ceil(max_offset);
							    
							$( "#slider-range-marks" ).val( [min_offset, max_offset] );

							var users_ary = GetCandCountByTotalMarks(min_offset, max_offset, UserInfo);

							$( "#slider-range-candidate" ).val(users_ary['candidate_count']);
							$( "#candidate" ).val( users_ary['candidate_count'] + " candidates in cropped range");

							var ary_sec_range = GetSectionRanges(users_ary['candidate_count'], ary_marks, UserInfo);

							$('div[id^=slider-range-percent-]').each(function(){
						    	var sec_name   		= $(this).attr('id').slice(21);
						    	var max_marks  		= TestCriteria[sec_name]['max_question'] * TestCriteria[sec_name]['marks_for_correct'];
						    	var min_offset		= (users_ary[sec_name]['min_marks'] * 100)/max_marks ;
						    	var max_offset 		= (users_ary[sec_name]['max_marks'] * 100)/max_marks ;
						    	
						    	$(this).val( [min_offset.toFixed(2), max_offset.toFixed(2)] );
							   	$( "#percent_"+sec_name ).val(min_offset.toFixed(2) + "% - " + max_offset.toFixed(2) + "%" );
							});
							 	
							$( "#marks" ).val(min_offset + " - " + max_offset);
						}
					}); 
					$( "#percent" ).val( TestCriteria['cutoff_min'] + "% - "+ TestCriteria['cutoff_max'] + "%");
				}
				// ----  ----  ----  ----  ----  ----  ----  ----  ----  ----
				
				// ----  ----  ----  ----  ----  ----  ----  ----  ----  ----
				// Candidate
				// ----  ----  ----  ----  ----  ----  ----  ----  ----  ----

				if($("#slider-range-candidate").html() == "")
				{
					$("#slider-range-candidate").noUiSlider({
						start: 1,
						step: 1,
						connect: "lower",
						range: {
							'min': [ 0 ],
							'max': [ top_result ]
						}
					});

					$("#slider-range-candidate").on({
						slide: function() {
							var cand_selected = parseInt($(this).val());
							
							$( "#candidate" ).val( cand_selected + " top candidates");

							var total_min_offset = ary_marks[cand_selected - 1];
							
						    var total_max_offset = ary_marks[0];
						    
						    var total_min_offset_pcnt = (total_min_offset* 100)/total_max_marks;
						    var total_max_offset_pcnt = (total_max_offset* 100)/total_max_marks;
						    
						    $( "#slider-range-marks" ).val( [Math.floor(total_min_offset), Math.ceil(total_max_offset)] );
						  	
						  	$( "#slider-range-percent" ).val( [total_min_offset_pcnt.toFixed(2), total_max_offset_pcnt.toFixed(2)] );
						    
						  	$( "#marks" ).val(total_min_offset + " - " + total_max_offset);
						  	$( "#percent" ).val(total_min_offset_pcnt.toFixed(2) + "% - " + total_max_offset_pcnt.toFixed(2) + "%" );

						    var ary_sec_range = GetSectionRanges(cand_selected, ary_marks, UserInfo);

						    $('div[id^=slider-range-percent-]').each(function(){
						    	var sec_name   		= $(this).attr('id').slice(21);
						    	var max_marks  		= TestCriteria[sec_name]['max_question'] * TestCriteria[sec_name]['marks_for_correct'];
						    	var min_offset		= (ary_sec_range[sec_name]['min_marks'] * 100)/max_marks ;
						    	var max_offset 		= (ary_sec_range[sec_name]['max_marks'] * 100)/max_marks ;

						    	$(this).val( [min_offset.toFixed(2), max_offset.toFixed(2)] );

						    	$( "#percent_"+sec_name ).val(min_offset.toFixed(2) + "% - " + max_offset.toFixed(2) + "%" );
							});
						}
					});
				}

				$( "#candidate" ).val( top_result );

				var cand_count_ary = GetCandCountByTotalMarks(total_cutoff_min, total_cutoff_max, UserInfo);
				$( "#slider-range-candidate" ).val(cand_count_ary['candidate_count']);
				$( "#candidate" ).val( cand_count_ary['candidate_count'] );
			}
			
			var bTblInit = false;
			
			$('#custom_results').stepy({
			validate: true,
			errorImage: true,
			block: true,
			back: function(index) {
				//alert('Going to step ' + index + '...');
			}, next: function(index) {
				if(index == 2)
				{
					var testId		 = $("#test_id").val();
					var tschdIdAary = $("select#tschd_id").val();

					$(".modal1").show();
					$("#sectionwise_range").load("ajax/ajax_get_sectionwise_range.php",{test_id : testId, tschd_id_ary : tschdIdAary}, function(){
						SetSliders(tschdIdAary);
						$(".modal1").hide();
					});
				}
				else if(index == 3)
				{
					var tschd_id_ary  	  = $("select#tschd_id").val();
					var sec_min_marks_ary = new Array();
					var sec_max_marks_ary = new Array();
					var sec_weight_ary    = new Array();
	
					var marks_index = 0;
					$('div[id^=slider-range-percent-]').each(function(){
						var sec_name   		= $(this).attr('id').slice(21);

						var slider_values				= $(this).val();
						sec_min_marks_ary[marks_index] 	= slider_values[0];
						sec_max_marks_ary[marks_index] 	= slider_values[1];
						sec_weight_ary[marks_index]		= $("#slider-range-weight-"+sec_name).val() ? $("#slider-range-weight-"+sec_name).val() : 1;
						marks_index++;
					});
					var slider_range_marks = $( "#slider-range-marks" ).val();
					var min_marks = slider_range_marks[0];
					var max_marks = slider_range_marks[1];
					var top_cand  = $( "#slider-range-candidate" ).val();
						
					PopulateTable(tschd_id_ary, min_marks, max_marks, top_cand, sec_min_marks_ary, sec_max_marks_ary, sec_weight_ary);
				}
			}, select: function(index) {
				if(index == 2)
				{
					var tschdIdAary = $("select#tschd_id").val();
					var testId		 = $("#test_id").val();

					$(".modal1").show();
					$("#sectionwise_range").load("ajax/ajax_get_sectionwise_range.php",{test_id : testId, tschd_id_ary : tschdIdAary}, function(){
						SetSliders(tschdIdAary);
						$(".modal1").hide();
					});
				}
				else if(index == 3)
				{
					
				}
			}, finish: function(index) {
				//alert('Finishing on step ' + index + '...');
			}, titleClick: true
		});

		function SetSliders(tschd_id_ary)
		{
			$(".modal1").show();
			
			//alert("ajax/ajax_get_tschd_info.php?tschd_ids="+tschd_id_ary.join(';'));
			if(tschd_id_ary)
			{
				tschd_id_ary = tschd_id_ary.join(';');
			}
			var batch_ids = ($("#batch_id").val() != "" && $("#batch_id").val() != null)?"&batch_ids="+$("#batch_id").val().join(","):"";
			$.getJSON("ajax/ajax_get_tschd_info.php?tschd_ids="+tschd_id_ary+batch_ids, function(data) {
				var TestCriteria = new Array();
				var TschdIDInfo = new Array();
				var UserInfo = new Array();
				
				var tschd_index = 0;
				var user_index =  0;
				
				$.each(data, function(key, val) {
				    if(key == "test_criteria")
				    {
				    	TestCriteria['criteria'] 			= val['criteria'];
				    	TestCriteria['cutoff_min'] 			= val['cutoff_min'];
						TestCriteria['cutoff_max'] 			= val['cutoff_max'];
						TestCriteria['top_result'] 			= val['top_result'];
						TestCriteria['max_question'] 		= val['max_question'];
						TestCriteria['marks_for_correct'] 	= val['marks_for_correct'];
						TestCriteria['negative_marks'] 		= val['negative_marks'];	
				    }
				    else if(key == "tschd_id")
				    {
				    	$.each(val, function(tschd_id, user_info) {
				    		TschdIDInfo[tschd_index++] = tschd_id;
				    		
				    		$.each(user_info, function(index, details) {
				    			UserInfo[user_index] = new Array();
				    			UserInfo[user_index]['user_id'] = details['user_id'];
				    			UserInfo[user_index]['marks']   = details['marks'];

				    			$.each(details['section_marks'], function(sec_marks_key,sec_marks_val) {
				    				UserInfo[user_index][sec_marks_key] =  sec_marks_val['marks'];
					    		});
				    			user_index++;
				    			//alert(objUtils.dump(UserInfo));
					    	});
				    	});
				    }
				    else if(key == "sec_details")
				    {
				    	$.each(val, function(sec_key, sec_val) {
							if(sec_val['name'] != null && sec_val['name'] != "")
							{
								TestCriteria[sec_val['name']] = new Array();
								
								TestCriteria[sec_val['name']]['max_question'] 		 = sec_val['questions'];
								TestCriteria[sec_val['name']]['cutoff_min']   		 = sec_val['min_cutoff'];
								TestCriteria[sec_val['name']]['cutoff_max']   		 = sec_val['max_cutoff'];
								TestCriteria[sec_val['name']]['marks_for_correct'] 	 = sec_val['mark_for_correct'];
								TestCriteria[sec_val['name']]['negative_marks']   	 = sec_val['mark_for_incorrect'];
							}
						});
					}
				});
				
				InitSliders(TestCriteria, TschdIDInfo, UserInfo);
				$(".modal1").hide();
			});
		}
		
		var objTbl;
		function PopulateTable(tschd_id_ary, min_marks, max_marks, top_cand, sec_min_marks_ary, sec_max_marks_ary, sec_weight_ary)
		{
			$("#example").dataTable().fnDestroy();
			$('#tbody_id').empty();
			$(".modal1").show();
			
			var batch_ids = ($("#batch_id").val() != "" && $("#batch_id").val() != null)?"&batch_ids="+$("#batch_id").val().join(","):"";
			$("#tbody_id").load("ajax/ajax_load_custom_result_table.php?tschd_ids="+tschd_id_ary.join(';')+"&min="+min_marks+"&max="+max_marks+"&top="+top_cand+"&sec_min_marks="+sec_min_marks_ary.join(';')+"&sec_max_marks="+sec_max_marks_ary.join(';')+"&sec_weights="+sec_weight_ary.join(';')+batch_ids, function(){
				

					
					'use strict';
					
					var table;
					var tableElement;
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
					            "aButtons": ["csv", "pdf"]
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
			});
		}
		
		function OnTestChange()
		{
			var test_id = $("#test_id").val();
			
			if(test_id)
			{
				$("#tschd_id").empty();
				$("#batch_id").empty();
				
				for (index in aryTestInfo[test_id])
				{
					$("#tschd_id").append(aryTestInfo[test_id][index]);
				}
				
				
				$("#tschd_table").show();
			}
			else
			{
				$("#tschd_table").hide();
			}
		}

		function ShowSectionWisePerformance(obj)
		{
			var objIdAry = obj.id.split(";");

			$(".modal1").show();
			$('#sec_perfrmnc_tbody').load('ajax/ajax_get_sectionwise_result.php',{test_pnr : objIdAry[0]}, function(){
				$('#sec_perfrmnc_modal').modal("show");
				$(".modal1").hide();
			});
		}
		
		$('#custom_results').validate({
			errorPlacement: function(error, element) {
				$('#custom_results div.stepy-error').append(error);
			}, rules: {
				'test_id':	{required: true},
				'tschd_id': {required: true}
			}, messages: {
				'test_id':	{required: "Please select a test!"},
				'tschd_id':	{required: "Please select at-least one scheduled test (date)!"}
			}
		});
		</script>
</body>
</html>