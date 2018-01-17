<?php

	class CBusinessAssociate 
	{
		var $db_link;
		
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// Constructor & Distructor
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		public function __construct() 
		{
			$this->db_link = mysql_connect ( CConfig::HOST, CConfig::USER_NAME, CConfig::PASSWORD );
			mysql_select_db ( CConfig::DB_MCAT, $this->db_link );
		}
		
		public function __destruct() 
		{
			mysql_close ( $this->db_link );
		}
		
		// ------------------------------------------
		// Private Functions
		// ------------------------------------------
		
		// ------------------------------------------
		// Public Functions
		// ------------------------------------------
		
		public function GetBACommissionByXactionId($xaction_id) 
		{
			$retVal = 0;
			
			$query = sprintf ( "select ba_commission_percent from user_billing_history where transaction_id=%d", $xaction_id );
			
			$result = mysql_query ( $query, $this->db_link ) or die ( 'Get BA commission by xaction id error : ' . mysql_error () );
			
			if (mysql_num_rows ( $result ) > 0) {
				$row = mysql_fetch_array ( $result );
				$retVal = $row ['ba_commission_percent'];
			}
			
			return $retVal;
		}
		
		public function AddBABalance($ba_id, $amount) 
		{
			$query = sprintf ( "update business_associate set balance=balance+%1.2f where ba_id='%s'", $amount, $ba_id );
			
			$result = mysql_query ( $query, $this->db_link ) or die ( 'Add ba balance error : ' . mysql_error () );
			
			return $result;
		}
		
		public function SubBABalance($ba_id, $amount) 
		{
			$query = sprintf ( "update business_associate set balance=balance-%1.2f where ba_id='%s'", $amount, $ba_id );
			
			$result = mysql_query ( $query, $this->db_link ) or die ( 'Sub ba balance error : ' . mysql_error () );
			
			return $result;
		}
		
		public function AddBAEarnedSoFar($ba_id, $amount) 
		{
			$query = sprintf ( "update business_associate set earned_sofar=earned_sofar+%f where ba_id='%s'", $amount, $ba_id );
			
			$result = mysql_query ( $query, $this->db_link ) or die ( 'Add ba earned so far error : ' . mysql_error () );
			
			return $result;
		}
		
		public function PopulateBATransactionHistory($ba_id) 
		{
			$query = sprintf ( "select * from ba_xaction_history where ba_id='%s'", $ba_id );
			
			$result = mysql_query ( $query, $this->db_link ) or die ( 'Populate ba transaction history error : ' . mysql_error () );
			
			$reset = date_default_timezone_get ();
			date_default_timezone_set ( $this->tzOffsetToName ( $time_zone ) );
			
			while ( $row = mysql_fetch_array ( $result ) ) {
				printf ( "<tr>" );
				printf ( "<td>%01.2f</td>", $row ['debt_amount'] );
				
				$gross_amount = $row ['debt_amount'] + $row ['service_tax_factor'] + $row ['tds_factor'];
				
				printf ( "<td>%01.2f</td>", $gross_amount );
				printf ( "<td>%01.2f</td>", $row ['service_tax_factor'] );
				printf ( "<td>%01.2f</td>", $row ['tds_factor'] );
				printf ( "<td>%s</td>", CConfig::$PAYMENT_MODE_TEXT_ARY [$row ['payment_mode']] );
				
				if ($row ['payment_mode'] == CConfig::PAYMENT_MODE_NEFT) {
					printf ( "<td>%s</td>", $row ['bank_ifsc'] );
				} else {
					printf ( "<td>Not Applicable</td>" );
				}
				
				printf ( "<td>%s</td>", $row ['payment_ordinal'] );
				printf ( "<td>%s</td>", date ( "F d, Y [H:i:s]", strtotime ( $row ['payment_date'] ) ) );
				printf ( "</tr>" );
			}
			
			date_default_timezone_set ( $reset );
		}
		
		public function PopulateBAEarningSourceHistory($ba_id) 
		{
			$query = sprintf ( "select ubh.user_id,users.email,users.firstname,users.lastname,users.organization_id,payment_date,realization_date,recharge_amount,ba_commission_percent from user_billing_history as ubh join users on users.user_id=ubh.user_id where users.buss_assoc_id='%s' and ubh.void_reason is NULL", $ba_id );
			;
			
			$result = mysql_query ( $query, $this->db_link ) or die ( 'Populate ba earning history error : ' . mysql_error () );
			
			while ( $row = mysql_fetch_array ( $result ) ) {
				$org_name = $this->GetOrganizationName ( $row ['organization_id'] );
				echo "<tr>";
				echo "<td>" . $row ['firstname'] . " " . $row ['lastname'] . "(" . $org_name . ", " . $row ['email'] . ")</td>";
				echo "<td>" . $row ['payment_date'] . "</td>";
				echo "<td>" . $row ['realization_date'] . "</td>";
				printf ( "<td>%01.2f</td>", $this->CalculateAmountFromPercentage ( $row ['recharge_amount'], $row ['ba_commission_percent'] ) );
				echo "</tr>";
			}
		}
		
		public function PopulateBAForProcessPayment() 
		{
			$query = sprintf ( "select ba.ba_id,users.firstname,users.lastname,users.email,users.organization_id from business_associate as ba join users on ba.ba_id=users.user_id where ba.balance <> 0" );
			
			$result = mysql_query ( $query, $this->db_link ) or die ( 'Populate ba for process payment error : ' . mysql_error () );
			
			while ( $row = mysql_fetch_array ( $result ) ) {
				printf ( "<option value='%s' id='%s'>%s %s(%s, %s)</option>", $row ['ba_id'], $row ['ba_id'], $row ['firstname'], $row ['lastname'], $this->GetOrganizationName ( $row ['organization_id'] ), $row ['email'] );
			}
		}
		
		public function ProcessBAPayment($ba_id, $gross_amount, $debt_amount, $service_tax_amount, $tds_amount, $payment_ordinal, $payment_date, $payment_agent) 
		{
			$query = sprintf ( "insert into ba_xaction_history(ba_id,debt_amount,service_tax_factor,tds_factor,payment_mode,bank_ifsc,payment_ordinal,payment_date) values('%s','%s','%s','%s','%s','%s','%s','%s')", $ba_id, $debt_amount, $service_tax_amount, $tds_amount, $this->GetBAPaymentMode ( $ba_id ), $payment_agent, $payment_ordinal, date ( 'Ymd', strtotime ( $payment_date ) ) );
			
			$result = mysql_query ( $query, $this->db_link ) or die ( 'Process ba payment error : ' . mysql_error () );
			
			$this->SubBABalance ( $ba_id, $gross_amount );
		}
		
		public function DoneBAClientPayment($client_xaction_array) 
		{
			for($index = 0; $index < count ( $client_xaction_array ); $index ++) {
				$query = sprintf ( "update user_billing_history set ba_xaction_done=1 where transaction_id=%d", $client_xaction_array [$index] );
				
				$result = mysql_query ( $query, $this->db_link ) or die ( 'Done ba client payment error : ' . mysql_error () );
			}
		}
		
		public function PopulateClientsForProcessBAPayment($ba_id) 
		{
			$query = sprintf ( "select ubh.transaction_id,ubh.user_id,users.firstname,users.lastname,users.email,users.organization_id,ubh.recharge_amount,ubh.ba_commission_percent from user_billing_history as ubh join users on ubh.user_id=users.user_id where ubh.ba_xaction_done=0 and realization_date is not NULL and void_reason is NULL and payment_mode <> -1 and users.buss_assoc_id='%s'", $ba_id );
			
			$result = mysql_query ( $query, $this->db_link ) or die ( 'Populate client for process ba payment error : ' . mysql_error () );
			
			$theme_changer = 0;
			
			while ( $row = mysql_fetch_array ( $result ) ) {
				$theme = "warning";
				if ($theme_changer % 2 == 0) {
					$theme = "info";
				}
				printf ( "<tr class='%s'>", $theme );
				printf ( "<td>%s %s</td>", $row ['firstname'], $row ['lastname'] );
				printf ( "<td>%s</td>", $this->GetOrganizationName ( $row ['organization_id'] ) );
				printf ( "<td>%s</td>", $row ['email'] );
				printf ( "<td>%01.2f</td>", $row ['recharge_amount'] );
				printf ( "<td id='amount%d'>%01.2f</td>", $row ['transaction_id'], $this->CalculateAmountFromPercentage ( $row ['recharge_amount'], $row ['ba_commission_percent'] ) );
				printf ( "<td><input type='checkbox' name='payment_done[]' id='%d' value='%d' onchange='OnCommissionSelect(this);' /></td>", $row ['transaction_id'], $row ['transaction_id'] );
				printf ( "</tr>" );
				$theme_changer ++;
			}
		}
		
		public function GetBAPaymentMode($ba_id) 
		{
			$retVal = NULL;
			
			$query = sprintf ( "select pref_pmnt_mode from business_associate where ba_id='%s'", $ba_id );
			
			$result = mysql_query ( $query, $this->db_link ) or die ( 'Get ba payment mode error : ' . mysql_error () );
			
			if (mysql_num_rows ( $result ) > 0) {
				$row = mysql_fetch_array ( $result );
				$retVal = $row ['pref_pmnt_mode'];
			}
			return $retVal;
		}
		
		public function PopulateBAClientInfo($ba_id) 
		{
			$query = sprintf ( "select * from users where buss_assoc_id='%s'", $ba_id );
			
			$result = mysql_query ( $query, $this->db_link ) or die ( 'populate BA client info error : ' . mysql_error () );
			
			if (mysql_num_rows ( $result ) > 0) {
				while ( $row = mysql_fetch_array ( $result ) ) {
					$org_name = $this->GetOrganizationName ( $row ['organization_id'] );
					$balance = $this->GetBalance ( $row ['user_id'] );
					$projected_balance = $this->GetProjectedBalance ( $row ['user_id'] );
					
					echo "<tr id='" . $row ['user_id'] . "'>";
					echo "<td>" . $row ['firstname'] . " " . $row ['lastname'] . "</td>";
					echo "<td>" . $org_name . "</td>";
					echo "<td>" . $row ['email'] . "</td>";
					echo "<td>" . $row ['contact_no'] . "</td>";
					
					if (! empty ( $row ['address'] )) {
						echo "<td>" . $row ['address'] . "</td>";
					} else {
						echo "<td>Not Available</td>";
					}
					echo "<td>" . $row ['city'] . ", " . $row ['state'] . ", " . $row ['country'] . "</td>";
					
					if (! empty ( $balance )) {
						echo "<td>" . $balance . "</td>";
					} else {
						echo "<td>0.0</td>";
					}
					
					if (! empty ( $projected_balance )) {
						echo "<td>" . $projected_balance . "</td>";
					} else {
						echo "<td>0.0</td>";
					}
					echo "</tr>";
				}
			}
		}
	}
?>