<?php
	include_once("../../lib/session_manager.php");
	include_once("../../database/mcat_db.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$objDB = new CMcatDB();
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	
	$pub_ques_pnt  = $objDB->GetPubQuesCount($user_id) ;
	$used_ques_pnt = ceil( $objDB->GetQuesUsedCount(-1, $user_id) / 40 ) ;
	$inst_test_pnt = $objDB->GetTestUsedCount(CConfig::UT_INSTITUTE) * 15 ;
	$corp_test_pnt = $objDB->GetTestUsedCount(CConfig::UT_CORPORATE) * 25 ;
	
	$total = $pub_ques_pnt + $used_ques_pnt + $inst_test_pnt + $corp_test_pnt;

	$pan_no = $objDB->GetUserPANNumber($user_id);
	
	// Don't change the calling order of function.
	$objDB->UpdateContribPntStatus($user_id, $pub_ques_pnt, $used_ques_pnt, $inst_test_pnt, $corp_test_pnt, $total);
	
	$balance = $objDB->AdjustContribEncashedPnts($user_id);
	$status = $objDB->GetEncashPntsStatus($user_id);
	if($balance >= CConfig::MIN_ENCASH_PNTS && $status == 1)
	{
		session_start();
		$_SESSION['balance'] = $balance;
	}

	$encashed = 0;
	if(!empty($_GET['encashed']))
	{
		$encashed = $_GET['encashed'];
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php printf("<script>save_success='%s'</script>",$encashed); ?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Contribute Test</title>
		<style type="text/css" title="currentStyle">
			@import "../../css/redmond/jquery-ui-1.9.0.custom.min.css";
			
			#sortable { list-style-type: none; margin: 0; padding: 0; width: 60%; }
		    #sortable li { margin: 0 5px 5px 5px; padding: 5px; font-size: 1em; height: 1em; }
		    html>body #sortable li { height: 1em; line-height: 1em; }
		    .ui-state-highlight { height: 1em; line-height: 1em; }
		</style>
		<link rel="stylesheet" type="text/css" href="../../css/mipcat.css" />
		<link rel="stylesheet" type="text/css" href="../../css/notify.css" />
		<script type="text/javascript" charset="utf-8" src="../../js/jquery.js"></script>
		<script type="text/javascript" charset="utf-8" src="../media/js/jquery.validate.js"></script>
		<script type="text/javascript" charset="utf-8" src="../../js/jquery-ui-custom.min.js"></script>
		<script type="text/javascript" src="../../js/notification.js"></script>
		<script type="text/javascript" charset="utf-8" src="../../js/mipcat/utils.js"></script>
	</head>
	<body style="font: 75% 'Trebuchet MS', sans-serif; margin: 5px; overflow:hidden;">
		<div id="page_loading_box" style="position:absolute;top:100px;left:50%;zindex:200;">
			<img src="../../images/page_loading.gif" width="32" height="32"/>
		</div>
		<div class="notification sticky hide">
	        	<p> Your Request to encash has been sent successfully, It will take minimum 7 days to process </p>
	        	<a class="close" href="javascript:">
	            	<img src="../../images/icon-close.png" /></a>
	    </div>
		<div id="encash_points">
			<ul>
				<li><a href="#tab1">En-cash</a></li>
			</ul>
			<div id="tab1">
				<form id="encash_form" action="post_get/form_encash_points_exec.php" onSubmit="return getTimeZone();" method="post">
					<p>Your current balance is <?php echo( $balance );?> points via various sources.</p>
					<?php
					if(empty($pan_no))
					{
					?>
					<label>PAN (Personal Account Number):</label><br/>
					<input type="text" name="pan_no" id="pan_no" size=40/><br/><br/>
					<?php
					}
					?>
					<input type="submit" value="En-Cash Points!" <?php echo(($balance < CConfig::MIN_ENCASH_PNTS) || ($status != 1)?"disabled='disabled'":""); ?>/><br/><br/>
					<input type="hidden" id="time_zone" name="time_zone"/><br/><br/>
					<?php
						if($balance < CConfig::MIN_ENCASH_PNTS)
						{
							echo ("Dear Contributor, You do not have sufficient points to encash, Please refer, <a href='help_manual/gs_help_contrib.php' target='_blank'><b>Getting Started as Contributor</b></a> or <a href='../../help-and-faq/faq/contrib_faq.php' target='_blank'><b>Contributor F. A. Q</b></a> for more information.");
						}
						else
						{
							if($status != 1)
							{
								echo ("Dear Contributor, Your previous transaction status is pending, Please refer, <a href='help_manual/gs_help_contrib.php' target='_blank'><b>Getting Started as Contributor</b></a> or <a href='../../help-and-faq/faq/contrib_faq.php' target='_blank'><b>Contributor F. A. Q</b></a> for more information.");	
							}
						}
					?>
				</form>
			</div>
		</div>
		
		<script type="text/javascript">

			$(window).load(function(){
				$("#page_loading_box").hide();
				$('#encash_points').show();
				$('#encash_points').tabs();
				
				var page_hgt = objUtils.AdjustHeight("tab1");
				$('#platform', window.parent.document).height(page_hgt+200);
			});

			function getTimeZone()
			{
				var current_date = new Date();
                $("#time_zone").val(-current_date.getTimezoneOffset()/60);
				return true;
			}
			$.validator.addMethod("pan_no_length",function(value){
   				if(document.getElementById("pan_no").value.length == 10)
				{
					return true;
				}
				return false;
			});
			 $(document).ready( function () {
				
				if(save_success == 1)
				{
					$('.notification.sticky').notify({ type: 'sticky' });
				}

				$('#encash_form').validate({
					rules: {
						pan_no : {
							required : true,
							pan_no_length : true
						}
					},
					messages: {
						pan_no : {
							required : " Please enter your PAN to encash points",
							pan_no_length : " Please enter proper PAN"
						}
					}
				});
			 });
		</script>
	</body>
</html>