<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	include_once(dirname(__FILE__)."/../../lib/include_js_css.php");
	include_once(dirname(__FILE__)."/../../lib/site_config.php");
	include_once(dirname(__FILE__)."/../../lib/session_manager.php");
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
	
	$menu_id = CSiteConfig::UAMM_SUPER_ADMIN;
	$page_id = CSiteConfig::UAP_BA_PAYMENT_PROCESS;
	
	$objIncludeJsCSS = new IncludeJSCSS();
?>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="Generator" content="EditPlus">
<meta name="Author" content="">
<meta name="Keywords" content="">
<meta name="Description" content="">
<title>Process BA Payment</title>
<?php 
$objIncludeJsCSS->CommonIncludeCSS("../../");
$objIncludeJsCSS->CommonIncludeJS("../../");
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
	<div class='container' style='width: 100%'>
		<div class='row-fluid'>
			<div class="col-lg-3">
				<?php 
				include_once(dirname(__FILE__)."/../../lib/sidebar.php");
				?>
			</div>
		</div>
	</div>
</body>
</html>
<!--  <html>
	<head>
		<title> Super Admin </title>
		<style type="text/css" title="currentStyle">
			@import "../../css/redmond/jquery-ui-1.9.0.custom.min.css";
			body.loading {
			    overflow: hidden;   
			}
			body.loading .modal {
			    display: block;
			}
		</style>
		<link rel="stylesheet" type="text/css" href="../../css/notify.css" />
		<link rel="stylesheet" type="text/css" href="../../3rd_party/bootstrap/css/bootstrap.css" />
		<link rel="stylesheet" type="text/css" href="../../css/mipcat.css" />
		<script type="text/javascript" charset="utf-8" src="../media/js/jquery.js"></script>
		<script type="text/javascript" src="../../3rd_party/wizard/js/jquery.validate.min.js"></script>
		<script type="text/javascript" charset="utf-8" src="../../js/jquery-ui-custom.min.js"></script>
		<script type="text/javascript" charset="utf-8" src="../../3rd_party/bootstrap/js/bootstrap.js"></script>
		<script type="text/javascript" charset="utf-8" src="../../js/mipcat/utils.js"></script>
		<script type="text/javascript" src="../../3rd_party/wizard/js/jquery.validate.min.js"></script>
		<script type="text/javascript" src="../../js/notification.js"></script>
		<style type="text/css">
			/*demo page css*/
			body{ font: 75% "Trebuchet MS", sans-serif; margin: 5px; overflow: hidden;}
		</style>
	</head>
	<body>
		<div id="page_loading_box" style="position:absolute;top:100px;left:50%;zindex:200;">
			<img src="../../images/page_loading.gif" width="32" height="32"/>
		</div>
		<div class="notification sticky hide">
	        	<p>You have successfully processed transaction of one business associate</p>
	        	<a class="close" href="javascript:">
	            	<img src="../../images/icon-close.png" /></a>
	    </div>
		<div id="page_title" style="display:none">
			<ul>
				<li><a href="#tab1">B.A. Payment Process</a></li>
			</ul>
			<div id="tab1" style="font: 90% 'Trebuchet MS', sans-serif;">
				<form id="ba_payment_form" action="post_get/form_ba_payment_exec.php" name="ba_payment_form" method="post" onSubmit="return GetUserInfo();">
					<table class="table table-bordered" id="payment_table">
						<tr class="success" id="payment_cheque_dd">
							<td style="padding-left:100px;" colspan="3">
								<div class="input-prepend input-append">
									<span style="font-weight:bold;" class="add-on">BA Information: </span>
									<select id="xaction_info" name="xaction_info">
										<option value='0'>--Select User--</option>
										<?php
											$objBilling->PopulateBAForProcessPayment();
										?>
									</select>
								</div><br />
								<div class="input-prepend input-append">
									<span style="font-weight:bold;" class="add-on">Net Commission: <img src="../../images/rupees.png" style="position:relative;bottom:2px"/></span>
									<input class="input-large" id="net_commission" name="net_commission" type="text" value="0.00" readonly/>
									<span class="add-on"><b>(INR)</b></span>
								</div>
								<div class="input-prepend input-append">
									<span style="font-weight:bold;" class="add-on">Gross Commission: <img src="../../images/rupees.png" style="position:relative;bottom:2px"/></span>
									<input class="input-large" id="gross_commission" name="gross_commission" type="text" value="0.00" readonly/>
									<span class="add-on"><b>(INR)</b></span>
								</div>
								<div class="input-prepend input-append">
									<span style="font-weight:bold;" class="add-on">Service Tax Amount: <img src="../../images/rupees.png" style="position:relative;bottom:2px"/></span>
									<input class="input-large" id="service_tax_amount" name="service_tax_amount" type="text" value="0.00" readonly/>
									<span class="add-on"><b>(INR)</b></span>
								</div>
								<div class="input-prepend input-append">
									<span style="font-weight:bold;" class="add-on">TDS Amount: <img src="../../images/rupees.png" style="position:relative;bottom:2px"/></span>
									<input class="input-large" id="tds_amount" name="tds_amount" type="text" value="0.00" readonly/>
									<span class="add-on"><b>(INR)</b></span>
								</div>
								<div id="payment_info" style="display:none">
								</div>
								<div id="payment_modal" class="modal hide fade in" style="display: none;">
									<div class="modal-header">
										<a class="close" data-dismiss="modal">x</a>
										<h3>Payment Confirmation</h3>
									</div>
									<div class="modal-body">
										<p>Do you really want to process the payment?</p>
									</div>
									<div class="modal-footer">
										<a href="#" class="btn" data-dismiss="modal">No</a>
										<input type="submit" class="btn btn-success" value="Yes Proceed" />
									</div>
								</div>
								<input type="hidden" id="user_info" name="user_info"/><br/><br/>				
							</td>
						</tr>
					</table>
				</form>
			</div>
		</div>
		<?php printf("<script>var service_tax = %f; var tds = %f; var tds_min_bracket =%f;</script>", CConfig::$BA_TAX_APPLIED_ARY["Service Tax"], CConfig::$BA_TAX_APPLIED_ARY["Tax Deduction at Source (TDS)"], CConfig::TDS_MIN_BRACKET); ?><br />
		<script type="text/javascript">
			$(window).load(function(){
				$("#page_loading_box").hide();
				$("#page_title").show();
				$("#page_title").tabs();
				
				var page_hgt = objUtils.AdjustHeight("tab1");
				$('#platform', window.parent.document).height(page_hgt+200);
			});

			function GetUserInfo()
			{
				element = document.getElementById($('#xaction_info').val()+"");
				$("#user_info").val(element.innerHTML);
				return true;
			}

			$('#xaction_info').change(function() {
				var ba_id = $('#xaction_info').val();
				$('#payment_info').hide();
				$("#process").attr("disabled", "disabled");
				if(ba_id != 0)
				{
					$('body').on({
						ajaxStart: function() { 
				    		$(this).addClass("loading");
						},
						ajaxStop: function() { 
				    		$(this).removeClass("loading");
						}    
					});	
					$('#payment_info').load('ajax/ajax_get_ba_payment.php?ba_id='+ba_id, function(data){
						//alert(data);
						$('#payment_info').show();
					});
				}
			});
			
			function OnCommissionSelect(obj)
			{
				var val =$("#"+obj.id).val();
				var gross_commission = parseFloat($("#gross_commission").val());
				element = document.getElementById("amount"+val);
				var addition = parseFloat(element.innerHTML);
				var updated_gross_commission 	= 0.0;
				var service_tax_amount 			= 0.0;
				var updated_net_commission 		= 0.0;
				var tds_amount					= (0.00).toFixed(2);			

				if($("#"+obj.id).is(':checked'))
				{
					updated_gross_commission = (gross_commission + addition).toFixed(2);
				}
				else
				{
					updated_gross_commission = (gross_commission - addition).toFixed(2);
				}
				service_tax_amount		 = ((updated_gross_commission * service_tax)/100).toFixed(2);
				updated_net_commission	 = (updated_gross_commission - service_tax_amount).toFixed(2);
				if(updated_net_commission >= tds_min_bracket)
				{
					tds_amount				= ((updated_net_commission * tds)/100).toFixed(2);
					updated_net_commission 	= (updated_net_commission - tds_amount).toFixed(2);
				}
				$("#gross_commission").val(updated_gross_commission);
				$("#service_tax_amount").val(service_tax_amount);
				$("#tds_amount").val(tds_amount);
				$("#net_commission").val(updated_net_commission);
			}

			function OnTermsClicked()
			{
				if ($("#confirm_payment").is(':checked')) 
				{
				    $("#process").removeAttr("disabled");
				}
				else {
				    $("#process").attr("disabled", "disabled");
				}
			}

			function ValidateInputChq()
			{
				var bRet = true;
				var objDate = new Date();
				
				if($.trim($("input[name=payment_ordinal]").val()))
				{
					$("#payment_ordinal_right").show();
					$("#payment_ordinal_wrong").hide();
					$("input[name=payment_ordinal]").css("border", "1px solid green");
				}
				else
				{
					$("#payment_ordinal_wrong").show();
					$("#payment_ordinal_right").hide();
					$("input[name=payment_ordinal]").css("border", "1px solid red");
					
					bRet = bRet && false;
				}

				if($.trim($("input[name=payment_date]").val()))
				{
					$("#payment_date_right").show();
					$("#payment_date_wrong").hide();
					$("input[name=payment_date]").css("border", "1px solid green");
				}
				else
				{
					$("#payment_date_wrong").show();
					$("#payment_date_right").hide();
					$("input[name=payment_date]").css("border", "1px solid red");
					
					bRet = bRet && false;
				}
				if($.trim($("input[name=payment_agent]").val()))
				{
					$("#payment_agent_right").show();
					$("#payment_agent_wrong").hide();
					$("input[name=payment_agent]").css("border", "1px solid green");
				}
				else
				{
					$("#payment_agent_wrong").show();
					$("#payment_agent_right").hide();
					$("input[name=payment_agent]").css("border", "1px solid red");
					
					bRet = bRet && false;
				}
				return bRet;
			}

			function ConfirmPayment()
			{
				var bVal = ValidateInputChq();
				if(bVal)
				{
					$("#payment_modal").modal('toggle');
				}
			}

			$(document).ready(function () {

				if(save_success == 1)
				{
					$('.notification.sticky').notify({ type: 'sticky' });
				}
			});
		</script>
		<script type="text/javascript" charset="utf-8" src="../../3rd_party/bootstrap/js/bootstrap-modal.js"></script>
	</body>
</html>-->
