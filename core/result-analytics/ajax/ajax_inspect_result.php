<?php
	include_once(dirname(__FILE__)."/../../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../../database/config.php");
	include_once(dirname(__FILE__)."/../../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/../../../lib/utils.php");
	include_once(dirname(__FILE__)."/../../../test/lib/tbl_result.php");
	
	$objTR = new CResult();
	
	$objDB = new CMcatDB();
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	
	$parsAry = parse_url(CUtils::curPageURL());
	$qry = split("[=&]", $parsAry["query"]);
	
	if($qry[0] == "test_pnr")
	{
		$ResultAry = $objTR->GetResultInspectionFromPNR($qry[1]);
		
		$qIndex = 0;
		//print_r($ResultAry);
		
		while($qIndex < count($ResultAry))
		{
			if(CUtils::getMimeType($ResultAry[$qIndex]['question']) != "application/octet-stream")
			{
				//$ResultAry[$qIndex]['question'] = "hello";
				//echo "hello";
				$ResultAry[$qIndex]['question'] = sprintf("<img align='top' src='../../test/lib/print_image.php?qid=%s&opt=0'>", $ResultAry[$qIndex]['ques_id']);
			}
			
			if(!empty($ResultAry[$qIndex]['linked_to']))
			{
				$ResultAry[$qIndex]['para_desc'] = $objDB->GetParaDescription($ResultAry[$qIndex]['linked_to'], $ResultAry[$qIndex]['ques_type']);
				if(CUtils::getMimeType($ResultAry[$qIndex]['para_desc']) != "application/octet-stream")
				{
					$ResultAry[$qIndex]['para_desc'] = sprintf("<img src='../../test/lib/print_image.php?para_id=%s&ques_type=%s'>",$ResultAry[$qIndex]['linked_to'], $ResultAry[$qIndex]['ques_type']);
				}
			}
			
			//echo (count($ResultAry[$qIndex]['options'])."<br/>");
			
			$ansAry = array();
			for($opt_idx = 0; $opt_idx < count($ResultAry[$qIndex]['options']); $opt_idx++)
			{
				if(CUtils::getMimeType(base64_decode($ResultAry[$qIndex]['options'][$opt_idx]['option'])) != "application/octet-stream")
				{
					$ResultAry[$qIndex]['options'][$opt_idx]['option'] = sprintf("<img align='top' src='../../test/lib/print_image.php?qid=%s&opt=%s'>", $ResultAry[$qIndex]['ques_id'],($opt_idx + 1));
				}
				else 
				{
					$ResultAry[$qIndex]['options'][$opt_idx]['option'] = base64_decode($ResultAry[$qIndex]['options'][$opt_idx]['option']);
				}
				
				if($ResultAry[$qIndex]['options'][$opt_idx]['answer'] == 1)
				{
					if($ResultAry[$qIndex]['ques_type'] == CConfig::QT_MATRIX) {
						array_push($ansAry, "(".$ResultAry[$qIndex]['options'][$opt_idx]['option'].") ");
					}
					else {
						array_push($ansAry, ($opt_idx + 1));
					}
				}
			}
			$ResultAry[$qIndex]['ques_type'] = $ResultAry[$qIndex]['ques_type'];
			$ResultAry[$qIndex]['subject'] = ucwords($objDB->GetSubjectName($ResultAry[$qIndex]['subject_id']));
			$ResultAry[$qIndex]['topic'] = ucwords($objDB->GetTopicName($ResultAry[$qIndex]['topic_id']));
			$ResultAry[$qIndex]['answer'] = implode(",", $ansAry);
			$qIndex++;
		}
		//echo $qIndex;
		//print_r($ResultAry);
		echo(json_encode($ResultAry));
	}
?>