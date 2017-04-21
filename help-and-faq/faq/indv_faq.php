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
		<title>Individual : Frequently Asked Questions</title>
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
			<h3 class="text-center">Individual : Frequently Asked Questions</h3><br/>
			<p>
				<b style="color:steelblue;">Qus 01: How can I register myself with <?php echo(CConfig::SNC_SITE_NAME);?>?</b><br/><br/>
				<b>Ans:</b> You can register using our various Subscription Plans for Individuals.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 02: How many tests shall I be able to attempt?</b><br/><br/>
				<b>Ans:</b> It is unlimited in number, you can take tests as soon as they will be available in your login. You can attempt test scheduled for you or any avaible free test.
			</p><hr style="width:25%;"/>
<p>
				<b style="color:steelblue;">Qus 03: Can I see detailed result analysis?</b><br/><br/>
				<b>Ans:</b> It depends on type of test. If test designer had selected option for <b>Detailed Visibility</b> then you can see detailed result analysis for that test.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 04: Can I inspect how I have performed in any test and see details question wise.</b><br/><br/>
				<b>Ans:</b> It depends on type of test. If test designer had selected option for <b>Detailed Visibility</b> then you can inspect your question wise performance for that test.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 05: Can I design test for myself?</b><br/><br/>
				<b>Ans:</b> No, you can not design test for yourself. For designing tests you have to register as contributor.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 06: Can I upload questions from my account?</b><br/><br/>
				<b>Ans:</b> No, you can not upload questions from your account. For contributing questions you have to register as contributor.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 07: Will I get free upgrade form one plan to another?</b><br/><br/>
				<b>Ans:</b> Yes you will be able to upgrade from one plan to another for free.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 08: Will result analysis for finished tests be saved for future view?</b><br/><br/>
				<b>Ans:</b> Yes, it will be preserved and we don&rsquo;t have any policies in near future to remove those.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 09: If I am a part of Institute/Organization which is your client and they had registered me, can then I still be able register as a different Individual with same email id?</b><br/><br/>
				<b>Ans:</b> No, but you can register with different email id. 
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 10: If I registered first with an email id, and same email id is used by an institute, then will personal account activity will be vanished?</b><br/><br/>
				<b>Ans:</b> No, your account activity will be preserved.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 11: If I attempt test and during the test timer expires but I did not get a chance to click on end exam, will my test be considered as submitted?</b><br/><br/>
				<b>Ans:</b> Yes, your test will be considered as submitted in that case.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 12: What if I want to finish the test earlier than time allotted or before timer expire?</b><br/><br/>
				<b>Ans:</b> You can finish/end the test by clicking on <b>End Exam</b> button.
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 13: Can I change the choice of answer I have chosen initially?</b><br/><br/>
				<b>Ans:</b> It depends upon test you are attempting. If that test allows you to change the option then only you can. So, we strongly recommend you to read all instructions about test carefully - before attempting test. 
			</p><hr style="width:25%;"/>
			<p>
				<b style="color:steelblue;">Qus 14: It is necessary to press submit button for each answer.</b><br/><br/>
				<b>Ans:</b> Yes, if you won&rsquo;t click on submit - your answer <b>will not</b> be considered as submitted.
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