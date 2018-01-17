<?php
	include_once('business_config.php');
	include_once('pay_per_use.php');
	include_once('pay_per_user_unlimited.php');
	
	class CBusinessPlan
	{
		var $objPlan = NULL;
		
		public function __construct($plan_type)
		{
			if($plan_type == CBusinessConfig::PT_PPU_PLAIN) {
				$objPlan = new CPayPerUse();
			}
			else {
				$objPlan = new CPayPerUserUnlimited($plan_type);
			}
		}
		
		public function __distruct()
		{
			unset($objPlan);
		}
		
		// ----------------------------------------------------------
		// Private Functions
		// ----------------------------------------------------------
		
		
		// **********************************************************
		
		// ----------------------------------------------------------
		// Public Functions
		// ----------------------------------------------------------
		public function PostTestAdjustBilling($schedular_id, $student_id)
		{
			$this->objPlan->PostTestAdjustBilling($schedular_id, $student_id);
		}
		
		public function CanSchedule($schedular_id)
		{
			$this->objPlan->CanSchedule($schedular_id);
		}
		
		public function GetBillingHistory($schedular_id)
		{
			
		}
		
		public function GenerateInvoice($schedular_id, $period_in_days)
		{
			
		}
	}
?>