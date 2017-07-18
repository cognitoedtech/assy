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
<title>EZeeAssess Features: Candidate Management</title>
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
					<a href="../images/features/cand-mgmt/register-users.png"
						target="_blank"><img
						src="../images/features/cand-mgmt/register-users.png" /></a>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="panel panel-primary">
				<div class="panel-heading clearfix">
					<span class="pull-right panel-title"><a href="knowledge-base-management.php">Knowledge-Base Management</a>&nbsp;&nbsp;<i class="icon-arrow-right-2"></i></span>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-8 text-justify">
							<h3>Candidate Registration &amp; Management</h3>
							The most important question that savvy HR professionals,
							Recruiters and Educational Institutes are asking is <i><b>&ldquo;What
									does it cost?&rdquo;</b></i> and <i><b>&ldquo;What do we
									get?&rdquo;</b></i> The benefits of using a <b>EZeeAssess&rsquo;s
								Candidate Managing Solution</b> is the return on investment in
							the shortest possible time. When you need to make it simple,
							cost-effective and fast, EZeeAssess, has a range of HR and
							Recruitment, and evaluation solutions for your business.<br /> <br />
							EZeeAssess's <b><i>Candidate Management</i></b>, you can have fun
							follwing simple steps in creating batches and scheduling tests -
							that is most easy and comprehending way. You may add hundreads of
							candidates, and after they got authenticated you can schedule
							tests for them in clicks. <br /> <br /> This module covers
							candidate registration process via uploading their details via
							bulk upload through Excel Sheet or sending them registration link
							(URL) via Email &frasl; Chat &frasl; IM &frasl; Text. The best
							part is you can pre-select the batch they should be registered
							to, and if you haven't made one - you will always have default
							batch.<br /> <br /> You need not have to worry about the
							typos&rsquo; while filling the candidate registration Excel
							Sheet, via our auto validation helps view the errors before
							processing the sheet. You will see the <b>&ldquo;Validation
								Console&rdquo;</b> at the bottom of the page.<br /> <br /> You
							can also take a look at registered candidate's details and their
							registration status (Pending &frasl; Activated). Here you can
							also export the list to CSV &frasl; PDF or archive &frasl; delete
							registered candidates.
						</div>
						<div class="col-md-4">
							<div class="drop-shadow lifted">
								<a
									href="../images/features/cand-mgmt/manage-registerd-users.png"
									target="_blank"> <img
									src="../images/features/cand-mgmt/manage-registerd-users.png" /></a>
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
