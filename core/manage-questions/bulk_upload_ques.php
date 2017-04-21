<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	include_once("../../lib/session_manager.php");
	include_once('../../database/mcat_db.php');
	include_once(dirname(__FILE__)."/../../lib/include_js_css.php");
	include_once(dirname(__FILE__)."/../../lib/site_config.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$objDB = new CMcatDB();
	
	$user_type = CSessionManager::Get(CSessionManager::INT_USER_TYPE);
	
	$objIncludeJsCSS = new IncludeJSCSS();
	
	$menu_id = CSiteConfig::UAMM_MANAGE_QUESTIONS;
	$page_id = CSiteConfig::UAP_BULK_UPLOAD_EXCEL;
?>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="Generator" content="Mastishka Intellisys Private Limited">
<meta name="Author" content="Mastishka Intellisys Private Limited">
<meta name="Keywords" content="">
<meta name="Description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo(CConfig::SNC_SITE_NAME);?>: Bulk Upload Questions</title>
<?php 
$objIncludeJsCSS->CommonIncludeCSS("../../");
$objIncludeJsCSS->IncludeIconFontCSS("../../");
$objIncludeJsCSS->CommonIncludeJS("../../");
$objIncludeJsCSS->IncludeJqueryValidateMinJS("../../");
$objIncludeJsCSS->IncludeBootStrap3TypeHeadMinJS("../../");
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
		<div class="col-lg-9 col-md-9 col-sm-9" style="border-left: 1px solid #ddd; border-top: 1px solid #ddd;">
			<br />
			<form style="margin: 30px;" class="form-horizontal" action="ajax/ajax_ques_excel.php" method="post" enctype="multipart/form-data" name="upld_ques_exl_form" id="upld_ques_exl_form" target="upload_status">
				<?php 
				if($user_type == CConfig::UT_SUPER_ADMIN)
				{
				?>
				<label><b>Emotional Questions:</b></label>
				<div class="form-group">
				    <div class="col-lg-1 col-md-1 col-sm-1">
				       	<div class="radio">
				       		<label>
					            <input type="radio" id="eq_yes" value='yes' name="eq_choice" onchange="OnEQChoiceChange();"> Yes
				       		</label>
				       	</div>
				       </div>	
				       <div class="col-lg-1 col-md-1 col-sm-1">
				        <div class="radio">
				          <label>
				            <input type="radio" id="eq_no" value='no' name="eq_choice" onchange="OnEQChoiceChange();" checked='checked'> No
				          </label>
				        </div>
				     </div>
				</div>
				
				<div id='test_config_div'>
					<label><b>Test name:</b></label>
					<div class="form-group">
					    <div class="col-lg-3 col-md-3 col-sm-3">
					  		<input class="form-control input-sm" type="text" id="test_name" onkeyup="OnTestNameChange(this);" name="test_name" value="<?php echo(uniqid("eza-"));?>">
						</div>
						<div class="col-sm-4 col-md-4 col-lg-4" style="padding-left: 0px;">
							<span id="t_checking" style="display: none;">&nbsp;<img src="../../images/updating.gif" width="12" height="12" /> Checking</span>
							<span id="t_exist" style="color: red;display: none;">&nbsp;Name already exists!</span>
						</div>
					</div>
					<label><b>Test Duration:</b></label>
					<div class="form-group">
					    <div class="col-lg-3 col-md-3 col-sm-3">
					  		<input class="form-control input-sm" type="text" id="duration" name="duration">
						</div>
					</div>
				</div>
				<?php 
				}
				?>
				
				<label for="ques_type">Question Type :</label>
				<div class="form-group">
					<div class="col-lg-2 col-md-2 col-sm-2">
					   	<select class="form-control input-sm" id="ques_type" name="ques_type">
							<option value='<?php echo(CConfig::QT_NORMAL);?>'>Normal</option>
							<option value='<?php echo(CConfig::QT_READ_COMP);?>'>Reading Comprehension</option>
							<option value='<?php echo(CConfig::QT_DIRECTIONS);?>'>Directions</option>
						</select>
					</div>
				</div><br />
				
				<label><b>Question(Excel) file has link to images:</b></label>
				<div class="form-group">
				    <div class="col-lg-1 col-md-1 col-sm-1">
				       	<div class="radio">
				       		<label>
					            <input type="radio" id="yes_link_image" value='yes' name="link_to_image" onchange="OnLinkOptionChange();"> Yes
				       		</label>
				       	</div>
				       </div>	
				       <div class="col-lg-1 col-md-1 col-sm-1">
				        <div class="radio">
				          <label>
				            <input type="radio" id="no_link_image" value='no' name="link_to_image" onchange="OnLinkOptionChange();" checked='checked'> No
				          </label>
				        </div>
				     </div>
				</div>
				
				<label><b>Select Question File(xls|xlsx):</b></label>
				<div class="form-group">
					<div class="col-lg-4 col-md-4 col-sm-4">
						<div class="metro">
							<div class="input-control file">
							    <input name="csv" type="file" />
							    <button class="btn-file"></button>
						    </div>
						</div>
					</div>
				</div>
				
				<div id="zip_img_id" style="display:none">
					<label><b>Select Image File(zip format):</b></label>
					<div class="form-group">
						<div class="col-lg-4 col-md-4 col-sm-4">
							<div class="metro">
								<div class="input-control file">
								    <input name="zip" type="file" />
								    <button class="btn-file"></button>
							    </div>
							</div>
							(This file should contain images which are linked to question file)
						</div>
					</div>
				</div>
				
				<?php 
				if($user_type == CConfig::UT_SUPER_ADMIN)
				{
				?>
				<div id='range_analysis_div'>
					<label><b>Select Range Analysis File(xls|xlsx):</b></label>
					<div class="form-group">
						<div class="col-lg-4 col-md-4 col-sm-4">
							<div class="metro">
								<div class="input-control file">
								    <input name="range_analysis_csv" type="file" />
								    <button class="btn-file"></button>
							    </div>
							</div>
						</div>
					</div>
				</div>
				<?php 
				}
				?>
				<br />
				
				<label><b>Tag Question Set(Optional):</b></label>
				<div class="form-group">
				    <div class="col-lg-4 col-md-4 col-sm-4">
				  		<input class="form-control input-sm" data-provide="typeahead"  type="text" onkeypress="GetHints();" id="ques_tag" name="ques_tag">
					</div>
				</div>
				
				<div class="form-group">
			      <div class="col-lg-4 col-md-4 col-sm-4">
			        <button id='submit_button' type="submit" class="btn btn-primary">Submit</button>
			      </div>
			    </div><br />
			    
			    <div class="row fluid">
				    <div class="col-lg-6 col-md-6 col-sm-6 upload_error">
					</div>
				</div>
				
				<br/><a href="../download/sample_questions.xls" class="btn btn-info btn-xs"><i class="icon-download"></i> Download</a> sample questions file.
				<br/><br/><hr/>
				
				<iframe name="upload_status" width="100%" height="400" src="" frameborder=0 style="border:1px solid #aaa;"></iframe>
			</form>
			<?php
			include_once (dirname ( __FILE__ ) . "/../../lib/footer.php");
			?>
		</div>
	</div>
	<script type="text/javascript">

		function GetHints()
		{
			$('#ques_tag').typeahead('destroy');
			$.getJSON("../ajax/ajax_get_ques_tags.php",{term: encodeURIComponent($("#ques_tag").val())}, function(data){
				$("#ques_tag").typeahead({ source:data });
			});
		}
		
		function OnLinkOptionChange()
		{
			var val = $("input[name=link_to_image]:checked").val();
	
			if(val == "yes")
			{
				$("#zip_img_id").show();
			}
			else
			{
				$("#zip_img_id").hide();
			}
		}

		<?php 
		if($user_type == CConfig::UT_SUPER_ADMIN)
		{
		?>
		function OnEQChoiceChange()
		{
			var val = $("input[name=eq_choice]:checked").val();

			if(val == "yes")
			{
				$("#test_config_div").show();
				$("#range_analysis_div").show();	
				$("#ques_type").val("<?php echo(CConfig::QT_DIRECTIONS);?>");
			}
			else
			{
				$("#test_config_div").hide();
				$("#range_analysis_div").hide();
				$("#ques_type").val("<?php echo(CConfig::QT_NORMAL);?>");
			}
		}

		var bTestExist = false;
		function OnTestNameChange(obj)
		{
			$("#t_exist").hide();
			$("#t_checking").show();
			
			$.getJSON("../ajax/ajax_check_test_name.php?test_name="+obj.value, function(data) {
				$("#t_checking").hide();
				
				if(data['present'] == 1)
				{
					$("#t_exist").show();
					bTestExist = true;
				}
				else
				{
					$("#t_exist").hide();
					bTestExist = false;
				}
			});
		}
		<?php 
		}
		?>

		$(document).ready(function () {
			<?php 
			if($user_type == CConfig::UT_SUPER_ADMIN)
			{
			?>
			OnEQChoiceChange();

			jQuery.validator.addMethod("TestNameExists", function(value, element) {
				return (!bTestExist);
			}, "Test Name Already Exists !");

			jQuery.validator.addMethod("NegetiveNumber", function(value, element) {
				return Number(value) >= 0;
			}, "Please enter only positive number. !");
			
			$('#upld_ques_exl_form').validate({
				errorPlacement: function(error, element) {
					//$(error).insertAfter(element);
					$('#upld_ques_exl_form div.upload_error').append(error);
				}, rules: {
					'test_name':			{required: true, 'TestNameExists': true},
					'duration':				{required:true, digits: true, 'NegetiveNumber' : true},
					'range_analysis_csv':	{required: true, accept: "xls|xlsx"},
					'csv':					{required: true, accept: "xls|xlsx"},
					'zip':					{required: true, accept: "zip"},
					'ques_tag':				{maxlength: 60},
				}, messages: {
					'test_name':			{required: "<span style='color:red;font-weight:bold;'>Please provide a name for the test!</span>"},
					'duration':				{required: "<span style='color:red;font-weight:bold;'>Please provide test duration in minutes!</span>", digits: "<span style='color:red;font-weight:bold;'>Please only enter digits for test duration!</span>"},
					'range_analysis_csv':	{required: "<span style='color:red;font-weight:bold;'>Please select a &lsquo;xls or xlsx&rsquo; (MS Excel) Range Analysis file to upload!</span>", accept: "<span style='color:red;font-weight:bold;'>Please select a valid &lsquo;xls or xlsx&rsquo; (MS Excel) Range Analysis file to upload!</span>"},
					'csv':					{required: "<span style='color:red;font-weight:bold;'>Please select a &lsquo;xls or xlsx&rsquo; (MS Excel) Question file to upload!</span>", accept: "<span style='color:red;font-weight:bold;'>Please select a valid &lsquo;xls or xlsx&rsquo; (MS Excel) Question file to upload!</span>"},
					'zip':					{required: "<span style='color:red;font-weight:bold;'>Please select a &lsquo;zip&rsquo; file to upload!</span>", accept: "<span style='color:red;font-weight:bold;'>Please select a valid &lsquo;zip&rsquo; file to upload!</span>"},
					'ques_tag':				{maxlength: "<span style='color:red;font-weight:bold;'>Please do not exceed tag limit of 60 characters! </span>"}
				}
			});
			<?php 
			}
			else 
			{
			?>
			$('#upld_ques_exl_form').validate({
				errorPlacement: function(error, element) {
					//$(error).insertAfter(element);
					$('#upld_ques_exl_form div.upload_error').append(error);
				}, rules: {
					'csv':			{required: true, accept: "xls|xlsx"},
					'zip':			{required: true, accept: "zip"},
					'ques_tag':		{maxlength: 60},
				}, messages: {
					'csv':			{required: "<span style='color:red;font-weight:bold;'>Please select a &lsquo;xls or xlsx&rsquo; (MS Excel) file to upload!</span>", accept: "<span style='color:red;font-weight:bold;'>Please select a valid &lsquo;xls or xlsx&rsquo; (MS Excel) file to upload!</span>"},
					'zip':			{required: "<span style='color:red;font-weight:bold;'>Please select a &lsquo;zip&rsquo; file to upload!</span>", accept: "<span style='color:red;font-weight:bold;'>Please select a valid &lsquo;zip&rsquo; file to upload!</span>"},
					'ques_tag':		{maxlength: "<span style='color:red;font-weight:bold;'>Please do not exceed tag limit of 60 characters! </span>"}
				}
			});
			<?php 
			}
			?>
		});
	</script>
</body>
</html>