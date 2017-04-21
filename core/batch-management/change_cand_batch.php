<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	include_once(dirname(__FILE__)."/../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/../../lib/billing.php");
	include_once(dirname(__FILE__)."/../../lib/include_js_css.php");
	include_once(dirname(__FILE__)."/../../lib/site_config.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$objDB = new CMcatDB();
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	
	$parsAry = parse_url(CUtils::curPageURL());
	$qry = explode("=", $parsAry["query"]);
	
	if($qry[0] == "save_success" && !empty($qry[1]))
	{
		printf("<script>save_success = 1;</script>");
	}
	else
	{
		printf("<script>save_success = 0;</script>");
	}
	
	$batch_array		= $objDB->GetBatches($user_id); 
	
	$objIncludeJsCSS = new IncludeJSCSS();
	
	$menu_id = CSiteConfig::UAMM_BATCH_MANAGEMENT;
	$page_id = CSiteConfig::UAP_CAHNGE_BATCH;
?>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="Generator" content="Mastishka Intellisys Private Limited">
<meta name="Author" content="Mastishka Intellisys Private Limited">
<meta name="Keywords" content="">
<meta name="Description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo(CConfig::SNC_SITE_NAME); ?>: Change Batch</title>
<?php 
$objIncludeJsCSS->CommonIncludeCSS("../../");
$objIncludeJsCSS->IncludeIconFontCSS("../../");
$objIncludeJsCSS->CommonIncludeJS("../../");
$objIncludeJsCSS->IncludeMetroNotificationJS("../../");
$objIncludeJsCSS->IncludeJqueryValidateMinJS("../../");
?>

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
		            url('../../images/page_loading.gif') 
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
	include_once(dirname(__FILE__)."/../../lib/header.php");
	?>

	<!-- --------------------------------------------------------------- -->
	<br />
	<br />
	<br />
	<div class='row-fluid'>
		<div class="col-sm-3 col-md-3 col-lg-3">
			<?php 
			include_once(dirname(__FILE__)."/../../lib/sidebar.php");
			?>
		</div>
		<div class="col-sm-9 col-md-9 col-lg-9" style="border-left: 1px solid #ddd; border-top: 1px solid #ddd;">
			<div class="modal1"></div>
			<br />
			<div class="row">
				<div class="col-sm-12 col-md-12 col-lg-12">
					<div class="reg-error" style="text-align: center;">
					</div>
				</div>
			</div>
			<div class="row fluid">
				<div class="col-sm-12 col-md-12 col-lg-12">
					<form method="POST" action="post_get/form_change_cand_batch.php" id="form_change_batch" onsubmit="return OnSubmit();">
						<div class="row">
							<div class="col-sm-6 col-md-6 col-lg-6" style="border-right:1px solid #ddd;">
								<div class="col-sm-5 col-md-5 col-lg-5 col-sm-offset-5 col-md-offset-5 col-lg-offset-5">
									<label for="from_batch"><b>From:</b></label>
									<select class="form-control input-sm" id="from_batch" name="from_batch" onkeyup="OnBatchSelect();" onkeydown="OnBatchSelect();" onchange="OnBatchSelect();">
										<option value='<?php echo(CConfig::CDB_ID);?>'><?php echo(CConfig::CDB_NAME);?></option>
										<?php 
										foreach($batch_array as $batch_id=>$info)
											printf("<option value='%s'>%s</option>", $batch_id, $info['batch_name']);
										?>
									</select>
								</div>
								<br /><br /><br /><br /><br /><br /><br />
							</div>
							<div class="col-sm-6 col-md-6 col-lg-6">
								<div class="col-sm-5 col-md-5 col-lg-5 col-sm-offset-2 col-md-offset-2 col-lg-offset-2">
									<label for="to_batch"><b>To:</b></label>
									<select class="form-control input-sm" id="to_batch" name="to_batch">
									</select>
								</div><br /><br /><br /><br /><br /><br /><br />
							</div>
						</div>
						<div class="row">
							<div class="col-sm-5 col-md-5 col-lg-5">
								<div class="row-fluid">
									<span style="font-size: 12px;"><b>Registered Candidates List</b>(<span id="active_cands"></span> active out of <span id="total_cands"></span> in selected batch):</span>
								</div>
								<div class="row-fluid">
									<select style="height:250px" class="form-control" id="choose_candidate" multiple="multiple">
									</select>
								</div>
								<div class="row-fluid" style="text-align: center;">
									<h5>^ Choose From ^</h5>
								</div>
							</div>
							<div class="col-sm-2 col-md-2 col-lg-2" style="height:270px;border:1px solid #ddd;">
								<br /><br /><br />
								<div class="row">
									<div class="col-sm-10 col-md-10 col-lg-10 col-sm-offset-2 col-md-offset-2 col-lg-offset-2">
										<input type="button" class="btn btn-xs btn-success" onclick="OnAddAll();" value="Add All &gt;&gt;"/>
									</div>
								</div><br />
								<div class="row">
									<div class="col-sm-10 col-md-10 col-lg-10 col-sm-offset-2 col-md-offset-2 col-lg-offset-2">
										<input type="button" class="btn btn-xs btn-success" onclick="OnAdd();" value="Add &gt;&gt;"/>
									</div>
								</div><br />
								<div class="row">
									<div class="col-sm-10 col-md-10 col-lg-10 col-sm-offset-2 col-md-offset-2 col-lg-offset-2">
										<input type="button" class="btn btn-xs btn-info" onclick="OnRemove();" value="&lt;&lt; Remove"/>
									</div>
								</div><br />
								<div class="row">
									<div class="col-sm-10 col-md-10 col-lg-10 col-sm-offset-2 col-md-offset-2 col-lg-offset-2">
										<input type="button" class="btn btn-xs btn-info" onclick="OnRemoveAll();" value="&lt;&lt; Remove All"/>
									</div>
								</div>
							</div>
							<div class="col-sm-5 col-md-5 col-lg-5">
								<br />
								<div class="row-fluid">
									<select style="height:250px" class="form-control" id="selected_candidate" multiple="multiple">
									</select>
								</div>
								<div class="row-fluid" style="text-align: center;">
									<h5>^ Selected Candidates ^</h5>
								</div>
								<input type="hidden" id="candidate_list" name="candidate_list" value=""/>
							</div>
						</div>
						<div class="row-fliud">
							<div class="col-sm-7 col-md-7 col-lg-7 col-sm-offset-5 col-md-offset-5 col-lg-offset-5">
								<input type="button" class="btn btn-success" onclick="window.location=window.location;" value="Refresh"/>
								<input id="change" class="btn btn-primary" type="submit" value="Change"/>
							</div>
						</div>
					</form>
				</div>
			</div><br /><br /><br />
			<?php
			include_once (dirname ( __FILE__ ) . "/../../lib/footer.php");
			?>
		</div>
	</div>
	<script type="text/javascript">
		$('#from_batch').change(function(){
		    $('#to_batch').html(''); //Clear
		    $('#from_batch option:not(:selected)')
		        .clone()
		        .appendTo('#to_batch');
		    $("#selected_candidate").empty();
		});
		
		function OnAdd()
		{
			var cand_list_val = $("#choose_candidate").val();
			
			for (index in cand_list_val)
			{
				$("#selected_candidate").append("<option style='color:darkblue;' value='"+cand_list_val[index]+"'>"+$("#choose_candidate option[value="+cand_list_val[index]+"]").text()+"</option>");
				$("#choose_candidate option[value="+cand_list_val[index]+"]").remove();
			}
			
		}
		
		function OnAddAll()
		{	
			if($("#choose_candidate").html() != "")
			{
				//$('#selected_candidate').html(''); //Clear
				$('#choose_candidate option')
					.clone()
					.appendTo('#selected_candidate');
				$("#choose_candidate").empty();
			}
		}
		
		function OnRemoveAll()
		{
			if($("#selected_candidate").html() != "")
			{
				//$('#choose_candidate').html(''); //Clear
				$('#selected_candidate option')
					.clone()
					.appendTo('#choose_candidate');
				$("#selected_candidate").empty();
			}
		}
	
		function OnBatchSelect()
		{
			$('#selected_candidate').html('');
			var batch_id = $("#from_batch").val();
			var cand_data = "";

			$('body').on({
			    ajaxStart: function() { 
			    	$(this).addClass("loading"); 
			    },
			    ajaxStop: function() { 
			    	$(this).removeClass("loading"); 
			    }    
			});
	
			$.ajax({
			  url: "../ajax/ajax_populate_cand_by_batch.php",
			  data: {'batch_ids' : batch_id},
			  type: 'POST',
			  dataType: 'json',
			  success: function(data){
					$.each(data, function(key, value){
						if(key == "active_count")
						{
							$("#active_cands").text(value);
						}
						else if(key == "total_count")
						{
							$("#total_cands").text(value);
						}
						else
						{
							cand_data += value;
						}
					});
					$("#choose_candidate").html(cand_data);
				},
				async: false
			});
		}
		
		function OnRemove()
		{
			var cand_list_val = $("#selected_candidate").val();
			//var cand_list_text = $("#choose_candidate").text();
			
			for (index in cand_list_val)
			{
				$("#choose_candidate").append("<option style='color:darkblue;' value='"+cand_list_val[index]+"'>"+$("#selected_candidate option[value="+cand_list_val[index]+"]").text()+"</option>");
				$("#selected_candidate option[value="+cand_list_val[index]+"]").remove();
			}
		}
	
		function OnSubmit()
		{
			var bRet = true;
			var sCandAry  = new Array();
			$("#selected_candidate option").each(function(i){
				sCandAry.push("'"+$(this).val()+"'");
		    });
		    
		    $("#candidate_list").val(sCandAry.join(","));
			return bRet;
		}
	
		 		
		$(document).ready(function () {
	
			if(save_success == 1)
			{
				 var not = $.Notify({
    				 caption: "Batch Updated",
    				 content: "Batch of candidates has been updated successfully!",
    				 style: {background: 'green', color: '#fff'}, 
    				 timeout: 5000
    				 });
			}
	
			OnBatchSelect();
			$('#form_change_batch').validate({
				errorPlacement: function(error, element) {
					$('div.reg-error').append(error);
				}, rules: {
					'candidate_list':	{required: true}
				}, messages: {
					'candidate_list':	{required:  "<p style='color:red;'>* Please select atleast one candidate from existing registered candidate!</p>" }
				}
			});
			
			$("#form_change_batch").data("validator").settings.ignore = "";
	
			 $('#to_batch').html(''); //Clear
			 $('#from_batch option:not(:selected)')
			        .clone()
			        .appendTo('#to_batch');
					
			if($("#to_batch").html() == "")
			{
				$("#change").attr("disabled","disabled");
			}
		});
	</script>
</body>
</html>