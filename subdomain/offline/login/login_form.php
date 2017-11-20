<?php
include_once(dirname(__FILE__)."/../lib/include_js_css.php");
include_once ("../lib/session_manager.php");
include_once ("../lib/user_manager.php");
include_once ("../lib/site_config.php");
include_once ("../lib/utils.php");

$parsAry = parse_url ( CUtils::curPageURL () );
$qry = split ( "[=&]", $parsAry ["query"] );

$objUserManager = new CUserManager();
$bEmptyUsersTable = $objUserManager->IsUsersTableEmpty(); 
$login_name = "";
if ($qry [0] == "ln") {
	$login_name = $qry [1];
}

$objIncludeJsCSS = new IncludeJSCSS();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
<TITLE>New Document</TITLE>
<META NAME="Generator" CONTENT="EditPlus">
<META NAME="Author" CONTENT="">
<META NAME="Keywords" CONTENT="">
<META NAME="Description" CONTENT="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php 
$objIncludeJsCSS->IncludeMipcatCSS("../");
$objIncludeJsCSS->IncludeBootstrap3_1_1Plus1CSS("../");
$objIncludeJsCSS->IncludeJqueryJS("../");
$objIncludeJsCSS->IncludeJqueryValidateMinJS("../");
?>
<script language="javascript" type="text/javascript">
		function DisplayError(error)
		{
		  var msgspan =	parent.document.getElementById("message");		 
		  msgspan.innerHTML = error;
		}
		$(function(){ 
		    // find all the input elements with title attributes
			//$('input[title!=""]').hint();
		});
		
		function OnLogin()
		{
			$("#login_form").hide();
			$("#loading").show();
		}
		
		function LoginFailed()
		{
			$("#login_form").show();
			$("#loading").hide();
		}
		</script>
</HEAD>
<BODY>
	<div class="drop-shadow raised" style="width: 250px">
		<form class="form-horizontal" id="login_form" method="post" action="login.php">
			<?php
			include_once ("../lib/session_manager.php");
			/*
			 * if(CSessionManager::IsError() &&
			 * CSessionManager::GetErrorMsg()!="") { echo(sprintf("<script
			 * language='javascript' type='text/javascript'>
			 * DisplayError('%s');</script>",CSessionManager::GetErrorMsg())); }
			 */
			if (CSessionManager::IsError () && CSessionManager::GetErrorMsg () != "") {
				echo ("<div style='color:red; text-align:center'>" . CSessionManager::GetErrorMsg () . "</div>");
				echo ("<script> LoginFailed(); </script>");
			}
			CSessionManager::ResetErrorMsg ();
			?>
			<br />
			<div class="form-group">
				<div class="col-lg-6 col-md-6 col-sm-6">
					<input class="form-control" id="USERNAME" type="text" name="email" placeholder="Email ID" />
				</div>
			</div>
			<?php 
			if(!$bEmptyUsersTable)
			{
			?>
			<div class="form-group">
				<div class="col-lg-6 col-md-6 col-sm-6">
					<input class="form-control" id="PASSWORD" type="password" name="password" placeholder="Password" />
					<input type="hidden" name="time_zone" id="time_zone" /> 
					<input type="hidden" name="login_name" value="<?php echo($login_name);?>" />
				</div>
			</div>
			<?php 
			}
			?>
			<div class="form-group" style="text-align: center;">
      			<div class="col-lg-10 col-md-10 col-sm-10  col-lg-offset-2 col-md-offset-2 col-sm-offset-2">
        			<input id="SUBMIT" class="btn btn-primary" type="submit" value="    Login    ">
     			</div>
   			</div>
		</form>
		<div id="loading" style="text-align: center; display: none">
			<b>Verifying Login Details.<br />Please Wait...<br /></b> <img
				src="../images/updating.gif" width="16" height="16" border="0"
				alt="">
		</div>
	</div>
	<script type="text/javascript">
		var time_zone_val = get_time_zone_offset();
		$(window).load(function(){
			$("#time_zone").val(time_zone_val+"");
		});
		
		/*$.fn.teletype = function(opts, callback){
		    var $this = this,
		        defaults = {
		            animDelay: 50
		        },
		        settings = $.extend(defaults, opts);
		    
		    count = settings.text.length;
		    
		    $.each(settings.text, function(i, letter){
		        setTimeout(function(){
		        	$this.val($this.val() + settings.text.charAt(i));
		            
		            if (!--count) callback();
		        }, settings.animDelay * i);
		    });
		    
		    //alert("Test");
		};
		
		function TypeEmail(email, password)
		{
			//alert(email);
			//alert(password);
			//return;
			$('#USERNAME').val('');
		    $('#USERNAME').focus();
		    $('#USERNAME').teletype({
		        animDelay: 125,
		        text: email
		    }, function(){
		    	$('#PASSWORD').val('');
			    $('#PASSWORD').focus();
			    $('#PASSWORD').teletype({
			        animDelay: 125,
			        text: password
			    }, function(){
			    	$('#SUBMIT').trigger('click');
			    });
		    });
		}*/

		$(document).ready(function () {

			<?php 
			if(!$bEmptyUsersTable)
			{
			?>
			$('#login_form').validate({
				rules: {
					'email':			{required: true, email: true},
					'password':			{required: true}
				}, messages: {
					'email':			{required:	"<p style='color: red;font-size: 12px;'>Please enter your email-id!</p>", email:	"<p style='color: red;font-size: 12px;'>Please enter your valid email-id!</p>"},
					'password':			{required:	"<p style='color: red;font-size: 12px;'>Please enter the password!</p>"}
				},
				submitHandler: function (form) {
					OnLogin();
		            form.submit();
		        }
			});
			<?php 
			}
			else 
			{
			?>
			$('#login_form').validate({
				rules: {
					'email':			{required: true, email: true},
				}, messages: {
					'email':			{required:	"<p style='color: red;font-size: 12px;'>Please enter your email-id!</p>", email:	"<p style='color: red;font-size: 12px;'>Please enter your valid email-id!</p>"},
				},
				submitHandler: function (form) {
					OnLogin();
		            form.submit();
		        }
			});
			<?php 
			}
			?>
			
		});

		function get_time_zone_offset() 
		{
		    var current_date = new Date();
		    return -current_date.getTimezoneOffset() / 60;
		}
		</script>
</body>
</html>