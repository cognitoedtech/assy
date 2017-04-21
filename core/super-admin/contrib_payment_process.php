<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
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
	$page_id = CSiteConfig::UAP_PROCESS_CONTRIBUTOR_PAYMENT;
?>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="Generator" content="EditPlus">
<meta name="Author" content="">
<meta name="Keywords" content="">
<meta name="Description" content="">
<title>Process Contributor Payment</title>
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
<?php 
if(false)
{

?>
<html>
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
			body{ font: 75% "Trebuchet MS", sans-serif; margin: 5px; overflow:hidden;}
		</style>
	</head>
	<body>
		<div id="page_loading_box" style="position:absolute;top:100px;left:50%;zindex:200;">
			<img src="../../images/page_loading.gif" width="32" height="32"/>
		</div>
		<div class="notification sticky hide">
	        	<p>You have successfully processed one encash request</p>
	        	<a class="close" href="javascript:">
	            	<img src="../../images/icon-close.png" /></a>
	    </div>
		<div id="page_title" style="display:none">
			<ul>
				<li><a href="#tab1">Contributor Payment Process</a></li>
			</ul>
			<div id="tab1" style="font: 90% 'Trebuchet MS', sans-serif;">
				<table class="table table-bordered" id="payment_table">
					<tr class="success" id="payment_cheque_dd">
						<td style="padding-left:100px;" colspan="3">
							<form id="contrib_payment_form" action="post_get/form_contrib_payment_exec.php" name="contrib_payment_form" method="post" onSubmit="return GetUserInfo();">
								<div class="input-prepend input-append">
									<span style="font-weight:bold;" class="add-on">Contributor Information</span>
									<select id="xaction_info" name="xaction_info">
										<option value='0'>--Select User--</option>
										<?php
											$objBilling->PopulatePendingPaymentContributors();
										?>
									</select>
								</div><br />
								<div id="payment_info" style="display:none">
								</div>
								<input id="process" class="btn btn-success" style="font-weight:bold;" type="button" onClick="ConfirmPayment();" value="Process >>" disabled/><br/><br/>
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
							</form>
						</td>
					</tr>
				</table>
			</div>
		</div>
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
				var transaction_id = $('#xaction_info').val();
				$('#payment_info').hide();
				$("#process").attr("disabled", "disabled");
				if(transaction_id != 0)
				{
					$('body').on({
						ajaxStart: function() { 
				    		$(this).addClass("loading");
						},
						ajaxStop: function() { 
				    		$(this).removeClass("loading");
						}    
					});	
					$('#payment_info').load('ajax/ajax_get_contrib_payment.php?transaction_id='+transaction_id, function(data){
						//alert(data);
						$('#payment_info').show();
					});
				}
			});

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
				
				if($.trim($("input[name=cheque_num]").val()))
				{
					$("#cheque_num_right").show();
					$("#cheque_num_wrong").hide();
					$("input[name=cheque_num]").css("border", "1px solid green");
				}
				else
				{
					$("#cheque_num_wrong").show();
					$("#cheque_num_right").hide();
					$("input[name=cheque_num]").css("border", "1px solid red");
					
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
				if($.trim($("input[name=drawn_bank]").val()))
				{
					$("#drawn_bank_right").show();
					$("#drawn_bank_wrong").hide();
					$("input[name=drawn_bank]").css("border", "1px solid green");
				}
				else
				{
					$("#drawn_bank_wrong").show();
					$("#drawn_bank_right").hide();
					$("input[name=drawn_bank]").css("border", "1px solid red");
					
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
</html>
<?php 
}
?>