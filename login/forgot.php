<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	include_once(dirname(__FILE__)."/../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../lib/include_js_css.php");
	include_once(dirname(__FILE__)."/../lib/site_config.php");
	include_once("../database/config.php");
	include_once(dirname(__FILE__)."/../lib/utils.php");
	//$page_id = CSiteConfig::HF_FAQ;
	$login = CSessionManager::Get(CSessionManager::BOOL_LOGIN);
	
	$objIncludeJsCSS = new IncludeJSCSS();
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo(CConfig::SNC_SITE_NAME);?> : Password Recovery</title>
		<style>
			a.anchor:link {color:GhostWhite;}    /* unvisited link */
			a.anchor:visited {color:GhostWhite;} /* visited link */
			a.anchor:hover {color:GhostWhite;}   /* mouse over link */
			a.anchor:active {color:GhostWhite;}  /* selected link */
			a:focus {outline: none;}
		</style>
		<?php 
			$objIncludeJsCSS->CommonIncludeCSS("../");
			$objIncludeJsCSS->IncludeMipcatCSS("../");
			$objIncludeJsCSS->CommonIncludeJS("../");
		?>
	</head>
	<body>
		<!-- Header -->
		<?php
			include(dirname(__FILE__)."/../lib/header.php");
		?>
		
		<br />
		<br />
		<div class="container">
			<h3 style="text-align:center;color:steelblue;">Password Recovery : Step - 1/2</h3><br/>
			<div class="drop-shadow raised">
				<form class="form-horizontal" method="post" action="forgot_search.php" name="INFO"
				    onSubmit="return validate_form(document.INFO)"><p>
				    <p class="text-center">Welcome to <a href="<?php echo(CSiteConfig::ROOT_URL);?>"><?php echo(CConfig::SNC_SITE_NAME);?></a> forgot password help !</p>
					<p class="text-center">Please enter your E-Mail ID, which you have provided while registering with us.</p><br/>
					<div class="form-group">
					  	<label for="email" class="col-lg-4 col-md-4 col-sm-4 control-label">E-Mail :</label>
					   	<div class="col-lg-4 col-md-4 col-sm-4">
					  		<input class="form-control" id="email" name="email" type="text" />
					  	</div>
					  	<div class="col-lg-2 col-md-2 col-sm-2 ">
					  		<input class="btn btn-primary" type="submit" value =" Submit "/>
					  	</div>
					</div>
				</form>
			</div>
			<div class=' col-lg-offset-1 col-md-offset-1 col-sm-offset-1'>
				<?php
					include_once (dirname ( __FILE__ ) . "/../lib/footer.php");
				?>
			</div>
		</div>
	</body>
	
	<script language="JavaScript">
		function validate_form(emailinfo)
		{
			if(emailinfo.email.value == "" || emailinfo.email.value == null)
			{
				// Check for an empty string or null value
				alert("Please enter your email");
				return(false);
			}
			else
			{
				if (validate_email(emailinfo.email ,"Not a valid e-mail address!")==false)
				{
					emailinfo.email.focus();
					return false;
				}
				else
				{
					return(true);
				}
			}
		}
	
		function validate_email(field,alerttxt)
		{
			with (field)
			{
				apos=value.indexOf("@");
				dotpos=value.lastIndexOf(".");

				var filter	 = /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))$/;
				if(!filter.test(value))
				{
					alert(alerttxt);
					return false;
				}
				else 
				{
					return true;
				}
			}
		}
	</script>
</html>




