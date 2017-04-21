<?php
	/*
	echo("<pre>");	
	print_r($_POST);
	echo("</pre>");
	exit(0);
	*/
	//Start session
	include_once(dirname(__FILE__)."/../../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../../database/config.php");
	include_once(dirname(__FILE__)."/../../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/../../../lib/user_manager.php");
	include_once(dirname(__FILE__)."/../../../lib/utils.php");
	
	//Function to sanitize values received from the form. Prevents SQL injection
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
	
	$objDB = new CMcatDB();
	
	// Sanitize the POST values
	$test_name 		= clean($_POST['test_name']);
    $duration  		= clean($_POST['duration']);
    $max_ques  		= clean($_POST['max_ques']);
    
    if(clean($_POST['criteria']) == "cutoff")
    {
    	$criteria = 0;
    }
    else if(clean($_POST['criteria']) == "top")
    {
    	$criteria = 1;
    }
    
    $cutoff_min 		= clean($_POST['cutoff_min']);
    $cutoff_max 		= clean($_POST['cutoff_max']);
    $top 				= clean($_POST['top']);
    $r_marks 			= clean($_POST['r_marks']);
    $w_marks 			= clean($_POST['w_marks']);
    $sec_count 			= clean($_POST['sec_count']);
    $ques_source 		= clean($_POST['ques_source']);
    $mcpa_flash_ques  	= clean($_POST['flash_ques']);
    $mcpa_lock_ques  	= clean($_POST['lock_ques']);
    $test_expiration	= $_POST['test_expiration'];
    $attempts			= $_POST['attempts'];
    
    // Test Atributes
    $visibility			= $_POST['visibility'];
    $mcq_type			= $_POST['ques_type'];
    $pref_lang			= $_POST['pref_lang'];
    $allow_trans		= $_POST['allow_trans'];
    $test_nature		= $_POST['test_nature'];
    
    $langAry = "";
    if($ques_source == "mipcat")
    {
    	$langAry = $objDB->GetDistLangFromQues();
    }
    else if($ques_source == "personal")
    {
    	$langAry = $objDB->GetDistLangFromQues($user_id);
    }
    //$langAry = $objDB->GetDistLangFromQues();
    $instrAry = array();
    
    foreach($langAry as $language)
    {
    	if( isset($_POST[$language.'_cust_instr']) )
    	{
    		$instrAry[$language] = $_POST[$language.'_cust_instr'];
    	}
    }
    
    // Get values from Array 
    $SectionName 					= $_POST['SectionName'];
    $SectionQuestions 				= $_POST['SectionQuestions'];
    $SectionMinCutoff 				= $_POST['SectionMinCutoff'];
    $SectionMaxCutoff 				= $_POST['SectionMaxCutoff'];
    $SectionMarksForCorrectAnswer	= $_POST['SectionMarksForCorrectAnswer'];
    $SectionNegetiveMarking			= $_POST['SectionNegetiveMarking'];
	$SubjectId 						= $_POST['SubjectId'];
	$SubjectQues 					= $_POST['SubjectQues'];
    $TopicQuestions 				= $_POST['TopicQuestions'];
    $TopicEasyQues	 				= $_POST['TopicEasyQues'];
    $TopicModerateQues				= $_POST['TopicModerateQues'];
    $TopicDifficultQues     		= $_POST['TopicDifficultQues'];
    $TopicId						= $_POST['TopicId'];
    $TagId							= $_POST['tag'];
    
    //echo $mcq_type;
    $test_id = $objDB->InsertIntoTest($user_id, $test_name, $mcpa_flash_ques, $mcpa_lock_ques, $test_expiration, $attempts, $mcq_type, $pref_lang, $allow_trans, $test_nature, $TagId);
    
    if($test_id !== FALSE)
    {
    	$objDB->InsertIntoTestInstruction($test_id, $instrAry);
    	
    	if($test_nature == CConfig::TEST_NATURE_DYNAMIC)
    	{
	    	$dataRow = array();
	    	
	    	// Process Section Details
		    $section_details = "";
		    $subject_in_section = "";
		    $topic_in_subject = "";
		    $SubIndex = 0;
		    foreach ($SectionName as $index_1 => $section)
		    {	
		    	$sec_min_cutoff 		= $SectionMinCutoff[$index_1] ;
		    	$sec_max_cutoff 		= $SectionMaxCutoff[$index_1] ;
		    	$sec_mark_for_correct	= $SectionMarksForCorrectAnswer[$index_1];
		    	$sec_negetive_mark		= $SectionNegetiveMarking[$index_1];
		    	
		    	$section_details .= $section."#".$SectionQuestions[$index_1]."(".$sec_min_cutoff.",".$sec_max_cutoff.",".$sec_mark_for_correct.",".$sec_negetive_mark.");";		    	
		    	// Process Subject Details
			    foreach ($SubjectId[$index_1] as $index_2 => $subject_id)
			    {
			    	$subject_in_section .= $section.":".$subject_id."#".$SubjectQues[$index_1][$index_2].";";
			    	 
			    	// Process Topic Details
			    	foreach ($TopicId[$SubIndex] as $index_3 => $topic_id)
					{
						//printf("index_1: %s, index_2: %s , index_3: %s <br/><br/>",$index_1, $SubIndex, $index_3);
						$nEasyQues = empty($TopicEasyQues[$SubIndex][$index_3]) ? 0 : $TopicEasyQues[$SubIndex][$index_3];
						$nModrQues = empty($TopicModerateQues[$SubIndex][$index_3]) ? 0 : $TopicModerateQues[$SubIndex][$index_3];
						$nHardQues = empty($TopicDifficultQues[$SubIndex][$index_3]) ? 0 : $TopicDifficultQues[$SubIndex][$index_3];
						
						$topic_in_subject .= $section.":".$subject_id."-".$topic_id."@EASY#".$nEasyQues."&MODERATE#".$nModrQues."&DIFFICULT#".$nHardQues.";";
					}
					
					$SubIndex++;
			    }
		    }
		    
		    //echo "Section Details: ".$section_details."<br/><br/>";
		    //echo "Subject Details: ".$subject_in_section."<br/><br/>";
		    //echo "Topic Details: ".$topic_in_subject."<br/><br/>";
		    $objDB->InsertIntoTestDynamic($test_id, $duration,
		    							  $max_ques, $criteria, $cutoff_min,
		    							  $cutoff_max, $top, $r_marks, $w_marks,
		    							  $sec_count, $ques_source, $section_details,
		    							  $subject_in_section, $topic_in_subject, $ques_source, $visibility);
    	}
    	else if($test_nature == CConfig::TEST_NATURE_STATIC)
    	{
    		$static_ques_qry = "";
    		$temp_qry = "";
    		$section_details = "";
    		
    		if($mcq_type == CConfig::QUES_CTG_SCA)
    		{
    			$temp_qry = sprintf("select ques_id from question");
    		}
    		else if($mcq_type == CConfig::QUES_CTG_MCA)
    		{
    			$temp_qry = sprintf("select ques_id from mca_question");
    		}
    		
    		$topic_in_subject = "";
		    $SubIndex = 0;
		    foreach ($SectionName as $index_1 => $section)
		    {	
		    	$sec_min_cutoff 		= $SectionMinCutoff[$index_1] ;
		    	$sec_max_cutoff 		= $SectionMaxCutoff[$index_1] ;
		    	$sec_mark_for_correct	= $SectionMarksForCorrectAnswer[$index_1];
		    	$sec_negetive_mark		= $SectionNegetiveMarking[$index_1];
		    	
		    	$section_details .= $section."#".$SectionQuestions[$index_1]."(".$sec_min_cutoff.",".$sec_max_cutoff.",".$sec_mark_for_correct.",".$sec_negetive_mark.");";
		    	
		    	// Process Subject Details
			    foreach ($SubjectId[$index_1] as $index_2 => $subject_id)
			    {
			    	$subject_in_section .= $section.":".$subject_id."#".$SubjectQues[$index_1][$index_2].";";
			    	 
			    	// Process Topic Details
			    	foreach ($TopicId[$SubIndex] as $index_3 => $topic_id)
					{
						//printf("index_1: %s, index_2: %s , index_3: %s <br/><br/>",$index_1, $SubIndex, $index_3);
						$nEasyQues = empty($TopicEasyQues[$SubIndex][$index_3]) ? 0 : $TopicEasyQues[$SubIndex][$index_3];
						$nModrQues = empty($TopicModerateQues[$SubIndex][$index_3]) ? 0 : $TopicModerateQues[$SubIndex][$index_3];
						$nHardQues = empty($TopicDifficultQues[$SubIndex][$index_3]) ? 0 : $TopicDifficultQues[$SubIndex][$index_3];
						
						$topic_in_subject .= $section.":".$subject_id."-".$topic_id."@EASY#".$nEasyQues."&MODERATE#".$nModrQues."&DIFFICULT#".$nHardQues.";";
					}
					
					$SubIndex++;
			    }
		    }
    		$objDB->InsertIntoTestStatic($user_id, $test_id, $duration,
		    							  $max_ques, $criteria, $cutoff_min,
		    							  $cutoff_max, $top, $r_marks, $w_marks,
		    							  $sec_count, $ques_source, $questions,
		    							  $section_details, $subject_in_section, 
		    							  $topic_in_subject, $ques_source, $visibility);
    	}
    	
	   CUtils::Redirect("../tdwizard.php?test_name=$test_name");
    }
?>