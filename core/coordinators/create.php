<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
	include_once("../../lib/session_manager.php");
	include_once("../../database/config.php");
	include_once("../../lib/user_manager.php");
    include_once("../../lib/site_config.php") ;
    include_once("../../database/mcat_db.php");
    include_once("../../lib/billing.php");
	include_once("../../lib/utils.php") ;
	include_once(dirname(__FILE__)."/../../lib/include_js_css.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URLe
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$objDB = new CMcatDB();
	$objBilling = new CBilling();
	
	$user_id     = CSessionManager::Get(CSessionManager::STR_USER_ID); 
	$user_type   = $objDB->GetUserType($user_id);
	$projected_balance 	= $objBilling->GetProjectedBalance($user_id);
	$coordinator = 0;
	
	if(!empty($_GET['coordinator']))
	{
		$coordinator = $_GET['coordinator'];
	}
	$currencyPrefix = NULL;
	$currency = $objBilling->GetCurrencyType($user_id);
	if($currency == "USD")
	{
		$currencyPrefix = "<img src='../../images/dollar.png' id='inst_dollar' style='position:relative;bottom:2px;'/>";
	}
	else
	{
		$currencyPrefix =  "<img src='../../images/rupees.png' id='inst_dollar' style='position:relative;bottom:2px;'/>";
	}
	
	$objIncludeJsCSS = new IncludeJSCSS();
	$objUM = new CUserManager() ;
	
	$menu_id = CSiteConfig::UAMM_MY_COORDINATORS;
	$page_id = CSiteConfig::UAP_REGISTERED_COORDINATORS;
	
?>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="Generator" content="Mastishka Intellisys Private Limited">
<meta name="Author" content="Mastishka Intellisys Private Limited">
<meta name="Keywords" content="">
<meta name="Description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo(CConfig::SNC_SITE_NAME);?>: Register Coordinator</title>
<?php 
$objIncludeJsCSS->CommonIncludeCSS("../../");
$objIncludeJsCSS->IncludeMipcatCSS("../../");
$objIncludeJsCSS->IncludeIconFontCSS("../../");
$objIncludeJsCSS->CommonIncludeJS("../../");
$objIncludeJsCSS->IncludeJqueryValidateMinJS("../../");
$objIncludeJsCSS->IncludeMetroNotificationJS("../../");
?>
<?php printf("<script>save_success=%s;</script>",$coordinator); ?>
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
			<br />
			<?php
				$objUM = new CUserManager() ;
				if(CSessionManager::IsError())
				{
					CSessionManager::SetError(false) ;
			?>
			<div class='row fluid'>
				<div class="drop-shadow raised" id="MSG">
					<fieldset>
					<legend>Error Message</legend>	
						<?php 
							echo("<p>Error during registeration : ".CSessionManager::GetErrorMsg()."</p>");
						?>
					<INPUT TYPE="button" NAME="HIDE" class='btn btn-success btn-sm' value="Hide" onClick="OnHide();"/>
					</fieldset>
				</div>
			</div><br />
			<?php
				}
			?>
			<form id="CREATE_CORD_FORM" method="post" class="form-horizontal" action="post_get/form_create_cordntr_exec.php">
				<div class="form-group">
			      <label for="FNAME" class="col-lg-2 col-md-2 col-sm-2 control-label">First Name :</label>
			      <div class="col-lg-4 col-md-4 col-sm-4">
			        <input class="form-control input-sm" id="FNAME" name="FNAME" type="text">
			      </div>
			    </div>
			    <div class="form-group">
			      <label for="LNAME" class="col-lg-2 col-md-2 col-sm-2 control-label">Last Name :</label>
			      <div class="col-lg-4 col-md-4 col-sm-4">
			        <input class="form-control input-sm" id="LNAME" name="LNAME" type="text">
			      </div>
			    </div>
			    <div class="form-group">
			      <label for="DEPARTMENT" class="col-lg-2 col-md-2 col-sm-2 control-label">Department :</label>
			      <div class="col-lg-4 col-md-4 col-sm-4">
			        <input class="form-control input-sm" id="DEPARTMENT" name="DEPARTMENT" type="text">
			      </div>
			    </div>
			    <div class="form-group">
				    <label class="col-lg-2 col-md-2 col-sm-2 control-label">Gender :</label>
			      	<div class="col-lg-6 col-md-6 col-sm-6">
			        	<div class="radio">
			          		<label>
					            <input name="GENDER" id="GENDER_MALE" value="1" checked="checked" type="radio">
					            Male
			          		</label>
			        	</div>
				        <div class="radio">
				          <label>
				            <input name="GENDER" id="GENDER_FEMALE" value="0" type="radio">
				           Female
				          </label>
				        </div>
				     </div>
			     </div>
			     <div class="form-group">
			      <label for="MONTH" class="col-lg-2 col-md-2 col-sm-2 control-label">Birth Day :</label>
			      <div class="col-lg-2 col-md-2 col-sm-2">
			        <select class="form-control input-sm" id="MONTH" name="MONTH">
				        <option value="01" >January</option>
						<option value="02" >February</option>
						<option value="03" >March</option>
						<option value="04" >April</option>
						<option value="05" >May</option>
						<option value="06" >June</option>
						<option value="07" >July</option>
						<option value="08" >August</option>
						<option value="09" >September</option>
						<option value="10" >October</option>
						<option value="11" >November</option>
						<option value="12" >December</option>
					</select>
				  </div>
				  <div class="col-lg-1 col-md-1 col-sm-1">				 
					<select class="form-control input-sm" name="DAY" id="DAY">
						<?php
							$objUM->ListDateOption() ;
						?>
					</select>
				  </div>
				  <div class="col-lg-2 col-md-2 col-sm-2">
					<select class="form-control input-sm" name="BIRTHYEAR" id="BIRTHYEAR">
						<?php
							$objUM->ListYearOption() ;
						?>
					</select>			        
			      </div>
			    </div>
			    <div class="form-group">
			      <label for="PHONE" class="col-lg-2 col-md-2 col-sm-2 control-label">Contact# :</label>
			      <div class="col-lg-4 col-md-4 col-sm-4">
			        <input class="form-control input-sm" id="PHONE" name="PHONE" type="text">
			      </div>
			    </div>
			    <div class="form-group">
			      <label for="EMAIL" class="col-lg-2 col-md-2 col-sm-2 control-label">Email :</label>
			      <div class="col-lg-4 col-md-4 col-sm-4">
			        <input class="form-control input-sm" id="EMAIL" name="EMAIL" type="text">
			      </div>
			    </div>
			    <div class="form-group">
				  <label for="ADDRESS" class="col-lg-2 col-md-2 col-sm-2 control-label">Address :</label>
				  <div class="col-lg-4 col-md-4 col-sm-4">
				    <textarea class="form-control" rows="3" id="ADDRESS" name="ADDRESS"></textarea>
				  </div>
				</div>
			    <div class="form-group">
			      <label for="CITY" class="col-lg-2 col-md-2 col-sm-2 control-label">City :</label>
			      <div class="col-lg-4 col-md-4 col-sm-4">
			        <input class="form-control input-sm" id="CITY" name="CITY" type="text">
			      </div>
			    </div>
			    <div class="form-group">
			      <label for="STATE" class="col-lg-2 col-md-2 col-sm-2 control-label">State :</label>
			      <div class="col-lg-4 col-md-4 col-sm-4">
			        <input class="form-control input-sm" id="STATE" name="STATE" type="text">
			      </div>
			    </div>
			    <div class="form-group">
			      <label for="RECHARGE_AMOUNT" class="col-lg-2 col-md-2 col-sm-2 control-label">Transfer Amount : <?php echo($currencyPrefix); ?></label>
			      <div class="col-lg-4 col-md-4 col-sm-4">
			        <input class="form-control input-sm" id="RECHARGE_AMOUNT" name="RECHARGE_AMOUNT" type="text">
			      </div>
			    </div>
			    <div class="form-group">
			      <label for="PROJ_BALANCE" class="col-lg-2 col-md-2 col-sm-2 control-label">Projected Balance : <?php echo($currencyPrefix); ?></label>
			      <div class="col-lg-4 col-md-4 col-sm-4">
			        <input class="form-control input-sm" id="PROJ_BALANCE" name="PROJ_BALANCE" value="<?php echo($projected_balance);?>" type="text" readonly>
			      </div>
			    </div>
			    <div class="form-group">
			      <div class="col-lg-6 col-md-6 col-sm-6 col-lg-offset-2 col-md-offset-2 col-sm-offset-2">
			        <button type="submit" class="btn btn-primary">Submit</button>
			      </div>
			    </div>
			</form>
			<?php
			include_once (dirname ( __FILE__ ) . "/../../lib/footer.php");
			?>
		</div>
	</div>
	<script type="text/javascript">

	jQuery.validator.addMethod("ValidateAmount", function(value, element) {
		var proj_bal = parseFloat($("#PROJ_BALANCE").val());
		if(value <= proj_bal)
		{
			return true;
		}
		else
		{
			return false;
		}
	}, "<span style='color: red;'>* Recharge amount should be either equal to or less than projected balance</span>");

	$.validator.addMethod('positiveNumber',
		    function (value) { 
		        return Number(value) > 0;
		    }, '<span style="color:red;">* Amount should be greater than 0.</span>');
	
	$(document).ready(function() {

		if(save_success == 1)
		{
			 var not = $.Notify({
				 caption: "Register Coordinator",
				 content: "Coordinator has been registered successfully!",
				 style: {background: 'green', color: '#fff'}, 
				 timeout: 5000
				 });
		}
		
		$("#CREATE_CORD_FORM").validate({
    		rules: {
        		FNAME: {
            		required:true,
        		},
        		LNAME: {
            		required:true,
        		},
        		DEPARTMENT: {
            		required:true,
        		},
        		PHONE:{
        			required:true,
           	 		number: true,
           		},
           		EMAIL: {
            		required: true,
            		email: true
        		},
        		ADDRESS:{
        			required:true,
               	},
               	CITY:{
               		required:true,
               	},	
            	STATE:{
            		required:true,
           		 },
            	RECHARGE_AMOUNT:{
                	required:true,
                	number: true,
                	'ValidateAmount' : true,
                	'positiveNumber' : true
             	}
    		},
    		messages: {
    			FNAME: {	
    				required:	"<span style='color:red'>* Please enter first name</span>",
        		},
        		LNAME: {	
    				required:	"<span style='color:red'>* Please enter last name</span>",
        		},
        		DEPARTMENT: {	
    				required:	"<span style='color:red'>* Please enter department information</span>",
        		},
        		PHONE:{
					required:	"<span style='color:red;'>* Please enter your contact no.</span>",
        	 		number:		"<span style='color:red;'>* contact number must contain digits only</span>"
				},
        		EMAIL:{
					required:	"<span style='color:red'>* Email id is required</span>",
					email:		"<span style='color:red'>* Please enter a valid email address</span"
				},	
				ADDRESS:{
					required:	"<span style='color:red;'>* Please provide the address</span>",
	            },
	            CITY:{
					required:	"<span style='color:red;'>* Please enter the name of city</span>",
               	},    
               	STATE:{
					required:	"<span style='color:red;'>* Please enter the name of state</span>",
				},
				RECHARGE_AMOUNT:{
					required:	"<span style='color:red;'>* Please enter the recharge amount</span>",
        	 		number:		"<span style='color:red;'>* Recharge amount should contain numbers only</span>"
				}
	    	},
    		submitHandler: function(form) {
    			form.submit();
    		}
		});
	});

	function OnHide()
	{
		$("#MSG").hide();
	}
	</script>
</body>
</html>