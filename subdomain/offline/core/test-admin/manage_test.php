<!doctype html>
<?php
include_once (dirname ( __FILE__ ) . "/../../lib/include_js_css.php");
include_once (dirname ( __FILE__ ) . "/../../lib/session_manager.php");
include_once (dirname ( __FILE__ ) . "/../../lib/site_config.php");
include_once (dirname ( __FILE__ ) . "/../../database/mcat_db.php");

// - - - - - - - - - - - - - - - - -
// On Session Expire Load ROOT_URL
// - - - - - - - - - - - - - - - - -
CSessionManager::OnSessionExpire ();
// - - - - - - - - - - - - - - - - -

$objDB = new CMcatDB ();

$user_id = CSessionManager::Get ( CSessionManager::STR_USER_ID );

$user_type = CSessionManager::Get ( CSessionManager::INT_USER_TYPE );

$objIncludeJsCSS = new IncludeJSCSS ();

$activeTestParams = $objDB->GetActiveTestStartParams();

$current_active_test = null;
if(!empty($activeTestParams))
{
	$current_active_test = $objDB->GetActiveTestName();
}

$candStatusAry = $objDB->PopulateCandidatesWithTestStatus();

$notStarted = $candStatusAry['total'] - ($candStatusAry['finished'] + $candStatusAry['unfinished']);

$bTestStartedByAdmin = $objDB->IsTestStartedByAdmin();
$submit_button_val = "Start Candidates Logins";
if($bTestStartedByAdmin == 1)
{
	$submit_button_val = "Hold Candidates Logins";
}

$menu_id = CSiteConfig::UAMM_MANAGE_TEST;
?>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="Generator" content="Mastishka Intellisys Private Limited">
<meta name="Author" content="Mastishka Intellisys Private Limited">
<meta name="Keywords" content="">
<meta name="Description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo(CConfig::SNC_SITE_NAME);?> Offline: Manage Test</title>
<?php
$objIncludeJsCSS->CommonIncludeCSS ( "../../" );
$objIncludeJsCSS->IncludeIconFontCSS ( "../../" );
$objIncludeJsCSS->IncludeFuelUXCSS ( "../../" );
$objIncludeJsCSS->CommonIncludeJS ("../../");
$objIncludeJsCSS->IncludeMetroNotificationJS ("../../");
$objIncludeJsCSS->IncludeJqueryValidateMinJS("../../");
$objIncludeJsCSS->IncludeCanvasMinJS ("../../");
?>
<style type="text/css">
	#overlay { position: fixed; left: 0px; top: 0px; width: 100%; height: 100%; z-index: 100; background-color:white;}
	
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
	if($user_type == CConfig::UT_INDIVIDAL)
	{
	?>
	<div id="overlay" style="display:none">
		<iframe id="overlay_frame" src="#" width="100%" height="100%"></iframe>
	</div>
	<?php 
	}
	?>

	<?php
	include_once (dirname ( __FILE__ ) . "/../../lib/header.php");
	?>

	<!-- --------------------------------------------------------------- -->
	<br />
	<br />
	<br />
	<div class='row-fluid'>
		<div class="col-lg-3 col-md-3 col-sm-3">
			<?php
			include_once (dirname ( __FILE__ ) . "/../../lib/sidebar.php");
			?>
		</div>
		<div class="col-lg-9 col-md-9 col-sm-9" style="border-left: 1px solid #ddd; border-top: 1px solid #ddd;">
			<br />
			<div class="fuelux modal1">
				<div class="preloader"><i></i><i></i><i></i><i></i></div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6" style="height: 400px;">
				<br /><br />
				<form class="form-horizontal" action="post_get/form_manage_test.php" method="post" enctype="multipart/form-data" id="upload_start_form">
					<div class="col-lg-offset-1">
						<label for="activeTest">Current Active Test :</label>
						<div class="form-group">
					      <div class="col-lg-7">
					        <input class="form-control input-sm" name="activeTest" id="activeTest" value="<?php echo(!empty($current_active_test)?$current_active_test:"Not Available");?>" disabled="" type="text">
					      	<input type='hidden' name="active_test" value='<?php echo(!empty($current_active_test)?$current_active_test:"Not Available");?>'>
					      </div>
					    </div>
				    </div>
				    
				    <?php 
				   if(!empty($current_active_test))
				    {
				    ?>
				    <div class="col-lg-offset-1">
					    <div class="form-group">
						    <div class="col-lg-7 col-md-7 col-sm-7">
						    	<div class="radio">
						          <label>
						            <input type="radio" id="start_test_choice" value='1' name="upload_start_test_choice" onchange="OnTestChoiceChange();" checked='checked'> Current Active Test
						          </label>
						        </div>
						       	<div class="radio">
						       		<label>
							            <input type="radio" id="upload_test_choice" value='0' name="upload_start_test_choice" onchange="OnTestChoiceChange();"> Upload a new test
						       		</label>
						       	</div>
						     </div>
						</div>
					</div>
					<?php 
				    }
				    ?>
				    
				    <div class="col-lg-offset-1" id="zip_file_div">
					    <label>Import Test Data (zip):</label>
					    <i class="icon-help mip-help" title="" data-placement="right" trigger="click hover focus" data-toggle="tooltip" data-html="true" data-original-title="select the zip file you have downloaded from your online <span style='color: red'><?php echo(strtolower(CConfig::SNC_SITE_NAME));?>.com</span> account"></i>
						<div class="form-group">
							<div class="col-lg-7 col-md-7 col-sm-7">
								<div class="metro">
									<div class="input-control file">
									    <input name="zip" type="file" />
									    <button class="btn-file"></button>
								    </div>
								</div>
								<div id="error">
								</div>
							</div>
						</div>
					</div>
					<br />
					<div class="form-group">
				      <div class="col-lg-4 col-lg-offset-1">
				        <button id="submit_btn" type="submit" class="btn btn-primary">Upload Test Data!</button>
				      </div>
				    </div>
				</form>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6">
				<div id="chartContainer" style='height:350px;'></div>
				<div id='refreshing_status' style="text-align: center;"></div>
			</div>
			<div class="modal" id="warning_modal">
				  <div class="modal-dialog">
				    <div class="modal-content">
				      <div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				        <h4 class="modal-title">Upload Test Warning</h4>
				      </div>
				      <div class="modal-body">
				      	<p style='color: red;'>If you upload new test, data of current active test will be deleted!</p>  
				      </div>
				      <div class="modal-footer">
				      	<button type="button" class="btn btn-default" onclick="CancelUploadNewTest();"><b>Cancel</b></button>
				        <button type="button" class="btn btn-warning" data-dismiss="modal"><b>I Understand the risk !</b></button>
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
		<?php 
		if(!empty($current_active_test))
		{
		?>
		function OnTestChoiceChange()
		{
			var val = $("input[name=upload_start_test_choice]:checked").val();
	
			if(val == 0)
			{
				$("#zip_file_div").show();
				$("#warning_modal").modal("show");
				$("#submit_btn").html("Upload Test Data!");
			}
			else
			{
				$("#zip_file_div").hide();
				$("#submit_btn").html("<?php echo($submit_button_val);?>!");
			}
		}

		function CancelUploadNewTest()
		{
			$("#warning_modal").modal("hide");
			$("#upload_test_choice").prop("checked", false);
			$("#start_test_choice").prop("checked", true);
			$("#zip_file_div").hide();
			$("#submit_btn").html("<?php echo($submit_button_val);?>!");
		}
		<?php 
		}
		?>

		var bAllFinished = false;
		$(document).ready(function () {
			<?php 
			if(!empty($current_active_test))
			{
			?>
			OnTestChoiceChange();
			refreshTestStatus();
			CanvasJS.addColorSet("customColors",
					[//colorSet Array

					 "#958c12",
					 "#953579" ,
					 "#4bb2c5",                
		            ]);
			var chart = new CanvasJS.Chart("chartContainer",
			    {
					  colorSet: "customColors",
				      title:{
				        text: "Current Test Status",
				        fontColor: "#317eac"
				      },
				      legend: {
					       fontSize: 15
					  },
				      data: [
				      {
				       type: "doughnut",
				       showInLegend: true,
				       dataPoints: [
				       {  y: <?php echo($candStatusAry['finished']);?>, legendText: "Finished", indexLabel: "Finished" , exploded: true},
				       {  y: <?php echo($candStatusAry['unfinished']);?>, legendText: "Unfinished" , indexLabel: "Unfinished" , exploded: true},
				       {  y: <?php echo($notStarted);?>, legendText: "Not Started Yet" , indexLabel: "Not Started Yet" , exploded: true},
				       ]
				     }
				     ]
				});

			<?php 
			if($candStatusAry['finished'] != 0 || $candStatusAry['unfinished'] != 0 || $notStarted != 0)
			{
			?>
			chart.render();
			<?php 
			}
			?>

			setInterval(function(){	
				if(!bAllFinished)
				{
					refreshTestStatus();
				}
			}, 30000);

			var refreshCount = 30;
			setInterval(function(){
				if(!bAllFinished)
				{
					if(refreshCount == 0 || refreshCount == 30)
					{
						refreshCount = 30;
						$("#refreshing_status").html("Refreshing status in <span id='stats_timer'></span>");
						//$("#stats_timer").show();
					}
					else if(refreshCount == 1)
					{
						//$("#stats_timer").hide();
						$("#refreshing_status").html("Refreshing status now...");
					}
					
					$("#stats_timer").html(refreshCount+" seconds.");
					refreshCount--;
				}
				else
				{
					$("#refreshing_status").html("All candidates finished the test.");
				}
			}, 1000);

			
			<?php 
			}
			
			if(isset($_GET['test_uploaded']) && $_GET['test_uploaded'] == 1)
			{
			?>

			
    		var not = $.Notify({
    			caption: "Test Uploaded",
    			content: "<b><?php echo(urldecode($_GET['test_name']));?></b> has been uploaded successfully!",
    			style: {background: 'green', color: '#fff'}, 
    			timeout: 5000
    			});
    		
    		<?php 
			}
			else if(isset($_GET['test_started']) && $_GET['test_started'] == 1)
			{
    		?>
    		var not = $.Notify({
    			caption: "Candidates Logins Started",
    			content: "Candidates logins has been started successfully for <b><?php echo(urldecode($_GET['test_name']));?></b>!",
    			style: {background: 'green', color: '#fff'}, 
    			timeout: 5000
    			});
    		<?php 
			}
			else if(isset($_GET['test_started']) && $_GET['test_started'] == 0)
			{
    		?>
    		var not = $.Notify({
    			caption: "Test On Hold",
    			content: "Candidates logins has been put on hold successfully for <b><?php echo(urldecode($_GET['test_name']));?></b>!",
    			style: {background: 'green', color: '#fff'}, 
    			timeout: 5000
    			});
    		<?php 
			}
    		?>
    		
			$('#upload_start_form').validate({
				errorPlacement: function(error, element) {
					$('#error').html(error);
				},rules: {
					'zip':			{required: true, accept: "zip"},
				}, messages: {
					'zip':			{required: "<span style='color:red;font-weight:bold;'>Please select a &lsquo;zip&rsquo; file to upload!</span>", accept: "<span style='color:red;font-weight:bold;'>Please select a valid &lsquo;zip&rsquo; file to upload!</span>"}
				}
			});
			$(".mip-help").tooltip();
		});

		<?php 
		if(!empty($current_active_test))
		{
		?>
		function refreshTestStatus()
		{
			$(".modal1").show();
			$.ajax({
				url: "ajax/ajax_get_test_status.php",
				async: false,
				dataType: 'json',
				success: function(data){
					var finished = 0;
					var unfinished = 0;
					var not_started = 0;
					$.each(data, function(key, value){
						if(key == "finished")
						{
							finished = value;
						}
						else if(key == "unfinished")
						{
							unfinished = value;
						}
						else if(key == "not_started")
						{
							not_started = value;
						}
					});

					if(finished != 0 && unfinished == 0 && not_started == 0)
					{
						bAllFinished = true;
					}
					else
					{
						bAllFinished = false;
					}
					$(".modal1").hide();
					$("#chartContainer").empty();
					CanvasJS.addColorSet("customColors",
							[//colorSet Array

							 "#958c12",
							 "#953579" ,
							 "#4bb2c5",                
				            ]);
					var chart = new CanvasJS.Chart("chartContainer",
					    {
							  colorSet: "customColors",
						      title:{
						        text: "Current Test Status",
						        fontColor: "#317eac"
						      },
						      legend: {
							       fontSize: 15
							  },
						      data: [
						      {
						       type: "doughnut",
						       showInLegend: true,
						       dataPoints: [
						       {  y: finished, legendText: "Finished", indexLabel: "Finished" , exploded: true},
						       {  y: unfinished, legendText: "Unfinished" , indexLabel: "Unfinished" , exploded: true},
						       {  y: not_started, legendText: "Not Started Yet" , indexLabel: "Not Started Yet" , exploded: true},
						       ]
						     }
						     ]
						});

					if(finished != 0 || unfinished != 0 || not_started != 0)
					{
						chart.render();	
					}
				}
				
			});
		}
		<?php 
		}
		?>
	</script>
</body>
</html>
