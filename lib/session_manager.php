<?php
	include_once("session.php") ;
	
	class CSessionManager
	{
		// - - - - - - - - - -
		// Session Variables
		// - - - - - - - - - -
		const STR_USER_ID				= "UserID" ;
		const STR_EMAIL_ID				= "EmailID" ;
		const STR_USER_NAME				= "UserName" ;
		const STR_LOGIN_NAME			= "LoginName" ;
		const STR_REDIRECT_URL			= "RedirectURL" ;
		const INT_USER_TYPE				= "UserType" ; 
		const BOOL_LOGIN				= "Login" ;
		const INT_LAST_SECTION			= "TestLastSection";
		const INT_LAST_QUESTION			= "TestLastQuestion";
		const INT_TEST_TIMER			= "TestTimer";
		const BOOL_DECR_ATTEMPT_COUNT	= "DecrAttemptCount";
		const STR_TRANS_LANG_CHOICE		= "TransLangChoice";
		const STR_TEST_TRANS_LANG		= "TestTransLang";
		const BOOL_SEL_TEST_LANG		= "SelectedTestLang";
		const FLOAT_TIME_ZONE			= "TimeZone";
		const BOOL_FREE_EZEEASSESS_USER  = "FreeEZeeAssessUser";
		const INT_CAPTCH_VALUE			= "CaptchaValue";
		const INT_APPLIED_PLAN			= "AppliedPlan";
		const INT_TEST_SCHEDULE_ID		= "TestScheduleID";
		// - - - - - - - - - -
		
		/*
		 * Constructor.
		 */
		public function __construct()
		{
			
		}
		
		/*
		 * Destructor.
		 */
		public function __destruct()
		{
			
		}
		
		/*
		 * Set Session Variable Value.
		 */
		static function Set($name, $val)
		{
			CSession::SetSessionData($name, $val) ;
		}
		
		/*
		 * Get Session Variable Value.
		 */
		static function Get($name)
		{
			return CSession::GetSessionData($name) ;
		}
		
		/*
		 * Unset Session Variable Value.
		 */
		static function UnsetSessVar($name)
		{
			unset($_SESSION[$name]) ;
		}

		/*
		 * Logout from this session.
		 */
		static function Logout()
		{
			// Unset all of the session variables.
			session_unset();
			
			// Finally, destroy the session.
			session_destroy();
			
			$_SESSION = array();
		}
		
		/*
		 * Set Error into Session.
		 */
		static function SetErrorMsg($err_msg)
		{
			CSessionManager::SetError(true);
			CSession::SetSessionData("ErrMsg", $err_msg) ;
		}
		static function SetErrorType($err_type)
		{
			CSession::SetSessionData("ErrType",$err_type);
		}
		static function GetErrorType()
		{
			CSession::GetSessionData("ErrType");
		}		
		/*
		 * Get Error from Session.
		 */
		static function GetErrorMsg()
		{
			return CSession::GetSessionData("ErrMsg") ;
		}
		/*
		 * Set Error from Session (true or false).
		 */
		static function SetError($value)
		{
			CSession::SetSessionData("Error", $value) ;
		}
		/*
		 * Set IsError into Session.
		 */
		static function IsError()
		{
			return CSession::GetSessionData("Error") ? 1 : 0;
		}		
		static function ResetErrorMsg()
		{
			CSessionManager::SetError(false);
			CSession::SetSessionData("ErrMsg", "");
		}
		
		static function OnSessionExpire($LoadParent=true)
		{
			// Check if session exists
			$login = CSessionManager::Get(CSessionManager::BOOL_LOGIN);
			if($login !== true)
			{
				include_once("site_config.php");
				
				if($LoadParent == true)
				{
					printf("<script>parent.location.replace('%s');</script>", CSiteConfig::ROOT_URL);
				}
				else 
				{
					printf("<script>window.location.replace('%s');</script>", CSiteConfig::ROOT_URL);
				}
				exit();
			}
		}
	}
?>