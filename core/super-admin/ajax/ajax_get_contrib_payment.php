<?php
include_once(dirname(__FILE__)."/../../../lib/billing.php");
$transaction_id = $_GET['transaction_id'];
$objBilling = new CBilling();
if($transaction_id != 0)
{
$encash_info = $objBilling->GetContribEncashHistoryByTransId($transaction_id);

$amount = $encash_info['points']/5;
?>
<div class="input-prepend input-append">
	<span style="font-weight:bold;" class="add-on">Points To Be Encashed: </span>
	<input class="input-large" id="points" name="points" type="text" value="<?php echo($encash_info['points']); ?>" readonly/>
	<span class="add-on"><i class="icon-gift"></i></span>
</div>
<div class="input-prepend input-append">
	<span style="font-weight:bold;" class="add-on">Amount: <img src="../../../images/rupees.png" style="position:relative;bottom:2px"/></span>
	<input class="input-large" id="amount" name="amount" type="text" value="<?php printf("%01.2f",$amount); ?>" readonly/>
	<span class="add-on"><b>(INR)</b></span>
</div>
<br/>
<div class="input-prepend input-append">
	<span style="font-weight:bold;" class="add-on">Cheque Number: </span>
	<input class="input-xlarge" id="prependedInput" name="cheque_num" type="text" />
	<span class="add-on"><i class="icon-list"></i></span>
	<span class="add-on"><img id="cheque_num_right" style="display:none" class="icon-ok"></i><img id="cheque_num_wrong" style="display:none" class="icon-remove"></i></span>
</div>
<div class="input-prepend input-append">
	<span style="font-weight:bold;" class="add-on">Date on Cheque: </span>
	<input class="input-xlarge" style="cursor: pointer;" id="datepicker1" name="payment_date" type="text" />
	<span class="add-on"><i class="icon-time"></i></span>
	<span class="add-on"><img id="payment_date_right" style="display:none" class="icon-ok"></i><img id="payment_date_wrong" style="display:none" class="icon-remove"></i></span>
</div>
<div class="input-prepend input-append">
	<span style="font-weight:bold;" class="add-on">Drawn Bank Name: </span>
	<input class="input-xxlarge" id="drawn_bank" type="text" name="drawn_bank"/>
	<span class="add-on"><i class="icon-pencil"></i></span>
	<span class="add-on"><img id="drawn_bank_right" style="display:none" class="icon-ok"></i><img id="drawn_bank_wrong" style="display:none" class="icon-remove"></i></span>
</div><br />
<div id="confirm_payment_id">
	<label class="checkbox">
		<input type="checkbox" id="confirm_payment" name="confirm_payment" value="yes" onchange="OnTermsClicked();"> I have checked all details regarding contributor payment, all details are proper and correct.
	</label>
</div>
<br/>
<script>
	$(function() {
		$( "#datepicker1" ).datepicker({
			numberOfMonths: 1,
			showButtonPanel: true,
			dateFormat: "d MM, yy"
		}).attr('readonly','readonly');
	});
</script>
<?php
}
?>
