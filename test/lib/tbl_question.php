<?php
	include_once("tbl_test_dynamic.php");
	include_once("tbl_test_static.php");
	
	class CQuestion
	{
		private $objDBLink;
		private $objTestDynamic;
		private $objTestStatic;
		
		function __construct($objDBLink)
		{
			$this->objDBLink = $objDBLink;
			$this->objTestDynamic = new CTestDynamic($objDBLink);
			$this->objTestStatic = new CTestStatic($objDBLink);
		}
		
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// Private Functions
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		private function GetSectionIndex($objSectionAry, $sSecName)
		{
			$nIndex = null;
			foreach ($objSectionAry as $key => $Section)
			{
				if($Section['name'] == $sSecName)
				{
					$nIndex = $key;
				}
			}
			
			return $nIndex;
		}
		
		private function GetSectionIndexFromTopicAry($objTopicAry, $sSecName)
		{
			$nIndex = null;
			foreach ($objSectionAry as $key => $Section)
			{
				if($Section['name'] == $sSecName)
				{
					$nIndex = $key;
				}
			}
			
			return $nIndex;
		}
		
		private function NormalizeQuesIterator(&$objIter, $objSectionAry)
		{
			foreach ($objSectionAry as $sIndex => $Section)
			{
				for($qIndex = 0; $qIndex < $Section['questions']; $qIndex++)
				{
					if(empty($objIter[$sIndex][$qIndex]['ques_id']) || !isset($objIter[$sIndex][$qIndex]['ques_id']))
					{
						$objIter[$sIndex][$qIndex]['ques_id']   	= -1;
						$objIter[$sIndex][$qIndex]['question']  	= "Not enough questions in database! Please contact Test-Admin.";
						$objIter[$sIndex][$qIndex]['options']   	= null;
						$objIter[$sIndex][$qIndex]['opt_count'] 	= 0;
						$objIter[$sIndex][$qIndex]['group_title']	= null;
						$objIter[$sIndex][$qIndex]['mca'] 			= 0;
						$objIter[$sIndex][$qIndex]['topic_id'] 		= -1;
						$objIter[$sIndex][$qIndex]['subject_id'] 	= -1;
						$objIter[$sIndex][$qIndex]['difficulty_id'] = -1;
						$objIter[$sIndex][$qIndex]['ques_type'] 	= -1;
						$objIter[$sIndex][$qIndex]['linked_to'] 	= -1;
						$objIter[$sIndex][$qIndex]['language'] 		= "English";
					}
				}
				
				$this->MIpAsort($objIter[$sIndex], "linked_to");
			}
		}
		
		private function GetTestOwnerID($test_id)
		{
			$query = sprintf("select owner_id from test where test_id='%s'", $test_id);
			
			$result = mysql_query($query, $this->objDBLink)  or die('get test owner_id error : ' . mysql_errno());
			
			$row = mysql_fetch_array($result);
			
			return $row['owner_id'];
		}
		
		private function MIpAsort (&$array, $key) 
		{
		    $sorter=array();
		    $ret=array();
		    reset($array);
		    
		    foreach ($array as $ii => $va) {
		        $sorter[$ii]=$va[$key];
		    }
		    
		    asort($sorter);
		    foreach ($sorter as $ii => $va) {
		        $ret[$ii]=$array[$ii];
		    }
		    
		    $array = $ret;
		}
		
		private function GetAnswerFromOptions($options)
		{
			$AnsAry = array();
			
			$opt_ary = json_decode($options, true);
			
			$index = 0;
			/*echo "<pre>";
			print_r($opt_ary);
			echo "</pre>";*/
			foreach($opt_ary as $key => $option)
			{
				if($option['answer'] != 0)
				{
					//echo $option['answer']." ".$key."<br />";
					//$AnsAry[$index++] = $option['answer'];
					$AnsAry[$index++] = $key + 1;
				}
			}
			
			return $AnsAry;
		}
		
		private function GetWeightFromOptions($options)
		{
			$weightAry = array();
				
			$opt_ary = json_decode($options, true);
				
			$index = 0;
			/*echo "<pre>";
			 print_r($opt_ary);
			echo "</pre>";*/
			foreach($opt_ary as $key => $option)
			{
				$weightAry[$index++] = $option['weightage'];
			}
				
			return $weightAry;
		}
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// Public Functions
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		public function GetQuestions($user_id, $test_id, $language, $objMCPAParam)
		{
			$objIter = array();
			$grpTitleAry = array();
			$ques_table = "question" ;
			
			/*if($this->GetMCQType($test_id) == CConfig::QUES_CTG_SCA)
			{
				$ques_table = "question" ;
			}
			else if($this->GetMCQType($test_id) == CConfig::QUES_CTG_MCA)
			{
				$ques_table = "mca_qustion" ;
			}*/
			
			$objSectionAry = $this->objTestDynamic->GetSectionDetails($test_id);
			/*echo ("<pre>");
			print_r($objSectionAry);
			echo ("</pre>");*/
			$objTopicAry   = $this->objTestDynamic->GetTopicDetails($test_id);
			/*echo ("<pre>");
			print_r($objTopicAry);
			echo ("</pre>");*/
			$qSource = $this->objTestDynamic->GetQuesSource($test_id);
			
			$sUser = null;
			if($qSource == "mipcat")
			{
				$sUser = "public=1";
			}
			else if ($qSource == "personal")
			{
				$sUser = "user_id='".$this->GetTestOwnerID($test_id)."'";
			}
			
			$tag_cond = "";
			if(!empty($objMCPAParam['tag_id']))
			{
				$tag_cond = sprintf("AND tag_id='%s'", $objMCPAParam['tag_id']);
			}
			
			$aryQuesIndex = array();
			foreach ($objTopicAry as $objTopic)
			{
				$nSecIndex = $this->GetSectionIndex($objSectionAry, $objTopic['section']);
				if(!isset($aryQuesIndex[$nSecIndex]))
				{
					$aryQuesIndex[$nSecIndex] = 0;
				}
				
				$query = sprintf("(select * from %s where %s AND subject_id='%s' AND topic_id='%s' AND difficulty_id='1' AND language='%s' %s order by rand() limit %d)", $ques_table, $sUser, $objTopic['subject_id'], $objTopic['topic_id'], $language, $tag_cond, $objTopic['easy_questions']);
				$query .= " UNION ";
				$query .= sprintf("(select * from %s where %s AND subject_id='%s' AND topic_id='%s' AND difficulty_id='2' AND language='%s' %s order by rand() limit %d)", $ques_table, $sUser, $objTopic['subject_id'], $objTopic['topic_id'], $language, $tag_cond, $objTopic['modr_questions']);	
				$query .= " UNION ";
				$query .= sprintf("(select * from %s where %s AND subject_id='%s' AND topic_id='%s' AND difficulty_id='3' AND language='%s' %s order by rand() limit %d)", $ques_table, $sUser, $objTopic['subject_id'], $objTopic['topic_id'], $language, $tag_cond, $objTopic['diff_questions']);
				
				//echo $query."<br/><br/><br/><br/>";
				
				$result = mysql_query($query, $this->objDBLink)  or die('error select from question : ' . mysql_errno());
				
				while($row = mysql_fetch_assoc($result))
				{
					$nQuesIndex = $aryQuesIndex[$nSecIndex];
					
					//echo $nQuesIndex."<br />";
					
					$objIter[$nSecIndex][$nQuesIndex]['ques_id']  		= $row['ques_id'];
					$objIter[$nSecIndex][$nQuesIndex]['question'] 		= $row['question'];
					$objIter[$nSecIndex][$nQuesIndex]['options'] 		= json_decode($row['options'], true);
					$objIter[$nSecIndex][$nQuesIndex]['opt_count'] 		= count(json_decode($row["options"], true));
					$objIter[$nSecIndex][$nQuesIndex]['group_title'] 	= $row['group_title'];
					$objIter[$nSecIndex][$nQuesIndex]['mca'] 			= $row["mca"];
					$objIter[$nSecIndex][$nQuesIndex]['topic_id'] 		= $row['topic_id'];
					$objIter[$nSecIndex][$nQuesIndex]['subject_id'] 	= $row['subject_id'];
					$objIter[$nSecIndex][$nQuesIndex]['difficulty_id'] 	= $row['difficulty_id'];
					$objIter[$nSecIndex][$nQuesIndex]['ques_type'] 		= $row['ques_type'];
					$objIter[$nSecIndex][$nQuesIndex]['linked_to'] 		= $row['linked_to'];
					$objIter[$nSecIndex][$nQuesIndex]['language'] 		= $row['language'];
					
					$aryQuesIndex[$nSecIndex]++;
				}
			}
			
			/*echo("<pre>");
			print_r($objIter);
			echo("</pre>");*/
			
			$this->NormalizeQuesIterator($objIter, $objSectionAry);
            mysql_free_result($result);
            
            return $objIter;
		}
		
		function __destruct()
		{
			unset($QAry);
		}
		
		public function GetQuestion($existingQID, $DiffLevel, $TopicID, $SubjectID, $language, $tag_id = NULL)
		{
			
			$tag_cond = "";
			if(!empty($tag_id))
			{
				$tag_cond = sprintf("AND tag_id='%s'", $tag_id);
			}
			
			$QAry = array();
			$query = sprintf("select * from question where subject_id='%s' AND topic_id='%s' AND difficulty_id='%s' AND language='%s' %s AND ques_id not in (%s) limit 1", $SubjectID, $TopicID, $DiffLevel, $language, $tag_cond, implode(",", $existingQID));
			
			//echo $query."<br/><br/><br/><br/>";
			$result = mysql_query($query) or die('Could not connect: ' . mysql_error());
						
			if(mysql_num_rows($result) > 0)
			{
				//echo "Question Found <br/><br/><br/><br/>";
				$row = mysql_fetch_assoc($result);
	
				$QAry['ques_id']		= $row['ques_id'];
				$QAry['question'] 		= $row['question'];
				$QAry['options']		= json_decode($row['options'], true);
				$QAry['opt_count']		= count(json_decode($row["options"], true));
				$QAry['group_title'] 	= $row['group_title'];
				$QAry['mca']			= $row['mca'];
				$QAry['topic_id'] 		= $row['topic_id'];
				$QAry['subject_id'] 	= $row['subject_id'];
				$QAry['difficulty_id'] 	= $row['difficulty_id'];
				$QAry['ques_type'] 		= $row['ques_type'];
				$QAry['linked_to'] 		= $row['linked_to'];
				$QAry['language'] 		= $row['language'];
				
				mysql_free_result($result);
			}
			else 
			{
				//echo "Question Not Found <br/><br/><br/><br/>";
				$QAry['ques_id']		= -1;
				$QAry['question'] 		= "Not enough questions in database!  Please contact Test-Admin.";
				$QAry['option_1']		= "Not Available";
				$QAry['option_2']		= "Not Available";
				$QAry['option_3']		= "Not Available";
				$QAry['option_4']		= "Not Available";
				$QAry['option_5']		= "Not Available";
				$QAry['topic_id'] 		= -1;
				$QAry['subject_id'] 	= -1;
				$QAry['difficulty_id'] 	= -1;
				$QAry['ques_type'] 		= -1;
				$QAry['linked_to'] 		= -1;
				$QAry['language'] 		= "English";
			}
			
			/*echo "GetQuestion: <pre>";
			print_r($QAry);
			echo "</pre>";*/
			
			return $QAry;		
		}
		
		public function GetQuestionByID($QID)
		{
			$QAry = array();
			
			if($QID != -1)
			{
				$query = sprintf("select * from question where ques_id='%s'", $QID);
							
				$result = mysql_query($query) or die('Could not get question: ' . mysql_error());
				if (!$result) 
				{
				   return -1;
				}
		
				$row = mysql_fetch_assoc($result);
	
				$QAry['ques_id']		= $row['ques_id'];
				$QAry['question'] 		= $row['question'];
				$QAry['options']		= json_decode($row['options'], true);
				$QAry['opt_count']		= count(json_decode($row["options"], true));
				$QAry['group_title'] 	= $row['group_title'];
				$QAry['mca']			= $row['mca'];
				$QAry['answer']			= $row['answer'];
				$QAry['topic_id'] 		= $row['topic_id'];
				$QAry['subject_id'] 	= $row['subject_id'];
				$QAry['difficulty_id'] 	= $row['difficulty_id'];
				$QAry['ques_type'] 		= $row['ques_type'];
				$QAry['linked_to'] 		= $row['linked_to'];
				$QAry['language'] 		= $row['language'];
				
				mysql_free_result($result);
			}
			else 
			{
				$QAry['ques_id']		= $QID;
				$QAry['question'] 		= "Not enough questions in database!  Please contact Test-Admin.";
				$QAry['options']		= null;
				$QAry['opt_count']		= 0;
				$QAry['group_title']	= null;
				$QAry['mca']			= 0;
				$QAry['answer']			= -1;
				$QAry['topic_id'] 		= -1;
				$QAry['subject_id'] 	= -1;
				$QAry['difficulty_id'] 	= -1;
				$QAry['ques_type'] 		= -1;
				$QAry['linked_to'] 		= -1;
				$QAry['language'] 		= "English";
			}
			
			return $QAry;		
		}
		
		public function GetTranslatedQuestion($group_title, $language)
		{
			
			$QAry = array();
			
			$query = sprintf("select * from question where group_title='%s' and language='%s'", mysql_real_escape_string($group_title), $language);
			
			$result = mysql_query($query) or die('Get Translated Question error: ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				//echo "Question Found <br/><br/><br/><br/>";
				$row = mysql_fetch_assoc($result);
			
				$QAry['ques_id']		= $row['ques_id'];
				$QAry['question'] 		= $row['question'];
				$QAry['options']		= json_decode($row['options'], true);
				$QAry['opt_count']		= count(json_decode($row["options"], true));
				$QAry['group_title'] 	= $row['group_title'];
				$QAry['mca']			= $row['mca'];
				$QAry['topic_id'] 		= $row['topic_id'];
				$QAry['subject_id'] 	= $row['subject_id'];
				$QAry['difficulty_id'] 	= $row['difficulty_id'];
				$QAry['ques_type'] 		= $row['ques_type'];
				$QAry['linked_to'] 		= $row['linked_to'];
				$QAry['language'] 		= $row['language'];
			
				mysql_free_result($result);
			}
			
			return $QAry;
		}
		
		public function GetQuestionsByIDList($sQuesList, &$aryParticulars)
		{
			// Clean empty values from array.
			$sQuesList = implode( ",", array_filter( explode(",", $sQuesList) ) );
			
			$query = sprintf("select * from question where ques_id IN (%s)", $sQuesList);
			
			//echo $query."<br/>";
			$result = mysql_query($query, $this->objDBLink) or die('Get Questions By ID List error : ' . mysql_error());
			
			$aryCorrectAns = array();
			while($row = mysql_fetch_array($result))
			{
				$aryCorrectAns[$row['ques_id']] = $this->GetAnswerFromOptions($row['options']);
				
				$aryParticulars[$row['ques_id']]['subject_id'] 		= $row['subject_id'];
				$aryParticulars[$row['ques_id']]['topic_id'] 		= $row['topic_id'];
				$aryParticulars[$row['ques_id']]['difficulty_id'] 	= $row['difficulty_id'];
				$aryParticulars[$row['ques_id']]['ques_type'] 		= $row['ques_type'];
				$aryParticulars[$row['ques_id']]['linked_to'] 		= $row['linked_to'];
				$aryParticulars[$row['ques_id']]['language'] 		= $row['language'];
			}
			
			return $aryCorrectAns;
		}
		
		public function GetEQQuestionsByIDList($sQuesList, &$aryParticulars)
		{
			// Clean empty values from array.
			$sQuesList = implode( ",", array_filter( explode(",", $sQuesList) ) );
				
			$query = sprintf("select * from question where ques_id IN (%s)", $sQuesList);
				
			//echo $query."<br/>";
			$result = mysql_query($query, $this->objDBLink) or die('Get Questions By ID List error : ' . mysql_error());
				
			$aryCorrectAns = array();
			while($row = mysql_fetch_array($result))
			{
				$aryCorrectAns[$row['ques_id']] = $this->GetWeightFromOptions($row['options']);
		
				$aryParticulars[$row['ques_id']]['subject_id'] 		= $row['subject_id'];
				$aryParticulars[$row['ques_id']]['topic_id'] 		= $row['topic_id'];
				$aryParticulars[$row['ques_id']]['difficulty_id'] 	= $row['difficulty_id'];
				$aryParticulars[$row['ques_id']]['ques_type'] 		= $row['ques_type'];
				$aryParticulars[$row['ques_id']]['linked_to'] 		= $row['linked_to'];
				$aryParticulars[$row['ques_id']]['language'] 		= $row['language'];
			}
				
			return $aryCorrectAns;
		}
		
		public function GetOptionText($ques_id, $option)
		{
			$sRet = "";
			
			$query = sprintf("select * from question where ques_id='%s'", $ques_id);
			
			$result = mysql_query($query, $this->objDBLink) or die('Get Option Text error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				
				$options = json_decode($row['options']);
				$sRet = $options[$option-1]['option'] ;
			}
			
			return $sRet;
		}
	}
?>	
