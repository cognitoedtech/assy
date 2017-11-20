<?php 
	include_once(dirname(__FILE__)."/../database/config.php");
	include_once(dirname(__FILE__)."/../lib/session_manager.php");

	class CImportNewTest
	{
		private $db_link;
		
		public function __construct()
		{
			$this->db_link = mysql_connect(CConfig::HOST, CConfig::USER_NAME, CConfig::PASSWORD);
			
			mysql_select_db(CConfig::DB_MCAT, $this->db_link);
		}
		
		public function __destruct()
		{
			mysql_close($this->db_link);
		}
		
		private function DeleteTestData()
		{
			$query = sprintf("delete from test");
			
			$result = mysql_query($query, $this->db_link) or die('Delete Test Data error : ' . mysql_error());
		}
		
		private function DeleteTestDynamicData()
		{
			$query = sprintf("delete from test_dynamic");
				
			$result = mysql_query($query, $this->db_link) or die('Delete Test Dynamic Data error : ' . mysql_error());
		}
		
		private function DeleteTestScheduleData()
		{
			$query = sprintf("delete from test_schedule");
				
			$result = mysql_query($query, $this->db_link) or die('Delete Test Schedule Data error : ' . mysql_error());
		}
		
		private function DeleteTestInstructionsData()
		{
			$query = sprintf("delete from test_schedule");
			
			$result = mysql_query($query, $this->db_link) or die('Delete Test Instructions Data error : ' . mysql_error());
		}
		
		private function DeleteQuestionData()
		{
			$query = sprintf("delete from question");
				
			$result = mysql_query($query, $this->db_link) or die('Delete Question Data error : ' . mysql_error());
		}
		
		private function DeleteRCParaData()
		{
			$query = sprintf("delete from rc_para");
		
			$result = mysql_query($query, $this->db_link) or die('Delete RC Para Data error : ' . mysql_error());
		}
		
		private function DeleteDirectionsParaData()
		{
			$query = sprintf("delete from directions_para");
		
			$result = mysql_query($query, $this->db_link) or die('Delete Directions Para Data error : ' . mysql_error());
		}
		
		private function DeleteUsersData()
		{
			$query = sprintf("delete from users where user_type = '%s'", CConfig::UT_INDIVIDAL);
		
			$result = mysql_query($query, $this->db_link) or die('Delete Users Data error : ' . mysql_error());
		}
		
		private function DeleteUserCVData()
		{
			$query = sprintf("delete from user_cv");
		
			$result = mysql_query($query, $this->db_link) or die('Delete User CV Data error : ' . mysql_error());
		}
		
		private function DeleteCountriesData()
		{
			$query = sprintf("delete from countries");
		
			$result = mysql_query($query, $this->db_link) or die('Delete Countries Data error : ' . mysql_error());
		}
		
		private function DeleteTestSessionData()
		{
			$query = sprintf("delete from test_session");
		
			$result = mysql_query($query, $this->db_link) or die('Delete Test Session Data error : ' . mysql_error());
		}
		
		private function DeleteResultData()
		{
			$query = sprintf("delete from result");
		
			$result = mysql_query($query, $this->db_link) or die('Delete Result Data error : ' . mysql_error());
		}
		
		public function CleanExistingTest($bCleanResult = true)
		{
			$this->DeleteTestDynamicData();
			$this->DeleteTestScheduleData();
			$this->DeleteTestInstructionsData();
			$this->DeleteQuestionData();
			$this->DeleteRCParaData();
			$this->DeleteDirectionsParaData();
			
			if($bCleanResult)
			{
				$this->DeleteTestData();
				$this->DeleteUsersData();
				$this->DeleteUserCVData();
				$this->DeleteCountriesData();
				$this->DeleteTestSessionData();
				$this->DeleteResultData();
			}
		}
		
		private function InsertTestData($data)
		{
			if($data['tag_id'] == '')
				$data['tag_id'] = -1;
			
			$query = sprintf("insert into test(test_id, owner_id, test_name, test_type, create_date, tag_id, is_static, is_published, mcq_type, pref_lang, allow_trans, mcpa_flash_ques, mcpa_lock_ques, expire_hrs, attempts, description, keywords, user_ratings, final_rating, public, submitted, deleted) values('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s',%s,'%s','%s',%s)", $data['test_id'], $data['owner_id'], $data['test_name'], $data['test_type'], $data['create_date'], $data['tag_id'], $data['is_static'], $data['is_published'], $data['mcq_type'], $data['pref_lang'], $data['allow_trans'], $data['mcpa_flash_ques'], $data['mcpa_lock_ques'], $data['expire_hrs'], $data['attempts'], $data['description'], $data['keywords'], $data['user_ratings'], !empty($data['final_rating'])?"'".$data['final_rating']."'": "NULL", $data['public'], $data['submitted'], !empty($data['deleted'])?"'".$data['deleted']."'": "NULL");
		
			$result = mysql_query($query, $this->db_link) or die('Insert Test Data error : ' . mysql_error());
			
			return $data['test_name'];
		}
		
		private function InsertTestDynamicData($data)
		{
			$query = sprintf("insert into test_dynamic values('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')", $data['test_id'], $data['section_count'], $data['section_details'], $data['subject_in_section'], $data['topic_in_subject'], $data['criteria'], $data['cutoff_min'], $data['cutoff_max'], $data['top_result'], $data['test_duration'], $data['marks_for_correct'], $data['negative_marks'], $data['max_question'], $data['ques_source'], $data['visibility'], $data['last_edited']);
			
			$result = mysql_query($query, $this->db_link) or die('Insert Test Dynamic Data error : ' . mysql_error());
		}
		
		private function InsertTestScheduleData($data)
		{
			$query = sprintf("insert into test_schedule values('%s','%s','%s',%s,%s,'%s','%s','%s','%s')", $data['schd_id'], $data['test_id'], $data['scheduler_id'], !empty($data['scheduled_on'])?"'".$data['scheduled_on']."'": "NULL", !empty($data['time_zone'])?"'".$data['time_zone']."'": "NULL", $data['create_date'], $data['user_list'], $data['pnr_list'], $data['schedule_type']);
				
			$result = mysql_query($query, $this->db_link) or die('Insert Test Schedule Data error : ' . mysql_error());
		}
		
		private function InsertTestInstructionsData($data)
		{
			$query = sprintf("insert into test_instructions values('%s','%s','%s')", $data['test_id'], $data['instruction'], $data['language']);
			
			$result = mysql_query($query, $this->db_link) or die('Insert Test Instructions Data error : ' . mysql_error());
		}
		
		private function InsertQuestionData($data)
		{
			$values_ary = array();
			
			foreach($data as $row)
			{
				if($row['tag_id'] == '')
					$row['tag_id'] =-1;
					
				$value_string = sprintf("('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')", $row['ques_id'], $row['ques_type'], $row['mca'], $row['linked_to'], mysql_real_escape_string($row['group_title']), $row['language'], $row['tag_id'], $row['user_id'], mysql_real_escape_string($row['options']), mysql_real_escape_string(base64_decode($row['question'])), $row['subject_id'], $row['topic_id'], $row['difficulty_id']);
			
				array_push($values_ary, $value_string);
			}
			
			$query = sprintf("insert into question values %s", implode(",", $values_ary));
				
			$result = mysql_query($query, $this->db_link) or die('Insert Question Data error : ' . mysql_error());
		}
		
		private function InsertRCParaData($data)
		{
			$values_ary = array();
			
			foreach($data as $row)
			{
				$value_string = sprintf("('%s','%s')", $row['rc_id'], mysql_real_escape_string(base64_decode($row['description'])));
					
				array_push($values_ary, $value_string);
			}
			
			$query = sprintf("insert into rc_para values %s", implode(",", $values_ary));
			
			$result = mysql_query($query, $this->db_link) or die('Insert RC Para Data error : ' . mysql_error());
		}
		
		private function InsertDirectionsParaData($data)
		{
			$values_ary = array();
				
			foreach($data as $row)
			{
				$value_string = sprintf("('%s','%s')", $row['directions_id'], mysql_real_escape_string(base64_decode($row['description'])));
					
				array_push($values_ary, $value_string);
			}
				
			$query = sprintf("insert into directions_para values %s", implode(",", $values_ary));
				
			$result = mysql_query($query, $this->db_link) or die('Insert Directions Para Data error : ' . mysql_error());
		}
		
		private function IsUserAlreadyInserted($email)
		{
			$bRet = false;
			
			$query = sprintf("select user_id from users where email='%s'", $email);
			
			$result = mysql_query($query, $this->db_link) or die('Is User Already Inserted error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$bRet = true;
			}
			return $bRet;
		}
		
		private function InsertUsersData($data, $email)
		{
			$isValidData = false;
			
			$values_ary = array();
			
			$user_id = "";
			$name = "";
			$login_name = "";
			
			$isUserAlreadyInserted = $this->IsUserAlreadyInserted($email);
			foreach($data as $row)
			{
				if($email == $row['email'] && CConfig::UT_CORPORATE)
				{
					$isValidData = true;
					$user_id = $row['user_id'];
					$name = $row['firstname']." ".$row['lastname'];
					$login_name = $row['login_name'];
				}
				
				if(($email == $row['email'] && !$isUserAlreadyInserted) || ($email != $row['email']))
				{
					$value_string = sprintf("('%s','%s','%s','%s','%s','%s','%s', '%s','%s','%s','%s','%s','%s')", mysql_real_escape_string($row['user_id']), $row['user_type'], mysql_real_escape_string($row['login_name']), mysql_real_escape_string($row['firstname']), mysql_real_escape_string($row['lastname']), mysql_real_escape_string($row['passwd']), mysql_real_escape_string($row['email']), mysql_real_escape_string($row['contact_no']), mysql_real_escape_string($row['gender']), mysql_real_escape_string($row['city']), mysql_real_escape_string($row['state']), mysql_real_escape_string($row['country']), mysql_real_escape_string($row['dob']));
					array_push($values_ary, $value_string);
				}
				
			}

			if($isValidData && !empty($values_ary))
			{
				$query = sprintf("insert into users values %s", implode(",", $values_ary));
					
				$result = mysql_query($query, $this->db_link) or die('Insert Users Data error : ' . mysql_error());
				
				if(!$isUserAlreadyInserted)
				{
					CSessionManager::Set(CSessionManager::STR_USER_ID, $user_id) ;
					CSessionManager::Set(CSessionManager::INT_USER_TYPE, CConfig::UT_CORPORATE) ;
					CSessionManager::Set(CSessionManager::STR_USER_NAME, $name) ;
					CSessionManager::Set(CSessionManager::STR_LOGIN_NAME, $login_name);
				}
			}
			return $isValidData;
		}
		
		private function InsertCountriesData()
		{
			$query = sprintf("INSERT INTO `countries` (`code`, `name`) VALUES (2, 'Afghanistan'),(3, 'Albania'),(4, 'Algeria'),(5, 'American Samoa'),(6, 'Andorra'),(7, 'Angola'),(8, 'Anguilla'),(9, 'Antigua and Barbuda'),(10, 'Argentina'),(11, 'Armenia'),(12, 'Ascension Island'),(13, 'Australia'),(14, 'Austria'),(15, 'Azerbaijan'),(16, 'Bahamas'),(17, 'Bahrain'),(18, 'Bangladesh'),(19, 'Barbados'),(20, 'Belarus'),(21, 'Belgium'),(22, 'Belize'),(23, 'Benin'),(24, 'Bermuda'),(25, 'Bhutan'),(26, 'Bolivia'),(27, 'Bosnia and Herzegovina'),(28, 'Botswana'),(29, 'Brazil'),(30, 'British Indian Ocean Territory'),(31, 'Brunei Darussalam'),(32, 'Bulgaria'),(33, 'Burkina Faso'),(34, 'Burundi'),(35, 'Cambodia'),(36, 'Cameroon'),(1, 'Canada'),(37, 'Cape Verde'),(38, 'Cayman Islands'),(39, 'Central African Republic'),(40, 'Chad'),(41, 'Chile'),(42, 'China'),(43, 'Colombia'),(44, 'Comoros'),(45, 'Congo'),(46, 'Cook Islands'),(47, 'Costa Rica'),(48, 'Cote D Ivoire'),(49, 'Croatia'),(50, 'Cuba'),(51, 'Cyprus'),(52, 'Czech Republic'),(53, 'Denmark'),(54, 'Djibouti'),(55, 'Dominica'),(56, 'Dominican Republic'),(57, 'Ecuador'),(58, 'Egypt'),(59, 'El Salvador'),(60, 'Equatorial Guinea'),(61, 'Eritrea'),(62, 'Estonia'),(63, 'Ethiopia'),(64, 'Falkland Islands'),(65, 'Faroe Islands'),(66, 'Federated States of Micronesia'),(67, 'Fiji'),(68, 'Finland'),(69, 'France'),(70, 'French Guiana'),(71, 'French Polynesia'),(72, 'Gabon'),(73, 'Georgia'),(74, 'Germany'),(75, 'Ghana'),(76, 'Gibraltar'),(77, 'Greece'),(78, 'Greenland'),(79, 'Grenada'),(80, 'Guadeloupe'),(81, 'Guam'),(82, 'Guatemala'),(83, 'Guinea'),(84, 'Guinea Bissau'),(85, 'Guyana'),(86, 'Haiti'),(87, 'Honduras'),(88, 'Hong Kong'),(89, 'Hungary'),(90, 'Iceland'),(92, 'Indonesia'),(93, 'Iran'),(94, 'Iraq'),(95, 'Ireland'),(96, 'Isle of Man'),(97, 'Israel'),(98, 'Italy'),(99, 'Jamaica'),(100, 'Japan'),(101, 'Jordan'),(102, 'Kazakhstan'),(103, 'Kenya'),(104, 'Kiribati'),(105, 'Korea (Peoples Republic of)'),(106, 'Korea (Republic of)'),(107, 'Kuwait'),(108, 'Kyrgyzstan'),(109, 'Laos'),(110, 'Latvia'),(111, 'Lebanon'),(112, 'Lesotho'),(113, 'Liberia'),(114, 'Libya'),(115, 'Liechtenstein'),(116, 'Lithuania'),(117, 'Luxembourg'),(118, 'Macau'),(119, 'Macedonia'),(120, 'Madagascar'),(121, 'Malawi'),(122, 'Malaysia'),(123, 'Maldives'),(124, 'Mali'),(125, 'Malta'),(126, 'Marshall Islands'),(127, 'Martinique'),(128, 'Mauritius'),(129, 'Mayotte'),(130, 'Mexico'),(131, 'Moldova'),(132, 'Monaco'),(133, 'Mongolia'),(134, 'Montenegro'),(135, 'Montserrat'),(136, 'Morocco'),(137, 'Mozambique'),(138, 'Myanmar'),(139, 'Namibia'),(140, 'Nauru'),(141, 'Nepal'),(142, 'Netherlands'),(143, 'Netherlands Antilles'),(144, 'New Caledonia'),(145, 'New Zealand'),(146, 'Nicaragua'),(147, 'Niger'),(148, 'Nigeria'),(149, 'Niue'),(150, 'Norfolk Island'),(151, 'Northern Mariana Islands'),(152, 'Norway'),(153, 'Oman'),(154, 'Pakistan'),(155, 'Palau'),(156, 'Panama'),(157, 'Papua New Guinea'),(158, 'Paraguay'),(159, 'Peru'),(160, 'Philippines'),(161, 'Pitcairn'),(162, 'Poland'),(163, 'Portugal'),(164, 'Puerto Rico'),(165, 'Qatar'),(166, 'Reunion'),(167, 'Romania'),(168, 'Russian Federation'),(169, 'Rwanda'),(170, 'Saint Vincent and the Grenadines'),(171, 'San Marino'),(172, 'Sao Tome and Principe'),(173, 'Saudi Arabia'),(174, 'Senegal'),(175, 'Serbia'),(176, 'Seychelles'),(177, 'Sierra Leone'),(178, 'Singapore'),(179, 'Slovakia'),(180, 'Slovenia'),(181, 'Solomon Islands'),(182, 'Somalia'),(183, 'South Africa'),(184, 'South Georgia'),(185, 'Spain'),(186, 'Sri Lanka'),(187, 'St. Kitts and Nevis'),(188, 'St. Lucia'),(189, 'St. Pierre and Miquelon'),(190, 'Sudan'),(192, 'Swaziland'),(193, 'Sweden'),(194, 'Switzerland'),(195, 'Syrian Arab Republic'),(196, 'Taiwan'),(197, 'Tajikistan'),(198, 'Tanzania'),(199, 'Thailand'),(200, 'The Gambia'),(201, 'Togo'),(202, 'Tokelau'),(203, 'Tonga'),(204, 'Trinidad and Tobago'),(205, 'Tunisia'),(206, 'Turkey'),(207, 'Turkmenistan'),(208, 'Turks and Caicos Islands'),(209, 'Tuvalu'),(210, 'Uganda'),(211, 'Ukraine'),(212, 'United Arab Emirates'),(213, 'United Kingdom'),(0, 'United States'),(214, 'Uruguay'),(215, 'Uzbekistan'),(216, 'Vanuatu'),(217, 'Venezuela'),(218, 'Viet Nam'),(219, 'Virgin Islands (U.K.)'),(220, 'Virgin Islands (U.S.)'),(221, 'Wallis and Futuna Islands'),(222, 'Western Samoa'),(223, 'Yemen'),(224, 'Yugoslavia'),(225, 'Zaire'),(226, 'Zambia'),(227, 'Zimbabwe'),(91, 'India');");
		
			$result = mysql_query($query, $this->db_link) or die('Insert Countries Data error : ' . mysql_error());
		}
		
		public function UploadNewTest($test_data, $email)
		{
			$this->CleanExistingTest();
			
			$isValidData = $this->InsertUsersData($test_data['users'], $email);
			
			$test_name = "";
			if($isValidData)
			{
				$test_name = $this->InsertTestData($test_data['test']);
					
				$this->InsertTestDynamicData($test_data['test_dynamic']);
				$this->InsertTestScheduleData($test_data['test_schedule']);
				if(!empty($test_data['test_instructions']))
				{
					$this->InsertTestInstructionsData($test_data['test_instructions']);
				}
				$this->InsertQuestionData($test_data['question']);
					
				if(!empty($test_data['rc_para']))
				{
					$this->InsertRCParaData($test_data['rc_para']);
				}
					
				if(!empty($test_data['directions_para']))
				{
					$this->InsertDirectionsParaData($test_data['directions_para']);
				}	
				
				$this->InsertCountriesData();
			}
			
			return $test_name;
		}
	}
?>