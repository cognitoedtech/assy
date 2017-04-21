<?php
	include_once(dirname(__FILE__)."/../database/config.php");
	include_once(dirname(__FILE__)."/utils.php");
	include_once(dirname(__FILE__)."/email.php");
	
	class CCandResultSearch
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
		
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// Public Functions
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		function ListResults($term, $top = 10)
		{
			
		}
	}
?>