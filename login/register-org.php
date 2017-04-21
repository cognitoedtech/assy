<?php
	include_once(dirname(__FILE__)."/../lib/include_js_css.php");
	include_once("../lib/session_manager.php");
	include_once("../database/config.php");
	include_once("../database/mcat_db.php");
	include_once("../lib/user_manager.php");
    include_once("../lib/site_config.php") ;
	include_once("../lib/utils.php") ;
	
	$objDB = new CMcatDB();
	
	$parsAry = parse_url(CUtils::curPageURL());
	$qry = split("[=&]", $parsAry["query"]);
	
	$subscription = "";
	$plan = "";
	if($qry[0] == "sub")
	{
		$subscription = $qry[1];
	}
	
	$subAry = array("corp" => CConfig::UT_CORPORATE , "inst" => CConfig::UT_INSTITUTE );
	
	$final_plan = $subAry[$subscription];
	
	$objIncludeJsCSS = new IncludeJSCSS();
	
	$plan_ary = array("basic"=>CConfig::SPT_BASIC, "professional"=>CConfig::SPT_PROFESSIONAL, "enterprise"=>CConfig::SPT_ENTERPRISE, "ppu"=>CConfig::SPT_PPU);
	$plan_rate_ary = array("basic"=>CConfig::SPR_BASIC_INR, "professional"=>CConfig::SPR_PROFESSIONAL_INR, "enterprise"=>CConfig::SPR_ENTERPRISE_INR, "ppu"=>CConfig::SPR_PPU_INR);
	
	$final_plan = $plan_ary["basic"];
	$final_plan_rate = CConfig::SPR_BASIC_INR;
	
	if(isset($_GET['plan']) && array_key_exists($_GET['plan'], $plan_ary))
	{
		$final_plan = $plan_ary[$_GET['plan']];
		$final_plan_rate = $plan_rate_ary[$_GET['plan']];
	}
?>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="Generator" content="Mastishka Intellisys Private Limited">
<meta name="Author" content="Mastishka Intellisys Private Limited">
<meta name="Keywords" content="">
<meta name="Description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo(CConfig::SNC_SITE_NAME);?>: Registration Form</title>
<?php 
	$objIncludeJsCSS->CommonIncludeCSS("../");
	$objIncludeJsCSS->IncludeMipcatCSS("../");
	$objIncludeJsCSS->IncludeIconFontCSS("../");
	$objIncludeJsCSS->CommonIncludeJS("../");
	$objIncludeJsCSS->IncludeJqueryValidateMinJS("../");
	$objIncludeJsCSS->IncludeMetroNotificationJS("../");
?>
</head>
<body>

	<?php
	include_once (dirname ( __FILE__ ) . "/../lib/header.php");
	?>

	<!-- --------------------------------------------------------------- -->
	<br />
	<br />
	<br />
	<div class='row-fluid'>
		<div class='col-lg-10 col-md-10 col-sm-10'>
			<?php
				if(CSessionManager::IsError())
				{
					CSessionManager::SetError(false) ;
			?>
			<div class='row fluid'>
				<div class='col-lg-offset-2 col-md-offset-2 col-sm-offset-2'>
					<div class="drop-shadow raised" id="MSG">
						<fieldset>
						<legend>Error Message</legend>	
							<?php 
								echo("<p>Error during registeration : ".CSessionManager::GetErrorMsg()."</p>");
							?>
						<INPUT TYPE="button" NAME="HIDE" class='btn btn-success btn-sm' value="Hide" onClick="OnHide();"/>
						</fieldset>
					</div>
				</div>
			</div>
			<br />
			<?php
			CSessionManager::Logout() ;
				}
			?>
			<fieldset>
				<div class='row fluid'>
					<div class='col-lg-offset-2 col-md-offset-2 col-sm-offset-2'>
						<legend><h3>Registration Form</h3></legend>
					</div>
				</div>
				<div class='row fluid'>
					<div class='col-lg-offset-2 col-md-offset-2 col-sm-offset-2' style="border: 1px solid #ddd;">
						<br />
						<form class="form-horizontal" method="POST" action="register-org-exec.php" id="registration">
							<div class="form-group">
                             	<label for="PLAN_TYPE" class="col-lg-2 col-md-2 col-sm-2 control-label col-lg-offset-2 col-md-offset-2 col-sm-offset-2">Plan Type:</label>
                                <div class="col-lg-4 col-md-4 col-sm-4">
	                                <select name="PLAN_TYPE" id="PLAN_TYPE"  class="form-control input-sm">
	                                	<option value='<?php echo(CConfig::SPT_BASIC);?>' <?php echo(($final_plan == CConfig::SPT_BASIC)? "selected='selected'":""); ?>>Proprietary SaaS</option>
	                                	<option value='<?php echo(CConfig::SPT_PROFESSIONAL);?>' <?php echo(($final_plan == CConfig::SPT_PROFESSIONAL)? "selected='selected'":""); ?>>Hybrid SaaS</option>
	                                	<!-- <option value='<?php echo(CConfig::SPT_ENTERPRISE);?>' <?php echo(($final_plan == CConfig::SPT_ENTERPRISE)? "selected='selected'":""); ?>>Enterprise SaaS</option>-->
	                                	<option value='<?php echo(CConfig::SPT_PPU);?>' <?php echo(($final_plan == CConfig::SPT_PPU)? "selected='selected'":""); ?>>Pay per Use SaaS</option>
	                                </select>
                                </div>
                             </div>
                             <div class="form-group">
                             	<label class="col-lg-2 col-md-2 col-sm-2  control-label col-lg-offset-2 col-md-offset-2 col-sm-offset-2">Plan Rate:</label>
                                <div class="col-lg-4 col-md-4 col-sm-4" style="padding-top: 9px;">
                                	<span id="PLAN_RATE">
                                		<?php
                                		if ($final_plan == CConfig::SPT_PPU)
                                		{
                                		?>
                                			&#x20B9;<?php echo(CConfig::SPR_PPU_INR*CConfig::SPR_MINIMUM_PPU_TESTS);?> minimum recharge for <?php echo(CConfig::SPR_MINIMUM_PPU_TESTS);?> tests and then &#x20B9;<?php echo(CConfig::SPR_PPU_INR);?> per test/user
                                		<?php
                                		}
                                		else
                                		{
                                		?>
                                			Minimum &#x20B9;<?php echo($final_plan_rate*CConfig::SPR_MINIMUM_TESTS);?> per month for <?php echo(CConfig::SPR_MINIMUM_TESTS);?> tests and then &#x20B9;<?php echo($final_plan_rate);?> per test/user
                                		<?php
                                		}
                                		?>
                                	</span>
                                	
                                </div>
                             </div>
							 <div class="form-group">
      							<label for="ORG" class="col-lg-2 col-md-2 col-sm-2  col-lg-offset-2 col-md-offset-2 col-sm-offset-2 control-label">Organization<span style='color: red;'>*</span>:</label>
      							<div class="col-lg-4 col-md-4 col-sm-4">
        							<input class="form-control input-sm" name="ORG" id="ORG" type="text"/>
      							</div>
   							 </div>
   							 <div class="form-group">
						    	<label for="ORG_TYPE" class="col-lg-2 col-md-2 col-sm-2  col-lg-offset-2 col-md-offset-2 col-sm-offset-2 control-label">Organization Type<span style='color: red;'>*</span>:</label>
      							<div class="col-lg-4 col-md-4 col-sm-4">
        							<select class="form-control input-sm" id='ORG_TYPE' name="ORG_TYPE" step="1">
										<option value="">--Select--</option>
										<?php 
										foreach(CConfig::$ORG_TYPE_ARY as $org_type_id=>$org_type_name)
											printf("<option value='%s'>%s</option>", $org_type_name, $org_type_name);
										?>
									</select>
      							</div>
						    </div>
						    <div class="form-group" id="OTHER_ORG_DIV" style="display:none;">
      							<label class="col-lg-2 col-md-2 col-sm-2  col-lg-offset-2 col-md-offset-2 col-sm-offset-2 control-label"></label>
      							<div class="col-lg-4 col-md-4 col-sm-4">
        							<input class="form-control input-sm" name="OTHER_ORG" id="OTHER_ORG" placeholder="Please Specify Other Here" type="text"/>
      							</div>
   							 </div>
   							 <div class="form-group">
      							<label for="FNAME" class="col-lg-2 col-md-2 col-sm-2  col-lg-offset-2 col-md-offset-2 col-sm-offset-2 control-label">First Name<span style='color: red;'>*</span>:</label>
      							<div class="col-lg-4 col-md-4 col-sm-4">
        							<input class="form-control input-sm" name="FNAME" id="FNAME" type="text"/>
      							</div>
   							 </div>
   							 <div class="form-group">
      							<label for="LNAME" class="col-lg-2 col-md-2 col-sm-2  col-lg-offset-2 col-md-offset-2 col-sm-offset-2 control-label">Last Name<span style='color: red;'>*</span>:</label>
      							<div class="col-lg-4 col-md-4 col-sm-4">
        							<input class="form-control input-sm" name="LNAME" id="LNAME" type="text"/>
      							</div>
   							 </div>
						    <div class="form-group">
						    	<label for='PHONE' class="col-lg-2 col-md-2 col-sm-2  col-lg-offset-2 col-md-offset-2 col-sm-offset-2 control-label">Contact#<span style='color: red;'>*</span>:</label>
						    	<div class="col-lg-4 col-md-4 col-sm-4">
						    		<input class='form-control input-sm' name="PHONE" type="text" id="PHONE" />
						    	</div>
						    </div>
						    <div class="form-group">
      							<label for="EMAIL" class="col-lg-2 col-md-2 col-sm-2  col-lg-offset-2 col-md-offset-2 col-sm-offset-2 control-label">Email<span style='color: red;'>*</span>:</label>
      							<div class="col-lg-4 col-md-4 col-sm-4">
        							<input class="form-control input-sm" name="EMAIL" id="EMAIL" type="text"/>
      							</div>
   							 </div>
                             <div class="form-group" style="display: none;">
                             	<label class="col-lg-2 col-md-2 col-sm-2  control-label col-lg-offset-2 col-md-offset-2 col-sm-offset-2">Usage Type<span style='color: red;'>*</span>:</label>
                               	<div class="col-lg-2 col-md-2 col-sm-2 " style="width: 12%">
                               		<div class="radio">
                               			<label>
                                   			<input type="radio" name="USAGE" id="USAGE_PER_TEST" CHECKED="checked" value="<?php echo(CConfig::AUT_PER_TEST);?>" /> Per Test 
                                   		</label>
                                   	</div> 
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 " style="width: 12%">
                                	<div class="radio">
                               			<label>
                                    		<input type="radio" name="USAGE" id="USAGE_PER_MONTH" value="<?php echo(CConfig::AUT_PER_MONTH);?>" /> Per Month  
                                    	</label>
                                   	</div>                                                        
                                </div>
                             </div>
                             <div class="form-group" style="display: none;">
                             	<div id="show_pay_type">
                                	<label   class="col-lg-2 col-md-2 col-sm-2  control-label col-lg-offset-2 col-md-offset-2 col-sm-offset-2">Payment Type<span style='color: red;'>*</span>:</label>
                                	<div class="col-lg-1 col-md-1 col-sm-1">
	                               		<div class="radio">
	                               			<label>
	                                   			<input type="radio" name="PAY_TYPE" id="PAY_TYPE_PRE" CHECKED="checked" value="<?php echo(CConfig::BPT_PREPAID);?>" /> Prepaid
	                                   		</label>
	                                   	</div> 
	                                </div>
	                                <div class="col-lg-1 col-md-1 col-sm-1">
	                                	<div class="radio">
	                               			<label>
	                                    		<input type="radio" name="PAY_TYPE" id="PAY_TYPE_POST" value="<?php echo(CConfig::BPT_POSTPAID);?>" /> Postpaid 
	                                    	</label>
	                                   	</div>                                                        
	                                </div>
                                 </div>
                             </div>
						    <div class="form-group">
						    	<label for="PASSWORD" class="col-lg-2 col-md-2 col-sm-2  col-lg-offset-2 col-md-offset-2 col-sm-offset-2 control-label">Password<span style='color: red;'>*</span>:</label>
      							<div class="col-lg-4 col-md-4 col-sm-4">
        							<input class="form-control input-sm" name="PASSWORD" id="PASSWORD" type="password"/>
        							<FONT ID="PASSWORD_MSG" SIZE="" ALIGN=\"CENTRE\" COLOR="BLUE">(Password Length Should be Greater Then Or Equal To 8 letters)</FONT>
      							</div>
						    </div>
						    <div class="form-group">
						    	<label for="CPASSWORD" class="col-lg-2 col-md-2 col-sm-2  col-lg-offset-2 col-md-offset-2 col-sm-offset-2 control-label">Confirm Password<span style='color: red;'>*</span>:</label>
      							<div class="col-lg-4 col-md-4 col-sm-4">
        							<input class="form-control input-sm" name="CPASSWORD" id="CPASSWORD" type="password"/>
      							</div>
						    </div>
						    <div class="form-group" style="display:none;">
						    	<label for="QUESTION" class="col-lg-2 col-md-2 col-sm-2  col-lg-offset-2 col-md-offset-2 col-sm-offset-2 control-label">Security Question<span style='color: red;'>*</span>:</label>
      							<div class="col-lg-4 col-md-4 col-sm-4">
        							<select class="form-control input-sm" id='QUESTION' name="QUESTION" step="1">
										<option value="">--Select--</option>
										<option value="What is your pets name?" selected>What is your pets name?</option>
										<option value="What was the name of your first school">What was the name of your first school?</option>
										<option value="Who was your childhood hero?">Who was your childhood hero?</option>
										<option value="What is your favorite pass-time?">What is your favorite pass-time?</option>
										<option value="What is your all-time favorite sports team?">What is your all-time favorite sports team?</option>
										<option value="What is your fathers middle name?">What is your fathers middle name?</option>
										<option value="What was your high school mascot?">What was your high school mascot?</option>
										<option value="What make was your first car or bike?">What make was your first car or bike?</option>
										<option value="Where did you first meet your spouse?">Where did you first meet your spouse?</option>
									</select>
      							</div>
						    </div>
						    <div class="form-group" style="display:none;">
						    	<label for="ANSWER" class="col-lg-2 col-md-2 col-sm-2  col-lg-offset-2 col-md-offset-2 col-sm-offset-2 control-label">Answer<span style='color: red;'>*</span>:</label>
      							<div class="col-lg-4 col-md-4 col-sm-4">
        							<input class="form-control input-sm" name="ANSWER" id="ANSWER" type="text" value="not applicable"/>
      							</div>
						    </div>
						    <div class="form-group">
							   	<label for="VERIF_CODE" class="col-lg-4 col-md-4 col-sm-4 control-label">Verify Text<span style='color: red;'>*</span>:</label>
							   	<div class="col-lg-3 col-md-3 col-sm-3">
							   		<input class="form-control input-sm" id="VERIF_CODE" name="VERIF_CODE" type="text" />
							   	</div>
							   	<div class="col-lg-2 col-md-2 col-sm-2 ">
							   		<img id="captcha_img_demo" src="">
							  	</div>
							</div>
							<div class="form-group">
						    	<label class="col-lg-2 col-md-2 col-sm-2  col-lg-offset-2 col-md-offset-2 col-sm-offset-2 control-label"></label>
      							<div class="col-lg-4 col-md-4 col-sm-4">
        							<input class="form-control input-sm" type="hidden"/>
        							<div class="checkbox">
							          <label>
							            <input onchange="OnTermsClicked();" type="checkbox" id="terms_chk">I agree to <a target="_blank" href="<?php echo CSiteConfig::ROOT_URL;?>/terms/terms-of-service.php">Terms of Service</a> &amp; <a target="_blank" href="<?php echo CSiteConfig::ROOT_URL;?>/terms/privacy_policy.php">Privacy Policy.</a>
							          </label>
							        </div>
      							</div>
						    </div>
							<div class='form-group'>
								<div class='col-lg-4 col-md-4 col-sm-4 col-lg-offset-4 col-md-offset-4 col-sm-offset-4'>
									<input id="reg_button" class='btn btn-primary' disabled="disabled" type="submit" name="Submit" value="Register!" />
								</div>
							</div>
						</form>
					</div>
				</div>
			</fieldset>
			<div class='col-lg-offset-3 col-md-offset-3 col-sm-offset-3'>
				<?php
					include_once (dirname ( __FILE__ ) . "/../lib/footer.php");
				?>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$('#captcha_img_demo').attr('src','../3rd_party/captcha/captcha.php?r=' + Math.random());

		jQuery.validator.addMethod("alphanumeric", function(value, element) {
			return this.optional(element) || /^[a-zA-Z_\s]+[0-9]*[a-zA-Z0-9_\s]*$/.test(value);
		}, "<p style='color: red;'>Field required only alphanumeric letters (underscore and space is allowed) !</p>");

		$("#ORG_TYPE").change(function(){
			if($("#ORG_TYPE").val() == "<?php echo(CConfig::$ORG_TYPE_ARY[CConfig::OT_OTHER]);?>")
			{
				$("#OTHER_ORG_DIV").show();
			}
			else
			{
				$("#OTHER_ORG_DIV").hide();
			}
		});

		$("#PLAN_TYPE").change(function(){
			if($("#PLAN_TYPE").val() == <?php echo(CConfig::SPT_BASIC);?>)
			{
				$("#PLAN_RATE").html("Minimum &#x20B9;<?php echo(CConfig::SPR_BASIC_INR*CConfig::SPR_MINIMUM_TESTS);?> per month for <?php echo(CConfig::SPR_MINIMUM_TESTS);?> tests and then &#x20B9;<?php echo(CConfig::SPR_BASIC_INR);?> per test/user");
			}
			else if($("#PLAN_TYPE").val() == <?php echo(CConfig::SPT_PROFESSIONAL);?>)
			{
				$("#PLAN_RATE").html("Minimum &#x20B9;<?php echo(CConfig::SPR_PROFESSIONAL_INR*CConfig::SPR_MINIMUM_TESTS);?> per month for <?php echo(CConfig::SPR_MINIMUM_TESTS);?> tests and then &#x20B9;<?php echo(CConfig::SPR_PROFESSIONAL_INR);?> per test/user");
			}
			else if($("#PLAN_TYPE").val() == <?php echo(CConfig::SPT_ENTERPRISE);?>)
			{
				$("#PLAN_RATE").html("Minimum &#x20B9;<?php echo(CConfig::SPR_ENTERPRISE_INR*CConfig::SPR_MINIMUM_TESTS);?> per month for <?php echo(CConfig::SPR_MINIMUM_TESTS);?> tests and then &#x20B9;<?php echo(CConfig::SPR_ENTERPRISE_INR);?> per test/user");
			}
			else if($("#PLAN_TYPE").val() == <?php echo(CConfig::SPT_PPU);?>)
			{
				$("#PLAN_RATE").html("&#x20B9;<?php echo(CConfig::SPR_PPU_INR*CConfig::SPR_MINIMUM_PPU_TESTS);?> minimum recharge for <?php echo(CConfig::SPR_MINIMUM_PPU_TESTS);?> tests and then &#x20B9;<?php echo(CConfig::SPR_PPU_INR);?> per test/user");
			}
		});
		
		$('#registration').validate({
			rules: {
				'FNAME':			{required: true, 'alphanumeric': true},
				'LNAME':			{required: true, 'alphanumeric': true},
				'ORG':				{required: true},
				'ORG_TYPE':			{required: true},
				'OTHER_ORG':		{required: true},
				'EMAIL':			{required: true, email: true},
				'PHONE':			{required: true},
				'PASSWORD':			{required: true, minlength: 8},
				'CPASSWORD':		{required: true, equalTo: '#PASSWORD'},
				'QUESTION':			'required',
				'ANSWER':			'required',
				'VERIF_CODE':		'required'
			}, messages: {
				'FNAME':			{required:  "<p style='color: red;'>Please enter first name!</p>"},
				'LNAME':			{required:  "<p style='color: red;'>Please enter last name!</p>"},
				'ORG':				{required:	"<p style='color: red;'>Please enter name of the organization!</p>"},
				'ORG_TYPE':			{required:	"<p style='color: red;'>Please select the organization type!</p>"},
				'OTHER_ORG':		{required:	"<p style='color: red;'>Please specify the other organization type!</p>"},
				'EMAIL':			{required:	"<p style='color: red;'>Please enter your valid email-id!</p>", email:	"<p style='color: red;'>Please enter your valid email-id!</p>"},
				'PHONE':			{required:  "<p style='color: red;'>Please enter your valid contact number!</p>"},
				'PASSWORD':			{required:	"<p style='color: red;'>Minimum length for password field should be eight letters!</p>", minlength:	"<p style='color: red;'>Minimum length for password field should be eight letters!</p>"},
				'CPASSWORD':		{required:	"<p style='color: red;'>Confirm password should be same as password filed!</p>", equalTo:	"<p style='color: red;'>Confirm password should be same as password filed!</p>"},
				'QUESTION':			{required:	"<p style='color: red;'>Please select a question!</p>"},
				'ANSWER':			{required:	"<p style='color: red;'>Please enter proper answer for the security question!</p>"},
				'VERIF_CODE':		{required:	"<p style='color: red;'>Please enter verification code!</p>"}
			}
		});

		function OnTermsClicked()
		{
			if ($("#terms_chk").is(':checked')) 
			{
			    $("#reg_button").removeAttr("disabled");
			}
			else {
			    $("#reg_button").attr("disabled", "disabled");
			}
		}

		function OnHide()
		{
			$("#MSG").hide();
		}
	</script>
</body>
</html>