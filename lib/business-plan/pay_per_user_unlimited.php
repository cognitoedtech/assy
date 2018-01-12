<?php 
	include_once(dirname(__FILE__)."/../../database/config.php");

	class CPayPerUserUnlimited
	{
		var $db_link;
		var $plan_type;
		
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// Constructor & Distructor
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		public function __construct($plan_type) 
		{
			$this->plan_type = $plan_type;
			
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
		
		private function Calculate()
		{
				
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