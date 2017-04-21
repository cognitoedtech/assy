<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	include_once(dirname(__FILE__)."/../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/../../lib/billing.php");
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
	$qry = explode("=", $parsAry["query"]);
	
	$jsCode = sprintf( "var dateToday='%s';", date( 'D, d M Y 00:00:00')." GMT" );
	
	$sPkgName = "";
	if($qry[0] == "pkg_name" && !empty($qry[1]))
	{
		printf("<script>save_success = 1; %s</script>", $jsCode);
		$sPkgName = urldecode($qry[1]);
	}
	else 
	{
		printf("<script>save_success = 0; %s</script>", $jsCode);
	}
	
	$objBilling = new CBilling();
	$currency = $objBilling->GetCurrencyType($user_id);
	$projected_balance = $objBilling->GetProjectedBalance($user_id);
	
	$rate_15 = null ;
	$rate_30 = null ;
	$rate_45 = null ;
	$rate_60 = null ;
	$rate_90 = null ;
	$currencyPrefix = "";
	if($currency == "INR")
	{
		$currencyPrefix = "Rs.";
		$rate_15 = CConfig::INR_RATE_15_DAYS ;
		$rate_30 = CConfig::INR_RATE_30_DAYS ;
		$rate_45 = CConfig::INR_RATE_45_DAYS ;
		$rate_60 = CConfig::INR_RATE_60_DAYS ;
		$rate_90 = CConfig::INR_RATE_90_DAYS ;
	}
	else 
	{
		$currencyPrefix = "$";
		$rate_15 = CConfig::USD_RATE_15_DAYS ;
		$rate_30 = CConfig::USD_RATE_30_DAYS ;
		$rate_45 = CConfig::USD_RATE_45_DAYS ;
		$rate_60 = CConfig::USD_RATE_60_DAYS ;
		$rate_90 = CConfig::USD_RATE_90_DAYS ;
	}
	
	$objIncludeJsCSS = new IncludeJSCSS();
	
	$menu_id = CSiteConfig::UAMM_TRADE_TEST_PACKGES;
	$page_id = CSiteConfig::UAP_TRADE_TEST_PACKGE;
?>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="Generator" content="Mastishka Intellisys Private Limited">
<meta name="Author" content="Mastishka Intellisys Private Limited">
<meta name="Keywords" content="">
<meta name="Description" content="">
<title>Trade Test Packges</title>
<?php 
$objIncludeJsCSS->CommonIncludeCSS("../../");
$objIncludeJsCSS->IncludeIconFontCSS("../../");
$objIncludeJsCSS->CommonIncludeJS("../../");
$objIncludeJsCSS->IncludeMetroNotificationJS("../../");
$objIncludeJsCSS->IncludeJqueryValidateMinJS("../../");
$objIncludeJsCSS->IncludeMetroCalenderJS("../../");
$objIncludeJsCSS->IncludeMetroDatepickerJS("../../");
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
		top:        0;
		left:       0;
		height:     100%;
		width:      100%;
		background: rgba( 255, 255, 255, .8 ) 
		            url('../../images/page_loading.gif') 
		            50% 200px 
		            no-repeat;
	}	
	body.loading {
	    overflow: hidden;   
	}
	body.loading .modal1 {
	    display: block;
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
		<div class="col-lg-3">
			<?php 
			include_once(dirname(__FILE__)."/../../lib/sidebar.php");
			?>
		</div>
		<div class="col-lg-9" style="border-left: 1px solid #ddd; border-top: 1px solid #ddd;">
			<div class="modal1"></div>
			<br />
			<div class="row fluid">
				<div class="col-lg-12">
					<form method="POST" action="post_get/form_edit_scheduled_test.php" id="form_schedule" onsubmit="return OnSubmit();">
						<div class="row">
							<div class="col-lg-6" style="border-right:1px solid #ddd;">
								<div class="row">
									<div class="col-lg-7">
										<label for="pkg_name"><b>Test Package Name:</b></label>
										<input class="form-control input-sm" type="text" id="pkg_name" name="pkg_name" onkeyup="OnTestPkgNameChange(this);"/>
										<br />
									</div>
									<div class="col-lg-4">
										<span id="tp_checking" style="display:none; position:relative; top: 25px;">&nbsp;<img src="../../images/updating.gif" width="12" height="12"/> Checking</span><span id="tp_exist" style="color:red;display:none;">&nbsp;Name already exists!</span>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-9">
										<label for="candidate_id"><b>Select Candidate:</b></label><br/>
										<select class="form-control input-sm" id="candidate_id" name="candidate_id">
											<?php
												$objDB->PrepareUserCombo($user_id);
											?>
										</select><br />
						    		</div>
								</div>
								<div class="row">
									<div class="col-lg-8">
										<label for="datepicker1_val"><b>Provisioned From(Date):</b></label>
										<div class="metro">
											<div class="input-control text" id="datepicker1">
								    			<input id="datepicker1_val" type="text">
								    			<button class="btn-date" onclick="return false;"></button>
								    		</div>
							    		</div>
							    		<input type="hidden" id="provisioned_from" value='' name="provisioned_from"/>
										<input type="hidden" id="time_zone" name="time_zone"/><br />
						    		</div>
								</div>
								<div class="row">
									<div class="col-lg-8">
										<label for="expire"><b>Expire In (Days):</b></label>
										<select class="form-control input-sm" id="expire" name="expire">
											<option value="15">15 Days (<?php echo($currencyPrefix." ".$rate_15);?>/-)</option>
											<option value="30">30 Days (<?php echo($currencyPrefix." ".$rate_30);?>/-)</option>
											<option value="45">45 Days (<?php echo($currencyPrefix." ".$rate_45);?>/-)</option>
											<option value="60">60 Days (<?php echo($currencyPrefix." ".$rate_60);?>/-)</option>
											<option value="90">90 Days (<?php echo($currencyPrefix." ".$rate_90);?>/-)</option>
										</select><br />
						    		</div>
								</div>
								<div class="row">
									<div class="col-lg-6">
										<label for="amnt_sold"><b>Amount Sold (Tax Inclusive):</b></label>
										<input class="form-control input-sm" type="text" id="amnt_sold" name="amnt_sold" disabled/>
						    		</div>
						    		<div class="col-lg-4">
						    			<div class="checkbox" style="position: relative; top: 20px; padding-left: 0px;">
						    				<label style="color:blue;"><input type="checkbox" name="invoice" onclick="OnEmailInvoice(this);"/>Email Invoice</label>
						    			</div><br /><br />
						    		</div>
								</div>
							</div>
							<div class="col-lg-6">
								<br /><br />
								<div class="col-lg-10 col-lg-offset-2">
									<div class="reg-error">
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-5">
								<div class="row-fluid">
									<span style="font-size: 12px;">
										<b>Registered Test List</b> (
										<?php
											$res = $objDB->GetPersonalTestCount($user_id);
											printf("Total available tests with personal questions %s", $res['total']); 
										?> ):</b>
									</span>
								</div>
								<div class="row-fluid">
									<select class="form-control" style="height:250px" onchange="OnSelectTest();" id="choose_test" multiple="multiple">
									<?php
										$objDB->PrepareTestList($user_id);
									?>
								</select>
								</div>
								<div class="row-fluid" style="text-align: center;">
									<h5>^ Choose From ^</h5>
								</div>
							</div>
							<div class="col-lg-2" style="height:270px;border:1px solid #ddd;">
								<br /><br /><br />
								<div class="row">
									<div class="col-lg-10 col-lg-offset-2">
										<input type="button" onclick="OnTestDetails();" class="btn btn-sm btn-primary" value="Test Details" id="test_dtl_btn" disabled="true"/>
									</div>
								</div><br />
								<div class="row">
									<div class="col-lg-10 col-lg-offset-3">
										<input type="button" class="btn btn-sm btn-success" onclick="OnAdd();" value="Add &gt;&gt;"/>
									</div>
								</div><br />
								<div class="row">
									<div class="col-lg-10 col-lg-offset-2">
										<input type="button" class="btn btn-sm btn-info" onclick="OnRemove();" value="&lt;&lt; Remove"/>
									</div>
								</div><br />
							</div>
							<div class="col-lg-5">
								<br />
								<div class="row-fluid">
									<select class="form-control" style="height:250px" id="selected_test" multiple="multiple">	
									</select>
									<input type="hidden" id="test_list" name="test_list" value=""/>
								</div>
								<div class="row-fluid" style="text-align: center;">
									<h5>^ Selected Tests ^</h5>
								</div>
							</div>
						</div>
						<div class="row-fliud">
							<div class="col-lg-7 col-lg-offset-5">
								<input type="button" class="btn btn-success" onclick="window.location=window.location;" value="Refresh"/>
								<input id="change" class="btn btn-primary" type="submit" value="Provision!"/>
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
			
			<div class="modal" id="mip_message_box">
			  	<div class="modal-dialog">
			    	<div class="modal-content">
			      		<div class="modal-header">
			       		 	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			        		<h4 class="modal-title">Not Enough Ballance !</h4>
			      		</div>
				      	<div class="modal-body" id="delete_test_modal_body">
				      		<p style="color:blue;text-align:center;font: 125% 'Trebuchet MS', sans-serif;">
							<span style="color:DarkRed;font-weight:bold;">You don't have enough ballance (Projected Balance: <?php echo($projected_balance." ".$currency); ?>), please recharge your account.</span><br/><br/>
							Goto <b>My Account &gt; Account Recharge</b> for payment instruction and recharge.<br/>
							<img src="../../images/account_recharge.png" width="567" height="270"/>
							</p>
				      	</div>
			      		<div class="modal-footer">
				        	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			      		</div>
			    	</div>
			  	</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$("#datepicker1").datepicker({
			format: "dd mmmm, yyyy"
		});

		function OnAdd()
		{
			$("#test_dtl_btn").attr("disabled",true);
			
			var test_list_val = $("#choose_test").val();
			
			for (index in test_list_val)
			{
				$("#selected_test").append("<option style='color:darkblue;' value='"+test_list_val[index]+"'>"+$("#choose_test option[value="+test_list_val[index]+"]").text()+"</option>");
				$("#choose_test option[value="+test_list_val[index]+"]").remove();
			}
			
		}
		
		function OnRemove()
		{
			var test_list_val = $("#selected_test").val();
			//var cand_list_text = $("#choose_test").text();
			
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
			$("#selected_test option").each(function(i){
				//alert($(this).val());
		        sTestList += $(this).val() + ";";
		    });
		    
		    $("#test_list").val(sTestList);
			$("#provisioned_from").val($( "#datepicker1_val" ).val());
			
			var current_date = new Date();
			$("#time_zone").val(-current_date.getTimezoneOffset()/60);
			
			var expire = $("#expire option:selected").val();
			expire /= 15;
			expire -= 1;
			
			var rateAry = new Array(<?php echo($rate_15.",".$rate_30.",".$rate_45.",".$rate_60.",0,".$rate_90)?>);
			var cost = rateAry[expire];
			if(<?php echo($projected_balance);?> < cost)
			{
				bRet = false;
				$("#mip_message_box").modal("show");
			}
			
			return bRet;
		}
		
		var bTestPkgExist = false;
		function OnTestPkgNameChange(obj)
		{
			$("#tp_exist").hide();
			$("#tp_checking").show();
			
			$.getJSON("ajax/ajax_check_test_pkg_name.php?pkg_name="+obj.value, function(data) {
				$("#tp_checking").hide();
				
				if(data['present'] == 1)
				{
					$("#tp_exist").show();
					bTestPkgExist = true;
				}
				else
				{
					$("#tp_exist").hide();
					bTestPkgExist = false;
				}
			});
		}
		
		$(document).ready(function () {
			if(save_success == 1)
			{
				//$('.notification.sticky').notify();
            	$('.notification.sticky').notify({ type: 'sticky' });
			}
			
			jQuery.validator.addMethod("TestPkgNameExists", function(value, element) {
				return (!bTestPkgExist);
			}, "Test Package Name Already Exists !");
			
			jQuery.validator.addMethod("rPastDate", function(value, element) {
				var d1 = new Date(value);
				utcLocal = d1.getTime() - (d1.getTimezoneOffset() * 60000);
				
				var d2 = new Date(dateToday);
				
				//alert(new Date(utcLocal).toUTCString() + " <-> " + dateToday);
				
				return new Date(utcLocal) >= new Date(dateToday);
			}, "<p style='color:red;'>* Test provision date is past date, please select today's date <b>("+new Date(dateToday).toUTCString()+")</b> or future date!</p>");
			
			$('#form_schedule').validate({
				errorPlacement: function(error, element) {
					$('#form_schedule div.reg-error').append(error);
				}, rules: {
					'pkg_name':			{required: true, 'TestPkgNameExists': true},
					//'candidate_id':		{required: true},
					'provisioned_from':	{required: true, "rPastDate": true},
					'test_list':		{required: true},
					'amnt_sold':		{required: true, number: true},
				}, messages: {
					'pkg_name':			{required: "<p style='color:red;'>* Please provide valid test package name!!</p>"},
					//'candidate_id':		{required: "<p style='color:red;'>Please select a test!</p>"},
					'provisioned_from':	{required: "<p style='color:red;'>* Please select a date on which test to be scheduled!</p>"},
					'test_list':		{required: "<p style='color:red;'>* Please select atleast one test from available tests!</p>" },
					'amnt_sold':		{required: "<p style='color:red;'>* Please enter the final selling rate!</p>", number: "<p style='color:red;'>Please enter number only!</p>"},
				}
			});
			
			$("#form_schedule").data("validator").settings.ignore = "";
		});

		function OnSelectTest()
		{
			candidate_id = $("#choose_test").val();
			
			if(candidate_id.length == 1)
			{
				$("#test_dtl_btn").removeAttr("disabled");
			}
			else
			{
				$("#test_dtl_btn").attr("disabled",true);
			}
		}
		
		function OnTestDetails()
		{
			var test_id = $("#choose_test").val();
			
			if(candidate_id.length == 1)
			{
				$("#test_dtl_btn").removeAttr("disabled");

				$('body').on({
				    ajaxStart: function() { 
				    	$(this).addClass("loading"); 
				    },
				    ajaxStop: function() { 
				    	$(this).removeClass("loading"); 
				    }    
				});
				
				$("#test_details_modal_body").load("../ajax/ajax_test_details.php?test_id="+test_id, function(){
					$("#test_details_modal").modal("show");
				});
			}
			else
			{
				alert("Please first select a test from left side list!")
			}
		}
		
		function OnEmailInvoice(obj)
		{
			if($(obj).is(':checked'))
			{
				$("#amnt_sold").removeAttr("disabled");
			}
			else
			{
				$("#amnt_sold").attr("disabled",true);
			}
		}
	</script>
</body>
</html>