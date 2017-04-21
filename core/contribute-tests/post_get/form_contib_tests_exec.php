<?php
	include_once(dirname(__FILE__)."/../../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../../database/config.php");
	include_once(dirname(__FILE__)."/../../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/../../../lib/utils.php");
	
	function clean($str) 
	{
		/*if(!get_magic_quotes_gpc()) 
		{
			$str = trim(mysql_real_escape_string($str));
		}
		else */
		{
			$str = trim($str);
		}

		return $str;
	}
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	
	$test_id 		= $_POST['test_id'];
	$keywords 		= clean($_POST['keywords']);
    $description	= clean($_POST['description']);
    
	$objDB = new CMcatDB();
	
	$test_name = $objDB->InsertTestDecr($test_id, $keywords, $description);
	
	CUtils::Redirect("../contrib_tests.php?test_name=".$test_name);
?>