<?php
	include_once("tbl_question.php");
	include_once("tbl_test_dynamic.php");
	include_once("tbl_test_schedule.php");
	include_once("tbl_test_session.php");
	
	class CTest
	{
		// Object Aggregation
		private $objQuestion;
		private $objTestDynamic;
		private $objTestStatic;
		private $objTestSchedule;
		private $objTestSession;
		
		// State of class
		private $objDBLink;
		private $objIterator;
		private $objTestParam = null;
		private $objMCPAParam = null;
		
		// Test Param Array Details
		private $aryDifficulty;
		private $aryTopicID;
		private $arySubjectID;
		private $aryQuestionID;
		
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// Private Function
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		private function FillNewTest($user_id, $test_id, $tschd_id, $language)
		{
			$this->objIterator = $this->objQuestion->GetQuestions($user_id, $test_id, $language, $this->objMCPAParam);
			
			/*echo("<pre>");
			print_r($this->objIterator);
			echo("</pre>");*/
			
			// Arrays referenced by functions below, will be automatically populated based on Iterator.
			$this->FillQuesIDArray();
			$this->FillSubjIDArray();
			$this->FillTopicIDArray();
			$this->FillDifficultyArray();
			
			$this->objTestSession->StartTestSession($user_id, $test_id, $tschd_id, $this->objIterator);
		}
		
		private function FillTestFromSession($user_id, $test_id, $tschd_id)
		{
			$this->objIterator = $this->objTestSession->ResumeTestSession($user_id, $test_id, $tschd_id);
			
			/*
			echo("From Session:<br/><pre>");
			print_r($this->objIterator);
			echo("</pre>");
			*/
			
			// Arrays referenced by functions below, will be automatically populated based on Iterator.
			$this->FillQuesIDArray();
			$this->FillSubjIDArray();
			$this->FillTopicIDArray();
			$this->FillDifficultyArray();
		}
		
		private function FillQuesIDArray()
		{
			foreach($this->objIterator as $secIdx => $Section)
			{
				foreach($Section as $qusIdx => $Question)
				{
					$this->aryQuestionID[$secIdx][$qusIdx] = $Question['ques_id'];
				}
			}
		}
		
		private function FillSubjIDArray()
		{
			foreach($this->objIterator as $secIdx => $Section)
			{
				foreach($Section as $qusIdx => $Question)
				{
					$this->arySubjectID[$secIdx][$qusIdx] = $Question['subject_id'];
				}
			}
		}
		
		private function FillTopicIDArray()
		{
			foreach($this->objIterator as $secIdx => $Section)
			{
				foreach($Section as $qusIdx => $Question)
				{
					$this->aryTopicID[$secIdx][$qusIdx] = $Question['topic_id'];
				}
			}
		}
		
		private function FillDifficultyArray()
		{
			foreach($this->objIterator as $secIdx => $Section)
			{
				foreach($Section as $qusIdx => $Question)
				{
					$this->aryDifficulty[$secIdx][$qusIdx] = $Question['difficulty_id'];
				}
			}
		}
		
		private function array_value_recursive($arr)
		{
		    $flat_array = array();
			
		    $index = 0;
		    
		    $iter = new RecursiveIteratorIterator(new RecursiveArrayIterator($arr));
			foreach($iter as $val) 
			{
				$flat_array[$index] = "'".$val."'";
				$index++;
			}
						
			/*
			echo "Flat Array <br/><pre>";
			print_r($flat_array);
			echo "</pre>";
			*/
			
			return $flat_array;
		}
		
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// Public Function
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		public function __construct($objDBLink)
		{
			$this->objDBLink = $objDBLink;
			
			$this->objQuestion 		= new CQuestion($objDBLink);
			$this->objTestDynamic 	= new CTestDynamic($objDBLink);
			$this->objTestStatic 	= new CTestStatic($objDBLink);
			$this->objTestSchedule 	= new CTestSchedule($objDBLink);
			$this->objTestSession 	= new CTestSession($objDBLink);
		}
		
		public function __destruct()
		{
			unset($this->objQuestion);
			unset($this->objTestDynamic);
			unset($this->objTestSchedule);
			unset($this->objTestSession);
		
			unset($this->objDBLink);
			unset($this->objIterator);
			unset($this->objTestParam);
			unset($this->objMCPAParam);
			unset($this->objTSessionID);
		}
		
		public function GetAttemptsFromTestSession($tsession_id, &$bShowPreRestoreForm)
		{
			return $this->objTestSession->GetAttemptsFromTestSession($tsession_id, $bShowPreRestoreForm);
		}
		
		public function IsTestPending($user_id, $test_id, $tschd_id)
		{
			return $this->objTestSession->SessionExist($user_id, $test_id, $tschd_id);
		}
		
		public function LoadTest($user_id, $test_id, $tschd_id, $language, &$bNew)
		{
			if($this->objTestSession->SessionExist($user_id, $test_id, $tschd_id) != null)
			{
				$bNew = false;
				$this->FillTestFromSession($user_id, $test_id, $tschd_id);
			}
			else 
			{
				$bNew = true;
				$this->FillNewTest($user_id, $test_id, $tschd_id, $language);
			}
			
			/*echo("<pre>");
			print_r($this->objIterator);
			echo("</pre>");*/
			
			return $this->objIterator;
		}
		
		public function GetMCPAParam($test_id)
		{
			if($this->objMCPAParam == null)
			{
				// Fire query and fill the details
				$query = sprintf("select * from test where test_id='%s'", $test_id);
		
				$result = mysql_query($query, $this->objDBLink) or die('Get QuesSource error : ' . mysql_error());
				
				$this->objMCPAParam = mysql_fetch_array($result);
			}
			return $this->objMCPAParam;
		}
		
		public function GetTestParam($test_id, $test_nature)
		{
			if($this->objTestParam == null)
			{
				// Fire query and fill the details
				if($test_nature == CConfig::TEST_NATURE_DYNAMIC)
				{
					$this->objTestParam = $this->objTestDynamic->GetTestParams($test_id);
				}
				else 
				{
					$this->objTestParam = $this->objTestStatic->GetTestParams($test_id);
				}
			}
			return $this->objTestParam;
		}
		
		public function GetTestType($test_id)
		{
			$retVal = 0;
			
			$query = sprintf("select test_type from test where test_id='%s'", $test_id);
			
			$result = mysql_query($query, $this->objDBLink) or die('Get Test Type error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				
				$retVal = $row['test_type'];
			}
			return $retVal;
		}
		
		public function UpdateQuestion($UserID, $TestID, $TSchd_id, $Section, $Question, $language)
		{
			$existingQID 	= $this->aryQuestionID[$Section][$Question];
			$DiffLevel 		= $this->aryDifficulty[$Section][$Question];
			$TopicID 		= $this->aryTopicID[$Section][$Question];
			$SubjectID 		= $this->arySubjectID[$Section][$Question];
			
			$testDataAry	= $this->GetMCPAParam($TestID);
			
			/*
			printf("<br/>Section; %s, Question:%s", $Section, $Question);
			printf("<br/>UpdateQuestion( existing_id:%s , new_id:%s)", $existingQID, $this->aryQuestionID[$Section][$Question]);
			echo "<pre>";
			print_r($this->arySubjectID);
			echo "</pre>";
			*/
			
			$this->objIterator[$Section][$Question] = $this->objQuestion->GetQuestion($this->array_value_recursive($this->aryQuestionID), $DiffLevel, $TopicID, $SubjectID, $language, $testDataAry['tag_id']);
			$this->aryQuestionID[$Section][$Question] = $this->objIterator[$Section][$Question]['ques_id'];
			
			/*
			echo "<pre>";
			print_r($this->arySubjectID);
			echo "</pre>";
			printf("<br/>UpdateQuestion( existing_id:%s , new_id:%s)", $existingQID, $this->aryQuestionID[$Section][$Question]);
			*/
			
			$this->objTestSession->UpdateQuesID($UserID, $TestID, $TSchd_id, $existingQID, $this->aryQuestionID[$Section][$Question]);
												   
			return $this->objIterator[$Section][$Question];
		}
		
		public function SubmitAnswer($UserID, $TestID, $nTSchdID, $Section, $Question, $Answer)
		{
			/*printf("Section: '%s', Question: '%s'<br/>", $Section, $Question);
			
			echo "<pre>";
			print_r($this->aryQuestionID[$Section][$Question]);
			echo "</pre>";*/
			
			$this->objTestSession->UpdateAnswer($UserID, $TestID, $nTSchdID, $this->aryQuestionID[$Section][$Question], $Answer);
		}
		
		public function UpdateTimeElapsed($UserID, $TestID, $nTSchdID, $TimeElapsed)
		{
			$this->objTestSession->SetTimeElapsed($UserID, $TestID, $nTSchdID, $TimeElapsed);
		}
		
		public function GetCurrentTime($user_id, $test_id, $tschd_id)
		{
			return $this->objTestSession->GetElapsedTime($user_id, $test_id, $tschd_id);
		}
		
		public function GetTestAnswers($user_id, $test_id, $tschd_id)
		{
			return $this->objTestSession->GetAttemptedAnswers($user_id, $test_id, $tschd_id);
		}
		
		public function GetFirstUnattemptedQuesInfo($tsession_id)
		{
			return $this->objTestSession->GetFirstUnattemptedQuesInfo($tsession_id);
		}
		
		public function TestRestoreLog($reason, $tsession_id, $time_zone)
		{
			return $this->objTestSession->TestRestoreLog($reason, $tsession_id, $time_zone);
		}
		
		public function EndTest($user_id, $test_id, $tschd_id)
		{
			$test_type = $this->GetTestType($test_id);
			
			return $this->objTestSession->EndSession($user_id, $test_id, $tschd_id, $test_type);
		}
		
		public function TerminateTestSession($tsession_id)
        {
            return $this->objTestSession->TerminateTestSession($tsession_id);
        }
        
        public function GetTranslatedQuestion($group_title, $language)
        {
        	return $this->objQuestion->GetTranslatedQuestion($group_title, $language);
        }
	}	
?>