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
	
	$data_row  = array();
	
	$code_error = array();
	
	$data_row[CConfig::$QUES_XLS_HEADING_ARY["S No"]] 			  = -1;
	$data_row[CConfig::$QUES_XLS_HEADING_ARY["Para Description"]] = "Not required here because it is not bulk upload";
	$data_row[CConfig::$QUES_XLS_HEADING_ARY["Topic"]]    		  = "No need to be updated";
	$data_row[CConfig::$QUES_XLS_HEADING_ARY["Subject"]]  		  = "No need to be updated";
	$data_row[CConfig::$QUES_XLS_HEADING_ARY["Language"]] 		  = "No need to be updated";
	$data_row[CConfig::$QUES_XLS_HEADING_ARY["Explanation"]]	  = "No need to be updated";
	
	if(isset($_POST['question_edit_choice']))
	{
		echo $_POST['question_edit_choice']." hello";
		if($_POST['question_edit_choice'] == "1")
		{
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
			else if($_POST['question_choice'] == "image")
			{
				$data_row[CConfig::$QUES_XLS_HEADING_ARY["Question"]] = file_get_contents($_FILES['question_choice_img']['tmp_name']);
			}
		}
		else
		{
			$data_row[CConfig::$QUES_XLS_HEADING_ARY["Question"]] = base64_decode($_POST['ques_hidden']);
		}	
	}
	else 
	{
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
		else if($_POST['question_choice'] == "image")
		{
			$data_row[CConfig::$QUES_XLS_HEADING_ARY["Question"]] = file_get_contents($_FILES['question_choice_img']['tmp_name']);
		}
	}
	
	$ans_ary = array();
	for($opt_index = CConfig::$QUES_XLS_HEADING_ARY["Option 1"], $opt_count = 1; $opt_count <= intval($_POST['options_count']); $opt_count++, $opt_index++)
	{
		if(isset($_POST['option'.$opt_count.'_edit_choice']))
		{
			if($_POST['option'.$opt_count.'_edit_choice'] == "1")
			{
				if($_POST['option'.$opt_count.'_choice'] == "text")
				{
					$data_row[$opt_index] = trim($_POST['option'.$opt_count.'_choice_text']);
						
					if(!$objDB->IsProgrammingCodeValid(trim($_POST['option'.$opt_count.'_choice_text'])))
					{
						array_push($code_error, "option ".$opt_count);
					}
					else
					{
						$data_row[$opt_index] = str_ireplace(CConfig::OPER_CODE_END,"</div>",str_ireplace(CConfig::OPER_CODE_START,"<div class='mipcat_code_ques'>",str_replace(">","&gt;",str_replace("<","&lt;",str_replace("&","&amp;",str_replace("’", "'", trim($_POST['option'.$opt_count.'_choice_text'])))))));
					}
				}
				else if($_POST['option'.$opt_count.'_choice'] == "image")
				{
					$data_row[$opt_index] = file_get_contents($_FILES['option'.$opt_count.'_choice_img']['tmp_name']);
				}
			}
			else 
			{
				$data_row[$opt_index] = base64_decode($_POST['option'.$opt_count.'_hidden']);
			}
		}
		else 
		{
			if($_POST['option'.$opt_count.'_choice'] == "text")
			{
				$data_row[$opt_index] = trim($_POST['option'.$opt_count.'_choice_text']);
			
				if(!$objDB->IsProgrammingCodeValid(trim($_POST['option'.$opt_count.'_choice_text'])))
				{
					array_push($code_error, "option ".$opt_count);
				}
				else
				{
					$data_row[$opt_index] = str_ireplace(CConfig::OPER_CODE_END,"</div>",str_ireplace(CConfig::OPER_CODE_START,"<div class='mipcat_code_ques'>",str_replace(">","&gt;",str_replace("<","&lt;",str_replace("&","&amp;",str_replace("’", "'", trim($_POST['option'.$opt_count.'_choice_text'])))))));
				}
			}
			else if($_POST['option'.$opt_count.'_choice'] == "image")
			{
				$data_row[$opt_index] = file_get_contents($_FILES['option'.$opt_count.'_choice_img']['tmp_name']);
			}
		}
	
		if(in_array("option".$opt_count, $_POST['answers']))
		{
			array_push($ans_ary, $opt_count);
		}
	}
	
	$data_row[CConfig::$QUES_XLS_HEADING_ARY["Difficulty"]] = $_POST['difficulty'];
	$data_row[CConfig::$QUES_XLS_HEADING_ARY["Answer"]] = implode(",", $ans_ary);
	
	ksort($data_row);
	$mca = $objDB->IsMCAQuestion($data_row);
	
	if(empty($code_error))
	{
		$objDB->UpdateQuestion($data_row, $_POST['ques_id'], $mca);
		
		CUtils::Redirect("../dt_reconcile_questions.php?ques_updated=1");
	}
	else 
	{
		CSessionManager::SetErrorMsg("Improper programming code uploaded in ".implode(",", $code_error).". Please use notations properly and do not use coding notations inside another coding notations.");
		CUtils::Redirect("../dt_reconcile_questions.php");
		exit();
	}
?>