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