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
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	$user_type = CSessionManager::Get(CSessionManager::INT_USER_TYPE);
	
	$parsAry = parse_url(CUtils::curPageURL());
	$qry = split("[=&]", $parsAry["query"]);
	
	
	$count			 = 0;
	$numOfCandidates = 0;
	$test_schd_id	 = 0;
	$testId	 	 	 = 0;
	$scheduled_date	 = "";
	$test_name		 = "";
	if($qry[0] == "count")
	{
		printf("<script>save_success = 1;</script>");
		$count = $qry[1];
		
		if($qry[2] == "tschd_id")
		{
			$test_schd_id 		= $qry[3];
		}
		
		if($qry[4] == "test_id")
		{
			$testId 	= $qry[5];
			$test_name  = $objDB->GetTestName($testId);
		}
		
		if($qry[6] == "candidates")
		{
			$numOfCandidates = $qry[7];
		}
		
		if($qry[8] == "schdld_date")
		{
			$scheduled_date 	= date("F j, Y", $qry[9]);
		}
	}
	else 
	{
		printf("<script>save_success = 0;</script>");
	}
	
	$objTR = new CResult();
	$TestInfoAry = $objTR->GetScheduledTestInfo($user_id);
	
	
	$objIncludeJsCSS = new IncludeJSCSS();
	
	$menu_id = CSiteConfig::UAMM_SCHEDULE_TEST;
	$page_id = CSiteConfig::UAP_MANAGE_SCHEDULED_TEST;
?>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="Generator" content="EditPlus">
<meta name="Author" content="">
<meta name="Keywords" content="">
<meta name="Description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo(CConfig::SNC_SITE_NAME);?>: Manage Scheduled Test</title>
<?php 
$objIncludeJsCSS->CommonIncludeCSS("../../");
$objIncludeJsCSS->IncludeIconFontCSS("../../");
$objIncludeJsCSS->IncludeFuelUXCSS ( "../../" );
$objIncludeJsCSS->CommonIncludeJS("../../");
$objIncludeJsCSS->IncludeMetroNotificationJS("../../");
$objIncludeJsCSS->IncludeJqueryValidateMinJS("../../");
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
			printf("aryTestInfo[%d] = new Array();",$test_id);
			
			foreach ($test_info['schd_id'] as $key => $tschd_id) 
			{
				
				
				printf("aryTestInfo[%d][%d] = \"<option value='%s'>%s (xID: %s)</option>\";", $test_id, $index, $tschd_id, $test_info['scheduled_on'][$key], $tschd_id);
				
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
	</div>
	<div class="col-lg-9 col-md-9 col-sm-9" style="border-left: 1px solid #ddd; border-top: 1px solid #ddd;">
		<div class="fuelux modal1">
			<div class="preloader"><i></i><i></i><i></i><i></i></div>
		</div>
		<br />
		<div class="row fluid">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<form method="POST" action="post_get/form_edit_scheduled_test.php" id="form_schedule" onsubmit="return OnSubmit();">
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6" style="border-right:1px solid #ddd;">
							<div class="row">
								<div class="col-lg-5 col-md-5 col-sm-5">
									<label for="test_id"><b>Select Test:</b></label>
									<select class="form-control input-sm" id="test_id" name="test_id" onkeyup="OnTestChange();" onkeydown="OnTestChange();" onclick="OnTestChange();" onchange="OnTestChange();">
										<?php
											$objDB->PrepareScheduledTestCombo($user_id, $testId);
										?>
									</select>&nbsp;
								</div>
								<div class="col-lg-5 col-md-5 col-sm-5" style="padding-left: 0px;">
									<a href="javascript:" style="position:relative;top: 25px;" onclick="OnTestDetails()"><img style="position:relative;" src="../../images/question_mark_small.png" width="18px" height="18px"/>Test Details</a>
								</div><br /><br /><br /><br />
							</div>
							<div class="row">
								<div class="col-lg-8 col-md-8 col-sm-8">
									<label for="tschd_id" id="tschd_id_label"><b>Select Scheduled Date: </b></label>
									<select class="form-control input-sm" id="tschd_id" name="tschd_id" onkeyup="OnTestScheduledDateChange();" onclick="OnTestScheduledDateChange();" onkeypress="OnTestScheduledDateChange();" onchange="OnTestScheduledDateChange();">
									</select>
									<input type="hidden" id="time_zone" name="time_zone"/>
					    		</div>
							</div><br /><br />
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6">
							<br /><br />
							<div class="col-lg-10 col-md-10 col-sm-10 col-lg-offset-2 col-md-offset-2 col-sm-offset-2">
								<div class="reg-error">
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-5 col-md-5 col-sm-5">
							<div class="row-fluid">
								<span style="font-size: 12px;"><b>Scheduled Candidates List :</b></span>
							</div>
							<div class="row-fluid">
								<select style="height:250px" class="form-control" id="choose_candidate" multiple="multiple">
								</select>
							</div>
							<div class="row-fluid" style="text-align: center;">
								<h5>^ Choose From ^</h5>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2" style="height:270px;border:1px solid #ddd;">
							<br /><br /><br /><br /><br />
							<div class="row">
								<div class="col-lg-10 col-md-10 col-sm-10 col-lg-offset-3 col-md-offset-3 col-sm-offset-3">
									<input type="button" class="btn btn-sm btn-success" onclick="OnAdd();" value="Add &gt;&gt;"/>
								</div>
							</div><br />
							<div class="row">
								<div class="col-lg-10 col-md-10 col-sm-10  col-lg-offset-2 col-md-offset-2 col-sm-offset-2">
									<input type="button" class="btn btn-sm btn-info" onclick="OnRemove();" value="&lt;&lt; Remove"/>
								</div>
							</div><br />
						</div>
						<div class="col-lg-5 col-md-5 col-sm-5">
							<br />
							<div class="row-fluid">
								<select style="height:250px" class="form-control" id="selected_candidate" multiple="multiple">
								</select>
							</div>
							<div class="row-fluid" style="text-align: center;">
								<h5>^ Selected Candidates ^</h5>
							</div>
							<input type="hidden" id="candidate_list" name="candidate_list" value=""/>
							<input type="hidden" id="pnr_list" name="pnr_list" value=""/>
						</div>
					</div>
					<div class="row-fliud">
						<div class="col-lg-7 col-md-7 col-sm-7 col-lg-offset-5 col-md-offset-5 col-sm-offset-5">
							<input type="button" class="btn btn-success" onclick="window.location=window.location;" value="Refresh"/>
							<input id="change" class="btn btn-primary" type="submit" value="Discard!"/>
						</div>
					</div>
				</form>
			</div>
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
		<br /><br /><br />
		<?php
		include_once (dirname ( __FILE__ ) . "/../../lib/footer.php");
		?>
	</div>
	<script type="text/javascript">
		function OnTestDetails()
		{
			$(".modal1").show();
			
			$("#test_details_modal_body").load("../ajax/ajax_test_details.php?test_id="+$("#test_id").val(), function(){
				$("#test_details_modal").modal("show");
				$(".modal1").hide();
			});
		}

		function OnAdd()
		{
			var cand_list_val = $("#choose_candidate").val();
			
			for (index in cand_list_val)
			{
				$("#selected_candidate").append("<option style='color:darkblue;' value='"+cand_list_val[index]+"'>"+$("#choose_candidate option[value="+cand_list_val[index]+"]").text()+"</option>");
				$("#choose_candidate option[value="+cand_list_val[index]+"]").remove();
			}
			
		}
		
		function OnRemove()
		{
			var cand_list_val = $("#selected_candidate").val();
			//var cand_list_text = $("#choose_candidate").text();
			
			for (index in cand_list_val)
			{
				$("#choose_candidate").append("<option style='color:darkblue;' value='"+cand_list_val[index]+"'>"+$("#selected_candidate option[value="+cand_list_val[index]+"]").text()+"</option>");
				$("#selected_candidate option[value="+cand_list_val[index]+"]").remove();
			}
		}
		
		function OnSubmit()
		{
			var bRet = true;
			
			var sCandList = "";
			var sPNRList  = "";
			var nCandCount = 0;
			$("#selected_candidate option").each(function(i){
				sCandList += $(this).val() + ";";
		        nCandCount++;
		    });
		    
		    $("#candidate_list").val(sCandList);
			$("#pnr_list").val(sPNRList);
			
			return bRet;
		}
		
		$(document).ready(function () {

			OnTestChange();

			if(save_success == 1)
			{
				var not = $.Notify({
    				 caption: "Candidates Discarded",
    				 content: "<?php echo(($numOfCandidates == $count)?$count." out of ".$numOfCandidates." candidates has been successfully removed from test ".$test_name." scheduled on ".$scheduled_date."(xID: ".$test_schd_id.")":$count." out of ".$numOfCandidates." candidates has been successfully removed from test ".$test_name." scheduled on ".$scheduled_date."(xID: ".$test_schd_id."). Remaining candidates either have started their tests or have finished while removing them.");?>",
    				 style: {background: 'green', color: '#fff'}, 
    				 timeout: 5000
    				 });
			}
			
			$('#form_schedule').validate({
				errorPlacement: function(error, element) {
					$('#form_schedule div.reg-error').append(error);
				}, rules: {
					'test_id':			{required: true},
					'tschd_id':			{required: true},
					'candidate_list':	{required: true}
				}, messages: {
					'test_id':			{required:  "<p style='color:red;'>* Please select a test!</p>"},
					'tschd_id':			{required:  "<p style='color:red;'>* Please select a date on which test to be scheduled!</p>"},
					'candidate_list':	{required:  "<p style='color:red;'>* Please select atleast one candidate from existing scheduled candidates!</p>" }
				}
			});
			
			$("#form_schedule").data("validator").settings.ignore = "";
		});

		function OnTestChange()
		{
			var test_id = $("#test_id").val();
			
			if(test_id)
			{
				$(".modal1").show();
				
				$('#tschd_id').load('ajax/ajax_test_scheduled_dates.php?test_id='+test_id+'&tschd_id='+<?php echo($test_schd_id);?>, function(data){
					//alert(data);
					//$('#payment_info').show();
					OnTestScheduledDateChange();
					$(".modal1").hide();
				});
			}
		}

		function OnTestScheduledDateChange()
		{
			var tschd_id = $("#tschd_id").val();
			
			if(tschd_id)
			{
				$(".modal1").show();
				
				$("#selected_candidate").empty();
				$('#choose_candidate').load('ajax/ajax_scheduled_candidates.php?tschd_id='+tschd_id, function(data){
					$(".modal1").hide();
					//alert(data);
					//$('#payment_info').show();
				});
			}
		}
	</script>
</body>
</html>