<!doctype html>
<?php
include_once ("../lib/session_manager.php");
include_once ("../lib/include_js_css.php");
include_once ("../lib/utils.php");
include_once ('../database/mcat_db.php');
include_once ("lib/test_helper.php");

// - - - - - - - - - - - - - - - - -
// On Session Expire Load ROOT_URL
// - - - - - - - - - - - - - - - - -
// CSessionManager::OnSessionExpire();
// - - - - - - - - - - - - - - - - -

const QUES_FLAG_MARKED_FOR_REVIEW = 1;
const QUES_FLAG_UNANSWERED = 2;
const QUES_FLAG_VISITED = 3;

$objDB = new CMcatDB ();

$bFreeEZeeAssesUser = CSessionManager::Get ( CSessionManager::BOOL_FREE_EZEEASSESS_USER );

$sUserID = "";
if ($bFreeEZeeAssesUser == 1) {
	$sUserID = $_COOKIE [CConfig::FEUC_NAME];
	printf ( "<script type='text/javascript'> var bIsFree = true;  </script>" );
} else {
	$sUserID = CSessionManager::Get ( CSessionManager::STR_USER_ID );
	printf ( "<script type='text/javascript'> var bIsFree = false;  </script>" );
}

$sUserName = $objDB->GetUserName($sUserID);

$bDecrAttemptCount = CSessionManager::Get ( CSessionManager::BOOL_DECR_ATTEMPT_COUNT );

$parsAry = parse_url ( CUtils::curPageURL () );
$qry = split ( "[=&]", $parsAry ["query"] );

$objTH = new CTestHelper ();

$nTestID = null;
$nTSchdID = null;
$nSection = null;
$nQuestion = null;
$nCurTime = null;
$aryQues = null;
$objAnsAry = null;

$langofchoice = CSessionManager::Get ( CSessionManager::BOOL_SEL_TEST_LANG );
$transLangChoice = CSessionManager::Get ( CSessionManager::STR_TRANS_LANG_CHOICE );
$testTransLang = CSessionManager::Get ( CSessionManager::STR_TEST_TRANS_LANG );

// echo $transLangChoice." ".$testTransLang."<br />";

$objTestParams = null;
$objMCPAParams = null;

$bNewTest = false;
$bTranslation = false;

/*
 * echo "<pre>"; print_r($qry); echo "</pre><br/>";
 */

if ($qry [0] == "test_id") {
	// The page is being called by clicking on question number or from Start
	// Test button
	$nTestID = $qry [1];
	
	if ($qry [2] == "tschd_id") {
		$nTSchdID = $qry [3];
	}
	if ($qry [4] == "sec") {
		$nSection = $qry [5];
	}
	if ($qry [6] == "ques") {
		$nQuestion = $qry [7];
	}
	if ($qry [8] == "trans_lang_choice") {
		if (! empty ( $transLangChoice )) {
			CSessionManager::UnsetSessVar ( CSessionManager::STR_TRANS_LANG_CHOICE );
		}
		$transLangChoice = $qry [9];
		CSessionManager::Set ( CSessionManager::STR_TRANS_LANG_CHOICE, $transLangChoice );
	}
	if ($qry [10] == "test_trans_lang") {
		if (! empty ( $testTransLang )) {
			CSessionManager::UnsetSessVar ( CSessionManager::STR_TEST_TRANS_LANG );
		}
		$testTransLang = $qry [11];
		CSessionManager::Set ( CSessionManager::STR_TEST_TRANS_LANG, $testTransLang );
	}
	
	// Get Org Name who owns the test
	$sOrgName = $objDB->GetOrganizationNameByTestID($nTestID);
	
	if(!empty($sOrgName))
	{
		$sOrgName = "by ".$sOrgName;
	}
	
	// Get Test Parameters
	$objTestParams = $objTH->GetTestParams ( $nTestID );
	$objMCPAParams = $objTH->GetMCPAParams ( $nTestID );
	
	$bTranslation = $objMCPAParams ['allow_trans'];
	
	if ($bTranslation) {
		if ($transLangChoice == "single") {
			$bNewTest = $objTH->StartTest ( $sUserID, $nTestID, $nTSchdID, $testTransLang );
		} else {
			$bNewTest = $objTH->StartTest ( $sUserID, $nTestID, $nTSchdID, $objMCPAParams ['pref_lang'] );
		}
	} else {
		$bNewTest = $objTH->StartTest ( $sUserID, $nTestID, $nTSchdID, $objMCPAParams ['pref_lang'] );
	}
	
	$session_time = CSessionManager::Get ( CSessionManager::INT_TEST_TIMER );
	$nCurTime = null;
	if ($qry [8] == "curtime" && $qry [9] != $session_time) {
		$nCurTime = $qry [9];
		
		if (empty ( $nCurTime )) {
			$nCurTime = $objTH->GetElapsedTime ( $sUserID, $nTestID, $nTSchdID );
		}
	} else {
		if ($bNewTest == false) {
			$nCurTime = $objTH->GetElapsedTime ( $sUserID, $nTestID, $nTSchdID );
		} else {
			$nCurTime = $objTestParams ['test_duration'] * 60;
		}
	}
	
	$LastSection = CSessionManager::Get ( CSessionManager::INT_LAST_SECTION );
	$LastQuestion = CSessionManager::Get ( CSessionManager::INT_LAST_QUESTION );
	
	$objAnsAry = $objTH->GetAnswers ( $sUserID, $nTestID, $nTSchdID );
	
	// echo "LastSection: ".$LastSection.", LastQuestion:
	// ".$LastQuestion."<br/><br/>";
	// printf("TestID: %s, SectionID: %s, Question#: %s, Answer: %s, Tschd_ID:
	// %s<br/>", $nTestID, $nSection, $nQuestion, $nAns, $nTSchdID);
	
	if ($LastSection == null) {
		CSessionManager::Set ( CSessionManager::INT_LAST_SECTION, $nSection );
		CSessionManager::Set ( CSessionManager::INT_LAST_QUESTION, $nQuestion );
	} else {
		// if last question was not already answered.
		if (count ( $objAnsAry [$LastSection] [$LastQuestion] ) == 1 && in_array ( - 1, $objAnsAry [$LastSection] [$LastQuestion] ) && $objMCPAParams ['mcpa_flash_ques'] == 1) {
			// printf("Unanswered Question: (Section: %s, Question: %s)",
			// $LastSection, $LastQuestion);
			if ($bTranslation) {
				if ($transLangChoice == "single") {
					$objTH->ReplaceQuestion ( $LastSection, $LastQuestion, $testTransLang );
				} else {
					$objTH->ReplaceQuestion ( $LastSection, $LastQuestion, $objMCPAParams ['pref_lang'] );
				}
			} else {
				$objTH->ReplaceQuestion ( $LastSection, $LastQuestion, $objMCPAParams ['pref_lang'] );
			}
			// $objTH->ReplaceQuestion($LastSection, $LastQuestion);
		}
		
		CSessionManager::Set ( CSessionManager::INT_LAST_SECTION, $nSection );
		CSessionManager::Set ( CSessionManager::INT_LAST_QUESTION, $nQuestion );
	}
	CSessionManager::Set ( CSessionManager::INT_TEST_TIMER, $nCurTime );
	
	$aryQues = $objTH->GetQuestion ( $nSection, $nQuestion, $nCurTime );
	/*
	 * echo $nQuestion."<br />"; echo "<pre>"; print_r($aryQues); echo "</pre>";
	 */
} else {
	// The page is being called by submitting an answer
	$nTestID = $_POST ['test_id'];
	$nSection = $_POST ['section'];
	$nQuestion = $_POST ['question'];
	$nAns = $_POST ['answer'];
	
	$langofchoice = $_POST ['langofchoice'];
	CSessionManager::Set ( CSessionManager::BOOL_SEL_TEST_LANG, $langofchoice );
	
	/*
	$handle = fopen("post_submit_ans.txt","w");
	fwrite($handle, print_r($_POST, TRUE));
	fclose($handle);
	*/
	
	if(count ( $nAns ) > 0 && ! in_array ( - 1, $nAns ))
	{
		$_POST ['flag_choice'] = 0;
	}
	
	if (isset ( $_POST ['flag_choice'] ) ) {
	if ($_POST ['flag_choice'] == QUES_FLAG_UNANSWERED) {
			$nAns = array ("-1" );
		} else if ($_POST ['flag_choice'] == QUES_FLAG_MARKED_FOR_REVIEW) {
			$nAns = array ("-2" );
		} else if ($_POST ['flag_choice'] == QUES_FLAG_VISITED) {
			$nAns = array ("-3" );
		}
	}
	
	$nCurTime = $_POST ['cur_timer'];
	$nTSchdID = $_POST ['tschd_id'];
	
	// printf("TestID: %s, SectionID: %s, Question#: %s, Answer: %s, Tschd_ID:
	// %s<br/>", $nTestID, $nSection, $nQuestion, $nAns, $nTSchdID);
	
	$objMCPAParams = $objTH->GetMCPAParams ( $nTestID );
	
	$bTranslation = $objMCPAParams ['allow_trans'];
	
	if ($bTranslation) {
		if ($transLangChoice == "single") {
			$bNewTest = $objTH->StartTest ( $sUserID, $nTestID, $nTSchdID, $testTransLang );
		} else {
			$bNewTest = $objTH->StartTest ( $sUserID, $nTestID, $nTSchdID, $objMCPAParams ['pref_lang'] );
		}
	} else {
		$bNewTest = $objTH->StartTest ( $sUserID, $nTestID, $nTSchdID, $objMCPAParams ['pref_lang'] );
	}
	
	/*
	 * echo("<pre>"); print_r($nAns); echo("</pre>");
	 */
	
	if (count ( $nAns ) > 0 && ! in_array ( - 1, $nAns )) {
		// echo("Test 1");
		$objTH->SubmitAnswer ( $sUserID, $nTestID, $nTSchdID, $nSection, $nQuestion, $nAns, $nCurTime );
	} else {
		// echo("Test 2");
		$objTH->SubmitAnswer ( $sUserID, $nTestID, $nTSchdID, $nSection, $nQuestion, array ("-1" ), $nCurTime );
		
		if ($objMCPAParams ['mcpa_flash_ques'] == 1) {
			if ($bTranslation) {
				if ($transLangChoice == "single") {
					$objTH->ReplaceQuestion ( $nSection, $nQuestion, $testTransLang );
				} else {
					$objTH->ReplaceQuestion ( $nSection, $nQuestion, $objMCPAParams ['pref_lang'] );
				}
			} else {
				$objTH->ReplaceQuestion ( $nSection, $nQuestion, $objMCPAParams ['pref_lang'] );
			}
		}
	}
	
	$aryQues = $objTH->GetNextQuestion ( $nSection, $nQuestion, $nCurTime );
	
	CSessionManager::Set ( CSessionManager::INT_LAST_SECTION, $nSection );
	CSessionManager::Set ( CSessionManager::INT_LAST_QUESTION, $nQuestion );
}

$aryTransQues = null;

if ($bTranslation) {
	if ($aryQues ['ques_id'] != - 1 && $transLangChoice == "both") {
		$aryTransQues = $objTH->GetTranslatedQuestion ( $aryQues ['group_title'], $testTransLang );
	}
}
// printf("TestID: %s, SectionID: %s, Question#: %s, TSchdID: %s", $nTestID,
// $nSection, $nQuestion, $nTSchdID);
/*
 * echo "<pre>"; print_r($aryQues); echo "</pre><br/>";
 */
	
	/*echo "<pre>";
	print_r($objMCPAParams);
	echo "</pre><br/>";*/
	
	if ($objTestParams == null) {
	$objTestParams = $objTH->GetTestParams ( $nTestID );
}

if ($objAnsAry == null) {
	$objAnsAry = $objTH->GetAnswers ( $sUserID, $nTestID, $nTSchdID );
}

$sSectionName = $objTH->GetSectionName ( $nSection );

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// Adjust attempts
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
if ($bDecrAttemptCount == 1) {
	$tsession_id = $objTH->IsTestPending ( $sUserID, $nTestID, $nTSchdID );
	$objTH->DecrementAttemptsInTestSession ( $tsession_id );
	
	CSessionManager::UnsetSessVar ( CSessionManager::BOOL_DECR_ATTEMPT_COUNT );
}
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

if (empty ( $aryTransQues )) {
	$langofchoice = 0;
}

$test_name = $objDB->GetTestName ( $nTestID );
/*
 * echo "Later:<pre>"; print_r($objAnsAry); echo "</pre><br/>";
 */

// echo("Current Answer: ".$objAnsAry[$nSection][$nQuestion]);
// echo $transLangChoice." ".$testTransLang." ".$aryQues['ques_id'];
$objIncludeJsCSS = new IncludeJSCSS ();

$ques_type_ary = array (CConfig::QT_READ_COMP => "Reading Comprehension Para", CConfig::QT_DIRECTIONS => "Direction" );

$bShowPara = 0;
if (isset ( $_POST ['showParaChoice'] )) {
	$bShowPara = $_POST ['showParaChoice'];
} else if (isset ( $_GET ['showParaChoice'] ) && ! empty ( $_GET ['showParaChoice'] )) {
	$bShowPara = $_GET ['showParaChoice'];
}

$prev_linked_to = - 1;
if (isset ( $_POST ['prev_linked_to'] )) {
	$prev_linked_to = $_POST ['prev_linked_to'];
} else if (isset ( $_GET ['prev_linked_to'] ) && ! empty ( $_GET ['prev_linked_to'] )) {
	$prev_linked_to = $_GET ['prev_linked_to'];
}

$bShowSections = 1;
if (isset ( $_POST ['showSectionChoice'] )) {
	$bShowSections = $_POST ['showSectionChoice'];
} else if (isset ( $_GET ['showSectionChoice'] ) && ! empty ( $_GET ['showSectionChoice'] )) {
	$bShowSections = $_GET ['showSectionChoice'];
}

if ((isset ( $_POST ['section'] ) && $_POST ['section'] != $nSection) || (isset ( $_GET ['sec'] ) && $_GET ['sec'] != $nSection)) {
	$bShowSections = 1;
}

$nAnsweredLegend = 0;
$nNotVisitedLegend = 0;
$nReviewLegend = 0;
$nUnansweredLegend = 0;

/*$handle = fopen("post_sec_ques.txt","w");
fwrite($handle, print_r($objAnsAry[$nSection], TRUE));
fclose($handle);*/

foreach ( $objAnsAry[$nSection] as $qusIndex => $Answer ) 
{
	if (count(array_intersect(array(-1, -2, -3), $Answer)) == 0 )
	{
		$nAnsweredLegend = $nAnsweredLegend + 1;
	}
	else if(in_array(-1, $Answer))
	{
		$nNotVisitedLegend = $nNotVisitedLegend + 1;
	}
	else if (in_array(-2, $Answer))
	{
		$nReviewLegend = $nReviewLegend + 1;
	}
	else if (in_array(-3, $Answer))
	{
		$nUnansweredLegend = $nUnansweredLegend + 1;
	}
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<style type="text/css">
div.mipcat_code_ques {
	font-family: "Courier New", monospace;
	white-space: pre;
	border: 1px solid #aaa;
	padding: 5px;
	margin: 10px;
}

span.username {
	color: #2c363f;
	float: left;
	font-size: 1.417em;
	font-weight: bold;
	width: 100%;
	padding: 4px;
}

.active a {
	background-color: #4390df !important; 
	color: white !important; 
}

</style>
		<?php
		$objIncludeJsCSS->CommonIncludeCSS ( "../" );
		$objIncludeJsCSS->IncludeJquerySnippetCSS ( "../" );
		$objIncludeJsCSS->IncludeTCSButtonsCSS ( "../" );
		
		$objIncludeJsCSS->CommonIncludeJS ( "../" );
		$objIncludeJsCSS->IncludeJquerySnippetJS ( "../" );
		$objIncludeJsCSS->IncludeMathJAXJS ( "../" );
		$objIncludeJsCSS->IncludeJqueryUI_1_12_1_JS ( "../" );
		
		?>
		<script type="text/javascript">
		 /* var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', 'UA-2246912-13']);
		  _gaq.push(['_trackPageview']);
		
		  (function() {
		    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })(); */
		</script> 
<script type="text/javascript" src="../js/mipcat/utils.js"></script>
<style type="text/css">
.selected_sec_name {
	float: left;
	display: inline-block;
	padding: 5px;
	margin-top: 5px;
}

.timer {
	float: right;
}

/*---------------------------------------------------------------*/
/* Group or Section Scroller Style*/
/*---------------------------------------------------------------*/

.wrapper {
    position:relative;
    margin:0 auto;
    overflow:hidden;
	padding:5px;
  	height:50px;
}

.list {
    position:relative;
    left:0px;
    top:0px;
  	min-width:3000px;
  	margin-left:12px;
    margin-top:0px;
    
    list-style: none;
    white-space: nowrap;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.list li{
	display:table-cell;
    position:relative;
    text-align:center;
    cursor:grab;
    cursor:-webkit-grab;
    color:#efefef;
    vertical-align:middle;
}

.scroller {
  text-align:center;
  cursor:pointer;
  display:none;
  padding:7px;
  padding-top:11px;
  white-space:no-wrap;
  vertical-align:middle;
  background-color:#fff;
}

.scroller-right{
  float:right;
}

.scroller-left {
  float:left;
}

/*---------------------------------------------------------------*/

@media ( max-width : 480px) {
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
		float: none;
	}
	.timer {
		float: none;
	}
}

.border
{
	border: 1px solid lightgrey;
}

.highlight-button {
	border-top: 2px solid #4390df;
}

@media ( min-width : 769px) and ( max-width : 8000px) {
	#test-buttons-desktop {
		display: block;
	}
	
	#test-buttons-mobile {
		display: none;
	}
	
	.hideon-dt-mode {
		display: none;
	}
	
	#md-container {
		position: relative;
	}
	
	#question-area {
		overflow-y: auto;
	}
	
	#toggle_para {
		display: block;
	}
	
	#dir-para {
		overflow-y: auto;
		overflow-x: auto;
		display: <?php echo($aryQues['ques_type'] == CConfig::QT_NORMAL ? "none" : "block");?> !important;
		position: relative;
		border:1px solid #aaa;
	}
	
	#legend-and-question {
		overflow-y: auto;
		display: block !important;
	}
	
	
}

@media ( min-width : 0px) and ( max-width : 768px) {
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
		float: none;
	}
	.timer {
		float: none;
	}
	
	/*Layout Divs*/
	#org-name {
		display:none;
	}
	
	#group-strip {
		display: none;
	}
	
	#timer-strip {
		
	}
	
	#cur-section-label {
		display: none;
	}
	
	#section-strip {
		
	}
	
	#lang-selection {
	
	}
	
	#user-identification{
		display: none;
	}
	
	#question-info {
	
	}
	
	#question-area {
		overflow-y: auto;
	}
	
	#toggle_para {
		display: none;
	}
	
	#dir-para {
    	display: none;
		position: absolute;
		background-color: #fff;
		z-index: 100;
		overflow-y: auto;
		overflow-x: auto;
		border:1px solid #aaa;
	}
	
	#md-container {
		position: relative;
	}
	
	#legend-and-question {
		display: none;
		position: absolute;
		background-color: #fff;
		z-index: 100;
		overflow-y: auto;
	}
	
	#test-buttons-desktop {
		display: none;
	}
	
	#test-buttons-mobile {
		display: block;
	}
}

.row-eq-height {
  display: -webkit-box;
  display: -webkit-flex;
  display: -ms-flexbox;
  display:         flex;
}

.modal-body {
	max-height: 350px;
	padding: 15px;
	overflow-y: auto;
	-webkit-overflow-scrolling: touch;
}

body {
	height: 100%;
	overflow: hidden;
	padding: 10px;
}
</style>
</head>
<body>
	<div class="container">
		<div class="row countme"
			style="color: white; font-weight: bold; background-color: CornflowerBlue; padding: 10px 10px;"
			id="header">
			<span>Test: <?php echo $test_name; ?></span> <span class="pull-right"
				style="margin-top: -5px" id="org-name">
				<?php echo($sOrgName);?>
			</span>
		</div>
		
		<div class="row row-eq-height countme">
			<div class="col-xs-12 col-sm-9">
				<div class="row row-eq-height" id="group-strip">
				</div>
				<div class="row border" id="timer-strip">
					<div class="col-sm-6" id="cur-section-label">
						<span class="selected_sec_name"><?php printf("<b><i class='icon-tasks icon-black'></i>&nbsp;Current Section: <span style='color:FireBrick;'>%s</span></b>",$sSectionName);?></span>
					</div>
					<div class="col-sm-6">
						<span class="timer"><input type="text"
							class="input-medium search-query" size="8" id="timer"
							style="border: none; text-align: center; color: #009900; font-weight: bold; width: 180px; height: 30px; margin-top: 5px;"></span>
						</div>
					</div>
					<div class="row border" id="section-strip">
						<div class="scroller scroller-left"><i class="fa fa-chevron-left" aria-hidden="false"></i>
						</div>
  						<div class="scroller scroller-right"><i class="fa fa-chevron-right" aria-hidden="false"></i>
  						</div>
						<div class="wrapper">
							<ul class="nav nav-tabs list">
							<?php
							$arySection = $objTH->GetSectionDetails ( $nTestID );
							
							$secIndex = 0;
							foreach ( $arySection as $key => $Section ) {
								if (! empty ( $Section ['name'] )) {
									if ($secIndex == $nSection)
										printf ( "<li class='active'><a href='#%s_questions' aria-controls='%s_questions' data-toggle='tab'><b>%s <i class='fa fa-info-circle' aria-hidden='true'></i></b></a></li>\n", $Section ['name'], $Section ['name'], $Section ['name'] );
									else
										printf ( "<li ><a href='#%s_questions' aria-controls='%s_questions' data-toggle='tab'><b>%s <i class='fa fa-info-circle' aria-hidden='true'></i></b></a></li>\n", $Section ['name'], $Section ['name'], $Section ['name'] );
								
								}
								$secIndex ++;
							}
							?>
						</ul>
					</div>
				</div>
				<div class="row border" id="lang-selection" style="color: #fff;	background-color: #4390df; padding: 5px;">
					<div class="col-xs-3 hideon-dt-mode" style="border-right: 1px solid #ddd;">
						<span class="btn" id="mobile-book-btn" style="<?php echo($aryQues['ques_type'] == CConfig::QT_NORMAL ? "display:none;" : "");?>"><i class="fa fa-book" aria-hidden="true"></i></span>
					</div>
					<div class="col-xs-6 col-sm-12">
						<div id="choose_lang" class="pull-right" style="<?php echo($transLangChoice != "both"?"display:none;":"");?>">
							<select  name="trans_choice" class="form-control" onchange="OnTransChoiceChange();">
								<option id="trans_choice_base" value="base" <?php echo($langofchoice==0?"selected":""); ?>><?php echo(ucfirst($objMCPAParams['pref_lang'])); ?></option>
								<?php
								if (! empty ( $aryTransQues )) {
								?>
								<option id="trans_choice_translated" value="translated" <?php echo($langofchoice==1?"selected":""); ?>><?php echo(ucfirst($testTransLang)); ?></option>
								<?php 
								}
								?>
							</select>
							<!-- 
							<div class="radio" style='color: White;'>Choose Language &nbsp; :&nbsp;&nbsp; <label
								class="radio"> <input type="radio" id="trans_choice_base"
									value='base' name="trans_choice"
									onchange="OnTransChoiceChange();"
									<?php echo($langofchoice==0?"checked":""); ?>>&nbsp;&nbsp;<?php echo(ucfirst($objMCPAParams['pref_lang'])); ?>&nbsp;&nbsp;
							</label>
							<?php
							if (! empty ( $aryTransQues )) {
								?>
							<label class="radio"> <input type="radio"
									id="trans_choice_translated" value='translated'
									name="trans_choice" onchange="OnTransChoiceChange();"
									<?php echo($langofchoice==1?"checked":""); ?>>&nbsp;&nbsp;<?php echo(ucfirst($testTransLang)); ?>
							</label>
							<?php
							} else {
								?>
							<label style="color: yellow;">Question&rsquo;s translation in <b>&lsaquo; <?php echo(ucfirst($testTransLang)); ?> Language &rsaquo;</b>
									is not available.
							</label>
							<?php
							}
							?>
							</div>-->
						</div>
					</div>
					<div class="col-xs-3 hideon-dt-mode" style="border-left: 1px solid #ddd;">
						<span class="btn" id="mobile-bars-btn"><i class="fa fa-bars" aria-hidden="true"></i></span>
					</div>
				</div>
			</div>
			<div class="col-sm-3 border" id="user-identification">
				<div class="row">
					<div class="col-sm-6">
						<img class="border" src="../images/NewCandidateImage.jpg" width="94" height="101"/>
					</div>
					<div class="col-sm-6">
						<span class="username"><?php echo($sUserName);?></span>
					</div>
				</div>
				
			</div>
		</div>
		<form id="question-form" action="mipcat-tcs.php" onReset="return ResetForm();" method="POST">
			<div class="row" id="md-container">
				<div class="col-xs-12 col-sm-9 border">
					<div class="row border" id="question-info">
					
					</div>
					<div class="row" id="question-area">
						<div id="dir-para" class="<?php echo($aryQues['ques_type'] == CConfig::QT_NORMAL ? "col-sm-0" : "col-xs-12 col-sm-6");?>">
							<!-- <button type="button" class="btn btn-primary" onclick="TogglePara()" id="toggle_para" style="<?php echo($aryQues['ques_type'] == CConfig::QT_NORMAL ? "display:none;" : "");?>">
							</button> -->
							<div class="well" id="base_para">
								<blockquote>
									<p>
		    					<?php
											if ($aryQues ['ques_type'] != CConfig::QT_NORMAL && $aryQues ['ques_type'] != - 1) {
												echo ($objTH->GetRCDirectionPara ( $aryQues ['ques_id'], $aryQues ['ques_type'] ));
											}
											?>
		    					</p>
									<small><?php echo(ucwords($aryQues['language']));?></small>
								</blockquote>
							</div>
	    	
	    					<?php
							if (! empty ( $aryTransQues )) {
							?>
	    					<div class="well" id="trans_para">
								<blockquote>
									<p>
			    				<?php
										if ($aryTransQues ['ques_type'] != CConfig::QT_NORMAL && $aryTransQues ['ques_type'] != - 1) {
											echo ($objTH->GetRCDirectionPara ( $aryTransQues ['ques_id'], $aryTransQues ['ques_type'] ));
										}
										?>
		    					</p>
									<small><?php echo(ucwords($aryTransQues['language']));?></small>
								</blockquote>
							</div>
	    					<?php
							}
							?>
						</div>
						<div class="col-xs-12 <?php echo($aryQues['ques_type'] == CConfig::QT_NORMAL ? "col-sm-12" : "col-sm-6");?>">
							<table width="100%" cellpadding="4" cellspacing="4">
								<tr>
									<td colspan="2" id="td_question" style="color: DarkSlateBlue;">
									<?php
									$ques_cnts = "";
									if (CUtils::getMimeType ( $aryQues ['question'] ) == "application/octet-stream") {
										$ques_cnts = str_replace ( "\n", "<br />", $aryQues ['question'] );
									} else {
										$ques_cnts = sprintf ( "<img src='lib/print_image.php?qid=%s&opt=0'>", $aryQues ['ques_id'] );
									}
									printf ( "<blockquote id='base_ques'><p><b>Ques %d). %s</b></p><small>%s</small></blockquote>", ($nQuestion + 1), $ques_cnts, ucwords ( $aryQues ['language'] ) );
									
									$opt_ary = array ();
									for($index = 0; $index < $aryQues ['opt_count']; $index ++) {
										if (CUtils::getMimeType ( base64_decode ( $aryQues ['options'] [$index] ["option"] ) ) == "application/octet-stream") {
											$opt_ary [$index] = base64_decode ( $aryQues ['options'] [$index] ["option"] );
										} else {
											$opt_ary [$index] = sprintf ( "<img src='lib/print_image.php?qid=%s&opt=%s'>", $aryQues ['ques_id'], ($index + 1) );
										}
									}
									
									if (! empty ( $aryTransQues )) {
										
										$ques_cnts = "";
										if (CUtils::getMimeType ( $aryTransQues ['question'] ) == "application/octet-stream") {
											$ques_cnts = str_replace ( "\n", "<br />", $aryTransQues ['question'] );
										} else {
											$ques_cnts = sprintf ( "<img src='lib/print_image.php?qid=%s&opt=0'>", $aryTransQues ['ques_id'] );
										}
										printf ( "<blockquote id='trans_ques' style='display :none'><p><b>Ques %d). %s</b></p><small>%s</small></blockquote>", ($nQuestion + 1), $ques_cnts, ucwords ( $aryTransQues ['language'] ) );
										
										$trans_opt_ary = array ();
										for($index = 0; $index < $aryTransQues ['opt_count']; $index ++) {
											if (CUtils::getMimeType ( base64_decode ( $aryTransQues ['options'] [$index] ["option"] ) ) == "application/octet-stream") {
												$trans_opt_ary [$index] = base64_decode ( $aryTransQues ['options'] [$index] ["option"] );
											} else {
												$trans_opt_ary [$index] = sprintf ( "<img src='lib/print_image.php?qid=%s&opt=%s'>", $aryTransQues ['ques_id'], ($index + 1) );
											}
										}
									}
									?><br />
									</td>
								</tr>
								<?php
								for($opt_idx = 0; $opt_idx < $aryQues ['opt_count']; $opt_idx ++) {
									if ($opt_idx == 0) {
										printf ( "<tr>\n" );
									} 
	
									else //if (($opt_idx % 2) == 0) {
									{
										printf ( "</tr>\n<tr>\n" );
									}
									
									$ip_type = "radio";
									if ($objMCPAParams ['mcq_type'] == 1) {
										$ip_type = "checkbox";
									}
								?>
								<td class="info" id="td_opts" style="<?php echo((empty($opt_ary[$opt_idx]) && !is_numeric($opt_ary[$opt_idx])) ? "display:none;" : "");?>"><label><?php echo($opt_idx+1);?>). <input
										style="position: relative; top: -4px;"
										id="rb_opt_<?php echo($opt_idx+1);?>"
										type="<?php echo($ip_type);?>" name="answer[]"
										value="<?php echo($opt_idx+1);?>"
										<?php echo(in_array(($opt_idx+1), $objAnsAry[$nSection][$nQuestion])?"checked='checked'":""); ?> />
										<span id="base_opt_<?php echo($opt_idx+1);?>"><?php echo($opt_ary[$opt_idx]);?></span><?php printf(!empty($aryTransQues)?"<span id='trans_opt_%d' style='display :none;'>%s</span>":"", ($opt_idx+1), $trans_opt_ary[$opt_idx]);?></label></td>
									<?php
									if ($opt_idx == ($aryQues ['opt_count'] - 1)) {
										printf ( "</tr>\n" );
										}
									}
								?>
							</table>
							<input type="hidden" id="test_id" name="test_id"
								value="<?php echo($nTestID);?>" /> <input type="hidden"
								id="tschd_id" name="tschd_id" value="<?php echo($nTSchdID);?>" />
							<input type="hidden" id="section" name="section"
								value="<?php echo($nSection);?>" /> <input type="hidden"
								id="question" name="question" value="<?php echo($nQuestion);?>" />
							<input type="hidden" id="langofchoice" name="langofchoice"
								value="0" /> <input type="hidden" id="showParaChoice"
								name="showParaChoice" /> <input type="hidden"
								id="prev_linked_to" name="prev_linked_to"
								value="<?php echo($aryQues['linked_to']);?>"> <input
								type="hidden" id="showSectionChoice" name="showSectionChoice">
							<?php
							if ($objMCPAParams ['mcpa_flash_ques'] != 1) {
								?>
							<input type="hidden" id="flag_choice" name="flag_choice" value="<?php echo(QUES_FLAG_VISITED);?>" />
							<?php
							}
							?>
							<input type="hidden" id="cur_timer" name="cur_timer" value="" />
						</div>
					</div>
				</div>
				<div class="col-sm-3 col-xs-12" style='border: 1px solid #000;' id="legend-and-question">
					<div class="row instruction_area" id="sec_ques_info" >
						<div class="col-sm-6 col-xs-6">
							<span class="answered"><?php echo($nAnsweredLegend);?></span> Answered
						</div>
						<div class="col-sm-6 col-xs-6">
							<span class="not_answered"><?php echo($nUnansweredLegend);?></span> Not Answered
						</div>
						<div class="col-sm-6 col-xs-6">
							<span class="not_visited"><?php echo($nNotVisitedLegend);?></span> Not Visited
						</div>
						<div class="col-sm-6 col-xs-6">
							<span class="review"><?php echo($nReviewLegend);?></span> Marked for Review
						</div>
					</div>
					<!--
					<div class="row">
						<span class="metro" id="sec_ques_info">
							<button class="info" style="margin-top: 5px;">Current</button>
							<button class="success" style="margin-top: 5px;">Attempted</button>
							<button class="warning" style="margin-top: 5px;">Flagged</button>
						</span> 
					</div>
					<div class="row">
						<button type="button" onclick="ToggleSections();"
							class="btn btn-primary" id="toggle_sec"
							style="font-weight: bold; margin-top: 5px; margin-left: 7px; float: left;"></button>
					</div>
					<br /> <br />
					 -->
					 
					<div class="row metro" id="section_info">
						<div class="tab-content">
						    <?php
							$secIndex = 0;
							foreach ( $arySection as $key => $Section ) {
								printf ( "<div style='padding: 5px; overflow-y: auto;' role='tabpanel' class='tab-pane %s' id='%s_questions'>", $secIndex == $nSection ? 'active' : '', $Section ['name'] );
								printf("<button style='margin-top: 5px;'><i class='fa fa-align-justify on-left' aria-hidden='true'></i>&nbsp;Reading Comprehension Group</button>");
								printf("<button style='margin-top: 5px;margin-bottom: 5px;'><i class='fa fa-arrow-right on-left' aria-hidden='true'></i>&nbsp;Direction Group</button>");
								printf("<div class='sec-name-questios'>%s</div>", $Section ['name']);
								printf("<div class='instruction_area'>");
								
								//printf("<script language='JavaScript' type='text/javascript'>alert('%s')</script>", print_r($objAnsAry, TRUE));
								
								for($ques = 0; $ques < $Section ['questions']; $ques ++) {
									printf ( "<span class='not_visited' style='margin-top: 5px;' onClick='LoadQuestion(%d, %d, %d, %d);' id='%d'>%d</span>\n", $nTestID, $nTSchdID, $ques, $secIndex, (($secIndex + 1) * 1000) + ($ques + 1), ($ques + 1) );
								}
								
								printf ( "</div>" );
								printf ( "</div>" );
								$secIndex ++;
							}
							?>
						</div>
					</div>
					<br />
				</div>
			</div>
			<div class="row row-eq-height countme" id="test-buttons-desktop">
				<div class="col-sm-9 border" style="padding-top: 10px;">
					<?php
						if ((count ( $objAnsAry [$nSection] [$nQuestion] ) == 1 && (in_array ( - 1, $objAnsAry [$nSection] [$nQuestion] ) || in_array ( - 2, $objAnsAry [$nSection] [$nQuestion] ))) || $objMCPAParams ['mcpa_lock_ques'] == 0) {
							$flag_btn_val = in_array ( - 2, $objAnsAry [$nSection] [$nQuestion] ) ? "Unflag" : "Mark for Review & Next";
							$flag_val = in_array ( - 2, $objAnsAry [$nSection] [$nQuestion] ) ? 2 : 1;
							echo (($objMCPAParams ['mcpa_flash_ques'] != 1) ? '<input type="submit" onclick="SetFlag(' . $flag_val . ');" class="btn btn-primary" id="flag_ques" name="btn2" value="' . $flag_btn_val . '" disabled/>&nbsp;&nbsp;&nbsp;&nbsp;' : '');
							echo ('<input type="reset" class="btn btn-default" value="Clear Response"/>&nbsp;&nbsp;&nbsp;&nbsp;');
							echo ('<input type="submit" id="submit_ans" class="btn btn-success pull-right" name="btn1" value="Go to Next" disabled/>');
						
						} else {
							echo (($objMCPAParams ['mcpa_flash_ques'] != 1) ? '<input type="submit" onclick="SetFlag(0);" class="btn btn-primary" id="flag_ques" name="btn2" value="Flag / Mark" disabled/>&nbsp;&nbsp;&nbsp;&nbsp;' : '');
							echo ('<input type="reset" class="btn btn-default" value="Clear Response" disabled/>&nbsp;&nbsp;&nbsp;&nbsp;');
							echo ('<input type="submit" id="submit_ans" class="btn btn-success pull-right" name="btn1" value="Go to Next" disabled/>');
						}
						
						//echo ('<br/><b>( After Reseting already selected option&lsaquo;s&rsaquo; or after Selecting option&lsaquo;s&rsaquo; press <span style="color:green">Submit</span> )</b>');
					?>
				</div>
				<div class="col-sm-3 border text-center" style="padding-top: 10px;">
					<input type="button" id="btn_end_exam" class="btn btn-danger" value="Submit & Close Test">
					</input>
				</div>
			</div>
			<div class="row row-eq-height countme" id="test-buttons-mobile">
				<div class="col-xs-12 border" style="padding: 5px;">
					<?php
						if ((count ( $objAnsAry [$nSection] [$nQuestion] ) == 1 && (in_array ( - 1, $objAnsAry [$nSection] [$nQuestion] ) || in_array ( - 2, $objAnsAry [$nSection] [$nQuestion] ))) || $objMCPAParams ['mcpa_lock_ques'] == 0) {
							$flag_btn_val = in_array ( - 2, $objAnsAry [$nSection] [$nQuestion] ) ? "Unflag" : "Mark for Review & Next";
							$flag_val = in_array ( - 2, $objAnsAry [$nSection] [$nQuestion] ) ? 2 : 1;
							echo ('<input type="reset" class="btn btn-default btn-sm pull-left" value="Clear Response"/>&nbsp;&nbsp;&nbsp;&nbsp;');
							echo (($objMCPAParams ['mcpa_flash_ques'] != 1) ? '<input type="submit" onclick="SetFlag(' . $flag_val . ');" class="btn btn-primary btn-sm pull-right" id="flag_ques_m" name="btn2" value="' . $flag_btn_val . '" disabled/>&nbsp;&nbsp;&nbsp;&nbsp;' : '');
						} else {
							echo ('<input type="reset" class="btn btn-default btn-sm pull-left" value="Clear Response" disabled/>&nbsp;&nbsp;&nbsp;&nbsp;');
							echo (($objMCPAParams ['mcpa_flash_ques'] != 1) ? '<input type="submit" onclick="SetFlag(0);" class="btn btn-primary btn-sm pull-right" name="btn2" id="flag_ques_m" value="Flag / Mark" disabled/>&nbsp;&nbsp;&nbsp;&nbsp;' : '');
						}
					?>
				</div>
				<div class="col-xs-12 border" style="padding: 5px;">
					<input type="button" id="btn_end_exam_m" class="btn btn-danger btn-sm pull-left" value="Submit & Close Test"></input>
					<?php
						if ((count ( $objAnsAry [$nSection] [$nQuestion] ) == 1 && (in_array ( - 1, $objAnsAry [$nSection] [$nQuestion] ) || in_array ( - 2, $objAnsAry [$nSection] [$nQuestion] ))) || $objMCPAParams ['mcpa_lock_ques'] == 0) {
							$flag_btn_val = in_array ( - 2, $objAnsAry [$nSection] [$nQuestion] ) ? "Unflag" : "Mark for Review & Next";
							$flag_val = in_array ( - 2, $objAnsAry [$nSection] [$nQuestion] ) ? 2 : 1;
							echo ('<input type="submit" id="submit_ans_m" class="btn btn-success btn-sm pull-right" name="btn1" value="Go to Next" disabled/>');
						
						} else {
							echo ('<input type="submit" id="submit_ans_m" class="btn btn-success btn-sm pull-right" name="btn1" value="Go to Next" disabled/>');
						}
					?>
				</div>
			</div>
		</form>
		
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
						<button type="button" class="btn btn-default"
							data-dismiss="modal">No</button>
						<button type="button" onclick="OnEndExam()"
							class="btn btn-primary">Yes</button>
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
					<div id="ModalMsgStr" class="modal-body"></div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default"
							data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
	
		<script language="JavaScript" type="text/javascript">
		//parent.ShowLeftMenu();
		// ---------------------------------------------------------
		var toggle_btn_book = 0;
		var toggle_btn_bars = 0;
		
		OnTransChoiceChange();

		$(document).ready(function() {
			TestTimer();
			HeartBeat();
			$("div.mipcat_code_ques").snippet("c",{style:"vim"});
			$("#submit_ans").removeAttr('disabled');
			$("#submit_ans_m").removeAttr('disabled');
			<?php
			if ($objMCPAParams ['mcpa_flash_ques'] != 1) {
				?>
			$("#flag_ques").removeAttr('disabled');
			$("#flag_ques_m").removeAttr('disabled');
			<?php
			}
			if ($aryQues ['ques_type'] != CConfig::QT_NORMAL) {
				?>
			TogglePara();
			<?php
			}
			?>

			ToggleSections();
			
			// -------------------------------------------------------

			var hidWidth;
			var scrollBarWidths = 40;
			var nItemsCount = 0;
			var nScrollLen;
			
			var widthOfList = function(){
 				var itemsWidth = 0;
 				nItemsCount = 0;
				$('.list li').each(function(){
  					var itemWidth = $(this).outerWidth();
 					itemsWidth+=itemWidth;
					
					nItemsCount++;
				});
  			return itemsWidth;
			};

			var widthOfHidden = function(){
				return (($('.wrapper').outerWidth())-widthOfList()-getLeftPosi())-scrollBarWidths;
			};

			var getLeftPosi = function(){
				return $('.list').position().left;
			};

			var heightExceptQArea = function(){
				var itemsHeight = 0;
				
				$('.container .countme:visible').each(function(){
  					var itemHeight = $(this).outerHeight();
  					//alert(itemHeight);
 					itemsHeight+=itemHeight;
				});
				return itemsHeight
			};

			var reAdjust = function(bFromScroller = 0){
				if($( window ).width() <= widthOfList() && !bFromScroller)
				{
					var amount = $(".list li.active").position().left;
					$('.wrapper').animate({scrollLeft:amount}, function(){});
				}
								
				if (($('.wrapper').outerWidth()) < widthOfList()) {
			    	$('.scroller-right').show();
			  	}
			  	else {
					$('.scroller-right').hide();
			  	}

				if (getLeftPosi()<0) {
			    	$('.scroller-left').show();
			  	}
			  	else {
			    	$('.item').animate({left:"-="+getLeftPosi()+"px"},'slow');
			    	$('.scroller-left').hide();
			  	}

			  	//alert("Window Height: " + $( window ).height() + ", RowsHeight: " + heightExceptQArea());
			  	$("#question-area").css("height", $( window ).height() - heightExceptQArea() - 40);
			  	$("#dir-para").css("height", $( window ).height() - heightExceptQArea() - 60);
			  	$("#legend-and-question").css("height", $( window ).height() - heightExceptQArea() - 40);
				
			  	var nItemsWidth = $('.wrapper').width();
			  	nScrollLen  = nItemsWidth / nItemsCount;
			}
			
			reAdjust();

			$(window).on('resize',function(e){
				if($( window ).width() <= widthOfList())
				{
					var amount = $(".list li.active").position().left;
					$('.wrapper').animate({scrollLeft:amount}, function(){
			        	reAdjust();
				        });
				}
				else
				{
					$('.wrapper').animate({scrollLeft:5},'slow',function(){
			        	reAdjust();
				        });
				}
			});

			if(!/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
			    $(".scroller-right").bind("click", function(event) {
			    	scrollContent("right");
			    });

			    $(".scroller-left").bind("click", function(event) {
			    	scrollContent("left");
			    });

			    function scrollContent(direction) {
			    	var amount = (direction === "left" ? "-="+ nScrollLen : "+="+nScrollLen);
					
			        $('.wrapper').animate({scrollLeft:amount}, function(){
				        reAdjust(1);
				        });
			    }
			  }
			// ---------------------------------------------------

			$("#mobile-book-btn").on("click", function(){
				//alert("Test-L");

				if(toggle_btn_book == 0)
				{
					$("#dir-para").show("slide", { direction: "left" }, 250);
					toggle_btn_book = 1;
				}
				else
				{
					$("#dir-para").hide("slide", { direction: "left" }, 250);
					toggle_btn_book = 0;
				}
			});

			$("#mobile-bars-btn").on("click", function(){
				if(toggle_btn_bars == 0)
				{
					$("#legend-and-question").show("slide", { direction: "right" }, 250);
					toggle_btn_bars = 1;
				}
				else
				{
					$("#legend-and-question").hide("slide", { direction: "right" }, 250);
					toggle_btn_bars = 0;
				}
			});

			$('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
				var newUrl;
				var url = e.target.toString();
				var nTargetSecIndex = $(e.target).closest('li').index();

				var secPattern = /&sec=[0-9]+/g;
				newUrl = url.replace(secPattern, "&sec="+nTargetSecIndex);

				if(newUrl === url)
				{
					$("#section").val(nTargetSecIndex);
					$("#question").val(-1);
					$("#question-form").submit();
				}
				else
				{
					var quesPattern = /&ques=[0-9]+/g;
					newUrl = newUrl.replace(quesPattern, "&ques=0");
	
					window.location = newUrl;
				}
				return false;
				/*
				var sSecNameLen = $(e.target).attr('href').lastIndexOf("_") - 1;
				// http://localhost/mipcat/test/mipcat-tcs.php?test_id=403&tschd_id=-100&sec=0&ques=0&curtime=2118&showParaChoice=&prev_linked_to=0&showSectionChoice=1#Quantitative_Aptitude_questions
				alert(<?php echo($nSection);?>);
				alert(e.target);alert(url);
				alert(nTargetSecIndex);
				alert(url.substr(url.lastIndexOf("#")+1, sSecNameLen));
				alert($(e.target).attr('href'));
				*/
			});
			
			$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
				if($( window ).width() <= widthOfList())
				{
					var amount = $(".list li.active").position().left;
					$('.wrapper').animate({scrollLeft:amount}, function(){
			        	reAdjust();
				        });
				}
			});
		});

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

		$("#btn_end_exam_m").click(function(){
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
					window.parent.HideOverlay();
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
		 		window.parent.HideOverlay();
			else										
				window.parent.postMessage("HideOverlay", "<?php echo(CSiteConfig::FREE_ROOT_URL); ?>");
		}

		<?php
		if ($objMCPAParams ['mcpa_flash_ques'] != 1) {
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
			var val = $('select[name=trans_choice]').val();
			//var val = $("input[name=trans_choice]:checked").val();
			
			if(val == "base")
			{
				$("#trans_ques").hide();
				$('span[id^=trans_opt_]').each(function(){
					$(this).hide();
				});

				<?php
				if ($aryQues ['ques_type'] != CConfig::QT_NORMAL) {
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
				if ($aryQues ['ques_type'] != CConfig::QT_NORMAL) {
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
			
			$("#timer").val("Time Left - "+secondsToTime(TestTimer.CurTime));
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
			$("input:radio[name='answer[]']").prop('checked', false);
			$("input:checkbox[name='answer[]']").prop('checked', false);
			
			<?php
			if ((count ( $objAnsAry [$nSection] [$nQuestion] ) > 0 && ! in_array ( - 1, $objAnsAry [$nSection] [$nQuestion] ) && ! in_array ( - 2, $objAnsAry [$nSection] [$nQuestion] ))) {
				?>
					ChangeSubmitBtnName("Save & Next");
			<?php
			} else {
				?>
				ChangeSubmitBtnName("Go to Next");
			<?php
			}
			?>

			$("#question-form").submit();
			return false;
		}
		
		$("input:checkbox[name='answer[]']").click(function(){
			var bAnyChecked = false;
			$("input:checkbox[name='answer[]']").each(function(key, val){
				if($(this).is(':checked'))
				{
		    		ChangeSubmitBtnName("Save & Next");
		    		bAnyChecked = true;
		    		return false;
				}
			});
			
			if(!bAnyChecked)
			{
				ChangeSubmitBtnName("Go to Next");
			}
		});
		
		$("input:radio[name='answer[]']").click(function(){
			if($(this).is(':checked'))
			{
	    		ChangeSubmitBtnName("Save & Next");
			}
		});
		
		function ChangeSubmitBtnName(sName)
		{
			$("#submit_ans").val(sName);
			$("#submit_ans_m").val(sName);
		}

		function LoadQuestion(test_id, tschd_id, ques, sec)
		{
			if(document.getElementById("timer") == null)
			{
				//alert ("mipcat-tcs.php?test_id="+test_id+"&sec="+sec+"&ques="+ques);
				location = "mipcat-tcs.php?test_id="+test_id+"&tschd_id="+tschd_id+"&sec="+sec+"&ques="+ques+"&showParaChoice="+$("#showParaChoice").val()+"&prev_linked_to="+$("#prev_linked_to").val()+"&showSectionChoice="+$("#showSectionChoice").val();
			}
			else
			{
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

				//alert(encodeURIComponent("mipcat-tcs.php?test_id="+test_id+"&tschd_id="+tschd_id+"&sec="+sec+"&ques="+ques+"&curtime="+nCurTime+"&showParaChoice="+$("#showParaChoice").val()+"&prev_linked_to="+$("#prev_linked_to").val()+"&showSectionChoice="+$("#showSectionChoice").val()));

				location = "mipcat-tcs.php?test_id="+test_id+"&tschd_id="+tschd_id+"&sec="+sec+"&ques="+ques+"&curtime="+encodeURIComponent(nCurTime)+"&showParaChoice="+$("#showParaChoice").val()+"&prev_linked_to="+$("#prev_linked_to").val()+"&showSectionChoice="+$("#showSectionChoice").val();
			}

			return false;
		}
		
		<?php
		$objIter = $objTH->GetIterator ();
		
		$linked_to = - 1;
		$colorAry = array ("SaddleBrown", "Black" );
		$qtAry = array (CConfig::QT_READ_COMP => "RC", CConfig::QT_DIRECTIONS => "DR" );
		$color = $colorAry [0];
		$index = 0;
		$icon_ary = array (CConfig::QT_READ_COMP => "<i class='fa fa-align-justify'></i>", CConfig::QT_DIRECTIONS => "<i class='fa fa-arrow-right'></i>" );
		$class_ary = array ("default", "danger" );
		
		foreach ( $objAnsAry as $secIndex => $ansSection ) {
			foreach ( $ansSection as $qusIndex => $ansQuestion ) {
				if ($linked_to != $objIter [$secIndex] [$qusIndex] ['linked_to'] && $objIter [$secIndex] [$qusIndex] ['ques_type'] != CConfig::QT_NORMAL) {
					$linked_to = $objIter [$secIndex] [$qusIndex] ['linked_to'];
					$index ++;
					
					printf ( "$(\"<span class='not_visited' style='margin-top: 5px; margin-right: -6px;' onclick='triggerClick(%s)'>%s</span>\").insertBefore('#%s');\n", ((($secIndex + 1) * 1000) + ($qusIndex + 1)), $icon_ary [$objIter [$secIndex] [$qusIndex] ['ques_type']], ((($secIndex + 1) * 1000) + ($qusIndex + 1)) );
					
					if (isset ( $objIter [$secIndex] [$qusIndex + 1] ['linked_to'] ) && $linked_to == $objIter [$secIndex] [$qusIndex + 1] ['linked_to']) {
						echo ("document.getElementById('" . ((($secIndex + 1) * 1000) + ($qusIndex + 1)) . "').setAttribute('style','margin-top: 5px;margin-right: -6px;');\n");
					}
					$color = $colorAry [$index % 2];
					
					if ($nSection == $secIndex && $nQuestion == $qusIndex && $prev_linked_to != $objIter [$secIndex] [$qusIndex] ['linked_to']) {
						$bShowPara = 1;
					}
				} else if ($linked_to == $objIter [$secIndex] [$qusIndex] ['linked_to'] && $objIter [$secIndex] [$qusIndex] ['ques_type'] != CConfig::QT_NORMAL) {
					if (isset ( $objIter [$secIndex] [$qusIndex + 1] ['linked_to'] ) && $linked_to == $objIter [$secIndex] [$qusIndex + 1] ['linked_to']) {
						echo ("document.getElementById('" . ((($secIndex + 1) * 1000) + ($qusIndex + 1)) . "').setAttribute('style','margin-top: 5px;margin-right: -6px;');\n");
					}
					
					if ($nSection == $secIndex && $nQuestion == $qusIndex && $prev_linked_to != $objIter [$secIndex] [$qusIndex] ['linked_to']) {
						$bShowPara = 1;
					}
				} else if ($objIter [$secIndex] [$qusIndex] ['ques_type'] == CConfig::QT_NORMAL) {
					$color = "Blue";
				}
				
				if ((count ( $objAnsAry [$secIndex] [$qusIndex] ) > 0 && count(array_intersect(array(-1, -2, -3), $objAnsAry [$secIndex] [$qusIndex])) == 0 )) {
					if ($nSection == $secIndex && $nQuestion == $qusIndex) {
						echo ("document.getElementById('" . ((($secIndex + 1) * 1000) + ($qusIndex + 1)) . "').setAttribute('class','answered highlight-button');\n");
					} else {
						echo ("document.getElementById('" . ((($secIndex + 1) * 1000) + ($qusIndex + 1)) . "').setAttribute('class','answered');\n");
					}
				} else if ((count ( $objAnsAry [$secIndex] [$qusIndex] ) == 1 && in_array ( - 1, $objAnsAry [$secIndex] [$qusIndex] ))) {
					if ($nSection == $secIndex && $nQuestion == $qusIndex) {
						echo ("document.getElementById('" . ((($secIndex + 1) * 1000) + ($qusIndex + 1)) . "').setAttribute('class','not_visited highlight-button');\n");
					}
				} else if ((count ( $objAnsAry [$secIndex] [$qusIndex] ) == 1 && in_array ( - 2, $objAnsAry [$secIndex] [$qusIndex] ))) {
					if ($nSection == $secIndex && $nQuestion == $qusIndex) {
						echo ("document.getElementById('" . ((($secIndex + 1) * 1000) + ($qusIndex + 1)) . "').setAttribute('class','review highlight-button');\n");
					} else {
						echo ("document.getElementById('" . ((($secIndex + 1) * 1000) + ($qusIndex + 1)) . "').setAttribute('class','review');\n");
					}
				} else if ((count ( $objAnsAry [$secIndex] [$qusIndex] ) == 1 && in_array ( - 3, $objAnsAry [$secIndex] [$qusIndex] ))) {
					if ($nSection == $secIndex && $nQuestion == $qusIndex) {
						echo ("document.getElementById('" . ((($secIndex + 1) * 1000) + ($qusIndex + 1)) . "').setAttribute('class','not_answered highlight-button');\n");
					} else {
						echo ("document.getElementById('" . ((($secIndex + 1) * 1000) + ($qusIndex + 1)) . "').setAttribute('class','not_answered');\n");
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
		if ($aryQues ['ques_type'] != CConfig::QT_NORMAL) {
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
			<script type="text/x-mathjax-config">
  			MathJax.Hub.Config({
    			tex2jax: {inlineMath: [["$","$"],["\\(","\\)"]]}
 			});
		</script>
		</div>

</body>
</html>
