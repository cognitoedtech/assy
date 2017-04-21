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
	$page_id = CSiteConfig:: UAP_FREE_EVALUATION_RECHARGE;
?>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="Generator" content="EditPlus">
<meta name="Author" content="">
<meta name="Keywords" content="">
<meta name="Description" content="">
<title>Free_Evalution_Recharge </title>
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
	        	<p>Free recharge has been done successfully.</p>
	        	<a class="close" href="javascript:">
	            	<img src="../../images/icon-close.png" /></a>
	    </div>
		<div id="page_title" style="display:none">
			<ul>
				<li><a href="#tab1">Free Recharge</a></li>
			</ul>
			<div id="tab1" style="font: 90% 'Trebuchet MS', sans-serif;">
				<form id="free_recharge_form" action="post_get/form_free_recharge_exec.php" name="free_recharge_form" method="post" onSubmit="return GetUserInfo();">
					<table class="table table-bordered">
						<tr class="warning">
							<td>
								<label class="radio inline">
									<input type="radio" name="recharge_choice" onchange="OnRechargeOptChange();" value="<?php echo(CConfig::UT_INSTITUTE); ?>" checked>Institute
								</label>
							</td>
							<td>
								<label class="radio inline">
									<input type="radio" name="recharge_choice" onchange="OnRechargeOptChange();" value="<?php echo(CConfig::UT_CORPORATE); ?>">Corporate
								</label>
							</td>
						</tr>				
						<tr class="success" id="payment_cheque_dd">
							<td colspan="2">
								<div id="institute_id" style="display:none; padding-left:100px;">
									<div class="input-prepend input-append">
										<span style="font-weight:bold;" class="add-on">Institute Information: </span>
										<select id="inst_info" name="inst_info">
											<option value='0'>--Select User--</option>
											<?php
												$objBilling->PopulateUsersForFreeRecharge(CConfig::UT_INSTITUTE);
											?>
										</select>
										<span class="add-on"><i class="icon-user"></i></span>
										<span class="add-on"><img id="inst_info_right" style="display:none" class="icon-ok"></i><img id="inst_info_wrong" style="display:none" class="icon-remove"></i></span>
									</div><br />
									<div class="input-prepend input-append" id="inst_recharge" style="display:none">
										<span style="font-weight:bold;" class="add-on">Free Recharge Amount: <img src="../../images/dollar.png" id="inst_dollar" style="position:relative;bottom:2px;display:none"/><img src="../../images/rupees.png" id="inst_rupee" style="position:relative;bottom:2px;display:none"/></span>
										<input class="input-large" id="recharge_inst_amount" name="recharge_inst_amount" type="text" />
										<span class="add-on"><i class="icon-gift"></i></span>
										<span class="add-on"><img id="recharge_inst_amount_right" style="display:none" class="icon-ok"></i><img id="recharge_inst_amount_wrong" style="display:none" class="icon-remove"></i></span>
									</div>
								</div>
								<div id="coporate_id" style="display:none; padding-left:100px;">
									<div class="input-prepend input-append">
										<span style="font-weight:bold;" class="add-on">Corporate Information: </span>
										<select id="corp_info" name="corp_info">
											<option value='0'>--Select User--</option>
											<?php
												$objBilling->PopulateUsersForFreeRecharge(CConfig::UT_CORPORATE);
											?>
										</select>
										<span class="add-on"><i class="icon-user"></i></span>
										<span class="add-on"><img id="corp_info_right" style="display:none" class="icon-ok"></i><img id="corp_info_wrong" style="display:none" class="icon-remove"></i></span>
									</div><br />
									<div class="input-prepend input-append" id="corp_recharge" style="display:none">
										<span style="font-weight:bold;" class="add-on">Free Recharge Amount: <img src="../../images/dollar.png" id="corp_dollar" style="position:relative;bottom:2px;display:none"/><img src="../../images/rupees.png" id="corp_rupee" style="position:relative;bottom:2px;display:none"/></span>
										<input class="input-large" id="recharge_corp_amount" name="recharge_corp_amount" type="text" />
										<span class="add-on"><i class="icon-gift"></i></span>
										<span class="add-on"><img id="recharge_corp_amount_right" style="display:none" class="icon-ok"></i><img id="recharge_corp_amount_wrong" style="display:none" class="icon-remove"></i></span>
									</div>
								</div><br />
								<input id="process" class="btn btn-success" style="font-weight:bold; margin-left:100px" type="button" onClick="ConfirmPayment();" value="Process >>" /><br/><br/>
								<div id="payment_modal" class="modal hide fade in" style="display: none;">
									<div class="modal-header">
										<a class="close" data-dismiss="modal">x</a>
										<h3>Recharge Confirmation</h3>
									</div>
									<div class="modal-body">
										<p>Do you really want to recharge the account?</p>
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
				var val = $("input[name=recharge_choice]:checked").val();
				
				if(val == 1)
				{
					element = document.getElementById($('#inst_info').val()+"");
					$("#user_info").val(element.innerHTML);
				}
				else
				{
					element = document.getElementById($('#corp_info').val()+"");
					$("#user_info").val(element.innerHTML);
				}
				return true;
			}

			function ValidateInputChq()
			{
				var bRet = true;
				var val = $("input[name=recharge_choice]:checked").val();
				
				if(val == 1)
				{
					if($.trim($("input[name=recharge_inst_amount]").val()))
					{
						$("#recharge_inst_amount_right").show();
						$("#recharge_inst_amount_wrong").hide();
						$("input[name=recharge_inst_amount]").css("border", "1px solid green");
					}
					else
					{
						$("#recharge_inst_amount_wrong").show();
						$("#recharge_inst_amount_right").hide();
						$("input[name=recharge_inst_amount]").css("border", "1px solid red");
						
						bRet = bRet && false;
					}

					if($('#inst_info').val() != 0)
					{
						$("#inst_info_right").show();
						$("#inst_info_wrong").hide();
						$("#inst_info").css("border", "1px solid green");
					}
					else
					{
						$("#inst_info_wrong").show();
						$("#inst_info_right").hide();
						$("#inst_info").css("border", "1px solid red");
						
						bRet = bRet && false;
					}
				}
				
				else
				{
					if($.trim($("input[name=recharge_corp_amount]").val()))
					{
						$("#recharge_corp_amount_right").show();
						$("#recharge_corp_amount_wrong").hide();
						$("input[name=recharge_corp_amount]").css("border", "1px solid green");
					}
					else
					{
						$("#recharge_corp_amount_wrong").show();
						$("#recharge_corp_amount_right").hide();
						$("input[name=recharge_corp_amount]").css("border", "1px solid red");
						
						bRet = bRet && false;
					}

					if($('#corp_info').val() != 0)
					{
						$("#corp_info_right").show();
						$("#corp_info_wrong").hide();
						$('#corp_info').css("border", "1px solid green");
					}
					else
					{
						$("#corp_info_wrong").show();
						$("#corp_info_right").hide();
						$('#corp_info').css("border", "1px solid red");
						
						bRet = bRet && false;
					}
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

			$('#inst_info').change(function() {
				var info = $('#inst_info').val();

				if(info != 0)
				{
					$("#inst_recharge").show();
					if(info.substring(0,3) == "USD")
					{
						$("#inst_rupee").hide();
						$("#inst_dollar").show();
						$("#recharge_inst_amount").val(25.00);
					}
					else
					{
						$("#inst_dollar").hide();
						$("#inst_rupee").show();
						$("#recharge_inst_amount").val(1000.00);
					}
				}
				else
				{
					$("#inst_recharge").hide();
				}
			});

			$('#corp_info').change(function() {
				var info = $('#corp_info').val();
				
				if(info != 0)
				{
					$("#corp_recharge").show();
					if(info.substring(0,3) == "USD")
					{
						$("#corp_rupee").hide();
						$("#corp_dollar").show();
						$("#recharge_corp_amount").val(45.00);
					}
					else
					{
						$("#corp_dollar").hide();
						$("#corp_rupee").show();
						$("#recharge_corp_amount").val(2000.00);
					}
				}
				else
				{
					$("#corp_recharge").hide();
				}
			});

			function OnRechargeOptChange()
			{
				var val = $("input[name=recharge_choice]:checked").val();
				if(val == 1)
				{
					$("#coporate_id").hide();
					$("#institute_id").show();
				}
				else if(val == 2)
				{
					$("#institute_id").hide();
					$("#coporate_id").show();
				}
			}

			$(document).ready(function () {

				if(save_success == 1)
				{
					$('.notification.sticky').notify({ type: 'sticky' });
				}

				OnRechargeOptChange();
			});
		</script>
		<script type="text/javascript" charset="utf-8" src="../../3rd_party/bootstrap/js/bootstrap-modal.js"></script>
	</body>
</html>
<?php 
}
?>