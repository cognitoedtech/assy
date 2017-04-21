<?php
	include_once("../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/../../test/lib/test_helper.php");
	
	$objDB = new CMcatDB();
	$objTestHelper = new CTestHelper();
	$testIdArray = NULL;
	
	$index = 0;
	if(!empty($_GET['test']))
	{
		$index = $_GET['test'];
	}

	if($_GET['decline'] == 1)
	{
		$objDB->DeclineTest($_POST['test_id'],$_POST['reason']);
	}
	if($_GET['accept'] == 1)
	{
		$objDB->AcceptTest($_POST['test_id']);
	}
	
	$upperLimit = NULL;
	$testIdArray = $objDB->GetUnverifiedTestIds();
	$upperLimit = count($testIdArray);
	
	if($index == $upperLimit)
	{
		$index = $index - 1;
		$bNext = false;
	}

	$bNext = false;
	$bPrev = false;
	if($index > 0 && !empty($testIdArray))
	{
		$bPrev = true;
	}
	if($index+1 < $upperLimit && $index >= 0 && !empty($testIdArray))
	{
		$bNext = true;
	}

	$accepted = 0;
	if(!empty($_GET['accept']))
	{
		$accepted = $_GET['accept'];
	}

	$declined = 0;
	if(!empty($_GET['decline']))
	{
		$declined = $_GET['decline'];
	}

	printf("<script>accept_success='%s'</script>",$accepted);
	printf("<script>decline_success='%s'</script>",$declined);
?>
<html>
	<head>
		<title>Test Verifier</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<style type="text/css" title="currentStyle">
			@import "../../css/redmond/jquery-ui-1.9.0.custom.min.css";
		</style>
		<link rel="stylesheet" type="text/css" href="../../css/notify.css" />
		<link rel="stylesheet" type="text/css" href="../../css/mipcat.css" />
		<script type="text/javascript" charset="utf-8" src="../media/js/jquery.js"></script>
		<script type="text/javascript" charset="utf-8" src="../../js/jquery-ui-custom.min.js"></script>
		<script type="text/javascript" charset="utf-8" src="../../js/mipcat/utils.js"></script>
		<script type="text/javascript" src="../../js/notification.js"></script>
	</head>

	<body style="font: 75% 'Trebuchet MS', sans-serif; margin: 5px; overflow:hidden;">
		<div id="page_loading_box" style="position:absolute;top:100px;left:50%;zindex:200;">
			<img src="../../images/page_loading.gif" width="32" height="32"/>
		</div>
		<div class="notification sticky hide">
	        	<p> You have successfully <?php echo(($accepted == 1)?"accepted":"declined"); ?> one test </p>
	        	<a class="close" href="javascript:">
	            	<img src="../../images/icon-close.png" /></a>
	    </div>
		<div id="verify_test">
			<ul>
				<li><a href="#tab1">Verify Test</a></li>
			</ul>
			<div id="tab1">
				<form id="test_form" action='verify_test.php?test=0' method="post">
					<div style="text-align:center;background-color:CornflowerBlue;color:white;height:30px;line-height:30px;-moz-border-radius: 20px;-webkit-border-radius: 20px;-khtml-border-radius: 20px;border-radius: 20px;">
						<b><?php echo((!empty($testIdArray))?'Test '.($index+1).' of '.$upperLimit:''); ?></b>
					</div><br /><br />
					<div style="border:hidden;">	
						<table align="center" width="60%">
							<tr>
								<td><input type="button" id="previous" <?php echo($bPrev?"":"disabled='disabled'");?> onClick="var e = document.getElementById('test_form'); e.action='verify_test.php?test=<?php echo $index-1; ?>'; e.submit();" value="<"></td>
								<td align="right"><input type="button" id="accept" value="Accept" <?php echo(!empty($testIdArray)?"":"disabled='disabled'");?> onClick="var e = document.getElementById('test_form'); e.action='verify_test.php?test=<?php echo $index; ?>&amp;accept=1'; e.submit();"/></td>
								<td align="center">
									<select id="reason" name="reason" <?php echo((!empty($testIdArray))?"":"disabled='disabled'");?>>
										<option value="0">--Select Reason for Decline--</option>
										<?php
											$objDB->PopulateDeclineReason(1);
										?>
									</select>&nbsp;&nbsp;&nbsp;&nbsp;
									<input type="button" value="Decline" id="decline" disabled="disabled" onClick="var e = document.getElementById('test_form'); e.action='verify_test.php?test=<?php echo $index; ?>&amp;decline=1'; e.submit();"/>
								</td>
								<td><input type="button" id="next" <?php echo($bNext?"":"disabled='disabled'");?> onClick="var e = document.getElementById('test_form'); e.action='verify_test.php?test=<?php echo $index+1; ?>'; e.submit();" value=">"></td>
							</tr>
						</table><br />
						<input type="hidden" name="test_id" value="<?php echo($testIdArray[$index]); ?>"
					</div>
				</form>
				<?php
					if(!empty($testIdArray))
					{
						echo $objTestHelper->PrepareTestDetailsHTML2($testIdArray[$index]);
					}
				?>
			</div>
		</div>
		<script type="text/javascript">
		
			$(window).load(function(){
				$("#page_loading_box").hide();
				$('#verify_test').show();
				$('#verify_test').tabs();
					
				var page_hgt = objUtils.AdjustHeight("tab1");
				$('#platform', window.parent.document).height(page_hgt+200);
			});

			$('#reason').change(function() {
				var reason_id = $('#reason').val();
				if(reason_id == 0)
				{
					$('#accept').removeAttr('disabled');
					$('#decline').attr('disabled','disabled');
				}
				else
				{
					$('#accept').attr('disabled','disabled');
					$('#decline').removeAttr('disabled');
				}
			});

			$(document).ready( function () {
				if(accept_success == 1 || decline_success == 1)
				{
					$('.notification.sticky').notify({ type: 'sticky' });
				}
			});
		</script>
	</body>
</html>
