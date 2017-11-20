<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	include_once("../lib/session_manager.php");
	include_once("../database/mcat_db.php");
	include_once("lib/test_helper.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	//CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$bFreeEZeeAssesUser = CSessionManager::Get(CSessionManager::BOOL_FREE_EZEEASSESS_USER);
	
	$user_id = "";
	if($bFreeEZeeAssesUser == 1)
	{
		$user_id = $_COOKIE[CConfig::FEUC_NAME];
	}
	else 
	{
		$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	}
	
	$objDB = new CMcatDB();
	
	$parsAry = parse_url(CUtils::curPageURL());
	$qry = split("[=&]", $parsAry["query"]);
	
	$test_id = null;
	if($qry[0] == "test_id")
	{
		$test_id = $qry[1];
	}
	
	$tschd_id = null;
	if($qry[2] == "tschd_id")
	{
		$tschd_id = $qry[3];
	}
	
	$objTH = new CTestHelper();
	
	$objMCPAParams 	= $objTH->GetMCPAParams($test_id);
	$objTestParams 	= $objTH->GetTestParams($test_id, $objMCPAParams['is_static']);
	
	$customInstrAry	= $objDB->GetTestInstructions($test_id); 
	
	$nTime		= $objTestParams['test_duration'];
	$nQuestion 	= $objTestParams['max_question'];
	$nSection  	= $objTestParams['section_count'];
	$nCorrect  	= $objTestParams['marks_for_correct'];
	$nWrong		= $objTestParams['negative_marks'];
	
	$bFlash			= $objMCPAParams['mcpa_flash_ques'];
	$bLock			= $objMCPAParams['mcpa_lock_ques'];
	$bTranslation	= $objMCPAParams['allow_trans'];
	
	$bMCPA		= $bFlash || $bLock;
	
	$QuesInfoAry = array('sec'=>0, 'ques'=>0);
	$btnText = "";
	$bTestPending = false;
	$tsession_id = $objTH->IsTestPending($user_id, $test_id, $tschd_id);
	if($tsession_id != null)
	{
		$QuesInfoAry = $objTH->GetFirstUnattemptedQuesInfo($tsession_id);
		$btnText = "Resume Test !";
		$bTestPending = true;
	}
	else 
	{
		$btnText = "Start Test !";
	}
	
	$bRet = false;
	$attempts = $objTH->GetAttemptsFromTestSession($tsession_id, $bRet);
	if($attempts > 0 || $tsession_id == null)
	{
		CSessionManager::Set(CSessionManager::BOOL_DECR_ATTEMPT_COUNT, 1);
	}
	
	$langAry = "";
	if($objTestParams['ques_source'] == "mipcat")
	{
		$langAry = $objDB->GetDistLangFromQues();
	}
	else if($objTestParams['ques_source'] == "personal")
	{
		$langAry = $objDB->GetDistLangFromQues($objMCPAParams['owner_id']);
	}
	/*echo("<pre>");
	print_r($QuesInfoAry);
	echo("</pre>");*/
?>
<html>
	 <head>
	 	<title> </title>
	 	<style type="text/css" title="currentStyle">
			@import "../core/media/css/ui-lightness/jquery-ui-1.8.21.custom.css";
		</style>
	 	<link rel="stylesheet" type="text/css" href="../css/mipcat.css" />
	 	<link rel="stylesheet" type="text/css" href="../3rd_party/bootstrap/css/bootstrap.css" />
	 	<script type="text/javascript" src="../3rd_party/wizard/js/jquery.min.js"></script>
		<script type="text/javascript" charset="utf-8" src="../core/media/js/jquery-ui-1.8.21.custom.min.js"></script>
		<script type="text/javascript" src="../3rd_party/bootstrap/js/bootstrap.js"></script>
	 </head>
	
	 <body>
		<H2><FONT COLOR="#990000">Test Instructions</FONT></H2>
		<?php 
		if(!empty($customInstrAry))
		{
		?>
		<br />
		<div style="text-align:center">
			<select id="cust_instr_lang" onkeyup="OnCustInstrLangChange();" onkeydown="OnCustInstrLangChange();" onchange="OnCustInstrLangChange();">
			<?php 
				echo("<option value=''>--Select Language For Test Instructions--</option>");
				foreach($customInstrAry as $instLang=>$instr)
				{
					printf("<option value='%s'>%s</option>", $instLang, ucwords($instLang));
				}
			?>
			</select>
		</div><br />
		<fieldset id='cust_instr_field' style='display:none;'>
			<legend style="color: CornflowerBlue"><b>Test Instructions By Admin</b></legend>
			<div id='cust_instr'>
			</div>
		</fieldset><br />
		<div id="mipcat_test_instr" style="display:none;">
		<?php 
		}
		?>
		<ul>
			<li>This is a <?php echo($nTime);?> minutes test consisting <?php echo($nQuestion);?> questions distributed in <?php echo($nSection);?> sections (as listed on left hand side) with <?php echo(ucwords($objMCPAParams['pref_lang']));?> as its base language.<br/><br/></li>
			<li style="color:red">For every correct answer you will be awarded <?php echo($nCorrect);?> marks and for every wrong answer <?php echo($nWrong);?> marks will be subtracted from total.<br/><br/></li>
			<?php
			if($bMCPA)
			{
			?>
			<li>This test has adapted Mastishka Cheating Prevention Algorithm (MCPA), which states 
				<ul style="color:red">
					<?php
					if($bFlash)
					{
					?>
					<li>Once you viewed any question (and do not answer) the question will be changed from same group of questions.</li>
					<li>This test has adapted 'MCPA Flash' cheating prevention algorithm, you will not be able to mark or flag any question.</li>
					<?php
					}
					
					if($bLock)
					{
					?>
					<li>Once you answered any question it will be locked, i.e. once answered you can't change your answer.</li>
				</ul>
					<?php
					}
					?><br/>
			</li>
			<?php
			}
			?>
			<li>If you have attempted (answered) all questions before timer expiry, then you have to click "End Exam" to finish the exam (test).<br/><br/></li>
			<li>Once you click on Start Test button below the test will start and you will be able to see timer running in top right text box in green.<br/><br/></li>
			<?php 
			if($bTranslation)
			{
			?>
			<li>You can select other language than <?php echo(ucwords($objMCPAParams['pref_lang']));?> in which you want to appear the test. Do you want to choose a language for translation?<br/></li>
			<?php 
			}
			?>
		</ul>
		<?php 
		if($bTranslation)
		{
		?>
		<div style="text-align:center">
			<label class="radio inline">
				<input type="radio" id="trans_choice_yes" value='yes' name="trans_choice" onchange="OnTransChoiceChange();"> Yes
			</label>
			<label class="radio inline">
				<input type="radio" id="trans_choice_no" value='no' name="trans_choice" onchange="OnTransChoiceChange();" checked> No
			</label><br /><br />
			<select id="test_trans_lang" style="display:none;" name="test_trans_lang" onchange="OnTestLangChange();" onkeyup="OnTestLangChange();" onkeydown="OnTestLangChange();">
				<option value="">--Select Language For Translation--</option>
			<?php 
				for($langIndex = 0; $langIndex < count($langAry); $langIndex++)
				{
					//echo $objMCPAParams['pref_lang']."<br />";
					if($langAry[$langIndex] != $objMCPAParams['pref_lang'])
					{
						printf("<option value='%s'>%s</option>", $langAry[$langIndex], ucwords($langAry[$langIndex]));
					}
				}
			?>
			</select>
			<div id="trans_lang_choice_id" style="display:none;">
				<label><b>Choose Your Choice:</b></label>
				<label class="radio inline">
					<input type="radio" id="trans_lang_both" value="both" name="trans_lang_choice" checked> <p id='both'></p>
				</label>
				<label class="radio inline">
					<input type="radio" id="trans_lang_only" value="single" name="trans_lang_choice" > <p id='single'></p>
				</label><br /><br />
			</div>
		</div>
		<?php
		}
		if($bTestPending)
		{
		?>
		<p style="color:OrangeRed; font-weight:bold;">Note: You have an unfinshed test, that can be resumed by clicking the &rdquo;Resume Test!&ldquo; button below.<br/><br/></p>
		<?php
		}
		?>
		<div style="text-align:center"><input style="font: 75% 'Trebuchet MS', sans-serif; font-weight:bold; margin: 5px;" id="btn_start_test" type="button" value="<?php echo($btnText);?>"></div><hr/>
		<H3 style="text-align:center"><FONT COLOR="#990000">This evaluation test is designed with <acronym title="Organization Crafted Mechanism For Empirical Natural Selection [ &copy; Mastishka Intellisys Private Limited ]">OCMAFENS</acronym> method adapted by <?php echo(CConfig::SNC_SITE_NAME);?>.</FONT></H3>
		<?php 
		if(!empty($customInstrAry))
		{
		?>
		</div>
		<?php 
		}
		?>
		<script type="text/javascript">
			$( "#btn_start_test", "body" ).button();
			$("#btn_start_test").click(function(){

				var langParams = "";
				<?php 
				if($bTranslation)
				{
				?>
				if($("input[name=trans_choice]:checked").val() == "yes")
				{
					langParams = "&trans_lang_choice="+$("input[name=trans_lang_choice]:checked").val();
					langParams += "&test_trans_lang="+$("#test_trans_lang").val();
				}
				<?php 
				}
				?>
				window.location = "mipcat-tcs.php?test_id="+<?php echo($test_id); ?>+"&tschd_id="+<?php echo($tschd_id); ?>+"&sec="+<?php echo($QuesInfoAry['sec']); ?>+"&ques="+<?php echo($QuesInfoAry['ques']); ?>+langParams;
			});

			<?php 
			if(!empty($customInstrAry))
			{
			?>
			function OnCustInstrLangChange()
			{
				var lang = $("#cust_instr_lang").val();

				if(lang)
				{
					$("#cust_instr_field").show();
					$("#mipcat_test_instr").show();
					$("#cust_instr").load("ajax/ajax_get_cust_instructions.php",{test_id : '<?php echo($test_id); ?>', language : lang}, function() {
					});
				}
				else
				{
					$("#cust_instr_field").hide();
					$("#mipcat_test_instr").hide();
				}
			}
			<?php 
			}
			if($bTranslation)
			{
			?>
			function OnTransChoiceChange()
			{
				var val = $("input[name=trans_choice]:checked").val();

				if(val == "yes")
				{
					$("#test_trans_lang").show();
					$("select[name=test_trans_lang] option:first").attr("selected","selected");
					$("#btn_start_test").attr("disabled","disabled");
				}
				else
				{
					$("#test_trans_lang").hide();
					$("#trans_lang_choice_id").hide();
					$("#btn_start_test").removeAttr("disabled");
				}
			}

			function OnTestLangChange()
			{
				var lang = $("#test_trans_lang").val();

				if(lang)
				{
					$("#both").empty();
					$("#single").empty();
					$("#both").text("<?php echo(ucwords($objMCPAParams['pref_lang']));?> and "+lang.charAt(0).toUpperCase() + lang.slice(1)+" both");
					$("#single").text("Only "+lang.charAt(0).toUpperCase() + lang.slice(1));
					//$("#trans_lang_choice_id").show();
					$("#btn_start_test").removeAttr("disabled");
				}
				else
				{
					$("#trans_lang_choice_id").hide();
					$("#btn_start_test").attr("disabled","disabled");
				}
				
			}

			<?php 
			}
			?>
		</script>
	 </body>
</html>