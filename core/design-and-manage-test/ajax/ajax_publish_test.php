<?php 
	include_once(dirname(__FILE__)."/../../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/../../../lib/session_manager.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$objDB = new CMcatDB();
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	
	if($_POST['publish']==1)
	{
		$keywords			= trim($_POST['publish_keywords']);
		$description		= trim($_POST['publish_test_desc']);
		$test_id			= trim($_POST['pub_test_id']);
		
		$objDB->PublishTest($keywords,$description,$test_id);
	}
	else if($_POST['unpublish']==0)
	{	
		$test_id	=	$_POST['test_id'];
		$objDB->UnPublishTest($test_id);
	}
?>
