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
<title>EZeeAssess Features: Departments</title>
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
					<a href="../images/features/departments/manage-coordinators.png"
						target="_blank"><img
						src="../images/features/departments/manage-coordinators.png" /></a>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="panel panel-primary">
				<div class="panel-heading clearfix">
					<span class="pull-left panel-title"><i class="icon-arrow-left-2"></i>&nbsp;&nbsp;<a href="batch-management.php">Batch Management</a></span>
					<span class="pull-right panel-title"><a href="billing-and-account-management.php">Billing Management</a>&nbsp;&nbsp;<i class="icon-arrow-right-2"></i></span>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-8 text-justify">
							<h3>Departments Or Coordinators</h3>
							EZeeAssess's <b><i>Departments &frasl; Coordinators</i></b>
							feature helps you to de-centalize your business function by
							creating coordinators that may head your branches or departments.
							You can then share previlages and assign them some credits to be
							on their own. <br /> <br /> Whenever organizations puchase SaaS
							subscription they have challange of managing departments with
							different accounts, through <b>EZeeAssess Coordinator&rsquo;s</b>
							management this challange is completly taken care.
						</div>
						<div class="col-md-4">
							<div class="drop-shadow lifted">
								<a
									href="../images/features/departments/register-coordinators.png"
									target="_blank"> <img
									src="../images/features/departments/register-coordinators.png" /></a>
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
