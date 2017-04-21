<?php
	include_once("../../../database/mcat_db.php");
	include_once("../../../lib/utils.php");
	include_once(dirname(__FILE__)."/../../../lib/session_manager.php");
	
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$objDB 		= new CMcatDB();
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
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
	
	$para_id	= clean($_POST["para_id"]);
	$ques_type  = clean($_POST["ques_type"]);
	   
	$para_desc = $objDB->GetParaDescription($para_id, $ques_type);
	
	$para_desc_type = CUtils::getMimeType($para_desc);
	if($para_desc_type == "application/octet-stream")
	{
		echo $para_desc;
	}
	else
	{
		printf ("<img src='../../../test/lib/print_image.php?para_id=%s&ques_type=%s'>", $para_id, $ques_type);
	}
	
	
	
	
	
?>