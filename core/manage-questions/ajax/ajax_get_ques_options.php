<?php 
	include_once(dirname(__FILE__)."/../../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../../database/config.php");
	include_once(dirname(__FILE__)."/../../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/../../../lib/utils.php");
	
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$objDB = new CMcatDB();
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	
	if(isset($_POST['ques_id']))
	{
		$OptionsAry = $objDB->GetQuesOptions($_POST['ques_id']);
		
		for($opt_idx = 0; $opt_idx < count($OptionsAry); $opt_idx++)
		{
			if(CUtils::getMimeType(base64_decode($OptionsAry[$opt_idx]['option'])) != "application/octet-stream")
			{
				$OptionsAry[$opt_idx]['option'] = sprintf("<img align='top' src='../../test/lib/print_image.php?qid=%s&opt=%s'>", $_POST['ques_id'],($opt_idx + 1));
			}
			else
			{
				$OptionsAry[$opt_idx]['option'] = base64_decode($OptionsAry[$opt_idx]['option']);
			}
		}
		echo(json_encode($OptionsAry));
	}
?>
