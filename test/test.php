<!doctype html>
<?php
include_once ("../lib/session_manager.php");
include_once ('../database/mcat_db.php');
include_once ("lib/test_helper.php");
include_once ("../lib/site_config.php");
include_once ("../lib/utils.php");
include_once (dirname ( __FILE__ ) . "/../lib/include_js_css.php");

$objIncludeJsCSS = new IncludeJSCSS ();

$objDB = new CMcatDB ();

$user_id = "";
$user_type = CConfig::UT_INSTITUTE;

$bIsTestPublished = $objDB->IsTestPublished ( $_GET ["test_id"] );
$test_rating_score = 0;

if ($_GET ["tschd_id"] == CConfig::FEUC_TEST_SCHEDULE_ID && $bIsTestPublished) {
	if (! isset ( $_COOKIE [CConfig::FEUC_NAME] )) {
		setcookie ( CConfig::FEUC_NAME, CUtils::uuid (), time () + (3600 * 24 * 30) );
	} else {
		setcookie ( CConfig::FEUC_NAME, $_COOKIE [CConfig::FEUC_NAME], time () + (3600 * 24 * 30) );
	}
	$user_id = $_COOKIE [CConfig::FEUC_NAME];
	CSessionManager::Set ( CSessionManager::BOOL_FREE_EZEEASSESS_USER, 1 );
	printf ( "<script type='text/javascript'> var bIsFree = true;  </script>" );
	
	if (isset ( $_COOKIE ["already_rated_tests"] )) {
		$rated_test_id_ary = explode ( ",", $_COOKIE ["already_rated_tests"] );
		
		$bAlreadyRated = false;
		foreach ( $rated_test_id_ary as $rating ) {
			$rating_ary = explode ( ";", $rating );
			if (in_array ( $_GET ['test_id'], $rating_ary )) {
				$bAlreadyRated = true;
				$test_rating_score = $rating_ary [1];
				break;
			}
		}
		
		if (! $bAlreadyRated) {
			printf ( "<script type='text/javascript'> var bIsTestRated = false;  </script>" );
		} else {
			printf ( "<script type='text/javascript'> var bIsTestRated = true;  </script>" );
		}
	} else {
		printf ( "<script type='text/javascript'> var bIsTestRated = false;  </script>" );
	}
} else {
	printf ( "<script type='text/javascript'> var bIsFree = false;  </script>" );
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire ();
	// - - - - - - - - - - - - - - - - -
	
	if ($_GET ["tschd_id"] == CConfig::FEUC_TEST_SCHEDULE_ID) {
		header ( "Location: " . CSiteConfig::ROOT_URL );
	}
	$user_id = CSessionManager::Get ( CSessionManager::STR_USER_ID );
	$user_type = CSessionManager::Get ( CSessionManager::INT_USER_TYPE );
}

$parsAry = parse_url ( CUtils::curPageURL () );
$qry = split ( "[=&]", $parsAry ["query"] );

$menu_page = "menu.php";
$start_page = "start.php";

$test_id = null;
$tschd_id = null;
if ($qry [0] == "test_id") {
	$test_id = $qry [1];
	
	if ($qry [2] == "tschd_id") {
		$tschd_id = $qry [3];
	}
	
	// $menu_page .= "?test_id=".$qry[1]."&tschd_id=".$qry[3];
	// $start_page .= "?test_id=".$qry[1]."&tschd_id=".$qry[3];
}
$test_name = $objDB->GetTestName ( $test_id );

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// Check for number of attempt and expiration, if session exists.
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
$objTH = new CTestHelper ();

$bNewTest = true;
$attempts = null;
$nExpireSecOffset = null;
$bTestSessionExpired = false;
$bShowPreRestoreForm = false;

$objMCPAParams = $objTH->GetMCPAParams ( $test_id );
$objTestParams = $objTH->GetTestParams ( $test_id, $objMCPAParams ['is_static'] );

$customInstrAry = $objDB->GetTestInstructions ( $test_id );

$nTime = $objTestParams ['test_duration'];
$nQuestion = $objTestParams ['max_question'];
$nSection = $objTestParams ['section_count'];
$nCorrect = $objTestParams ['marks_for_correct'];
$nWrong = $objTestParams ['negative_marks'];

$bFlash = $objMCPAParams ['mcpa_flash_ques'];
$bLock = $objMCPAParams ['mcpa_lock_ques'];
$bTranslation = $objMCPAParams ['allow_trans'];

$bMCPA = $bFlash || $bLock;

$QuesInfoAry = array ('sec' => 0, 'ques' => 0 );
$btnText = "";
$bTestPending = false;

$tsession_id = $objTH->IsTestPending ( $user_id, $test_id, $tschd_id );
if ($tsession_id != null) {
	$bNewTest = false;
	$bShowPreRestoreForm = ($bNewTest == false && $user_type == CConfig::UT_INDIVIDAL) ? true : false;
	
	$bRet = false;
	$attempts = $objTH->GetAttemptsFromTestSession ( $tsession_id, $bRet );
	$bShowPreRestoreForm = $bShowPreRestoreForm && $bRet ? true : false;
	
	$bTestSessionExpired = $objTH->IsTestSessionExpire ( $tsession_id, $nExpireSecOffset );
	
	if ($bTestSessionExpired == true || $attempts == 0) {
		$objTH->EndExam ( $user_id, $test_id, $tschd_id );
	}
	
	if ($attempts <= - 1) {
		$attempts = "Unlimited";
	}
	
	$nExpireSecOffset = ($nExpireSecOffset == "NEVER") ? $nExpireSecOffset : abs ( $nExpireSecOffset );
	$sExpire = ($nExpireSecOffset == "NEVER") ? "Never Expire" : sprintf ( "%03d:%02d:%02d", floor ( $nExpireSecOffset / 3600 ), floor ( ($nExpireSecOffset % 3600) / 60 ), $nExpireSecOffset % 60 );
	
	$QuesInfoAry = $objTH->GetFirstUnattemptedQuesInfo ( $tsession_id );
	$btnText = "Resume Test !";
	$bTestPending = true;

} else {
	$btnText = "Start Test !";
}

if ($attempts > 0 || $tsession_id == null) {
	CSessionManager::Set ( CSessionManager::BOOL_DECR_ATTEMPT_COUNT, 1 );
}

$langAry = "";
if ($objTestParams ['ques_source'] == "mipcat") {
	$langAry = $objDB->GetDistLangFromQues ();
} else if ($objTestParams ['ques_source'] == "personal") {
	$langAry = $objDB->GetDistLangFromQues ( $objMCPAParams ['owner_id'] );
}

$test_type = $objDB->GetTestType ( $test_id );

// echo("New Test: ".($bNewTest == true?"True":"False").", User Type:
// ".$user_type.", Expires: ".$sExpire."<br/>");
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
?>
<html>
<head>
<meta charset="UTF-8">
<meta name="Generator" content="">
<meta name="Author" content="">
<meta name="Keywords" content="">
<meta name="Description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title> 
	<?php echo(CConfig::SNC_SITE_NAME);?> </title>
	<?php
	$objIncludeJsCSS->CommonIncludeCSS ( "../" );
	
	$objIncludeJsCSS->CommonIncludeJS ( "../" );
	$objIncludeJsCSS->IncludeJqueryFormJS ( "../" );
	$objIncludeJsCSS->IncludeJqueryValidateMinJS ( "../", "1.16.0" );
	$objIncludeJsCSS->IncludeMathJAXJS( "../" );
	$objIncludeJsCSS->IncludeJqueryUI_1_12_1_JS("../");
	?>
	<style type="text/css" title="currentStyle">
		.modal_overlapped {
			display: none;
			position: fixed;
			z-index: 1000;
			top: 0;
			left: 0;
			height: 100%;
			width: 100%;
			background: rgba(255, 255, 255, .7) url('../images/page_loading.gif')
				50% 50% no-repeat;
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
	<script type="text/javascript">
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

	  ga('create', 'UA-2246912-17', 'auto');
	  ga('send', 'pageview');
	</script>
</head>
<body>
	<div class="container">
		<div class="modal_overlapped"></div>
		<div
			style="color: white; font-weight: bold; background-color: CornflowerBlue; padding: 10px;"
			id="header">
			<span>Test: <?php echo $test_name; ?></span> <span class="pull-right"
				style="margin-top: -5px">
				<button id="btn_end_exam" class="btn btn-sm btn-danger">
					Close <i class="fa fa-window-close" aria-hidden="true"></i>
				</button>
			</span>
		</div>

		<div id="pre_restore_log" style="margin-left: 25%;height:100px;<?php echo( $bShowPreRestoreForm == false ? "display:none;": "");?>">
			<?php
			if ($bTestSessionExpired == true) {
				printf ( "<h4 style='width:50%%'>Test: %s, is expired %s (HHH:MM:SS) ago. Result is concluded and available now under <span style='color:blue'>Result Analytics</span> Section.</h4>", $test_name, $sExpire );
			} else if ($attempts == 0 && $attempts != "Unlimited") {
				printf ( "<h4 style='width:50%%'>You have taken all attempts of this Test: %s. Result is concluded and available now under <span style='color:blue'>Result Analytics</span> Section.</h4>", $test_name, $sExpire );
			} else {
				?>
				<form id="form_restore_log" method="post">
				<br /> <br />
				<div class="input-prepend">
					<span class="add-on"><b><i class="icon-shopping-cart"></i> Attempts
							Left:</b></span> <input style="color: red; font-weight: bold;"
						class="input-small span2" type="text"
						value="<?php echo($attempts); ?>" readonly="readonly" />
				</div>
				<br />
				<div class="input-prepend input-append">
					<span class="add-on"><b><i class="icon-time"></i> Expires in:</b></span>
					<input style="color: red; font-weight: bold;" class="input-small"
						type="text" value="<?php echo($sExpire); ?>" readonly="readonly" />
					<span class="add-on"><b>(HHH:MM:SS)</b></span>
				</div>
				<br />
				<div class="input-prepend">
					<span class="add-on"><b>Test Interruption Reason:</b></span> <select
						id="reason_list" onchange="OnReasonChange(this);">
						<option value="0">Power Failure</option>
						<option value="1">Browser Crash</option>
						<option value="2">Network or Connectivity Issue</option>
						<option value="3">Intentional (Browser Window) Close</option>
					</select>
				</div>
				<span id="div_spacing" style="display: none"><br /></span>
				<div class="input-prepend" id="reason_div" style="display: none">
					<span class="add-on"><b>Reason:</b></span> <input id="reason"
						class="input-xxlarge" type="text" name="reason"
						placeholder="Why did you closed down browser window?" />
				</div>
				<br /> <input style="font-weight: bold;" class="btn btn-success"
					type="submit" value="Continue &gt;&gt;" />
			</form>
			<?php
			}
			?>
		</div>

		<div id="div_test" style="<?php echo( $bShowPreRestoreForm == true ? "display:none": "");?>">
			<H2>
				<FONT COLOR="#990000">Test Instructions</FONT>
			</H2>
			<?php
			if (! empty ( $customInstrAry )) {
				?>
			<br />
			<div style="text-align: center">
				<select id="cust_instr_lang" onkeyup="OnCustInstrLangChange();"
					onkeydown="OnCustInstrLangChange();"
					onchange="OnCustInstrLangChange();">
				<?php
				echo ("<option value=''>--Select Language For Test Instructions--</option>");
				foreach ( $customInstrAry as $instLang => $instr ) {
					printf ( "<option value='%s'>%s</option>", $instLang, ucwords ( $instLang ) );
				}
				?>
				</select>
			</div>
			<br />
			<fieldset id='cust_instr_field' style='display: none;'>
				<legend style="color: CornflowerBlue">
					<b>Test Instructions By Admin</b>
				</legend>
				<div id='cust_instr'></div>
			</fieldset>
			<br />
			<div id="mipcat_test_instr" style="display: none;">
			<?php
			}
			?>
			<ol>
					<li>This is a <?php echo($nTime);?> minutes test consisting <?php echo($nQuestion);?> questions distributed in <?php echo($nSection);?> sections (as listed on top) with <?php echo(ucwords($objMCPAParams['pref_lang']));?> as its base language.<br />
						<br /></li>
				<?php
				if ($test_type == CConfig::TT_DEFAULT) {
					?>
				<li style="color: red">For every correct answer you will be awarded <?php echo($nCorrect);?> marks and for every wrong answer <?php echo($nWrong);?> marks will be subtracted from total.<br />
						<br /></li>
				<?php
				} else if ($test_type == CConfig::TT_EQ) {
					?>
				<li style="color: red">Each option has a specific weightage. Choose
						atleast one option.<br /> <br />
					</li>
				<?php
				}
				if ($bMCPA) {
					?>
				<li>This test has adapted Mastishka Cheating Prevention Algorithm
						(MCPA), which states
						<ol style="color: red">
						<?php
					if ($bFlash) {
						?>
						<li>Once you viewed any question (and do not answer) the question
								will be changed from same group of questions.</li>
							<li>This test has adapted 'MCPA Flash' cheating prevention
								algorithm, you will not be able to mark or flag any question.</li>
						<?php
					}
					
					if ($bLock) {
						?>
						<li>Once you answered any question it will be locked, i.e. once
								answered you can't change your answer.</li>
						<?php
					}
					?>
					</ol> <br />
					</li>
				<?php
				}
				?>
				<li>In order to submit the answer, you need to press Submit Button
						otherwise it will not be considerd as attempted.</li>
					<li>If you have attempted (answered) all questions before timer
						expiry, then you have to click "End Exam" to finish the exam
						(test).<br /> <br />
					</li>
					<li>Once you click on Start Test button below the test will start
						and you will be able to see timer running in top right text box in
						green.<br /> <br />
					</li>
				<?php
				if ($bTranslation) {
					?>
				<li>You can select other language than <?php echo(ucwords($objMCPAParams['pref_lang']));?> in which you want to appear the test. Do you want to choose a language for translation?<br /></li>
				<?php
				}
				?>
			</ol>
			<?php
			if ($bTranslation) {
				?>
			<div style="text-align: center">
					<label class="radio inline"> <input type="radio"
						id="trans_choice_yes" value='yes' name="trans_choice"
						onchange="OnTransChoiceChange();"> Yes
					</label> <label class="radio inline"> <input type="radio"
						id="trans_choice_no" value='no' name="trans_choice"
						onchange="OnTransChoiceChange();" checked> No
					</label><br /> <br /> <select id="test_trans_lang"
						style="display: none;" name="test_trans_lang"
						onchange="OnTestLangChange();" onkeyup="OnTestLangChange();"
						onkeydown="OnTestLangChange();">
						<option value="">--Select Language For Translation--</option>
				<?php
				for($langIndex = 0; $langIndex < count ( $langAry ); $langIndex ++) {
					// echo $objMCPAParams['pref_lang']."<br />";
					if ($langAry [$langIndex] != $objMCPAParams ['pref_lang']) {
						printf ( "<option value='%s'>%s</option>", $langAry [$langIndex], ucwords ( $langAry [$langIndex] ) );
					}
				}
				?>
				</select>
					<div id="trans_lang_choice_id" style="display: none;">
						<label><b>Choose Your Choice:</b></label> <label
							class="radio inline"> <input type="radio" id="trans_lang_both"
							value="both" name="trans_lang_choice" checked>
							<p id='both'></p>
						</label> <label class="radio inline"> <input type="radio"
							id="trans_lang_only" value="single" name="trans_lang_choice">
							<p id='single'></p>
						</label><br /> <br />
					</div>
				</div>
			<?php
			}
			if ($bTestPending) {
				?>
			<p style="color: OrangeRed; font-weight: bold;">
					Note: You have an unfinshed test, that can be resumed by clicking
					the &rdquo;Resume Test!&ldquo; button below.<br /> <br />
				</p>
			<?php
			}
			?>
			<div style="text-align: center">
					<input class="btn btn-success" id="btn_start_test" type="button"
						value="<?php echo($btnText);?>">
				</div>
				<hr />
				<H3 style="text-align: center">
					<FONT COLOR="#990000">This evaluation test is designed with <acronym
						title="Organization Crafted Mechanism For Empirical Natural Selection [ &copy; Mastishka Intellisys Private Limited ]">OCMAFENS</acronym> method adapted by <?php echo(CConfig::SNC_SITE_NAME);?>.</FONT>
				</H3>
			<?php
			if (! empty ( $customInstrAry )) {
				?>
			</div>
			<?php
			}
			?>
		</div>
		<div id="div_result" style="display: none">
			<iframe id="frame_result" width="100%" frameborder="0" src="#"
				name="display"></iframe>
		</div>
		<!-- Modal -->
		<div class="modal fade" id="dlg_test_end_confirm" tabindex="-1"
			role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"
							aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title" id="myModalLabel">End Exam Confirmation</h4>
					</div>
					<div class="modal-body">
						<p>Are you sure to end the exam? After confirmation your test
							progress will be submited for result and you will no longer be
							able to attempt this test again.</p>
						<p style="color: #666">To cancel, click the No button or hit the
							ESC key.</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
						<button type="button" onclick="OnEndExam()" class="btn btn-primary">Yes</button>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="dlg_rate_test" tabindex="-1"
			role="dialog" aria-labelledby="dlg_rate_test_label">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"
							aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title" id="dlg_rate_test_label">Rate This Test</h4>
					</div>
					<div class="modal-body">
						<div style="margin-left: 100px;" id="test_ratings"></div>
						<hr />
						<p style="color: #666">To close, click the Close button.</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
		
		<div class="modal fade" id="MessageModal" tabindex="-1"
			role="dialog" aria-labelledby="MessageModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"
							aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title" id="MessageModalLabel"><?php echo(CConfig::SNC_SITE_NAME);?> - Closing Test</h4>
					</div>
					<div id="ModalMsgStr" class="modal-body">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
		
		<script type="text/javascript">
			$(document).ready(function() {
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
			});
			
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
					parent.HideOverlay();
					
					<?php
					if ($bTestSessionExpired == true || $attempts == 0) 
					{
					?>
						parent.RemoveTest();					
					<?php
					}
					?>
				}
				else
				{
					if(OnEndExam.bExamEnded == false)
					{
						//$('#dlg_test_end_confirm').overlay().load();
						$('#dlg_test_end_confirm').modal('show');
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
					//$('#dlg_test_end_confirm').overlay().close();
					$('#dlg_test_end_confirm').modal('hide');
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
				//$('#dlg_rate_test').overlay().load();
				$('#dlg_rate_test').modal('show');
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
				if ($bTranslation) {
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
			if (! empty ( $customInstrAry )) {
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
			if ($bTranslation) {
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
		<script type="text/x-mathjax-config">
  			MathJax.Hub.Config({
    			tex2jax: {inlineMath: [["$","$"],["\\(","\\)"]]}
 			});
		</script>
	</div>
</body>
</html>
