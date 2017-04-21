<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
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
	
	$parsAry = parse_url(CUtils::curPageURL());
	$qry = explode("=", $parsAry["query"]);
	
	$sTestName = "";
	if($qry[0] == "test_name")
	{
		echo "<script>save_success = 1; </script>";
		$sTestName = urldecode($qry[1]);
	}
	else 
	{
		echo "<script>save_success = 0; </script>";
	}
?>
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
		<link href="../../css/notify.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" charset="utf-8" src="../../js/jquery.js"></script>
		<script type="text/javascript" charset="utf-8" src="../../js/jquery-ui-custom.min.js"></script>
		<script type="text/javascript" src="../../3rd_party/wizard/js/jquery.validate.min.js"></script>
		<script type="text/javascript" src="../../js/notification.js"></script>
	</head>
	<body style="font: 70% 'Trebuchet MS', sans-serif; margin: 5px;">
		<div class="notification sticky hide">
	        <p> The test <?php echo("<b>".$sTestName."</b>"); ?> has been submitted for verification successfully! </p>
	        <a class="close" href="javascript:">
	            <img src="../../images/icon-close.png" /></a>
	    </div>
		<div id="contrib_tests">
			<ul>
				<li><a href="#tab1">Contribute Test</a></li>
			</ul>
			<div id="tab1">
				<form id="contrib_form" method="post" action="post_get/form_contib_tests_exec.php">
					<label style="color:Navy;"><b>Select Test:</b></label>
					<select name="test_id">
						<?php
							$objDB->PrepareTestCombo($user_id);
						?>
					</select><br/><br/><br/>
					<label style="color:Navy;"><b>Comma Saparated Key Words (Minimum 10 letters) : </b></label><span id="kywd_count" style="color:blue">[You typed: 0 letter]</span><br/>
					<textarea id="kywd_area" name="keywords" rows="4" cols="80" onkeyup="OnKeyword();"></textarea>
					<br/><br/><br/>
					<label style="color:Navy;"><b>Test Desctiption (Minimum 150 letters) : </b></label><span id="descr_count" style="color:blue">[You typed: 0 letter]</span><br/>
					<textarea id="decr_area" rows="8" cols="80" name="description" onkeyup="OnDecr();"></textarea>
					<br/><br/><br/>
					<input type="submit" value="Contribute !">
				</form>
			</div>
		</div>
		
		<script type="text/javascript">
			function OnKeyword()
			{
				var text = $("#kywd_area").val();
				$("#kywd_count").html("[You typed: "+text.length+" letters]");
				
				if(text.length < 10)
				{
					$("#kywd_count").css("color","blue");
				}
				else
				{
					$("#kywd_count").css("color","green");
				}
			}
			
			function OnDecr()
			{
				var text = $("#decr_area").val();
				$("#descr_count").html("[You typed: "+text.length+" letters]");
				
				if(text.length < 150)
				{
					$("#descr_count").css("color","blue");
				}
				else
				{
					$("#descr_count").css("color","green");
				}
			}
			
			$(function() {
		        $( "#sortable" ).sortable({
		            placeholder: "ui-state-highlight"
		        });
		        $( "#sortable" ).disableSelection();
		    });
		    
		    $('#contrib_form').validate({
				errorPlacement: function(error, element) {
					//$('div.contrib-error').empty();
					//$('div.contrib-error').append(error);
					error.insertAfter(element);
				}, rules: {
					'keywords':			{required: true, minlength: 10},
					'description':		{required: true, minlength: 150}
				}, messages: {
					'keywords':			{required: "<span style='color:red'><br/>Please type minimum 10 letters for keywords!</span>", minlength: "<span style='color:red'><br/>Please type minimum 10 letters for keywords!</span>"},
					'description':		{required: "<span style='color:red'><br/>Please type minimum 150 letters for test description!</span>", minlength: "<span style='color:red'><br/>Please type minimum 150 letters for test description!</span>"}
				}
			});
			
		    $('#contrib_tests').tabs();
		    
		    $(document).ready(function () {
				if(save_success == 1)
				{
					$('.notification.sticky').notify({ type: 'sticky' });
				}
			});
		</script>
	</body>
</html>