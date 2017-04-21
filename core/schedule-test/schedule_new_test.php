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
	$user_type = CSessionManager::Get(CSessionManager::INT_USER_TYPE);
	$time_zone = CSessionManager::Get(CSessionManager::FLOAT_TIME_ZONE);
	$applied_plan = CSessionManager::Get(CSessionManager::INT_APPLIED_PLAN);
	
	$parsAry = parse_url(CUtils::curPageURL());
	$qry = explode("=", $parsAry["query"]);
	
	$reset = date_default_timezone_get();
	date_default_timezone_set($objDB->tzOffsetToName($time_zone));
	$currentDate = date("d-m-Y");
	$jsCode = sprintf( "var dateToday='%s';", date( 'D, d M Y H:i:s')." GMT" );
	$select_combo_date = date("Y/m/d H:i:s", strtotime("+10 minutes"));
	$select_combo_hours = date("H", strtotime($select_combo_date));
	$select_combo_mins = date("i", strtotime($select_combo_date));
	date_default_timezone_set($reset);
	
	$sTestName = "";
	if($qry[0] == "test_name" && !empty($qry[1]))
	{
		printf("<script>save_success = 1; %s</script>", $jsCode);
		$sTestName = urldecode($qry[1]);
	}
	else 
	{
		printf("<script>save_success = 0; %s</script>", $jsCode);
	}
	
	$objBilling = new CBilling();
	
	if($applied_plan == CConfig::SPT_BASIC || $applied_plan == CConfig::SPT_PROFESSIONAL || $applied_plan == CConfig::SPT_ENTERPRISE)
	{
		$retAry = $objBilling->GetRecentRealization($user_id);
		if(!empty($retAry))
		{
			// Process recent realization
		}
	}
	
	$currency = $objBilling->GetCurrencyType($user_id);
	$balance = $objBilling->GetBalance($user_id);
	$projected_balance = $objBilling->GetProjectedBalance($user_id);
	
	$rate_personal_ques = $objBilling->GetPersonalQuesRate($user_id);
	$rate_mipcat_ques 	= $objBilling->GetMIpCATQuesRate($user_id);
	$batch_array		= $objDB->GetBatches($user_id);
	
	$objIncludeJsCSS = new IncludeJSCSS();
	
	$menu_id = CSiteConfig::UAMM_SCHEDULE_TEST;
	$page_id = CSiteConfig::UAP_SCHEDULE_TEST;
?>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="Generator" content="Mastishka Intellisys Private Limited">
<meta name="Author" content="Mastishka Intellisys Private Limited">
<meta name="Keywords" content="">
<meta name="Description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo(CConfig::SNC_SITE_NAME);?>: Schedule Test</title>
<?php 
$objIncludeJsCSS->CommonIncludeCSS("../../");
$objIncludeJsCSS->IncludeIconFontCSS("../../");
$objIncludeJsCSS->IncludeFuelUXCSS ( "../../" );
$objIncludeJsCSS->CommonIncludeJS("../../");
$objIncludeJsCSS->IncludeMetroNotificationJS("../../");
$objIncludeJsCSS->IncludeJqueryValidateMinJS("../../");
$objIncludeJsCSS->IncludeMetroCalenderJS("../../");
$objIncludeJsCSS->IncludeMetroDatepickerJS("../../");
?>
<style type="text/css">
	.modal, .modal.fade.in {
	    top: 15%;
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
	
	.modal1 {
		display:    none;
		position:   fixed;
		z-index:    1000;
		top:        50%;
		left:       60%;
		height:     100%;
		width:      100%;
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
					<form method="POST" action="ajax/ajax_schedule_test.php" id="form_schedule" enctype="multipart/form-data" onsubmit="return OnSubmit();">
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6" style="border-right:1px solid #ddd;">
						        <div class="row">
									<div class="col-lg-7 col-md-7 col-sm-7">
										<label for="schd_type"><b>Select Schedule Type:</b></label>
										<select class="form-control input-sm" id="schd_type" name="schd_type" onchange="OnSchdTypeChange();" onkeyup="OnSchdTypeChange();" onkeydown="OnSchdTypeChange();">
											<option value="<?php echo(CConfig::TST_ONLINE);?>">Preregistered Users (Online)</option>
											<?php 
											if($plan_type == CConfig::SPT_PROFESSIONAL || $plan_type == CConfig::SPT_ENTERPRISE || $user_type == CConfig::UT_SUPER_ADMIN)
											{
											?>
											<option value="<?php echo(CConfig::TST_OTFA_EMAIL);?>">On-the-fly Assessment (Email)</option>
											<option value="<?php echo(CConfig::TST_OTFA_TPIN);?>">On-the-fly Assessment (TPIN)</option>
											<?php
											}
											?>
											<?php 
											if($plan_type == CConfig::SPT_ENTERPRISE || $user_type == CConfig::UT_SUPER_ADMIN)
											{
											?>
											<option value="<?php echo(CConfig::TST_OFFLINE);?>">Offline Schedule</option>
											<?php 
											}
											?>
										</select>
									</div>
								</div><br />
								<div class="row">
									<div class="col-lg-7 col-md-7 col-sm-7">
										<label for="test_id"><b>Select Test:</b></label>
										<select class="form-control input-sm" id="test_id" name="test_id" onchange="SetBatchName();" onkeyup="SetBatchName();" onkeydown="SetBatchName();">
											<?php
												$objDB->PrepareTestCombo($user_id);
											?>
										</select>&nbsp;
									</div>
									<div class="col-lg-5 col-md-5 col-sm-5" style="padding-left: 0px;">
										<a href="javascript:" style="position:relative;top: 25px;" onclick="OnTestDetails()"><img style="position:relative;" src="../../images/question_mark_small.png" width="18px" height="18px"/>Test Details</a>
									</div><br /><br /><br /><br />
								</div>
								<div class="row" id="test_schedule_date_div">
									<div class="col-lg-5 col-md-5 col-sm-5">
										<label for="datepicker1_val"><b>Schedule On (Date):</b></label>
										<div class="metro">
											<div class="input-control text" id="datepicker1">
								    			<input id="datepicker1_val" type="text">
								    			<button class="btn-date" onclick="return false;"></button>
								    		</div>
							    		</div>
						    		</div>
						    		<div class="col-lg-3 col-md-3 col-sm-3">
						    			<label for="hours"><b>Hours(HH):</b></label>
						    			<select class="form-control input-sm" id="hours" name="hours">
											<?php
												for($hours = 0; $hours <= 23; $hours++)
												{
													printf("<option value='%02d' %s>%02d</option>", $hours, ($select_combo_hours == $hours)?"selected='selected'":"", $hours);
												}
											?>
										</select>
						    		</div>
						    		<div class="col-lg-3 col-md-3 col-sm-3">
						    			<label for="minutes"><b>Minutes(MM):</b></label>
						    			<select id="minutes" class="form-control input-sm" name="minutes">
											<?php
												for($minutes = 0; $minutes <= 59; $minutes++)
												{
													printf("<option value='%02d' %s>%02d</option>", $minutes, ($select_combo_mins == $minutes)?"selected='selected'":"", $minutes);
												}
											?>
										</select>
						    		</div>
								</div>
								 <div class="checkbox" id="expire_never_date_div">
								 	<label>
								    	<input type="checkbox" id="expire_never_date" name="expire_never_date" value="" onchange="OnExpireNever()" checked> Expire Never 
								    </label>
								 </div>
								<div class="row" id="test_end_schedule_date_div" style="display:none;">
									<div class="col-lg-5 col-md-5 col-sm-5">
										<label for="datepicker2_val"><b>Expire On (Date):</b></label>
										<div class="metro">
											<div class="input-control text" id="datepicker2">
								    			<input id="datepicker2_val" type="text">
								    			<button class="btn-date" onclick="return false;"></button>
								    		</div>
							    		</div>
						    		</div>
						    		<div class="col-lg-3 col-md-3 col-sm-3">
						    			<label for="expire_hours"><b>Hours(HH):</b></label>
						    			<select class="form-control input-sm" id="expire_hours" name="expire_hours">
											<?php
												for($hours = 0; $hours <= 23; $hours++)
												{
													printf("<option value='%02d' %s>%02d</option>", $hours, ($select_combo_hours == $hours)?"selected='selected'":"", $hours);
												}
											?>
										</select>
						    		</div>
						    		<div class="col-lg-3 col-md-3 col-sm-3">
						    			<label for="expire_minutes"><b>Minutes(MM):</b></label>
						    			<select id="expire_minutes" class="form-control input-sm" name="expire_minutes">
											<?php
												for($minutes = 0; $minutes <= 59; $minutes++)
												{
													printf("<option value='%02d' %s>%02d</option>", $minutes, ($select_combo_mins == $minutes)?"selected='selected'":"", $minutes);
												}
											?>
										</select>
						    		</div>
								</div>
								<!-- <div class="form-group">
									<p style="color:blue;">(Expire Date is Optional)</p>
								</div> -->
								<div class="row" id="time_zone_div">
									<div class="col-lg-8 col-md-8 col-sm-8">
										<label for="select_time_zone"><b>Time Zone:</b></label><br/>
										<select class="form-control input-sm" id="select_time_zone" name="select_time_zone">
											<?php
												$objDB->PopulateTimeZones($time_zone);
											?>
										</select>
										<input type="hidden" id="scheduled_on" value='' name="scheduled_on"/>
										<input type="hidden" id="expire_on" value='' name="expire_on"/>
										<input type="hidden" id="time_zone" name="time_zone"/>
										<input type="hidden" id="cost" name="cost"/>
										<input type="hidden" id="time" name="time"/>
									
									</div>
								</div>
								<div class="row" id="select_batch_div">
									<br />
									<div class="col-lg-6 col-md-6 col-sm-6">
										<label for="batch_id"><b>Select Batches:</b></label><br/>
										<select class="form-control input-sm" id="batch_id" name="batch_id" onchange="OnBatchSelect();" multiple>
											<option value='<?php echo(CConfig::CDB_ID);?>'><?php echo(CConfig::CDB_NAME);?></option>
											<?php 
											foreach($batch_array as $batch_id=>$info)
												printf("<option value='%s'>%s</option>", $batch_id, $info['batch_name']);
											?>
										</select>
									</div>
								</div><br />
								<?php 
								if($plan_type == CConfig::SPT_PROFESSIONAL || $plan_type == CConfig::SPT_ENTERPRISE || $user_type == CConfig::UT_SUPER_ADMIN)
								{
								?>
								<div id="otfa_emails_div">
									<div class="row">
										<div class="col-lg-7 col-md-7 col-sm-7">
											<label><b>Upload Email IDs (Excel xls | xlsx):</b></label>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-7 col-md-7 col-sm-7" id="otfa_email_file_div">
											<div class="metro">
												<div class="input-control file">
												    <input name="email_csv" type="file" />
												    <button class="btn-file"></button>
											    </div>
											</div>
											<a href="../download/email_reg_sample.xlsx">download sample file</a>
										</div>
									</div>
								</div>
								<div id="otfa_tpin_div">
									<div class="row">
										<div class="col-lg-8 col-md-8 col-sm-8">
											<label for="tpin_choice"><b>TPIN Choice:</b>&nbsp;&nbsp;<i data-original-title="This is a unique Test Personal Identification Number (TPIN)." data-toggle="tooltip" trigger="click hover focus" data-placement="right" title="" class="icon-help mip-help"></i></label>
											<select class="form-control input-sm" id="tpin_choice" name="tpin_choice" onchange="OnTPINChoiceChange();" onkeyup="OnTPINChoiceChange();" onkeydown="OnTPINChoiceChange();">
												<option value="0">Provide Required Number Of TPINs</option>
												<option value="1">Upload Candidate Details (Excel xls | xlsx)</option>
											</select>
										</div>
									</div><br />
									<div class="row">
										<div class="col-lg-7 col-md-7 col-sm-7" id="otfa_tpin_text_div">
											<input type="text" class="form-control input-sm" name="tpin_cand_count" id="tpin_cand_count" placeholder="TPIN Count"/>
										</div>
										<div class="col-lg-7 col-md-7 col-sm-7" id="otfa_tpin_file_div">
											<label><b>Browse File (xls | xlsx):</b></label>
											<div class="metro">
												<div class="input-control file">
												    <input name="tpin_cand_csv" id="tpin_cand_csv" type="file" />
												    <button class="btn-file"></button>
											    </div>
											</div>
											<a href="../download/tpin_reg_sample.xlsx">download sample file</a>
										</div>
									</div>
								</div><br />
								<div id="batch_selection_div">
									<div class="row">
										<div class="col-lg-3 col-md-3 col-sm-3">
									       	<div class="radio">
									       		<label>
										            <input type="radio" value='0' name="batch_choice" onchange="OnBatchChoiceChange();" checked='checked'> New Batch
									       		</label>
									       	</div>
									    </div>	
									    <div class="col-lg-4 col-md-4 col-sm-4">
									    	<div class="radio">
									          <label>
									            <input type="radio" value='1' name="batch_choice" onchange="OnBatchChoiceChange();"> Existing Batch
									          </label>
									        </div>
									    </div>
									 </div>
									<div class="row" id="otfa_text_batch_div">
										<div class="col-lg-7 col-md-7 col-sm-7">
											<label><b>Batch Name:</b></label>
											<input type="text" class="form-control input-sm" name="test_batch_name" id="test_batch_name"/>
										</div>
									</div>
									<div class="row"  id="otfa_select_batch_div">
										<div class="col-lg-7 col-md-7 col-sm-7">
											<label for="batch_id"><b>Select Batche:</b></label><br/>
											<select class="form-control input-sm" id="otfa_batch_id" name="otfa_batch_id">
												<option value='<?php echo(CConfig::CDB_ID);?>'><?php echo(CConfig::CDB_NAME);?></option>
												<?php 
												foreach($batch_array as $batch_id=>$info)
													printf("<option value='%s'>%s</option>", $batch_id, $info['batch_name']);
												?>
											</select>
										</div>
									</div>
								</div>
								<?php 
								}
								?>
								<br />
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6">
								<br /><br />
								<div class="col-lg-10 col-md-10 col-sm-10 col-lg-offset-2 col-md-offset-2 col-sm-offset-2">
									<div class="reg-error">
									</div>
								</div>  
							</div>
						</div>
						<div id="cand_select_div">
							<div class="row">
								<div class="col-lg-5 col-md-5 col-sm-5">
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
								<div class="col-lg-2 col-md-2 col-sm-2" style="height:270px;border:1px solid #ddd;">
									<br /><br /><br />
									<div class="row">
										<div class="col-lg-10 col-md-10 col-sm-10  col-lg-offset-2 col-md-offset-2 col-sm-offset-2">
											<input type="button" class="btn btn-xs btn-success" onclick="OnAddAll();" value="Add All &gt;&gt;"/>
										</div>
									</div><br />
									<div class="row">
										<div class="col-lg-10 col-md-10 col-sm-10  col-lg-offset-2 col-md-offset-2 col-sm-offset-2">
											<input type="button" class="btn btn-xs btn-success" onclick="OnAdd();" value="Add &gt;&gt;"/>
										</div>
									</div><br />
									<div class="row">
										<div class="col-lg-10 col-md-10 col-sm-10  col-lg-offset-2 col-md-offset-2 col-sm-offset-2">
											<input type="button" class="btn btn-xs btn-info" onclick="OnRemove();" value="&lt;&lt; Remove"/>
										</div>
									</div><br />
									<div class="row">
										<div class="col-lg-10 col-md-10 col-sm-10  col-lg-offset-2 col-md-offset-2 col-sm-offset-2">
											<input type="button" class="btn btn-xs btn-info" onclick="OnRemoveAll();" value="&lt;&lt; Remove All"/>
										</div>
									</div>
								</div>
								<div class="col-lg-5 col-md-5 col-sm-5">
									<br />
									<div class="row-fluid">
										<select style="height:250px" class="form-control" id="selected_candidate" multiple="multiple">
										</select>
									</div>
									<div class="row-fluid" style="text-align: center;">
										<h5>^ Selected Candidates ^</h5>
									</div>
									<input type="hidden" id="candidate_list" name="candidate_list" value=""/>
									<input type="hidden" id="pnr_list" name="pnr_list" value=""/>
								</div>
							</div>
							<div class="row-fliud">
								<div class="col-lg-7 col-md-7 col-sm-7  col-lg-offset-5 col-md-offset-5 col-sm-offset-5">
									<input type="button" class="btn btn-success" onclick="window.location=window.location;" value="Refresh"/>
									<input id="change" class="btn btn-primary" type="submit" value="Schedule!"/>
								</div>
							</div>
						</div>
						<?php 
						if($plan_type == CConfig::SPT_PROFESSIONAL || $plan_type == CConfig::SPT_ENTERPRISE || $user_type == CConfig::UT_SUPER_ADMIN)
						{
						?>
						<div id="otfa_form_div" style="border:1px solid #ddd;">
							<div class="row">
								<div class="col-lg-offset-4 col-md-offset-4 col-sm-offset-4">
			    					<h5>Please select the information you want from the candidate :</h5>
			    				</div>
			    			</div>
				    		<div class="row">
					    		<div class='col-lg-3 col-md-3 col-sm-3 col-lg-offset-1 col-md-offset-1 col-sm-offset-1'>
					    			<label class="checkbox inline">
										<input type="checkbox" name="FNAME" disabled> First Name 
									</label>
					    		</div>
					    		<div class='col-lg-3 col-md-3 col-sm-3 col-lg-offset-1 col-md-offset-1 col-sm-offset-1'>
									<label class="checkbox inline">
										<input type="checkbox" name="LNAME" disabled> Last Name 
									</label>
								</div>
								<div class='col-lg-3 col-md-3 col-sm-3 col-lg-offset-1 col-md-offset-1 col-sm-offset-1'>
					    			<label class="checkbox inline">
										<input type="checkbox" name="GENDER" onclick="ChangeVal(this);"> Gender 
									</label>
					    		</div>
							</div>
							<div class="row">
					    		<div class='col-lg-3 col-md-3 col-sm-3 col-lg-offset-1 col-md-offset-1 col-sm-offset-1'>
									<label class="checkbox inline">
										<input type="checkbox" name="DOB" onclick="ChangeVal(this);"> Date of Birth 
									</label>
								</div>
								<div class='col-lg-3 col-md-3 col-sm-3 col-lg-offset-1 col-md-offset-1 col-sm-offset-1'>
					    			<label class="checkbox inline">
										<input type="checkbox" name="CONTACT" onclick="ChangeVal(this);"> Contact No. 
									</label>
					    		</div>
					    		<div class='col-lg-3 col-md-3 col-sm-3 col-lg-offset-1 col-md-offset-1 col-sm-offset-1'>
									<label class="checkbox inline">
										<input type="checkbox" name="CITY" onclick="ChangeVal(this);"> City 
									</label>
								</div>
							</div>
							<div class="row">
					    		<div class='col-lg-3 col-md-3 col-sm-3 col-lg-offset-1 col-md-offset-1 col-sm-offset-1'>
					    			<label class="checkbox inline">
										<input type="checkbox" name="STATE" onclick="ChangeVal(this);"> State
									</label>
					    		</div>
					    		<div class='col-lg-3 col-md-3 col-sm-3 col-lg-offset-1 col-md-offset-1 col-sm-offset-1'>
									<label class="checkbox inline">
										<input type="checkbox" name="COUNTRY" onclick="ChangeVal(this);"> Country 
									</label>
								</div>
								<div class='col-lg-3 col-md-3 col-sm-3 col-lg-offset-1 col-md-offset-1 col-sm-offset-1'>
					    			<label class="checkbox inline">
										<input type="checkbox" name="EDU_QUA" onclick="ChangeVal(this);"> Educational Qualification
									</label>
					    		</div>
							</div>
						</div><br />
						<div class="row" id="otfa_submit_div">
							<div class="col-lg-7 col-md-7 col-sm-7  col-lg-offset-5 col-md-offset-5 col-sm-offset-5">
								<input id="otfa_submit" class="btn btn-primary" type="submit" value="Schedule!"/>
							</div>
						</div>
						<?php 
						}
						?>
					</form>
				</div>
			</div>
			
			<div class="modal" id="error_details_modal">
			  	<div class="modal-dialog">
			    	<div class="modal-content">
			    		<div class="modal-header">
			       		 	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			        		<h4 class="modal-title">Erros During Sheet Upload!</h4>
			      		</div>
			    		<div class="modal-body">
			    			<?php
								if(CSessionManager::IsError())
								{
									echo CSessionManager::GetErrorMsg();
								}
							?>
			    		</div>
			    		<div class="modal-footer">
			      			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			    		</div>
			    	</div>
			  	</div>
			</div>
			
			<div class="modal" id="test_details_modal">
			  	<div class="modal-dialog">
			    	<div class="modal-content">
			    		<div class="modal-body" id="test_details_modal_body">
			    		</div>
			    		<div class="modal-footer">
			      			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			    		</div>
			    	</div>
			  	</div>
			</div>
			
			<div class="modal" id="mip_message_box">
			  	<div class="modal-dialog">
			    	<div class="modal-content">
			      		<div class="modal-header">
			       		 	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			        		<h4 class="modal-title">Not Enough Ballance !</h4>
			      		</div>
				      	<div class="modal-body" id="delete_test_modal_body">
				      		<p style="color:blue;text-align:center;font: 125% 'Trebuchet MS', sans-serif;">
							<span style="color:DarkRed;font-weight:bold;">You don't have enough ballance (Projected Balance: <?php echo($projected_balance." ".$currency); ?>), please recharge your account.</span><br/><br/>
							Goto <b>My Account &gt; Account Recharge</b> for payment instruction and recharge.<br/>
							<img src="../../images/account_recharge.png" width="567" height="270"/>
							</p>
				      	</div>
			      		<div class="modal-footer">
				        	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			      		</div>
			    	</div>
			  	</div>
			</div>
			<?php
			include_once (dirname ( __FILE__ ) . "/../../lib/footer.php");
			?>
		</div>
	</div>
	<script type="text/javascript">

		var scheduleOnDate = new Date(); 
		scheduleOnDate.setDate(scheduleOnDate.getDate());
		$("#datepicker1").datepicker({
			date :scheduleOnDate,
			format: "dd mmmm, yyyy"
		});

		var expireOnDate = new Date(); 
		expireOnDate.setDate(expireOnDate.getDate() + 7); 
	
		$("#datepicker2").datepicker({
			format: "dd mmmm yyyy"
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

		var bOffline = false;
		function OnSchdTypeChange()
		{
			var schd_type = $("#schd_type").val();
			bOffline = false;
			
			if(schd_type == <?php echo(CConfig::TST_ONLINE);?>)
			{
				$("#otfa_form_div").hide();
				$("#otfa_emails_div").hide();
				$("#otfa_tpin_div").hide();
				$("#otfa_submit_div").hide();
				$("#batch_selection_div").hide();
				$("#time_zone_div").show();
				$("#test_schedule_date_div").show();
				//$("#test_end_schedule_date_div").show();
				$("#expire_never_date_div").show();
				$(".mipcat_ques_source").show();
				$("#cand_select_div").show();
				$("#select_batch_div").show();
				$("#form_schedule").prop("target", "_self");
				OnExpireNever();
			}
			<?php 
			if($plan_type == CConfig::SPT_ENTERPRISE || $user_type == CConfig::UT_SUPER_ADMIN)
			{
			?>
			else if(schd_type == <?php echo(CConfig::TST_OFFLINE);?>)
			{
				$("#otfa_form_div").hide();
				$("#otfa_emails_div").hide();
				$("#test_schedule_date_div").hide();
			    $("#test_end_schedule_date_div").hide();
			    $("#expire_never_date_div").hide();
				$("#time_zone_div").hide();
			    $(".mipcat_ques_source").hide();
			    $("#otfa_tpin_div").hide();
			    $("#otfa_submit_div").hide();
			    $("#batch_selection_div").hide();
			    $('#test_id option[class="personal_ques_source"]').first().prop('selected', true);
			    $("#cand_select_div").show();
			    $("#select_batch_div").show();
			    $("#form_schedule").prop("target", "_self");
			    bOffline = true;
			}
			<?php 
			}
			if($plan_type == CConfig::SPT_PROFESSIONAL || $plan_type == CConfig::SPT_ENTERPRISE || $user_type == CConfig::UT_SUPER_ADMIN)
			{
			?>
			else if(schd_type == <?php echo(CConfig::TST_OTFA_EMAIL);?>)
			{
				$("#cand_select_div").hide();
				$("#select_batch_div").hide();
				$("#otfa_tpin_div").hide();
				$("#time_zone_div").hide();
				$("#test_schedule_date_div").hide();
				$("#expire_never_date_div").hide();
				$(".mipcat_ques_source").show();
				$("#otfa_form_div").show();
				$("#otfa_emails_div").show();
				$("#otfa_submit_div").show()
				$("#batch_selection_div").show();
				$("#form_schedule").prop("target", "_self");
				OnBatchChoiceChange();
				SetOTFAForm();
			}
			else if(schd_type == <?php echo(CConfig::TST_OTFA_TPIN);?>)
			{
				$("#cand_select_div").hide();
				$("#select_batch_div").hide();
				$("#otfa_emails_div").hide();
				$("#time_zone_div").hide();
				$("#test_schedule_date_div").hide();
				$("#expire_never_date_div").hide();
				$(".mipcat_ques_source").show();
				$("#otfa_form_div").show();
				$("#otfa_tpin_div").show();
				$("#otfa_submit_div").show();
				$("#batch_selection_div").show();
				$("#form_schedule").prop("target", "_blank");
				OnTPINChoiceChange();
				OnBatchChoiceChange();
				SetOTFAForm();
			}
			<?php 
			}
			?>
		}

		<?php 
		if($plan_type == CConfig::SPT_PROFESSIONAL || $plan_type == CConfig::SPT_ENTERPRISE || $user_type == CConfig::UT_SUPER_ADMIN)
		{
		?>
		function OnTPINChoiceChange()
		{
			var val = $("#tpin_choice").val();
			
			if(val == 0)
			{
				$("#otfa_tpin_file_div").hide();
				$("#otfa_tpin_text_div").show();
			}
			else if(val == 1)
			{
				$("#otfa_tpin_text_div").hide();
				$("#otfa_tpin_file_div").show();
			}
		}

		function OnBatchChoiceChange()
		{
			var val = $("input[name=batch_choice]:checked").val();
			
			if(val == 0)
			{
				$("#otfa_select_batch_div").hide();
				$("#otfa_text_batch_div").show();
			}
			else if(val == 1)
			{
				$("#otfa_text_batch_div").hide();
				$("#otfa_select_batch_div").show();
			}
		}

		function SetOTFAForm()
		{
			$(".modal1").show();
			
			$.ajax({
				url : "ajax/ajax_last_otfa_form.php",
				type : "POST",
				data : {"test_id" : $("#test_id").val()},
				dataType : "json",
				success : function(data){

					$.each(data, function(key, value){
						var prop_val = false;
						if(value == 1)
						{
							prop_val = true;	
						}
						
						if(key == "firstname")
						{
							$("input[name='FNAME']").prop("checked", prop_val);
							$("input[name='FNAME']").val(value);
						}
						else if(key == "lastname")
						{
							$("input[name='LNAME']").prop("checked", prop_val);
							$("input[name='LNAME']").val(value);
						}
						else if(key == "gender")
						{
							$("input[name='GENDER']").prop("checked", prop_val);
							$("input[name='GENDER']").val(value);
						}
						else if(key == "dob")
						{
							$("input[name='DOB']").prop("checked", prop_val);
							$("input[name='DOB']").val(value);
						}
						else if(key == "contact_no")
						{
							$("input[name='CONTACT']").prop("checked", prop_val);
							$("input[name='CONTACT']").val(value);
						}
						else if(key == "city")
						{
							$("input[name='CITY']").prop("checked", prop_val);
							$("input[name='CITY']").val(value);
						}
						else if(key == "state")
						{
							$("input[name='STATE']").prop("checked", prop_val);
							$("input[name='STATE']").val(value);
						}
						else if(key == "country")
						{
							$("input[name='COUNTRY']").prop("checked", prop_val);
							$("input[name='COUNTRY']").val(value);
						}
						else if(key == "edu_qualification")
						{
							$("input[name='EDU_QUA']").prop("checked", prop_val);
							$("input[name='EDU_QUA']").val(value);
						}
					});
					$(".modal1").hide();
				}
			});
		}

		function ChangeVal(obj)
		{
			if($(obj).is(':checked'))
			{
				$(obj).val(1);
			}
			else
			{
				$(obj).val(0);
			}
		}
		<?php 
		}
		?>

		function SetBatchName()
		{
			<?php 
			if($plan_type == CConfig::SPT_PROFESSIONAL || $plan_type == CConfig::SPT_ENTERPRISE || $user_type == CConfig::UT_SUPER_ADMIN)
			{
			?>
			$("#test_batch_name").val($("#test_id option:selected").text().replace(" (EZeeAssess)", "").replace(" (Personal)", "")+"-<?php echo($currentDate);?>");
			SetOTFAForm();
			<?php 
			}
			?>
		}

		function OnExpireNever()
		{
			if ($('#expire_never_date').is(':checked')) 
			{
				$("#datepicker2_val").val("");
				$("#test_end_schedule_date_div").hide();			
			}
			else 
			{
				$("#test_end_schedule_date_div").show();
				$("#datepicker2_val").val(expireOnDate.format("dd mmmm yyyy"));
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
			var batch_ids = $("#batch_id").val().join(",");
			var cand_data = "";
			//alert(batch_ids);
			
			/*$("#selected_candidate").load("ajax/ajax_populate_cand_by_batch.php", {'batch_ids' : batch_ids}, function(data){
			});*/

			$(".modal1").show();

			$.ajax({
			  url: "../ajax/ajax_populate_cand_by_batch.php",
			  data: {'batch_ids' : batch_ids},
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
					$(".modal1").hide();
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
		
		function get_time_zone_offset( ) 
		{
		    var current_date = new Date();
		    return -current_date.getTimezoneOffset() / 60;
		}

		function OnSubmit()
		{
			var bRet = true;
			
			var sCandList = "";
			var sPNRList  = "";
			var nCandCount = 0;
			$("#selected_candidate option").each(function(i){
				sCandList += $(this).val() + ";";
		        nCandCount++;
		    });
		    
		    $("#candidate_list").val(sCandList);
			$("#pnr_list").val(sPNRList);

			$("#scheduled_on").val($( "#datepicker1_val" ).val());
	
			$("#expire_on").val($( "#datepicker2_val" ).val());

			
			var current_date = new Date();
			$("#time_zone").val(-current_date.getTimezoneOffset()/60);
			
			var TestRate = null;
			var test_id = $("#test_id option:selected").val();
			//alert("ajax/ajax_get_ques_source.php?test_id="+test_id);

			$(".modal1").show();
			$.ajax({
			  url: "ajax/ajax_get_ques_source.php?test_id="+test_id,
			  dataType: 'json',
			  success: function(result){
					//objUtils.dump(result);
					//alert(result['ques_source']);
					if(result['ques_source'] == "mipcat")
					{
						TestRate = <?php echo($rate_mipcat_ques);?>;
					}
					else if(result['ques_source'] == "personal")
					{
						TestRate = <?php echo($rate_personal_ques);?>;
					}
					$(".modal1").hide();
				},
				error: function(request, textStatus, errorThrown) {
					//alert(textStatus);
				},
				complete: function(request, textStatus) { //for additional info
					//alert(request.responseText);
					//alert(textStatus);
				},
				async: false
			});
			
			var cost = nCandCount * TestRate;
			//alert(<?php echo($projected_balance);?>+" - "+cost+"[ "+nCandCount+", "+TestRate+"]");
			if(<?php echo($projected_balance);?> < cost)
			{
				bRet = false;
				$("#mip_message_box").modal("show");
			}
			
			$("#cost").val(cost);
			
			return bRet;
		}

		function OnTestDetails()
		{
			$(".modal1").show();
			
			$("#test_details_modal_body").load("../ajax/ajax_test_details.php?test_id="+$("#test_id").val(), function(){
				$("#test_details_modal").modal("show");
				$(".modal1").hide();
			});
		}

		function printTime(offset) 
		{
			workDate = new Date();
			UTCDate = new Date();
			UTCDate.setTime(workDate.getTime()+workDate.getTimezoneOffset()*60000);
			tempDate = new Date();
			tempDate.setTime(UTCDate.getTime()+3600000*(offset));
			return tempDate;
		}

		$(document).ready(function () {
			<?php 
			if(CSessionManager::IsError())
			{
			?>
			$("#error_details_modal").modal("show");
			<?php
			CSessionManager::SetError(false) ;
			}
			if(isset($_GET["schd_type"]))
			{
			?>
			$("#schd_type").val(<?php echo($_GET["schd_type"]);?>);
			$("#test_id").val(<?php echo($_GET["test_id"]);?>);
			<?php 
			}
			?>

			<?php
			if(isset($_GET["low_balance"]) && $_GET["low_balance"] == 1)
			{
			?>
			$("#mip_message_box").modal("show");
			<?php 
			}
			?>

			OnSchdTypeChange();
			SetBatchName();
			<?php 
			if($plan_type == CConfig::SPT_ENTERPRISE || $user_type == CConfig::UT_SUPER_ADMIN)
			{
			?>
			if($('#test_id option[class="personal_ques_source"]').length == 0)
			{
				$("#schedule_offline").attr("disabled","disabled");
			}
			<?php 
			}
			?>
			if(save_success == 1)
			{
            	var not = $.Notify({
      				 caption: "Test Scheduled",
      				 content: "The test <?php echo("<b>".$sTestName."</b>"); ?> has been scheduled successfully!",
      				 style: {background: 'green', color: '#fff'}, 
      				 timeout: 5000
      				 });
			}
			
			jQuery.validator.addMethod("rPastDate", function(value, element) {

				var schd_type = $("#schd_type").val();
				
				if(schd_type == <?php echo(CConfig::TST_ONLINE);?>)
				{
					var values  	= value.split(" ");
					var new_value	= values[1].replace(","," ")+values[0]+", "+values[2];
					var hours 		= $("#hours").val();
					var minutes 	= $("#minutes").val();
					var d1 			= new Date(new_value+" "+hours+":"+minutes+":"+"00");
					utcLocal 		= d1.getTime() - (d1.getTimezoneOffset() * 60000);
					var d2 = new Date(dateToday);
					utcDate = new Date();
					utcTime = utcDate.getTime() + (utcDate.getTimezoneOffset() * 60000);
					//alert(new Date(utcLocal).toUTCString() + " <-> " + dateToday);

					var time_zone_to_validate = parseFloat($("#select_time_zone").val());
					var offset = -(time_zone_to_validate);

					var newDate = new Date(utcTime + (3600000*offset));
					//utcLocal= d1.getTime() - (d1.getTimezoneOffset() * 60000);

					var d3 = printTime(time_zone_to_validate);
					//return d1.getTime() > d3.getTime();
					return d1 > d3;
					//return new Date(utcLocal) >= new Date(dateToday);
				}
				else
				{
					return true;
				}
			}, "<p style='color:red;'>* The test schedule on date/time is in past, please select today's date <b>("+printTime(parseFloat($("#select_time_zone").val())).toString().split("GMT")[0].trim()+")</b> or future date with proper time and time zone!</p>");

			jQuery.validator.addMethod("requiredScheduleDate", function(value, element) {

				var retVal = true;
				var schd_type = $("#schd_type").val();
				
				if((schd_type == <?php echo(CConfig::TST_ONLINE);?>) && (value == "" || value == null || typeof value == undefined))
				{
					retVal = false;
				}
				return retVal;
			}, "<p style='color:red;'>* Please select a date on which test to be scheduled!</p>");

			
			jQuery.validator.addMethod("requiredExpireOnDate", function(value, element) {

				var schd_type = $("#schd_type").val();
				
				if(schd_type == <?php echo(CConfig::TST_ONLINE);?>)
				{
					var Startdate		= $("#scheduled_on").val();
					var Enddate 		= $("#expire_on").val();
					if(Enddate)
					{	 
						return Date.parse(Enddate)>=Date.parse(Startdate);	
					}
					else 
					{	
						return true;
					}
				}
				else
				{
					return true;
				}
			}, "<p style='color:red;'>*Expire on date should be later than scheduled on date!</p>");


			jQuery.validator.addMethod("requiredExpireOnDatetime", function(value, element) {

				Startdate = (new Date($("#datepicker1_val").val())).format("yyyy-mm-dd");
				Enddate = (new Date($("#datepicker2_val").val())).format("yyyy-mm-dd");
				
				var hours			= $("#hours").val();
				var expire_hours	= $("#expire_hours").val();
				var minutes			= $("#minutes").val();
				var expire_minutes 	= $("#expire_minutes").val();

				
				if(Enddate)
				{

					if(Startdate == Enddate && hours == expire_hours && minutes == expire_minutes)
					{ 
						return false;
					}
					else 
					{
	
						return true;
					}
				
				}
				else
				{	
					return true;
				}
			}, "<p style='color:red;'>*Expire on date &amp;time should be later than schedule on date &amp; time!</p>");
			
			jQuery.validator.addMethod("requiredExpireOntime", function(value, element) {

				Startdate = (new Date($("#datepicker1_val").val())).format("yyyy-mm-dd");
				Enddate = (new Date($("#datepicker2_val").val())).format("yyyy-mm-dd");
				
				var hours			= $("#hours").val();
				var expire_hours	= $("#expire_hours").val();
				var minutes			= $("#minutes").val();
				var expire_minutes 	= $("#expire_minutes").val();
				
				if(Enddate && Startdate == Enddate)
				{
					if(expire_hours < hours)
					{ 
						return false;
					}
					else 
					{
	
						return true;
					}
				
				}
				else
				{	
					return true;
				}
			}, "<p style='color:red;'>*Expire on date &amp; time should be later than schedule on date &amp; time!</p>");

			jQuery.validator.addMethod("requiredExpireOntimeMin", function(value, element) {

				Startdate = (new Date($("#datepicker1_val").val())).format("yyyy-mm-dd");
				Enddate = (new Date($("#datepicker2_val").val())).format("yyyy-mm-dd");
				
				var hours			= $("#hours").val();
				var expire_hours	= $("#expire_hours").val();
				var minutes			= $("#minutes").val();
				var expire_minutes 	= $("#expire_minutes").val();
				
				if(Enddate && Startdate == Enddate && expire_hours== hours)
				{
					if(expire_minutes < minutes)
					{ 
						return false;
					}
					else 
					{
	
						return true;
					}
				
				}
				else
				{	
					return true;
				}
			}, "<p style='color:red;'>*Expire on date &amp; time should be later than schedule on date &amp; time!</p>");

			jQuery.validator.addMethod("requiredCandList", function(value, element) {

				var retVal = true;
				var schd_type = $("#schd_type").val();
				
				if((schd_type == <?php echo(CConfig::TST_ONLINE);?>) && (value == "" || value == null || typeof value == undefined))
				{
					retVal = false;
				}
				return retVal;				
			}, "<p style='color:red;'>* Please select atleast one candidate from existing registered candidate!</p>");

			jQuery.validator.addMethod("requiredEmailCSV", function(value, element) {

				var retVal = true;
				var schd_type = $("#schd_type").val();
				
				if(schd_type == <?php echo(CConfig::TST_OTFA_EMAIL);?> && (value == "" || value == null || typeof value == undefined))
				{
					retVal = false;
				}
				return retVal;				
			}, "<p style='color:red;'>* Please upload the file containing email details!</p>");

			jQuery.validator.addMethod("requiredTPINCandCSV", function(value, element) {

				var retVal = true;
				var schd_type = $("#schd_type").val();
				var tpin_choice_val = $("#tpin_choice").val();
				
				if(schd_type == <?php echo(CConfig::TST_OTFA_TPIN);?> && (value == "" || value == null || typeof value == undefined) && tpin_choice_val == 1)
				{
					retVal = false;
				}
				return retVal;				
			}, "<p style='color:red;'>* Please upload the file containing candidate details!</p>");

			jQuery.validator.addMethod("requiredTPINCount", function(value, element) {

				var retVal = true;
				var schd_type = $("#schd_type").val();
				var tpin_choice_val = $("#tpin_choice").val();
				
				if(schd_type == <?php echo(CConfig::TST_OTFA_TPIN);?> && (value == "" || value == null || typeof value == undefined) && tpin_choice_val == 0)
				{
					retVal = false;
				}
				return retVal;				
			}, "<p style='color:red;'>* Please provide required number of Test PINs!</p>");
			
			jQuery.validator.addMethod("requiredTestBatchName", function(value, element) {

				var retVal = true;
				var schd_type = $("#schd_type").val();
				var batch_choice = $("input[name=batch_choice]:checked").val();
				
				if((schd_type == <?php echo(CConfig::TST_OTFA_TPIN);?> || schd_type == <?php echo(CConfig::TST_OTFA_EMAIL);?>) && (value == "" || value == null || typeof value == undefined) && batch_choice == 0)
				{
					retVal = false;
				}
				return retVal;				
			}, "<p style='color:red;'>* Please specify the batch name!</p>");
			
			$('#form_schedule').validate({
				errorPlacement: function(error, element) {
					$('#form_schedule div.reg-error').append(error);
				}, rules: {
					'test_id':			{required: true},
					'scheduled_on':		{"requiredScheduleDate": true, "rPastDate": true},
					'expire_on':		{"requiredExpireOnDate":true,"requiredExpireOnDatetime":true,"requiredExpireOntime":true,"requiredExpireOntimeMin":true},
					'candidate_list':	{"requiredCandList": true},
					<?php 
					if($plan_type == CConfig::SPT_PROFESSIONAL || $plan_type == CConfig::SPT_ENTERPRISE || $user_type == CConfig::UT_SUPER_ADMIN)
					{
					?>
					'email_csv':		{requiredEmailCSV: true, accept: "xls|xlsx"},
					'tpin_cand_count':	{requiredTPINCount: true, digits: true, min: 1},
					'tpin_cand_csv':	{requiredTPINCandCSV: true, accept: "xls|xlsx"},
					'test_batch_name':	{requiredTestBatchName: true}
					<?php 
					}
					?>
				}, messages: {
					'test_id':			{required:  "<p style='color:red;'>* Please select a test!</p>"},
					<?php 
					if($plan_type == CConfig::SPT_PROFESSIONAL || $plan_type == CConfig::SPT_ENTERPRISE || $user_type == CConfig::UT_SUPER_ADMIN)
					{
					?>
					'email_csv':		{accept: "<p style='color:red;'>* Please upload the file containing email details in xls or xlsx format only!</p>"},
					'tpin_cand_count':	{digits: "<p style='color:red;'>* Please provide number of Test PINs in digits only!</p>", min: "<p style='color:red;'>* Minimum required number of Test PINs are 1!</p>"},
					'tpin_cand_csv':	{accept: "<p style='color:red;'>* Please upload the file containing candidate details in xls or xlsx format only!</p>"}
					<?php 
					}
					?>
				}
			});
			
			$("#form_schedule").data("validator").settings.ignore = "";
			$(".mip-help").tooltip();
		});
	</script>
</body>
</html>