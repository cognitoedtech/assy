<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	include_once("../lib/session_manager.php");
	include_once('../database/mcat_db.php');
	include_once("lib/test_helper.php");
	include_once("../lib/site_config.php");
	include_once("../lib/utils.php");
	
	$objDB = new CMcatDB();
	
	$user_id   = "";
	$user_type = CConfig::UT_INSTITUTE;
	
	$bIsTestPublished = $objDB->IsTestPublished($_GET["test_id"]);
	$test_rating_score = 0;
	
	if($_GET["tschd_id"] == CConfig::FEUC_TEST_SCHEDULE_ID && $bIsTestPublished)
	{
		if(!isset($_COOKIE[CConfig::FEUC_NAME]))
		{
			setcookie(CConfig::FEUC_NAME,CUtils::uuid(), time()+(3600*24*30));
		}
		else
		{
			setcookie(CConfig::FEUC_NAME,$_COOKIE[CConfig::FEUC_NAME], time()+(3600*24*30));
		}
		$user_id = $_COOKIE[CConfig::FEUC_NAME];
		CSessionManager::Set(CSessionManager::BOOL_FREE_EZEEASSESS_USER, 1);
		printf("<script type='text/javascript'> var bIsFree = true;  </script>");
		
		if(isset($_COOKIE["already_rated_tests"]))
		{
			$rated_test_id_ary = explode(",",$_COOKIE["already_rated_tests"]);
			
			$bAlreadyRated = false;
			foreach($rated_test_id_ary as $rating)
			{
				$rating_ary = explode(";", $rating);
				if(in_array($_GET['test_id'], $rating_ary))
				{
					$bAlreadyRated = true;
					$test_rating_score = $rating_ary[1];
					break;
				}
			}
				
			if(!$bAlreadyRated)
			{
				printf("<script type='text/javascript'> var bIsTestRated = false;  </script>");
			}
			else 
			{
				printf("<script type='text/javascript'> var bIsTestRated = true;  </script>");
			}
		}
		else 
		{
			printf("<script type='text/javascript'> var bIsTestRated = false;  </script>");
		}
	}
	else 
	{
		printf("<script type='text/javascript'> var bIsFree = false;  </script>");
		// - - - - - - - - - - - - - - - - -
		// On Session Expire Load ROOT_URL
		// - - - - - - - - - - - - - - - - -
		CSessionManager::OnSessionExpire();
		// - - - - - - - - - - - - - - - - -
		
		if($_GET["tschd_id"] == CConfig::FEUC_TEST_SCHEDULE_ID)
		{
			header("Location: ".CSiteConfig::ROOT_URL);
		}
		$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
		$user_type = CSessionManager::Get(CSessionManager::INT_USER_TYPE);
	}
	
	$parsAry = parse_url(CUtils::curPageURL());
	$qry = split("[=&]", $parsAry["query"]);
	
	$menu_page = "menu.php";
	$start_page = "start.php";
	
	$test_id = null;
	$tschd_id = null;
	if($qry[0] == "test_id")
	{
		$test_id = $qry[1];
		
		if($qry[2] == "tschd_id")
		{
			$tschd_id = $qry[3];
		}
		
		$menu_page  .= "?test_id=".$qry[1]."&tschd_id=".$qry[3];
		$start_page .= "?test_id=".$qry[1]."&tschd_id=".$qry[3];
	}
	$test_name = $objDB->GetTestName($test_id);
	
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	// Check for number of attempt and expiration, if session exists.
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	$objTH = new CTestHelper();
	
	$bNewTest = true;
	$attempts = null;
	$nExpireSecOffset = null;
	$bTestSessionExpired = false;
	$bShowPreRestoreForm = false;
	
	$tsession_id = $objTH->IsTestPending($user_id, $test_id, $tschd_id);
	if($tsession_id != null)
	{
		$bNewTest = false;
		$bShowPreRestoreForm = ($bNewTest == false && $user_type == CConfig::UT_INDIVIDAL) ? true : false;
		
		$bRet = false;
		$attempts = $objTH->GetAttemptsFromTestSession($tsession_id, $bRet);
		$bShowPreRestoreForm = $bShowPreRestoreForm && $bRet ? true : false;
		
		$bTestSessionExpired = $objTH->IsTestSessionExpire($tsession_id, $nExpireSecOffset);
		
		if($bTestSessionExpired == true || $attempts == 0)
		{
			$objTH->EndExam($user_id, $test_id, $tschd_id);
		}
		
		if($attempts <= -1)
		{
			$attempts = "Unlimited";
		}
		
		$nExpireSecOffset = ($nExpireSecOffset == "NEVER") ? $nExpireSecOffset : abs($nExpireSecOffset);
		$sExpire = ($nExpireSecOffset == "NEVER") ? "Never Expire" : sprintf("%03d:%02d:%02d", floor($nExpireSecOffset/3600), floor(($nExpireSecOffset%3600) / 60), $nExpireSecOffset%60);
	}
	
	//echo("New Test: ".($bNewTest == true?"True":"False").", User Type: ".$user_type.", Expires: ".$sExpire."<br/>");
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
?>
<html>
	<head>
		<title> EZeeAssess: Assessment </title>
		<style type="text/css" title="currentStyle">
			@import "../core/media/css/ui-lightness/jquery-ui-1.8.21.custom.css";
			#dlg_test_end_confirm {
			
			    /* overlay is hidden before loading */
			    display:none;
			
			    /* standard decorations */
			    width:500px;
			    border:10px solid #390;
			    font-size:14px;
				
			    /* for modern browsers use semi-transparent color on the border. nice! */
			    border:10px solid rgba(33, 99, 00, 0.698);
			    background-color:#fff;
			
			    /* hot CSS3 features for mozilla and webkit-based browsers (rounded borders) */
			    -moz-border-radius:8px;
			    -webkit-border-radius:8px;
			}
			
			#dlg_test_end_confirm div {
			    padding:10px;
			    border:1px solid #3B5998;
			    background-color:#999;
			    font-family:"lucida grande",tahoma,verdana,arial,sans-serif;
			}
			
			#dlg_test_end_confirm h2 {
			    margin:-11px;
			    margin-bottom:0px;
			    color:#fff;
			    background-color:green;
			    padding:5px 10px;
			    border:1px solid #3B5998;
			    font-size:20px;
			}
			
			#dlg_rate_test{
			
			    /* overlay is hidden before loading */
			    display:none;
			
			    /* standard decorations */
			    width:350px;
			    border:10px solid #390;
			    font-size:14px;
				
			    /* for modern browsers use semi-transparent color on the border. nice! */
			    border:10px solid rgba(33, 99, 00, 0.698);
			    background-color:#fff;
			
			    /* hot CSS3 features for mozilla and webkit-based browsers (rounded borders) */
			    -moz-border-radius:8px;
			    -webkit-border-radius:8px;
			}
			
			#dlg_rate_test div {
			    font-family:"lucida grande",tahoma,verdana,arial,sans-serif;
			}
			
			#dlg_rate_test h2 {
			    margin:-11px;
			    margin-bottom:0px;
			    color:#fff;
			    background-color:green;
			    padding:5px 10px;
			    border:1px solid #3B5998;
			    font-size:20px;
			}
			
			.modal_overlapped {
			    display:    none;
			    position:   fixed;
			    z-index:    1000;
			    top:        0;
			    left:       0;
			    height:     100%;
			    width:      100%;
			    background: rgba( 255, 255, 255, .7 ) 
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
			body.loading .modal_overlapped {
			    display: block;
			}
		</style>
		<link rel="stylesheet" type="text/css" href="../css/mipcat.css" />
		<link rel="stylesheet" type="text/css" href="../3rd_party/bootstrap/css/bootstrap.css" />
		<script type="text/javascript" src="../3rd_party/wizard/js/jquery.min.js"></script>
		<script type="text/javascript" charset="utf-8" src="../core/media/js/jquery-ui-1.8.21.custom.min.js"></script>
		<script type="text/javascript" charset="utf-8" src="../core/media/js/jquery.tools.min.js"></script>
		<script type="text/javascript" src="../3rd_party/raty/lib/jquery.raty.js"></script>
		<script type="text/javascript" charset="utf-8" src="../3rd_party/wizard/js/jquery.validate.min.js"></script>
		<script type="text/javascript" src="../3rd_party/bootstrap/js/bootstrap.js"></script>
		<script type="text/javascript">
			if(!bIsFree)
			{
				var _gaq = _gaq || [];
				_gaq.push(['_setAccount', 'UA-2246912-13']);
				_gaq.push(['_trackPageview']);
			
				(function() {
				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
				})();
			}
			else
			{
				(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
					  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
					  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
				})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	
				ga('create', 'UA-2246912-17', 'ezeeassess.com');
				ga('send', 'pageview');
			}
		</script>
	</head>
	<body style="font: 80% 'Trebuchet MS', sans-serif; margin: 5px; overflow: hidden">
		<div class="modal_overlapped"></div>
		<div style="color:white;font-weight:bold;background-color:CornflowerBlue;padding:10px 10px;" id="header">
			<input type="button" id="btn_end_exam" class="btn btn-mini btn-danger" value="Close (X)" style="font-weight:bold;position:absolute; right:20px;top:12px"/><span>Test: <?php echo $test_name; ?></span>
		</div>
		
		<div id="pre_restore_log" style="position:relative;left:25%;height:100px;<?php echo( $bShowPreRestoreForm == false ? "display:none;": "");?>">
			<?php
				if($bTestSessionExpired == true)
				{
					printf("<h4 style='width:50%%'>Test: %s, is expired %s (HHH:MM:SS) ago. Result is concluded and available now under <span style='color:blue'>Result Analytics</span> Section.</h4>", $test_name, $sExpire);
				}
				else if($attempts == 0 && $attempts != "Unlimited")
				{
					printf("<h4 style='width:50%%'>You have taken all attempts of this Test: %s. Result is concluded and available now under <span style='color:blue'>Result Analytics</span> Section.</h4>", $test_name, $sExpire);
				}
				else 
				{
			?>
				<form id="form_restore_log" method="post" class="form-horizontal">
					<br/><br/>
					<div class="input-prepend">
						<span class="add-on"><b><i class="icon-shopping-cart"></i> Attempts Left:</b></span>
						<input style="color:red;font-weight:bold;" class="input-small" type="text" value="<?php echo($attempts); ?>" readonly="readonly"/>
					</div>
					<br/><br/>
					<div class="input-prepend input-append">
						<span class="add-on"><b><i class="icon-time"></i> Expires in:</b></span>
						<input style="color:red;font-weight:bold;" class="input-small" type="text" value="<?php echo($sExpire); ?>" readonly="readonly"/>
						<span class="add-on"><b>(HHH:MM:SS)</b></span>
					</div>
					<br/><br/>
					<div class="input-prepend">
						<span class="add-on"><b>Test Interruption Reason:</b></span>
						<select id="reason_list" onchange="OnReasonChange(this);">
							<option value="0">Power Failure</option>
							<option value="1">Browser Crash</option>
							<option value="2">Network or Connectivity Issue</option>
							<option value="3">Intentional (Browser Window) Close</option>
						</select>
					</div>
					<span id="div_spacing" style="display:none"><br/><br/></span>
					<div class="input-prepend" id="reason_div" style="display:none">
						<span class="add-on"><b>Reason:</b></span>
						<input id="reason" class="input-xxlarge" type="text" name="reason" placeholder="Why did you closed down browser window?"/>
					</div>
					<br/><br/>
					<input style="font-weight:bold;" class="btn btn-success" type="submit" value="Continue &gt;&gt;"/>
				</form>
			<?php
				}
			?>
		</div>
		
		<div id="div_test" style="<?php echo( $bShowPreRestoreForm == true ? "display:none": "");?>">
			<table width="100%" height="100%" border=0>
				<tr>
					<td width="16%" height="100%" id="menu_iframe" style="display: none;"> <iframe width="100%" frameborder="0" src="<?php echo($menu_page) ?>" name="menu"></iframe> </td>
					<td width="84%" height="100%"> <iframe width="100%" frameborder="0" src="<?php echo($start_page) ?>" name="display"></iframe> </td>
				</tr>
			</table>
		</div>
		<div id="div_result" style="display:none">
			<iframe id="frame_result" width="100%" frameborder="0" src="#" name="display"></iframe>
		</div>
		<div id="dlg_test_end_confirm" style="display:none">
		    <h2>End Exam Confirmation</h2>
				<p>Are you sure to end the exam? After confirmation your test progress will be submited for result and you will no longer be able to attempt this test again.</p>
				<p style="color:#666">
			      To cancel, click the No button or hit the ESC key.
			    </p>
			    <!-- yes/no buttons -->
			    <p style="text-align:center">
			      <input type="button" onclick="OnEndExam()" value="Yes">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" onclick="$('#dlg_test_end_confirm').overlay().close();" value="No">
			    </p>
		</div>
		
		<div id="dlg_rate_test" style="display:none;">
		    <h2 id='test_rating_heading'>Rate This Test</h2><br />
			<div style="margin-left:100px;" id="test_ratings"></div><hr />
			<p style="color:#666">
				To close, click the Close button.
			</p>
			<p style="text-align:center">
				<input id="rate_test_close" type="button" onclick="$('#dlg_rate_test').overlay().close();" value="Close" disabled>
			</p>
		</div>
		
		<div id="MessageModal" style="zindex:10000" class="modal hide fade">
		  <div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		    <h3><?php echo(CConfig::SNC_SITE_NAME);?> - Closing Test</h3>
		  </div>
		  <div id="ModalMsgStr" class="modal-body">
		  	
		  </div>
		  <div class="modal-footer">
		    <a href="#" onclick="HideOL(); $('#MessageModal').modal('hide');" class="btn">Close</a>
		  </div>
		</div>
		
		<script type="text/javascript">

			if(bIsFree)
			{
				if(bIsTestRated)
				{
					 $("#test_rating_heading").html("You have already rated following");
					 $("#rate_test_close").removeAttr('disabled');
					 $('#test_ratings').raty({ 
						 readOnly: true, 
						 score: <?php echo($test_rating_score);?>,
						 half      : true,
						 size      : 24,
						 starHalf  : '../3rd_party/raty/demo/img/star-half-big.png',
						 starOff   : '../3rd_party/raty/demo/img/star-off-big.png',
						 starOn    : '../3rd_party/raty/demo/img/star-on-big.png' 
					 });
				}
				else
				{
					$("#test_ratings").raty({
					    half      : true,
					    size      : 24,
					    starHalf  : '../3rd_party/raty/demo/img/star-half-big.png',
					    starOff   : '../3rd_party/raty/demo/img/star-off-big.png',
					    starOn    : '../3rd_party/raty/demo/img/star-on-big.png',
					    click: function(score) {
					        $("#rate_test_close").removeAttr('disabled');
					        bIsTestRated = true;
					        $(this).find('img').unbind();
					        $.post("ajax/ajax_rate_test.php",{'test_id':'<?php echo($_GET["test_id"]);?>', 'score': score}, function(){
						        
						    });
					    }
					});	
				}
			}

			if(!bIsFree)
			{
				$("table").height($(parent.window).height() - $("#header").height() - 30 );
				$("iframe").height($(parent.window).height() - $("#header").height() - 30 );
			}
			else
			{
				$("table").height(<?php echo($_GET['height']);?> - $("#header").height() - 30 );
				$("iframe").height(<?php echo($_GET['height']);?> - $("#header").height() - 30 );
			}
			//$( "#btn_end_exam", "#header" ).button();
			
			function CloseTestWithMsg(mgs, bClose)
			{
				$("#ModalMsgStr").html(mgs);
				$("#MessageModal").modal('show');
				if(bClose = 1)
				{
					OnEndExam();
				}
			}
			
			var bTestStarted = false;
			$("#btn_end_exam").click(function(){
				if(!bTestStarted)
				{
				if(!bIsFree)
					parent.HideOverlay();
				else						
					 window.parent.postMessage("HideOverlay", "<?php echo(CSiteConfig::FREE_ROOT_URL); ?>");
					<?php
					if($bTestSessionExpired == true || $attempts == 0)
					{
					?>
					if(!bIsFree)
						parent.RemoveTest();
					else										
					 window.parent.postMessage("RemoveTest", "<?php echo(CSiteConfig::FREE_ROOT_URL); ?>");					
					<?php
					}
					?>
				}
				else
				{
					if(OnEndExam.bExamEnded == false)
					{
						$('#dlg_test_end_confirm').overlay().load();
					}
					else 
					{
						OnEndExam();
					}
				}
			});
			
			OnEndExam.bExamEnded = false;
			function OnEndExam()
			{				
				if(OnEndExam.bExamEnded == false)
				{
					$("#div_test").hide();
					$("#frame_result").attr("src","end_exam.php?test_id=<?php echo($test_id); ?>"+"&tschd_id=<?php echo($tschd_id); ?>");
					$("#div_result").show();
					
					$("#btn_end_exam").val("Close (X)");
					$('#dlg_test_end_confirm').overlay().close();
					OnEndExam.bExamEnded = true;
					
					if(!bIsFree)
						parent.RemoveTest();
					else										
					 window.parent.postMessage("RemoveTest", "<?php echo(CSiteConfig::FREE_ROOT_URL); ?>");
				}
				else
				{
				if(!bIsFree)
					parent.HideOverlay();
				else										
					 window.parent.postMessage("HideOverlay", "<?php echo(CSiteConfig::FREE_ROOT_URL); ?>");					
				}
			}
			
			function HideOL()
			{
			 	if(!bIsFree)
					parent.HideOverlay();
				else										
					 window.parent.postMessage("HideOverlay", "<?php echo(CSiteConfig::FREE_ROOT_URL); ?>");
			}

			function ShowTestRating()
			{
				$('#dlg_rate_test').overlay().load();
			}
			
			function ShowLeftMenu()
			{
				$("#menu_iframe").show();
			}
			
			function OnTestStarted()
			{
				bTestStarted = true;
				$("#btn_end_exam").val("End Exam (X)");
			}
			
			function OnReasonChange(obj)
			{
				if(obj.value == 3)
				{
					$("#div_spacing").show();
					$("#reason_div").show();
				}
				else
				{
					$("#div_spacing").hide();
					$("#reason_div").hide();
				}
			}
			
			function SubmitRestoreLog()
			{
				var sReason = $("#reason_list option:selected").text();

				var current_date = new Date();
			    var time_zone = -current_date.getTimezoneOffset() / 60;
				
				if($("#reason_list option:selected").val() == 3)
				{
					sReason += " : " + $("#reason").val();
				}
				
				$('body').on({
				    ajaxStart: function() { 
				    	$(this).addClass("loading"); 
				    },
				    ajaxStop: function() { 
				    	$(this).removeClass("loading"); 
				    }    
				});
				
				$.getJSON("ajax/ajax_submit_restore_log.php?reason="+encodeURIComponent(sReason)+"&tsession_id="+encodeURIComponent('<?php echo($tsession_id); ?>')+"&time_zone="+time_zone, function(data) {
					$("#pre_restore_log").hide();
					$("#div_test").show();
				});
				
				return false;
			}
			
			jQuery.validator.addMethod("chars_not_allowed", function(value, element) {
				var str = $("#reason").val();
				
				return ( !( (str.indexOf("#") >= 0) || (str.indexOf(";") >= 0) ) );
			}, "<p style='color:red;'># (hash) & ; (semi-colon) characters are not allowed in reason!</p>");
			
			$("#form_restore_log").validate({
				errorPlacement: function(error, element) {
					$(error).insertAfter(element);
				}, rules: {
					'reason':			{required: true, 'chars_not_allowed': true}
				}, messages: {
					'reason':			{required: "<p style='color:red;'>Please provide valid reason!!</p>"}
				}, submitHandler: function(form) {
					SubmitRestoreLog();
				}
			});
				
			// select the overlay element - and "make it an overlay"
			$("#dlg_test_end_confirm").overlay({
				// custom top position
				top: 200,
				// some mask tweaks suitable for facebox-looking dialogs
				mask: {
					// you might also consider a "transparent" color for the mask
					color: '#06F',
					// load mask a little faster
					loadSpeed: 200,
					// very transparent
					opacity: 0.5
					},
				// disable this for modal dialog-type of overlays
				closeOnClick: false,
				// load it immediately after the construction
				load: false
			});

			$("#dlg_rate_test").overlay({
				// custom top position
				top: 200,
				// some mask tweaks suitable for facebox-looking dialogs
				mask: {
					// you might also consider a "transparent" color for the mask
					color: '#06F',
					// load mask a little faster
					loadSpeed: 200,
					// very transparent
					opacity: 0.5
					},
				// disable this for modal dialog-type of overlays
				closeOnClick: false,
				// load it immediately after the construction
				load: false,
				closeOnEsc: false
			});

			var bPageLoad = false;
			
			function SetBPageLoad(bVal)
			{
				bPageLoad = bVal;
			}

			function GetBPageLoad()
			{
				return bPageLoad;
			}
		</script>
	</body>
</html>
