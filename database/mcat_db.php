<?php
	include_once("config.php");
	include_once(dirname(__FILE__)."/../lib/utils.php");
	include_once(dirname(__FILE__)."/../lib/site_config.php");
	include_once(dirname(__FILE__)."/../lib/new-email.php");
	include_once(dirname(__FILE__)."/../test/lib/tbl_test_static.php");
	
	class CMcatDB
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
		
		public function GetSubjectName($subject_id)
		{
			$sSubjctName = null;
			
			$query = sprintf("select subject_name from subject where subject_id='%s'", $subject_id);
			
			$result = mysql_query($query, $this->db_link) or die('Get Subject Name error : ' . mysql_error());
			
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
			
			$result = mysql_query($query, $this->db_link) or die('Get Topic Name error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				
				$sTopicName = $row['topic_name'];
			}
			
			return $sTopicName;
		}
		
		public function GetSubjects($user_id, $tag_id = null, $lang = null, $bAll = false, $hint="", $mcq_type=null)
		{
			$query = "";
			$subject_ary = array();
			
			if($bAll)
			{
				$hint = urldecode($hint);
				$wrd_ary = explode(" ", $hint);
				$wrd_cnt = count($wrd_ary) - 1;
				
				$query = sprintf("select * from subject where ");
				
				$index = 0;
				foreach ($wrd_ary as $word)
				{
					$query .= sprintf("locate('%s', subject_name)", $word);
					
					if($index < $wrd_cnt)
					{
						$query .= " and ";
					}					
					$index++;
				}
				
				//echo $wrd_cnt." - ".$query."<br/>";
				$result = mysql_query($query, $this->db_link);
				
				$index = 0;
				while ($row = mysql_fetch_assoc($result)) 
				{
					$subject_ary[$index] = $row['subject_name'];
					
					$index++;
				}
			}
			else 
			{
				$lang_cond = "";
				if($lang != null)
				{
					$lang_cond = sprintf("and question.language='%s'", $lang);
				}
				
				$mcq_cond = "";
				if($mcq_type != null && $mcq_type == 0)
				{
					$mcq_cond = sprintf("and question.mca='%s'", $mcq_type);
				}
				
				if($user_id == null) // Public Set of Questions
				{
					//$query = sprintf("select * from subject where subject_id in (select distinct subject_id from question where public=1 %s %s)", $lang_cond, $mcq_cond);
					$query = sprintf("select distinct subject.*, COUNT(question.subject_id) as total_questions from subject join question on subject.subject_id = question.subject_id where question.public=1 %s %s %s group by question.subject_id", $lang_cond, $mcq_cond, !empty($tag_id)?"and question.tag_id='".$tag_id."'":"");
				}
				else 
				{
					$query = sprintf("select distinct subject.*, COUNT(question.subject_id) as total_questions from subject join question on subject.subject_id = question.subject_id where question.user_id='%s' %s %s %s group by question.subject_id", $user_id, !empty($tag_id)?"and question.tag_id='".$tag_id."'":"", $lang_cond, $mcq_cond);
				}
		
				//echo ($query."<br/><br/>");
				$result = mysql_query($query, $this->db_link);
				
				$index = 0;
				while ($row = mysql_fetch_assoc($result)) 
				{
					$subject_ary[$index] = array();
					array_push($subject_ary[$index], $row['subject_id'], $row['subject_name'], $row['total_questions']);
		
					$index++;
				}
			}
	
			return $subject_ary;
		}
		
		public function GetTopics($user_id, $subject_id, $tag_id=null, $lang=null, $hint="", $mcq_type=null)
		{
			$query = "";
			$topic_ary = array();
			
			if(!empty($hint))
			{
				$hint = urldecode($hint);
				$wrd_ary = explode(" ", $hint);
				$wrd_cnt = count($wrd_ary) - 1;
				
				$query = sprintf( "select * from topic where locate('#%s#', subject_ids) and ", $this->GetSubjectId($subject_id) );
				
				$index = 0;
				foreach ($wrd_ary as $word)
				{
					$query .= sprintf("locate('%s', topic_name)", $word);
					
					if($index < $wrd_cnt)
					{
						$query .= " and ";
					}					
					$index++;
				}
				
				//echo $wrd_cnt." - ".$query."<br/>";
				$result = mysql_query($query, $this->db_link);
				
				$index = 0;
				while ($row = mysql_fetch_assoc($result)) 
				{
					$topic_ary[$index] = $row['topic_name'];
					
					$index++;
				}
			}
			else 
			{
				$lang_cond = "";
				if($lang != null)
				{
					$lang_cond = sprintf("and question.language='%s'", $lang);
				}
				
				$mcq_cond = "";
				if($mcq_type != null && $mcq_type == 0)
				{
					$mcq_cond = sprintf("and question.mca='%s'", $mcq_type);
				}
				
				if($user_id == null)
				{
					//$query = sprintf("select * from topic where locate('#%s#', subject_ids) and topic_id in (select topic_id from question where public=1 %s %s)", $subject_id, $user_id, $lang_cond, $mcq_cond);
					$query = sprintf("select distinct topic.* from topic join question on topic.topic_id = question.topic_id where locate('#%s#', topic.subject_ids) and question.public=1 %s %s %s", $subject_id, $lang_cond, $mcq_cond, !empty($tag_id)? "and question.tag_id='".$tag_id."'":"");
				}
				else 
				{
					$query = sprintf("select distinct topic.* from topic join question on topic.topic_id = question.topic_id where locate('#%s#', topic.subject_ids) and question.user_id='%s' %s %s %s", $subject_id, $user_id, !empty($tag_id)? "and question.tag_id='".$tag_id."'":"", $lang_cond, $mcq_cond);
				}
				
				//echo $query."<br/><br/>";
				$result = mysql_query($query, $this->db_link);
				
				$index = 0;
				while ($row = mysql_fetch_array($result)) 
				{
					$topic_ary[$index] = array();
					array_push($topic_ary[$index], $row['topic_id'], $row['topic_name']);
		
					$index++;
				}
			}
	
			return $topic_ary;
		}
		
		public function GetPromotionalEmails($hint)
		{
			$query = "";
			$email_ary = array();
			
			if(!empty($hint))
			{
				$hint = urldecode($hint);
				$wrd_ary = explode(" ", $hint);
				$wrd_cnt = count($wrd_ary) - 1;
				
				$query = sprintf( "select email from promotional_email where ");
				
				$index = 0;
				foreach ($wrd_ary as $word)
				{
					$query .= sprintf("locate('%s', email) and deleted=0", $word);
					
					if($index < $wrd_cnt)
					{
						$query .= " and ";
					}					
					$index++;
				}
				
				//echo $wrd_cnt." - ".$query."<br/>";
				$result = mysql_query($query, $this->db_link);
				
				$index = 0;
				while ($row = mysql_fetch_assoc($result)) 
				{
					$email_ary[$index] = $row['email'];
					
					$index++;
				}
			}
			
			return $email_ary;
		}
		
		public function GetPromotionalCompany($hint)
		{
			$query = "";
			$company_ary = array();
				
			if(!empty($hint))
			{
				$hint = urldecode($hint);
				$wrd_ary = explode(" ", $hint);
				$wrd_cnt = count($wrd_ary) - 1;
		
				$query = sprintf( "select distinct organization_name from promotional_email where ");
		
				$index = 0;
				foreach ($wrd_ary as $word)
				{
					$query .= sprintf("locate('%s', organization_name) and deleted=0", $word);
						
					if($index < $wrd_cnt)
					{
						$query .= " and ";
					}
					$index++;
				}
				
				//echo $wrd_cnt." - ".$query."<br/>";
				$result = mysql_query($query, $this->db_link);
				
				$index = 0;
				while ($row = mysql_fetch_assoc($result))
				{
					$company_ary[$index] = $row['organization_name'];
						
					$index++;
				}
			}
				
			return $company_ary;
		}
		
		public function GetEmailsForPromotion($company=null)
		{
			$retArray = array();
			
			$query = sprintf("select * from promotional_email where organization_name='%s' and deleted=0", $company);
			
			$result = mysql_query($query, $this->db_link) or die('Get emails for promotion error : ' . mysql_error());
			
			while($row = mysql_fetch_array($result))
			{
				$retArray[$row['email']] = $row['name'];	
			}
			return $retArray;
		}
		
		public function UnsubscribePromotionalEmail($email)
		{
			$query = sprintf("update promotional_email set deleted=1 where email='%s'",$email);
			
			$result = mysql_query($query, $this->db_link) or die('Unsubscribe promotional email error : ' . mysql_error());
			
			return $result;
		}
		
		public function GetQuesCount($subject_id, $user_id, $topic_id, $difficulty=-1, $lang=null, $tag_id=null)
		{
			$query = "";
			
			$apndQry = ($user_id == null) ? "public=1" : ("user_id='".$user_id."'");
			
			$lang_cond = "";
			if($lang != null)
			{
				$lang_cond = sprintf("and language='%s'", $lang);
			}
			
			if($difficulty != -1)
			{
				$query = sprintf("select count(*) as count from question where subject_id='%s' and topic_id='%s' and difficulty_id='%s' and %s %s %s", $subject_id, $topic_id, $difficulty, $apndQry, $lang_cond, !empty($tag_id)? "and question.tag_id='".$tag_id."'":"");
			}
			else 
			{
				$query = sprintf("select count(*) as count from question where subject_id='%s' and topic_id='%s' and %s %s %s", $subject_id, $topic_id, $apndQry, $lang_cond, !empty($tag_id)? "and question.tag_id='".$tag_id."'":"");
			}
			
			//echo $query."<br/><br/>";
			$result = mysql_query($query, $this->db_link);
			
			$nCount = 0;
			if(mysql_num_rows($result)) 
			{
				$row = mysql_fetch_assoc($result);
				
				$nCount = $row['count'];
			}
			
			return $nCount;
		}
		
		public function IsEmailExists($email, $user_id, &$status, $batch_id)
		{
			$nRet = false;
			$query = "select * from users where email='".$email."'";
			//echo $query."<br/><br/>";
			$result = mysql_query($query, $this->db_link) or die('Select from users error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				if(strpos($row['owner_id'], $user_id) === false)
				{
					$batch_cond = "";
					if($batch_id != CConfig::CDB_ID && !empty($batch_id))
					{
						$batch_cond = sprintf(", batch = replace(batch, ']', ',%s]')", $batch_id);
					}
					$query = sprintf("update users set owner_id=concat(owner_id,'|','%s')%s where email='%s'", $user_id, $batch_cond, $email);
					$result = mysql_query($query, $this->db_link) or die('update users set owner_id error : ' . mysql_error());
					
					$status = 1;
				}
				else 
				{
					$status = 0;
				}
				$nRet = true;
			}
			return $nRet;
		}
		
		public function GetSubjectId($subject)
		{
			$nRet = null;
            $query="select subject_id from subject where subject_name='".ucwords(strtolower($subject))."'";
    
            $result = mysql_query($query, $this->db_link) or die('Get Subjec tId error : ' . mysql_error());
    
            if(mysql_num_rows($result) > 0)
            {
                $row = mysql_fetch_assoc($result);
                $nRet = $row['subject_id'];
            }
            else
            {
                $query="insert into subject(subject_name) values ('".ucwords(strtolower($subject))."')";
                mysql_query($query, $this->db_link) or die('insert error : ' . mysql_error());
    
                $nRet = $this->GetSubjectId($subject);
            }
    
            return $nRet;
		}
		
		public function PopulateSubjectComboForVerifier($subject_id)
        {
            printf("<option value=''>-- Select --</option>");
            $query = "select distinct subject_id from question join users on users.user_id=question.user_id and users.user_type=".CConfig::UT_CONTRIBUTOR." where question.public=0";
            $result = mysql_query($query, $this->db_link) or die('Error select subject combo ' . mysql_error());
           
            if(mysql_num_rows($result) > 0)
            {
                while($row = mysql_fetch_array($result))
                {
                    if($subject_id == $row['subject_id'])
                    {
                        printf("<option value='%s' selected='selected'>%s</option>", $row['subject_id'], $this->GetSubjectName($row['subject_id']));
                    }
                    else
                    {
                        printf("<option value='%s'>%s</option>", $row['subject_id'], $this->GetSubjectName($row['subject_id']));
                    }
                }
            }
        }
	
		public function GetTopicId($topic, $subject)
		{
			$nRet = null;
            $query="select subject_ids, topic_id from topic where topic_name='".ucwords(strtolower($topic))."'";
    
            $result = mysql_query($query, $this->db_link) or die('select error : ' . mysql_error());
           
            $subject_id = $this->GetSubjectId($subject);
            if(mysql_num_rows($result) > 0)
            {
                $row = mysql_fetch_assoc($result);
                $nRet = $row['topic_id'];
               
                //echo $row['subject_ids']."</br></br>";
                if(stripos($row['subject_ids'], ("#".$subject_id."#")) === FALSE)
                {
                    $new_sub_ids = $row['subject_ids'].$subject_id."#";
                    $query="update topic set subject_ids ='".$new_sub_ids."' where topic_id='".$nRet."'";
    
                    //echo "Subject IDs : ".$new_sub_ids."</br></br>";
                    mysql_query($query, $this->db_link) or die('insert into topic error : ' . mysql_error());    
                }
            }
            else
            {
                $query="insert into topic(topic_name, subject_ids) values ('".ucwords(strtolower($topic))."','#".$subject_id."#')";
                mysql_query($query, $this->db_link) or die('insert into topic error : ' . mysql_error());
               
                //echo "Topic : ".$topic."- Subject : ".$subject."</br></br>";
                $nRet = $this->GetTopicId($topic, $subject);
            }
    
            return $nRet;
		}
		
		private function GetCountryName($code)
		{
			$query = "select * from countries where code='".$code."'";
			//echo $query."<br/><br/>";
			$result = mysql_query($query, $this->db_link) or die('Select from countries error : ' . mysql_error());
			
			$row = null;
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
			}
			return $row['name'];
		}
		
		private function GetCountryCode($name)
		{
			$query = "select * from countries where name='".$name."'";
			//echo $query."<br/><br/>";
			$result = mysql_query($query, $this->db_link) or die('Select from countries error : ' . mysql_error());
			
			$row = null;
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
			}
			return $row['code'];
		}
		
		public function GetQuestionDetails($qid)
		{
			$query = "select * from question where ques_id=".$qid;
			//echo $query."<br/><br/>";
			$result = mysql_query($query, $this->db_link) or die('Select from question error : ' . mysql_error());
			
			$row = null;
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
			}
		
			$row['subject'] = $this->GetSubjectName($row['subject_id']);
			$row['topic']   = $this->GetTopicName($row['topic_id']);
			
			if(!empty($row['tag']))
			{
				$row['tag']     = $this->GetQuestionTag($row['tag_id']);
			}
			
			return $row;
		}
		public function GetQuestionIdArray($subject_id,$user_id)
		{
			$idArray = array();
			$query = NULL;
			
			if(!empty($subject_id))
			{
				$query = sprintf("select ques_id from question where subject_id=%d AND user_id IN (select user_id from users where user_type=%d) AND public=0 order by ques_id",$subject_id,CConfig::UT_CONTRIBUTOR);
			}
			else
			{
				$query = sprintf("select ques_id from question where user_id='%s' AND public=0 order by ques_id",$user_id);
			}
			$result = mysql_query($query, $this->db_link) or die('Error select question id : ' . mysql_error());
			$index = 0;
			while($row = mysql_fetch_array($result))
			{
				$idArray[$index] = $row['ques_id'];
				$index++;
			}
			return $idArray;
		}
		
		public function DeclineQuestion($ques_id,$reason_id)
		{
			$ques = $this->GetQuestionDetails($ques_id);
			$query = sprintf("insert into declined_questions(ques_id,user_id,public,question,options,subject_id,topic_id,reason_id) ");
			$query.=sprintf("values('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')",mysql_real_escape_string($ques['ques_id']),mysql_real_escape_string($ques['user_id']),mysql_real_escape_string($ques['public']),mysql_real_escape_string($ques['question']),mysql_real_escape_string($ques['option_1']),mysql_real_escape_string($ques['option_2']),mysql_real_escape_string($ques['option_3']),mysql_real_escape_string($ques['option_4']),mysql_real_escape_string($ques['answer']),mysql_real_escape_string($ques['subject_id']),mysql_real_escape_string($ques['topic_id']),mysql_real_escape_string($reason_id));
			mysql_query($query, $this->db_link) or die('Decline insert Error : ' . mysql_error());
			
			$removeQuery = sprintf("delete from question where ques_id='%s'",$ques_id);
			mysql_query($removeQuery, $this->db_link) or die('Decline delete Error : ' . mysql_error());
			
			$this->UpdateDeclineReason($ques_id,$reason_id);
		}
		
		public function AcceptQuestion($ques_id)
		{
			$query = sprintf("update question set public=1 where ques_id='%s'", $ques_id);
			
			$result = mysql_query($query, $this->db_link) or die('accept question error : ' . mysql_error());
			
			return $result;
		}
		
		public function IsMCAQuestion($ques_ary)
		{
			$retVal = 0;
				
			$comma_pos = strpos($ques_ary[CConfig::$QUES_XLS_HEADING_ARY["Answer"]], ",");
			if($comma_pos !== false)
			{
				$retVal = 1;
			}
			return $retVal;
		}
		
		private function CreateQuestionGroupTitle($ques_id, $question)
		{
			$title = "{Group_id : ".$ques_id."} ";
			 
			if(CUtils::getMimeType($question) != "application/octet-stream")
			{
				$title .= "Question in image format";
			}
			else
			{
				$ques_ary = explode(" ",$question);
				if(count($ques_ary) > 10)
				{
					for($word_index = 0; $word_index < 10; $word_index++)
					{
						$title .= $ques_ary[$word_index]." ";
					}
				}
				else
				{
					$title .= $question;
				}
			}
			return trim($title);
		}
		
		public function GetTagId($tag)
		{
			$nRet = null;
			
			$query = sprintf("select tag_id from question_tag where tag='%s'", mysql_real_escape_string($tag));
			
			$result = mysql_query($query, $this->db_link) or die('Get Tag Id error 1: ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_assoc($result);
				$nRet = $row['tag_id'];
			}
			else 
			{
				$query_inner = sprintf("insert into question_tag(tag) values('%s')", mysql_real_escape_string($tag));
				
				$result_inner = mysql_query($query_inner, $this->db_link) or die('Get Tag Id error 2: ' . mysql_error());
				
				$nRet = mysql_insert_id();
			}
			
			return $nRet;
		}
		
		public function InsertQuestion($row, $user_id, $mca, $ques_type = 0, $group_title=NULL, $tag_id = NULL, $linked_to = NULL, $is_eq = false)
		{
			$query = "";
			if(empty($tag_id) || $tag_id == 0)
			{
				$query = sprintf("insert into question(ques_type, linked_to, language, mca, user_id, question, subject_id, topic_id, difficulty_id, explanation) values('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')", $ques_type, $linked_to, mysql_real_escape_string(strtolower($row[CConfig::$QUES_XLS_HEADING_ARY["Language"]])), $mca, $user_id, mysql_real_escape_string($row[CConfig::$QUES_XLS_HEADING_ARY["Question"]]), $this->GetSubjectId(mysql_real_escape_string($row[CConfig::$QUES_XLS_HEADING_ARY["Subject"]])), $this->GetTopicId(mysql_real_escape_string($row[CConfig::$QUES_XLS_HEADING_ARY["Topic"]]),mysql_real_escape_string($row[CConfig::$QUES_XLS_HEADING_ARY["Subject"]])), $row[CConfig::$QUES_XLS_HEADING_ARY["Difficulty"]], mysql_real_escape_string($row[CConfig::$QUES_XLS_HEADING_ARY["Explanation"]]));
			}
			else
			{
				$query = sprintf("insert into question(ques_type, linked_to, language, mca, tag_id, user_id, question, subject_id, topic_id, difficulty_id, explanation) values('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')", $ques_type, $linked_to, mysql_real_escape_string(strtolower($row[CConfig::$QUES_XLS_HEADING_ARY["Language"]])), $mca, $tag_id, $user_id, mysql_real_escape_string($row[CConfig::$QUES_XLS_HEADING_ARY["Question"]]), $this->GetSubjectId(mysql_real_escape_string($row[CConfig::$QUES_XLS_HEADING_ARY["Subject"]])), $this->GetTopicId(mysql_real_escape_string($row[CConfig::$QUES_XLS_HEADING_ARY["Topic"]]),mysql_real_escape_string($row[CConfig::$QUES_XLS_HEADING_ARY["Subject"]])), $row[CConfig::$QUES_XLS_HEADING_ARY["Difficulty"]], mysql_real_escape_string($row[CConfig::$QUES_XLS_HEADING_ARY["Explanation"]]));
			}
           
            //echo $query;
            $result =  mysql_query($query, $this->db_link) or die('Insert Question error : ' . mysql_error());
               
            $ques_id = mysql_insert_id($this->db_link);

            if(empty($group_title))
            {
            	if(CUtils::getMimeType($row[CConfig::$QUES_XLS_HEADING_ARY["Question"]]) != "application/octet-stream")
            	{
                	$group_title = $this->CreateQuestionGroupTitle($ques_id, $row[CConfig::$QUES_XLS_HEADING_ARY["Question"]]);
            	}
            	else 
            	{
            		$group_title = $this->CreateQuestionGroupTitle($ques_id, mysql_real_escape_string($row[CConfig::$QUES_XLS_HEADING_ARY["Question"]]));
            	}
            }
            $this->UpdateQuesGrpTitle($ques_id, $group_title);

            if(!$is_eq)
            {
            	$this->InsertOptions($row, $ques_id);
            }
            else 
            {
            	$this->InsertOptionsWithWeightage($row, $ques_id);
            }
               
            return $group_title;
		}
		
		public function UpdateQuesGrpTitle($ques_id, $group_title)
		{
			$query = sprintf("update question set group_title='%s' where ques_id='%s'", $group_title, $ques_id);
			 
			$result =  mysql_query($query, $this->db_link) or die('Update Ques Grp Title error : ' . mysql_error());
			 
			return $result;
		}
		
		// insert reading comprehension
		public function InsertReadComp($rc_description)
		{
			$query = sprintf("insert into rc_para(description) values('%s')", mysql_real_escape_string($rc_description));
			
			$result = mysql_query($query, $this->db_link) or die('Insert read comp error : ' . mysql_error());
			
			return mysql_insert_id($this->db_link);
		}
		
		//insert directions para
		public function InsertDirectionsPara($dir_description)
		{
			$query = sprintf("insert into directions_para(description) values('%s')", mysql_real_escape_string($dir_description));
			
			$result = mysql_query($query, $this->db_link) or die('Insert directions para error : ' . mysql_error());
			
			return mysql_insert_id();
		}
		
		public function UpdatePara($description, $para_id, $ques_type)
		{
			$table_name 	 = "rc_para";
			$para_id_type    = "rc_id";
			if($ques_type == CConfig::QT_DIRECTIONS)
			{
				$table_name 	 = "directions_para";
				$para_id_type    = "directions_id";
			}
			
			$query = sprintf("update %s set description='%s' where %s='%s'", $table_name, mysql_real_escape_string($description), $para_id_type, $para_id);
			
			$result = mysql_query($query, $this->db_link) or die('Update para error : ' . mysql_error());
		}
		
		// check if para title is already available as topic in topic table
		public function IsTopicExists($topic, $user_id, $ques_type = -1)
		{
			$retVal = false;
			
			$ques_type_cond = "";
			
			if($ques_type == CConfig::QT_NORMAL)
			{
				$ques_type_cond = sprintf("and (question.ques_type='%s' or question.ques_type='%s')", CConfig::QT_READ_COMP, CConfig::QT_DIRECTIONS);
			}
			
			$user_type = $this->GetUserType($user_id);
			
			$query = "";
			if($user_type == CConfig::UT_CONTRIBUTOR)
			{
				$query = sprintf("select * from topic join question on topic.topic_id = question.topic_id where topic.topic_name='%s' and question.public=1 %s",ucwords(strtolower($topic)), $ques_type_cond);
			}
			else
			{
				$query = sprintf("select * from topic join question on topic.topic_id = question.topic_id where topic.topic_name='%s' and question.user_id='%s' %s",ucwords(strtolower($topic)), $user_id, $ques_type_cond);
			}
			
			//echo $query;
			
			$result = mysql_query($query, $this->db_link) or die('Is Topic Exists Error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$retVal = true;
			}
			
			return $retVal;
		}
		
		public function InsertTestDecr($test_id, $keywords, $description)
		{
			$query = sprintf("update test set keywords='%s', description='%s', submitted=1 where test_id='%s'", 
								mysql_real_escape_string($keywords),
								mysql_real_escape_string($description),
								$test_id);
			
			//echo $query."<br/><br/><br/><br/>";
			mysql_query($query) or die('Update test error : ' . mysql_error());
			
			return $this->GetTestName($test_id);
		}
		
		public function InsertCandidate($row, $owner_id)
		{
			$user_id = CUtils::uuid() ;
			$login_name = uniqid();
			$query = "insert into users(user_id,owner_id,user_type,login_name,firstname,lastname,gender,dob,contact_no,email,city,state,country, batch) VALUES ('".$user_id."', '".$owner_id."',".CConfig::UT_INDIVIDAL.",'".$login_name."','".mysql_real_escape_string($row[0])."','".mysql_real_escape_string($row[1])."','".mysql_real_escape_string($row[2])."','".mysql_real_escape_string($row[3])."','".mysql_real_escape_string($row[4])."','".mysql_real_escape_string($row[5])."','".mysql_real_escape_string($row[6])."','".mysql_real_escape_string($row[7])."','".$this->GetCountryCode(mysql_real_escape_string($row[8]))."','".mysql_real_escape_string($row[9])."')";
			if(!empty($row[10]))
			{
				$query = "insert into users(user_id,owner_id,user_type,login_name,firstname,lastname,gender,dob,contact_no,email,city,state,country, batch, passwd, reg_status, isvalid) VALUES ('".$user_id."', '".$owner_id."',".CConfig::UT_INDIVIDAL.",'".$login_name."','".mysql_real_escape_string($row[0])."','".mysql_real_escape_string($row[1])."','".mysql_real_escape_string($row[2])."','".mysql_real_escape_string($row[3])."','".mysql_real_escape_string($row[4])."','".mysql_real_escape_string($row[5])."','".mysql_real_escape_string($row[6])."','".mysql_real_escape_string($row[7])."','".$this->GetCountryCode(mysql_real_escape_string($row[8]))."','".mysql_real_escape_string($row[9])."', '".mysql_real_escape_string(md5($row[10]))."', 1, 0)";
			}
			
			//echo $query."<br/><br/><br/><br/>";
			mysql_query($query) or die('Insert into question error : ' . mysql_error());
			
			return $login_name;
		}
		
		public function ValidateCellValue($cell_value, $cell_index, $owner_id, &$err_msg, &$bOwner, $batch_id)
		{
			//printf("Cell[%d]: %s<br/><br/>", $cell_index, $cell_value);
			
			$nRet = true;
			$cell_type = array("<b>First Name</b>", "<b>Last Name</b>", "<b>Gender</b>", "<b>Date of Birth</b>", "<b>Contact #</b>", "<b>E-mail</b>", "<b>City</b>", "<b>State</b>", "<b>Country</b>");
			$error_ary = array("Expecting alphabetic value (letters) for ", 
							"Expecting either 0 (female) or 1 (male) for ", 
							"Invalid date value, should be in YYYYMMDD format for ",
							"Expecting number for ",
							"Expecting valid value for ",
							"Email-ID is already registered under your ownership",
							"Email-ID is already registered with us, we have added you as administrator of user",
							"Cell is empty");
			if(($cell_index < 2 || $cell_index > 5) && $cell_index != 9)
			{
				if(!ctype_alpha(str_replace(' ', '', $cell_value)))
				{
					$nRet = false;
					$err_msg = "{Cell Value : ".$cell_value."} ".$error_ary[0].$cell_type[$cell_index];
				}
			}
			else if ($cell_index == 2)
			{
				if(!($cell_value == '0' || $cell_value == '1'))
				{
					$nRet = false;
					$err_msg = "{Cell Value : ".$cell_value."} ".$error_ary[1].$cell_type[$cell_index];
				}
			}
			else if ($cell_index == 3)
			{
				$dateAry = date_parse($cell_value);
				if($dateAry == false)
				{
					$nRet = false;
					$err_msg = "{Cell Value : ".$cell_value."} ".$error_ary[2].$cell_type[$cell_index];
				}
				else if(checkdate($dateAry['month'], $dateAry['day'], $dateAry['year']) == false)
				{
					$nRet = false;
					$err_msg = "{Cell Value : ".$cell_value."} ".$error_ary[2].$cell_type[$cell_index];
				}
			}
			else if ($cell_index == 4)
			{
				if(!ctype_digit($cell_value))
				{
					$nRet = false;
					$err_msg = "{Cell Value : ".$cell_value."} ".$error_ary[3].$cell_type[$cell_index];
				}
			}
			else if ($cell_index == 5)
			{
				if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $cell_value))
				{
					$nRet = false;
					$err_msg = "{Cell Value : ".$cell_value."} ".$error_ary[4];
				}
				else if($this->IsEmailExists($cell_value, $owner_id, $status, $batch_id))
				{
					$nRet = false;
					if($status == 0)
					{
						$err_msg = "{Cell Value : ".$cell_value."} ".$error_ary[5];
					}
					else if($status == 1)
					{
						$bOwner = true;
						$err_msg = "{Cell Value : ".$cell_value."} ".$error_ary[6];
					}
				}
			}
			
			return $nRet;
		}
		
		public function IsLoginNameExists($login_name)
		{
			$bRet = false;
			
			$query = sprintf("select email from users where login_name='%s'", $login_name);
			
			$result = mysql_query($query, $this->db_link) or die('Is Login Name Exists error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$bRet = true;
			}
			return $bRet;
		}
		
		public function PopulateQuestionsForCitation($user_id, $user_type)
		{
			$query = "";
			if($user_type == CConfig::UT_SUPER_ADMIN)
			{
				$query="select * from question";
			}
			else 
			{
				$query = sprintf("select * from question where user_id='%s' and public=0", $user_id);
			}
			
			$result = mysql_query($query, $this->db_link) or die('Select from question error : ' . mysql_error());
			
			while($row=mysql_fetch_array($result))
			{
				echo "<tr id='".$row['ques_id']."'>";
				echo "<td>".$row['question']."</td>";
				echo "<td>".$row['option_1']."</td>";
				echo "<td>".$row['option_2']."</td>";
				echo "<td>".$row['option_3']."</td>";
				echo "<td>".$row['option_4']."</td>";
				echo "<td>".$row['answer']."</td>";
				echo "<td>".$this->GetSubjectName($row['subject_id'])."</td>";
				echo "<td>".$this->GetTopicName($row['topic_id'])."</td>";
				echo "<td>".$row['difficulty_id']."</td>";
				echo "<td>".$row['explanation']."</td>";
				echo "</tr>";
			}
		}
		
		public function GetSimilarQuestions($inQuesId, $boolTop=false, $min_percent=50, $user_id=null)
        {
            $table = array();
            $ques = $this->GetQuestionDetails($inQuesId);
            $quesArray = explode(" ",preg_replace('!\s+!', ' ', trim($ques['question'])));
            $size = count($quesArray);
           
            $pairArray = array();
           
            for($i=0;$i<$size-1;$i++)
            {
                $pairArray[$i][0] = $quesArray[$i].' '.$quesArray[$i+1];
            }
           
            if(empty($user_id))
            {
                $quesQuery = sprintf("select ques_id,question from question where subject_id=%d AND topic_id=%d AND ques_id != %d AND public=1", $ques['subject_id'], $ques['topic_id'], $inQuesId);        
            }
            else
            {
                $quesQuery = sprintf("select ques_id,question from question where subject_id=%d AND topic_id=%d AND ques_id != %d AND user_id='%s'", $ques['subject_id'], $ques['topic_id'], $inQuesId,$user_id);
            }
           
            $quesResult = mysql_query($quesQuery, $this->db_link) or die('Error finding similar questions: ' . mysql_error());
           
            while($quesRow = mysql_fetch_array($quesResult))
            {
                $outQuesId = $quesRow['ques_id'];
                $question = $quesRow['question'];
                if($size < count(explode(" ",preg_replace('!\s+!', ' ', trim($question)))))
                {
                    continue;
                }
                   
                $count = 0;
                $order = 0;
                for($i=0; $i < count($pairArray); $i++)
                {
                    $pos = strpos($question,$pairArray[$i][0]);
                    if($pos !== false)
                    {
                        for($k=0;$k<$i;$k++)
                        {
                            if($pos == $pairArray[$k][1])
                            {
                                $pos = strpos($question,$pairArray[$i][0],$pos+1);
                            }
                        }
                        if($pos !== false)
                        {
                            $pairArray[$i][1] = $pos;
                            $count++;
                            if($i > 0)
                            {
                                if($pos > $pairArray[$i-1][1])
                                {
                                    $order++;
                                }
                            }
                        }
                    }
                }
                   
                if($count > ($size-1)*($min_percent/100))
                {
                    $pairPercent = $count*100/($size-1);
                    $orderPercent = $order*100/($size-2);
                    $resultPercent = ($pairPercent+$orderPercent)/2;
                    if($resultPercent >= $min_percent)
                    {
                        $table[$outQuesId] = round($resultPercent, 2);
                    }
                }
            }
           
            arsort($table);
           
            $objRet = NULL;
            if($boolTop)
            {
                $objRet = current($table);
            }
            else
            {
                $objRet = $table;
            }
           
            return $objRet;
        }
		
		public function GetQuesUsedCount($qid, $user_id=null, $paid=1)
		{
			$used_count = 0;
			$query = "";
			
			if($user_id == null)
			{
				$query = sprintf("select count(*) as used_count from result where locate('%s,', ques_id)", $qid);
			}
			else 
			{
				$query = sprintf("SELECT count( * ) AS used_count FROM result, ( SELECT ques_id FROM question WHERE public=1 AND user_id='%s' ) AS qt WHERE ( locate( concat( ',', qt.ques_id, ',' ) , result.ques_id ) OR result.ques_id LIKE concat( qt.ques_id, ',%%' )) AND paid='%s'", $user_id, $paid);
				
			}
			
			//echo $user_id." - ".$query."<br/>";
			$result = mysql_query($query, $this->db_link) or die('Get Question Used Count error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				
				$used_count = $row['used_count'];
			}
			
			return $used_count;
		}
		
		public function GetPubQuesCount($user_id)
		{
			$pub_count = 0;
			$query = sprintf("select count(*) as pub_count from question where public=1 and user_id='%s'", $user_id);
			
			//echo $query."<br/>";
			$result = mysql_query($query, $this->db_link) or die('Get Question Public Count error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				
				$pub_count = $row['pub_count'];
			}
			
			return $pub_count;
		}
		
		public function PopulateContributorQuestions($user_id)
		{
			$query = sprintf("select * from question where user_id='%s'", $user_id);
			
			$result = mysql_query($query, $this->db_link) or die('Select contributor question error : ' . mysql_error());
			
			while($row=mysql_fetch_array($result))
			{
				echo "<tr id='".$row['ques_id']."'>";
				echo "<td>".$row['question']."</td>";
				echo "<td>".$this->GetSubjectName($row['subject_id'])."</td>";
				echo "<td>".($row['public'] ? "Accepted" : "Pending")."</td>";
				echo "<td>".($row['public'] ? "Not Applicable": (100 - $this->GetSimilarQuestions($row['ques_id'], true)."%" ))."</td>";
				
				$used_count = $this->GetQuesUsedCount($row['ques_id']);
				echo "<td>".$used_count."</td>";
				
				$ques_points = floor($used_count / 40);
				echo "<td>".$ques_points." + ". $row['public']."</td>";
				echo "</tr>";
			}
		}
		
		public function GetTestUsedCount($user_type, $test_id = null)
		{
			$used_count = 0;
			$query = "";
			
			if($test_id != null)
			{
				$query = sprintf("SELECT COUNT(*) as used_count FROM result r JOIN (SELECT DISTINCT test_id FROM test_schedule s JOIN users u ON s.scheduler_id = u.user_id WHERE u.user_type='%s' and s.test_id='%s') s USING (test_id) where r.paid=1", $user_type, $test_id);
			}
			else 
			{
				$query = sprintf("SELECT COUNT(*) as used_count FROM result r JOIN (SELECT DISTINCT test_id FROM test_schedule s JOIN users u ON s.scheduler_id = u.user_id WHERE u.user_type='%s') s USING (test_id) where r.paid=1", $user_type);
			}
			
			//echo $query."<br/>";
			
			$result = mysql_query($query, $this->db_link) or die('Get Test Used Count error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				
				$used_count = $row['used_count'];
			}
			
			return $used_count;
		}
		
		public function UpdateContribPntStatus($user_id, $pub_ques_pnt, $used_ques_pnt, $inst_test_pnt, $corp_test_pnt, $total)
		{
			$query = sprintf("select * from contribution where user_id='%s'",$user_id);
			
			$result = mysql_query($query, $this->db_link) or die('Select from contribution error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$query = sprintf("update contribution set ques_upload_points='%s',ques_used_points='%s',inst_test_points='%s',corp_test_points='%s',earned_points='%s' where user_id='%s'",$pub_ques_pnt, $used_ques_pnt, $inst_test_pnt, $corp_test_pnt, $total, $user_id);
			}
			else
			{
				$query = sprintf("insert into contribution(user_id,ques_upload_points,ques_used_points,inst_test_points,corp_test_points,earned_points) values('%s','%s','%s','%s','%s','%s')",$user_id, $pub_ques_pnt, $used_ques_pnt, $inst_test_pnt, $corp_test_pnt, $total);
			}
			
			$result = mysql_query($query, $this->db_link) or die('update or insert contribution error : ' . mysql_error());
			
			return $result;
		}
		
		public function GetTotalEarnedPoints($user_id)
		{
			$total = 0;
		
			$query = sprintf("select earned_points from contribution where user_id='%s'",$user_id);
			
			$result = mysql_query($query, $this->db_link) or die('Select earned_points from contribution error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				$total = $row['earned_points'];
			}
			
			return $total;
		}
		
		public function AdjustContribEncashedPnts($user_id)
		{
			$balance = 0;
					
			$query = sprintf("select sum(points) as encashed_points from contrib_encash_history where user_id='%s'", $user_id);
			
			$result = mysql_query($query, $this->db_link) or die('Select points from contrib_encash_history error : ' . mysql_error());
		
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_assoc($result);
				
				$total = $this->GetTotalEarnedPoints($user_id);
				
				//echo $total.'<br />';
				
				if($total > 0)
				{
					$balance = $total - $row['encashed_points'];
				}
			}
			
			return $balance;
		}
		
		public function GetEncashPntsStatus($user_id)
		{
			$status = 1;
		
			$query = sprintf("select count(*) as counter, max(last_edited),status from contrib_encash_history where user_id='%s' group by user_id", $user_id);
			//echo($query."<br/>");
			$result = mysql_query($query, $this->db_link) or die('Get Encash Pnts Status error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_assoc($result);
				
				if($row['counter'] > 0)
				{								
					$status = $row['status'];
				}
			}
			
			return $status;
		}
		
		public function UpdateContribBalance($balance, $user_id)
		{
			$query = sprintf("update contribution set balance_points='%s' where user_id='%s'",$balance, $user_id);
			
			$result = mysql_query($query, $this->db_link) or die('update contribution set balance_points error : ' . mysql_error());
			
			return $result; 
		}
		
		public function InsertContribEncashRequest($user_id,$timezone,$ip_address,$encash,$status)
		{
			$query = sprintf("insert into contrib_encash_history(user_id,req_timestamp,req_timezone,req_ip_addr,points,status) values('%s',now(),'%s','%s','%s','%s')",$user_id,$timezone,$ip_address,$encash,$status);
			
			$result = mysql_query($query, $this->db_link) or die('insert into contrib_encash_history error : ' . mysql_error());
		
			return $result;
		}
		
		public function GetContribEncashHistory($user_id)
		{
			$retArray = array();
			
			$query = sprintf("select * from contrib_encash_history where user_id='%s'",$user_id);
			
			$result = mysql_query($query, $this->db_link) or die('get cotrib encash history error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$index = 0;
				
				while($row = mysql_fetch_array($result))
				{
					$retArray[$index]['transaction_id'] = $row['transaction_id'];
					$retArray[$index]['req_timestamp'] = $row['req_timestamp'];
					$retArray[$index]['req_timezone'] = $row['req_timezone'];
					$retArray[$index]['req_ip_addr'] = $row['req_ip_addr'];
					$retArray[$index]['points'] = $row['points'];
					if($row['status'] == 1)
					{
						$retArray[$index]['status'] = "Processed";
						$retArray[$index]['last_edited'] = $row['last_edited'];
						$retArray[$index]['cheque_dd_no'] = $row['cheque_dd_no'];
						$retArray[$index]['drawn_bank'] = $row['drawn_bank'];
						$retArray[$index]['cheque_dd_date'] = $row['cheque_dd_date'];
					}
					else
					{
						$retArray[$index]['status'] = "Pending";
						$retArray[$index]['last_edited'] = "Not Applicable";
						$retArray[$index]['cheque_dd_no'] = "Not Applicable";
						$retArray[$index]['drawn_bank'] = "Not Applicable";
						$retArray[$index]['cheque_dd_date'] = "Not Applicable";
					}
					$index++;
				}
			}
			return $retArray;
		}
		
		public function PopulateContributorTests($user_id)
		{
			$query = sprintf("select * from test where owner_id='%s' and submitted=1 and deleted is null", $user_id);
			
			$result = mysql_query($query, $this->db_link) or die('Select contributor test error : ' . mysql_error());
			
			while($row=mysql_fetch_array($result))
			{
				echo "<tr id='".$row['test_id']."'>";
				echo "<td>".$row['test_name']."</td>";
				echo "<td>".($row['public'] ? "Accepted" : "Pending")."</td>";
				echo "<td>".$row['description']."</td>";
				echo "<td>".$row['keywords']."</td>";
				
				$inst_used_count = $this->GetTestUsedCount(CConfig::UT_INSTITUTE, $row['test_id']);
				$corp_used_count = $this->GetTestUsedCount(CConfig::UT_CORPORATE, $row['test_id']);
				
				echo "<td>".$inst_used_count."</td>";
				echo "<td>".$corp_used_count."</td>";
				
				// 15 Points every time test was used by Institutes and 25 Points every time test was used by Corporate (per candidate)
				$test_points = ($inst_used_count * 15) + ($corp_used_count * 25);
				echo "<td>".$test_points."</td>";
				echo "</tr>";
			}
		}
		
		public function PopulateUsersByType($user_type, $time_zone)
		{
			if($user_type == CConfig::UT_CONTRIBUTOR)
			{
				$query=sprintf("select * from users where user_type=%d", $user_type);
			}
			else
			{
				$query=sprintf("select users.*, organization.* from users, organization where users.organization_id=organization.organization_id AND user_type=%d", $user_type);
			}
		
			$result = mysql_query($query, $this->db_link) or die('Select from user error : ' . mysql_error());
			
			$reset = date_default_timezone_get();
			date_default_timezone_set($this->tzOffsetToName($time_zone));
			while($row=mysql_fetch_array($result))
			{
				echo "<tr id='".$row['user_id']."'>";
				echo "<td>".$row['login_name']."</td>";
				echo "<td>".$row['firstname']."</td>";
				echo "<td>".$row['lastname']."</td>";
				echo "<td>".($row['gender']==1?"Male":"Female")."</td>";
				echo "<td>".$row['contact_no']."</td>";
				echo "<td>".$row['email']."</td>";
				echo "<td>".$row['city'].", ".$row['state'].", ".$row['country']."</td>";
				
				if($user_type != CConfig::UT_CONTRIBUTOR)
				{
					echo "<td>".$row['organization_name']." (".$row['organization_size'].")</td>";
				}
				
				echo "<td>".$row['signupdate']."</td>";
				echo "</tr>";
			}
			date_default_timezone_set($reset);
		}
		
		/*public function PopulateBAClientInfo($ba_id)
		{
			$query = sprintf("select * from users where buss_assoc_id='%s'",$ba_id);
			
			$result = mysql_query($query, $this->db_link) or die('populate BA client info error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				while($row=mysql_fetch_array($result))
				{
					$org_name = $this->GetOrganizationName($row['organization_id']);
					echo "<tr id='".$row['user_id']."'>";
					echo "<td>".$row['firstname']." ".$row['lastname']."</td>";
					echo "<td>".$org_name."</td>";
					echo "<td>".$row['email']."</td>";
					echo "<td>".$row['contact_no']."</td>";
					if(!empty($row['address']))
					{
						echo "<td>".$row['address']."</td>";
					}
					else
					{
						echo "<td>Not Available</td>";
					}
					echo "<td>".$row['city'].", ".$row['state'].", ".$row['country']."</td>";
					echo "</tr>";
				}
			}
		
		}*/
		
		public function PopulateCandidates($user_id=null, $time_zone=null, $batch_id = null)
		{
			$query = "";
			$batch_id_array = array_keys($this->GetBatches($user_id));
		
			if($user_id == null)
			{
				$query = sprintf("select * from users where user_type=%d", CConfig::UT_INDIVIDAL);
			}
			else 
			{
				$query = sprintf("select * from users where locate('%s',owner_id) AND user_type=%d AND (locate('[%s,', batch) OR locate(',%s,', batch) OR locate(',%s]', batch) OR locate('[%s]', batch))", $user_id, CConfig::UT_INDIVIDAL, $batch_id, $batch_id, $batch_id, $batch_id);
			}
			
			//echo($query."<br/>");
			
			$result = mysql_query($query, $this->db_link) or die('Select from user error : ' . mysql_error());
			
			//$reset = date_default_timezone_get();
			//date_default_timezone_set($this->tzOffsetToName($time_zone));
			$dtzone = new DateTimeZone($this->tzOffsetToName($time_zone));
			while($row=mysql_fetch_array($result))
			{
				if($batch_id == CConfig::CDB_ID && count(array_intersect($batch_id_array, json_decode($row['batch'], true))) > 0)
				{
					continue;
				}
				echo "<tr id='".$row['user_id']."'>";
				echo "<td>".$row['firstname']."</td>";
				echo "<td>".$row['lastname']."</td>";
				echo "<td>".($row['gender']==1?"Male":"Female")."</td>";
				echo "<td>".$row['dob']."</td>";
				echo "<td>".$row['contact_no']."</td>";
				echo "<td>".$row['email']."</td>";
				if(!empty($row['city']))
				{
					echo "<td>".$row['city'].", ".$row['state'].", ".$row['country']."</td>";
				}
				else 
				{
					echo "<td></td>";
				}
				echo "<td>".($row['reg_status']==1?"Activated":"Pending")."</td>";
				
				$dtime  = new DateTime($row['signupdate']);
				$dtime->setTimezone($dtzone);
				echo "<td>".$dtime->format("F d, Y [H:i:s]")."</td>";
				echo "</tr>";
			}
			//date_default_timezone_set($reset);
		}
		
		private function GetQuestion($qid)
		{
			
		}
		
		/*public function UpdateQuestion($quesDataAry)
		{
			$query = "update question set question='".mysql_real_escape_string($quesDataAry['question'])."' , option_1='".mysql_real_escape_string($quesDataAry['option_1'])."' , option_2='".mysql_real_escape_string($quesDataAry['option_2'])."' , option_3='".mysql_real_escape_string($quesDataAry['option_3'])."' , option_4='".mysql_real_escape_string($quesDataAry['option_4'])."' , answer='".mysql_real_escape_string($quesDataAry['answer'])."' , subject_id='".$this->GetSubjectId(mysql_real_escape_string($quesDataAry['subject_id']))."' , topic_id='".$this->GetTopicId(mysql_real_escape_string($quesDataAry['topic_id']),mysql_real_escape_string($quesDataAry['subject_id']))."' , difficulty_id='".mysql_real_escape_string($quesDataAry[difficulty_id])."', explanation='".mysql_real_escape_string($quesDataAry['explanation'])."' where ques_id=".$quesDataAry['ques_id'];
			
			mysql_query($query, $this->db_link) or die(mysql_error());
			return $query;
		}*/
		
		public function UpdateQuestion($data_row, $ques_id, $mca)
		{
			$query = sprintf("update question set question = '%s' , mca = '%s' where ques_id = '%s'", mysql_real_escape_string($data_row[CConfig::$QUES_XLS_HEADING_ARY["Question"]]), $mca, $ques_id);
			
			mysql_query($query, $this->db_link) or die("Update Question Error :".mysql_error());
			
			$this->InsertOptions($data_row, $ques_id);
		}
		
		private function DeleteQuestion($qid)
		{
			$query = "update question set user_id=null where ques_id='".$qid."'";
			$result = mysql_query($query, $this->db_link) or die("delete from question error".mysql_error());
			
			return $result;
		}
		
		private function DeleteTest($fp, $test_id)
		{
			$query = "update test set deleted=now() where test_id='".$test_id."'";
			$result = mysql_query($query, $this->db_link) or die("Delete from test: ".mysql_error());
						
			return $result;
		}
		
		private function DeleteUser($user_ids, $owner_id, $owner_type, $batch_id = null)
		{
			$query = "";
			
			if($batch_id == CConfig::CDB_ID)
			{
				$query = sprintf("update users set owner_id=replace(owner_id, '%s', '') where user_id in (%s) and locate('%s', owner_id)", $owner_id, $user_ids, $owner_id);
			}
			else 
			{
				$query  = sprintf("update users set owner_id=replace(owner_id, '%s', ''),batch = CASE\n", $owner_id);
				$query .= sprintf("WHEN batch like '[%s,%%' THEN replace(batch, '[%s,', '[')\n", $batch_id, $batch_id);
				$query .= sprintf("WHEN batch like '%%,%s,%%' THEN replace(batch, ',%s,', ',')\n", $batch_id, $batch_id);
				$query .= sprintf("WHEN batch like '%%,%s]' THEN replace(batch, ',%s]', ']')\n", $batch_id, $batch_id);
				$query .= sprintf("END\n");
				$query .= sprintf("where locate('%s', owner_id) and user_id in (%s)", $owner_id, $user_ids);
			}
			//fwrite($fp, $query);
			$result = mysql_query($query, $this->db_link) or die("Delete from Users: ".mysql_error());
			
			return $result;
		}
		
		public function PrepareSubjectCombo($user_id=null, $tag_id=null, $lang=null, $mcq_type=null, $subject_id=-1)
		{
			$subAry = $this->GetSubjects($user_id, $tag_id, $lang, false, null, $mcq_type);
			
			foreach ($subAry as $sub)
			{
				echo("<option value='".$sub[0]."' ".($subject_id==$sub[0]?"selected='selected'":"").">".$sub[1]." (Total Questions: ".$sub[2].")</option>");
			}
		}
		
		public function PrepareTopicCombo($user_id, $subject_id, $tag_id=null, $lang=null, $mcq_type=null, $topic_id = -1, $reconcile = 0)
		{
			$topicAry = $this->GetTopics($user_id, $subject_id, $tag_id, $lang, null, $mcq_type);
			
			//print_r($topicAry);
			foreach ($topicAry as $topic)
			{
				$nEasyCount 	= $this->GetQuesCount($subject_id, $user_id, $topic[0], 1, $lang, $tag_id);
				$nModerateCount = $this->GetQuesCount($subject_id, $user_id, $topic[0], 2, $lang, $tag_id);
				$nHardCount 	= $this->GetQuesCount($subject_id, $user_id, $topic[0], 3, $lang, $tag_id);
				$nTotal			= $nEasyCount + $nModerateCount + $nHardCount ;
				
				$topic_type_ary = $this->GetTopicType($topic[0], $user_id);
				
				$linked_to = "";
				if(!empty($topic_type_ary['linked_to']))
				{
					$linked_to = sprintf("linked_to='%s'", $topic_type_ary['linked_to']);
				}
				
				$optionsAry = array("blue" => array(), "green" => array(), "black" => array());
				$blckI = 0;
				$greenI = 0;
				$blueI = 0;
				
				if($reconcile == 1)
				{
					if($topic_type_ary['ques_type'] == CConfig::QT_NORMAL)
					{
						$optionsAry["black"][$blckI++] = sprintf("<option value='%s'>%s</option>", $topic[0], $topic[1]);
					}
					else if($topic_type_ary['ques_type'] == CConfig::QT_READ_COMP)
					{
						$optionsAry["green"][$greenI++] = sprintf("<option style='color:green;' value='%s'>%s</option>", $topic[0], $topic[1]);
					}
					else
					{
						$optionsAry["blue"][$blueI++] = sprintf("<option style='color:darkblue;' value='%s'>%s</option>", $topic[0], $topic[1]);
					}
				}
				else 
				{
					if($topic_type_ary['ques_type'] == CConfig::QT_NORMAL)
					{
						$optionsAry["black"][$blckI++] = sprintf("<option esy='%s' mod='%s' hrd='%s' value='%s' type='%s' %s %s>%s (Total:%d, E:%d, M:%d, H:%d)</option>", $nEasyCount, $nModerateCount, $nHardCount, $topic[0], $topic_type_ary['ques_type'], $linked_to, ($topic_id==$topic[0]?"selected='selected'":""), $topic[1], $nTotal, $nEasyCount, $nModerateCount, $nHardCount);
					}
					else if($topic_type_ary['ques_type'] == CConfig::QT_READ_COMP)
					{
						$optionsAry["green"][$greenI++] = sprintf("<option style='color:green;' esy='%s' mod='%s' hrd='%s' value='%s' type='%s' %s %s>%s (Total:%d, E:%d, M:%d, H:%d)</option>", $nEasyCount, $nModerateCount, $nHardCount, $topic[0], $topic_type_ary['ques_type'], $linked_to, ($topic_id==$topic[0]?"selected='selected'":""), $topic[1], $nTotal, $nEasyCount, $nModerateCount, $nHardCount);
					}
					else
					{
						$optionsAry["blue"][$blueI++] = sprintf("<option style='color:darkblue; esy='%s' mod='%s' hrd='%s' value='%s' type='%s' %s %s>%s (Total:%d, E:%d, M:%d, H:%d)</option>", $nEasyCount, $nModerateCount, $nHardCount, $topic[0], $topic_type_ary['ques_type'], $linked_to, ($topic_id==$topic[0]?"selected='selected'":""), $topic[1], $nTotal, $nEasyCount, $nModerateCount, $nHardCount);
					}	
				}
				
				foreach($optionsAry as $opt_ary)
				{
					foreach($opt_ary as $opt_to_print)
					{
						echo($opt_to_print);
					}
				}
				
				//sprintf("<option esy='%s' mod='%s' hrd='%s' value='%s' type='%s' %s %s>%s (Total:%d, E:%d, M:%d, H:%d)</option>", $nEasyCount, $nModerateCount, $nHardCount, $topic[0], $topic_type_ary['ques_type'], $linked_to, ($topic_id==$topic[0]?"selected='selected'":""), $topic[1], $nTotal, $nEasyCount, $nModerateCount, $nHardCount);
			}
		}
		
		public function GetTopicType($topic_id, $user_id)
		{
			$topic_type_ary = array();
			
			$query = "";
			if(!empty($user_id))
			{
				$query = sprintf("select linked_to, ques_type from question where topic_id='%s' and user_id='%s' limit 1", $topic_id, $user_id);
			}
			else
			{
				$query = sprintf("select linked_to, ques_type from question where topic_id='%s' and public=1 limit 1", $topic_id);
			}
			
			$result = mysql_query($query, $this->db_link) or die("Get Topic Type error: ".mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				
				$topic_type_ary['linked_to'] = $row['linked_to'];
				
				$topic_type_ary['ques_type'] = $row['ques_type'];
			}
			
			return $topic_type_ary;
		}
		
		public function AJXProcessQuestionRow($data)
		{
			$objRet = array(
				"id" => -1,
				"error" => "",
				"fieldErrors" => array(),
				"data" => array()
			);
			
			$fp = fopen('process_question.txt', 'w+');
			fwrite($fp, print_r($data, true));
			
			
			if ( !isset($data['action']) ) // Get data
			{
				
			}
			else if ( $data['action'] == "remove" ) // Remove row
			{
				$this->DeleteQuestion($data['data'][0]);			
			}
			else if ( $data['action'] == "edit" )// Edit row
			{
				$dataAry = array();
				$dataAry['ques_id'] = $data['id'];
				$dataAry = array_merge($dataAry, $data['data']);
				fwrite($fp, print_r($dataAry, true));
				fwrite($fp, $this->UpdateQuestion($dataAry));
				 
				$objRet['id'] = $data['id'];
			}
			fwrite($fp, print_r($objRet, true));
			fclose($fp);
			return $objRet;
		}
		
		public function AJXProcessTestRow($data)
		{
			$objRet = array(
				"id" => -1,
				"error" => "",
				"fieldErrors" => array(),
				"data" => array()
			);
			
			$fp = fopen('delete_test.txt', 'w+');
			fwrite($fp, print_r($data, true));
			
			if ( !isset($data['action']) ) // Get data
			{
				
			}
			else if ( $data['action'] == "remove" ) // Remove row
			{
				$this->DeleteTest($fp, $data['data'][0]);			
			}
			
			fwrite($fp, print_r($objRet, true));
			fclose($fp);
			return $objRet;
		}
		
		public function AJXProcessCandidateRow($data, $owner_id, $owner_type, $batch_id = null)
		{
			$objRet = array(
				"id" => -1,
				"error" => "",
				"fieldErrors" => array(),
				"data" => array()
			);
			
			$fp = fopen('delete_user.txt', 'w+');
			fwrite($fp, print_r($data, true));
			
			if ( !isset($data['action']) ) // Get data
			{
				
			}
			else if ( $data['action'] == "remove" ) // Remove row
			{
				$this->DeleteUser(implode(",",$data['data']), $owner_id, $owner_type, $batch_id);			
			}
			
			fwrite($fp, print_r($objRet, true));
			fclose($fp);
			return $objRet;
		}
		
		public function GetReasonForDecline($tq_id,$reason_ctg,$bRid = false)
		{
			$query = "select * from declined_reasons where ids like '".$tq_id.";%' or ids like '%;".$tq_id.";%' and reason_ctg=".$reason_ctg."";
			$result = mysql_query($query) or die('select from declined_reasons error : ' . mysql_error());
			$row = mysql_fetch_assoc($result);
			if($bRid)
			{
				return $row;
			}
			else
			{
				return $row['reason'];
			}	
		}
		
		public function GetDeclinedIds($reason_id)
		{
			$query = sprintf("select ids from declined_reasons where reason_id='%s'",$reason_id);
			$result = mysql_query($query) or die('select from declined_reasons error : ' . mysql_error());
			$row = mysql_fetch_assoc($result);
			return explode(';',$row['ids']);
		}
		
		public function UpdateDeclineReason($tq_id,$reason_id)
		{
			$query = sprintf("update declined_reasons set ids=concat(concat(ids,'%s'),';') where reason_id='%s'",$tq_id,$reason_id);
			
			$result = mysql_query($query, $this->db_link) or die('update reason error : ' . mysql_error());
			
			return $result;
		}
		
		public function PopulateDeclineReason($reason_ctg)
		{
			$query = sprintf("select reason_id,reason from declined_reasons where reason_ctg='%s'",$reason_ctg);
			
			$result = mysql_query($query, $this->db_link) or die('populate decline reason error : ' . mysql_error());
			
			while($row = mysql_fetch_array($result))
			{
				printf("<option value='%s'>%s</option>",$row['reason_id'],$row['reason']);
			}
		}
		/*public function PopulateTests($owner_id)
		{
			$query = "select test.test_id as test_id, test.test_name as test_name, test.create_date as create_date, test_dynamic.max_question as max_question, test_dynamic.ques_source as ques_source from test, test_dynamic where test.owner_id='".$owner_id."' AND test.test_id=test_dynamic.test_id and test.deleted is null";
			
			$result = mysql_query($query, $this->db_link) or die('Select from test error : ' . mysql_error());
			
			while($row = mysql_fetch_array($result))
			{
				echo "<tr id='".$row['test_id']."'>";
				echo "<td>".$row['test_name']."</td>";
				echo "<td>".$row['max_question']."</td>";
				echo "<td>".ucfirst($row['ques_source'])."</td>";
				echo "<td>".$row['create_date']."</td>";
				printf("<td><a href='javascript:' onclick=\"parent.ShowOverlay('test/test.php?test_id=%d&tschd_id=-100','st_x');\">Preview Test</a></td>", $row['test_id']);
				echo "<td><input type='button' onclick='OnTestDetails(".$row['test_id'].");' value='Test Details'/></td>";
				echo "</tr>";
			}
		}*/
		
		public function PopulateTests($owner_id, $time_zone)
		 {
			$query = sprintf("select * from test where owner_id='%s' and deleted is null", $owner_id);
				
			$result = mysql_query($query, $this->db_link) or die('Select from test error : ' . mysql_error());
			
			//$reset = date_default_timezone_get();
			$dtzone = new DateTimeZone($this->tzOffsetToName($time_zone));
			//date_default_timezone_set($this->tzOffsetToName($time_zone));
			while($row = mysql_fetch_array($result))
			{
				$test_static_ary = NULL;
				$test_dynamic_ary = NULL;
				$last_edited      = NULL;
				echo "<tr id='".$row['test_id']."'>";
				echo "<td>".$row['test_name']."</td>";
				if($row['is_static'] == CConfig::TEST_NATURE_STATIC)
				{
					$test_static_ary = $this->GetStaticTest($row['test_id']);
					
					echo "<td>Static <button class='btn btn-mini' id='".$row['test_id']."' onclick='OnTestRefresh(this);'><i class='icon-refresh'></i></button></td>";
					echo "<td>".$test_static_ary['max_question']."</td>";
					echo "<td>".(($test_static_ary['ques_source'] == "mipcat")?CConfig::SNC_SITE_NAME : "Personal")."</td>";
					$last_edited = $test_static_ary['last_edited'];
				}
				else 
				{
					$test_dynamic_ary = $this->GetDynamicTest($row['test_id']);
					
					echo "<td>Dynamic</td>";
					echo "<td>".$test_dynamic_ary['max_question']."</td>";
					echo "<td>".(($test_dynamic_ary['ques_source'] == "mipcat")?CConfig::SNC_SITE_NAME : "Personal")."</td>";
					$last_edited = $test_dynamic_ary['last_edited'];
				}
				$dtime  = new DateTime($row['create_date']);
				$dtime->setTimezone($dtzone);
				$last_edited_date_time = new DateTime($last_edited);
				$last_edited_date_time->setTimezone($dtzone);
				echo "<td>".$dtime->format("F d, Y [H:i:s]")."</td>";
				echo "<td>".$last_edited_date_time->format("Y-m-d H:i:s")."</td>";
				//echo(($row['is_static'] == CConfig::TEST_NATURE_STATIC)?"<td>".date("F d, Y [H:i:s]", strtotime($test_static_ary['last_edited']))."</td>":"<td>".date("F d, Y [H:i:s]", strtotime($test_dynamic_ary['last_edited']))."</td>");
				printf("<td><a href='javascript:' onclick=\"ShowOverlay('%s/test/test.php?test_id=%d&tschd_id=-100','st_x');\">Preview Test</a></td>", CSiteConfig::ROOT_URL, $row['test_id']);
				echo "<td><input type='button' class='btn btn-sm btn-primary' onclick='OnTestDetails(".$row['test_id'].");' value='Test Details'/></td>";
				$isChecked = ($row['is_published'] == 1)?"checked='checked'":"";
				$isHidden = ($row['is_published'] == 0)?"style=display:none;":"";
				$keywords  = !empty($row['keywords'])?$row['keywords']:"";
				$description  = !empty($row['description'])?$row['description']:"";
				echo "<td style='text-align: center;'><input type='checkbox' made_publish='0' id='".$row['test_id']."_checkbox' class='publish' test_id='".$row['test_id']."' test_name='".$row['test_name']."' onclick='OnPublish(this);' ".$isChecked."><br /><br /><input type='button' test_name='".$row['test_name']."' data-clipboard-text='".CSiteConfig::FREE_ROOT_URL."/".$row['test_id']."-".date("d")."-".substr($owner_id, 0, 2)."' class='btn btn-sm btn-success' id='".$row['test_id']."_copy' value='Copy Test Link' ".$isHidden."/><div style='display:none;'><span id='".$row['test_id']."_keywords'>".$keywords."</span><span id='".$row['test_id']."_description'>".$description."</span></div></td>";
				echo "</tr>";
			}
			//date_default_timezone_set($reset);
		}
		
		public function GetDynamicTest($test_id)
		{
			$retAry = NULL;
			
			$query = sprintf("select * from test_dynamic where test_id='%s'", $test_id);
				
			$result = mysql_query($query, $this->db_link) or die('Get Dynamic Test error : ' . mysql_error());
				
			if(mysql_num_rows($result) > 0)
			{
				$retAry = mysql_fetch_array($result);
			}
			return $retAry;
		}
		
		public function GetStaticTest($test_id)
		{
			$retAry = NULL;
				
			$query = sprintf("select * from test_static where test_id='%s'", $test_id);
		
			$result = mysql_query($query, $this->db_link) or die('Get Static Test error : ' . mysql_error());
		
			if(mysql_num_rows($result) > 0)
			{
				$retAry = mysql_fetch_array($result);
			}
			return $retAry;
		}
		
		public function PrepareTestCombo($owner_id)
		{
			//$query = "select test.*, test_dynamic.ques_source from test, test_dynamic where test_dynamic.test_id = test.test_id and test.owner_id='".$owner_id."' and test.submitted=0 and test.deleted is null";
			
			$query = sprintf("select test.*, test_dynamic.ques_source, assigned_packages.package_id from test join test_dynamic  on test.test_id=test_dynamic.test_id left join assigned_packages on test.test_id = assigned_packages.test_id  where  (test.owner_id='%s' or (test.public=1 and assigned_packages.user_id='%s')) and test.submitted=0 and test.deleted is null", $owner_id, $owner_id);
			
			$result = mysql_query($query, $this->db_link) or die('Select from test error : ' . mysql_error());
			
			$package_name_ary = array();
			if(mysql_num_rows($result) > 0)
			{
				while($row = mysql_fetch_array($result))
				{
					$class = "personal_ques_source";
					$ques_source = "Personal";
					if($row['ques_source'] == "mipcat")
					{
						$class = "mipcat_ques_source";
						$ques_source = CConfig::SNC_SITE_NAME;
					}
					if($row['public'] == 1 && $owner_id != $row['owner_id'])
					{
						if(!isset($package_name_ary[$row['package_id']]))
						{
							$package_name_ary[$row['package_id']] = $this->GetAssignedPackageName($row['package_id']);
						}
						echo "<option value='".$row['test_id']."' class='".$class."'>".$row['test_name']." (Package : ".$package_name_ary[$row['package_id']].")</option>";
					}
					else 
					{
						echo "<option value='".$row['test_id']."' class='".$class."'>".$row['test_name']." (".$ques_source.")</option>";
					}
				}
			}
			else 
			{
				echo "<option value=''>No Test Available</option>";
			}
		}
		
		public function GetAssignedPackageName($package_id)
		{
			$retVal = null;
			
			$query = sprintf("select package_name from package where package_id='%s'", $package_id);
			
			$result = mysql_query($query, $this->db_link) or die('Get Assigned Package Name : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				
				$retVal = $row["package_name"];
			}
			
			return $retVal;
		}
		
		public function IsTestAssignedFromPackage($test_id, $user_id)
		{
			$bRet = false;
			
			$query = sprintf("select package_id from assigned_packages where test_id='%s' and user_id='%s'", $test_id, $user_id);
			
			$result = mysql_query($query, $this->db_link) or die('Is Test Assigned From Package error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$bRet = true;
			}
			return $bRet;
		}
		
		/*public function PrepareAssignedPackageTestCombo($user_id)
		{
			$query = sprintf("select test.* from test, assigned_packages where assigned_packages.test_id = test.test_id and test.public=1 and test.submitted=0 and test.deleted is null and assigned_packages.user_id='%s'", $user_id);
			
			$result = mysql_query($query, $this->db_link) or die('Prepare Assigned Package Test Combo error : ' . mysql_error());
			
			while($row = mysql_fetch_array($result))
			{
				echo "<option value='".$row['test_id']."'>".$row['test_name']." (Package)</option>";
			}
		}*/
		
		public function PrepareUserCombo($owner_id)
		{
			$query = sprintf("select * from users where locate('%s', owner_id) and deleted is null and reg_status=1 order by firstname", $owner_id);
			//echo ($query."<br/>");
			$result = mysql_query($query, $this->db_link) or die('Select from user error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				while($row = mysql_fetch_array($result))
				{
					echo "<option value='".$row['user_id']."'>".$row['firstname']." ".$row['lastname']." (".$row['email'].")</option>";
				}
			}
			else 
			{
				echo "<option value=''>No User Available</option>";
			}
		}
		
		public function GetCandidateCount($owner_id)
		{
			$query = sprintf("select count(*) as total, sum(reg_status) as activated from users where locate('%s',owner_id) AND user_type=%d", $owner_id, CConfig::UT_INDIVIDAL);
			
			$result = mysql_query($query, $this->db_link) or die('Select count from user error : ' . mysql_error());
			
			$row = mysql_fetch_array($result);
			
			return $row;
		}
		
		public function GetPersonalTestCount($owner_id)
		{
			$query = sprintf("select count(*) as total from test join test_dynamic on test.test_id=test_dynamic.test_id and test_dynamic.ques_source='personal' where owner_id='%s' and deleted is null", $owner_id);
			//echo($query."<br/>");
			$result = mysql_query($query, $this->db_link) or die('Select count from test error : ' . mysql_error());
			
			$row = mysql_fetch_array($result);
			
			return $row;
		}
		
		public function PrepareCandidateList($owner_id)
		{
			$query = sprintf("select * from users where locate('".$owner_id."',owner_id) AND user_type=%d AND reg_status=1", CConfig::UT_INDIVIDAL);
			
			$result = mysql_query($query, $this->db_link) or die('Select from user error : ' . mysql_error());
			
			while($row = mysql_fetch_array($result))
			{
				echo "<option style='color:darkblue;' value='".$row['user_id']."'>".$row['firstname']." ".$row['lastname']." - (".$row['email'].")</option>";
			}
		}
		
		public function PrepareCandidateListByBatch($owner_id, $batch_ary)
		{
			$retAry = array();
			$activeCount = 0;
			$totalCount  = 0;
			$batch_id_array = array_keys($this->GetBatches($owner_id));
			
			$query = sprintf("select * from users where locate('".$owner_id."',owner_id) AND user_type=%d ORDER BY firstname ASC", CConfig::UT_INDIVIDAL);
			
			$result = mysql_query($query, $this->db_link) or die('Select from user error : ' . mysql_error());
			
			while($row = mysql_fetch_array($result))
			{
				$cand_batch_ary = json_decode($row['batch'], true);
				
				//count(array_intersect($batch_ary, $cand_batch_ary)) > 0 && $row['reg_status'] == 1 && ((count(array_intersect($batch_id_array, $batch_ary)) > 0) || (count(array_intersect($batch_id_array, $cand_batch_ary)) == 0 && in_array(-1, $batch_ary))))
				
				if(count(array_intersect($batch_ary, $cand_batch_ary)) > 0 && $row['reg_status'] == 1 && !in_array(CConfig::CDB_ID, $batch_ary))
				{
					$retAry[$row['user_id']] = "<option style='color:darkblue;' value='".$row['user_id']."'>".$row['firstname']." ".$row['lastname']." - (".$row['email'].")</option>";
					$activeCount++;
					$totalCount++;
				}
				else if(count(array_intersect($batch_ary, $cand_batch_ary)) > 1 && $row['reg_status'] == 1)
				{
					$retAry[$row['user_id']] = "<option style='color:darkblue;' value='".$row['user_id']."'>".$row['firstname']." ".$row['lastname']." - (".$row['email'].")</option>";
					$activeCount++;
					$totalCount++;
				}
				else if(count(array_intersect($batch_ary, $cand_batch_ary)) > 0 && count(array_intersect($batch_id_array, $cand_batch_ary)) == 0 && $row['reg_status'] == 1)
				{
					$retAry[$row['user_id']] = "<option style='color:darkblue;' value='".$row['user_id']."'>".$row['firstname']." ".$row['lastname']." - (".$row['email'].")</option>";
					$activeCount++;
					$totalCount++;
				}
				else if(count(array_intersect($batch_ary, $cand_batch_ary)) > 0 && $row['reg_status'] != 1)
				{
					$totalCount++;
				}
			}
			
			$retAry['active_count'] = $activeCount;
			$retAry['total_count'] = $totalCount;
			
			return $retAry;
		}
		
		public function PrepareTestList($owner_id)
		{
			$query = sprintf("select * from test join test_dynamic on test.test_id=test_dynamic.test_id and test_dynamic.ques_source='personal' where owner_id='%s' AND deleted is null", $owner_id);
			
			//echo($query."<br/>");
			
			$result = mysql_query($query, $this->db_link) or die('Select from test error : ' . mysql_error());
			
			while($row = mysql_fetch_array($result))
			{
				echo "<option style='color:darkblue;' value='".$row['test_id']."'>".$row['test_name']."</option>";
			}
		}
		
		public function InsertIntoTest($user_id, $test_name, $mcpa_flash_ques, $mcpa_lock_ques, $test_expiration, 
									$attempts, $mcq_type, $pref_lang, $allow_trans, $test_nature, $tag_id, $test_type = CConfig::TT_DEFAULT)
		{
			$nRet = FALSE;
			$query = sprintf("insert into test (owner_id, test_name, mcpa_flash_ques, mcpa_lock_ques, expire_hrs, attempts, tag_id, is_static,  mcq_type,  pref_lang, allow_trans, test_type) values ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s','%s', '%s')",$user_id, $test_name, $mcpa_flash_ques, $mcpa_lock_ques, ($test_expiration==-1)?-1:($test_expiration*24), $attempts, $tag_id, $test_nature, $mcq_type, $pref_lang, $allow_trans, $test_type);
			
			//echo $query."<br />";
			$result = mysql_query($query, $this->db_link) or die('Insert into test error : ' . mysql_error());
			
			if($result !== FALSE)
			{
				$nRet = mysql_insert_id($this->db_link);
			}
			
			//echo "ID: ".$nRet;
			return $nRet ;
		}
		
		public function InsertIntoTestDynamic($test_id, $duration,
		    							  $max_ques, $criteria, $cutoff_min,
		    							  $cutoff_max, $top, $r_marks, $w_marks,
		    							  $sec_count, $ques_source, $section_details,
		    							  $subject_in_section, $topic_in_subject, $ques_source, $visibility)
		{
			$query = sprintf("insert into test_dynamic (test_id, section_count, section_details, subject_in_section, topic_in_subject, criteria, cutoff_min, cutoff_max, top_result, test_duration, marks_for_correct, negative_marks, max_question, ques_source, visibility) values ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')", $test_id, $sec_count, $section_details, $subject_in_section, $topic_in_subject, $criteria, $cutoff_min, $cutoff_max, $top, $duration, $r_marks, $w_marks, $max_ques, $ques_source, $visibility);
			
			//echo $query."<br/>";
			
			$result = mysql_query($query, $this->db_link) or die('Insert into test dynamic error : ' . mysql_error());
			
			return $result;
		}
		
		public function InsertIntoTestStatic($user_id,$test_id, $duration,
		    							  $max_ques, $criteria, $cutoff_min,
		    							  $cutoff_max, $top, $r_marks, $w_marks,
		    							  $sec_count, $ques_source, $questions,
		    							  $section_details, $subject_in_section, 
		    							  $topic_in_subject, $ques_source, $visibility)
		{
			$query = sprintf("insert into test_static (test_id, section_count, section_details, subject_in_section, topic_in_subject, criteria, cutoff_min, cutoff_max, top_result, test_duration, marks_for_correct, negative_marks, max_question, ques_source, visibility) values ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')", $test_id, $sec_count,  $section_details, $subject_in_section, $topic_in_subject, $criteria, $cutoff_min, $cutoff_max, $top, $duration, $r_marks, $w_marks, $max_ques, $ques_source, $visibility);
			
			//echo $query."<br/>";
			
			$result = mysql_query($query, $this->db_link) or die('Insert into test static error : ' . mysql_error());
			
			$this->UpdateTestStaticQuestions($user_id, $test_id);
			
			return $result;
		}
		
		public function UpdateTestStaticQuestions($user_id, $test_id)
		{
			$objTestStatic = new CTestStatic($this->db_link);
			
			$query = sprintf("update test_static set questions = '%s'", mysql_real_escape_string($objTestStatic->GetQuestionsJSON($user_id, $test_id)));
			
			$result = mysql_query($query, $this->db_link) or die('Update Test Static Questions error : ' . mysql_error());
				
			return $result;
		}
		
		public function GetTestName($test_id)
		{
			$query = sprintf("select test_name from test where test_id='%s'", $test_id);
			
			$result = mysql_query($query, $this->db_link) or die('Get test_name error : ' . mysql_error());
			
			$row = mysql_fetch_array($result);
			
			return $row['test_name'];
		}
		
		public function GetTestOwnerID($test_id)
		{
			$RetID = null;
			$query = sprintf("select owner_id from test where test_id='%s'", $test_id);
			
			$result = mysql_query($query, $this->db_link) or die('Get Test Owner ID error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				$RetID = $row['owner_id'];
			}
			
			return $RetID;
		}
		
		public function IsTestPublished($test_id, $owner_id_hint="")
		{
			$bRet = false;
			
			$owner_id_cond = "";
			if(!empty($owner_id_hint))
			{
				$owner_id_cond = sprintf("and owner_id like '%s%%'", $owner_id_hint);
			}
			
			$query = sprintf("select is_published from test where test_id='%s' and is_published = 1 %s", $test_id, $owner_id_cond);
				
			$result = mysql_query($query, $this->db_link) or die('Is Test Published error : ' . mysql_error());
				
			if(mysql_num_rows($result) > 0)
			{
				$bRet = true;
			}
			
			return $bRet;
		}
		
		public function InsertIntoTestSchedule($test_id, $user_id, $scheduled_on, $hours, $minutes, $expire_on, $expire_hours, $expire_minutes, $candidate_list, $time_zone, $schd_type, &$schd_id)
		{
			$sTestName = $this->GetTestName($test_id);
			
			//date_default_timezone_set($this->tzOffsetToName($time_zone));
			$test_date  = date("Y-m-d", strtotime($scheduled_on));
			$test_date .= " ".$hours.":".$minutes.":00";
			//$test_date  = date( 'D, d M Y 00:00:00', strtotime($scheduled_on))." GMT";
			
			if(empty($expire_on))
			{
				$query = sprintf("insert into test_schedule (test_id, scheduler_id, scheduled_on, time_zone, user_list, schedule_type) values('%s', '%s', '%s','%s', '%s', '%s')", $test_id, $user_id, $test_date, $time_zone, $candidate_list, $schd_type);
			}
			else 
			{	
				$test_end_date = date("Y-m-d", strtotime($expire_on));
				$test_end_date .= " ".$expire_hours.":".$expire_minutes.":00";
			
				$query = sprintf("insert into test_schedule (test_id, scheduler_id, scheduled_on,expire_on, time_zone, user_list, schedule_type) values('%s', '%s', '%s', '%s', '%s', '%s', '%s')", $test_id, $user_id, $test_date, $test_end_date, $time_zone, $candidate_list, $schd_type);
			}
			
			$result = mysql_query($query, $this->db_link) or die('Insert into test schedule error : ' . mysql_error());
			
			$schd_id = mysql_insert_id($this->db_link);
			
			return $sTestName;
		}
		
		public function InsertOfflineTestSchedule($test_id, $user_id, $candidate_list)
		{
			$sTestName = $this->GetTestName($test_id);
			
			$query = sprintf("insert into test_schedule (test_id, scheduler_id, user_list, schedule_type) values('%s', '%s', '%s', '%s')", $test_id, $user_id, $candidate_list, CConfig::TST_OFFLINE);
				
			$result = mysql_query($query, $this->db_link) or die('Insert into offline test schedule error : ' . mysql_error());
			
			return $sTestName;
		}
		
		public function InsertIntoTestPackage($pkg_name, $candidate_id, $user_id, $provisioned_from, 
											$time_zone, $expire, $rate, $amnt_sold, $test_list)
		{
			date_default_timezone_set($this->tzOffsetToName($time_zone));
			$test_date  = date("Y-m-d", strtotime($provisioned_from));
			$test_date .= " 00:00:00";
			//echo $test_date."<br />";
			$create_date  = date("Y-m-d");
			$create_date .= " 00:00:00";
			
			$query = sprintf("insert into test_package (pkg_name, consumer_id, producer_id, test_ids, rate, sold_at, created_on, deploy_on, valid_for, time_zone) values('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')", $pkg_name, $candidate_id, $user_id, $test_list, $rate, $amnt_sold, $create_date, $test_date, $expire, $time_zone);
			
			//echo($query."<br/>");
			$result = mysql_query($query, $this->db_link) or die('Insert into test package error : ' . mysql_error());
		}
		
		public function LoadTest($test_id)
		{
			$query = sprintf("select * from test, test_dynamic where test.test_id='%s' AND test.test_id=test_dynamic.test_id", $test_id);
			
			$result = mysql_query($query, $this->db_link) or die('Load Test error : ' . mysql_error());
			
			$row = mysql_fetch_array($result);
			
			return $row;
		}
		
		public function GetUserIdByEmail($userInfo, $email="")
		{
			$user_id = null;
			if(empty($email))
			{
				$email = substr($userInfo, stripos($userInfo,"(")+1,-1);
			}
			
			$query = sprintf("select user_id from users where email='%s'",trim($email));
			$result = mysql_query($query, $this->db_link) or die('Error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				$user_id = $row['user_id'];
			}
			
			return $user_id;
		}
		public function GetUsers($hint)
		{
			$user_ary = array();
			$hint = urldecode($hint);
			
			$wrd_ary = explode(" ", $hint);
			$wrd_cnt = count($wrd_ary) - 1;
			
			$query = "select * from users where ";
			$index = 0;
			foreach ($wrd_ary as $word)
			{
				$query .= sprintf("(locate('%s', firstname) or locate('%s', lastname) or locate('%s', email)) AND user_id IN (select user_id from question where user_id IS NOT NULL) AND user_type='%d'", $word, $word, $word, CConfig::UT_CONTRIBUTOR);
				if($index < $wrd_cnt)
				{
					$query .= " and ";
				}	
				$index++;
			}
			//echo $wrd_cnt." - ".$query."<br/>";
			$result = mysql_query($query, $this->db_link) or die('select from users error: ' . mysql_error());
			
			$index = 0;
			while ($row = mysql_fetch_assoc($result)) 
			{
				$user_ary[$index] = $row['firstname'].' '.$row['lastname']."(".$row['email'].")";
				//echo $user_ary[$index]."<br/>";
				$index++;
			}
			return $user_ary;
		}
		
		public function GetUserType($user_id)
		{
			$query = sprintf("select user_type from users where user_id='%s'", $user_id);
			
			$result = mysql_query($query, $this->db_link) or die('Get UserType error : ' . mysql_error());
			
			$row = mysql_fetch_array($result);
			
			return $row['user_type'];	
		}
		
		public function GetUserTypeByEmail($email)
		{
			$retVal = null;
			
			$query = sprintf("select user_type from users where email='%s'", $email);
				
			$result = mysql_query($query, $this->db_link) or die('Get UserType By Email error : ' . mysql_error());

			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				$retVal = $row['user_type'];
			}
				
			return $retVal;
		}
		
		public function GetUserPANNumber($user_id)
		{
			$query = sprintf("select pan_no from users where user_id='%s'", $user_id);
			
			$result = mysql_query($query, $this->db_link) or die('Get UserPAN error : ' . mysql_error());
			
			$row = mysql_fetch_array($result);
			
			return $row['pan_no'];
		}
		
		public function SetUserPANNumber($pan_no,$user_id)
		{
			$query = sprintf("update users set pan_no='%s' where user_id='%s'",$pan_no,$user_id);
			
			$result = mysql_query($query, $this->db_link) or die('Set UserPAN error : ' . mysql_error());
			
			return $result;
		}
		
		public function GetUserName($user_id)
		{
			$query = sprintf("select firstname, lastname from users where user_id='%s'", $user_id);
			
			$result = mysql_query($query, $this->db_link) or die('Get UserName error : ' . mysql_error());
			
			$row = mysql_fetch_array($result);
			
			return $row['firstname']." ".$row['lastname'];
		}
		
		public function GetUnverifiedTestIds()
		{
			$index = 0;
			$testIdArray = array();
			$query = sprintf("select test_id from test where public=0 AND submitted=1 AND deleted is NULL");
			//echo $query;
			$result = mysql_query($query, $this->db_link) or die('select from test error: ' . mysql_error());
			while($row = mysql_fetch_array($result))
			{
				$testIdArray[$index] = $row['test_id'];
				//echo $testIdArray[$index];
				$index++;
			}
			//print_r($testIdArray);
			return $testIdArray;
		}
		
		public function DeclineTest($test_id,$reason_id)
		{
			$this->DeleteTest(NULL,$test_id);
			$this->UpdateDeclineReason($test_id,$reason_id);
		}
		
		public function AcceptTest($test_id)
		{
			$query = sprintf("update test set public=1,submitted=0 where test_id='%s'",$test_id);
			
			$result = mysql_query($query, $this->db_link) or die('accept test error : ' . mysql_error());
			
			return $result;
		}
		
		public function GetUserEmail($user_id)
		{
			$query = sprintf("select email from users where user_id='%s'", $user_id);
			
			$result = mysql_query($query, $this->db_link) or die('Get UserEmail error : ' . mysql_error());
			
			$row = mysql_fetch_array($result);
			
			return $row['email'];	
		}
		
		public function IsTestFinished2($user_id, $user_type, $user_list, $tschd_id)
		{
			$bRet = false;
			
			$query = "";
			if($user_type == CConfig::UT_INDIVIDAL)
			{
				$query = sprintf("select * from result where user_id='%s' and tschd_id='%s'", $user_id, $tschd_id);
			}
			else 
			{
				$aryUser = explode(";", $user_list);
				
				$SanitizedUSER = array();
				foreach ($aryUser as $user)
				{
					if(!empty($user))
					{
						array_push($SanitizedUSER, "'".$user."'"); 
					}
				}
				
				$userIn = implode(",", $SanitizedUSER);
				
				if(empty($userIn))
				{
					$userIn = "''";
				}
				
				$query = sprintf("select * from result where user_id in (%s) and tschd_id='%s'", $userIn, $tschd_id);
			}
			
			//echo($query."<br/>");
			$result = mysql_query($query, $this->db_link) or die('Is Test Finished 2 error : ' . mysql_error());
			
			$rowCount = mysql_num_rows($result);
			if($rowCount > 0)
			{
				if($user_type == CConfig::UT_INDIVIDAL)
				{
					$bRet = true;
				}
				else 
				{
					$bRet = (count($SanitizedUSER) <= $rowCount) ? true : false;
				}
			}
			
			return $bRet;
		}
		
		/*private function IsTestFinished($user_id, $user_type, $user_list, $pnr_list, &$opHTML)
		{
			$bRet = false;
			
			$aryUser = explode(";", $user_list);
			$aryPNR	 = explode(";", $pnr_list);
			
			//echo("<pre>");
			//print_r($aryPNR);
			//echo("</pre>");
			
			$SanitizedPNR = array();
			foreach ($aryPNR as $pnr)
			{
				if(!empty($pnr))
				{
					array_push($SanitizedPNR, "'".$pnr."'"); 
				}
			}
			
			$SanitizedUSER = array();
			foreach ($aryUser as $user)
			{
				if(!empty($user))
				{
					array_push($SanitizedUSER, "'".$user."'"); 
				}
			}
			
			$userIn = implode(",", $SanitizedUSER);
			$pnrIn = implode(",", $SanitizedPNR);
			
			if(!empty($pnrIn))
			{
				if($user_type == CConfig::UT_INDIVIDAL)
				{
					$query = sprintf("select * from result where user_id='%s' AND test_pnr in (%s)", $user_id, $pnrIn);
					
					//echo $query."<br/>";
					$result = mysql_query($query, $this->db_link) or die('Is Test Finished error : ' . mysql_error());
					
					if(mysql_num_rows($result) > 0)
					{
						//echo "Finished...<br/>";
						$bRet = true;
					}
				}
				else 
				{
					if(count($SanitizedUSER) <= count($SanitizedPNR))
					{
						$bRet = true;
					}
					else 
					{
						$aryTestCmplUsers = array();
						$query = sprintf("select user_id from result where user_id in (%s) and test_pnr not in (%s)", $userIn, $pnrIn);
						//echo $query."<br/>";
						$result = mysql_query($query, $this->db_link) or die('Is Test Finished error : ' . mysql_error());
						while($row = mysql_fetch_assoc($result))
						{
							array_push($aryTestCmplUsers, $row['user_id']);
						}
						
						$opHTML = "<table width='100%' style='margin-top:-120px;border-collapse:collapse;font:inherit;' border='1'>";
						$opHTML .= "<tr><td colspan='2' style='background-color:#A2BFF4' align='center'><b>Test Status</b></td></tr>";
						foreach($aryUser as $user_prvd)
						{
							$opHTML .= "<tr>";
							if(array_search($user_prvd, $aryTestCmplUsers) != FALSE)
							{
								$opHTML .= "<td>".$this->GetUserName($user_prvd)."</td><td style='color:green;font-weight:bold;'>Completed</td><br/>";
							}
							else if(!empty($user_prvd))
							{
								$opHTML .= "<td>".$this->GetUserName($user_prvd)."</td><td style='color:red;font-weight:bold;'>Pending</td><br/>";
							}
							$opHTML .= "</tr>";
						}
						$opHTML .= "</table>";
						//echo $opHTML;
					}
					//echo (" ( ".count($SanitizedUSER)." - ".count($SanitizedPNR)." ) ");
				}
			}
			
			return $bRet;
		}*/
		
		private function GetCandidateNameFromSchedule2($user_list, $tschd_id)
		{
			$RetAry = array();
			$TestCmplAry = array();
			
			$userAry = explode(";", $user_list);
			
			$query = sprintf("select * from users where user_id in (select user_id from result where tschd_id='%s')", $tschd_id);
			//echo $query."<br/>";
			$result = mysql_query($query, $this->db_link) or die('Get candidate name from schedule error_1 : ' . mysql_error());
			
			while($row = mysql_fetch_array($result))
			{
				$TestCmplAry[$row['user_id']] = 1;
			}
			mysql_free_result($result);
			
			// Sanitize and populate UserID Array.
			foreach ($userAry as $key => $user_id)
			{
				if(empty($user_id))
				{
					array_splice($userAry, $key, 1);
				}
				else 
				{
					$userAry[$key] = "'".$user_id."'";
					if(!isset($TestCmplAry[$user_id]))
					{
						$TestCmplAry[$user_id] = 0;
					}
				}
			}
			$userIn = implode(",", $userAry);
			
			if(!empty($userIn))
			{
				$query = sprintf("select * from users where user_id in (%s)", $userIn);
				//echo $query."<br/>";
				$result = mysql_query($query, $this->db_link) or die('Get candidate name from schedule error_2 : ' . mysql_error());
					
				while($row = mysql_fetch_array($result))
				{
					/*$hash = $row['firstname']." ".$row['lastname']." (Login: ".$row['login_name'].")";
				
					$RetAry[$hash] = $TestCmplAry[$row['user_id']];*/
				
					if(($key = array_search("'".$row['user_id']."'", $userAry)) !== false) {
						unset($userAry[$key]);
					}
				
					$RetAry[$row['user_id']]['name'] 			= $row['firstname']." ".$row['lastname'];
					$RetAry[$row['user_id']]['email'] 			= $row['email'];
					$RetAry[$row['user_id']]['login_name'] 		= $row['login_name'];
					$RetAry[$row['user_id']]['contact_no'] 		= $row['contact_no'];
					$RetAry[$row['user_id']]['location'] 		= $row['city'].', '.$row['state'].' ('.$this->GetCountryName($row['country']).')';
					$RetAry[$row['user_id']]['test_finished'] 	= $TestCmplAry[$row['user_id']];
				}	
			}
			
			foreach($userAry as $tpin)
			{
				$RetAry[$tpin]['name'] 			= " ";
				$RetAry[$tpin]['email'] 		= rtrim(ltrim($tpin, "'"), "'");
				$RetAry[$tpin]['login_name'] 	= " ";
				$RetAry[$tpin]['contact_no'] 	= " ";
				$RetAry[$tpin]['location'] 		= " ";
				$RetAry[$tpin]['test_finished'] = 0;
			}
			
			return $RetAry;
		}
		
		/*private function GetCandidateNameFromSchedule($user_list, $pnr_list)
		{
			$RetAry = array();
			$TestCmplAry = array();
			
			$userAry = explode(";", $user_list);
			$pnrAry  = explode(";", $pnr_list);
			
			foreach ($pnrAry as $key => $pnr)
			{
				//echo $pnr."<br/>";
				if(empty($pnr))
				{
					array_splice($pnrAry, $key, 1);
				}
			}
			foreach ($pnrAry as $key => $pnr)
			{
				$pnrAry[$key] = "'".$pnr."'";
			}
			$pnrIn = implode(",", $pnrAry);
			
			if(!empty($pnrIn))
			{
				$query = sprintf("select * from users where user_id in (select user_id from result where test_pnr in (%s))", $pnrIn);
				//echo $query."<br/>";
				$result = mysql_query($query, $this->db_link) or die('Get candidate name from schedule error 1 : ' . mysql_error());
				
				while($row = mysql_fetch_array($result))
				{
					$TestCmplAry[$row['user_id']] = 1;
				}
			}
			
			foreach ($userAry as $key => $user_id)
			{
				//echo $user_id."<br/>";
				if(empty($user_id))
				{
					array_splice($userAry, $key, 1);
				}
				else 
				{
					$userAry[$key] = "'".$user_id."'";
					if(!isset($TestCmplAry[$user_id]))
					{
						$TestCmplAry[$user_id] = 0;
					}
				}
			}
			$userIn = implode(",", $userAry);			
			
			$query = sprintf("select * from users where user_id in (%s)", $userIn);
			
			//echo $query."<br/>";
			
			$result = mysql_query($query, $this->db_link) or die('Get candidate name from schedule error : ' . mysql_error());
			
			while($row = mysql_fetch_array($result))
			{
				$hash = $row['firstname']." ".$row['lastname']." (Login: ".$row['login_name'].")";
				
				$RetAry[$hash] = $TestCmplAry[$row['user_id']];
			}
			
			return $RetAry;
		}*/
		
		public function PopulateTableTestSchedule($user_id = null, $time_zone = null)
		{
			$query = "";
			if($user_id == null)
			{
				$query = sprintf("select test_schedule.*, test.test_name, test.owner_id from test_schedule, test where test.test_id=test_schedule.test_id and test.deleted is null");
			}
			else 
			{
				$query = sprintf("select test_schedule.*, test.test_name from test_schedule join test on test.test_id = test_schedule.test_id left join assigned_packages on test.test_id=assigned_packages.test_id where (test.owner_id='%s' or (test.public = 1 AND assigned_packages.user_id = '%s')) AND test_schedule.scheduler_id='%s' and test.deleted is null", $user_id, $user_id, $user_id);
			}
			
			$result = mysql_query($query, $this->db_link) or die('Select from test error : ' . mysql_error());
			
			while($row = mysql_fetch_array($result))
			{
				echo "<tr id='".$row['test_id']."'>";
				echo "<td>".$row['schd_id']."_mip</td>";
				echo "<td>".$row['test_name']."</td>";
				if($user_id == null)
				{
					echo "<td>".$this->GetUserName($row['owner_id'])."</td>";
				}
				//echo "<td>".date("F d, Y", strtotime($row['scheduled_on']))."</td>";
				
				//$reset = date_default_timezone_get();
				$test_schedule_type = CConfig::TST_ONLINE;
				$test_schedule_type2 = ($row['schedule_type'] == CConfig::TST_OFFLINE)?"Offline":"Online";
				if($row['schedule_type'] == CConfig::TST_OFFLINE && empty($row['scheduled_on']))
				{
					echo "<td>Not Applicable</td>";
					echo "<td>Not Applicable</td>";
					
					$dtzone = new DateTimeZone($this->tzOffsetToName($time_zone));
					$dtime  = new DateTime($row['create_date']);
					$dtime->setTimezone($dtzone);
					echo "<td>".$dtime->format("Y-m-d H:i:s")."</td>";
					$test_schedule_type = CConfig::TST_OFFLINE;
				}
				else
				{
					echo "<td>".date("F d, Y [H:i:s]", strtotime($row['scheduled_on']))."</td>";
					echo "<td>".$this->tzOffsetToName($row['time_zone'])."</td>";
					
					$dtzone = new DateTimeZone($this->tzOffsetToName($row['time_zone']));
					$dtime  = new DateTime($row['create_date']);
					$dtime->setTimezone($dtzone);
					echo "<td>".$dtime->format("Y-m-d H:i:s")."</td>";
				}
				//date_default_timezone_set($this->tzOffsetToName($row['time_zone']));
				//date_default_timezone_set($reset);
				
				//$RetAry = $this->GetCandidateNameFromSchedule2($row['user_list'],$row['schd_id']);
				
				$user_list = "";
				$finished_list = "";
				$colors = array("blue","green");
				$toggleOut = 0;
				$toggleIn  = 0;
				
				echo "<td><input type='button' class='btn btn-sm btn-primary' schd_id='".$row['schd_id']."' test_schedule_type='".$test_schedule_type."' value='View Details' onclick='ShowCandidateDetails(this)'/></td>";
				//echo "<td>".$finished_list."</td>";
				
				$export_test_button = ($test_schedule_type == CConfig::TST_OFFLINE)?"<br /><br /><a href='ajax/ajax_download_offline_test.php?schd_id=".$row['schd_id']."' target='_blank' class='btn btn-sm btn-success'>Export Test</a>":(($row['schedule_type'] == CConfig::TST_OFFLINE)?" (Finished)":"");
				echo "<td style='text-align: center;'>".$test_schedule_type2.$export_test_button."</td>";
				echo "</tr>";
			}
		}
		
		public function GetScheduledCandidateDetails($schd_id)
		{
			$retAry = NULL;
			
			$query = sprintf("select user_list from test_schedule where schd_id='%s'", $schd_id);
			
			$result = mysql_query($query, $this->db_link) or die('Get Scheduled Candidate Details Error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				
				$retAry = $this->GetCandidateNameFromSchedule2($row['user_list'],$schd_id);
			}
			return $retAry;
		}
		
		private function FormatTestInPkgs($test_ids)
		{
			$sTestNameList = "";
			
			$aryTstIds  = explode(",", $test_ids);
			$aryLen 	= count($aryTstIds);
			
			foreach ($aryTstIds as $index => $test_id)
			{
				$sTestNameList .= $this->GetTestName($test_id);
				
				if($index < ($aryLen - 1))
				{
					$sTestNameList .= ", ";
				}
			}
			
			return $sTestNameList;
		}
		
		
		private function FormatPNRInfo($pnr_list)
		{
			$sResInfoList = "";
			
			$query = sprintf("select test.*, rs.count from test, (select test_id, count(*) from result where test_pnr in (%s)) as rs where test.test_id=rs.test_id");
			
			//echo $query."<br/>";
			
			$result = mysql_query($query, $this->db_link) or die('Format PNR Info error : ' . mysql_error());
			
			$arySize = mysql_num_rows($result);
			
			$index = 0;
			while($row = mysql_fetch_array($result))
			{
				$sResInfoList .= $row['test_name']."(Taken: ".$row['count']." times)";
				
				if($index < ($arySize-1))
				{
					$sResInfoList .= ", ";
				}
			}
			
			return $sResInfoList;
		}
		
		public function PopulateTestPkgSchedule($producer_id)
		{
			$query = sprintf("select firstname, lastname, email, test_package.* from users, test_package where test_package.producer_id='%s' AND users.user_id=test_package.consumer_id", $producer_id);
			
			$result = mysql_query($query, $this->db_link) or die('Populate test package error : ' . mysql_error());
			
			while($row = mysql_fetch_array($result))
			{
				printf("<tr id='%s'>", $row['pkg_id']);
				printf("<td>%s %s (%s)</td>", $row['firstname'], $row['lastname'], $row['email']);
				printf("<td>%s</td>", $row['pkg_name']);
				
				$test_name = "";
				$tname_ary = explode(";", $row['test_ids']);
				foreach ($tname_ary as $key => $test_id)
				{
					if(!empty($test_id))
					{
						$test_name .= $this->GetTestName($test_id);
						
						if($key != (count($tname_ary) - 2))
						{
							$test_name .= ",<br/>";
						}
					}
				}
				printf("<td>%s</td>", $test_name);
				
				printf("<td>%s</td>", date("F d, Y", strtotime($row['created_on'])));
				printf("<td>%s</td>", date("F d, Y", strtotime($row['deploy_on'])));
				printf("<td>%s Days (Rate: %s)</td>", $row['valid_for'], $row['rate']);
			}
		}
		
		public function tzOffsetToName($offset, $isDst = null)
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
	    
	    public function formatOffset($offset) 
	    {	
	        $hours = $offset / 3600;
	        $remainder = $offset % 3600;
	        $sign = $hours > 0 ? '+' : '-';
	        $hour = (int) abs($hours);
	        $minutes = (int) abs($remainder / 60);
	
	        if ($hour == 0 AND $minutes == 0) {
	            $sign = ' ';
	        }
	        return $sign . str_pad($hour, 2, '0', STR_PAD_LEFT) .':'. str_pad($minutes,2, '0');

		}
		
		public function PopulateTimeZones($time_zone)
		{
			$utc = new DateTimeZone('UTC');
			$dt = new DateTime('now', $utc);
			
			$count = 0;
			foreach(CConfig::$TIME_ZONE_ARRAY as $key => $tz) 
			{
				$current_tz = new DateTimeZone($tz);
				$offset =  $current_tz->getOffset($dt);
				//$transition =  $current_tz->getTransitions($dt->getTimestamp(), $dt->getTimestamp());
				//$abbr = $transition[0]['abbr'];
				
				$float_time_zone = $offset/3600;
				
				$selected_zone = "";
				if($time_zone == $float_time_zone && $count == 0)
				{
					$selected_zone = "selected='selected'";
					$count++;
				}
				//echo '<option value="' .$float_time_zone. '" '.$selected_zone.'>' .$tz. ' [' .$abbr. ' '. $this->formatOffset($offset). ']</option>';
				echo '<option value="' .$float_time_zone. '" '.$selected_zone.'>' .$key.'</option>';
			}
		}
	    
	    private function GetTestFinishedInfo($tschd_id)
	    {
	    	$RetAry = array("completed" => 0, "total" => 0);
	    	
	    	$query = sprintf("select user_list from test_schedule where schd_id='%s'", $tschd_id);
	    	
	    	$result = mysql_query($query, $this->db_link) or die('Get Test Finished Info (Outer) error : ' . mysql_error());
	    	
	    	$total = 0;
	    	if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				$userAry = explode(";", $row['user_list']);
				
				// Sanitize and populate UserID Array.
				foreach ($userAry as $key => $user_id)
				{
					if(empty($user_id))
					{
						array_splice($userAry, $key, 1);
					}
					else 
					{
						$userAry[$key] = "'".$user_id."'";
						$total++;
					}
				}
				$RetAry['total'] = $total;
				
				$userIn = implode(",", $userAry);
				
				if(empty($userIn))
				{
					$userIn = "''";
				}
				
				$innerQuery = sprintf("select count(*) as completed from result where user_id in (%s) and tschd_id='%s'", $userIn, $tschd_id);
				//echo($innerQuery."<br/>");
	    		$innerResult = mysql_query($innerQuery, $this->db_link) or die('Get Test Finished Info (Inner) error : ' . mysql_error());
	    		if(mysql_num_rows($innerResult) > 0)
				{
					$innerRow = mysql_fetch_array($innerResult);
					$RetAry['completed'] = $innerRow["completed"];
				}
			}
			
			return $RetAry;
	    }
	    
	    private function SessionExist($user_id, $test_id, $tschd_id) 
		{
			$tsession_id = null;
			
			$query = sprintf("select * from test_session where user_id='%s' AND test_id='%s' AND tschd_id='%s'", $user_id, $test_id, $tschd_id);
			//echo ($query."<br/>");
			$result = mysql_query($query, $this->db_link) or die('Session Exist error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				$tsession_id = $row['tsession_id'];
			}
			
			return $tsession_id;
		}
		
		public function PopultateScheduledTest($user_id)
		{
			$query = "";
			
			$user_type = $this->GetUserType($user_id);
			if($user_type != CConfig::UT_INDIVIDAL)
			{
				$query = sprintf("select * from test, test_schedule where test.owner_id='%s' AND test.test_id=test_schedule.test_id and deleted is null", $user_id);
			}
			else 
			{
				$query = sprintf("select * from test, test_schedule where locate('%s', test_schedule.user_list) AND test.test_id=test_schedule.test_id  and deleted is null and test_schedule.schedule_type != '%s'", $user_id, CConfig::TST_OFFLINE);
			}
			
			$result = mysql_query($query, $this->db_link) or die('Load Test error : ' . mysql_error());
			
			$stIndex = 0;
			$bTestScheduled = false;
			while($row = mysql_fetch_array($result))
			{
				date_default_timezone_set($this->tzOffsetToName($row['time_zone']));
				$opHTML = "";
				if($this->IsTestFinished2($user_id, $user_type, $row['user_list'], $row['schd_id']) == false)
				{
					$scheduler = $this->GetUserName($row['scheduler_id']);
					
					$bgColor = "#FFFFD6";
					$sResumeMsg = "";
					$tsession_id = $this->SessionExist($user_id, $row['test_id'], $row['schd_id']);
					if($user_type == CConfig::UT_INDIVIDAL && $tsession_id != null)
					{
						$bgColor = "#E6E6B8";
						$sResumeMsg = "<span style='color:red;font-weight:bold;'>(Unfinished Test!)</span>";
					}
					if($user_type == CConfig::UT_INDIVIDAL && strtotime($row['scheduled_on']) <= strtotime(date("Y-m-d H:i:s")) && empty($row["expire_on"]))
					{
                                                printf("<div id='st_%s' style='background-color:%s;border:1px solid #aaa;padding:5px;'>", $stIndex, $bgColor);
						printf("Test <b><a style='color:blue;text-decoration:underline' href='javascript:' onclick=ShowOverlay('%s/test/test.php?test_id=%d&tschd_id=%d','st_%s')>%s</a> (xID: %s_mip)</b> is scheduled on <b>%s</b> (%s), by %s. %s<br/>", CSiteConfig::ROOT_URL, $row['test_id'], $row['schd_id'], $stIndex, $row['test_name'], $row['schd_id'], date("F d, Y [H:i]", strtotime($row['scheduled_on'])), date_default_timezone_get(), $scheduler, $sResumeMsg);
                                                printf("</div>");
						$bTestScheduled = true;	
					}
					else if($user_type == CConfig::UT_INDIVIDAL && strtotime($row['scheduled_on']) <= strtotime(date("Y-m-d H:i:s")) && !empty($row["expire_on"]) &&  strtotime(date("Y-m-d H:i:s")) < strtotime($row["expire_on"]))
					{
                       	printf("<div id='st_%s' style='background-color:%s;border:1px solid #aaa;padding:5px;'>", $stIndex, $bgColor);
						printf("Test <b><a style='color:blue;text-decoration:underline' href='javascript:' onclick=ShowOverlay('%s/test/test.php?test_id=%d&tschd_id=%d','st_%s')>%s</a> (xID: %s_mip)</b> is scheduled on <b>%s</b> and will expire on <b>%s</b> (%s), by %s. %s<br/>", CSiteConfig::ROOT_URL, $row['test_id'], $row['schd_id'], $stIndex, $row['test_name'], $row['schd_id'], date("F d, Y [H:i]", strtotime($row['scheduled_on'])),date("F d, Y [H:i]", strtotime( $row["expire_on"])), date_default_timezone_get(), $scheduler, $sResumeMsg);
                       	printf("</div>");
                       	$bTestScheduled = true;
					}
					else if($user_type == CConfig::UT_INDIVIDAL && strtotime($row['scheduled_on']) > strtotime(date("Y-m-d H:i:s")) && empty($row["expire_on"]))
					{
						printf("<div id='st_%s' style='background-color:%s;border:1px solid #aaa;padding:5px;'>", $stIndex, $bgColor);
						printf("Test <b>%s (xID: %s_mip)</b> is scheduled on <b>%s</b> (%s), by %s. %s<br/>", $row['test_name'], $row['schd_id'], date("F d, Y [H:i]", strtotime($row['scheduled_on'])), date_default_timezone_get(), $scheduler, $sResumeMsg);
						printf("</div>");
						$bTestScheduled = true;
					}
					else if($user_type == CConfig::UT_INDIVIDAL && strtotime($row['scheduled_on']) > strtotime(date("Y-m-d H:i:s")) && !empty($row["expire_on"]) &&  strtotime(date("Y-m-d H:i:s")) < strtotime($row["expire_on"]))
					{
						printf("<div id='st_%s' style='background-color:%s;border:1px solid #aaa;padding:5px;'>", $stIndex, $bgColor);
						printf("Test <b>%s (xID: %s_mip)</b> is scheduled on <b>%s</b> and will expire on <b>%s</b>  (%s), by %s. %s<br/>", $row['test_name'], $row['schd_id'], date("F d, Y [H:i]", strtotime($row['scheduled_on'])), date("F d, Y [H:i]", strtotime( $row["expire_on"])),date_default_timezone_get(), $scheduler, $sResumeMsg);
						printf("</div>");
						$bTestScheduled = true;
					}
					else if($user_type != CConfig::UT_INDIVIDAL)
					{
                                                printf("<div id='st_%s' style='background-color:%s;border:1px solid #aaa;padding:5px;'>", $stIndex, $bgColor);
						$InfoAry = $this->GetTestFinishedInfo($row['schd_id']);
						printf("Test <b>%s (xID: %s_mip)</b> is scheduled on <b>%s</b> (%s), <span style='color:blue'>Completed: %d out of %d</span>.<br/>", $row['test_name'], $row['schd_id'], date("F d, Y", strtotime($row['scheduled_on'])), date_default_timezone_get(), $InfoAry['completed'], $InfoAry['total']);
						
						/*echo("<pre>");
						print_r($row);
						echo("</pre>");*/
                                                printf("</div>");
                                                $bTestScheduled = true;
					}
					
					
					$stIndex++;
				}
			}
			
			if($bTestScheduled == false) 
			{
				if($user_type == CConfig::UT_INDIVIDAL)
				{
					printf("No tests has been scheduled for you.");
				}
				else
				{
					printf("No tests has been scheduled by you.");
				}
			}
		}
		
		public function PopulateBillingDetails($user_id)
		{
			
		}
		
		private function GetUsersByType($user_type)
		{
			$retArray = array();
		
			$query = sprintf("select * from users where user_type='%s'",$user_type);
			
			$result = mysql_query($query, $this->db_link) or die('select user ids from users : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{	
				$index = 0;
						
				while($row = mysql_fetch_assoc($result))
				{
					$retArray[$index]['user_id'] = $row['user_id'];
					$retArray[$index]['organization_id'] = $row['organization_id'];
					$retArray[$index]['email'] = $row['email'];
					$retArray[$index]['firstname'] = $row['firstname'];
					$retArray[$index]['lastname'] = $row['lastname'];
					$index++;
				}
			}
			
			return $retArray;
		}
		
		public function GetOrganizationName($org_id)
		{
			$query = sprintf("select organization_name from organization where organization_id='%s'",$org_id);
			
			$result = mysql_query($query, $this->db_link) or die('select organization_name from organization : ' . mysql_error());
		
			$row = mysql_fetch_array($result);
			
			return $row['organization_name'];
		}
		
		public function GetOrgLogoImage($org_id)
		{
			$query = sprintf("select logo_image from organization where organization_id='%s'",$org_id);
				
			$result = mysql_query($query, $this->db_link) or die('select logo_image from organization : ' . mysql_error());
			
			$row = mysql_fetch_array($result);
				
			return $row['logo_image'];
		}
		
		public function PopulateBusinessAssociate($user_type)
		{
			$userInfoArray = $this->GetUsersByType($user_type);
			foreach($userInfoArray as $key=>$value)
			{
				printf("<option value='%s'>%s</option>",$value['user_id'],$this->GetOrganizationName($value['organization_id']));
			}
		}
		
		public function EmailTestScheduleNotification($user_id, $test_name, $candidate_list, $date, $hours, $minutes, $expire_on, $expire_hours, $expire_minutes,$time_zone)
		{
			$query = sprintf("select * from users, organization where user_id='%s' AND users.organization_id=organization.organization_id", $user_id);
			
			$result = mysql_query($query, $this->db_link) or die('select from users and organization : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				
				$organization_name = $row['organization_name'];
				$user_email = $row['email'];
				$user_name = $row['firstname']." ".$row['lastname'];
				
				mysql_free_result($result);
				
				$CandidateAry = explode(";", $candidate_list);
				
				$objMail = new CEMail(CConfig::OEI_SUPPORT, $this->GetPasswordFromOfficialEMail(CConfig::OEI_SUPPORT));
				
				$time_zone_name = $this->tzOffsetToName($time_zone);
				foreach($CandidateAry as $CandidateID)
				{
					$query = sprintf("select * from users where user_id='%s'", $CandidateID);
					
					//echo $query."<br/>";
					$result = mysql_query($query, $this->db_link) or die('select from users E T S N : ' . mysql_error($this->db_link));
					
					if(mysql_num_rows($result) > 0)
					{
						$row = mysql_fetch_array($result);
						
						if($row['isvalid'] == 1)
						{
							$candidate_email = $row['email'];
							$candidate_name  = $row['firstname']." ".$row['lastname'];
							
							mysql_free_result($result);
							if(!empty($expire_on))
							{
								$objMail->PrepAndSendTestScheduleMail($test_name, $candidate_email, $candidate_name, $organization_name, $user_email, $user_name, $date, $hours, $minutes, $expire_on, $expire_hours, $expire_minutes,$time_zone_name, $this);
								//CEMail::PrepAndSendTestScheduleMail($test_name, $candidate_email, $candidate_name, $organization_name, $user_email, $user_name, $date, $this);
							}
							else
							{
								$objMail->PrepAndSendTestScheduleMail($test_name, $candidate_email, $candidate_name, $organization_name, $user_email, $user_name, $date, $hours, $minutes,"", "", "",$time_zone_name, $this);
							}
						}
					}
				}
			}
		}
		
		public function EmailTestPackageNotification($user_id, $pkg_name, $candidate_id, $provisioned_from, $expire, $amnt_sold)
		{
			$query = sprintf("select * from users, organization where user_id='%s' AND users.organization_id=organization.organization_id", $user_id);
			
			$result = mysql_query($query, $this->db_link) or die('select from users and organization : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				
				$organization_name = $row['organization_name'];
				$user_email = $row['email'];
				$user_name = $row['firstname']." ".$row['lastname'];
				
				mysql_free_result($result);
				
				$query = sprintf("select * from users where user_id='%s'", $candidate_id);
				
				//echo $query."<br/>";
				$result = mysql_query($query, $this->db_link) or die('select from users : ' . mysql_error());
				
				if(mysql_num_rows($result) > 0)
				{
					$row = mysql_fetch_array($result);
					$candidate_email = $row['email'];
					$candidate_name  = $row['firstname']." ".$row['lastname'];
					
					mysql_free_result($result);
					
					$objMail = new CEMail(CConfig::OEI_SUPPORT, $this->GetPasswordFromOfficialEMail(CConfig::OEI_SUPPORT));
					
					$objMail->PrepAndSendTestPackageMail($pkg_name, $candidate_email, $candidate_name, $organization_name, $user_email, $user_name, $provisioned_from, $expire, $amnt_sold);
				}
			}
		}
		
		public function EmailRegNotification($user_id, $candidate_name, $candidate_email, $password)
		{
			$query = sprintf("select * from users, organization where user_id='%s' AND users.organization_id=organization.organization_id", $user_id);
			
			//echo $query."<br/>";
			$result = mysql_query($query, $this->db_link) or die('select from users & organization : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				
				$organization_name = $row['organization_name'];
				$user_email = $row['email'];
				$user_name = $row['firstname']." ".$row['lastname'];
				
				mysql_free_result($result);
				
				$objMail = new CEMail(CConfig::OEI_SUPPORT, $this->GetPasswordFromOfficialEMail(CConfig::OEI_SUPPORT));
				$objMail->PrepAndSendRegMail($candidate_email, $candidate_name, $organization_name, $user_email, $user_name, "http://www.".strtolower(CConfig::SNC_SITE_NAME).".com", $password);
				//CEMail::PrepAndSendRegMail($candidate_email, $candidate_name, $organization_name, $user_email, $user_name, "http://www.mipcat.com", $password);
			
				$objMail->PrepAndSendNewCandRegistrationNotificationMail($user_name, $row['email'], $candidate_name, $candidate_email);
			}
		}
		
		public function ListFreeTests($demo = false)
		{
			$apndQry = "";
			if($demo)
			{
				$apndQry = "order by rand() limit 2";
			}
			
			$query = sprintf("select * from test where public=1 %s", $apndQry);
			
			//echo $query."<br/>";owner_id
			$result = mysql_query($query, $this->db_link) or die('List Free Tests error : ' . mysql_error());
			
			$index = 1;
			while($row = mysql_fetch_array($result))
			{
				printf("<tr>");
				printf("<td><a href='javascript:' onclick=\"parent.ShowOverlay('test/test.php?test_id=%d&tschd_id=-100','st_x');\">%s</a></td>", $row['test_id'], $row['test_name']);
				printf("</tr>");
			}
		}
		
		public function GetUserOrgInfo($user_id)
		{
			$objRet = null;
			
			$query = sprintf("select * from organization join users on users.user_id='%s' and organization.organization_id=users.organization_id", $user_id);
			
			//echo $query."<br/>";owner_id
			$result = mysql_query($query, $this->db_link) or die('Get User Org Name error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$objRet = mysql_fetch_array($result);
			}
			
			return $objRet;
		}
		
		public function ListTestPackages($user_id)
		{
			$query = sprintf("select * from test_package where consumer_id='%s'", $user_id);
			
			//echo $query."<br/>";
			$result = mysql_query($query, $this->db_link) or die('List Test Packages error : ' . mysql_error());
			
			$index = 1;
			while($row = mysql_fetch_array($result))
			{
				$testIdAry = explode(";", $row['test_ids']);
				
				$OrgInfoAry = $this->GetUserOrgInfo($row['producer_id']);
				
				// - - - - - - - - - - - - - - - - - - - - - - - - - - -
				// Package Scheduling & Expiration
				// - - - - - - - - - - - - - - - - - - - - - - - - - - -
				$reset = date_default_timezone_get();
				date_default_timezone_set($this->tzOffsetToName($row['time_zone']));
				$now_time = strtotime("now");
				$deploy_on = strtotime($row['deploy_on']);
				$expire_on = strtotime($row['deploy_on']." +".$row['valid_for']." days");
				//printf("%s Seconds Remaining.", ($expire_on - $now_time));
				
				$bPkgExpired 	= ($now_time > $expire_on) ? true : false;
				if($bPkgExpired)
				{
					// Skip to next package.
					continue;
				}
				
				$bPkgActive 	= ($now_time >= $deploy_on) ? true : false;
				$nDaysRemaining = ($bPkgActive == true) ? ceil(($expire_on - $now_time)/86400) : "--";
				// - - - - - - - - - - - - - - - - - - - - - - - - - - -
				
				echo "<table style='font:inherit;' class='table table-striped table-bordered table-hover' width='100%'>\n";
				//printf("<table style='font:inherit;' class='table table-striped table-bordered table-hover' width='100%'>\n");
				
				// - - - - - - - - - - - - - - - - - - - - - - - - - - -
				// Package Heading
				// - - - - - - - - - - - - - - - - - - - - - - - - - - -
				$sStatus = ($bPkgActive == true) ? "<span style='color:Green;'>(Active)</span>" : "<span style='color:FireBrick;'>(Provisioned)</span>";
				printf("<tr class='info'>\n");
				printf("<th colspan='3' style='text-align:center;'>Test Package&nbsp;<i class='icon-edit icon-black'></i>&nbsp;-&nbsp;<span style='color:blue'>%s</span> %s</th>\n", $row['pkg_name'], $sStatus);
				printf("</tr>\n");
				// - - - - - - - - - - - - - - - - - - - - - - - - - - -
				
				// - - - - - - - - - - - - - - - - - - - - - - - - - - -
				// Scheduler Information
				// - - - - - - - - - - - - - - - - - - - - - - - - - - -
				printf("<tr class='warning'>\n");
				printf("<td width='35%%'><b>Sheduled By:</b> %s (<u style='color:blue'>%s</u>)&nbsp;<i class='icon-user icon-black'></i></td>\n", $this->GetUserName($row['producer_id']), $this->GetUserEmail($row['producer_id']));
				printf("<td width='65%%' colspan='2'><b>Organization:</b> <a href='%s' target='_blank'>%s</a>&nbsp;<i class='icon-qrcode icon-black'></i></td>\n", $OrgInfoAry['organization_url'], $OrgInfoAry['organization_name']);
				printf("</tr>\n");
				// - - - - - - - - - - - - - - - - - - - - - - - - - - -
				
				// - - - - - - - - - - - - - - - - - - - - - - - - - - -
				// Date/Time & Cost Information
				// - - - - - - - - - - - - - - - - - - - - - - - - - - -
				printf("<tr class='success'>\n");
				printf("<td><b>Created On:</b> %s&nbsp;<i class='icon-map-marker icon-black'></i></td>\n", date("F d, Y", strtotime($row['created_on'])));
				printf("<td><b>Provisioned From:</b> %s (%s Day&frasl;s Remaining)&nbsp;<i class='icon-time icon-black'></i></td>\n", date("F d, Y", strtotime($row['deploy_on'])), $nDaysRemaining);
				$fCost = empty($row['sold_at']) ? "Not Revealed" : $row['sold_at'];
				printf("<td><b>Cost:</b> %s&nbsp;<i class='icon-certificate icon-black'></i></td>\n", $fCost);
				printf("</tr>\n");
				// - - - - - - - - - - - - - - - - - - - - - - - - - - -
				
				// - - - - - - - - - - - - - - - - - - - - - - - - - - -
				// Test Links
				// - - - - - - - - - - - - - - - - - - - - - - - - - - -
				printf("<tr>\n");
				foreach ($testIdAry as $key => $test_id)
				{
					if(!empty($test_id))
					{
						if($bPkgActive)
						{
							printf("<td><a style='color:white;'class='btn btn-success btn-small' href='javascript:' onclick=\"parent.ShowOverlay('test/test.php?test_id=%d&tschd_id=null','st_x');\">[ %s ]&nbsp;&nbsp;&nbsp;&nbsp;<i style='position:relative;top:2px;' class='icon-pencil icon-white'></i></a></td>\n", $test_id, $this->GetTestName($test_id));
						}
						else 
						{
							printf("<td><span style='color:white;'class='btn btn-inverse btn-small'>[ %s ]&nbsp;&nbsp;&nbsp;&nbsp;<i style='position:relative;top:2px;' class='icon-pencil icon-white'></i></span></td>\n", $this->GetTestName($test_id));
						}
						
						if($key !=0 && (($key+1) % 3) == 0)
						{
							printf("</tr>\n");
							printf("<tr>\n");
						}
					}
				}
				
				if(($key % 3) != 0)
				{
					for($i = 0; $i < (3-($key % 3)); $i++)
					{
						printf("<td>&nbsp;</td>");
					}
				}
				
				printf("</tr>\n", ($key), ($key % 3));
				// - - - - - - - - - - - - - - - - - - - - - - - - - - -
				
				printf("</table>");
				
				date_default_timezone_set($reset);
			}
		}
		
		public function ListBusinessAssociate()
		{
			$query = sprintf("select * from users, organization where user_type='%s' and organization.organization_id=users.organization_id", CConfig::UT_BUSINESS_ASSOCIATE);
			
			//echo $query."<br/>";
			$result = mysql_query($query, $this->db_link) or die('List Business Associate error : ' . mysql_error());
			
			$sListing = "";
			while($row = mysql_fetch_array($result))
			{
				$sListing .= "<table width='100%' class='table table-striped table-bordered table-hover'>";
				
				$sListing .= "<tr class='info'>";
				$sListing .= "<td>Organization Name:</td>";
				$sListing .= "<td>";
				$sListing .= $row['organization_name'];
				$sListing .= "</td>";
				$sListing .= "</tr>";
				
				$sListing .= "<tr class='success'>";
				$sListing .= "<td>Contact Person:</td>";
				$sListing .= "<td>";
				$sListing .= $row['firstname']." ".$row['lastname'];
				$sListing .= "</td>";
				$sListing .= "</tr>";
				
				$sListing .= "<tr class='warning'>";
				$sListing .= "<td>Website:</td>";
				$sListing .= "<td>";
				$sListing .= empty($row['organization_url']) ? "Not Available" : "<a href='".$row['organization_url']."'>".$row['organization_url']."</a>";
				$sListing .= "</td>";
				$sListing .= "</tr>";
				
				$sListing .= "<tr class='warning'>";
				$sListing .= "<td>Email:</td>";
				$sListing .= "<td>";
				$sListing .= $row['email'];
				$sListing .= "</td>";
				$sListing .= "</tr>";
				
				$sListing .= "<tr class='warning'>";
				$sListing .= "<td>Phone:</td>";
				$sListing .= "<td>";
				$sListing .= $row['contact_no'];
				$sListing .= "</td>";
				$sListing .= "</tr>";
				
				$sListing .= "<tr class='error'>";
				$sListing .= "<td>Address:</td>";
				$sListing .= "<td>";
				$sListing .= $row['address']."<br/>".$row['city'].", ".$row['state']."<br/>".$this->GetCountryName($row['country']);
				$sListing .= "</td>";
				$sListing .= "</tr>";
				
				$sListing .= "</table>";
			}
			
			echo($sListing);
		}
		
		public function GetQuestionsInfo($user_id=null)
		{
			$QuesInfoAry = array();
			
			$query = "";
			
			if($user_id == null)
			{
				$query = sprintf("select * from question where public=1");
			}
			else 
			{
				$query = sprintf("select * from question where user_id='%s' and public=0", $user_id);
			}
			
			$result = mysql_query($query, $this->db_link)  or die('Get Questions Info error : ' . mysql_errno());
			
			while($row = mysql_fetch_array($result))
			{
				if(isset($QuesInfoAry[$row['subject_id']][$row['topic_id']][$row['language']][$row['difficulty_id']]))
				{
					$QuesInfoAry[$row['subject_id']][$row['topic_id']][$row['language']][$row['difficulty_id']]++;
				}
				else 
				{
					$QuesInfoAry[$row['subject_id']][$row['topic_id']][$row['language']][$row['difficulty_id']] = 1;
				}
			}
			
			return $QuesInfoAry;
		}
		
		public function GetTestCriteria($test_id)
		{
			$RetAry = array();
			
			$query = sprintf("select * from test_dynamic where test_id='%s'", $test_id);
			
			$result = mysql_query($query, $this->db_link) or die('Get Test Criteria error : ' . mysql_errno());
			
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				
				$RetAry['criteria'] 			= $row['criteria'];
				$RetAry['cutoff_min'] 			= $row['cutoff_min'];
				$RetAry['cutoff_max'] 			= $row['cutoff_max'];
				$RetAry['top_result'] 			= $row['top_result'];
				$RetAry['max_question'] 		= $row['max_question'];
				$RetAry['marks_for_correct'] 	= $row['marks_for_correct'];
				$RetAry['negative_marks'] 		= $row['negative_marks'];
			}
			
			return $RetAry;
		}

		public function GetTestSchdResultDeatils($tschd_ids, $owner_id="", $batch_ids = "")
		{
			include_once(dirname(__FILE__)."/../test/lib/test_helper.php");
		
			$RetAry = array();
			
			$objTH 	= new CTestHelper();
			
			$tschd_id_ary = explode(";", $tschd_ids);
			
			foreach($tschd_id_ary as $key => $tschd_id)
			{
				$tschd_id_ary[$key] = "'".$tschd_id_ary[$key]."'";
			}
			
			$tschd_ids_in = implode(",", $tschd_id_ary);
			
			$owner_batch_array = array();
			$batch_id_ary      = explode(",", $batch_ids);
			if(!empty($owner_id))
			{
				$owner_batch_array = array_keys($this->GetBatches($owner_id));
			}
			
			$query = sprintf("select * from result where tschd_id in (%s)", $tschd_ids_in);
			
			$result = mysql_query($query, $this->db_link) or die('Get Test Schd Result Deatils error : ' . mysql_errno());
			
			while($row = mysql_fetch_array($result))
			{
				$cand_batch_ary = $this->GetCandidateBatches($row['user_id']);
				if(!isset($RetAry['tschd_id'][$row['tschd_id']]))
				{
					$RetAry['tschd_id'][$row['tschd_id']] = array();
					$RetAry['tschd_id'][$row['tschd_id']] = array();
					$RetAry['test_criteria'] = $this->GetTestCriteria($row['test_id']);
					
					$RetAry['sec_details'] = $objTH->GetSectionDetails($row['test_id']);
				}
				
				if(!empty($batch_ids))
				{	
					if(count(array_intersect($batch_id_ary, $cand_batch_ary)) > 0 && !in_array(CConfig::CDB_ID, $batch_id_ary))
					{
						$user_info = array('user_id' => $row['user_id'], 'marks' => $row['marks'], 'section_marks' => json_decode($row['section_marks']));
						array_push($RetAry['tschd_id'][$row['tschd_id']], $user_info);
					}
					else if(count(array_intersect($batch_id_ary, $cand_batch_ary)) > 1)
					{
						$user_info = array('user_id' => $row['user_id'], 'marks' => $row['marks'], 'section_marks' => json_decode($row['section_marks']));
						array_push($RetAry['tschd_id'][$row['tschd_id']], $user_info);
					}
					else if(count(array_intersect($batch_id_ary, $cand_batch_ary)) > 0 && count(array_intersect($owner_batch_array, $cand_batch_ary)) == 0)
					{
						$user_info = array('user_id' => $row['user_id'], 'marks' => $row['marks'], 'section_marks' => json_decode($row['section_marks']));
						array_push($RetAry['tschd_id'][$row['tschd_id']], $user_info);
					}
				}
				else 
				{
					$user_info = array('user_id' => $row['user_id'], 'marks' => $row['marks'], 'section_marks' => json_decode($row['section_marks']));
					array_push($RetAry['tschd_id'][$row['tschd_id']], $user_info);
				}
			}
			
			return $RetAry;
		}
		
		private function GetUserNameAry($user_id)
		{
			$ResAry = null;
			$query = sprintf("select firstname, lastname, email from users where user_id='%s'", $user_id);
			
			$result = mysql_query($query, $this->db_link) or die('Get username error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);;
				$ResAry = $row;
			}
			
			return $ResAry;
		}
		
		public function PopulateCustomResultList($tschd_ids, $min_marks, $max_marks, $top_cand, $sec_min_percents, $sec_max_percents, $sec_weights, $batch_ids = "")
		{
			if($min_marks != "NaN")
			{
				$tschd_id_ary = explode(";", $tschd_ids);
				
				$min_percntg_ary = explode(";", $sec_min_percents);
				$max_percntg_ary = explode(";", $sec_max_percents);
				
				//print_r($min_percntg_ary);
				$sec_weight_ary  = explode(";", $sec_weights);
				
				$total_weight = 0;
				foreach($sec_weight_ary as $key)
				{
					$total_weight += $key;
				}
				foreach($tschd_id_ary as $key => $tschd_id)
				{
					$tschd_id_ary[$key] = "'".$tschd_id_ary[$key]."'";
				}
				
				$tschd_ids_in = implode(",", $tschd_id_ary);
				
				$query = sprintf("select DISTINCT result.*, test_schedule.scheduler_id, test_schedule.schd_id, test_schedule.time_zone, test_schedule.scheduled_on, test_dynamic.marks_for_correct, test_dynamic.max_question from result, test_dynamic, test_schedule where tschd_id in (%s) and result.test_id=test_dynamic.test_id and result.marks between %s and %s and result.tschd_id=test_schedule.schd_id order by marks desc", $tschd_ids_in, floor($min_marks), ceil($max_marks));
				
				//echo($query."<br/>");
				$result = mysql_query($query, $this->db_link) or die('Populate Custom Result List error : ' . mysql_errno());
						
				$rank = 1;	
				$tbl_content_ary		 = array();	
				$percentile_ary			 = array();
				$marks_ary		 		 = array();
				$scheduler_batch_array 	 = array();
				$batch_id_ary		     = explode(",", $batch_ids);
				while($row = mysql_fetch_array($result))
				{
					$cand_batch_ary = array();
					$NameAry = $this->GetUserNameAry($row['user_id']);

					if(empty($scheduler_batch_array))
					{
						$scheduler_batch_array = array_keys($this->GetBatches($row['scheduler_id']));
					}
					
					if(!empty($batch_ids))
					{
						$cand_batch_ary = $this->GetCandidateBatches($row['user_id']);
					}
					
					//$tsCD = strtotime($row['create_date']);
					//$tsTD = strtotime($row['test_date']);
					if((!empty($batch_ids) && ((count(array_intersect($batch_id_ary, $cand_batch_ary)) > 0 && !in_array(CConfig::CDB_ID, $batch_id_ary)) || (count(array_intersect($batch_id_ary, $cand_batch_ary)) > 1) || (count(array_intersect($batch_id_ary, $cand_batch_ary)) > 0 && count(array_intersect($scheduler_batch_array, $cand_batch_ary)) == 0))) || empty($batch_ids))
					{
						$secDetails = json_decode($row['section_marks'], true);
						
						$secCount 			  = 0;
						$secIndex 			  = 0;
						$weighted_marks 	  = 0;
						$obtained_marks		  = 0;
						$total_marks    	  = 0;
						$total_weighted_marks = 0;
						foreach($secDetails as $secName)
						{
							$secMinMarks =  floor(($min_percntg_ary[$secIndex] * $secName['max_marks'])/100);
							$secMaxMarks =  ceil(($max_percntg_ary[$secIndex] * $secName['max_marks'])/100);
							
							//echo $secMinMarks." ".$secMaxMarks." ".$secName['marks']."<br />";
							
							if($secName['marks'] >= $secMinMarks && $secName['marks'] <= $secMaxMarks)
							{
								$secCount++; 
								$weighted_marks += $secName['marks'] * $sec_weight_ary[$secIndex];
								$obtained_marks += $secName['marks'];
								$total_marks 	+= $secName['max_marks'];
								$total_weighted_marks += $secName['max_marks'] * $sec_weight_ary[$secIndex];
							}
							$secIndex++;
						}
						
						if($secCount == count($secDetails))
						{	
							$tbl_content_ary[$row['test_pnr']]['name_ary'] 		= $this->GetUserNameAry($row['user_id']);
							$tbl_content_ary[$row['test_pnr']]['schd_id'] 		= $row['schd_id'];
							$tbl_content_ary[$row['test_pnr']]['test_id'] 		= $row['test_id'];
							$tbl_content_ary[$row['test_pnr']]['scheduled_on']	= strtotime($row['scheduled_on']);
							$tbl_content_ary[$row['test_pnr']]['test_date']		= $row['test_date'];
							$tbl_content_ary[$row['test_pnr']]['marks']			= $obtained_marks;
							$tbl_content_ary[$row['test_pnr']]['total_marks']	= $total_marks;
							$tbl_content_ary[$row['test_pnr']]['percentile']	= ($weighted_marks*100)/$total_weighted_marks;
							$tbl_content_ary[$row['test_pnr']]['time_taken']    = floor($row['time_taken'] / 60).":".($row['time_taken'] % 60);
							
							if($time_zone==null)
							{
								$tbl_content_ary[$row['test_pnr']]['time_zone'] 	= $row['time_zone'];
							}
							else 
							{
								$tbl_content_ary[$row['test_pnr']]['time_zone'] 	= $time_zone;
							}
							
							$percentile_ary[$row['test_pnr']] = $tbl_content_ary[$row['test_pnr']]['percentile'];
							$marks_ary[$row['test_pnr']]	  = $obtained_marks;
						}
					}
				}
				
				if(!empty($tbl_content_ary))
				{
					array_multisort($percentile_ary, SORT_DESC, $marks_ary, SORT_DESC, $tbl_content_ary);
					
					$prcntile = null;
					foreach ($tbl_content_ary as $key=>$value)
					{
						if(!empty($prcntile) && $prcntile != $value['percentile'])
						{
							$rank++;
						}
						$prcntile = $value['percentile'];
						printf("<tr id='%s'>", $key);
						printf("<td>%s</td>", $value['schd_id']);
						printf("<td>%s</td>", $this->GetTestName($value['test_id']));
						
						$reset = date_default_timezone_get();
						
						printf("<td>%s</td>", date("M d, Y", $value['scheduled_on']));
						
						$dtzone = new DateTimeZone($this->tzOffsetToName($value['time_zone']));
						$dtTime  = new DateTime($value['test_date']);
						$dtTime->setTimezone($dtzone);
						printf("<td>%s</td>", $dtTime->format("M d, Y [H:i:s]"));
						
						printf("<td>%s %s (%s)</td>", $value['name_ary']['firstname'], $value['name_ary']['lastname'], $value['name_ary']['email']);
						printf("<td style='text-align: center;'>%s out of %s<br /><br /><button id='%s;details' class='btn btn-success btn-sm' onclick='ShowSectionWisePerformance(this);' title='Section-Wise Details'><i class='icon-list'></i></button></td>", $value['marks'], $value['total_marks'], $key);
						printf("<td>%01.2f</td>", $value['percentile']);
						printf("<td>%s</td>", $rank);
						
						//$sTime = floor($row['time_taken'] / 60).":".($row['time_taken'] % 60);
						printf("<td>%s</td>", $value['time_taken']);
						printf("</tr>");
					}
				}
			}
		}
		
		public function CheckLoginName($login_name, $user_id)
		{
			$aryRet = array("present" => 0);
			$query = sprintf("select * from users where login_name='%s' and user_id <> '%s'", $login_name, $user_id);
			
			$result = mysql_query($query, $this->db_link) or die('Check Login Name error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$aryRet["present"] = 1;
			}
			
			return $aryRet;
		}
		
		public function PopulateTagList($user_id=NULL)
		{
			$user_cond = "public=1";
			if(!empty($user_id))
			{
				$user_cond = sprintf("user_id='%s'", $user_id);
			}
			
			$query = sprintf("select distinct tag, question_tag.tag_id from question_tag join question on question_tag.tag_id=question.tag_id where %s", $user_cond);
			
			$result = mysql_query($query, $this->db_link) or die('Populate Tag List error : ' . mysql_error());
			
			while($row = mysql_fetch_array($result))
			{
				printf("<option value='%s'>%s</option>\n", $row['tag_id'], $row['tag']);
			}
		}
				
		
		public function PrepareRCDirectionsHTML($para_id, $ques_type)
		{
			$objRet = "";
			$query  = "";
				
			if($ques_type == CConfig::QT_READ_COMP)
			{
				$query = sprintf("select * from rc_para where rc_id='%s'", $para_id);
			}
			else if($ques_type == CConfig::QT_DIRECTIONS)
			{
				$query = sprintf("select * from directions_para where directions_id='%s'", $para_id);
			}
				
			$result = mysql_query($query, $this->db_link) or die('Prepare RC Directions HTML : ' . mysql_error());
				
			while($row = mysql_fetch_array($result))
			{
				if(CUtils::getMimeType($row['description']) != "application/octet-stream")
				{
					$objRet .= sprintf("<p><img src='../../test/lib/print_image.php?para_id=%s&ques_type=%s'></p>\n", $para_id, $ques_type);
				}
				else
				{
					$objRet .= sprintf("<p>%s</p><hr/>\n", stripslashes($row['description']));
				}
			}
				
			return $objRet;
		}
		
		// mcat db function for getting question tags for bulk upload "Tag Question Set(Optional)" autocomplete feature
		public function GetQuestionTags($hint)
        {
            $query = "";
            $tags_ary = array();
           
            if(!empty($hint))
            {
                $hint = urldecode($hint);
                $wrd_ary = explode(" ", $hint);
                $wrd_cnt = count($wrd_ary) - 1;
               
                $query = sprintf("select DISTINCT tag from question_tag where ");
               
                $index = 0;
                foreach ($wrd_ary as $word)
                {
                    $query .= sprintf("locate('%s', tag)", $word);
                       
                    if($index < $wrd_cnt)
                    {
                        $query .= " and ";
                    }
                    $index++;
                }
               
                //echo $wrd_cnt." - ".$query."<br/>";
                $result = mysql_query($query, $this->db_link);
               
                $index = 0;
                while ($row = mysql_fetch_assoc($result))
                {
                    $tags_ary[$index] = $row['tag'];
                       
                    $index++;
                }    
            }
            return $tags_ary;
        }
        
        public function GetParaDescription($para_id, $ques_type)
        {
            $retVal = "";
            $query = "";
           
            if($ques_type == CConfig::QT_READ_COMP )
            {
                $query = sprintf("select description from rc_para where rc_id=%d",$para_id);
            }
            else if($ques_type == CConfig::QT_DIRECTIONS)
            {
                $query = sprintf("select description from directions_para where directions_id=%d",$para_id);
            }
           
            $result = mysql_query($query, $this->db_link) or die('Get Para Description error : ' . mysql_error());
           
            if(mysql_num_rows($result) > 0)
            {
                $row = mysql_fetch_array($result);
               
                $retVal = $row['description'];
            }
            return $retVal;
        }
        
        public function GetPasswordFromOfficialEMail($email)
        {
            $retVal = NULL;
           
            $query = sprintf("select passwd from official_email_info where email='%s'", $email);
           
            $result = mysql_query($query, $this->db_link) or die('Get password from official email error : ' . mysql_error());
           
            if(mysql_num_rows($result) > 0)
            {
                $row = mysql_fetch_array($result);
               
                $retVal = $row["passwd"];
            }
            return $retVal;
        }
        
        public function PrepareScheduledTestCombo($owner_id, $test_id = 0)
        {
            $query = sprintf("select DISTINCT ts.test_id, test.test_name from test_schedule ts join test on ts.test_id = test.test_id left join assigned_packages on assigned_packages.test_id = test.test_id where ((test.owner_id='%s' and ts.scheduler_id ='%s') or (ts.scheduler_id ='%s' and assigned_packages.user_id='%s')) and test.submitted=0 and test.deleted is null and ts.scheduled_on is not null", $owner_id, $owner_id, $owner_id, $owner_id);
       
            $result = mysql_query($query, $this->db_link) or die('Prepare scheduled test combo error : ' . mysql_error());
           
            if(mysql_num_rows($result) > 0)
            {
                while($row = mysql_fetch_array($result))
                {
                    if($test_id != 0)
                    {
                        echo (($test_id == $row['test_id'])?"<option value='".$row['test_id']."' selected>".$row['test_name']."</option>":"<option value='".$row['test_id']."'>".$row['test_name']."</option>");
                    }
                    else
                    {
                        echo "<option value='".$row['test_id']."'>".$row['test_name']."</option>";
                    }
                }
            }
            else
            {
                echo "<option value=''>No Test Available</option>";
            }
        }
       
        public function PrepareScheduledTestDateCombo($test_id, $scheduler_id = "", $tschd_id = 0)
        {
        	
        	$scheduler_id_cond = "";
        	if(!empty($scheduler_id))
        	{
        		$scheduler_id_cond = sprintf("and scheduler_id='%s'", $scheduler_id);
        	}
        	
            $query = sprintf("select * from test_schedule where test_id=%d and scheduled_on is not null %s", $test_id, $scheduler_id_cond);
    
            $result = mysql_query($query, $this->db_link) or die('prepare scheduled test date combo error: ' . mysql_error());
           
            while($row = mysql_fetch_array($result))
            {
                if($tschd_id != 0)
                {
                    $selected = ($tschd_id == $row['schd_id'])?'selected':'';
                   
                    printf("<option value='%s' %s>%s (xID: %s)</option>", $row['schd_id'], $selected, date("F j, Y", strtotime($row['scheduled_on'])), $row['schd_id']);
                }
                else
                {
                    printf("<option value='%s'>%s (xID: %s)</option>", $row['schd_id'], date("F j, Y", strtotime($row['scheduled_on'])), $row['schd_id']);
                }
            }
        }
       
        public function GetCandidateResult($candidate_id, $tschd_id)
        {
            $retVal = NULL;
           
            $query = sprintf("select * from result where user_id='%s' and tschd_id=%d", $candidate_id, $tschd_id);
           
            $result = mysql_query($query, $this->db_link) or die('get candidate result error: ' . mysql_error());
           
            if(mysql_num_rows($result) > 0)
            {
                $retVal = mysql_fetch_array($result);
            }
           
            return $retVal;
        }
       
        public function GetScheduledTestDate($tschd_id)
        {
            $retVal = NULL;
           
            $query = sprintf("select scheduled_on from test_schedule where schd_id=%d", $tschd_id);
    
            $result = mysql_query($query, $this->db_link) or die('Get scheduled test date error: ' . mysql_error());
           
            if(mysql_num_rows($result) > 0)
            {
                $row = mysql_fetch_array($result);
               
                $retVal = date("F j, Y", strtotime($row['scheduled_on']));
            }
           
            return $retVal;
        }
       
        public function GetTestSession($candidate_id, $tschd_id)
        {
            $retVal = NULL;
           
            $query = sprintf("select * from test_session where user_id='%s' and tschd_id=%d", $candidate_id, $tschd_id);
           
            $result = mysql_query($query, $this->db_link) or die('get test session error: ' . mysql_error());
           
            if(mysql_num_rows($result) > 0)
            {
                $retVal = mysql_fetch_array($result);
            }
           
            return $retVal;
        }
       
        public function PrepareScheduledCandidatesCombo($tschd_id)
        {
            $query = sprintf("select user_list from test_schedule where schd_id=%d", $tschd_id);
           
            $result = mysql_query($query, $this->db_link) or die('prepare scheduled candidates combo error: ' . mysql_error());
           
            if(mysql_num_rows($result) > 0)
            {
                $row = mysql_fetch_array($result);
               
                $candidate_ary = explode(";",$row['user_list']);
               
                $optionsAry = array("blue" => array(), "green" => array(), "red" => array());
                $bI = 0;
                $gI = 0;
                $rI = 0;
                for($index = 0; $index < count($candidate_ary); $index++)
                {
                    if(!empty($candidate_ary[$index]))
                    {
                        $isTestCompleted         = $this->GetCandidateResult($candidate_ary[$index], $tschd_id);
                       
                        $isTestSessionExists     = $this->GetTestSession($candidate_ary[$index], $tschd_id);
                       
                        if(!empty($isTestCompleted))
                        {
                            $optionsAry["red"][$bI++] = sprintf("<option value='%s' style='background-color: #ddd;color: red;' disabled>%s - (%s)</option>", $candidate_ary[$index], $this->GetUserName($candidate_ary[$index]), $this->GetUserEmail($candidate_ary[$index]));    
                        }
                        else if(!empty($isTestSessionExists))
                        {
                            $optionsAry["green"][$gI++] = sprintf("<option value='%s' style='background-color: #ddd;color:green;' disabled>%s - (%s)</option>", $candidate_ary[$index], $this->GetUserName($candidate_ary[$index]), $this->GetUserEmail($candidate_ary[$index]));
                        }
                        else
                        {
                            //$optionsAry["blue"][$rI++] = sprintf("<option value='%s' style='color:darkblue;'>%s - (%s)</option>", $candidate_ary[$index], $this->GetUserName($candidate_ary[$index]), $this->GetUserEmail($candidate_ary[$index]));
                            $cand_name = trim($this->GetUserName($candidate_ary[$index]));
                            $cand_email = $this->GetUserEmail($candidate_ary[$index]);
                            if(!empty($cand_name))
                            {
                            	$optionsAry["blue"][$rI++] = sprintf("<option value='%s' style='color:darkblue;'>%s - (%s)</option>", $candidate_ary[$index], $cand_name, $cand_email);
                            }
                            else
                            {
                            	$optionsAry["blue"][$rI++] = sprintf("<option value='%s' style='color:darkblue;'>TPIN - %s</option>", $candidate_ary[$index], $candidate_ary[$index]);
                            }
                        }
                    }
                }
               
                foreach($optionsAry as $opt_ary)
                {
                    foreach($opt_ary as $opt_to_print)
                    {
                        echo($opt_to_print);
                    }
                }
            }
        }
       
        public function DeleteScheduledTest($tschd_id)
        {
            $query = sprintf("delete from test_schedule where schd_id = %d", $tschd_id);
           
            $result = mysql_query($query, $this->db_link) or die('delete scheduled test error: ' . mysql_error());
                   
            return $result;
                   
        }
        
        public function RemoveScheduledCandidate($candidate_id, $tschd_id)
        {
             $query = sprintf("update test_schedule set user_list = replace(user_list,'%s;','') where schd_id = %d", $candidate_id, $tschd_id);
        
            $result = mysql_query($query, $this->db_link) or die('remove scheduled candidate error: ' . mysql_error());
           
            $scheduled_test = $this->GetScheduledTest($tschd_id);
           
            if(empty($scheduled_test['user_list']))
            {
                $this->DeleteScheduledTest($tschd_id);
            }
           
            return $result;
        }
        
        public function GetScheduledTest($tschd_id)
        {
            $retVal = NULL;
        
            $query = sprintf("select * from test_schedule where schd_id=%d", $tschd_id);
        
            $result = mysql_query($query, $this->db_link) or die('Get scheduled test date error: ' . mysql_error());
        
            if(mysql_num_rows($result) > 0)
            {
                $retVal = mysql_fetch_array($result);
            }
        
            return $retVal;
        }
        
        public function PopulateActiveTestsBySchedulerId($scheduler_id, $time_zone)
        {
            $query = sprintf("select tsession.*, tschedule.scheduled_on from test_session tsession join test_schedule tschedule on tschedule.schd_id = tsession.tschd_id where tschedule.scheduler_id='%s'", $scheduler_id);
       
            $result = mysql_query($query, $this->db_link) or die('populate running tests by scheduler id error: ' . mysql_error());
       
            $dtzone = new DateTimeZone($this->tzOffsetToName($time_zone));
            while($row = mysql_fetch_array($result))
            {
                //$total_questions        = count(explode(",",$row['attempted_answers'])) - 1;
                $ques_map				= json_decode($row['ques_map'],true);
            	$total_questions 		= count($ques_map);
            	
            	$unattempted_questions		= 0;
            	foreach($ques_map as $ques_id => $ans_ary)
            	{
            		if(count($ans_ary) == 1 && (in_array(-2, $ans_ary) || in_array(-1, $ans_ary)))
            		{
            			$unattempted_questions++;
            		}
            	}
                $attempted_questions    = $total_questions - $unattempted_questions;
                $marked_for_termination = ($row['forced_kill'] == 1)?"true":"false";
                printf("<tr>");
                printf("<td id='name%s'>%s (%s)</td>", $row['user_id'], $this->GetUserName($row['user_id']), $this->GetUserEmail($row['user_id']));
                printf("<td>%s</td>", $this->GetTestName($row['test_id']));
                printf("<td>%s (xID: %s)</td>", date("F j, Y", strtotime($row['scheduled_on'])), $row['tschd_id']);
                
                $dtime  = new DateTime($row['session_created']);
                $dtime->setTimezone($dtzone);
                printf("<td>%s</td>", $dtime->format("F j, Y - H:i:s"));
                printf("<td>%s/%s</td>", $attempted_questions, $total_questions);
                printf("<td>%02d:%02d:%02d</td>", ($row['cur_chronological_time']/3600),($row['cur_chronological_time']/60%60), $row['cur_chronological_time']%60);
                printf("<td>%s</td>", ucwords($marked_for_termination));
                printf("<td><input class='btn btn-sm btn-primary' type='button' id='%s;%s;%s;%s' value='Conclude Test' onclick='ShowConfirmation(this);'></td>", $row['tsession_id'], $row['user_id'], $row['tschd_id'], $row['test_id']);
                printf("</tr>");
            }
       
        }
        
        public function GetPermissions($user_id)
        {
	        $per = 0;
	       
	        $query = sprintf("select permissions  from users where user_id='%s'", $user_id);
	        $result = mysql_query($query, $this->db_link) or die('Get Permissions Error : ' . mysql_error());
	        
	        if(mysql_num_rows($result) > 0)
	        {
	        	$row =    mysql_fetch_array($result);
	        	$per = $row['permissions'];
	        }
	        
	        return $per;
        }
       
        public function GetOwnerId($user_id)
        {
            $owner_id = 0;
           
            $query = sprintf("select owner_id  from users where user_id='%s'", $user_id);
            $result = mysql_query($query, $this->db_link) or die('Get Owner Id Error: ' . mysql_error());
            
            if(mysql_num_rows($result) > 0)
            {
                $row =    mysql_fetch_array($result);
                $owner_id = $row['owner_id'];
            }
            
            return $owner_id;
        }
        
        public function GetDistLangFromQues($user_id=null)
        {
        	$LangAry = array();
        	
        	$query = "";
        	if($user_id == null)
        	{
	        	$query = sprintf("select distinct language from question where public=1");
        	}
        	else 
        	{
        		$query = sprintf("select distinct language from question where user_id='%s'", $user_id);
        	}
        	
        	//echo ($query);
            $result = mysql_query($query, $this->db_link) or die('Get Dist Lang From Ques: ' . mysql_error());
            
            $index = 0;
            while($row = mysql_fetch_array($result))
            {
            	$LangAry[$index] = $row["language"];
            	$index++;
            }
            
            return $LangAry;
        }
        
        public function InsertIntoTestInstruction($test_id, $instrAry)
        {
        	foreach ($instrAry as $language => $instuction)
        	{
        		$query = sprintf("insert into test_instructions() values('%s', '%s', '%s')", $test_id, mysql_real_escape_string($instuction), $language);
        		//echo $query."<br />";
        		
        		$result = mysql_query($query, $this->db_link) or die('Insert Into Test Instruction ('+$language+') error: ' . mysql_error());
        	}
        }
        
        public function InsertOptions($row, $ques_id)
        {
            $option_ary = array();
            $answers    = "";
            $index      = 0;
			
            $comma_pos = strpos($row[CConfig::$QUES_XLS_HEADING_ARY["Answer"]], ",");
			
            if($comma_pos !== false)
            {
                    $answers = explode(",", $row[CConfig::$QUES_XLS_HEADING_ARY["Answer"]]);
            }
            else
            {
                    $answers = $row[CConfig::$QUES_XLS_HEADING_ARY["Answer"]];
            }
			
            end($row);
            $last_key = key($row);
            for($opt_index = CConfig::$QUES_XLS_HEADING_ARY["Option 1"], $ans_index = 1; $opt_index <= $last_key; $opt_index++, $ans_index++)
            {
                    $ans = 0;

                    $option_ary[$index]['option'] = base64_encode($row[$opt_index]);

                    if(is_array($answers))
                    {
                            if(in_array($ans_index, $answers))
                            {
                                    $ans = 1;
                            }
                    }
                    else if($ans_index == $answers)
                    {
                            $ans = 1;
                    }
                    $option_ary[$index]['answer'] = $ans;

                    $index++;
            }

            $query = sprintf("update question set options = '%s' where ques_id = '%s'", json_encode($option_ary), $ques_id);

            $result =  mysql_query($query, $this->db_link) or die('Insert Options error : ' . mysql_error());

            return $result;
        }
        
        public function InsertOptionsWithWeightage($row, $ques_id)
        {
        	$option_ary = array();
        	$index      = 0;
        		
        	$weightage_ary = explode(",", $row[CConfig::$QUES_XLS_HEADING_ARY["Answer"]]);
        		
        	end($row);
        	$last_key = key($row);
        	for($opt_index = CConfig::$QUES_XLS_HEADING_ARY["Option 1"], $weight_index = 0; $opt_index <= $last_key; $opt_index++, $weight_index++)
        	{
        		$option_ary[$index]['option'] = base64_encode($row[$opt_index]);
        	
        		$option_ary[$index]['weightage'] = $weightage_ary[$weight_index];
        	
        		$index++;
        	}
        	
        	$query = sprintf("update question set options = '%s' where ques_id = '%s'", json_encode($option_ary), $ques_id);
        	
        	$result =  mysql_query($query, $this->db_link) or die('Insert Options With Weightage error : ' . mysql_error());
        	
        	return $result;
        }
        
        public function InsertEQRangeAnalysis($data_ary)
        {
        	$query = sprintf("insert into eq_range_analysis values %s", implode(",", $data_ary));
        	
        	$result =  mysql_query($query, $this->db_link) or die('Insert EQ Range Analysis error : ' . mysql_error());
        }
        
        public function PopulateRcdDirTitles($user_id, $question_type)
        {
                $query = sprintf("select DISTINCT topic.topic_name, question.linked_to, question.subject_id, question.language from topic join question on question.topic_id=topic.topic_id where question.user_id='%s' and question.ques_type='%s'", $user_id,$question_type);

                //echo "<option>".$query."</option>";
                $result = mysql_query($query, $this->db_link) or die('Get Rc Para Name error : ' . mysql_error());

                while($row=mysql_fetch_array($result))
                {
                        echo"<option value='".$row['linked_to'].";".$row['topic_name'].";".$this->GetSubjectName($row['subject_id']).";".$row['language']."'>".$row['topic_name']."</option>";
                }
        }
        
        public function GetTestType($test_id)
        {
        	$retVal = 0;
        		
        	$query = sprintf("select test_type from test where test_id='%s'", $test_id);
        		
        	$result = mysql_query($query, $this->db_link) or die('Get Test Type error : ' . mysql_error());
        		
        	if(mysql_num_rows($result) > 0)
        	{
        		$row = mysql_fetch_array($result);
        
        		$retVal = $row['test_type'];
        	}
        	return $retVal;
        }
        
        public function GetTestInstructions($test_id)
        {
            $retAry = array();
           
            $query = sprintf("select * from test_instructions where test_id='%s'", $test_id);
           
            $result = mysql_query($query, $this->db_link) or die('Get Test Instructions error: ' . mysql_error());
           
            while($row = mysql_fetch_array($result))
            {
                $retAry[$row['language']] = $row['instruction'];
            }
            return $retAry;
        }
        
        public function MySQL_MD5($str)
        {
        	$sRet = "";
        	
        	$query = sprintf("select MD5('%s') as MD5_STR", $str);
           
            $result = mysql_query($query, $this->db_link) or die('MySQL_MD5 error: ' . mysql_error());
            
            if(mysql_num_rows($result) > 0)
            {
            	$row = mysql_fetch_array($result);
            	$sRet = $row['MD5_STR'];
            }
            
        	return $sRet;
        }
	
		public function IsProgrammingCodeValid($code)
        {
        	$retVal = true;
        	$no_of_start_codes = substr_count(trim(strtoupper($code)), CConfig::OPER_CODE_START);
        	$no_of_end_codes = substr_count(trim(strtoupper($code)), CConfig::OPER_CODE_END);
        		
        	if($no_of_start_codes != $no_of_end_codes)
        	{
        		if($no_of_start_codes > $no_of_end_codes)
        		{
        			$retVal = false;
        		}
        		else
        		{
        			$retVal = false;
        		}
        	}
        	else
        	{
        		$code_count = 1;
        		$code_start_search = 0;
        		$code_end_search   = -1;
        		while($code_count <= $no_of_start_codes)
        		{
        			$code_start_pos = stripos(trim($code), CConfig::OPER_CODE_START, $code_start_search);
        			if($code_start_pos !== false)
        			{
        				$code_end_pos = stripos(trim($code), CConfig::OPER_CODE_END, $code_start_pos);
        				if($code_end_pos === false)
        				{
        					$retVal = false;
        				}
        				else if($code_end_pos == $code_end_search)
        				{
        					$retVal = false;
        				}
        				$code_end_search = $code_end_pos;
        			}
        			$code_start_search = ($code_start_pos + 10);
        			$code_count++;
        		}
        	}
        	return $retVal;
        }
        
        public function GetQuestionTag($tag_id)
        {
        	$retVal = "";
        	
        	$query = sprintf("select tag from question_tag where tag_id='%s'", $tag_id);
        	
        	$result = mysql_query($query, $this->db_link) or die('Get Question Tag Error: ' . mysql_error());
        	
        	if(mysql_num_rows($result) > 0)
        	{
        		$row = mysql_fetch_array($result);
        		
        		$retVal = $row['tag'];
        	}
        	
        	return $retVal;
        }
        
        public function GetReconcileQuestions($language, $tag_id, $subject_id, $topic_id, $user_id = NULL) 
        {
        	$retVal = array();
        	
        	$user_cond = "and public = 1";
        	if(!empty($user_id))
        	{
        		$user_cond = sprintf("and user_id='%s'", $user_id);
        	}
        	 
        	$query  = sprintf("select DISTINCT * from question where language='%s' %s and subject_id='%s' and topic_id='%s' %s order by ques_id", $language, !empty($tag_id)?"and tag_id='".$tag_id."'":"", $subject_id, $topic_id, $user_cond);
        	
        	//echo $query;
        	$result = mysql_query($query, $this->db_link) or die('Get Question Type By Language Error: ' . mysql_error());
        	
        	$qIndex = 0;
        	while($row = mysql_fetch_array($result))
        	{
        		$retVal[$qIndex]['ques_id'] 	  = $row['ques_id'];
        		$retVal[$qIndex]['question'] 	  = $row['question'];
        		$retVal[$qIndex]['linked_to'] 	  = $row['linked_to'];
        		$retVal[$qIndex]['ques_type'] 	  = $row['ques_type'];
        		$retVal[$qIndex]['difficulty_id'] = $row['difficulty_id'];
        		$retVal[$qIndex]['explanation']   = $row['explanation'];
        		$qIndex++;
        	}
        	
        	return $retVal;
        }
        
        public function GetReconcileQuestionSet($language, $user_id = NULL)
        {
        	$retVal = array();
        	 
        	$user_cond = "and public = 1";
        	if(!empty($user_id))
        	{
        		$user_cond = sprintf("and user_id='%s'", $user_id);
        	}
        	
        	$query = sprintf("select DISTINCT question_tag.tag_id, tag from question_tag join question on question_tag.tag_id = question.tag_id where question.language='%s' %s", $language, $user_cond);
        	
        	$result = mysql_query($query, $this->db_link) or die('Get Reconcile Question Set Error: ' . mysql_error());
        	
        	while($row = mysql_fetch_array($result))
        	{
        		$retVal[$row['tag_id']] = $row['tag'];
        	}
        	
        	return $retVal;
        }
        
        public function GetQuesOptions($ques_id)
        {
        	$retVal = array();
        	
        	$query = sprintf("select options from question where ques_id='%s'", $ques_id);
        	
        	$result = mysql_query($query, $this->db_link) or die('Get Ques Options Error: ' . mysql_error());
        	
        	if(mysql_num_rows($result) > 0)
        	{
        		$row 	= mysql_fetch_array($result);
        		$retVal = json_decode($row['options'], true);
        	}
        	
        	return $retVal;
        }
        
        public function GetBatchId($batch_name,$owner_id)
        {
        	$nRet  = null;
        	$query = sprintf("select batch_id from batch where batch_name='%s' and owner_id='%s'", $batch_name, $owner_id);
        		
        	$result = mysql_query($query, $this->db_link) or die('Get Batch Id error : ' . mysql_error());
        		
        	if(mysql_num_rows($result) > 0)
        	{
        		$row  = mysql_fetch_assoc($result);
        		$nRet = $row['batch_id'];
        	}
        		
        	return $nRet;
        }
        
        public function InsertBatch($batch_name, $owner_id, $description)
        {
        	$query = sprintf("insert into batch (batch_name,owner_id,description) values('%s', '%s','%s') ", mysql_real_escape_string($batch_name), $owner_id, mysql_real_escape_string($description));
        
        	$result = mysql_query($query, $this->db_link) or die('Insert batch error : ' . mysql_error());
        	
        	return mysql_insert_id();	
        }
        
        public function GetBatches($owner_id)
        {
        	$batch_ary = array();
        	
        	$query = sprintf("select batch_id, batch_name, description from batch where owner_id='%s'", $owner_id);
        		
        	$result = mysql_query($query, $this->db_link) or die('Get batches error : ' . mysql_error());
        		
        	while($row = mysql_fetch_array($result))
        	{
        		$batch_ary[$row['batch_id']]['batch_name'] = $row['batch_name'];
        		$batch_ary[$row['batch_id']]['description'] = $row['description'];
        	}
        	return $batch_ary;
        }
        
        public function PopulateBatches($owner_id)
        {
        	$batch_array = $this->GetBatches($owner_id);
        	
        	$batch_id_array = array_keys($batch_array);
        	
        	$query = sprintf("select * from users where locate('".$owner_id."',owner_id) AND user_type=%d", CConfig::UT_INDIVIDAL);
        		
        	$result = mysql_query($query, $this->db_link) or die('Get candidate count by batch : ' . mysql_error());
        	
        	while($row = mysql_fetch_array($result))
        	{
        		$cand_batch_ary = json_decode($row['batch'], true);
        		
        		$common_ary = array_intersect($batch_id_array, $cand_batch_ary);
        		
        		if(count($common_ary) > 0)
        		{
        			foreach($common_ary as $batch_id)
        			{
        				if(!isset($batch_array[$batch_id]['cand_count']))
        				{
        					$batch_array[$batch_id]['cand_count'] = 1;
        				}
        				else
        				{
        					$batch_array[$batch_id]['cand_count']++;
        				}
        			}
        		}
        		else 
        		{
        			if(!isset($batch_array[CConfig::CDB_ID]['cand_count']))
        			{
        				$batch_array[CConfig::CDB_ID]['cand_count'] = 1;
        			}
        			else
        			{
        				$batch_array[CConfig::CDB_ID]['cand_count']++;
        			}
        		}
        	}
        	
        	$batch_array[CConfig::CDB_ID]['batch_name'] = CConfig::CDB_NAME;
        	$batch_array[CConfig::CDB_ID]['description'] = CConfig::CDB_DESCRIPTION;
        	
        	ksort($batch_array, SORT_NUMERIC);
        	foreach($batch_array as $batch_id=>$info)
        	{
        		printf("<tr id='%s' onclick='SetSelectedRowId(this);'>\n", $batch_id);
        		printf("<td>%s</td>\n", $info['batch_name']);
        		printf("<td>%s</td>\n", !empty($info['cand_count'])?$info['cand_count']:0);
        		printf("<td>%s</td>\n", !empty($info['description'])?$info['description']:"Not Available");
        		printf("</tr>\n");
        	}
        }
        
        public function UpdateCandidateBatchByEmail($email, $batch_id)
        {
        	$query = sprintf("select batch from users where email='%s'", $email);
        		
        	$result = mysql_query($query, $this->db_link) or die('Update Candidate Batch By Email error 1 : ' . mysql_error());
        		
        	if(mysql_num_rows($result) > 0)
        	{
        		$row = mysql_fetch_array($result);
        
        		if(!empty($row['batch']))
        		{
        			$batch_array = json_decode($row['batch'], true);
        				
        			if(!in_array($batch_id, $batch_array))
        			{
        				array_push($batch_array, $batch_id);
        
        				$query_inner = sprintf("update users set batch='%s' where email='%s'", json_encode($batch_array), $email);
        
        				$result_inner = mysql_query($query_inner, $this->db_link) or die('Update Candidate Batch By Email error 2 : ' . mysql_error());
        			}
        		}
        	}
        }
        
		public function GetCandidateBatches($candidate_id)
        {
        	$retVal = array();
        	 
        	$query = sprintf("select batch from users where user_id='%s'", $candidate_id);
        	 
        	$result = mysql_query($query, $this->db_link) or die('Get Candidate Batches error : ' . mysql_error());
        	 
        	if(mysql_num_rows($result) > 0)
        	{
        		$row = mysql_fetch_array($result);
        		
        		$retVal = json_decode($row['batch'], true);
        	}
        	 
        	return $retVal;
        }
        
        public function GetBatchName($batch_id, $owner_id)
        {
        	$retVal = "";
        	
        	$query = sprintf("select batch_name from batch where owner_id='%s' and batch_id='%s'", $owner_id, $batch_id);
        	
        	$result = mysql_query($query, $this->db_link) or die('Get batch name error : ' . mysql_error());
        	
        	if(mysql_num_rows($result) > 0)
        	{
        		$row = mysql_fetch_array($result);
        		
        		$retVal = $row['batch_name'];
        	}
        	return $retVal;
        }
        
        public function IsBatchExists($batch_name, $owner_id, $batch_id = "")
        {
        	$bRet = false;
        	
        	$batch_id_cond = "";
        	if(!empty($batch_id))
        	{
        		$batch_id_cond = sprintf(" and batch_id <> '%s'", $batch_id);
        	}
        	
        	$query = sprintf("select * from batch where lower(batch_name) = '%s' and owner_id='%s' %s", strtolower($batch_name), $owner_id, $batch_id_cond);
        	
        	$result = mysql_query($query, $this->db_link) or die('Is batch exists error : ' . mysql_error());
        	
        	if(mysql_num_rows($result) > 0)
        	{
        		$bRet = true;
        	}
        	return $bRet;
        }
        
        public function UpdateBatch($batch_id, $new_batch_name, $new_description)
        {
        	$query = sprintf("update batch set batch_name='%s', description='%s' where batch_id='%s'", mysql_real_escape_string($new_batch_name), mysql_real_escape_string($new_description), $batch_id);
        	
        	$result = mysql_query($query, $this->db_link) or die('Update batch name error : ' . mysql_error());
        }
        
        public function DeleteBatch($batch_id, $owner_id)
        {
        	$query_users = sprintf("update users set owner_id=replace(owner_id, '%s', ''), batch = CASE\n", $owner_id);
        	$query_users .= sprintf("WHEN batch like '[%s,%%' THEN replace(batch, '[%s,', '[')\n", $batch_id, $batch_id);
        	$query_users .= sprintf("WHEN batch like '%%,%s,%%' THEN replace(batch, ',%s,', ',')\n", $batch_id, $batch_id);
        	$query_users .= sprintf("WHEN batch like '%%,%s]' THEN replace(batch, ',%s]', ']')\n", $batch_id, $batch_id);
        	$query_users .= sprintf("END\n");
        	$query_users .= sprintf("where locate('%s', owner_id) and (locate('[%s,', batch) or locate(',%s,', batch) or locate(',%s]', batch))", $owner_id, $batch_id, $batch_id, $batch_id);
        	//echo $query_users;
        	
        	$result_users = mysql_query($query_users, $this->db_link) or die('Delete batch error 1: ' . mysql_error());
        	
        	$query_batch = sprintf("delete from batch where batch_id='%s'", $batch_id);
        	
        	$result_batch = mysql_query($query_batch, $this->db_link) or die('Delete batch error 2: ' . mysql_error());
        }
        
        public function ChangeCandidateBatch($old_batch_id, $new_batch_id, $owner_id, $cand_ids)
        {
        	$query = "";
        	
        	if($old_batch_id == CConfig::CDB_ID)
        	{
        		$query = sprintf("update users set batch = replace(batch, ']', ',%s]') where locate('%s', owner_id) and user_id in (%s)", $new_batch_id, $owner_id, $cand_ids);
        	}
        	else if($new_batch_id == CConfig::CDB_ID)
        	{
        		
        		$query  = sprintf("update users set batch = CASE\n");
        		$query .= sprintf("WHEN batch like '[%s,%%' THEN replace(batch, '[%s,', '[')\n", $old_batch_id, $old_batch_id);
        		$query .= sprintf("WHEN batch like '%%,%s,%%' THEN replace(batch, ',%s,', ',')\n", $old_batch_id, $old_batch_id);
        		$query .= sprintf("WHEN batch like '%%,%s]' THEN replace(batch, ',%s]', ']')\n", $old_batch_id, $old_batch_id);
        		$query .= sprintf("END\n");
        		$query .= sprintf("where locate('%s', owner_id) and user_id in (%s)", $owner_id, $cand_ids);
        	}
        	else 
        	{
	        	$query = sprintf("update users set batch = CASE\n");
	        	$query .= sprintf("WHEN batch like '[%s,%%' THEN replace(batch, '[%s,', '[%s,')\n", $old_batch_id, $old_batch_id, $new_batch_id);
	        	$query .= sprintf("WHEN batch like '%%,%s,%%' THEN replace(batch, ',%s,', ',%s,')\n", $old_batch_id, $old_batch_id, $new_batch_id);
	        	$query .= sprintf("WHEN batch like '%%,%s]' THEN replace(batch, ',%s]', ',%s]')\n", $old_batch_id, $old_batch_id, $new_batch_id);
	        	$query .= sprintf("END\n");
	        	$query .= sprintf("where locate('%s', owner_id) and user_id in (%s)", $owner_id, $cand_ids);
        	}
        	
        	$result = mysql_query($query, $this->db_link) or die('Shift candidate batch error: ' . mysql_error());
        }
        
        public function GetOrgIdByTestId($test_id)
        {
        	$retVal = "";
        
        	$query = sprintf("select users.organization_id from users join test on test.owner_id = users.user_id where test.test_id='%s'", $test_id);
        
        	$result = mysql_query($query, $this->db_link) or die("Get Org Id By Test Id Error: ".mysql_error($this->db_link)) ;
        
        	if(mysql_num_rows($result) > 0)
        	{
        		$row = mysql_fetch_array($result);
        		$retVal = $row['organization_id'];
        	}
        
        	return $retVal;
        }
        
        public function GetScheduledTestAnalytics($from_date, $to_date, $test_id, $scheduler_id)
        {
        	$retAry = array();
        	 
        	$query = sprintf("SELECT DATE_FORMAT(test_schedule.scheduled_on, '%%b %%d, %%Y') as schedule_date,test_schedule.user_list , count( test_pnr ) AS completed FROM `test_schedule` LEFT JOIN result ON test_schedule.schd_id = result.tschd_id where test_schedule.scheduler_id='%s' AND DATE(test_schedule.scheduled_on) >= '%s' AND DATE(test_schedule.scheduled_on) <= '%s' AND test_schedule.test_id='%s' GROUP BY test_schedule.schd_id", $scheduler_id, $from_date, $to_date, $test_id);
        	
        	$result = mysql_query($query, $this->db_link) or die("Get Org Id By Test Id Error: ".mysql_error($this->db_link));
        	
        	while($row = mysql_fetch_array($result))
        	{
        		if(isset($retAry[$row['schedule_date']]))
        		{
        			$retAry[$row['schedule_date']]['attempted'] += $row['completed'];
        			$retAry[$row['schedule_date']]['scheduled'] += count(explode(";",$row['user_list'])) - 1;
        		}
        		else 
        		{
        			$retAry[$row['schedule_date']] = array();
        			$retAry[$row['schedule_date']]['attempted'] = $row['completed'];
        			$retAry[$row['schedule_date']]['scheduled'] = count(explode(";",$row['user_list'])) - 1;
        		}
        	}
        	return $retAry;
        }
        
        public function GetTestUserRatings($test_id)
        {
        	$retVal = "";
        	 
        	$query = sprintf("select user_ratings from test where test_id='%s'", $test_id);
        	 
        	$result = mysql_query($query, $this->db_link) or die("Get Test User Ratings Error: ".mysql_error($this->db_link)) ;
        	 
        	if(mysql_num_rows($result) > 0)
        	{
        		$row = mysql_fetch_array($result);
        
        		$retVal = $row["user_ratings"];
        	}
        	return $retVal;
        }
        
        public function RateTest($test_id, $user_ratings)
        {
        	$test_user_ratings = json_decode($this->GetTestUserRatings($test_id), true);
        	 
        	if(empty($test_user_ratings))
        	{
        		$test_user_ratings = array($user_ratings);
        	}
        	else
        	{
        		array_push($test_user_ratings, $user_ratings);
        	}
        	 
        	$final_rating = array_sum($test_user_ratings)/count($test_user_ratings);
        	 
        	$query = sprintf("update test set user_ratings='%s', final_rating=%1.2f where test_id='%s'", json_encode($test_user_ratings), $final_rating, $test_id);
        	 
        	$result = mysql_query($query, $this->db_link) or die("Rate Test Error: ".mysql_error($this->db_link)) ;
        }
        
        public function GetOrgIdByUserId($user_id)
        {
        	$retVal = "";
        	 
        	$query = sprintf("select organization_id from users where user_id='%s'", $user_id);
        	 
        	$result = mysql_query($query, $this->db_link) or die("Get Org Id By User Id Error: ".mysql_error($this->db_link)) ;
        	 
        	if(mysql_num_rows($result) > 0)
        	{
        		$row = mysql_fetch_array($result);
        
        		$retVal = $row['organization_id'];
        	}
        	return $retVal;
        }
        
        public function GetFreeUsersByOrgId($organization_id)
        {
        	$retAry = array();
        	 
        	$query = sprintf("select free_user.*,fut1.test_pnr, fut1.test_id from free_user join free_user_test fut1 on fut1.free_user_id=free_user.free_user_id where fut1.organization_id = '%s' and fut1.last_updated=(select max(fut2.last_updated) from free_user_test fut2 where fut2.test_id = fut1.test_id and fut2.free_user_id = fut1.free_user_id)", $organization_id);
        
        	$result = mysql_query($query, $this->db_link) or die("Get Free Users By Org Id Error: ".mysql_error($this->db_link));
        	 
        	$i = 0;
        	 
        	$test_name_ary = array();
        	while($row = mysql_fetch_array($result))
        	{
        		$owner_org_ids = json_decode($row['owner_org_ids'], true);
        
        		if(!isset($test_name_ary[$row['test_id']]))
        		{
        			$test_name_ary[$row['test_id']] = $this->GetTestName($row['test_id']);
        		}
        
        		if(!in_array($organization_id, $owner_org_ids))
        		{
        			$retAry[$i]['name'] = sprintf("<td>%s********</td>", substr($row['name'], 0, 2));
        			$retAry[$i]['email'] = sprintf("<td>%s********@%s</td>", substr($row['email'], 0, 2), end(explode("@",$row['email'])));
        			$retAry[$i]['phone'] = sprintf("<td>%s********</td>", substr($row['phone'], 0, 2));
        			$retAry[$i]['city'] = sprintf("<td>%s********</td>", substr($row['city'], 0, 2));
        			$retAry[$i]['test'] = sprintf("<td>%s</td>", $test_name_ary[$row['test_id']]);
        			$retAry[$i]['enable_info'] = sprintf("<td><input onclick='ShowInfoModal(this, false);' type='button' class='btn btn-sm btn-primary' value='Enable Info' free_user_id='%s'/></td>", $row['free_user_id']);
        			$retAry[$i++]['result'] = sprintf("<td><img width='35' align='top' height='35' src='../../images/export_pdf.jpg' title='Test DNA Analysis'/>&nbsp;&nbsp;&nbsp;<img width='35' align='top' height='35' src='../../images/export_pdf.jpg' title='Result Inspection'/></td>");
        		}
        		else
        		{
        			$retAry[$i]['name'] = sprintf("<td>%s</td>", $row['name']);
        			$retAry[$i]['email'] = sprintf("<td>%s</td>", $row['email']);
        			$retAry[$i]['phone'] = sprintf("<td>%s</td>", $row['phone']);
        			$retAry[$i]['city'] = sprintf("<td>%s</td>", $row['city']);
        			$retAry[$i]['test'] = sprintf("<td>%s</td>", $test_name_ary[$row['test_id']]);
        			$retAry[$i]['enable_info'] = sprintf("<td>Enabled</td>");
        			$retAry[$i++]['result'] = sprintf("<td><a href='ajax/ajax_download_result_pdf.php?test_pnr=%s&test_dna=1&name=%s&email=%s&from_free=1' target ='_blank'><img width='35' align='top' height='35' src='../../images/export_pdf.jpg' title='Test DNA Analysis'/></a>&nbsp;&nbsp;&nbsp;<a href='ajax/ajax_download_result_pdf.php?test_pnr=%s&inspect_result=1&name=%s&email=%s&from_free=1' target='_blank'><img width='35' align='top' height='35' src='../../images/export_pdf.jpg' title='Result Inspection'/></a></td>", $row['test_pnr'], urlencode($row['name']), urlencode($row['email']),  $row['test_pnr'], urlencode($row['name']), urlencode($row['email']));
        		}
        	}
        	return $retAry;
        }
        
        public function UpdateFreeUserOwnerOrg($free_user_id, $org_id, $free_user_id_ary, $bAll = false)
        {
        	$query = "";
        
        	if(!$bAll && !empty($free_user_id))
        	{
        		$ownerOrgs = $this->GetFreeUserOwnerOrgs($free_user_id);
        
        		if(empty($ownerOrgs))
        		{
        			$ownerOrgs = array();
        			array_push($ownerOrgs, $org_id);
        		}
        
        		$query = sprintf("update free_user set owner_org_ids = '%s' where free_user_id='%s'", json_encode($ownerOrgs), $free_user_id);
        	}
        	else if(!empty($free_user_id_ary) && $bAll)
        	{
        		$query = sprintf("update free_user set owner_org_ids = CASE\n");
        
        		$query .= sprintf("WHEN owner_org_ids like '%%]' THEN replace(owner_org_ids, ']', ',\"%s\"]')\n", $org_id);
        		 
        		$query .= sprintf("ELSE '%s' \n", json_encode(array($org_id)));
        
        		$query .= sprintf("END\n");
        
        		$query .= sprintf(" where free_user_id IN (%s)", implode(",", $free_user_id_ary));
        	}
        	 
        	$result = mysql_query($query, $this->db_link) or die("Update Free User Owner Org Error: ".mysql_error($this->db_link));
        	 
        	return mysql_affected_rows($this->db_link);
        }
        
        public function GetFreeUserOwnerOrgs($free_user_id)
        {
        	$retVal = array();
        	 
        	$query = sprintf("select owner_org_ids from free_user where free_user_id='%s'", $free_user_id);
        	 
        	$result = mysql_query($query, $this->db_link) or die("Get Free User Owner Orgs Error: ".mysql_error($this->db_link));
        	 
        	if(mysql_num_rows($result) > 0)
        	{
        		$row = mysql_fetch_array($result);
        
        		$retVal = json_decode($row['owner_org_ids'], true);
        	}
        	 
        	return $retVal;
        }
        
        public function GetDisabledFreeUserIds($org_id)
        {
        	$retAry = array();
        	 
        	$query = sprintf("select DISTINCT free_user.* from free_user join free_user_test on free_user.free_user_id = free_user_test.free_user_id where free_user.owner_org_ids not like '%%%s%%' and free_user_test.organization_id='%s'", $org_id, $org_id);
        	 
        	$result = mysql_query($query, $this->db_link) or die("Get Disabled Free User Ids Error: ".mysql_error($this->db_link));
        	 
        	while($row = mysql_fetch_array($result))
        	{
        		array_push($retAry, $row['free_user_id']);
        	}
        	return $retAry;
        }
        
        public function PublishTest($keywords,$description,$test_id)
        {
        	$query 		= 	sprintf("update test set keywords='%s', description='%s',is_published=1 where test_id='%s'", mysql_real_escape_string($keywords), mysql_real_escape_string($description),$test_id );
        	$result		=	mysql_query($query, $this->db_link) or die('Publish Test error : ' . mysql_error());
        	return $result;
        }
        
        public function UnPublishTest($test_id)
        {
        	$query 		= 	sprintf("update test set is_published=0 where test_id='%s'",$test_id );
        	$result		=	mysql_query($query, $this->db_link) or die('UnPublish Test error : ' . mysql_error());
        	return $result;
        }
        
        public function IsValidOfflineSchedule($schd_id, $user_id, $bNullScheduledOnCond = true)
        {
        	$retVal = null;
        	
        	$nullScheduledCond = "";
        	if($bNullScheduledOnCond)
        	{
        		$nullScheduledCond = sprintf("and scheduled_on IS NULL and time_zone IS NULL");
        	}
        	
        	$query = sprintf("select test_id from test_schedule where schd_id='%s' and scheduler_id='%s' and schedule_type='%s' %s", $schd_id, $user_id, CConfig::TST_OFFLINE, $nullScheduledCond);
        
        	$result = mysql_query($query, $this->db_link) or die('Is Valid Offline Schedule error : ' . mysql_error());
        	
        	if(mysql_num_rows($result) > 0)
        	{
        		$row = mysql_fetch_array($result); 
        		
        		$retVal = $row['test_id'];
        	}
        	
        	return $retVal;
        }
        
        public function GetLatestOTFAForm($test_id)
        {
        	$retAry = array();
        	 
        	$query = sprintf("select firstname, lastname, gender, dob, contact_no, city, state, country, edu_qualification from otfa_user_form_info where test_id = '%s' order by otfa_id desc limit 1", $test_id);
        	 
        	$result = mysql_query($query, $this->db_link) or die('Get Latest OTFA Form error : ' . mysql_error());
        	 
        	if(mysql_num_rows($result) > 0)
        	{
        		$retAry = mysql_fetch_assoc($result);
        	}
        	 
        	return $retAry;
        }
        
        public function InsertOTFACandidatesWithEmail($email_ary, $user_id, $batch_id)
        {
        	$candIDAry = array();
        	
        	$insertValueStringAry = array();
        	for($email_index = 0; $email_index < count($email_ary); $email_index++)
        	{
        		$status = "";
        		
        		$isEmailExists = $this->IsEmailExists($email_ary[$email_index], $user_id, $status, $batch_id);
        		
        		if($isEmailExists)
        		{
        			$cand_id = $this->GetUserIdByEmail("", $email_ary[$email_index]);
        			
        			if($status == 0)
        			{
        				$cand_batch_id_ary = $this->GetCandidateBatches($cand_id);
        				
        				$batch_array = $this->GetBatches($user_id);
        				 
        				$batch_id_array = array_keys($batch_array);
        				
        				$common_ary = array_intersect($batch_id_array, $cand_batch_id_ary);
        				
        				if(count($common_ary) > 0)
        				{
        					foreach($common_ary as $common_batch_id)
        					{
        						$this->ChangeCandidateBatch($common_batch_id, $batch_id, $user_id, "'".$cand_id."'");
        					}
        				}
        			}
        			array_push($candIDAry, $cand_id);
        		}
        		else 
        		{
        			$cand_id = CUtils::uuid() ;
        			$login_name = uniqid();
        			$insertValue = sprintf("('%s', '%s', '%s', '%s', '%s', '%s')", $cand_id, $user_id, CConfig::UT_INDIVIDAL, $login_name, $email_ary[$email_index], json_encode(array(CConfig::CDB_ID, intval($batch_id))));
        			array_push($insertValueStringAry, $insertValue);
        			array_push($candIDAry, $cand_id);
        		}
        	}
        	
        	if(!empty($insertValueStringAry))
        	{
        		$query = sprintf("insert into users(user_id,owner_id,user_type,login_name,email,batch) VALUES %s", implode(",", $insertValueStringAry));
        		
        		$result = mysql_query($query, $this->db_link) or die("Insert OTFA Candidates With Email Error: ".mysql_error($this->db_link));
        	}
        	return $candIDAry;
        }
        
        public function InsertOTFAUserForm($test_id, $schd_id, $batch_id, $form_data, $tpin_list = null)
        {
        	$query = null;
        	if(!empty($tpin_list))
        	{
        		$query = sprintf("insert into otfa_user_form_info(test_id, tschd_id, batch_id, firstname, lastname, gender, dob, contact_no, city, state, country, edu_qualification, tpin_list) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')", $test_id, $schd_id, $batch_id, 1, 1, (isset($form_data["GENDER"]) && $form_data["GENDER"] == 1)?1:0, (isset($form_data["DOB"]) && $form_data["DOB"] == 1)?1:0, (isset($form_data["CONTACT"]) && $form_data["CONTACT"] == 1)?1:0, (isset($form_data["CITY"]) && $form_data["CITY"] == 1)?1:0, (isset($form_data["STATE"]) && $form_data["STATE"] == 1)?1:0, (isset($form_data["COUNTRY"]) && $form_data["COUNTRY"] == 1)?1:0, (isset($form_data["EDU_QUA"]) && $form_data["EDU_QUA"] == 1)?1:0, $tpin_list);
        	}
        	else 
        	{
        		$query = sprintf("insert into otfa_user_form_info(test_id, tschd_id, batch_id, firstname, lastname, gender, dob, contact_no, city, state, country, edu_qualification) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')", $test_id, $schd_id, $batch_id, 1, 1, (isset($form_data["GENDER"]) && $form_data["GENDER"] == 1)?1:0, (isset($form_data["DOB"]) && $form_data["DOB"] == 1)?1:0, (isset($form_data["CONTACT"]) && $form_data["CONTACT"] == 1)?1:0, (isset($form_data["CITY"]) && $form_data["CITY"] == 1)?1:0, (isset($form_data["STATE"]) && $form_data["STATE"] == 1)?1:0, (isset($form_data["COUNTRY"]) && $form_data["COUNTRY"] == 1)?1:0, (isset($form_data["EDU_QUA"]) && $form_data["EDU_QUA"] == 1)?1:0);
        	}
        
        	$result = mysql_query($query, $this->db_link) or die("Insert OTFA User Form Error: ".mysql_error($this->db_link));
        }
        
        public function EmailOTFARegLink($email_ary, $user_id, $test_name, $tschd_id)
        {
        	$user_name = $this->GetUserName($user_id);
        	$org_info_ary = $this->GetUserOrgInfo($user_id);
        	$user_email = $this->GetUserEmail($user_id);
        	$objMail = new CEMail(CConfig::OEI_SUPPORT, $this->GetPasswordFromOfficialEMail(CConfig::OEI_SUPPORT));
        	
        	foreach($email_ary as $email)
        	{
        		$objMail->PrepAndSendOTFARegLinkMail($email, $test_name, $tschd_id, $user_name, $user_email, $org_info_ary['organization_name']);
        	}
        }
        
        public function GetOTFAFormByTestSchdId($tschd_id)
        {
        	$retAry = array();
        	
        	$query = sprintf("select test_id, batch_id, firstname, lastname, gender, dob, contact_no, city, state, country, edu_qualification, tpin_list from otfa_user_form_info where tschd_id = '%s'", $tschd_id);
        	
        	$result = mysql_query($query, $this->db_link) or die('Get OTFA Form By Test Schd Id error : ' . mysql_error());
        	
        	if(mysql_num_rows($result) > 0)
        	{
        		$retAry = mysql_fetch_assoc($result);
        	}
        	
        	return $retAry;
        }
        
        public function UpdateTPINInTestScheduleUserList($tschd_id, $tpin, $user_id)
        {
        	$query = sprintf("update test_schedule set user_list = replace(user_list,'%s', '%s') where schd_id='%s'", $tpin, $user_id, $tschd_id);
        	
        	$result = mysql_query($query, $this->db_link) or die('Update TPIN In Test Schedule User List error : ' . mysql_error());
        }
        
        public function IsValidEmail($user_id)
        {
        	$retVal = 0;
        		
        	$query = sprintf("select isvalid from users where user_id='%s'", $user_id);
        	
        	$result = mysql_query($query, $this->db_link) or die('Is Valid Email error : ' . mysql_error());
        	
        	if(mysql_num_rows($result) > 0)
        	{
        		$row = mysql_fetch_array($result);
        		$retVal = $row['isvalid'];
        	}
        	
        	return $retVal;
        }
        
        public function UpdateTestScheduleUserList($tschd_id, $user_id)
        {
        	$query = sprintf("update test_schedule set user_list = CONCAT(user_list, '%s;') where schd_id='%s'", $user_id, $tschd_id);
        
        	$result = mysql_query($query, $this->db_link) or die('Update Test Schedule User List error : ' . mysql_error());
        }
	}
?>