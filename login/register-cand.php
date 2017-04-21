<?php
	include_once(dirname(__FILE__)."/../lib/include_js_css.php");
	include_once("../lib/session_manager.php");
	include_once("../database/config.php");
	include_once("../database/mcat_db.php");
	include_once("../lib/user_manager.php");
    include_once("../lib/site_config.php") ;
	include_once("../lib/utils.php") ;
	
	$objUM = new CUserManager();
	$objDB = new CMcatDB();
	
	$parsAry = parse_url(CUtils::curPageURL());
	$qry = split("[=&]", $parsAry["query"]);
	
	$final_plan = "";
	$owner_id = "";
	$bExcludeContact = false;
	$batch_id = "";
	$batch_name = NULL;
	if($qry[0] == "plan")
	{
		$plan = $qry[1];
		$planAry = array("silver"=>7, "gold"=>8, "platinum"=>9);
		
		$final_plan = $planAry[$plan];
	}
	else if($qry[0] == "owner")
	{
		$owner_id = $qry[1];
		
		if($qry[2] == "exld_contact")
		{
			$bExcludeContact = ($qry[3] == 1) ? true : false;
		}
		else if($qry[2] == "batch_id")
		{
			$batch_id 	= $qry[3];
			
			if($batch_id == CConfig::CDB_ID)
			{
				$batch_name = CConfig::CDB_NAME;
			}
			else 
			{
				$batch_name = $objDB->GetBatchName($batch_id, $owner_id);
			}
		}
		
		if($qry[4] == "exld_contact")
		{
			$bExcludeContact = ($qry[5] == 1) ? true : false;
		}
	}
	$objIncludeJsCSS = new IncludeJSCSS();
	$owner_name = $objUM->GetFieldValueByID($owner_id, "organization_id");
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
			</div><br />
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
						<form class="form-horizontal" method="POST" action="register-cand-exec.php" id="registration">
							<?php 
							if(!empty($owner_id))
							{
								echo("<div class='col-lg-offset-2 col-md-offset-2 col-sm-offset-2'><label>Following organization/s wants to register you with them at ".CConfig::SNC_SITE_NAME.", confirm your registration with:</label><br />");
								echo("<input type='checkbox' checked='checked' disabled/> <font color='red'>".$owner_name."</font></div><br/>");
								echo("<input type='hidden' name='owner_id' value='".$owner_id."'/>");
								//echo("<br/><hr style='width:30%;position:absolute;left:20px;height:0;border-style:dotted;border-width:1px 0 0 0;border-color:#000;'/><br/>");
							}
							?>
							<div class="form-group">
      							<label class="col-lg-3 col-md-3 col-sm-3 col-lg-offset-2 col-md-offset-2 col-sm-offset-2 control-label">Batch :</label>
      							<div class="col-lg-4 col-md-4 col-sm-4">
        							<input class="form-control input-sm" value="<?php echo($batch_name);?>" type="text" readonly />
        							<input name="batch_id" type="hidden" value="<?php echo($batch_id);?>"/>
        							<input name="splan" type="hidden" value="<?php echo(CConfig::UT_INDIVIDAL);?>"/>
      							</div>
   							 </div>
   							 <div class="form-group">
      							<label for="fname" class="col-lg-3 col-md-3 col-sm-3 col-lg-offset-2 col-md-offset-2 col-sm-offset-2 control-label">First Name<span style='color: red;'>*</span> :</label>
      							<div class="col-lg-4 col-md-4 col-sm-4">
        							<input class="form-control input-sm" name="fname" id="fname" type="text"/>
      							</div>
   							 </div>
   							 <div class="form-group">
      							<label for="lname" class="col-lg-3 col-md-3 col-sm-3 col-lg-offset-2 col-md-offset-2 col-sm-offset-2 control-label">Last Name<span style='color: red;'>*</span> :</label>
      							<div class="col-lg-4 col-md-4 col-sm-4">
        							<input class="form-control input-sm" name="lname" id="lname" type="text"/>
      							</div>
   							 </div>
   							 <div class="form-group">
						      <label class="col-lg-3 col-md-3 col-sm-3 col-lg-offset-2 col-md-offset-2 col-sm-offset-2 control-label">Gender:</label>
						      <div class="col-lg-1 col-md-1 col-sm-1">
						      	<div class="radio">
						          <label>
						            <input NAME="gender" id="gender_male" value="1" CHECKED="checked" type="radio" />
						            Male
						          </label>
						        </div>
						      </div>
						      <div class="col-lg-1 col-md-1 col-sm-1">
						        <div class="radio">
						          <label>
						            <input NAME="gender" id ="gender_female" value="0" type="radio" />
						            Female
						          </label>
						        </div>
						      </div>
						    </div>
						    <div class="form-group">
						    	<label for="month" class="col-lg-3 col-md-3 col-sm-3 col-lg-offset-2 col-md-offset-2 col-sm-offset-2 control-label">Birth Day:</label>
						    	<div class="col-lg-2 col-md-2 col-sm-2">
						    		<select name="month" class='form-control input-sm' id="month">
										<option value="01" >January</option>
										<option value="02" >February</option>
										<option value="03" >March</option>
										<option value="04" >April</option>
										<option value="05" >May</option>
										<option value="06" >June</option>
										<option value="07" >July</option>
										<option value="08" >August</option>
										<option value="09" >September</option>
										<option value="10" >October</option>
										<option value="11" >November</option>
										<option value="12" >December</option>
									</select>
						    	</div>
						    	<div class="col-lg-1 col-md-1 col-sm-1">
						    		<select name="day" class='form-control input-sm' id="day">
										<?php
											$objUM->ListDateOption() ;
										?>
									</select>
						    	</div>
						    	<div class="col-lg-2 col-md-2 col-sm-2">
						    		<select name="birthyear" class='form-control input-sm' id="birthyear">
										<?php
											$objUM->ListYearOption() ;
										?>
									</select>
						    	</div>
						    </div>
						    <div class="form-group">
						    	<label for='contact' class="col-lg-3 col-md-3 col-sm-3 col-lg-offset-2 col-md-offset-2 col-sm-offset-2 control-label" style="<?php echo($bExcludeContact?"display:none;":"");?>">Contact Number <span style='color: red;'>*</span> :</label>
						    	<div class="col-lg-4 col-md-4 col-sm-4">
						    		<input class='form-control input-sm' style="<?php echo($bExcludeContact?"display:none;":"");?>" name="contact" type="text" id="contact" value="<?php echo($bExcludeContact?"1234567890":"");?>"/>
						    	</div>
						    </div>
						    <div class="form-group">
      							<label for="email" class="col-lg-3 col-md-3 col-sm-3 col-lg-offset-2 col-md-offset-2 col-sm-offset-2 control-label">Email<span style='color: red;'>*</span> :</label>
      							<div class="col-lg-4 col-md-4 col-sm-4">
        							<input class="form-control input-sm" name="email" id="email" type="text"/>
      							</div>
   							 </div>
   							 <div class="form-group">
      							<label for="city" class="col-lg-3 col-md-3 col-sm-3 col-lg-offset-2 col-md-offset-2 col-sm-offset-2 control-label">City<span style='color: red;'>*</span> :</label>
      							<div class="col-lg-4 col-md-4 col-sm-4">
        							<input class="form-control input-sm" name="city" id="city" type="text"/>
      							</div>
   							 </div>
   							 <div class="form-group">
      							<label for="state" class="col-lg-3 col-md-3 col-sm-3 col-lg-offset-2 col-md-offset-2 col-sm-offset-2 control-label">State<span style='color: red;'>*</span> :</label>
      							<div class="col-lg-4 col-md-4 col-sm-4">
        							<input class="form-control input-sm" name="state" id="state" type="text"/>
      							</div>
   							 </div>
   							 <div class="form-group">
      							<label for="country" class="col-lg-3 col-md-3 col-sm-3 col-lg-offset-2 col-md-offset-2 col-sm-offset-2 control-label">Country<span style='color: red;'>*</span> :</label>
      							<div class="col-lg-4 col-md-4 col-sm-4">
        							<select class="form-control input-sm" name="country" id="country">
										<?php
											$objUM->ListCountryOption() ;
										?>
									</select>
      							</div>
   							 </div>
						    <div class="form-group">
						    	<label for="password" class="col-lg-3 col-md-3 col-sm-3 col-lg-offset-2 col-md-offset-2 col-sm-offset-2 control-label">Password<span style='color: red;'>*</span> :</label>
      							<div class="col-lg-4 col-md-4 col-sm-4">
        							<input class="form-control input-sm" name="password" id="password" type="password"/>
        							<FONT ID="PASSWORD_MSG" SIZE="" ALIGN=\"CENTRE\" COLOR="BLUE">(Password Length Should be Greater Then Or Equal To 8 letters)</FONT>
      							</div>
						    </div>
						    <div class="form-group">
						    	<label for="cpassword" class="col-lg-3 col-md-3 col-sm-3 col-lg-offset-2 col-md-offset-2 col-sm-offset-2 control-label">Confirm Password<span style='color: red;'>*</span> :</label>
      							<div class="col-lg-4 col-md-4 col-sm-4">
        							<input class="form-control input-sm" name="cpassword" id="cpassword" type="password"/>
      							</div>
						    </div>
						    <div class="form-group" style="display:none">
						    	<label for="question" class="col-lg-3 col-md-3 col-sm-3 col-lg-offset-2 col-md-offset-2 col-sm-offset-2 control-label">Security Question<span style='color: red;'>*</span> :</label>
      							<div class="col-lg-4 col-md-4 col-sm-4">
        							<select class="form-control input-sm" id='question' name="question" step="1">
										<option value="">--Select--</option>
										<option value="What is your pets name?" selected='selected'>What is your pets name?</option>
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
						    <div class="form-group" style="display:none">
						    	<label for="answer" class="col-lg-3 col-md-3 col-sm-3 col-lg-offset-2 col-md-offset-2 col-sm-offset-2 control-label">Answer<span style='color: red;'>*</span> :</label>
      							<div class="col-lg-4 col-md-4 col-sm-4">
        							<input class="form-control input-sm" name="answer" id="answer" type="text" value='not applicable'/>
      							</div>
						    </div>
						    <div class="form-group">
							   	<label for="VERIF_CODE" class="col-lg-5 col-md-5 col-sm-5 control-label">Verify Text<span style='color: red;'>*</span> :</label>
							   	<div class="col-lg-3 col-md-3 col-sm-3">
							   		<input class="form-control input-sm" id="VERIF_CODE" name="VERIF_CODE" type="text" />
							   	</div>
							   	<div class="col-lg-2 col-md-2 col-sm-2" style="position:relative;">
							   		<img id="captcha_img_demo" src="">
							  	</div>
							</div>
							<div class="form-group">
						    	<label class="col-lg-3 col-md-3 col-sm-3 col-lg-offset-2 col-md-offset-2 col-sm-offset-2 control-label"></label>
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
								<div class='col-lg-4 col-md-4 col-sm-4 col-lg-offset-5 col-md-offset-5 col-sm-offset-5'>
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
		
		$('#registration').validate({
			rules: {
				'fname':			{required: true, 'alphanumeric': true},
				'lname':			{required: true, 'alphanumeric': true},
				'city':				{required: true, 'alphanumeric': true},
				'state':			{required: true, 'alphanumeric': true},
				'email':			{required: true, email: true},
				'contact':			{required: true},
				'password':			{required: true, minlength: 8},
				'cpassword':		{required: true, equalTo: '#password'},
				'question':			'required',
				'answer':			'required',
				'VERIF_CODE':		'required'
			}, messages: {
				'fname':			{required:  "<p style='color: red;'>Please enter first name!</p>"},
				'lname':			{required:  "<p style='color: red;'>Please enter last name!</p>"},
				'city':				{required:	"<p style='color: red;'>Please enter name of city you belongs to...</p>"},
				'state':			{required:	"<p style='color: red;'>Please enter name of state you belongs to...</p>"},
				'email':			{required:	"<p style='color: red;'>Please enter your valid email-id!</p>", email:	"<p style='color: red;'>Please enter your valid email-id!</p>"},
				'contact':			{required:  "<p style='color: red;'>Please enter your valid contact number!</p>"},
				'password':			{required:	"<p style='color: red;'>Minimum length for password field should be eight letters!</p>", minlength:	"<p style='color: red;'>Minimum length for password field should be eight letters!</p>"},
				'cpassword':		{required:	"<p style='color: red;'>Confirm password should be same as password filed!</p>", equalTo:	"<p style='color: red;'>Confirm password should be same as password filed!</p>"},
				'question':			{required:	"<p style='color: red;'>Please select a question!</p>"},
				'answer':			{required:	"<p style='color: red;'>Please enter proper answer for the security question!</p>"},
				'VERIF_CODE':			{required:	"<p style='color: red;'>Please enter verification code!</p>"}
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