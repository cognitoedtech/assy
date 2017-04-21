<?php 
	include_once(dirname(__FILE__)."/../../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/../../../lib/session_manager.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	
	if(isset($_POST['test_id']))
	{
		$objDB = new CMcatDB();
		
		$form_content_ary = $objDB->GetLatestOTFAForm(trim($_POST['test_id']));
		
		if(empty($form_content_ary))
		{
			//$form_content_ary = array();
			
			$form_content_ary["firstname"] 			= 1;
			$form_content_ary["lastname"] 			= 1;
			$form_content_ary["gender"] 			= 0;
			$form_content_ary["dob"] 				= 0;
			$form_content_ary["contact_no"] 		= 0;
			$form_content_ary["city"] 				= 0;
			$form_content_ary["state"] 				= 0;
			$form_content_ary["country"] 			= 0;
			$form_content_ary["edu_qualification"]  = 0;
		}
		echo json_encode($form_content_ary);
	}
?>