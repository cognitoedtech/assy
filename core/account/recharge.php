<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	include_once(dirname(__FILE__)."/../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/../../lib/user_manager.php");
	include_once(dirname(__FILE__)."/../../lib/billing.php");
	include_once(dirname(__FILE__)."/../../lib/include_js_css.php");
	include_once(dirname(__FILE__)."/../../lib/site_config.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$objDB = new CMcatDB();
	$objUM = new CUserManager() ;
	$objBilling = new CBilling();
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	$user_email = CSessionManager::Get(CSessionManager::STR_EMAIL_ID);
	$user_type = CSessionManager::Get(CSessionManager::INT_USER_TYPE);
	
	$balance 		= $objBilling->GetBalance($user_id);
	$projected_balance = $objBilling->GetProjectedBalance($user_id);
	$currency 		= $objBilling->GetCurrencyType($user_id);
	$sub_plan		= $objBilling->GetSubscriptionPlan($user_id);
	$ba_org_name 	= $objBilling->GetBusinessAssociateName($user_id);
	
	$sMsg =  "" ;
	if($projected_balance <= 0)
	{
		$sMsg =  "Your account has &lsquo;No&rsquo; balance, recharge your account now!" ;
	}
	else if($projected_balance <= 1000)
	{
		$sMsg =  "Your account has &lsquo;Low&rsquo; balance, recharge your account now!" ;
	}
	else 
	{
		$sMsg =  "Welcome to account recharge!" ;
	}
	
	$user_info = $objUM->GetUserById($user_id);
	
	$objIncludeJsCSS = new IncludeJSCSS();
	
	$menu_id = CSiteConfig::UAMM_MY_ACCOUNT;
	$page_id = CSiteConfig::UAP_ACOOUNT_RECHARGE;
?>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="Generator" content="Mastishka Intellisys Private Limited">
		<meta name="Author" content="Mastishka Intellisys Private Limited">
		<meta name="Keywords" content="">
		<meta name="Description" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo(CConfig::SNC_SITE_NAME);?>: Account Recharge</title>
		<?php 
			$objIncludeJsCSS->CommonIncludeCSS("../../");
			$objIncludeJsCSS->IncludeIconFontCSS("../../");
			$objIncludeJsCSS->CommonIncludeJS("../../");
			$objIncludeJsCSS->IncludeMetroCalenderJS("../../");
			$objIncludeJsCSS->IncludeMetroDatepickerJS("../../");
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
			<div class="col-lg-3 col-md-3 col-sm-3">
				<?php 
				include_once(dirname(__FILE__)."/../../lib/sidebar.php");
				?>
			</div>
			<div class=" col-lg-9 col-sm-9 col-md-9" style="border-left: 1px solid #ddd; border-top: 1px solid #ddd;">
				<br />
				<div id="tab_container">
					<ul class="nav nav-tabs" style="margin-bottom: 15px;">
						<li class="active"><a href="#pay_pal_payment" data-toggle="tab">U.S. Dollars (USD) *</a></li>
			 			<li class="dropdown">
			   				<a class="dropdown-toggle" data-toggle="dropdown" href="#">Bank *<span class="caret"></span></a>
			    			<ul class="dropdown-menu">
			      				<li id="pay_chq"><a href="#pay_via_check"  data-toggle="tab">Pay via Cheque</a></li> 
			      				<li id="pay_dd"  ><a href="#pay_via_check"  data-toggle="tab">Pay via Demand Draft (DD)</a></li>
			      				<li id="pay_neft" ><a href="#pay_via_neft"  data-toggle="tab">Pay via NEFT</a></li>
			   				 </ul>
			  			</li>
					</ul>
				</div>
				<div id="myTabContent" class="tab-content">
				  	<div class="tab-pane fade active in" id="pay_pal_payment">
				    	<div class="success" id="payment_paypal">
						 <div style="padding-left:20px; padding-top:20px" >
							<form action="<?php echo(CConfig::PAYPAL_URL);?>" method="post" target="_blank">
								<input type="hidden" name="payment_mode_usd"  id="payment_mode_usd" value="<?php echo(CConfig::PAYMENT_MODE_GATEWAY); ?>">	 
							    <input type="hidden" name="cmd" value="_xclick">
							    <input type="hidden" name="business" value="manish.mastishka@gmail.com">
							    <input type="hidden" name="item_name" value="MIpCAT <?php echo(ucfirst($sub_plan));?> Subscription Plan">
							    <input type="hidden" name="item_number" value="MIP_<?php echo(strtoupper($sub_plan).CConfig::$USD_SUBSCRIPTION_PLANS[$user_type]['MINIMUM_RECHARGE']);?>">
							    <input type="hidden" name="amount" value="<?php echo(CConfig::$USD_SUBSCRIPTION_PLANS[$user_type]['MINIMUM_RECHARGE']);?>">
							    <input type="hidden" name="quantity" value="1">
							    <input type="hidden" name="no_note" value="1">
							    <input type="hidden" name="currency_code" value="USD">
							    <INPUT TYPE="hidden" NAME="return" value="<?php echo(CSiteConfig::ROOT_URL);?>">
							    <INPUT TYPE="hidden" NAME="notify_url" value="<?php echo(CSiteConfig::ROOT_URL."/core/payment_gateway/paypal_callback.php");?>">
							    <!-- Enable override of buyers's address stored with PayPal . -->
							    <!--<input type="hidden" name="address_override" value="1"> -->
							    <!-- Set variables that override the address stored with PayPal. -->
							    <input type="hidden" name="email" value="<?php echo($user_email);?>">
							    <input type="hidden" name="first_name" value="<?php echo($user_info->GetFirstName());?>">
							    <input type="hidden" name="last_name" value="<?php echo($user_info->GetLastName());?>">
							    <input type="hidden" name="address1" value="<?php echo($user_info->GetAddress());?>">
							    <input type="hidden" name="city" value="<?php echo($user_info->GetCity());?>">
							    <input type="hidden" name="state" value="<?php echo($user_info->GetState());?>">
							    <input type="hidden" name="country" value="<?php echo($user_info->GetCountry());?>">
							    <input type="image" name="submit" border="0"
							    src="../../images/btn_paynow_paypal.gif" alt="PayPal - The safer, easier way to pay online">
							</form>
						</div>
						</div>
				 	</div>
					<div class="tab-pane fade" id="pay_via_check">
						<h5>*Only for organizations having their registered office in India</h5>
						<div id="CHECK_PAYMENT_FORM">
							<form  class="form-horizontal" id="form_check_dd_payment" method="post" action="post_get/form_account_recharge_exec.php">
			    				<fieldset>
    								<legend>CHEQUE/DD PAYMENT INFORMATION</legend>			 	
							    	<div class="form-group">
  										<label for="recharge_amount_chq" class="col-sm-4 col-md-4 col-lg-4 control-label">Amount <?php echo($currency=="INR"?"Rs.":"$");?> :</label>
  											<div class="input-group col-sm-4 col-md-4 col-lg-4">
												<input class="form-control" id="recharge_amount_chq" name="recharge_amount_chq" type="text" value="<?php echo(CConfig::$INR_SUBSCRIPTION_PLANS[$user_type]["MINIMUM_RECHARGE"]);?>">
         		 								<span class="input-group-addon"><b>(<?php echo($currency);?>)</b> </span>
    										</div>
  									</div>				
									<div class="form-group">
  										<label  for="payment_ordinal_chq" class="col-sm-4 col-md-4 col-lg-4 control-label">Cheque &frasl; DD Number :</label>
  											<div class="input-group col-sm-4 col-md-4 col-lg-4">
												<input class="form-control" id="payment_ordinal_chq" name="payment_ordinal_chq" type="text">
         		 								<span class="input-group-addon"><i class="icon-list"></i></span>
    										</div>
  									</div>
  									<div class="metro">
										<div class="row">
  											<label for="payment_date_chq" class="col-sm-4 col-md-4 col-lg-4 control-label"> Date on Cheque &frasl; DD :</label>
  												<div class="col-sm-4 col-md-4 col-lg-4">
													<div class="input-control text" id="datepicker1">
			    										<input id="payment_date_chq" name="payment_date_chq" type="text" />
			    										<button class="btn-date"></button>
			    									</div>
		    									</div>
  										</div>
  									</div><br />												
  									<div class="form-group">
  										<label  for="payment_agent_chq" class="col-sm-4 col-md-4 col-lg-4 control-label">Drawn Bank Name :</label>
  											<div class="input-group col-sm-4 col-md-4 col-lg-4">
												<input class="form-control" id="payment_agent_chq" name="payment_agent_chq" type="text">
         		 								<p class="input-group-addon"><i class="icon-pencil"></i></p>
    										</div>
  									</div><br/>
  									<div class="form-group">
										<div class="col-sm-5 col-md-5 col-lg-5">
											<input type="hidden" name="currency" id="currency" value="<?php echo($currency);?>">
											<input type="hidden" name="payment_mode_inr"  id="payment_mode_inr" value="">	 
										</div>
									</div>
									<div class="form-group">
										<div class="col-lg-offset-4 col-sm-offset-4 col-md-offset-4" id="td_personal_edit_btn">
											<input class="btn btn-success" type="submit"  style="font-weight:bold;" value="Process >>" />	
						        		</div>
									</div>
									<div class="form-group">
	    								<div class="col-lg-offset-4 col-sm-offset-4 col-md-offset-4" id="error_div_2">
										</div>
	    							</div>
								</fieldset>
			    			</form>
							<br/><br/>
							<p style="color:blue;">Payment Instructions:</p>
							<ul>
								<li>Payment should always made in favor of <b>&lsquo;Mastishka Intellisys Private Limited&rsquo;</b>, Cheque / Demand Draft should be payable at Indore (M.P.)</li>
								<li>Write down your registered Email-ID (<u style="color:blue;"><?php echo($user_email);?></u>) behind Cheque / Demand Draft.</li>
								<li style="<?php echo($ba_org_name==null?'display:none':'');?>">You can hand-over Cheque / Demand Draft to our Business Associate, who registered you to <u style="color:blue;"><?php echo(CSiteConfig::ROOT_URL);?></u>.</li>
								<li style="<?php echo($ba_org_name==null?'display:none':'');?>">OR</li>
								<li style="color:blue">Post the Cheque / Demand Draft at following (registered) address,<br/>
									<address style="color:#B22400;">
										<strong>Mastishka Intellisys Private Limited</strong><br>
										95, Veena Nagar<br/>
										Opposite Bombay Hospital<br/>
										Ring Road, Indore 452010, India<br/>
										<abbr title="Phone">Ph:</abbr> +91 98266 00457, +91 90395 79039
								</address>
								</li>
							</ul>
						</div>
					</div> 
			  		<!--<div class="tab-pane fade" id="pay_via_dd">
			    		<h5>*Only for organizations having their registered office in India</h5>
			    	</div>-->
			  		<div class="tab-pane fade" id="pay_via_neft">
			    		<h5>*Only for organizations having their registered office in India</h5>
			    		<div id="NEFT_PAYMENT_FORM">
			    			<form  class="form-horizontal" id="form_neft_payment" method="post" action="post_get/form_account_recharge_exec.php">
			    				<fieldset>
    								<legend>NEFT PAYMENT</legend>			 	
							    	<div class="form-group">
  										<label for="recharge_amount_neft" class="col-sm-4 col-md-4 col-lg-4 control-label">Amount <?php echo($currency=="INR"?"Rs.":"$");?> :</label>
  											<div class="input-group col-sm-4 col-md-4 col-lg-4">
												<input class="form-control" id="recharge_amount_neft" name="recharge_amount_neft" type="text" value="<?php echo(CConfig::$INR_SUBSCRIPTION_PLANS[$user_type]["MINIMUM_RECHARGE"]);?>">
         		 								<span class="input-group-addon"><b>(<?php echo($currency);?>)</b> </span>
    										</div>
  									</div>				
									<div class="form-group">
  										<label  for="payment_ordinal_neft" class="col-sm-4 col-md-4 col-lg-4 control-label">NEFT Transaction ID :</label>
  											<div class="input-group col-sm-4 col-md-4 col-lg-4">
												<input class="form-control" id="payment_ordinal_neft" name="payment_ordinal_neft" type="text">
         		 								<span class="input-group-addon"><i class="icon-list"></i></span>
    										</div>
  									</div>
  									<div class="metro">
										<div class="row">
  											<label  for="payment_date_neft" class="col-sm-4 col-md-4 col-lg-4 control-label">Date of Payment :</label>
  											<div class="col-sm-4 col-md-4 col-lg-4">
												<div class="input-control text " name="datepicker2" id="datepicker2" style="width:105%" >
			    									<input id="payment_date_neft"  name="payment_date_neft" type="text">
			    									<button class="btn-date"></button>
			    								</div>
		    								</div>
  										</div>
  									</div>											
  									<div class="form-group">
  										<label for="payment_agent_neft" class="col-sm-4 col-md-4 col-lg-4 control-label">Bank (who) Processed</label>
  											<div class="input-group col-sm-4 col-md-4 col-lg-4">
												<input class="form-control" id="payment_agent_neft" name="payment_agent_neft" type="text">
         		 								<span class="input-group-addon"><i class="icon-pencil"></i></span>
    										</div>
  									</div>
  									<div class="form-group">
  											<div class="input-group col-sm-4 col-md-4 col-lg-4">
  												<input type="hidden" name="currency" value="INR">
												<input type="hidden" name="payment_mode_inr" id="payment_mode_inr" value="<?php echo(CConfig::PAYMENT_MODE_NEFT); ?>">
    										</div>
  									</div> 
									<div class="form-group">
										<div class="col-lg-offset-4 col-sm-offset-4 col-md-offset-4" id="td_personal_edit_btn">
											<input  class="btn btn-success" type="submit"  style="font-weight:bold;" value="Process >>"/>	
						        		</div>
									</div>
									<div class="form-group">
	    								<div class="col-lg-offset-4 col-sm-offset-4 col-md-offset-4" id="error_div_1">
										</div>
	    							</div>
								</fieldset>
			    			</form>
							<br/><br/>
							<p style="color:blue;">NEFT Payment Instructions:</p>
							<ul>
								<li>Payment should always made in favor of,<br/>
								Account Name:<b>&lsquo;Mastishka Intellisys Private Limited&rsquo;</b><br/>
								Account Number:  <b>04 0420 0000 4883</b><br/>
								IFSC Code:  <b>HDFC0000404</b><br/>
								Bank: <b>HDFC Bank</b><br/>
								</li>
							</ul>		
			    		</div>
			    	</div>
			  	</div>
			  	<div style="padding-top:100px; display:none;">
					<ul style="color:#C53030;font-weight:bold">
						<li>Corporate &frasl; Institutes whose registered offices are in India are requested to pay in INR (Indian Rupees).</li> 
						<li>Overseas Clients (other than India) are requested to pay in USD (US Dollars).</li>
						<li>We will not accept recharge amount in INR (currency) from clients other than India.</li>
					</ul>
				</div>
				<?php 
					include_once(dirname(__FILE__)."/../../lib/footer.php");
				?>	
			</div> 
		</div>	
	</body>
	<script type="text/javascript">
		$(document).ready(function () {
			$('#pay_dd').click(function(){
	       			 $('#payment_mode_inr').val('<?php echo(CConfig::PAYMENT_MODE_DD); ?>');
	   			 });
				$('#pay_chq').click(function(){
		      	 $('#payment_mode_inr').val('<?php echo(CConfig::PAYMENT_MODE_CHEQUE); ?>');
		   	 });
			 	
			$("#datepicker2").datepicker({
				format: "dd mmmm yyyy",
				selected: function(d, d0){
					$("#date").empty();
				}			
			});
			$("#datepicker1").datepicker({
				format: "dd mmmm yyyy",
				selected: function(d, d0){
					$("#date").empty();	 
				}
			});
	
			jQuery.validator.addMethod("validate_date_neft", function(value, element) {
				if($( "#payment_date_neft" ).val() == "")
				{
					return false;
				}
				else
				{
	    			return true;
				}
			},"<span  id='date' style='color:red;'>*Please Select Date</style>");
			
			$("#form_neft_payment").validate({
				rules: {
					recharge_amount_neft: {
	        			required:true,
	        			number:true
	    			},
	    			payment_ordinal_neft: {
	        			required:true,
	    			}, 
	    			payment_date_neft:{
	    				'validate_date_neft':true, 
	       			},
	       			payment_agent_neft:{
	        			required:true,
	       	 		} 
	            	 
				},
				messages: {
					recharge_amount_neft: {	
						required:	"<span style='color:red'>*Amount Should Not Be Blank</span>",
						number:		"<span style='color:red'>*Amount Should Be In Number </span>",		
	    			},
	
	    			payment_ordinal_neft:{	
						required:	"<span style='color:red'>*NEFT Transaction ID Should Not Blank</span>",
	    			},
					payment_agent_neft:{
						required:	"<span style='color:red;'>*Please Enter Your Bank Name</span>",
					}
		    	},
		    	 errorElement: "div",
                 errorPlacement: function(error, element) {
                     error.appendTo("div#error_div_1");
                 }, 
		        
	    		submitHandler: function(form) {
	        		form.submit();
	    		}
	    		
			});

			jQuery.validator.addMethod("validate_date_chq_dd", function(value, element) {
				if($("#payment_date_chq" ).val() == "")
				{
					return false; //$("#error_div").html("<span style='color:red;'><b></b></style>");
				}
				else
				{
	    			return true;
				}
			},"<span  id='date' style='color:red;'>*Please Select Date</style>");
			
			$("#form_check_dd_payment").validate({
				rules: {
					recharge_amount_chq: {
	        			required:true,
	        			number:true,
	    			},
	    			payment_ordinal_chq: {
	        			required:true,
	    			}, 
	    			payment_date_chq:{
	    				'validate_date_chq_dd' : true, 
	       			},
	       			payment_agent_chq:{
	        			required:true,
	       	 		} 
	            	 
				},
				messages: {
					recharge_amount_chq: {	
						required:	"<span style='color:red'>*Amount Should Not Be Blank</span>",
						number:		"<span style='color:red'>*Amount Should Be In Number </span>",
	    			},
	
	    			payment_ordinal_chq:{	
						required:	"<span style='color:red'>*Check/DD Number Should Not Be Blank</span>",
	    			},
					payment_agent_chq:{
						required:	"<span style='color:red;'>*Please Enter Your Bank Name</span>",
					}
		    	},
		    	errorElement: "div",
                //place all errors in a <div id="errors"> element
                errorPlacement: function(error, element) {
                    error.appendTo("div#error_div_2");
                },
	    		submitHandler: function(form) {
	        		form.submit();
	    		}
			});

			$("#pay_chq").click(function(){
					$('#form_check_dd_payment')[0].reset();
				   	$("#form_check_dd_payment").validate().resetForm();
			});
			
			$("#pay_dd").click(function(){
					$('#form_check_dd_payment')[0].reset();
				   	$("#form_check_dd_payment").validate().resetForm();
			});
		});
	</script>
</html>