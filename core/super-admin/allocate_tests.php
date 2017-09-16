<!DOCTYPE HTML>
<?php
	include_once(dirname(__FILE__)."/../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../lib/include_js_css.php");
	include_once(dirname(__FILE__)."/../../lib/site_config.php");	
	include_once(dirname(__FILE__)."/../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/../../lib/billing.php");

	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -

	$objBilling = new CBilling();

	$processed = 0;
	if(!empty($_GET['processed']))
	{
		$processed = $_GET['processed'];
	}

	printf("<script>save_success='%s'</script>",$processed);
	
	$objIncludeJsCSS = new IncludeJSCSS();
	
	$menu_id = CSiteConfig::UAMM_SUPER_ADMIN;
	$page_id = CSiteConfig:: UAP_ALLOCATE_TESTS;
?>
<html lang="en">

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
	<head>
	<meta charset="UTF-8">
	<meta name="Generator" content="Mastishka Intellisys Private Limited">
	<meta name="Author" content="Mastishka Intellisys Private Limited">
	<meta name="Keywords" content="">
	<meta name="Description" content="">
	<title>Allocate Test </title>
	<?php
	$objIncludeJsCSS->IncludeBootstrap3_1_1Plus1CSS("../../");
	$objIncludeJsCSS->IncludeBootswatch3_1_1Plus1LessCSS("../../");
	$objIncludeJsCSS->IncludeMetroBootstrapCSS("../../");
	$objIncludeJsCSS->CommonIncludeCSS("../../");
	$objIncludeJsCSS->IncludeIconFontCSS("../../");
	$objIncludeJsCSS->IncludeMipcatCSS("../../");
	$objIncludeJsCSS->CommonIncludeJS("../../");
	$objIncludeJsCSS->IncludeMetroNotificationJS("../../");
	$objIncludeJsCSS->IncludeJqueryValidateMinJS("../../");
	?>
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
			<div class="col-lg-3">
				<?php 
					include_once(dirname(__FILE__)."/../../lib/sidebar.php");
				?>
			</div>
			<div class="col-lg-9" style="border-left: 1px solid #ddd; border-top: 1px solid #ddd;">
			<div class="fuelux modal1">
				<div class="preloader"><i></i><i></i><i></i><i></i></div>
			</div>
			<br /><br />
				<form class="form-horizontal" id="test_allocate_form" action="post_get/form_test_allocate.php" name="test_allocate_form" method="post"  enctype="multipart/form-data" onsubmit="return OnSubmit();">
					<div class="form-group">
						<label for="user_info" class="col-lg-3 control-label">User Information :</label>
						<div class="col-lg-6">
							<select class="form-control input-sm" id="user_info" name="user_info">
								<option value=''>--Select User--</option>
								<?php
									$objBilling->PopulateUsersForFreeRecharge(CConfig::UT_CORPORATE);
								?>
							</select>
						</div>
					</div>
					<div id="cand_select_div">
							<div class="row">
								<div class="col-lg-5 col-md-5 col-sm-5">
									<div class="row-fluid">
										<span style="font-size: 12px;"><b>Available Tests</b>
									</div>
									<div class="row-fluid">
										<select style="height:250px" class="form-control" id="choose_test" multiple="multiple">
										</select>
									</div>
									<div class="row-fluid" style="text-align: center;">
										<h5>^ Choose From ^</h5>
									</div>
								</div>
								<div class="col-lg-2 col-md-2 col-sm-2" style="height:270px;border:1px solid #ddd;">
									<br /><br /><br />
									<div class="row">
										<div class="col-lg-10 col-md-10 col-sm-10  col-lg-offset-2 col-md-offset-2 col-sm-offset-2">
											<input type="button" class="btn btn-xs btn-success" onclick="OnAddAll();" value="Add All &gt;&gt;"/>
										</div>
									</div><br />
									<div class="row">
										<div class="col-lg-10 col-md-10 col-sm-10  col-lg-offset-2 col-md-offset-2 col-sm-offset-2">
											<input type="button" class="btn btn-xs btn-success" onclick="OnAdd();" value="Add &gt;&gt;"/>
										</div>
									</div><br />
									<div class="row">
										<div class="col-lg-10 col-md-10 col-sm-10  col-lg-offset-2 col-md-offset-2 col-sm-offset-2">
											<input type="button" class="btn btn-xs btn-info" onclick="OnRemove();" value="&lt;&lt; Remove"/>
										</div>
									</div><br />
									<div class="row">
										<div class="col-lg-10 col-md-10 col-sm-10  col-lg-offset-2 col-md-offset-2 col-sm-offset-2">
											<input type="button" class="btn btn-xs btn-info" onclick="OnRemoveAll();" value="&lt;&lt; Remove All"/>
										</div>
									</div>
								</div>
								<div class="col-lg-5 col-md-5 col-sm-5">
									<br />
									<div class="row-fluid">
										<select style="height:250px" class="form-control" id="selected_test" multiple="multiple">
										</select>
									</div>
									<div class="row-fluid" style="text-align: center;">
										<h5>^ Selected Tests ^</h5>
									</div>
									<input type="hidden" id="test_list" name="test_list" value=""/>									
								</div>
							</div>
							<div class="row-fliud">
								<div class="col-lg-7 col-md-7 col-sm-7  col-lg-offset-5 col-md-offset-5 col-sm-offset-5">
									<input type="button" class="btn btn-success" onclick="window.location=window.location;" value="Refresh"/>
									<input id="change" class="btn btn-primary" type="submit" value="Allocate!"/>
								</div>
							</div>
						</div>
							
				</form>
				<?php 
				include_once(dirname(__FILE__)."/../../lib/footer.php");
				?>
			</div>
		</div>
		<script type="text/javascript">

			$('#test_allocate_form').validate({
				rules: {
					'user_info':		{required: true},					
				}, messages: {
					'user_info':		{required:  "<p style='color:red;'>* Please select a user!</p>"}					
				}
			});

			$(document).ready(function () {
				
				LoadTests();
				if(save_success == 1)
				{
					var not = $.Notify({
	      				 caption: "Test Allocated",
	      				 content: "Tests  has been allocated successfully!",
	      				 style: {background: 'green', color: '#fff'}, 
	      				 timeout: 5000
	      				 });
				}
				
				
			});

			function OnAdd()
			{
				var test_list_val = $("#choose_test").val();
				
				for (index in test_list_val)
				{
					$("#selected_test").append("<option style='color:darkblue;' value='"+test_list_val[index]+"'>"+$("#choose_test option[value="+test_list_val[index]+"]").text()+"</option>");
					$("#choose_test option[value="+test_list_val[index]+"]").remove();
				}
				
			}
			function OnAddAll()
			{	
				if($("#choose_test").html() != "")
				{
				
					$('#choose_test option')
						.clone()
						.appendTo('#selected_test');
					$("#choose_test").empty();
				}
			}
			
			function OnRemoveAll()
			{
				if($("#selected_test").html() != "")
				{
				
					$('#selected_test option')
						.clone()
						.appendTo('#choose_test');
					$("#selected_test").empty();
				}
			}

			function LoadTests()
			{
				$('#selected_test').html('');				
				var test_data = "";
				
				$(".modal1").show();

				$.ajax({
				  url: "../ajax/ajax_get_tests.php",				
				  type: 'POST',
				  dataType: 'json',
				  success: function(data){
						$.each(data, function(key, value)
								{							
							test_data += value;
								}
						);
						$("#choose_test").html(test_data);
						$(".modal1").hide();
					},
					async: false
				});
			}
			
			function OnRemove()
			{
				var test_list_val = $("#selected_test").val();
				
				
				for (index in test_list_val)
				{
					$("#choose_test").append("<option style='color:darkblue;' value='"+test_list_val[index]+"'>"+$("#selected_test option[value="+test_list_val[index]+"]").text()+"</option>");
					$("#selected_test option[value="+test_list_val[index]+"]").remove();
				}
			}
			
			function get_time_zone_offset( ) 
			{
			    var current_date = new Date();
			    return -current_date.getTimezoneOffset() / 60;
			}

			function OnSubmit()
			{
				var bRet = true;
				
				var sTestList = "";
				
				var nCandCount = 0;
				$("#selected_test option").each(function(i){
					sTestList += $(this).val() + ";";			        
			    });

				if(sTestList == '')
				{
					alert("Please select atleast one test");
					return false;
				}
			    
			    $("#test_list").val(sTestList);
               
				
			}

			function OnTestDetails()
			{
				$(".modal1").show();
				
				$("#test_details_modal_body").load("../ajax/ajax_test_details.php?test_id="+$("#test_id").val(), function(){
					$("#test_details_modal").modal("show");
					$(".modal1").hide();
				});
			}

			function printTime(offset) 
			{
				workDate = new Date();
				UTCDate = new Date();
				UTCDate.setTime(workDate.getTime()+workDate.getTimezoneOffset()*60000);
				tempDate = new Date();
				tempDate.setTime(UTCDate.getTime()+3600000*(offset));
				return tempDate;
			}


			
		</script>
	</body>
</html>