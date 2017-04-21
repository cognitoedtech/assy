<?php
	
	include_once(dirname(__FILE__)."/../../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/../../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../../lib/utils.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	
	$objDB = new CMcatDB();
	
	$ques_type = $_POST['ques_type'];
	
	$data_row  = array();
	
	$data_row[CConfig::$QUES_XLS_HEADING_ARY["S No"]] = -1;
	$data_row[CConfig::$QUES_XLS_HEADING_ARY["Para Description"]] = "Not required here because it is not bulk upload";
	
	$code_error = array();
	 
	if($_POST['question_choice'] == "text")
	{			
		if(!$objDB->IsProgrammingCodeValid(trim($_POST['question_choice_text'])))
		{
			array_push($code_error, "question");
		}
		else 
		{
			$data_row[CConfig::$QUES_XLS_HEADING_ARY["Question"]] = str_ireplace(CConfig::OPER_CODE_END,"</div>",str_ireplace(CConfig::OPER_CODE_START,"<div class='mipcat_code_ques'>",str_replace(">","&gt;",str_replace("<","&lt;",str_replace("&","&amp;",str_replace("’", "'", trim($_POST['question_choice_text'])))))));
		}
	}
	else 
	{
		$data_row[CConfig::$QUES_XLS_HEADING_ARY["Question"]] = file_get_contents($_FILES['question_choice_img']['tmp_name']);
	}
	
	$ans_ary = array();
	for($opt_index = CConfig::$QUES_XLS_HEADING_ARY["Option 1"], $opt_count = 1; $opt_count <= intval($_POST['options_count']); $opt_count++, $opt_index++)
	{
		if($_POST['option'.$opt_count.'_choice'] == "text")
		{
			$data_row[$opt_index] = trim($_POST['option'.$opt_count.'_choice_text']);
			
			if(!$objDB->IsProgrammingCodeValid(trim($_POST['question_choice_text'])))
			{
				array_push($code_error, "option ".$opt_count);
			}
			else 
			{
				$data_row[$opt_index] = str_ireplace(CConfig::OPER_CODE_END,"</div>",str_ireplace(CConfig::OPER_CODE_START,"<div class='mipcat_code_ques'>",str_replace(">","&gt;",str_replace("<","&lt;",str_replace("&","&amp;",str_replace("’", "'", trim($_POST['option'.$opt_count.'_choice_text'])))))));
			}
		}
		else
		{
			$data_row[$opt_index] = file_get_contents($_FILES['option'.$opt_count.'_choice_img']['tmp_name']);
		}	
		
		if(in_array("option".$opt_count, $_POST['answers']))
		{
			array_push($ans_ary, $opt_count);
		}
	}
	
	$data_row[CConfig::$QUES_XLS_HEADING_ARY["Difficulty"]] = $_POST['difficulty'];
	$data_row[CConfig::$QUES_XLS_HEADING_ARY["Answer"]] = implode(",", $ans_ary);
	
	if($_POST['explanation'] == "text")
	{
		if(!$objDB->IsProgrammingCodeValid(trim($_POST['explanation_text'])))
		{
			array_push($code_error, "Explanation");
		}
		else
		{
			$data_row[CConfig::$QUES_XLS_HEADING_ARY["Explanation"]] = str_ireplace(CConfig::OPER_CODE_END,"</div>",str_ireplace(CConfig::OPER_CODE_START,"<div class='mipcat_code_ques'>",str_replace(">","&gt;",str_replace("<","&lt;",str_replace("&","&amp;",str_replace("’", "'", trim($_POST['explanation_text'])))))));
		}
	}
	else 
	{
		$data_row[CConfig::$QUES_XLS_HEADING_ARY["Explanation"]] = file_get_contents($_FILES['explanation_image']['tmp_name']);
	}
	
	$linked_to = NULL;
	$mca	   = 0;
	if($ques_type == CConfig::QT_READ_COMP || $ques_type ==  CConfig::QT_DIRECTIONS)	
	{
		if($_POST['rc_dir_existing_choice'] == "yes")
		{
			$para_info_ary = explode(";",$_POST['existing_para']);
			
			$linked_to =  $para_info_ary[0];
			
			$data_row[CConfig::$QUES_XLS_HEADING_ARY["Topic"]]    = $para_info_ary[1];
			
			$data_row[CConfig::$QUES_XLS_HEADING_ARY["Subject"]]  = $para_info_ary[2];
			
			$data_row[CConfig::$QUES_XLS_HEADING_ARY["Language"]] = $para_info_ary[3];
			
			$mca = $objDB->IsMCAQuestion($data_row);
		}
		else 
		{
			
			if($objDB->IsTopicExists(trim($_POST['topic']), $user_id))
			{
				CSessionManager::SetErrorMsg("Please use a different title for the given para. Title already exists");
				CUtils::Redirect("../submit_single_ques.php");
				exit();
			}
			
			$data_row[CConfig::$QUES_XLS_HEADING_ARY["Topic"]]    = trim($_POST['topic']);
			$data_row[CConfig::$QUES_XLS_HEADING_ARY["Subject"]]  = trim($_POST['subject']);
			$data_row[CConfig::$QUES_XLS_HEADING_ARY["Language"]] = $_POST['language'];
			
			if($ques_type == CConfig::QT_READ_COMP)
			{
				$mca = $objDB->IsMCAQuestion($data_row);
				if($_POST['rc_dir_type'] == "text")
				{
					if(!$objDB->IsProgrammingCodeValid(trim($_POST['para_text'])))
					{
						array_push($code_error, "Reading Comprehension Para");
					}
					else if(empty($code_error))
					{
						$linked_to = $objDB->InsertReadComp(str_ireplace(CConfig::OPER_CODE_END,"</div>",str_ireplace(CConfig::OPER_CODE_START,"<div class='mipcat_code_ques'>",str_replace(">","&gt;",str_replace("<","&lt;",str_replace("&","&amp;",str_replace("’", "'", trim($_POST['para_text']))))))));
					}
				}
				else
				{
					$linked_to = $objDB->InsertReadComp(file_get_contents($_FILES['para_img']['tmp_name']));
				}
			}
			else 
			{
				if($_POST['rc_dir_type'] == "text")
				{
					if(!$objDB->IsProgrammingCodeValid(trim($_POST['para_text'])))
					{
						array_push($code_error, "Directions Para");
					}
					else if(empty($code_error)) 
					{
						$linked_to = $objDB->InsertDirectionsPara(str_ireplace(CConfig::OPER_CODE_END,"</div>",str_ireplace(CConfig::OPER_CODE_START,"<div class='mipcat_code_ques'>",str_replace(">","&gt;",str_replace("<","&lt;",str_replace("&","&amp;",str_replace("’", "'", trim($_POST['para_text']))))))));
					}
				}
				else
				{
					$linked_to = $objDB->InsertDirectionsPara(file_get_contents($_FILES['para_img']['tmp_name']));
				}
			} 
		}
	}
	else 
	{
		
		if($objDB->IsTopicExists(trim($_POST['topic']), $user_id, $ques_type))
		{
			CSessionManager::SetErrorMsg("Please use a different topic for the question. Topic entered by you is in use as title of some reading comprehension or directions para which is unique.");
			CUtils::Redirect("../submit_single_ques.php");
			exit();
		}
		
		$data_row[CConfig::$QUES_XLS_HEADING_ARY["Topic"]]    = trim($_POST['topic']);
		$data_row[CConfig::$QUES_XLS_HEADING_ARY["Subject"]]  = trim($_POST['subject']);
		$data_row[CConfig::$QUES_XLS_HEADING_ARY["Language"]] = $_POST['language'];
		$mca = $objDB->IsMCAQuestion($data_row);
	}
	ksort($data_row);
	if(empty($code_error)) 
	{
		$objDB->InsertQuestion($data_row, $user_id, $mca, $ques_type, NULL, NULL, $linked_to);
	}
	else 
	{
		CSessionManager::SetErrorMsg("Improper programming code in uploaded ".implode(",", $code_error).". Please use notations properly and do not use coding notations inside another coding notations.");
		CUtils::Redirect("../submit_single_ques.php");
		exit();
	}
	
	CUtils::Redirect("../submit_single_ques.php?ques=1");
	/*echo "<pre>";
	print_r($data_row);
	echo "</pre>";*/
	
	
	
?>