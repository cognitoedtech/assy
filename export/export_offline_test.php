<?php 
	include_once(dirname(__FILE__)."/../database/config.php");
	
	class CExportOfflineTest
	{
		private $db_link;
		private $user_list;
		private $tag_id;
		private $subject_topic_array;
		private $rc_id_array;
		private $direction_id_array;
		
		public function __construct()
		{
			$this->db_link = mysql_connect(CConfig::HOST, CConfig::USER_NAME, CConfig::PASSWORD);
			mysql_select_db(CConfig::DB_MCAT, $this->db_link);
		}
		
		public function __destruct()
		{
			mysql_close($this->db_link);
			unset($this->user_list);
		}
		
		private function GetTestTableData($test_id)
		{
			$retArray = array();
			
			$query = sprintf("select * from test where test_id='%s'", $test_id);
			
			$result = mysql_query($query, $this->db_link) or die('Get Test Table Data error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$retArray = mysql_fetch_assoc($result);
				
				$this->tag_id = $retArray['tag_id'];
			}
			
			return $retArray;
		}
		
		private function GetTestDynamicTableData($test_id)
		{
			$retArray = array();
			
			$this->subject_topic_array = array();
			
			$query = sprintf("select * from test_dynamic where test_id='%s'", $test_id);
			
			$result = mysql_query($query, $this->db_link) or die('Get Test Dynamic Table Data error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$retArray = mysql_fetch_assoc($result);
				
				$topicDetails =  split('[-:@#&;]',$retArray['topic_in_subject']);
				
				for($index = 0; $index < count($topicDetails); $index += 9)
				{
					if(!empty($topicDetails[$index]))
					{
						if(isset($this->subject_topic_array[$topicDetails[$index + 1]]))
						{
							array_push($this->subject_topic_array[$topicDetails[$index + 1]], $topicDetails[$index + 2]);
						}
						else 
						{
							$this->subject_topic_array[$topicDetails[$index + 1]] = array();
							array_push($this->subject_topic_array[$topicDetails[$index + 1]], $topicDetails[$index + 2]);
						}
					}
				}
			}
			
			return $retArray;
		}
		
		private function GetTestScheduleTableData($schd_id)
		{
			$retArray = array();
			
			$query = sprintf("select * from test_schedule where schd_id='%s'", $schd_id);
				
			$result = mysql_query($query, $this->db_link) or die('Get Test Schedule Table Data error : ' . mysql_error());
				
			if(mysql_num_rows($result) > 0)
			{
				$retArray = mysql_fetch_assoc($result);
				
				$this->user_list = $retArray['user_list'];
			}
			
			return $retArray;
		}
		
		private function GetTestInstructionsTableData($test_id)
		{
			$retArray = array();
			
			$query = sprintf("select * from test_instructions where test_id='%s'", $test_id);
		
			$result = mysql_query($query, $this->db_link) or die('Get Test Instructions Table Data error : ' . mysql_error());
		
			if(mysql_num_rows($result) > 0)
			{
				$retArray = mysql_fetch_assoc($result);
			}
			
			return $retArray;
		}
		
		private function GetUserstableData($user_id)
		{
			$retArray = array();
			
			$user_list_array = explode(";", $this->user_list);
			
			array_pop($user_list_array);
			
			$user_list_for_query = "'".implode("', '",$user_list_array)."'";
			
			$query = sprintf("select user_id, user_type, login_name, firstname, lastname, passwd, email, contact_no, gender, city, state, country, dob from users where user_id IN (%s, '%s') ", $user_list_for_query, $user_id);
			
			$result = mysql_query($query, $this->db_link) or die('Get Users Table Data error : ' . mysql_error());
			
			while($row = mysql_fetch_assoc($result))
			{
				array_push($retArray, $row);
			}
			
			return $retArray;
		}
		
		private function GetQuestionTableData()
		{
			$retArray = array();
			
			$this->rc_id_array = array();
			$this->direction_id_array = array();
			
			$subject_topic_cond = "";
			$tag_id_cond = "";
			if(!empty($this->tag_id))
			{
				$tag_id_cond = sprintf("and tag_id='%s'", $this->tag_id);
			}
			
			foreach ($this->subject_topic_array as $subject_id=>$topic_id_ary)
			{
				foreach($topic_id_ary as $topic_id)
				{
					$query = sprintf("select ques_id, ques_type, mca, linked_to, group_title, language, tag_id, user_id, options, question, subject_id, topic_id, difficulty_id from question where subject_id='%s' and topic_id='%s' %s", $subject_id, $topic_id, $tag_id_cond);
				
					$result = mysql_query($query, $this->db_link) or die('Get Question Table Data error : ' . mysql_error());
						
					while($row = mysql_fetch_assoc($result))
					{
						$row['question'] = base64_encode($row['question']);
						
						if($row['ques_type'] == CConfig::QT_READ_COMP && !in_array($row['linked_to'], $this->rc_id_array))
						{
							array_push($this->rc_id_array, $row['linked_to']);
						}
						else if($row['ques_type'] == CConfig::QT_DIRECTIONS && !in_array($row['linked_to'], $this->direction_id_array))
						{
							array_push($this->direction_id_array, $row['linked_to']);
						}
						
						array_push($retArray, $row);
					}
				}
			}
			return $retArray;
		}
		
		private function GetRCParaTableData()
		{
			$retArray = array();
			
			if(!empty($this->rc_id_array))
			{
				$query = sprintf("select * from rc_para where rc_id IN (%s)", implode(",", $this->rc_id_array));
					
				$result = mysql_query($query, $this->db_link) or die('Get RC Para Table Data error : ' . mysql_error());
					
				while($row = mysql_fetch_assoc($result))
				{
					$row['description'] = base64_encode($row['description']);
				
					array_push($retArray, $row);
				}	
			}
			
			return $retArray;
		}
		
		private function GetDirectionsParaTableData()
		{
			$retArray = array();
			
			if(!empty($this->direction_id_array))
			{
				$query = sprintf("select * from directions_para where directions_id IN (%s)", implode(",", $this->direction_id_array));
					
				$result = mysql_query($query, $this->db_link) or die('Get Directions Para Table Data error : ' . mysql_error());
					
				while($row = mysql_fetch_assoc($result))
				{
					$row['description'] = base64_encode($row['description']);
				
					array_push($retArray, $row);
				}
			}
			
			return $retArray;
		}
		
		public function ExportData($test_id, $schd_id, $user_id)
		{
			$exportedDataArray = array();
			
			$exportedDataArray["test"] 				= $this->GetTestTableData($test_id); 
			$exportedDataArray["test_dynamic"] 		= $this->GetTestDynamicTableData($test_id);
			$exportedDataArray["test_schedule"] 	= $this->GetTestScheduleTableData($schd_id);
			$exportedDataArray["test_instructions"] = $this->GetTestInstructionsTableData($test_id);
			$exportedDataArray["users"] 			= $this->GetUserstableData($user_id);
			$exportedDataArray["question"] 			= $this->GetQuestionTableData();
			$exportedDataArray["rc_para"] 			= $this->GetRCParaTableData();
			$exportedDataArray["directions_para"] 	= $this->GetDirectionsParaTableData();
			
			$file = tempnam("tmp", "zip");
			$zip = new ZipArchive();
			$zip->open($file, ZipArchive::OVERWRITE);
			
			// Stuff with content
			$zip->addFromString($schd_id."_"."file.json", json_encode($exportedDataArray));
			
			// Close and send to users
			$zip->close();
			header('Content-Type: application/zip');
			header('Content-Length: ' . filesize($file));
			header('Content-Disposition: attachment; filename="'.$exportedDataArray["test"]["test_name"].'.zip"');
			readfile($file);
			unlink($file);
		}
	}
?>