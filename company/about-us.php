<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
include_once (dirname ( __FILE__ ) . "/../lib/session_manager.php");
include_once (dirname ( __FILE__ ) . "/../lib/include_js_css.php");
include_once (dirname ( __FILE__ ) . "/../lib/site_config.php");
include_once (dirname ( __FILE__ ) . "/../lib/utils.php");
include_once (dirname ( __FILE__ ) . "/../database/config.php");
$page_id = CSiteConfig::HF_FAQ;
$login = CSessionManager::Get ( CSessionManager::BOOL_LOGIN );

$objIncludeJsCSS = new IncludeJSCSS ();
?>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>About <?php echo(CConfig::SNC_SITE_NAME);?></title>	
<?php
$objIncludeJsCSS->IncludeMipcatCSS ( "../" );
$objIncludeJsCSS->IncludeIconFontCSS ( "../" );
$objIncludeJsCSS->CommonIncludeCSS ( "../" );
$objIncludeJsCSS->CommonIncludeJS ( "../" );

$objIncludeJsCSS->CommonIncludeNivoSliderCSS ( "../" );
$objIncludeJsCSS->IncludeNivoSliderJS ( "../" );
?>	
</head>
<body>
	<!-- Header -->
		<?php
		include (dirname ( __FILE__ ) . "/../lib/header.php");
		$bShowCKEditor = FALSE;
		?>
		<br />
	<br />
	<div class="container">
		<div class="text-justify">
			<h3 class="text-center">About <?php echo(CConfig::SNC_SITE_NAME);?></h3>
			<br />
			<div
				style="color: #000000; text-align: justify; text-justify: distribute; padding-right: 20px; position: relative; left: 20px">
				<b>EZeeAssess.com</b> is &quot;one-stop-solution&quot; for all 
				Institutes/Corporates &amp; Individuals, where they can design and 
				generate a quality question paper for candidate's skill testing/initial 
				recruitment processes or self analysis respectively. Through 
				<?php echo(CConfig::SNC_SITE_NAME);?> both on-line and offline tests 
				can be conducted and reports can be generated as soon as the test is over. 
				There can be a number of sections to test a candidate on various parameters, 
				it can be utilized for any entrance exam which opt for <b>objective
					type test</b>. Therefore, the corporates/institutes can design a
				quality question paper of their own choice to test their prospective
				employees/students to the fullest. The reports of each candidate
				will be available all time in your login and can be sent to you at
				registered email-id with full performance statistics in each section
				so that you can evaluate the candidates better.<br /> <br /> Our
				strength lies in building secure, robust and easy to use software.
				Our team is receiving lot of appreciation for product and already
				have many institutional users. <?php echo(CConfig::SNC_SITE_NAME);?> team 
				envisage to bring plausible and veritable assessment to various aspects 
				of education, training and employment. <?php echo(CConfig::SNC_SITE_NAME);?> 
				aims to help organization from choosing the best individual. <br />
				<br />
				<h4>Why EZeeAssess:</h4>
				<ul>
					<li>EZeeAssess helps organizations to create and administer all
						kinds of tests in a fast and reliable way.</li>
					<li>EZeeAssess is not just confined to institutes or enterprise. It
						proves to be a Generalized Solution to all firms who need to
						evaluate its crew.</li>
					<li>This new platform lets you create tests using an interface that
						is intuitive before anything else, and that is hosted 100 %
						online. You can have them edited from wherever you are. Anywhere
						there's a computer that can access the Internet will do.</li>
				</ul>

				<h4>Where EZeeAssess can be deployed:</h4>
				<ul>
					<li>Business and Training: Test employees to assess their skills
						and training requirements.</li>
					<li>Recruitment &amp; Pre-Employment Testing: Test employment
						candidates prior to interview with results e-mailed to you
						instantly.</li>
					<li>Exams for Education Institutes: Conduct online exams in the
						classroom or at home. Set practice tests and receive instant
						results.</li>
					<li>Distance learning and online courses: Roll out your tests
						locally or internationally in a secure web-based test environment.</li>
					<li>Entrance Examination: Now government departments have started
						promoting CBT to bring transparency in selection process.</li>
					<li>Self-study: Add practice quizzes and test yourself and your
						study group.</li>
				</ul>

				<h4>Features that will help our clients in day to day affairs:</h4>
				<ul>
					<li>Remarkable User Interface</li>
					<li>Instant Generation, Evaluation &amp; Inspection of Result</li>
					<li>Result Consolidation and Merit List Generation for tests
						conducted on different dates</li>
					<li>Option to upload and manage Personal set of Questions</li>
					<li>Candidate Registration via Bulk Upload (Excel)</li>
					<li>Candidate management via batches</li>
					<li>Cheating Prevention Algorithm</li>
					<li>Choice of Test Translation (99 support languages)</li>
					<li>Control &amp; Monitor Test Activity from any-where</li>
					<li>Create Coordinators for your different Branches/Departments</li>
					<li>Cost Effective</li>
				</ul>

				<h3>Management Team</h3>
				<br />
				<p>
					<span style="font-weight: bold">Mr. R. Kanungo</span><br /> <i><b>[Director
							of Enginnering &frasl; Operations &amp; Co-Founder]</b></i>
				</p>
				<p>
					<img style="padding: 20px;" src="../images/about/executive-ph.jpg"
						width="225" height="225" border="0" align="left" alt=""> Mr.
					Kanungo is an engineering graduate and manages the execution of
					product development &amp; all technical initiatives. He is
					responsible of prioritizing client's requirements and manages
					effectively what to be delivered. He has extensive experience
					managing development of software products. He is an excellent team
					leader and keeps all his team motivated and moving to achieve goal.
					<br /> <br /> He has more than a decade experience in the field of
					software product development and was associated with UK
					headquartered company Exis Technologies as operational manager and technology lead. He had
					worked on very challenging projects including standardization and
					automation of business processes, with clients in US and Europe. He
					has managed and developed many other projects with varied number of
					technology requirements.

				</p>
				<br /><br />
				<p>
					<span style="font-weight: bold">Mr. M. Jha</span><br /> <i><b>[Advisory
							Board Member]</b></i>
				</p>
				<p>
					<img style="padding: 20px;" src="../images/about/executive-ph.jpg"
						width="225" height="225" border="0" align="left" alt=""> Mr. Jha
					is an Engineering graduate and later did his Post
					Graduate Diploma in Management from XLRI, Jamshedpur which is one
					of the top ranked B-school in Asia. With graduation in Information
					Technology and specialization in Management, Meetesh brings in with
					him a blend of techno-managerial skills. <br />
					<br /> He has a diverse experience starting from an IT company
					(Impetus Infotech (I) Pvt. Ltd.), an upcoming Management Institute
					(Core Business School) and variety of consulting assignments
					spanning from Corporate Training to helping businesses grow. In
					addition to this, due to the inclination towards developmental
					sector, he also volunteers for Goonj (a nationwide NGO that works
					on the basic issue of clothing). <br />
					<br /> Meetesh has taken many social initiative and love to help
					community in various ways.He is also taking care of marketing
					initiatives for his family business of pharmacy products.

				</p>
				<br /> <br /> <br /> <br /> <br />


			</div>
		</div>
		<div class="col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
		<?php
		include ("../lib/footer.php");
		?>
		</div>
	</div>
</body>
</html>