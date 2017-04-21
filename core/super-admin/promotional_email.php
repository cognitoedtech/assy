<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	include_once(dirname(__FILE__)."/../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/../../lib/billing.php");
	include_once(dirname(__FILE__)."/../../lib/include_js_css.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -

	$objBilling = new CBilling();

	$emailed = 0;
	if(!empty($_GET['email_success']))
	{
		$emailed = $_GET['email_success'];
	}

	$unsubscribed = 0;
	if(!empty($_GET['unsubscribe_success']))
	{
		$unsubscribed = $_GET['unsubscribe_success'];
	}

	printf("<script>email_success='%s'</script>",$emailed);
	printf("<script>unsubscribe_success='%s'</script>",$unsubscribed);
	
	$objIncludeJsCSS = new IncludeJSCSS();
	
	$menu_id = CSiteConfig::UAMM_SUPER_ADMIN;
	$page_id = CSiteConfig::UAP_EMAIL_PROMOTIONS;
?>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="Generator" content="EditPlus">
<meta name="Author" content="">
<meta name="Keywords" content="">
<meta name="Description" content="">
<title>Promotional Email</title>
<?php 
$objIncludeJsCSS->CommonIncludeCSS("../../");
$objIncludeJsCSS->CommonIncludeJS("../../");
?>
</head>
<body>

	<?php 
	include_once(dirname(__FILE__)."/../../lib/header.php");
	?>

	<!-- --------------------------------------------------------------- -->
	<br />
	<br />
	<br />
	<div class='container' style='width: 100%'>
		<div class='row-fluid'>
			<div class="col-lg-3">
				<?php 
				include_once(dirname(__FILE__)."/../../lib/sidebar.php");
				?>
			</div>
		</div>
	</div>
</body>
</html>
<?php 
if(false)
{
?>
<html>
	<head>
		<title> Super Admin </title>
		<style type="text/css" title="currentStyle">
			@import "../../css/redmond/jquery-ui-1.9.0.custom.min.css";
			body.loading {
			    overflow: hidden;   
			}
			body.loading .modal {
			    display: block;
			}
		</style>
		<link rel="stylesheet" type="text/css" href="../../css/notify.css" />
		<link rel="stylesheet" type="text/css" href="../../css/mipcat.css" />
		<script type="text/javascript" charset="utf-8" src="../media/js/jquery.js"></script>
		<script type="text/javascript" src="../../3rd_party/wizard/js/jquery.validate.min.js"></script>
		<script type="text/javascript" charset="utf-8" src="../../js/jquery-ui-custom.min.js"></script>
		<script type="text/javascript" charset="utf-8" src="../../js/mipcat/utils.js"></script>
		<script type="text/javascript" src="../../3rd_party/wizard/js/jquery.validate.min.js"></script>
		<script type="text/javascript" src="../../js/notification.js"></script>
		<script type="text/javascript" src="../../3rd_party/ckeditor/ckeditor.js"></script>
		<style type="text/css">
			/*demo page css*/
			body{ font: 75% "Trebuchet MS", sans-serif; margin: 5px; overflow:hidden;}
		</style>
	</head>
	<body>
		<div id="page_loading_box" style="position:absolute;top:100px;left:50%;zindex:200;">
			<img src="../../images/page_loading.gif" width="32" height="32"/>
		</div>
		<div class="notification sticky hide">
	        	<p>  <?php echo(($emailed == 1)?"Email sent successfully":"You have successfully unsubscribed one email id"); ?> </p>
	        	<a class="close" href="javascript:">
	            	<img src="../../images/icon-close.png" /></a>
	    </div>
		<div id="page_title" style="display:none">
			<ul>
				<li><a href="#tab1">Promotional Email</a></li>
			</ul>
			<div id="tab1" style="font: 90% 'Trebuchet MS', sans-serif;">
				<form action="post_get/form_promotional_email_exec.php" method="post" onsubmit="return ValidateInput();">
					<br /><br />
					<label style="margin-left: 65px"><b>Email Choice: </b></label><br/>
					<input type="radio" id="email_choice" name="email_choice" style="margin-left: 65px" value="email" checked='checked' onchange="OnEmailOptChange();"/> Send Email
					<input type="radio" id="email_choice" name="email_choice" style="margin-left: 65px" value="unsubscribe" onchange="OnEmailOptChange();"/> Unsubscribe Email
					<br /><br />
					
					<div id="unsubscribe_id" style="display:none">
						<label style="margin-left: 65px"><b>Unsubscribe Email: </b></label><br/>
						<input type="text" id="unsubscribe_email" name="unsubscribe_email" size="60" style="margin-left: 65px" placeholder="Choose Email From List To Unsubscribe" onKeyDown="setBEmail(false);"/><br/><br/>
					</div>
					
					<div id="email_id">
						<label style="margin-left: 65px"><b>Company: </b></label><br/>
						<input type="text" id="email_to_company" name="email_to_company" size="120" style="margin-left: 65px" placeholder="Enter Company Name"/><br/><br/>
						
						<label style="margin-left: 65px"><b>Subject: </b></label><br/>
						<input type="text" id="email_subject" name="email_subject" size="120" style="margin-left: 65px" placeholder="Enter Proper Subject Of Mail"/><br/><br/>
					
						<label style="margin-left: 65px"><b>Body: </b></label><br/>
						<div style="margin-left: 65px">
							<textarea id="email_body" name="email_body"></textarea><br/><br/>
						</div>
					</div>
					<input type="submit" id="submit_id" value="Send" style="margin-left: 65px"/>
				</form>
			</div>
		</div>
		<script type="text/javascript">
			$(window).load(function(){
				$("#page_loading_box").hide();
				$("#page_title").show();
				$("#page_title").tabs();
				
				var page_hgt = objUtils.AdjustHeight("tab1");
				$('#platform', window.parent.document).height(page_hgt+200);
			});	
			
			CKEDITOR.replace( 'email_body');
			
			var bEmail = false;
			function setBEmail(bVal)
			{
				bEmail = bVal;
			}

			$( "#unsubscribe_email" ).autocomplete({
        	   source: function(request,response) {
		   		//alert("Test");
				$.getJSON("ajax/ajax_promotional_emails.php",{term: request.term},function(data){
						//alert('hi');
						response(data);
					});
				},
        		minLength: 2,
        		autoFocus: true,
        		response: function(event, ui){
       		 		//alert("Test");
           		},
				select: function(event, ui){
					setBEmail(true);
				}
			});

			$( "#email_to_company" ).autocomplete({
	        	   source: function(request,response) {
			   		//alert("Test");
					$.getJSON("ajax/ajax_company_list_for_promotion.php",{term: request.term},function(data){
							//alert('hi');
							response(data);
						});
					},
	        		minLength: 1,
	        		autoFocus: true,
	        		response: function(event, ui){
	       		 		//alert("Test");
	           		},
					select: function(event, ui){
						//setBEmail(true);
					}
				});

			function ValidateInput()
			{
				var bRet = true;
				var val = $("input[name=email_choice]:checked").val();
				
				if(val == "unsubscribe")
				{
					if($.trim($("input[name=unsubscribe_email]").val()))
					{
						if(bEmail)
						{
							$("input[name=unsubscribe_email]").css("border", "1px solid green");
						}
						else
						{
							$("input[name=unsubscribe_email]").css("border", "1px solid red");
							
							bRet = bRet && false;
						}
					}
					else
					{
						$("input[name=unsubscribe_email]").css("border", "1px solid red");
							
							bRet = bRet && false;
					}
				}
				else
				{
					if($.trim($("input[name=email_subject]").val()))
					{
						$("input[name=email_subject]").css("border", "1px solid green");
					}
					else
					{
						$("input[name=email_subject]").css("border", "1px solid red");
							
						bRet = bRet && false;
					}

					if($.trim($("#email_body").val()))
					{
						$("#email_body").css("border", "1px solid green");
					}
					else
					{
						$("#email_body").css("border", "1px solid red");
							
						bRet = bRet && false;
					}
				}
				return bRet;
			}

			function OnEmailOptChange()
			{
				var val = $("input[name=email_choice]:checked").val();
				if(val == "email")
				{
					$("#unsubscribe_id").hide();
					$("#email_id").show();
					$("#submit_id").val("Send");
				}
				else
				{
					$("#email_id").hide();
					$("#unsubscribe_id").show();
					$("#submit_id").val("Unsubscribe");
				}
			}
			
			$(document).ready(function () {
				if(unsubscribe_success == 1 || email_success == 1)
				{
					$('.notification.sticky').notify({ type: 'sticky' });
				}
				OnEmailOptChange();
			});
		</script>
	</body>
</html>
<?php 
}
?>