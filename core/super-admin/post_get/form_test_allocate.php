<?php
	include_once(dirname(__FILE__)."/../../../database/mcat_db.php");
	include_once("../../../lib/billing.php");
	include_once("../../../lib/new-email.php");
	include_once("../../../lib/utils.php");
	include_once("../../../lib/session_manager.php");
	
	
	
	$objDB = new CMcatDB();
	
	//print_r($_POST);	
	$assigner_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	$assignee_id = substr($_POST['user_info'],3);		
	$test_id_ary 	= explode(";", $_POST['test_list']);
	
	foreach($test_id_ary as $test_id)
	{
		if($test_id !='')
		{
		$objDB->allocate_test($assigner_id, $assignee_id, $test_id);
		}
	}
	CUtils::Redirect("../allocate_tests.php?processed=1");	
?>