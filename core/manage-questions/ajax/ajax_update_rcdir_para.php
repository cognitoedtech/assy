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
	
	$responseData = array();
	
	if($_POST['rc_dir_type'] == "text")
	{
		if(!$objDB->IsProgrammingCodeValid(trim($_POST['para_text'])))
		{
			$responseData['code_error'] = "Improper programming code uploaded in submitted para. Please use notations properly and do not use coding notations inside another coding notations."; 
		}
		else if(empty($code_error))
		{
			$objDB->UpdatePara(str_ireplace(CConfig::OPER_CODE_END,"</div>",str_ireplace(CConfig::OPER_CODE_START,"<div class='mipcat_code_ques'>",str_replace(">","&gt;",str_replace("<","&lt;",str_replace("&","&amp;",str_replace("’", "'", trim($_POST['para_text']))))))), $_POST['para_id'], $_POST['ques_type']);
			$responseData['para_desc']		= $_POST['para_text'];
			$responseData['html_para_desc'] = str_ireplace(CConfig::OPER_CODE_END,"</div>",str_ireplace(CConfig::OPER_CODE_START,"<div class='mipcat_code_ques'>",str_replace(">","&gt;",str_replace("<","&lt;",str_replace("&","&amp;",str_replace("’", "'", trim($_POST['para_text'])))))));
		}
	}
	else
	{
		$objDB->UpdatePara(file_get_contents($_FILES['para_img']['tmp_name']), $_POST['para_id'], $_POST['ques_type']);
		$responseData['html_para_desc'] = sprintf ("<img src='../../../test/lib/print_image.php?para_id=%s&ques_type=%s'>", $_POST['para_id'], $_POST['ques_type']);
	}
	
	echo json_encode($responseData);
?>