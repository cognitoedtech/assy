<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
include_once (dirname ( __FILE__ ) . "/../../lib/session_manager.php");
include_once (dirname ( __FILE__ ) . "/../../database/mcat_db.php");
include_once (dirname ( __FILE__ ) . "/../../lib/utils.php");
include_once (dirname ( __FILE__ ) . "/../../lib/include_js_css.php");
include_once (dirname ( __FILE__ ) . "/../../lib/site_config.php");

// - - - - - - - - - - - - - - - - -
// On Session Expire Load ROOT_URL
// - - - - - - - - - - - - - - - - -
CSessionManager::OnSessionExpire ();
// - - - - - - - - - - - - - - - - -

$objDB = new CMcatDB ();

$parsAry = parse_url ( CUtils::curPageURL () );
$qry = explode ( "=", $parsAry ["query"] );

$user_id = CSessionManager::Get ( CSessionManager::STR_USER_ID );
$user_type = CSessionManager::Get ( CSessionManager::INT_USER_TYPE );

$sTestName = "";
if ($qry [0] == "test_name") {
	echo "<script>save_success = 1; </script>";
	$sTestName = urldecode ( $qry [1] );
} else {
	echo "<script>save_success = 0; </script>";
}

$objIncludeJsCSS = new IncludeJSCSS ();

$menu_id = CSiteConfig::UAMM_DESIGN_MANAGE_TEST;
$page_id = CSiteConfig::UAP_TEST_DESIGN_WIZARD;

$plan_type = CSessionManager::Get ( CSessionManager::INT_APPLIED_PLAN );
?>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="Generator" content="Mastishka Intellisys Private Limited">
<meta name="Author" content="Mastishka Intellisys Private Limited">
<meta name="Keywords" content="">
<meta name="Description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo(CConfig::SNC_SITE_NAME); ?>: Test Design Wizard</title>
<?php
$objIncludeJsCSS->CommonIncludeCSS("../../");
$objIncludeJsCSS->IncludeIconFontCSS ( "../../" );
$objIncludeJsCSS->IncludeBootStrapWYSIHTML5CSS( "../../" );
$objIncludeJsCSS->IncludeJqueryStepyCSS("../../");
$objIncludeJsCSS->IncludeFuelUXCSS ("../../");

$objIncludeJsCSS->CommonIncludeJS("../../");
$objIncludeJsCSS->IncludeMetroNotificationJS("../../");
$objIncludeJsCSS->IncludeMetroAccordionJS("../../");
$objIncludeJsCSS->IncludeJqueryValidateMinJS("../../");
$objIncludeJsCSS->IncludeJQueryStepyMinJS("../../");
$objIncludeJsCSS->CommonIncludeWYSIHTMLJS("../../");
$objIncludeJsCSS->IncludeUtilsJS("../../");
?>
<style type="text/css">
	.wizard > .content > .body input[type="radio"]
	{
	    display: inline;
	}
	.modal, .modal.fade.in {
	    top: 15%;
	}
	.js-responsive-table thead{font-weight: bold}	
	.js-responsive-table th{ -moz-box-sizing: border-box; -webkit-box-sizing: border-box;-o-box-sizing: border-box;-ms-box-sizing: border-box;box-sizing: border-box;padding: 0px;}
	.js-responsive-table th span{display: none}		
	
	@media all and (max-width:767px){
		.js-responsive-table{width: 100%;max-width: 400px;}
		.js-responsive-table thead{display: none}
		.js-responsive-table th{width: 100%;display: block}
		.js-responsive-table th span{float: left;font-weight: bold;display: block}
		.js-responsive-table th span:after{content:' : '}
		.js-responsive-table th{border:0px;border-bottom:1px solid #ddd}	
		.js-responsive-table tr:last-child th:last-child{border: 0px}		
	}
	
	.js-responsive-table th{text-align: center;}
	
	.step {
		width: 100%;
	}
	
	.metro .accordion.with-marker .heading:before {
	  top: 10px;
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
</style>
</head>
<body>
	<?php
	include_once (dirname ( __FILE__ ) . "/../../lib/header.php");
	?>

	<!-- --------------------------------------------------------------- -->
	<br />
	<br />
	<br />
	<div class='row-fluid'>
		<div class="col-sm-3 col-md-3 col-lg-3">
			<?php
			include_once (dirname ( __FILE__ ) . "/../../lib/sidebar.php");
			?>
		</div>
		<div class="col-sm-9 col-md-9 col-lg-9" style="border-left: 1px solid #ddd; border-top: 1px solid #ddd;">
			<div class="fuelux modal1">
				<div class="preloader"><i></i><i></i><i></i><i></i></div>
			</div>
			<form id="callback" method="POST" action="post_get/form_test_wizard.php">
				<fieldset title="Test Details" style="padding: 12px;">
					<legend>
						Basic Test Deatils
					</legend>
					<div class="col-sm-6 col-md-6 col-lg-6">
						<div class="row fluid">
							<div class="col-sm-6 col-md-6 col-lg-6">
								<label>Test Name : </label>
								<input type="text" class="form-control input-sm" onkeyup="OnTestNameChange(this);" size="40" name="test_name" value="<?php echo(uniqid("eza-"));?>" /> 
							</div>
							<div class="col-sm-4 col-md-4 col-lg-4" style="padding-left: 0px;">
								<br /><br /><br />
								<i class="icon-help mip-help" data-toggle="tooltip" trigger="click hover focus" data-placement="right" title="Unique test template name."></i>
								<span id="t_checking" style="display: none;">&nbsp;<img src="../../images/updating.gif" width="12" height="12" /> Checking</span>
								<span id="t_exist" style="color: red; display: none;">&nbsp;Name already exists!</span>
							</div>
						</div>
						<div class="row fluid">
							<div class="col-sm-6 col-md-6 col-lg-6">
								<label>Test Duration (in minutes): </label>
								<input type="text" class="form-control input-sm" name="duration" value="60" />
							</div>
							<div class="col-sm-4 col-md-4 col-lg-4" style="padding-left: 0px;">
								<br /><br /><br />
								<i class="icon-help mip-help" data-toggle="tooltip" trigger="click hover focus" data-placement="right" title="Test duration in minutes."></i>
							</div>
						</div>
						<div class="row fluid">
							<div class="col-sm-6 col-md-6 col-lg-6">
								<label>Total Number of Questions : </label>
								<input type="text" class="form-control input-sm" onchange="pageDirty.SetDirty(1);" onkeyup="OnSectionQuesEnter();" name="max_ques" value="50" />
							</div>
							<div class="col-sm-4 col-md-4 col-lg-4" style="padding-left: 0px;">
								<br /><br /><br />
								<i class="icon-help mip-help" data-toggle="tooltip" trigger="click hover focus" data-placement="right" title="Total number of questions in test, including all sections (cumulative)."></i>
							</div>
						</div>
						<div class="row fluid">
							<div class="col-sm-6 col-md-6 col-lg-6">
								<label>Minimum CutOff % (in percent): </label>
								<input class="form-control input-sm" type="text" name="cutoff_min" onchange="OnCutoffChange(this);" value="50" />
							</div>
							<div class="col-sm-4 col-md-4 col-lg-4" style="padding-left: 0px;">
								<br /><br /><br />
								<i class="icon-help mip-help" data-toggle="tooltip" trigger="click hover focus" data-placement="right" title="Cut-off range selection (lower cap)."></i>
							</div>
						</div>
						<div class="row fluid">
							<div class="col-sm-6 col-md-6 col-lg-6">
								<label>Maximum CutOff % (in percent): </label>
								<input type="text" class="form-control input-sm" name="cutoff_max" onchange="OnCutoffChange(this);" value="100" />
							</div>
							<div class="col-sm-4 col-md-4 col-lg-4" style="padding-left: 0px;">
								<br /><br /><br />
								<i class="icon-help mip-help" data-toggle="tooltip" trigger="click hover focus" data-placement="right" title="Cut-off range selection (upper cap)."></i>
							</div>
						</div>
						<div class="row fluid">
							<div class="col-sm-6 col-md-6 col-lg-6">
								<label>Number of Groups : </label>
								<input type="text" class="form-control input-sm" onchange="pageDirty.SetDirty(1);" name="group_count" value="1" />
							</div>
							<div class="col-sm-4 col-md-4 col-lg-4" style="padding-left: 0px;">
								<br /><br /><br />
								<i class="icon-help mip-help" data-toggle="tooltip" trigger="click hover focus" data-placement="right" title="Total number of Groups in test, you then set details of each group in coming steps of this wizard."></i>
							</div>
						</div>
						<div class="row fluid">
							<div class="col-sm-6 col-md-6 col-lg-6">
								<label>Number of Sections : </label>
								<input type="text" class="form-control input-sm" onchange="pageDirty.SetDirty(1);" onkeyup="OnSecCountChange(this)" name="sec_count" value="1" />
							</div>
							<div class="col-sm-4 col-md-4 col-lg-4" style="padding-left: 0px;">
								<br /><br /><br />
								<i class="icon-help mip-help" data-toggle="tooltip" trigger="click hover focus" data-placement="right" title="Total number of Sections in test, you then set details of each section in coming steps of this wizard."></i>
							</div>
						</div>
						<div class="row fluid">
							<div class="col-sm-6 col-md-6 col-lg-6">
								<label>Marking Scheme : </label>
								<input checked type="radio" name="marking_scheme" value="consistent" onChange="OnMarkingSchemeChange();" />Consistent 
								<input type="radio" name="marking_scheme" value="section_wise" onChange="OnMarkingSchemeChange();" />Section Wise
								<i class="icon-help mip-help" data-html="true" data-toggle="tooltip" trigger="click hover focus" data-placement="right" html="true" title="You may either select <b> Consistent or Section Wise</b> marking scheme through out all the sections."></i>
							</div>
						</div>
						<div id="scheme">
							<div class="row fluid">
								<div class="col-sm-6 col-md-6 col-lg-6">
									<label>Marks for Correct Answer :</label>
									<input type="text" class="form-control input-sm" name="r_marks" value="1" onchange="OnMarksChange(this);" />
								</div>
								<div class="col-sm-4 col-md-4 col-lg-4" style="padding-left: 0px;">
									<br /><br /><br />
									<i class="icon-help mip-help" data-toggle="tooltip" trigger="click hover focus" data-placement="right" title="If you choose 2 marks for correct answer and Max Questions: 100, then total marks for test will be 200."></i>
								</div>
							</div>
							<div class="row fluid">
								<div class="col-sm-6 col-md-6 col-lg-6">
									<label>Partial Marks (on MCA) :</label>
									<input type="text" class="form-control input-sm" name="p_marks" value="0" onchange="OnPartialMarksChange(this);" />
								</div>
								<div class="col-sm-4 col-md-4 col-lg-4" style="padding-left: 0px;">
									<br /><br /><br />
									<i class="icon-help mip-help" data-toggle="tooltip" trigger="click hover focus" data-placement="right" title="If you choose 4 marks for correct answer and for Multiple Correct Answer question or Matrix Question, if answered partially correct then every correct option will be allocated partial marks."></i>
								</div>
							</div>
							<div class="row fluid">
								<div class="col-sm-6 col-md-6 col-lg-6">
									<label>Negative Marking (if any) : </label>
									<input type="text" class="form-control input-sm" name="w_marks" value="0" onchange="OnMarksChange(this);" />
								</div>
								<div class="col-sm-4 col-md-4 col-lg-4" style="padding-left: 0px;">
									<br /><br /><br />
									<i class="icon-help mip-help" data-toggle="tooltip" trigger="click hover focus" data-placement="right" title="Enter absolute value, you don't need to put -ive sign. For example if you want 0.5 as -ive marks for wrong answer, just enter 0.5"></i>
								</div>
							</div>
						</div>
						<div class="row fluid">
							<div class="col-sm-6 col-md-6 col-lg-6">
								<label>Question Source :</label>
								<input type="radio" onchange="PopulateLanguageSet('mipcat');" name="ques_source" value="mipcat" <?php echo((($plan_type == CConfig::SPT_BASIC || $plan_type == CConfig::SPT_PPU) && $user_type != CConfig::UT_SUPER_ADMIN)?"":"checked='checked'");?>/> <span style="color: blue"><?php echo(CConfig::SNC_SITE_NAME);?></span>
								<i class="icon-help mip-help" data-toggle="tooltip" trigger="click hover focus" data-placement="right" title="Questions will be pulled from <?php echo(CConfig::SNC_SITE_NAME);?> knowledge-base and cost per candidate per test will be chared accordingly."></i>
								
								<input type="radio" onchange="PopulateLanguageSet('personal');" name="ques_source" value="personal" <?php echo((($plan_type == CConfig::SPT_BASIC || $plan_type == CConfig::SPT_PPU) && $user_type != CConfig::UT_SUPER_ADMIN)?"checked='checked'":"");?>/> <span style="color: blue">Personal</span>
								<i class="icon-help mip-help" data-toggle="tooltip" trigger="click hover focus" data-placement="right" title="Questions will be pulled from your personal knowledge-base and cost per candidate per test will be chared accordingly."></i>
								
								<script type="text/javascript">
									$("input[name=ques_source]:radio").bind( "change", function() { pageDirty.SetDirty(1); } );
								</script>
							</div>
						</div>
					</div>
					<div class="col-sm-6 col-md-6 col-lg-6">
						<fieldset style="border: 1px solid #ddd;padding: 5px;">
							<legend>Test Attributes</legend>
							<div class="row fluid">
								<div class="col-sm-12 col-md-12 col-lg-12">
									<label>Visibility of Result Analytics to Candidate: </label>
									<input type="radio" name="visibility" value="0"> None</input>
									<i class="icon-help mip-help" data-toggle="tooltip" data-html="true" data-placement="right" html="true" title="" data-original-title="<span style='color: red'>None:</span>After exam completion, candidate will only be able to see question attempted and questions unanswered."></i>
									
									<input type="radio" name="visibility" value="1"> Minimal</input>
									<i class="icon-help mip-help" data-html="true" data-toggle="tooltip" trigger="click hover focus" data-placement="right" html="true" title="<span style='color: red'>Minimal:</span> After exam
										completion - candidate will be able to see number of right, wrong
										and unanswered questions.">
									</i> 
									
									<input checked type="radio" name="visibility" value="2"> Detailed</input>
									<i class="icon-help mip-help" data-html="true" data-toggle="tooltip" trigger="click hover focus" data-placement="right" html="true" title="<span style='color: red'>Detailed</span> After exam
										completion - candidate will be able to see detailed performance
										analysis in result analytics section.">
									</i>
									<label>
										* <span style="color: blue">Result Analytics</span> helps in rational resoning for candidate's performance.
									</label>
									<hr />
								</div>
							</div>
							<div class="row fluid" style="display:none;">
								<div class="col-sm-12 col-md-12 col-lg-12">
									<label>Question Type:</label>
									<input type="radio" name="ques_type" value="<?php echo(CConfig::QUES_CTG_SCA);?>" />
									<span style='color: darkred'><b>Single Correct Answer</b></span>
									<span style="color: blue">(MCQ-SCA)</span>
									<i class="icon-help mip-help" data-html="true" data-toggle="tooltip" trigger="click hover focus" data-placement="right" html="true" title="<span style='color: red'>MCQ-SCA:</span> Questions with only single correct answer."></i>
									<br /> 
									
									<input type="radio" name="ques_type" value="<?php echo(CConfig::QUES_CTG_MCA);?>" checked/>
									<span style='color: darkred'><b>Multiple Correct Answers</b></span>
									<span style="color: blue">(MCQ-MCA)</span>
									<i class="icon-help mip-help" data-html="true" data-toggle="tooltip" trigger="click hover focus" data-placement="right" html="true" title="<span style='color: red'>MCQ-MCA:</span> Questions with multiple correct answers."></i>
									<hr />
								</div>
							</div>
							<div class="row fluid">
								<div class="col-sm-12 col-md-12 col-lg-12">
									<div class="row fluid">
										<div class="col-sm-5 col-md-5 col-lg-5">
											<label>Preferred Test Language:</label>
											<select class="form-control input-sm" name="pref_lang" id="pref_lang">
											</select>
										</div>
									</div>
									
									<div class="row fluid">
										<div class="col-sm-12 col-md-12 col-lg-12">
											<label>Provide Translation Choice to Candidate:</label>
											
											<input type="radio" name="allow_trans" value="1" /><span style="color: blue"> Yes </span>
											<i class="icon-help mip-help" data-html="true" data-toggle="tooltip" trigger="click hover focus" data-placement="right" html="true" title="<span style='color: red'>Yes:</span> 
												Candidate will be
												able to choose language for test instruction, translated version
												of test paper and two language (prefered language + another
												choice) support.">
											</i>
											
											<input type="radio" name="allow_trans" value="0" checked /><span style="color: blue"> No </span>
											<i class="icon-help mip-help" data-html="true" data-toggle="tooltip" trigger="click hover focus" data-placement="right" html="true" title="<span style='color: red'>No:</span> Test will be conducted only in preferred language."></i>
										
											<script type="text/javascript">
												$("#pref_lang").bind( "change", function() { pageDirty.SetDirty(1); } );
											</script>
											<hr />
										</div>
									</div>
									<div class="row fluid" hidden="hidden">
										<div class="col-sm-2 col-md-2 col-lg-2">
											<label>Test Expiration: </label>
											<select class="form-control input-sm" id="test_expiration" name="test_expiration">
												<option value="-1">Never</option>
												<option value="0.25">06 HRS</option>
												<option value="0.50">12 HRS</option>
												<option value="1">1 Day</option>
												<option value="2">2 Days</option>
												<option value="3">3 Days</option>
												<option value="4">4 Days</option>
												<option value="5">5 Days</option>
												<option value="6">6 Days</option>
												<option value="7">7 Days</option>
											</select>
										</div>
										<div class="col-sm-4 col-md-4 col-lg-4" style="padding-left: 0px;">
											<!-- <br /><br /><br /> -->
											<i class="icon-help mip-help" data-html="true" data-toggle="tooltip" trigger="click hover focus" data-placement="right" html="true" title="<span   style='color:red'>Test Expiration:</span> Once the test is started, it has to be finished before expiration period.This period is including number of atempts."></i>
										</div>
									</div><!-- <br /> -->
									<div class="row fluid" hidden="hidden">
										<div class="col-sm-2 col-md-2 col-lg-2">
											<label>Number of Attempts: </label>
											<select class="form-control input-sm" id="attempts" name="attempts">
												<option value="-1">Unlimited</option>
												<option value="1">One (01)</option>
												<option value="2">Two (02)</option>
												<option value="5">Five (05)</option>
												<option value="10">Ten (10)</option>
												<option value="15">Fifteen (15)</option>
												<option value="20">Twenty (20)</option>
												<option value="30">Thirty (30)</option>
												<option value="40">Fourty (40)</option>
												<option value="50">Fifty (50)</option>
											</select>
										</div>
										<div class="col-sm-4 col-md-4 col-lg-4" style="padding-left: 0px;">
											<br /><br /><br />
											<i class="icon-help mip-help" data-html="true" data-toggle="tooltip" trigger="click hover focus" data-placement="right" html="true" title="<span   style='color:red'>Attempts:</span> It may be possible	that test may get interupted due to network issues, browser crash, power failuer or manual error. Considering those cases you may choose number of attempts."></i>
										</div>
									</div><br />
									<div class="row fluid">
										<div class="col-sm-12 col-md-12 col-lg-12">
											<label><i class="icon-cycle"></i> Flash Question (MCPA Security Parameter): </label> 
											<input type="radio"	name="flash_ques" value="1">Yes</input> 
											<input checked type="radio" name="flash_ques" value="0">No</input> 
											<i class="icon-help mip-help" data-html="true" data-toggle="tooltip" trigger="click hover focus" data-placement="right" html="true" title="<span   style='color: red'>MCPA Flash:</span> If selected	yes, the question seen in adaptive test but not ansered by candidate - will be changed from question of same topic and difficulty level."></i>
										</div>
									</div><br />
									<div class="row fluid">
										<div class="col-sm-12 col-md-12 col-lg-12">
											<label><i class="icon-locked"></i> Lock Question (MCPA Security Parameter): </label>
											<input type="radio" name="lock_ques" value="1" />Yes
											<input checked type="radio" name="lock_ques" value="0" />No
											<i class="icon-help mip-help" data-html="true" data-toggle="tooltip" trigger="click hover focus" data-placement="right" html="true" title="<span   style='color: red'>MCPA Lock:</span> If selected yes, the question answered by candidate will be locked - i.e. once answered candidate will not be able to change the answer."></i>
										</div>
									</div><br />
									<div class="row fluid">
										<div class="col-sm-11 col-md-11 col-lg-11">
											<label id="tag_label">Question Set (Tag): </label>
											<select class="form-control input-sm" id="tag" name="tag" OnChange="ShowShuffleOption(this)">
											</select>
										</div>
										<script type="text/javascript">
											$("#tag").bind( "change", function() { pageDirty.SetDirty(1); } );
										</script>
									</div>
									
									<div class="row fluid" id="div_shuffle" style="display:none">
										<div class="col-sm-12 col-md-12 col-lg-12">
											<label id="tag_label">Shuffe Questions:
											<input type ="checkbox"  id="shuffle" name="shuffle" checked="true" />
											 </label>
										</div>
										<script type="text/javascript">
											$("#shuffle").bind( "change", function() { pageDirty.SetDirty(1); } );
										</script>
									</div>
									<div class="row fluid" >
										<div class="col-sm-11 col-md-11 col-lg-11" >
											<label>* <span style="color: blue">MCPA</span> stands for <span
												style="color: blue">Mastishka Cheating Prevention Algorithm &copy; <?php echo(date('Y')); ?></span>.
											</label>
										</div>
									</div>
								</div>
							</div>
						</fieldset>
						<br /><br /> 
					</div>
				</fieldset>
		
				<fieldset title="Instructions" style="padding: 12px;">
					<legend>
						Custom Test Instruction
					</legend>
					
					<div class="row fluid">
						<div class="col-sm-3 col-md-3 col-lg-3">
							<label>Instruction Language: </label> 
							<select style="width: 60%" class="form-control input-sm" name="instr_lang" id="instr_lang">
							</select>
						</div>
					</div>
					<div class="row fluid">
						<div class="col-sm-8 col-md-8 col-lg-8">
							<label>Add custom test instructions: </label>
							<textarea style="height: 200px;" class="form-control" placeholder="Enter test instructions ..." id="cust_instruction" name="cust_instruction"></textarea>
							<br /> 
							<input style="margin-bottom: 20px;" class="btn btn-primary" type="button" id="id_add_cust_instr" onclick="OnAddCustInstructions();" value="Add !"></input>
							<div id="custom_instructions"></div>
						</div>
					</div>
				</fieldset>
		
				<fieldset title="Group" style="padding: 12px;">
					<legend>
						Create Groups <br/>for Sections 
					</legend>
		
					<div class="row fluid" id="group_div">
						<div id='group_remaining_section'>
							<div class='row'>
								<div class='col-sm-offset-1 col-sm-10' style='margin-bottom: 15px;'>
								<div class='col-sm-3'><label>Sections to distribute :</label></div>
								<div class='col-sm-2'><input class='form-control input-sm' type='text' id='RemainingSections' name='RemainingSections' value='' readonly='readonly'/></div>
							</div>
						</div>
					</div>
				</fieldset>
		
				<fieldset title="Section" style="padding: 12px;">
					<legend>
						Provide Section<br/> Details
					</legend>
					<div class="row fluid">
						<div class="metro">
							<div class="accordion with-marker col-sm-4 col-md-4 col-lg-4" id="accordion" data-role="accordion">
							</div>
						</div>
					</div>
					<div class="row fluid">
						<div class="col-sm-3 col-md-3 col-lg-3">
							<label>Questions Remaining: </label>
							<input type="text" class="form-control input-sm" readonly="readonly" name="ques_remaining" value="50" />
						</div>
					</div><br />
				</fieldset>
		
				<fieldset title="Subjects" style="padding: 12px;">
					<legend>
						Subjects Under<br /> Each Section
					</legend>
		
					<div id="subject_tab" class='col-sm-12 col-md-12 col-lg-12' style="font: 100% 'Trebuchet MS', sans-serif;">
					</div>
				</fieldset>
		
				<fieldset title="Topics" style="padding: 12px;">
					<legend>
						Topics Under<br /> Each Subject
					</legend>
		
					<div id="topic_tab" class='col-sm-12 col-md-12 col-lg-12' style="font: 100% 'Trebuchet MS', sans-serif;"></div>
				</fieldset>
		
				<fieldset title="Save" style="padding: 12px;">
					<legend>
						Preview<br/> Before Save
					</legend>
		
					<div id="preview_tab" style="font: 100% 'Trebuchet MS', sans-serif;">
		
					</div>
				</fieldset>
		
				<div class="modal" id="para_detail_modal">
				  	<div class="modal-dialog">
				    	<div class="modal-content">
				      		<div class="modal-header">
				       		 	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				        		<h4 class="modal-title"></h4>
				      		</div>
					      	<div class="modal-body">
					      	</div>
				      		<div class="modal-footer">
					        	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				      		</div>
				    	</div>
				  	</div>
				</div>
				
				<input type="submit" class="finish" value="Finish!" />
			</form>
			<?php 
				include(dirname ( __FILE__ )."/../../lib/footer.php");
			?>
		</div>
	</div>

	<script type="text/javascript">
		$(window).on('load', function(){
			$('#cust_instruction').wysihtml5();
			
			$(".mip-help").tooltip();
			
			var ques_src = $("input[name='ques_source']:checked").val() ;
			PopulateLanguageSet(ques_src);
		});

		var pageDirty = {
			'page_1' : false,
			'page_2' : false,
			'page_3' : false,
			'page_4' : false,
			'page_5' : false,
			'CheckPage' : function(page) {
				var bRet = false;
				
				switch(page)
				{
					case 1:
						bRet = this.page_1;
						break;
					case 2:
						bRet = this.page_2;
						break;
					case 3:
						bRet = this.page_3;
						break;
					case 4:
						bRet = this.page_4;
						break;
					case 5:
						bRet = this.page_5;
						break;
				}
				
				return bRet;
			},
			'SetDirty' : function(page) {
				switch(page)
				{
					case 1:
						this.page_1 = true;
						break;
					case 2:
						this.page_2 = true;
						break;
					case 3:
						this.page_3 = true;
						break;
					case 4:
						this.page_4 = true;
						break;
					case 5:
						this.page_5 = true;
						break;
				}
			},
			'UnsetDirty' : function(page) {
				switch(page)
				{
					case 1:
						this.page_1 = false;
						break;
					case 2:
						this.page_2 = false;
						break;
					case 3:
						this.page_3 = false;
						break;
					case 4:
						this.page_4 = false;
						break;
					case 5:
						this.page_5 = false;
						break;
				}
			}
		};
		
		$('#callback').stepy({
			validate: true,
			errorImage: true,
			block: true,
			back: function(index) {
				//alert('Going to step ' + index + '...');
			}, next: function(index) {
				if(index == 2)
				{
					
				}
				else if(index == 3)
				{
					//var ques_src = $("input[name='ques_source']:checked").val() ;
					GroupAddPanel(parseInt($('input[name="group_count"]').val()));
				}
				else if(index == 4)
				{
					SectionAddPanel(parseInt($('input[name="sec_count"]').val()));
				}
				else if (index == 5)
				{	
					if(pageDirty.CheckPage(1))
					{
						if( SectionAddPanel(parseInt($('input[name="sec_count"]').val())) )
						{
							SubjectAddPane(parseInt($('input[name="sec_count"]').val()));
						}
					}
					else
					{
						SubjectAddPane(parseInt($('input[name="sec_count"]').val()));
					}
				}
				else if(index == 6)
				{
					TopicAddPane();
				}
				else if(index == 7)
				{
					Preview();
				}
			}, select: function(index) {
				if(index == 2)
				{
					
					
				}
				else if(index == 3)
				{
					//var ques_src = $("input[name='ques_source']:checked").val() ;
					GroupAddPanel(parseInt($('input[name="group_count"]').val()));
				}
				else if(index == 4)
				{
					SectionAddPanel(parseInt($('input[name="sec_count"]').val()));
				}
				else if (index == 5)
				{	
					/*if(pageDirty.CheckPage(1))
					{
						if( SectionAddPanel(parseInt($('input[name="sec_count"]').val())) )
						{
							SubjectAddPane(parseInt($('input[name="sec_count"]').val()));
						}
					}
					else
					{
						SubjectAddPane(parseInt($('input[name="sec_count"]').val()));
					}*/
				}
				else if(index == 6)
				{
					TopicAddPane();
				}
				else if(index == 7)
				{
					Preview();
				}
			}, finish: function(index) {
			}, titleClick: true
		});

		jQuery.validator.addMethod("MinCutoffGreater", function(value, element) {
			return (!bGreaterMinCutoff);
		}, "Minimum cutoff should be less than maximum cutoff !");

		jQuery.validator.addMethod("SecMinCutoffGreater", function(value, element) {
			var remainingName = $(element).attr('name').slice(16);

			var min_cutoff = parseInt($('input[name="SectionMinCutoff'+remainingName+'"]').val());
			var max_cutoff = parseInt($('input[name="SectionMaxCutoff'+remainingName+'"]').val());

			if(min_cutoff > max_cutoff)
			{
				return false;
			}
			return true;
		}, "Minimum cutoff should be less than maximum cutoff !");
		
		jQuery.validator.addMethod("TestNameExists", function(value, element) {
			return (!bTestExist);
		}, "Test Name Already Exists !");

		jQuery.validator.addMethod("NegetiveNumber", function(value, element) {
			return Number(value) >= 0;
		}, "Please enter only positive number. !");
		
		jQuery.validator.addMethod("alphanumeric", function(value, element) {
			return this.optional(element) || /^[a-zA-Z0-9_]+$/.test(value);
		}, "Field required only alphanumeric letters (underscore is allowed) !");

		jQuery.validator.addMethod("GroupAlphanumeric", function(value, element) {
			return this.optional(element) || /^[a-zA-Z0-9_\~]+$/.test(value);
		}, "Field required only alphanumeric letters (underscore and tilde are allowed) !");

		jQuery.validator.addMethod("SectionNameExists", function(value, element) {
			return (!bSectionExist);
		}, "Section name should be unique !");

		$('#callback').validate({
			errorPlacement: function(error, element) {
				$('#callback div.stepy-error').append(error);
			}, rules: {
				'test_name':		{required: true, 'TestNameExists': true},
				'duration':			{required:true, digits: true, 'NegetiveNumber' : true},
				'max_ques':			{required:true, max:300, 'NegetiveNumber' : true},
				'cutoff_min':		{required:true, digits: true, 'MinCutoffGreater': true},
				'cutoff_max':		{required:true, digits: true},
				'top':				{required:true, digits: true},
				'r_marks':			{required:true, number: true, 'NegetiveNumber' : true},
				'p_marks':			{required:true, number: true, 'NegetiveNumber' : true},
				'w_marks':			{required:true, number: true, 'NegetiveNumber' : true},
				'group_count':		{required:true, digits: true, min:1},
				'sec_count':		{required:true, digits: true, min:1},
				'RemainingSections': {min:0, max:0},
				'GroupName[]': 		 {required:true, 'alphanumeric': true},		
				'GroupSection[]' :	 {required:true, digits: true, min: 1},
				'ques_remaining':	{min:0, max:0}
			}, messages: {
				'test_name':		{required: "Please provide a name for the test!"},
				'duration':			{required: "Please provide test duration in minutes!", digits: "Please only enter digits for test duration!"},
				'max_ques':			{required: "Please provide total number of questions for test!", max:"Total number of questions for test can not be more than 200!"},
				'cutoff_min':		{required: "Please provide minimum cutoff marks for the test!", digits: "Please only enter numeric digits for minimum cutoff marks!"},
				'cutoff_max':		{required: "Please provide maximum cutoff marks for the test!", digits: "Please only enter numeric digits for maximum cutoff marks!"},
				'top':				{required: "Please provide top N as passing criteria!", digits: "Please only enter digits for top N as passing criteria!"},
				'r_marks':			{required: "Please provide marks for every correct answer!", number: "Please only enter numeric value for marks for every correct answer!"},
				'p_marks':			{required: "Please provide marks for every partial correct answer!", number: "Please only enter numeric value for marks for every partial correct answer!"},
				'w_marks':			{required: "Please provide marks for every wrong answer!", number: "Please only enter numeric value for marks for every wrong answer!"},
				'group_count':		{required: "Please enter number of sections you will be needing for test!", digits: "Please only enter digits for number of groups!", min: "There should be atleast one group!"},
				'sec_count':		{required: "Please enter number of sections you will be needing for test!", digits: "Please only enter digits for number of sections!", min: "There should be atleast one section!"},
				'RemainingSections':	{min: "Please distribute sections properly to every group (remaining sections should be zero)!", max:"Please distribute sections properly to every group (remaining sections should be zero)!"},
				'GroupName[]': 		{required: "Please enter group name!", 'alphanumeric': "Please only enter alphanumeric group name!"},
				'GroupSection[]' :	{required: "Please assign atleast one section to group!", digits: "Please only enter digits for number of sections in group!"},
				'ques_remaining':	{min: "Please distribute questions properly to every section (remaining questions should be zero)!", max:"Please distribute questions properly to every section (remaining questions should be zero)!"}
			}
		});
		
		//PopulateLanguageSet.Toggle = false;
		function PopulateLanguageSet(ques_src)
		{
			var qrystr = "";
			if(ques_src == "mipcat")
			{
				qrystr = "mipcat=1";
			}
			else if(ques_src == "personal")
			{
				qrystr = "user_id=<?php echo($user_id);?>";
			}
			
			/*$('body').on({
			    ajaxStart: function() { 
			    	$(this).addClass("loading"); 
			    },
			    ajaxStop: function() { 
			    	$(this).removeClass("loading"); 
			    }    
			});*/

			$(".modal1").show();
			$("#tag").load("../ajax/ajax_get_ques_tags.php",{ques_source : ques_src}, function(){
				
			});
			
			$.getJSON("../ajax/ajax_get_languages.php?"+qrystr, function(data) {
				$("#instr_lang").empty();
				$("#pref_lang").empty();
				$.each(data, function(index, value) {
					//alert("<option value='"+value+"'>"+objUtils.ucfirst(value)+"</option>");
					$("#instr_lang").append("<option value='"+value+"'>"+objUtils.ucfirst(value)+"</option>");
					$("#pref_lang").append("<option value='"+value+"'>"+objUtils.ucfirst(value)+"</option>");
				});
				$(".modal1").hide();
			});
		}

		var bGreaterMinCutoff = false;
		function OnCutoffChange(obj)
		{
			var min_cutoff = "";
			var max_cutoff = "";
			if($(obj).attr('name') == "cutoff_min" || $(obj).attr('name') == "cutoff_max")
			{
				min_cutoff = parseInt($('input[name="cutoff_min"]').val());
				max_cutoff = parseInt($('input[name="cutoff_max"]').val());
				
				if(min_cutoff > max_cutoff)
				{
					bGreaterMinCutoff = true;
				}
				else
				{
					bGreaterMinCutoff = false;
				}
			}
		}
		
		function OnSelectionCriteria(top)
		{
			if(top == 1)
			{
				document.getElementById("cutoff").style.display='none';
				document.getElementById("top").style.display='block';
			}
			else
			{
				document.getElementById("cutoff").style.display='block';
				document.getElementById("top").style.display='none';
			}
		}

		function OnSecCountChange(obj)
		{
			$('input[name="RemainingSections"]').val(obj.value);
		}

		function OnSectionQuesEnter()
		{
			var sec_count = parseInt($('input[name="sec_count"]').val());
			var total_ques = parseInt($('input[name="max_ques"]').val());	
			
			var ques_used = 0;
			for(var index=0; index < sec_count; index++)
			{
				if(!isNaN(parseInt($('input[name="SectionQuestions['+index+']"]').val())))
				{
					ques_used += parseInt($('input[name="SectionQuestions['+index+']"]').val());
				}
				else
				{
					ques_used += 0;
				}
			}
			
			$('input[name="ques_remaining"]').val(total_ques-ques_used);
			$('input[name="ques_remaining"]').keyup();
			
			pageDirty.SetDirty(2);
		}

		var bGroupExist = false;
		function OnGroupNameEnter(obj)
		{
			$('input[name*="GroupName"]').each(function(){
				if($(this).attr('name') != obj.name)
				{
					if($(this).val() == $(obj).val())
					{
						bGroupExist = true;
						return;
					}
					else
					{
						bGroupExist = false;
					}
				}
			});
			
			pageDirty.SetDirty(3);
		}

		function OnGroupSectionEnter(obj)
		{
			var sec_count = parseInt($('input[name="sec_count"]').val());
			var group_count = parseInt($('input[name="group_count"]').val());
			//alert($('input[name="RemainingSections"]').val() +" - "+sec_count+" - "+group_count);

			var sec_used = 0;
			$('input[name*="GroupSection"]').each(function(){
				if(!isNaN(parseInt($(this).val())))
				{
					sec_used += parseInt($(this).val());
				}
				else
				{
					sec_used += 0;
				}
			});
			
			$('input[name="RemainingSections"]').val(sec_count-sec_used);
			$('input[name="RemainingSections"]').keyup();
			pageDirty.SetDirty(3);
		}
		
		var bSectionExist = false;
		function OnSectionNameEnter(obj, sGroup)
		{
			if(obj.value.indexOf(sGroup+'~') !== 0) {
		        $(obj).val(sGroup+'~'+obj.value);
		    }
		    
			$('input[name*="SectionName"]').each(function(){
				if($(this).attr('name') != obj.name)
				{
					if($(this).val() == $(obj).val())
					{
						bSectionExist = true;
						return;
					}
					else
					{
						bSectionExist = false;
					}
				}
			});
			var nDiv = $(obj).parent().parent();
			var nH3 = nDiv.find("a");
			nH3.html(sGroup+"~Section - "+$(obj).val());
			
			pageDirty.SetDirty(2);
		}
		
		function OnSubjQuesEnter(list_id)
		{
			var sec_count = parseInt($('input[name="sec_count"]').val());
			var sub_count = AddSubject.SubCount[list_id];
			var section_ques  = parseInt($('input[name="SectionQuestions['+list_id+']"]').val());
			
			var ques_used = 0;
			var value;
			for(i = 0; i < sub_count; i++)
			{
				value = parseInt($("#subject_accordion_"+list_id+' input[name="SubjectQues['+list_id+']['+i+']"]').val());
				if(!isNaN(value))
				{
					ques_used += value;
				}
			}
			
			$('input[name="RemainingSecQuestions['+list_id+']"]').val(section_ques-ques_used);
			$('input[name="RemainingSecQuestions['+list_id+']"]').keyup();
			
			pageDirty.SetDirty(3);
		}
		
		function OnTopicQuesEnter(sub_list_id, list_id)
		{
			var sub_count 		= TopicAddPane.SubCount;
			var topic_count 	= AddTopic.TopicCount[list_id];
			var subject_ques  	= parseInt($('input[name="TopicQuestions['+(list_id)+']"]').attr('sub_ques'));
			
			var flash_ques = $('input[name="flash_ques"]:checked').val();
			
			var ques_used = 0;
			var value;
			var ques_lmt;
			for(i = 0; i < topic_count; i++)
			{
				easy_lmt = parseInt($("#topic_accordion_"+list_id+' input[name="TopicEasyQues['+list_id+']['+i+']"]').attr('ques_lmt'));
				mdrt_lmt = parseInt($("#topic_accordion_"+list_id+' input[name="TopicModerateQues['+list_id+']['+i+']"]').attr('ques_lmt'));
				hard_lmt = parseInt($("#topic_accordion_"+list_id+' input[name="TopicDifficultQues['+list_id+']['+i+']"]').attr('ques_lmt'));
				if(flash_ques == 1)
				{
					easy_lmt = Math.floor(easy_lmt/2);
					mdrt_lmt = Math.floor(mdrt_lmt/2);
					hard_lmt = Math.floor(hard_lmt/2);
				}
				
				value = parseInt($("#topic_accordion_"+list_id+' input[name="TopicEasyQues['+list_id+']['+i+']"]').val());
				if(!isNaN(value))
				{
					if(value > easy_lmt)
					{
						value = easy_lmt;
						$("#topic_accordion_"+list_id+' input[name="TopicEasyQues['+list_id+']['+i+']"]').val(value);
					}
					ques_used += value;
				}
				
				value = parseInt($("#topic_accordion_"+list_id+' input[name="TopicModerateQues['+list_id+']['+i+']"]').val());
				if(!isNaN(value))
				{
					if(value > mdrt_lmt)
					{
						value = mdrt_lmt;
						$("#topic_accordion_"+list_id+' input[name="TopicModerateQues['+list_id+']['+i+']"]').val(value);
					}
					ques_used += value;
				}
				
				value = parseInt($("#topic_accordion_"+list_id+' input[name="TopicDifficultQues['+list_id+']['+i+']"]').val());
				if(!isNaN(value))
				{
					if(value > hard_lmt)
					{
						value = hard_lmt;
						$("#topic_accordion_"+list_id+' input[name="TopicDifficultQues['+list_id+']['+i+']"]').val(value);
					}
					ques_used += value;
				}
			}
			
			$('input[name="TopicQuestions['+(list_id)+']"]').val(subject_ques-ques_used);
			$('input[name="TopicQuestions['+(list_id)+']"]').keyup();
		}
		
		var objTopic2SubListMap = Array();
		function AddTopic(sub_list_id, list_id, subject_count, sub_index)
		{
			topic_id = $("#topic_list_"+list_id).val();
			if(topic_id != null)
			{
				if(AddTopic.TopicCount == undefined)
				{
					AddTopic.TopicCount = new Array();
					//alert("list_id: "+list_id);
				}
				if(AddTopic.TopicCount[list_id] == undefined)
				{
					AddTopic.TopicCount[list_id] = 0;
					//alert(AddSubject.SubCount[list_id]);
				}

				var requiredHTML = $("#topic_accordion_"+list_id).html();
				var inputValueArray = new Array();
				$("#topic_accordion_"+list_id).find("input[type='text']").each(function(){
					inputValueArray[$(this).attr("name")] = $(this).val();
				});

								
				$("#topic_accordion_"+list_id).empty();
				$("#topic_accordion_"+list_id).accordion('destroy');
				$("#topic_accordion_"+list_id).append(requiredHTML);

				$("#topic_accordion_"+list_id).find(".content").each(function(){
					if(!$(this).parent().find("a").hasClass("active"))
					{
						$(this).slideUp();
					}
					else
					{
						$(this).parent().find("a").removeClass("active");
						$(this).slideUp();
					}
				});
				
				$("#topic_accordion_"+list_id).find("input[type='text']").each(function(){
					$(this).val(inputValueArray[$(this).attr("name")]);
				});
				
				for (index in topic_id)
				{
					topic_name = $("#topic_list_"+list_id+" option[value="+topic_id[index]+"]").text();
					easy_lmt = $("#topic_list_"+list_id+" option[value="+topic_id[index]+"]").attr("esy");
					mdrt_lmt = $("#topic_list_"+list_id+" option[value="+topic_id[index]+"]").attr("mod");
					hard_lmt = $("#topic_list_"+list_id+" option[value="+topic_id[index]+"]").attr("hrd");
					
					/*var sPanel = "<h3><a href='#'> "+topic_name+"<img topic_id='"+topic_id[index]+"' topic_name='"+topic_name+"' list_id='"+list_id+"' width='16' height='16' src='../../images/close.png' style='position:absolute;right:5px;' onClick='RemoveAccTopic(this);'/></a></h3>";
					sPanel += "<div>";
					sPanel += "<label>Easy Questions:</label>";
					sPanel += "<input onkeyup='OnTopicQuesEnter("+sub_list_id+","+list_id+")' sub_list_id='"+sub_list_id+"' sub_index='"+sub_index+"' size='30' type='text' ques_lmt='"+easy_lmt+"' topic_name='"+topic_name+"' topic_id='"+topic_id[index]+"' name='TopicEasyQues["+list_id+"]["+AddTopic.TopicCount[list_id]+"]' value='0'/><br/>";
					sPanel += "<label>Moderate Questions:</label>";
					sPanel += "<input onkeyup='OnTopicQuesEnter("+sub_list_id+","+list_id+")' sub_list_id='"+sub_list_id+"' sub_index='"+sub_index+"' size='30' type='text' ques_lmt='"+mdrt_lmt+"' topic_name='"+topic_name+"' topic_id='"+topic_id[index]+"' name='TopicModerateQues["+list_id+"]["+AddTopic.TopicCount[list_id]+"]' value='0'/><br/>";
					sPanel += "<label>Hard Questions:</label>";
					sPanel += "<input onkeyup='OnTopicQuesEnter("+sub_list_id+","+list_id+")' sub_list_id='"+sub_list_id+"' sub_index='"+sub_index+"' size='30' type='text' ques_lmt='"+hard_lmt+"' topic_name='"+topic_name+"' topic_id='"+topic_id[index]+"' name='TopicDifficultQues["+list_id+"]["+AddTopic.TopicCount[list_id]+"]' value='0'/><br/>";
					sPanel += "<input type='hidden' name=TopicId["+list_id+"]["+AddTopic.TopicCount[list_id]+"] value='"+topic_id[index]+"' />";
					sPanel += "<input type='hidden' name=TopicName["+list_id+"]["+AddTopic.TopicCount[list_id]+"] value='"+topic_name+"' />";
					sPanel += "</div>";*/

					var sPanel = "<div class='accordion-frame'>";
					sPanel += "<a class='heading bg-lightBlue fg-white' style='font-size: 12px;' href='#'> "+topic_name+"<img topic_id='"+topic_id[index]+"' topic_name='"+topic_name+"' list_id='"+list_id+"' width='16' height='16' src='../../images/close.png' style='position:absolute;right:5px;' onClick='RemoveAccTopic(this);'/></a>";
					sPanel += "<div class='content'>";
					sPanel += "<label>Easy Questions:</label>";
					sPanel += "<input class='form-control input-sm' style='background-color: #fff;width: 50%;' onkeyup='OnTopicQuesEnter("+sub_list_id+","+list_id+")' sub_list_id='"+sub_list_id+"' sub_index='"+sub_index+"' type='text' ques_lmt='"+easy_lmt+"' topic_name='"+topic_name+"' topic_id='"+topic_id[index]+"' name='TopicEasyQues["+list_id+"]["+AddTopic.TopicCount[list_id]+"]' value='0'/><br />";
					sPanel += "<label>Moderate Questions:</label>";
					sPanel += "<input class='form-control input-sm' style='background-color: #fff;width: 50%;' onkeyup='OnTopicQuesEnter("+sub_list_id+","+list_id+")' sub_list_id='"+sub_list_id+"' sub_index='"+sub_index+"' type='text' ques_lmt='"+mdrt_lmt+"' topic_name='"+topic_name+"' topic_id='"+topic_id[index]+"' name='TopicModerateQues["+list_id+"]["+AddTopic.TopicCount[list_id]+"]' value='0'/><br />";
					sPanel += "<label>Hard Questions:</label>";
					sPanel += "<input class='form-control input-sm' style='background-color: #fff;width: 50%;' onkeyup='OnTopicQuesEnter("+sub_list_id+","+list_id+")' sub_list_id='"+sub_list_id+"' sub_index='"+sub_index+"' type='text' ques_lmt='"+hard_lmt+"' topic_name='"+topic_name+"' topic_id='"+topic_id[index]+"' name='TopicDifficultQues["+list_id+"]["+AddTopic.TopicCount[list_id]+"]' value='0'/><br />";
					sPanel += "<input type='hidden' name=TopicId["+list_id+"]["+AddTopic.TopicCount[list_id]+"] value='"+topic_id[index]+"' />";
					sPanel += "<input type='hidden' name=TopicName["+list_id+"]["+AddTopic.TopicCount[list_id]+"] value='"+topic_name+"' />";
					sPanel += "</div>";
					sPanel += "</div>";

					for(id = 0; id < subject_count; id++)
					{
						var objElm = $("#topic_list_"+id+" option[value="+topic_id[index]+"]");
						
						if(!$.isArray( objTopic2SubListMap[ topic_id[index] ] ))
						{
							objTopic2SubListMap[ topic_id[index] ] = Array();
						}
						
						if(objElm.length > 0)
						{
							objElm.remove();
							tpc_index = objTopic2SubListMap[ topic_id[index] ].length;
							objTopic2SubListMap[ topic_id[index] ][id] = id;
						}
					}

					$("#topic_accordion_"+list_id).append(sPanel);
										
					/*$("#topic_accordion_"+list_id).append(sPanel).accordion('destroy').accordion({
					    active: 0,
					    collapsible: false,
					    changestart: function(event, ui) {
							ui.newContent.css('height', 'auto');
						}
					});
					$("#topic_accordion_"+list_id).children().eq(1).css('height', 'auto');*/
					
					AddTopic.TopicCount[list_id]++;
					//alert(AddSubject.SubCount[list_id]);
				}

				$("#topic_accordion_"+list_id).find(".heading").last().addClass("active");
				$("#topic_accordion_"+list_id).accordion();

				if(parseInt($("#topic_accordion_"+list_id).parent().parent().parent().siblings(".col-lg-2").css("height")) < parseInt($("#topic_accordion_"+list_id).parent().parent().parent().css("height")))
				{
					$("#topic_accordion_"+list_id).parent().parent().parent().siblings(".col-lg-2").css("height", $("#topic_accordion_"+list_id).parent().parent().parent().css("height"));
				}

				$('input[name*="TopicEasyQues"]').each(function(){
					$(this).rules("add", {
						required: true,
						messages: {required: $("#subject_accordion_"+$(this).attr('sub_list_id')+' input[name="SubjectQues['+$(this).attr('sub_list_id')+']['+$(this).attr('sub_index')+']"]').attr('sub_name')+" Topic- "+$(this).attr('topic_name')+" easy questions required!"}
					});
				});

				$('input[name*="TopicModerateQues"]').each(function(){
					$(this).rules("add", {
						required: true,
						messages: {required: $("#subject_accordion_"+$(this).attr('sub_list_id')+' input[name="SubjectQues['+$(this).attr('sub_list_id')+']['+$(this).attr('sub_index')+']"]').attr('sub_name')+" Topic- "+$(this).attr('topic_name')+" moderate questions required!"}
					});
				});

				$('input[name*="TopicDifficultQues"]').each(function(){
					$(this).rules("add", {
						required: true,
						messages: {required: $("#subject_accordion_"+$(this).attr('sub_list_id')+' input[name="SubjectQues['+$(this).attr('sub_list_id')+']['+$(this).attr('sub_index')+']"]').attr('sub_name')+" Topic- "+$(this).attr('topic_name')+" difficult questions required!"}
					});
				});
			}
			else
			{
				alert("Please first select topic/s from left side combo box!");
			}
		}
		
		function TopicAddPane()
		{
			var subject_count = 0;
			
			for(i = 0; i < AddSubject.SubCount.length; i++)
			{
				subject_count += AddSubject.SubCount[i];
			}
			
			if(TopicAddPane.SubCount != subject_count)
			{
				TopicAddPane.SubCount = 0;
				$('#topic_tab').empty();
				
				sPane  = "<p style='color:red'>* If you have selected <b>yes</b> for Flash Question (MCPA Security Setting), you can't opt for more than 50% of available questions.</p>";
				sPane += "<p style='color:red'>* If you have selected <b>no</b> for Flash Question (MCPA Security Setting), you can't opt for more than available questions.</p>";
				sPane += "<p style='color:red'>* If you will try to opt for more than permissible limit of questions, wizard will automatically round off the opt count to upper-cap.</p>"
				$('#topic_tab').append(sPane);
				
				var subSource = 0;
				if($('input[name="ques_source"]:checked').val() == "mipcat")
				{
					subSource = 1;
				}
				
				tag_id = $("#tag").val();
				/*if($("input[name='ques_source']:checked").val() == "personal")
				{
					tag_id = $("#tag").val();
				}*/
				
				for(list_id = 0; list_id < AddSubject.SubCount.length; list_id++)
				{
					var sub_count = AddSubject.SubCount[list_id];
					//alert(sub_count);
					for(index = 0; index < sub_count; index++)
					{
						sub_ques 	= parseInt($("#subject_accordion_"+list_id+' input[name="SubjectQues['+list_id+']['+index+']"]').val());
						sub_name 	= $("#subject_accordion_"+list_id+' input[name="SubjectQues['+list_id+']['+index+']"]').attr('sub_name');
						sub_id 		= $("#subject_accordion_"+list_id+' input[name="SubjectQues['+list_id+']['+index+']"]').attr('sub_id');
						
						if(sub_name != undefined)
						{
							sPane = "<fieldset style='border: 1px solid #AAA; padding: 5px;>";
							sPane += "<div class='row fluid'>";
							sPane += "<div class='col-sm-5 col-md-5 col-lg-5'>";
							sPane += "<div class='row fluid'>";
							sPane += "<div class='col-sm-8 col-md-8 col-lg-8'>";
							sPane += "<label>Questions Remaining</label>";
							sPane += "<input readonly='readonly' class='form-control input-sm' type='text' name='TopicQuestions["+TopicAddPane.SubCount+"]' value='"+sub_ques+"' sub_ques='"+sub_ques+"'/><br/>";
							sPane += "</div>";
							sPane += "</div>";
		
							sPane += "<div class='row fluid'>";
							sPane += "<div class='col-sm-12 col-md-12 col-lg-12'>";
							sPane += "<select class='form-control input-sm' style='height: 150px;' id='topic_list_"+TopicAddPane.SubCount+"' onDblClick='onDblClickTopic(this.value, \""+sub_name+"\", "+sub_id+");' multiple='multiple'>";
							sPane += "</select>";
							sPane += "</div>";
							sPane += "</div>";
							sPane += "</div>";
		
							sPane += "<div class='col-sm-2 col-md-2 col-lg-2' style='border-left:1px dotted #003399; border-right:1px dotted #003399'>";
							sPane += "<div class='col-sm-offset-3 col-md-offset-3 col-lg-offset-3'><br/><br/><br/><br/><button class='btn btn-default' onclick='AddTopic("+list_id+","+TopicAddPane.SubCount+","+subject_count+","+index+");'>Add &gt;&gt;</button></div><br/><br/><br/><br/>";
							sPane += "<p>Click on <img width='16' height='16' src='../../images/close.png'/> button &gt;&gt; on top of panel to remove topic from list.</p>";
							sPane += "</div>";
		
							sPane += "<div class='col-sm-5 col-md-5 col-lg-5'>";
							sPane += "<div class='row fluid'>";
							sPane += "<div style='color:#FFF;background-color:CornflowerBlue;text-align:center;border: 1px dotted #003399;'>";
							sPane += "<b>"+sub_name+" Topics</b>";
							sPane += "</div>";
							sPane += "</div>";
		
							sPane += "<div class='row fluid'>";
							sPane += "<div class='metro'>";
							sPane += "<div class='accordion with-marker' data-role='accordion' id='topic_accordion_"+TopicAddPane.SubCount+"'>";
							sPane += "</div>";
							sPane += "</div>";
							sPane += "</div>";
							sPane += "</div>";
		
							sPane += "</div>";
							sPane += "</fieldset><br />";
							
							$('#topic_tab').append(sPane);
	
							$("#topic_accordion_"+TopicAddPane.SubCount).accordion();
							
							/*$('body').on({
							    ajaxStart: function() { 
							    	$(this).addClass("loading"); 
							    },
							    ajaxStop: function() { 
							    	$(this).removeClass("loading"); 
							    }    
							});*/
							//alert('ajax/ajax_get_topics.php?sub_id='+sub_id+'&mipcat='+subSource+'&tag='+encodeURI(tag));
							var pref_lang = $("#pref_lang").val();
							var mcq_type  = $('input[name="ques_type"]:checked').val();

							$(".modal1").show();
							$('#topic_list_'+TopicAddPane.SubCount).load('../ajax/ajax_get_topics.php',{'sub_id':sub_id, 'mipcat':subSource, 'tag_id': tag_id, 'lang': pref_lang, 'mcq_type': mcq_type},function(data){
							 		$(".modal1").hide();
							});
							 
							var j = 1;
							$('input[name*="TopicQuestions"]').each(function(){
								$(this).rules("add", {
									min: 0, max: 0,
									messages: { min: "Subject-"+j+" : Please distribute questions properly to every Topic (remaining questions should be zero)!", max: "Subject-"+j+" : Please distribute questions properly to every Topic (remaining questions should be zero)!" }
								});
								j++;
							});
							
							TopicAddPane.SubCount++;
							//alert("TopicAddPane.SubCount: "+TopicAddPane.SubCount);
						}
					}
				}
			}
		}
		
		var objChosenSubs = Array();
		function AddSubject(list_id, section_count)
		{
			sub_id = $("#sub_list_"+list_id).val();
			if(sub_id != null && sub_id != -1)
			{
				if(AddSubject.SubCount == undefined)
				{
					AddSubject.SubCount = new Array();
					//alert("list_id: "+list_id);
				}
				if(AddSubject.SubCount[list_id] == undefined)
				{
					AddSubject.SubCount[list_id] = 0;
					//alert(AddSubject.SubCount[list_id]);
				}

				var requiredHTML = $("#subject_accordion_"+list_id).html();
				var inputValueArray = new Array();
				$("#subject_accordion_"+list_id).find("input[type='text']").each(function(){
					inputValueArray[$(this).attr("name")] = $(this).val();
				});

								
				$("#subject_accordion_"+list_id).empty();
				$("#subject_accordion_"+list_id).accordion('destroy');
				$("#subject_accordion_"+list_id).append(requiredHTML);

				$("#subject_accordion_"+list_id).find(".content").each(function(){
					if(!$(this).parent().find("a").hasClass("active"))
					{
						$(this).slideUp();
					}
					else
					{
						$(this).parent().find("a").removeClass("active");
						$(this).slideUp();
					}
				});
				
				$("#subject_accordion_"+list_id).find("input[type='text']").each(function(){
					$(this).val(inputValueArray[$(this).attr("name")]);
				});
				for (index in sub_id)
				{				
					if (objChosenSubs.hasOwnProperty(sub_id[index]))
					{
						objChosenSubs[ sub_id[index] ] += 1;
					}
					else
					{
						objChosenSubs[ sub_id[index] ] = 1;
					}
					
					sub_name = $("#sub_list_"+list_id+" option[value="+sub_id[index]+"]").text() + " # " + objChosenSubs[ sub_id[index] ];

					var sPanel = "<div class='accordion-frame'>";
					
					sPanel += "<a class='heading bg-lightBlue fg-white' style='font-size: 12px;' href='#'> "+sub_name+"<img sub_id='"+sub_id[index]+"' sub_name='"+sub_name+"' list_id='"+list_id+"' section_count='"+section_count+"' width='16' height='16' src='../../images/close.png' style='position:absolute;right:5px;' onClick='RemoveAccSubject(this);'/></a>";
					sPanel += "<div class='content'>";
					sPanel += "<label>Questions:</label>";
					sPanel += "<input class='form-control input-sm' onkeyup='OnSubjQuesEnter("+list_id+")' style='background-color: #fff;width: 50%;' type='text' sub_name='"+sub_name+"' sub_id='"+sub_id[index]+"' name='SubjectQues["+list_id+"]["+AddSubject.SubCount[list_id]+"]' value='0'/>";
					sPanel += "<input type='hidden' name=SubjectId["+list_id+"]["+AddSubject.SubCount[list_id]+"] value='"+sub_id[index]+"' />";
					sPanel += "<input type='hidden' name=SubjectName["+list_id+"]["+AddSubject.SubCount[list_id]+"] value='"+sub_name+"' />";
					sPanel += "</div>";
					sPanel += "</div>";
					
					//for(id = 0; id < section_count; id++)
					{
						$("#sub_list_"+list_id+" option[value="+sub_id[index]+"]").remove();
					}
					
					$("#subject_accordion_"+list_id).append(sPanel);
					//$("#subject_accordion_"+list_id).find(".custom-close").addClass("active").removeClass("custom-close");
					
					//$("#subject_accordion_"+list_id).children().eq(1).css('height', 'auto');
					
					AddSubject.SubCount[list_id]++;
					//alert(AddSubject.SubCount[list_id]);
				}
				$("#subject_accordion_"+list_id).find(".heading").last().addClass("active");
				$("#subject_accordion_"+list_id).accordion();
				if(parseInt($("#subject_accordion_"+list_id).parent().parent().parent().siblings(".col-lg-2").css("height")) < parseInt($("#subject_accordion_"+list_id).parent().parent().parent().css("height")))
				{
					$("#subject_accordion_"+list_id).parent().parent().parent().siblings(".col-lg-2").css("height", $("#subject_accordion_"+list_id).parent().parent().parent().css("height"));
				}
				
				$("input[name*='SubjectQues["+list_id+"]']").each(function(){
					$(this).rules("add", {
						required: true,
						min: 1,
						messages: {required: $('input[name="SectionName['+list_id+']"]').val()+" Subjects- "+$(this).attr('sub_name')+" required!", min: $('input[name="SectionName['+list_id+']"]').val()+" Subjects- "+$(this).attr('sub_name')+" should contain atleast one question!" }
					});
				});
			}
			else if (sub_id == -1)
			{
				alert("You don't have personal questions uploaded in our knowledge base!");
			}
			else
			{
				alert("Please first select subject/s from left side combo box!");
			}
		}

		function SubjectAddPane(section_count)
		{
			pg_1_dirty = pageDirty.CheckPage(1);
			pg_2_dirty = pageDirty.CheckPage(2);
			
			//alert("Sec Count Status: " + (SubjectAddPane.SecCount != section_count));
			//alert("Page 1 Dirty: " + pg_1_dirty);
			//alert("Page 2 Dirty: " + pg_2_dirty);
			
			tag_id = $("#tag").val();
			/*if($("input[name='ques_source']:checked").val() == "personal")
			{
				tag_id = $("#tag").val();
			}*/
			
			if(SubjectAddPane.SecCount != section_count || pg_1_dirty || pg_2_dirty)
			{
				var subSource = 0;
				if($('input[name="ques_source"]:checked').val() == "mipcat")
				{
					subSource = 1;
				}
				
				SubjectAddPane.SecCount = 0;
				$('#subject_tab').empty();
				
				for (var i = 0; i < section_count; i++)
				{
					sPane = "<fieldset style='border: 1px solid #AAA; padding: 5px;>";
					sPane += "<div class='row fluid'>";
					sPane += "<div class='col-sm-5 col-md-5 col-lg-5'>";
					sPane += "<div class='row fluid'>";
					sPane += "<div class='col-sm-8 col-md-8 col-lg-8'>";
					sPane += "<label>Questions Remaining</label>";
					sPane += "<input readonly='readonly' class='form-control input-sm' type='text' name='RemainingSecQuestions["+i+"]' value='"+$('input[name="SectionQuestions['+i+']"]').val()+"'/><br/>";
					sPane += "</div>";
					sPane += "</div>";

					sPane += "<div class='row fluid'>";
					sPane += "<div class='col-lg-11'>";
					sPane += "<select class='form-control input-sm' style='height: 150px;' id='sub_list_"+i+"' multiple='multiple'>";
					sPane += "</select>";
					sPane += "</div>";
					sPane += "</div>";
					sPane += "</div>";

					sPane += "<div class='col-sm-2 col-md-2 col-lg-2' style='border-left:1px dotted #003399; border-right:1px dotted #003399'>";
					sPane += "<div class='col-sm-offset-3 col-md-offset-3 col-lg-offset-3'><br/><br/><br/><br/><button class='btn btn-default' onclick='AddSubject("+i+","+section_count+");'>Add &gt;&gt;</button></div><br/><br/><br/><br/>";
					sPane += "<p>Click on <img width='16' height='16' src='../../images/close.png'/> button &gt;&gt; on top of panel to remove subject from list.</p>";
					sPane += "</div>";

					sPane += "<div class='col-sm-5 col-md-5 col-lg-5'>";
					sPane += "<div class='row fluid'>";
					sPane += "<div style='color:#FFF;background-color:CornflowerBlue;text-align:center;border: 1px dotted #003399;'>";
					sPane += "<b>Section - "+$('input[name="SectionName['+i+']"]').val()+" Subjects</b>";
					sPane += "</div>";
					sPane += "</div>";

					sPane += "<div class='row fluid'>";
					sPane += "<div class='metro'>";
					sPane += "<div class='accordion with-marker' data-role='accordion' id='subject_accordion_"+i+"'>";
					sPane += "</div>";
					sPane += "</div>";
					sPane += "</div>";
					sPane += "</div>";

					sPane += "</div>";
					sPane += "</fieldset><br />";
					
					$('#subject_tab').append(sPane);
					SubjectAddPane.SecCount++;

					$("#subject_accordion_"+i).accordion();
					
					var pref_lang = $("#pref_lang").val();
					var mcq_type  = $('input[name="ques_type"]:checked').val();
					
					//alert('ajax/ajax_get_subjects.php?mipcat='+subSource+'&tag='+tag);
					$(".modal1").show();
					$('#sub_list_'+i).load('../ajax/ajax_get_subjects.php',
						{'mipcat': subSource, 'tag_id': encodeURI(tag_id), 'lang': pref_lang, 'mcq_type': mcq_type}, 
						function(responseText, textStatus, XMLHttpReques){
							responseText = $.trim(responseText);
							
							//alert(responseText+" : "+ responseText.length);
							
							if(responseText.length == 0)
	                		{
	                			$(this).parent().append("<p style='color:red;font-weight:bold;'>You have not uploaded personal set of questions in our knowledge base. Please first upload questions or select <?php echo(CConfig::SNC_SITE_NAME);?> as question source.</p>");
	                			$(this).remove();
	                		}
							$(".modal1").hide();
                    });
				}
				
				var j = 1;
				$('input[name*="RemainingSecQuestions"]').each(function(){
					$(this).rules("add", {
						min: 0, max: 0,
						messages: { min: "Section-"+j+" : Please distribute questions properly to every subject (remaining questions should be zero)!", max: "Section-"+j+" : Please distribute questions properly to every subject (remaining questions should be zero)!" }
					});
					j++;
				});
				
				pg_1_dirty ? pageDirty.UnsetDirty(1) : '';
				pg_2_dirty ? pageDirty.UnsetDirty(2) : '';
			}
		}

		function GroupAddPanel(group_count)
		{
			var section_count = parseInt($('input[name="sec_count"]').val());

			if(GroupAddPanel.GroupCount != group_count || GroupAddPanel.SecCount != section_count)
			{
				GroupAddPanel.GroupCount = 0;
				GroupAddPanel.SecCount = section_count;
				$('#group_remaining_section').next().remove();

				var sGroupElement = "";
				for(var i = 0; i < group_count; i++)
				{
					sGroupElement += "<div class='row'><div class='col-sm-offset-1 col-sm-10' style='border: 1px solid #ddd; padding: 5px;'>";
					sGroupElement += "<div class='col-sm-offset-1 col-sm-2'><label>Group "+(i+1)+" :</label></div>";
					sGroupElement += "<div class='col-sm-4'><input placeholder='Group "+(i+1)+" Name' class='form-control input-sm' onkeyup='OnGroupNameEnter(this)' type='text' name='GroupName[]'/></div>";
					sGroupElement += "<div class='col-sm-4'><input placeholder='Sections in group "+(i+1)+"' class='form-control input-sm' onkeyup='OnGroupSectionEnter(this)' type='text' name='GroupSection[]'/></div>";
					//sGroupElement += "<div class='col-sm-3'><input placeholder='Time in minutes "+(i+1)+"' class='form-control input-sm' type='text' name='GroupTime[]'/></div>";
					sGroupElement += "</div></div>";
					
					GroupAddPanel.GroupCount++;
				}
				
				sGroupElement += "</div><br/><br/><br/><br/>";

				$('#group_remaining_section').append(sGroupElement);
			}
		}
		
		function SectionAddPanel(section_count)
		{
			var objGroupNames = [];
			var objGroupSections = [];
			var question_count = parseInt($('input[name="max_ques"]').val());

			var i = 0;
			$('input[name="GroupName[]"]').each(function(){
				objGroupNames[i] = $(this).val();
				i++;
			});
			var i = 0;
			$('input[name="GroupSection[]"]').each(function(){
				objGroupSections[i] = $(this).val();
				i++;
			});
			
			if(SectionAddPanel.SecCount != section_count || SectionAddPanel.QuesCount != question_count || pageDirty.CheckPage(3))
			{
				pageDirty.UnsetDirty(3);
				
				SectionAddPanel.SecCount = 0;
				SectionAddPanel.QuesCount = question_count;
				$('#accordion').empty();
				$('#accordion').accordion('destroy');
				
				var fields = new Array("SectionName", "SectionQuestions", "SectionMinCutoff", "SectionMaxCutoff", "SectionMarksForCorrectAnswer", "SectionPartialMarks", "SectionNegetiveMarking");

				var nGroupOffset = 0;
				var nGroupSecPos = objGroupSections[nGroupOffset];
				for (var i = 0; i < section_count; i++)
				{
					if(i == nGroupSecPos) {
						nGroupOffset++;
						nGroupSecPos = parseInt(nGroupSecPos) + parseInt(objGroupSections[nGroupOffset]);
					}
					//alert("("+nGroupSecPos+") "+"Section: "+i+" - "+objGroupNames[nGroupOffset]+" - "+objGroupSections[nGroupOffset]);
					
					var sPanel = "<div class='accordion-frame'>";
					
					if(i == 0)
						sPanel += "<a class='heading bg-lightBlue fg-white active' style='font-size: 15px;' href='#'>Section - "+objGroupNames[nGroupOffset]+"~"+(i+1)+"</a>";
					else
						sPanel += "<a class='heading bg-lightBlue fg-white' style='font-size: 15px;' href='#'>Section - "+objGroupNames[nGroupOffset]+"~"+(i+1)+"</a>";
					
					sPanel += "<div class='content'>";
					for (index in fields)
					{
						sPanel += "<label>"+fields[index]+":</label>";
						
						switch(index)
						{
							case '0':
								sPanel += "<input class='form-control input-mini' onkeyup='OnSectionNameEnter(this,\""+objGroupNames[nGroupOffset]+"\")' type='text' name='"+fields[index]+"["+SectionAddPanel.SecCount+"]' value='"+objGroupNames[nGroupOffset]+"~Sec_"+(i+1)+"'/><br/>";
								break;
							case '1':
								sPanel += "<input class='form-control input-mini' onkeyup='OnSectionQuesEnter()' type='text' name='"+fields[index]+"["+SectionAddPanel.SecCount+"]' value='0'/><br/>";
								break;
							case '2':
								sPanel += "<input class='form-control input-mini' type='text' name='"+fields[index]+"["+SectionAddPanel.SecCount+"]' value='0'/><br/>";
								break;
							case '3':
								sPanel += "<input class='form-control input-mini' type='text' name='"+fields[index]+"["+SectionAddPanel.SecCount+"]' value='100'/><br/>";
								break;
							case '4':
								sPanel += "<input class='form-control input-mini' type='text' name='"+fields[index]+"["+SectionAddPanel.SecCount+"]' value='"+$('input[name="r_marks"]').val()+"'/><br/>";
								break;
							case '5':
								sPanel += "<input class='form-control input-mini' type='text' name='"+fields[index]+"["+SectionAddPanel.SecCount+"]' value='"+$('input[name="p_marks"]').val()+"'/><br/>";
								break;
							case '6':
								sPanel += "<input class='form-control input-mini' type='text' name='"+fields[index]+"["+SectionAddPanel.SecCount+"]' value='"+$('input[name="w_marks"]').val()+"'/><br/>";
								break;
						}
					}
					sPanel += "</div>";
					sPanel += "</div>";
					
					$('#accordion').append(sPanel);
					//$("#accordion").children().eq(1).css('height', 'auto');
					SectionAddPanel.SecCount++;
				}
				
				$('#accordion').accordion();
				
				for(i = 0; i < fields.length; i++)
				{
					j = 1;
					if(i==0)
					{
						$('input[name*="' + fields[i] + '"]').each(function(){
							$(this).rules("add", {
								required: true,
								'GroupAlphanumeric': true,
								'SectionNameExists': true,
								messages: { required: "Section-"+j+" "+fields[i]+" required!" }
							});
							j++;
						});
					}
					else if(i==1)
					{
						$('input[name*="' + fields[i] + '"]').each(function(){
							$(this).rules("add", {
								'NegetiveNumber' : true,
								min: 1,
								messages: { min: "Section-"+j+" "+fields[i]+" should contain atleast one question!" }
							});
							j++;
						});
					}
					else if(i == 2)
					{
						$('input[name*="' + fields[i] + '"]').each(function(){
							$(this).rules("add", {
								required: true,
								'SecMinCutoffGreater': true,
								digits: true,
								messages: { required: "Section-"+j+" "+fields[i]+" required!", digits: "Section-"+j+" "+fields[i]+" should be numeric digits!" }
							});
							j++;
						});
					}
					else if(i == 3)
					{
						$('input[name*="' + fields[i] + '"]').each(function(){
							$(this).rules("add", {
								required: true,
								digits: true,
								messages: { required: "Section-"+j+" "+fields[i]+" required!", digits: "Section-"+j+" "+fields[i]+" should be numeric digits!" }
							});
							j++;
						});
					}
					else if(i==4 || i == 5)
					{
						$('input[name*="' + fields[i] + '"]').each(function(){
							$(this).rules("add", {
								required: true,
								'NegetiveNumber' : true,
								number: true,
								messages: { required: "Section-"+j+" "+fields[i]+" required!", number: "Section-"+j+" "+fields[i]+" should be numeric value!" }
							});
							j++;
						});
					}
				}
				
				$('input[name="ques_remaining"]').val( parseInt($('input[name="max_ques"]').val()) );
				
				pageDirty.CheckPage(1) ? pageDirty.UnsetDirty(1) : '';
			}

			OnMarkingSchemeChange();
			
			return pageDirty.CheckPage(1);
		}

		function OnMarkingSchemeChange()
		{
			var marking_scheme = $('input[name="marking_scheme"]:checked').val();

			if(marking_scheme == "section_wise")
			{
				$("#scheme").hide();
				$('input[name*="SectionMarksForCorrectAnswer"]').each(function(){
					$(this).removeAttr("readonly");
				});

				$('input[name*="SectionPartialMarks"]').each(function(){
					$(this).removeAttr("readonly");
				});
				
				$('input[name*="SectionNegetiveMarking"]').each(function(){
					$(this).removeAttr("readonly");
				});
			}
			else if(marking_scheme == "consistent")
			{
				$('input[name*="SectionMarksForCorrectAnswer"]').each(function(){
					$(this).attr("readonly", "readonly");
				});

				$('input[name*="SectionPartialMarks"]').each(function(){
					$(this).attr("readonly", "readonly");
				});
				
				$('input[name*="SectionNegetiveMarking"]').each(function(){
					$(this).attr("readonly", "readonly");
				});
				$("#scheme").show();
			}
		}

		function OnMarksChange(obj)
		{
			var objName = obj.name;

			if(objName == "r_marks")
			{
				$('input[name*="SectionMarksForCorrectAnswer"]').each(function(){
					$(this).val($('input[name="r_marks"]').val());
				});
			}
			else if(objName == "p_marks")
			{
				$('input[name*="SectionPartialMarks"]').each(function(){
					$(this).val($('input[name="p_marks"]').val());
				});
			}
			else if(objName == "w_marks")
			{
				$('input[name*="SectionNegetiveMarking"]').each(function(){
					$(this).val($('input[name="w_marks"]').val());
				});
			}
		}
		
		function RemoveAccTopic(obj)
		{
			topic_id		= $(obj).attr('topic_id');
			topic_name 		= $(obj).attr('topic_name');
			list_id 		= $(obj).attr('list_id');
			
			for ( index in objTopic2SubListMap[topic_id] )
			{
				$("#topic_list_"+objTopic2SubListMap[topic_id][index]).append("<option value='"+topic_id+"'>"+topic_name+"</option>");
			}
			//AddTopic.TopicCount[list_id]--;
			
			/*var parent = $(obj).closest('h3');
		    var head = parent.next('div');
		    parent.add(head).remove();*/

			$(obj).closest('a').parent().remove();

			if(parseInt($("#topic_accordion_"+list_id).parent().parent().parent().parent().children().first().css("height")) < parseInt($("#topic_accordion_"+list_id).parent().parent().parent().css("height")))
			{
				$("#topic_accordion_"+list_id).parent().parent().parent().siblings(".col-lg-2").css("height", $("#topic_accordion_"+list_id).parent().parent().parent().css("height"));
			}
		    
		    var subject_ques = parseInt($('input[name="TopicQuestions['+(list_id)+']"]').attr('sub_ques'));
		    
		    $('input[name="TopicQuestions['+(list_id)+']"]').val(subject_ques);
			//$('input[name="TopicQuestions['+(list_id)+']"]').keyup();
			$('input[name*="TopicEasyQues['+(list_id)+']"]').each(function(){
				$(this).keyup();
			});
		}
		
		function RemoveAccSubject(obj)
		{
			sub_id 			= $(obj).attr('sub_id');
			sub_name 		= $(obj).attr('sub_name');
			list_id 		= $(obj).attr('list_id');
			section_count   = $(obj).attr('section_count');
			
			sub_name = sub_name.substring(0, sub_name.lastIndexOf("#") - 1);
			
			if (objChosenSubs.hasOwnProperty(sub_id))
			{
				objChosenSubs[ sub_id ] -= 1;
			}
			//for(id = 0; id < section_count; id++)
			{
				$("#sub_list_"+list_id).append("<option value='"+sub_id+"'>"+sub_name+"</option>");
			}
			
			//AddSubject.SubCount[list_id]--;
			
			/*var parent = $(obj).closest('h3');
		    var head = parent.next('div');
		    parent.add(head).remove();*/
			$(obj).closest('a').parent().remove();
			if(parseInt($("#subject_accordion_"+list_id).parent().parent().parent().parent().children().first().css("height")) < parseInt($("#subject_accordion_"+list_id).parent().parent().parent().css("height")))
			{
				$("#subject_accordion_"+list_id).parent().parent().parent().siblings(".col-lg-2").css("height", $("#subject_accordion_"+list_id).parent().parent().parent().css("height"));
			}

			//$("#subject_accordion_"+list_id").
		    
		    var section_ques  = parseInt($('input[name="SectionQuestions['+list_id+']"]').val());
			
			$('input[name="RemainingSecQuestions['+list_id+']"]').val(section_ques);
			//$('input[name="RemainingSecQuestions['+list_id+']"]').keyup();
			$('input[name*="SubjectQues['+(list_id)+']"]').each(function(){
				$(this).keyup();
			});
		}
		
		function Preview()
		{
			$('#preview_tab').empty();
			var vsbltyAry = ["None", "Minimal", "Detailed"];
			
			var sPreview = "<h2>Test Name: " + $('input[name="test_name"]').val() + "</h2>";
			
			var criteria = $('input[name="criteria"]:checked').val();
			// Basic Test Details
			sPreview += "<h3>Basic Test Details:</h3>";
			sPreview += "<table class='js-responsive-table' width='100%' border='1' style='font: inherit;border-collapse:collapse;'>";
			sPreview += "<tr style='color:#800000'>";
			sPreview += "<th>Duration (mins)</th>";
			sPreview += "<th>Total Questions</th>";
			//sPreview += "<th>Criteria</th>";
			sPreview += "<th>Question Type</th>";
			if(criteria == "cutoff")
			{
				sPreview += "<th>Minimum Cutoff</th>";
				sPreview += "<th>Maximum Cutoff</th>";
			}
			else if (criteria == "top")
			{
				sPreview += "<th>Top Candidates</th>";
			}
			sPreview += "<th>Marking Scheme</th>";
			sPreview += "<th>Total Sections</th>";
			sPreview += "<th>Question Source</th>";
			sPreview += "<th>Result Visibility</th></tr>";
			sPreview += "<tr style='color:blue'><th>" + $('input[name="duration"]').val() + "</th>";
			sPreview += "<th>" + $('input[name="max_ques"]').val() + "</th>";
			//sPreview += "<th>" + criteria + "</th>";
			if($('input[name="ques_type"]:checked').val() == <?php echo(CConfig::QUES_CTG_SCA);?>)
			{
				sPreview += "<th>Single Correct Answer</th>";
			}
			else
			{
				sPreview += "<th>Multiple Correct Answer</th>";
			}
			if(criteria == "cutoff")
			{
				sPreview += "<th>" + $('input[name="cutoff_min"]').val() + "</th>";
				sPreview += "<th>" + $('input[name="cutoff_max"]').val() + "</th>";
			}
			else if(criteria == "top")
			{
				sPreview += "<th>" + $('input[name="top"]').val() + "</th>";
			}

			if($('input[name="marking_scheme"]:checked').val() == "consistent")
			{
				sPreview += "<th>Consistent</th>";
			}
			else
			{
				sPreview += "<th>Section Wise</th>";
			}			
			sPreview += "<th>" + $('input[name="sec_count"]').val() + "</th>";
			sPreview += "<th>" + (($('input[name="ques_source"]:checked').val() == "mipcat")?"<?php echo(CConfig::SNC_SITE_NAME);?>":$('input[name="ques_source"]:checked').val()) + "</th>";
			sPreview += "<th>" + vsbltyAry[$('input[name="visibility"]:checked').val()] + "</th></tr></table><br/>";

			//Custom Instructions
			sPreview += "<h3>Custom Instructions:</h3>";
			sPreview += "<table class='js-responsive-table' width='100%' border='1' style='font: inherit;border-collapse:collapse;'>";
			sPreview += "<tr>";
			sPreview += "<th>Language</th><th>Instructions</th>";
			sPreview += "</tr>";
			if(instr_lang_ary.length > 0)
			{
				for(lang_index in instr_lang_ary)
				{
					sPreview += "<tr>";
					sPreview += "<th style='color:green'>"+objUtils.ucfirst(instr_lang_ary[lang_index])+"</th><th>"+instr_ary[lang_index]+"</th>";
					sPreview += "</tr>";
				}
			}
			else
			{
				sPreview += "<tr>";
				sPreview += "<th>Not Selected</th><th>Not Available</th>";
				sPreview += "</tr>";
			}
			sPreview += "</table><br/>";
			
			// MCPA Security Setting
			sPreview += "<h3>MCPA Security Setting:</h3>";
			sPreview += "<table class='js-responsive-table' width='100%' border='1' style='font: inherit;border-collapse:collapse;'>";
			sPreview += "<tr>";
			sPreview += "<th>Flash Questions</th>";
			sPreview += "<th>Lock Questions</th>";
			sPreview += "<th>Test Expiration</th>";
			sPreview += "<th>Attempts Allowed</th><th>Shuffle Questions</th></tr>";
			sPreview += "<tr style='color:blue'>";
			sPreview += "<th>"+($('input[name="flash_ques"]:checked').val()==1?"Yes":"No")+"</th>";
			sPreview += "<th>"+($('input[name="lock_ques"]:checked').val()==1?"Yes":"No")+"</th>";
			var test_expr = $('#test_expiration').val();
			sPreview += "<th>"+(test_expr==-1?"Never":((test_expr*24)+" HRS"))+"</th>";
			var attempts = $('#attempts').val();
			var shuffle = $('#shuffle').is(':checked')?'Yes':'No';
			sPreview += "<th>"+(attempts==-1?"Unlimited":attempts)+"</th><th>" +shuffle  +" </th> </tr>";
			sPreview += "</table><br/>";
			
			// Section Details
			var i = 0;
			var objSectionName = new Array();
			var objSectionQuestions = new Array();
			var objSectionMinCutoff = new Array();
			var objSectionMaxCutoff = new Array();
			var objSectionRMarks = new Array();
			var objSectionPMarks = new Array();
			var objSectionWMarks = new Array();
			sPreview += "<h3>Section Details:</h3>";
			sPreview += "<table class='js-responsive-table' width='100%' border='1' style='font: inherit; border-collapse:collapse;'>";
			sPreview += "<tr>";
			sPreview += "<th style='color:green' width='25%'>Section Name</th>";
			sPreview += "<th style='color:green'>Questions Limit</th>";
			sPreview += "<th style='color:green'>Min Cutoff</th>";
			sPreview += "<th style='color:green'>Max Cutoff</th>";
			sPreview += "<th style='color:green'>Marks for Correct</th>";
			sPreview += "<th style='color:green'>Partial Marks</th>";
			sPreview += "<th style='color:green'>Marks for Incorrect</th>";
			sPreview += "</tr>";
			$('input[name*="SectionName"]').each(function(){
				//sPreview += "<th>" + $(this).val() + "</th>";
				objSectionName[i] = $(this).val();
				i++;
			});
			
			i = 0;
			$('input[name*="SectionQuestions"]').each(function(){
				//sPreview += "<th style='color:blue'>"+$(this).val()+"</th>";
				objSectionQuestions[i] = $(this).val();
				i++;
			});

			i = 0;
			$('input[name*="SectionMinCutoff"]').each(function(){
				//sPreview += "<th style='color:blue'>"+$(this).val()+"</th>";
				objSectionMinCutoff[i] = $(this).val();
				i++;
			});

			i = 0;
			$('input[name*="SectionMaxCutoff"]').each(function(){
				//sPreview += "<th style='color:blue'>"+$(this).val()+"</th>";
				objSectionMaxCutoff[i] = $(this).val();
				i++;
			});

			i = 0;
			$('input[name*="SectionMarksForCorrectAnswer"]').each(function(){
				//sPreview += "<th style='color:blue'>"+$(this).val()+"</th>";
				objSectionRMarks[i] = $(this).val();
				i++;
			});
			
			i = 0;
			$('input[name*="SectionPartialMarks"]').each(function(){
				//sPreview += "<th style='color:blue'>"+$(this).val()+"</th>";
				objSectionPMarks[i] = $(this).val();
				i++;
			});

			i = 0;
			$('input[name*="SectionNegetiveMarking"]').each(function(){
				//sPreview += "<th style='color:blue'>"+$(this).val()+"</th>";
				objSectionWMarks[i] = $(this).val();
				i++;
			});

			for(i=0; i < objSectionName.length; i++)
			{
				sPreview += "<tr>";
				sPreview += "<th>" + objSectionName[i] + "</th>";
				sPreview += "<th style='color:blue'>" + objSectionQuestions[i] + "</th>";
				sPreview += "<th style='color:blue'>" + objSectionMinCutoff[i] + "</th>";
				sPreview += "<th style='color:blue'>" + objSectionMaxCutoff[i] + "</th>";
				sPreview += "<th style='color:blue'>" + objSectionRMarks[i] + "</th>";
				sPreview += "<th style='color:blue'>" + objSectionPMarks[i] + "</th>";
				sPreview += "<th style='color:blue'>" + objSectionWMarks[i] + "</th>";
				sPreview += "</tr>";
			}
			sPreview += "</table><br/>";
			
			// Subject Details
			var i = 0, j = 0;
			var objSubjectName = new Array();
			var objSubjectQuestions = new Array();
			sPreview += "<h3>Subject Details:</h3>";
			sPreview += "<table class='js-responsive-table' width='100%' border='1' style='font: inherit;border-collapse:collapse;'>";
			sPreview += "<tr>";
			sPreview += "<th>Section</th><th>Questions Limit</th>";
			//$('input[name*="SubjectName[0]"]').each(function(){
				sPreview += "<th>Subject Details</th>";
			//});
			sPreview += "</tr>";
			for(list_id = 0; list_id < AddSubject.SubCount.length; list_id++)
			{
				var subNameAry = new Array();
				var subQuesAry = new Array();
				
				$('input[name*="SubjectName['+list_id+']"]').each(function(){
					//sPreview += "<th>" + $(this).val() + "</th>";
					subNameAry.push($(this).val());
					objSubjectName[i] = $(this).val();
					i++;
				});
				//sPreview += "</tr>";
				
				//sPreview += "<tr>";
				$('input[name*="SubjectQues['+list_id+']"]').each(function(){
					//sPreview += "<th>" + $(this).val() + "</th>";
					subQuesAry.push($(this).val());
					objSubjectQuestions[j] = $(this).val();
					j++;
				});
				
				sPreview += "<tr>";
				sPreview += "<th style='color:green' rowspan='"+((2*subNameAry.length) + 1)+"'>"+objSectionName[list_id]+"</th>";
				sPreview += "<th style='color:green' rowspan='"+((2*subNameAry.length) + 1)+"'>"+objSectionQuestions[list_id]+"</th>";
				sPreview += "</tr>";
				
				for (index in subNameAry)
				{
					sPreview += "<tr><th style='color:#800000'>" + subNameAry[index] + "</th></tr>";
					sPreview += "<tr><th>" + subQuesAry[index] + "</th></tr>";
				}
			}
			sPreview += "</table><br/>";
			
			// Topic Details
			sPreview += "<h3>Topic Details:</h3>";
			sPreview += "<table class='js-responsive-table' width='100%' border='1' style='font: inherit;border-collapse:collapse;'>";
			sPreview += "<tr><th>Subject</th><th>Questions Limit</th>";
			sPreview += "<th colspan='2'>Topic Details</th>";
			sPreview += "</tr>";
			
			for(list_id = 0; list_id < AddTopic.TopicCount.length; list_id++)
			{
				var tpcNameAry = new Array();
				var tpcEasyAry = new Array();
				var tpcModrAry = new Array();
				var tpcHardAry = new Array();
				
				$('input[name*="TopicName['+list_id+']"]').each(function(){
					tpcNameAry.push($(this).val());
				});
				
				$('input[name*="TopicEasyQues['+list_id+']"]').each(function(){
					tpcEasyAry.push($(this).val());
				});
				
				$('input[name*="TopicModerateQues['+list_id+']"]').each(function(){
					tpcModrAry.push($(this).val());
				});
				
				$('input[name*="TopicDifficultQues['+list_id+']"]').each(function(){
					tpcHardAry.push($(this).val());
				});
				
				sPreview += "<tr>";
				sPreview += "<th style='color:green' rowspan='"+((4*tpcNameAry.length) +1)+"'>"+objSubjectName[list_id]+"</th>";
				sPreview += "<th style='color:green' rowspan='"+((4*tpcNameAry.length) +1)+"'>"+objSubjectQuestions[list_id]+"</th>";
				sPreview += "</tr>";
				for (index in tpcNameAry)
				{
					sPreview += "<tr><th colspan='2' style='color:#800000'>"+ tpcNameAry[index] + "</th></tr>";
					sPreview += "<tr><th>Easy: </th>";
					sPreview += "<th style='color:blue'>" + tpcEasyAry[index] + "</th></tr>";
					sPreview += "<tr><th>Moderate: </th>";
					sPreview += "<th style='color:blue'>" + tpcModrAry[index] + "</th></tr>";
					sPreview += "<tr><th>Difficult: </th>";
					sPreview += "<th style='color:blue'>" + tpcHardAry[index] + "</th></tr>";
				}
			}
			sPreview += "</table><br/>";
			
			$('#preview_tab').append(sPreview);
			
			
			
		}
		
		$(document).ready(function () {
			if(save_success == 1)
    		{
    			 var not = $.Notify({
    				 caption: "Test Saved",
    				 content: "The test <?php echo("<b>".$sTestName."</b>"); ?> has been saved successfully!",
    				 style: {background: 'green', color: '#fff'}, 
    				 timeout: 5000
    				 });
    		}
		});
		
		var bTestExist = false;
		function OnTestNameChange(obj)
		{
			$("#t_exist").hide();
			$("#t_checking").show();
			
			$.getJSON("../ajax/ajax_check_test_name.php?test_name="+obj.value, function(data) {
				$("#t_checking").hide();
				
				if(data['present'] == 1)
				{
					$("#t_exist").show();
					bTestExist = true;
				}
				else
				{
					$("#t_exist").hide();
					bTestExist = false;
				}
			});
		}
		
		// Hide Ques Source for Contributors
		if(<?php echo ($user_type == CConfig::UT_CONTRIBUTOR?1:0); ?>)
		{
			$(".not_contrib").hide();
		}
		
		// select the overlay element - and "make it an overlay"
		/*$("#overlay_box").overlay({
			// custom top position
			top: 50,
			// some mask tweaks suitable for facebox-looking dialogs
			mask: {
				// you might also consider a "transparent" color for the mask
				color: '#fff',
				// load mask a little faster
				loadSpeed: 200,
				// very transparent
				opacity: 0.5
				},
			// disable this for modal dialog-type of overlays
			closeOnClick: false,
			// load it immediately after the construction
			load: false
		});*/

		function onDblClickTopic(topic_id, sub_name, sub_id)
		{
			//alert(topic_id + " - " + sub_name + " - " + sub_id);

			var ques_type = $("option[value="+topic_id+"]").attr("type");

			if(ques_type == <?php echo(CConfig::QT_READ_COMP);?> || ques_type == <?php echo(CConfig::QT_DIRECTIONS);?>)
			{
				var linked_to = $("option[value="+topic_id+"]").attr("linked_to");

				var opt_text_ary = $("option[value="+topic_id+"]").text().split("(Total:");
				$(".modal-title").html(opt_text_ary[0]);
				$(".modal1").show();
				$(".modal-body").load("ajax/ajax_rc_topic_details.php",{qtype : ques_type, para_id : linked_to}, function(){
					$("#para_detail_modal").modal("show");
					$(".modal1").hide();
				});
			}
			
				//alert("ajax/ajax_rc_topic_details.php?topic_id="+topic_id+"&sub_name="+sub_name);
		}

		var instr_lang_ary = new Array();
		var instr_ary = new Array();
		var cust_instr_count = 1;
		function OnAddCustInstructions()
		{
			var instr = $("#cust_instruction").val();
			
			if(instr)
			{
				var lang = $("#instr_lang").val();
				instr_lang_ary.push(lang);
				instr_ary.push(instr);
				var cust_instr_str = "<div class='alert alert-success' style='margin:20px;' id='cust_instr_" + cust_instr_count + "'> <button class='close pull-right' onclick=\"OnRemoveCustInstructions('cust_instr_"+cust_instr_count+"', '"+lang+"')\">("+objUtils.ucfirst(lang)+") &times;</button><br/><br/>";
				
				cust_instr_str += "<div class='alert-block' style='padding:10px;'>" + instr + "</div>";
				cust_instr_str += "<textarea style='display:none' name='"+lang+"_cust_instr'>"+ instr +"</textarea>";
				cust_instr_str += "</div>";
				
				//alert(cust_instr_str);
				$("#custom_instructions").append(cust_instr_str);
				
				cust_instr_count++;
				
				var objElm = $("#instr_lang option[value="+lang+"]");
				objElm.remove();
				
				if($('select#instr_lang option').length <= 0)
				{
					$("#id_add_cust_instr").attr("disabled", "disabled");
				}
				
				$('#cust_instruction').data("wysihtml5").editor.clear();
				
				$("body").scrollTop( $("#cust_instr_" + cust_instr_count).scrollTop() + 100);
			}
		}
		
		function OnRemoveCustInstructions(ci_id, lang)
		{
			$("#"+ci_id).remove();
			cust_instr_count--;

			instr_lang_ary.pop();
			instr_ary.pop();
			$("#instr_lang").append("<option value='"+lang+"'>"+objUtils.ucfirst(lang)+"</option>")

			$("#id_add_cust_instr").removeAttr("disabled"); 
		}
		
		function ShowShuffleOption(objselect)
		{
			
			if(objselect.value != "")
			{
			 $("#div_shuffle").attr("style", "display:inline");
			}
			else
			{
				if(!$("#shuffle").is(':checked'))
				{
					$('#shuffle').prop('checked',true)
				}
				
				$("#div_shuffle").attr("style", "display:none");
				//alert($("#shuffle").is(':checked'));
			}
			
		}
		
		</script>
</body>
</html>