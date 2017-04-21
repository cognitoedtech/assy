<?php
	include_once(dirname(__FILE__)."/../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/../../lib/billing.php");
	include_once(dirname(__FILE__)."/../../lib/new-email.php");
	include_once(dirname(__FILE__)."/../../lib/user_manager.php");
	
	// STEP 1: Read POST data
	 
	// reading posted data from directly from $_POST causes serialization 
	// issues with array data in POST
	// reading raw POST data from input stream instead. 
	$raw_post_data = file_get_contents('php://input');
	$raw_post_array = explode('&', $raw_post_data);
	$myPost = array();
	
	
	foreach ($raw_post_array as $keyval) 
	{
	  $keyval = explode ('=', $keyval);
	  if (count($keyval) == 2)
	     $myPost[$keyval[0]] = urldecode($keyval[1]);
	}
	
	// read the post from PayPal system and add 'cmd'
	$req = 'cmd=_notify-validate';
	if(function_exists('get_magic_quotes_gpc')) 
	{
	   $get_magic_quotes_exists = true;
	} 
	foreach ($myPost as $key => $value) 
	{
	   if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) { 
	        $value = urlencode(stripslashes($value)); 
	   } else {
	        $value = urlencode($value);
	   }
	   $req .= "&$key=$value";
	}
 
 
	// STEP 2: Post IPN data back to paypal to validate
	 
	$ch = curl_init(CConfig::PAYPAL_URL);
	curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
	 
	// In wamp like environments that do not come bundled with root authority certificates,
	// please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set the directory path 
	// of the certificate as shown below.
	// curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
	if( !($res = curl_exec($ch)) ) 
	{
	    // error_log("Got " . curl_error($ch) . " when processing IPN data");
	    curl_close($ch);
	    exit;
	}
	curl_close($ch);
 
 
	// STEP 3: Inspect IPN validation result and act accordingly
	 
   // check whether the payment_status is Completed
   // check that txn_id has not been previously processed
   // check that receiver_email is your Primary PayPal email
   // check that payment_amount/payment_currency are correct
   // process payment
	//file_put_contents("payment.txt", print_r($_POST, true));
	 	
   // assign posted variables to local variables
    $item_name = $_POST['item_name'];
    $item_number = $_POST['item_number'];
    $payment_status = $_POST['payment_status'];
    $payment_amount = $_POST['mc_gross'];
    $payment_currency = $_POST['mc_currency'];
    $txn_id = $_POST['txn_id'];
    $receiver_email = $_POST['receiver_email'];
    $payer_email = $_POST['payer_email'];
   
    //file_put_contents("payment".uniqid().".txt", print_r($_POST, true));
    
    $objDB = new CMcatDB();
    $objUM = new CUserManager();
    $objUser = $objUM->GetUserByEmail($payer_email);
    $user_id =  $objUser->GetUserID();
    
	$user_name = $objUser->GetFirstName()." ".$objUser->GetLastName();
	
	$objMail = new CEMail(CConfig::OEI_FINANCE, $objDB->GetPasswordFromOfficialEMail(CConfig::OEI_FINANCE));
	
	if (strcmp ($res, "VERIFIED") == 0)		
	{
		$objBilling = new CBilling();
		$ba_commission_percent = $objBilling->GetBACommissionRate($user_id);
		$payment_agent = "PAYPAL - ".$payment_currency;
		
		$xaction_id = $objBilling->InsertReceivedPayment($user_id, CConfig::PAYMENT_MODE_GATEWAY, $payment_agent, $txn_id, date('Y-m-d H:i:s'), $payment_amount, $ba_commission_percent);
										
		$objBilling->RealizePayment($user_id, $xaction_id);
		$objBilling->AddBalance($user_id, $payment_amount);
		$objBilling->AddProjectedBalance($user_id, $payment_amount);
		
		// Email for success
		if(!empty($xaction_id))
		{
			$objMail->PrepAndSendRealizePaymentMail($payer_email, $user_name, $xaction_id, $payment_amount, '$');
			//CEMail::PrepAndSendRealizePaymentMail($payer_email, $user_name, $xaction_id, $payment_amount, '$');
		}
	}
	else if (strcmp ($res, "INVALID") == 0) 
	{
	    // email for manual investigation
		$objMail->PrepAndSendInvalidPaypalTransactionMail($payer_email, $user_name);
		//CEMail::PrepAndSendInvalidPaypalTransactionMail($payer_email, $user_name);
		
	    //file_put_contents("invalid.txt", print_r($_POST, true));
	}
?>