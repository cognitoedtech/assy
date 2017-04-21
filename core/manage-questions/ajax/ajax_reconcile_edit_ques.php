<?php 
	include_once(dirname(__FILE__)."/../../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../../database/config.php");
	include_once(dirname(__FILE__)."/../../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/../../../lib/utils.php");
	
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$objDB = new CMcatDB();
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);

	if(isset($_POST['ques_id']))
	{
		$quesDisplayStyle = "";
		$quesDetails      = $objDB->GetQuestionDetails($_POST['ques_id']);
		$optDetails       = json_decode($quesDetails['options'], true);
		$ansAry           = array();
?>
		<div style='height: 330px;overflow-y: auto;overflow-x: hidden;margin:12px;padding:12px;'>
			<form class="form-horizontal" action="post_get/form_update_question.php" method="post" enctype="multipart/form-data" name="upld_ques_exl_form" id="upld_ques_exl_form">
				<label><b>Question: </b></label>
				<?php 
				if(CUtils::getMimeType($quesDetails['question']) != "application/octet-stream")
				{
					printf("<div id='question_image_div'>");
					printf("<img align='top' src='../../test/lib/print_image.php?qid=%s&opt=0'><br /><br />", $quesDetails['ques_id']);
					printf("<button type='button' class='btn btn-sm btn-success' id='question_edit_btn' onclick='EditImage(this);'>Replace Image</button>");
					printf("</div>");
					printf("<input type='hidden' name='ques_hidden' value='%s'/>", base64_encode($quesDetails['question']));
					printf("<input type='hidden' name='question_edit_choice' id='question_edit_choice' value='0'/>");
					printf("<div id='question_edit_div' style='display:none;'>");
				}
				?>
				
				<div class="form-group">
				    <div class="col-lg-2 col-md-2 col-sm-2">
				        <div class="radio">
				       		<label>
					           <input type="radio" value="text" name="question_choice" onchange="OnFormateChange(this);" checked/> Text
				        	</label>
				    	</div>
				    </div>	
					<div class="col-lg-2 col-md-2 col-sm-2">
					    <div class="radio">
					    	<label>
					        	<input type="radio" value="image" name="question_choice" onchange="OnFormateChange(this);" /> Image
					    	</label>
						</div>
					</div>
				</div>
				<div class="form-group" id="question_choice_text">
				    <div class="col-lg-9 col-md-9 col-sm-9">
				  		<textarea class="form-control" rows="6" name="question_choice_text" placeholder="Enter Text Here"><?php echo((CUtils::getMimeType($quesDetails['question']) != "application/octet-stream")?"":str_ireplace("&amp;","&",str_ireplace("&lt;","<",str_ireplace("&gt;",">",str_ireplace("<div class='mipcat_code_ques'>", CConfig::OPER_CODE_START, str_ireplace("</div>", CConfig::OPER_CODE_END,$quesDetails['question']))))));?></textarea>
					</div>
				</div>
				<div class="form-group" id="question_choice_image" style="display:none;">
					<div class="col-lg-9 col-md-9 col-sm-9">
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
				
			<?php
			if(CUtils::getMimeType($quesDetails['question']) != "application/octet-stream")
			{
				printf("<button type='button' class='btn btn-sm btn-info' id='question_cancel_btn' onclick='CancelEditImage(this);'>Cancel</button>");
				printf("</div><br /><br />");
			}
			for($opt_idx = 0; $opt_idx < count($optDetails); $opt_idx++)
			{
				if($optDetails[$opt_idx]['answer'] == 1)
				{
					array_push($ansAry, ($opt_idx+1));
				}
				printf("<div id='option%s_div'>",($opt_idx + 1));
				printf("<label><b>Option %s: </b></label>", ($opt_idx + 1));
				if(CUtils::getMimeType(base64_decode($optDetails[$opt_idx]['option'])) != "application/octet-stream")
				{
					printf("<div id='option%s_image_div'>", ($opt_idx + 1));
					printf("<img align='top' src='../../test/lib/print_image.php?qid=%s&opt=%s'><br /><br />", $quesDetails['ques_id'], ($opt_idx + 1));
					printf("<button type='button' class='btn btn-sm btn-success' id='option%s_edit_btn' onclick='EditImage(this);'>Replace Image</button>", ($opt_idx + 1));
					printf("</div>");
					printf("<input type='hidden' name='option%s_hidden' value='%s'/>", ($opt_idx + 1), $optDetails[$opt_idx]['option']);
					printf("<input type='hidden' name='option%s_edit_choice' id='option%s_edit_choice' value='0'/>", ($opt_idx + 1), ($opt_idx + 1));
					printf("<div id='option%s_edit_div' style='display:none;'>", ($opt_idx + 1));
				}
			?>  
				
				<div class="form-group">
				    <div class="col-lg-2 col-md-2 col-sm-2">
				        <div class="radio">
				       		<label>
					           <input type="radio" value="text" name="option<?php echo($opt_idx + 1);?>_choice" onchange="OnFormateChange(this);" checked/> Text
				        	</label>
				    	</div>
				    </div>	
					<div class="col-lg-2 col-md-2 col-sm-2">
					    <div class="radio">
					    	<label>
					        	<input type="radio" value="image" name="option<?php echo($opt_idx + 1);?>_choice" onchange="OnFormateChange(this);"/> Image
					    	</label>
						</div>
					</div>
				</div>
				<div class="form-group" id="option<?php echo($opt_idx + 1);?>_choice_text">
				    <div class="col-lg-7 col-md-7 col-sm-7">
				  		<input class="form-control input-sm" value="<?php echo((CUtils::getMimeType(base64_decode($optDetails[$opt_idx]['option'])) != "application/octet-stream")?"":str_ireplace("&amp;","&",str_ireplace("&lt;","<",str_ireplace("&gt;",">",str_ireplace("<div class='mipcat_code_ques'>", CConfig::OPER_CODE_START, str_ireplace("</div>", CConfig::OPER_CODE_END,base64_decode($optDetails[$opt_idx]['option'])))))));?>" type="text" name="option<?php echo($opt_idx + 1);?>_choice_text">
					</div>
				</div>
				<div class="form-group" id="option<?php echo($opt_idx + 1);?>_choice_image" style="display:none;">
					<div class="col-lg-9 col-md-9 col-sm-9">
				    	<div class="fileupload fileupload-new" data-provides="fileupload">
							<div class="fileupload-preview thumbnail" style="width: 90%; height: 300px;"></div>
							<div>
								<span class="btn btn-sm btn-success btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span><input type="file" name="option<?php echo($opt_idx + 1);?>_choice_img" /></span>
								<a href="#" class="btn btn-sm btn-success fileupload-exists" data-dismiss="fileupload">Remove</a>
							</div>
						</div><br />
						<div id="option<?php echo($opt_idx + 1);?>_choice_img_error">
						</div>
					</div>
				</div>
			<?php
				if(CUtils::getMimeType(base64_decode($optDetails[$opt_idx]['option'])) != "application/octet-stream")
				{
					printf("<button type='button' class='btn btn-sm btn-info' id='option%s_cancel_btn' onclick='CancelEditImage(this);'>Cancel</button>", ($opt_idx + 1));
					printf("</div><br /><br />");
				}
				printf("</div>");
			}
			?>
				<div id="options_div">		
				</div>
				<input type="hidden" id="options_count" value="<?php echo(count($optDetails));?>" name="options_count">
				
				<div class="form-group">
			      <div class="col-lg-6 col-md-6 col-sm-6">
			        <input class="btn btn-sm btn-info" id='add_option' onclick="AddOption();" type='button' value='Add Option'>
			        <input class="btn btn-sm btn-info" id='remove_option' onclick="RemoveOption();" type='button' value='Remove Option' <?php echo((count($optDetails) == 2)?"disabled":"");?>>
			      </div>
			    </div>
			    
			    <label><b>Correct Options:</b></label>
			    <div class="form-group">
					<div class="col-lg-4 col-md-4 col-sm-4">
						<select class="form-control input-sm" id="answers" name="answers[]" multiple>
							<?php 
							for($opt_idx = 0; $opt_idx < count($optDetails); $opt_idx++)
							{
								printf("<option value='option%s' %s>Option %s</option>", ($opt_idx+1), in_array(($opt_idx+1),$ansAry)?"selected='selected'":"", ($opt_idx+1));
							}
							?>
						</select>
					</div>
				</div>
				
				<label><b>Difficulty:</b></label>
				<div class="form-group">
					<div class="col-lg-4 col-md-4 col-sm-4">
						<select class="form-control input-sm" id="difficulty" name="difficulty">
							<option value="<?php echo(CConfig::DIFF_LVL_EASY);?>" <?php echo(($quesDetails['difficulty_id'] == CConfig::DIFF_LVL_EASY)?"selected='selected'":"")?>>Easy</option>
							<option value="<?php echo(CConfig::DIFF_LVL_MODERATE);?>" <?php echo(($quesDetails['difficulty_id'] == CConfig::DIFF_LVL_MODERATE)?"selected='selected'":"")?>>Moderate</option>
							<option value="<?php echo(CConfig::DIFF_LVL_HARD);?>" <?php echo(($quesDetails['difficulty_id'] == CConfig::DIFF_LVL_HARD)?"selected='selected'":"")?>>Hard</option>
						</select>
					</div>
				</div>
				
				<div class="form-group">
			      <div class="col-lg-4 col-md-4 col-sm-4">
			        <button id='submit_button' type="submit" class="btn btn-primary">Submit</button>
			      </div>
			    </div>
				<input type="hidden" value="<?php echo($_POST['ques_id']);?>" name="ques_id" />
			</form>
		</div>
		<script type="text/javascript">

			var optCounter  = <?php echo(count($optDetails) + 1);?>;
			
			function OnFormateChange(obj)
		    {
		        var objName = $(obj).attr("name");

		        if($(obj).val() == "text")
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

	        function EditImage(obj)
	        {
		        var idArray = $(obj).attr("id").split("_");

		        $("#"+idArray[0]+"_image_div").hide();
		        $("#"+idArray[0]+"_choice_text").hide();
		    	$("input[name='"+idArray[0]+"_choice']").each( function () {
		    		if($(this).val() == "image")
		    		{
			    		$(this).attr("checked","checked");
			    	}
		    		else
		    		{
		    			$(this).removeAttr("checked");
			    	}
		    	});
		        $("#"+idArray[0]+"_edit_div").show(); 
		        $("#"+idArray[0]+"_choice_image").show();
		        $("#"+idArray[0]+"_edit_choice").val("1");
		    }

		    function CancelEditImage(obj)
		    {
		    	var idArray = $(obj).attr("id").split("_");

		    	$("#"+idArray[0]+"_edit_div").hide();
		        $("#"+idArray[0]+"_image_div").show(); 
		        $("#"+idArray[0]+"_edit_choice").val("0");
			}

		    function AddOption()
	        {
				var sOpt = "<div id='option"+optCounter+"_div'>";
				sOpt += "<label><b>Option "+optCounter+":</b></label>";
				sOpt += "<div class='form-group'>";
				sOpt += "<div class='col-lg-2 col-md-2 col-sm-2'><div class='radio'><label><input type='radio' value='text' name='option"+optCounter+"_choice' onchange='OnFormateChange(this);' checked> Text</label></div></div>";
				sOpt += "<div class='col-lg-2 col-md-2 col-sm-2'><div class='radio'><label><input type='radio' value='image' name='option"+optCounter+"_choice' onchange='OnFormateChange(this);'> Image</label></div></div>";
				sOpt += "</div>";

				sOpt += "<div class='form-group' id='option"+optCounter+"_choice_text'>";
				sOpt += "<div class='col-lg-7 col-md-7 col-sm-7'><input class='form-control input-sm' opt_count='"+optCounter+"' type='text' name='option"+optCounter+"_choice_text'></div>";
				sOpt += "</div>";

				sOpt += "<div class='form-group' id='option"+optCounter+"_choice_image' style='display:none;'>";
				sOpt += "<div class='col-lg-9 col-md-9 col-sm-9'><div class='fileupload fileupload-new' data-provides='fileupload'><div class='fileupload-preview thumbnail' style='width: 90%; height: 300px;'></div>";
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
		</script>
<?php
	}
?>