<?php
include_once(dirname(__FILE__)."/../../../lib/billing.php");
$ba_id = $_GET['ba_id'];
$objBilling = new CBilling();
if(!empty($ba_id))
{
$payment_mode = $objBilling->GetBAPaymentMode($ba_id);
		
?>
<div class="input-prepend input-append">
	<span style="font-weight:bold;" class="add-on"><?php echo(($payment_mode == CConfig::PAYMENT_MODE_NEFT)?"NEFT Transaction ID:":"Cheque Number:"); ?></span>
	<input class="input-xlarge" id="prependedInput" name="payment_ordinal" type="text" />
	<span class="add-on"><i class="icon-list"></i></span>
	<span class="add-on"><img id="payment_ordinal_right" name="payment_ordinal_right" style="display:none" class="icon-ok"></i><img id="payment_ordinal_wrong" name="payment_ordinal_wrong" style="display:none" class="icon-remove"></i></span>
</div>
<div class="input-prepend input-append">
	<span style="font-weight:bold;" class="add-on"><?php echo(($payment_mode == CConfig::PAYMENT_MODE_NEFT)?"Date of Payment:":"Date on Cheque :"); ?></span>
	<input class="input-xlarge" id="datepicker1" name="payment_date" type="text" />
	<span class="add-on"><i class="icon-time"></i></span>
	<span class="add-on"><img id="payment_date_right" name="payment_date_right" style="display:none" class="icon-ok"></i><img id="payment_date_wrong" name="payment_date_wrong" style="display:none" class="icon-remove"></i></span>
</div>
<div class="input-prepend input-append">
	<span style="font-weight:bold;" class="add-on">Bank IFSC Code: </span>
	<input class="input-xxlarge" id="" type="text" name="payment_agent" />
	<span class="add-on"><i class="icon-pencil"></i></span>
	<span class="add-on"><img id="payment_agent_right" name="payment_agent_right" style="display:none" class="icon-ok"></i><img id="payment_agent_wrong" name="payment_agent_wrong" style="display:none" class="icon-remove"></i></span>
</div><br />
<div id="confirm_payment_id">
	<label class="checkbox">
		<input type="checkbox" id="confirm_payment" name="confirm_payment" value="yes" onchange="OnTermsClicked();"> I have checked and correctly filled all details regarding payment of selected business associate, all details are proper and correct.
	</label>
</div><br />
<input id="process" class="btn btn-success" style="font-weight:bold;" type="button" onClick="ConfirmPayment();" value="Process >>" disabled/><br/><br/>
<b>Client List of Selected Business Associate:</b><br />
<table align="center" id='hello' style="font: 100% 'Trebuchet MS', sans-serif;border-collapse:collapse;" class="table table-bordered table-hover">
	<tbody>
		<tr class="error">
			<td><b>Name</b></td>
			<td><b>Organization</b></td>
			<td><b>Email</b></td>
			<td><b>Recharge Amount</b></td>
			<td><b>Commission</b></td>
			<td><b>Select Commission to pay </b></td>
		</tr>
		<?php
			$objBilling->PopulateClientsForProcessBAPayment($ba_id);
		?>
	</tbody>
</table>
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
