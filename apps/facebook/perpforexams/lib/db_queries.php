<?php
	include_once(dirname(__FILE__)."/../../../../database/config.php");
	include_once(dirname(__FILE__)."/../../../../lib/new-email.php");
	
	class CDBQueries
	{
		var $db_link;
		
		public function __construct()
		{
			$this->db_link = mysql_connect(CConfig::HOST, CConfig::USER_NAME, CConfig::PASSWORD);
			mysql_select_db(CConfig::DB_MCAT, $this->db_link);
		}
		
		public function __destruct()
		{
			mysql_close($this->db_link);
		}
		
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// Private set of functions
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		private function CreateUser($login_name, $email, $first_name, $last_name, $dob, 
								$alien_type, $alien_id, $alien_username, $gender, $city, $country)
		{
			
		}
		
		private function IsUserExists($login_name)
		{
			$bRet = false;
			
			
			return $bRet; 
		}
		
		private function UpdateEmailID($login_name, $email, $first_name, $last_name, $dob, 
								$alien_username, $gender, $city, $country)
		{
			$bRet = false;
			
			
			return $bRet;
		}
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// Public set of functions
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		public function RegisterUser($user_profile)
		{
			$bRet = false;
			
			$hometown = null;
			if(isset($user_profile['hometown']))
			{
				$hometown = explode(',', $user_profile['hometown']['name']);
			}
				
			if($this->IsUserExists($user_profile['id']))
			{
				if($this->UpdateEmailID($user_profile['id'], $user_profile['email'], $user_profile['first_name'], 
								  $user_profile['last_name'], date("Y-m-d", strtotime($user_profile['birthday'])), 
								  $user_profile['username'], $user_profile['gender'], trim($hometown[0]), trim($hometown[1])))
				{
					// Send Complementry User Registration (for MIpCAT) email to user.
				}
			}
			else
			{
				$this->CreateUser($user_profile['id'], $user_profile['email'], $user_profile['first_name'], 
								  $user_profile['last_name'], date("Y-m-d", strtotime($user_profile['birthday'])), 
								  2, $user_profile['id'], $user_profile['username'], $user_profile['gender'],
								  trim($hometown[0]), trim($hometown[1]));
				
				// Send Complementry User Registration (for MIpCAT) email to user.
			}
			
			return $bRet; 
		}
		
		public function RegisterChallange()
		{
			
		}
		
		public function ListMyChallanges()
		{
			
		}
		
		public function ListChallangeStats()
		{
			
		}
		
		public function PopulateAvailableChallanges()
		{
			
		}
		
		public function PopulateRankInChallanges()
		{
			
		}
	}
?>