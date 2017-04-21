<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	include_once("../../lib/session_manager.php");
	include_once("../../lib/site_config.php");
	include_once('../../database/mcat_db.php');
	include_once(dirname(__FILE__)."/../../lib/include_js_css.php");
	include_once(dirname(__FILE__)."/../../lib/site_config.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$objDB = new CMcatDB();
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	
	$batch_array = $objDB->GetBatches($user_id);
	
	$objIncludeJsCSS = new IncludeJSCSS();
	
	$menu_id = CSiteConfig::UAMM_REGISTER_CANDITATES;
	$page_id = CSiteConfig::UAP_REGISTER_USERS;
?>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="Generator" content="Mastishka Intellisys Private Limited">
<meta name="Author" content="Mastishka Intellisys Private Limited">
<meta name="Keywords" content="">
<meta name="Description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo(CConfig::SNC_SITE_NAME); ?>: Register Users</title>
<?php 
$objIncludeJsCSS->IncludeMipcatCSS( "../../" );
$objIncludeJsCSS->CommonIncludeCSS("../../");
$objIncludeJsCSS->IncludeIconFontCSS("../../");
$objIncludeJsCSS->CommonIncludeJS("../../");
$objIncludeJsCSS->IncludeJqueryValidateMinJS("../../");
$objIncludeJsCSS->IncludeBootStrap3TypeHeadMinJS("../../");
?>
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
			<br /><br />
			<div class='col-sm-4 col-md-4 col-lg-4'>
				<form action="ajax/ajax_reg_candidates.php" method="post" enctype="multipart/form-data" name="upld_cands_form" id="upld_cands_form" target="upload_status">
					<div class="row">
						<div class="col-sm-6 col-md-6 col-lg-6">
							<label for="excel_batch"><b>Select Batch:</b></label>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-7 col-md-7 col-lg-7">
						   	<select class="form-control input-sm" id="excel_batch" name="excel_batch">
								<option value='<?php echo(CConfig::CDB_ID);?>'><?php echo(CConfig::CDB_NAME);?></option>
								<?php 
								foreach($batch_array as $batch_id=>$info)
									printf("<option value='%s'>%s</option>", $batch_id, $info['batch_name']);
								?>
							</select>
						</div>
					</div><br />
					
					<div class="row">
						<div class="col-sm-6 col-md-6 col-lg-6">
							<label for="csv"><b>Select File:</b></label>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-9 col-md-9 col-lg-9">
							<div class="metro">
								<div class="input-control file">
								    <input id="csv" name="csv" type="file" />
								    <button class="btn-file"></button>
							    </div>
							</div>
						</div>
					</div><br />
					
					<div class="row">
				      <div class="col-sm-4 col-md-4 col-lg-4">
				        <button id='submit_button' type="submit" class="btn btn-primary">Submit</button>
				      </div>
				    </div><br />
			    
				    <div class="row fluid">
					    <div class="col-sm-12 col-md-12 col-lg-12 upload_error">
						</div>
					</div><br />
					<div class="row fluid">
					    <div class="col-sm-12 col-md-12 col-lg-12">
							<a class="btn btn-info btn-xs" href="../download/candidate_reg_sample.xls"><i class="icon-download"></i> Download</a> sample candidate registration file.
						</div>
					</div>
				</form>
			</div>
			<div class='col-sm-1 col-md-1 col-lg-1' style="text-align: center;border-left:1px dotted #003399;border-right:1px dotted #003399;height: 300px;">
				<p style="margin-top: 145px;">OR</p>
			</div>
			<div class='col-sm-7 col-md-7 col-lg-7'>
				<div class="row">
					<div class="col-sm-6 col-md-6 col-lg-6">
						<label for="excel_batch"><b>Select Batch:</b></label>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-4 col-md-4 col-lg-4">
					   	<select class="form-control input-sm" id="batch" name="batch" onkeyup="OnBatchSelect();" onkeydown="OnBatchSelect();" onchange="OnBatchSelect();">
							<option value='<?php echo(CConfig::CDB_ID);?>'><?php echo(CConfig::CDB_NAME);?></option>
							<?php 
							foreach($batch_array as $batch_id=>$info)
								printf("<option value='%s'>%s</option>", $batch_id, $info['batch_name']);
							?>
						</select>
					</div>
				</div><br />
				<div class="row">
					<div class="col-sm-12 col-md-12 col-lg-12">
						<label><b>Copy and E-mail link given below to candidate/s for registration:</b></label>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 col-md-12 col-lg-12">
						<textarea class="form-control" style="cursor:text" id="reg_link" rows="3" cols="80" readonly="readonly"><?php echo(CSiteConfig::ROOT_URL);?>/login/register-cand.php?owner=<?php echo($user_id);?></textarea><br/>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 col-md-12 col-lg-12">
						<div class="checkbox">
							<label><input id="exclude_contact_num" type="checkbox" onchange="OnExcludeContactNum();" /><span style="color:red;">Exclude contact number while registration</span></label>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12 col-md-12 col-lg-12">	
					<iframe name="upload_status" width="100%" height="400" src="" frameborder=0 style="border:1px solid #aaa;"></iframe>
				</div>
			</div>
			<?php
			include_once (dirname ( __FILE__ ) . "/../../lib/footer.php");
			?>
		</div>
	</div>
	<script type="text/javascript">
		$('#upld_cands_form').validate({
			errorPlacement: function(error, element) {
				//$(error).insertAfter(element);
				$('#upld_cands_form div.upload_error').append(error);
			}, rules: {
				'csv':			{required: true, accept: "xls|xlsx"}
			}, messages: {
				'csv':			{required: "<span style='color:red;font-weight:bold;'>Please select a valid &lsquo;xls or xlsx&rsquo; (MS Excel) file to upload!</span>", accept: "<span style='color:red;font-weight:bold;'>Please select a valid &lsquo;xls or xlsx&rsquo; (MS Excel) file to upload!</span>"}
			}
		});

		function OnExcludeContactNum()
		{
			if ($('#exclude_contact_num').is(':checked')) 
			{
			    $("#reg_link").val($("#reg_link").val() + "&exld_contact=1");
			}
			else
			{
				$("#reg_link").val("<?php echo(CSiteConfig::ROOT_URL);?>/login/register-cand.php?owner=<?php echo($user_id);?>"+"&batch_id="+$("#batch").val());
			} 
		}

		function OnBatchSelect()
		{
			if ($('#exclude_contact_num').is(':checked')) 
			{
				$("#reg_link").val("<?php echo(CSiteConfig::ROOT_URL);?>/login/register-cand.php?owner=<?php echo($user_id);?>"+"&batch_id="+$("#batch").val()+"&exld_contact=1");
			}
			else
			{
				$("#reg_link").val("<?php echo(CSiteConfig::ROOT_URL);?>/login/register-cand.php?owner=<?php echo($user_id);?>"+"&batch_id="+$("#batch").val());
			}
		}

		$(document).ready(function () {
			OnBatchSelect();
		});
	</script>
</body>
</html>