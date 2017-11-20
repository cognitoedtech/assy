<?php
	include_once(dirname(__FILE__)."/../lib/include_js_css.php");
	include_once("../lib/session_manager.php");
	include_once("../database/config.php");
	include_once("../database/mcat_db.php");
	include_once("../lib/user_manager.php");
    include_once("../lib/site_config.php") ;
	include_once("../lib/utils.php") ;
	
	$objUM = new CUserManager();
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
<title><?php echo(CConfig::SNC_SITE_NAME);?>: Registration Form</title>
<?php 
	$objIncludeJsCSS->CommonIncludeCSS("../");
	$objIncludeJsCSS->IncludeMipcatCSS("../");
	$objIncludeJsCSS->IncludeIconFontCSS("../");
	$objIncludeJsCSS->IncludeJqueryStepyCSS("../");
	$objIncludeJsCSS->CommonIncludeJS("../");
	$objIncludeJsCSS->IncludeJqueryValidateMinJS("../");
	$objIncludeJsCSS->IncludeMetroNotificationJS("../");
	$objIncludeJsCSS->IncludeJQueryStepyMinJS("../");
	$objIncludeJsCSS->IncludeMetroAccordionJS("../");
?>
<style type="text/css">
.step {
	width: 100%;
}
.metro .accordion.with-marker .heading:before {
	  top: 10px;
}
</style>
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
			<form method="POST" action="otfa-reg-form-exec.php" id="registration">
				<fieldset title="Basic Details" style="padding: 12px;">
					<legend>Basic deatils yourself</legend>
					<div class="row">
						<div class='col-lg-6 col-md-6 col-sm-6'>
		   					<div class="row">
		      					<div class="col-lg-6 col-md-6 col-sm-6">
		      						<label for="fname">First Name<span style='color: red;'>*</span> :</label>
		        					<input step="1" class="form-control input-sm" name="fname" id="fname" type="text"/>
		      					</div>
		   					</div>
		   					<div class="row">
		      					<div class="col-lg-6 col-md-6 col-sm-6">
		      						<label for="lname">Last Name<span style='color: red;'>*</span> :</label>
		        					<input step="1" class="form-control input-sm" name="lname" id="lname" type="text"/>
		      					</div>
		   					</div>
		   					<br />
		   					<div class="row">
								<label class="col-lg-2 col-md-2 col-sm-2">Gender:</label>
								<div class="col-lg-2 col-md-2 col-sm-2">
								 	<div class="radio">
								    	<label>
								        	<input NAME="gender" id="gender_male" value="1" CHECKED="checked" type="radio" />
								            Male
								        </label>
								    </div>
								</div>
								<div class="col-lg-2 col-md-2 col-sm-2">
									<div class="radio">
								    	<label>
								            <input NAME="gender" id ="gender_female" value="0" type="radio" />
								            Female
								        </label>
								    </div>
								</div>
							</div>
							<div class="row">
							   	<div class="col-lg-4 col-md-4 col-sm-4">
							   		<label for="month">Birth Day:</label>
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
							   	<div class="col-lg-2 col-md-2 col-sm-2">
							   		<label for="day">&nbsp;&nbsp;</label>
							   		<select name="day" class='form-control input-sm' id="day">
										<?php
											$objUM->ListDateOption() ;
										?>
									</select>
							   	</div>
							   	<div class="col-lg-4 col-md-4 col-sm-4">
							   		<label for="birthyear">&nbsp;&nbsp;</label>
							   		<select name="birthyear" class='form-control input-sm' id="birthyear">
										<?php
											$objUM->ListYearOption() ;
										?>
									</select>
							   	</div>
							</div>
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-6">
									<label for='contact'>Contact Number :</label>
									<input step="1" class='form-control input-sm' name="contact" type="text" id="contact" />
								</div>
							</div>
							<div class="row">
		      					<div class="col-lg-6 col-md-6 col-sm-6">
		      						<label for="email">Email<span style='color: red;'>*</span> :</label>
		        					<input step="1" class="form-control input-sm" name="email" id="email" type="text"/>
		      					</div>
		   					</div>
		   					<div class="row">
		      					<div class="col-lg-6 col-md-6 col-sm-6">
		      						<label for="city" >City :</label>
		        					<input step="1" class="form-control input-sm" name="city" id="city" type="text"/>
		      					</div>
		   					</div>
		   					<div class="row">
		      					<div class="col-lg-6 col-md-6 col-sm-6">
		      						<label for="state">State :</label>
		        					<input step="1" class="form-control input-sm" name="state" id="state" type="text"/>
		      					</div>
		   					</div>
		   					<div class="row">
		      					<div class="col-lg-6 col-md-6 col-sm-6">
		      						<label for="country">Country :</label>
		        					<select class="form-control input-sm" name="country" id="country">
										<?php
											$objUM->ListCountryOption() ;
										?>
									</select>
		      					</div>
		   					</div>
							<div class="row">
		      					<div class="col-lg-6 col-md-6 col-sm-6">
		      						<label for="password">Password<span style='color: red;'>*</span> :</label>
		        					<input step="1" class="form-control input-sm" name="password" id="password" type="password"/>
		        					<FONT ID="PASSWORD_MSG" SIZE="" ALIGN=\"CENTRE\" COLOR="BLUE">(Password Length Should be Greater Then Or Equal To 8 letters)</FONT>
		      					</div>
							</div>
							<div class="row">
		      					<div class="col-lg-6 col-md-6 col-sm-6">
		      						<label for="cpassword">Confirm Password<span style='color: red;'>*</span> :</label>
		        					<input step="1" class="form-control input-sm" name="cpassword" id="cpassword" type="password"/>
		      					</div>
							</div>
							<div class="row" style="display:none">
							   	<label for="question">Security Question<span style='color: red;'>*</span> :</label>
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
							<div class="row" style="display:none">
							   	<label for="answer">Answer<span style='color: red;'>*</span> :</label>
		      					<div class="col-lg-4 col-md-4 col-sm-4">
		        					<input class="form-control input-sm" name="answer" id="answer" type="text" value='not applicable'/>
		      					</div>
							</div>
							<input name="tschd_id" type="hidden" value="<?php echo($tschd_id);?>"/>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6" id="step1-error">
						</div>
					</div>
				</fieldset>
				<fieldset title="Academic Details" style="padding: 12px;">
					<legend>Provide education details</legend>
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6" style="border-right: 1px solid #ddd;">
							<div class="row">
		      					<div class="col-lg-6 col-md-6 col-sm-6">
		      						<label for="qualification">Qualification :</label>
		        					<select step="2" class="form-control input-sm" name="qualification" id="qualification">
										<option value="">--Select--</option>
										<option value="1">Higher Secondary - 10th</option>
										<option value="2">Senior Secondary - 12th</option>
										<option value="3">Diploma</option>
										<option value="4">Graduation</option>
										<option value="5">Post Graduation</option>
										<option value="6">Doctor of Phylosophy (PhD)</option>
									</select>
		      					</div>
		   					</div>
		   					<div class="row">
		      					<div class="col-lg-6 col-md-6 col-sm-6">
		      						<label for="area">Area :</label>
		        					<input step="2" class="form-control input-sm" name="area" id="area" type="text"/>
		      					</div>
		   					</div>
		   					<div class="row">
		      					<div class="col-lg-6 col-md-6 col-sm-6">
		      						<label for="stream">Stream :</label>
		        					<input step="2" class="form-control input-sm" name="stream" id="stream" type="text"/>
		      					</div>
		   					</div>
		   					<div class="row">
		      					<div class="col-lg-6 col-md-6 col-sm-6">
		      						<label for="percent">Percentage or CGPA :</label>
		        					<input step="2" class="form-control input-sm" name="percent" id="percent" type="text"/>
		      					</div>
		   					</div>
		   					<div class="row">
		      					<div class="col-lg-6 col-md-6 col-sm-6">
		      						<label for="institute">School or Institute :</label>
		        					<input step="2" class="form-control input-sm" name="institute" id="institute" type="text"/>
		      					</div>
		   					</div>
		   					<div class="row">
		      					<div class="col-lg-6 col-md-6 col-sm-6">
		      						<label for="board">Board or University :</label>
		        					<input step="2" class="form-control input-sm" name="board" id="board" type="text"/>
		      					</div>
		   					</div>
		   					<div class="row">
		      					<div class="col-lg-6 col-md-6 col-sm-6">
		      						<label for="passing_year">Passing Year (YYYY) :</label>
		        					<input step="2" class="form-control input-sm" name="passing_year" id="passing_year" type="text"/>
		      					</div>
		   					</div>
		   					<div class="row">
		   						<div class="col-lg-6 col-md-6 col-sm-6">
				   					<label><br/>To add this qualification into your profile press</label>
									<input type="button" class="btn btn-primary" value="Add >>" onclick="add_acc();"/>
								</div>
							</div>
							<div class="row">
		      					<div class="col-lg-6 col-md-6 col-sm-6">
		      						<label for="qual_count">Bucket Entries (Auto-Update) :</label>
		        					<input step="2" class="form-control input-sm" id="qual_count" readonly="readonly" name="qual_count" value='0' type="text"/>
		      					</div>
		   					</div>
							<div class="row">
							   	<label></label>
		      					<div class="col-lg-8 col-md-8 col-sm-8">
		        					<div class="checkbox">
							          <label>
							            <input onchange="OnTermsClicked();" type="checkbox" id="terms_chk">I agree to <a target="_blank" href="<?php echo CSiteConfig::ROOT_URL;?>/terms/terms-of-service.php">Terms of Service</a> &amp; <a target="_blank" href="<?php echo CSiteConfig::ROOT_URL;?>/terms/privacy_policy.php">Privacy Policy.</a>
							          </label>
							        </div>
		      					</div>
							</div>
	   					</div>
	   					<div class="col-lg-6 col-md-6 col-sm-6">
	   						<div style="font: 150% 'Trebuchet MS', sans-serif;color:#FFF;background-color:CornflowerBlue;text-align:center;border: 1px dotted #003399;">
								<b>Qualification Bucket</b>
							</div>
							<div class="metro">
								<div class="accordion with-marker col-sm-12 col-md-12 col-lg-12" id="accordion" data-role="accordion">
								</div>
							</div>
							<div id="step2-error">
							</div>
	   					</div>
   					</div><br />
				</fieldset>
				<input id="reg_button" class='finish' disabled="disabled" type="submit" name="Submit" value="Register!" />
			</form>
			<div class='col-lg-offset-3 col-md-offset-3 col-sm-offset-3'>
				<?php
					include_once (dirname ( __FILE__ ) . "/../lib/footer.php");
				?>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$('#registration').stepy({
			validate: true,
			errorImage: true,
			block: true,
			back: function(index) {
				//alert('Going to step ' + index + '...');
			}, next: function(index) {
				//alert('Going to step ' + index + '...');
			}, select: function(index) {
				//alert('Current step ' + index + '.');
			}, finish: function(index) {
				//alert('Finishing on step ' + index + '...');
			}, titleClick: true
		});
		jQuery.validator.addMethod("alphanumeric", function(value, element) {
			return this.optional(element) || /^[a-zA-Z_\s]+[0-9]*[a-zA-Z0-9_\s]*$/.test(value);
		}, "<p style='color: red;'>Field required only alphanumeric letters (underscore and space is allowed) !</p>");
		
		$('#registration').validate({
			errorPlacement: function(error, element) {
				if(element.attr("step") == 1)
				{
					$('#registration div#step1-error').append(error);
				}
				else if(element.attr("step") == 2)
				{
					$('#registration div#step2-error').append(error);
				}
			},
			rules: {
				'fname':			{required: true, 'alphanumeric': true},
				'lname':			{required: true, 'alphanumeric': true},
				'email':			{required: true, email: true},
				'password':			{required: true, minlength: 8},
				'cpassword':		{required: true, equalTo: '#password'},
				'passing_year':		{minlength:4, maxlength:4}
			}, messages: {
				'fname':			{required:  "<p style='color: red;'>Please enter first name!</p>"},
				'lname':			{required:  "<p style='color: red;'>Please enter last name!</p>"},
				'email':			{required:	"<p style='color: red;'>Please enter your valid email-id!</p>", email:	"<p style='color: red;'>Please enter your valid email-id!</p>"},
				'password':			{required:	"<p style='color: red;'>Minimum length for password field should be eight letters!</p>", minlength:	"<p style='color: red;'>Minimum length for password field should be eight letters!</p>"},
				'cpassword':		{required:	"<p style='color: red;'>Confirm password should be same as password filed!</p>", equalTo:	"<p style='color: red;'>Confirm password should be same as password filed!</p>"},
				'passing_year':		{minlength: 'Passing year should be in YYYY format.', maxlength:'Passing year should be in YYYY format.'}
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

		function add_acc()
		{
			$("#qual_count").val( parseInt($("#qual_count").val()) + 1 );			
			if($('#registration').valid())
			{
				var qual_ary = new Array($('#qualification option:selected').text(),
										 $('#qualification').val(),
									 	 $('input[name="area"]').val(),
									 	 $('input[name="stream"]').val(),
									 	 $('input[name="percent"]').val(),
									 	 $('input[name="institute"]').val(),
									 	 $('input[name="board"]').val(),
									 	 $('input[name="passing_year"]').val());

				var requiredHTML = $("#accordion").html();
				$("#accordion").empty();
				$("#accordion").accordion('destroy');
				$("#accordion").append(requiredHTML);
				$('#accordion').append(GetAccPanel(qual_ary));
				$('#accordion').accordion();
			}
			else
			{
				$("#qual_count").val( parseInt($("#qual_count").val()) - 1 );
			}
		}
		
		String.prototype.capitalize = function() 
		{
		    return this.charAt(0).toUpperCase() + this.slice(1);
		}
		
		function GetAccPanel(qualAry)
		{
			if( typeof GetAccPanel.nCounter == 'undefined' ) 
			{
				GetAccPanel.nCounter = 0;
		    }
		    
		    var fields = new Array("qualification", "stream", "area", "percent", "institute", "board", "passing_year");

			var sPanel = "<div class='accordion-frame'>";
			sPanel += "<a class='heading bg-lightBlue fg-white' style='font-size: 12px;' href='#'> "+qualAry[0]+"<img width='16' height='16' src='../images/close.png' style='position:absolute;right:5px;' onClick='RemoveAcc(this);'/></a>";
			sPanel += "<div class='content'>";
			for (index in fields)
			{
				if(index == 0)
				{
					sPanel += "<input type='hidden' name='"+fields[index]+"["+GetAccPanel.nCounter+"]' value='"+(qualAry[parseInt(index)+1])+"'/><br/>";
				}
				else
				{
					sPanel += "<div class='row'>";
					sPanel += "<div class='col-lg-8 col-md-8 col-sm-8'>";
					sPanel += "<lable><b>"+(fields[index].capitalize())+":</b></lable>";
					sPanel += "<input class='form-control input-sm'readonly='readonly' type='text' name='"+fields[index]+"["+GetAccPanel.nCounter+"]' value='"+(qualAry[parseInt(index)+1])+"'/><br/><br/>";
					sPanel += "</div>";
					sPanel += "</div>";
				}
			}
			sPanel += "</div>";
			sPanel += "</div>";
			
			//alert(sPanel);
			GetAccPanel.nCounter++;
			// Update qualification count of 
			
			return sPanel;
		}
		function RemoveAcc(obj)
		{
			$(obj).closest('a').parent().remove();
		    
		    $("#qual_count").val( parseInt($("#qual_count").val()) - 1 );
		}
	</script>
</body>
</html>