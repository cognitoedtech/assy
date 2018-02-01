<?php
	include_once(dirname(__FILE__)."/../../database/config.php");
	include_once(dirname(__FILE__)."/../../lib/utils.php");
	include_once("tbl_test.php");
	
	class CTestHelper 
	{
		private $objTest;
		private $objTestDynamic;
		
		private $objDBLink;
		private $objIterator;
		
		private $sUserID;
		private $nTestID;
		private $nTschdID;
		
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// Private Functions
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		private function GetBasicTestDetails($test_id)
		{
			$query = sprintf("select * from test where test_id='%s'", $test_id);
			
			$result = mysql_query($query, $this->objDBLink) or die('Get Basic Test Details error : ' . mysql_error());
			
			$row = mysql_fetch_array($result);
			
			return $row;
		}
		
		private function GetExtendedTestDetails($test_id)
		{
			$query = sprintf("select * from test_dynamic where test_id='%s'", $test_id);
			
			$result = mysql_query($query, $this->objDBLink) or die('Get Extended Test Details error : ' . mysql_error());
			
			$row = mysql_fetch_array($result);
			
			return $row;
		}
		
		private function NormalizeSubDetailsAry($arySubDtls)
		{
			$normAry = array();
			$arySize = count($arySubDtls);
			
			for($index = 0; $index < $arySize; $index += 3)
			{
				if(!(empty($arySubDtls[$index]) && !is_numeric($arySubDtls[$index])) && !is_array($normAry[$arySubDtls[$index]]))
				{
					$normAry[$arySubDtls[$index]] = array();
				}
				
				if(!(empty($arySubDtls[$index]) && !is_numeric($arySubDtls[$index])))
				{
					array_push($normAry[$arySubDtls[$index]], array($arySubDtls[$index+1], $arySubDtls[$index+2])) ;
				}
			}
			
			return $normAry;
		}
		
		private function NormalizeTpcDetailsAry($aryTopicDtls)
		{
			$normAry = array();
			$arySize = count($aryTopicDtls);
			
			for($index = 0; $index < $arySize; $index += 9)
			{
				if(!(empty($aryTopicDtls[$index+1]) && !is_numeric($aryTopicDtls[$index+1])) &&  !is_array($normAry[$aryTopicDtls[$index+1]]))
				{
					$normAry[$aryTopicDtls[$index+1]] = array();
				}
				
				if(!(empty($aryTopicDtls[$index+1]) && !is_numeric($aryTopicDtls[$index+1])) && !is_array($normAry[$aryTopicDtls[$index+1]]['topic_details']))
				{
					$normAry[$aryTopicDtls[$index+1]]['topic_details'] = array();
				}
				
				if(!(empty($aryTopicDtls[$index+1]) && !is_numeric($aryTopicDtls[$index+1])))
				{
					$normAry[$aryTopicDtls[$index+1]]['ques_cnt'] += $aryTopicDtls[$index+4] + $aryTopicDtls[$index+6] + $aryTopicDtls[$index+8];
				
					array_push($normAry[$aryTopicDtls[$index+1]]['topic_details'], array($aryTopicDtls[$index+2], $aryTopicDtls[$index+4], $aryTopicDtls[$index+6], $aryTopicDtls[$index+8])) ;
				}
			}
			
			return $normAry;
		}
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// Public Functions
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		public function __construct()
		{
			$this->objDBLink = mysql_connect(CConfig::HOST, CConfig::USER_NAME, CConfig::PASSWORD);
			mysql_select_db(CConfig::DB_MCAT, $this->objDBLink);
			
			$this->objTest = new CTest($this->objDBLink);
			$this->objTestDynamic = new CTestDynamic($this->objDBLink);
		}
		
		public function __destruct()
		{
			mysql_close($this->objDBLink);
		}
		
		public function GetIterator()
		{
			return $this->objIterator;
		}
		
		public function GetQuesSource($test_id)
		{
			return $this->objTestDynamic->GetQuesSource($test_id);
		}
		
		public function GetAttemptsFromTestSession($tsession_id, &$bShowPreRestoreForm)
		{
			return $this->objTest->GetAttemptsFromTestSession($tsession_id, $bShowPreRestoreForm);
		}
		
		public function IsTestSessionExpire($tsession_id, &$nExpireSecOffset)
		{
			$bRet = false;
			$query = sprintf("select test.expire_hrs, test_session.session_created, now() as now from test, test_session where test.test_id=test_session.test_id and test_session.tsession_id='%s'", $tsession_id);
			
			$result = mysql_query($query, $this->objDBLink) or die('Is Test Session Expire error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				
				if($row['expire_hrs'] != -1)
				{
					$bRet = strtotime($row['session_created']." + ".$row['expire_hrs']."Hours") < strtotime($row['now']) ? true : false;
					//printf("Expire Hours: %s, Session Created: %s, Now: %s [Expire at: %s, TCreated: %s, TNow: %s]<br/><br/>", $row['expire_hrs'], $row['session_created'], $row['now'], strtotime($row['session_created']." + ".$row['expire_hrs']."Hours"), strtotime($row['session_created']), strtotime($row['now']));
					
					$nExpireSecOffset = strtotime($row['session_created']." + ".$row['expire_hrs']."Hours") - strtotime($row['now']);
				}
				else 
				{
					$nExpireSecOffset = "NEVER";
				}
			}
			
			return $bRet;
		}
		
		public function DecrementAttemptsInTestSession($tsession_id)
		{
			$query = sprintf("update test_session set attempts_remaining = (attempts_remaining-1) where test_session.tsession_id='%s'", $tsession_id);
			
			$result = mysql_query($query, $this->objDBLink) or die('Decrement Attempts In Test Session error : ' . mysql_error());
		}
		
		public function CheckTestName($test_name, $user_id)
		{
			$aryRet = array("present" => 0);
			$query = sprintf("select * from test where test_name='%s' AND owner_id='%s'", $test_name, $user_id);
			
			$result = mysql_query($query, $this->objDBLink) or die('Check Test Name error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$aryRet["present"] = 1;
			}
			
			return $aryRet;
		}
		
		public function CheckTestPkgName($pkg_name, $user_id)
		{
			$aryRet = array("present" => 0);
			$query = sprintf("select * from test_package where pkg_name='%s' AND producer_id='%s'", $pkg_name, $user_id);
			
			$result = mysql_query($query, $this->objDBLink) or die('Check Test Package Name error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$aryRet["present"] = 1;
			}
			
			return $aryRet;
		}
		
		public function IsTestPending($user_id, $test_id, $tschd_id)
		{
			// echo $user_id;
			return $this->objTest->IsTestPending($user_id, $test_id, $tschd_id);

		}
		
		public function StartTest($user_id, $test_id, $tschd_id, $language)
		{
			$bNew = false;
			$this->objIterator  = $this->objTest->LoadTest($user_id, $test_id, $tschd_id, $language, $bNew);
			
			/*echo("<pre>");
			printf(count($this->objIterator));
			echo("</pre>");*/
			
			$this->sUserID  = $user_id;
			$this->nTestID  = $test_id;
			$this->nTschdID = $tschd_id;
			
			return $bNew;
		}
		
		public function TestRestoreLog($reason, $tsession_id, $time_zone)
		{
			return $this->objTest->TestRestoreLog($reason, $tsession_id, $time_zone);
		}
		
		public function GetMCPAParams($test_id)
		{
			return $this->objTest->GetMCPAParam($test_id);
		}
		
		public function GetTestParams($test_id, $test_nature)
		{
			return $this->objTest->GetTestParam($test_id, $test_nature);
		}
		
		public function ReplaceQuestion($Section, $Question, $language)
		{
			$this->objIterator[$Section][$Question] = $this->objTest->UpdateQuestion($this->sUserID, $this->nTestID, $this->nTschdID, $Section, $Question, $language);
		}
		
		public function GetQuestion($Section, $Question, $TimeElapsed)
		{
			//printf("<br/>GetQuestion(Section: %s, Question: %s)<br/>", $Section, $Question);
			if($TimeElapsed != -1)
			{
				$this->objTest->UpdateTimeElapsed($this->sUserID, $this->nTestID, $this->nTschdID, $TimeElapsed);
			}
			
			/*ksort($this->objIterator[$Section]);
			echo("<pre>");
			print_r($this->objIterator[$Section]);
			echo("</pre>");*/
			
			return $this->objIterator[$Section][$Question] ;
		}

		public function GetNextQuestion(&$Section, &$CurQuestion, $TimeElapsed)
		{
			$QuesAry = null;
			
			//printf("<br/>GetNextQuestion(Section: %s, CurQuestion: %s)<br/>", $Section, $CurQuestion);
			
			/*echo("<pre>");
			print_r($this->objIterator);
			echo("</pre>");*/
			
			// Update Elapsed Time
			if($TimeElapsed != -1)
			{
				$this->objTest->UpdateTimeElapsed($this->sUserID, $this->nTestID, $this->nTschdID, $TimeElapsed);
			}
			
			// Locate and return question
			if(isset($this->objIterator[$Section][$CurQuestion+1]))
			{
				$CurQuestion 	+= 1;
				$QuesAry 		= $this->objIterator[$Section][$CurQuestion] ;
				
			}
			else if(isset($this->objIterator[$Section+1][0]))
			{
				$Section 		+= 1;
				$CurQuestion 	= 0;
				$QuesAry 		= $this->objIterator[$Section][$CurQuestion];
			}
			else 
			{
				$Section 		= 0;
				$CurQuestion 	= 0;
				$QuesAry 		= $this->objIterator[$Section][$CurQuestion];
			}
			
			return $QuesAry;
		}
		
		public function GetTranslatedQuestion($group_title, $language)
		{
			return $this->objTest->GetTranslatedQuestion($group_title, $language);
		}
		
		public function GetAnswers($user_id, $test_id, $tschd_id)
		{
			return $this->objTest->GetTestAnswers($user_id, $test_id, $tschd_id);
		}
		
		public function GetQuesType($Section, $Question)
		{
			$iterator = $this->objTest->GetTestIterator();
			
			//CUtils::LogDataInFile("post_answer.txt", $iterator, true);
			
			return $iterator[$Section][$Question]['ques_type'];
		}
		
		public function GetIntQuesAns($Section, $Question)
		{
			$iterator = $this->objTest->GetTestIterator($Section, $Question);
			
			return $iterator[$Section][$Question]['options'][0]['option'];
		}
		
		public function SubmitAnswer($UserID, $TestID, $nTSchdID, $Section, $Question, $Answer, $TimeElapsed)
		{
			$this->objTest->SubmitAnswer($UserID, $TestID, $nTSchdID, $Section, $Question, $Answer);
			
			$this->objTest->UpdateTimeElapsed($UserID, $TestID, $nTSchdID, $TimeElapsed);
		}
		
		public function GetElapsedTime($user_id, $test_id, $tschd_id)
		{
			return $this->objTest->GetCurrentTime($user_id, $test_id, $tschd_id);
		}
		
		public function GetSectionDetails($test_id)
		{
			return $this->objTestDynamic->GetSectionDetails($test_id);
		}
		
		public function GetSubjectDetails($test_id)
		{
			return $this->objTestDynamic->GetSubjectDetails($test_id);
		}
		
		public function GetTopicDetails($test_id)
		{
			return $this->objTestDynamic->GetTopicDetails($test_id);
		}
		
		public function GetDuration($test_id)
		{
			return $this->objTestDynamic->GetDuration($test_id);
		}
		
		public function EndExam($user_id, $test_id, $tschd_id)
		{
			$RetVal = $this->objTest->EndTest($user_id, $test_id, $tschd_id);
			
			unset($_SESSION[CSessionManager::INT_LAST_SECTION]);
			unset($_SESSION[CSessionManager::INT_LAST_QUESTION]);
			unset($_SESSION[CSessionManager::STR_TRANS_LANG_CHOICE]);
			unset($_SESSION[CSessionManager::STR_TEST_TRANS_LANG]);
			unset($_SESSION[CSessionManager::BOOL_FREE_EZEEASSESS_USER]);
			
			return $RetVal;
		}
		
		public function GetSectionName($secIndex)
		{
			$secAry = $this->GetSectionDetails($this->nTestID);
			
			return $secAry[$secIndex]['name'];
		}
		
		public function GetResultVisibility($test_id)
		{
			$visibility = null;
			$query = sprintf("select * from test_dynamic where test_id='%s'", $test_id);
			
			$result = mysql_query($query, $this->objDBLink) or die('Get Result Visibility Error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				
				$visibility = $row['visibility'];
			}
			
			return $visibility;
		}
		
		public function GetFirstUnattemptedQuesInfo($tsession_id)
		{
			return $this->objTest->GetFirstUnattemptedQuesInfo($tsession_id);
		}
		
		public function PrepareTestDetailsHTML($test_id)
		{
			$sTestDetails = "";
			
			$aryCriteria   = array("Cutoff", "Top Candidates");
			$aryVisibility = array("None", "Minimal", "Detailed");
			
			$aryBTDetails = $this->GetBasicTestDetails($test_id);
			$aryETDetails = $this->GetExtendedTestDetails($test_id);
			
			$criteria = $aryETDetails['criteria'];
			
			// - - - - - - - - - - - - - - - - - -
			// Prepare Basic Details
			// - - - - - - - - - - - - - - - - - -
			$sTestDetails .= "<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>";
			$sTestDetails .= "<h2>Test Name: ".$aryBTDetails['test_name']."</h2>";
			$sTestDetails .= "<hr />";
			$sTestDetails .= "<div style='height:300px;overflow:auto;'>";
			$sTestDetails .= "<div style='border:hidden;'>";
			$sTestDetails .= "<b>Basic Test Details:</b>";
			$sTestDetails .= "<table class='js-responsive-table' width='100%' border='1' style='font-weight:bold;margin-top:5px;text-align:center;font:inherit;border-collapse:collapse;'>";
			$sTestDetails .= "<tr style='color:darkred;font-weight:bold;'>";
			$sTestDetails .= "<td>Duration (mins)</td>";
			$sTestDetails .= "<td>Total Questions</td>";
			//$sTestDetails .= "<td>Criteria</td>";
			if($criteria == 0)
			{
				$sTestDetails .= "<td>Minimum Cutoff</td>";
				$sTestDetails .= "<td>Maximum Cutoff</td>";
			}
			else if($criteria == 1)
			{
				$sTestDetails .= "<td colspan='2'>Top N Candidates</td>";
			}
			$sTestDetails .= "</tr>";
			$sTestDetails .= "<tr>";
			$sTestDetails .= "<td>".$aryETDetails['test_duration']."</td>";
			$sTestDetails .= "<td>".$aryETDetails['max_question']."</td>";
			//$sTestDetails .= "<td>".$aryCriteria[$criteria]."</td>";
			
			if($criteria == 0)
			{
				$sTestDetails .= "<td>".$aryETDetails['cutoff_min']."</td>";
				$sTestDetails .= "<td>".$aryETDetails['cutoff_max']."</td>";
			}
			else if($criteria == 1)
			{
				$sTestDetails .= "<td>".$aryETDetails['top_result']."</td>";
			}
			$sTestDetails .= "</tr>";
			$sTestDetails .= "<tr style='color:darkred;font-weight:bold;'>";
			$sTestDetails .= "<td>Question Type</td>";
			$sTestDetails .= "<td>Total Sections</td>";
			$sTestDetails .= "<td>Question Source</td>";
			$sTestDetails .= "<td>Result Visibility</td>";
			$sTestDetails .= "</tr>";
			$sTestDetails .= "<tr>";
			if($aryBTDetails['mcq_type'] == CConfig::QUES_CTG_SCA)
			{
				$sTestDetails .= "<td>Single Correct Answer</td>";
			}
			else
			{
				$sTestDetails .= "<td>Multiple Correct Answer</td>";
			}
			$sTestDetails .= "<td>".$aryETDetails['section_count']."</td>";
			$sTestDetails .= "<td>".(($aryETDetails['ques_source'] == "mipcat")?CConfig::SNC_SITE_NAME : "Personal")."</td>";
			$sTestDetails .= "<td>".$aryVisibility[$aryETDetails['visibility']]."</td>";
			$sTestDetails .= "</tr></table><br/>";
			
			// - - - - - - - - - - - - - - - - - -
			// Prepare MCPA Details
			// - - - - - - - - - - - - - - - - - -
			$aryYN = array("No", "Yes");
			$sTestDetails .= "<b>MCPA Security Setting:</b>";
			$sTestDetails .= "<table class='js-responsive-table' width='100%' border='1' style='font-weight:bold;margin-top:5px;text-align:center;font:inherit;border-collapse:collapse;'>";
			$sTestDetails .= "<tr style='color:darkred;font-weight:bold;'>";
			$sTestDetails .= "<td>Flash Questions</td>";
			$sTestDetails .= "<td>Lock Questions</td>";
			//$sTestDetails .= "<td>Test Expiration (HRS)</td>";
			//$sTestDetails .= "<td>Attempts Allowed</td>";
			$sTestDetails .= "</tr>";
			$sTestDetails .= "<tr>";
			$sTestDetails .= "<td>".$aryYN[$aryBTDetails['mcpa_flash_ques']]."</td>";
			$sTestDetails .= "<td>".$aryYN[$aryBTDetails['mcpa_lock_ques']]."</td>";
			//$sTestDetails .= "<td>".($aryBTDetails['expire_hrs'] == -1?"Never":$aryBTDetails['expire_hrs'])."</td>";
			//$sTestDetails .= "<td>".($aryBTDetails['attempts'] == -1?"Unlimited":$aryBTDetails['attempts'])."</td>";
			$sTestDetails .= "</tr>";
			$sTestDetails .= "</table><br/>";
			
			// - - - - - - - - - - - - - - - - - -
			// Prepare Section Details
			// - - - - - - - - - - - - - - - - - -
			$arySecDtls = explode(";", $aryETDetails['section_details']);
			$sTestDetails .= "<b>Section Details:</b>";
			$sTestDetails .= "<table class='js-responsive-table' width='100%' border='1' style='font-weight:bold;margin-top:5px;text-align:center;font:inherit;border-collapse:collapse;'>";
			$sTestDetails .= "<tr style='font-weight:bold;'>";
			$sTestDetails .= "<td style='color:green'>Section Name</td>";
			$sTestDetails .= "<td style='color:green'>Questions Limit</td>";
			$sTestDetails .= "<td style='color:green'>Min Cutoff</td>";
			$sTestDetails .= "<td style='color:green'>Max Cutoff</td>";
			$sTestDetails .= "<td style='color:green'>Marks for Correct</td>";
			$sTestDetails .= "<td style='color:green'>Marks for Incorrect</td>";
			$sTestDetails .= "</tr>";
			foreach($arySecDtls as $section)
			{
				$params = split('[#(,,,)]', $section);
				if(!empty($params[0]))
				{
					$sTestDetails .= "<tr>";
					$sTestDetails .= "<td>".$params[0]."</td>";
					$sTestDetails .= "<td>".$params[1]."</td>";
					$sTestDetails .= "<td>".$params[2]."</td>";
					$sTestDetails .= "<td>".$params[3]."</td>";
					$sTestDetails .= "<td>".$params[4]."</td>";
					$sTestDetails .= "<td>".$params[5]."</td>";
					$sTestDetails .= "</tr>";
				}
			}
			$sTestDetails .= "</table><br/>";
			/*$sec_fields = 4;
			$sTestDetails .= "<b>Section Details:</b>";
			$sTestDetails .= "<table width='100%' border='1' style='font-weight:bold;margin-top:5px;text-align:center;font:inherit;border-collapse:collapse;'>";
			$sTestDetails .= "<tr>";
			$sTestDetails .= "<td style='color:green;font-weight:bold;'>Section Name</td>";
			for($index=0; $index < $aryETDetails['section_count']; $index++)
			{
				$sTestDetails .= "<td>".$arySecDtls[$index*$sec_fields]."</td>";
			}
			$sTestDetails .= "</tr>";
			$sTestDetails .= "<tr>";
			$sTestDetails .= "<td style='color:green;font-weight:bold;'>Questions Limit</td>";
			for($index=0; $index < $aryETDetails['section_count']; $index++)
			{
				$sTestDetails .= "<td>".$arySecDtls[($index*$sec_fields)+1]."</td>";
			}
			$sTestDetails .= "</tr>";
			$sTestDetails .= "<tr>";
			$sTestDetails .= "<td style='color:darkorange;font-weight:bold;'>Minimum Cutoff</td>";
			for($index=0; $index < $aryETDetails['section_count']; $index++)
			{
				$sTestDetails .= "<td>".$arySecDtls[($index*$sec_fields)+2]."</td>";
			}
			$sTestDetails .= "</tr>";
			$sTestDetails .= "<tr>";
			$sTestDetails .= "<td style='color:darkorange;font-weight:bold;'>Maximum Cutoff</td>";
			for($index=0; $index < $aryETDetails['section_count']; $index++)
			{
				$sTestDetails .= "<td>".$arySecDtls[($index*$sec_fields)+3]."</td>";
			}
			$sTestDetails .= "</tr>";
			$sTestDetails .= "</table><br/>";*/
			
			// - - - - - - - - - - - - - - - - - -
			// Prepare Subject Details
			// - - - - - - - - - - - - - - - - - -
			$arySubDtls = split("[:#;]", $aryETDetails['subject_in_section']);
			$normSubAry = $this->NormalizeSubDetailsAry($arySubDtls);
			/*
			echo "<pre>";
			print_r($normSubAry);
			echo "</pre>";
			*/
			$sTestDetails .= "<b>Subject Details:</b>";
			$sTestDetails .= "<table class='js-responsive-table' width='100%' border='1' style='font-weight:bold;margin-top:5px;text-align:center;font:inherit;border-collapse:collapse;'>";
			$sTestDetails .= "<tr>";
			$sTestDetails .= "<td style='color:green;font-weight:bold;'>Section</td>";
			$sTestDetails .= "<td style='color:green;font-weight:bold;'>Questions Limit</td>";
			$sTestDetails .= "<td style='color:green;font-weight:bold;'>Subject Details</td>";
			$sTestDetails .= "</tr>";
			$sTestDetails .= "<tr>";
			
			$arySecDtls = split("[#(,,,);]", $aryETDetails['section_details']);
			foreach ($normSubAry as $section => $SubjDtls)
			{
				$key = array_search($section, $arySecDtls);
				$ques_cnt = $arySecDtls[$key+1];
				$sTestDetails .= "<td rowspan='".((count($SubjDtls)*2))."'>".$section."</td>";
				$sTestDetails .= "<td rowspan='".((count($SubjDtls)*2))."'>".$ques_cnt."</td>";
				
				foreach ($SubjDtls as $key => $arySubj)
				{
					if($key != 0)
					{
						$sTestDetails .= "<tr>";
					}
					$sTestDetails .= "<td style='color:darkred;font-weight:bold;'>".htmlentities($this->objTestDynamic->GetSubjectName($arySubj[0]))."</td>";
					$sTestDetails .= "</tr>";
					$sTestDetails .= "<tr>";
					$sTestDetails .= "<td>".$arySubj[1]."</td>";
					$sTestDetails .= "</tr>";
				}
			}
			$sTestDetails .= "</table><br/>";
			
			// - - - - - - - - - - - - - - - - - -
			// Prepare Topic Details
			// - - - - - - - - - - - - - - - - - -
			$aryTopicDtls = split('[-:@#&;]', $aryETDetails['topic_in_subject']);
			$normTpcAry = $this->NormalizeTpcDetailsAry($aryTopicDtls);
			/*
			echo "<pre>";
			print_r($normTpcAry);
			echo "</pre>";
			*/
			$sTestDetails .= "<b>Topic Details:</b>";
			$sTestDetails .= "<table class='js-responsive-table' width='100%' border='1' style='font-weight:bold;margin-top:5px;text-align:center;font:inherit;border-collapse:collapse;'>";
			$sTestDetails .= "<tr>";
			$sTestDetails .= "<td style='color:green;font-weight:bold;'>Subject</td>";
			$sTestDetails .= "<td style='color:green;font-weight:bold;'>Question Limit</td>";
			$sTestDetails .= "<td style='color:green;font-weight:bold;' colspan='2'>Topic Details</td>";
			$sTestDetails .= "</tr>";
			
			foreach($normTpcAry as $subj_id => $subj_dtls)
			{
				//echo count($subj_dtls['topic_details'])."<br/>";
				$sTestDetails .= "<tr>";
				$sTestDetails .= "<td style='color:blue;font-weight:bold;' rowspan='".((count($subj_dtls['topic_details'])*4))."'>".$this->objTestDynamic->GetSubjectName($subj_id)."</td>";
				$sTestDetails .= "<td style='color:blue;font-weight:bold;' rowspan='".((count($subj_dtls['topic_details'])*4))."'>".$subj_dtls['ques_cnt']."</td>";
				foreach($subj_dtls['topic_details'] as $key => $topic_dtls)
				{
					if($key != 0)
					{
						$sTestDetails .= "<tr>";
					}
					$sTestDetails .= "<td colspan='2' style='color:darkred;font-weight:bold;'>".ucwords($this->objTestDynamic->GetTopicName($topic_dtls[0]))."</td>";
					$sTestDetails .= "</tr>";
					$sTestDetails .= "<tr>";
					$sTestDetails .= "<td align='left'>Easy</td>";
					$sTestDetails .= "<td>".$topic_dtls[1]."</td>";
					$sTestDetails .= "</tr>";
					$sTestDetails .= "<tr>";
					$sTestDetails .= "<td align='left'>Moderate</td>";
					$sTestDetails .= "<td>".$topic_dtls[2]."</td>";
					$sTestDetails .= "</tr>";
					$sTestDetails .= "<tr>";
					$sTestDetails .= "<td align='left'>Hard</td>";
					$sTestDetails .= "<td>".$topic_dtls[3]."</td>";
					$sTestDetails .= "</tr>";
				}
			}
			$sTestDetails .= "</table><br/>";
			
			$sTestDetails .= "</div></div>";
			return $sTestDetails;
		}
		public function PrepareTestDetailsHTML2($test_id)
		{
			$sTestDetails = "";
			
			$aryCriteria   = array("Cutoff", "Top Candidates");
			$aryVisibility = array("None", "Minimal", "Detailed");
			
			$aryBTDetails = $this->GetBasicTestDetails($test_id);
			$aryETDetails = $this->GetExtendedTestDetails($test_id);
			
			$criteria = $aryETDetails['criteria'];
			
			// - - - - - - - - - - - - - - - - - -
			// Prepare Basic Details
			// - - - - - - - - - - - - - - - - - -
			$sTestDetails .= "<h2 style='margin-left:65px;'>Test Name: ".$aryBTDetails['test_name']."</h2>";
			//$sTestDetails .= "<div style='height:300px;width:578px;overflow:auto;'>";
			//$sTestDetails .= "<div style='border:hidden;'>";
			$sTestDetails .= "<b style='margin-left:65px;'>Basic Test Details:</b>";
			$sTestDetails .= "<table align='center' width='90%' border='1' style='font-weight:bold;margin-top:5px;text-align:center;font:inherit;border-collapse:collapse;'>";
			$sTestDetails .= "<tr style='color:darkred;font-weight:bold;'>";
			$sTestDetails .= "<td>Duration (mins)</td>";
			$sTestDetails .= "<td>Total Questions</td>";
			//$sTestDetails .= "<td>Criteria</td>";
			if($criteria == 0)
			{
				$sTestDetails .= "<td>Minimum Cutoff</td>";
				$sTestDetails .= "<td>Maximum Cutoff</td>";
			}
			else if($criteria == 1)
			{
				$sTestDetails .= "<td colspan='2'>Top N Candidates</td>";
			}
			$sTestDetails .= "</tr>";
			$sTestDetails .= "<tr>";
			$sTestDetails .= "<td>".$aryETDetails['test_duration']."</td>";
			$sTestDetails .= "<td>".$aryETDetails['max_question']."</td>";
			//$sTestDetails .= "<td>".$aryCriteria[$criteria]."</td>";
			
			if($criteria == 0)
			{
				$sTestDetails .= "<td>".$aryETDetails['cutoff_min']."</td>";
				$sTestDetails .= "<td>".$aryETDetails['cutoff_max']."</td>";
			}
			else if($criteria == 1)
			{
				$sTestDetails .= "<td>".$aryETDetails['top_result']."</td>";
			}
			$sTestDetails .= "</tr>";
			$sTestDetails .= "<tr style='color:darkred;font-weight:bold;'>";
			$sTestDetails .= "<td>Question Type</td>";
			$sTestDetails .= "<td>Total Sections</td>";
			$sTestDetails .= "<td>Question Source</td>";
			$sTestDetails .= "<td>Result Visibility</td>";
			$sTestDetails .= "</tr>";
			$sTestDetails .= "<tr>";
			if($aryBTDetails['mcq_type'] == CConfig::QUES_CTG_SCA)
			{
				$sTestDetails .= "<td>Single Correct Answer</td>";
			}
			else 
			{
				$sTestDetails .= "<td>Multiple Correct Answer</td>";
			}
			$sTestDetails .= "<td>".$aryETDetails['section_count']."</td>";
			$sTestDetails .= "<td>".(($aryETDetails['ques_source'] == "mipcat")?CConfig::SNC_SITE_NAME : "Personal")."</td>";
			$sTestDetails .= "<td>".$aryVisibility[$aryETDetails['visibility']]."</td>";
			$sTestDetails .= "</table><br/>";
			
			// - - - - - - - - - - - - - - - - - -
			// Prepare MCPA Details
			// - - - - - - - - - - - - - - - - - -
			$aryYN = array("No", "Yes");
			$sTestDetails .= "<b style='margin-left:65px;'>MCPA Security Setting:</b>";
			$sTestDetails .= "<table align='center' width='90%' border='1' style='font-weight:bold;margin-top:5px;text-align:center;font:inherit;border-collapse:collapse;'>";
			$sTestDetails .= "<tr style='color:darkred;font-weight:bold;'>";
			$sTestDetails .= "<td>Flash Questions</td>";
			$sTestDetails .= "<td>Lock Questions</td>";
			$sTestDetails .= "<td>Test Expiration (HRS)</td>";
			$sTestDetails .= "<td>Attempts Allowed</td>";
			$sTestDetails .= "</tr>";
			$sTestDetails .= "<tr>";
			$sTestDetails .= "<td>".$aryYN[$aryBTDetails['mcpa_flash_ques']]."</td>";
			$sTestDetails .= "<td>".$aryYN[$aryBTDetails['mcpa_lock_ques']]."</td>";
			$sTestDetails .= "<td>".($aryBTDetails['expire_hrs'] == -1?"Never":$aryBTDetails['expire_hrs'])."</td>";
			$sTestDetails .= "<td>".($aryBTDetails['attempts'] == -1?"Unlimited":$aryBTDetails['attempts'])."</td>";
			$sTestDetails .= "</tr>";
			$sTestDetails .= "</table><br/>";
			
			// - - - - - - - - - - - - - - - - - -
			// Prepare Section Details
			// - - - - - - - - - - - - - - - - - -
			
			$arySecDtls = explode(";", $aryETDetails['section_details']);
			$sTestDetails .= "<b>Section Details:</b>";
			$sTestDetails .= "<table width='90%' border='1' style='font-weight:bold;margin-top:5px;text-align:center;font:inherit;border-collapse:collapse;'>";
			$sTestDetails .= "<tr>";
			$sTestDetails .= "<th style='color:green'>Section Name</th>";
			$sTestDetails .= "<th style='color:green'>Questions Limit</th>";
			$sTestDetails .= "<th style='color:green'>Min Cutoff</th>";
			$sTestDetails .= "<th style='color:green'>Max Cutoff</th>";
			$sTestDetails .= "<th style='color:green'>Marks for Correct</th>";
			$sTestDetails .= "<th style='color:green'>Marks for Incorrect</th>";
			$sTestDetails .= "</tr>";
			foreach($arySecDtls as $section)
			{
				$params = split('[#(,,,)]', $section);
				if(!empty($params[0]))
				{
					$sTestDetails .= "<tr>";
					$sTestDetails .= "<td>".$params[0]."</td>";
					$sTestDetails .= "<td>".$params[1]."</td>";
					$sTestDetails .= "<td>".$params[2]."</td>";
					$sTestDetails .= "<td>".$params[3]."</td>";
					$sTestDetails .= "<td>".$params[4]."</td>";
					$sTestDetails .= "<td>".$params[5]."</td>";
					$sTestDetails .= "</tr>";
				}
			}
			$sTestDetails .= "</table><br/>";
			/*$arySecDtls = split("[#;]", $aryETDetails['section_details']);
			$sTestDetails .= "<b style='margin-left:65px;'>Section Details:</b>";
			$sTestDetails .= "<table align='center' width='90%' border='1' style='font-weight:bold;margin-top:5px;text-align:center;font:inherit;border-collapse:collapse;'>";
			$sTestDetails .= "<tr>";
			$sTestDetails .= "<td style='color:green;font-weight:bold;'>Section Name</td>";
			for($index=0; $index < $aryETDetails['section_count']; $index++)
			{
				$sTestDetails .= "<td>".$arySecDtls[$index*2]."</td>";
			}
			$sTestDetails .= "</tr>";
			$sTestDetails .= "<tr>";
			$sTestDetails .= "<td style='color:green;font-weight:bold;'>Questions Limit</td>";
			for($index=0; $index < $aryETDetails['section_count']; $index++)
			{
				$sTestDetails .= "<td>".$arySecDtls[($index*2)+1]."</td>";
			}
			$sTestDetails .= "</tr>";
			$sTestDetails .= "</table><br/>";*/
			
			// - - - - - - - - - - - - - - - - - -
			// Prepare Subject Details
			// - - - - - - - - - - - - - - - - - -
			$arySubDtls = split("[:#;]", $aryETDetails['subject_in_section']);
			$normSubAry = $this->NormalizeSubDetailsAry($arySubDtls);
			/*
			echo "<pre>";
			print_r($normSubAry);
			echo "</pre>";
			*/
			$sTestDetails .= "<b style='margin-left:65px;'>Subject Details:</b>";
			$sTestDetails .= "<table align='center' width='90%' border='1' style='font-weight:bold;margin-top:5px;text-align:center;font:inherit;border-collapse:collapse;'>";
			$sTestDetails .= "<tr>";
			$sTestDetails .= "<td style='color:green;font-weight:bold;'>Section</td>";
			$sTestDetails .= "<td style='color:green;font-weight:bold;'>Questions Limit</td>";
			$sTestDetails .= "<td style='color:green;font-weight:bold;'>Subject Details</td>";
			$sTestDetails .= "</tr>";
			$sTestDetails .= "<tr>";
			$arySecDtls = split("[#(,,,);]", $aryETDetails['section_details']);
			foreach ($normSubAry as $section => $SubjDtls)
			{
				$key = array_search($section, $arySecDtls);
				$ques_cnt = $arySecDtls[$key+1];
				$sTestDetails .= "<td rowspan='".((count($SubjDtls)*2))."'>".$section."</td>";
				$sTestDetails .= "<td rowspan='".((count($SubjDtls)*2))."'>".$ques_cnt."</td>";
				
				foreach ($SubjDtls as $key => $arySubj)
				{
					if($key != 0)
					{
						$sTestDetails .= "<tr>";
					}
					$sTestDetails .= "<td style='color:darkred;font-weight:bold;'>".$this->objTestDynamic->GetSubjectName($arySubj[0])."</td>";
					$sTestDetails .= "</tr>";
					$sTestDetails .= "<tr>";
					$sTestDetails .= "<td>".$arySubj[1]."</td>";
					$sTestDetails .= "</tr>";
				}
			}
			$sTestDetails .= "</table><br/>";
			
			// - - - - - - - - - - - - - - - - - -
			// Prepare Topic Details
			// - - - - - - - - - - - - - - - - - -
			$aryTopicDtls = split('[-:@#&;]', $aryETDetails['topic_in_subject']);
			$normTpcAry = $this->NormalizeTpcDetailsAry($aryTopicDtls);
			/*
			echo "<pre>";
			print_r($normTpcAry);
			echo "</pre>";
			*/
			$sTestDetails .= "<b style='margin-left:65px;'>Topic Details:</b>";
			$sTestDetails .= "<table align='center' width='90%' border='1' style='font-weight:bold;margin-top:5px;text-align:center;font:inherit;border-collapse:collapse;'>";
			$sTestDetails .= "<tr>";
			$sTestDetails .= "<td style='color:green;font-weight:bold;'>Subject</td>";
			$sTestDetails .= "<td style='color:green;font-weight:bold;'>Question Limit</td>";
			$sTestDetails .= "<td style='color:green;font-weight:bold;' colspan='2'>Topic Details</td>";
			$sTestDetails .= "</tr>";
			
			foreach($normTpcAry as $subj_id => $subj_dtls)
			{
				//echo count($subj_dtls['topic_details'])."<br/>";
				$sTestDetails .= "<tr>";
				$sTestDetails .= "<td style='color:blue;font-weight:bold;' rowspan='".((count($subj_dtls['topic_details'])*4))."'>".$this->objTestDynamic->GetSubjectName($subj_id)."</td>";
				$sTestDetails .= "<td style='color:blue;font-weight:bold;' rowspan='".((count($subj_dtls['topic_details'])*4))."'>".$subj_dtls['ques_cnt']."</td>";
				foreach($subj_dtls['topic_details'] as $key => $topic_dtls)
				{
					if($key != 0)
					{
						$sTestDetails .= "<tr>";
					}
					$sTestDetails .= "<td colspan='2' style='color:darkred;font-weight:bold;'>".ucwords($this->objTestDynamic->GetTopicName($topic_dtls[0]))."</td>";
					$sTestDetails .= "</tr>";
					$sTestDetails .= "<tr>";
					$sTestDetails .= "<td align='left'>Easy</td>";
					$sTestDetails .= "<td>".$topic_dtls[1]."</td>";
					$sTestDetails .= "</tr>";
					$sTestDetails .= "<tr>";
					$sTestDetails .= "<td align='left'>Moderate</td>";
					$sTestDetails .= "<td>".$topic_dtls[2]."</td>";
					$sTestDetails .= "</tr>";
					$sTestDetails .= "<tr>";
					$sTestDetails .= "<td align='left'>Hard</td>";
					$sTestDetails .= "<td>".$topic_dtls[3]."</td>";
					$sTestDetails .= "</tr>";
				}
			}			
			$sTestDetails .= "</table><br/>";
			$sTestDetails .= "<b style='margin-left:65px;'>Other Details:</b>";
			$sTestDetails .= "<table align='center' width='90%' border='1' style='font-weight:bold;margin-top:5px;text-align:center;font:inherit;border-collapse:collapse;'>";
			$sTestDetails .= "<tr style='color:darkred;font-weight:bold;'>";
			$sTestDetails .= "<td>Description</td>";
			$sTestDetails .= "<td>Keywords</td>";
			$sTestDetails .= "</tr>";
			$sTestDetails .= "<tr>";
			$sTestDetails .= "<td>".$aryBTDetails['description']."</td>";
			$sTestDetails .= "<td>".$aryBTDetails['keywords']."</td>";
			$sTestDetails .= "</tr>";
			$sTestDetails .= "</table><br/>";
			//$sTestDetails .= "</div></div>";
			return $sTestDetails;
		}
		
		public function GetRCDirectionPara($qid, $ques_type)
		{
			 $sRet  = "";
            $query = "";
            
            //echo ($qid." - ".$ques_type);
            if($ques_type == CConfig::QT_READ_COMP )
            {
                $query = sprintf("select * from rc_para where rc_id = (select linked_to from question where ques_id='%s')", $qid);
            }
            else if($ques_type == CConfig::QT_DIRECTIONS)
            {
                $query = sprintf("select * from directions_para where directions_id = (select linked_to from question where ques_id='%s')", $qid);
            }
           
            //echo ($query."<br/>");
            
            $result = mysql_query($query, $this->objDBLink) or die('Get RC Direction Para error : ' . mysql_error());
           
            while($row = mysql_fetch_array($result))
            {
                $description_mime_type = CUtils::getMimeType($row['description']);
               
                if($description_mime_type == "application/octet-stream")
                {
                    $sRet = sprintf("<p>%s</p>\n", stripslashes($row['description']));
                }
                else
                {
                	$sRet = sprintf("<img class='para_img' src='lib/print_image.php?para_id=%s&ques_type=%s'>",$row[0],$ques_type);
                }
            }
            
            return $sRet;
		}
		
		public function TerminateTestSession($tsession_id)
        {
            return $this->objTest->TerminateTestSession($tsession_id);
        }
	}	
?>