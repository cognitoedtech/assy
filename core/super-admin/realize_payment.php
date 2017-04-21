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

	$realized = 0;
	if(!empty($_GET['realized']))
	{
		$realized = $_GET['realized'];
	}

	$voided = 0;
	if(!empty($_GET['voided']))
	{
		$voided = $_GET['voided'];
	}

	printf("<script>realize_success='%s'</script>",$realized);
	printf("<script>void_success='%s'</script>",$voided);
	
	$objIncludeJsCSS = new IncludeJSCSS();
	
	$menu_id = CSiteConfig::UAMM_SUPER_ADMIN;
	$page_id = CSiteConfig::UAP_REALIZE_PAYMENT;
?>
<html lang="en">
	<head>
	<meta charset="UTF-8">
	<meta name="Generator" content="Mastishka Intellisys Private Limited">
	<meta name="Author" content="Mastishka Intellisys Private Limited">
	<meta name="Keywords" content="">
	<meta name="Description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo(CConfig::SNC_SITE_NAME); ?>: Realize Payment</title>
	<?php 
		$objIncludeJsCSS->IncludeBootstrap3_1_1Plus1CSS("../../");
		$objIncludeJsCSS->IncludeBootswatch3_1_1Plus1LessCSS("../../");
		$objIncludeJsCSS->IncludeMetroBootstrapCSS("../../");
		$objIncludeJsCSS->CommonIncludeCSS("../../");
		$objIncludeJsCSS->IncludeIconFontCSS("../../");
		$objIncludeJsCSS->IncludeMipcatCSS("../../");
		$objIncludeJsCSS->IncludeFuelUXCSS ( "../../" );
		$objIncludeJsCSS->IncludeJqueryFormJS("../../");
		$objIncludeJsCSS->CommonIncludeJS("../../");
		$objIncludeJsCSS->IncludeMetroNotificationJS("../../");
	?>
	
	<style type="text/css">
		.modal, .modal.fade.in {
		    top: 15%;
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
		<div class="col-sm-3 col-md-3 col-lg-3">
			<?php 
				include_once(dirname(__FILE__)."/../../lib/sidebar.php");
			?>
		</div>
		<div class="col-sm-9 col-md-9 col-lg-9" style="border-left: 1px solid #ddd; border-top: 1px solid #ddd;">
			<div class="fuelux modal1">
				<div class="preloader"><i></i><i></i><i></i><i></i></div>
			</div>
			<br /><br />
			<form class="form-horizontal" id="payment_realize_form" action="post_get/form_realize_payment_exec.php" name="payment_realize_form" method="post" onSubmit="return GetUserInfo();">
				<div class="form-group">
					<label for="xaction_info" class="col-sm-3 col-md-3 col-lg-3 control-label">User Information :</label>
					<div class="input-group col-sm-5 col-md-5 col-lg-5">
						<select class="form-control input-sm" id="xaction_info" name="xaction_info" >
							<option value='0'>--Select User--</option>
								<?php
									$objBilling->PopulatePendingRealizationUsers();
								?>
						</select>
					</div>
				</div><br />				
				<div id="payment_info" style="display:none">
				</div>
				<div class="form-group">
					<div class="col-sm-2 col-md-2 col-lg-2 col-sm-offset-3 col-md-offset-3 col-lg-offset-3">
						<input id="process" class="btn btn-success" type="button" onClick="ConfirmRealization();" value="Process >>" disabled/><br/><br/>	 
			        </div>
		      	</div>
				
				<div id="realize_modal" style="display: none;" class="modal">
				 	<div class="modal-dialog">
					 	<div  class="modal-content">
							<div class="modal-header">
						    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						        <h3>Realize Confirmation</h3>
						    </div>
						     <div class="modal-body">
						     	<p>Do you really want to realize this transaction?</p>
						     </div>
						     <div class="modal-footer">
						     	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						       	<input type="submit" class="btn btn-success" value="Realize" />
						     </div>
						 </div>
				 	 </div>
				 </div>
				   
				<div id="void_modal" style="display: none;" class="modal">
					<div class="modal-dialog">
				    	<div  class="modal-content">
					      	<div class="modal-header">
					        	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					        	<h3>Void Confirmation</h3>
					      	</div>
					      	<div class="modal-body">
					        	<p>Do you really want to void this transaction?</p>
					      	</div>
					      	<div class="modal-footer">
					        	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					        	<input type="submit" class="btn btn-success" value="Void" />
					      	</div>
					   	</div>
					   	<input type="hidden" id="user_info" name="user_info"/><br/><br/>
				   </div>
				</div>
			</form>
			<?php
			include_once (dirname ( __FILE__ ) . "/../../lib/footer.php");
			?>
		</div>
	</div>
	<script type="text/javascript">
		function GetUserInfo()
		{
			element = document.getElementById($('#xaction_info').val()+"");
			$("#user_info").val(element.innerHTML);
			return true;
		}
		
		$('#xaction_info').change(function() {
			var transaction_id = $('#xaction_info').val();
			$('#payment_info').hide();
			if(transaction_id != 0)
			{
				$(".modal1").show();
				$('#payment_info').load('ajax/ajax_get_realize_payment.php?transaction_id='+transaction_id, function(data){
					//alert(data);
					$('#payment_info').show();
					$(".modal1").hide();
				});
			}
		});
		
		function OnReasonChange()
		{
			var reason = $('#void_reasons').val();
			if(reason == "")
			{
				$('#other_void_reason_id').hide();
				$('#void_realize_check_id').hide();
				$("#void_realize").removeAttr('checked');
				$("#process").attr("disabled", "disabled");
			}
			else if(reason == "other")
			{
				$('#other_void_reason_id').show();
				$('#void_realize_check_id').show();
			}
			else
			{
				$('#other_void_reason_id').hide();
				$('#void_realize_check_id').show();
			}
		}
		
		function OnRealizationChoiceChange()
		{
			var val = $("input[name=realize_choice]:checked").val();
				
			if(val == "yes")
			{
				$("#void_realize_id").hide();
				$("#void_realize").removeAttr('checked');
				$("#process").attr("disabled", "disabled");
				$("#confirm_realize_id").show();
			}
			else
			{
				$("#confirm_realize_id").hide();
				$("#confirm_realize").removeAttr('checked');
				$("#process").attr("disabled", "disabled");
				$("#void_realize_id").show();
			}
		}
		
		function OnTermsClicked()
		{
			if ($("#confirm_realize").is(':checked') || $("#void_realize").is(':checked')) 
			{
			    $("#process").removeAttr("disabled");
			}
			else {
			    $("#process").attr("disabled", "disabled");
			}
		}
	
		function ValidateOtherReasonField()
		{
			var bRet = true;
			if($('#void_reasons').val() == "other")
			{
				if($("input[name=other_void_reason]").val().trim())
				{
					$("#other_void_reason_CR").show();
					$("#other_void_reason_WR").hide();
					$("input[name=other_void_reason]").css("border", "1px solid green");
				}
				else
				{
					$("#other_void_reason_WR").show();
					$("#other_void_reason_CR").hide();
					$("input[name=other_void_reason]").css("border", "1px solid red");
					
					bRet = bRet && false;
				}
			}
			return bRet;
		}
		
		function ConfirmRealization()
		{
			var bVal = ValidateOtherReasonField();
			if(bVal)
			{
				var val = $("input[name=realize_choice]:checked").val();
		
				if(val == "yes")
				{
					$("#realize_modal").modal();
				}
				else
				{
					$("#void_modal").modal();
				}
			}
		}

		$(document).ready(function () {
			OnRealizationChoiceChange();
			if(realize_success == 1 || void_success == 1)
			{
				$.Notify({
					caption: "Payment Realization",
					content: "You have successfully <?php echo(($realized == 1)?"realized":"voided"); ?> one transaction!",
					style: {background: 'green', color: '#fff'}, 
					timeout: 5000 
				});
			}
		});
		
	</script>
	</body>
</html>
