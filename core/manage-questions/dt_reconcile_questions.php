<!DOCTYPE HTML>
<?php 
	include_once(dirname(__FILE__)."/../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/../../lib/include_js_css.php");
	include_once(dirname(__FILE__)."/../../lib/site_config.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$objDB = new CMcatDB();
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	
	$langArray = $objDB->GetDistLangFromQues($user_id); 
	
	$parsAry = parse_url(CUtils::curPageURL());
	$qry = explode("=", $parsAry["query"]);
	
	$sQuestion = "";
	if($qry[0] == "ques_updated")
	{
		echo "<script>save_success = 1; </script>";
	}
	else
	{
		echo "<script>save_success = 0; </script>";
	}
	
	$objIncludeJsCSS = new IncludeJSCSS();
	
	$menu_id = CSiteConfig::UAMM_MANAGE_QUESTIONS;
	$page_id = CSiteConfig::UAP_RECONCILE_QUESTIONS;
?>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="Generator" content="Mastishka Intellisys Private Limited">
<meta name="Author" content="Mastishka Intellisys Private Limited">
<meta name="Keywords" content="">
<meta name="Description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo(CConfig::SNC_SITE_NAME);?>: Reconcile Question</title>
<?php 
$objIncludeJsCSS->IncludeDatatablesBootstrapCSS("../../");
$objIncludeJsCSS->IncludeDatatablesResponsiveCSS("../../");
$objIncludeJsCSS->CommonIncludeCSS("../../");
$objIncludeJsCSS->IncludeIconFontCSS("../../");
$objIncludeJsCSS->IncludeBootStrapFileUploadCSS("../../");
$objIncludeJsCSS->IncludeJquerySnippetCSS("../../");
$objIncludeJsCSS->IncludeMipcatCSS("../../");
$objIncludeJsCSS->IncludeFuelUXCSS ( "../../" );
$objIncludeJsCSS->CommonIncludeJS("../../");
$objIncludeJsCSS->IncludeJqueryDatatablesMinJS("../../");
$objIncludeJsCSS->IncludeDatatablesTabletoolsMinJS("../../");
$objIncludeJsCSS->IncludeDatatablesBootstrapJS("../../");
$objIncludeJsCSS->IncludeDatatablesResponsiveJS("../../");
$objIncludeJsCSS->IncludeJqueryFormJS("../../");
$objIncludeJsCSS->IncludeJqueryValidateMinJS("../../");
$objIncludeJsCSS->IncludeJquerySnippetJS("../../");
$objIncludeJsCSS->IncludeBootStrapFileUploadMinJS("../../");
$objIncludeJsCSS->IncludeUtilsJS("../../");
$objIncludeJsCSS->IncludeMathJAXJS( "../../" );
$objIncludeJsCSS->IncludeBootStrap3TypeHeadMinJS("../../");
$objIncludeJsCSS->IncludeBootstrapNumberSpinnerJS("../../");
?>
<style type="text/css">
	.modal, .modal.fade.in {
	    top: 15%;
	}
	
	.js-responsive-table thead{font-weight: bold}	
	.js-responsive-table td{ -moz-box-sizing: border-box; -webkit-box-sizing: border-box;-o-box-sizing: border-box;-ms-box-sizing: border-box;box-sizing: border-box;padding: 0px;}
	.js-responsive-table td span{display: none}		
	
	@media all and (max-width:767px){
		.js-responsive-table{width: 100%;max-width: 400px;}
		.js-responsive-table thead{display: none}
		.js-responsive-table td{width: 100%;display: block}
		.js-responsive-table td span{float: left;font-weight: bold;display: block}
		.js-responsive-table td span:after{content:' : '}
		.js-responsive-table td{border:0px;border-bottom:1px solid #ddd}	
		.js-responsive-table tr:last-child td:last-child{border: 0px}		
	}
	
	.modal1 {
		display:    none;
		position:   fixed;
		z-index:    1000;
		top:        50%;
		left:       60%;
		height:     100%;
		width:      100%;
	}
	
	div.mipcat_code_ques {
		font-family: "Courier New", monospace;
		white-space: pre;
		border:1px solid #aaa;
		padding:5px;
		margin: 10px;
	}
	
	.hide_div {
		display: none;
	}
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
			<div class="row fluid">
				<div class="col-lg-2 col-md-2 col-sm-2">
					<label for="language" class="control-label">Select Language :</label><br />
					<select id="language" class="form-control input-sm" name="language" onchange='OnLanguageChange();' onkeyup='OnLanguageChange();' onkeydown='OnLanguageChange();'>
					</select>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3">
					<label for="tag_id" class="control-label">Select Question Set :</label><br />
					<select id="tag_id" class="form-control input-sm" name="tag_id" onchange='OnTagChange();' onkeyup='OnTagChange();' onkeydown='OnTagChange();'>
					</select>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3">
					<label for="subject_id" class="control-label">Select Subject :</label><br />
					<select id="subject_id" class="form-control input-sm" name="subject_id" onchange='OnSubjectChange();' onkeyup='OnSubjectChange();' onkeydown='OnSubjectChange();'>
					</select>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4">
					<label for="topic_id" class="control-label">Select Topic :</label><br />
					<select id="topic_id" class="form-control input-sm" name="topic_id" onchange='OnTopicChange();' onkeyup='OnTopicChange();' onkeydown='OnTopicChange();'>
					</select>
				</div>
			</div><br />
			
			<div id="div_para_field" style="display : none;">
				<div id="para_div">
					<fieldset id="para_field">
		    			<legend><h3>Para Description :</h3></legend>
		    			<p id="para" style="text-align:justify;text-justify:inter-word;">
		    			</p>
		    		</fieldset><hr />
		    		<button class="btn btn-info" onclick="EditPara();" id="edit_para">Edit Para</button>
	    		</div>
	    		<div id="div_edit_para" style="display: none;">
	    			<form class="form-horizontal" id="PARAFORM" name="PARAFORM"  action="ajax/ajax_update_rcdir_para.php"  method="POST">
		    			<div class="form-group">
				     		<div class="col-lg-2 col-md-2 col-sm-2">
					    		<label class="control-label">Para Description :</label>
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
						  		<textarea class="form-control" rows="3" id="para_text_id" name="para_text" placeholder="Enter Text Here"></textarea>
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
								<div id="code_error"></div>
								<div id="para_img_error">
								</div>
							</div>
						</div>
						<div class="form-group">
			      			<div class="col-lg-6 col-md-6 col-sm-6 col-lg-offset-2">
								<input class="btn btn-primary" type='submit' value='Save'>&nbsp;&nbsp; 
								<input class="btn btn-info" type='button' value='Cancel' onclick="CancelEditPara();"><br /><br />
							</div>
						</div>
					</form>
	    		</div>
    		</div>
    		<br /><br />
			<div id='TableToolsPlacement'>
			</div><br />
		    <div class="form-inline">
		        <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
		        	<thead>
						<tr>
							<th data-class="expand" ><font color="#000000">S. No.</font></th>
							<th data-class="phone,tablet" ><font color="#000000">Question</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Options</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Difficulty</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Edit Question</font></th>
						</tr>
					</thead>
					<tbody id='ques_tbody'>
					</tbody>
					<tfoot>
						<tr>
							<th data-class="expand" ><font color="#000000">S. No.</font></th>
							<th data-class="phone,tablet" ><font color="#000000">Question</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Options</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Difficulty</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Edit Question</font></th>
						</tr>
					</tfoot>
		        </table>
		    </div><br /><br />
		    
		    <div class="modal" id="options_modal">
			  	<div class="modal-dialog">
			    	<div class="modal-content">
			      		<div class="modal-header">
			       		 	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			        		<h4 class="modal-title">Options</h4>
			      		</div>
				      	<div class="modal-body" id="options_body">
				      	</div>
			      		<div class="modal-footer">
				        	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			      		</div>
			    	</div>
			  	</div>
			</div>
			
			<div class="modal" id="edit_ques_modal">
			  	<div class="modal-dialog">
			    	<div class="modal-content">
			      		<div class="modal-header">
			       		 	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			        		<h4 class="modal-title">Edit Question</h4>
			      		</div>
				      	<div class="modal-body" id="edit_ques_modal_body">
				      	</div>
			      		<div class="modal-footer">
				        	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			      		</div>
			    	</div>
			  	</div>
			</div>
			
			<div class="modal" id="delete_ques_modal">
			  	<div class="modal-dialog">
			    	<div class="modal-content">
			      		<div class="modal-header">
			       		 	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			        		<h4 class="modal-title">Delete Question</h4>
			      		</div>
				      	<div class="modal-body" id="delete_ques_modal_body">
				      	</div>
			      		<div class="modal-footer">
				        	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				        	<button type="button" id="delete_btn" class="btn btn-primary" onclick="DeleteQuestion();">Delete</button>
			      		</div>
			    	</div>
			  	</div>
			</div>
			
			<?php
			include_once (dirname ( __FILE__ ) . "/../../lib/footer.php");
			?>
		</div>
	</div>
	<script type="text/javascript">

		$(document).ready(function () {
	
			if(save_success == 1)
			{
				 var not = $.Notify({
    				 caption: "Question Updated",
    				 content: "Your question has been updated successfully!",
    				 style: {background: 'green', color: '#fff'}, 
    				 timeout: 5000
    				 });
			}
	
			BodyLoading();
			$.getJSON("../ajax/ajax_get_languages.php?user_id=<?php echo($user_id);?>", function(data) {
				var selected = "";
				$.each(data, function(index, value) {
					selected = (value == 'english')?"selected='selected'":"";
					$("#language").append("<option value='"+value+"' "+selected+">"+objUtils.ucfirst(value)+"</option>");
				});
				GetQuestionSets($("#language").val());
			});
		});
		
		function GetQuestionSets(language)
		{
			
			$.getJSON("ajax/ajax_reconcile_ques_set.php", {'lang': language}, function(data){
	
				var question_type = "";
				$("#tag_id").empty();
				$.each(data, function(index, value) {
					$("#tag_id").append("<option value='"+index+"'>"+value+"</option>");
				});
				$("#tag_id").append("<option value=''>None</option>");
				GetSubjects(language, $("#tag_id").val());
			});
		}
	
		function GetSubjects(language, tag_id)
		{
			$("#subject_id").load("../ajax/ajax_get_subjects.php",{'lang' : language, 'tag_id' : tag_id, 'mipcat' : 0}, function(){
				GetTopics(language, tag_id, $("#subject_id").val());
			});
		}
	
		function GetTopics(language, tag_id, subject_id)
		{
			$("#topic_id").load("../ajax/ajax_get_topics.php",{'lang' : language, 'tag_id' : tag_id, 'mipcat' : 0, 'sub_id' : subject_id, 'reconcile' : 1}, function(){
				LoadQuestions(language, tag_id, subject_id, $("#topic_id").val());
			});
		}
	
		function OnLanguageChange()
		{
			BodyLoading();
			GetQuestionSets($("#language").val());
		}
	
		function OnTagChange()
		{
			BodyLoading();
			GetSubjects($("#language").val(), $("#tag_id").val());
		}
	
		function OnSubjectChange()
		{
			BodyLoading();
			GetTopics($("#language").val(), $("#tag_id").val(), $("#subject_id").val());
		}
	
		function OnTopicChange()
		{
			BodyLoading();
			LoadQuestions($("#language").val(), $("#tag_id").val(), $("#subject_id").val(), $("#topic_id").val());
		}

		function BodyLoading()
		{
			
		}

		var options 	= "";
		var para_id 	= "";
		var ques_type 	= "";
		function ShowPara(responseText, statusText, xhr, $form)
	    {
			var jsonData = $.parseJSON(responseText);
			$.each(jsonData, function(key,value){
				if(key == "code_error")
				{
					$("#code_error").html("<p style='color :red;'>"+value+"</p>");
					$("#code_error").show();
				}
				else if(key == "html_para_desc" && value != "<img src='../../test/lib/print_image.php?para_id="+para_id+"&ques_type="+ques_type+"'>")
				{
					$("#code_error").hide();
					$("#div_edit_para").hide();
					$("#para_text_id").val(jsonData['para_desc']);
					$("#para").html(value);
					$("#para_div").show();
				}
				else if(key == "html_para_desc" && value == "<img src='../../test/lib/print_image.php?para_id="+para_id+"&ques_type="+ques_type+"'>")
				{
					$("#code_error").hide();
					$("#div_edit_para").hide();
					$("#para_text_id").val("");
					$("#para").html(value);
					$("#para_div").show();
				}
			});
			$(".modal1").hide();
		}

		var delete_ques_id;
		function LoadQuestions(language, tag_id, subject_id, topic_id)
		{
			$(".modal1").show();
			$("#example").dataTable().fnDestroy();
			$('#ques_tbody').empty();
			$.ajax({
				data: {'language' : language, 'tag_id' : tag_id, 'subject_id' : subject_id, 'topic_id' : topic_id},
				type: 'POST', 
			    dataType: 'json',
			    success: function(data) {
				   if(data != null || data != "")
				   {
					   var tableData = "";
					   var paraCount = 0;
					   $.each(data, function (qIndex, qInfoAry){

						   tableData += "<tr id='"+qInfoAry['ques_id']+"'>";
						   tableData += "<td>"+(qIndex+1)+"</td>";
						   tableData += "<td>"+qInfoAry['question']+"</td>";
						   tableData += "<td><input type='button' class='btn btn-sm btn-success' value='Options' id='"+qInfoAry['ques_id']+"_options' onclick='GetOptions(this);'/></td>";	

						   var difficulty = "";
						   if(qInfoAry['difficulty_id'] == <?php echo(CConfig::DIFF_LVL_EASY);?>)
						   {
							   difficulty = "Easy";
						   }
						   else if(qInfoAry['difficulty_id'] == <?php echo(CConfig::DIFF_LVL_MODERATE);?>)
						   {
							   difficulty = "Moderate";
						   }
						   else if(qInfoAry['difficulty_id'] == <?php echo(CConfig::DIFF_LVL_HARD);?>)
						   {
							   difficulty = "Hard";
						   }
						   
						   tableData += "<td>"+difficulty+"</td>";

						   tableData += "<td><input type='button' class='btn btn-sm btn-primary' value='Edit Question' id='"+qInfoAry['ques_id']+"_edit' onclick='EditQuestion(this);'/></td>";	
						   tableData += "</tr>"; 

						   if(qInfoAry['linked_to'] != 0 && qInfoAry['linked_to'] != null && qInfoAry['linked_to'] != "")
						   {
							   para_id   = qInfoAry['linked_to'];
							   ques_type = qInfoAry['ques_type'];

							   options = { 
							       	 	//target:        '',   // target element(s) to be updated with server response 
							       		// beforeSubmit:  showRequest,  // pre-submit callback 
							       		data:		   {'para_id' : qInfoAry['linked_to'], 'ques_type' : qInfoAry['ques_type']},
							      	 	success:       ShowPara,  // post-submit callback 
							 
							        	// other available options: 
							        	url:      'ajax/ajax_update_rcdir_para.php',         // override for form's 'action' attribute 
							        	type:      'POST',       // 'get' or 'post', override for form's 'method' attribute 
							        	//dataType:  null        // 'xml', 'script', or 'json' (expected server response type) 
							        	//clearForm: true,        //clear all form fields after successful submit 
							        	//resetForm: true        // reset the form after successful submit 
							 
							        	// $.ajax options can be used here too, for example: 
							        	//timeout:   3000 
							    };

							   if(paraCount == 0)
							   {
							   	   $("#para").html(qInfoAry['para_desc']);
								   $("#para_text_id").val("");
							   	   if(qInfoAry['para_desc'] != "<img src='../../test/lib/print_image.php?para_id="+qInfoAry['linked_to']+"&ques_type="+qInfoAry['ques_type']+"'>")
							   	   {
								   	   $("#para_text_id").val(qInfoAry['replaced_para_desc']);
								   }
								   $("#div_para_field").show();
							   }
						   }
						   else
						   {
							   $("#div_para_field").hide();
						   }
						   paraCount++;			   
						});

					   $("#ques_tbody").append(tableData);
					   CancelEditPara();
						
					   $("div.mipcat_code_ques").snippet("c",{style:"vim"});
				   }

				   'use strict';
				   	var table;
					var tableElement;
					var responsiveHelper = undefined;
					var breakpointDefinition = {
					        tablet: 1024,
					        phone : 480
					    };

				   TableTools.BUTTONS.custom_button = $.extend( true, TableTools.buttonBase, {
						"sNewLine": "<br>",
						"sButtonText": "Delete",
						"fnClick": function() {
							if(delete_ques_id != "" && delete_ques_id != undefined)
							{
								$("#delete_ques_modal_body").html("Do you want to delete this question?");
								$("#delete_btn").show();
								$("#delete_ques_modal").modal("show");
							}
							else
							{
								$("#delete_ques_modal_body").html("Please select the question to delete.");
								$("#delete_btn").hide();
								$("#delete_ques_modal").modal("show");
							}
						}
					} );
				    
				   $.fn.dataTable.TableTools.defaults.sSwfPath = "<?php $objIncludeJsCSS->IncludeDatatablesCopy_CSV_XLS_PDF("../../");?>";
				    tableElement = $('#example');
				    table = tableElement.dataTable({
				    	"sDom": 'T<"clear">lfrtip<"clear spacer">T',
				    	"bPaginate": true,
				    	"bFilter": true,
				    	"oTableTools": {
				    		"sRowSelect": "single",
				            "aButtons": [
					            {
									"sExtends":    "custom_button",
									"sButtonText": "Delete",
								}
				            ]
				        },
				        autoWidth      : false,
				        preDrawCallback: function () {
				            // Initialize the responsive datatables helper once.
				            if (!responsiveHelper) {
				                responsiveHelper = new ResponsiveDatatablesHelper(tableElement, breakpointDefinition);
				            }
				            var oTableTools = TableTools.fnGetInstance( 'example' );
				            $('#TableToolsPlacement').before( oTableTools.dom.container );
				        },
				        rowCallback    : function (nRow) {
				            responsiveHelper.createExpandIcon(nRow);
				        },
				        drawCallback   : function (oSettings) {
					        //alert("hello");
				            responsiveHelper.respond();
				            $('#example tbody').on( 'click', 'tr', function () {
				            	if( $(this).hasClass('active') ) {
					            	
				            		delete_ques_id = $(this).attr("id");
				                }
				            	else
				            	{
				            		delete_ques_id = "";
					            }
				            } );
				        }
				    });
				    $(".modal1").hide();
			    },
			    url: 'ajax/ajax_get_reconcile_questions.php'
			});			
			
		}

		function DeleteQuestion()
		{
			$(".modal1").show();
			
			$("#delete_ques_modal").modal("hide");
			$.post("ajax/ajax_delete_question.php",{"action": "remove", "data": [delete_ques_id]},function(){
				$("#example").dataTable().api().rows( ".active" )
		        .remove()
		        .draw();
				$(".modal1").hide();
			});
		}

		function GetOptions(obj)
		{
			var idAry = $(obj).attr("id").split("_");

			$(".modal1").show();
			$.ajax({
				data : {'ques_id' : idAry[0]},
				type: 'POST', 
			    dataType: 'json',
			    success: function(data) {

				     var optionData  = "<div style='margin:12px;padding:12px;-moz-box-shadow: 3px 3px 5px 6px #ccc; -webkit-box-shadow: 3px 3px 5px 6px #ccc; box-shadow: 3px 3px 5px 6px #ccc;'><fieldset style='background-color:#EBF0FF;border:1px solid #aaa'>";
					 optionData 	+= "<table class='table table-bordered js-responsive-table' width='100%' style='font:inherit;'>";
				     var optCounter = 0;
				     var answer     = new Array();
			    	 $.each(data, function (key, value) {

			    		 if(optCounter == 0)
						 {
			    			 optionData += "<tr>";
						 }
						 else if((optCounter % 2) == 0)
						 {
							 optionData += "</tr><tr>";
						 }

			    		 var opt_class = "";
						 var opt_icon  = "";
						 if(value['answer'])
						 {
							opt_class = "class='alert alert-success'";
							opt_icon  = "<i class='icon-checkmark'></i>";
							answer.push(optCounter+1);
						 }

						 if(optCounter == (data.length - 1) && (optCounter % 2) == 0)
						 {
							 optionData += "<td colspan='2' "+opt_class+">";
						 }
						 else
						 {
							 optionData += "<td style='width: 50%;' "+opt_class+">";
						 }
						 optionData += opt_icon+"&nbsp;"+(optCounter+1)+"). "+value['option'];
						 optionData += "</td>";

						 if(optCounter == (data.length - 1))
						 {
							 optionData += "</tr>";
						 }
						 optCounter++;
				     });
			    	 optionData += "</table>";
			    	 optionData += "<br /><br />"+"<span class='label label-warning'>Correct Answer: "+answer.join(",")+"</span></div>";
			    	 //alert(optionData);
			    	 $("#options_body").html(optionData);
			    	 $("#options_modal").modal("show");
			    	 $(".modal1").hide();
				},
				url: 'ajax/ajax_get_ques_options.php'
			});
		}

		function EditPara()
		{
			$("#para_div").hide();
			$("#div_edit_para").show();
		}
		
		function CancelEditPara()
		{
			$("#div_edit_para").hide();
			$("#code_error").hide();
			$("#para_div").show();
		}

		function OnRCDirTypeChange()
		{
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

		function OnHide()
    	{
    		$("#MSG").hide();
    	}

		$("#PARAFORM").validate({
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
        		para_text: {
            		required:true,
        		},
        		para_img: {
            		required:true,
        		}
    		},
    		messages: {
        		para_text: {
        			required:	"<span style='color:red'>* Please enter the para description</span>"
        		},
        		para_img: {
        			required:	"<span style='color:red'>* Please select the para description image</span>"
        		}
	    	},
    		submitHandler: function(form) {
    			SubmitFormData();
    		}
		});
		
		function SubmitFormData()
		{
			$(".modal1").show();
			$('#PARAFORM').ajaxSubmit(options); 
			return false;
	    }

		function EditQuestion(obj)
		{
			BodyLoading();
			var ques_id_ary = $(obj).attr("id").split("_");

			$(".modal1").show();
			$("#edit_ques_modal_body").load("ajax/ajax_reconcile_edit_ques.php",{'ques_id' : ques_id_ary[0]}, function(){
				$("#edit_ques_modal").modal("show");
				$(".modal1").hide();
			});
		}
	</script>
	<script type="text/x-mathjax-config">
  		MathJax.Hub.Config({
    		tex2jax: {inlineMath: [["$","$"],["\\(","\\)"]]}
 		});
	</script>
</body>
</html>