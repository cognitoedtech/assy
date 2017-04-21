<?php
	$cache_expire = 60*60*24*365;
	header("Pragma: public");
	header("Cache-Control: max-age=".$cache_expire);
	header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$cache_expire) . ' GMT');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	include_once(dirname(__FILE__)."/../../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../../database/config.php");
	include_once(dirname(__FILE__)."/../../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/../../../lib/site_config.php");
	include_once(dirname(__FILE__)."/../../../lib/utils.php");
	include_once(dirname(__FILE__)."/../../../lib/billing.php");
	include_once("lib/fb_wrapper.php");
	include_once("lib/header.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	//CSessionManager::OnSessionExpire(false);
	// - - - - - - - - - - - - - - - - -
	
	$page_id =CSiteConfig::HF_DASHBOARD;
	$login = CSessionManager::Get(CSessionManager::BOOL_LOGIN);
	$login_name = CSessionManager::Get(CSessionManager::STR_USER_NAME);
	
	/*echo ("<pre>");
	print_r($_SESSION);
	echo ("</pre>");*/
	
	$user_type  = 0;//CSessionManager::Get(CSessionManager::INT_USER_TYPE);
	$user_email	= "manish.mastishka@gmail.com";//CSessionManager::Get(CSessionManager::STR_EMAIL_ID);
	$user_id	= "ef7bd85d-778d-bd24-f5d1-e233a9a2df0c";//CSessionManager::Get(CSessionManager::STR_USER_ID);
	
	$UserTypeTextAry = array("Super User", "Institutional", "Corporate", "Candidate", "Contributor", "Verifier", "Business Associate","Coordinotor");
	
	$bDemoAcnt = false;
	$sScriptCode = "";
	if(array_search($user_email, CConfig::$reserved_emails) !== FALSE)
	{
		$bDemoAcnt = true;
		
		$tto_sunday = strtotime("next Sunday") - time();
		$sScriptCode = sprintf("var bDemoTour=1; var tto_sunday=%s;", $tto_sunday);
	}
	else 
	{
		$sScriptCode = sprintf("var bDemoTour=0;");
	}
	
	$tour_prev = "";
	$tour_next = "";
	
	$objDB = new CMcatDB();
	$objBilling = new CBilling();
	
	$permissions 		= 65536;
	$owner_type         = null;//$objDB->GetUserType($objDB->GetOwnerId($user_id));
	//echo $permissions;
	$permitted_all 		= true;
	$MNG_QUES			= false;
	$TST_DSG_WZD		= false;
	$REG_CAND			= false;
	$SCD_TEST			= false;
	$TRD_PKG			= false;
	$BRIEF_RESULT		= false;
	$TST_DNA			= false; 
	$PROD_CUSTM_RESULT	= false;
	$SNEEK_PEEK			= false;
	$RESULT_INSPCTN     = false;
	$RESULT_ANALYTICS	= false;
	
	$objFB = new CFBWrapper();
	
	$info = null;
	if($objFB->CheckLoginStatus($info))
	{
		echo("<pre>");
		print_r($info);
		echo("</pre>");
	}
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>MIpCAT - <?php echo($UserTypeTextAry[$user_type]); ?>  Account (<?php echo($user_email); ?>)</title>
		<link rel="stylesheet" type="text/css" href="../../../css/mipcat.css" />
		<link rel="stylesheet" type="text/css" href="../../../css/jquery-jvert-tabs-1.1.4.css" />
		<link rel="stylesheet" type="text/css" href="../../../lib/bootstrap/css/bootstrap.css" />
		<link rel="stylesheet" type="text/css" href="../../../lib/guiders/guiders-1.2.8.css" />
		<link rel="stylesheet" type="text/css" href="../../../css/glossymenu.css" />
		<link rel="stylesheet" type="text/css" href="../../../css/stats_box.css" />
		
		<script type="text/javascript" src="../../../js/jquery.js"></script>
		<script type="text/javascript" src="../../../lib/bootstrap/js/bootstrap.js"></script>
		<script type="text/javascript" src="../../../js/jquery-jvert-tabs-1.1.4.js"></script>
		<script type="text/javascript" src="../../../lib/guiders/guiders-1.2.8.js"></script>
		<script type="text/javascript" src="../../../js/ddaccordion.js"></script>
		<script type="text/javascript" charset="utf-8" src="../../../js/mipcat/utils.js"></script>
		<link rel="stylesheet" type="text/css" href="../../../css/jq_acc_menu_style.css" />
		<style>
			#overlay { position: fixed; left: 0px; top: 0px; width: 100%; height: 100%; z-index: 100; background-color:white;}
			a.anchor:link {color:GhostWhite;}    /* unvisited link */
			a.anchor:visited {color:GhostWhite;} /* visited link */
			a.anchor:hover {color:GhostWhite;}   /* mouse over link */
			a.anchor:active {color:GhostWhite;}  /* selected link */
			a:focus {outline: none;}
			
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
			div.vnav_menu, div.vnav_menu div, div.vnav_menu div div {
				height: 100%;
			}
		</style>
	</head>
	<body style="font: 75% 'Trebuchet MS', sans-serif; margin: 5px;">
		<div id="fb-root"></div>
	    <script src="//connect.facebook.net/en_US/all.js"></script>
	    
	    <script type="text/javascript">
	      
	      // Initialize the Facebook JavaScript SDK
	      FB.init({
	        appId: '535079056529152',
	        xfbml: true,
	        status: true,
	        cookie: true,
	      });
	      
	      // Check if the current user is logged in and has authorized the app
	      FB.getLoginStatus(checkLoginStatus);
	      
	      // Login in the current user via Facebook and ask for email permission
	      function authUser() {
	        FB.login(checkLoginStatus, {scope:'email'});
	      }
	      
	      // Check the result of the user status and display login button if necessary
	      function checkLoginStatus(response) 
	      {
	        if(response && response.status == 'connected') 
	        {
	          //alert('User is authorized');
	          FB.api('/me/picture', function(response) {
	          	$('#mip_fb_user_pic').html("<img src='"+response.data.url+"'/>");
			  });
			  
	          FB.api('/me', function(response) {
	          	$('#mip_fb_user_name').html("<span>"+response.name+"</span>");
			  });
	          // Now Personalize the User Experience
	          console.log('Access Token: ' + response.authResponse.accessToken);
	        } 
	        else 
	        {
	          //alert('User is not authorized');
	          
	          authUser();
	        }
	      }
	     
	    </script>
		
		<div id="overlay" style="display:none">
			<iframe id="overlay_frame" src="#" width="100%" height="100%"></iframe>
		</div>
		
		<!-- Header Start-->
		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font:inherit;<?php echo(($page_id == CSiteConfig::HF_PLANS)?"position:fixed;top:0;":"");?>">
			<tr align="right">
				<td style="text-align:center;background-color:CornflowerBlue;padding:5px;font: 160% 'Trebuchet MS', sans-serif; -moz-border-bottom-left-radius: 5px;-webkit-border-bottom-left-radius: 5px;-khtml-border-bottom-left-radius: 5px;border-bottom-left-radius: 5px;">
					<div style="width:160px;"><a href="<?php echo (CSiteConfig::ROOT_URL.'/'.$login_name);?>" style="text-decoration: none;color:GhostWhite;text-shadow: -1px -1px 1px #fff, 1px 1px 1px #000;opacity: 1.0;"><b><?php echo(CAppConfig::APP_LOGO_NAME);?></b></a><br/><span style="color:GhostWhite;font:50% 'Trebuchet MS', sans-serif;"><b><?php echo(CAppConfig::APP_PLINE);?></b></span></div>
				</td>
				<td style="background-color:CornflowerBlue;padding:10px;filter: progid:DXImageTransform.Microsoft.gradient(GradientType='1', startColorstr='CornflowerBlue', endColorstr='#F0F4FD');background: -webkit-gradient(linear, left top, right top, from(CornflowerBlue), to(#F0F4FD));background: -moz-linear-gradient(left,  CornflowerBlue,  #F0F4FD);" WIDTH="100%">
					<span id='hmenu_plans' class='btn btn-mini btn-danger'><i class='icon-list icon-white'></i> <b>Get Premium Account!</b></span>
					<span id='hmenu_help' style="text-align:left;" class="dropdown">
						<a style="color:GhostWhite;text-decoration:none;" class="btn btn-mini btn-mipcat dropdown-toggle" id="dLabel" role="button" data-toggle="dropdown" data-target="#" ><i class='icon-hand-right icon-white'></i> <b>Getting Started</b> <b class="caret"></b> </a>
						<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
							<li><a href="<?php echo CSiteConfig::ROOT_URL;?>/core/help_manual/gs_help_inst.php"><b>Help Manual :</b> Institutes / Colleges</a></li>
							<li><a href="<?php echo CSiteConfig::ROOT_URL;?>/core/help_manual/gs_help_corp.php"><b>Help Manual :</b> Corporate / Employer</a></li>
							<li><a href="<?php echo CSiteConfig::ROOT_URL;?>/core/help_manual/gs_help_indv.php"><b>Help Manual :</b> Individual / Personal</a></li>
							<li class="divider"></li>
							<li><a href="<?php echo CSiteConfig::ROOT_URL;?>/core/help_manual/gs_help_contrib.php"><b>Help Manual :</b> Contributor</a></li>
							<li class="divider"></li>
							<li><a href="<?php echo CSiteConfig::ROOT_URL;?>/sample_report.pdf"><b>Sample Result Analysis</b></a></li>
						</ul>
					</span>
					<span id='hmenu_terms' style="text-align:left;" class="dropdown">
						<a style="color:GhostWhite;text-decoration:none;" class="btn btn-mini btn-mipcat dropdown-toggle" id="dLabel" role="button" data-toggle="dropdown" data-target="#"><i class='icon-fire icon-white'></i> <b>Terms</b> <b class="caret"></b> </a>
						<ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dLabel">
							<li><a href="<?php echo CSiteConfig::ROOT_URL;?>/core/terms/mipcat_terms.php">Terms of Service</a></li>
							<li><a href="<?php echo CSiteConfig::ROOT_URL;?>/core/terms/contrib_terms.php">Terms of Contribution</a></li>
							<li class="divider"></li>
							<li><a href="<?php echo CSiteConfig::ROOT_URL;?>/core/terms/privacy_policy.php">Privacy Policy</a></li>
						</ul>
					</span>
					<span id='hmenu_abt' style="text-align:left;" class="dropdown">
						<a style="color:GhostWhite;text-decoration:none;" class="btn btn-mini btn-mipcat dropdown-toggle" id="dLabel" role="button" data-toggle="dropdown" data-target="#" data-dropdown="#dropdown-1"><i class='icon-leaf icon-white'></i> <b>About</b> <b class="caret"></b> </a>
						<ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dLabel">
							<li><a href="<?php echo CSiteConfig::ROOT_URL;?>/aboutus.php">About MIpCAT</a></li>
							<li class="divider"></li>
							<li><a href="<?php echo CSiteConfig::ROOT_URL;?>/contact_us.php">Contact Us</a></li>
						</ul>
					</span>
				</td>
			</tr>
		</table>
		<!-- Header End-->
		
		<div id="loading_bar" style="text-align:center;position:relative;top:100px;display:none;">
			<img src="images/loading-bar.gif" width="212" height="60"/>
		</div>
		<table width="100%" height="100%" id="vtabs">
			<tr>
				<td valign="top">
					<div class="glossymenu" style="float:left">
						<a id="tour_dashboard" class="menuitem" href="javascript:" onclick='objUtils.IFLoadPage("core/dashboard.php");'>Dashboard</a>
						<a id="tour_tdwiz" class="menuitem" href="javascript:" onclick='objUtils.IFLoadPage("core/challenge_friends.php");'>Challenge Friends</a>
						<a id="tour_sneek_peek" class="menuitem" href="javascript:" onclick='objUtils.IFLoadPage("core/explore_qb.php");'>Explore Question Bank</a>
						<a id="tour_myacc" class="menuitem" href="javascript:" onclick='objUtils.IFLoadPage("core/recharge.php");'>Recharge</a>
						<a id="tour_tdwiz" class="menuitem" href="javascript:" onclick='objUtils.IFLoadPage("core/practice_subjects.php");'>Practice Subjects</a>
						<a id="tour_tdwiz" class="menuitem" href="javascript:" onclick='objUtils.IFLoadPage("core/mock_exams.php");'>Mock Exams</a>
						<a id="tour_tdwiz" class="menuitem" href="javascript:" onclick='objUtils.IFLoadPage("core/placement_prep.php");'>Placement Preprations</a>
						<a id="tour_reslt_ana" class="menuitem submenuheader" href="javascript:">Result Analytics</a> 
						<div class="submenu">
							<ul>
								<li><a href="javascript:" onclick='objUtils.IFLoadPage("core/result_analytics_brief.php");'>Brief Result</a></li>
								<li><a href="javascript:" onclick='objUtils.IFLoadPage("core/result_analytics_detailed.php");'>Test DNA Analysis</a></li>
							</ul>
						</div>
						<a id="tour_tdwiz" class="menuitem" href="javascript:" onclick='objUtils.IFLoadPage("core/result_inspection.php");'>Result Inspection</a>
						<a id="tour_tdwiz" class="menuitem" href="javascript:" onclick='objUtils.IFLoadPage("core/suggestions.php");'>Suggestions</a>
						<div id="stats" style="color:white;height:150px;border:1px solid #aaa;background-color:CornflowerBlue;padding:10px;-moz-border-radius: 5px;-webkit-border-radius: 5px;-khtml-border-radius: 5px;border-radius: 5px;box-shadow: 0 0 .05em rgba(0,0,0,0.5);-moz-box-shadow: 0 0 .05em rgba(0,0,0,0.5);-webkit-box-shadow: 0 0 .05em rgba(0,0,0,0.5);">
						<div style="width:100%;text-align:center;font-weight:bold;" id="mip_fb_user_pic">
							
						</div><br/>
						<div style="width:100%;text-align:center;font-weight:bold;" id="mip_fb_user_name"></div>
					</div>
					</div>
				</td>
				<td valign="top" width="100%" height="100%">
					<iframe name="if_platform" id="platform" width="100%" height="100%" style="border:1px solid #aaa;" src="" frameborder="0"></iframe>
				</td>
			</tr>
			<tr>
				<td colspan="3" style="text-align:center;">All Rights Reserved <br/> <b>Copyright</b> &copy; <b><?php echo(date('Y')); ?>, Mastishka Intellisys Private Limited</b></td>
			</tr>
		</table>
		
		<script type="text/javascript">
			<?php echo($sScriptCode);?>
			
			function ShowOverlay(url, div_id)
			{
				var current_date = new Date();
			    var time_zone = -current_date.getTimezoneOffset() / 60;
			    
				$("#overlay_frame").attr("src",url+"&time_zone"+time_zone).ready(function(){
					$("#overlay").show(800);
					$("body").css("overflow", "hidden");
				});
				
				RemoveTest.div_id = div_id;
			}
			
			function HideOverlay()
			{
				$("#overlay").hide(500);
				$("body").css("overflow", "auto");
			}
			
			function RemoveTest()
			{
				window.if_platform.TestOver(RemoveTest.div_id);
			}
			
			window.onload = function() {
				$("#loading_bar").hide();
				$("#vtabs").show();
				objUtils.IFLoadPage("core/dashboard.php");
				
				
			 
			$('.dropdown-toggle').dropdown();
			
			ddaccordion.init({
				headerclass: "submenuheader", //Shared CSS class name of headers group
				contentclass: "submenu", //Shared CSS class name of contents group
				revealtype: "click", //Reveal content when user clicks or onmouseover the header? Valid value: "click", "clickgo", or "mouseover"
				mouseoverdelay: 200, //if revealtype="mouseover", set delay in milliseconds before header expands onMouseover
				collapseprev: true, //Collapse previous content (so only one open at any time)? true/false 
				defaultexpanded: [], //index of content(s) open by default [index1, index2, etc] [] denotes no content
				onemustopen: false, //Specify whether at least one header should be open always (so never all headers closed)
				animatedefault: false, //Should contents open by default be animated into view?
				persiststate: true, //persist state of opened contents within browser session?
				toggleclass: ["", ""], //Two CSS classes to be applied to the header when it's collapsed and expanded, respectively ["class1", "class2"]
				togglehtml: ["suffix", "<img src='images/acc_vmenu/plus.png' class='statusicon' />", "<img src='images/acc_vmenu/minus.png' class='statusicon' />"], //Additional HTML added to the header when it's collapsed and expanded, respectively  ["position", "html1", "html2"] (see docs)
				animatespeed: "normal", //speed of animation: integer in milliseconds (ie: 200), or keywords "fast", "normal", or "slow"
				oninit:function(headers, expandedindices){ //custom code to run when headers have initalized
					if_platform=window.frames["if_platform"]
					if (expandedindices.length>0) //if there are 1 or more expanded headers
					{
						//if_platform.location.replace(headers[expandedindices.pop()].getAttribute('href')) //Get "href" attribute of final expanded header to load into IFRAME
					}
				},
				onopenclose:function(header, index, state, isuseractivated){ //custom code to run whenever a header is opened or closed
					if (state=="block" && isuseractivated==true){ //if header is expanded and as the result of the user initiated action
						//if_platform.location.replace(header.getAttribute('href'))
					}
				}
			});
		</script>
	</body>
</html>