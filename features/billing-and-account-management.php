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
<title>EZeeAssess Features: Billing Management</title>
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
					<a href="../images/features/billing-mgmt/billing-information.png"
						target="_blank"><img
						src="../images/features/billing-mgmt/billing-information.png" /></a>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="panel panel-primary">
				<div class="panel-heading clearfix">
					<span class="pull-left panel-title"><i class="icon-arrow-left-2"></i>&nbsp;&nbsp;<a href="departments-coordinators.php">Departments</a></span>
					<span class="pull-right panel-title"><a href="publish-and-promote.php">Publish &amp; Promote</a>&nbsp;&nbsp;<i class="icon-arrow-right-2"></i></span>
				</div>
				<div class="panel-body">
					<div class="col-md-8 text-justify">
						<h3>Billing &amp; Account Management</h3>
						EZeeAssess's <b><i>Billing &amp; Account Management</i></b> let's
						you fule-in your operations and gives you unbounded freedom to
						keep rolling. You can get to see your usage on finger tips, just
						select date range and here you go - we will show your granular
						details of when, why and where we charged you. <br />
						<br />It's now time to experiance the product, so why are you
						waiting? Jump on!
					</div>
					<div class="col-md-4">
						<div class="drop-shadow lifted">
							<a href="../images/features/billing-mgmt/account-recharge.png"
								target="_blank"> <img
								src="../images/features/billing-mgmt/account-recharge.png" /></a>
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
