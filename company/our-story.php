<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
include_once (dirname ( __FILE__ ) . "/../lib/include_js_css.php");

$objIncludeJsCSS = new IncludeJSCSS ();
?>
<head>
<meta charset="UTF-8">
<meta name="Generator" content="Mastishka Intellisys Private Limited">
<meta name="Author" content="Mastishka Intellisys Private Limited">
<meta name="Keywords" content="">
<meta name="Description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>EZeeAssess Features:Our Story</title>
<?php
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
		<div class="text-justify">
			<h3 class="text-center"><?php echo(CConfig::SNC_SITE_NAME);?> Story</h3>
			<br /> Mastishka Intellisys Private Limited was founded by two
			engineering graduates in year 2007. The primary operations of company
			were to provide software services and consultation to clients in
			India and Abroad. We provided our services in Financial and Telecom
			Domain to our clients in USA and Europe. In Financial domain we
			served our clients to automate stock market trading strategies via
			High Frequency Trading, and in Telecom domain we helped our clients
			in finding right path to route calls over ISP and to save lot of cost
			for international IP telephony.<br /> <br /> In addition to software
			consultancy, we were lately associated with Center for Development of
			Advanced Computing (Autonomous Scientific Society of Govt. of India)
			to deliver P.G. Diploma courses in the finishing school arena. While
			engaged in training activities we found the biggest challenge was
			right assessment of candidates, by right assessment we mean quick,
			robust, and easy to deploy assessment to make sure candidate's
			full-proof evaluation with very good post assessment analytics. This
			thinking encouraged us developing assessment tool EZeeAssess.com.
		</div>
		<div class="col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
		<?php
		include ("../lib/footer.php");
		?>
		</div>
	</div>
	<script type="text/javascript">
		$(".icon-home").addClass("glyphicon");
		$(".icon-home").addClass("glyphicon-home");
	
		$(".icon-user").addClass("glyphicon");
		$(".icon-user").addClass("glyphicon-user");
	</script>
</body>
</html>