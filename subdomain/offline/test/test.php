<!doctype html>
<?php
	include_once("../lib/session_manager.php");
	include_once('../database/mcat_db.php');
	include_once("lib/test_helper.php");
	include_once("../lib/site_config.php");
	include_once("../lib/utils.php");
	
	$objDB = new CMcatDB();
	
	$bIsTestStarted = $objDB->IsTestStartedByAdmin();
	
	$user_id   = "";
	$user_type = CConfig::UT_INSTITUTE;
	
	$bIsTestPublished = $objDB->IsTestPublished($_GET["test_id"]);
	$test_rating_score = 0;
	
	if($_GET["tschd_id"] == CConfig::FEUC_TEST_SCHEDULE_ID && $bIsTestPublished)
	{
		if(!isset($_COOKIE[CConfig::FEUC_NAME]))
		{
			setcookie(CConfig::FEUC_NAME,CUtils::uuid(), time()+(3600*24*30));
		}
		else
		{
			setcookie(CConfig::FEUC_NAME,$_COOKIE[CConfig::FEUC_NAME], time()+(3600*24*30));
		}
		$user_id = $_COOKIE[CConfig::FEUC_NAME];
		CSessionManager::Set(CSessionManager::BOOL_FREE_EZEEASSESS_USER, 1);
		printf("<script type='text/javascript'> var bIsFree = true;  </script>");
		
		if(isset($_COOKIE["already_rated_tests"]))
		{
			$rated_test_id_ary = explode(",",$_COOKIE["already_rated_tests"]);
			
			$bAlreadyRated = false;
			foreach($rated_test_id_ary as $rating)
			{
				$rating_ary = explode(";", $rating);
				if(in_array($_GET['test_id'], $rating_ary))
				{
					$bAlreadyRated = true;
					$test_rating_score = $rating_ary[1];
					break;
				}
			}
				
			if(!$bAlreadyRated)
			{
				printf("<script type='text/javascript'> var bIsTestRated = false;  </script>");
			}
			else 
			{
				printf("<script type='text/javascript'> var bIsTestRated = true;  </script>");
			}
		}
		else 
		{
			printf("<script type='text/javascript'> var bIsTestRated = false;  </script>");
		}
	}
	else 
	{
		printf("<script type='text/javascript'> var bIsFree = false;  </script>");
		// - - - - - - - - - - - - - - - - -
		// On Session Expire Load ROOT_URL
		// - - - - - - - - - - - - - - - - -
		CSessionManager::OnSessionExpire();
		// - - - - - - - - - - - - - - - - -
		
		if($_GET["tschd_id"] == CConfig::FEUC_TEST_SCHEDULE_ID)
		{
			header("Location: ".CSiteConfig::ROOT_URL);
		}
		$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
		$user_type = CSessionManager::Get(CSessionManager::INT_USER_TYPE);
	}
	
	$parsAry = parse_url(CUtils::curPageURL());
	$qry = split("[=&]", $parsAry["query"]);
	
	$menu_page = "menu.php";
	$start_page = "start.php";
	
	$test_id = null;
	$tschd_id = null;
	
	if(isset($_GET['test_id']))
	{
		$test_id = $_GET['test_id'];
		if(isset($_GET['tschd_id']))
		{
			$tschd_id = $_GET['tschd_id'];
		}
	}
	
	$test_name = $objDB->GetTestName($test_id);
	
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	// Check for number of attempt and expiration, if session exists.
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	$objTH = new CTestHelper();
	
	$bNewTest = true;
	$attempts = null;
	$nExpireSecOffset = null;
	$bTestSessionExpired = false;
	$bShowPreRestoreForm = false;
	
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
		$bNewTest = false;
		$bShowPreRestoreForm = ($bNewTest == false && $user_type == CConfig::UT_INDIVIDAL) ? true : false;
		
		$bRet = false;
		$attempts = $objTH->GetAttemptsFromTestSession($tsession_id, $bRet);
		$bShowPreRestoreForm = $bShowPreRestoreForm && $bRet ? true : false;
		
		$bTestSessionExpired = $objTH->IsTestSessionExpire($tsession_id, $nExpireSecOffset);
		
		if($bTestSessionExpired == true || $attempts == 0)
		{
			$objTH->EndExam($user_id, $test_id, $tschd_id);
		}
		
		if($attempts <= -1)
		{
			$attempts = "Unlimited";
		}
		
		$nExpireSecOffset = ($nExpireSecOffset == "NEVER") ? $nExpireSecOffset : abs($nExpireSecOffset);
		$sExpire = ($nExpireSecOffset == "NEVER") ? "Never Expire" : sprintf("%03d:%02d:%02d", floor($nExpireSecOffset/3600), floor(($nExpireSecOffset%3600) / 60), $nExpireSecOffset%60);
	
		$QuesInfoAry = $objTH->GetFirstUnattemptedQuesInfo($tsession_id);
		$btnText = "Resume Test !";
		$bTestPending = true;
	
	}
	else
	{
		$btnText = "Start Test !";
	}
	
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
	
	$test_type = $objDB->GetTestType($test_id);
	
	//echo("New Test: ".($bNewTest == true?"True":"False").", User Type: ".$user_type.", Expires: ".$sExpire."<br/>");
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
?>
<html>
	<head>
		<title> EZeeAssess: Assessment </title>
		<style type="text/css" title="currentStyle">
			@import "../core/media/css/ui-lightness/jquery-ui-1.8.21.custom.css";
			#dlg_test_end_confirm {
			
			    /* overlay is hidden before loading */
			    display:none;
			
			    /* standard decorations */
			    width:500px;
			    border:10px solid #390;
			    font-size:14px;
				
			    /* for modern browsers use semi-transparent color on the border. nice! */
			    border:10px solid rgba(33, 99, 00, 0.698);
			    background-color:#fff;
			
			    /* hot CSS3 features for mozilla and webkit-based browsers (rounded borders) */
			    -moz-border-radius:8px;
			    -webkit-border-radius:8px;
			}
			
			#dlg_test_end_confirm div {
			    padding:10px;
			    border:1px solid #3B5998;
			    background-color:#999;
			    font-family:"lucida grande",tahoma,verdana,arial,sans-serif;
			}
			
			#dlg_test_end_confirm h2 {
			    margin:-11px;
			    margin-bottom:0px;
			    color:#fff;
			    background-color:green;
			    padding:5px 10px;
			    border:1px solid #3B5998;
			    font-size:20px;
			}
			
			#dlg_rate_test{
			
			    /* overlay is hidden before loading */
			    display:none;
			
			    /* standard decorations */
			    width:350px;
			    border:10px solid #390;
			    font-size:14px;
				
			    /* for modern browsers use semi-transparent color on the border. nice! */
			    border:10px solid rgba(33, 99, 00, 0.698);
			    background-color:#fff;
			
			    /* hot CSS3 features for mozilla and webkit-based browsers (rounded borders) */
			    -moz-border-radius:8px;
			    -webkit-border-radius:8px;
			}
			
			#dlg_rate_test div {
			    font-family:"lucida grande",tahoma,verdana,arial,sans-serif;
			}
			
			#dlg_rate_test h2 {
			    margin:-11px;
			    margin-bottom:0px;
			    color:#fff;
			    background-color:green;
			    padding:5px 10px;
			    border:1px solid #3B5998;
			    font-size:20px;
			}
			
			.modal_overlapped {
			    display:    none;
			    position:   fixed;
			    z-index:    1000;
			    top:        0;
			    left:       0;
			    height:     100%;
			    width:      100%;
			    background: rgba( 255, 255, 255, .7 ) 
			                url('../images/page_loading.gif') 
			                50% 50% 
			                no-repeat;
			}
			
			/* When the body has the loading class, we turn
			   the scrollbar off with overflow:hidden */
			body.loading {
			    overflow: hidden;   
			}
			
			/* Anytime the body has the loading class, our
			   modal element will be visible */
			body.loading .modal_overlapped {
			    display: block;
			}
		</style>
		<link rel="stylesheet" type="text/css" href="../css/mipcat.css" />
		<link rel="stylesheet" type="text/css" href="../3rd_party/bootstrap/css/bootstrap.css" />
		<script type="text/javascript" src="../3rd_party/wizard/js/jquery.min.js"></script>
		<script type="text/javascript" charset="utf-8" src="../core/media/js/jquery-ui-1.8.21.custom.min.js"></script>
		<script type="text/javascript" charset="utf-8" src="../core/media/js/jquery.tools.min.js"></script>
		<script type="text/javascript" src="../3rd_party/raty/lib/jquery.raty.js"></script>
		<script type="text/javascript" charset="utf-8" src="../3rd_party/wizard/js/jquery.validate.min.js"></script>
		<script type="text/javascript" src="../3rd_party/bootstrap/js/bootstrap.js"></script>
	</head>
	<body style="font: 80% 'Trebuchet MS', sans-serif; margin: 5px;">
		<div class="modal_overlapped"></div>
		<div style="color:white;font-weight:bold;background-color:CornflowerBlue;padding:10px 10px;" id="header">
			<input type="button" id="btn_end_exam" class="btn btn-mini btn-danger" value="Close (X)" style="display:none;font-weight:bold;position:absolute; right:20px;top:12px"/><span>Test: <?php echo $test_name; ?></span>
		</div>
		
		<div id="pre_restore_log" style="margin-left: 25%;height:100px;<?php echo( $bShowPreRestoreForm == false ? "display:none;": "");?>">
			<?php
				if($bTestSessionExpired == true)
				{
					printf("<h4 style='width:50%%'>Test: %s, is expired %s (HHH:MM:SS) ago. Result is concluded and available now under <span style='color:blue'>Result Analytics</span> Section.</h4>", $test_name, $sExpire);
				}
				else if($attempts == 0 && $attempts != "Unlimited")
				{
					printf("<h4 style='width:50%%'>You have taken all attempts of this Test: %s. Result is concluded and available now under <span style='color:blue'>Result Analytics</span> Section.</h4>", $test_name, $sExpire);
				}
				else 
				{
			?>
				<form id="form_restore_log" method="post">
					<br/><br/>
					<div class="input-prepend">
						<span class="add-on"><b><i class="icon-shopping-cart"></i> Attempts Left:</b></span>
						<input style="color:red;font-weight:bold;" class="input-small span2" type="text" value="<?php echo($attempts); ?>" readonly="readonly"/>
					</div>
					<br/>
					<div class="input-prepend input-append">
						<span class="add-on"><b><i class="icon-time"></i> Expires in:</b></span>
						<input style="color:red;font-weight:bold;" class="input-small" type="text" value="<?php echo($sExpire); ?>" readonly="readonly"/>
						<span class="add-on"><b>(HHH:MM:SS)</b></span>
					</div>
					<br/>
					<div class="input-prepend">
						<span class="add-on"><b>Test Interruption Reason:</b></span>
						<select id="reason_list" onchange="OnReasonChange(this);">
							<option value="0">Power Failure</option>
							<option value="1">Browser Crash</option>
							<option value="2">Network or Connectivity Issue</option>
							<option value="3">Intentional (Browser Window) Close</option>
						</select>
					</div>
					<span id="div_spacing" style="display:none"><br/></span>
					<div class="input-prepend" id="reason_div" style="display:none">
						<span class="add-on"><b>Reason:</b></span>
						<input id="reason" class="input-xxlarge" type="text" name="reason" placeholder="Why did you closed down browser window?"/>
					</div>
					<br/>
					<input style="font-weight:bold;" class="btn btn-success" type="submit" value="Continue &gt;&gt;"/>
				</form>
			<?php
				}
			?>
		</div>
		
		<div id="div_test" style="<?php echo( $bShowPreRestoreForm == true ? "display:none": "");?>">
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
				<?php 
				if($test_type == CConfig::TT_DEFAULT)
				{
				?>
				<li style="color:red">For every correct answer you will be awarded <?php echo($nCorrect);?> marks and for every wrong answer <?php echo($nWrong);?> marks will be subtracted from total.<br/><br/></li>
				<?php 
				}
				else if($test_type == CConfig::TT_EQ)
				{
				?>
				<li style="color:red">Each option has a specific weightage. Choose atleast one option.<br/><br/></li>
				<?php
				}
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
						<?php
						}
						?>
					</ul><br/>
				</li>
				<?php
				}
				?>
				<li>In order to submit the answer, you need to press Submit Button otherwise it will not be considerd as attempted.</li>
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
			if($bIsTestStarted == 0 && !$bTestPending)
			{
			?>
			<p id='star_test_note' style="color:OrangeRed; font-weight:bold;">Note: Please wait until your test administrator allows you to start the test!<br/><br/></p>
			<?php 
			}
			?>
			<div style="text-align:center"><input style="font: 100% 'Trebuchet MS', sans-serif; font-weight:bold; margin: 5px;" id="btn_start_test" type="button" value="<?php echo($btnText);?>" <?php echo((($bIsTestStarted == 0 && !$bTestPending))?"disabled='disabled'":"");?>></div><hr/>
			<H3 style="text-align:center"><FONT COLOR="#990000">This evaluation test is designed with <acronym title="Organization Crafted Mechanism For Empirical Natural Selection [ &copy; Mastishka Intellisys Private Limited ]">OCMAFENS</acronym> method adapted by <?php echo(CConfig::SNC_SITE_NAME);?>.</FONT></H3>
			<?php 
			if(!empty($customInstrAry))
			{
			?>
			</div>
			<?php 
			}
			?>
		</div>
		<div id="div_result" style="display:none">
			<iframe id="frame_result" width="100%" frameborder="0" src="#" name="display"></iframe>
		</div>
		<div id="dlg_test_end_confirm" style="display:none">
		    <h2>End Exam Confirmation</h2>
				<p>Are you sure to end the exam? After confirmation your test progress will be submited for result and you will no longer be able to attempt this test again.</p>
				<p style="color:#666">
			      To cancel, click the No button or hit the ESC key.
			    </p>
			    <!-- yes/no buttons -->
			    <p style="text-align:center">
			      <input type="button" onclick="OnEndExam()" value="Yes">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" onclick="$('#dlg_test_end_confirm').overlay().close();" value="No">
			    </p>
		</div>
		
		<div id="dlg_rate_test" style="display:none;">
		    <h2 id='test_rating_heading'>Rate This Test</h2><br />
			<div style="margin-left:100px;" id="test_ratings"></div><hr />
			<p style="color:#666">
				To close, click the Close button.
			</p>
			<p style="text-align:center">
				<input id="rate_test_close" type="button" onclick="$('#dlg_rate_test').overlay().close();" value="Close" disabled>
			</p>
		</div>
		
		<div id="MessageModal" style="zindex:10000" class="modal hide fade">
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
		
		<script type="text/javascript">

			if(bIsFree)
			{
				if(bIsTestRated)
				{
					 $("#test_rating_heading").html("You have already rated following");
					 $("#rate_test_close").removeAttr('disabled');
					 $('#test_ratings').raty({ 
						 readOnly: true, 
						 score: <?php echo($test_rating_score);?>,
						 half      : true,
						 size      : 24,
						 starHalf  : '../3rd_party/raty/demo/img/star-half-big.png',
						 starOff   : '../3rd_party/raty/demo/img/star-off-big.png',
						 starOn    : '../3rd_party/raty/demo/img/star-on-big.png' 
					 });
				}
				else
				{
					$("#test_ratings").raty({
					    half      : true,
					    size      : 24,
					    starHalf  : '../3rd_party/raty/demo/img/star-half-big.png',
					    starOff   : '../3rd_party/raty/demo/img/star-off-big.png',
					    starOn    : '../3rd_party/raty/demo/img/star-on-big.png',
					    click: function(score) {
					        $("#rate_test_close").removeAttr('disabled');
					        bIsTestRated = true;
					        $(this).find('img').unbind();
					        $.post("ajax/ajax_rate_test.php",{'test_id':'<?php echo($_GET["test_id"]);?>', 'score': score}, function(){
						        
						    });
					    }
					});	
				}
			}

			if(!bIsFree)
			{
				$("table").height($(parent.window).height() - $("#header").height() - 30 );
				$("iframe").height($(parent.window).height() - $("#header").height() - 30 );
			}
			else
			{
				$("table").height(<?php echo($_GET['height']);?> - $("#header").height() - 30 );
				$("iframe").height(<?php echo($_GET['height']);?> - $("#header").height() - 30 );
			}
			//$( "#btn_end_exam", "#header" ).button();
			
			function CloseTestWithMsg(mgs, bClose)
			{
				$("#ModalMsgStr").html(mgs);
				$("#MessageModal").modal('show');
				if(bClose = 1)
				{
					OnEndExam();
				}
			}
			
			var bTestStarted = false;
			$("#btn_end_exam").click(function(){
				if(!bTestStarted)
				{
				if(!bIsFree)
					parent.HideOverlay();
				else						
					 window.parent.postMessage("HideOverlay", "<?php echo(CSiteConfig::FREE_ROOT_URL); ?>");
					<?php
					if($bTestSessionExpired == true || $attempts == 0)
					{
					?>
					if(!bIsFree)
						parent.RemoveTest();
					else										
					 window.parent.postMessage("RemoveTest", "<?php echo(CSiteConfig::FREE_ROOT_URL); ?>");					
					<?php
					}
					?>
				}
				else
				{
					if(OnEndExam.bExamEnded == false)
					{
						$('#dlg_test_end_confirm').overlay().load();
					}
					else 
					{
						OnEndExam();
					}
				}
			});
			
			OnEndExam.bExamEnded = false;
			function OnEndExam()
			{				
				if(OnEndExam.bExamEnded == false)
				{
					$("#div_test").hide();
					$("#frame_result").attr("src","end_exam.php?test_id=<?php echo($test_id); ?>"+"&tschd_id=<?php echo($tschd_id); ?>");
					$("#div_result").show();
					
					$("#btn_end_exam").val("Close (X)");
					$('#dlg_test_end_confirm').overlay().close();
					OnEndExam.bExamEnded = true;
					
					if(!bIsFree)
						parent.RemoveTest();
					else										
					 window.parent.postMessage("RemoveTest", "<?php echo(CSiteConfig::FREE_ROOT_URL); ?>");
				}
				else
				{
				if(!bIsFree)
					parent.HideOverlay();
				else										
					 window.parent.postMessage("HideOverlay", "<?php echo(CSiteConfig::FREE_ROOT_URL); ?>");					
				}
			}
			
			function HideOL()
			{
			 	if(!bIsFree)
					parent.HideOverlay();
				else										
					 window.parent.postMessage("HideOverlay", "<?php echo(CSiteConfig::FREE_ROOT_URL); ?>");
			}

			function ShowTestRating()
			{
				$('#dlg_rate_test').overlay().load();
			}
			
			function ShowLeftMenu()
			{
				$("#menu_iframe").show();
			}
			
			function OnTestStarted()
			{
				bTestStarted = true;
				$("#btn_end_exam").val("End Exam (X)");
			}
			
			function OnReasonChange(obj)
			{
				if(obj.value == 3)
				{
					$("#div_spacing").show();
					$("#reason_div").show();
				}
				else
				{
					$("#div_spacing").hide();
					$("#reason_div").hide();
				}
			}
			
			function SubmitRestoreLog()
			{
				var sReason = $("#reason_list option:selected").text();

				var current_date = new Date();
			    var time_zone = -current_date.getTimezoneOffset() / 60;
				
				if($("#reason_list option:selected").val() == 3)
				{
					sReason += " : " + $("#reason").val();
				}
				
				$('body').on({
				    ajaxStart: function() { 
				    	$(this).addClass("loading"); 
				    },
				    ajaxStop: function() { 
				    	$(this).removeClass("loading"); 
				    }    
				});
				
				$.getJSON("ajax/ajax_submit_restore_log.php?reason="+encodeURIComponent(sReason)+"&tsession_id="+encodeURIComponent('<?php echo($tsession_id); ?>')+"&time_zone="+time_zone, function(data) {
					$("#pre_restore_log").hide();
					$("#div_test").show();
				});
				
				return false;
			}
			
			jQuery.validator.addMethod("chars_not_allowed", function(value, element) {
				var str = $("#reason").val();
				
				return ( !( (str.indexOf("#") >= 0) || (str.indexOf(";") >= 0) ) );
			}, "<p style='color:red;'># (hash) & ; (semi-colon) characters are not allowed in reason!</p>");
			
			$("#form_restore_log").validate({
				errorPlacement: function(error, element) {
					$(error).insertAfter(element);
				}, rules: {
					'reason':			{required: true, 'chars_not_allowed': true}
				}, messages: {
					'reason':			{required: "<p style='color:red;'>Please provide valid reason!!</p>"}
				}, submitHandler: function(form) {
					SubmitRestoreLog();
				}
			});
				
			// select the overlay element - and "make it an overlay"
			$("#dlg_test_end_confirm").overlay({
				// custom top position
				top: 200,
				// some mask tweaks suitable for facebox-looking dialogs
				mask: {
					// you might also consider a "transparent" color for the mask
					color: '#06F',
					// load mask a little faster
					loadSpeed: 200,
					// very transparent
					opacity: 0.5
					},
				// disable this for modal dialog-type of overlays
				closeOnClick: false,
				// load it immediately after the construction
				load: false
			});

			$("#dlg_rate_test").overlay({
				// custom top position
				top: 200,
				// some mask tweaks suitable for facebox-looking dialogs
				mask: {
					// you might also consider a "transparent" color for the mask
					color: '#06F',
					// load mask a little faster
					loadSpeed: 200,
					// very transparent
					opacity: 0.5
					},
				// disable this for modal dialog-type of overlays
				closeOnClick: false,
				// load it immediately after the construction
				load: false,
				closeOnEsc: false
			});

			var bPageLoad = false;
			
			function SetBPageLoad(bVal)
			{
				bPageLoad = bVal;
			}

			function GetBPageLoad()
			{
				return bPageLoad;
			}


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
				window.location = "mipcat.php?test_id="+<?php echo($test_id); ?>+"&tschd_id="+<?php echo($tschd_id); ?>+"&sec="+<?php echo($QuesInfoAry['sec']); ?>+"&ques="+<?php echo($QuesInfoAry['ques']); ?>+langParams;
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
			if($bIsTestStarted == 0)
			{
			?>
			var bTestStartedByAdmin = false;
			setInterval(function(){
				if(!bTestStartedByAdmin)
				{
					$.ajax({
						url: "ajax/ajax_test_start_notification.php",
						async: false,
						dataType: 'json',
						success: function(data){
							$.each(data, function(key, value){
								if(value == 0)
								{
									//alert("hello");
								}
								else if(value == 1)
								{
									bTestStartedByAdmin = true;
									$("#btn_start_test").removeAttr("disabled");
									$("#star_test_note").hide();
								}
							});
						}
						
					});
				}
			},5000);
			<?php 
			}
			?>
			
		</script>
	</body>
</html>
