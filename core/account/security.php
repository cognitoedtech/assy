<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	include_once(dirname(__FILE__)."/../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/../../lib/user_manager.php");
	include_once(dirname(__FILE__)."/../../lib/include_js_css.php");
	include_once(dirname(__FILE__)."/../../lib/site_config.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$objDB = new CMcatDB();
	$objUM = new CUserManager() ;
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	$objUser = $objUM->GetUserById($user_id);
	$billingInfo = 	$objUM->GetBillingInfo($user_id);
	
	$user_type = CSessionManager::Get(CSessionManager::INT_USER_TYPE);
	
	$objIncludeJsCSS = new IncludeJSCSS();
	
	$menu_id = CSiteConfig::UAMM_MY_ACCOUNT;
	$page_id = CSiteConfig::UAP_ACCOUNT_SECURITY;
?>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="Generator" content="Mastishka Intellisys Private Limited">
		<meta name="Author" content="Mastishka Intellisys Private Limited">
		<meta name="Keywords" content="">
		<meta name="Description" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo(CConfig::SNC_SITE_NAME);?>: Account Security</title>
		<?php 
			$objIncludeJsCSS->CommonIncludeCSS("../../");
			$objIncludeJsCSS->IncludeIconFontCSS("../../");
			$objIncludeJsCSS->CommonIncludeJS("../../");
			$objIncludeJsCSS->IncludeJqueryValidateMinJS("../../");
		?>
		<script type="text/javascript" charset="utf-8">
			function EditModeFormSecurity()
			{
				$("#td_password_val").hide();
				$("#td_password_edt").show();
				
				$("#td_cpassword_val").hide();
				$("#td_cpassword_edt").show();
				
				$("#td_question_val").hide();
				$("#td_question_edt").show();
				
				$("#td_answer_val").hide();
				$("#td_answer_edt").show();
				
				$("#td_secure_edit_btn").hide();
				$("#td_secure_save_btn").show();
			}
			function CancelSecurity()
			{
		       
				$("#td_email_val").show();
				$("#td_email_edt").hide();
				
				$("#td_password_val").show();
				$("#td_password_edt").hide();
				
				$("#td_cpassword_val").show();
				$("#td_cpassword_edt").hide();
				
				$("#td_question_val").show();
				$("#td_question_edt").hide();
				
				$("#td_answer_val").show();
				$("#td_answer_edt").hide();
				
				$("#td_secure_edit_btn").show();
				$("#td_secure_save_btn").hide();
				
		
			}
		</script>
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
				<br />
				<form class="form-horizontal" id="acnt_scrty" action="ajax/ajax_account_details.php?sec=2" method="POST">
			 		<div class="form-group">
					   	<label for="td_email_val" class="col-lg-2 col-md-2 col-sm-2 col-lg-offset-2 col-sm-offset-2 col-md-offset-2 control-label">Email :</label>
					   	<div class="col-sm-4 col-md-4 col-lg-4" id="td_email_val"  style="padding-top:10px;">
					   		<?php echo($objUser->GetEmail()); ?>	 
					   	</div>
					</div>
					<div class="form-group">
					   	<label for="PASSWORD" class="col-lg-2 col-md-2 col-sm-2 col-lg-offset-2 col-sm-offset-2 col-md-offset-2 control-label">Password :</label>
					   	<div class="col-lg-2 col-md-2 col-sm-2" id="td_password_val" style="padding-top:11px;" >****************</div>
					   	<div class="col-sm-4 col-md-4 col-lg-4" id="td_password_edt" style="display:none">
					   		<input class="form-control input-sm" id="PASSWORD" name="PASSWORD" type="password" />
					   		<BR/><FONT ID="PASSWORD_MSG" SIZE="" ALIGN=\"CENTRE\" COLOR="BLUE">(Password Length Should be Greater Then Or Equal To 8 )</FONT><BR/>
					   	</div>
					</div>
					<div class="form-group">
					   	<label for="CPASSWORD" class="col-lg-2 col-md-2 col-sm-2 col-lg-offset-2 col-sm-offset-2 col-md-offset-2 control-label">Confirm Password :</label>
					   	<div class="col-lg-1 col-md-1 col-sm-1" id="td_cpassword_val" style="padding-top:11px;">****************</div>
					   	<div class="col-sm-4 col-md-4 col-lg-4" id="td_cpassword_edt" style="display:none">
					   		<input class="form-control input-sm" id="CPASSWORD" name="CPASSWORD" type="password" />
					   	</div>	
					</div>
					<div class="form-group">
						<label for="QUESTION" class="col-lg-2 col-md-2 col-sm-2 col-lg-offset-2 col-sm-offset-2 col-md-offset-2 control-label">Security Question :</label>
						<div class="col-lg-1 col-md-1 col-sm-1" id="td_question_val" style="padding-top:11px;" >****************</div>
						<div class="col-sm-5 col-md-5 col-lg-5" id="td_question_edt" style="display:none">
							<select name="QUESTION" id="QUESTION" class="form-control input-sm">
								<option value="">-----Select----</option>
								<option value="What is your pets name?">What is your pets name?</option>
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
						<label for="ANSWER" class="col-lg-2 col-md-2 col-sm-2 col-lg-offset-2 col-sm-offset-2 col-md-offset-2 control-label">Answer:</label>
						<div class="col-lg-1 col-md-1 col-sm-1" id="td_answer_val" style="padding-top:11px;">****************</div>
						<div class="col-sm-5 col-md-5 col-lg-5" id="td_answer_edt" style="display:none">
							<input class="form-control input-sm" id="ANSWER" name="ANSWER" type="text" />
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-5 col-md-5 col-lg-5 control-label" id="td_secure_edit_btn">
							<input type="button" class="btn btn-primary" OnClick="return EditModeFormSecurity();" value="Edit" />	
				       	</div>
				       	<div class="col-sm-6 col-md-6 col-lg-6  control-label" id="td_secure_save_btn" style="display:none">
				       		<input type="submit" class="btn btn-primary" name="Submit"  value="Save" /> &nbsp;
				       		<input type="reset"  id="cancel" class="btn btn-primary" OnClick="return CancelSecurity();" value="Cancel" />
				       	</div>
			      	</div>
			 	</form><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
			 	<?php 
					include(dirname ( __FILE__ )."/../../lib/footer.php");
				?>
			</div>
		</div>
		<script type="text/javascript">
			$(document).ready(function() {
				$("#acnt_scrty").validate({
	    			rules: {
	    				PASSWORD: {
	            			required:true,
	       		 			minlength: 8
	        			},
	        			CPASSWORD: {
	            			required:true,
	            			equalTo: '#PASSWORD'
	        			},
	        			QUESTION: {
	            			required:true,
	        			},
	        			ANSWER:{
	        				required:true,
	           			}
	    			},
	    			messages: {
	    				PASSWORD: {	
	    					required:	"<span style='color:red'>*Please enter valid password!</span>",
	    					minlength:	"<span style='color:red'>*Password length should be minimum 8 letters!</span>"
	        			},
	
	        			CPASSWORD:{	
	    					required:	"<span style='color:red'>*Please enter password again!</span>",
	    					equalTo:	"<span style='color:red'>Confirm password should be same as password! </span>"
	    					
	        			},
	        			QUESTION:{	
	    					required:	"<span style='color:red'>*Please select a valid question!</span>",
	    				
	        			},
	        			ANSWER:{
							required:	"<span style='color:red;'>*Please enter a valid answer for security question!</span>",
	        	 		}
	
			    	},
		    		submitHandler: function(form) {
		        		form.submit();
		    		}
				});
				 
			});
		</script>
	</body>
</html>