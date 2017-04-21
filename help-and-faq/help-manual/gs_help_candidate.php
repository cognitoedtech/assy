<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
include_once (dirname ( __FILE__ ) . "/../../lib/session_manager.php");
include_once (dirname ( __FILE__ ) . "/../../lib/include_js_css.php");
include_once (dirname ( __FILE__ ) . "/../../database/config.php");
include_once (dirname ( __FILE__ ) . "/../../lib/site_config.php");
include_once (dirname ( __FILE__ ) . "/../../lib/utils.php");
include_once (dirname ( __FILE__ ) . "/../../lib/include_js_css.php");

$page_id = CSiteConfig::HF_GS_HELP;
$login = CSessionManager::Get ( CSessionManager::BOOL_LOGIN );

$objIncludeJsCSS = new IncludeJSCSS ();

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Getting Started :Corporate</title>
				 
<?php
	$objIncludeJsCSS->CommonIncludeCSS ( "../../" );
	$objIncludeJsCSS->IncludeFuelUXCSS ( "../../" );
	$objIncludeJsCSS->IncludeIconFontCSS ( "../../" );
	$objIncludeJsCSS->IncludeBootStrapDocsCSS ( "../../" );
	$objIncludeJsCSS->IncludeBootStrapResponsiveCSS ( "../../" );
	$objIncludeJsCSS->CommonIncludeJS ( "../../" );
?>
		
	<style>
		.fixed {
		    position: fixed;
		}
		
		@media (max-width:1024px) {
		    .fixed {
		        position: relative;
		    }
		}		

		.add {
			text-align: left;
			color: #D00000;
			background-color: #FFFFFF;
			border: 2px solid #939393;
			border-radius: 25px;
			padding: 10px 20px;
		}
		
		.nav>.active>a,.nav>.active>a:hover {
			color: #ffffff;
			text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.2);
			background-color: #0088cc;
		}
		
		.modal1 {
			display: none;
			position: fixed;
			z-index: 1000;
			top: 50%;
			left: 60%;
			height: 100%;
			width: 100%;
		}
	</style>
</head>
<body>
	<!-- Header -->
	<!--  <header class="subhead" style="margin-bottom: 0px;">-->
<?php
	include_once (dirname ( __FILE__ ) . "/../../lib/header.php");
?>
		
	<div class="fuelux modal1">
		<div class="preloader">
			<i></i><i></i><i></i><i></i>
		</div>
	</div>
	<!--</header>  -->
	<br />
	<br />
	<div class="container text-justify">
		<div class="row-fluid">
			<div class="col-md-3 bs-docs-sidebar">
				<ul id="sidebar" class="nav nav-list-new bs-docs-sidenav fixed">
					<li><a href="#account_creation"><i style="float: right"
							class="icon-arrow-right-5"></i>Account Creation</a></li>
					<li><a href="#account_activation"><i style="float: right"
							class="icon-arrow-right-5"></i>Account Activation</a></li>
					<li><a href="#dashboard"><i style="float: right"
							class="icon-arrow-right-5"></i> Dashboard</a></li>
					<li><a href="#myaccount"><i style="float: right"
							class="icon-arrow-right-5"></i> My Account</a></li>
					<li><a href="#ra"><i style="float: right" class="icon-arrow-right-5"></i>Result
							Analytics</a></li>
					<li><a href="#at"><i style="float: right" class="icon-arrow-right-5"></i>Attempting
							Test</a></li>
					<li><a href="#dm"><i style="float: right" class="icon-arrow-right-5"></i>Fail Safe</a></li>
				</ul>
			</div>
			<div class="col-md-9">

				<!--	=======================INTRODUCTION===========================	-->

				<section id="account_creation">
				<div class="page-header">
					<h1>Getting Started as Candidate : Account Creation</h1>
				</div>
				<p>Candidates can register themselves under any organization.
					Organization will send them a link to registration page and by
					filling the information they can register themselves.</p>
				<div class="well text-center">
					<a href="images/candidate/user_reg_link.jpg" target="_blank"><img
						src="images/candidate/user_reg_link.jpg" height="600" width="700" /></a>
				</div>

				</section>

				<section id="account_activation">
				<div class="page-header">
					<h1>Account Activation</h1>
				</div>
				<p>Once form filled an email is sent out to candidate for
					verification. Candidate has to click on link to activate his
					account. Unless account is not activated, he/she is not able to log
					in.</p>
				<div class="well text-center">
					<a href="images/candidate/account_activation_email.jpg"
						target="_blank"><img
						src="images/candidate/account_activation_email.jpg" height="600"
						width="700" /></a>
				</div>

				</section>


				<!--	======================= DASHBOARD===========================	-->

				<section id="dashboard">
				<div class="page-header">
					<h1>Dashboard</h1>
				</div>
				<p>Dash board is the first page after login. On dashboard candidate
					can see all his recent test activities. He/she can see all
					scheduled/unfinished tests and can attempt test by clicking on the
					link. He/she can see the details as shown in figure.</p>
				<div class="well text-center">
					<a href="images/candidate/candidate_dashboard.jpg" target="_blank"><img
						src="images/candidate/candidate_dashboard.jpg" height="600"
						width="700" /></a>
				</div>
				</section>


				<!--	=======================MY ACCOUNT===========================	-->

				<section id="myaccount">
				<div class="page-header">
					<h1>My Account</h1>
				</div>
				<p>This section is related to your personal data. From this section
					you can edit your personal details, change your account password,
					edit his personal details like First Name, Last Name, Gender, Birth
					date, Contact Number, Address, City, State and Country.</p>

				<!--	====Personal Details====	-->

				<h3 style="color: black">Personal Details</h3>
				<p>
					Your personal details are displayed here. Clicking on Edit button
					at the bottom will take you to another form where you can edit your
					details. Once you finish, save your details and it will get
					updated. You can reach here using - <strong>My Account <i
						class="icon-arrow-right"></i> Personal Details.
					</strong>
				</p>
				<h5 style="color: black">Viewing personal Details:</h5>
				<div class="well text-center">
					<a href="images/candidate/candidate_personal_details.jpg"
						target="_blank"><img
						src="images/candidate/candidate_personal_details.jpg" height="600"
						width="700" /></a>
				</div>

				<h5 style="color: black">Editing personal Details:</h5>
				<div class="well text-center">
					<a href="images/candidate/candidate_personal_details_edit.jpg"
						target="_blank"><img
						src="images/candidate/candidate_personal_details_edit.jpg"
						height="600" width="700" /></a>
				</div>

				<!--	====Account Security (Password)====	-->

				<h3 style="color: black">Account Security (Password)</h3>
				<p>
					You can change your security question and password using this
					option.You can reach here using - <br /> <strong>My Account <i
						class="icon-arrow-right"></i> Account Security(Password).
					</strong>
				</p>
				<h5 style="color: black">Viewing Account Security:</h5>
				<div class="well text-center">
					<a href="images/candidate/candidate_view_acc_security.jpg"
						target="_blank"><img
						src="images/candidate/candidate_view_acc_security.jpg"
						height="600" width="700" /></a>
				</div>

				<h5 style="color: black">Editing Account Security:</h5>
				<div class="well text-center">
					<a href="images/candidate/candidate_edit_acc_security.jpg"
						target="_blank"><img
						src="images/candidate/candidate_edit_acc_security.jpg"
						height="600" width="700" /></a>
				</div>
				</section>



				<!--	=======================RESULT ANALYTICS===========================	-->

				<section id="ra">
				<div class="page-header">
					<h1>Result Analytics</h1>
				</div>
				<p>In this section, you can see results of all the tests you. It
					supports three types of analysis.</p>

				<!--	====Brief Result====	-->

				<h3 style="color: black">Brief Result</h3>
				<p>
					This page provides listing of the tests you have conducted with
					necessary information. This page is displayed using Result
					Analytics <i class="icon-arrow-right"></i> Brief Result and it
					displays following detail,
				</p>
				<ol>
					<li style="font-size: 16px"><strong>Test Name :</strong> Name of
						the test candidate attempted. <br /></li>
					
					<li style="font-size: 16px"><strong>Scheduled On :</strong> Date on
						which, test has been scheduled. <br /></li>
					
					<li style="font-size: 16px"><strong>Completed On :</strong> Date on
						which, candidate has finished the test. <br /></li>
					
					<li style="font-size: 16px"><strong>Scheduled By :</strong> Admin
						who scheduled the test. <br /></li>
					
					<li style="font-size: 16px"><strong>Marks Obtained :</strong> Marks
						obtained by candidate out of full marks are displayed in this
						section. <br /></li>
					
					<li style="font-size: 16px"><strong>Result/Rank :</strong> Result,
						whether candidate is pass or fail, or rank if top candidates has
						been chosen as selection criteria. <br /></li>
					
					<li style="font-size: 16px"><strong>Time Taken :</strong> Time
						taken by candidate to finish test. <br /></li>
					
					<li style="font-size: 16px"><strong>Activity Log :</strong> If the
						test gets interrupted, that particular reason is shown in this
						block like- power failure, browser crash, network or connectivity
						issue etc. <br /></li>
					
				</ol>
				<div class="well text-center">
					<a href="images/candidate/candidate_brief_result.jpg"
						target="_blank"><img
						src="images/candidate/candidate_brief_result.jpg" height="600"
						width="700" /></a>
				</div>
				<br />
				<div class="well text-center">
					<a href="images/candidate/candidate_section_performance.jpg"
						target="_blank"><img
						src="images/candidate/candidate_section_performance.jpg"
						height="600" width="700" /></a>
				</div>
				<br />
				<div class="well text-center">
					<a href="images/candidate/candidate_activity_log.jpg"
						target="_blank"><img
						src="images/candidate/candidate_activity_log.jpg" height="600"
						width="700" /></a>
				</div>
				<br />


				<!--	====Test DNA Analysis====	-->

				<h3 style="color: black">Test DNA Analysis</h3>
				<p>This is among the key features of <?php echo(CConfig::SNC_SITE_NAME);?>. All minute level details of candidate's performance in test can be seen here. This helps a lot to evaluate the candidate and improve his performance.<br />
					In order to see the DNA Analysis, you have to select the Test, then
					Date. As you choose the test, it will show you the date selection
					combo box, and as you select the date, choose the date and complete
					Test DNA analysis will be in front of you.<br /> The DNA analysis
					shows candidates overall performance, performance in each section,
					in every subject of section and in every topic of subject. The
					Analysis is shown in Pie Chart and Bar Charts to easily understand
					the result analysis.
				</p>
				<br />

				<div class="well text-center">
					<a href="images/candidate/candidate_test_dna_1.jpg" target="_blank"><img
						src="images/candidate/candidate_test_dna_1.jpg" height="400"
						width="500" /></a>
				</div>
				<br />

				<h5 style="color: black">Choose Date</h5>
				<div class="well text-center">
					<a href="images/candidate/candidate_test_dna_2.jpg" target="_blank"><img
						src="images/candidate/candidate_test_dna_2.jpg" height="400"
						width="500" /></a>
				</div>
				<br />

				<h5 style="color: black">DNA Analysis</h5>
				<div class="well text-center">
					<a href="images/candidate/candidate_test_dna_3.jpg" target="_blank"><img
						src="images/candidate/candidate_test_dna_3.jpg" height="1024"
						width="500" /></a>
				</div>
				<br />
				<!--	====Result Inspection====	-->

				<h3 style="color: black">Result Inspection</h3>
				<p>This feature allows you to inspect the candidate's question
					paper, it shows all the questions appeared in the test. Candidate's
					attempted which questions and what answer they have chosen, It also
					shows correct answer. If candidate has given wrong answer, question
					is shown in red, if he has given right answer, question is shown in
					green. If he has left the question, question is shown in blue.</p>
				<br />
				<div class="well text-center">
					<a href="images/result9.jpg" target="_blank"><img
						src="images/candidate/candidate_result_inspection.jpg" height="600" width="700" /></a>
				</div>
				<br />

				</section>
				<section id="at">
				<div class="page-header">
					<h1>Attempting Test</h1>
				</div>
				<p>Once candidate clicks on test link, a test window is opened, with
					test instructions. User has to read instructions carefully. On the
					instruction page, user can see a button on the bottom on the page.</p>
				<div class="well text-center">
					<a href="images/candidate/candidate_instruction_page.jpg"
						target="_blank"><img
						src="images/candidate/candidate_instruction_page.jpg" height="600"
						width="700" /></a>
				</div>
				<br />
				<p>The test window is divided in two parts. On the left hand, window
					has Sections, which contains questions. On the right side
					individual question is displayed. On the top right, reverse timer
					is present which shows how much time left.There are three buttons
				
				
				<ul>
					<li>Reset: This button is used to clear any answer selected by
						mistake.</li>
					<li>Flab/Mark: This button is used to flag the question. Once
						flagged, a flag mark is appeared in the question on left pan.
						Candidate can attempt that question later. Note that this option
						is available only when test admin want to provide it in specific
						test.</li>
					<li>Next/Submit: If candidate does not select any answer, he/she
						can skip the question by pressing Next button. Once candidate
						selected any answer the button gets converted to Submit and
						candidate has to press Submit button to submit the answer.</li>
				</ul>
				</p>
				<div class="well text-center">
					<a href="images/candidate/candidate_test screen.jpg"
						target="_blank"><img
						src="images/candidate/candidate_test screen.jpg" height="600"
						width="700" /></a>
				</div>
				<br />

				<h3 style="color: black">End Exam</h3>
				<p>Candidate can end exam at any point by pressing End Exam button.
					After finishing test a brief graphical overview of the test is
					shown</p>
				<div class="well text-center">
					<a href="images/candidate/end_exam_summary.jpg" target="_blank"><img
						src="images/candidate/end_exam_summary.jpg" height="600"
						width="700" /></a>
				</div>
				<br />
				</section>

				<section id="dm">
				<div class="page-header">
					<h1>Fail Safe</h1>
				</div>
				<p>In case of Browser or System Crash, Power failure, Internet
					connection down, candidate can re login and resume the test from
					the point where he left. However the number of attempts is set by
					the test admin only. For example, if test admin has set 5 numbers
					of attempts then user has to attempt test within 5 attempts.</p>
				<p>After a crash, EZeeAssess system asks for reason of crash. It
					also shows how much attempts left for a test on the same screen.
					After pressing continue, Instructions page is shown and candidate
					can resume the test by pressing â€œResume Testâ€� button.</p>
				<div class="well text-center">
					<a href="images/candidate/candidate_resume1.jpg" target="_blank"><img
						src="images/candidate/candidate_resume1.jpg" height="600"
						width="700" /></a>
				</div>
				<br />
				<div class="well text-center">
					<a href="images/candidate/candidate_resume2.jpg" target="_blank"><img
						src="images/candidate/candidate_resume2.jpg" height="600"
						width="700" /></a>
				</div>
				<br />
				<div class="well text-center">
					<a href="images/candidate/candidate_resume3.jpg" target="_blank"><img
						src="images/candidate/candidate_resume3.jpg" height="600"
						width="700" /></a>
				</div>
				<br />

				</section>

			</div>
		</div>
	</div>

	<script type="text/javascript">
			$(".modal1").show();
			window.onload = function () {
				$(".modal1").hide();
	
				$('body').scrollspy({
				    target: '.bs-docs-sidebar',
				    offset: 40
				});
			}
	</script>
</body>
</html>