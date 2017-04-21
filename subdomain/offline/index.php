<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	include_once(dirname(__FILE__)."/import/create_db.php");
	include_once(dirname(__FILE__)."/lib/include_js_css.php");
	include_once("lib/session_manager.php");
	include_once("lib/site_config.php"); 
	include_once("lib/utils.php");
	include_once("database/config.php");
	
	$objCreateDB = new CCreateDB();
	
	$objCreateDB->CreateOfflineDatabase();
	
	$setting_file_contents = file_get_contents(dirname(__FILE__)."/../../settings.json");
	
	$setting_ary = json_decode($setting_file_contents, true);
	
	if(CSiteConfig::ROOT_URL != "http://".$setting_ary['web_server']['listen_on'][0].":5087")
	{
		$site_config_file_contents = file_get_contents(dirname(__FILE__)."/lib/site_config.php");
		$pattern = '/(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)(\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)){3}/';
		$new_site_config_file_contents = preg_replace($pattern, $setting_ary['web_server']['listen_on'][0], $site_config_file_contents);
		file_put_contents(dirname(__FILE__)."/lib/site_config.php", $new_site_config_file_contents);
	}
	
	$page_id = CSiteConfig::HF_INDEX_ID;
	$login = CSessionManager::Get(CSessionManager::BOOL_LOGIN);
	
	$parsAry = parse_url(CUtils::curPageURL());
	$qry = split("[=&]", $parsAry["query"]);
	
	if($login)
	{
		$user_type = CSessionManager::Get(CSessionManager::INT_USER_TYPE);
		
		if($user_type != CConfig::UT_INDIVIDAL)
		{
			CUtils::Redirect("core/test-admin/manage_test.php");
		}
		else
		{
			CUtils::Redirect("core/candidate/start_test.php");
		}
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
		<title><?php echo(CConfig::SNC_SITE_NAME." Offline - ".CConfig::SNC_PUNCH_LINE); ?></title>
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
		?>
		<br />
		<br />
		<br />
		<div class="container" style="width: 100%;">
			<div class="row-fluid">
				<div class="col-md-8" style="width: 75%;">
					<div style="width: 85%;">
						<div class="drop-shadow raised">
							<a class="anchor" href="#"><img src="images/home_pg_img.jpg" width="929" height="330" border="0" alt=""/></a>
						</div>
					</div>
				</div>
				<div class="col-md-2" style="width: 25%;">
					<br />
					<div style="border-left: 1px solid #ddd; text-align: center;">
						<IFRAME WIDTH="270" HEIGHT="270" SRC="login/login_form.php?ln=<?php echo(urlencode($login_name));?>" NAME="LOGIN_FRAME" ID="LOGIN_FRAME" SCROLLING="NO" MARGINWIDTH="0" MARGINHEIGHT="0" FRAMEBORDER="0" HSPACE="0" VSPACE="0"></IFRAME><br/><br/>
						<BR/>
						<p id="browser_msg_tbl" style="color:OrangeRed; display: none;"><b>Due to high use of Web 2.0 (browser based) features, we are not supporting IE versions less than 10 (Internet Explorer, version 10) at the moment. <?php echo(CConfig::SNC_SITE_NAME);?> team recommend to use browsers like <a href="https://www.google.com/intl/en/chrome/browser/" target="_blank">Google Chrome</a> or <a href="http://www.mozilla.org/en-US/firefox/new/" target="_blank">Mozilla Firefox</a> for best performance.<br/><br/>Please install modern browser and then login to your account.</b></p>
						<a class="btn btn-danger" role="button" href="login/otfa-reg-form.php" align="center"><b>&nbsp;&nbsp; Register As A Candidate ! &nbsp;&nbsp;</b></a>				
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
		</script>
	</body>
</html>