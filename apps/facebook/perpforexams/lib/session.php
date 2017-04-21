<?php
	// Start session
	session_start() ;
	
 	class CSession
 	{
 		static function GetSessionID()
 		{
 			return session_id() ;
 		}
 		
 		static function SetSessionData($name, $value)
 		{
 			$_SESSION[$name] = $value ;
 		}
 		
 		static function GetSessionData($name)
 		{
			return isset($_SESSION[$name]) ? $_SESSION[$name] : null ;
 		}
 	}
?>