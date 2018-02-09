<?php
	class CTestDynamic
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
		public function ParseSectionDetails($section_details)
		{
			$objSecDetails = array();
			
			$secAry = explode(';', $section_details);
			
			$i = 0;
			foreach ($secAry as $section)
			{
				if(substr_count($section, ",") == 3) {
					$params = split('[#(,,,)]', $section);
					
					$objSecDetails[$i]['name'] 					= $params[0];
					$objSecDetails[$i]['questions'] 			= $params[1];
					$objSecDetails[$i]['min_cutoff'] 			= empty($params[2]) ? 0 : $params[2];
					$objSecDetails[$i]['max_cutoff'] 			= empty($params[3]) ? 100 : $params[3];
					$objSecDetails[$i]['mark_for_correct'] 		= $params[4];
					$objSecDetails[$i]['mark_for_incorrect'] 	= $params[5];
				}
				else if(substr_count($section, ",") == 4) {
					$params = split('[#(,,,)]', $section);
						
					$objSecDetails[$i]['name'] 					= $params[0];
					$objSecDetails[$i]['questions'] 			= $params[1];
					$objSecDetails[$i]['min_cutoff'] 			= empty($params[2]) ? 0 : $params[2];
					$objSecDetails[$i]['max_cutoff'] 			= empty($params[3]) ? 100 : $params[3];
					$objSecDetails[$i]['mark_for_correct'] 		= $params[4];
					$objSecDetails[$i]['partial_marks']			= $params[5];					
					$objSecDetails[$i]['mark_for_incorrect'] 	= $params[6];
				}
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
		
		public function ParseTopicDetails($topic_in_subject, $parse_group = true)
		{
			$objTopicDetails = null;
			
			$topicAry = explode(';', $topic_in_subject);
			
			$i = 0;
			foreach ($topicAry as $topic)
			{
				$part = explode('@', $topic);
				
				$params_1 = $parse_group ? split('[~:-]', $part[0]) : split('[:-]', $part[0]);
				$params_2 = split('[&#]', $part[1]);
				
				if(count($params_1) == 4 && $parse_group) {
					$objTopicDetails[$i]['group'] = $params_1[0];
					$objTopicDetails[$i]['section'] = $params_1[1];
					$objTopicDetails[$i]['subject_id'] = $params_1[2];
					$objTopicDetails[$i]['topic_id'] = $params_1[3];
				}
				else {
					$objTopicDetails[$i]['group'] = "-";
					$objTopicDetails[$i]['section'] = $params_1[0];
					$objTopicDetails[$i]['subject_id'] = $params_1[1];
					$objTopicDetails[$i]['topic_id'] = $params_1[2];
				}
				
				$objTopicDetails[$i]['easy_questions'] = $params_2[1];
				$objTopicDetails[$i]['modr_questions'] = $params_2[3];
				$objTopicDetails[$i]['diff_questions'] = $params_2[5];
				
				$i++;
			}
			
			return $objTopicDetails;
		}
		
		// - - - - - - - - - - - - - - - - - - - - - - - - - -
		// Public Member Function
		// - - - - - - - - - - - - - - - - - - - - - - - - - -
		public function GetSectionDetails($test_id)
		{
			$query = sprintf("select section_details from test_dynamic where test_id='%s'", $test_id);
			
			$result = mysql_query($query, $this->objDBLink) or die('Get Section error : ' . mysql_error());
			
			$row = mysql_fetch_array($result);
			
			return $this->ParseSectionDetails($row['section_details']);
		}
		
		public function GetSubjectDetails($test_id)
		{
			$query = sprintf("select subject_in_section from test_dynamic where test_id='%s'", $test_id);
		
			$result = mysql_query($query, $this->objDBLink) or die('Get Subject error : ' . mysql_error());
			
			$row = mysql_fetch_array($result);
			
			return $this->ParseSubjectDetails($row['subject_in_section']);
		}
		
		public function GetTopicDetails($test_id, $parse_group = true)
		{
			$query = sprintf("select topic_in_subject from test_dynamic where test_id='%s'", $test_id);
		
			$result = mysql_query($query, $this->objDBLink) or die('Get Topic error : ' . mysql_error());
			
			$row = mysql_fetch_array($result);
			
			return $this->ParseTopicDetails($row['topic_in_subject'], $parse_group);
		}

		public function GetDuration($test_id)
		{
			$query = sprintf("select test_duration from test_dynamic where test_id='%s'", $test_id);
		
			$result = mysql_query($query, $this->objDBLink) or die('Get Duration error : ' . mysql_error());
			
			$row = mysql_fetch_array($result);
			
			return $row['test_duration'];
		}
		
		public function GetQuesSource($test_id)
		{
			$query = sprintf("select ques_source from test_dynamic where test_id='%s'", $test_id);
		
			$result = mysql_query($query, $this->objDBLink) or die('Get QuesSource error : ' . mysql_error());
			
			$row = mysql_fetch_array($result);
			
			return $row['ques_source'];
		}
		
		public function GetTestParams($test_id)
		{
			$query = sprintf("select * from test_dynamic where test_id='%s'", $test_id);
			
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
	}
?>