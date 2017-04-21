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
	$page_id = CSiteConfig::UAP_PERSONAL_DETAILS;
?>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="Generator" content="Mastishka Intellisys Private Limited">
		<meta name="Author" content="Mastishka Intellisys Private Limited">
		<meta name="Keywords" content="">
		<meta name="Description" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo(CConfig::SNC_SITE_NAME);?>: Personal Details</title>
		<?php 
			$objIncludeJsCSS->CommonIncludeCSS("../../");
			$objIncludeJsCSS->IncludeIconFontCSS("../../");
			$objIncludeJsCSS->CommonIncludeJS("../../");
			$objIncludeJsCSS->IncludeJqueryValidateMinJS("../../");
		?>
		 
		<script type="text/javascript" charset="utf-8">
			function EditModeFormPersonal()
			{
				$("#td_fname_val").hide();
				$("#td_fname_edt").show();
				
				$("#td_lname_val").hide();
				$("#td_lname_edt").show();
				
				$("#td_gender_val").hide();
				$("#td_gender_edt").show();
				
				$("#td_dob_val").hide();
				$("#td_dob_edt").show();

				$("#td_contact_val").hide();
				$("#td_contact_edt").show();

				$("#td_address_val").hide();
				$("#td_address_edt").show();
				
				$("#td_city_val").hide();
				$("#td_city_edt").show();
				
				$("#td_state_val").hide();
				$("#td_state_edt").show();
				
				$("#td_country_val").hide();
				$("#td_country_edt").show();
				
				$("#td_personal_edit_btn").hide();
				$("#td_personal_save_btn").show();
			}
			function CancelPersonal()
			{
				$("#td_fname_val").show();
				$("#td_fname_edt").hide();
				
				$("#td_lname_val").show();
				$("#td_lname_edt").hide();
				
				$("#td_gender_val").show();
				$("#td_gender_edt").hide();
				
				$("#td_dob_val").show();
				$("#td_dob_edt").hide();

				$("#td_contact_val").show();
				$("#td_contact_edt").hide();

				$("#td_address_val").show();
				$("#td_address_edt").hide();
				
				$("#td_city_val").show();
				$("#td_city_edt").hide();
				
				$("#td_state_val").show();
				$("#td_state_edt").hide();
				
				$("#td_country_val").show();
				$("#td_country_edt").hide();
				
				$("#td_personal_edit_btn").show();
				$("#td_personal_save_btn").hide();
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
			<div id="page_title" class="col-sm-9 col-md-9 col-lg-9">
				<div  id="tab1" class="col-sm-12 col-md-12 col-lg-12" style="border-left: 1px solid #ddd; border-top: 1px solid #ddd;"><br />
					<form class="form-horizontal" id="prsnl_dtls" action="ajax/ajax_account_details.php?sec=1" method="POST">
						<div  id="pers_detail_form_content">
							<div class="form-group">
							    <label for="FNAME" class="col-sm-4 col-md-4 col-lg-4 control-label">First Name :</label>
								<div class="col-sm-2 col-md-2 col-lg-2" id="td_fname_val" style="padding-top:11px;"><?php echo($objUser->GetFirstName()); ?></div>
								<div class="col-sm-4 col-md-4 col-lg-4" id="td_fname_edt" style="display:none">
							    	<input class="form-control input-sm" id="FNAME" name="FNAME" type="text" value="<?php echo($objUser->GetFirstName()); ?>"  class="input" />
							    </div>
							</div>
						    <div class="form-group">
							    <label for="LNAME" class="col-sm-4 col-md-4 col-lg-4 control-label">Last Name :</label>
							    <div class="col-sm-2 col-md-2 col-lg-2" id="td_lname_val" style="padding-top:11px;"><?php echo($objUser->GetLastName()); ?></div>
							   	<div class="col-sm-4 col-md-4 col-lg-4" id="td_lname_edt" style="display:none">
						    		<input class="form-control input-sm" id="LNAME" name="LNAME" type="text"  value="<?php echo($objUser->GetLastName()); ?>" class="input" />
						    	</div>
						    </div>
						    <div class="form-group">
								<label for="GENDER" class="col-sm-4 col-md-4 col-lg-4 control-label">Gender :</label>
								<div class="col-sm-2 col-md-2 col-lg-2" id="td_gender_val" style="padding-top:11px;"><?php echo(($objUser->GetGender() == 0)?"Female":"Male"); ?></div>
								<div class="col-sm-4 col-md-4 col-lg-4" id="td_gender_edt" style="display:none">
									<INPUT  TYPE="radio"  NAME="GENDER" id="GENDER_MALE" value="1" <?php echo(($objUser->GetGender() == 1)?"CHECKED='checked'":""); ?> /> Male &nbsp;
									<INPUT TYPE="radio" NAME="GENDER" id ="GENDER_FEMALE" value="0" <?php echo(($objUser->GetGender() == 0)?"CHECKED='checked'":""); ?>/> Female
								</div>
						 	</div>
						 	<div class="form-group">
								<label for="BIRTDAY" class="col-sm-4 col-md-4 col-lg-4 control-label">Birth Day :</label>
								<div class="col-sm-2 col-md-2 col-lg-2" id="td_dob_val" style="padding-top:11px;"><?php echo($objUser->GetDOB()); ?></div>
								<div style="display:none" id="td_dob_edt">
									<div class="col-sm-2 col-md-2 col-lg-2" >
										<select name="MONTH" id="MONTH" class="form-control input-sm">
											<?php
												$ary_dob = $objUser->GetDOB(true);
												$objUM->ListMonthOption($ary_dob['month']);
											?>	 
										</select>
									</div>
									<div class="col-sm-1 col-md-1 col-lg-1">
										<select name="DAY" id="DAY" class="form-control input-sm">
											<?php
												$objUM->ListDateOption($ary_dob['day']) ;
											?>
										</select>
									</div>
									<div class="col-sm-2 col-md-2 col-lg-2">
										<select name="BIRTHYEAR" id="BIRTHYEAR" class="form-control input-sm">
											<?php
												$objUM->ListYearOption($ary_dob['year']) ;
											?>
										</select>
									</div>
								</div>		
							</div>
							<div class="form-group">
								<label for="PHONE" class="col-sm-4 col-md-4 col-lg-4 control-label">Contact#:</label>
								<div class="col-sm-2 col-md-2 col-lg-2" id="td_contact_val" style="padding-top:11px;"><?php echo($objUser->GetContactNo()); ?></div>
								<div class="col-sm-4 col-md-4 col-lg-4" id="td_contact_edt" style="display:none">
									<input class="form-control input-sm" id="PHONE" name="PHONE" type="text"  value="<?php echo($objUser->GetContactNo()); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label for="ADDRESS" class="col-sm-4 col-md-4 col-lg-4 control-label">Address :</label>
								<div class="col-sm-2 col-md-2 col-lg-2" id="td_address_val" style="padding-top:11px;"><?php echo($objUser->GetAddress()); ?></div>
								<div class="col-sm-4 col-md-4 col-lg-4" id="td_address_edt" style="display:none">
									<input class="form-control input-sm" id="ADDRESS" name="ADDRESS" type="text"  value="<?php echo($objUser->GetAddress()); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label for="CITY" class="col-sm-4 col-md-4 col-lg-4 control-label">City :</label>
								<div class="col-sm-2 col-md-2 col-lg-2" id="td_city_val" style="padding-top:11px;"><?php echo($objUser->GetCity()); ?></div>
								<div class="col-sm-4 col-md-4 col-lg-4" id="td_city_edt" style="display:none">
									<input class="form-control input-sm" id="CITY" name="CITY" type="text"  value="<?php echo($objUser->GetCity()); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label for="STATE" class="col-sm-4 col-md-4 col-lg-4 control-label">State :</label>
								<div class="col-sm-2 col-md-2 col-lg-2" id="td_state_val" style="padding-top:11px;"><?php echo($objUser->GetState()); ?></div>
								<div class="col-sm-4 col-md-4 col-lg-4" id="td_state_edt" style="display:none">
									<input class="form-control input-sm" id="STATE" name="STATE" type="text"  value="<?php echo($objUser->GetState()); ?>"/>
								</div>
							</div>
							<div class="form-group">
								<label for="COUNTRY" class="col-sm-4 col-md-4 col-lg-4 control-label">Country :</label>
								<div class="col-sm-2 col-md-2 col-lg-2" id="td_country_val" style="padding-top:11px;"> <?php echo ($objUM->GetCountryText($objUser->GetCountry())); ?></div>
								<div class="col-sm-4 col-md-4 col-lg-4" id="td_country_edt" style="display:none">
									<select name="COUNTRY"  id="COUNTRY" class="form-control">
										<?php
											$objUM->ListCountryOption($objUser->GetCountry()) ;
										?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-5 col-md-5 col-lg-5 col-lg-offset-4 col-sm-offset-4 col-md-offset-4" id="td_personal_edit_btn">
									<input type="button" class="btn btn-primary" OnClick="return EditModeFormPersonal();" value="Edit" />	
					       		</div>
					       		<div class="col-sm-6 col-md-6 col-lg-6 control-label" id="td_personal_save_btn" style="display:none">
					       			<input type="submit" class="btn btn-primary" name="Submit"  value="Save" /> &nbsp;
					       			<input type="reset"  class="btn btn-primary" OnClick="return CancelPersonal();" value="Cancel" />
					       		</div>
				      		</div>
				      	</div>			    
					</form><br /><br /><br /><br /><br /><br /><br />
				</div>
				<?php 
					include(dirname ( __FILE__ )."/../../lib/footer.php");
				?>
			</div>
		</div>
		<script type="text/javascript">
			$(document).ready(function() {
				$("#prsnl_dtls").validate({
					rules: {
						FNAME: {
		        			required:true,
		    			},
		    			LNAME: {
		        			required:true,
		    			},
		    			 
		    			PHONE:{
		    				required:true,
		       	 			number: true,
		       			},
		       			ADDRESS:{
		        			required:true,
		       	 		},
		       	 		
		           	 	CITY:{
		          			required:true,
		            	},
		            	STATE:{
		           			required:true,
		            	}
		            	 
					},
					messages: {
						FNAME: {	
							required:	"<span style='color:red'>*Please enter first name</span>",
		    			},
		
		    			LNAME:{	
							required:	"<span style='color:red'>*Please enter last name</span>",
		    			},
		    			 
		    			PHONE:{
							required:	"<span style='color:red;'>*Please enter your contact#</span>",
		    	 			number:		"<span style='color:red;'>*contact number must contain digits only</span>",
						},
		
						ADDRESS:{
							required:	"<span style='color:red;'>*Please enter your address</span>",
						},
						CITY:{
							required:	"<span style='color:red;'>*Please enter name of the city </span>",
						},
						STATE:{
							required:	"<span style='color:red;'>*Please enter name of the state </span>",
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