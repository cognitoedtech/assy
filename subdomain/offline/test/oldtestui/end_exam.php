 <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
 <?php
 	include_once(dirname(__FILE__)."/../lib/session_manager.php");
 	include_once(dirname(__FILE__)."/../lib/utils.php");
 	include_once("lib/tbl_result.php");
 	include_once("lib/test_helper.php");
 	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	//CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$bFreeEZeeAssesUser = CSessionManager::Get(CSessionManager::BOOL_FREE_EZEEASSESS_USER);
	
	$sUserID = "";
	$style="";
	if($bFreeEZeeAssesUser == 1)
	{
		$style = "style='border:1px solid CornflowerBlue;float: right;width: 50%'";
		$sUserID = $_COOKIE[CConfig::FEUC_NAME];
	}
	else 
	{
		$style="style='display:none;border:1px solid black;float: right;'";
		$sUserID = CSessionManager::Get(CSessionManager::STR_USER_ID);
	}
	
	$parsAry = parse_url(CUtils::curPageURL());
	$qry = split("[=&]", $parsAry["query"]);
	
	$objTR = new CResult();
	$objTH = new CTestHelper();
	
	$nTestID = null;
	if($qry[0] == "test_id")
	{
		$nTestID = $qry[1];
	}
	
	$nTschdID = null;
	if($qry[2] == "tschd_id")
	{
		$nTschdID = $qry[3];
	}
	
	$test_pnr = $objTH->EndExam($sUserID, $nTestID, $nTschdID);
	$res_visibility = $objTH->GetResultVisibility($nTestID);
	
	$ResultAry = $objTR->GetResult($test_pnr);
	
	printf("<script>var rslt_visibility=%d;</script>", $res_visibility);
	
	/*echo "<pre>";
	printf($ResultAry);
	echo "</pre>";*/
 ?>
<html>
 <head>
  <title> MACS Mock CET - Exam Ended </title>
  <meta name="Generator" content="EditPlus">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
	<link rel="stylesheet" type="text/css" href="../css/mipcat.css" />
	<link rel="stylesheet" type="text/css" href="../3rd_party/bootstrap/css/bootstrap.css" />
	<link class="include" rel="stylesheet" type="text/css" href="jqplot/data/css/jquery.jqplot.min.css" />
	<link rel="stylesheet" type="text/css" href="jqplot/data/css/examples.min.css" />
    <link type="text/css" rel="stylesheet" href="jqplot/plugins/3rd-party/syntaxhighlighter/styles/shCoreDefault.min.css" />
    <link type="text/css" rel="stylesheet" href="jqplot/plugins/3rd-party/syntaxhighlighter/styles/shThemejqPlot.min.css" />
	<!--[if lt IE 9]><script language="javascript" type="text/javascript" src="jqplot/plugins/js/excanvas.js"></script><![endif]-->
	<script type="text/javascript" src="../js/jquery.js"></script>
	<script type="text/javascript" src="../js/jquery.form.js"></script>   
	<script type="text/javascript" charset="utf-8" src="../3rd_party/wizard/js/jquery.validate.min.js"></script>
	
	<style type="text/css">
			.modal1 {
			    display:    none;
			    position:   fixed;
			    z-index:    1000;
			    top:        0;
			    left:       0;
			    height:     100%;
			    width:      100%;
			    background: rgba( 255, 255, 255, .8 ) 
			                url('../images/page_loading.gif') 
			                50% 200px 
			                no-repeat;
			}
			body.loading {
			    overflow: hidden;   
			}
			body.loading .modal1 {
			    display: block;
			}
 	</style>
 </head>

<body>
	<?php
  		$CorrectAns = 0;
		$WrongAns   = 0;
		$Unanswered	= 0;
		
		foreach ($ResultAry as $sectionName => $SectionAry)
		{
			foreach ($SectionAry as $subjectName => $SubjectAry)
			{
				foreach ($SubjectAry as $topicName => $TopicAry)
				{
					foreach ($TopicAry as $difficulty => $QuestionAry)
					{
						foreach ($QuestionAry as $QuestionIdx => $Answer )
						{
							if($Answer == 0)
							{
								$WrongAns++;
							}
							else if($Answer == 1)
							{
								$CorrectAns++;
							}
							else if($Answer == -1 || $Answer == -2)
							{
								$Unanswered++;
							}
						}
					}
				}
			}
		}
	
		$total_questions = $CorrectAns+$WrongAns+$Unanswered;
		if($res_visibility != CConfig::RV_NONE)
		{
			echo ("<span id='span_result_summary'>You answered ".$CorrectAns." correct answers and ".$WrongAns." wrong answers out of ".($CorrectAns+$WrongAns+$Unanswered).".</span><br />");
		}
		else 
		{
			echo ("<span id='span_result_summary'>You attempted ".($CorrectAns+$WrongAns)." questions out of ".($CorrectAns+$WrongAns+$Unanswered).".</span><br />");
		}
?>
	<div id="chart1" style="width:600px; height:250px;float:left;" <?php $res_visibility != 0?>></div>
	<fieldset <?php echo($style);?> id="information_form_fieldset">
		<legend style="color:CornflowerBlue;"><b>Provide information to get detailed result:</b></legend>
		<div id="response_div" style="text-align: center"></div>
		<table width="100%">
			<tr>
				<td style="border-right:1px solid #ddd;text-align:center;">
					<p style="color:blue;">New User?</p>
					<form id='free_user_registration'>
						<table width="100%">
							<tr>
								<td><b>Name:</b></td>
								<td><input type="text" name="name" placeholder="Name"/></td>
							</tr>
							<tr>
								<td><b>EMail:</b></td>
								<td><input type="text" name="email" placeholder="EMail"/></td>
							</tr>
							<tr>
								<td><b>Phone:</b></td>
								<td><input type="text" name="phone" placeholder="Phone#"/></td>
							</tr>
							<tr>
								<td><b>City:</b></td>
								<td><input type="text" name="city" placeholder="City"/></td>
							</tr>
							<tr>
								<td><b>Verify Text:</b></td>
								<td><input type="text" name="captch_value" id="captch_value"  placeholder="Text" /></td>
								<td><img id="captcha_img" src="../3rd_party/captcha/captcha.php"></td>
							</tr>
							<tr>
								<td colspan="2"><button type="submit" class="btn btn-primary">Submit</button></td>
							</tr>
						</table>
						<input type="hidden" name="new_free_user" value='1' />	
					</form>
				</td>
				<td valign="top" style="text-align:center;">
					<p style="color:green;">Already attempted earlier?</p>
					<form id="email_submit_form">
						<table width="100%">
							<tr>
								<td><b>EMail:</b></td>
								<td><input type="text" name="email" placeholder="EMail" /></td>
							</tr>
							<tr>
								<td colspan="2"><button type="submit" class="btn btn-success">Submit</button></td>
							</tr>
						</table>
						<input type="hidden" name="existing_free_user" value='1' />
					</form>
				</td>
			</tr>
		</table>
	</fieldset>
    <script class="code" type="text/javascript">
	<?php 
	if($bFreeEZeeAssesUser == 1)
	{
	?>
    parent.ShowTestRating();
    <?php 
	}
    ?>
    jQuery.validator.addMethod("ValidatePhoneNumber", function(value, element) {
	
    	if(value == "9999999999" || /^[0-6]/.test(value) || value == "7777777777" || value == "8888888888" || value == "987654321")
    	{
        	return false;
    	}
    	else
    	{
        	return true;
    	}
	}, "<span style='color:red;'>* Please enter a valid phone number</style>");

    var counter = 20;
    var time_out;
    function timedCount()
    {
	    $("#timer").text(counter+"");
	    counter = counter-1;
	    if(counter == 0)
	    {
		    parent.OnEndExam();
		}
	    time_out = setTimeout(function(){timedCount()},1000);
    }

    function showResponse(responseText, statusText, xhr, form)
	{
    	$.each(responseText, function(key,value){
        	if(key == "error")
        	{
        		$("#response_div").html(value);	
        	}
        	else if(key == "success")
        	{
            	$("#chart1").hide();
            	$("#information_form_fieldset").hide();
            	$("#span_result_summary").html("Detailed result has been sent successfully on your EMail Id. This window will be closed automatically just after <b><span id='timer'>"+counter+"</span> seconds</b>.");
            	timedCount();
            }
        });
   	 	$("#captcha_img").attr("src","../3rd_party/captcha/captcha.php?r=" + Math.random());
	}

    var time_zone = get_time_zone_offset( );
    var options = { 
    	    data: {'test_id': '<?php echo($nTestID);?>', 'test_pnr' : '<?php echo($test_pnr);?>', 'time_zone' : time_zone},
       	 	//target:        '',   // target element(s) to be updated with server response 
       		// beforeSubmit:  showRequest,  // pre-submit callback 
      	 	 success:       showResponse,  // post-submit callback 
 
        	// other available options: 
        	url:      'ajax/ajax_free_user_result.php',         // override for form's 'action' attribute 
        	type:      'POST',       // 'get' or 'post', override for form's 'method' attribute 
        	dataType:  'json',        // 'xml', 'script', or 'json' (expected server response type) 
        	clearForm: false        // clear all form fields after successful submit 
        	//resetForm: true        // reset the form after successful submit 
 
        	// $.ajax options can be used here too, for example: 
        	//timeout:   3000 
    	};
	
	$(document).ready(function(){
		<?php 
		if($res_visibility != CConfig::RV_NONE)
		{
		?>
		var s1 = [<?php echo($CorrectAns);?>];
		var s2 = [<?php echo($WrongAns);?>];
		var s3 = [<?php echo($Unanswered);?>];
		<?php 
		}
		else 
		{
		?>
		var s1 = [<?php echo($CorrectAns+$WrongAns);?>];
		var s2 = [<?php echo($Unanswered);?>];
		<?php
		}
		?>
		// Can specify a custom tick Array.
		// Ticks should match up one for each y value (category) in the series.
		var ticks = ['Answers'];

		var tickIntervalValue = Math.ceil(<?php echo($total_questions);?>/10);
		if(rslt_visibility != 0)
		{
			var plot1 = $.jqplot('chart1', [s1, s2, s3], {
				// The "seriesDefaults" option is an options object that will
				// be applied to all series in the chart.
				seriesDefaults:{
					renderer:$.jqplot.BarRenderer,
					rendererOptions: {fillToZero: true},
					pointLabels: { show: true, location: 'n'}
				},
				// Custom labels for the series are specified with the "label"
				// option on the series option.  Here a series option object
				// is specified for each series.
				series:[
					{label:'Correct'},
					{label:'Wrong'},
					{label:'Not Answered'}
				],
				// Show the legend and put it outside the grid, but inside the
				// plot container, shrinking the grid to accomodate the legend.
				// A value of "outside" would not shrink the grid and allow
				// the legend to overflow the container.
				legend: {
					show: true,
					placement: 'outsideGrid'
				},
				axes: {
					// Use a category axis on the x axis and use our custom ticks.
					xaxis: {
						renderer: $.jqplot.CategoryAxisRenderer,
						ticks: ticks
					},
					// Pad the y axis just a little so bars can get close to, but
					// not touch, the grid boundaries.  1.2 is the default padding.
					yaxis: {
						//pad: 1.05,
						min: 0,  
					    tickInterval: tickIntervalValue,
						tickOptions: {formatString: '%d'}
					}
				}
			});
		}
		else
		{
			var plot1 = $.jqplot('chart1', [s1, s2], {
				// The "seriesDefaults" option is an options object that will
				// be applied to all series in the chart.
				seriesDefaults:{
					renderer:$.jqplot.BarRenderer,
					rendererOptions: {fillToZero: true},
					pointLabels: { show: true, location: 'n'}
				},
				// Custom labels for the series are specified with the "label"
				// option on the series option.  Here a series option object
				// is specified for each series.
				series:[
					{label:'Attempted'},
					{label:'Not Attempted'}
				],
				// Show the legend and put it outside the grid, but inside the
				// plot container, shrinking the grid to accomodate the legend.
				// A value of "outside" would not shrink the grid and allow
				// the legend to overflow the container.
				legend: {
					show: true,
					placement: 'outsideGrid'
				},
				axes: {
					// Use a category axis on the x axis and use our custom ticks.
					xaxis: {
						renderer: $.jqplot.CategoryAxisRenderer,
						ticks: ticks
					},
					// Pad the y axis just a little so bars can get close to, but
					// not touch, the grid boundaries.  1.2 is the default padding.
					yaxis: {
						min: 0,  
					    tickInterval: tickIntervalValue,
						tickOptions: {formatString: '%d'}
					}
				}
			});
		}

		$("#free_user_registration").validate({
        	rules: {

        		name: {
                 required: true,
                 minlength: 2
             },
            	email: {
                	required: true,
                	email: true
            	},
            	phone:{
                	required:true,
               	 	number: true,
                  	minlength: 10,
                  	maxlength: 10,
                  	'ValidatePhoneNumber' : true
            		
            	},
           		city:"required",
           		captch_value:"required"

        	},
        	messages: {
        		name: 	{
        			required:	"<span style='color:red;'>* Please enter your name</span>",
           		 	minlength:	"<span style='color:red;'>* Minimum length of name should be 2</span>"
            		},
            		email: 	{
            			email:	"<span style='color:red;'>* Please enter a valid email address</span>",
               		 	required:	"<span style='color:red;'>* Please enter the email address</span>"
                		},

            	city: 			"<span style='color:red;'>* Please enter your city name</span>",
            	captch_value:	"<span style='color:red;'>* Please enter the code shown in image</span>",

            	phone:{
           		 	required:	"<span style='color:red;'>* Please enter your phone no.</span>",
            	 	number:		"<span style='color:red;'>* Phone number must contain digits only</span>",
            	 	minlength:	"<span style='color:red;'>* Minimum length of phone no. should be 10</span>",
            	 	maxlength:	"<span style='color:red;'>* Maximum length of phone no. should be 10</span>"
            	}
        	},
        
        	submitHandler: function(form) {
        		$('body').on({
				    ajaxStart: function() { 
				    	$(this).addClass("loading"); 
				    },
				    ajaxStop: function() { 
				    	$(this).removeClass("loading"); 
				    }    
				});
    			$('#free_user_registration').ajaxSubmit(options);
        	}
    	});

		$("#email_submit_form").validate({
        	rules: {

        		email: {
                	required: true,
                	email: true
            	}
        	},
        	messages: {
        		email: 	{
        			email:	"<span style='color:red;'>* Please enter a valid email address</span>",
           		 	required:	"<span style='color:red;'>* Please enter the email address</span>"
            		}
        	},
        
        	submitHandler: function(form) {
        		$('body').on({
				    ajaxStart: function() { 
				    	$(this).addClass("loading"); 
				    },
				    ajaxStop: function() { 
				    	$(this).removeClass("loading"); 
				    }    
				});
				if(!parent.bIsTestRated)
				{
					parent.ShowTestRating();
				}
				else
				{
    				$('#email_submit_form').ajaxSubmit(options);
				}
        	}
    	});
	});

	function get_time_zone_offset( ) 
	{
	    var current_date = new Date();
	    return -current_date.getTimezoneOffset() / 60;
	}
	</script>

	<!-- End example scripts -->
	<!-- Don't touch this! -->

	<script class="include" type="text/javascript" src="jqplot/plugins/js/jquery.jqplot.min.js"></script>
	<!-- End Don't touch this! -->
	<!-- Additional plugins go here -->

	<script class="include" language="javascript" type="text/javascript" src="jqplot/plugins/jqplot.barRenderer.min.js"></script>
	<script class="include" language="javascript" type="text/javascript" src="jqplot/plugins/jqplot.categoryAxisRenderer.min.js"></script>
	<script class="include" language="javascript" type="text/javascript" src="jqplot/plugins/jqplot.pointLabels.min.js"></script>
	<!-- End additional plugins -->
	<div class="modal1"></div>
 </body>
</html>
