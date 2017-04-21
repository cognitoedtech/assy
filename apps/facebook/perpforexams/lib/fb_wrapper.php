<?php
	include_once(dirname(__FILE__)."/../config/app_config.php");
	include_once(dirname(__FILE__)."/../../facebook-php-sdk/src/facebook.php");
	
	class CFBWrapper
	{
		var $facebook;
		
		public function __construct()
		{
			$config = array(
				'appId'  => CAppConfig::APP_ID,
				'secret' => CAppConfig::APP_SECRET
			);
			
			$this->facebook = new Facebook($config);
		}
		
		public function __destruct()
		{
			
		}
		
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// Private set of functions
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		private function GetLoginURL()
		{
			$params = array(
				'scope' => CAppConfig::APP_SCOPE,
				'redirect_uri' => CAppConfig::APP_URI
			);
			
			return $this->facebook->getLoginUrl($params);
		}
		
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// Public set of functions
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		public function CheckLoginStatus(&$info)
		{
			$RetVal = false;
			
			$user_id = $this->facebook->getUser();
			
			if($user_id) 
			{
				try 
				{
					$info = $this->facebook->api('/me','GET');
					
					$RetVal = true;
				} 
				catch(FacebookApiException $e) 
				{
					$info = $this->GetLoginURL();
					//error_log($e->getType());
					//error_log($e->getMessage());
				}   
			}
			else 
			{
				$info = $this->GetLoginURL();
			}
			
			return $RetVal;
		}
	}
?>