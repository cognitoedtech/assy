<?php 
include_once(dirname(__FILE__)."/../database/config.php");

class CCreateDB
{
	private $db_link;
	
	private function CreateTableDirectionsPara()
	{
		$query = sprintf("CREATE TABLE IF NOT EXISTS `directions_para` (  `directions_id` bigint(20) NOT NULL,  `description` longblob NOT NULL,  PRIMARY KEY (`directions_id`)) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
	
		$result = mysql_query($query, $this->db_link) or die('Create Table Directions Para error : ' . mysql_error());
	}
	
	private function CreateQuestionTable()
	{
		$query = sprintf("CREATE TABLE IF NOT EXISTS `question` (  `ques_id` int(10) NOT NULL,  `ques_type` tinyint(4) NOT NULL,  `mca` tinyint(4) NOT NULL,  `linked_to` bigint(20) NOT NULL,  `group_title` varchar(256) NOT NULL,  `language` varchar(64) NOT NULL,  `tag_id` bigint(20) DEFAULT NULL,  `user_id` varchar(40) DEFAULT NULL,  `options` longblob NOT NULL,  `question` longblob,  `subject_id` varchar(10) DEFAULT NULL,  `topic_id` varchar(10) DEFAULT NULL,  `difficulty_id` varchar(10) DEFAULT NULL,  PRIMARY KEY (`ques_id`),  KEY `subject_id` (`subject_id`),  KEY `difficulty_id` (`difficulty_id`),  KEY `topic_id` (`topic_id`),  KEY `user_id` (`user_id`)) ENGINE=MyISAM DEFAULT CHARSET=latin1;");
		
		$result = mysql_query($query, $this->db_link) or die('Create Question Table error : ' . mysql_error());
	}
	
	private function CreateRCParaTable()
	{
		$query = sprintf("CREATE TABLE IF NOT EXISTS `rc_para` (  `rc_id` bigint(20) NOT NULL,  `description` longblob NOT NULL,  PRIMARY KEY (`rc_id`)) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
		
		$result = mysql_query($query, $this->db_link) or die('Create RC Para Table error : ' . mysql_error());
	}
	
	private function CreateResultTable()
	{
		$query = sprintf("CREATE TABLE IF NOT EXISTS `result` (  `test_pnr` varchar(40) NOT NULL,  `tschd_id` bigint(20) NOT NULL COMMENT 'Test Schedule ID',  `user_id` varchar(40) DEFAULT NULL,  `test_id` int(10) NOT NULL,  `ques_map` text NOT NULL,  `ques_id` varchar(4096) DEFAULT NULL COMMENT 'Comma saparated question ids',  `answers` varchar(1024) DEFAULT NULL COMMENT 'Comma saparated values of chosen answers of list of questions',  `marks` float NOT NULL COMMENT 'Marks Obtained',  `section_marks` text NOT NULL,  `time_taken` float NOT NULL COMMENT 'Time taken to complete the test',  `visibility` tinyint(4) NOT NULL,  `test_date` datetime NOT NULL,  `attempt_history` text NULL,  `paid` tinyint(4) NOT NULL DEFAULT '0',  KEY `user_id` (`user_id`),  KEY `Q_id` (`ques_id`(1000))) ENGINE=MyISAM DEFAULT CHARSET=latin1;");
		
		$result = mysql_query($query, $this->db_link) or die('Create Result Table error : ' . mysql_error());
	}
	
	private function CreateTestTable()
	{
		$query = sprintf("CREATE TABLE IF NOT EXISTS `test` (  `test_id` bigint(10) NOT NULL,  `owner_id` varchar(40) NOT NULL COMMENT 'owner_id is user_id from users table.',  `test_name` varchar(64) NOT NULL,  `test_type` tinyint(4) NOT NULL DEFAULT '0',  `create_date` datetime NOT NULL,  `tag_id` bigint(20) DEFAULT NULL,  `is_static` tinyint(1) DEFAULT '0',  `is_published` tinyint(4) NOT NULL,  `mcq_type` tinyint(4) NOT NULL,  `pref_lang` varchar(64) NOT NULL,  `allow_trans` tinyint(4) NOT NULL,  `mcpa_flash_ques` tinyint(4) NOT NULL,  `mcpa_lock_ques` tinyint(4) NOT NULL,  `expire_hrs` int(11) NOT NULL DEFAULT '-1',  `attempts` int(11) NOT NULL DEFAULT '-1',  `description` text NOT NULL,  `keywords` varchar(256) NOT NULL,  `user_ratings` text,  `final_rating` double DEFAULT NULL,  `public` tinyint(4) NOT NULL DEFAULT '0',  `submitted` tinyint(4) NOT NULL DEFAULT '0',  `deleted` timestamp NULL DEFAULT NULL,  `is_started` tinyint(4) NOT NULL DEFAULT '0',  PRIMARY KEY (`test_id`),  KEY `Owner_id` (`owner_id`)) ENGINE=MyISAM DEFAULT CHARSET=latin1;");
		
		$result = mysql_query($query, $this->db_link) or die('Create Test Table error : ' . mysql_error());
	}
	
	private function CreateTestDynamicTable()
	{
		$query = sprintf("CREATE TABLE IF NOT EXISTS `test_dynamic` (  `test_id` int(10) NOT NULL,  `section_count` tinyint(2) NOT NULL,  `section_details` varchar(512) NOT NULL,  `subject_in_section` varchar(512) NOT NULL,  `topic_in_subject` text NOT NULL,  `criteria` tinyint(4) NOT NULL,  `cutoff_min` float DEFAULT NULL,  `cutoff_max` float DEFAULT NULL,  `top_result` int(5) DEFAULT NULL,  `test_duration` int(5) NOT NULL,  `marks_for_correct` float NOT NULL,  `negative_marks` float DEFAULT NULL,  `max_question` int(5) NOT NULL,  `ques_source` varchar(16) NOT NULL,  `visibility` tinyint(4) NOT NULL DEFAULT '1',  `last_edited` datetime NOT NULL,  PRIMARY KEY (`test_id`),  KEY `test_id` (`test_id`)) ENGINE=MyISAM DEFAULT CHARSET=latin1;");		
		
		$result = mysql_query($query, $this->db_link) or die('Create Test Dynamic Table error : ' . mysql_error());
	}
	
	private function CreateTestInstructionsTable()
	{
		$query = sprintf("CREATE TABLE IF NOT EXISTS `test_instructions` (  `test_id` bigint(20) NOT NULL,  `instruction` text NOT NULL,  `language` varchar(80) NOT NULL,  PRIMARY KEY (`test_id`)) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
		
		$result = mysql_query($query, $this->db_link) or die('Create Test Instructions Table error : ' . mysql_error());
	}
	
	private function CreateTestScheduleTable()
	{
		$query = sprintf("CREATE TABLE IF NOT EXISTS `test_schedule` (  `schd_id` bigint(20) NOT NULL,  `test_id` bigint(20) NOT NULL,  `scheduler_id` varchar(40) NOT NULL,  `scheduled_on` datetime DEFAULT NULL,  `time_zone` float DEFAULT NULL,  `create_date` datetime NOT NULL,  `user_list` longtext NOT NULL,  `pnr_list` longtext NOT NULL,  `schedule_type` tinyint(4) NOT NULL DEFAULT '0',  PRIMARY KEY (`schd_id`)) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
		
		$result = mysql_query($query, $this->db_link) or die('Create Test Schedule Table error : ' . mysql_error());
	}
	
	private function CreateTestSessionTable()
	{
		$query = sprintf("CREATE TABLE IF NOT EXISTS `test_session` (  `tsession_id` varchar(40) NOT NULL DEFAULT '',  `tschd_id` bigint(20) NOT NULL,  `test_id` bigint(20) NOT NULL,  `user_id` varchar(40) DEFAULT NULL,  `ques_map` text NOT NULL,  `assigned_ques_ids` varchar(4096) DEFAULT NULL,  `attempted_answers` varchar(1024) DEFAULT NULL,  `cur_chronological_time` bigint(20) NOT NULL,  `attempts_remaining` int(11) NOT NULL,  `forced_kill` tinyint(4) NOT NULL DEFAULT '0',  `session_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,  `attempt_history` text  NULL,  PRIMARY KEY (`tsession_id`),  KEY `user_id` (`user_id`)) ENGINE=MyISAM DEFAULT CHARSET=latin1;");
		
		$result = mysql_query($query, $this->db_link) or die('Create Test Session Table error : ' . mysql_error());
	}
	
	private function CreateUsersTable()
	{
		$query = sprintf("CREATE TABLE IF NOT EXISTS  `users` (  `user_id` varchar( 40  )  NOT  NULL  COMMENT  'UUID', `user_type` int( 2  )  NOT  NULL DEFAULT  '-1' COMMENT  '0: Super User, 1: Institute, 2: Corporate User, 3: Candidate, 4: Contributor, 5: Verifier, 6: Business Associate', `login_name` varchar( 16  )  NOT  NULL  COMMENT  'Alpha-numeric name, alternative for email as login name', `firstname` varchar( 100  )  NOT  NULL , `lastname` varchar( 100  )  NOT  NULL , `passwd` varchar( 32  )  NOT  NULL DEFAULT  '', `email` varchar( 100  )  NOT  NULL , `contact_no` varchar( 15  )   NULL , `gender` tinyint( 4  )  NOT  NULL , `city` varchar( 255  )  NOT  NULL , `state` varchar( 255  )  NOT  NULL , `country` varchar( 255  )  NOT  NULL , `dob` date NOT  NULL , PRIMARY  KEY (  `user_id`  )  ) ENGINE  =  MyISAM  DEFAULT CHARSET  = latin1;");
		
		$result = mysql_query($query, $this->db_link) or die('Create Users Table error : ' . mysql_error());
	}
	
	private function CreateCountriesTable()
	{
		$query = sprintf("CREATE TABLE IF NOT EXISTS  `countries` (  `code` int( 4  )  NOT  NULL , `name` varchar( 64  )  NOT  NULL  ) ENGINE  = InnoDB  DEFAULT CHARSET  = latin1;");
		
		$result = mysql_query($query, $this->db_link) or die('Create Countries Table error : ' . mysql_error());
	}
	
	private function CreateUserCVTable()
	{
		$query = sprintf("CREATE TABLE IF NOT EXISTS `user_cv` ( `user_id` varchar(40) NOT NULL,  `edu_type` int(2) NOT NULL COMMENT '1: HS, 2: SS, 3:Diploma, 4:Graduate, 5:PG, 6:PhD',  `area` varchar(64) NOT NULL,  `stream` varchar(64) NOT NULL,  `percent_cgpa` float NOT NULL,  `school_institute` varchar(128) NOT NULL,  `board_university` varchar(128) NOT NULL,  `passing_year` varchar(8) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
	
		$result = mysql_query($query, $this->db_link) or die('Create User CV Table error : ' . mysql_error());
	}
	
	public function __construct()
	{
		$this->db_link = mysql_connect(CConfig::HOST, CConfig::USER_NAME, CConfig::PASSWORD);
	}
	
	public function __destruct()
	{
		mysql_close($this->db_link);
	}
	
	public function CreateOfflineDatabase()
	{
		/*if(!$this->db_link)
		{
			$this->db_link = mysql_connect(CConfig::HOST, CConfig::USER_NAME, "");

			mysql_select_db("mysql", $this->db_link);
			
			$query = sprintf("update user set password=PASSWORD('%s') where User='%s'", CConfig::PASSWORD, CConfig::USER_NAME);
			
			$result = mysql_query($query, $this->db_link) or die('Change Root Password error : ' . mysql_error());

			$flush_query = sprintf("FLUSH PRIVILEGES");

			$flush_result = mysql_query($flush_query, $this->db_link) or die('Flush Privileges error : ' . mysql_error());

			unset($this->db_link);
		} */
		
		$this->db_link = mysql_connect(CConfig::HOST, CConfig::USER_NAME, CConfig::PASSWORD);

		$db_selected = mysql_select_db(CConfig::DB_MCAT, $this->db_link);
		
		if(!$db_selected)
		{
			$query = sprintf("CREATE DATABASE %s", CConfig::DB_MCAT);
			
			$result = mysql_query($query, $this->db_link) or die('Create Offline Database error : ' . mysql_error());
			
			if($result)
			{
				mysql_select_db(CConfig::DB_MCAT, $this->db_link);
				
				$this->CreateTableDirectionsPara();
				$this->CreateQuestionTable();
				$this->CreateRCParaTable();
				$this->CreateResultTable();
				$this->CreateTestTable();
				$this->CreateTestDynamicTable();
				$this->CreateTestInstructionsTable();
				$this->CreateTestScheduleTable();
				$this->CreateTestSessionTable();
				$this->CreateUsersTable();
				$this->CreateCountriesTable();
				$this->CreateUserCVTable();
			}
		}
	}
}
?>