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
<title>EZeeAssess Features: Test Scheduling And Monitoring</title>
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
					<a href="../images/features/test-scdl-monitoring/schedule-test.png"
						target="_blank"><img
						src="../images/features/test-scdl-monitoring/schedule-test.png" /></a>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="panel panel-primary">
				<div class="panel-heading clearfix">
					<span class="pull-left panel-title"><i class="icon-arrow-left-2"></i>&nbsp;&nbsp;<a href="test-design-and-managment.php">Test Design &amp; Management</a></span> 
					<span class="pull-right panel-title"><a href="result-analytics.php">Result Analytics</a>&nbsp;&nbsp;<i class="icon-arrow-right-2"></i></span>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-8">
							<h3>Test Scheduling &amp; Monitoring</h3>
							EZeeAssess's <b><i>Test Scheduling &amp; Monitoring</i></b>
							feature, helps you scheduling the test in few clicks by selecting
							test, date, time, time zone and candidates. While designing this
							feature we kept in mind about your valuable time and on-the-fly
							scheduling.<br /> <br /> Are you thinking of revoking test after
							scheduling and before it has started? We still have an option for
							you - whereby you could click on <i><b>&ldquo;Manage Scheduled
									Tests&rdquo;</b></i>, to make the necessary changes. After the
							test has been revoked we will provide a complete reimbursement
							for the test (per user), that was charged to you during
							scheduling. <br /> <br /> We have catered our features in a way
							that it would replicate real life scenarios, we have come across
							scenarios where test admin would require to monitor and conclude
							test at their end (similar to snatching answer sheet, if you
							found something mischievous), and yes we have that feature
							implemented as well, <i><b>&ldquo;Monitor Active Tests&rdquo;</b></i>.
							<br /> <br /> We also have provision to check back how many of
							scheduled candidates have finished test and how many are still
							need to attempt the test for particular test schedule, you can do
							so by checking <i><b>&ldquo;View Scheduled Tests&rdquo;</b></i>.
						</div>
						<div class="col-md-4">
							<div class="drop-shadow lifted">
								<a
									href="../images/features/test-scdl-monitoring/manage-scheduled-test.png"
									target="_blank"> <img
									src="../images/features/test-scdl-monitoring/manage-scheduled-test.png" /></a>
							</div>
							<div class="drop-shadow lifted">
								<a
									href="../images/features/test-scdl-monitoring/monitor-active-tests.png"
									target="_blank"> <img
									src="../images/features/test-scdl-monitoring/monitor-active-tests.png" /></a>
							</div>
							<div class="drop-shadow lifted">
								<a
									href="../images/features/test-scdl-monitoring/view-scheduled-tests.png"
									target="_blank"> <img
									src="../images/features/test-scdl-monitoring/view-scheduled-tests.png" /></a>
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
