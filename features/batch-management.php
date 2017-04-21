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
<title>EZeeAssess Features: Batch Management</title>
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
					<a href="../images/features/batch-mgmt/manage-batch.png"
						target="_blank"><img
						src="../images/features/batch-mgmt/manage-batch.png" /></a>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="panel panel-primary">
				<div class="panel-heading clearfix">
					<span class="pull-left panel-title"><i class="icon-arrow-left-2"></i>&nbsp;&nbsp;<a href="result-analytics.php">Result Analytics</a></span> 
					<span class="pull-right panel-title"><a href="departments-coordinators.php">Departments</a>&nbsp;&nbsp;<i class="icon-arrow-right-2"></i></span>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-8 text-justify">
							<h3>Candidate Batches</h3>
							EZeeAssess's <b><i>Candidate&rsquo;s Batch Management</i></b>,
							enables Test Admin to segregate candidates based on various
							criteria like location, batch time, course type etc. and put them
							in batches. Batches helps in <b>locating candidates</b> and <b>scheduling
								tests</b> specific for batches. With <b><i>&ldquo;Manage
									Batch&rdquo;</i></b> you can create a new batch, add (optional)
							description, edit the name &frasl; description later and even
							delete them. Deleting batches donesn't mean deleting candidates
							registered, upon deleting batch - candidate entry will be moved
							to <b>Default Batch</b>.<br /> <br /> "I see, you mentioned that
							editing batch means editing name and description but how can I
							move candidates from one batch to another?". This question has an
							answer too, with <b><i>&ldquo;Change Batch&rdquo;</i></b> feature
							you can do the shuffling of candidates among batches. Doesn't it
							sound pretty easy to manage candidates with batches!
						</div>
						<div class="col-md-4">
							<div class="drop-shadow lifted">
								<a href="../images/features/batch-mgmt/change-batch.png"
									target="_blank"> <img
									src="../images/features/batch-mgmt/change-batch.png" /></a>
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
