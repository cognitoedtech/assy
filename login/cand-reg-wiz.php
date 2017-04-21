<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	include_once(dirname(__FILE__)."/../lib/include_js_css.php");
	include_once(dirname(__FILE__)."/../lib/site_config.php");
	include_once("../lib/session_manager.php");
	include_once("../database/config.php") ;
	include_once("../lib/user_manager.php");
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	$login = CSessionManager::Get(CSessionManager::BOOL_LOGIN);
	
	$objUM = new CUserManager();
	$owners_in_waiting = $objUM->GetFieldValueByID($user_id, "owners_in_waiting");
	
	$ownerAry = "";
	if(!empty($owners_in_waiting))
	{
		$ownerAry = explode("|", $owners_in_waiting);
	}
	$objIncludeJsCSS = new IncludeJSCSS();
?>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="Generator" content="Mastishka Intellisys Private Limited">
<meta name="Author" content="Mastishka Intellisys Private Limited">
<meta name="Keywords" content="">
<meta name="Description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo(CConfig::SNC_SITE_NAME);?>: Security & Password Details</title>
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
	<div style="color:#FFF;background-color:CornflowerBlue;text-align:center; border: 1px dotted #003399;">
		<h4 style="color: #fff">Please follow the registration wizard to complete your registration process.</h4>
	</div>
	<div class='row-fluid'>
		<div class='col-lg-10 col-md-10 col-sm-10'>
			<fieldset>
				<div class='row fluid'>
					<div class='col-lg-offset-2 col-md-offset-2 col-sm-offset-2'>
						<legend><h3>Change Password</h3></legend>
					</div>
				</div>
				<div class='row fluid'>
					<div class='col-lg-offset-2 col-md-offset-2 col-sm-offset-2' style="border: 1px solid #ddd;">
						<br />
						<form class="form-horizontal" method="POST" action="cand-reg-wiz-exec.php" id="registration">
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
						    <div class="form-group" style="display: none;">
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
						    <div class="form-group">
						    	<label for="answer" class="col-lg-3 col-md-3 col-sm-3 col-lg-offset-2 col-md-offset-2 col-sm-offset-2 control-label"></label>
      							<div class="col-lg-4 col-md-4 col-sm-4">
      								<div style="display: none;">
        								<input class="form-control input-sm" name="answer" id="answer" type="text" value='not applicable'/><br />
        							</div>
        							<div class="checkbox">
							          <label>
							            <input onchange="OnTermsClicked();" type="checkbox" id="terms_chk">I agree to <a target="_blank" href="<?php echo CSiteConfig::ROOT_URL;?>/terms/terms-of-service.php">Terms of Service</a> &amp; <a target="_blank" href="<?php echo CSiteConfig::ROOT_URL;?>/terms/privacy_policy.php">Privacy Policy.</a>
							          </label>
							        </div>
      							</div>
						    </div>
							<div class='form-group'>
								<div class='col-lg-4 col-md-4 col-sm-4 col-lg-offset-5 col-md-offset-5 col-sm-offset-5'>
									<input id="reg_button" class='btn btn-primary' disabled="disabled" type="submit" name="Submit" value="Save!" />
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
		
		$('#registration').validate({
			rules: {
				'password':			{required: true, minlength: 8},
				'cpassword':		{required: true, equalTo: '#password'},
				'question':			'required',
				'answer':			'required'
			}, messages: {
				'password':			{required:	"<p style='color: red;'>Minimum length for password field should be eight letters!</p>", minlength:	"<p style='color: red;'>Minimum length for password field should be eight letters!</p>"},
				'cpassword':		{required:	"<p style='color: red;'>Confirm password should be same as password filed!</p>", equalTo:	"<p style='color: red;'>Confirm password should be same as password filed!</p>"},
				'question':			{required:	"<p style='color: red;'>Please select a question!</p>"},
				'answer':			{required:	"<p style='color: red;'>Please enter proper answer for the security question!</p>"}
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
	</script>
</body>
</html>