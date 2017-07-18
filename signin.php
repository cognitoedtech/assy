<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	include_once(dirname(__FILE__)."/lib/include_js_css.php");
	include_once("lib/session_manager.php");
	include_once("lib/site_config.php"); 
	include_once("lib/utils.php");
	include_once("database/config.php");
	
	$page_id = CSiteConfig::HF_INDEX_ID;
	$login = CSessionManager::Get(CSessionManager::BOOL_LOGIN);
	
	$parsAry = parse_url(CUtils::curPageURL());
	$qry = split("[=&]", $parsAry["query"]);
	
	if($login)
	{
		CUtils::Redirect("core/dashboard.php");
	}
	else if(CSiteConfig::DEBUG_SITE == true && stristr($parsAry["host"], strtolower(CConfig::SNC_SITE_NAME).".com") == FALSE)
	{
		if($qry[0] != "debug" && $qry[1] != "639")
		{
			CUtils::Redirect(CSiteConfig::ROOT_URL, true);
		}
	}
	
	/*echo "Login Name: ".$_GET['ln']."<br/>";
	echo("<pre>");
	print_r($qry);
	echo("</pre>");*/
	
	$objIncludeJsCSS = new IncludeJSCSS();
?>
<html>
	<head>
		<title><?php echo(CConfig::SNC_SITE_NAME." - ".CConfig::SNC_PUNCH_LINE); ?></title>
		<style type="text/css">
			td.tdcolor
			{
				border-bottom: solid 1px black;
				padding-bottom:15px;
			}
			a.anchor:link {color:GhostWhite;}    /* unvisited link */
			a.anchor:visited {color:GhostWhite;} /* visited link */
			a.anchor:hover {color:GhostWhite;}   /* mouse over link */
			a.anchor:active {color:GhostWhite;}  /* selected link */
			a:focus {outline: none;}
			
			.modal1 {
				display:    none;
				position:   fixed;
				z-index:    1000;
				top:        50%;
				left:       50%;
				height:     100%;
				width:      100%;
			}
		</style>
		<?php 
			//$objIncludeJsCSS->IncludeBootstrapCSS("");
			$objIncludeJsCSS->IncludeBootstrap3_1_1Plus1CSS("");
			$objIncludeJsCSS->IncludeBootswatch3_1_1Plus1LessCSS("");
			$objIncludeJsCSS->IncludeMetroBootstrapCSS("");
			$objIncludeJsCSS->IncludeMipcatCSS("");
			$objIncludeJsCSS->IncludeFuelUXCSS("");
			$objIncludeJsCSS->CommonIncludeJS("","1.8.2");
			$objIncludeJsCSS->IncludeJqueryFormJS("");
			$objIncludeJsCSS->IncludeJqueryValidateMinJS("");
		?>
	</head>
	<body style="margin: 5px;">
		<!--For Facebook Like Button-->
		<div id="fb-root"></div>
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>
		<!--For Facebook Like Button-->
		
		<!-- Header -->
		<?php
			include("lib/header.php");
			$bShowCKEditor = FALSE;
		?>
		<br />
		<br />
		<br />
		<div class="container">
			<div class="row-fluid">
				<div class="fuelux modal1">
					<div class="preloader"><i></i><i></i><i></i><i></i></div>
				</div>
				<div id="demoRequest" class="modal">
  					<div class="modal-dialog">
    					<div class="modal-content">
    						<form class="form-horizontal" id="REQUESTFORM" name="REQUESTFORM" action="core/ajax/ajax_demo_req_exec.php"  method="POST">
	      						<div class="modal-header">
	       	 						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	        						<h4 class="modal-title">Request <?php echo(CConfig::SNC_SITE_NAME);?> Demo</h4>
	      						</div>
	      						<div class="modal-body">
	      							<div id="demo_form_content">
										<div class="form-group">
									    	<label for="NAME" class="col-lg-4 control-label">Name<span style='color: red;'>*</span> :</label>
									    	<div class="col-lg-6">
									    		<input class="form-control" id="NAME" name="NAME" type="text" />
									    	</div>
									    </div>
									    <div class="form-group">
									    	<label for="EMAIL" class="col-lg-4 control-label">Your Email<span style='color: red;'>*</span> :</label>
									    	<div class="col-lg-6">
									    		<input class="form-control" id="EMAIL" name="EMAIL" type="text" />
									    	</div>
									    </div>
									    <div class="form-group">
									    	<label for="CONTACT" class="col-lg-4 control-label">Contact#<span style='color: red;'>*</span> :</label>
									    	<div class="col-lg-6">
									    		<input class="form-control" id="CONTACT" name="CONTACT" type="text" />
									    	</div>
									    </div>
									    <div class="form-group">
									      <label for="ORG_TYPE" class="col-lg-4 control-label">Organization Type<span style='color: red;'>*</span> :</label>
									      <div class="col-lg-6">
									        <select class="form-control" name="ORG_TYPE" id="ORG_TYPE">
									        	<option value="">--Select Organization--</option>
								 				<?php 
												foreach(CConfig::$ORG_TYPE_ARY as $org_type_id=>$org_type_name)
													printf("<option value='%s'>%s</option>", $org_type_name, $org_type_name);
												?>
									        </select>
									      </div>
									    </div>
									    <div class="form-group" id="OTHER_ORG_DIV" style="display:none;">
			      							<label class="col-lg-4 control-label"></label>
			      							<div class="col-lg-6">
			        							<input class="form-control" name="OTHER_ORG" id="OTHER_ORG" placeholder="Please Specify Other Here" type="text"/>
			      							</div>
			   							 </div>
									    <div class="form-group">
									    	<label for="ORG_NAME" class="col-lg-4 control-label">Organization Name<span style='color: red;'>*</span> :</label>
									    	<div class="col-lg-6">
									    		<input class="form-control" id="ORG_NAME" name="ORG_NAME" type="text" />
									    	</div>
									    </div>
									    <div class="form-group">
									      <label for="USAGE" class="col-lg-4 control-label">Monthly Tests<span style='color: red;'>*</span> :</label>
									      <div class="col-lg-6">
									        <select class="form-control" name="USAGE" id="USAGE">
									        	<option value="" >--Select Usage--</option>
												<option value="Less than 500" >Less than 500</option>
												<option value="501 - 1000" >501 - 1000</option>
												<option value="1,001 - 5,000" >1,001 - 5,000</option>
												<option value="5,001 - 10,000" >5,001 - 10,000</option>
												<option value="10,000 and more" >10,000 and more</option>
									        </select>
									      </div>
									    </div>
									    <div class="form-group">
									    	<label for="SUBJECT" class="col-lg-4 control-label">Subject :</label>
									    	<div class="col-lg-6">
									    		<input class="form-control" id="SUBJECT" name="SUBJECT" type="text"  value='Request for <?php echo(CConfig::SNC_SITE_NAME);?>.com Demo !' readonly/>
									    	</div>
									    </div>
									    <div class="form-group">
									      <label for="MESSAGE" class="col-lg-4 control-label">Message<span style='color: red;'>*</span> :</label>
									      <div class="col-lg-6">
									        <textarea class="form-control" rows="3" id="MESSAGE" name="MESSAGE"></textarea>
									      </div>
									    </div>
									    <div class="form-group">
									    	<label for="VERIF_CODE" class="col-lg-4 control-label">Verify Text<span style='color: red;'>*</span> :</label>
									    	<div class="col-lg-4">
									    		<input class="form-control" id="VERIF_CODE" name="VERIF_CODE" type="text" />
									    	</div>
									    	<div class="col-lg-2" style="position:relative;top:7px;">
									    		<img id="captcha_img_demo" src="">
									    	</div>
									    </div>
								    </div>
								    <div id="demo_response" style="display:none;">
									</div>
	      						</div>
	      						<div class="modal-footer">
		        					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		        					<button type="submit" id="demo_submit_btn" class="btn btn-primary">Send Request</button>
	      						</div>
	      					</form>
    					</div>
  					</div>
				</div>
			</div>
		</div>
		<div class="container" style="width: 100%;">
			<div class="row-fluid">
				<div class="col-md-8" style="width: 75%;">
					<div style="width: 85%;">
						<div class="drop-shadow raised" style="width : 900px;">
							<a class="anchor" href="pricing.php"><img src="images/home_pg_img.jpg" width="929" height="330" border="0" alt=""/></a>
						</div>
					</div>
				</div>
				<div class="col-md-2" style="width: 25%;">
					<br />
					<div style="border-left: 1px solid #ddd; text-align: center;">
						<IFRAME WIDTH="270" HEIGHT="270" SRC="login/login_form.php?ln=<?php echo(urlencode($login_name));?>" NAME="LOGIN_FRAME" ID="LOGIN_FRAME" SCROLLING="NO" MARGINWIDTH="0" MARGINHEIGHT="0" FRAMEBORDER="0" HSPACE="0" VSPACE="0"></IFRAME><br/><br/>
						<a align="center" onclick="LaunchRequestedModal();" href="#" id="corporate_id" role="button" class="btn btn-danger"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Request a Demo ! &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></a>
						<BR/>
						<BR/>
						<center>
							<A HREF="login/forgot.php">Click here</A> to <A HREF="login/forgot.php"><b>Recover your Password</b></A>.<br/><br/>
							<div class="fb-like" data-href="http://www.facebook.com/ezeeasses" data-send="true" data-layout="button_count" data-width="250" data-show-faces="true"></div><br/><hr/>
							<!-- <A class="anchor" HREF="login/subs_plans.php"><IMG SRC="images/reg_now.jpg" WIDTH="180" HEIGHT="58" BORDER="0" ALT=""></A><br/><br/><img src="images/blue_up_arrow.jpg" width="65" height="65" border="0" alt=""/> -->
						</center>
						<BR/>
						<p id="browser_msg_tbl" style="color:OrangeRed; display: none;"><b>Due to high use of Web 2.0 (browser based) features, we are not supporting IE versions less than 10 (Internet Explorer, version 10) at the moment. <?php echo(CConfig::SNC_SITE_NAME);?> team recommend to use browsers like <a href="https://www.google.com/intl/en/chrome/browser/" target="_blank">Google Chrome</a> or <a href="http://www.mozilla.org/en-US/firefox/new/" target="_blank">Mozilla Firefox</a> for best performance.<br/><br/>Please install modern browser and then login to your account.</b></p>
						<BR/>
						<BR/>
						<BR/>				
					</div>
				</div>
			</div>
			<?php 
			include("lib/footer.php");
			?>
			<div class='img_modal'>
			</div>
		</div>
		<script type="text/javascript">
			$("#btn_reg_contributor", "body" ).button();
			/*$("#btn_reg_contributor").click(function(){
				window.location = "login/register-contrib.php";
			});*/
			
			$(window).load(function() {
			        //$('#slider').nivoSlider();
			        
			        var b_version = parseInt($.browser.version, 10);
			        if($.browser.msie && b_version < 10)
			        {
			        	$("#login_tbl").hide();
			        	$("#browser_msg_tbl").show();
			        }
			        else
			        {
			        	$("#browser_msg_tbl").hide();
			        	$("#login_tbl").show();
			        }
			    });

			$("#ORG_TYPE").change(function(){
				if($("#ORG_TYPE").val() == "<?php echo(CConfig::$ORG_TYPE_ARY[CConfig::OT_OTHER]);?>")
				{
					$("#OTHER_ORG_DIV").show();
				}
				else
				{
					$("#OTHER_ORG_DIV").hide();
				}
			});

			function showResponse(responseText, statusText, xhr, form)
			{
				$(".modal1").hide();
				$("#demo_form_content").hide();
				$("#demo_response").html(responseText);
				$("#demo_response").show();
				$("#demo_submit_btn").hide();
				$("#demoRequest").modal();	 
			}

			var options = { 
		       	 	//target:        '',   // target element(s) to be updated with server response 
		       		// beforeSubmit:  showRequest,  // pre-submit callback 
		      	 	 success:       showResponse,  // post-submit callback 
		 
		        	// other available options: 
		        	url:      'core/ajax/ajax_demo_req_exec.php',         // override for form's 'action' attribute 
		        	type:      'POST',       // 'get' or 'post', override for form's 'method' attribute 
		        	//dataType:  null        // 'xml', 'script', or 'json' (expected server response type) 
		        	clearForm: true        // clear all form fields after successful submit 
		        	//resetForm: true        // reset the form after successful submit 
		 
		        	// $.ajax options can be used here too, for example: 
		        	//timeout:   3000 
		    	};
			
			$(document).ready(function() {
				$("#REQUESTFORM").validate({
		    		rules: {
		        		NAME: {
		            		required:true,
		       		 		minlength: 2
		        		},
		            	EMAIL: {
		            		required: true,
		            		email: true
		        		},
		        		CONTACT:{
		        			required:true,
		           	 		number: true,
		           		},
		        		ORG_TYPE:{
		        			required:true,
		               	},
		               	OTHER_ORG:{
		        			required:true,
		               	},
		               	ORG_NAME:{
		               		required:true,
		               	},	
		            	USAGE:{
		            		required:true,
		           		 },
		            	MESSAGE:{
		                	required:true,
		             	},
		            	VERIF_CODE:"required"
		    		},
		    		messages: {
		    			NAME: {	
		    				required:	"<span style='color:red'>* Please enter your name</span>",
		    				minlength:	"<span style='color:red'>* Minimum length of name should be 2</span>"
		        		},
		        		EMAIL:{
							required:	"<span style='color:red'>* Email id is required</span>",
							email:		"<span style='color:red'>* Please enter a valid email address</span"
						},	
						CONTACT:{
							required:	"<span style='color:red;'>* Please enter your contact no.</span>",
		        	 		number:		"<span style='color:red;'>* contact number must contain digits only</span>"
						},
						ORG_TYPE:{
							required:	"<span style='color:red;'>* Please select organization Type</span>",
			            },
			            OTHER_ORG:{
							required:	"<span style='color: red;'>* Please specify the other organization type</span>",
			            },
			            ORG_NAME:{
							required:	"<span style='color:red;'>* Please enter organization name</span>",
		               	},    
		               	USAGE:{
							required:	"<span style='color:red;'>* Please select your monthly usage</span>",
						},
						MESSAGE:{
							 required:	"<span style='color:red;'>* Please provide a message</span>",
						},
						VERIF_CODE:			"<span style='color:red;'>* Please enter the code shown in image</span>"
			    	},
		    		submitHandler: function(form) {
		    			$('#demoRequest').modal('hide');
		    			$(".modal1").show();
		    			$('#REQUESTFORM').ajaxSubmit(options);
		    		}
				});
			});

		    function LaunchRequestedModal()
		    {
		    	$("#demo_response").hide();
				$("#demo_form_content").show();
				$("#demo_submit_btn").show();
				$("#REQUESTFORM").validate().resetForm();
				$('#captcha_img_demo').attr('src','3rd_party/captcha/captcha.php?r=' + Math.random());
				$('#demoRequest').modal();
			}
		</script>
	</body>
</html>