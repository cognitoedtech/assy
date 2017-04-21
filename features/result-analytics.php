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
<title>EZeeAssess Features: Result Analytics</title>
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
	?>
	<br />
	<br />
	<br />
	<br />
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="drop-shadow lifted">
					<a href="../images/features/result-analytics/brief-result.png"
						target="_blank"><img
						src="../images/features/result-analytics/brief-result.png" /></a>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="panel panel-primary">
				<div class="panel-heading clearfix">
					<span class="pull-left panel-title"><i class="icon-arrow-left-2"></i>&nbsp;&nbsp;<a href="test-scheduling-and-monitoring.php">Test Scheduling &amp; Monitoring</a></span> 
					<span class="pull-right panel-title"><a href="batch-management.php">Batch Management</a>&nbsp;&nbsp;<i class="icon-arrow-right-2"></i></span>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-8">
							<h3>Result Analytics (Charting) &amp; Question Paper Inspection</h3>
							EZeeAssess's <b><i>Result Analytics (Charting) &amp; Question
									Paper Inspection</i></b> helps you analysing candidate's
							performance by digging deep into every section. With <i><b>&ldquo;Test
									DNA Analysis&rdquo;</b></i> you can get the visual plots via
							charts - that illustrates Candidate&rsquo;s over all performance
							in assessment/test. Not to mention that we understand the
							requirement of PDF export and Test Admin will find that on top
							right which is <i><b>&ldquo;PDF Download&rdquo;</b></i> icon. <br />
							<br /> Okay, but what if you just want to have a glimpse of
							everything for a particular schedule? Well you just have to check
							<i><b>&ldquo;Brief Result&rdquo;</b></i>, navigate and select
							from drop down options, apply filters and get the statistics on
							your fingretips. Isn't that sound wonderfull ! <br /> <br /> We
							are sure you will be looking a place now to check the question
							paper asked during test to analyze and see which question was
							rightly or wrongly attempted by a candidate. Not to worry, <i><b>&ldquo;Result
									Inspection&rdquo;</b></i> empowers you to do exactly that with
							an option to export attempted question paper in PDF file. <br />
							<br /> Challange for Test Admin doesn't end here, as there are
							scenarios when same test has to be conducted on <b>differnt time
								and dates</b>. <b><i>Result consolidation</i></b> and <b><i>merit
									list generation</i></b> is always been a challanging and time
							consuming task, our designers and product team has came up with a
							solution for result consolidation, i.e. <i><b>&ldquo;Produce
									Custom Result&rdquo;</b></i>. It not only helps <b>Test Admin</b>
							to consolidate result but also allows to apply custom filters
							before the generation of final short listing. Now shouldn't you
							say, it's Awesome!

						</div>
						<div class="col-md-4">
							<div class="drop-shadow lifted">
								<a
									href="../images/features/result-analytics/produce-custom-result.png"
									target="_blank"> <img
									src="../images/features/result-analytics/produce-custom-result.png" /></a>
							</div>
							<div class="drop-shadow lifted">
								<a
									href="../images/features/result-analytics/test-dna-analysis.png"
									target="_blank"> <img
									src="../images/features/result-analytics/test-dna-analysis.png" /></a>
							</div>
							<div class="drop-shadow lifted">
								<a
									href="../images/features/result-analytics/result-inspection.png"
									target="_blank"> <img
									src="../images/features/result-analytics/result-inspection.png" /></a>
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
