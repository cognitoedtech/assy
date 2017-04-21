<?php
	include_once(dirname(__FILE__)."/../database/config.php");
	include_once(dirname(__FILE__)."/utils.php");
	include_once(dirname(__FILE__)."/new-email.php");
	
	class CBilling
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
		
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// Private Functions
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		private function tzOffsetToName($offset, $isDst = null)
		{
			if ($isDst === null)
			{
				$isDst = date('I');
			}
		
			$offset *= 3600;
			$zone    = timezone_name_from_abbr('', $offset, $isDst);
		
			if ($zone === false)
			{
				foreach (timezone_abbreviations_list() as $abbr)
				{
					foreach ($abbr as $city)
					{
						// (bool)$city['dst'] === (bool)$isDst &&
						if (strlen($city['timezone_id']) > 0    &&
								$city['offset'] == $offset)
						{
							$zone = $city['timezone_id'];
							break;
						}
					}
		
					if ($zone !== false)
					{
						break;
					}
				}
			}
			 
			return $zone;
		}
		
		private function GetOrganizationName($org_id)
		{
			$query = sprintf("select organization_name from organization where organization_id='%s'",$org_id);
			
			$result = mysql_query($query, $this->db_link) or die('select organization_name from organization : ' . mysql_error());
		
			$row = mysql_fetch_array($result);
			
			return $row['organization_name'];
		}
		
		private function CalculateAmountFromPercentage($amount, $percent)
		{
			$retValue = ($amount*$percent)/100;
			
			return $retValue;
		}
		
		private function GetBusinessAssociateId($user_id)
		{
			$retVal = NULL;
		
			$query = sprintf("select buss_assoc_id from users where user_id='%s'", $user_id);
			
			$result = mysql_query($query, $this->db_link) or die('Get business associate id error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				
				$retVal = $row['buss_assoc_id'];
			}
			
			return $retVal;
		}
		
		private function GetScheduledTestBillingInfo($scheduler_id, &$accountUsageAry)
		{
			$i = count($accountUsageAry);
			$mipcat_ques_rate	= $this->GetMIpCATQuesRate($scheduler_id);
			$personal_ques_rate	= $this->GetPersonalQuesRate($scheduler_id);
		
			$query		=	sprintf("select test.test_id, test.test_name, test_schedule.schedule_type, test_schedule.scheduled_on , test_schedule.schedule_type, test_schedule.create_date,test_schedule.schd_id ,test_schedule.user_list FROM test Join test_schedule ON test_schedule.test_id =test.test_id where scheduler_id='%s'", $scheduler_id);
		
			$result 	= 	mysql_query($query, $this->db_link) or die('Get Scheduled Test Billing Info error : '. mysql_error());
		
			while($row = mysql_fetch_array($result))
			{
				$offline_cost = 0;
				if($row['schedule_type'] == CConfig::TST_OFFLINE)
				{
					$offline_cost = $this->GetOfflineVersionRate($scheduler_id);
				}
				
				$isTestFromAssignedPackage = $this->IsTestAssignedFromPackage($row['test_id'], $scheduler_id);
				$assignedPackageTestRate = 0;
				if($isTestFromAssignedPackage)
				{
					$assignedPackageTestRate = $this->GetAssignedPackageTestRate($row['test_id'], $scheduler_id);
				}
				
				$ques_source		= $this->GetQuesSource($row['test_id']);
				$cand_count			= count(explode(";",$row['user_list'])) - 1;
				$finished_cands 	= $this->GetFinishedTests($row['schd_id']);
				$consumed_projected	= $cand_count*($mipcat_ques_rate + $offline_cost + $assignedPackageTestRate);
				$consumed_bal		= ($mipcat_ques_rate + $offline_cost + $assignedPackageTestRate)*$finished_cands;
		
				if($ques_source == "personal")
				{
					$consumed_projected	= $cand_count*($personal_ques_rate + $offline_cost + $assignedPackageTestRate);
					$consumed_bal		= ($personal_ques_rate + $offline_cost + $assignedPackageTestRate)*$finished_cands;
				}
				$accountUsageAry[$i]['date']			= strtotime($row['create_date']);
				$accountUsageAry[$i]['description']	= sprintf("Test <b>%s</b> was scheduled on <b>%s</b> for <b>%d</b> candidates with <b>(xID:%s)</b> and <b>%d</b> candidates finished this test.", $row['test_name'], date("F d,Y [H:i:s]", strtotime($row['scheduled_on'])) , $cand_count, $row['schd_id'], $finished_cands);
				if($row['schedule_type'] == CConfig::TST_OFFLINE && empty($row['scheduled_on']))
				{
					$accountUsageAry[$i]['description']	= sprintf("Test <b>%s</b> was scheduled offline for <b>%d</b> candidates with <b>(xID:%s)</b> and <b>%d</b> candidates finished this test.", $row['test_name'], $cand_count, $row['schd_id'], $finished_cands);
				}
				$accountUsageAry[$i]['credit_amount'] = sprintf("<b>-</b>");
				$accountUsageAry[$i]['debit_amount'] = sprintf("%s Projected Bal., %s Available Bal.", $consumed_projected, $consumed_bal);
				$accountUsageAry[$i]['projected_bal']= -$consumed_projected;
				$accountUsageAry[$i++]['main_bal']	= -$consumed_bal;
			}
		}
		
		private function GetUserRechargeHistory($user_id, &$accountUsageAry)
		{
			$currency = $this->GetCurrencyType($user_id);
			 
			$i = count($accountUsageAry);
		
			$payment_mode_ary = array(CConfig::PAYMENT_MODE_FREE=>"Free Recharge", CConfig::PAYMENT_MODE_CHEQUE=>"Cheque", CConfig::PAYMENT_MODE_DD=>"DD", CConfig::PAYMENT_MODE_NEFT=>"NEFT", CConfig::PAYMENT_MODE_NET_BANKING=>"Net Banking", CConfig::PAYMENT_MODE_GATEWAY=>"Payment Gateway");
		
			$query = sprintf("select * from user_billing_history where user_id='%s' and realization_date is not NULL and void_reason is NULL", $user_id);
		
			$result = mysql_query($query, $this->db_link) or die('Get User Recharge History error : ' . mysql_error());
		
			while($row = mysql_fetch_array($result))
			{
				$accountUsageAry[$i]['date']			 = strtotime($row['realization_date']);
				$accountUsageAry[$i]['description']	 = sprintf("Account was credited with <b>%s %s</b> having payment ordinal: <b>%s</b> of <b>%s</b> by <b>%s</b>.", ($currency == CConfig::CURRENCY_INR)?"Rs.":"$", $row['recharge_amount'], $row['payment_ordinal'], $row['payment_agent'], $payment_mode_ary[$row['payment_mode']]);
				$accountUsageAry[$i]['credit_amount'] = sprintf("%s", $row['recharge_amount']);
				$accountUsageAry[$i]['debit_amount'] = sprintf("<b>-</b>");
				$accountUsageAry[$i]['projected_bal'] = $row['recharge_amount'];
				$accountUsageAry[$i++]['main_bal']	 = $row['recharge_amount'];
			}
		}
		
		private function GetCoordinatorBillingHistory($user_id, &$accountUsageAry)
		{
			$i = count($accountUsageAry);
			
			$currency = $this->GetCurrencyType($user_id);
		
			$query	=	sprintf("SELECT coordinator_billing_history.*,users.firstname,users.lastname,users.email FROM coordinator_billing_history JOIN  users ON users.user_id=coordinator_billing_history.coordinator_id  WHERE users.owner_id='%s'", $user_id);
		
			$result = mysql_query($query, $this->db_link) or die('Get Coordinator Billing History error : ' . mysql_error());
		
			while($row	= mysql_fetch_array($result))
			{
				$accountUsageAry[$i]['date'] 		 = strtotime($row['xaction_timestamp']);
				$accountUsageAry[$i]['description']   = sprintf("Coordinator account of <b>%s %s(%s)</b> was %s by amount <b>%s%s</b>", $row['firstname'], $row['lastname'], $row['email'], ($row['xaction_type'] == CConfig::CTT_RECHARGE)?"recharged":"reclaimed", ($currency == CConfig::CURRENCY_INR)?"Rs.":"$", $row['amount']);
				
				if($row['xaction_type'] == CConfig::CTT_RECHARGE)
				{
					$accountUsageAry[$i]['credit_amount'] = sprintf("<b>-</b>");
					$accountUsageAry[$i]['debit_amount'] = sprintf("%s", $row['amount']);
				}
				else 
				{
					$accountUsageAry[$i]['credit_amount'] = sprintf("%s", $row['amount']);
					$accountUsageAry[$i]['debit_amount'] = sprintf("<b>-</b>");
				}
				$accountUsageAry[$i]['projected_bal'] = ($row['xaction_type'] == CConfig::CTT_RECHARGE)?-$row['amount'] : $row['amount'];
				$accountUsageAry[$i++]['main_bal'] 	 = ($row['xaction_type'] == CConfig::CTT_RECHARGE)?-$row['amount'] : $row['amount'];
			}
		}
		
		private function GetFreeUserBillingHistory($user_id, &$accountUsageAry)
		{
			$i = count($accountUsageAry);
				
			$currency = $this->GetCurrencyType($user_id);
		
			$query	=	sprintf("select * from free_user_billing_history where user_id='%s'", $user_id);
		
			$result = mysql_query($query, $this->db_link) or die('Get Free User Billing History error : ' . mysql_error());
		
			while($row	= mysql_fetch_array($result))
			{
				$accountUsageAry[$i]['date'] 		 = strtotime($row['xaction_timestamp']);
				$accountUsageAry[$i]['description']   = sprintf("Information of <b>%s</b> free users was enabled.", $row['no_of_candidates']);
				$accountUsageAry[$i]['credit_amount'] = sprintf("<b>-</b>");
				$accountUsageAry[$i]['debit_amount'] = sprintf("%s", $row['amount']);
				$accountUsageAry[$i]['projected_bal'] = -$row['amount'];
				$accountUsageAry[$i++]['main_bal'] 	 = -$row['amount'];
			}
		}
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// Public Functions
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		public function GetUserIdByXactionId($xaction_id)
		{
			$user_id = NULL;
				
			$query = sprintf("select user_id from user_billing_history where transaction_id=%d", $xaction_id);
				
			$result = mysql_query($query, $this->db_link) or die('Get user id by transaction id error : ' . mysql_error());
				
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				$user_id = $row['user_id'];
			}
			return $user_id;
		}
		
		public function GetQuesSource($test_id)
		{
			$query = sprintf("select ques_source from test_dynamic where test_id='%s'", $test_id);
		
			$result = mysql_query($query, $this->db_link) or die('[Billing] Get QuesSource error : ' . mysql_error());
			
			$row = mysql_fetch_array($result);
			
			return $row['ques_source'];
		}
		
		public function GetTestSchedulerID($tschd_id)
		{
			$query = sprintf("select scheduler_id from test_schedule where schd_id='%s'", $tschd_id);
			
			$result = mysql_query($query, $this->db_link) or die('Get Test Scheduler ID error : ' . mysql_error());
			
			$row = mysql_fetch_array($result);
			
			return $row['scheduler_id'];
		}
		
		public function GetCurrencyType($user_id)
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
		
		public function GetMIpCATQuesRate($user_id)
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
		
		public function GetPersonalQuesRate($user_id)
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
		
		public function GetCandidateSearchRate($user_id)
		{
			$sRet = null;
			$query = sprintf("select rate_cand_search from billing where user_id='%s'", $user_id);
			
			$result = mysql_query($query, $this->db_link) or die('Get Candidate Search Rate error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				
				$sRet = $row['rate_cand_search'];
			}
			
			return $sRet;
		}
		
		public function GetOfflineVersionRate($user_id)
		{
			$sRet = null;
			
			$query = sprintf("select rate_offline_version from billing where user_id='%s'", $user_id);
				
			$result = mysql_query($query, $this->db_link) or die('Get Offline Version Rate error : ' . mysql_error());
				
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
			
				$sRet = $row['rate_offline_version'];
			}
				
			return $sRet;
		}
		
		public function GetAssignedPackageTestRate($test_id, $user_id)
		{
			$sRet = null;
				
			$query = sprintf("select cost from assigned_packages where test_id='%s' and user_id='%s'", $test_id, $user_id);
		
			$result = mysql_query($query, $this->db_link) or die('Get Assigned Package Test Rate error : ' . mysql_error());
		
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
					
				$sRet = $row['cost'];
			}
		
			return $sRet;
		}
		
		public function IsTestAssignedFromPackage($test_id, $user_id)
		{
			$bRet = false;
		
			$query = sprintf("select package_id from assigned_packages where test_id='%s' and user_id='%s'", $test_id, $user_id);
		
			$result = mysql_query($query, $this->db_link) or die('Is Test Assigned From Package error : ' . mysql_error());
		
			if(mysql_num_rows($result) > 0)
			{
				$bRet = true;
			}
			return $bRet;
		}
		
		public function GetSubscriptionPlan($user_id)
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
		
		public function GetPlanType($user_id)
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
		
		public function GetLastEdited($user_id)
		{
			$sRet = null;
			$query = sprintf("select last_edited from billing where user_id='%s'", $user_id);
			
			$result = mysql_query($query, $this->db_link) or die('Get Last Edited error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				
				$sRet = $row['last_edited'];
			}
			
			return $sRet;
		}
		
		public function InsertReceivedPayment($user_id, $payment_mode, $payment_agent, $payment_ordinal, $payment_date, $recharge_amount, $ba_commission_percent)
		{
			$query = sprintf("insert into user_billing_history(user_id, payment_mode, payment_agent, payment_ordinal, payment_date, recharge_amount, ba_commission_percent) values('%s','%s','%s','%s','%s','%s','%s') ", $user_id, $payment_mode, $payment_agent, $payment_ordinal, $payment_date, $recharge_amount, $ba_commission_percent);
			
			//file_put_contents("InsertReceivedPayment.txt", $query);
			$result = mysql_query($query, $this->db_link) or die('Insert Received Payment error : ' . mysql_error());
			
			return mysql_insert_id($this->db_link);
		}
		
		public function RealizePayment($user_id, $xaction_id)
		{
			$query = sprintf("update user_billing_history set realization_date=now() where user_id='%s' and transaction_id='%s'", $user_id, $xaction_id);
			
			$result = mysql_query($query, $this->db_link) or die('Realize Payment error : ' . mysql_error());
			
			return $result;
		}
		
		public function RealizeTransaction($xaction_id, $amount)
		{
			$query = sprintf("update user_billing_history set realization_date=now() where transaction_id='%s'", $xaction_id);
			
			$result = mysql_query($query, $this->db_link) or die('Realize transaction error : ' . mysql_error());
			
			$user_id = $this->GetUserIdByXactionId($xaction_id);
			
			$this->AddBalance($user_id, $amount);
			
			$this->AddProjectedBalance($user_id, $amount);
			
			$ba_id = trim($this->GetBusinessAssociateId($user_id));
			
			if(!empty($ba_id))
			{
				$commission_percent = $this->GetBACommissionByXactionId($xaction_id);
				
				$commision_amount = $this->CalculateAmountFromPercentage($amount, $commission_percent);
				
				$this->AddBABalance($ba_id,$commision_amount);
				
				$this->AddBAEarnedSoFar($ba_id,$commision_amount);	
			}	
		}
		
		public function GetRecentRealization($user_id)
		{
			$retVal = array();
			
			$query = sprintf("select *, DATE_ADD(realization_date, INTERVAL 30 DAY) as exp_date from user_billing_history where user_id='%s' ORDER BY realization_date DESC limit 1;", $user_id);
			
			$result = mysql_query($query, $this->db_link) or die('Get recent realization error: ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$retVal = mysql_fetch_array($result);
				
				$now = new DateTime();
				$last_realization = new DateTime($retVal["realization_date"]);
				
				$retVal["diff_days"] = $last_realization->diff($now)->format("%a");
				$retVal["diff_hour"] = $last_realization->diff($now)->h;
				$retVal["diff_min"] = $last_realization->diff($now)->i;
				$retVal["diff_sec"] = $last_realization->diff($now)->s;
				
				if($retVal["diff_days"] > 30)
				{
					// Reset balance to zero.
					$this->SubBalance($user_id, -1);
					$this->SubProjectedBalance($user_id, -1);
					
					// Make entry in user_billing_history table
					$this->InsertReceivedPayment($user_id, CConfig::PAYMENT_MODE_RESET_ACCOUNT, 
							CConfig::SNC_SITE_NAME, 0, $retVal["exp_date"], 0, 0);
				}
			}
			
			return $retVal;
		}
		
		public function GetBACommissionByXactionId($xaction_id)
		{
			$retVal = 0;
		
			$query = sprintf("select ba_commission_percent from user_billing_history where transaction_id=%d", $xaction_id);
			
			$result = mysql_query($query, $this->db_link) or die('Get BA commission by xaction id error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				$retVal = $row['ba_commission_percent'];
			}
			
			return $retVal;
		}
		
		public function AddBABalance($ba_id, $amount)
		{
			$query = sprintf("update business_associate set balance=balance+%1.2f where ba_id='%s'", $amount, $ba_id);
			
			$result = mysql_query($query, $this->db_link) or die('Add ba balance error : ' . mysql_error());
			
			return $result;
		}
		
		public function SubBABalance($ba_id, $amount)
		{
			$query = sprintf("update business_associate set balance=balance-%1.2f where ba_id='%s'", $amount, $ba_id);
				
			$result = mysql_query($query, $this->db_link) or die('Sub ba balance error : ' . mysql_error());
				
			return $result;
		}
		
		public function AddBAEarnedSoFar($ba_id, $amount)
		{
			$query = sprintf("update business_associate set earned_sofar=earned_sofar+%f where ba_id='%s'", $amount, $ba_id);
			
			$result = mysql_query($query, $this->db_link) or die('Add ba earned so far error : ' . mysql_error());
			
			return $result;
		}
		
		public function VoidTransaction($xaction_id, $void_reason)
		{
			$query = sprintf("update user_billing_history set realization_date=now(),void_reason='%s' where transaction_id='%s'", mysql_real_escape_string($void_reason), $xaction_id);
			//echo $query;
			
			$result = mysql_query($query, $this->db_link) or die('Void Transaction error : ' . mysql_error());
			
			return $result;
			
		}
		
		public function ProcessContribPayment($xaction_id, $cheque_no, $drawn_bank, $cheque_date)
		{
			$query = sprintf("update contrib_encash_history set status=1,cheque_dd_no='%s',drawn_bank='%s',cheque_dd_date='%s' where transaction_id='%s'", $cheque_no, $drawn_bank, date('Ymd', strtotime($cheque_date)), $xaction_id);
		
			$result = mysql_query($query, $this->db_link) or die('Process contrib payment error : ' . mysql_error());
		
			return $result;
		}
		
		public function GetBalance($user_id)
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
		
		public function SubBalance($user_id, $amount)
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
		
		public function AddBalance($user_id, $amount)
		{
			$query = sprintf("update billing set balance=balance+%1.2f where user_id='%s'", $amount, $user_id);
			
			$result = mysql_query($query, $this->db_link) or die('Update Balance error : ' . mysql_error());
			
			return $result;
		}
		
		public function UpdateBalance($user_id, $amount)
		{
			$query = sprintf("update billing set balance='%s' where user_id='%s'", $amount, $user_id);
			
			$result = mysql_query($query, $this->db_link) or die('Update Balance error : ' . mysql_error());
			
			return $result;
		}
		
		public function GetProjectedBalance($user_id)
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
		
		public function SubProjectedBalance($user_id, $amount)
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
		
		public function AddProjectedBalance($user_id, $amount)
		{
			$query = sprintf("update billing set projected_balance=projected_balance+%1.2f where user_id='%s'", $amount, $user_id);
			
			$result = mysql_query($query, $this->db_link) or die('Update Projected Balance error : ' . mysql_error());
			
			return $result;
		}
		
		public function UpdateProjectedBalance($user_id, $amount)
		{
			$query = sprintf("update billing set projected_balance='%s' where user_id='%s'", $amount, $user_id);
			
			$result = mysql_query($query, $this->db_link) or die('Update Projected Balance error : ' . mysql_error());
			
			return $result;
		}
		
		public function ResetBillingRates($user_id, $rate_mipcat_ques, $rate_personal_ques, $rate_cand_search)
		{
			// If rate is null, keep that as is.
			$rate_mipcat_ques 	= ($rate_mipcat_ques == null) ? "rate_mipcat_ques": $rate_mipcat_ques;
			$rate_personal_ques = ($rate_personal_ques == null) ? "rate_personal_ques" : $rate_personal_ques;
			$rate_cand_search 	= ($rate_cand_search == null) ? "rate_cand_search" : $rate_cand_search;
			
			$query = sprintf("update billing set rate_mipcat_ques=%s, rate_personal_ques=%s, rate_cand_search=%s where user_id='%s'", $rate_mipcat_ques, $rate_personal_ques, $rate_cand_search, $user_id);
			
			$result = mysql_query($query, $this->db_link) or die('Reset Billing Rates error : ' . mysql_error());
			
			return $result;
		}
		
		public function GetBusinessAssociateName($user_id)
		{
			$sRet = null;
			
			$query = sprintf("select * from organization join users on users.buss_assoc_id = organization.organization_id and users.user_id='%s'", $user_id);
			
			$result = mysql_query($query, $this->db_link) or die('Get Business Associate Name error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				
				$sRet = $row['organization_name']." (URL: ".$row['organization_url'].")";
			}
			
			return $sRet;
		}
		
		public function GetBACommissionRate($user_id)
		{
			$nRet = 0;
			
			$query = sprintf("( SELECT count( * ) as info FROM user_billing_history WHERE user_billing_history.user_id = '%s' AND user_billing_history.payment_mode <> -1 ) UNION ( SELECT buss_assoc_id FROM users WHERE users.user_id = '%s' ) ", $user_id, $user_id, CConfig::PAYMENT_MODE_FREE);
			
			file_put_contents("GetBACommissionRate.txt", $query);
			$result = mysql_query($query, $this->db_link) or die('Get BA Commission Rate error : ' . mysql_error());
			
			$nRowCount = mysql_num_rows($result);
			if($nRowCount == 2)
			{
				$row_1 = mysql_fetch_array($result);
				$row_2 = mysql_fetch_array($result);
								
				if($row_1['info'] == 0 && !empty($row_2['info']))
				{
					$nRet = 20;
				}
				else if($row_1['info'] > 0 && !empty($row_2['info']))
				{
					$nRet = 10;
				}
			}
			
			return $nRet;
		}
		
		public function CanSchedule($user_id, $amount)
		{
			$projected_balance = $this->GetProjectedBalance($user_id);
			
			$bRet = $projected_balance > $amount ? true : false;
			
			return $bRet;
		}
		
		public function ApplyPlan($user_id, $user_type, $currency="USD", $plan_type = CConfig::SPT_BASIC, $plan_rate = CConfig::SPR_BASIC, $usage_type = CConfig::AUT_PER_TEST, $payment_type = CConfig::BPT_PREPAID)
		{
			$query = "";
			$planDes = array(CConfig::UT_INSTITUTE => "institute",
							 CConfig::UT_CORPORATE => "corporate",
							 CConfig::UT_INDIVIDAL => "individual");

			// If rate(-1) means Not applicable.
			$rate_mipcat 	= 0;
			$rate_personal 	= 0;
			if($currency == "USD")
			{
				$rate_personal 	= CConfig::$USD_SUBSCRIPTION_PLANS[$user_type]["RATE_PERSONAL_QUESTION"];
				$rate_mipcat 	= CConfig::$USD_SUBSCRIPTION_PLANS[$user_type]["RATE_MIPCAT_QUESTION"];
			}
			else 
			{
				$rate_personal 	= CConfig::$INR_SUBSCRIPTION_PLANS[$user_type]["RATE_PERSONAL_QUESTION"];
				$rate_mipcat	= CConfig::$INR_SUBSCRIPTION_PLANS[$user_type]["RATE_MIPCAT_QUESTION"];
			}
								
			$rate_search = -1;

			if($plan_type == CConfig::SPT_PPU)
			{
				$balance = CConfig::SPR_PPU_INR * 10; 
				$projected_balance = $balance;
			}
			else 
			{
				$balance = 0;
				$projected_balance = $balance;
			}

			$last_billed = date('Ymd H:i:s', time());
			
			$query = sprintf("insert into billing(user_id, currency, rate_mipcat_ques, rate_personal_ques, rate_cand_search, subscription_plan, balance, projected_balance, plan_type, usage_type, payment_type) values('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')",
							$user_id, $currency, $plan_rate, $plan_rate, $plan_rate, $planDes[$user_type], $balance, $projected_balance, $plan_type, $usage_type, $payment_type);
			
			//echo $query;
			$bRet = mysql_query($query, $this->db_link) or die ("Error during applying plan :".mysql_error($this->db_link_id));
			
			return $bRet;
		}
		
		public function PopulateBillingHistory($user_id, $time_zone)
        {
            $query = sprintf("select * from user_billing_history where user_id='%s' and void_reason IS NULL",$user_id);
           
            $result = mysql_query($query, $this->db_link) or die('Populate Billing History error : ' . mysql_error());
           
            if(mysql_num_rows($result) > 0)
            {
            	//$reset = date_default_timezone_get();
            	//date_default_timezone_set($this->tzOffsetToName($time_zone));
            	$dtzone = new DateTimeZone($this->tzOffsetToName($time_zone));
                while($row = mysql_fetch_array($result))
                {
                    echo "<tr>";
                    echo "<td>".$row['transaction_id']."</td>";
                    echo "<td>".$row['recharge_amount']."</td>";
                    echo "<td>".CConfig::$PAYMENT_MODE_TEXT_ARY[$row['payment_mode']]."</td>";
                    echo "<td>".$row['payment_agent']."</td>";
                    echo "<td>".$row['payment_ordinal']."</td>";
                    $paymentDtime  = new DateTime($row['payment_date']);
                    $paymentDtime->setTimezone($dtzone);
                    echo "<td>".$paymentDtime->format("F d, Y")."</td>";
                    if(!empty($row['realization_date']))
                    {
                    	$realizationDtime  = new DateTime($row['realization_date']);
                    	$realizationDtime->setTimezone($dtzone);
                    	echo "<td>".$realizationDtime->format("F d, Y [H:i:s]")."</td>";
                    }
                    else
                    {
                        echo "<td>Realization In Process</td>";
                    }
                    echo "</tr>";
                }
                //date_default_timezone_set($reset);
            }
        }
		
		public function PopulatePendingRealizationUsers()
		{
			$query = sprintf("select ubh.transaction_id,users.email,users.firstname,users.lastname,users.organization_id from user_billing_history as ubh join users on ubh.user_id=users.user_id where ubh.realization_date is NULL");
			
			$result = mysql_query($query, $this->db_link) or die('Populate pending realization users error : ' . mysql_error());
			
			while($row = mysql_fetch_array($result))
			{
				printf("<option value='%d' id='%d'>%s %s(%s, %s)</option>",$row['transaction_id'],$row['transaction_id'],$row['firstname'],$row['lastname'],$this->GetOrganizationName($row['organization_id']),$row['email']);
			}
		}
		
		public function GetContribEncashHistoryByTransId($transaction_id)
		{
			$retArray = NULL;
		
			$query = sprintf("select * from contrib_encash_history where transaction_id=%d",$transaction_id);
			
			$result = mysql_query($query, $this->db_link) or die('Get contrib encash history by transaction id error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$retArray = mysql_fetch_array($result);
			}
			
			return $retArray;		
		}		
		
		public function PopulatePendingPaymentContributors()
		{
			$query = sprintf("select ceh.transaction_id,users.email,users.firstname,users.lastname from contrib_encash_history as ceh join users on ceh.user_id=users.user_id where ceh.status=0");
			
			$result = mysql_query($query, $this->db_link) or die('Populate pending realization users error : ' . mysql_error());
			
			while($row = mysql_fetch_array($result))
			{
				printf("<option value='%d' id='%d'>%s %s(%s)</option>",$row['transaction_id'],$row['transaction_id'],$row['firstname'],$row['lastname'],$row['email']);
			}
		}
		
		public function GetBillingHistoryByTransId($transaction_id)
		{
			$retArray = NULL;
		
			$query = sprintf("select * from user_billing_history where transaction_id=%d",$transaction_id);
			
			$result = mysql_query($query, $this->db_link) or die('Get billing history by transaction id error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$retArray = mysql_fetch_array($result);
			}
			
			return $retArray;
		}
		
		public function PopulateBATransactionHistory($ba_id)
		{
			$query = sprintf("select * from ba_xaction_history where ba_id='%s'",$ba_id);
			
			$result = mysql_query($query, $this->db_link) or die('Populate ba transaction history error : ' . mysql_error());
			
			$reset = date_default_timezone_get();
			date_default_timezone_set($this->tzOffsetToName($time_zone));
		
			while($row = mysql_fetch_array($result))
			{
				printf("<tr>");
				printf("<td>%01.2f</td>",$row['debt_amount']);
				
				$gross_amount = $row['debt_amount'] + $row['service_tax_factor'] + $row['tds_factor'];
				
				printf("<td>%01.2f</td>",$gross_amount);
				printf("<td>%01.2f</td>",$row['service_tax_factor']);
				printf("<td>%01.2f</td>",$row['tds_factor']);
				printf("<td>%s</td>",CConfig::$PAYMENT_MODE_TEXT_ARY[$row['payment_mode']]);
				
				if($row['payment_mode'] == CConfig::PAYMENT_MODE_NEFT)
				{
					printf("<td>%s</td>",$row['bank_ifsc']);
				}
				else
				{
					printf("<td>Not Applicable</td>");
				}
				
				printf("<td>%s</td>",$row['payment_ordinal']);
				printf("<td>%s</td>",date("F d, Y [H:i:s]", strtotime($row['payment_date'])));
				printf("</tr>");
			}
			
			date_default_timezone_set($reset);
		}
		
		public function PopulateUsersForFreeRecharge($user_type)
		{
			$query = sprintf("select user_id,firstname,lastname,email,organization_id from users where user_type=%d",$user_type);
			
			$result = mysql_query($query, $this->db_link) or die('Populate users for free recharge : ' . mysql_error());
			
			while($row = mysql_fetch_array($result))
			{
				$currency = $this->GetCurrencyType($row['user_id']);
				printf("<option value='%s%s' id='%s%s'>%s %s(%s, %s)</option>",$currency,$row['user_id'],$currency,$row['user_id'],$row['firstname'],$row['lastname'],$this->GetOrganizationName($row['organization_id']),$row['email']);
			}
		}
		
		public function ProcessFreeRecharge($user_id, $amount)
		{
			mt_srand(mktime());
			$rand_value = mt_rand();
			
			$query = sprintf("insert into user_billing_history(user_id,payment_mode,payment_agent,payment_ordinal,payment_date,realization_date,recharge_amount) values('%s',%d,'Free One Time Recharge','%s',NOW(),NOW(),'%s')",$user_id,CConfig::PAYMENT_MODE_FREE,$rand_value,$amount);
		
			$result = mysql_query($query, $this->db_link) or die('Process free recharge error : ' . mysql_error());
			
			$this->AddBalance($user_id, $amount);
			
			$this->AddProjectedBalance($user_id, $amount);
		}
		
		public function PopulateBAEarningSourceHistory($ba_id)
		{
			$query = sprintf("select ubh.user_id,users.email,users.firstname,users.lastname,users.organization_id,payment_date,realization_date,recharge_amount,ba_commission_percent from user_billing_history as ubh join users on users.user_id=ubh.user_id where users.buss_assoc_id='%s' and ubh.void_reason is NULL",$ba_id);;
		
			$result = mysql_query($query, $this->db_link) or die('Populate ba earning history error : ' . mysql_error());
			
			while($row = mysql_fetch_array($result))
			{
				$org_name = $this->GetOrganizationName($row['organization_id']);
				echo "<tr>";
				echo "<td>".$row['firstname']." ".$row['lastname']."(".$org_name.", ".$row['email'].")</td>";
				echo "<td>".$row['payment_date']."</td>";
				echo "<td>".$row['realization_date']."</td>";
				printf("<td>%01.2f</td>",$this->CalculateAmountFromPercentage($row['recharge_amount'], $row['ba_commission_percent']));
				echo "</tr>";
			}
		}
		
		public function PopulateBAForProcessPayment()
		{
			$query = sprintf("select ba.ba_id,users.firstname,users.lastname,users.email,users.organization_id from business_associate as ba join users on ba.ba_id=users.user_id where ba.balance <> 0");
			
			$result = mysql_query($query, $this->db_link) or die('Populate ba for process payment error : ' . mysql_error());
			
			while($row = mysql_fetch_array($result))
			{
				printf("<option value='%s' id='%s'>%s %s(%s, %s)</option>", $row['ba_id'], $row['ba_id'], $row['firstname'], $row['lastname'], $this->GetOrganizationName($row['organization_id']), $row['email']);
			}
		}
		
		public function ProcessBAPayment($ba_id, $gross_amount, $debt_amount, $service_tax_amount, $tds_amount, $payment_ordinal, $payment_date, $payment_agent)
		{
			$query = sprintf("insert into ba_xaction_history(ba_id,debt_amount,service_tax_factor,tds_factor,payment_mode,bank_ifsc,payment_ordinal,payment_date) values('%s','%s','%s','%s','%s','%s','%s','%s')",$ba_id, $debt_amount, $service_tax_amount, $tds_amount,$this->GetBAPaymentMode($ba_id), $payment_agent, $payment_ordinal, date('Ymd', strtotime($payment_date)));
			
			$result = mysql_query($query, $this->db_link) or die('Process ba payment error : ' . mysql_error());
			
			$this->SubBABalance($ba_id, $gross_amount);
		}
		
		public function DoneBAClientPayment($client_xaction_array)
		{
			for($index = 0; $index < count($client_xaction_array); $index++)
			{
				$query = sprintf("update user_billing_history set ba_xaction_done=1 where transaction_id=%d", $client_xaction_array[$index]);
				
				$result = mysql_query($query, $this->db_link) or die('Done ba client payment error : ' . mysql_error());
			}	
		}
		
		public function PopulateClientsForProcessBAPayment($ba_id)
		{
			$query = sprintf("select ubh.transaction_id,ubh.user_id,users.firstname,users.lastname,users.email,users.organization_id,ubh.recharge_amount,ubh.ba_commission_percent from user_billing_history as ubh join users on ubh.user_id=users.user_id where ubh.ba_xaction_done=0 and realization_date is not NULL and void_reason is NULL and payment_mode <> -1 and users.buss_assoc_id='%s'", $ba_id);
		
			$result = mysql_query($query, $this->db_link) or die('Populate client for process ba payment error : ' . mysql_error());
		
			$theme_changer = 0;
		
			while($row = mysql_fetch_array($result))
			{
				$theme = "warning";
				if($theme_changer%2 == 0)
				{
					$theme = "info";
				}
				printf("<tr class='%s'>",$theme);
				printf("<td>%s %s</td>", $row['firstname'], $row['lastname']);
				printf("<td>%s</td>", $this->GetOrganizationName($row['organization_id']));
				printf("<td>%s</td>", $row['email']);
				printf("<td>%01.2f</td>", $row['recharge_amount']);
				printf("<td id='amount%d'>%01.2f</td>", $row['transaction_id'], $this->CalculateAmountFromPercentage($row['recharge_amount'],$row['ba_commission_percent']));
				printf("<td><input type='checkbox' name='payment_done[]' id='%d' value='%d' onchange='OnCommissionSelect(this);' /></td>", $row['transaction_id'], $row['transaction_id']);
				printf("</tr>");
				$theme_changer++;
			}
		}
		
		public function GetBAPaymentMode($ba_id)
		{
			$retVal = NULL;
		
			$query = sprintf("select pref_pmnt_mode from business_associate where ba_id='%s'", $ba_id);
			
			$result = mysql_query($query, $this->db_link) or die('Get ba payment mode error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				$retVal = $row['pref_pmnt_mode'];
			}
			return $retVal;
		}
		
		public function PopulateBAClientInfo($ba_id)
        {
            $query = sprintf("select * from users where buss_assoc_id='%s'",$ba_id);
               
            $result = mysql_query($query, $this->db_link) or die('populate BA client info error : ' . mysql_error());
               
            if(mysql_num_rows($result) > 0)
            {
                while($row=mysql_fetch_array($result))
                {
                    $org_name            = $this->GetOrganizationName($row['organization_id']);
                    $balance             = $this->GetBalance($row['user_id']);
                    $projected_balance  = $this->GetProjectedBalance($row['user_id']);
                   
                    echo "<tr id='".$row['user_id']."'>";
                    echo "<td>".$row['firstname']." ".$row['lastname']."</td>";
                    echo "<td>".$org_name."</td>";
                    echo "<td>".$row['email']."</td>";
                    echo "<td>".$row['contact_no']."</td>";
                   
                    if(!empty($row['address']))
                    {
                        echo "<td>".$row['address']."</td>";
                    }
                    else
                    {
                        echo "<td>Not Available</td>";
                    }
                    echo "<td>".$row['city'].", ".$row['state'].", ".$row['country']."</td>";
                   
                    if(!empty($balance))
                    {
                        echo "<td>".$balance."</td>";
                    }
                    else
                    {
                        echo "<td>0.0</td>";
                    }

                    if(!empty($projected_balance))
                    {
                        echo "<td>".$projected_balance."</td>";
                    }
                    else
                    {
                        echo "<td>0.0</td>";
                    }
                    echo "</tr>";
                }
            }
        }
        
        public function PopulateCoordinator($user_id)
        {
       
            $query = sprintf("select * from users LEFT JOIN coordinator ON coordinator.coordinator_id = users.user_id where  locate('%s',owner_id) AND user_type=%d", $user_id, CConfig::UT_COORDINATOR);
               
       		//echo ($query);
       		
            $result = mysql_query($query, $this->db_link) or die('Select from user error : ' . mysql_error());
       		
            while($row=mysql_fetch_array($result))
            {
            	$proj_balance = $this->GetProjectedBalance($row['user_id']);
            	$balance = $this->GetBalance($row['user_id']);
                echo "<tr id='".$row['user_id']."'>";
                echo "<td>".$row['firstname']." ".$row['lastname']."</td>";
                echo "<td>".$row['contact_no']."</td>";
                echo "<td>".$row['email']."</td>";
                echo "<td>".$row['city'].", ".$row['state']."</td>";
                echo "<td>".$row['department']."</td>";
                echo "<td>".$balance."</td>";
                echo "<td>".$proj_balance."</td>";
                echo "<td><input type='button' coord_id='".$row['user_id']."' proj_balance='".$proj_balance."' main_balance='".$balance."'  class='btn btn-sm btn-primary' id='".$row['user_id']."' onclick='OnEditDetails(this);' value='Edit Account Details'/></td>";
                echo "</tr>";
            }
        }
        
         
        public function GetFinishedTests($schd_id)
        {
        	$query 		= sprintf("select count(*) as completed from result where tschd_id='%s'",$schd_id);
        	//echo $query;
        	$result 	= mysql_query($query, $this->db_link) or die('Get Test Finished Test error : '. mysql_error());
        	
        	if(mysql_num_rows($result) >0)
        	{
        		$row	= mysql_fetch_assoc($result);
        		$retVal	= $row['completed'];	
        	}
        	return $retVal;
       	}
       	
       	public function AddCoordinatorBillingHistory($coordinator_id, $amount, $xaction_type)
       	{
       		$query  = sprintf("insert into coordinator_billing_history(coordinator_id, amount, xaction_type) values('%s', '%s', '%s')", $coordinator_id, $amount, $xaction_type);
       		
       		$result	= mysql_query($query, $this->db_link) or die('Add Coordinator Billing History error : '. mysql_error());
       	}
       	
       	public function AddFreeUserBillingHistory($user_id, $no_of_cands, $amount)
       	{
       		$query = sprintf("insert into free_user_billing_history(user_id, no_of_candidates, amount) values('%s', '%s', '%s')", $user_id, $no_of_cands, $amount);
       		
       		$result	= mysql_query($query, $this->db_link) or die('Add Free User Billing History error : '. mysql_error());
       	}
       	
       	public function PopulateAccountUsage($user_id, $time_zone, $from_date="", $to_date="")
       	{
       		$accountUsageAry = array();
       		$this->GetScheduledTestBillingInfo($user_id, $accountUsageAry);
       		$this->GetUserRechargeHistory($user_id, $accountUsageAry);
       		$this->GetCoordinatorBillingHistory($user_id, $accountUsageAry);
       		$this->GetFreeUserBillingHistory($user_id, $accountUsageAry);
       		
       		$sort_date_ary = array();
       		foreach($accountUsageAry as $index)
       		{
       			array_push($sort_date_ary, $index['date']);
       		}
       		
       		array_multisort($sort_date_ary, SORT_DESC, $accountUsageAry);
       		
       		$projected_balance = 0;
       		$main_balance      = 0;
       		
       		$dtzone = new DateTimeZone($this->tzOffsetToName($time_zone));
       		
       		for($iterator = count($accountUsageAry)-1; $iterator >= 0; $iterator--)
       		{
       			$projected_balance += $accountUsageAry[$iterator]['projected_bal'];
       			$main_balance 	   += $accountUsageAry[$iterator]['main_bal'];
       			
       			$dtTime  = new DateTime();
       			//$dtTime->setTimestamp(strtotime(date("d F Y",$accountUsageAry[$iterator]['date'])));
       			$dtTime->setTimestamp($accountUsageAry[$iterator]['date']);
       			$dtTime->setTimezone($dtzone);
       			
       			if(!empty($from_date))
       			{
       				if(strtotime($from_date) <= strtotime($dtTime->format("d F Y")) && strtotime($to_date) >= strtotime($dtTime->format("d F Y")))
       				{
       					print "<tr>";
       					print "<td>".$dtTime->getTimestamp()."</td>";
       					print "<td>".$dtTime->format("F d, Y")."</td>";
       					print "<td>".$accountUsageAry[$iterator]['description']."</td>";
       					print "<td>".$accountUsageAry[$iterator]['credit_amount']."</td>";
       					print "<td>".$accountUsageAry[$iterator]['debit_amount']."</td>";
       					print "<td>".$projected_balance."</td>";
       					print "<td>".$main_balance."</td>";
       					print "</tr>";
       				}
       			}
       			else 
       			{
       				if($iterator >= 10)
       				{
       					continue;
       				}
       				print "<tr>";
       				print "<td>".$dtTime->getTimestamp()."</td>";
       				print "<td>".$dtTime->format("F d, Y")."</td>";
       				print "<td>".$accountUsageAry[$iterator]['description']."</td>";
       				print "<td>".$accountUsageAry[$iterator]['credit_amount']."</td>";
       				print "<td>".$accountUsageAry[$iterator]['debit_amount']."</td>";
       				print "<td>".$projected_balance."</td>";
       				print "<td>".$main_balance."</td>";
       				print "</tr>";
       				$limit++;
       			}
       		}
       		
       	}
        
	}
?>