<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	include_once("../lib/session_manager.php");
	include_once("../lib/utils.php");
	include_once("lib/test_helper.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	//CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$bFreeEZeeAssesUser = CSessionManager::Get(CSessionManager::BOOL_FREE_EZEEASSESS_USER);
	
	$sUserID = "";
	if($bFreeEZeeAssesUser == 1)
	{
		$sUserID = $_COOKIE[CConfig::FEUC_NAME];
	}
	else
	{
		$sUserID = CSessionManager::Get(CSessionManager::STR_USER_ID);
	}
	
	$bDecrAttemptCount  = CSessionManager::Get(CSessionManager::BOOL_DECR_ATTEMPT_COUNT);
	
	$parsAry = parse_url(CUtils::curPageURL());
	$qry = split("[=&]", $parsAry["query"]);
	
	$objTH = new CTestHelper();
	
	$nTestID 	= null;
	$nTSchdID	= null;
	$nSection 	= null;
	$nQuestion 	= null;
	$nCurTime 	= null;
	$aryQues 	= null;
	$objAnsAry	= null;
	
	$langofchoice	 = CSessionManager::Get(CSessionManager::BOOL_SEL_TEST_LANG);
	$transLangChoice = CSessionManager::Get(CSessionManager::STR_TRANS_LANG_CHOICE);
	$testTransLang   = CSessionManager::Get(CSessionManager::STR_TEST_TRANS_LANG);
	
	
	//echo $transLangChoice." ".$testTransLang."<br />";
	
	$objTestParams = null;
	$objMCPAParams = null;
	
	$bNewTest 		= false;
	$bTranslation   = false;
	
	/*echo "<pre>";
	print_r($qry);
	echo "</pre><br/>";*/
	if($qry[0] == "test_id")
	{
		// The page is being called by clicking on question number or from Start Test button
		$nTestID = $qry[1];
		
		if($qry[2] == "tschd_id")
		{
			$nTSchdID = $qry[3];
		}
		if($qry[4] == "sec")
		{
			$nSection = $qry[5];
		}
		if($qry[6] == "ques")
		{
			$nQuestion = $qry[7];
		}
		if($qry[8] == "trans_lang_choice")
		{
			if(!empty($transLangChoice))
			{
				CSessionManager::UnsetSessVar(CSessionManager::STR_TRANS_LANG_CHOICE);
			}
			$transLangChoice = $qry[9];
			CSessionManager::Set(CSessionManager::STR_TRANS_LANG_CHOICE, $transLangChoice);
		}
		if($qry[10] == "test_trans_lang")
		{
			if(!empty($testTransLang))
			{
				CSessionManager::UnsetSessVar(CSessionManager::STR_TEST_TRANS_LANG);
			}
			$testTransLang = $qry[11];
			CSessionManager::Set(CSessionManager::STR_TEST_TRANS_LANG, $testTransLang);
		}
		// Get Test Parameters
		$objTestParams 	= $objTH->GetTestParams($nTestID);
		$objMCPAParams 	= $objTH->GetMCPAParams($nTestID);
		
		$bTranslation	= $objMCPAParams['allow_trans'];
		
		if($bTranslation)
		{
			if($transLangChoice == "single")
			{
				$bNewTest = $objTH->StartTest($sUserID, $nTestID, $nTSchdID, $testTransLang);
			}
			else 
			{
				$bNewTest = $objTH->StartTest($sUserID, $nTestID, $nTSchdID, $objMCPAParams['pref_lang']);
			}
		}
		else
		{
			$bNewTest = $objTH->StartTest($sUserID, $nTestID, $nTSchdID, $objMCPAParams['pref_lang']);
		}
		
		$session_time = CSessionManager::Get(CSessionManager::INT_TEST_TIMER);
		$nCurTime = null;
		if($qry[8] == "curtime" && $qry[9] != $session_time)
		{
			$nCurTime = $qry[9];
			
			if(empty($nCurTime))
			{
				$nCurTime = $objTH->GetElapsedTime($sUserID, $nTestID, $nTSchdID);
			}
		}
		else 
		{
			if($bNewTest == false)
			{
				$nCurTime = $objTH->GetElapsedTime($sUserID, $nTestID, $nTSchdID);
			}
			else 
			{
				$nCurTime = $objTestParams['test_duration'] * 60;
			}
		}
		
		$LastSection = CSessionManager::Get(CSessionManager::INT_LAST_SECTION);
		$LastQuestion = CSessionManager::Get(CSessionManager::INT_LAST_QUESTION);
		
		$objAnsAry = $objTH->GetAnswers($sUserID, $nTestID, $nTSchdID);
		
		//echo "LastSection: ".$LastSection.", LastQuestion: ".$LastQuestion."<br/><br/>";
		//printf("TestID: %s, SectionID: %s, Question#: %s, Answer: %s, Tschd_ID: %s<br/>", $nTestID, $nSection, $nQuestion, $nAns, $nTSchdID);
		
		if($LastSection == null)
		{
			CSessionManager::Set(CSessionManager::INT_LAST_SECTION, $nSection);
			CSessionManager::Set(CSessionManager::INT_LAST_QUESTION, $nQuestion);
		}
		else 
		{
			// if last question was not already answered.
			if(count($objAnsAry[$LastSection][$LastQuestion]) == 1 && in_array(-1, $objAnsAry[$LastSection][$LastQuestion]) && $objMCPAParams['mcpa_flash_ques'] == 1)
			{
				//printf("Unanswered Question: (Section: %s, Question: %s)", $LastSection, $LastQuestion);
				if($bTranslation)
				{
					if($transLangChoice == "single")
					{
						$objTH->ReplaceQuestion($LastSection, $LastQuestion, $testTransLang);
					}
					else
					{
						$objTH->ReplaceQuestion($LastSection, $LastQuestion, $objMCPAParams['pref_lang']);
					}
				}
				else
				{
					$objTH->ReplaceQuestion($LastSection, $LastQuestion, $objMCPAParams['pref_lang']);
				}
				//$objTH->ReplaceQuestion($LastSection, $LastQuestion);
			}
			
			CSessionManager::Set(CSessionManager::INT_LAST_SECTION, $nSection);
			CSessionManager::Set(CSessionManager::INT_LAST_QUESTION, $nQuestion);
		}
		CSessionManager::Set(CSessionManager::INT_TEST_TIMER, $nCurTime);
		
		$aryQues = $objTH->GetQuestion($nSection, $nQuestion, $nCurTime);
		/*echo $nQuestion."<br />";
		echo "<pre>";
		print_r($aryQues);
		echo "</pre>";*/
	}
	else 
	{
		// The page is being called by submitting an answer
		$nTestID 		= $_POST['test_id'];
		$nSection 		= $_POST['section'];
		$nQuestion 		= $_POST['question'];
		$nAns 			= $_POST['answer'];
		
		$langofchoice	= $_POST['langofchoice'];
		CSessionManager::Set(CSessionManager::BOOL_SEL_TEST_LANG, $langofchoice);
		
		if(isset($_POST['flag_choice']))
		{
			if($_POST['flag_choice'] == 1)
			{
				$nAns   = array("-2");
			}
			else if($_POST['flag_choice'] == 2)
			{
				$nAns   = array("-1");
			}
		}
		
		$nCurTime	= $_POST['cur_timer'];
		$nTSchdID	= $_POST['tschd_id'];
		
		//printf("TestID: %s, SectionID: %s, Question#: %s, Answer: %s, Tschd_ID: %s<br/>", $nTestID, $nSection, $nQuestion, $nAns, $nTSchdID);
	
		$objMCPAParams 	= $objTH->GetMCPAParams($nTestID);
		
		$bTranslation	= $objMCPAParams['allow_trans']; 
		
		if($bTranslation)
		{	
			if($transLangChoice == "single")
			{
				$bNewTest = $objTH->StartTest($sUserID, $nTestID, $nTSchdID, $testTransLang);
			}
			else
			{
				$bNewTest = $objTH->StartTest($sUserID, $nTestID, $nTSchdID, $objMCPAParams['pref_lang']);
			}
		}
		else
		{
			$bNewTest = $objTH->StartTest($sUserID, $nTestID, $nTSchdID, $objMCPAParams['pref_lang']);
		}
		
		/*echo("<pre>");
		print_r($nAns);
		echo("</pre>");*/
		
		if(count($nAns) > 0 && !in_array(-1, $nAns))
		{
			//echo("Test 1");
			$objTH->SubmitAnswer($sUserID, $nTestID, $nTSchdID, $nSection, $nQuestion, $nAns, $nCurTime);
		}
		else
		{
			//echo("Test 2");
			$objTH->SubmitAnswer($sUserID, $nTestID, $nTSchdID, $nSection, $nQuestion, array("-1"), $nCurTime);
			
			if($objMCPAParams['mcpa_flash_ques'] == 1)
			{
				if($bTranslation)
				{		
					if($transLangChoice == "single")
					{
						$objTH->ReplaceQuestion($nSection, $nQuestion, $testTransLang);
					}
					else
					{
						$objTH->ReplaceQuestion($nSection, $nQuestion, $objMCPAParams['pref_lang']);
					}
				}
				else
				{
					$objTH->ReplaceQuestion($nSection, $nQuestion, $objMCPAParams['pref_lang']);
				}
			}
		}
		
		$aryQues = $objTH->GetNextQuestion($nSection, $nQuestion, $nCurTime);
		
		CSessionManager::Set(CSessionManager::INT_LAST_SECTION, $nSection);
		CSessionManager::Set(CSessionManager::INT_LAST_QUESTION, $nQuestion);
	}
	
	$aryTransQues = null;
	
	if($bTranslation)
	{
		if($aryQues['ques_id'] != -1 && $transLangChoice == "both")
		{
			$aryTransQues = $objTH->GetTranslatedQuestion($aryQues['group_title'], $testTransLang);
		}
	}
	//printf("TestID: %s, SectionID: %s, Question#: %s, TSchdID: %s", $nTestID, $nSection, $nQuestion, $nTSchdID);
	/*echo "<pre>";
	print_r($aryQues);
	echo "</pre><br/>";*/
	
	/*echo "<pre>";
	print_r($objMCPAParams);
	echo "</pre><br/>";*/
	
	if($objTestParams == null)
	{
		$objTestParams 	= $objTH->GetTestParams($nTestID);
	}
	
	if($objAnsAry == null)
	{
		$objAnsAry = $objTH->GetAnswers($sUserID, $nTestID, $nTSchdID);
	}
	
	$sSectionName = $objTH->GetSectionName($nSection);
	
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	// Adjust attempts
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	if($bDecrAttemptCount == 1)
	{
		$tsession_id = $objTH->IsTestPending($sUserID, $nTestID, $nTSchdID);
		$objTH->DecrementAttemptsInTestSession($tsession_id);
		
		CSessionManager::UnsetSessVar(CSessionManager::BOOL_DECR_ATTEMPT_COUNT);
	}
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	
	if(empty($aryTransQues))
	{
		$langofchoice = 0;
	}
	/*echo "Later:<pre>";
	print_r($objAnsAry);
	echo "</pre><br/>";*/
	
	//echo("Current Answer: ".$objAnsAry[$nSection][$nQuestion]);
	//echo $transLangChoice." ".$testTransLang." ".$aryQues['ques_id'];
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
		<style type="text/css">
			@import "../core/media/css/ui-lightness/jquery-ui-1.8.21.custom.css";
			.horiz { float: left; padding: 0 90px; }
			.right{position:absolute;right:0px;width:300px;}
			div.mipcat_code_ques
			{
				font-family: "Courier New", monospace;
				white-space: pre;
				border:1px solid #aaa;
				padding:5px;
				margin: 10px;
			}
		</style>
		<link rel="stylesheet" type="text/css" href="../css/mipcat.css" />
		<link rel="stylesheet" type="text/css" href="../3rd_party/bootstrap/css/bootstrap.css" />
		<link rel="stylesheet" type="text/css" href="../core/media/css/jquery.snippet.css" />
		<script type="text/javascript">
		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', 'UA-2246912-13']);
		  _gaq.push(['_trackPageview']);
		
		  (function() {
		    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();
		</script>
		<script type="text/javascript" src="../3rd_party/wizard/js/jquery.min.js"></script>
		<script type="text/javascript" charset="utf-8" src="../core/media/js/jquery-ui-1.8.21.custom.min.js"></script>
		<script type="text/javascript" src="../core/media/js/jquery.snippet.js"></script>
		<script type="text/javascript" src="../3rd_party/bootstrap/js/bootstrap.js"></script>
		<script type="text/javascript" src="../js/mipcat/utils.js"></script>
	</head>
	<body style="font: 80% 'Trebuchet MS', sans-serif; margin: 5px;">
		<div>
			<span style="float:left;border:1px solid #aaa;padding:5px;"><?php printf("<b><i class='icon-tasks icon-black'></i>&nbsp;Section: <span style='color:FireBrick;'>%s</span></b>",$sSectionName);?></span>
			<span style="float:right;"><input type="text" class="input-medium search-query" size="8" id="timer" style="text-align:center;color:#009900;font-weight: bold;"></span>
		</div>
    	<br/><br/><br/>
		
		<form action="mipcat.php" onReset="return ResetForm();" method="POST">
			<div id="choose_lang" class="form-inline" style="text-align:center;width:400px;height:auto;margin-left:auto;margin-right:auto;border:1px solid #aaa;padding:5px;<?php echo($transLangChoice != "both"?"display:none":"");?>">
    			<span style='color:DarkSlateGray;'>Choose Language &nbsp;<i class='icon-comment icon-black'></i> :&nbsp;&nbsp;
    			
    			<label class="radio">
					<input type="radio" id="trans_choice_base" value='base' name="trans_choice" onchange="OnTransChoiceChange();" <?php echo($langofchoice==0?"checked":""); ?>>&nbsp;&nbsp;<?php echo(ucfirst($objMCPAParams['pref_lang'])); ?>&nbsp;&nbsp;
				</label>
				
				<?php 
		    	if(!empty($aryTransQues))
		    	{
		    	?>
				<label class="radio">
					<input type="radio" id="trans_choice_translated" value='translated' name="trans_choice" onchange="OnTransChoiceChange();" <?php echo($langofchoice==1?"checked":""); ?>>&nbsp;&nbsp;<?php echo(ucfirst($testTransLang)); ?>
				</label>
				<?php 
		    	}
		    	else
		    	{
				?>
					<label style="color:red;">Question&rsquo;s translation in <b>&lsaquo; <?php echo(ucfirst($testTransLang)); ?> Language &rsaquo;</b> is not available.</label>
				<?php 
		    	}
				?>
			</div><br /><br />
			
	    	<div id="base_para" style="overflow: auto;border:1px solid #aaa;padding:5px;height:250px;<?php echo($aryQues['ques_type'] == CConfig::QT_NORMAL ? "display:none;" : "");?>">
	    		<blockquote>
		    		<p>
		    		<?php
		    			if($aryQues['ques_type'] != CConfig::QT_NORMAL && $aryQues['ques_type'] != -1)
		    			{
		    				echo($objTH->GetRCDirectionPara($aryQues['ques_id'], $aryQues['ques_type']));
		    			}
		    		?>
		    		</p>
		    		<small><?php echo(ucwords($aryQues['language']));?></small>
	    		</blockquote>
	    		<br/><br/><br/>
	    	</div>
	    	
	    	<?php 
	    	if(!empty($aryTransQues))
	    	{
	    	?>
	    	<div id="trans_para" style="overflow: auto;border:1px solid #aaa;padding:5px;height:250px;display:none;">
	    		<blockquote>
		    		<p>
		    		<?php
		    			if($aryTransQues['ques_type'] != CConfig::QT_NORMAL && $aryTransQues['ques_type'] != -1)
		    			{
		    				echo($objTH->GetRCDirectionPara($aryTransQues['ques_id'], $aryTransQues['ques_type']));
		    			}
		    		?>
		    		</p>
		    		<small><?php echo(ucwords($aryTransQues['language']));?></small>
	    		</blockquote>
	    		<br/><br/><br/>
	    	</div>
	    	<?php 
	    	}
	    	?>	 
			<table width="100%" cellpadding="4" cellspacing="4">
				<tr>
					<td colspan="2" id="td_question" style="color:DarkSlateBlue;">
					<?php
						$ques_cnts = "";
                           if(CUtils::getMimeType($aryQues['question']) == "application/octet-stream")
                           {
                               $ques_cnts =  $aryQues['question'];
                           }
                           else
                           {
                               $ques_cnts = sprintf("<img src='lib/print_image.php?qid=%s&opt=0'>", $aryQues['ques_id']);
                           }
                           printf("<blockquote id='base_ques'><p><b>Ques %d). %s</b></p><small>%s</small></blockquote>", ($nQuestion+1), $ques_cnts, ucwords($aryQues['language']));
                           
                           $opt_ary = array();
                           for($index = 0; $index < $aryQues['opt_count']; $index++)
                           {
                           	if(CUtils::getMimeType(base64_decode($aryQues['options'][$index]["option"])) == "application/octet-stream")
	                           {
	                               $opt_ary[$index] =  base64_decode($aryQues['options'][$index]["option"]);
	                           }
	                           else
	                           {
	                               $opt_ary[$index] = sprintf("<img src='lib/print_image.php?qid=%s&opt=%s'>", $aryQues['ques_id'], ($index + 1));
	                           }
                           }
                           
                           if(!empty($aryTransQues))
                           {
                           	
                           	$ques_cnts = "";
                           	if(CUtils::getMimeType($aryTransQues['question']) == "application/octet-stream")
                           	{
                           		$ques_cnts = $aryTransQues['question'];
                           	}
                           	else
                           	{
                           		$ques_cnts = sprintf("<img src='lib/print_image.php?qid=%s&opt=0'>", $aryTransQues['ques_id']);
                           	}
                           	printf("<blockquote id='trans_ques' style='display :none'><p><b>Ques %d). %s</b></p><small>%s</small></blockquote>", ($nQuestion+1), $ques_cnts, ucwords($aryTransQues['language']));
                           	
                           	$trans_opt_ary = array();
                           	for($index = 0; $index < $aryTransQues['opt_count']; $index++)
                           	{
                           		if(CUtils::getMimeType(base64_decode($aryTransQues['options'][$index]["option"])) == "application/octet-stream")
                           		{
                           			$trans_opt_ary[$index] =  base64_decode($aryTransQues['options'][$index]["option"]);
                           		}
                           		else
                           		{
                           			$trans_opt_ary[$index] = sprintf("<img src='lib/print_image.php?qid=%s&opt=%s'>", $aryTransQues['ques_id'], ($index + 1));
                           		}
                           	}
                           }
					?><br/><br/>
					</td>
				</tr>
				<?php
					for($opt_idx = 0; $opt_idx < $aryQues['opt_count']; $opt_idx++)
					{
						if($opt_idx == 0)
						{
							printf("<tr>\n");
						}
						
						else if(($opt_idx % 2) == 0)
						{
							printf("</tr>\n<tr>\n");
						}
						
						$ip_type = "radio";
						if($objMCPAParams['mcq_type'] == 1)
						{
							$ip_type = "checkbox";
						}
				?>
					<td class="info" id="td_opts" style="<?php echo((empty($opt_ary[$opt_idx]) && !is_numeric($opt_ary[$opt_idx])) ? "display:none;" : "");?>"><label><?php echo($opt_idx+1);?>). <input style="position:relative;top:-4px;" id="rb_opt_<?php echo($opt_idx+1);?>" type="<?php echo($ip_type);?>" name="answer[]" value="<?php echo($opt_idx+1);?>" <?php echo(in_array(($opt_idx+1), $objAnsAry[$nSection][$nQuestion])?"checked='checked'":""); ?> /> <span id="base_opt_<?php echo($opt_idx+1);?>"><?php echo($opt_ary[$opt_idx]);?></span><?php printf(!empty($aryTransQues)?"<span id='trans_opt_%d' style='display :none;'>%s</span>":"", ($opt_idx+1), $trans_opt_ary[$opt_idx]);?></label></td>
				<?php
						if( $opt_idx == ($aryQues['opt_count']-1) )
						{
							printf("</tr>\n");
						}
					}
				?>
			</table>
			<input type="hidden" id="test_id" name="test_id" value="<?php echo($nTestID);?>"/>
			<input type="hidden" id="tschd_id" name="tschd_id" value="<?php echo($nTSchdID);?>"/>
			<input type="hidden" id="section" name="section" value="<?php echo($nSection);?>"/>
			<input type="hidden" id="question" name="question" value="<?php echo($nQuestion);?>"/>
			<input type="hidden" id="langofchoice" name="langofchoice" value="0"/>
			<?php 
			if($objMCPAParams['mcpa_flash_ques'] != 1)
			{
			?>
			<input type="hidden" id="flag_choice" name="flag_choice" value="0"/>
			<?php 		
			}
			?>
			<input type="hidden" id="cur_timer" name="cur_timer" value=""/>
			<?php
				if((count($objAnsAry[$nSection][$nQuestion]) == 1 && (in_array(-1, $objAnsAry[$nSection][$nQuestion]) || in_array(-2, $objAnsAry[$nSection][$nQuestion]))) || $objMCPAParams['mcpa_lock_ques'] == 0)
				{
					$flag_btn_val = in_array(-2, $objAnsAry[$nSection][$nQuestion])?"Unflag / Unmark":"Flag / Mark";
					$flag_val	  = in_array(-2, $objAnsAry[$nSection][$nQuestion])?2:1;
					echo('<br/><br/><input type="reset" class="btn btn-inverse"/>&nbsp;&nbsp;&nbsp;&nbsp;');
					echo(($objMCPAParams['mcpa_flash_ques'] != 1)?'<input type="submit" onclick="SetFlag('.$flag_val.');" class="btn btn-primary" id="flag_ques" name="btn2" value="'.$flag_btn_val.'" disabled/>&nbsp;&nbsp;&nbsp;&nbsp;':'');
					echo('<input type="submit" id="submit_ans" class="btn btn-success" name="btn1" value="Next" disabled/>');
					
				}
				else 
				{
					echo('<br/><br/><input type="reset" class="btn btn-inverse" disabled/>&nbsp;&nbsp;&nbsp;&nbsp;');
					echo(($objMCPAParams['mcpa_flash_ques'] != 1)?'<input type="submit" onclick="SetFlag(0);" class="btn btn-primary" name="btn2" value="Flag / Mark" disabled/>&nbsp;&nbsp;&nbsp;&nbsp;':'');
					echo('<input type="submit" class="btn btn-success" name="btn1" value="Next" disabled/>');
				}
				
				echo('<br/><br/><b>( After Reseting already selected option&lsaquo;s&rsaquo; or after Selecting option&lsaquo;s&rsaquo; press <span style="color:green">Submit</span> )</b>');
			?>
		</form>
		
		<script  language="JavaScript" type="text/javascript">
		parent.ShowLeftMenu();
		OnTransChoiceChange();
		
		window.onload = function() {
			parent.OnTestStarted();
			TestTimer();
			HeartBeat();
			$("div.mipcat_code_ques").snippet("c",{style:"vim"});
			parent.SetBPageLoad(true);
			$("#submit_ans").removeAttr('disabled');
			<?php
			if($objMCPAParams['mcpa_flash_ques'] != 1)
			{ 
			?>
			$("#flag_ques").removeAttr('disabled');
			<?php 
			}
			?>
		}

		<?php 
		if($objMCPAParams['mcpa_flash_ques'] != 1)
		{
		?>
		function SetFlag(value)
		{
			$("#flag_choice").val(value);
		}
		<?php 
		}
		?>
		function OnTransChoiceChange()
		{
			var val = $("input[name=trans_choice]:checked").val();

			if(val == "base")
			{
				$("#trans_ques").hide();
				$('span[id^=trans_opt_]').each(function(){
					$(this).hide();
				});

				<?php 
				if($aryQues['ques_type'] != CConfig::QT_NORMAL)
				{
				?>
				$("#trans_para").hide();
				$("#base_para").show("fade", "slow");
				<?php 
				}
				?>
				$("#base_ques").show("fade", "slow");
				$('span[id^=base_opt_]').each(function(){
					$(this).show("fade", "slow");
				});
				
				$("#langofchoice").val(0);
			}
			else
			{
				$("#base_ques").hide();
				$('span[id^=base_opt_]').each(function(){
					$(this).hide();
				});
				<?php 
				if($aryQues['ques_type'] != CConfig::QT_NORMAL)
				{
				?>
				$("#base_para").hide();
				$("#trans_para").show("fade", "slow");
				<?php 
				}
				?>
				$("#trans_ques").show("fade", "slow");
				$('span[id^=trans_opt_]').each(function(){
					$(this).show("fade", "slow");
				});
				
				$("#langofchoice").val(1);
			}	
		}
		
		TestTimer.CurTime = <?php echo($nCurTime); ?>;
		function TestTimer()
		{
			if(TestTimer.CurTime < 0)
			{
				parent.OnEndExam();
				clearTimeout(TestTimer.hTimer);
				return;
			}
			else if(TestTimer.CurTime <= 600)
			{
				$("#timer").css("color", "#FF0000");
			}
			
			$("#timer").val(secondsToTime(TestTimer.CurTime));
			$("#cur_timer").val(TestTimer.CurTime);
			
			TestTimer.CurTime--;
			TestTimer.hTimer = setTimeout(function(){TestTimer()}, 1000);
		}
		
		var connection_error_count = 0;
		function HeartBeat()
		{
			if(TestTimer.CurTime < 0)
			{
				// End Test
				clearTimeout(HeartBeat.hTimer);
				return;
			}
			
			$.post("ajax/ajax_timer_heartbeat.php", 
					{ user_id: "<?php echo($sUserID);?>", test_id: "<?php echo($nTestID);?>", timer: TestTimer.CurTime, tschd_id: "<?php echo($nTSchdID);?>", langofchoice: $("#langofchoice").val()},
					function(data) {
						connection_error_count = 0;
						
						var response = $.parseJSON(data);
						//alert(response.TSchdID);
						if(response.Result == <?php echo(CConfig::FOKI_YES);?>)
						{
							clearTimeout(TestTimer.hTimer);
							clearTimeout(HeartBeat.hTimer);
							
							parent.CloseTestWithMsg("<p style='color:red'>Your test administrator has instructed to end this test. <b>We have forcefully ended this test</b>. Please contact your test administrator for reasons.</p>", 1);
				   			return;
						}
					} ).fail(function() { 
						connection_error_count++;
						if(connection_error_count >= 6)
						{
							connection_error_count = 0;
							clearTimeout(TestTimer.hTimer);
							clearTimeout(HeartBeat.hTimer);
							
							parent.CloseTestWithMsg("<p style='color:red'>Can't connect to <?php echo(CSiteConfig::ROOT_URL);?> server, there might be problems with your internet connection. automatically closing test. Please resume test when you have internet connectivity.</p>", 0);
				   			return;
						}
				});
			
			HeartBeat.hTimer = setTimeout(function(){HeartBeat()}, 5000);
		}
		
		function secondsToTime(secs)
		{
		    var hours = Math.floor(secs / (60 * 60));
		   
		    var divisor_for_minutes = secs % (60 * 60);
		    var minutes = Math.floor(divisor_for_minutes / 60);
		 
		    var divisor_for_seconds = divisor_for_minutes % 60;
		    var seconds = Math.ceil(divisor_for_seconds);
		   
		    var obj = ("0" + hours).slice(-2) + ":" + ("0" + minutes).slice(-2) +":"+ ("0" + seconds).slice(-2);
		    
		    return obj;
		}
		
		function ResetForm()
		{
			$("input:radio[name='answer[]']").removeAttr("checked");
			$("input:checkbox[name='answer[]']").removeAttr("checked");
			
			<?php
				if((count($objAnsAry[$nSection][$nQuestion]) > 0 && !in_array(-1, $objAnsAry[$nSection][$nQuestion]) && !in_array(-2, $objAnsAry[$nSection][$nQuestion])))
				{
			?>
					ChangeSubmitBtnName("Submit");
			<?php
				}
				else
				{
			?>
				ChangeSubmitBtnName("Next");
			<?php
				}
			?>
			
			return false;
		}
		
		$("input:checkbox[name='answer[]']").click(function(){
			var bAnyChecked = false;
			$("input:checkbox[name='answer[]']").each(function(key, val){
				if($(this).is(':checked'))
				{
		    		ChangeSubmitBtnName("Submit");
		    		bAnyChecked = true;
		    		return false;
				}
			});
			
			if(!bAnyChecked)
			{
				ChangeSubmitBtnName("Next");
			}
		});
		
		$("input:radio[name='answer[]']").click(function(){
			if($(this).is(':checked'))
			{
	    		ChangeSubmitBtnName("Submit");
			}
		});
		
		function ChangeSubmitBtnName(sName)
		{
			$("#submit_ans").val(sName);
		}
		
		<?php
			$objIter = $objTH->GetIterator();
			
			$linked_to = -1;
			$colorAry	= array("SaddleBrown", "Black");
			$qtAry		= array(CConfig::QT_READ_COMP  => "RC", CConfig::QT_DIRECTIONS => "DR");
			$color		= $colorAry[0];
			$index = 0;
			foreach($objAnsAry as $secIndex => $ansSection)
			{
				foreach($ansSection as $qusIndex => $ansQuestion)
				{
					if($linked_to != $objIter[$secIndex][$qusIndex]['linked_to'] && $objIter[$secIndex][$qusIndex]['ques_type'] != CConfig::QT_NORMAL)
					{
						$linked_to = $objIter[$secIndex][$qusIndex]['linked_to'];
						
						$index++;
						$color	= $colorAry[$index%2];
					}
					else if($objIter[$secIndex][$qusIndex]['ques_type'] == CConfig::QT_NORMAL)
					{
						$color	= "Blue";
					}
					
					if((count($objAnsAry[$secIndex][$qusIndex]) > 0 && !in_array(-1, $objAnsAry[$secIndex][$qusIndex]) && !in_array(-2, $objAnsAry[$secIndex][$qusIndex])))
					{
						echo ("parent.menu.document.getElementById('".((($secIndex+1)*1000)+($qusIndex+1))."').innerHTML = '<b style=\"color:".$color.";\">".($qusIndex+1).(($objIter[$secIndex][$qusIndex]['ques_type'] == CConfig::QT_NORMAL) ? "" : " (".$qtAry[$objIter[$secIndex][$qusIndex]['ques_type']]."-".$objIter[$secIndex][$qusIndex]['linked_to'].")")."</b>&nbsp;<i style=\"background-color: OliveDrab;float:right;\" class=\"icon-pencil icon-white\"></i>';");
						
						if($nSection == $secIndex && $nQuestion == $qusIndex)
						{
							echo ("parent.menu.document.getElementById('".((($secIndex+1)*1000)+($qusIndex+1))."').setAttribute('style','background-color: BurlyWood;');");
						}
						else
						{
							echo ("parent.menu.document.getElementById('".((($secIndex+1)*1000)+($qusIndex+1))."').setAttribute('style','background-color: white;');");
						}
					}
					else if((count($objAnsAry[$secIndex][$qusIndex]) == 1 && in_array(-1, $objAnsAry[$secIndex][$qusIndex])))
					{
						echo ("parent.menu.document.getElementById('".((($secIndex+1)*1000)+($qusIndex+1))."').innerHTML = '<b style=\"color:".$color.";\">".($qusIndex+1).(($objIter[$secIndex][$qusIndex]['ques_type'] == CConfig::QT_NORMAL) ? "" : " (".$qtAry[$objIter[$secIndex][$qusIndex]['ques_type']]."-".$objIter[$secIndex][$qusIndex]['linked_to'].")")."</b>&nbsp;&nbsp;&nbsp;&nbsp;<i style=\"background-color: Orange;\" class=\"icon-bell icon-white\"></i>';");
						
						if($nSection == $secIndex && $nQuestion == $qusIndex)
						{
							echo ("parent.menu.document.getElementById('".((($secIndex+1)*1000)+($qusIndex+1))."').setAttribute('style','background-color: BurlyWood;');");
						}
						else
						{
							echo ("parent.menu.document.getElementById('".((($secIndex+1)*1000)+($qusIndex+1))."').setAttribute('style','background-color: white;');");
						}
					}
					else if((count($objAnsAry[$secIndex][$qusIndex]) == 1 && in_array(-2, $objAnsAry[$secIndex][$qusIndex])))
					{
						echo ("parent.menu.document.getElementById('".((($secIndex+1)*1000)+($qusIndex+1))."').innerHTML = '<b style=\"color:".$color.";\">".($qusIndex+1).(($objIter[$secIndex][$qusIndex]['ques_type'] == CConfig::QT_NORMAL) ? "" : " (".$qtAry[$objIter[$secIndex][$qusIndex]['ques_type']]."-".$objIter[$secIndex][$qusIndex]['linked_to'].")")."</b>&nbsp;<i style=\"background-color: Teal;margin-left: 55px;\" class=\"icon-flag icon-white\"></i>';");
						
						if($nSection == $secIndex && $nQuestion == $qusIndex)
						{
							echo ("parent.menu.document.getElementById('".((($secIndex+1)*1000)+($qusIndex+1))."').setAttribute('style','background-color: BurlyWood;');");
						}
						else
						{
							echo ("parent.menu.document.getElementById('".((($secIndex+1)*1000)+($qusIndex+1))."').setAttribute('style','background-color: #BCF5BC;');");
						}
					}
				}
			}
		?>
		// Make sure section is expanded under which current question belongs.
		parent.menu.ddaccordion.expandone('submenuheader', <?php echo($nSection);?> );
		</script>
	</body>
</html>
