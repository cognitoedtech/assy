<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
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
	$user_id   = CSessionManager::Get(CSessionManager::STR_USER_ID);
	
	$parsAry = parse_url(CUtils::curPageURL());
	$qry = explode("=", $parsAry["query"]);
	
	$sQuestion = "";
	if($qry[0] == "ques")
	{
		echo "<script>save_success = 1; </script>";
	}
	else 
	{
		echo "<script>save_success = 0; </script>";
	}
	
	$objIncludeJsCSS = new IncludeJSCSS();
	
	$menu_id = CSiteConfig::UAMM_MANAGE_QUESTIONS;
	$page_id = CSiteConfig::UAP_SUBMIT_QUESTION;
?>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="Generator" content="Mastishka Intellisys Private Limited">
<meta name="Author" content="Mastishka Intellisys Private Limited">
<meta name="Keywords" content="">
<meta name="Description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo(CConfig::SNC_SITE_NAME);?>: Submit Question</title>
<?php 
$objIncludeJsCSS->CommonIncludeCSS("../../");
$objIncludeJsCSS->IncludeMipcatCSS("../../");
$objIncludeJsCSS->IncludeIconFontCSS("../../");
$objIncludeJsCSS->IncludeBootStrapFileUploadCSS("../../");
$objIncludeJsCSS->IncludeFuelUXCSS ( "../../" );
$objIncludeJsCSS->CommonIncludeJS("../../");
$objIncludeJsCSS->IncludeJqueryValidateMinJS("../../");
$objIncludeJsCSS->IncludeMetroNotificationJS("../../");
$objIncludeJsCSS->IncludeBootStrapFileUploadMinJS("../../");
$objIncludeJsCSS->IncludeBootStrap3TypeHeadMinJS("../../");
?>

<style type="text/css">
	.modal1 {
		display:    none;
		position:   fixed;
		z-index:    1000;
		top:        50%;
		left:       60%;
		height:     100%;
		width:      100%;
	}
</style>
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
			<div class="fuelux modal1">
				<div class="preloader"><i></i><i></i><i></i><i></i></div>
			</div>
			<br />
			<?php
				$objUM = new CUserManager() ;
				if(CSessionManager::IsError())
				{
					CSessionManager::SetError(false) ;
			?>
			<div class='row fluid'>
				<div class="drop-shadow raised" id="MSG">
					<fieldset>
					<legend>Error Message</legend>	
						<?php 
							echo("<p>Error during question upload : ".CSessionManager::GetErrorMsg()."</p>");
						?>
					<INPUT TYPE="button" NAME="HIDE" class='btn btn-success btn-sm' value="Hide" onClick="OnHide();"/>
					</fieldset>
				</div>
			</div><br />
			<?php
				}
			?>
			<form class="form-horizontal" action="post_get/form_ques_upload.php" method="post" enctype="multipart/form-data" name="upld_ques_exl_form" id="upld_ques_exl_form">
				<div class="form-group">
					<div class="col-lg-2 col-md-2 col-sm-2">
				    	<label class="control-label">Question Type :</label>
				    </div>
			      	<div class="col-lg-1 col-md-1 col-sm-1">
			        	<div class="radio">
			          		<label>
					            <input type="radio" id="normal" value='<?php echo(CConfig::QT_NORMAL); ?>' name="ques_type" onchange="OnQuesTypeChange();" checked> Normal
			          		</label>
			        	</div>
			        </div>
			        <div class="col-lg-3 col-md-3 col-sm-3" style="width: 23%">
				        <div class="radio">
				          <label>
				            <input type="radio" id="rc" value='<?php echo(CConfig::QT_READ_COMP); ?>' name="ques_type" onchange="OnQuesTypeChange();"> Reading Comprehension(RC)
				          </label>
				        </div>
				     </div>
				     <div class="col-lg-2 col-md-2 col-sm-2">
				        <div class="radio">
				          <label>
				            <input type="radio" id="directions" value='<?php echo(CConfig::QT_DIRECTIONS); ?>' name="ques_type" onchange="OnQuesTypeChange();"> Directions
				          </label>
				        </div>
				     </div>
			     </div>
			     <div id="rc_dir_existing" style="display:none;">
			     	<div class="form-group">
			     		<div class="col-lg-2 col-md-2 col-sm-2">
				    		<label class="control-label">Use Existing Para:</label>
				    	</div>
				    	<div class="col-lg-1 col-md-1 col-sm-1">
				        	<div class="radio">
				          		<label>
						            <input type="radio" id="rc_dir_existing_yes" value='yes' name="rc_dir_existing_choice" onchange="OnExistingParaChoiceChange();"> Yes
				          		</label>
				        	</div>
				        </div>	
				        <div class="col-lg-1 col-md-1 col-sm-1">
					        <div class="radio">
					          <label>
					            <input type="radio" id="rc_dir_existing_no" value='no' name="rc_dir_existing_choice" onchange="OnExistingParaChoiceChange();" checked='checked'> No
					          </label>
					        </div>
					     </div>
				     </div>
					<div id="existing_para_id" style="display:none;">
						<div class="form-group">
							<div class="col-lg-2 col-md-2 col-sm-2">
				    			<label for="existing_para" class="control-label">Select Para :</label>
				    		</div>
							<div class="col-lg-4 col-md-4 col-sm-4">
								<select class="form-control input-sm" id="existing_para" name="existing_para" onkeydown="OnExitingParaIdChange();" onkeyup="OnExitingParaIdChange();" onchange="OnExitingParaIdChange();">
								</select>
							</div>
						</div>
					</div>
					<fieldset id="existing_para_desc_field" style="display:none;">
						<legend><h3>Para Description</h3></legend>
						<div id="existing_para_desc_id">	
						</div>
						<hr />
					</fieldset>
				</div>
				<div id="rc_dir_new">
					<div class="form-group">
						<div class="col-lg-2 col-md-2 col-sm-2">
					    	<label for="language" class="control-label">Select Language :</label>
					    </div>
					    <div class="col-lg-2 col-md-2 col-sm-2">
					    	<select class="form-control input-sm" id="language" name="language">
								<?php 
								for($i = 0; $i < count(CConfig::$TEST_LANGUAGES); $i++) 
								{
								?>
								<option value='<?php echo(CConfig::$TEST_LANGUAGES[$i]); ?>' <?php echo((CConfig::$TEST_LANGUAGES[$i] == "english")?"selected":"");?>> <?php echo(ucwords(CConfig::$TEST_LANGUAGES[$i])); ?> </option>
								<?php 
								}
								?>
							</select>
					    </div>
				    </div>
				    <div class="form-group">
						<div class="col-lg-2 col-md-2 col-sm-2">
					    	<label for="subject" class="control-label">Subject :</label>
					    </div>
					    <div class="col-lg-3 col-md-3 col-sm-3">
					    	<input data-provide="typeahead"  type="text" onkeypress="GetHints();" id="subject" name="subject" class="form-control input-sm" />
					    </div>
				    </div>
				    <div class="form-group">
						<div class="col-lg-2 col-md-2 col-sm-2">
					    	<label id="topic_label" for="topic" class="control-label">Topic :</label>
					    </div>
					    <div class="col-lg-3 col-md-3 col-sm-3">
					    	<input type="text" id="topic" name="topic" class="form-control input-sm" />
					    	<span id="topic_selection_msg">(Topic should be different from RC or directions para title already submitted.)</span>
					    </div>
				    </div>
				    <div id="rc_dir_id" style="display:none">
				    	<div class="form-group">
				     		<div class="col-lg-2 col-md-2 col-sm-2">
					    		<label class="control-label">Para Type :</label>
					    	</div>
					    	<div class="col-lg-1 col-md-1 col-sm-1">
					        	<div class="radio">
					          		<label>
							           <input type="radio" id="text" value="text" name="rc_dir_type" checked='checked' onchange="OnRCDirTypeChange();"> Text
					          		</label>
					        	</div>
					        </div>	
					        <div class="col-lg-1 col-md-1 col-sm-1">
						        <div class="radio">
						          <label>
						            <input type="radio" id="image" value="image" name="rc_dir_type" onchange="OnRCDirTypeChange();"> Image
						          </label>
						        </div>
							</div>
						</div>
						<div class="form-group" id="para_text">
							<div class="col-lg-2 col-md-2 col-sm-2">
						    	<label class="control-label"></label>
						    </div>
						    <div class="col-lg-4 col-md-4 col-sm-4">
						  		<textarea class="form-control" rows="3" name="para_text" placeholder="Enter Text Here"></textarea>
							</div>
						</div>
						<div class="form-group" id="para_img_id" style="display:none;">
							<div class="col-lg-2 col-md-2 col-sm-2">
							   	<label class="control-label"></label>
							</div>
							<div class="col-lg-7 col-md-7 col-sm-7">
						    	<div class="fileupload fileupload-new" data-provides="fileupload">
									<div class="fileupload-preview thumbnail" style="width: 90%; height: 300px;"></div>
									<div>
										<span class="btn btn-sm btn-success btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span><input type="file" name="para_img" /></span>
										<a href="#" class="btn btn-sm btn-success fileupload-exists" data-dismiss="fileupload">Remove</a>
									</div>
								</div><br />
								<div id="para_img_error">
								</div>
							</div>
						</div>
				    </div>
				</div>
				<div class="form-group">
				   	<div class="col-lg-2 col-md-2 col-sm-2">
				    	<label class="control-label">Question Format :</label>
				    </div>
				    <div class="col-lg-1 col-md-1 col-sm-1">
				        <div class="radio">
				       		<label>
					           <input type="radio" value="text" name="question_choice" onchange="OnFormateChange(this);" checked> Text
				        	</label>
				    	</div>
				    </div>	
					<div class="col-lg-1 col-md-1 col-sm-1">
					    <div class="radio">
					    	<label>
					        	<input type="radio" value="image" name="question_choice" onchange="OnFormateChange(this);"> Image
					    	</label>
						</div>
					</div>
				</div>
				<div class="form-group" id="question_choice_text">
					<div class="col-lg-2 col-md-2 col-sm-2">
				    	<label class="control-label"></label>
				    </div>
				    <div class="col-lg-4 col-md-4 col-sm-4">
				  		<textarea class="form-control" rows="3" name="question_choice_text" placeholder="Enter Text Here"></textarea>
					</div>
				</div>
				<div class="form-group" id="question_choice_image" style="display:none;">
					<div class="col-lg-2 col-md-2 col-sm-2">
					   	<label class="control-label"></label>
					</div>
					<div class="col-lg-7 col-md-7 col-sm-7">
				    	<div class="fileupload fileupload-new" data-provides="fileupload">
							<div class="fileupload-preview thumbnail" style="width: 90%; height: 300px;"></div>
							<div>
								<span class="btn btn-sm btn-success btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span><input type="file" name="question_choice_img" /></span>
								<a href="#" class="btn btn-sm btn-success fileupload-exists" data-dismiss="fileupload">Remove</a>
							</div>
						</div><br />
						<div id="question_choice_img_error">
						</div>
					</div>
				</div>
				
				<div class="form-group">
				   	<div class="col-lg-2 col-md-2 col-sm-2">
				    	<label class="control-label">Option 1 :</label>
				    </div>
				    <div class="col-lg-1 col-md-1 col-sm-1">
				        <div class="radio">
				       		<label>
					           <input type="radio" value="text" name="option1_choice" onchange="OnFormateChange(this);" checked> Text
				        	</label>
				    	</div>
				    </div>	
					<div class="col-lg-1 col-md-1 col-sm-1">
					    <div class="radio">
					    	<label>
					        	<input type="radio" value="image" name="option1_choice" onchange="OnFormateChange(this);"> Image
					    	</label>
						</div>
					</div>
				</div>
				<div class="form-group" id="option1_choice_text">
					<div class="col-lg-2 col-md-2 col-sm-2">
				    	<label class="control-label"></label>
				    </div>
				    <div class="col-lg-4 col-md-4 col-sm-4">
				  		<input class="form-control input-sm"  type="text" name="option1_choice_text">
					</div>
				</div>
				<div class="form-group" id="option1_choice_image" style="display:none;">
					<div class="col-lg-2 col-md-2 col-sm-2">
					   	<label class="control-label"></label>
					</div>
					<div class="col-lg-7 col-md-7 col-sm-7">
				    	<div class="fileupload fileupload-new" data-provides="fileupload">
							<div class="fileupload-preview thumbnail" style="width: 90%; height: 300px;"></div>
							<div>
								<span class="btn btn-sm btn-success btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span><input type="file" name="option1_choice_img" /></span>
								<a href="#" class="btn btn-sm btn-success fileupload-exists" data-dismiss="fileupload">Remove</a>
							</div>
						</div><br />
						<div id="option1_choice_img_error">
						</div>
					</div>
				</div>
				
				<div class="form-group">
				   	<div class="col-lg-2 col-md-2 col-sm-2">
				    	<label class="control-label">Option 2 :</label>
				    </div>
				    <div class="col-lg-1 col-md-1 col-sm-1">
				        <div class="radio">
				       		<label>
					           <input type="radio" value="text" name="option2_choice" onchange="OnFormateChange(this);" checked> Text
				        	</label>
				    	</div>
				    </div>	
					<div class="col-lg-1 col-md-1 col-sm-1">
					    <div class="radio">
					    	<label>
					        	<input type="radio" value="image" name="option2_choice" onchange="OnFormateChange(this);"> Image
					    	</label>
						</div>
					</div>
				</div>
				<div class="form-group" id="option2_choice_text">
					<div class="col-lg-2 col-md-2 col-sm-2">
				    	<label class="control-label"></label>
				    </div>
				    <div class="col-lg-4 col-md-4 col-sm-4">
				  		<input class="form-control input-sm"  type="text" name="option2_choice_text">
					</div>
				</div>
				<div class="form-group" id="option2_choice_image" style="display:none;">
					<div class="col-lg-2 col-md-2 col-sm-2">
					   	<label class="control-label"></label>
					</div>
					<div class="col-lg-7 col-md-7 col-sm-7">
				    	<div class="fileupload fileupload-new" data-provides="fileupload">
							<div class="fileupload-preview thumbnail" style="width: 90%; height: 300px;"></div>
							<div>
								<span class="btn btn-sm btn-success btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span><input type="file" name="option2_choice_img" /></span>
								<a href="#" class="btn btn-sm btn-success fileupload-exists" data-dismiss="fileupload">Remove</a>
							</div>
						</div><br />
						<div id="option2_choice_img_error">
						</div>
					</div>
				</div>
				
				<div id="options_div">		
				</div>
				<input type="hidden" id="options_count" value="2" name="options_count">
				
				<div class="form-group">
			      <div class="col-lg-8 col-md-8 col-sm-8 col-lg-offset-2 col-md-offset-2 col-sm-offset-2">
			        <input class="btn btn-sm btn-info" id='add_option' onclick="AddOption();" type='button' value='Add Option'>
			        <input class="btn btn-sm btn-info" id='remove_option' onclick="RemoveOption();" type='button' value='Remove Option' disabled>
			      </div>
			    </div>
			    
			    <div class="form-group">
					<div class="col-lg-2 col-md-2 col-sm-2">
						<label for="answers" class="control-label">Correct Options :</label>
					</div>
					<div class="col-lg-2 col-md-2 col-sm-2">
						<select class="form-control input-sm" id="answers" name="answers[]" multiple>
							<option value='option1'>Option 1</option>
							<option value='option2'>Option 2</option>
						</select>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-lg-2 col-md-2 col-sm-2">
						<label for="difficulty" class="control-label">Difficulty :</label>
					</div>
					<div class="col-lg-2 col-md-2 col-sm-2">
						<select class="form-control input-sm" id="difficulty" name="difficulty">
							<option value="<?php echo(CConfig::DIFF_LVL_EASY);?>">Easy</option>
							<option value="<?php echo(CConfig::DIFF_LVL_MODERATE);?>">Moderate</option>
							<option value="<?php echo(CConfig::DIFF_LVL_HARD);?>">Hard</option>
						</select>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-lg-2 col-md-2 col-sm-2">
				    	<label class="control-label">Explanation :</label>
				    </div>
				    <div class="col-lg-1 col-md-1 col-sm-1">
				        <div class="radio">
				       		<label>
					           <input type="radio" value="text" name="explanation" onchange="OnFormateChange(this);" checked> Text
				        	</label>
				    	</div>
				    </div>	
					<div class="col-lg-1 col-md-1 col-sm-1">
					    <div class="radio">
					    	<label>
					        	<input type="radio" value="image" name="explanation" onchange="OnFormateChange(this);"> Image
					    	</label>
						</div>
					</div>
				</div>
				<div class="form-group" id="explanation_text">
					<div class="col-lg-2 col-md-2 col-sm-2">
				    	<label class="control-label"></label>
				    </div>
				    <div class="col-lg-4 col-md-4 col-sm-4">
				  		<textarea class="form-control" rows="3" name="explanation_text" placeholder="Enter Text Here"></textarea>
					</div>
				</div>
				<div class="form-group" id="explanation_image" style="display:none;">
					<div class="col-lg-2 col-md-2 col-sm-2">
					   	<label class="control-label"></label>
					</div>
					<div class="col-lg-7 col-md-7 col-sm-7">
				    	<div class="fileupload fileupload-new" data-provides="fileupload">
							<div class="fileupload-preview thumbnail" style="width: 90%; height: 300px;"></div>
							<div>
								<span class="btn btn-sm btn-success btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span><input type="file" name="explanation_img" /></span>
								<a href="#" class="btn btn-sm btn-success fileupload-exists" data-dismiss="fileupload">Remove</a>
							</div>
						</div>
					</div>
				</div>
				
				<div class="form-group" id="tag_text">
					<div class="col-lg-2 col-md-2 col-sm-2">
						<label><b>Tag Question Set(Optional):</b></label>
					</div>
					<div class="form-group">
					    <div class="col-lg-4 col-md-4 col-sm-4">
					  		<input class="form-control input-sm" data-provide="typeahead"  type="text" onkeypress="GetTagHints();" id="ques_tag" name="ques_tag">
						</div>
					</div>
				</div>
				
				<div class="form-group">
			      <div class="col-lg-6 col-md-6 col-sm-6 col-lg-offset-2 col-md-offset-2 col-sm-offset-2">
			        <button id='submit_button' type="submit" class="btn btn-primary">Submit</button>
			      </div>
			    </div>
			</form>
			
			<?php
			include_once (dirname ( __FILE__ ) . "/../../lib/footer.php");
			?>
		</div>
	</div>
	<script type="text/javascript">
		var optCounter  = 3;

		function GetTagHints()
		{
			$('#ques_tag').typeahead('destroy');
			$.getJSON("../ajax/ajax_get_ques_tags.php",{term: encodeURIComponent($("#ques_tag").val())}, function(data){
				$("#ques_tag").typeahead({ source:data });
			});
		}
		
		function GetHints()
		{
			$('#subject').typeahead('destroy');
			$.getJSON("../ajax/ajax_get_subjects.php",{term: encodeURIComponent($("#subject").val())}, function(data){
				$("#subject").typeahead({ source:data });
			});
		}

		$.getJSON("../ajax/ajax_get_subjects.php",{term: encodeURIComponent($("#subject").val())}, function(data){
			$("#subject").typeahead({ source:data });
		});
		
		function OnQuesTypeChange()
        {
			$("#upld_ques_exl_form").validate().resetForm();
        	var val = $("input[name=ques_type]:checked").val();
        	
        	if(val == "<?php echo(CConfig::QT_READ_COMP); ?>") 
        	{
        		
	    	    $("#para_img_id").hide();
	    	    $("#topic_label").text("Title :");
	    	    $("#topic_selection_msg").text("(Please select unique title.)");
        		$("#rc_dir_new").hide();
        		$("#rc_dir_id").show();
        		$("#dir_para_id").hide();
				$("#rc_para_id").show();
				$("#image").removeAttr("checked");
				$("#text").attr("checked","checked");
				$("#para_text").show();
				$("#rc_dir_existing").show();
				$("input:radio[name='rc_dir_existing_choice'][value ='no']").prop('checked', true);
				OnExistingParaChoiceChange();
				
        	}
        	else if(val == "<?php echo(CConfig::QT_DIRECTIONS); ?>")
        	{
        		$("#para_img_id").hide();
        		$("#rc_para_id").hide();
        		$("#topic_label").text("Title :");
        		$("#topic_selection_msg").text("(Please select unique title.)");
        		$("#rc_dir_new").hide();
        		$("#rc_dir_id").show();
        		$("#dir_para_id").show();
				$("#image").removeAttr("checked");
				$("#text").attr("checked","checked");
				$("#para_text").show();
				$("#rc_dir_existing").show();
				$("input:radio[name='rc_dir_existing_choice'][value ='no']").prop('checked', true);
			    OnExistingParaChoiceChange();					
        	}
        	else
        	{
        		$("#rc_para_id").hide();
        		$("#dir_para_id").hide();
				$("#rc_dir_id").hide();
				$("#rc_dir_questions").hide();
				$("#rc_dir_existing").hide();
				$("#topic_label").text("Topic :");
				$("#topic_selection_msg").text("(Topic should be different from RC or directions para title already submitted.)");
				$("#rc_dir_new").show();
				$("#topic_id").show();
				$("#submit_button").removeAttr("disabled");
	        }
		}

		function OnExistingParaChoiceChange()
	    {
			$("#upld_ques_exl_form").validate().resetForm();
	    	var val 		= $("input[name=rc_dir_existing_choice]:checked").val();
	    	var quesTypeVal = $("input[name=ques_type]:checked").val();
	    	if(val == "yes")
	    	{
		    	$("#rc_dir_new").hide();

		    	$(".modal1").show();
		    	$('#existing_para').load('ajax/ajax_get_para_title.php',{ques_type:quesTypeVal}, function(data){
        			OnExitingParaIdChange();
        			if(data == null || data == "")
        			{
	        			$("#submit_button").attr("disabled","disabled");
	        		}
        			$(".modal1").hide();
		        });
		    	$("#existing_para_id").show();
		    	$("#existing_para_desc_field").show();
	    	}
	    	else
	    	{
	    		$("#existing_para_id").hide();
	    		$("#existing_para_desc_field").hide();
	    		$("#rc_dir_new").show();
	    		$("#submit_button").removeAttr("disabled");
		    }
	    }

		function OnExitingParaIdChange()
		{
			$("#upld_ques_exl_form").validate().resetForm();
			var rc_dir_para_id	= $('#existing_para option:selected').val();
			var para_ques_type = $("input[name=ques_type]:checked").val();
			
			//alert(rc_dir_para_id+" "+para_ques_type);
			$(".modal1").show();
			$('#existing_para_desc_id').load('ajax/ajax_get_para_desc.php',{para_id : rc_dir_para_id, ques_type : para_ques_type}, function(){
				$("#existing_para_desc_field").show();
				$(".modal1").hide();
			});
		}
		
		function OnFormateChange(obj)
	    {
			$("#upld_ques_exl_form").validate().resetForm();
	        var objName = obj.name;
	
	        if(obj.value == "text")
	        {
				$("#"+objName+"_image").hide();
				$("#"+objName+"_text").show();
		    }
	        else
	        {
	        	$("#"+objName+"_text").hide();
	        	$("#"+objName+"_image").show();
	        }
	    }

		function OnRCDirTypeChange()
		{
			$("#upld_ques_exl_form").validate().resetForm();
			var val = $("input[name=rc_dir_type]:checked").val();

			if(val == "text")
			{
				$("#para_img_id").hide();
				$("#para_text").show();
			}
			else
			{
				$("#para_text").hide();
				$("#para_img_id").show();
			}
		}

		function AddOption()
        {
			$("#upld_ques_exl_form").validate().resetForm();
			var sOpt = "<div id='option"+optCounter+"_div'>";
			sOpt += "<div class='form-group'>";
			sOpt += "<div class='col-lg-2 col-md-2 col-sm-2'><label class='control-label'>Option "+optCounter+" :</label></div>";
			sOpt += "<div class='col-lg-1 col-md-1 col-sm-1'><div class='radio'><label><input type='radio' value='text' name='option"+optCounter+"_choice' onchange='OnFormateChange(this);' checked> Text</label></div></div>";
			sOpt += "<div class='col-lg-1 col-md-1 col-sm-1'><div class='radio'><label><input type='radio' value='image' name='option"+optCounter+"_choice' onchange='OnFormateChange(this);'> Image</label></div></div>";
			sOpt += "</div>";

			sOpt += "<div class='form-group' id='option"+optCounter+"_choice_text'>";
			sOpt += "<div class='col-lg-2 col-md-2 col-sm-2'><label class='control-label'></label></div><div class='col-lg-4 col-md-4 col-sm-4'><input class='form-control input-sm' opt_count='"+optCounter+"' type='text' name='option"+optCounter+"_choice_text'></div>";
			sOpt += "</div>";

			sOpt += "<div class='form-group' id='option"+optCounter+"_choice_image' style='display:none;'>";
			sOpt += "<div class='col-lg-2 col-md-2 col-sm-2'><label class='control-label'></label></div>";
			sOpt += "<div class='col-lg-7 col-md-7 col-sm-7'><div class='fileupload fileupload-new' data-provides='fileupload'><div class='fileupload-preview thumbnail' style='width: 90%; height: 300px;'></div>";
			sOpt += "<div>";
			sOpt += "<span class='btn btn-sm btn-success btn-file'><span class='fileupload-new'>Select image</span><span class='fileupload-exists'>Change</span><input type='file' opt_count='"+optCounter+"' name='option"+optCounter+"_choice_img' /></span>";
			sOpt += "<a href='#' class='btn btn-sm btn-success fileupload-exists' data-dismiss='fileupload'>Remove</a>";
			sOpt += "</div>";
			sOpt += "</div>";
			sOpt += "<br /><div id='option"+optCounter+"_choice_img_error'></div>";
			sOpt += "</div>";
			sOpt += "</div>";

			sOpt += "</div>";
			
        	$("#options_div").append(sOpt);
        	$("#remove_option").removeAttr('disabled');
        	$("#options_count").val(optCounter+"");
        	$("#answers").append("<option value='option"+optCounter+"'>Option "+optCounter+"</option>");
        	optCounter++;

        	$('input[name$="_text"]').each(function(){
        		if(parseInt($(this).attr('opt_count')) >= 3)
        		{
					$(this).rules("add", {
						required: true,
						messages: {required: "<span style='color:red'>* Please enter Option "+$(this).attr('opt_count')+"</span>"}
					});
        		}
			});

        	$('input[name$="_img"]').each(function(){
        		if(parseInt($(this).attr('opt_count')) >= 3)
        		{
					$(this).rules("add", {
						required: true,
						messages: {required: "<span style='color:red'>* Please select Option "+$(this).attr('opt_count')+" image</span>"}
					});
        		}
			});
	    }

        function RemoveOption()
        {
        	$("#upld_ques_exl_form").validate().resetForm();
        	var numOfOptions = parseInt($("#options_count").val());

			$("select#answers option:last").remove();
	        $("#option"+(optCounter-1)+"_div").remove();
	        $("#options_count").val((optCounter-2) + "");
	        optCounter--;
	        if(optCounter == 3)
	        {
	        	$("#remove_option").attr('disabled','disabled');
	        }
	    }

        function OnHide()
    	{
    		$("#MSG").hide();
    	}

        $(document).ready(function() {

    		if(save_success == 1)
    		{
    			 var not = $.Notify({
    				 caption: "Question Upload",
    				 content: "Your question has been uploaded successfully!",
    				 style: {background: 'green', color: '#fff'}, 
    				 timeout: 5000
    				 });
    		}
    		
    		$("#upld_ques_exl_form").validate({
    			errorPlacement: function(error, element) {
			    	if($(element).attr("name").indexOf("_img") != -1)
			    	{
				    	$("#"+$(element).attr("name")+"_error").append(error);
				    }
			    	else
			    	{
				    	$(error).insertAfter(element);
				    }
		    	},
        		rules: {
        			subject: {
                		required:true,
            		},
            		topic: {
                		required:true,
            		},
            		para_text: {
                		required:true,
            		},
            		para_img: {
                		required:true,
            		},
            		question_choice_text: {
                		required:true,
            		},
            		question_choice_img: {
                		required:true,
            		},
            		option1_choice_text: {
                		required:true,
            		},
            		option1_choice_img: {
                		required:true,
            		},
            		option2_choice_text: {
                		required:true,
            		},
            		option2_choice_img: {
                		required:true,
            		},
            		'answers[]': {
            			required:true,
                	}
        		},
        		messages: {
        			subject: {	
        				required:	"<span style='color:red'>* Please enter the subject</span>"
            		},
            		topic: {	
        				required:	"<span style='color:red'>* Please enter the topic/title</span>"
            		},
            		para_text: {
            			required:	"<span style='color:red'>* Please enter the para description</span>"
            		},
            		para_img: {
            			required:	"<span style='color:red'>* Please select the para description image</span>"
            		},
            		question_choice_text: {
            			required:	"<span style='color:red'>* Please enter the quetsion</span>"
            		},
            		question_choice_img: {
            			required:	"<span style='color:red'>* Please select the question image</span>"
            		},
            		option1_choice_text: {
            			required:	"<span style='color:red'>* Please enter Option 1</span>"
            		},
            		option1_choice_img: {
            			required:	"<span style='color:red'>* Please select Option 1 image</span>"
            		},
            		option2_choice_text: {
            			required:	"<span style='color:red'>* Please enter Option 2</span>"
            		},
            		option2_choice_img: {
            			required:	"<span style='color:red'>* Please select Option 2 image</span>"
            		},
            		'answers[]': {
            			required:	"<span style='color:red'>* Please select the correct answers</span>"
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