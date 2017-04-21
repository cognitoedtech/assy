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
	
	if($user_type == CConfig::UT_INDIVIDAL)
	{
		echo("<script> var tab_id='tab2'; </script>");
	}
	else
	{
		echo("<script> var tab_id='tab1'; </script>");
	}
	$orgAry = $objUM->GetOrgInfo($objUser->GetOrganizationId()); 
	
	$objIncludeJsCSS = new IncludeJSCSS();
	
	
	$menu_id = CSiteConfig::UAMM_MY_ACCOUNT;
	$page_id = CSiteConfig::UAP_ABOUT_ORGANIZATION;
?>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="Generator" content="Mastishka Intellisys Private Limited">
		<meta name="Author" content="Mastishka Intellisys Private Limited">
		<meta name="Keywords" content="">
		<meta name="Description" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo(CConfig::SNC_SITE_NAME);?>: About Organization </title>
		<?php 
			$objIncludeJsCSS->CommonIncludeCSS("../../");
			$objIncludeJsCSS->IncludeBootStrapFileUploadCSS("../../");
			$objIncludeJsCSS->IncludeIconFontCSS("../../");
			$objIncludeJsCSS->CommonIncludeJS("../../");
			$objIncludeJsCSS->IncludeBootStrapFileUploadMinJS("../../");
			$objIncludeJsCSS->IncludeJqueryValidateMinJS("../../");
		?>
		<script type="text/javascript" charset="utf-8">
			function EditModeFormOrg()
				{
					$("#td_orgname_val").hide();
					$("#td_orgname_edt").show();
		
					$("#td_orgurl_val").hide();
					$("#td_orgurl_edt").show();
						
					$("#td_orgsize_val").hide();
					$("#td_orgsize_edt").show();
		
					$("#tr_orglogo_selection").show();
					$("#logo_type_text").removeAttr("checked");
					$("#logo_type_img").attr("checked", "checked");
	
					$("#orglogo_img_val").hide();
					$("#orglogo_img_edt").show();
					$("#lbl_orglogo_img").show();
		
					$("#lbl_orglogo_name").hide();
					$("#td_orglogoname_val").hide();
					$("#lbl_orglogo_punch_line").hide();
					$("#td_orgpunchline_val").hide();
						
					$("#td_orgloginname_val").hide();
					$("#td_orgloginname_edt").show();
						
					$("#td_org_edit_btn").hide();
					$("#td_org_save_btn").show();
					}
					function CancelOrg()
					{
						$("#td_orgname_val").show();
						$("#td_orgname_edt").hide();
		
						$("#td_orgurl_val").show();
						$("#td_orgurl_edt").hide();
						
						$("#td_orgsize_val").show();
						$("#td_orgsize_edt").hide();
		
						$("#tr_orglogo_selection").hide();
		
						$("#orglogo_img_edt").hide();
						<?php 
						if(empty($orgAry['punch_line']) && empty($orgAry['logo_name']))
						{
						?>
						$("#lbl_orglogo_img").show();
						$("#orglogo_img_val").show();
						<?php 
						}
						else 
						{
						?>
						$("#lbl_orglogo_img").hide();
						$("#orglogo_img_val").hide();
						<?php 
						}
						?>
		
						$("#td_orglogoname_edt").hide();
						$("#td_orgpunchline_edt").hide();
						<?php 
						if(empty($orgAry['logo_image']))
						{
						?>
						$("#lbl_orglogo_name").show();
						$("#td_orglogoname_val").show();
		
						$("#lbl_orglogo_punch_line").show();
						$("#td_orgpunchline_val").show();
						<?php 
						}
						else 
						{
						?>
						$("#lbl_orglogo_name").hide();
						$("#td_orglogoname_val").hide();
		
						$("#lbl_orglogo_punch_line").hide();
						$("#td_orgpunchline_val").hide();
						<?php 
						}
						?>
						
						$("#td_orgloginname_val").show();
						$("#td_orgloginname_edt").hide();
						
						$("#td_org_edit_btn").show();
						$("#td_org_save_btn").hide();
					}
		
					function OnLogoOptionChange(obj)
					{
						var logo_type = $(obj).val();
		
						if(logo_type == "text")
						{
							$("#orglogo_img_edt").hide();
							$("#lbl_orglogo_img").hide();
							$("#lbl_orglogo_punch_line").show();
							$("#lbl_orglogo_name").show();
							$("#td_orglogoname_edt").show();
							$("#td_orgpunchline_edt").show();
						} 
						else
						{
							$("#lbl_orglogo_punch_line").hide();
							$("#lbl_orglogo_name").hide();
							$("#td_orglogoname_edt").hide();
							$("#td_orgpunchline_edt").hide();
							$("#lbl_orglogo_img").show();
							$("#orglogo_img_edt").show();
						}
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
			<div class="col-lg-3 col-md-3 col-sm-3">
				<?php 
					include_once(dirname(__FILE__)."/../../lib/sidebar.php");
				?>
			</div>
			<div  id="tab1" class="col-lg-9 col-sm-9col-md-9" style="border-left: 1px solid #ddd; border-top: 1px solid #ddd;"> <br />
				<div <?php echo($user_type==CConfig::UT_INDIVIDAL?"style='display:none'":""); ?>>
					<form class="form-horizontal" id="acnt_dtls" action="ajax/ajax_account_details.php?sec=3" method="POST" enctype="multipart/form-data">
						<div  id="abt_org_form_content">
							<div class="form-group">
								<label for="ORG" class="col-sm-4 col-md-4 col-lg-4 control-label">Organization Name:</label>
								<div class="col-sm-4 col-md-4 col-lg-4" id="td_orgname_val" style="padding-top:11px;">
									<?php
										echo $orgAry['organization_name'];
									?>
								</div>
								<div class="col-sm-4 col-md-4 col-lg-4" id="td_orgname_edt" style="display:none">
									<input class="form-control input-sm" id="ORG" name="ORG" type="text" value="<?php echo $orgAry['organization_name']; ?>"/>
								</div>
							</div>
							<div class="form-group">
								<label for="ORGURL" class="col-sm-4 col-md-4 col-lg-4 control-label">Organization Url:</label>
									<div  class="col-sm-4 col-md-4 col-lg-4"  id="td_orgurl_val" style="padding-top:11px;">
										<?php
											echo $orgAry['organization_url'];
										?>
									</div>
									<div  class="col-sm-4 col-md-4 col-lg-4" id="td_orgurl_edt"  style="display:none"><input class="form-control input-sm"  name="ORGURL" type="text"  value="<?php echo $orgAry['organization_url']; ?>" id="ORGURL" /></div>
							</div>		
							<div class="form-group">
							 	<label for="ORGSIZE" class="col-sm-4 col-md-4 col-lg-4 control-label">Organization Size :</label>
							 	<div class="col-sm-2 col-md-2 col-lg-2" id="td_orgsize_val" style="padding-top:11px;">
							 		<?php 
							 			echo $orgAry['organization_size']; 
							 		?>
							 	</div>
								<div class="col-sm-4 col-md-4 col-lg-4" id="td_orgsize_edt" style="display:none">
							  		<select  class="form-control input-sm" name="ORGSIZE" id="ORGSIZE" >
										<?php
											$objUM->ListOrgSizeOption($orgAry['organization_size']) ;
										?>
									</select>
							 	</div>							 
							</div>
							<?php 
							if($user_type != CConfig::UT_COORDINATOR)
							{
							?>
							<div class="form-group" id='tr_orglogo_selection' style="display: none;" >
								<label for="LOGO_TYPE" class="col-sm-4 col-md-4 col-lg-4 control-label">Logo Type:</label>
									<div class="col-sm-4 col-md-4 col-lg-4" style="padding-top:8px;">
										<input type="radio" name="logo_type" id="logo_type_text" onchange="OnLogoOptionChange(this);" value="text" /> Text
										<input type="radio" name="logo_type" id="logo_type_img" onchange="OnLogoOptionChange(this);" value="image" checked/> Image
									</div>
							 </div>
							<div class="form-group">
							 	<div id="lbl_orglogo_name" style="<?php echo(empty($orgAry['logo_image'])?'':'display:none;');?>">
									<label  class="col-sm-4 col-md-4 col-lg-4 control-label" for="LOGONAME">Logo Name:</label>
								</div>
								<div class="col-sm-4 col-md-4 col-lg-4" id="td_orglogoname_val" style="<?php echo(empty($orgAry['logo_image'])?'':'display:none;');?> padding-top:11px;">
									<?php
										echo (!empty($orgAry['logo_name'])?$orgAry['logo_name'] : "Not Applicable");
									?>
								</div>
								<div  class="col-sm-4 col-md-4 col-lg-4"id="td_orglogoname_edt" style="display:none">
									<input  class="form-control input-sm"  name="LOGONAME" type="text"  value="<?php echo $orgAry['logo_name']; ?>"  id="LOGONAME" />
								</div>
							</div>
							<div class="form-group" >
								<div id="lbl_orglogo_punch_line" style="<?php echo(empty($orgAry['logo_image'])?'':'display:none;');?>">
									<label  class="col-sm-4 col-md-4 col-lg-4 control-label" for="PUNCHLINE">Punch Line:</label>
								</div>
								<div  class="col-sm-4 col-md-4 col-lg-4" id="td_orgpunchline_val" style="<?php echo(empty($orgAry['logo_image'])?'':'display:none;');?> padding-top:11px;">
									<?php
										echo (!empty($orgAry['punch_line'])?$orgAry['punch_line'] : "Not Applicable");
									?>
								</div>
								<div class="col-sm-4 col-md-4 col-lg-4" id="td_orgpunchline_edt" style="display:none">
									<input  class="form-control input-sm" name="PUNCHLINE" type="text"  value="<?php echo $orgAry['punch_line']; ?>"  id="PUNCHLINE"  />
								</div>
							</div>
							<div class="form-group" >
								<div id="lbl_orglogo_img" style="<?php echo((empty($orgAry['punch_line']) && empty($orgAry['logo_name']))?'':'display:none;');?>">
									<label for="LOGO_IMAGE" class="col-sm-4 col-md-4 col-lg-4 control-label">Logo Image:</label>
								</div>
								<div class="col-sm-4 col-md-4 col-lg-4" id="orglogo_img_val" style="<?php echo((empty($orgAry['punch_line']) && empty($orgAry['logo_name']))?'':'display:none;');?> padding-top:11px; ">
									<img src="../../test/lib/print_image.php?org_logo_img=<?php echo($objUser->GetOrganizationId());?>" style="width: <?php echo(CConfig::OL_WIDTH);?>px; height: <?php echo(CConfig::OL_HEIGHT);?>px;" alt="Logo image is not availabe" />
								</div>
								<div  class="col-sm-4 col-md-4 col-lg-4" id='orglogo_img_edt' style="display: none;">
									<p style="padding-top:8px;">(Image dimensions should be <b><?php echo(CConfig::OL_WIDTH);?>px X <?php echo(CConfig::OL_HEIGHT);?>px</b>)</p>
									<div class="fileupload fileupload-new" data-provides="fileupload">
										<div class="fileupload-preview thumbnail" style="width: 60%; height: 125px;"></div>
										<div>
											<span class="btn btn-sm btn-success btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span><input type="file" id="ORGLOGOIMG" name="ORGLOGOIMG" /></span>
											<a href="#" class="btn btn-sm btn-success fileupload-exists" data-dismiss="fileupload">Remove</a>
										</div>
									</div>
									<span id="ORGLOGOIMG_ERR" style="color: red;"></span>
								</div>
							</div>
							<div class="form-group">
								<label for="LOGINNAME" class="col-sm-4 col-md-4 col-lg-4 control-label">Login Name:</label> 
									<div class="col-lg-8 col-sm-8col-md-8" id="td_orgloginname_val" style="padding-top:11px;">
										<?php
											echo $objUser->GetLoginName();
										?>
									(<b>Your personalized URL:</b> <u style='color:blue'><?php echo(CSiteConfig::ROOT_URL ."/".$objUser->GetLoginName());?></u>)
									</div>
									<div class="col-sm-4 col-md-4 col-lg-4" id="td_orgloginname_edt" style="display:none">
									 	<input class="form-control input-sm" id="LOGINNAME" name="LOGINNAME" type="text" value="<?php echo $objUser->GetLoginName(); ?>"  onkeyup="OnLoginNameChange(this);" /><span id="tp_checking" style="display:none;">&nbsp;<img src="../../images/updating.gif" width="12" height="12"/> Checking</span><span id="tp_exist" style="color:red;display:none;">&nbsp;Name already exists!</span>
									</div>
							</div>
							<div class="form-group">
								<div class="col-sm-4 col-md-4 col-lg-4">
									<input	name="ORG_ID" type="hidden" value="<?php echo $objUser->GetOrganizationId();?>"/>
								</div>
							</div>
							<div class="form-group">
								<div id="td_org_edit_btn" class="col-lg-offset-4 col-sm-offset-4 col-md-offset-4">
										&nbsp;&nbsp;<input type="button" class="btn btn-primary btn-sm" OnClick="return EditModeFormOrg();" value="Edit" />
								</div>
							</div>
							<div class="form-group">
								<div  class="col-lg-offset-4 col-sm-offset-4 col-md-offset-4" id="td_org_save_btn" style="display:none">
								&nbsp;&nbsp;&nbsp;&nbsp;<b>Your personalized URL:</b> <u style='color:blue'><?php echo(CSiteConfig::ROOT_URL ."/".$objUser->GetLoginName());?></u><br /> <br/>&nbsp;&nbsp;<input type="submit" name="Submit"  class="btn btn-primary btn-sm" value="Save" />&nbsp;<input type="reset" class="btn btn-primary btn-sm" OnClick="return CancelOrg();" value="Cancel" />
								</div>
							</div>
							<?php 
							}
							?>
						</div>
					</form><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
				</div>
				<?php 
					include(dirname ( __FILE__ )."/../../lib/footer.php");
				?>
			</div>
		</div>	
	</body>
	<script type="text/javascript">
			jQuery.validator.addMethod("logo_image_dim", function(val, elem) {

				if(image_width == <?php echo(CConfig::OL_WIDTH);?> && image_height == <?php echo(CConfig::OL_HEIGHT);?>)
				{
					return true;
				}
				return false;
		    }, 'Image dimensions are not proper!');

			jQuery.validator.addMethod("logo_image_size", function(val, elem) {
				if(image_size > 1048576)
				{
					return false;
				}
				return true;
		    }, 'Image file should not exceed 1MB size limit!');

			jQuery.validator.addMethod("complete_url", function(val, elem) {
			    }, '<span style="color:red">You must enter a valid URL</span>');

			jQuery.validator.addMethod("complete_url", function(val, elem) {
			    // if no url, don't do anything
			    if (val.length == 0) { return true; }
			 
			    // if user has not entered http:// https:// or ftp:// assume they mean http://
			    if(!/^(https?|ftp):\/\//i.test(val)) {
			        val = 'http://'+val; // set both the value
			        $(elem).val(val); // also update the form element
			    }
			    // now check if valid url
			    // http://docs.jquery.com/Plugins/Validation/Methods/url
			    return /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&amp;'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&amp;'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&amp;'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&amp;'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&amp;'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(val);
			});
			
			var bLoginNameExist = false;
			function OnLoginNameChange(obj)
			{
				$("#tp_exist").hide();
				$("#tp_checking").show();
				
				$.getJSON("ajax/ajax_check_login_name.php?login_name="+obj.value, function(data) {
					$("#tp_checking").hide();
					
					if(data['present'] == 1)
					{
						$("#tp_exist").show();
						bLoginNameExist = true;
					}
					else
					{
						$("#tp_exist").hide();
						bLoginNameExist = false;
					}
				});
			}

			var _URL = window.URL;
			var image_width  = 0;
			var image_height = 0;
			var image_size   = 0;
			$("#ORGLOGOIMG").change(function (e) {
			    var file, img;
			    if ((file = this.files[0])) {
			        img = new Image();
			        img.onload = function () {
			        	//this will give you image width and height and you can easily validate here....
			        	image_width = this.width;
			        	image_height = this.height;
			        };
			        img.src = _URL.createObjectURL(file);
			        image_size	 = file.size;
			    }
			});
			
			jQuery.validator.addMethod("LoginNameExists", function(value, element) {
					return (!bLoginNameExist);
				}, "Login Name Already Exists !");
			
			$(document).ready(function() {
				$('#acnt_dtls').validate({
					errorPlacement: function(error, element) {			
						//$('#acadamic_details div.reg-error').append(error);
						if(element.is("#ORGLOGOIMG"))
						{
							error.appendTo('#ORGLOGOIMG_ERR');
						}
						else
						{
							$(error).insertAfter(element);
						}
					}, rules: {
						'ORG':		  {required:true, minlength:4},
					    'ORGURL':	  "complete_url",
					    'LOGONAME':   {maxlength:30},
					    'PUNCHLINE':  {maxlength:60},
					    'LOGINNAME':  {required: true, 'LoginNameExists': true, maxlength:15},
					    //'ORGLOGOIMG': {accept: "png|jpe?g|gif", 'logo_image_size' : true, 'logo_image_dim' : true}   
					}, messages: {
						'ORG':		  {required:  '<span style="color:red">Please enter valid organization name!</span>', minlength: '<span style="color:red">Orgnazation name length should be minimum 4 letters!</span>' },
						'LOGONAME':	  { maxlength: '<span style="color:red">Logo name length should be maximum 30 letters!</span>' },
						'PUNCHLINE':  { maxlength: '<span style="color:red">Punch line length should be maximum 60 letters!</span>' },
						'LOGINNAME':  {required:  '<span style="color:red">Please enter valid login name!</span>', 'LoginNameExists': '<span style="color:red">Login name already exists!</span>', maxlength: '<span style="color:red">Login Name length should be maximum 15 letters!</span>' },
						//'ORGLOGOIMG': {accept: 'Please upload only png, jpg or gif image only!'}
					},submitHandler: function(form) {
						form.submit();
					}
				});
			});	
	</script>
</html>