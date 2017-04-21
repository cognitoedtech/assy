<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	include_once("../../database/mcat_db.php");
	include_once("../../lib/session_manager.php");
	include_once("../../lib/billing.php");
	include_once(dirname(__FILE__)."/../../lib/include_js_css.php");
	include_once(dirname(__FILE__)."/../../lib/site_config.php");

	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -

	$objDB 		= new CMcatDB();
	$objBilling = new CBilling();
	
	$user_id     = CSessionManager::Get(CSessionManager::STR_USER_ID);
	$user_type   = $objDB->GetUserType($user_id);
	
	$projected_balance 	= $objBilling->GetProjectedBalance($user_id);
	$balance 			= $objBilling->GetBalance($user_id);
	
	$currencyPrefix = NULL;
	$currency 		= $objBilling->GetCurrencyType($user_id);
	
	if($currency == "USD")
	{
		$currencyPrefix = "<img src='../../images/dollar.png' id='inst_dollar' style='position:relative;bottom:2px;'/>";
	}
	else
	{
		$currencyPrefix =  "<img src='../../images/rupees.png' id='inst_dollar' style='position:relative;bottom:2px;'/>";
	}
	
	$processed = 0;
	
	if(!empty($_GET['processed']))
	{ 
		$procevalue = $_GET['processed'];
		
		if($procevalue == 1 )
		{
			$processed = $_GET['processed'];
		}
		else if($procevalue == 2)
		{
			$processed = $_GET['processed'];
			
		}else if($procevalue == 3)
		{
			$processed = $_GET['processed'];
			
		}
	}
	
	printf("<script>save_success='%s'</script>",$processed);
	
	$objIncludeJsCSS = new IncludeJSCSS();
	
	$menu_id = CSiteConfig::UAMM_MY_COORDINATORS;
	$page_id = CSiteConfig::UAP_MANAGE_COORDINATORS;
	
?>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="Generator" content="Mastishka Intellisys Private Limited">
<meta name="Author" content="Mastishka Intellisys Private Limited">
<meta name="Keywords" content="">
<meta name="Description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo(CConfig::SNC_SITE_NAME);?>: Manage Coordinator</title>
<?php 
$objIncludeJsCSS->IncludeDatatablesBootstrapCSS("../../");
$objIncludeJsCSS->IncludeDatatablesResponsiveCSS("../../");
$objIncludeJsCSS->CommonIncludeCSS("../../");
$objIncludeJsCSS->IncludeIconFontCSS("../../");
$objIncludeJsCSS->CommonIncludeJS("../../");
$objIncludeJsCSS->IncludeJqueryDatatablesMinJS("../../");
$objIncludeJsCSS->IncludeDatatablesTabletoolsMinJS("../../");
$objIncludeJsCSS->IncludeDatatablesBootstrapJS("../../");
$objIncludeJsCSS->IncludeDatatablesResponsiveJS("../../");
$objIncludeJsCSS->IncludeJqueryValidateMinJS("../../");
?>
<style type="text/css">
	.modal, .modal.fade.in {
	    top: 15%;
	}
	
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
		<div class="col-lg-3 col-md-3 col-sm-3">
			<?php 
			include_once(dirname(__FILE__)."/../../lib/sidebar.php");
			?>
		</div>
		<div class="col-lg-9 col-md-9 col-sm-9" style="border-left: 1px solid #ddd; border-top: 1px solid #ddd;">
			<div class="modal1"></div>
			<br />
			<div id='TableToolsPlacement'>
			</div><br />
		    <div class="form-inline">
		        <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
		        	<thead>
						<tr>
							<th data-class="expand" ><font color="#000000">Name</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Contact</font></th>
							<th data-hide="phone,tablet"><font color="#000000">E-mail</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Location(city,state)</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Department</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Balance</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Projected Balance</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Edit Account Details</font></th>
						</tr>
					</thead>
					<?php
						$objBilling->PopulateCoordinator($user_id);
					?>
					<tfoot>
						<tr>
							<th data-class="expand" ><font color="#000000">Name</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Contact</font></th>
							<th data-hide="phone,tablet"><font color="#000000">E-mail</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Location(city,state)</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Department</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Balance</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Projected Balance</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Edit Account Details</font></th>
						</tr>
					</tfoot>
		        </table>
		    </div><br /><br />
		    
		    <div class="modal" id="edit_details_modal">
			  	<div class="modal-dialog">
			    	<div class="modal-content">
			    		<div class="modal-header">
				       	 	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				        	<h4 id='modal-title' class="modal-title">Edit Details</h4>
				      	</div>
			    		<div class="modal-body">
			    			<ul class="nav nav-tabs">
							  <li class="active"><a href="#recharge_account" data-toggle="tab">Recharge account</a></li>
							  <li class=""><a href="#reclaim_amount" data-toggle="tab">Reclaim amount</a></li>
							</ul>
							<div id="myTabContent" class="tab-content" style="height: 100%;">
							  <div class="tab-pane fade active in" id="recharge_account" style="height: 100%;">
							  	<form id="recharge_form" method="post" action="post_get/form_mng_cordntr_exec.php">
								  	<div class="container">
								  		<br />
								  		<div class="row fluid">
										  	<div class="form-group">
										      <label for="insert_recharge_amount" class="col-lg-2 col-md-2 col-sm-2 control-label">Recharge Amount :</label>
										      <div class="col-lg-3 col-md-3 col-sm-3">
										      	<div class="input-group">
													<span class="input-group-addon"><?php echo($currencyPrefix); ?></span>
													<input class="form-control" id="insert_recharge_amount" name="insert_recharge_amount" type="text">
												</div>
										      </div>
										    </div>
										</div>
										<div class="row fluid">
											<div class="col-lg-5 col-md-5 col-sm-5" id="recharge_error_div" style="text-align: right;">
											</div>
										</div><br />
								    
								    	<div class="row fluid">
										    <div class="form-group">
										      <label for="self_proj_balance" class="col-lg-2 col-md-2 col-sm-2 control-label">Projected Balance :</label>
										      <div class="col-lg-3 col-md-3 col-sm-3">
										      	<div class="input-group">
													<span class="input-group-addon"><?php echo($currencyPrefix); ?></span>
													<input class="form-control" value="<?php echo($projected_balance);?>" id="self_proj_balance" name="self_proj_balance" type="text" readonly>
												</div>
										      </div>
										    </div>
										</div><br />
										
										<div class="row fluid">
										    <div class="form-group">
										      <label for="self_balance" class="col-lg-2 col-md-2 col-sm-2 control-label">Balance :</label>
										      <div class="col-lg-3 col-md-3 col-sm-3">
										      	<div class="input-group">
													<span class="input-group-addon"><?php echo($currencyPrefix); ?></span>
													<input class="form-control" id="self_balance" value="<?php echo($balance);?>" name="self_balance" type="text" readonly>
												</div>
										      </div>
										    </div>
										 </div><br />
										 
										 <div class="row fluid">
										    <div class="form-group">
										     	<div class="col-lg-6 col-md-6 col-sm-6  col-lg-offset-2 col-sm-offset-2 col-md-offset-2">
											        <button type="submit" class="btn btn-primary">Recharge</button>
											    </div>
										    </div>
										 </div>
										 
										 <input type="hidden" name="edit_details" value="recharge" />
										 <input type="hidden" id="recharge_coord_id" name="coord_id" />
								  	</div>
							  	</form>
							  </div>
							  <div class="tab-pane fade" id="reclaim_amount">
							  	<form id="reclaim_form" method="post" action="post_get/form_mng_cordntr_exec.php">
								    <div class="container">
								  		<br />
								  		<div class="row fluid">
										  	<div class="form-group">
										      <label for="insert_reclaim_amount" class="col-lg-2 col-md-2 col-sm-2 control-label">Reclaim Amount :</label>
										      <div class="col-lg-3 col-md-3 col-sm-3">
										      	<div class="input-group">
													<span class="input-group-addon"><?php echo($currencyPrefix); ?></span>
													<input class="form-control" id="insert_reclaim_amount" name="insert_reclaim_amount" type="text">
												</div>
										      </div>
										    </div>
										</div>
										<div class="row fluid">
											<div class="col-lg-5 col-md-5 col-sm-5" id="reclaim_error_div" style="text-align: right;">
											</div>
										</div><br />
								    
								    	<div class="row fluid">
										    <div class="form-group">
										      <label for="cord_proj_balance" class="col-lg-2 col-md-2 col-sm-2 control-label">Coordinator Projected Balance :</label>
										      <div class="col-lg-3 col-md-3 col-sm-3">
										      	<div class="input-group">
													<span class="input-group-addon"><?php echo($currencyPrefix); ?></span>
													<input class="form-control" id="cord_proj_balance" name="cord_proj_balance" type="text" readonly>
												</div>
										      </div>
										    </div>
										</div><br />
										
										<div class="row fluid">
										    <div class="form-group">
										      <label for="cord_balance" class="col-lg-2 col-md-2 col-sm-2 control-label">Coordinator Balance :</label>
										      <div class="col-lg-3 col-md-3 col-sm-3">
										      	<div class="input-group">
													<span class="input-group-addon"><?php echo($currencyPrefix); ?></span>
													<input class="form-control" id="cord_balance" name="cord_balance" type="text" readonly>
												</div>
										      </div>
										    </div>
										 </div><br />
										 
										 <div class="row fluid">
										    <div class="form-group">
										     	<div class="col-lg-6 col-md-6 col-sm-6 col-lg-offset-2 col-sm-offset-2 col-md-offset-2">
											        <button type="submit" class="btn btn-primary">Reclaim</button>
											    </div>
										    </div>
										 </div>
										 
										 <input type="hidden" name="edit_details" value="reclaim" />
										 <input type="hidden" id="reclaim_coord_id" name="coord_id" />
								  	</div>
							  	</form>
							  </div>
							</div>
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

	jQuery.validator.addMethod("ValidateRechargeAmount", function(value, element) {
		var proj_bal = parseFloat($("#self_proj_balance").val());
		if(value <= proj_bal)
		{
			return true;
		}
		else
		{
			return false;
		}
	}, "<span style='color: red;'>* Recharge amount can not be greater than projected balance</span>");

	jQuery.validator.addMethod("ValidateReclaimAmount", function(value, element) {
		var proj_bal = parseFloat($("#cord_proj_balance").val());
		if(value <= proj_bal)
		{
			return true;
		}
		else
		{
			return false;
		}
	}, "<span style='color: red;'>* Reclaim amount can not be greater than projected balance</span>");

	$.validator.addMethod('positiveNumber',
		    function (value) { 
		        return Number(value) > 0;
		    }, '<span style="color:red;">* Amount should be greater than 0.</span>');

	
	$(document).ready(function () {
		'use strict';

		var table;
		var tableElement;
		var responsiveHelper = undefined;
		var breakpointDefinition = {
		        tablet: 1024,
		        phone : 480
		    };
		$.fn.dataTable.TableTools.defaults.sSwfPath = "<?php $objIncludeJsCSS->IncludeDatatablesCopy_CSV_XLS_PDF("../../");?>";
		    tableElement = $('#example');
		    table = tableElement.dataTable({
		    	"sDom": 'T<"clear">lfrtip<"clear spacer">T',
		    	"bPaginate": true,
		    	"bFilter": true,
		    	"oTableTools": {
		            "aButtons": [
						{
						    "sExtends": "csv",
						    "mColumns": [ 0, 1, 2, 3, 4, 5, 6 ]
						}
		            ]
		        },
		        autoWidth      : false,
		        //ajax           : './arrays.txt',
		        preDrawCallback: function () {
		            // Initialize the responsive datatables helper once.
		            if (!responsiveHelper) {
		                responsiveHelper = new ResponsiveDatatablesHelper(tableElement, breakpointDefinition);
		            }
		            var oTableTools = TableTools.fnGetInstance( 'example' );
		            $('#TableToolsPlacement').before( oTableTools.dom.container );
		        },
		        rowCallback    : function (nRow) {
		            responsiveHelper.createExpandIcon(nRow);
		        },
		        drawCallback   : function (oSettings) {
			        //alert("hello");
		            responsiveHelper.respond();
		        }
		    });

		    $("#recharge_form").validate({
		    	errorPlacement: function(error, element) {
			    	$("#recharge_error_div").append(error);
		    	    //error.insertBefore(element);
		    	},
		    	rules: {
		    		insert_recharge_amount:{
	                	required:true,
	                	number: true,
	                	'ValidateRechargeAmount' : true,
	                	'positiveNumber' : true
	             	}
			    },
			    messages: {
			    	insert_recharge_amount:{
						required:	"<span style='color:red;'>* Please enter the recharge amount</span>",
	        	 		number:		"<span style='color:red;'>* Recharge amount should contain numbers only</span>"
					}
				},
				submitHandler: function(form) {
					$('#edit_details_modal').modal('hide');
	    			form.submit();
	    		}
			});

		    $("#reclaim_form").validate({
		    	errorPlacement: function(error, element) {
		    		$("#reclaim_error_div").append(error);
		    	},
		    	rules: {
		    		insert_reclaim_amount:{
	                	required:true,
	                	number: true,
	                	'ValidateReclaimAmount' : true,
	                	'positiveNumber' : true
	             	}
			    },
			    messages: {
			    	insert_reclaim_amount:{
						required:	"<span style='color:red;'>* Please enter the recharge amount</span>",
	        	 		number:		"<span style='color:red;'>* Recharge amount should contain numbers only</span>"
					}
				},
				submitHandler: function(form) {
					$('#edit_details_modal').modal('hide');
	    			form.submit();
	    		}
			});

		    if(save_success == 2)
			{
				 var not = $.Notify({
					 caption: "Recharge Coordinator account",
					 content: "Coordinator account has been recharged successfully!",
					 style: {background: 'green', color: '#fff'}, 
					 timeout: 5000
					 });
			}
		    else if(save_success == 3)
		    {
		    	 var not = $.Notify({
					 caption: "Reclaim Coordinator account",
					 content: "You have successfully reclaimed amount from coordinator account!",
					 style: {background: 'green', color: '#fff'}, 
					 timeout: 5000
					 });
			}
	});

	function OnEditDetails(obj)
	{
		$("#recharge_form").validate().resetForm();
		$("#reclaim_form").validate().resetForm();
		$("#recharge_coord_id").val($(obj).attr("coord_id"));
		$("#reclaim_coord_id").val($(obj).attr("coord_id"));
		$("#cord_proj_balance").val($(obj).attr("proj_balance"));
		$("#cord_balance").val($(obj).attr("main_balance"));
		$("#edit_details_modal").modal("show");
	}
	</script>
</body>
</html>