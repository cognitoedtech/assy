<?php 
	include_once(dirname(__FILE__)."/../../database/config.php");

	class CPayPerUse
	{
		var $db_link;
		
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// Constructor & Distructor
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		public function __construct() 
		{
			$this->db_link = mysql_connect(CConfig::HOST, CConfig::USER_NAME, CConfig::PASSWORD);
			mysql_select_db(CConfig::DB_MCAT, $this->db_link);
		}
	
		public function __destruct() 
		{
			mysql_close($this->db_link);
		}
		
		// ------------------------------------------
		// Private Functions
		// ------------------------------------------
		
		private function GetCurrencyType($user_id)
		{
			$sRet = null;
			$query = sprintf("select currency from billing where user_id='%s'", $user_id);
				
			$result = mysql_query($query, $this->db_link) or die('Get Currency Type error : ' . mysql_error());
				
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
		
				$sRet = $row['currency'];
			}
				
			return $sRet;
		}
		
		private function GetMIpCATQuesRate($user_id)
		{
			$sRet = null;
			$query = sprintf("select rate_mipcat_ques from billing where user_id='%s'", $user_id);
				
			$result = mysql_query($query, $this->db_link) or die('Get MIpCAT Ques Rate error : ' . mysql_error());
				
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
		
				$sRet = $row['rate_mipcat_ques'];
			}
				
			return $sRet;
		}
		
		private function GetPersonalQuesRate($user_id)
		{
			$sRet = null;
			$query = sprintf("select rate_personal_ques from billing where user_id='%s'", $user_id);
				
			$result = mysql_query($query, $this->db_link) or die('Get Personal Ques Rate error : ' . mysql_error());
				
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
		
				$sRet = $row['rate_personal_ques'];
			}
				
			return $sRet;
		}
		
		private function GetSubscriptionPlan($user_id)
		{
			$sRet = null;
			$query = sprintf("select subscription_plan from billing where user_id='%s'", $user_id);
				
			$result = mysql_query($query, $this->db_link) or die('Get Subscription Plan error : ' . mysql_error());
				
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
		
				$sRet = $row['subscription_plan'];
			}
				
			return $sRet;
		}
		
		private function GetPlanType($user_id)
		{
			$sRet = null;
			$query = sprintf("select plan_type from billing where user_id='%s'", $user_id);
				
			$result = mysql_query($query, $this->db_link) or die('Get Plan Type error : ' . mysql_error());
				
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
		
				$sRet = $row['plan_type'];
			}
				
			return $sRet;
		}
		
		private function GetBalance($user_id)
		{
			$sRet = null;
			$query = sprintf("select balance from billing where user_id='%s'", $user_id);
				
			$result = mysql_query($query, $this->db_link) or die('Get Balance error : ' . mysql_error());
				
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
		
				$sRet = $row['balance'];
			}
				
			return $sRet;
		}
		
		private function SubBalance($user_id, $amount)
		{
			$query = "";
			if($amount == -1)
			{
				$query = sprintf("update billing set balance=0 where user_id='%s'", $user_id);
			}
			else
			{
				$query = sprintf("update billing set balance=balance-%1.2f where user_id='%s'", $amount, $user_id);
			}
			$result = mysql_query($query, $this->db_link) or die('Update Balance error : ' . mysql_error());
				
			return $result;
		}
		
		private function AddBalance($user_id, $amount)
		{
			$query = sprintf("update billing set balance=balance+%1.2f where user_id='%s'", $amount, $user_id);
				
			$result = mysql_query($query, $this->db_link) or die('Update Balance error : ' . mysql_error());
				
			return $result;
		}
		
		private function UpdateBalance($user_id, $amount)
		{
			$query = sprintf("update billing set balance='%s' where user_id='%s'", $amount, $user_id);
				
			$result = mysql_query($query, $this->db_link) or die('Update Balance error : ' . mysql_error());
				
			return $result;
		}
		
		private function GetProjectedBalance($user_id)
		{
			$sRet = null;
			$query = sprintf("select projected_balance from billing where user_id='%s'", $user_id);
				
			$result = mysql_query($query, $this->db_link) or die('Get Projected Balance error : ' . mysql_error());
				
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
		
				$sRet = $row['projected_balance'];
			}
				
			return $sRet;
		}
		
		private function SubProjectedBalance($user_id, $amount)
		{
			$query = "";
				
			if($amount == -1)
			{
				$query = sprintf("update billing set projected_balance=0 where user_id='%s'", $user_id);
			}
			else
			{
				$query = sprintf("update billing set projected_balance=projected_balance-%1.2f where user_id='%s'", $amount, $user_id);
			}
			$result = mysql_query($query, $this->db_link) or die('Update Projected Balance error : ' . mysql_error());
				
			return $result;
		}
		
		private function AddProjectedBalance($user_id, $amount)
		{
			$query = sprintf("update billing set projected_balance=projected_balance+%1.2f where user_id='%s'", $amount, $user_id);
				
			$result = mysql_query($query, $this->db_link) or die('Update Projected Balance error : ' . mysql_error());
				
			return $result;
		}
		
		private function UpdateProjectedBalance($user_id, $amount)
		{
			$query = sprintf("update billing set projected_balance='%s' where user_id='%s'", $amount, $user_id);
				
			$result = mysql_query($query, $this->db_link) or die('Update Projected Balance error : ' . mysql_error());
				
			return $result;
		}
		
		private function ResetBillingRates($user_id, $rate_mipcat_ques, $rate_personal_ques, $rate_cand_search)
		{
			// If rate is null, keep that as is.
			$rate_mipcat_ques 	= ($rate_mipcat_ques == null) ? "rate_mipcat_ques": $rate_mipcat_ques;
			$rate_personal_ques = ($rate_personal_ques == null) ? "rate_personal_ques" : $rate_personal_ques;
			$rate_cand_search 	= ($rate_cand_search == null) ? "rate_cand_search" : $rate_cand_search;
				
			$query = sprintf("update billing set rate_mipcat_ques=%s, rate_personal_ques=%s, rate_cand_search=%s where user_id='%s'", $rate_mipcat_ques, $rate_personal_ques, $rate_cand_search, $user_id);
				
			$result = mysql_query($query, $this->db_link) or die('Reset Billing Rates error : ' . mysql_error());
				
			return $result;
		}
		
		// ------------------------------------------
		// Public Functions
		// ------------------------------------------
		
		public function PostTestAdjustBilling($owner_id, $user_id)
		{
			
		}
		
		public function CanSchedule($user_id)
		{
				
		}
	}
?>