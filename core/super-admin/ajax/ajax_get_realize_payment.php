<?php
include_once(dirname(__FILE__)."/../../../lib/billing.php");
include_once(dirname(__FILE__)."/../../../lib/session_manager.php");

$transaction_id = $_GET['transaction_id'];
$objBilling = new CBilling();

if($transaction_id != 0)
{
$user_id 	  = $objBilling->GetUserIdByXactionId($transaction_id);
$currency 	  = $objBilling->GetCurrencyType($user_id);
$billing_info = $objBilling->GetBillingHistoryByTransId($transaction_id);
		
?>
<div class="form-group">
	<label for="recharge_amount" class="col-sm-3 col-md-3 col-lg-3 control-label">Amount &nbsp;<?php echo(($currency == "INR")?"<img src=' ../../images/rupees.png' style='position:relative;bottom:2px'/>":"$");?> :</label>
	<div class="input-group col-sm-4 col-md-4 col-lg-4">
		<input class="form-control" id="recharge_amount" name="recharge_amount" type="text" value="<?php printf("%01.2f",$billing_info['recharge_amount']); ?>" readonly/>
		<span class="input-group-addon"><b>(<?php echo($currency);?>)</b></span>
	</div>
</div>

<div class="form-group">
	<label for="prependedInput" class="col-sm-3 col-md-3 col-lg-3 control-label"><?php echo(($billing_info['payment_mode'] == CConfig::PAYMENT_MODE_NEFT)?"NEFT Transaction ID:":"Cheque &frasl; DD Number:"); ?> </label>
	<div class="input-group col-sm-4 col-md-4 col-lg-4">
		<input  class="form-control" id="prependedInput" name="payment_ordinal" type="text" value="<?php echo($billing_info['payment_ordinal']); ?>" readonly/>
		<span class="input-group-addon"><i class="icon-list"></i></span>
	</div>
</div>

<div class="form-group">
	<label for="datepicker1"  class="col-sm-3 col-md-3 col-lg-3 control-label"><?php echo(($billing_info['payment_mode'] == CConfig::PAYMENT_MODE_NEFT)?"Date of Payment:":"Date on Cheque &frasl; DD:"); ?> </label>
	<div class="input-group col-sm-4 col-md-4 col-lg-4">
		<input class="form-control" id="datepicker1" name="payment_date" type="text" value="<?php echo(date('d-m-Y', strtotime($billing_info['payment_date']))); ?>" readonly/>
		<span class="input-group-addon"><i class="icon-clock"></i></span>
	</div>
</div>

<div class="form-group">
	<label for="payment_agent" class="col-sm-3 col-md-3 col-lg-3 control-label"><?php echo(($billing_info['payment_mode'] == CConfig::PAYMENT_MODE_NEFT)?"Bank (who) Processed":"Drawn Bank Name:"); ?></label>
	<div class="input-group col-sm-4 col-md-4 col-lg-4">
		<input class="form-control" id="payment_agent" type="text" name="payment_agent" value="<?php echo($billing_info['payment_agent']); ?>" readonly/>
		<span class="input-group-addon"><i class="icon-pencil"></i></span>
	</div>
</div>

<div class="form-group">
	<label class="col-sm-3 col-md-3 col-lg-3 control-label">Realization Choice :</label>
    <div class="col-sm-1 col-md-1 col-lg-1">
    	<div class="radio">
          <label>
          	<input type="radio" name="realize_choice" onchange="OnRealizationChoiceChange();" value="yes" checked>Realize  
          </label>
        </div>
    </div>
    <div class="col-sm-1 col-md-1 col-lg-1">
        <div class="radio">
          <label>
          	<input type="radio" name="realize_choice" onchange="OnRealizationChoiceChange();" value="no">void
          </label>
        </div>
      </div>
</div>

<div class="form-group">
	<div id="confirm_realize_id" class="col-sm-8 col-md-8 col-lg-8 col-sm-offset-3 col-md-offset-3 col-lg-offset-3">
		<label class="checkbox">
			<input type="checkbox" id="confirm_realize" name="confirm_realize" value="yes" onchange="OnTermsClicked();"> I have checked all details regarding recieved payment, all details are proper and correct.
		</label>
	</div>
</div>

<div id="void_realize_id" style="display:none">
	<label for="void_reasons" class="col-sm-3 col-md-3 col-lg-3 control-label">Void Realization Reasons :</label> 
	<div class="col-sm-4 col-md-4 col-lg-4">
		<select class="form-control input-sm" id="void_reasons" name="void_reasons" onChange="OnReasonChange();">
			<option value="">--Select Void Realization Reason--</option>
			<option value="Cheque &frasl; DD expired">Cheque/DD expired</option>
			<option value="Invalid Cheque &frasl; DD">Invalid Cheque/DD</option>
			<option value="Incomplete Information">Incomplete Information</option>
			<option value="other">other</option>
		</select>
	</div><br /><br /><br />
	<div class="form-group">
		<div id="other_void_reason_id" style="display:none">
			<div class="col-sm-4 col-md-4 col-lg-4 col-sm-offset-3 col-md-offset-3 col-lg-offset-3">
				<input class="form-control" id="other_void_reason" type="text" name="other_void_reason" placeholder="Specify other reason for voiding this transaction"/>
			</div>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-8 col-md-8 col-lg-8 col-sm-offset-3 col-md-offset-3 col-lg-offset-3" id="void_realize_check_id" style="display:none">
			<label class="checkbox" >
				<input type="checkbox" id="void_realize" name="void_realize" value="no" onchange="OnTermsClicked();"> I have checked all details regarding recieved payment and found available details are not proper.
			</label>
		</div>
	</div>
</div>
<?php
}
?>
