<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	include_once(dirname(__FILE__)."/../../lib/utils.php");
	include_once(dirname(__FILE__)."/../../lib/include_js_css.php");
	include_once(dirname(__FILE__)."/../../lib/site_config.php");
	include_once("../../lib/session_manager.php");
	include_once('../../test/lib/tbl_result.php');
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$objTR = new CResult();
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	$nUserType = CSessionManager::Get(CSessionManager::INT_USER_TYPE);
	
	$TNameAry = $objTR->GetCompletedTestNames($user_id, $nUserType);
	
	$objIncludeJsCSS = new IncludeJSCSS();
	
	$menu_id = CSiteConfig::UAMM_RESULT_ANALYTICS;
	$page_id = CSiteConfig::UAP_RESULT_INSPECTION;
?>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="Generator" content="Mastishka Intellisys Private Limited">
<meta name="Author" content="Mastishka Intellisys Private Limited">
<meta name="Keywords" content="">
<meta name="Description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo(CConfig::SNC_SITE_NAME);?>: Result Inspection</title>
<?php 
$objIncludeJsCSS->CommonIncludeCSS("../../");
$objIncludeJsCSS->IncludeJquerySnippetCSS("../../");
$objIncludeJsCSS->IncludeMipcatCSS("../../");
$objIncludeJsCSS->IncludeIconFontCSS ( "../../" );
$objIncludeJsCSS->IncludeFuelUXCSS ( "../../" );

$objIncludeJsCSS->CommonIncludeJS("../../");
$objIncludeJsCSS->IncludeJquerySnippetJS("../../");
$objIncludeJsCSS->IncludeMetroNotificationJS ( "../../" );
$objIncludeJsCSS->IncludeMathJAXJS( "../../" );
?>
<style type="text/css">
	.modal1 {
		display:    none;
		position:   fixed;
		z-index:    1000;
		top:        50%;
		left:       60%;
		height:     100%;
		width:      100%;
	}
	
	.js-responsive-table thead{font-weight: bold}	
	.js-responsive-table td{ -moz-box-sizing: border-box; -webkit-box-sizing: border-box;-o-box-sizing: border-box;-ms-box-sizing: border-box;box-sizing: border-box;padding: 0px;}
	.js-responsive-table td span{display: none}		
	
	@media all and (max-width:767px){
		.js-responsive-table{width: 100%;max-width: 400px;}
		.js-responsive-table thead{display: none}
		.js-responsive-table td{width: 100%;display: block}
		.js-responsive-table td span{float: left;font-weight: bold;display: block}
		.js-responsive-table td span:after{content:' : '}
		.js-responsive-table td{border:0px;border-bottom:1px solid #ddd}	
		.js-responsive-table tr:last-child td:last-child{border: 0px}		
	}
	
	div.mipcat_code_ques {
		font-family: "Courier New", monospace;
		white-space: pre;
		border:1px solid #aaa;
		padding:5px;
		margin: 10px;
		white-space: pre-wrap;      /* CSS3 */   
	    white-space: -moz-pre-wrap; /* Firefox */    
	    white-space: -pre-wrap;     /* Opera <7 */   
	    white-space: -o-pre-wrap;   /* Opera 7 */    
	    word-wrap: break-word;      /* IE */
	}
</style>
</head>
<body>

	<?php 
	include_once(dirname(__FILE__)."/../../lib/header.php");
	?>

	<!-- --------------------------------------------------------------- -->
	<br />
	<br />
	<br />
	<div class='row-fluid'>
		<div class="col-lg-3 col-md-3 col-sm-3">
			<?php 
			include_once(dirname(__FILE__)."/../../lib/sidebar.php");
			?>
		</div>
		<div class="col-lg-9 col-md-9 col-sm-9" style="border-left: 1px solid #ddd; border-top: 1px solid #ddd;">
			<div class="fuelux modal1">
				<div class="preloader"><i></i><i></i><i></i><i></i></div>
			</div>
			<br />
			<div class="row fluid">
				<div class="col-lg-12 col-md-12 col-sm-12">
					<div class="col-lg-3 col-md-3 col-sm-3">
						<select class="form-control input-sm" id='ri_test_id'>
							<?php
								printf("<option value='' selected='selected'>-- Choose Test --</option>", $test_id, $test_name);
								foreach($TNameAry as $test_id => $test_name)
								{
									printf("<option value='%s'>%s</option>", $test_id, $test_name);
								}
							?>
						</select>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-3">
						<select class="form-control input-sm" id='ri_test_schd_date' style="display:none">
						</select>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-3" style="<?php echo(($nUserType == CConfig::UT_INDIVIDAL)?"display:none;": "");?>">
						<select class="form-control input-sm" id='ri_batch' style="display:none" onkeyup="OnBatchChange();" onkeydown="OnBatchChange();" onchange="OnBatchChange();">
						</select>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-3">
						<select class="form-control input-sm" id='ri_candidate_id' style="display:none">
						</select>
					</div>
				</div>
			</div>
			<?php 
			if($nUserType != CConfig::UT_INDIVIDAL)
			{
			?>
			<div class="row fluid">
				<div class="col-lg-12 col-md-12 col-sm-12">
					<br />
					<a href="#" target="_blank" id="download_pdf" style="display:none;float: right;" onclick="DownloadPDF(this);"><img width="70" align="top" height="70" src="../../images/export_pdf.jpg" title="Export Result In PDF"/></a>
				</div>
			</div>
			<?php 
			}
			?>
			<div class="row fluid">
				<div class="col-lg-12 col-md-12 col-sm-12">
					<div id="result_inspection">			
					</div>
				</div>
			</div>
			<?php
			include_once (dirname ( __FILE__ ) . "/../../lib/footer.php");
			?>
		</div>
	</div>
	<script type="text/javascript">
		$(document).ready(function () {
			//Disable cut copy paste
		    $('body').bind('cut copy paste', function (e) {
		        e.preventDefault();
		        $.Notify({
					 caption: "<b>Cut / Copy / Paste is not allowed !",
					 content: "Cut / Copy / Paste operations are not allowed on this page.",
					 style: {background: 'green', color: '#fff'}, 
					 timeout: 5000
					 });
		    });
		   
		    //Disable mouse right click
		    /*
		    $("body").on("contextmenu",function(e){
		    	$.Notify({
					 caption: "<b>Right click is not allowed !",
					 content: "Right click is not allowed on this page.",
					 style: {background: 'green', color: '#fff'}, 
					 timeout: 5000
					 });
				 
		        return false;
		    });
		    */
		});
		
		$('#ri_test_id').change(function() {
			var test_id = $('#ri_test_id').val();
			
			$('#ri_test_schd_date').hide();
			$('#ri_candidate_id').hide();
			$("#ri_batch").hide();
			$("#result_inspection").hide();
			<?php 
			if($nUserType != CConfig::UT_INDIVIDAL)
			{
			?>
			$("#download_pdf").hide();
			<?php 
			}
			?>
			
			$(".modal1").show();
				
			$('#ri_test_schd_date').load('ajax/ajax_get_test_dates.php?test_id='+test_id, function(){
				$('#ri_test_schd_date').show();
				 $(".modal1").hide();
			});
		});
	
		var result_cand_ary = "";
		var batch_ary       = "";
		$('#ri_test_schd_date').change(function() {
			$("#result_inspection").hide();
			<?php 
			if($nUserType != CConfig::UT_INDIVIDAL)
			{
			?>
			$("#download_pdf").hide();
			<?php 
			}
			?>
			var test_id = $('#ri_test_id').val();
			var tschd_id = $('#ri_test_schd_date').val();
	
			var current_date = new Date();
			var time_zone    = -current_date.getTimezoneOffset()/60;
			
			$(".modal1").show();
			//alert('ajax/ajax_get_result_candidates.php?test_id='+test_id+'&test_date='+test_date);
			/*$('#ri_candidate_id').load('ajax/ajax_get_result_candidates.php?test_id='+test_id+'&test_date='+test_date+'&time_zone='+time_zone, function(){
				$('#ri_candidate_id').show();
			});*/
	
			result_cand_ary  = new Array();
			batch_ary        = new Array();
			var result_cands = "<option value=''>--Choose Candidate--</option>";
			var batches		 = "<option value=''>--Choose Specific Batch--</option>"
			$.ajax({
					 url: 'ajax/ajax_get_result_candidates.php?test_id='+test_id+'&tschd_id='+tschd_id+'&time_zone='+time_zone,
				dataType: 'json',
				 success: function(data) {
					 $.each(data, function(key, value){
						 result_cand_ary[key] = new Array();
						 
						 result_cand_ary[key]['result'] = value['result'];
						 result_cand_ary[key]['batch']  = value['batch'];
	
						 if(batch_ary.indexOf(value['batch']) == -1 && value['batch'] != "")
						 {
						 	batch_ary.push(value['batch']);
						 	batches   += "<option value='"+value['batch']+"'>"+value['batch']+"</option>";
						 }
						 result_cands += "<option value='"+key+"'>"+value['result']+"</option>";
					 });
	
					 $("#ri_batch").html(batches);
					 $('#ri_candidate_id').html(result_cands);
					 
					 $('#ri_candidate_id').show();
					 <?php 
					 	if($nUserType != CConfig::UT_INDIVIDAL)
					 	{
					 ?>
					 $("#ri_batch").show();
					 <?php 
						}
					 ?>
					 $(".modal1").hide();
				 } 
			});
		});

		<?php 
		if($nUserType != CConfig::UT_INDIVIDAL)
		{
		?>
		function DownloadPDF(obj)
		{
			var test_pnr = $('#ri_candidate_id').val();
			$(obj).attr("href", "ajax/ajax_download_result_pdf.php?test_pnr="+test_pnr+"&inspect_result=1");
		}
		<?php 
		}
		?>
	
		function OnBatchChange()
		{
			$("#result_inspection").hide();
			<?php 
			if($nUserType != CConfig::UT_INDIVIDAL)
			{
			?>
			$("#download_pdf").hide();
			<?php 
			}
			?>
			var result_cands = "<option value=''>--Choose Candidate--</option>";
	
			for(index in result_cand_ary)
			{
				if($("#ri_batch").val() == result_cand_ary[index]['batch'] && $("#ri_batch").val() != "")
				{
					result_cands += "<option value='"+index+"'>"+result_cand_ary[index]['result']+"</option>";
				}
				else if($("#ri_batch").val() == "")
				{
					result_cands += "<option value='"+index+"'>"+result_cand_ary[index]['result']+"</option>";
				}
			}
			$('#ri_candidate_id').html(result_cands);
		}
		
		$('#ri_candidate_id').change(function() {
			var test_pnr = $('#ri_candidate_id').val();

			<?php 
			if($nUserType != CConfig::UT_INDIVIDAL)
			{
			?>
			if(test_pnr)
			{
				$("#download_pdf").show();
			}
			else
			{
				$("#download_pdf").hide();
			}
			<?php 
			}
			?>

			if(test_pnr)
			{
				$(".modal1").show();
				
				$.ajax({
				    dataType: 'json',
				    success: function(data) {
				    	$("#result_inspection").empty();
				    	$("#result_inspection").show();
				    	if(jQuery.isEmptyObject(data))
				    	{
				    		$("#result_inspection").append("<h2>No results found, test was not attempted properly.</h2>");
				    		$(".modal1").hide();
				    		return;
				    	}
				    	
				    	$.each(data, function (qIndex, qInfoAry){
							//alert(dump(qInfoAry));
							tColor = "";
							sStatus = "";
							bgColor = "";
							if(qInfoAry['selected'] == -1 || qInfoAry['selected'] == -2)
							{
								tColor = 'blue';
								sStatus = "Not Attempted";
								bgColor = "#EBF0FF";
							}
							else if(qInfoAry['selected']==qInfoAry['answer'])
							{
								tColor = 'green';
								sStatus = "Correct";
								bgColor = "#F0FEF0";
							}
							else 
							{
								tColor = 'red';
								sStatus = "Wrong";
								bgColor = "LavenderBlush";
							}
							sPara = "<div style='margin:12px;padding:12px;-moz-box-shadow: 3px 3px 5px 6px #ccc; -webkit-box-shadow: 3px 3px 5px 6px #ccc; box-shadow: 3px 3px 5px 6px #ccc;'><div style='margin:12px;padding:12px;background-color:"+bgColor+";border:1px solid #aaa'>";
							sPara += "<div class='col-lg-2 col-md-2 col-sm-2' style='border:1px solid #aaa; color:"+tColor+";background-color:"+bgColor+";'> QID:"+qInfoAry['ques_id']+" </div>";
							sPara += "<br/><br /><span style='background-color: #2FA4E7;color: #ffffff;border-radius: 0.25em;font-size: 75%;font-weight: bold;padding: 0.2em 0.6em 0.3em;'>Subject : "+qInfoAry['subject']+"</span>";
							sPara += "<br /><br /><span style='background-color: #2FA4E7;color: #ffffff;border-radius: 0.25em;font-size: 75%;font-weight: bold;padding: 0.2em 0.6em 0.3em;'>Topic : "+qInfoAry['topic']+"</span>";
		
							if(qInfoAry['para_desc'] != null && qInfoAry['para_desc'] != "")
							{
								sPara += "<br /><br /><div style='margin:5px;padding:5px;text-align : justify; border: 1px solid #ddd;'><h4> Para Description </h4>"+qInfoAry['para_desc']+"</div>";
							}
							sPara += "<blockquote><p>"+(qIndex+1)+"). "+qInfoAry['question']+"</p><small>"+sStatus+"</small></blockquote><br/>";
							
							sPara += "<table class='js-responsive-table table table-bordered' style='font:inherit;'>";
		
							var valid_option = 0;
							for(opt_idx = 0; opt_idx < qInfoAry['options'].length; opt_idx++)
							{
								if(qInfoAry['options'][opt_idx]['option'].trim() != null && qInfoAry['options'][opt_idx]['option'].trim() != "")
								{
									valid_option++;
								}
							}
		
							for(opt_idx = 0; opt_idx < valid_option; opt_idx++)
							{
								/*if(qInfoAry['options'][opt_idx]['option'].trim() != null && qInfoAry['options'][opt_idx]['option'].trim() != "")
								{*/
									if(opt_idx == 0)
									{
										sPara += "<tr>";
									}
									else if((opt_idx % 2) == 0)
									{
										sPara += "</tr><tr>";
										//console.log(sPara);
										//console.log(qInfoAry['options'].length+"");
									}
									
									var opt_class = "";
									var opt_icon  = "";
									if(qInfoAry['options'][opt_idx]['answer'])
									{
										opt_class = "class='alert alert-success'";
										opt_icon  = "<i class='icon-checkmark'></i>";
									}
									else if(typeof(qInfoAry['selected']) == "string")
									{
										if(qInfoAry['selected'].split(",").indexOf(opt_idx+1+"") != -1)
										{
											opt_class = "class='alert alert-error'";
											opt_icon  = "<i class='icon-cancel-2'></i>";	
										}
									}
		
									if(opt_idx == (valid_option - 1) && (opt_idx % 2) == 0)
									{
										sPara += "<td colspan='2' "+opt_class+">";
									}
									else
									{
										sPara += "<td style='width: 50%;' "+opt_class+">";
									}
									sPara += opt_icon+"&nbsp;"+(opt_idx+1)+"). "+qInfoAry['options'][opt_idx]['option'];
									sPara += "</td>";
									
									if(opt_idx == (valid_option - 1))
									{
										sPara += "</tr>";
									}
								//}
							}
							sPara += "</table><br/>";
							
							lbl_cls = "";
							if(tColor == "red")
							{
								lbl_cls = "danger";
							}
							else if(tColor == "green")
							{
								lbl_cls = "success";
							}
							
							if(qInfoAry['selected'] == -1 || qInfoAry['selected'] == -2)
							{
								sPara += "<span style='background-color: #2FA4E7;color: #ffffff;border-radius: 0.25em;font-size: 75%;font-weight: bold;padding: 0.2em 0.6em 0.3em;'>You have not answered this question.</span>";
							}
							else
							{
								sPara += "<span class='label label-"+lbl_cls+"'>Your Choice: "+qInfoAry['selected']+"</span>";
							}
							sPara += "</b>";
							
							sPara += "<br/><br/>"+"<span class='label label-warning'>Correct Answer: "+qInfoAry['answer']+"</span><br /><br /></div></div>";
							$('#result_inspection').append(sPara);
							$('#result_inspection').show();
						});
						
						$("div.mipcat_code_ques").snippet("c",{style:"vim"});
						$(".modal1").hide();
				    },
				    url: 'ajax/ajax_inspect_result.php?test_pnr='+test_pnr
				});
			}
		});
	</script>
	<script type="text/x-mathjax-config">
  		MathJax.Hub.Config({
    		tex2jax: {inlineMath: [["$","$"],["\\(","\\)"]]}
 		});
	</script>
</body>
</html>