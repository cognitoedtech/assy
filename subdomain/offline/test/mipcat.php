<!doctype html>
<?php
	include_once("../lib/session_manager.php");
	include_once("../lib/include_js_css.php");
	include_once("../lib/utils.php");
	include_once('../database/mcat_db.php');
	include_once("lib/test_helper.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	//CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	
	$objDB = new CMcatDB();
	
	$bFreeEZeeAssesUser = CSessionManager::Get(CSessionManager::BOOL_FREE_EZEEASSESS_USER);
	
	$sUserID = "";
	if($bFreeEZeeAssesUser == 1)
	{
		$sUserID = $_COOKIE[CConfig::FEUC_NAME];
		printf("<script type='text/javascript'> var bIsFree = true;  </script>");
	}
	else
	{
		$sUserID = CSessionManager::Get(CSessionManager::STR_USER_ID);
		printf("<script type='text/javascript'> var bIsFree = false;  </script>");
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
	if(isset($_GET['test_id']))
	{
		// The page is being called by clicking on question number or from Start Test button
		$nTestID = $_GET['test_id'];
		
		if(isset($_GET['tschd_id']))
		{
			$nTSchdID = $_GET['tschd_id'];
		}
		if(isset($_GET['sec']))
		{
			$nSection = $_GET['sec'];
		}
		if(isset($_GET['ques']))
		{
			$nQuestion = $_GET['ques'];
		}
		if(isset($_GET['trans_lang_choice']))
		{
			if(!empty($transLangChoice))
			{
				CSessionManager::UnsetSessVar(CSessionManager::STR_TRANS_LANG_CHOICE);
			}
			$transLangChoice = $_GET['trans_lang_choice'];
			CSessionManager::Set(CSessionManager::STR_TRANS_LANG_CHOICE, $transLangChoice);
		}
		if(isset($_GET['test_trans_lang']))
		{
			if(!empty($testTransLang))
			{
				CSessionManager::UnsetSessVar(CSessionManager::STR_TEST_TRANS_LANG);
			}
			$testTransLang = $_GET['test_trans_lang'];
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
		if(isset($_GET['curtime']) && $_GET['curtime'] != $session_time)
		{
			$nCurTime = $_GET['curtime'];
			
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
	
	$test_name = $objDB->GetTestName($nTestID);
	/*echo "Later:<pre>";
	print_r($objAnsAry);
	echo "</pre><br/>";*/
	
	//echo("Current Answer: ".$objAnsAry[$nSection][$nQuestion]);
	//echo $transLangChoice." ".$testTransLang." ".$aryQues['ques_id'];
	$objIncludeJsCSS = new IncludeJSCSS();
	
	$ques_type_ary = array(CConfig::QT_READ_COMP  => "Reading Comprehension Para", CConfig::QT_DIRECTIONS => "Direction");
	
	$bShowPara = 0;
	if(isset($_POST['showParaChoice']))
	{
		$bShowPara = $_POST['showParaChoice'];
	}
	else if(isset($_GET['showParaChoice']) && !empty($_GET['showParaChoice']))
	{
		$bShowPara = $_GET['showParaChoice'];
	}
	
	$prev_linked_to = -1;
	if(isset($_POST['prev_linked_to']))
	{
		$prev_linked_to = $_POST['prev_linked_to'];
	}
	else if(isset($_GET['prev_linked_to']) && !empty($_GET['prev_linked_to']))
	{
		$prev_linked_to = $_GET['prev_linked_to'];
	}
	
	$bShowSections = 1;
	if(isset($_POST['showSectionChoice']))
	{
		$bShowSections = $_POST['showSectionChoice'];
	}
	else if(isset($_GET['showSectionChoice']) && !empty($_GET['showSectionChoice']))
	{
		$bShowSections = $_GET['showSectionChoice'];
	}
	
	if((isset($_POST['section']) &&  $_POST['section'] != $nSection) || (isset($_GET['sec']) &&  $_GET['sec'] != $nSection))
	{
		$bShowSections = 1;
	}
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
		<?php 
			$objIncludeJsCSS->IncludeMetroBootstrapCSS("../");
			//$objIncludeJsCSS->IncludeIconFontCSS("../");
		?>
		<link rel="stylesheet" type="text/css" href="../css/mipcat.css" />
		<link rel="stylesheet" type="text/css" href="../3rd_party/bootstrap/css/bootstrap.css" />
		<link rel="stylesheet" type="text/css" href="../core/media/css/jquery.snippet.css" />
		<script type="text/javascript" src="../3rd_party/wizard/js/jquery.min.js"></script>
		<script type="text/javascript" charset="utf-8" src="../core/media/js/jquery-ui-1.8.21.custom.min.js"></script>
		<script type="text/javascript" src="../core/media/js/jquery.snippet.js"></script>
		<script type="text/javascript" src="../3rd_party/bootstrap/js/bootstrap.js"></script>
		<script type="text/javascript" src="../js/mipcat/utils.js"></script>
		<?php 
			$objIncludeJsCSS->IncludeMetroMinJS("../");
		?>
		<style type="text/css">
		
		.selected_sec_name {
			float:left;
			display: inline-block;
			border:1px solid #aaa;
			padding:5px;
			margin-top : 5px;
		 }
		 
		 .timer {
			float:right;
		 }
		 
		@media (max-width: 480px) {
			
			.modal { 
			    position: absolute; 
			    top: 3%; 
			    right: 3%; 
			    left: 3%; 
			    width: auto; 
			    margin: 0; 
			}
			.modal-body { 
			    height: 60%; 
			}
			
		.selected_sec_name {
			float:none;
		 }
		 
		 .timer {
			float:none;
		 }
	
		}
		
		@media (max-width: 767px) {
			
			.modal { 
			    position: absolute; 
			    top: 3%; 
			    right: 3%; 
			    left: 3%; 
			    width: auto; 
			    margin: 0; 
			}
			.modal-body { 
			    height: 60%; 
			}
			
		.selected_sec_name {
			float:none;
		 }
		 
		 .timer {
			float:none;
		 }
	
		}
		
		.modal-body { 
		    max-height: 350px; 
		    padding: 15px; 
		    overflow-y: auto; 
		    -webkit-overflow-scrolling: touch; 
		 }
		</style>
	</head>
	<body style="margin: 5px;">
		
		<div style="color:white;font-weight:bold;background-color:CornflowerBlue;padding:10px 10px;" id="header">
			<input type="button" id="btn_end_exam" class="btn btn-mini btn-danger" value="End Exam (X)" style="font-weight:bold;float: right;"/><span>Test: <?php echo $test_name; ?></span>
		</div>
		
		<div class="container-fluid">
		<div class="row-fluid" style="text-align: center;">
			<span class="selected_sec_name"><?php printf("<b><i class='icon-tasks icon-black'></i>&nbsp;Section: <span style='color:FireBrick;'>%s</span></b>",$sSectionName);?></span>
			<span class="metro" id="sec_ques_info">
				<button class="info" style="margin-top: 5px;">Current</button>
				<button class="success" style="margin-top: 5px;">Attempted</button>
				<button class="warning" style="margin-top: 5px;">Flagged</button>
				<button style="margin-top: 5px;"><i class='icon-align-justify on-left'></i>&nbsp;Reading Comprehension Group</button>
				<button style="margin-top: 5px;"><i class='icon-arrow-right on-left'></i>&nbsp;Direction Group</button>
			</span>
			<span class="timer"><input type="text" class="input-medium search-query" size="8" id="timer" style="text-align:center;color:#009900;font-weight: bold;width: 180px;height: 30px; margin-top: 5px;"></span>
		</div><br />
		<div>
			<button type="button" onclick="ToggleSections();" class="btn btn-primary" id="toggle_sec" style="font-weight:bold;margin-top: 5px;margin-left: 7px;float: left;"></button>
		</div>
    	<br/><br/>
    	<div class="metro" id="section_info">
			<div class="tab-control" data-role="tab-control" data-effect="fade">
				<ul class="tabs">
				<?php 
					$arySection = $objTH->GetSectionDetails($nTestID);
						
					$secIndex = 0;
					foreach($arySection as $key => $Section)
					{	
						if(!empty($Section['name']))
						{
							if($secIndex == $nSection)
								printf("<li class='active'><a href='#%s_questions'><b style='color: blue;'>%s</b></a></li>\n",$Section['name'], $Section['name']);
							else
								printf("<li><a href='#%s_questions'><b style='color: blue;'>%s</b></a></li>\n",$Section['name'], $Section['name']);
							
						}
						$secIndex++;
					}
				?>
				</ul>
				<div class="frames">
					<?php 
						$secIndex = 0;
						foreach($arySection as $key => $Section)
						{
							printf("<div class='frame' id='%s_questions'>", $Section['name']);
							for($ques = 0; $ques < $Section['questions']; $ques++)
							{
								printf("<button style='margin-top: 5px;' onClick='LoadQuestion(%d, %d, %d, %d);' id='%d'>%d</button>\n", $nTestID, $nTSchdID, $ques, $secIndex, (($secIndex+1)*1000)+($ques+1), ($ques+1));
							}
							printf("</div>");
							$secIndex++;
						}
					?>
				</div>
			</div>
		</div><br />
		
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
			</div><br />
			
			<button type="button" class="btn btn-primary" onclick="TogglePara()" id="toggle_para" style="<?php echo($aryQues['ques_type'] == CConfig::QT_NORMAL ? "display:none;" : "");?>">
				
			</button><br /><br />
			
	    	<div class="well" id="base_para" style="overflow: auto;border:1px solid #aaa;max-height:250px;<?php echo($aryQues['ques_type'] == CConfig::QT_NORMAL ? "display:none;" : "");?>">
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
	    	</div>
	    	
	    	<?php 
	    	if(!empty($aryTransQues))
	    	{
	    	?>
	    	<div id="trans_para" style="overflow: auto;border:1px solid #aaa;padding:5px;max-height:250px;display:none;">
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
                               $ques_cnts =  str_replace("\n","<br />",$aryQues['question']);
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
                           		$ques_cnts = str_replace("\n","<br />",$aryTransQues['question']);
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
					?><br/>
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
			<input type="hidden" id="showParaChoice" name="showParaChoice"/>
			<input type="hidden" id="prev_linked_to" name="prev_linked_to" value="<?php echo($aryQues['linked_to']);?>">
			<input type="hidden" id="showSectionChoice" name="showSectionChoice">
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
					$flag_btn_val = in_array(-2, $objAnsAry[$nSection][$nQuestion])?"Unflag":"Flag";
					$flag_val	  = in_array(-2, $objAnsAry[$nSection][$nQuestion])?2:1;
					echo('<br/><br/><input type="reset" class="btn btn-inverse"/>&nbsp;&nbsp;&nbsp;&nbsp;');
					echo(($objMCPAParams['mcpa_flash_ques'] != 1)?'<input type="submit" onclick="SetFlag('.$flag_val.');" class="btn btn-warning" id="flag_ques" name="btn2" value="'.$flag_btn_val.'" disabled/>&nbsp;&nbsp;&nbsp;&nbsp;':'');
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
		
		<div class="modal hide fade in" id="dlg_test_end_confirm" role="dialog" tabindex="-1">
		   	<div class="modal-header">
		   		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		  		<h3>End Exam Confirmation</h3>
			</div>
		   	<div class="modal-body">
		   		<p>Are you sure to end the exam? After confirmation your test progress will be submited for result and you will no longer be able to attempt this test again.</p>
		   	</div>
		   	<div class="modal-footer">
		   		<a href="#" class="btn btn-success" onclick="OnEndExam()">Yes</a>
		   		<a href="#" class="btn" data-dismiss="modal">No</a>
		   	</div>
		</div>
		
		<div id="MessageModal" role="dialog" tabindex="-1" class="modal hide fade in">
		  <div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		    <h3><?php echo(CConfig::SNC_SITE_NAME);?> - Closing Test</h3>
		  </div>
		  <div id="ModalMsgStr" class="modal-body">
		  	
		  </div>
		  <div class="modal-footer">
		    <a href="#" onclick="HideOL(); $('#MessageModal').modal('hide');" class="btn">Close</a>
		  </div>
		</div>
		</div>
		<script  language="JavaScript" type="text/javascript">
		//parent.ShowLeftMenu();
		OnTransChoiceChange();
		
		window.onload = function() {
			TestTimer();
			HeartBeat();
			$("div.mipcat_code_ques").snippet("c",{style:"vim"});
			$("#submit_ans").removeAttr('disabled');
			<?php
			if($objMCPAParams['mcpa_flash_ques'] != 1)
			{ 
			?>
			$("#flag_ques").removeAttr('disabled');
			<?php 
			}
			if($aryQues['ques_type'] != CConfig::QT_NORMAL)
			{
			?>
			TogglePara();
			<?php 
			}
			?>

			ToggleSections();
		}

		$("#btn_end_exam").click(function(){
			if(OnEndExam.bExamEnded == false)
			{
				$('#dlg_test_end_confirm').modal("show");
			}
			else 
			{
				OnEndExam();
			}
		});

		OnEndExam.bExamEnded = false;
		function OnEndExam()
		{				
			if(OnEndExam.bExamEnded == false)
			{
				$('#dlg_test_end_confirm').modal("hide");
				OnEndExam.bExamEnded = true;
				window.location = "end_exam.php?test_id=<?php echo($nTestID); ?>"+"&tschd_id=<?php echo($nTSchdID); ?>";
			}
			else
			{
				if(!bIsFree)
					parent.HideOverlay();
				else										
					 window.parent.postMessage("HideOverlay", "<?php echo(CSiteConfig::FREE_ROOT_URL); ?>");					
			}
		}

		function CloseTestWithMsg(mgs, bClose)
		{
			$("#ModalMsgStr").html(mgs);
			$("#MessageModal").modal('show');
			if(bClose = 1)
			{
				OnEndExam();
			}
		}

		function HideOL()
		{
		 	if(!bIsFree)
				parent.HideOverlay();
			else										
				 window.parent.postMessage("HideOverlay", "<?php echo(CSiteConfig::FREE_ROOT_URL); ?>");
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
				OnEndExam();
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
							
							CloseTestWithMsg("<p style='color:red'>Your test administrator has instructed to end this test. <b>We have forcefully ended this test</b>. Please contact your test administrator for reasons.</p>", 1);
				   			return;
						}
					} ).fail(function() { 
						connection_error_count++;
						if(connection_error_count >= 6)
						{
							connection_error_count = 0;
							clearTimeout(TestTimer.hTimer);
							clearTimeout(HeartBeat.hTimer);
							
							CloseTestWithMsg("<p style='color:red'>Can't connect to <?php echo(CSiteConfig::ROOT_URL);?> server, there might be problems with your internet connection. automatically closing test. Please resume test when you have internet connectivity.</p>", 0);
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

		function LoadQuestion(test_id, tschd_id, ques, sec)
		{
			
			if(document.getElementById("timer") == null)
			{
				//alert ("mipcat.php?test_id="+test_id+"&sec="+sec+"&ques="+ques);
				location = "mipcat.php?test_id="+test_id+"&tschd_id="+tschd_id+"&sec="+sec+"&ques="+ques+"&showParaChoice="+$("#showParaChoice").val()+"&prev_linked_to="+$("#prev_linked_to").val()+"&showSectionChoice="+$("#showSectionChoice").val();
			}
			else
			{
				//alert ("mipcat.php?test_id="+test_id+"&sec="+sec+"&ques="+ques+"&curtime="+encodeURIComponent(parent.display.TestTimer.CurTime));
				var nCurTime = TestTimer.CurTime;
				if( !( nCurTime) )
				{
					$.getJSON("ajax/ajax_get_elapsed_time.php?test_id="+test_id+"&tschd_id="+tschd_id, function(data) {
						if(data['TestCurTime'])
						{
							nCurTime = data['TestCurTime'];
						}
					});
				}
					
				location = "mipcat.php?test_id="+test_id+"&tschd_id="+tschd_id+"&sec="+sec+"&ques="+ques+"&curtime="+encodeURIComponent(nCurTime)+"&showParaChoice="+$("#showParaChoice").val()+"&prev_linked_to="+$("#prev_linked_to").val()+"&showSectionChoice="+$("#showSectionChoice").val();
			}
		}
		
		<?php
			$objIter = $objTH->GetIterator();
			
			$linked_to = -1;
			$colorAry	= array("SaddleBrown", "Black");
			$qtAry		= array(CConfig::QT_READ_COMP  => "RC", CConfig::QT_DIRECTIONS => "DR");
			$color		= $colorAry[0];
			$index = 0;
			$icon_ary = array(CConfig::QT_READ_COMP  => "<i class='icon-align-justify'></i>", CConfig::QT_DIRECTIONS => "<i class='icon-arrow-right'></i>");
			$class_ary = array("default", "danger");
			
			foreach($objAnsAry as $secIndex => $ansSection)
			{
				foreach($ansSection as $qusIndex => $ansQuestion)
				{
					if($linked_to != $objIter[$secIndex][$qusIndex]['linked_to'] && $objIter[$secIndex][$qusIndex]['ques_type'] != CConfig::QT_NORMAL)
					{
						$linked_to = $objIter[$secIndex][$qusIndex]['linked_to'];
						$index++;
						
						printf("$(\"<button style='margin-right: -4px; margin-top: 4px;height: 26px;' onclick='triggerClick(%s)'>%s</button>\").insertBefore('#%s');", ((($secIndex+1)*1000)+($qusIndex+1)), $icon_ary[$objIter[$secIndex][$qusIndex]['ques_type']], ((($secIndex+1)*1000)+($qusIndex+1)));
						
						if(isset($objIter[$secIndex][$qusIndex+1]['linked_to']) && $linked_to == $objIter[$secIndex][$qusIndex+1]['linked_to'])
						{
							echo ("document.getElementById('".((($secIndex+1)*1000)+($qusIndex+1))."').setAttribute('style','margin-top: 5px;margin-right: -4px;');");
						}
						$color	= $colorAry[$index%2];
						
						if($nSection == $secIndex && $nQuestion == $qusIndex && $prev_linked_to != $objIter[$secIndex][$qusIndex]['linked_to'])
						{
							$bShowPara = 1;
						}
					}
					else if($linked_to == $objIter[$secIndex][$qusIndex]['linked_to'] && $objIter[$secIndex][$qusIndex]['ques_type'] != CConfig::QT_NORMAL)
					{
						if(isset($objIter[$secIndex][$qusIndex+1]['linked_to']) && $linked_to == $objIter[$secIndex][$qusIndex+1]['linked_to'])
						{
							echo ("document.getElementById('".((($secIndex+1)*1000)+($qusIndex+1))."').setAttribute('style','margin-top: 5px;margin-right: -4px;');");
						}
						
						if($nSection == $secIndex && $nQuestion == $qusIndex && $prev_linked_to != $objIter[$secIndex][$qusIndex]['linked_to'])
						{
							$bShowPara = 1;
						}
					}
					else if($objIter[$secIndex][$qusIndex]['ques_type'] == CConfig::QT_NORMAL)
					{
						$color	= "Blue";
					}
						
					if((count($objAnsAry[$secIndex][$qusIndex]) > 0 && !in_array(-1, $objAnsAry[$secIndex][$qusIndex]) && !in_array(-2, $objAnsAry[$secIndex][$qusIndex])))
					{
						if($nSection == $secIndex && $nQuestion == $qusIndex)
						{
							echo ("document.getElementById('".((($secIndex+1)*1000)+($qusIndex+1))."').setAttribute('class','info');");
						}
						else
						{
							echo ("document.getElementById('".((($secIndex+1)*1000)+($qusIndex+1))."').setAttribute('class','success');");
						}
					}
					else if((count($objAnsAry[$secIndex][$qusIndex]) == 1 && in_array(-1, $objAnsAry[$secIndex][$qusIndex])))
					{
						if($nSection == $secIndex && $nQuestion == $qusIndex)
						{
							echo ("document.getElementById('".((($secIndex+1)*1000)+($qusIndex+1))."').setAttribute('class','info');");
						}
					}
					else if((count($objAnsAry[$secIndex][$qusIndex]) == 1 && in_array(-2, $objAnsAry[$secIndex][$qusIndex])))
					{
						if($nSection == $secIndex && $nQuestion == $qusIndex)
						{
							echo ("document.getElementById('".((($secIndex+1)*1000)+($qusIndex+1))."').setAttribute('class','info');");
						}
						else
						{
							echo ("document.getElementById('".((($secIndex+1)*1000)+($qusIndex+1))."').setAttribute('class','warning');");
						}
					}
				}
			}
		?>

		function triggerClick(id)
		{
			$("#"+id).trigger("click");
		}

		var bShowSections = <?php echo($bShowSections);?>;
		function ToggleSections()
		{
			if(bShowSections)
			{
				$("#showSectionChoice").val("1");
				$("#toggle_sec").html("<i class='icon-minus icon-white'></i> Hide Sections");
				$("#sec_ques_info").show();
				$("#section_info").show();
				bShowSections = false;
			}
			else
			{
				$("#showSectionChoice").val("0");
				$("#toggle_sec").html("<i class='icon-plus icon-white'></i> Show Sections");
				$("#sec_ques_info").hide();
				$("#section_info").hide();
				bShowSections = true;
			}
		}

		<?php 
		if($aryQues['ques_type'] != CConfig::QT_NORMAL)
		{
		?>
		var bShowPara = <?php echo($bShowPara);?>;
		function TogglePara()
		{
			var trans_val = $("input[name=trans_choice]:checked").val();
			if(bShowPara)
			{
				$("#toggle_para").html("<i class='icon-minus icon-white'></i> Hide <?php echo($ques_type_ary[$aryQues['ques_type']]);?>");
				$("#showParaChoice").val("1");
				if(trans_val == "base")
				{
					$("#base_para").show();
				}
				else
				{
					$("#trans_para").show();
				}
				bShowPara = false;
			}
			else
			{
				$("#toggle_para").html("<i class='icon-plus icon-white'></i> Show <?php echo($ques_type_ary[$aryQues['ques_type']]);?>");
				$("#showParaChoice").val("0");
				if(trans_val == "base")
				{
					$("#base_para").hide();
				}
				else
				{
					$("#trans_para").hide();
				}
				bShowPara = true;
			}
		}
		<?php 
		}
		?>
		</script>
	</body>
</html>
