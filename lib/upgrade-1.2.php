<?php
	include_once("../database/config.php");
	include_once("session_manager.php");
	include_once("utils.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$nUserType = CSessionManager::Get(CSessionManager::INT_USER_TYPE);
	
	class UpgradeDB_1_2
	{
		var $db_link;
	
		public function __construct() 
		{
			$this->db_link = mysql_connect(CConfig::HOST, CConfig::USER_NAME, CConfig::PASSWORD);
			mysql_select_db(CConfig::DB_MCAT, $this->db_link);
		}
	
		public function __destruct() 
		{
			mysql_close($this->db_link);
		}
		
		private function RemoveOptionFields()
		{
			$query = sprintf("ALTER TABLE question Drop (option_1, option_2, option_3, option_4, option_5)");
			
			$result = mysql_query($query, $this->db_link) or die ('Remove Option Fields error : ' . mysql_error());
			
			return $result;
		}
		
		public function MergeOptions()
		{
			$query = sprintf("select * from question");
			
			$result = mysql_query($query, $this->db_link) or die ('Merge Options - 1 error : ' . mysql_error());
			
			while($row = mysql_fetch_array($result))
			{
				$index = 0;
				$optionsAry = array();
				
				if($row["option_1"] != "")
				{
					$optionsAry[$index]["option"] = base64_encode($row["option_1"]);
					$optionsAry[$index++]["answer"] = 0;
				}
				
				if($row["option_2"] != "")
				{
					$optionsAry[$index]["option"] = base64_encode($row["option_2"]);
					$optionsAry[$index++]["answer"] = 0;
				}
				
				if($row["option_3"] != "")
				{
					$optionsAry[$index]["option"] = base64_encode($row["option_3"]);
					$optionsAry[$index++]["answer"] = 0;
				}
				
				if($row["option_4"] != "")
				{
					$optionsAry[$index]["option"] = base64_encode($row["option_4"]);
					$optionsAry[$index++]["answer"] = 0;
				}
				
				if($row["option_5"] != "")
				{
					$optionsAry[$index]["option"] = base64_encode($row["option_5"]);
					$optionsAry[$index++]["answer"] = 0;
				}
				
				$optionsAry[ $row["answer"] - 1 ]["answer"] = 1;
				
				$query_inner = sprintf("update question set options='%s' where ques_id='%s'", 
										mysql_real_escape_string(json_encode($optionsAry)), $row["ques_id"]);
				
				//echo($query_inner."<br/>");
				
				$result_inner = mysql_query($query_inner, $this->db_link) or die ('Merge Options - 2 error : ' . mysql_error());
				//break;
				unset($optionsAry);
			}
			
			//$this->RemoveOptionFields();
		}
		
		public function SectionDetailsToJSON()
		{
			
		}
		
		public function ShowOptions($offset, $count)
		{
			$query = sprintf("select options from question where limit %d, %d", $offset, $count);
			
			$result = mysql_query($query, $this->db_link) or die ('Show Options error : ' . mysql_error());
			
			while($row = mysql_fetch_array($result))
			{
				printf("<p>Total %d: %s</p><br/>", count(json_decode($row["options"])), $row["options"]);
			}
			
			mysql_free_result($result);
		}
		
		public function ResultMergeQuesAndAns()
		{
			$query = sprintf("select * from result");
			
			$result = mysql_query($query, $this->db_link) or die ('Result Merge Ques And Ans error - 1: ' . mysql_error());
			
			while($row = mysql_fetch_array($result))
			{
				$ques_map = array();
				$quesIdAry = explode(",", $row['ques_id']);
				$answerAry = explode(",", $row['answers']);
				
				foreach ($quesIdAry as $key => $QuesID)
				{
					if(!empty($QuesID))
					{
						$ques_map[$QuesID] = array($answerAry[$key]);
					}
				}
				
				/*echo("<pre>");
				print_r($ques_map);
				echo("</pre>");*/
				$query_inner = sprintf("update result set ques_map='%s' where test_pnr='%s'", 
										mysql_real_escape_string(json_encode($ques_map)), $row["test_pnr"]);
				
				//echo($query_inner."<br/>");
				$result_inner = mysql_query($query_inner, $this->db_link) or die ('Result Merge Ques And Ans error - 2: ' . mysql_error());
				unset($ques_map);
			}
		}
		
		public function TestSessionMergeQuesAndAns()
		{
			$query = sprintf("select * from test_session");
			
			$result = mysql_query($query, $this->db_link) or die ('Test Session Merge Ques And Ans error - 1: ' . mysql_error());
			
			while($row = mysql_fetch_array($result))
			{
				$ques_map = array();
				$quesIdAry = explode(",", $row['assigned_ques_ids']);
				$answerAry = explode(",", $row['attempted_answers']);
				
				foreach ($quesIdAry as $key => $QuesID)
				{
					if(!empty($QuesID))
					{
						$ques_map[$QuesID] = array($answerAry[$key]);
					}
				}
				
				/*echo("<pre>");
				print_r($ques_map);
				echo("</pre>");*/
				$query_inner = sprintf("update test_session set ques_map='%s' where tsession_id='%s'", 
										mysql_real_escape_string(json_encode($ques_map)), $row["tsession_id"]);
				
				//echo($query_inner."<br/>");
				$result_inner = mysql_query($query_inner, $this->db_link) or die ('Test Session Merge Ques And Ans error - 2: ' . mysql_error());
				unset($ques_map);
			}
		}
		
		public function UpdateTestDynamicSectionDetails()
		{
			$query = sprintf("select * from test_dynamic");
			
			$result = mysql_query($query, $this->db_link) or die ('Update Test Dynamic Section Details error: -1' . mysql_error());
			
			while($row = mysql_fetch_array($result))
			{
				$secAry = explode(';', $row['section_details']);
					
				$updatedSecAry = array();
				foreach ($secAry as $section)
				{
					if(!empty($section))
					{
						$params = split('[#(,,,)]', $section);
						
						if(count($params) == 2)
						{
							array_push($updatedSecAry,$params[0]."#".$params[1]."(0,100,".$row['marks_for_correct'].",".$row['negative_marks'].")");
						}
					}
				}
				if(!empty($updatedSecAry))
				{
					$query_inner = sprintf("update test_dynamic set section_details = '%s' where test_id = '%s'", implode(";",$updatedSecAry), $row['test_id']);
					
					$result_inner = mysql_query($query_inner, $this->db_link) or die ('Update Test Dynamic Section Details error: -2' . mysql_error());
				}
			}
		}
		
		private function ParseTestDynamicSectionDetails($section_details)
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
		
		private function GetTestDynamicSectionDetails($test_id)
		{
			$retAry = null;
				
			$query = sprintf("select * from test_dynamic where test_id='%s'", $test_id);
				
			$result = mysql_query($query, $this->db_link) or die ('Get Test Dynamic Section Details error: ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				
				$retAry = $this->ParseTestDynamicSectionDetails($row['section_details']);
			}
			
			return $retAry;
		}
		
		public function UpdateResultSectionMarks()
		{
			$query = sprintf("select * from result where section_marks is null or section_marks=''");
				
			$result = mysql_query($query, $this->db_link) or die ('Update Result Section Marks error: -1' . mysql_error());
			
			while($row = mysql_fetch_array($result))
			{
				/*echo(mysql_num_rows($result)."<br/>");
				echo("<pre>");
				print_r($row);
				echo("</pre>");*/
				
				$sec_marks = $this->CalculateResultSectionMarks($row['ques_map'], $row['test_id']);
				
				$query_inner = sprintf("update result set section_marks='%s' where test_pnr='%s'", json_encode($sec_marks), $row['test_pnr']);
				
				$result_inner = mysql_query($query_inner, $this->db_link) or die ('Update Result Section Marks error: -2' . mysql_error());
			}
		}
		
		private function GetAnswerFromOptions($options)
		{
			$AnsAry = array();
				
			$opt_ary = json_decode($options, true);
				
			$index = 0;
			foreach($opt_ary as $key => $option)
			{
				if($option['answer'] != 0)
				{
					//$AnsAry[$index++] = $option['answer'];
					$AnsAry[$index++] = $key + 1;
				}
			}
				
			return $AnsAry;
		}
		
		private function GetQuestionsByIDList($sQuesList, &$aryParticulars)
		{
			// Clean empty values from array.
			$sQuesList = implode( ",", array_filter( explode(",", $sQuesList) ) );
				
			$query = sprintf("select * from question where ques_id IN (%s)", $sQuesList);
				
			//echo $query."<br/>";
			$result = mysql_query($query, $this->db_link) or die('Get Questions By ID List error : ' . mysql_error());
				
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
		
		private function CalculateResultSectionMarks($ques_map, $test_id)
		{
		
			$ques_map = json_decode($ques_map, true);
			$qusAry = array_keys($ques_map);
			$ansAry = array_values($ques_map);
		
			$aryParticulars  = array();
			$aryCorrectAns = $this->GetQuestionsByIDList(implode(",", $qusAry), $aryParticulars);
		
			$objSecDetails = $this->GetTestDynamicSectionDetails($test_id);
		
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
					else if(count($ansAry[$key]) == 1 && (in_array(-1, $ansAry[$key]) || in_array(-2, $ansAry[$key])))
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
		
		public function UpdateQuestions()
		{
			$query = sprintf("select * from question");
			
			$result = mysql_query($query, $this->db_link) or die('Update Questions error 1: ' . mysql_error());
			
			while($row = mysql_fetch_array($result))
			{
				if(CUtils::getMimeType($row['question']) == "application/octet-stream")
				{
					$updated_question = "";
					$updated_explanation = "";
					if(strpos($row['question'], 'class="code"') !== false)
					{
						$updated_question = str_replace('class="code"', 'class="mipcat_code_ques"', $row['question']);
					}
					else if(strpos($row['question'], "class='code'") !== false)
					{
						$updated_question = str_replace("class='code'", 'class="mipcat_code_ques"', $row['question']);
					}
					
					if(!empty($updated_question))
					{
						$query_inner1 = sprintf("update question set question='%s' where ques_id='%s'", mysql_real_escape_string($updated_question), $row['ques_id']);
						
						//echo $query_inner1."<br />";
						
						$result_inner1 =  mysql_query($query_inner1, $this->db_link) or die('Update Questions error 2: ' . mysql_error());
					}
					
					if(strpos($row['explanation'], 'class="code"') !== false)
					{
						$updated_explanation = str_replace('class="code"', 'class="mipcat_code_ques"', $row['explanation']);
					}
					else if(strpos($row['question'], "class='code'") !== false)
					{
						$updated_explanation = str_replace("class='code'", 'class="mipcat_code_ques"', $row['explanation']);
					}
					
					
					if(!empty($updated_explanation))
					{
						$query_inner2 = sprintf("update question set question='%s' where ques_id='%s'", mysql_real_escape_string($updated_explanation), $row['ques_id']);
					
						$result_inner2 =  mysql_query($query_inner2, $this->db_link) or die('Update Questions error 2: ' . mysql_error());
					}
				}
			}
		}
		
		private function GetQuestionTags()
		{
			$retArray = array();
		
			$query = sprintf("select DISTINCT tag from question");
		
			$result = mysql_query($query, $this->db_link);
		
			while($row = mysql_fetch_array($result))
			{
				if(!empty($row['tag']))
				{
					array_push($retArray, $row['tag']);
				}
			}
		
			return $retArray;
		}
		
		public function CreateQuestionTagTable()
		{
			$query = sprintf("create table question_tag(tag_id bigint not null unique auto_increment, tag varchar(64) not null)");
			
			$result = mysql_query($query, $this->db_link);
		}
		
		public function UpdateTags()
		{
			$tags = $this->GetQuestionTags();
			
			$values = array();
			
			foreach($tags as $tag)
			{
				$query = sprintf("insert into question_tag(tag) values ('%s')", $tag);
				
				$result = mysql_query($query, $this->db_link);
				
				$query_inner = sprintf("update question set tag = '%s' where tag = '%s'",mysql_insert_id(), $tag);
				
				$result_inner = mysql_query($query_inner, $this->db_link);
			}
			
			//echo implode(",",$values);
		}
		
		public function AlterAndUpdateQuestionTable()
		{
			$query = sprintf("ALTER TABLE `question` CHANGE `tag` `tag_id` BIGINT DEFAULT NULL");
			
			$result = mysql_query($query, $this->db_link);
			
			$query_inner = sprintf("update question set tag_id = NULL where tag_id = 0");
			
			$result_inner = mysql_query($query_inner, $this->db_link);
		}
		
		public function AlterTestTable()
		{
			$query = sprintf("ALTER TABLE test ADD tag_id BIGINT DEFAULT NULL AFTER create_date");
			
			$result = mysql_query($query, $this->db_link);
		}
		
		public function CreateBatchTable()
		{
			$query = sprintf("create table batch(batch_id bigint not null unique auto_increment, owner_id varchar(512) not null, batch_name varchar(512) not null, description varchar(2048) default null)");
				
			$result = mysql_query($query, $this->db_link);
		}
		
		public function AlterUsersTable()
		{
			$query = sprintf("ALTER TABLE users ADD batch TEXT DEFAULT NULL AFTER owner_id");
				
			$result = mysql_query($query, $this->db_link);
		}
		
		public function SetCandidateBatch()
		{
			$query = sprintf("update users set batch='%s' where user_type='%s'", json_encode(array(-1)), CConfig::UT_INDIVIDAL);
			
			$result = mysql_query($query, $this->db_link);
		}
		
		public function AlterResultTable()
		{
			$query = sprintf("ALTER TABLE `result` CHANGE `test_date` `test_date` DATETIME NOT NULL ");
			
			$result = mysql_query($query, $this->db_link);
		}
		
		public function CreateCoordinatorBillingHistoryTable()
		{
			$query = sprintf("CREATE TABLE `coordinator_billing_history` (`transaction_id` BIGINT NOT NULL AUTO_INCREMENT ,`coordinator_id` VARCHAR( 40 ) NOT NULL ,`amount` DOUBLE NOT NULL ,`xaction_type` TINYINT NOT NULL ,`xaction_timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY ( `transaction_id` )) ENGINE = InnoDB;");
		
			$result = mysql_query($query, $this->db_link);
		}
		
		public function InsertIntoCoordinarorBillingHistory()
		{
			$values = "";
			$query = sprintf("select billing.user_id, billing.balance, billing.last_edited from billing join users on billing.user_id=users.user_id where billing.balance != 0 and users.user_type='%s'", CConfig::UT_COORDINATOR);
			
			$result = mysql_query($query, $this->db_link);
			
			$i = 0;
			
			while($row = mysql_fetch_array($result))
			{
				if($i != 0)
				{
					$values .= sprintf(", ('%s','%s','%s', '%s')", $row['user_id'], $row['balance'], CConfig::CTT_RECHARGE, $row['last_edited']);
				}
				else 
				{
					$values .= sprintf("values ('%s','%s','%s', '%s')", $row['user_id'], $row['balance'], CConfig::CTT_RECHARGE, $row['last_edited']);
				}
			}
			
			if(!empty($values))
			{
				$insert_query = sprintf("insert into coordinator_billing_history(coordinator_id, amount, xaction_type, xaction_timestamp) %s", $values);
				
				$insert_result = mysql_query($insert_query, $this->db_link);
			}
		}
		
		public function AddIsPublishedInTestTable()
		{
			$query = sprintf("ALTER TABLE `test` ADD `is_published` TINYINT NOT NULL AFTER `is_static`");
			
			$result = mysql_query($query, $this->db_link);
		}
		
		public function CreateFreeUserTable()
		{
			$query = sprintf("CREATE TABLE `free_user` (`free_user_id` BIGINT NOT NULL AUTO_INCREMENT ,`email` VARCHAR( 100 ) NOT NULL ,`phone` VARCHAR( 15 ) NOT NULL ,`name` VARCHAR( 100 ) NOT NULL ,`city` VARCHAR( 255 ) NOT NULL ,PRIMARY KEY ( `free_user_id` )) ENGINE = InnoDB;");
			
			$result = mysql_query($query, $this->db_link);
		}
		
		public function FreeUserTestTable()
		{
			$query = sprintf("CREATE TABLE `free_user_test` (`free_user_id` BIGINT NOT NULL ,`test_id` BIGINT NOT NULL ,`test_pnr` VARCHAR( 40 ) NOT NULL ,`organization_id` VARCHAR( 40 ) NOT NULL) ENGINE = InnoDB;");
			
			$result = mysql_query($query, $this->db_link);
		}
		
		public function AlterFreeUserTestTable()
		{
			$query = sprintf("ALTER TABLE  `free_user_test` ADD `last_updated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `organization_id` ");
			
			$result = mysql_query($query, $this->db_link);
		}
		
		public function AlterFreeUserTable()
		{
			$query = sprintf("ALTER TABLE  `free_user` ADD `owner_org_ids` TEXT DEFAULT NULL AFTER `city` ");
			
			$result = mysql_query($query, $this->db_link);
		}
		
		public function AddTestRatingFields()
		{
			$query = sprintf("ALTER TABLE `test` ADD `user_ratings` TEXT NULL DEFAULT NULL AFTER `keywords` , ADD `final_rating` DOUBLE NULL DEFAULT NULL AFTER `user_ratings`");
			
			$result = mysql_query($query, $this->db_link);
		}
		
		public function UpdateFreeUserTest()
		{
			$query = sprintf("update `free_user_test`,result set free_user_test.last_updated = result.test_date where free_user_test.test_pnr = result.test_pnr");
			
			$result = mysql_query($query, $this->db_link);
		}
		
		public function CreateTableFreeUserBillingHistory()
		{
			$query = sprintf("CREATE TABLE `free_user_billing_history` ( `transaction_id` BIGINT NOT NULL AUTO_INCREMENT , `user_id` VARCHAR( 40 ) NOT NULL , `no_of_candidates` INT NOT NULL , `amount` DOUBLE NOT NULL , `xaction_timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY ( `transaction_id` ) ) ENGINE = InnoDB;");
		
			$result = mysql_query($query, $this->db_link);
		}
		
		public function AlterTableTestScheduleForScheduledOnNull()
		{
			$query = sprintf("ALTER TABLE `test_schedule` CHANGE `scheduled_on` `scheduled_on` DATETIME NULL DEFAULT NULL");
			
			$result = mysql_query($query, $this->db_link);
		}
		
		public function AlterTableTestScheduleForTimeZoneNull()
		{
			$query = sprintf("ALTER TABLE `test_schedule` CHANGE `time_zone` `time_zone` FLOAT NULL DEFAULT NULL");
				
			$result = mysql_query($query, $this->db_link);
		}
		
		public function AlterTableTestAddScheduleType()
		{
			$query = sprintf("ALTER TABLE `test_schedule` ADD `schedule_type` TINYINT NOT NULL DEFAULT '0' AFTER `pnr_list`");
		
			$result = mysql_query($query, $this->db_link);
		}
		
		public function AlterBillingTableAddRateOfflineVersion()
		{
			$query = sprintf("ALTER TABLE `billing` ADD `rate_offline_version` FLOAT NOT NULL DEFAULT '0' AFTER `rate_personal_ques`");
			
			$result = mysql_query($query, $this->db_link);
		}
		
		public function CreatePackageTable()
		{
			$query = sprintf("CREATE TABLE `package` (`package_id` BIGINT NOT NULL AUTO_INCREMENT ,`package_name` VARCHAR( 512 ) NOT NULL ,`package_test_cost` DOUBLE NOT NULL ,`result_view` INT NOT NULL ,PRIMARY KEY ( `package_id` )) ENGINE = InnoDB;");
			
			$result = mysql_query($query, $this->db_link);
		}
		
		public function CreateAssignedPackagesTestTable()
		{
			$query = sprintf("CREATE TABLE `assigned_packages` (`package_id` BIGINT NOT NULL ,`test_id` BIGINT NOT NULL ,`user_id` VARCHAR( 40 ) NOT NULL ,`cost` DOUBLE NOT NULL) ENGINE = InnoDB;");
			
			$result = mysql_query($query, $this->db_link);
		}
		
		public function AlterTestTableAddTestType()
		{
			$query = sprintf("ALTER TABLE `test` ADD `test_type` TINYINT NOT NULL DEFAULT '0' AFTER `test_name`");
			
			$result = mysql_query($query, $this->db_link);
		}

		public function CreateEQRangeAnalysisTable()
		{
			$query = sprintf("CREATE TABLE `eq_range_analysis` (`test_id` BIGINT NOT NULL ,`topic_id` BIGINT NOT NULL ,`lower_range_limit` INT NOT NULL ,higher_range_limit` INT NOT NULL ,`analysis` TEXT NOT NULL ,`summary` TEXT NULL DEFAULT NULL) ENGINE = InnoDB;");
				
			$result = mysql_query($query, $this->db_link);
		}
		
		public function CreateExpiredUserListTable()
		{
			$query = sprintf("CREATE TABLE `expired_user_list` (`test_id` bigint( 20 ) NOT NULL ,`schd_id` bigint( 20 ) NOT NULL ,`expired_users` longtext NOT NULL ,`last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP) ENGINE = InnoDB DEFAULT CHARSET = latin1;");
		
			$result = mysql_query($query, $this->db_link);
		}
		
		public function AlterTestScheduleAddExpireOn()
		{
			$query = sprintf("ALTER TABLE `test_schedule` ADD `expire_on` DATETIME NULL DEFAULT NULL AFTER `scheduled_on`");
				
			$result = mysql_query($query, $this->db_link);
		}
		
		public function CreateTableOTFAUserFormInfo()
		{
			$query = sprintf("CREATE  TABLE  `otfa_user_form_info` (  `otfa_id` bigint( 20  )  NOT  NULL  AUTO_INCREMENT , `test_id` bigint( 20  )  NOT  NULL , `tschd_id` bigint( 20  )  NOT  NULL , `batch_id` bigint( 20  )  NOT  NULL , `firstname` tinyint( 1  )  NOT  NULL , `lastname` tinyint( 1  )  NOT  NULL , `gender` tinyint( 1  )  NOT  NULL , `dob` tinyint( 1  )  NOT  NULL , `contact_no` tinyint( 1  )  NOT  NULL , `city` tinyint( 1  )  NOT  NULL , `state` tinyint( 1  )  NOT  NULL , `country` tinyint( 1  )  NOT  NULL , `edu_qualification` tinyint( 1  )  NOT  NULL , `tpin_list` longtext NOT  NULL , PRIMARY  KEY (  `otfa_id`  )  ) ENGINE  = InnoDB  DEFAULT CHARSET  = latin1;");
		
			$result = mysql_query($query, $this->db_link);
		}
		
		public function AlterUsersTableAddIsValidField()
		{
			$query = sprintf("ALTER TABLE `users` ADD `isvalid` TINYINT NOT NULL DEFAULT '1' AFTER `online`");
			
			$result = mysql_query($query, $this->db_link);
		}
	}
	

	if($nUserType == CConfig::UT_SUPER_ADMIN)
	{
		$objUP = new UpgradeDB_1_2();
		
		$objUP->AlterUsersTableAddIsValidField();
		
		//$objUP->CreateTableOTFAUserFormInfo()
		//$objUP->CreateExpiredUserListTable();
		//$objUP->AlterTestScheduleAddExpireOn();
		
		//$objUP->AlterTestTableAddTestType();
		//$objUP->CreateEQRangeAnalysisTable();
		
		
		
		/*$objUP->AlterTableTestScheduleForScheduledOnNull();
		$objUP->AlterTableTestScheduleForTimeZoneNull();
		$objUP->AlterTableTestAddScheduleType();
		$objUP->AlterBillingTableAddRateOfflineVersion();
		$objUP->CreatePackageTable();
		$objUP->CreateAssignedPackagesTestTable();*/
		
		/*$objUP->CreateFreeUserTable();
		$objUP->FreeUserTestTable();

		$objUP->AlterFreeUserTestTable();
		$objUP->AlterFreeUserTable();
		$objUP->AddTestRatingFields();
		$objUP->UpdateFreeUserTest();
		$objUP->CreateTableFreeUserBillingHistory();*/
		//$objUP->AddIsPublishedInTestTable();
		//$objUP->CreateFreeUserTable();
		//$objUP->FreeUserTestTable();
		//$objUP->CreateBatchTable();
		//$objUP->AlterUsersTable();
		//$objUP->SetCandidateBatch();
		//$objUP->AlterResultTable();
		//$objUP->CreateCoordinatorBillingHistoryTable();
		//$objUP->InsertIntoCoordinarorBillingHistory();
		//$objUP->InsertIntoQuestionTags();
		
		/*$objUP->CreateQuestionTagTable();
		 $objUP->UpdateTags();
		$objUP->AlterAndUpdateQuestionTable();
		$objUP->AlterTestTable();*/
		//$objUP->UpdateQuestions();
		
		//$objUP->SectionDetailsToJSON();
		//$objUP->MergeOptions();
		//$objUP->ResultMergeQuesAndAns();
		//$objUP->TestSessionMergeQuesAndAns();
		//$objUP->UpdateTestDynamicSectionDetails();
		//$objUP->UpdateResultSectionMarks();
		//$objUP->ShowOptions(0, 400);
	}
?>