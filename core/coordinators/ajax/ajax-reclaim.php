<?php 
	include_once("../../../database/mcat_db.php");
	include_once("../../../../lib/utils.php");
	include_once("../../../lib/billing.php");
	
	$objDB = new CMcatDB();
	$objBilling = new CBilling();
	
	function clean($str)
	{
		/*if(!get_magic_quotes_gpc())
		{
			$str = trim(mysql_real_escape_string($str));
		}
		else*/
		{
			$str = trim($str);
		}
	
		return $str;
	}
	
	//Sanitize the POST values
	$user_id			= clean($_GET['user_id']);
	$projected_balance 	= $objBilling->GetProjectedBalance($user_id);
	$balance 			= $objBilling->GetBalance($user_id);
	$currencyPrefix 	= NULL;
	$currency = $objBilling->GetCurrencyType($user_id);
	
	if($currency == "USD")
	{
		$currencyPrefix = "<img src='../../../images/dollar.png' id='inst_dollar' style='position:relative;bottom:2px;'/>";
	}
	else
	{
		$currencyPrefix =  "<img src='../../images/rupees.png' id='inst_dollar' style='position:relative;bottom:2px;'/>";
	}
?>
<div class="input-prepend input-append">
	<input class="textfield" id="coor_projected_amount" name="coor_projected_amount" type="hidden" value="<?php echo($projected_balance);?>" size="50" class="input"/>
	<span style="font-weight:bold;" class="add-on"> Reclaim Amount: <?php echo($currencyPrefix); ?></span>
	<input class="input-large" id="insert_reclaim_amount" name="insert_reclaim_amount" type="text"  onblur ="CompareBlance(this);"/>
	<span class="add-on"><img id="insert_reclaim_amount_CR" STYLE="display:none" class="icon-ok"></i><img id="insert_reclaim_amount_WR" STYLE="display:none" class="icon-remove"></i></span><br/>
</div>
<p><FONT ID="insert_reclaim_amount_MSG" SIZE="" ALIGN=\"CENTRE\" COLOR="BLUE">( Reclaim Amount Should be Less Then Or Equal To Coordinator Projected Balance )</FONT></p>
<p style="color:blue">Projected Balance : <?php echo($currencyPrefix); ?> <input readonly class="input-large" type="text" value="<?php echo($projected_balance);?>"/></p>
<p style="color:blue">Available Balance : <?php echo($currencyPrefix); ?> <input readonly class="input-large" type="text" value="<?php echo($balance);?>"/></p>
<label class="checkbox" id="recailm_check_id">
	<input type="checkbox" id="reclaim_checkbox" name="reclaim_checkbox""" value="no" onchange="OnTermsClicked();"> I have checked all details regarding reclaim amount please proceed .
</label>
				
<script type="text/javascript" >		

	function CompareBlance(obj) 
	{
		var bResult = false;
		var pro_bal = parseFloat((document.getElementById("coor_projected_amount").value));
		var objMsg = document.getElementById(obj.name+"_MSG");
		var enter_bal = parseFloat(obj.value);

		if( pro_bal >= enter_bal)
		{
			$("#insert_reclaim_amount_CR").show();
			$("#insert_reclaim_amount_WR").hide();
			$("#insert_reclaim_amount").css("border", "1px solid green");
			objMsg.color = "green";
			bValid = true;					
			bResult = true;
		}
		else
		{
			$("#insert_reclaim_amount_WR").show();
			$("#insert_reclaim_amount_CR").hide();
			$("#insert_reclaim_amount").css("border", "1px solid red");
			objMsg.color = "RED";
			bValid = false;
		}
		return bResult;	
	}			 
</script>
											
		