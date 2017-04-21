<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	include_once("../lib/session_manager.php");
	include_once("../database/config.php");
	include_once("../lib/site_config.php");
	include_once("../lib/utils.php");
	include_once('../3rd_party/recaptcha/recaptchalib.php');
	
	$page_id = CSiteConfig::HF_PLANS;
	$login 	 = CSessionManager::Get(CSessionManager::BOOL_LOGIN);
	
	$user_type  = CSessionManager::Get(CSessionManager::INT_USER_TYPE);
	$user_email	= CSessionManager::Get(CSessionManager::STR_EMAIL_ID);
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Subscription Plans - <?php echo(CConfig::SNC_SITE_NAME); ?></title>
		<link rel="stylesheet" type="text/css" href="../css/mipcat.css" />
		<link rel="stylesheet" type="text/css" href="../css/jquery-jvert-tabs-1.1.4.css" />
		<link rel="stylesheet" type="text/css" href="../3rd_party/bootstrap/css/bootstrap.css" />
		<link rel="stylesheet" type="text/css" href="../3rd_party/guiders/guiders-1.2.8.css" />
		<link rel="stylesheet" type="text/css" href="../css/glossymenu.css" />
		<link rel="stylesheet" type="text/css" href="../css/stats_box.css" />
		
		<script type="text/javascript" src="../js/jquery.js"></script>
		<script type="text/javascript" src="../js/jquery.form.js"></script>
		<script type="text/javascript" src="../3rd_party/bootstrap/js/bootstrap.js"></script>
		<script type="text/javascript" src="../js/jquery-jvert-tabs-1.1.4.js"></script>
		<script type="text/javascript" src="../3rd_party/guiders/guiders-1.2.8.js"></script>
		<script type="text/javascript" src="../js/ddaccordion.js"></script>
		<script type="text/javascript" charset="utf-8" src="../js/mipcat/utils.js"></script>
		<style>
			html::-webkit-scrollbar{
			    width:12px;
			    height:12px;
			    background-color:#fff;
			    box-shadow: inset 1px 1px 0 rgba(0,0,0,.1),inset -1px -1px 0 rgba(0,0,0,.07);
			}
			html::-webkit-scrollbar:hover{
			    background-color:#eee;
			}
			html::-webkit-resizer{
			    -webkit-border-radius:4px;
			    background-color:#666;
			}
			html::-webkit-scrollbar-thumb{
			    min-height:0.8em;
			    min-width:0.8em;
			    background-color: rgba(0, 0, 0, .2);
			    box-shadow: inset 1px 1px 0 rgba(0,0,0,.1),inset -1px -1px 0 rgba(0,0,0,.07);
			}
			html::-webkit-scrollbar-thumb:hover{
			    background-color: #bbb;
			}
			html::-webkit-scrollbar-thumb:active{
			    background-color:#888;
			}
			.img_modal {
			    display:    none;
			    position:   fixed;
			    z-index:    1000;
			    top:        0;
			    left:       0;
			    height:     100%;
			    width:      100%;
			    background: rgba( 255, 255, 255, .5 ) 
			                url('../images/page_loading.gif') 
			                50% 50% 
			                no-repeat;
			}
			
			/* When the body has the loading class, we turn
			   the scrollbar off with overflow:hidden */
			body.loading {
			    overflow: hidden;   
			}
			
			/* Anytime the body has the loading class, our
			   modal element will be visible */
			body.loading .img_modal {
			    display: block;
			}
		</style>
		<style>
	     .ui-MESSAGE-se {
		  bottom: 17px;
	      }
	   </style>
	  
		<SCRIPT LANGUAGE="JavaScript">
		var RecaptchaOptions = {
   				theme : 'clean'
		};
		
		function ValidateUserForm()
			{
				if(document.getElementById("NAME").value.trim() == '')
				{
					CheckForEmpty(document.getElementById("NAME"), true) ;
					document.REQUESTFORM.NAME.focus() ;
					return false;
				}
				if(document.getElementById("EMAIL").value.trim() == '')
				{
					CheckForEmpty(document.getElementById("EMAIL")) ;
					document.REQUESTFORM.EMAIL.focus() ;
					return false;
				}
				else
				{
					if(!ValidateEmail(document.getElementById("EMAIL")))
					{
						document.REQUESTFORM.EMAIL.focus() ;
						return false;
					}
				}
				if(document.getElementById("CONTACT").value.trim() == '')
				{
					CheckForEmpty(document.getElementById("CONTACT"), true) ;
					document.REQUESTFORM.CONTACT.focus() ;
					return false;
				}
				if(document.getElementById("ORG_NAME").value.trim() == '')
				{
					CheckForEmpty(document.getElementById("ORG_NAME")) ;
					document.REQUESTFORM.ORG_NAME.focus() ;
					return false;
				}
				if(document.getElementById("MESSAGE").value.trim() == '')
				{
					CheckForEmpty(document.getElementById("MESSAGE")) ;
					document.REQUESTFORM.MESSAGE.focus() ;
					return false;
				}
				if(document.getElementById("USAGE").value.trim() == '')
				{
					CheckForEmpty(document.getElementById("USAGE")) ;
					document.REQUESTFORM.USAGE.focus() ;
					return false;
				}
				
				
			
				return true;
			}
			
			function ValidateEmail(obj) 
			{
				var bResult = false ;
				
				var style_cr = document.getElementById(obj.name+"_CR").style ;
				var style_wr = document.getElementById(obj.name+"_WR").style ;

				if((obj.value.indexOf(".") > 0) && (obj.value.indexOf("@") > 0))
				{
					style_cr.display = "inline" ;
					style_wr.display = "none" ;
					bResult = true ;
				}
				else
				{
					style_cr.display = "none" ;
					style_wr.display = "inline" ;
				}
				
				return bResult;
			}
			
			
			function CheckForEmpty(obj, bAlpha)
			{
				var bResult = false ;
				
				var style_cr = document.getElementById(obj.name+"_CR").style ;
				var style_wr = document.getElementById(obj.name+"_WR").style ;
				
				if(obj.value.trim() == '')
				{	
					style_cr.display = "none" ;
					style_wr.display = "inline" ;
					
					bResult = true ;
				}
				else 
				{
					if(bAlpha)
					{
						bResult = IsContainsNumber(obj.value);
						if(bResult)
						{
							style_cr.display = "none" ;
							style_wr.display = "inline" ;
						}
						else
						{
							style_cr.display = "inline" ;
							style_wr.display = "none" ;
						}
					}
					else
					{
						style_cr.display = "inline" ;
						style_wr.display = "none" ;
					}
				}
				
				return bResult;
			}

			function showResponse(responseText, statusText, xhr, $form)
			{
				$("#request_modal_body").html(responseText);
				$("#request_send_btn").hide();
    			$("#getQuotes").show();
				
				//alert('status: ' + statusText + '\n\nresponseText: \n' + responseText + '\n\nThe output div should have already been updated with the responseText.'); 
			}
			
			var options = { 
	       	 	//target:        '',   // target element(s) to be updated with server response 
	       		// beforeSubmit:  showRequest,  // pre-submit callback 
	      	 	 success:       showResponse,  // post-submit callback 
	 
	        	// other available options: 
	        	url:      'ajax/ajax_subs_plans_exec.php',         // override for form's 'action' attribute 
	        	type:      'POST',       // 'get' or 'post', override for form's 'method' attribute 
	        	//dataType:  null        // 'xml', 'script', or 'json' (expected server response type) 
	        	clearForm: true        // clear all form fields after successful submit 
	        	//resetForm: true        // reset the form after successful submit 
	 
	        	// $.ajax options can be used here too, for example: 
	        	//timeout:   3000 
	    	};

	    	function SubmitFormData()
	    	{
		    	if(ValidateUserForm())
		    	{
		    		$("#getQuotes").hide();
		    		$('body').on({
					    ajaxStart: function() { 
					    	$(this).addClass("loading"); 
					    },
					    ajaxStop: function() { 
					    	$(this).removeClass("loading"); 
					    }    
					});
	    			$('#REQUESTFORM').ajaxSubmit(options);
		    	} 
	    		return false;
		    }
		</SCRIPT>
	</head>
	<body style="font: 75% 'Trebuchet MS', sans-serif; margin: 5px;">
		<!-- Header -->
		<?php
			include("../lib/header.php");
		?>
		<br/><br/><br/>
		<div id="getQuotes" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="getQuotesLabel" aria-hidden="true">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3 id="getQuotesLabel">Request <?php echo(CConfig::SNC_SITE_NAME);?> Usage Quotes (Rates)</h3>
			</div>
			<div class="modal-body" id="request_modal_body">
				<form id="REQUESTFORM" name="REQUESTFORM"  action="ajax/ajax_subs_plans_exec.php"  method="POST" onsubmit="ValidateUserForm();">
					<table border="0" align="center" cellpadding="2" cellspacing="0">
						<tr>
							<td><span class="boldfont">Name :</span><BR/></td>
							<td><input class="input-xlarge" name="NAME" type="text" class="textfield" id="NAME" onblur ="CheckForEmpty(this);" size="30" class="input"/>&nbsp;&nbsp;<IMG ID="NAME_CR" STYLE="display:none" SRC="../images/apply.png" WIDTH="16" HEIGHT="16" BORDER="0" ALT=""><IMG ID="NAME_WR" STYLE="display:none" SRC="../images/cancel.png" WIDTH="16" HEIGHT="16" BORDER="0" ALT=""><BR/></td>
			   			</tr>
			  			<tr>
							<td><span class="boldfont">Your Email :</span><BR/></td>
					 		<td><input class="input-xlarge" name="EMAIL" type="text" class="textfield" id="EMAIL" onblur ="ValidateEmail(this);" size="50" class="input" />&nbsp;&nbsp;<IMG ID="EMAIL_CR" STYLE="display:none" SRC="../images/apply.png" WIDTH="16" HEIGHT="16" BORDER="0" ALT=""><IMG ID="EMAIL_WR" STYLE="display:none" SRC="../images/cancel.png" WIDTH="16" HEIGHT="16" BORDER="0" ALT=""><BR/></td>
			 		    </tr> 
			 			<tr>
			  				<td><span class="boldfont">Contact # :</span><BR/></td>
							<td><input class="input-xlarge" name="CONTACT" type="text" class="textfield" id="CONTACT" onblur ="CheckForEmpty(this);" size="50" class="input" />&nbsp;&nbsp;<IMG ID="CONTACT_CR" STYLE="display:none" SRC="../images/apply.png" WIDTH="16" HEIGHT="16" BORDER="0" ALT=""><IMG ID="CONTACT_WR" STYLE="display:none" SRC="../images/cancel.png" WIDTH="16" HEIGHT="16" BORDER="0" ALT=""><BR/></td>
						</tr> 
			 			<tr>
					 		<td><span class="boldfont">Organization Type:</span><BR/></td>
					 		<td><input class="input-xlarge uneditable-input" type="text" name="ORG_TYPE" id="ORG_TYPE" readonly><br/></td>
						</tr> 
						<tr>
				 			<td><span class="boldfont">Organization Name:</span><BR/></td>
							<td><input class="input-xlarge" name="ORG_NAME" type="text" class="textfield" id="ORG_NAME" onblur ="CheckForEmpty(this);" size="50"/>&nbsp;&nbsp;<IMG ID="ORG_NAME_CR" STYLE="display:none" SRC="../images/apply.png" WIDTH="16" HEIGHT="16" BORDER="0" ALT=""><IMG ID="ORG_NAME_WR" STYLE="display:none" SRC="../images/cancel.png" WIDTH="16" HEIGHT="16" BORDER="0" ALT=""><BR/></td>
		   				</tr>
		  				<tr>
							<td><span class="boldfont">Minimum Monthly Tests:</span><BR/></td>
							<td><select name="USAGE" id="USAGE" onblur ="CheckForEmpty(this);">
								<option value="" >Select Usage</option>
								<option value="Less than 500" >Less than 500</option>
								<option value="501 - 1000" >501 - 1000</option>
								<option value="1,001 - 5,000" >1,001 - 5,000</option>
								<option value="5,001 - 10,000" >5,001 - 10,000</option>
								<option value="10,000 and more" >10,000 and more</option>
								</select>&nbsp;&nbsp;<IMG ID="USAGE_CR" STYLE="display:none" SRC="../images/apply.png" WIDTH="16" HEIGHT="16" BORDER="0" ALT=""><IMG ID="USAGE_WR" STYLE="display:none" SRC="../images/cancel.png" WIDTH="16" HEIGHT="16" BORDER="0" ALT=""><BR/>
							</td>
		 				</tr>
		 				<tr>
							<td><span class="boldfont">Subject:</span><BR/></td>
							<td><input class="input-xlarge" type="text" name="SUBJECT" id="SUBJECT" value="Request for <?php echo(CConfig::SNC_SITE_NAME);?>.com Usage Rates !" readonly><BR/></td>
	    				</tr>
	     				<tr>
							<td><span class="boldfont">Message:</span><BR/></td>
							<td><textarea class="input-xlarge" name="MESSAGE" id="MESSAGE" rows="3" cols="12" onblur ="CheckForEmpty(this);"></textarea>&nbsp;&nbsp;<IMG ID="MESSAGE_CR" STYLE="display:none" SRC="../images/apply.png" WIDTH="16" HEIGHT="16" BORDER="0" ALT=""><IMG ID="MESSAGE_WR" STYLE="display:none" SRC="../images/cancel.png" WIDTH="16" HEIGHT="16" BORDER="0" ALT=""><BR/></td>
	    				</tr>
	    				<tr>
							<td colspan="2" align="center">
								<?php
	    							echo recaptcha_get_html(CConfig::CK_PUBLIC);
	  							?>
							</td>
						</tr>
			 		</table>
				</form>	
			</div>
			<div class="modal-footer">
				<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
				<button class="btn btn-primary" id="request_send_btn" onclick="SubmitFormData();">Send Request</button>
			</div>
		</div>
		<table style="font:inherit;" class="table table-striped table-hover table-bordered">
			<thead>
				<tr style="background-color:#80804C;color:GhostWhite;font: 150% 'Trebuchet MS', sans-serif;font-weight:bold">
					<td style="padding:15px;text-align:center;">Features<br/></td>
					<td>Corporate&nbsp;</td>
					<td>Institutional&nbsp;</td>
					<!--<td>Individual&nbsp;</td>-->
				</tr>
				<tr style="background-color:#A35200;color:GhostWhite">
					<td><b>Minimum Recharge &amp; Test Rates (Inclusive of All Taxes)</b></td>
					<td><a href="#getQuotes" onClick="$('#ORG_TYPE').val('Corporate');" id="corporate_id" role="button" class="btn btn-primary" data-toggle="modal"><b>Get Usage Quotes</b></a></td>
					<td><a href="#getQuotes" onClick="$('#ORG_TYPE').val('Institute');" id="institute_id" role="button" class="btn btn-primary" data-toggle="modal"><b>Get Usage Quotes</b></a></td>
					<!--<td><b>Free</b></td>-->
				</tr><?php echo("");?>
				<tr style="background-color:#80804C;color:GhostWhite;font: 150% 'Trebuchet MS', sans-serif;font-weight:bold">
					<td style="padding:15px;text-align:center;">Features<br/></td>
					<td><input style="position:fixed;font-weight:bold;" class="btn btn-warning" onClick="location.href='register-org.php?sub=corp';" type="button" value="Free - Sign Up!"/></td>
					<td><input style="position:fixed;font-weight:bold;" class="btn btn-success" onClick="location.href='register-org.php?sub=inst';" type="button" value="Free - Sign Up!"/></td>
					<!--<td><input style="position:fixed;font-weight:bold;" class="btn btn-danger" onClick="location.href='register-cand.php?plan=silver';" type="button" value="Free - Sign Up!"/></td>-->
				</tr>
			</thead>
			<tbody>
				<tr style="color:blue">
					<td style="background-color:#E6E68A;"><b>Candidate  Management</b></td>
					<td style="background-color:#E6E68A;">Free</td>
					<td style="background-color:#E6E68A;">Free</td>
					<!--<td style="background-color:#E6E68A;">Not Applicable</td>-->
				</tr>
				<tr>
					<td>Unlimited Number of Registered Candidates</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<!--<td><img src="../images/cancel.png" width="16" height="16"></td>-->
				</tr>
				<tr>
					<td>Bulk Registration through Single Click Upload (Excel)</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<!--<td><img src="../images/cancel.png" width="16" height="16"></td>-->
				</tr>
				<tr>
					<td>Registration via Emailing URL</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<!--<td><img src="../images/cancel.png" width="16" height="16"></td>-->
				</tr>
				<tr>
					<td>View Registered Candidate Details</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<!--<td><img src="../images/cancel.png" width="16" height="16"></td>-->
				</tr>
				<tr>
					<td>Unregister Candidates</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<!--<td><img src="../images/cancel.png" width="16" height="16"></td>-->
				</tr>
				<tr>
					<td>Export details for all registered Candidates in PDF</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<!--<td><img src="../images/cancel.png" width="16" height="16"></td>-->
				</tr>
				<tr>
					<td>Export details for all registered Candidates in CSV</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<!--<td><img src="../images/cancel.png" width="16" height="16"></td>-->
				</tr>
				<tr style="color:blue">
					<td style="background-color:#E6E68A;"><b>Knowledge Base Management</b></td>
					<td style="background-color:#E6E68A;">Free</td>
					<td style="background-color:#E6E68A;">Free</td>
					<!--<td style="background-color:#E6E68A;">Not Applicable</td>-->
				</tr>
				<tr>
					<td>Unlimited Subjects</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<!--<td><img src="../images/cancel.png" width="16" height="16"></td>-->
				</tr>
				<tr>
					<td>Unlimited Topics</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<!--<td><img src="../images/cancel.png" width="16" height="16"></td>-->
				</tr>
				<tr>
					<td>Unlimited Qustions</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<!--<td><img src="../images/cancel.png" width="16" height="16"></td>-->
				</tr>
				<tr>
					<td>Single Question Entry form</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<!--<td><img src="../images/cancel.png" width="16" height="16"></td>-->
				</tr><br>
				<tr>
					<td>Bulk Question Upload via Excel</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<!--<td><img src="../images/cancel.png" width="16" height="16"></td>-->
				</tr>
				<tr>
					<td>Question Reconciling</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<!--<td><img src="../images/cancel.png" width="16" height="16"></td>-->
				</tr>
				<tr>
					<td>Option to set the difficutly level of question</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<!--<td><img src="../images/cancel.png" width="16" height="16"></td>-->
				</tr>
				<tr>
					<td>Option to explain answer of question</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<!--<td><img src="../images/cancel.png" width="16" height="16"></td>-->
				</tr>
				<tr>
					<td>PDF Export for all uploaded questions</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<!--<td><img src="../images/cancel.png" width="16" height="16"></td>-->
				</tr>
				<tr>
					<td>CSV Export for all uploaded questions</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<!--<td><img src="../images/cancel.png" width="16" height="16"></td>-->
				</tr>
				<tr style="color:blue">
					<td style="background-color:#E6E68A;"><b>Test Design &amp; Management</b></td>
					<td style="background-color:#E6E68A;">Free</td>
					<td style="background-color:#E6E68A;">Free</td>
					<!--<td style="background-color:#E6E68A;">Not Applicable</td>-->
				</tr>
				<tr>
					<td>Design and Save Unlimited Tests</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<!--<td><img src="../images/cancel.png" width="16" height="16"></td>-->
				</tr>
				<tr>
					<td>Passing Criteria based on Min&frasl;Max Cut-Off </td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<!--<td><img src="../images/cancel.png" width="16" height="16"></td>-->
				</tr>
				<tr>
					<td>Passing Criteria based on Topper Candidates</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<!--<td><img src="../images/cancel.png" width="16" height="16"></td>-->
				</tr>
				<tr>
					<td>Question Source (<?php echo(CConfig::SNC_SITE_NAME);?> &frasl; Personal) choice while Designing Test</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<!--<td><img src="../images/cancel.png" width="16" height="16"></td>-->
				</tr>
				<tr>
					<td>Result Analytics Visibility Control for End User (Candidate)</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<!--<td><img src="../images/cancel.png" width="16" height="16"></td>-->
				</tr>
				<tr>
					<td>M.C.P.A. ( Mastishka Cheating Prevention Algorithm) Based Tests</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<!--<td><img src="../images/cancel.png" width="16" height="16"></td>-->
				</tr>
				<tr>
					<td>Flash Question (MCPA Parameter)</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<!--<td><img src="../images/cancel.png" width="16" height="16"></td>-->
				</tr>
				<tr>
					<td>Lock Question (MCPA Parameter)</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<!--<td><img src="../images/cancel.png" width="16" height="16"></td>-->
				</tr>
				<tr>
					<td>Test Expiration Options</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<!--<td><img src="../images/cancel.png" width="16" height="16"></td>-->
				</tr>
				<tr>
					<td>Automatic Creation of Sections based on number of Section</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<!--<td><img src="../images/cancel.png" width="16" height="16"></td>-->
				</tr>
				<tr>
					<td>Automatic Distrubution of Subjects within Sections</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<!--<td><img src="../images/cancel.png" width="16" height="16"></td>-->
				</tr>
				<tr>
					<td>Unlimited Subject Limit in Test</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<!--<td><img src="../images/cancel.png" width="16" height="16"></td>-->
				</tr>
				<tr>
					<td>Unlimited Topic Limit in Test</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<!--<td><img src="../images/cancel.png" width="16" height="16"></td>-->
				</tr>
				<tr>
					<td>Detailed View of Designed Test</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<!--<td><img src="../images/cancel.png" width="16" height="16"></td>-->
				</tr>
				<tr>
					<td>Question Selection Based On Difficulty Level</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<!--<td><img src="../images/cancel.png" width="16" height="16"></td>-->
				</tr>
				<tr>
					<td>Attempt &frasl; Preview Test before Scheduling</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<!--<td><img src="../images/cancel.png" width="16" height="16"></td>-->
				</tr>
				<tr style="color:blue">
					<td style="background-color:#E6E68A;"><b>Test Scheduling</b></td>
					<td style="background-color:#E6E68A;">Free</td>
					<td style="background-color:#E6E68A;">Free</td>
					<!--<td style="background-color:#E6E68A;">Not Applicable</td>-->
				</tr>
				<tr>
					<td>Test Scheduling for Unlimited Candidates</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<!--<td><img src="../images/cancel.png" width="16" height="16"></td>-->
				</tr>
				<tr>
					<td>Test Details Preview Before Scheduling</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<!--<td><img src="../images/cancel.png" width="16" height="16"></td>-->
				</tr>
				<tr>
					<td>View Scheduled Test</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<!--<td><img src="../images/cancel.png" width="16" height="16"></td>-->
				</tr>
				<tr>
					<td>View Status of All Scheduled Tests</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<!--<td><img src="../images/cancel.png" width="16" height="16"></td>-->
				</tr>
				<tr style="color:blue">
					<td style="background-color:#E6E68A;"><b>Result Analytics</b></td>
					<td style="background-color:#E6E68A;">Free</td>
					<td style="background-color:#E6E68A;">Free</td>
					<!--<td style="background-color:#E6E68A;">Free</td>-->
				</tr>
				<tr>
					<td>Brief Result Viewing of all Candidates</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<!--<td><img src="../images/apply.png" width="16" height="16"></td>-->
				</tr>
				<tr>
					<td>Test DNA Analysis</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<!--<td>Conditional</td>-->
				</tr>
				<tr>
					<td>Result Inspection</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<!--<td>Conditional</td>-->
				</tr>
				<tr style="color:blue">
					<td style="background-color:#E6E68A;"><b>Test Packages</b></td>
					<td style="background-color:#E6E68A;">Not Applicable</td>
					<td style="background-color:#E6E68A;">Available</td>
					<!--<td style="background-color:#E6E68A;">Available</td>-->
				</tr>
				<tr>
					<td>Design &amp; Save Unlimited Number of Test Packages</td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<!--<td><img src="../images/cancel.png" width="16" height="16"></td>-->
				</tr>
				<tr>
					<td>Unlimited Number of Tests per Package</td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<!--<td><img src="../images/cancel.png" width="16" height="16"></td>-->
				</tr>
				<tr>
					<td>Sell &frasl; Trade Test Packages</td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<!--<td><img src="../images/cancel.png" width="16" height="16"></td>-->
				</tr>
				<tr>
					<td>Candidate Registration For Individual Package</td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<!--<td><img src="../images/apply.png" width="16" height="16"></td>-->
				</tr>
				<tr>
					<td>Options to choose test package among 15, 30, 45, 60, 90 days</td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<!--<td><img src="../images/apply.png" width="16" height="16"></td>-->
				</tr>
				<tr>
					<td>15 Days Subscription Cost</td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
					<td><b>India:</b> Rs. <?php echo(CConfig::INR_RATE_15_DAYS);?>* (INR)<br/> <b>Overseas:</b> $ <?php echo(CConfig::USD_RATE_15_DAYS);?>* (USD)</td>
					<!--<td>Price will be associated with<br/>Test Package</td>-->
				</tr>
				<tr>
					<td>30 Days Subscription Cost</td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
					<td><b>India:</b> Rs. <?php echo(CConfig::INR_RATE_30_DAYS);?>* (INR)<br/> <b>Overseas:</b> $ <?php echo(CConfig::USD_RATE_30_DAYS);?>* (USD)</td>
					<!--<td>Price will be associated with<br/>Test Package</td>-->
				</tr>
				<tr>
					<td>45 Days Subscription Cost</td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
					<td><b>India:</b> Rs. <?php echo(CConfig::INR_RATE_45_DAYS);?>* (INR)<br/> <b>Overseas:</b> $ <?php echo(CConfig::USD_RATE_45_DAYS);?>* (USD)</td>
					<!--<td>Price will be associated with<br/>Test Package</td>-->
				</tr>
				<tr>
					<td>60 Days Subscription Cost</td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
					<td><b>India:</b> Rs. <?php echo(CConfig::INR_RATE_60_DAYS);?>* (INR)<br/> <b>Overseas:</b> $ <?php echo(CConfig::USD_RATE_60_DAYS);?>* (USD)</td>
					<!--<td>Price will be associated with<br/>Test Package</td>-->
				</tr>
				<tr>
					<td>90 Days Subscription Cost</td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
					<td><b>India:</b> Rs. <?php echo(CConfig::INR_RATE_90_DAYS);?>* (INR)<br/> <b>Overseas:</b> $ <?php echo(CConfig::USD_RATE_90_DAYS);?>* (USD)</td>
					<!--<td>Price will be associated with<br/>Test Package</td>-->
				</tr>
				<tr style="color:blue">
					<td style="background-color:#E6E68A;"><b>Billing Management</b></td>
					<td style="background-color:#E6E68A;">Available</td>
					<td style="background-color:#E6E68A;">Available</td>
					<!--<td style="background-color:#E6E68A;">Available</td>-->
				</tr>
				<tr>
					<td>Live Billing information In My Account</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<!--<td><img src="../images/apply.png" width="16" height="16"></td>-->
				</tr>
				<tr>
					<td>Online Account Recharge</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<!--<td><img src="../images/apply.png" width="16" height="16"></td>-->
				</tr>
				<tr style="color:blue">
					<td style="background-color:#E6E68A;"><b>Attempt Test and Test Packages</b></td>
					<td style="background-color:#E6E68A;">Not Applicable</td>
					<td style="background-color:#E6E68A;">Not Applicable</td>
					<!--<td style="background-color:#E6E68A;">Available</td>-->
				</tr>
				<tr>
					<td>Attempt Available Tests (Free &amp; Scheduled)</td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
					<!--<td><img src="../images/apply.png" width="16" height="16"></td>-->
				</tr>
				<tr>
					<td>Attempt Available Test Packages (Free, Scheduled &amp; Paid)</td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
					<!--<td><img src="../images/apply.png" width="16" height="16"></td>-->
				</tr>
				<tr>
					<td>Resume Test (Session is Preserved)</td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
					<!--<td><img src="../images/apply.png" width="16" height="16"></td>-->
				</tr>
				<tr>
					<td>Fail-Safe (on Power Failuer or Web-Browser Crash)</td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
					<td><img src="../images/cancel.png" width="16" height="16"></td>
					<!--<td><img src="../images/apply.png" width="16" height="16"></td>-->
				</tr>
				<tr style="color:blue">
					<td style="background-color:#E6E68A;"><b>Personal Account Management</b></td>
					<td style="background-color:#E6E68A;">Available</td>
					<td style="background-color:#E6E68A;">Available</td>
					<!--<td style="background-color:#E6E68A;">Available</td>-->
				</tr>
				<tr>
					<td>Manage Personal Details</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<!--<td><img src="../images/apply.png" width="16" height="16"></td>-->
				</tr>
				<tr>
					<td>Account Security (Password Update/Reset)</td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<td><img src="../images/apply.png" width="16" height="16"></td>
					<!--<td><img src="../images/apply.png" width="16" height="16"></td>-->
				</tr>
			</tbody>
			<thead>
				<tr style="background-color:#80804C;color:GhostWhite;font: 150% 'Trebuchet MS', sans-serif;font-weight:bold">
					<th style="text-align:center;">Features</th>
					<th>Corporate</th>
					<th>Institutional</th>
					<!--<th>Individual</th>-->
				</tr>
			</thead>
		</table>
		<ul style="color:red;font-weight:bold">
			<li>Price mentioned is inclusive of all taxes.</li>
			<li>Corporate &frasl; Institutes whose registered offices are in India are requested to pay in INR (Indian Rupees).</li> 
			<li>Overseas Clients (other than India) are requested to pay in USD (US Dollars).</li>
			<li>We will not accept recharge amount in INR (currency) from clients other than India.</li>
		</ul><br/><br/>
		
		<div class="img_modal"></div>
	</body>
</html>