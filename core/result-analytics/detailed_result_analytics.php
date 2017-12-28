<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	include_once("../../lib/session_manager.php");
	include_once('../../test/lib/tbl_result.php');
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
	
	$TNameAry = $objTR->GetCompletedTestNames($user_id, $nUserType);
	//CUtils::LogDataInFile("CompletedTests.txt", $TNameAry, true);
	
	$objIncludeJsCSS = new IncludeJSCSS();
	
	$menu_id = CSiteConfig::UAMM_RESULT_ANALYTICS;
	$page_id = CSiteConfig::UAP_TEST_DNA_ANALYSIS;
?>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="Generator" content="Mastishka Intellisys Private Limited">
<meta name="Author" content="Mastishka Intellisys Private Limited">
<meta name="Keywords" content="">
<meta name="Description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo(CConfig::SNC_SITE_NAME);?>: Test DNA Analysis</title>
<?php 
$objIncludeJsCSS->CommonIncludeCSS ( "../../" );
$objIncludeJsCSS->IncludeIconFontCSS ( "../../" );
$objIncludeJsCSS->IncludeFuelUXCSS ( "../../" );
$objIncludeJsCSS->CommonIncludeJS ( "../../");
$objIncludeJsCSS->IncludeCanvasMinJS ( "../../");
$objIncludeJsCSS->CommonIncludeHighchartsJS("../../");
$objIncludeJsCSS->IncludeJQueryNouisliderMinJS("../../");
$objIncludeJsCSS->IncludeResultAnalyticsJS("../../");
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
			<div class="row fluid">
				<div class="col-lg-12 col-md-12 col-sm-12">
					<div class="col-lg-3 col-md-3 col-sm-3">
						<select class="form-control input-sm" id='dr_test_id'>
							<?php
								printf("<option value='' selected='selected'>-- Choose Test --</option>", $test_id, $test_name);
								foreach($TNameAry as $test_id => $test_name)
								{
									printf("<option value='%s'>%s</option>", $test_id, $test_name);
								}
							?>
						</select>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-3">
						<select class="form-control input-sm" id='dr_test_schd_date' style="display:none">
						</select>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-3" style="<?php echo(($nUserType == CConfig::UT_INDIVIDAL)?"display:none;": "");?>">
						<select class="form-control input-sm" id='dr_batch' style="display:none" onkeyup="OnBatchChange();" onkeydown="OnBatchChange();" onchange="OnBatchChange();">
						</select>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-3">
						<select class="form-control input-sm" id='dr_candidate_id' style="display:none">
						</select>
					</div>
				</div>
			</div>
			<div class="row fluid" id='result_views_div' style='display:none;'>
				<div class="col-lg-12 col-md-12 col-sm-12">
					<br />
					<div class="col-lg-3 col-md-3 col-sm-3">
						<select class="form-control input-sm" id='result_views' onkeyup="OnResultViewChange();" onkeydown="OnResultViewChange();" onchange="OnResultViewChange();">
						</select>
					</div>
				</div>
			</div>
			<div class="row fluid">
				<div class="col-lg-12 col-md-12 col-sm-12">
					<br />
					<a href="#" target="_blank" id="download_pdf" style="display:none;float: right;" onclick="DownloadPDF(this);"><img width="70" align="top" height="70" src="../../images/export_pdf.jpg" title="Export Result In PDF"/></a>
				</div>
			</div>
			<div class="row fluid">
				<div class="col-lg-12 col-md-12 col-sm-12">
					<div id="result_charts" style="display:none">
					</div>
				</div>
			</div>
			<div class="row fluid">
				<div class="col-lg-12 col-md-12 col-sm-12">
					<div id="holistic_view" style="display:none;">
					</div>
				</div>
			</div>
			<div class="row fluid">
				<div class="col-lg-12 col-md-12 col-sm-12">
					<div id="iq_view" style="display:none;">
					</div>
				</div>
			</div>
			<div class="row fluid">
				<div class="col-lg-12 col-md-12 col-sm-12">
					<div id="eq_view" style="display:none;">
					</div>
				</div>
			</div>
			<?php
			include_once (dirname ( __FILE__ ) . "/../../lib/footer.php");
			?>
		</div>
	</div>
	<script type="text/javascript">
		$('#dr_test_id').change(function() {
			var test_id = $('#dr_test_id').val();
			//
			$('#dr_test_schd_date').hide();
			$('#dr_candidate_id').hide();
			$("#dr_batch").hide();
			$("#result_charts").hide();
			$("#download_pdf").hide();
			$("#holistic_view").hide();
			$("#iq_view").hide();
			$("#eq_view").hide();
			$("#result_views_div").hide();
			$(".modal1").show();
				
			$('#dr_test_schd_date').load('ajax/ajax_get_test_dates.php?test_id='+test_id, function(){
				$('#dr_test_schd_date').show();
				$("#result_views").load('ajax/ajax_populate_detailed_result_views.php', {'test_id' : test_id}, function(){
					$(".modal1").hide();
				});
			});
		});

		var holistic_legend_ary = new Array();
		var range_ary = new Array();
		<?php 
			foreach(CConfig::$HOLISTIC_CHART_LEGEND_ARY as $legend_value=>$legend_category)
			{
				printf("holistic_legend_ary.push('%s');\n\t",$legend_value." (".$legend_category.")");
				printf("range_ary.push('%s');\n\t", $legend_category);
			}
		?>

		var iq_legend_ary = new Array();
		var iq_range_ary = new Array();
		<?php 
			foreach(CConfig::$IQ_CHART_LEGEND_ARY as $legend_value=>$legend_category)
			{
				printf("iq_legend_ary.push('%s');\n\t",$legend_value." (".$legend_category.")");
				printf("iq_range_ary.push('%s');\n\t", $legend_category);
			}
		?>
		
		var result_cand_ary = "";
		var batch_ary       = "";
		$('#dr_test_schd_date').change(function() {
			$("#result_charts").hide();
			$("#holistic_view").hide();
			$("#download_pdf").hide();
			$("#iq_view").hide();
			$("#eq_view").hide();
			$("#result_views_div").hide();
			var test_id = $('#dr_test_id').val();
			var tschd_id = $('#dr_test_schd_date').val();
	
			var current_date = new Date();
			var time_zone    = -current_date.getTimezoneOffset()/60;
			
			$(".modal1").show();
	
			result_cand_ary  = new Array();
			batch_ary        = new Array();
			var result_cands = "<option value=''>--Choose Candidate--</option>";
			var batches		 = "<option value=''>--Choose Specific Batch--</option>"
			$.ajax({
					 url: 'ajax/ajax_get_result_candidates.php?test_id='+test_id+'&tschd_id='+tschd_id+'&time_zone='+time_zone,
				dataType: 'json',
				 success: function(data) {
					 $.each(data, function(key, value){
						 result_cand_ary[key] = new Array();
						 
						 result_cand_ary[key]['result'] = value['result'];
						 result_cand_ary[key]['batch']  = value['batch'];
	
						 if(batch_ary.indexOf(value['batch']) == -1 && value['batch'] != "")
						 {
						 	batch_ary.push(value['batch']);
						 	batches   += "<option value='"+value['batch']+"'>"+value['batch']+"</option>";
						 }
						 result_cands += "<option value='"+key+"'>"+value['result']+"</option>";
					 });
	
					 $("#dr_batch").html(batches);
					 $('#dr_candidate_id').html(result_cands);
					 
					 $('#dr_candidate_id').show();
	
					<?php 
						if($nUserType != CConfig::UT_INDIVIDAL)
					 	{
					?>
					 $("#dr_batch").show();
					<?php 
						}
					?>
					$(".modal1").hide();
				 } 
			});
		});
	
		function DownloadPDF(obj)
		{
			var test_pnr = $('#dr_candidate_id').val();
			var resultView = $("#result_views").val();
			$(obj).attr("href", "ajax/ajax_download_result_pdf.php?test_pnr="+test_pnr+"&test_dna="+resultView);
		}

		function OnResultViewChange()
		{
			LoadResult();
		}
	
		function OnBatchChange()
		{
			$("#result_charts").hide();
			$("#download_pdf").hide();
			$("#holistic_view").hide();
			$("#iq_view").hide();
			$("#eq_view").hide();
			$("#result_views_div").hide();
			var result_cands = "<option value=''>--Choose Candidate--</option>";
	
			for(index in result_cand_ary)
			{
				if($("#dr_batch").val() == result_cand_ary[index]['batch'] && $("#dr_batch").val() != "")
				{
					result_cands += "<option value='"+index+"'>"+result_cand_ary[index]['result']+"</option>";
				}
				else if($("#dr_batch").val() == "")
				{
					result_cands += "<option value='"+index+"'>"+result_cand_ary[index]['result']+"</option>";
				}
			}
			$('#dr_candidate_id').html(result_cands);
		}

		function LoadResult()
		{
			$("#result_views_div").show();
			var test_pnr = $('#dr_candidate_id').val();
			var resultView = $("#result_views").val();
			
			if(resultView == <?php echo(CConfig::PRV_DETAILED);?>)
			{
				$('#gauge_container').hide();
				if(test_pnr)
				{
					$("#download_pdf").show();
				}
				else
				{
					$("#download_pdf").hide();
				}

				if(test_pnr)
				{
					$(".modal1").show();

					$("#holistic_view").hide();
					$("#iq_view").hide();
					$("#eq_view").hide();
					//alert('ajax/ajax_fetch_result.php?test_pnr='+test_pnr);
					$.ajax({
					    dataType: 'json',
					    data: {'resultView' : resultView},
						type: 'POST',
					    success: function(data) {
						    //alert("Test");
						    if(jQuery.isEmptyObject(data))
					    	{
					    		$("#result_charts").empty();
					    		$("#result_charts").append("<h2>No results found, test was not attempted properly.</h2>");
					    		$(".modal1").hide();
					    		return;
					    	}

					    	var arySection 		 = new Array();
					    	var arySubject 		 = new Array();
					    	var aryTopic 		 = new Array();
					    	var arySecCorrect 	 = new Array();
					    	var arySecWrong    	 = new Array();
					    	var arySecUnanswered = new Array();
					    	var arySubCorrect 	 = new Array();
					    	var arySubWrong    	 = new Array();
					    	var arySubUnanswered = new Array();
					    	var aryTpcCorrect 	 = new Array();
					    	var aryTpcWrong    	 = new Array();
					    	var aryTpcUnanswered = new Array();
					    	var aryDifCorrect 	 = new Array();
					    	var aryDifWrong    	 = new Array();
					    	var aryDifUnanswered = new Array();
					    	var nTotalCorrectAns = 0, nTotalWrongAns = 0, nTotalUnanswered = 0;
					    	$.each(data, function (section, subject_ary){
								//alert("Section: "+section);
								var nSecCorrectAns = 0, nSecWrongAns = 0, nSecUnanswered = 0;
								arySection.push(section);
								
								$.each(subject_ary, function (subject, topic_ary){
									//alert("Subject: "+subject);
									arySubject.push(subject);
									var nSubCorrectAns = 0, nSubWrongAns = 0, nSubUnanswered = 0;
									
									$.each(topic_ary, function (topic, difficulty_ary){
										//alert("Topic: "+topic);
										var nDifCorrectAns = new Array(), nDifWrongAns = new Array(), nDifUnanswered = new Array();
										nDifCorrectAns[1] = 0, nDifCorrectAns[2] = 0, nDifCorrectAns[3] = 0;
										nDifWrongAns[1] = 0, nDifWrongAns[2] = 0, nDifWrongAns[3] = 0;
										nDifUnanswered[1] = 0, nDifUnanswered[2] = 0, nDifUnanswered[3] = 0;
									
										if(aryTopic[subject] == undefined)
										{
											aryTopic[subject] 			= new Array();
											aryTpcCorrect[subject] 		= new Array();
											aryTpcWrong[subject] 		= new Array();
											aryTpcUnanswered[subject] 	= new Array();
											aryDifCorrect[subject] 		= new Array();
											aryDifWrong[subject] 		= new Array();
											aryDifUnanswered[subject] 	= new Array();
										}
										if(aryDifCorrect[subject][topic] == undefined)
										{
											aryDifCorrect[subject][topic]		= new Array();
											aryDifWrong[subject][topic] 		= new Array();
											aryDifUnanswered[subject][topic] 	= new Array();
										}
										
										aryTopic[subject].push(topic);
										var nTpcCorrectAns = 0, nTpcWrongAns = 0, nTpcUnanswered = 0;
										
										$.each(difficulty_ary, function (difficulty, question_ary){
											//alert("Difficulty: "+difficulty);
												
											$.each(question_ary, function (question, answer){
												//alert("Question: "+question+", Answer: "+answer);
												if(answer == 1)
												{
													nSecCorrectAns++;
													nSubCorrectAns++;
													nTpcCorrectAns++;
													nDifCorrectAns[difficulty]++;
													nTotalCorrectAns++;
												}
												else if(answer == 0)
												{
													nSecWrongAns++;
													nSubWrongAns++;
													nTpcWrongAns++;
													nDifWrongAns[difficulty]++;
													nTotalWrongAns++;
												}
												else if(answer == -1 || answer == -2 || answer == -3)
												{
													nSecUnanswered++;
													nSubUnanswered++;
													nTpcUnanswered++;
													nDifUnanswered[difficulty]++;
													nTotalUnanswered++;
												}
											});
										});
										
										aryDifCorrect[subject][topic].push(nDifCorrectAns[1]);
										aryDifCorrect[subject][topic].push(nDifCorrectAns[2]);
										aryDifCorrect[subject][topic].push(nDifCorrectAns[3]);
										
										aryDifWrong[subject][topic].push(nDifWrongAns[1]);
										aryDifWrong[subject][topic].push(nDifWrongAns[2]);
										aryDifWrong[subject][topic].push(nDifWrongAns[3]);
										
										aryDifUnanswered[subject][topic].push(nDifUnanswered[1]);
										aryDifUnanswered[subject][topic].push(nDifUnanswered[2]);
										aryDifUnanswered[subject][topic].push(nDifUnanswered[3]);
										
										aryTpcCorrect[subject].push(nTpcCorrectAns);
										aryTpcWrong[subject].push(nTpcWrongAns);
										aryTpcUnanswered[subject].push(nTpcUnanswered);
									});
									
									arySubCorrect.push(nSubCorrectAns);
									arySubWrong.push(nSubWrongAns);
									arySubUnanswered.push(nSubUnanswered);
								});
								
								arySecCorrect.push(nSecCorrectAns);
								arySecWrong.push(nSecWrongAns);
								arySecUnanswered.push(nSecUnanswered);
							});
							
							objRAna.LoadCharts(arySection, arySubject, aryTopic, 
					    			   arySecCorrect, arySecWrong, arySecUnanswered,
					    			   arySubCorrect, arySubWrong, arySubUnanswered,
					    			   aryTpcCorrect, aryTpcWrong, aryTpcUnanswered,
					    			   aryDifCorrect, aryDifWrong, aryDifUnanswered,
					    			   nTotalCorrectAns, nTotalWrongAns, nTotalUnanswered);
							$(".modal1").hide();
					    },
					    error: function (xhr, ajaxOptions, thrownError) {
					    	$(".modal1").hide();
					      },
					    url: 'ajax/ajax_fetch_result.php?test_pnr='+test_pnr
					});
				}	
			}
			else if(resultView == <?php echo(CConfig::PRV_HOLISTIC);?>)
			{
				$(".modal1").show();
				$("#result_charts").hide();
				$("#iq_view").hide();
				$("#eq_view").hide();
				$.ajax({
					url: 'ajax/ajax_fetch_result.php?test_pnr='+test_pnr,
					data: {'resultView' : resultView},
					type: 'POST',
					dataType: 'json',
					success: function(data) {
							if(jQuery.isEmptyObject(data))
					    	{
					    		$("#holistic_view").empty();
					    		$("#holistic_view").append("<h2>No results found, test was not attempted properly.</h2>");
					    		$(".modal1").hide();
					    		return;
					    	}
							objRAna.LoadHolisticCharts(data, holistic_legend_ary, range_ary);
							$("#download_pdf").show();
							$(".modal1").hide();
						}

				});
			}
			else if(resultView == <?php echo(CConfig::PRV_IQ);?>)
			{
				$(".modal1").show();
				$("#result_charts").hide();
				$("#holistic_view").hide();
				$("#eq_view").hide();
				$.ajax({
					url: 'ajax/ajax_fetch_result.php?test_pnr='+test_pnr,
					data: {'resultView' : resultView},
					type: 'POST',
					dataType: 'json',
					success: function(data) {
							if(jQuery.isEmptyObject(data))
					    	{
					    		$("#iq_view").empty();
					    		$("#iq_view").append("<h2>No results found, test was not attempted properly.</h2>");
					    		$(".modal1").hide();
					    		return;
					    	}
							objRAna.LoadIQResultCharts(data, '<?php echo(CSiteConfig::ROOT_URL);?>/images/IQ_curve.png', iq_legend_ary, iq_range_ary);
							$("#download_pdf").show();
							$(".modal1").hide();
						}

				});
			}
			else if(resultView == <?php echo(CConfig::PRV_EQ);?>)
			{
				$(".modal1").show();
				$("#result_charts").hide();
				$("#holistic_view").hide();
				$("#iq_view").hide();
				$.ajax({
					url: 'ajax/ajax_fetch_result.php?test_pnr='+test_pnr,
					data: {'resultView' : resultView},
					type: 'POST',
					dataType: 'json',
					success: function(data) {
							if(jQuery.isEmptyObject(data))
					    	{
					    		$("#eq_view").empty();
					    		$("#eq_view").append("<h2>No results found, test was not attempted properly.</h2>");
					    		$(".modal1").hide();
					    		return;
					    	}
							else
							{
								$("#eq_view").empty();
								var sEQ = ""; 
								$.each(data, function(section, analysis){

									sEQ += "<h2> "+section+" </h2>";
									sEQ += "<p> "+analysis+" </p>";
									sEQ += "<hr />";
								});
								$("#eq_view").append(sEQ);
								$("#eq_view").show();
								$("#download_pdf").show();
							}
							$(".modal1").hide();
						}

				});
			}
		}

		$('#dr_candidate_id').change(function() {
			var test_pnr = $('#dr_candidate_id').val();
			if(test_pnr)
			{
				if(test_pnr)
				{
					$(".modal1").show();
					$("#result_views").load('ajax/ajax_populate_detailed_result_views.php', {'test_pnr' : test_pnr}, function(){
						$(".modal1").hide();
						LoadResult();
					});
				}
			}
			else
			{
				$("#result_charts").hide();
				$("#download_pdf").hide();
				$("#holistic_view").hide();
				$("#iq_view").hide();
				$("#eq_view").hide();
				$("#result_views_div").hide();
			}
		});
	</script>
</body>
</html>