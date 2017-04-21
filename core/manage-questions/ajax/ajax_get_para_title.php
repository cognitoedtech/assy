<?php
	include_once("../../../database/mcat_db.php");
	include_once("../../../lib/utils.php");
	include_once(dirname(__FILE__)."/../../../lib/session_manager.php");
	
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	
	$objDB 		= new CMcatDB();
	
	
	function clean($str)
	{
		/*if(!get_magic_quotes_gpc())
		{
			$str = trim(mysql_real_escape_string($str));
		}
		else*/
		{
			$str = trim($str);
		}
	
		return $str;
	}
	
	$question_type		= clean($_POST["ques_type"]);
	   
	$objDB->PopulateRcdDirTitles($user_id,$question_type);
		
	
	
	
	
	
?>