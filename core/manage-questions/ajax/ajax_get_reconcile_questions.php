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
	
	if(isset($_POST['language']))
	{
		$QuesAry = $objDB->GetReconcileQuestions($_POST['language'], $_POST['tag_id'], $_POST['subject_id'], $_POST['topic_id'], $user_id);
		
		$qIndex = 0;
		//print_r($QuesAry);
		
		while($qIndex < count($QuesAry))
		{
			if(CUtils::getMimeType($QuesAry[$qIndex]['question']) != "application/octet-stream")
			{
				//$QuesAry[$qIndex]['question'] = "hello";
				//echo "hello";
				$QuesAry[$qIndex]['question'] = sprintf("<img class='img-thumbnail' align='top' src='../../test/lib/print_image.php?qid=%s&opt=0' />", $QuesAry[$qIndex]['ques_id']);
			}
				
			if(!empty($QuesAry[$qIndex]['linked_to']))
			{
				$QuesAry[$qIndex]['para_desc'] = $objDB->GetParaDescription($QuesAry[$qIndex]['linked_to'], $QuesAry[$qIndex]['ques_type']);
				if(CUtils::getMimeType($QuesAry[$qIndex]['para_desc']) != "application/octet-stream")
				{
					$QuesAry[$qIndex]['para_desc'] = sprintf("<img class='img-thumbnail' src='../../test/lib/print_image.php?para_id=%s&ques_type=%s' />",$QuesAry[$qIndex]['linked_to'], $QuesAry[$qIndex]['ques_type']);
				}
				else 
				{
					$QuesAry[$qIndex]['replaced_para_desc'] = str_ireplace("&amp;","&",str_ireplace("&lt;","<",str_ireplace("&gt;",">",str_ireplace("<div class='mipcat_code_ques'>", CConfig::OPER_CODE_START, str_ireplace("</div>", CConfig::OPER_CODE_END,$QuesAry[$qIndex]['para_desc'])))));
				}
			}
			$qIndex++;
		}
			//echo $qIndex;
			//print_r($QuesAry);
			echo(json_encode($QuesAry));
	}
?>