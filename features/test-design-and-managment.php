<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
include_once (dirname ( __FILE__ ) . "/../lib/include_js_css.php");

$objIncludeJsCSS = new IncludeJSCSS ();
?>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="Generator" content="Mastishka Intellisys Private Limited">
<meta name="Author" content="Mastishka Intellisys Private Limited">
<meta name="Keywords" content="">
<meta name="Description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>EZeeAssess Features: Test Design And Management</title>
<?php
$objIncludeJsCSS->IncludeMipcatCSS ( "../" );
$objIncludeJsCSS->IncludeIconFontCSS ( "../" );
$objIncludeJsCSS->CommonIncludeCSS ( "../" );
$objIncludeJsCSS->CommonIncludeJS ( "../" );
?>
</head>
<body>
	<?php
	include_once (dirname ( __FILE__ ) . "/../lib/header.php");
	$bShowCKEditor = FALSE;
	?>
	<br />
	<br />
	<br />
	<br />
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="drop-shadow lifted">
					<a
						href="../images/features/test-design-mgmt/test-design-wizard.png"
						target="_blank"><img
						src="../images/features/test-design-mgmt/test-design-wizard.png" /></a>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="panel panel-primary">
				<div class="panel-heading clearfix">
					<span class="pull-left panel-title"><i class="icon-arrow-left-2"></i>&nbsp;&nbsp;<a href="knowledge-base-management.php">Knowledge-Base Management</a></span> 
					<span class="pull-right panel-title"><a href="test-scheduling-and-monitoring.php">Test Scheduling &amp; Monitoring</a>&nbsp;&nbsp;<i class="icon-arrow-right-2"></i></span>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-8">
							<h3>Test &frasl; Assessment Design &amp; Management</h3>
							EZeeAssess's <b><i>Design and Manage Test</i></b> is our <b><i>&ldquo;Unique
									Selling Preposition&rdquo;</i></b> we would like to call it a 2
							minute utility design for any kind of test on the fly followed
							with simple steps (through wizard). It has all that you need to
							design an assessment &frasl; test, in fast, robust and reliable
							manner. Duh! We know we are apprising ourselves, but we mean it,
							so try it, to know it!<br /> <br /> We made a <b>remarkable
								difference</b> in <b>test deployment</b> from this glaring
							unitlity which is like sub-application. It provides <b>complete
								automation</b> to test &frasl; assessment process. Additionally
							from <b><i>&ldquo;Manage Tests&rdquo;</i></b> you can manage
							already created tests and <b>Preview the Test</b> for free before
							scheduling it for any candidate to identify that the right set of
							questions are being asked. <br /> <br /> Just think about the
							features you would like to have in <b><i>&ldquo;Test Design
									Wizard&rdquo;</i></b> and it already exists. Let us quickly run
							through the options:<br /> <br />
							<div class="well">
								<ul>
									<li>Choice of Test Name, if you don't have one don't worry we
										will give it a unique name.</li>
									<li>Test Duration in Minutes.</li>
									<li>Total Number of Questions in test.</li>
									<li>Minimum Cut-Off (%) in percent.</li>
									<li>Maximum Cut-Off (%) in percent.</li>
									<li>Number of Sections in test.</li>
									<li>Section wise vs Consistant marking scheme.</li>
									<li>Negative Marking.</li>
									<li>Choicee of Knowledge-Base (question), form EZeeAssess or
										Personal.</li>
									<li>Visibility control of Result Analytics (charting) for
										candidates.</li>
									<li>Option to select questions among Multiple Correct and
										Single Correct answers.</li>
									<li>Choice of test language.</li>
									<li>Custom test instructions in supported language of choice
										over WYSIWYG editor.</li>
									<li>Support for cheating prevention algorithm.</li>
									<li>Select questions in question paper from already tagged set.</li>
									<li>Selection of multiple subject per section.</li>
									<li>Selection of multiple topics from each subject.</li>
									<li>Selecting topics of specific difficulty.</li>
									<li>Preview before create &frasl; save.</li>
								</ul>
							</div>
						</div>
						<div class="col-md-4">
							<div class="drop-shadow lifted">
								<a href="../images/features/test-design-mgmt/manage-test.png"
									target="_blank"> <img
									src="../images/features/test-design-mgmt/manage-test.png" /></a>
							</div>
						</div>
					</div>
				</div>
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
