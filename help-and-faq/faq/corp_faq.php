<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	include_once(dirname(__FILE__)."/../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../lib/include_js_css.php");
	include_once(dirname(__FILE__)."/../../lib/site_config.php");
	include_once(dirname(__FILE__)."/../../lib/utils.php");
	include_once(dirname(__FILE__)."/../../database/config.php");
	$page_id = CSiteConfig::HF_FAQ;
	
	$objIncludeJsCSS = new IncludeJSCSS();
	
	$login = CSessionManager::Get(CSessionManager::BOOL_LOGIN);
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Corporate : Frequently Asked Questions</title>
		 
		 <?php 
			$objIncludeJsCSS->CommonIncludeCSS("../../");
			$objIncludeJsCSS->CommonIncludeJS("../../");
		?>
	</head>
			
	<body>
		<!-- Header -->
		<?php
			include_once (dirname ( __FILE__ ) . "/../../lib/header.php");
		?>	
		<br />
		<br />
		<br />
		<div class="container text-justify">
			<h3 class="text-center">Organization : Frequently Asked Questions</h3><br/>
			<p>
				<b style="color:steelblue;">Qus 01: How is <?php echo(CConfig::SNC_SITE_NAME);?> beneficial for Corporate/Employer?</b><br/><br/>
				<b>Ans:</b> <?php echo(CConfig::SNC_SITE_NAME);?> provides unique analysis tools for analyzing performance of candidates. It provides complete DNA Analysis of each candidate, thus helps out sort listing of potential candidates just after the test (within seconds). This reduces overall cost, time and efforts of selection committee. <?php echo(CConfig::SNC_SITE_NAME);?> is web-based application that removes the geographical boundaries&rsquo;. Corporate can design and schedule test to any for registered potential candidates. Candidate at remote place can also attempt test. You can use this tool to test performance of your existing employees as well.  
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 02: How many types of result analysis <?php echo(CConfig::SNC_SITE_NAME);?> provides?</b><br/><br/>
				<b>Ans:</b> <?php echo(CConfig::SNC_SITE_NAME);?> provides summary level as well as detail level of analysis which is called <b>Test DNA Analysis</b>.</b> 
			</p><hr style="width:25%;"/>
<p>
				<b style="color:steelblue;">Qus 03: Does <?php echo(CConfig::SNC_SITE_NAME);?> provides way to upload my questions?</b><br/><br/>
				<b>Ans:</b> Yes, you can upload your own questions. Based on your choice, test can be conducted on those questions only. 
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 04: Can I add my own Subjects and Topics to the test during test design?</b><br/><br/>
				<b>Ans:</b> Yes, <?php echo(CConfig::SNC_SITE_NAME);?> is generic and adaptable in nature. You can add any Subject and Topic to the test you design with the help of <b>Test Design Wizard</b>.</b> 
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 05: Will my questions and tests be public and can any other use them?</b><br/><br/>
				<b>Ans:</b> No, your questions will be your own intellectual property. <?php echo(CConfig::SNC_SITE_NAME);?> assures this via our 'Terms of Service'.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 06: Is there any upper limit on the number of questions in one test?</b><br/><br/>
				<b>Ans:</b> Yes, you can't have more than 200 questions although there is no lower limit. It&rsquo;s your decision to set the number of question between (inclusive) 1 - 200.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 07: Is there any limit on number of sections included in one test?</b><br/><br/>
				<b>Ans:</b> No there is no limit on number of sections.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 08: Is there any limit on number of subjects in any section?</b><br/><br/>
				<b>Ans:</b> No , there is no limit on the number of subjects per section. However total number of questions should be properly distributed from each section to subjects per section. 
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 09: How many topics can I add under each subject?</b><br/><br/>
				<b>Ans:</b> You can add any number of topics. However question of each section should be properly divided into each subject.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 10: Can I add questions provided by <?php echo(CConfig::SNC_SITE_NAME);?>, during test design?</b><br/><br/>
				<b>Ans:</b> Yes, during the test design - you can add questions provided by <?php echo(CConfig::SNC_SITE_NAME);?>. 
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 11: Do you have any pre-designed tests, which are ready to schedule?</b><br/><br/>
				<b>Ans:</b> Yes, we provided you pre-designed tests which can directly be scheduled from <b>Schedule Test</b> left navigation bar. 
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 12: Can I reuse my designed test after I schedule it once?</b><br/><br/>
				<b>Ans:</b> Yes you can reuse your test as many times as you want. The test you design with <b>Test Design Wizard</b> is kind of <b>Test Template</b> which adapts questions automatically based on parameter you have specified during design. 
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 13: May I use my uploaded questions while scheduling pre-design test?</b><br/><br/>
				<b>Ans:</b> No, for pre-designed tests question source will always be <?php echo(CConfig::SNC_SITE_NAME);?>.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 14: What is MCPA Security Parameter?</b><br/><br/>
				<b>Ans:</b> MCPA stands for Mastishka Cheating Prevention Algorithm. This algorithm uses security parameters to prevent cheating during the conduction of online tests. These Parameters are, <br/> A). Flash Questions <br/>B). Lock Questions

			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 15: What will happen if I select <b>Yes</b> for Flash Questions (MCPA) security parameter?</b><br/><br/>
				<b>Ans:</b> When you select <b>Yes</b> for Flash Questions (MCPA) security parameter, then during test if candidate view question and leaves that question without answering, then question get replaced by another question of same subject, topic and difficulty level - going back to that question will have replaced question.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 16: Is there any prerequisite (condition) to use Flash Questions as (MCPA) security parameter.</b><br/><br/>
				<b>Ans:</b> Yes, you should have double the amount of questions available than number of questions you opt in any test.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 17: Can I edit the <b>Test Template</b>, after I completed the test design (for my saved tests)?</b><br/><br/>
				<b>Ans:</b> No, as test creation/design is 2 (two) minute process, it hardly matters. In this case you can delete your test and design new test again.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 18: Can I choose Cut-off as passing criteria for any test?</b><br/><br/>
				<b>Ans:</b> Yes, during <b>Test Design</b> you can choose minimum and maximum cut-off as passing criteria and assign cut-off percentage for the test.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 19: Is there any way that I can select <b>Top N</b> passing/selected candidates from a test?</b><br/><br/>
				<b>Ans:</b> Yes, you can set passing criteria as Top Candidates while designing the test. 
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 20:	I want to schedule the test but don't know how can I register candidates?</b><br/><br/>
				<b>Ans:</b> You can register candidates either by uploading an xls (Microsoft Excel) file as per <?php echo(CConfig::SNC_SITE_NAME);?>&rsquo;s prescribed format or by emailing a link embeded/available in your login so that to ask candidate to register by him/her-self.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 21: How do candidate knows that a test has been scheduled for them?</b><br/><br/>
				<b>Ans:</b> Candidates are informed by an email about the test scheduled for them.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 22: If I scheduled a test on a particular date, can candidates take that test before the scheduled date?</b><br/><br/>
				<b>Ans:</b> No, candidates can&rsquo;t attempt the test before scheduled date - although they can attempt the test on and after the scheduled date. However, you will have the information about the test attempted date and time and it is up to you whether to accept the test result or not.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 23: For how long scheduled test will be available in Student's account?</b><br/><br/>
				<b>Ans:</b> It depends upon test designer. The <b>Test Designer Wizard</b> has option to set expiration time. It can be from 6 hrs to 7 days. There is one more <b>Never Expires</b> option which means test will never expire and student can attempt test in any time.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 24: What will happen if the time (in minutes) set for test expires and candidate has not finished the test?</b><br/><br/>
				<b>Ans:</b> We have provided heart beat mechanism (with help of timer) for every <?php echo(CConfig::SNC_SITE_NAME);?> test. It keeps track of time taken for test, once it is zero - test automatically gets finished and submitted for result.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 25: What if any candidate is taking test and internet connection goes down, browser crashes, system crashes or power cut happen?</b><br/><br/>
				<b>Ans:</b> We have a very robust <b>Disaster Control Machanism</b> deviced to deal with this kind of situation. In any of these cases, test progress is preserved and candidates can resume the test from same point where they left the test. 
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 26: Can I have test at the places where there is no internet connectivity for each of the individual user or having bandwidth constraints?</b><br/><br/>
				<b>Ans:</b> We have offline (intranet based) module that can work after you install that in your local network (intranet) which works on web-browser based Client Server Model. In offline based module Internet Connectivity is only required before the whole test process gets started, to synchroniz with server database and then at the end of test when test is required to be submitted to the <?php echo(CConfig::SNC_SITE_NAME);?>.com server. The connectivity is required for very short period of time (before and after of test conduction process for less than a minute in case of 2 Mbps broadband connection).
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 27: Will I be offered any training about how to use <?php echo(CConfig::SNC_SITE_NAME);?> services?</b><br/><br/>
				<b>Ans:</b> Yes, <?php echo(CConfig::SNC_SITE_NAME);?> team of trainiers will help you getting trained for our services. We will offer you one day (free) training program about usage of web-application.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 28: Can I create department wise users, who can use <?php echo(CConfig::SNC_SITE_NAME);?> as and conduct test for their respective departments?</b><br/><br/>
				<b>Ans:</b> Yes, you can create unlimited users under your account, they are called Coordinators and you can set permissions for them so use features offered by <?php echo(CConfig::SNC_SITE_NAME);?>.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 29: If any student who is already registered with <?php echo(CConfig::SNC_SITE_NAME);?> (and not regisered with us), become part of our test process - can he/she again register with same email-id with us?</b><br/><br/>
				<b>Ans:</b> Yes, they can. We call it limited ownership of candidate, you can re-regiter them under your ownership and then his/her activity information related to your assignments will be available to you.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 30: <?php echo(CConfig::SNC_SITE_NAME);?> has test resumption feature for students. How many times a user can resume same test?</b><br/><br/>
				<b>Ans:</b> <?php echo(CConfig::SNC_SITE_NAME);?> has flexible model, it all depends upon test designer. This can vary from 0 (No resumption) to infinite (any number of resumption).
			</p><hr style="width:25%;"/>
		</div>
		<div class="col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
		<?php
		include_once (dirname ( __FILE__ ) . "/../../lib/footer.php");
		?>
		</div>
		<script type="text/javascript">
			$(".icon-home").addClass("glyphicon");
			$(".icon-home").addClass("glyphicon-home");
		
			$(".icon-user").addClass("glyphicon");
			$(".icon-user").addClass("glyphicon-user");
		</script>
	</body>
</html>