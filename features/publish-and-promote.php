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
					<a href="../images/features/publish-and-promote/free-subdomain.png"
						target="_blank"><img
						src="../images/features/publish-and-promote/free-subdomain.png" /></a>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="panel panel-primary">
				<div class="panel-heading clearfix">
					<span class="pull-left panel-title"><i class="icon-arrow-left-2"></i>&nbsp;&nbsp;<a
						href="billing-and-account-management.php">Billing Management</a></span>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-8 text-justify">
							<h3>Publish &amp; Promote</h3>
							EZeeAssess's <b><i>Publish &amp; Promote</i></b> feature is
							unique phenomenon to promote your business for free, by
							publishing test at <a href="http://free.ezeeassess.com">free.ezeeassess.com</a>
							you will be exposing your training methods and market yourself in
							this free test prepration market place by hilightling how good
							your test prepration methods are. <br /> <br /> Famous economists
							mention it many times that <b><i>&ldquo;There's no such thing as
									a free lunch&ldquo;</i></b> and we are having no different
							views when it comes to business! So, what's the catch? The catch
							is our <b><i>Publish &amp; Promote</i></b> feature serves
							win-win-win strategy for candidate-<b>you</b>-ezeeasses. It's
							free for candidate but we get candidate information before
							releasing test performance details to candidate and you can get
							that information with candidate's details by paying us per test
							cost applicable as per plan. With <b><i>Publish &amp; Promote</i></b>
							we don't charge for test here rather we charge for candidate data
							who finished your published test.
						</div>
						<div class="col-md-4">
							<div class="drop-shadow lifted">
								<a href="../images/features/publish-and-promote/manage-test.png"
									target="_blank"> <img
									src="../images/features/publish-and-promote/manage-test.png" /></a>
							</div>
							<div class="drop-shadow lifted">
								<a href="../images/features/publish-and-promote/free-user-results.png"
									target="_blank"> <img
									src="../images/features/publish-and-promote/free-user-results.png" /></a>
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
