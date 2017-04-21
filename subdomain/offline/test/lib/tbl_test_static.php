<?php
	class CTestStatic
	{
		private $objDBLink;
		
		public function __construct($objDBLink)
		{
			$this->objDBLink = $objDBLink;
		}
		
		public function __destruct()
		{
			
		}
		
		// - - - - - - - - - - - - - - - - - - - - - - - - - -
		// Private Member Function
		// - - - - - - - - - - - - - - - - - - - - - - - - - -
		
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
		
		private function GetTestOwnerID($test_id)
		{
			$query = sprintf("select owner_id from test where test_id='%s'", $test_id);
			
			$result = mysql_query($query, $this->objDBLink)  or die('get test owner_id error : ' . mysql_errno());
			
			$row = mysql_fetch_array($result);
			
			return $row['owner_id'];
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
		// - - - - - - - - - - - - - - - - - - - - - - - - - -
		// Public Member Function
		// - - - - - - - - - - - - - - - - - - - - - - - - - -
		public function ParseSectionDetails($section_details)
		{
			$objSecDetails = null;
			
			$secAry = explode(';', $section_details);
			
			$i = 0;
			foreach ($secAry as $section)
			{
				$params = split('[#(,,,)]', $section);
				
				$objSecDetails[$i]['name'] 					= $params[0];
				$objSecDetails[$i]['questions'] 			= $params[1];
				$objSecDetails[$i]['min_cutoff'] 			= empty($params[2]) ? 0 : $params[2];
				$objSecDetails[$i]['max_cutoff'] 			= empty($params[3]) ? 100 : $params[3];
				$objSecDetails[$i]['mark_for_correct'] 		= $params[4];
				$objSecDetails[$i]['mark_for_incorrect'] 	= $params[5];
				$i++;
			}
			
			return $objSecDetails;
		}
		
		public function ParseSubjectDetails($subject_in_section)
		{
			$objSubDetails = null;
			
			$subAry = explode(';', $subject_in_section);
			
			$i = 0;
			foreach ($subAry as $subject)
			{
				$params = split('[:#]', $subject);
				
				$objSubDetails[$i]['section'] = $params[0];
				$objSubDetails[$i]['subject_id'] = $params[1];
				$objSubDetails[$i]['questions'] = $params[2];
				$i++;
			}
			
			return $objSubDetails;
		}
		
		public function ParseTopicDetails($topic_in_subject)
		{
			$objTopicDetails = null;
			
			$topicAry = explode(';', $topic_in_subject);
			
			$i = 0;
			foreach ($topicAry as $topic)
			{
				$part = explode('@', $topic);
				
				$params_1 = split('[:-]', $part[0]);
				$params_2 = split('[&#]', $part[1]);
				
				$objTopicDetails[$i]['section'] = $params_1[0];
				$objTopicDetails[$i]['subject_id'] = $params_1[1];
				$objTopicDetails[$i]['topic_id'] = $params_1[2];
				
				$objTopicDetails[$i]['easy_questions'] = $params_2[1];
				$objTopicDetails[$i]['modr_questions'] = $params_2[3];
				$objTopicDetails[$i]['diff_questions'] = $params_2[5];
				
				$i++;
			}
			
			return $objTopicDetails;
		}
		
		public function GetSectionDetails($test_id)
		{
			$query = sprintf("select section_details from test_static where test_id='%s'", $test_id);
			
			$result = mysql_query($query, $this->objDBLink) or die('Get Section error : ' . mysql_error());
			
			$row = mysql_fetch_array($result);
			
			return $this->ParseSectionDetails($row['section_details']);
		}
		
		public function GetSubjectDetails($test_id)
		{
			$query = sprintf("select subject_in_section from test_static where test_id='%s'", $test_id);
		
			$result = mysql_query($query, $this->objDBLink) or die('Get Subject error : ' . mysql_error());
			
			$row = mysql_fetch_array($result);
			
			return $this->ParseSubjectDetails($row['subject_in_section']);
		}
		
		public function GetTopicDetails($test_id)
		{
			$query = sprintf("select topic_in_subject from test_static where test_id='%s'", $test_id);
		
			$result = mysql_query($query, $this->objDBLink) or die('Get Topic error : ' . mysql_error());
			
			$row = mysql_fetch_array($result);
			
			return $this->ParseTopicDetails($row['topic_in_subject']);
		}

		public function GetDuration($test_id)
		{
			$query = sprintf("select test_duration from test_static where test_id='%s'", $test_id);
		
			$result = mysql_query($query, $this->objDBLink) or die('Get Duration error : ' . mysql_error());
			
			$row = mysql_fetch_array($result);
			
			return $row['test_duration'];
		}
		
		public function GetQuesSource($test_id)
		{
			$query = sprintf("select ques_source from test_static where test_id='%s'", $test_id);
		
			$result = mysql_query($query, $this->objDBLink) or die('Get QuesSource error : ' . mysql_error());
			
			$row = mysql_fetch_array($result);
			
			return $row['ques_source'];
		}
		
		public function GetTestParams($test_id)
		{
			$query = sprintf("select * from test_static where test_id='%s'", $test_id);
			
			$result = mysql_query($query, $this->objDBLink) or die('Get QuesSource error : ' . mysql_error());
			
			$row = mysql_fetch_array($result);
			
			return $row;
		}
		
		public function GetSubjectName($subject_id)
		{
			$sSubjctName = null;
			
			$query = sprintf("select subject_name from subject where subject_id='%s'", $subject_id);
			
			$result = mysql_query($query, $this->objDBLink) or die('Get Subject Name error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				
				$sSubjctName = $row['subject_name'];
			}
			
			return $sSubjctName;
		}
		
		public function GetTopicName($topic_id)
		{
			$sTopicName = null;
			
			$query = sprintf("select topic_name from topic where topic_id='%s'", $topic_id);
			
			//echo $query."<br/>";
			
			$result = mysql_query($query, $this->objDBLink) or die('Get Topic Name error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				
				$sTopicName = $row['topic_name'];
			}
			
			return $sTopicName;
		}
		
		public function GetQuestionsIter($test_id)
		{
			$quesIter = null;
			$query = sprintf("select questions from test_static where test_id='%s'", $test_id);
			
			$result = mysql_query($query, $this->objDBLink)  or die('Get Questions error : ' . mysql_errno());
			
			if(mysql_num_rows($result) > 0)
			{
				$quesIter = json_decode($row["questions"]);
			}
			
			return $quesIter;
		}
		
		public function GetQuestionsJSON($user_id, $test_id)
		{
			$objIter = array();
			
			//echo $user_id." ".$test_id."<br />";
			$objSectionAry = $this->GetSectionDetails($test_id);
			/*echo ("<pre>");
			print_r($objSectionAry);
			echo ("</pre>");*/
			$objTopicAry   = $this->GetTopicDetails($test_id);
			/*echo ("<pre>");
			print_r($objTopicAry);
			echo ("</pre>");*/
			$qSource = $this->GetQuesSource($test_id);
			
			$sUser = null;
			if($qSource == "mipcat")
			{
				$sUser = "public=1";
			}
			else if ($qSource == "personal")
			{
				$sUser = "user_id='".$this->GetTestOwnerID($test_id)."'";
			}
			
			$aryQuesIndex = array();
			foreach ($objTopicAry as $objTopic)
			{
				$nSecIndex = $this->GetSectionIndex($objSectionAry, $objTopic['section']);
				if(!isset($aryQuesIndex[$nSecIndex]))
				{
					$aryQuesIndex[$nSecIndex] = 0;
				}
				
				$query = sprintf("(select * from question where %s AND subject_id='%s' AND topic_id='%s' AND difficulty_id='1' order by rand() limit %d)", $sUser, $objTopic['subject_id'], $objTopic['topic_id'], $objTopic['easy_questions']);
				$query .= " UNION ";
				$query .= sprintf("(select * from question where %s AND subject_id='%s' AND topic_id='%s' AND difficulty_id='2' order by rand() limit %d)", $sUser, $objTopic['subject_id'], $objTopic['topic_id'], $objTopic['modr_questions']);	
				$query .= " UNION ";
				$query .= sprintf("(select * from question where %s AND subject_id='%s' AND topic_id='%s' AND difficulty_id='3' order by rand() limit %d)", $sUser, $objTopic['subject_id'], $objTopic['topic_id'], $objTopic['diff_questions']);
				
				//echo $query."<br/><br/><br/><br/>";
				
				$result = mysql_query($query, $this->objDBLink)  or die('error select from question : ' . mysql_error());
				
				while($row = mysql_fetch_assoc($result))
				{
					$nQuesIndex = $aryQuesIndex[$nSecIndex];
					
					$objIter[$nSecIndex][$nQuesIndex]['ques_id']  		= $row['ques_id'];
					$objIter[$nSecIndex][$nQuesIndex]['question'] 		= $row['question'];
					$objIter[$nSecIndex][$nQuesIndex]['options'] 		= json_decode($row['options'], true);
					$objIter[$nSecIndex][$nQuesIndex]['opt_count'] 		= count(json_decode($row["options"], true));
					$objIter[$nSecIndex][$nQuesIndex]['mca'] 			= $row["mca"];
					$objIter[$nSecIndex][$nQuesIndex]['topic_id'] 		= $row['topic_id'];
					$objIter[$nSecIndex][$nQuesIndex]['subject_id'] 	= $row['subject_id'];
					$objIter[$nSecIndex][$nQuesIndex]['difficulty_id'] 	= $row['difficulty_id'];
					$objIter[$nSecIndex][$nQuesIndex]['ques_type'] 		= $row['ques_type'];
					$objIter[$nSecIndex][$nQuesIndex]['linked_to'] 		= $row['linked_to'];
					
					$aryQuesIndex[$nSecIndex]++;
				}
			}
			
			/*echo("<pre>");
			print_r($objIter);
			echo("</pre>");*/
			
			$this->NormalizeQuesIterator($objIter, $objSectionAry);
            mysql_free_result($result);
            
            return json_encode($objIter);
		}
	}
?>