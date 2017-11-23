<?php
	include_once(dirname(__FILE__)."/../../lib/utils.php");
	include_once("tbl_test_dynamic.php");
	include_once("tbl_test_schedule.php");
	include_once("tbl_question.php");
	
	include_once(dirname(__FILE__)."/../../database/config.php");
	include_once(dirname(__FILE__)."/../../lib/billing.php");
	
	class CTestSession
	{
		private $objDBLink;
		private $objTestDynamic;
		private $dbCreated = false;
		private $bResultExist = FALSE;
		
		public function __construct($objDBLink = null)
		{
			if($objDBLink != null)
			{
				$this->objDBLink = $objDBLink;
			}
			else 
			{
				$dbCreated = true;
				$this->objDBLink = mysql_connect(CConfig::HOST, CConfig::USER_NAME, CConfig::PASSWORD);
				mysql_select_db(CConfig::DB_MCAT, $this->objDBLink);
			}
			
			$this->objTestDynamic = new CTestDynamic($this->objDBLink);
		}
		
		public function __destruct()
		{
			if($dbCreated == true)
			{
				mysql_close($this->objDBLink);
			}
		}
		// - - - - - - - - - - - - - - - - - - - - - - - - -
		// Private Functions
		// - - - - - - - - - - - - - - - - - - - - - - - - -
		
		private function GetFirstQuestionId($user_id, $test_id, $tschd_id)
		{
			$retVal = null;
			
			$query = sprintf("select ques_map from test_session where user_id='%s' AND test_id='%s' AND tschd_id='%s'", $user_id, $test_id, $tschd_id);
				
			$result = mysql_query($query, $this->objDBLink) or die('Get First Question Id error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				
				$ques_map = json_decode($row['ques_map'], true);
				
				$retVal = current(array_keys($ques_map));
			}
			return $retVal;
		}
		
		private function GetAttemptsFromTest($test_id)
		{
			$nRet = 0;
			$query = sprintf("select attempts from test where test_id='%s'", $test_id);
			
			$result = mysql_query($query, $this->objDBLink) or die('Get Attempts From Test error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				
				$nRet = $row['attempts'];
			}
			
			return $nRet;
		}
		
		private function CreateTestSession($user_id, $test_id, $tschd_id, $objIterator, $cur_chronological_time)
		{
			$ques_map = array();
			$sSessionID = CUtils::uuid();
			
			$attempts_remaining = $this->GetAttemptsFromTest($test_id);
			
			foreach ($objIterator as $Section)
			{
				ksort($Section);
				foreach ($Section as $row)
				{
					$ques_map[$row['ques_id']] = array("-1");
				}
			}
			
			$query = sprintf("insert into test_session (tsession_id, tschd_id, test_id, user_id, ques_map, cur_chronological_time, attempts_remaining) values ('%s','%s','%s','%s','%s','%s','%s')", $sSessionID, $tschd_id, $test_id, $user_id, json_encode($ques_map), ($cur_chronological_time=='')?0:$cur_chronological_time, $attempts_remaining);
			
			$result = mysql_query($query, $this->objDBLink) or die('Create Test Session error : ' . mysql_error());
			
			return $sSessionID;
		}
		
		private function LoadTestFromSession($user_id, $test_id, $tschd_id)
		{
			$objIter = array();
			
			$objQuestion = new CQuestion($this->objDBLink);
			
			$objSecDetails = $this->objTestDynamic->GetSectionDetails($test_id);
			
			$query = sprintf("select * from test_session where user_id='%s' AND test_id='%s' AND tschd_id='%s'", $user_id, $test_id, $tschd_id);
			
			$result = mysql_query($query, $this->objDBLink) or die('Load Test From Session error : ' . mysql_error());
			
			$row = mysql_fetch_array($result);
			
			$qidIndex = 0;
			$qidAry = array_keys(json_decode($row['ques_map'], true));
			foreach($objSecDetails as $key => $objSection)
			{
				for($qIndex = 0; $qIndex < $objSection['questions']; $qIndex++)
				{
					$objIter[$key][$qIndex] = $objQuestion->GetQuestionByID( $qidAry[$qidIndex] );
					
					$qidIndex++;
				}
			}
			
			return $objIter;
		}
		
		private function GetUpdatedAttemptedAns($user_id, $test_id, $nTSchdID, $ques_id, $answer)
		{
			$ques_map = null;
			
			$query = sprintf("select * from test_session where user_id='%s' AND test_id='%s' AND tschd_id='%s'", $user_id, $test_id, $nTSchdID);
			
			$result = mysql_query($query, $this->objDBLink) or die('Get Updated Attempted Ans error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				
				$ques_map = json_decode($row['ques_map'], true);
				
				/*echo ("GetUpdatedAttemptedAns<pre>");
				print_r($ques_map);
				echo ("</pre>");*/
				$ques_map[$ques_id] = $answer;
				/*echo ("GetUpdatedAttemptedAns<pre>");
				print_r($ques_map);
				echo ("</pre>");*/
			}
			
			return $ques_map;
		}
		
		public function PrepareAnswerArray($test_id, $ques_map)
		{
			$objIter = array();
			
			$objSecDetails = $this->objTestDynamic->GetSectionDetails($test_id);
			
			$ansIndex = 0;
			$ques_map = json_decode($ques_map, true);
			$ansAry = array_keys($ques_map);
			
			/*echo("<pre>");
			print_r($ques_map);
			echo("</pre>");*/
			
			foreach($objSecDetails as $key => $objSection)
			{
				for($qIndex = 0; $qIndex < $objSection['questions']; $qIndex++)
				{
					$objIter[$key][$qIndex] = $ques_map[$ansAry[$ansIndex]];
					$ansIndex++;
				}
			}
			
			/*echo("<pre>");
			print_r($objIter);
			echo("</pre>");*/
			
			return $objIter;
		}
		
		private function CalculateEQScore($ques_map, $objTestParam)
		{
			$arySecPerformance = $this->CalculateSectionEQScores($ques_map, $objTestParam);
				
			$totalScore = 0;
			foreach($arySecPerformance as $key => $objSecResult)
			{
				$totalScore += $objSecResult['score'];
			}
		
			return $totalScore;
		}
		
		private function CalculateSectionEQScores($ques_map, $objTestParam)
		{
			$objQuestion = new CQuestion($this->objDBLink);
		
			$ques_map = json_decode($ques_map, true);
			$qusAry = array_keys($ques_map);
			$ansAry = array_values($ques_map);
		
			$aryParticulars  = array();
			$aryWeights = $objQuestion->GetEQQuestionsByIDList(implode(",", $qusAry), $aryParticulars);
		
			$objSecDetails = $this->objTestDynamic->GetSectionDetails($objTestParam['test_id']);
		
			$arySecQuestions = array();
			foreach($objSecDetails as $key)
			{
				if(!empty($key['questions']))
				{
					array_push($arySecQuestions, $key['questions']);
				}
			}
				
			$secIndex 			 = 0;
			$arySecAttemptedQues = array();
		
			foreach ($qusAry as $key => $ques_id)
			{
				if(isset($aryWeights[$ques_id]))
				{	
					if(count($ansAry[$key]) == 1 && !in_array(-1, $ansAry[$key]) && !in_array(-2, $ansAry[$key]) && !in_array(-3, $ansAry[$key]))
					{
						if(!isset($arySecAttemptedQues[$secIndex]['score']))
						{
							$arySecAttemptedQues[$secIndex]['score'] = $aryWeights[$ques_id][$ansAry[$key][0] - 1];
							$arySecAttemptedQues[$secIndex]['topic_id'] = $aryParticulars[$ques_id]['topic_id'];
						}
						else 
						{
							$arySecAttemptedQues[$secIndex]['score'] += $aryWeights[$ques_id][$ansAry[$key][0] - 1];
						}
					}
					else 
					{
						if(!isset($arySecAttemptedQues[$secIndex]['score']))
						{
							$arySecAttemptedQues[$secIndex]['score'] = 0;
							$arySecAttemptedQues[$secIndex]['topic_id'] = $aryParticulars[$ques_id]['topic_id'];
						}
					}
		
					$qCount++;
		
					if($arySecQuestions[$secIndex] == $qCount)
					{
						$secIndex++;
						$qCount = 0;
					}
				}
			}
		
			$secIndex = 0;
			$arySecPerformance = array();
			foreach($objSecDetails as $key => $objSection)
			{
				if(!empty($objSection['questions']))
				{
					$arySecPerformance[$objSection['name']]['score'] 	= $arySecAttemptedQues[$secIndex]['score'];
					$arySecPerformance[$objSection['name']]['topic_id'] = $arySecAttemptedQues[$secIndex]['topic_id'];
				}
				$secIndex++;
			}
			return $arySecPerformance;
		}
		
		private function CalculateMarks($ques_map, $objTestParam)
		{	
			$arySecPerformance = $this->CalculateSectionMarks($ques_map, $objTestParam);
		
			$marksObtained = 0;
			foreach($arySecPerformance as $key => $objSecResult)
			{
				$marksObtained += $objSecResult['marks'];
			}
			
			return $marksObtained;
		}
		
		private function CalculateSectionMarks($ques_map, $objTestParam)
		{
			$objQuestion = new CQuestion($this->objDBLink);
				
			$ques_map = json_decode($ques_map, true);
			$qusAry = array_keys($ques_map);
			$ansAry = array_values($ques_map);
				
			$aryParticulars  = array();
			$aryCorrectAns = $objQuestion->GetQuestionsByIDList(implode(",", $qusAry), $aryParticulars);
				
			$objSecDetails = $this->objTestDynamic->GetSectionDetails($objTestParam['test_id']);
				
			$arySecQuestions = array();
			foreach($objSecDetails as $key)
			{
				if(!empty($key['questions']))
				{
					array_push($arySecQuestions, $key['questions']);
				}
			}
			
			$secIndex 			 = 0;
			$arySecAttemptedQues = array();
				
			$nRight = 0;
			$nWrong = 0;
			$nUnans = 0;
			$qCount = 0;
			foreach ($qusAry as $key => $ques_id)
			{
				if(isset($aryCorrectAns[$ques_id]))
				{
					/*file_put_contents("diff_data.txt", print_r($aryCorrectAns[$ques_id], true)."\r\n", FILE_APPEND);
					file_put_contents("diff_data.txt", print_r($ansAry[$key], true)."\r\n", FILE_APPEND);
					file_put_contents("diff_data.txt", "\r\n----------\r\n", FILE_APPEND);*/
					
					if(count(array_diff($aryCorrectAns[$ques_id], $ansAry[$key])) == 0 && count(array_diff($ansAry[$key], $aryCorrectAns[$ques_id])) == 0)
					{
						$nRight++;
					}
					else if(count($ansAry[$key]) == 1 && count(array_intersect(array(-1, -2, -3), $ansAry[$key])) != 0)//count($ansAry[$key]) == 1 && (in_array(-1, $ansAry[$key]) || in_array(-2, $ansAry[$key])))
					{	
						$nUnans++;
					}
					else
					{
						$nWrong++;
					}
						
					$qCount++;
						
					if($arySecQuestions[$secIndex] == $qCount)
					{
						$arySecAttemptedQues[$secIndex]['right'] = $nRight;
						$arySecAttemptedQues[$secIndex]['unans'] = $nUnans;
						$arySecAttemptedQues[$secIndex]['wrong'] = $nWrong;
			
						$secIndex++;
			
						$nRight = 0;
						$nWrong = 0;
						$nUnans = 0;
						$qCount = 0;
					}
				}
			}
				
			$secIndex = 0;
			$arySecPerformance = array();
			foreach($objSecDetails as $key => $objSection)
			{
				if(!empty($objSection['questions']))
				{
					$arySecPerformance[$objSection['name']]['marks'] 	 = ($arySecAttemptedQues[$secIndex]['right'] * $objSection['mark_for_correct']) - ($arySecAttemptedQues[$secIndex]['wrong'] * $objSection['mark_for_incorrect']);
					$arySecPerformance[$objSection['name']]['max_marks'] = ($objSection['questions'] * $objSection['mark_for_correct']);
					
					$max_marks = $arySecPerformance[$objSection['name']]['max_marks'] * ($objSection['max_cutoff']/100);
					$min_marks = $arySecPerformance[$objSection['name']]['max_marks'] * ($objSection['min_cutoff']/100);
					
					$arySecPerformance[$objSection['name']]['result']	 = (($arySecPerformance[$objSection['name']]['marks'] >= $min_marks) && ($arySecPerformance[$objSection['name']]['marks'] <= $max_marks))?(CConfig::RS_PASS):(CConfig::RS_FAIL);
				}
				$secIndex++;
			}
			return $arySecPerformance;
		}
		
		private function CalculateTimeTaken($cur_chronological_time, $objTestParam)
		{
			return ($objTestParam['test_duration'] * 60) - $cur_chronological_time;
		}
		
		private function IsResultPresent($user_id, $test_id, $tschd_id, $test_type)
		{
			$retVat = 0;
			$query = sprintf("select * from result where user_id='%s' AND test_id='%s' AND tschd_id='%s' AND tschd_id > 0", $user_id, $test_id, $tschd_id);
				
			$result = mysql_query($query, $this->objDBLink) or die('Is Result Present error : ' . mysql_error());
				
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				$retVal = $row['test_pnr'];
			}
			
			return $retVal;
		}
		
		private function PrepareAndSaveResult($user_id, $test_id, $tschd_id, $test_type)
		{
			$test_pnr = $this->IsResultPresent($user_id, $test_id, $tschd_id, $test_type);
			if (!empty($test_pnr))
			{
				$this->bResultExist = TRUE;
				return $test_pnr;
			}
			$this->bResultExist = FALSE;
			
			$query = sprintf("select * from test_session where user_id='%s' AND test_id='%s' AND tschd_id='%s'", $user_id, $test_id, $tschd_id);
			
			$result = mysql_query($query, $this->objDBLink) or die('Prepare And Save Result error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				
				$objTestParam = $this->objTestDynamic->GetTestParams($test_id);
				$ques_map = $row['ques_map'];
				
				$marks = 0;
				$secPerformance = array();
				if($test_type == CConfig::TT_DEFAULT)
				{
					$marks = $this->CalculateMarks($ques_map, $objTestParam);
					$secPerformance = $this->CalculateSectionMarks($ques_map, $objTestParam);
				}
				else if($test_type == CConfig::TT_EQ)
				{
					$marks = $this->CalculateEQScore($ques_map, $objTestParam);
					$secPerformance = $this->CalculateSectionEQScores($ques_map, $objTestParam);
				}
				
				$time_taken = $this->CalculateTimeTaken($row['cur_chronological_time'], $objTestParam);
				
				$query = sprintf("insert into result (test_pnr, tschd_id, user_id, test_id, ques_map, marks, section_marks, time_taken, visibility, test_date, attempt_history) values ('%s','%s','%s','%s','%s','%s','%s', '%s', '%s', NOW() , '%s')", $row['tsession_id'], $tschd_id, $user_id, $test_id, $ques_map, $marks, json_encode($secPerformance), $time_taken, $objTestParam['visibility'], mysql_real_escape_string($row['attempt_history']));
			
				//echo $query."<br/>";
				$result = mysql_query($query, $this->objDBLink) or die('insert into result error : ' . mysql_error());
			}
			
			return $row['tsession_id'];
		}
		
		private function PurgeSession($user_id, $test_id, $tschd_id)
		{
			$query = sprintf("delete from test_session where user_id='%s' AND test_id='%s' AND tschd_id='%s'", $user_id, $test_id, $tschd_id);
			
			$result = mysql_query($query, $this->objDBLink) or die('End Session error : ' . mysql_error());
			
			return $result;
		}
		
		private function ReplaceKey($a, $key1, $key2)
		{
			if (!array_key_exists($key1,$a) && !array_key_exists($key2,$a))
			{
				return;
			}
			$search = array_flip(array_keys($a));
			
			$key1_index= $search[$key2];
			$key1_value = $a[$key1];
			
			$key2_index= $search[$key1];
			$key2_value = $a[$key2];
			
			$i=0;
			$new = array();
			foreach($a as $key => $value)
			{
				if($i == $key2_index)
				{
					$new[$key2] = $key2_value;
				}
				else 
				{
					$new[$key] = $value;
				}
				$i++;
			}
			return $new;
		}
		
		private function tzOffsetToName($offset, $isDst = null)
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
		// - - - - - - - - - - - - - - - - - - - - - - - - -
		// Public Functions
		// - - - - - - - - - - - - - - - - - - - - - - - - -
		public function TestRestoreLog($reason, $tsession_id, $time_zone)
		{
			$reset = date_default_timezone_get();
			
			date_default_timezone_set($this->tzOffsetToName($time_zone));
			$reason_append = $reason."#".date("Y-m-d H:i:s").";";
			date_default_timezone_set($reset);
			
			$query = sprintf("update test_session set attempt_history=concat(attempt_history, '%s') where tsession_id='%s'", mysql_real_escape_string($reason_append), $tsession_id);
			
			$result = mysql_query($query, $this->objDBLink) or die('Test Restore Log error : ' . mysql_error());
			
			
			return $result;
		}
		
		public function GetAttemptsFromTestSession($tsession_id, &$bShowPreRestoreForm)
		{
			$nRet = 0;
			$query = sprintf("select test_session.attempts_remaining, test_session.attempt_history, test.attempts from test, test_session where tsession_id='%s' and test_session.test_id=test.test_id", $tsession_id);
			//echo($query."<br/>");
			$result = mysql_query($query, $this->objDBLink) or die('Get Attempts From Test Session error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				
				$nRet = $row['attempts_remaining'];
				
				$nAtmpHistoryCnt = empty($row['attempt_history']) ? 0 : count(explode(";", $row['attempt_history']));
				
				if($row['attempts'] != -1 && $nAtmpHistoryCnt != 0)
				{
					$bShowPreRestoreForm = ($nAtmpHistoryCnt == ($row['attempts'] - $row['attempts_remaining'] )) ? true : false;
				}
				else
				{
					$bShowPreRestoreForm = true;
				}
				
				//printf("Attempts: %s, Attempts Remaining: %s, Atmpt History Count: %s, Show Form: %s<br/>",$row['attempts'], $row['attempts_remaining'], $nAtmpHistoryCnt, $bShowPreRestoreForm?1:0);
			}
			
			return $nRet;
		}
		
		public function SessionExist($user_id, $test_id, $tschd_id) 
		{
			$tsession_id = null;
			
			$query = sprintf("select * from test_session where user_id='%s' AND test_id='%s' AND tschd_id='%s'", $user_id, $test_id, $tschd_id);
			//echo ("SessionExist: ".$query."<br/>");
			$result = mysql_query($query, $this->objDBLink) or die('Session Exist error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				$tsession_id = $row['tsession_id'];
			}
			
			return $tsession_id;
		}
		
		public function StartTestSession($user_id, $test_id, $tschd_id, $objIterator, $cur_chronological_time)
		{
			return $this->CreateTestSession($user_id, $test_id, $tschd_id, $objIterator, $cur_chronological_time);
		}
		
		public function ResumeTestSession($user_id, $test_id, $tschd_id)
		{
			return $this->LoadTestFromSession($user_id, $test_id, $tschd_id);
		}
		
		public function SetTimeElapsed($user_id, $test_id, $tschd_id, $cur_chronological_time)
		{
			$query = sprintf("update test_session set cur_chronological_time='%s' where user_id='%s' AND test_id='%s' AND tschd_id='%s'", $cur_chronological_time, $user_id, $test_id, $tschd_id);
			
			$result = mysql_query($query, $this->objDBLink) or die('Set Time Elapsed error : ' . mysql_error());
			
			$query = sprintf("select * from test_session where user_id='%s' AND test_id='%s' AND tschd_id='%s'", $user_id, $test_id, $tschd_id);
			
			$result_select = mysql_query($query, $this->objDBLink) or die('Get Kill Test error : ' . mysql_error());
			
			$forced_kill = 0;
			if(mysql_num_rows($result_select) > 0)
			{
				$row = mysql_fetch_array($result_select);
				$forced_kill = $row['forced_kill'];
			}
			
			return $forced_kill;
		}
		
		public function GetElapsedTime($user_id, $test_id, $tschd_id)
		{
			$bTime = 0;
			
			$query = sprintf("select cur_chronological_time from test_session where user_id='%s' AND test_id='%s' AND tschd_id='%s'", $user_id, $test_id, $tschd_id);
			$result = mysql_query($query, $this->objDBLink) or die('Get Time Elapsed error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				$bTime = $row['cur_chronological_time'];
			}
			
			return $bTime;
		}
		
		public function UpdateAnswer($user_id, $test_id, $nTSchdID, $ques_id, $answer)
		{
			$ques_map = $this->GetUpdatedAttemptedAns($user_id, $test_id, $nTSchdID, $ques_id, $answer);
			
			$query = sprintf("update test_session set ques_map='%s' where user_id='%s' AND test_id='%s' AND tschd_id='%s'", json_encode($ques_map), $user_id, $test_id, $nTSchdID);
			//echo($query."<br/>");
			$bResult = mysql_query($query, $this->objDBLink) or die('Update Answer error : ' . mysql_error());
			
			return $bResult;
		}
		
		public function UpdateQuesID($user_id, $test_id, $tschd_id, $old_ques_id, $new_ques_id)
		{
			//printf("<br/>Update Ques ID (OLD: %s, NEW; %s)<br/>", $old_ques_id, $new_ques_id);
			
			$query_1 = sprintf("select ques_map from test_session where user_id='%s' AND test_id='%s' AND tschd_id='%s'", $user_id, $test_id, $tschd_id);
			
			$result_1 = mysql_query($query_1, $this->objDBLink) or die('Update Ques ID error  - 1: ' . mysql_error());
			
			if(mysql_num_rows($result_1) > 0)
			{
				$row 	  = mysql_fetch_array($result_1);
				$ques_map = json_decode($row['ques_map'], true);
				$ques_map = $this->ReplaceKey($ques_map, $old_ques_id, $new_ques_id);
				$ques_map[$new_ques_id] = array("-1");
				
				$query = sprintf("update test_session set ques_map='%s' where user_id='%s' AND test_id='%s' AND tschd_id='%s'", json_encode($ques_map), $user_id, $test_id, $tschd_id);
			
				$result = mysql_query($query, $this->objDBLink) or die('Update Ques ID error - 2: ' . mysql_error());
			}
			
			return $result;
		}
		
		public function GetAttemptedAnswers($user_id, $test_id, $tschd_id)
		{
			$ansAry = null;
			$query = sprintf("select * from test_session where user_id='%s' AND test_id='%s' AND tschd_id='%s'", $user_id, $test_id, $tschd_id);
			//echo("GetAttemptedAnswers ".$query."<br/>");
			$result = mysql_query($query, $this->objDBLink) or die('Get Attempted Answer error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				//echo($row['tsession_id']."<br/>");
				$ansAry = $this->PrepareAnswerArray($test_id, $row['ques_map']);
			}
			
			return $ansAry;
		}
		
		public function GetFirstUnattemptedQuesInfo($tsession_id)
		{
			$QuesInfoAry = array('sec'=>0, 'ques'=>0);
			
			$query = sprintf("select * from test_session where tsession_id='%s'", $tsession_id);
			//echo("GetFirstUnattemptedQuesInfo: ".$query."<br/>");
			$result = mysql_query($query, $this->objDBLink) or die('Get First Unattempted Ques Info error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				
				$objSecDetails = $this->objTestDynamic->GetSectionDetails($row['test_id']);
				
				$ansIndex = 0;
				$ques_map = json_decode($row['ques_map'], true);
				$ansAry = array_keys($ques_map);
				
				/*echo "Prev<pre>";
				print_r($ansAry);
				echo "</pre><br/>";*/
				
				foreach($objSecDetails as $key => $objSection)
				{
					for($qIndex = 0; $qIndex < $objSection['questions']; $qIndex++)
					{
						//printf("Question: %s, Answer: %s, Section: %s\n<br/>", $qIndex, $ansAry[$ansIndex], $key);
						$answer_ary = $ques_map[$ansAry[$ansIndex]]['answer'];
						if(count($answer_ary) == 1 && (in_array(-1, $$answer_ary) || in_array(-3, $$answer_ary)))
						{
							$QuesInfoAry['sec'] = $key;
							$QuesInfoAry['ques'] = $qIndex;
							
							return $QuesInfoAry;
						}
						$ansIndex++;
					}
				}
			}
			
			return $QuesInfoAry;
		}
		
		public function EndSession($user_id, $test_id, $tschd_id, $test_type = CConfig::TT_DEFAULT)
		{
			$SessionID = $this->PrepareAndSaveResult($user_id, $test_id, $tschd_id, $test_type);
			
			$this->PurgeSession($user_id, $test_id, $tschd_id);
			
			if($this->bResultExist == FALSE)
			{
				$amount = null;
				$objBilling = new CBilling();
				$scheduler_id = $objBilling->GetTestSchedulerID($tschd_id);
				if($objBilling->GetQuesSource($test_id) == "mipcat")
				{
					$amount = $objBilling->GetMIpCATQuesRate($scheduler_id);
				}
				else 
				{
					$amount = $objBilling->GetPersonalQuesRate($scheduler_id);
				}
				
				$isTestFromAssignedPackage = $objBilling->IsTestAssignedFromPackage($test_id, $scheduler_id);
				$assignedPackageTestRate = 0;
				if($isTestFromAssignedPackage)
				{
					$assignedPackageTestRate = $objBilling->GetAssignedPackageTestRate($test_id, $scheduler_id);
				}
				$objBilling->SubBalance($scheduler_id, ($amount+$assignedPackageTestRate));
			}
			
			return $SessionID;
		}
		
		public function TerminateTestSession($tsession_id)
        {
            $query = sprintf("update test_session set forced_kill = '%s' where tsession_id = '%s'", CConfig::FOKI_YES, $tsession_id);
               
            $result = mysql_query($query, $this->objDBLink) or die('Terminate Test Session error: ' . mysql_error());
               
            return $result;
        }
        
        public function updateResult($test_schid)
        {
        	 
        	$query =  sprintf("select * from result where tschd_id='%s'", $test_schid);
        	
        	$result = mysql_query($query, $this->objDBLink) or die('Select result pnr error : ' . mysql_error());
        	
        	// Loop throught  the test pnr for above scheudle id
        	
        	$counter = 0;
        	while($row = mysql_fetch_array($result))
        	{
        	
        		 
        		$objTestParam = $this->objTestDynamic->GetTestParams($row['test_id']);
        		$ques_map = $row['ques_map'];
        		 
        		$marks = 0;
        		$secPerformance = array();
        		if($test_type == CConfig::TT_DEFAULT)
        		{
        			$marks = $this->CalculateMarks($ques_map, $objTestParam);
        			$secPerformance = $this->CalculateSectionMarks($ques_map, $objTestParam);
        		}
        		else if($test_type == CConfig::TT_EQ)
        		{
        			$marks = $this->CalculateEQScore($ques_map, $objTestParam);
        			$secPerformance = $this->CalculateSectionEQScores($ques_map, $objTestParam);
        		}
        		
        		
        		//$time_taken = $this->CalculateTimeTaken($row['cur_chronological_time'], $objTestParam);
        		 
        		$query = sprintf("update result set marks = '%s', section_marks = '%s' where test_pnr = '%s'", $marks, json_encode($secPerformance),$row['test_pnr']);
        	
        		$updateres = mysql_query($query, $this->objDBLink) or die('Update result error : ' . mysql_error());
        		$counter++;        	        	
        	}
        	
        	return $counter;
        	 
        }
        
        
	}
?>