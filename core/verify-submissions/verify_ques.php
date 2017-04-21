<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	include_once("../../database/mcat_db.php");
	
	session_start();
	
	$index = 0;
	$objDB = new CMcatDB();
	
	$verfOptChoice = $_POST['choice'];
	if(isset($_SESSION['choice']))
	{
		$verfOptChoice = $_SESSION['choice'];
		$updateQues = array();
		$updateQues['ques_id'] = $_SESSION['id'];
		$updateQues['question'] = $_POST['question'];
		$updateQues['option_1'] = $_POST['option_1'];
		$updateQues['option_2'] = $_POST['option_2'];
		$updateQues['option_3'] = $_POST['option_3'];
		$updateQues['option_4'] = $_POST['option_4'];
		$updateQues['answer'] = $_POST['answer'];
		$updateQues['subject_id'] = $_POST['subject'];
		$updateQues['topic_id'] = $_POST['topic'];
		$updateQues['difficulty_id'] = $_POST['difficulty'];
		$updateQues['explanation'] = $_POST['explanation'];
		$objDB->UpdateQuestion($updateQues);
	}
	
	if($_GET['decline'] == 1)
	{
		$objDB->DeclineQuestion($_POST['ques_id'],$_POST['reason']);
	}
	if($_GET['accept'] == 1)
	{
		$objDB->AcceptQuestion($_POST['ques_id']);
	}
	
	$subject_id = NULL;
	$user		= NULL;
	$idArray	= NULL;
	$quesArray	= NULL;
	$user_id	= NULL;
	$upperLimit	= NULL;
	
	if($verfOptChoice == "subject")
	{
		$subject_id = $_POST['subject_id'];
		if(isset($_SESSION['subject_id']))
		{
			$subject_id = $_SESSION['subject_id'];
		}
	}
	else
	{
		$user = $_POST['user'];
		if(isset($_SESSION['user']))
		{
			$user = $_SESSION['user'];
		}	
	}
	
	if(!empty($_GET['ques']))
	{
		$index	= $_GET['ques'];
	}
	
	if(!empty($subject_id) || !empty($user))
	{
		if($user != null)
		{
			$user_id = $objDB->GetUserIdByEmail($user);
		}
		$idArray 	= $objDB->GetQuestionIdArray($subject_id,$user_id);
		if(!empty($idArray))
		{
			$upperLimit = count($idArray);
			if($index == $upperLimit)
			{
				$index = $index - 1;
				$bNext = false;
			}
			$quesArray	= $objDB->GetQuestionDetails($idArray[$index]);
		}
	}
	
	$bNext = true;
	$bPrev = true;	
	
	if(($index+1) >= $upperLimit || (empty($subject_id) && empty($user)))
	{
		$bNext = false;
	}
	if($index <= 0 || (empty($subject_id) && empty($user)))
	{
		$bPrev = false;
	}
	
	unset($_SESSION['choice']);
	unset($_SESSION['id']);
	unset($_SESSION['subject_id']);
	unset($_SESSION['user']);
	
	$accepted = 0;
	if(!empty($_GET['accept']))
	{
		$accepted = $_GET['accept'];
	}

	$declined = 0;
	if(!empty($_GET['decline']))
	{
		$declined = $_GET['decline'];
	}

	printf("<script>accept_success='%s'</script>",$accepted);
	printf("<script>decline_success='%s'</script>",$declined);
?>
<html>
	<head>
		<title>Question Verifier</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<style type="text/css" title="currentStyle">
					@import "../../css/redmond/jquery-ui-1.9.0.custom.min.css";
					@import "../media/css/demo_table.css";
					@import "../media/css/TableTools.css";
					@import "../media/css/dataTables.editor.css";

		</style>
		<link rel="stylesheet" type="text/css" href="../../css/notify.css" />
		<link rel="stylesheet" type="text/css" href="../../css/mipcat.css" />
		<script type="text/javascript" charset="utf-8" src="../media/js/jquery.js"></script>
		<script type="text/javascript" charset="utf-8" src="../media/js/jquery.dataTables.js"></script>
		<script type="text/javascript" charset="utf-8" src="../media/js/ZeroClipboard.js"></script>
		<script type="text/javascript" charset="utf-8" src="../media/js/TableTools.js"></script>
		<script type="text/javascript" charset="utf-8" src="../media/js/dataTables.editor.js"></script>
		<script type="text/javascript" charset="utf-8" src="../../js/jquery-ui-custom.min.js"></script>
		<script type="text/javascript" src="../../3rd_party/wizard/js/jquery.validate.min.js"></script>
		<script type="text/javascript" charset="utf-8" src="../../js/mipcat/utils.js"></script>
		<script type="text/javascript" src="../../js/notification.js"></script>
	</head>

	<body style="font: 75% 'Trebuchet MS', sans-serif; margin: 5px; overflow:hidden;">
		<div id="page_loading_box" style="position:absolute;top:100px;left:50%;zindex:200;">
			<img src="../../images/page_loading.gif" width="32" height="32"/>
		</div>
		<div class="notification sticky hide">
	        	<p> You have successfully <?php echo(($accepted == 1)?"accepted":"declined"); ?> one question </p>
	        	<a class="close" href="javascript:">
	            	<img src="../../images/icon-close.png" /></a>
	    </div>
		<div id="verify_ques">
			<ul>
				<li><a href="#tab1">Verify Questions</a></li>
			</ul>
			<div id="tab1">
				<form id="myform" method="post" action="verify_ques.php?ques=0">
					<div style="text-align:center;background-color:CornflowerBlue;color:white;height:30px;line-height:30px;-moz-border-radius: 20px;-webkit-border-radius: 20px;-khtml-border-radius: 20px;border-radius: 20px;">
						<input type="radio" name="choice" onChange="OnVerfOptChange(); <?php if(!empty($user)){ ?>$('#curtain').show();<?php } else if(!empty($subject_id)) { ?>$('#curtain').hide();<?php } ?>"  value="subject" <?php echo(($verfOptChoice == "subject" || (empty($user) && empty($subject_id)))?"checked='checked'":""); ?> /> Subject
						<input type="radio" name="choice" onChange="OnVerfOptChange(); <?php if(!empty($subject_id)){ ?>$('#curtain').show();<?php } else if(!empty($user)) { ?>$('#curtain').hide();<?php } ?>"  value="user" <?php echo(($verfOptChoice == "user")?"checked='checked'":"");  ?> /> Contributor
						<span id="subject_combo" <?php echo(($verfOptChoice == "user")?"style='display:none'":"");?>>
							<select name="subject_id" id="sub_combo" >
								<?php
									$objDB->PopulateSubjectComboForVerifier($subject_id);
								?>
							</select>
						</span>
						<span id="user_textbox" <?php echo(($verfOptChoice != "user")?"style='display:none'":"");?>>
							<input id="user_info" type="text" size="50" name="user" value="<?php echo $user; ?>" onKeyDown="onSelect(false);"/>
						</span>
						<input type="submit" value="Go!"/>
						<b><?php echo((!empty($idArray))?'Question '.($index+1).' of '.$upperLimit:''); ?></b>
					</div>
					<div id="curtain" style="position:absolute;top:80px;left:0px;opacity:0.4;width:100%;height:100%;background-color:CornflowerBlue;z-index:1000;">
					</div>
					<table width="100%" align="center" style="font:inherit;">
						<tr>
							<td>
								<table width="100%" style="font:inherit;">
									<tr align="center">
										<td width="20%" align="right"><input type="button" id="previous" <?php echo($bPrev?"":"disabled='disabled'");?> onClick="var e = document.getElementById('myform'); e.action='verify_ques.php?ques=<?php echo $index-1; ?>'; e.submit();" value="<"></td>
										<td width="60%">
											<table border="1" width="80%" rules="all" style="font:inherit;">
												<tr>
													<td colspan="2">Question : <?php echo(!empty($quesArray)?trim($quesArray['question']):'After selecting subject or user, question will be appeared here with correct option in green and bold'); ?></td>
												</tr>
												<tr>
													<td id="1" width="50%" <?php echo($quesArray['answer']==1?"style='color:green;font-weight:bold;'":""); ?>>(A). <?php echo (!empty($quesArray)?trim($quesArray['option_1']):'First Option'); ?></td>
													<td id="2" width="50%" <?php echo($quesArray['answer']==2?"style='color:green;font-weight:bold;'":""); ?>>(B). <?php echo (!empty($quesArray)?trim($quesArray['option_2']):'Second Option'); ?></td>
												</tr>
												<tr>
													<td id="3" width="50%" <?php echo($quesArray['answer']==3?"style='color:green;font-weight:bold;'":""); ?>>(C). <?php echo (!empty($quesArray)?trim($quesArray['option_3']):'Third Option'); ?></td>
													<td id="4" width="50%" <?php echo($quesArray['answer']==4?"style='color:green;font-weight:bold;'":""); ?>>(D). <?php echo (!empty($quesArray)?trim($quesArray['option_4']):'Fourth Option'); ?></td>
												</tr>
											</table><br />
											<table border="1" width="80%" rules="all" style="font:inherit;">
												<tr>
													<td width="50%">Subject : <?php echo $quesArray['subject']; ?></td>
													<td width="50%">Topic : <?php echo $quesArray['topic']; ?></td>
												</tr>
											</table>
										</td>
										<td width="20%" align="left"><input type="button" id="next" <?php echo($bNext?"":"disabled='disabled'");?> onClick="var e = document.getElementById('myform'); e.action='verify_ques.php?ques=<?php echo $index+1; ?>'; e.submit();" value=">"></td>
									</tr> 
								</table>
							</td>
						</tr>
					</table><br /><br />
					<table width="80%" align="center" style="font:inherit;">
						<tr align="center">
							<td width="40%" align="right"><input type="radio" name="edit" value="1" />Editing Required&nbsp;&nbsp;<input type="radio" name="edit" value="0"  checked/>Done & Next</td>
							<td width="10%"><input type="button" id="accept" value="Accept" onClick="var val = $('input[name=edit]:checked').val(); var e = document.getElementById('myform'); e.action=(val==1)?'verify_edit_ques.php?id=<?php echo ($idArray[$index].'&ques='.$index); ?>':'verify_ques.php?ques=<?php echo $index; ?>&amp;accept=1'; e.submit();" <?php echo(empty($idArray)?"disabled='disabled'":"");?>/></td>
							<td width="25%" align="center">
								<select id="reason" name="reason" <?php echo((empty($idArray))?"disabled='disabled'":"");?> onChange="(this.selectedIndex != 0)?$:">
									<option value="0">--Select Reason for Decline--</option>
									<?php
										$objDB->PopulateDeclineReason(0);
									?>
								</select>
							</td>
							<td width="25%" align="left"><input type="button" id="decline" value="Decline" onClick="var e = document.getElementById('myform'); e.action='verify_ques.php?decline=1&amp;ques=<?php echo $index; ?>'; e.submit();" disabled/></td>
						</tr>
					</table><br /><br />
					<input type="hidden" name="ques_id" value="<?php echo($idArray[$index]); ?>" />
				</form>
				<table width="95%" align="center" style="font:inherit;">
					<tr>
						<td>
							<table cellpadding="0" cellspacing="0" border="0" width="100%" class="display" id="example">
								<thead>
									<tr>
										<th><font color="#000000">%Match</font></th>
										<th><font color="#000000">Ques_Id</font></th>
										<th><font color="#000000">Question</font></th>
										<th><font color="#000000">Option 1</font></th>
										<th><font color="#000000">Option 2</font></th>
										<th><font color="#000000">Option 3</font></th>
										<th><font color="#000000">Option 4</font></th>
										<th><font color="#000000">Answer</font></th>
										<th><font color="#000000">Subject</font></th>
										<th><font color="#000000">Topic</font></th>
									</tr>
								</thead>
									<?php 
										if($idArray != NULL)
										{
											$table = $objDB->GetSimilarQuestions($idArray[$index],false,10,$user_id);
											foreach($table as $key => $value)
											{
												$question = $objDB->GetQuestionDetails($key);
												printf("<tr>");
												printf("<td align='center'>%s</td>",$value);
												printf("<td align='center'>%s</td>",$key);
												printf("<td align='center'>%s</td>",$question['question']);
												printf("<td align='center'>%s</td>",$question['option_1']);
												printf("<td align='center'>%s</td>",$question['option_2']);
												printf("<td align='center'>%s</td>",$question['option_3']);
												printf("<td align='center'>%s</td>",$question['option_4']);
												printf("<td align='center'>%s</td>",$question['answer']);
												printf("<td align='center'>%s</td>",$question['subject']);
												printf("<td align='center'>%s</td>",$question['topic']);
												printf("</tr>");
											}
										}
									?>
								<tfoot>
									<tr>
										<th><font color="#000000">%Match</font></th>
										<th><font color="#000000">Ques_Id</font></th>
										<th><font color="#000000">Question</font></th>
										<th><font color="#000000">Option 1</font></th>
										<th><font color="#000000">Option 2</font></th>
										<th><font color="#000000">Option 3</font></th>
										<th><font color="#000000">Option 4</font></th>
										<th><font color="#000000">Answer</font></th>
										<th><font color="#000000">Subject</font></th>
										<th><font color="#000000">Topic</font></th>
									</tr>
								</tfoot>
							</table>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<script type="text/javascript">
		
			$(window).load(function(){
				$("#page_loading_box").hide();
				$('#verify_ques').show();
				$('#verify_ques').tabs();
					
				var page_hgt = objUtils.AdjustHeight("tab1");
				$('#platform', window.parent.document).height(page_hgt+200);
			});
	
			var bUser = false;
		
			function onSelect(bVal)
			{
				bUser = bVal;
			}
		
			$( "#user_info" ).autocomplete({
        	   source: function(request,response) {
		   		//alert("Test");
				$.getJSON("ajax/ajax_get_users.php",{term: request.term},function(data){
						//alert('hi');
						response(data);
					});
				},
        		minLength: 2,
        		autoFocus: true,
        		response: function(event, ui){
       		 		//alert("Test");
           		},
				select: function(event, ui){
					onSelect(true);
				}
			});
		
			$.validator.addMethod("username_email",function(value){
   				return bUser; 
			});
		
			$(document).ready( function () {
				$('#myform').validate({
					rules: {
						subject_id: {required: true},
						user: {
							required: true,
							username_email: true						
						}
					},
					messages: {
						subject_id: "Please select a subject",
						user: {
							required: "Please enter the user information",
							username_email: "Please select the user information from available suggestions"		
						}
					}
				});
			
				OnVerfOptChange();
				$("#curtain").hide();
			
				//TableTools.DEFAULTS.aButtons = [  ];
				$('#example').dataTable( 
					{"bFilter": false}
				);
			
				if(accept_success == 1 || decline_success == 1)
				{
					$('.notification.sticky').notify({ type: 'sticky' });
				}
			});

			$('#reason').change(function() {
				var reason_id = $('#reason').val();
				if(reason_id == 0)
				{
					$('#accept').removeAttr('disabled');
					$('#decline').attr('disabled','disabled');
				}
				else
				{
					$('#accept').attr('disabled','disabled');
					$('#decline').removeAttr('disabled');
				}
			});

			function OnVerfOptChange()
			{
				var val = $("input[name=choice]:checked").val();
				if(val == "subject")
				{
					$("#user_textbox").hide();
					$("#subject_combo").show();
				}
				else if(val == "user")
				{
					$("#subject_combo").hide();
					$("#user_textbox").show();
				}
			}
		</script>
	</body>
</html>
