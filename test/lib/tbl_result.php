<?php
	include_once(dirname(__FILE__)."/../../lib/utils.php");
	include_once(dirname(__FILE__)."/../../database/config.php");
	include_once("tbl_test_dynamic.php");
	include_once("tbl_question.php");
	
	class CResult
	{
		private $objDBLink;
		
		public function __construct()
		{
			$this->objDBLink = mysql_connect(CConfig::HOST, CConfig::USER_NAME, CConfig::PASSWORD);
			mysql_select_db(CConfig::DB_MCAT, $this->objDBLink);
		}
		
		public function __destruct()
		{
			mysql_close($this->objDBLink);
		}
		
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// Private Functions
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		private function GetSectionName($QuesID, $objTopicDetails, &$sGroupName)
		{
			$sSection = "";
			
			$query = sprintf("select * from question where ques_id='%s'", $QuesID);
			
			$result = mysql_query($query, $this->objDBLink) or die('Get Result error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				
				//CUtils::LogDataInFile("obj_topic_dtls.txt", $objTopicDetails, true, "a");
				foreach ($objTopicDetails as $objTopic)
				{
					if($objTopic['topic_id'] == $row['topic_id'])
					{
						$sSection = $objTopic['section'];
						$sGroupName = empty($objTopic['group']) ? " - " : $objTopic['group'];
						break;
					}
				}
			}
			
			return $sSection;
		}
		
		private function GetResultQIds($test_id, $ques_map, $chart, $aryRef)
		{
			$aryQID = array();
			
			switch($chart)
			{
				case "test_overview":
					
					break;
				case "section_overview":
					
					break;
				case "subject_overview":
					
					break;
				case "topic_overview":
					
					break;
				case "topic_perf":
					
					break;
			}
			
			return $aryQID;
		}
		
		private function PrepareEQResult($ques_map)
		{
			$objResult = array();
			
			$CandAnsAry = json_decode($ques_map, true);
			
			$objResult['attempted'] = 0;
			$objResult['unattempted'] = 0;
			foreach($CandAnsAry as $key=>$answer_ary)
			{
				if(count($answer_ary) == 1 && !in_array(-1, $answer_ary) && !in_array(-2, $answer_ary) && !in_array(-3, $answer_ary))
				{
					$objResult['attempted']++;
				}
				else 
				{
					$objResult['unattempted']++;
				}
			}
			return $objResult;
		}
		
		private function GetEQRangeAnalysis($test_id)
		{
			$retAry = array();
			
			$query = sprintf("select * from eq_range_analysis where test_id = '%s'", $test_id);
			
			$result = mysql_query($query, $this->objDBLink) or die('Get Range Analysis error : ' . mysql_error());
			
			while($row = mysql_fetch_assoc($result))
			{
				array_push($retAry, $row);
			}
			
			return $retAry;
		}
		
		private function PrepareResult($test_id, $ques_map)
		{
			$objResult = array();
			
			$objTD  = new CTestDynamic($this->objDBLink);
			$objQus = new CQuestion($this->objDBLink);
			
			$objTopicDetails = $objTD->GetTopicDetails($test_id);
			$objSectionDetails = $objTD->GetSectionDetails($test_id);
			
			/*echo("<pre>");
			print_r($objSectionDetails);
			echo("</pre>");*/
			
			$aryParticulars  = array();
			
			$CandAnsAry 	= json_decode($ques_map, true);
			$question_list 	= array_keys($CandAnsAry);
			$aryCorrectAns  = $objQus->GetQuestionsByIDList(implode(",",$question_list), $aryParticulars);
			
			$arySecIndex = array();
			foreach ($aryCorrectAns as $QuesID => $Answer)
			{
				$QuesAry 	= $objQus->GetQuestionByID($QuesID);
				
				$QuesType	= $QuesAry['ques_type'];
				$nMCA		= $QuesAry['mca'];
				
				$Group		= "-";
				$Section 	= $this->GetSectionName($QuesID, $objTopicDetails, $Group);
				$Subject 	= $objTD->GetSubjectName($aryParticulars[$QuesID]['subject_id']);
				$Topic 		= $objTD->GetTopicName($aryParticulars[$QuesID]['topic_id']);
				$Difficulty = $aryParticulars[$QuesID]['difficulty_id'];
				
				if ( !isset($objResult[$Group][$Section]['@sec_dtls_ary']) ) {
					foreach ($objSectionDetails as $nIndex => $arySection)
					{
						$strSecNamePos = strpos($arySection['name'],"~") ? strpos($arySection['name'],"~")+1 : 0;
						$sSectionName = substr($arySection ['name'], $strSecNamePos, strlen($arySection ['name']));
						
						if(strcasecmp($sSectionName, $Section) == 0) {
							$objResult[$Group][$Section]['@sec_dtls_ary'] = $objSectionDetails[$nIndex];
							break;
						}
					}
				}
				
				if(!isset($arySecIndex[$Section]))
				{
					$arySecIndex[$Section] = 0;
				}
				
				$Question = $arySecIndex[$Section];
				
				if(count($CandAnsAry[$QuesID]) == 1 && in_array(-1, $CandAnsAry[$QuesID]))
				{
					$objResult[$Group][$Section][$Subject][$Topic][$Difficulty][$Question] = -1;
				}
				else if(count($CandAnsAry[$QuesID]) == 1 && in_array(-2, $CandAnsAry[$QuesID]))
				{
					$objResult[$Group][$Section][$Subject][$Topic][$Difficulty][$Question] = -2;
				}
				else if(count($CandAnsAry[$QuesID]) == 1 && in_array(-3, $CandAnsAry[$QuesID]))
				{
					$objResult[$Group][$Section][$Subject][$Topic][$Difficulty][$Question] = -3;
				}
				else
				{
					if($QuesType == CConfig::QT_MATRIX) {
						foreach($Answer as $opt => $correct) {
							if(in_array($correct == $CandAnsAry[$QuesID])) {
								$objResult[$Group][$Section][$Subject][$Topic][$Difficulty][$Question] = 2;
							}
							else {
								$objResult[$Group][$Section][$Subject][$Topic][$Difficulty][$Question] = 0;
							}
						}
					}
					else if(count(array_diff($Answer, $CandAnsAry[$QuesID])) == 0 && count(array_diff($CandAnsAry[$QuesID], $Answer)) == 0)
					{
						$objResult[$Group][$Section][$Subject][$Topic][$Difficulty][$Question] = 1;
					}
					else if($nMCA == 1) {
						$crtCount = 0;
						foreach($CandAnsAry[$QuesID] as $ansKey => $ansVal) {
							if(in_array($ansVal, $Answer)) {
								$crtCount++;
							}
							else {
								$crtCount = 0;
								$objResult[$Group][$Section][$Subject][$Topic][$Difficulty][$Question] = 0;
								break;
							}
						}
							
						if($crtCount > 0) {
							$objResult[$Group][$Section][$Subject][$Topic][$Difficulty][$Question] = "2-".$crtCount;
						}
					}
					else if(count(array_diff($Answer, $CandAnsAry[$QuesID])) > 0 || count(array_diff($CandAnsAry[$QuesID], $Answer)) > 0)
					{
						$objResult[$Group][$Section][$Subject][$Topic][$Difficulty][$Question] = 0;
					}
				}
				
				$arySecIndex[$Section]++;
			}
			
			/*echo("<pre>");
			print_r($objResult);
			echo("</pre>");*/
			
			return $objResult;
		}
		
		private function PrepareResultInspection($test_id, $ques_map)
		{
			$objResult = array();
			
			$objQus = new CQuestion($this->objDBLink);
			
			$aryQ = array_keys($ques_map);
			$aryA = array_values($ques_map);
			
			//print_r($aryA);
			//CUtils::LogDataInFile("question-map.txt", $ques_map, true);
			$qIndex = 0;
			foreach ($aryQ as $key => $qID)
			{
				if($qID != -1 && !empty($qID))
				{
					$objQAry = $objQus->GetQuestionByID($qID);
					$objQAry['selected'] = $aryA[$key];
					$objResult[$qIndex] = $objQAry;
					$qIndex++;
				}
			}
			//CUtils::LogDataInFile("question-map.txt", $objResult, true);
			return $objResult;
		}
		
		private function PrepareSectionwiseResult($test_id, $section_result)
		{
			$objResult = array();
			
			$objTD  = new CTestDynamic($this->objDBLink);
			
			$objSecDetails = $objTD->GetSectionDetails($test_id);
			
			$arySectionalCutoff = array();
			foreach($objSecDetails as $key)
			{
				if(!empty($key['questions']))
				{
					$arySectionalCutoff[$key['name']]['max_cutoff'] =  $key['max_cutoff'];
					$arySectionalCutoff[$key['name']]['min_cutoff'] =  $key['min_cutoff'];
				}
			}
			
			foreach($section_result as $sec_name => $sec_details)
			{
				$objResult[$sec_name]['marks'] 				= $sec_details['marks']."/".$sec_details['max_marks'];
				$objResult[$sec_name]['min_passing_marks']	= $sec_details['max_marks'] * ($arySectionalCutoff[$sec_name]['min_cutoff']/100);
				$objResult[$sec_name]['max_passing_marks']	= $sec_details['max_marks'] * ($arySectionalCutoff[$sec_name]['max_cutoff']/100);
				$objResult[$sec_name]['result']				= $sec_details['result'];
			}
			return $objResult;
		}
		
		private function PrepareQuestionAry()
		{
			$query = sprintf("select * from question where user_id='%s' AND test_id='%s'", $user_id, $test_id);
			
			$result = mysql_query($query, $this->objDBLink) or die('Get Result error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
			}
		}
		
		private function GetUserName($user_id)
		{
			$ResAry = null;
            $query = sprintf("select * from users where user_id='%s'", $user_id);
           
            $result = mysql_query($query, $this->objDBLink) or die('Get username error : ' . mysql_error());
           
            if(mysql_num_rows($result) > 0)
            {
                $row = mysql_fetch_array($result);;
                $ResAry = $row;
            }
           
            return $ResAry;
		}
		
		public function tzOffsetToName($offset, $isDst = null)
	    {
	        if ($isDst === null)
	        {
	            $isDst = date('I');
	        }
	
	        $offset *= 3600;
	        $zone    = timezone_name_from_abbr('', $offset, $isDst);
	
	        if ($zone === false)
	        {
	            foreach (timezone_abbreviations_list() as $abbr)
	            {
	                foreach ($abbr as $city)
	                {
	                	// (bool)$city['dst'] === (bool)$isDst &&
	                    if (strlen($city['timezone_id']) > 0    &&
	                        $city['offset'] == $offset)
	                    {
	                        $zone = $city['timezone_id'];
	                        break;
	                    }
	                }
	
	                if ($zone !== false)
	                {
	                    break;
	                }
	            }
	        }
	    
	        return $zone;
	    }
	    
	    private function GetCountryName($code)
        {
            $query = "select * from countries where code='".$code."'";
            //echo $query."<br/><br/>";
            $result = mysql_query($query, $this->objDBLink) or die('Select from countries error : ' . mysql_error());
               
            $row = null;
            if(mysql_num_rows($result) > 0)
            {
                $row = mysql_fetch_array($result);
            }
            return $row['name'];
        }
        
        private function GetSchedulerBatches($scheduler_id)
        {
        	$retVal = array();
        	
        	$query = sprintf("select * from batch where owner_id='%s'", $scheduler_id);
        	
        	$result = mysql_query($query, $this->objDBLink) or die('Get Scheduler Batches error : ' . mysql_error());
        	
        	$i = 0;
        	if(mysql_num_rows($result) > 0)
        	{
        		while($row = mysql_fetch_array($result))
        		{
        			$retVal[$i++] = $row['batch_id'];
        		}	
        	}
        	else 
        	{
        		$retVal[$i] = CConfig::CDB_ID;
        	}
        	
        	return $retVal;
        }
        
        private function GetCandidateBatches($candidate_id)
        {
        	$retVal = array();
        	 
        	$query = sprintf("select batch from users where user_id='%s'", $candidate_id);
        	 
        	$result = mysql_query($query, $this->objDBLink) or die('Get Candidate Batches error : ' . mysql_error());
        	 
        	if(mysql_num_rows($result) > 0)
        	{
        		$row = mysql_fetch_array($result);
        		
        		$retVal = json_decode($row['batch'], true);
        	}
        	 
        	return $retVal;
        }
        
        private function GetBatchName($batch_id)
        {
        	$retVal = "";
        	 
        	$query = sprintf("select batch_name from batch where batch_id='%s'", $batch_id);
        	 
        	$result = mysql_query($query, $this->objDBLink) or die('Get batch name error : ' . mysql_error());
        	 
        	if(mysql_num_rows($result) > 0)
        	{
        		$row = mysql_fetch_array($result);
        
        		$retVal = $row['batch_name'];
        	}
        	return $retVal;
        }
        
        private function GetCandidatesQuesMapsForIQ($scheduler_id, $test_id, $test_pnr)
        {
        	$retArray = array();
        	 
        	$query = sprintf("select DISTINCT result.test_pnr, result.ques_map from result join test_schedule on result.tschd_id = test_schedule.schd_id where test_schedule.scheduler_id='%s' and result.test_pnr != '%s' and result.test_id='%s'", $scheduler_id, $test_pnr, $test_id);
        	 
        	$result = mysql_query($query, $this->objDBLink) or die('Get Candidates Ques Maps For IQ error : ' . mysql_error());
        	 
        	while($row = mysql_fetch_array($result))
        	{
        		$retArray[$row['test_pnr']] = $row['ques_map'];
        	}
        	return $retArray;
        }
	    // - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// Public Functions 
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        public function GetScheduledTest($schd_id)
        {
        	$retVal = "";
        
        	$query = sprintf("select * from test_schedule where schd_id='%s'", $schd_id);
        
        	$result = mysql_query($query, $this->objDBLink) or die('Get Scheduled Test error : ' . mysql_error());
        
        	if(mysql_num_rows($result) > 0)
        	{
        		$retVal = mysql_fetch_array($result);
        	}
        
        	return $retVal;
        }
        
		public function GetScheduledTestInfo($owner_id,$bincludeResult= false)
		{
			$RetAry = array();
			
			//$query = sprintf("select * from test join test_schedule on test.test_id = test_schedule.test_id where test.owner_id='%s' and test_schedule.scheduler_id = '%s' and test_schedule.scheduled_on is not null and test.deleted is null",$owner_id,$owner_id);
			
			$query = "";
			if($bincludeResult)
			{
				$query = sprintf("select distinct( test.test_id) as tid,  test.*,test_schedule.*,ta.allocation_id from test join test_schedule on test.test_id = test_schedule.test_id inner join result on test.test_id = result.test_id and test_schedule.schd_id = result.tschd_id  left join test_allocation ta on ta.test_id = test.test_id where (test.owner_id='%s' or ta.assignee_id ='%s') and test_schedule.scheduler_id = '%s' and test_schedule.scheduled_on is not null and test.deleted is null",$owner_id,$owner_id,$owner_id);
			}
			else
			{
			$query = sprintf("select test.*,test_schedule.* ,ta.allocation_id from test join test_schedule on test.test_id = test_schedule.test_id left join test_allocation ta on ta.test_id = test.test_id where (test.owner_id='%s' or ta.assignee_id ='%s') and test_schedule.scheduler_id = '%s' and test_schedule.scheduled_on is not null and test.deleted is null",$owner_id,$owner_id,$owner_id);
			}
			//echo $query;
			
			$result = mysql_query($query, $this->objDBLink) or die('Get Scheduled Test Info error : ' . mysql_error());
			
			while($row = mysql_fetch_array($result))
			{
				
				$ea_test = $row['allocation_id']==null?"":" (EzeeAssess)";
				$RetAry[$row['test_id']]['test_name'] = $row['test_name'] .$ea_test;
				
				if(!isset($RetAry[$row['test_id']]['scheduled_on']))
				{
					$RetAry[$row['test_id']]['scheduled_on'] 	= array();
					$RetAry[$row['test_id']]['schd_id']			= array();
					$RetAry[$row['test_id']]['batch']			= array();
				}
				
				//date_default_timezone_set($this->tzOffsetToName($row['time_zone']));
				array_push($RetAry[$row['test_id']]['scheduled_on'], date("F d, Y", strtotime($row['scheduled_on'])));
				//date_default_timezone_set($reset);
				
				array_push($RetAry[$row['test_id']]['schd_id'], $row['schd_id']);
				
				if(!isset($RetAry[$row['test_id']]['batch'][$row['schd_id']]))
				{
					$RetAry[$row['test_id']]['batch'][$row['schd_id']] = $this->GetCandidateBatchByScheduleId($row['schd_id'], $owner_id, $row['user_list']);
				}
			}
			
			return $RetAry;
		}
		
		public function GetCandidateBatchByScheduleId($schd_id, $owner_id, $candidate_list)
		{
			$retArray		   = array();
			$scheduler_batches = $this->GetSchedulerBatches($owner_id);
			
			$candidateAry	   = explode(";", $candidate_list);
			
			foreach($candidateAry as $candidate)
			{
				if(!empty($candidate))
				{
					$candidate_batch = $this->GetCandidateBatches($candidate);
					
					$common_batch_ary = array_intersect($candidate_batch, $scheduler_batches);
					
					if(count($common_batch_ary) > 0)
					{
						if(in_array(CConfig::CDB_ID, $scheduler_batches) && !isset($retArray[CConfig::CDB_ID]))
						{
							$retArray[CConfig::CDB_ID] = CConfig::CDB_NAME;
						}
						else
						{
							foreach($common_batch_ary as $batch_id)
							{
								if(!isset($retArray[$batch_id]))
								{
									$retArray[$batch_id] = $this->GetBatchName($batch_id);
								}
							}
						}
					}
					else if(!isset($retArray[CConfig::CDB_ID]))
					{
						$retArray[CConfig::CDB_ID] = CConfig::CDB_NAME;
					}
				}	
			}
			
			return $retArray;
		}
		
		public function GetResult($test_pnr, $test_type = CConfig::TT_DEFAULT)
		{
			//printf ("Test PNR: %s<br/>", $test_pnr);
			$objRet = null;
			$query = sprintf("select * from result where test_pnr='%s'", $test_pnr);
			
			$result = mysql_query($query, $this->objDBLink) or die('Get Result error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				//echo ("Result Exists... <br/>");
				$row = mysql_fetch_array($result);
				
				//printf ("Test ID: %s<br/>", $row['test_id']);
				//printf ("Ques ID: %s<br/>", $row['ques_id']);
				//printf ("Answers: %s<br/>", $row['answers']);
				
				if($test_type == CConfig::TT_DEFAULT)
				{
					$objRet = $this->PrepareResult($row['test_id'], $row['ques_map']);
				}
				else if($test_type == CConfig::TT_EQ)
				{
					$objRet = $this->PrepareEQResult($row['ques_map']);
				}
			}
			
			return $objRet;
		}
		
		public function GetResultFromPNR($test_pnr)
		{
			$objRet = null;
			$query = sprintf("select * from result where test_pnr='%s'", $test_pnr);
			
			$result = mysql_query($query, $this->objDBLink) or die('Get Result From PNR error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				
				$objRet = $this->PrepareResult($row['test_id'], $row['ques_map']);
			}
			
			return $objRet;
		}
		
		public function GetMarksFromPNR($test_pnr)
		{
			$objRet = null;
			$query = sprintf("select marks from result where test_pnr='%s'", $test_pnr);
				
			$result = mysql_query($query, $this->objDBLink) or die('Get Marks From PNR error : ' . mysql_error());
				
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
		
				$objRet = $row['marks'];
			}
				
			return $objRet;
		}
		
		public function GetUnpreparedResultFromPNR($test_pnr)
		{
			$objRet = null;
			$query = sprintf("select * from result where test_pnr='%s'", $test_pnr);
				
			$result = mysql_query($query, $this->objDBLink) or die('Get Unprepared Result From PNR error : ' . mysql_error());
				
			if(mysql_num_rows($result) > 0)
			{
				$objRet = mysql_fetch_array($result);
			}
				
			return $objRet;
		}
		
		public function GetResultInspectionFromPNR($test_pnr)
		{
			$objRet = null;
			$query = sprintf("select * from result where test_pnr='%s'", $test_pnr);
			
			$result = mysql_query($query, $this->objDBLink) or die('Get Result Inspection From PNR error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				
				$objRet = $this->PrepareResultInspection($row['test_id'], json_decode($row['ques_map'], true));
			}
			
			return $objRet;
		}
		
		public function GetSectionwiseResultFromPNR($test_pnr)
		{
			$objRet = null;
			$query = sprintf("select * from result where test_pnr='%s'", $test_pnr);
			
			$result = mysql_query($query, $this->objDBLink) or die('Get Sectionwise Result From PNR error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				
				$objRet = $this->PrepareSectionwiseResult($row['test_id'], json_decode($row['section_marks'], true));
			}
			return $objRet;
		}
		
		public function PopulateResult($owner_id, $user_type, $time_zone=null)
		{
			$query = "";
			
			if($user_type != CConfig::UT_INDIVIDAL)
			{
				$query = sprintf("select DISTINCT * from result, test, test_dynamic where test.test_id = result.test_id and test.test_id = test_dynamic.test_id and test.owner_id='%s' and test.deleted is null and result.tschd_id != '%s'", $owner_id, CConfig::FEUC_TEST_SCHEDULE_ID);
			}
			else 
			{
				$query = sprintf("select DISTINCT test.*, result.*, test_dynamic.*, test_schedule.scheduler_id from result, test, test_dynamic, test_schedule where test.test_id = result.test_id and test.test_id=test_dynamic.test_id and test.test_id=test_schedule.test_id and result.user_id='%s' and test.deleted is null", $owner_id);
			}
			
			//echo $query."<br/>";
			
			$result = mysql_query($query, $this->objDBLink) or die('Populate Result error : ' . mysql_error());
			
			$test_name = "";
			while($row = mysql_fetch_array($result))
			{
				if($user_type!= CConfig::UT_INDIVIDAL || $row['visibility'] > 0)
				{
					$NameAry = $this->GetUserName( ($user_type != CConfig::UT_INDIVIDAL) ? $row['user_id'] : $row['scheduler_id']);
					
					$tsCD = strtotime($row['create_date']);
					$tsTD = strtotime($row['test_date']);
					
					printf("<tr id='%s'>", $row['test_pnr']);
					printf("<td>%s</td>", $row['test_name']);
					
					$reset = date_default_timezone_get();
					if($time_zone==null)
					{
						$time_zone = $row['time_zone'];
					}
					date_default_timezone_set($this->tzOffsetToName($time_zone));
					printf("<td>%s</td>", date("M d, Y", $tsCD));
					printf("<td>%s</td>", date("M d, Y [H:i:s]", $tsTD));
					date_default_timezone_set($reset);
					
					printf("<td>%s %s (%s)</td>", $NameAry['firstname'], $NameAry['lastname'], $NameAry['email']);
					printf("<td>%s / %s</td>", $row['marks'], ($row['marks_for_correct'] * $row['max_question']));
					if($row['criteria'] == CConfig::PC_CUTOFF)
					{
						$min_marks = ($row['marks_for_correct'] * $row['max_question']) * ($row['cutoff_min'] / 100);
						$max_marks = ($row['marks_for_correct'] * $row['max_question']) * ($row['cutoff_max'] / 100);
						
						printf("<td>%s</td>", ($row['marks'] >= $min_marks && $row['marks'] <= $max_marks)? "Pass":"Fail");
					}
					else if($row['criteria'] == CConfig::PC_TOP_CAND)
					{
						printf("<td>%s</td>", $row['marks']);
					}
					
					$sTime = floor($row['time_taken'] / 60).":".($row['time_taken'] % 60);
					printf("<td>%s</td>", $sTime);
					printf("</tr>");
				}
			}
		}
		
		public function IsResultExistForTest($test_id)
		{
			$bReturn = false;
			
			$query = sprintf("select count(*) as num_of_rows from result where test_id = %d", $test_id);
			
			$result = mysql_query($query, $this->objDBLink) or die('Is Result Exist For Test error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				
				$bReturn = $row['num_of_rows'];
			}
			
			return $bReturn;
		}
		
		public function GetCompletedTestNames($owner_id, $user_type, $bIncludePackageTest = false)
		{
			$ResultAry = array();
			$query = "";
			
			$includePackageCond = "";
			if($bIncludePackageTest)
			{
				$includePackageCond = sprintf("or (test_schedule.scheduler_id='%s' AND test_schedule.schd_id = result.tschd_id)", $owner_id);
			}
			
			if($user_type != CConfig::UT_INDIVIDAL)
			{
				//$query = sprintf("select test.test_id, test.owner_id, test_name, result.tschd_id from result, test, test_schedule where test.test_id = result.test_id and (test.owner_id='%s' %s) and test.deleted is null and result.tschd_id != '%s'", $owner_id, $includePackageCond, CConfig::FEUC_TEST_SCHEDULE_ID);
				$query = sprintf("select distinct(test.test_id), test.owner_id, test_name, ta.allocation_id, result.tschd_id from result inner join test on test.test_id = result.test_id inner JOIN test_schedule on (test.test_id = test_schedule.test_id or result.tschd_id < 0) and (test_schedule.schd_id = result.tschd_id or result.tschd_id < 0) left join test_allocation ta on result.test_id = ta.test_id where (test.owner_id='%s' or ta.assignee_id ='%s' %s) and test.deleted is null", $owner_id,$owner_id, $includePackageCond);
				
			}
			else 
			{
				$query = sprintf("select test.test_id, test_name, test.owner_id, result.tschd_id, result.visibility from result inner join test on test.test_id = result.test_id left join test_allocation ta on result.test_id = ta.test_id where result.user_id='%s' and test.deleted is null or ta.assignee_id= '%s'", $owner_id,$owner_id);
			}
			
			//echo $query."<br/>";
			$result = mysql_query($query, $this->objDBLink) or die('Get Completed Test Names error : ' . mysql_error());
			
			//CUtils::LogDataInFile("Test.txt", $query."\r\n", false, "a");
			while($row = mysql_fetch_array($result))
			{
				//CUtils::LogDataInFile("Test.txt", $row['test_id']."\r\n", false, "a");
				if($row['tschd_id'] > 0) {
					$schdld_test_ary = $this->GetScheduledTest($row['tschd_id']);
				}
				else if ($this->IsResultExistForTest($row['test_id']) > 0 && $user_type != CConfig::UT_INDIVIDAL) {
					$schdld_test_ary = array();
					$schdld_test_ary['scheduler_id'] = $owner_id;
				}
				
				
				/*$isTestFromAssignedTestPackage  = false;
				if(!empty($schdld_test_ary))
				{
					$isTestFromAssignedTestPackage = $this->IsTestFromAssignedTestPackage($row['test_id'], $schdld_test_ary['scheduler_id']);
				}*/
				//print_r($row);
				// && $schdld_test_ary['scheduler_id'] == $row['owner_id'] removed from first condition but need to cross verify with Shashank or Manish
				if(($user_type == CConfig::UT_INDIVIDAL && $row['visibility'] == 2) || ($user_type != CConfig::UT_INDIVIDAL && $schdld_test_ary['scheduler_id'] == $owner_id || empty($schdld_test_ary)) || ($user_type == CConfig::UT_INDIVIDAL && $row['visibility'] == 2 && $bIncludePackageTest))
				{
					
					$ea_test = $row['allocation_id']==null?"":" (EzeeAssess)";
					$ResultAry[$row['test_id']] = $row['test_name'] .$ea_test;
				}
			}
			
			return $ResultAry;
		}
		
		public function GetCompletedTestDates($owner_id, $user_type, $test_id, $time_zone = NULL)
		{
			$ResultAry = array();
			$query = "";
			
			$includePackageCond = sprintf("or (test_schedule.scheduler_id='%s' AND test_schedule.schd_id = result.tschd_id)", $owner_id);
			
			if($user_type != CConfig::UT_INDIVIDAL)
			{
				//$query = sprintf("select test_date, owner_id, tschd_id from result,test,test_schedule where (test.owner_id='%s' %s) and test.test_id='%s' and test.test_id=result.test_id and test.deleted is null and result.tschd_id != '%s'", $owner_id, $includePackageCond, $test_id, CConfig::FEUC_TEST_SCHEDULE_ID);
				$query = sprintf("select DISTINCT test_date, owner_id, tschd_id from result inner join 
				test on result.test_id= test.test_id left join test_schedule on test_schedule.schd_id = result.tschd_id
				where (test.owner_id='%s' or test_schedule.scheduler_id='%s') and test.test_id='%s'",$owner_id,$owner_id,$test_id);
			}
			else 
			{
				$query = sprintf("select test_date, owner_id, tschd_id from result inner join test on test.test_id=result.test_id where result.user_id='%s' and test.test_id='%s' and test.deleted is null", $owner_id, $test_id);
			}
			
			//echo $query."<br/>";
			$result = mysql_query($query, $this->objDBLink) or die('Get Completed Test Dates error : ' . mysql_error());
			
			$index = 0;
			$dtzone = new DateTimeZone($this->tzOffsetToName($time_zone));
			while($row = mysql_fetch_array($result))
			{
				/*$testDtime  = new DateTime($row['test_date']);
				$testDtime->setTimezone($dtzone);
				$tDate = $testDtime->format("M d, Y");
				$ResultAry[$tDate] = $testDtime->getTimestamp();*/
				
				if(!isset($ResultAry[$row['tschd_id']]))
				{
					$schdld_test_ary = $this->GetScheduledTest($row['tschd_id']);
					
					if(!empty($schdld_test_ary))
					{
						if(($user_type == CConfig::UT_INDIVIDAL && $row['visibility'] == 2 && $schdld_test_ary['scheduler_id'] == $row['owner_id']) || ($user_type != CConfig::UT_INDIVIDAL && $schdld_test_ary['scheduler_id'] == $owner_id) || ($user_type == CConfig::UT_INDIVIDAL))
						{
							$tDate = date("M d, Y", strtotime($schdld_test_ary['scheduled_on']));
							$ResultAry[$row['tschd_id']] = $tDate;
						}
					}
					
					if($row['tschd_id'] == -100)
					{
						$ResultAry[$row['tschd_id']] = "Demo Test";
					}
				}
				/*$ts = strtotime($row['test_date']);
				$tDate = date("M d, Y", $ts);
				$ResultAry[$tDate] = $ts;*/
				
				$index++;
			}
			
			ksort($ResultAry);
			
			return $ResultAry;
		}
		
		public function GetCompletedTestCandidates($owner_id, $user_type, $test_id, $tschd_id, $time_zone = null)
		{
			$ResultAry = array();
			$query = "";
			
			//$includePackageCond = sprintf("or (test_schedule.scheduler_id='%s')", $owner_id);
			
			if($user_type != CConfig::UT_INDIVIDAL)
			{
				//$query = sprintf("select tschd_id, test_pnr, user_id, test_date, owner_id from result,test,test_schedule where (test.owner_id='%s' %s) and test.test_id='%s' and test.test_id=result.test_id and result.tschd_id != '%s'", $owner_id, $includePackageCond, $test_id, CConfig::FEUC_TEST_SCHEDULE_ID);								
				$query = sprintf("select tschd_id, test_pnr, user_id, test_date, owner_id from result inner join test on test.test_id = result.test_id left JOIN
				test_schedule on test_schedule.schd_id = result.tschd_id  where
						 (test.owner_id='%s ' or test_schedule.scheduler_id='%s')  and test.test_id='%s'", $owner_id,  $owner_id,$test_id);
			}
			else 
			{
				$query = sprintf("select test_pnr, user_id, test_date, tschd_id, owner_id from result inner join test on test.test_id=result.test_id where result.user_id='%s' and test.test_id='%s' ", $owner_id, $test_id);
			}
			
			//echo $query."<br/>";
			//CUtils::LogDataInFile("select.txt", $query);
			$result = mysql_query($query, $this->objDBLink) or die('Get Completed Test Candidate error : ' . mysql_error());
			
			$index = 0;
			$scheduler_batch_ary = array();
			$cand_batch_ary		 = array();
			$batch_name_ary		 = array();
			
			$dtzone = new DateTimeZone($this->tzOffsetToName($time_zone));
			while($row = mysql_fetch_array($result))
			{
				$batch = "";
				
				//$testTS = strtotime($row['test_date']);
				
				$testDtime  = new DateTime($row['test_date']);
				$testDtime->setTimezone($dtzone);
				$dateToBeCompared = $testDtime->format("M d, Y");
				
				$fromTestDtime = new DateTime();
				$fromTestDtime->setTimestamp($test_date);
				$dateFromCompared = $fromTestDtime->format("M d, Y");
				
				//$reset = date_default_timezone_get();
				//date_default_timezone_set($this->tzOffsetToName($time_zone));
				$schdld_test_ary = $this->GetScheduledTest($row['tschd_id']);
				
				if(($user_type == CConfig::UT_INDIVIDAL && $row['visibility'] == 2 && $schdld_test_ary['scheduler_id'] == $row['owner_id']) || ($user_type != CConfig::UT_INDIVIDAL && $schdld_test_ary['scheduler_id'] == $owner_id) || ($user_type == CConfig::UT_INDIVIDAL) || $row['tschd_id'] == -100)
				{
					if($tschd_id == $row['tschd_id'])
					{
						$test_pnr = $row['test_pnr'];
						if($user_type != CConfig::UT_INDIVIDAL)
						{
							if($row['tschd_id'] != -100)
							{
								if(!array_key_exists($owner_id, $scheduler_batch_ary))
								{
									$scheduler_batch_ary[$owner_id] = $this->GetSchedulerBatches($owner_id);
								}
									
								if(!array_key_exists($row['user_id'], $cand_batch_ary))
								{
									$cand_batch_ary[$row['user_id']] = $this->GetCandidateBatches($row['user_id']);
								}
									
								$common_batch_ary = array_intersect($cand_batch_ary[$row['user_id']], $scheduler_batch_ary[$owner_id]);
									
								if(count($common_batch_ary) > 0)
								{
									if(in_array(CConfig::CDB_ID, $scheduler_batch_ary[$owner_id]))
									{
										$batch = CConfig::CDB_NAME;
									}
									else
									{
										foreach($common_batch_ary as $batch_id)
										{
											if(!array_key_exists($batch_id, $batch_name_ary))
											{
												$batch_name_ary[$batch_id] = $this->GetBatchName($batch_id);
											}
						
											$batch = $batch_name_ary[$batch_id];
										}
									}
								}
								else
								{
									$batch = CConfig::CDB_NAME;
								}
							}
							$ResultAry[$test_pnr]['batch'] = $batch;
						}
						
						$NameAry = $this->GetUserName($row['user_id']);
						
						if($row['tschd_id'] < 0) {
							$ResultAry[$test_pnr]['result'] = sprintf("%s %s (Time: %s)",$NameAry['firstname'], $NameAry['lastname'],$testDtime->format("[H:i:s]"));
						}
						else {
							$ResultAry[$test_pnr]['result'] = sprintf("%s %s",$NameAry['firstname'], $NameAry['lastname']);
						}
					}
				}
				//date_default_timezone_set($reset);
				$index++;
			}
			
			asort($ResultAry);
			
			return $ResultAry;
		}
		
		public function GetCompletedTestInfo($owner_id, $time_zone)
		{
			$RetAry = array();
			
			//$query = sprintf("select result.test_id, result.test_date, result.tschd_id from result,test where test.owner_id='%s' and test.test_id=result.test_id and test.deleted is null and result.tschd_id != '%s'", $owner_id, CConfig::FEUC_TEST_SCHEDULE_ID);
			
			$query = sprintf("select result.test_id, result.test_date, result.tschd_id from result inner join test on result.test_id = test.test_id left join test_allocation ta on test.test_id = ta.test_id where  (test.owner_id='%s' or ta.assignee_id = '%s') and test.deleted is null and result.tschd_id != '%s'", $owner_id, $owner_id, CConfig::FEUC_TEST_SCHEDULE_ID);
			
			//echo $query;
			$result = mysql_query($query, $this->objDBLink) or die('Get Completed Test Info error : ' . mysql_error());
			
			$dtzone = new DateTimeZone($this->tzOffsetToName($time_zone));
			
			while($row = mysql_fetch_array($result))
			{
				$schdld_test_ary = $this->GetScheduledTest($row['tschd_id']);
				
				if($schdld_test_ary['scheduler_id'] == $owner_id || $row['tschd_id'] == -100)
				{
					if(!isset($RetAry[$row['test_id']]['tschd_id']))
					{
						$RetAry[$row['test_id']]['test_date'] 		= array();
						$RetAry[$row['test_id']]['tschd_id']		= array();
						$RetAry[$row['test_id']]['scheduled_on']	= array();
					}
					
					if(!in_array($row['tschd_id'], $RetAry[$row['test_id']]['tschd_id']))
					{
						$testDtime  = new DateTime($row['test_date']);
						$testDtime->setTimezone($dtzone);
							
						array_push($RetAry[$row['test_id']]['test_date'],$testDtime->format("F d, Y"));
						array_push($RetAry[$row['test_id']]['tschd_id'], $row['tschd_id']);
							
						if(!empty($schdld_test_ary))
						{
							array_push($RetAry[$row['test_id']]['scheduled_on'], date("M d, Y", strtotime($schdld_test_ary['scheduled_on'])));
						}
						else
						{
							array_push($RetAry[$row['test_id']]['scheduled_on'], "");
						}
					}	
				}
			}
			return $RetAry;
		}
		
		public function PopulateBriefResultList($owner_id, $user_type, $time_zone=null)
        {
            $query = "";
           
            if($user_type != CConfig::UT_INDIVIDAL)
            {
                //$query = sprintf("select DISTINCT result.*, test.*, test_dynamic.marks_for_correct, test_dynamic.max_question, test_dynamic.cutoff_min, test_dynamic.cutoff_max from result, test, test_dynamic where test.test_id = result.test_id and test.test_id = test_dynamic.test_id and test.owner_id='%s' and test.deleted is null and result.tschd_id != '%s'", $owner_id, CConfig::FEUC_TEST_SCHEDULE_ID);
                
            	$query = sprintf("select DISTINCT result.*, test.*, test_dynamic.marks_for_correct, test_dynamic.max_question, test_dynamic.cutoff_min, test_dynamic.cutoff_max, test_dynamic.partial_marks from result inner join test on test.test_id = result.test_id inner join test_dynamic on test.test_id = test_dynamic.test_id left join 
				test_allocation ta on test.test_id = ta.test_id where (test.owner_id='%s' or ta.assignee_id = '%s') and test.deleted is null and result.tschd_id != '%s'", $owner_id,$owner_id, CConfig::FEUC_TEST_SCHEDULE_ID);                
            }
            else
            {
                $query = sprintf("select DISTINCT test.*, result.*, test_dynamic.marks_for_correct, test_dynamic.max_question, test_dynamic.cutoff_min, test_dynamic.cutoff_max, test_schedule.scheduler_id from result, test, test_dynamic, test_schedule where test.test_id = result.test_id and test.test_id=test_dynamic.test_id and test.test_id=test_schedule.test_id and result.user_id='%s' and result.visibility != %d and test.deleted is null", $owner_id, CConfig::RV_NONE);               
            
            }
           
            //echo $query."<br/>";
           
            $result = mysql_query($query, $this->objDBLink) or die('Populate Result error : ' . mysql_error());
           
            $test_name = "";
            $RetAry = array();
            $scheduler_batch_ary = array();
            $cand_batch_ary		 = array();
            $batch_name_ary		 = array();
            while($row = mysql_fetch_array($result))
            {
            	$schdld_test_ary = $this->GetScheduledTest($row['tschd_id']);
            	
            	$isTestFromAssignedTestPackage = false;
            	if(!empty($schdld_test_ary))
				{
					$isTestFromAssignedTestPackage = $this->IsTestFromAssignedTestPackage($schdld_test_ary['test_id'], $schdld_test_ary['scheduler_id']);
				}
               
				if(!$isTestFromAssignedTestPackage)
				{
	                $NameAry = $this->GetUserName( ($user_type != CConfig::UT_INDIVIDAL) ? $row['user_id'] : $row['scheduler_id']);
	                
	                $scheduler_id = ($user_type == CConfig::UT_INDIVIDAL) ? $row['scheduler_id'] : $owner_id;
	                
	    			$batch = "";
	                
	                if($row['tschd_id'] != -100)
	                {
	                	if(!array_key_exists($scheduler_id, $scheduler_batch_ary))
	                	{
	                		$scheduler_batch_ary[$scheduler_id] = $this->GetSchedulerBatches($scheduler_id);
	                	}
	                	
	                	if(!array_key_exists($row['user_id'], $cand_batch_ary))
	                	{
	                		$cand_batch_ary[$row['user_id']] = $this->GetCandidateBatches($row['user_id']);
	                	}
	                	
	                	$common_batch_ary = array_intersect($cand_batch_ary[$row['user_id']], $scheduler_batch_ary[$scheduler_id]);
	
	                	if(count($common_batch_ary) > 0)
	                	{
	                		if(in_array(CConfig::CDB_ID, $scheduler_batch_ary[$scheduler_id]))
	                		{
	                			$batch = CConfig::CDB_NAME;
	                		}
	                		else 
	                		{
	                			foreach($common_batch_ary as $batch_id)
	                			{
	                				if(!array_key_exists($batch_id, $batch_name_ary))
	                				{
	                					$batch_name_ary[$batch_id] = $this->GetBatchName($batch_id);
	                				}
	                				
	                				$batch = $batch_name_ary[$batch_id];
	                			}
	                		}	
	                	}
	                	else 
	                	{
	                		$batch = CConfig::CDB_NAME;
	                	}
	                }
	                
	                $RetAry[$row['test_pnr']]['batch'] = $batch;
	                
	                $tsCD = "";
	                if(!empty($schdld_test_ary))
	                {
	                	$tsCD = strtotime($schdld_test_ary['scheduled_on']);
	                }
	                else 
	                {
	                	$tsCD = "Not Applicable";
	                }
	
	                $tsTD = strtotime($row['test_date']);
	                
	                $RetAry[$row['test_pnr']]['schd_id'] = $row['tschd_id'];
	                $RetAry[$row['test_pnr']]['test_id'] = $row['test_id'];
	                $RetAry[$row['test_pnr']]['tr_open'] = sprintf("<tr id='%s'>", $row['test_pnr']);
	                $RetAry[$row['test_pnr']]['test_name'] = sprintf("<td>%s</td>", $row['test_name']);
	                   
	                $reset = date_default_timezone_get();
	                if($time_zone==null)
	                {
	                    $time_zone = $schdld_test_ary['time_zone'];
	                }
	                
	                $dtzone = new DateTimeZone($this->tzOffsetToName($time_zone));
	                $testDtime  = new DateTime($row['test_date']);
	                $testDtime->setTimezone($dtzone);
	                
	                //date_default_timezone_set($this->tzOffsetToName($time_zone));
	                $RetAry[$row['test_pnr']]['completed_on'] = sprintf("<td>%s</td>",$testDtime->format("M d, Y [H:i:s]"));
	                $RetAry[$row['test_pnr']]['scheduled_on'] = sprintf("<td>%s</td>",($tsCD != "Not Applicable")?date("M d, Y", $tsCD):$tsCD);                
	                //date_default_timezone_set($reset);
	                $RetAry[$row['test_pnr']]['name'] = sprintf("<td>%s %s (%s)</td>", $NameAry['firstname'], $NameAry['lastname'], $NameAry['email']);
	                $RetAry[$row['test_pnr']]['location'] = sprintf("<td>%s, %s, %s</td>", $NameAry['city'], $NameAry['state'], $this->GetCountryName($NameAry['country']));            
	                if($row['criteria'] == CConfig::PC_CUTOFF)
	                {
	                	/*$objTD  = new CTestDynamic($this->objDBLink);
	                	$objSecDetails = $objTD->GetSectionDetails($row['test_id']);*/
	                    
	                    $objSecPerformance = json_decode($row['section_marks'], true); 
	                    
	                    $max_total_marks = 0;
	                    
	                    $result_state = CConfig::RS_PASS;
	                    foreach($objSecPerformance as $key)
	                    {
	                    	$max_total_marks += $key['max_marks'];
	                    	
	                    	if($result_state != CConfig::RS_FAIL)
	                    	{
	                    		$result_state = $key['result'];
	                    	}
	                    }
	                    
	                    if($result_state == CConfig::RS_FAIL)
	                    {
	                    	$RetAry[$row['test_pnr']]['result'] = "<td>Fail</td>";
	                    }
	                    else 
	                    {
	                    	$min_marks = ($max_total_marks) * ($row['cutoff_min'] / 100);
	                    	$max_marks = ($max_total_marks) * ($row['cutoff_max'] / 100);
	                    	$RetAry[$row['test_pnr']]['result'] = sprintf("<td>%s</td>", ($row['marks'] >= $min_marks && $row['marks'] <= $max_marks)? "Pass":"Fail");
	                    }
	                    $RetAry[$row['test_pnr']]['marks'] = sprintf("<td style='text-align: center;'>%s out of %s<br /><br /><button id='%s;details' class='btn btn-sm btn-success' onclick='ShowSectionWisePerformance(this);' title='Section-Wise Details'><i class='icon-list'></i></button></td>", $row['marks'], $max_total_marks, $row['test_pnr']);
	                }
	                else if($row['criteria'] == CConfig::PC_TOP_CAND)
	                {
	                    $RetAry[$row['test_pnr']]['result'] = sprintf("<td>%s</td>", $row['marks']);
	                }
	                   
	                $sTime = floor($row['time_taken'] / 60).":".($row['time_taken'] % 60);
	                $RetAry[$row['test_pnr']]['time_taken'] = sprintf("<td>%s</td>", $sTime);
	                   
	                if($user_type != CConfig::UT_INDIVIDAL)
	                {
	                    $none_checked = "";
	                    $min_checked = "";
	                    $det_checked = "";
	                       
	                    if($row['visibility'] == CConfig::RV_NONE)
	                    {
	                        $none_checked = "checked";
	                    }
	                    else if($row['visibility'] == CConfig::RV_MINIMAL)
	                    {
	                        $min_checked = "checked";
	                    }
	                    else
	                    {
	                        $det_checked = "checked";
	                    }
	                    $RetAry[$row['test_pnr']]['visibility'] = sprintf("<td><label class='radio inline'><input type='radio' value='%s' name='%s;visibility' onchange='OnVisibilityChange(this);' %s> None </label><br /><label class='radio inline'><input type='radio' value='%s' name='%s;visibility' onchange='OnVisibilityChange(this);' %s> Minimal </label><br /><label class='radio inline'><input type='radio' value='%s' name='%s;visibility' onchange='OnVisibilityChange(this);' %s> Detailed    </label></td>", CConfig::RV_NONE, $row['test_pnr'], $none_checked, CConfig::RV_MINIMAL, $row['test_pnr'], $min_checked, CConfig::RV_DETAILED, $row['test_pnr'], $det_checked);
	                }
	                $RetAry[$row['test_pnr']]['activity_log_details'] = $row['attempt_history'];
	                $RetAry[$row['test_pnr']]['btn_activity_log'] = sprintf("<td><input type='button' class='btn btn-sm btn-primary' id='%s;log' value='Activity Log' onclick='ShowActivityLog(this);' /></td>", $row['test_pnr']);
	                $RetAry[$row['test_pnr']]['tr_close'] = sprintf("</tr>");
				}
            }
            return $RetAry;
        }

        public function UpdateResultVisibility($test_pnr, $visibility)
        {
            $query = sprintf("update result set visibility = '%s' where test_pnr = '%s'", $visibility, $test_pnr);
           
            $result = mysql_query($query, $this->objDBLink) or die('Update Result Visibility error : ' . mysql_error());
           
            return $result;
        }
        
        public function GetUserInfoByTestPNR($test_pnr)
        {
        	$retAry = array();
        	
        	$query = sprintf("select users.* from users join result on result.user_id = users.user_id where result.test_pnr='%s'", $test_pnr);
        	
        	$result = mysql_query($query, $this->objDBLink) or die('Get User Info By Test PNR error : ' . mysql_error());
        	
        	if(mysql_num_rows($result) > 0)
        	{
        		$retAry = mysql_fetch_array($result);
        	}
        	
        	return $retAry;
        }
        
        public function IsResultAlreadyExist($user_id, $test_id, $tschd_id)
        {
        	$bRet = false;
        	
        	$query = sprintf("select * from result where user_id='%s' and test_id='%s' and tschd_id='%s'", $user_id, $test_id, $tschd_id);
        	
        	$result = mysql_query($query, $this->objDBLink) or die('Is Result Already Exist error : ' . mysql_error());
        	
        	if(mysql_num_rows($result) > 0)
        	{
        		$bRet = true;
        	}
        	return $bRet;
        }
        
        public function UpdateOfflineTestSchedule($schd_id, $scheduleDate, $scheduleTimeZone)
        {
        	$query = sprintf("update test_schedule set scheduled_on='%s', time_zone='%s' where schd_id='%s' and scheduled_on is null and time_zone is null", $scheduleDate, $scheduleTimeZone, $schd_id);
        	
        	$result = mysql_query($query, $this->objDBLink) or die('Update Offline Test Schedule error : ' . mysql_error());
        }
        
        public function InsertOfflineResult($resultArray, $schedule_id, $scheduleDate, $scheduleTimeZone)
        {
        	$this->UpdateOfflineTestSchedule($schedule_id, $scheduleDate, $scheduleTimeZone);
        	
        	
        	$query = sprintf("insert into result(test_pnr, tschd_id, user_id, test_id, ques_map, marks, section_marks, time_taken, visibility, test_date, attempt_history, paid) values %s", implode(",",$resultArray));
        	
        	$result = mysql_query($query, $this->objDBLink) or die('Insert Offline Result error : ' . mysql_error());
        }
        
        public function IsTestFromAssignedTestPackage($test_id, $scheduler_id)
        {
        	$bRet = false;
        	 
        	$query = sprintf("select * from assigned_packages where test_id='%s' and user_id='%s'", $test_id, $scheduler_id);
        	 
        	$result = mysql_query($query, $this->objDBLink) or die('Is Test From Assigned Test Package error : ' . mysql_error());
        	 
        	if(mysql_num_rows($result) > 0)
        	{
        		$bRet = true;
        	}
        	return $bRet;
        }
        
        public function GetPackageTestResultView($test_id, $user_id)
        {
        	$retVal = 0;
        	
        	$query = sprintf("select result_view from package join assigned_packages on package.package_id = assigned_packages.package_id where assigned_packages.test_id='%s' and assigned_packages.user_id='%s'", $test_id, $user_id);
        
        	$result = mysql_query($query, $this->objDBLink) or die('Get Package Test Result View error : ' . mysql_error());
        	
        	if(mysql_num_rows($result) > 0)
        	{
        		$row = mysql_fetch_array($result);
        		
        		$retVal = $row['result_view'];
        	}
        	return $retVal;
        }
        
        public function GetHolisticMarks($ques_map, $test_id, $time_taken = "")
        {
        	$objQuestion = new CQuestion($this->objDBLink);
        
        	$ques_map = json_decode($ques_map, true);
        	$qusAry = array_keys($ques_map);
        	$ansAry = array_values($ques_map);
        
        	$aryParticulars  = array();
        	$aryCorrectAns = $objQuestion->GetQuestionsByIDList(implode(",", $qusAry), $aryParticulars);
        
        	$objTestDynamic = new CTestDynamic($this->objDBLink);
        	$objSecDetails = $objTestDynamic->GetSectionDetails($test_id);
        	
        	$arySecQuestions = array();
        	foreach($objSecDetails as $key)
        	{
        		if(!empty($key['questions']))
        		{
        			array_push($arySecQuestions, $key['questions']);
        		}
        	}

        	$total_ques_correct = 0;
        	$total_ques_attempted = 0;
        	
        	$secIndex = 0;
        	$qCount = 0;
        	$total_ques_count = 0;
        	
        	$questionMarksDataAry = array();
        	foreach ($qusAry as $key => $ques_id)
        	{
        		if(isset($aryCorrectAns[$ques_id]))
        		{	
        			if(count(array_diff($aryCorrectAns[$ques_id], $ansAry[$key])) == 0 && count(array_diff($ansAry[$key], $aryCorrectAns[$ques_id])) == 0)
        			{
        				$questionMarksDataAry[$ques_id]['correct'] 	  = 1;
        				$questionMarksDataAry[$ques_id]['wrong']   	  = 0;
        				$questionMarksDataAry[$ques_id]['unanswered'] = 0;
        				$total_ques_correct++;
        				$total_ques_attempted++;
        			}
        			else if(count($ansAry[$key]) == 1 && (in_array(-1, $ansAry[$key]) || in_array(-2, $ansAry[$key])))
        			{
        				$questionMarksDataAry[$ques_id]['correct'] 	  = 0;
        				$questionMarksDataAry[$ques_id]['wrong']   	  = 0;
        				$questionMarksDataAry[$ques_id]['unanswered'] = 1;
        			}
        			else
        			{
        				$questionMarksDataAry[$ques_id]['correct'] 	  = 0;
        				$questionMarksDataAry[$ques_id]['wrong']   	  = 1;
        				$questionMarksDataAry[$ques_id]['unanswered'] = 0;
        				$total_ques_attempted++;
        			}
        
        			$questionMarksDataAry[$ques_id]['sec_index'] = $secIndex;
        			$qCount++;
        			$total_ques_count++;
        			if($arySecQuestions[$secIndex] == $qCount)
        			{	
        				$secIndex++;
        				$qCount = 0;
        			}
        		}
        	}
        
        	$secIndex = 0;
        	$marksArray = array();
        	$subjectNameAry = array();
        	$topicNameAry	= array();
        	$marksArray['max_marks'] = 0;
        	$marksArray['total_marks'] = 0;
        	
        	if($total_ques_correct == 0 && $total_ques_attempted == 0)
        	{
        		$marksArray['accuracy'] = 0;
        	}
        	else 
        	{
        		$marksArray['accuracy'] = round(($total_ques_correct/$total_ques_attempted) * 100);
        	}
        	
        	if(!empty($time_taken))
        	{
        		$testDuration = $objTestDynamic->GetDuration($test_id);
        		$time_avail_for_single_ques = ($testDuration * 60)/$total_ques_count;
        		if($testDuration != 0)
        		{
        			$marksArray['speed'] = round((($time_avail_for_single_ques * $total_ques_attempted)/$time_taken) * 100);
        		}
        		else
        		{
        			$marksArray['speed'] = 0;
        		}
        	}
        	foreach($objSecDetails as $key => $objSection)
        	{
        		foreach($questionMarksDataAry as $ques_id=>$data)
        		{
        			if($data['sec_index'] == $secIndex)
        			{   
        				$Subject = "";
        				if(isset($subjectNameAry[$aryParticulars[$ques_id]['subject_id']]))
        				{
        					$Subject = $subjectNameAry[$aryParticulars[$ques_id]['subject_id']];
        				}
        				else 
        				{
        					$subjectNameAry[$aryParticulars[$ques_id]['subject_id']] = ucfirst($objTestDynamic->GetSubjectName($aryParticulars[$ques_id]['subject_id']));
        					$Subject = $subjectNameAry[$aryParticulars[$ques_id]['subject_id']];
        				} 

        				$Topic = "";
        				if(isset($topicNameAry[$aryParticulars[$ques_id]['topic_id']]))
        				{
        					$Topic = $topicNameAry[$aryParticulars[$ques_id]['topic_id']];
        				}
        				else 
        				{
        					$topicNameAry[$aryParticulars[$ques_id]['topic_id']] = ucfirst($objTestDynamic->GetTopicName($aryParticulars[$ques_id]['topic_id']));
        					$Topic = $topicNameAry[$aryParticulars[$ques_id]['topic_id']];
        				}
        				//$braces = array("{", "}");
        				//$Topic = str_replace($braces, "-", $Topic);
        				
        				if(!isset($marksArray[$Subject]['max_marks']))
        				{
        					$marksArray[$Subject]['max_marks'] = 0;
        					$marksArray[$Subject]['total_marks'] = 0;
        				}
        				
        				$marksArray['max_marks'] += $objSection['mark_for_correct'];
        				$marksArray[$Subject]['max_marks'] += $objSection['mark_for_correct'];
        				if(isset($marksArray[$Subject][$Topic]))
        				{
        					$marksArray[$Subject][$Topic]['max_marks'] += $objSection['mark_for_correct'];
        					
        					if($data['correct'] == 1)
        					{
        						$marksArray[$Subject][$Topic]['total_marks'] += $objSection['mark_for_correct'];
        						$marksArray[$Subject]['total_marks'] += $objSection['mark_for_correct'];
        						$marksArray['total_marks'] += $objSection['mark_for_correct'];
        					}
        					else if($data['wrong'] == 1)
        					{
        						$marksArray[$Subject][$Topic]['total_marks'] -= $objSection['mark_for_incorrect'];
        						$marksArray[$Subject]['total_marks'] -= $objSection['mark_for_incorrect'];
        						$marksArray['total_marks'] -= $objSection['mark_for_incorrect'];
        					}
        				}
        				else 
        				{
        					$marksArray[$Subject][$Topic]['max_marks'] = $objSection['mark_for_correct'];
        					
        					if($data['correct'] == 1)
        					{
        						$marksArray[$Subject][$Topic]['total_marks'] = $objSection['mark_for_correct'];
        						$marksArray[$Subject]['total_marks'] += $objSection['mark_for_correct'];
        						$marksArray['total_marks'] += $objSection['mark_for_correct'];
        					}
        					else if($data['wrong'] == 1)
        					{
        						$marksArray[$Subject][$Topic]['total_marks'] = -$objSection['mark_for_incorrect'];
        						$marksArray[$Subject]['total_marks'] -= $objSection['mark_for_incorrect'];
        						$marksArray['total_marks'] -= $objSection['mark_for_incorrect'];
        					}
        					else if($data['unanswered'] == 1)
        					{
        						$marksArray[$Subject][$Topic]['total_marks'] = 0;
        					}
        				}
        			}
        		}
        		$secIndex++;
        	}
        	return $marksArray;
        }
        
        public function GetEQResult($test_pnr)
        {
        	$EQResultAry = array();
        	
        	$unpreparedResult = $this->GetUnpreparedResultFromPNR($test_pnr);
        	
        	$section_scores = json_decode($unpreparedResult['section_marks'], true);
        	
        	$EQRangeAnalysisAry = $this->GetEQRangeAnalysis($unpreparedResult['test_id']);
        	
        	foreach($section_scores as $section_name=>$section_vals)
        	{
        		foreach($EQRangeAnalysisAry as $rangeAnalysis)
        		{
        			if($section_vals['topic_id'] == $rangeAnalysis['topic_id'] && $section_vals['score'] >= $rangeAnalysis['lower_range_limit'] && $section_vals['score'] <= $rangeAnalysis['higher_range_limit'])
        			{
        				$EQResultAry[$section_name] = $rangeAnalysis['analysis'];
        				break;
        			}
        		}	
        	}
        	return $EQResultAry;
        }
        
        public function GetIQResult($test_pnr, $scheduler_id, $nUserType)
        {
        	$IQResultAry = array();
        	
        	$unpreparedResult = $this->GetUnpreparedResultFromPNR($test_pnr);
        	
        	if($nUserType == CConfig::UT_INDIVIDAL)
        	{
        		$test_schedule_ary = $this->GetScheduledTest($unpreparedResult['tschd_id']);
        		$scheduler_id = $test_schedule_ary['scheduler_id'];
        	}
        	
        	$raminingCandsQuesMapsAry = $this->GetCandidatesQuesMapsForIQ($scheduler_id, $unpreparedResult['test_id'], $test_pnr);
        	
        	$currentCandHolisticMarksAry = $this->GetHolisticMarks($unpreparedResult['ques_map'], $unpreparedResult['test_id']);
        	
        	$candsHolisticMarksAry = array();
        	$candsHolisticMarksAry[$test_pnr] = $currentCandHolisticMarksAry;
        	
        	$sumArray = array();
        	foreach($raminingCandsQuesMapsAry as $test_pnr_id=>$ques_map)
        	{
        		$candsHolisticMarksAry[$test_pnr_id] = $this->GetHolisticMarks($ques_map, $unpreparedResult['test_id']);
        		
        		if(isset($sumArray['total_marks']))
        		{
        			$sumArray['total_marks'] += $candsHolisticMarksAry[$test_pnr_id]['total_marks'];
        		}
        		else 
        		{
        			$sumArray['total_marks'] = $candsHolisticMarksAry[$test_pnr]['total_marks'] + $candsHolisticMarksAry[$test_pnr_id]['total_marks'];
        		}
        	}
        	
        	$candCount = count($candsHolisticMarksAry);
        	$medianAry = array();
        	$absDeviationSquareSumAry = array();
        	$currentCandAbsoluteDeviationAry = array();
        	foreach($candsHolisticMarksAry as $test_pnr_id => $holistics_marks)
        	{
        		unset($holistics_marks['max_marks']);
        		if(!isset($medianAry['total_marks']))
        		{
        			$medianAry['total_marks'] = $sumArray['total_marks']/$candCount;
        		}
        		
        		if(isset($absDeviationSquareSumAry['total_marks']))
        		{
        			$absDeviationSquareSumAry['total_marks'] += pow(($holistics_marks['total_marks'] - $medianAry['total_marks']), 2);
        		}
        		else 
        		{
        			$absDeviationSquareSumAry['total_marks'] = pow(($holistics_marks['total_marks'] - $medianAry['total_marks']), 2);
        		}
        		
        		if($test_pnr_id == $test_pnr)
        		{
        			$currentCandAbsoluteDeviation['total_marks'] = $holistics_marks['total_marks'] - $medianAry['total_marks'];
        		}
        	}
        	
        	$candIQAry = $currentCandHolisticMarksAry;
        	foreach($absDeviationSquareSumAry as $absDeviation => $absDeviationValues)
        	{
        		if($absDeviation == "total_marks")
        		{
        			$candIQAry['iq'] = round(100 + ($currentCandAbsoluteDeviation['total_marks']/(sqrt($absDeviationValues/($candCount - 1)))) * 15);
        			break;
        		}
        	}
        	
        	return $candIQAry;
        }
        
        /*
        	$query: 0: Correct, 1: Wrong, 2: Unanswered
        */
        public function GetQuetionsForSlider($test_pnr, $query)
        {
        	$objHTML = "";
        	$AnsStatus = array(1, 0, -1);
        	$objSome = $this->GetResultInspectionFromPNR($test_pnr);
        	/*echo "<pre>";
        	print_r($objSome);
        	echo "</pre>";*/
        	return $objHTML;
        }

        public function GetSectionalQuetionsForSlider($test_pnr, $section_name, $query)
        {
        	$objHTML = "";
        	$AnsStatus = array(1, 0, -1);
        	
        	return $objHTML;
        }

        public function GetSubjectQuetionsForSlider($test_pnr, $subject, $query)
        {
        	$objHTML = "";
        	$AnsStatus = array(1, 0, -1);
        	
        	return $objHTML;
        }
			
        public function GetTopicQuetionsForSlider($test_pnr, $subject, $topic, $query)
        {
        	$objHTML = "";
        	$AnsStatus = array(1, 0, -1);
        	
        	return $objHTML;
        }
			
        public function GetTopicPrefQuetionsForSlider($test_pnr, $difficulty, $subject, $topic, $query)
        {
        	$objHTML = "";
        	$AnsStatus = array(1, 0, -1);
        	
        	return $objHTML;
        }
        
        
       
        
        
        
        
        
	}
?>