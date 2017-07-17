<?php 
	include_once(dirname(__FILE__)."/../database/config.php");
	include_once(dirname(__FILE__)."/../database/mcat_db.php");
	include_once(dirname(__FILE__)."/utils.php");

	require_once dirname(__FILE__)."/../3rd_party/php_excel_classes/PHPExcel/IOFactory.php";
	require_once dirname(__FILE__)."/../3rd_party/php_excel_classes/PHPExcel/Reader/Excel2007.php";

	class CQuesExcel
	{
		
		private $objDB;
		private $section_ary;
		private $topic_ary;
		private $topic_id_ary;
		private $difficulty_ary;
		private $max_questions;
		private $test_tag_id;
		private $pref_lang;
		private $subject;
		private $eq_test_id;
		
		
		////////////////////////////////////////
		// PRIVATE FUNCTIONS
		////////////////////////////////////////
		
		private function CountColumn($worksheet, $row_index)
		{
			$col_count = 0;
			foreach ($worksheet->getRowIterator() as $row)
			{
				if($row->getRowIndex() == $row_index)
				{
					$cellIterator = $row->getCellIterator();
					foreach ($cellIterator as $cell)
					{
						$col_count++;
					}
				}
			}
			return $col_count;
		}
		
		private function GetImageFromZip($zip_file, $img)
		{
			$retVal = NULL;
			if($zip_file['size'] > 0)
			{
				$zipFileTmp = $zip_file['tmp_name'];
				$zipFileName = substr($zip_file["name"] ,0,-4);
				$zip = new ZipArchive;
				if($zip->open($zipFileTmp) === TRUE)
				{
					$img_content = $zip->getFromName($zipFileName.'/'.$img);
		
					if(!$img_content)
					{
						$retVal = "Image link is not proper, please provide only valid image link";
					}
					else
					{
						$retVal = $img_content;
					}
					$zip->close();
				}
				else
				{
					$retVal = "Unable to open uploaded zip file";
				}
			}
			else
			{
				$retVal = "Zip file uploaded by you is empty";
			}
			return $retVal;
		}
		
		private function IsParaTitleExists($title, $user_id, $ques_type = null)
		{
			$retVal = false;
			
			if($this->objDB->IsTopicExists($title, $user_id, $ques_type))
			{
				$retVal = true;
			}
			return $retVal;
		}
		
		private function ValidateProgrammingCode($worksheet)
		{
			$generated_errors = "";
			foreach($worksheet->getRowIterator() as $row)
			{
				if($row->getRowIndex() > 1)
				{
					$cellIterator = $row->getCellIterator();
					$cell_index = 'A';
						
					foreach ($cellIterator as $cell)
					{
						if($cell_index == CConfig::$QUES_XLS_HEADING_ARY["Para Description"] || $cell_index == CConfig::$QUES_XLS_HEADING_ARY["Question"] || $cell_index >= CConfig::$QUES_XLS_HEADING_ARY["Explanation"])
						{
							$no_of_start_codes = substr_count(trim($cell->getValue()), CConfig::EA_OPER_CODE_START);
							$no_of_end_codes = substr_count(trim($cell->getValue()), CConfig::EA_OPER_CODE_END);
							
							if($no_of_start_codes != $no_of_end_codes)
							{
								if($no_of_start_codes > $no_of_end_codes)
								{
									$generated_errors .= "[".$cell_index.$row->getRowIndex()."](row ".$row->getRowIndex(). " : column ".$cell_index.") :   Please end the code properly using. ".CConfig::EA_OPER_CODE_END.".;";
								}
								else 
								{
									$generated_errors .= "[".$cell_index.$row->getRowIndex()."](row ".$row->getRowIndex(). " : column ".$cell_index.") :   Please start the code properly using. ".CConfig::EA_OPER_CODE_START.". Code can not be ended without starting.;";
								}
							}
							else 
							{
								$code_count = 1;
								$code_start_search = 0;
								$code_end_search   = -1;
								while($code_count <= $no_of_start_codes)
								{
									$code_start_pos = stripos(trim($cell->getValue()), CConfig::EA_OPER_CODE_START, $code_start_search);
									if($code_start_pos !== false)
									{
										$code_end_pos = stripos(trim($cell->getValue()), CConfig::EA_OPER_CODE_END, $code_start_pos);
										if($code_end_pos === false)
										{
											$generated_errors .= "[".$cell_index.$row->getRowIndex()."](row ".$row->getRowIndex(). " : column ".$cell_index.") :   Please end the code properly using. ".CConfig::EA_OPER_CODE_END.".;";
										}
										else if($code_end_pos == $code_end_search)
										{
											$generated_errors .= "[".$cell_index.$row->getRowIndex()."](row ".$row->getRowIndex(). " : column ".$cell_index.") :   Please do not use coding notations inside another coding notations.;";
										}
										$code_end_search = $code_end_pos;
									}
									$code_start_search = ($code_start_pos + 10);
									$code_count++;
								}
							}
						}
						$cell_index++;
					}
				}
			}
			return $generated_errors;
		}
		
		private function ValidateForTranslation($worksheet)
		{
			$s_no_ary 		  = array();
			$lang_ary 		  = array();
			$ans_ary		  = array();
			$diff_ary		  = array();
			$row_index 		  = 0;
			$generated_errors = "";
			
			foreach($worksheet->getRowIterator() as $row)
			{
				if($row->getRowIndex() > 1)
				{
					$cellIterator = $row->getCellIterator();
					$cell_index = 'A';
					
					foreach ($cellIterator as $cell)
					{
						if($cell_index == CConfig::$QUES_XLS_HEADING_ARY["S No"])
						{
							if(preg_match('/^\d+$/',trim($cell->getValue())) != 0)
							{
								$s_no_ary[$row_index] = trim($cell->getValue());
							}
							else if(strtoupper(trim($cell->getValue())) == CConfig::OPER_XLS_COPY)
							{
								$s_no_ary[$row_index] = $s_no_ary[$row_index - 1];
							}
							else if(strtoupper(trim($cell->getValue())) == CConfig::EA_OPER_XLS_COPY)
							{
								$s_no_ary[$row_index] = $s_no_ary[$row_index - 1];
							}
						}
						else if($cell_index == CConfig::$QUES_XLS_HEADING_ARY["Language"])
						{
							if(strtoupper(trim($cell->getValue())) == CConfig::OPER_XLS_COPY)
							{
								$lang_ary[$row_index] = $lang_ary[$row_index - 1];
							}
							else if(strtoupper(trim($cell->getValue())) == CConfig::EA_OPER_XLS_COPY)
							{
								$lang_ary[$row_index] = $lang_ary[$row_index - 1];
							}
							else
							{
								$lang_ary[$row_index] = trim($cell->getValue());
							}
						}
						else if($cell_index == CConfig::$QUES_XLS_HEADING_ARY["Answer"])
						{
							if(strtoupper(trim($cell->getValue())) == CConfig::OPER_XLS_COPY)
							{
								$ans_ary[$row_index] = $ans_ary[$row_index - 1];
							}
							else if(strtoupper(trim($cell->getValue())) == CConfig::EA_OPER_XLS_COPY)
							{
								$ans_ary[$row_index] = $ans_ary[$row_index - 1];
							}
							else
							{
								$ans_ary[$row_index] = trim($cell->getValue());
							}
						}
						else if($cell_index == CConfig::$QUES_XLS_HEADING_ARY["Difficulty"])
						{
							if(strtoupper(trim($cell->getValue())) == CConfig::OPER_XLS_COPY)
							{
								$diff_ary[$row_index] = $diff_ary[$row_index - 1];
							}
							else if(strtoupper(trim($cell->getValue())) == CConfig::EA_OPER_XLS_COPY)
							{
								$diff_ary[$row_index] = $diff_ary[$row_index - 1];
							}
							else
							{
								$diff_ary[$row_index] = trim($cell->getValue());
							}
						}
						$cell_index++;
					}
					
					$s_no_reptd_ary = array_keys($s_no_ary, $s_no_ary[$row_index]);
					if(count($s_no_reptd_ary) > 1)
					{
						$prev_ques_cols = $this->CountColumn($worksheet, $s_no_reptd_ary[0] + 2);
						$cur_ques_cols  = $this->CountColumn($worksheet, $row_index + 2);
						if($cur_ques_cols != $prev_ques_cols)
						{
							$generated_errors .= "[row ".($row_index + 2)."] :   Number of options should always be equal for questions with same serial number. If you do not find such issue then please check specified row for blank cells.;";
						}
						
						if($ans_ary[$row_index] != $ans_ary[$s_no_reptd_ary[0]])
						{
							$generated_errors .= "[row ".($row_index + 2)."] :  Answer should always be same for questions with same serial number. If you do not find such issue then please check specified row for blank cells.;";
						}
						
						if($diff_ary[$row_index] != $diff_ary[$s_no_reptd_ary[0]])
						{
							$generated_errors .= "[row ".($row_index + 2)."] :  Difficulty level should always be same for questions with same serial number. If you do not find such issue then please check specified row for blank cells.;";
						}
					}
					$row_index++;
				}
			}
		
			$sno_lang_ary = array();
			for($sno_lang_index = 0; $sno_lang_index < count($s_no_ary); $sno_lang_index++)
			{
				$sno_lang = $lang_ary[$sno_lang_index].$s_no_ary[$sno_lang_index];
				
				if(in_array($sno_lang, $sno_lang_ary))
				{
					$generated_errors .= "[row ".($sno_lang_index + 2)."] :   This row contains same combination of serial number and language which already exists in the sheet. You have enterd same question more than once in same language which is not acceptable.;";
				}
				
				array_push($sno_lang_ary, $sno_lang);
			}
			return $generated_errors;
		}
		
		private function ValidateRCDirSubTopics($worksheet)
		{
			$para_ary  		  = array();
			$sub_ary		  = array();
			$topic_ary		  = array();
			$sub_topic_ary    = array();
			$row_index	   	  = 0;
			$generated_errors = "";
			
			foreach ($worksheet->getRowIterator() as $row)
			{
				if($row->getRowIndex() > 1)
				{
					$cellIterator = $row->getCellIterator();
					$cell_index = 'A';
			
					foreach ($cellIterator as $cell)
					{
						if($cell_index == CConfig::$QUES_XLS_HEADING_ARY["Para Description"])
						{
							if(strtoupper(trim($cell->getValue())) == CConfig::OPER_XLS_COPY)
							{
								$para_ary[$row_index] = $para_ary[$row_index - 1];
							}
							else if(strtoupper(trim($cell->getValue())) == CConfig::EA_OPER_XLS_COPY)
							{
								$para_ary[$row_index] = $para_ary[$row_index - 1];
							}
							else 
							{
								$para_ary[$row_index] = trim($cell->getValue());
							}
						}
						else if($cell_index == CConfig::$QUES_XLS_HEADING_ARY["Subject"])
						{
							if(strtoupper(trim($cell->getValue())) == CConfig::OPER_XLS_COPY)
							{
								$sub_ary[$row_index] = $sub_ary[$row_index - 1];
							}
							else if(strtoupper(trim($cell->getValue())) == CConfig::EA_OPER_XLS_COPY)
							{
								$sub_ary[$row_index] = $sub_ary[$row_index - 1];
							}
							else
							{
								$sub_ary[$row_index] = strtolower(trim($cell->getValue()));
							}
						}
						else if($cell_index == CConfig::$QUES_XLS_HEADING_ARY["Topic"])
						{
							if(strtoupper(trim($cell->getValue())) == CConfig::OPER_XLS_COPY)
							{
								$topic_ary[$row_index] = $topic_ary[$row_index - 1];
							}
							else if(strtoupper(trim($cell->getValue())) == CConfig::EA_OPER_XLS_COPY)
							{
								$topic_ary[$row_index] = $topic_ary[$row_index - 1];
							}
							else
							{
								$topic_ary[$row_index] = strtolower(trim($cell->getValue()));
							}
						}
						$cell_index++;
					}
					$sub_topic_ary[$row_index] = $sub_ary[$row_index]." ".$topic_ary[$row_index];
					$row_index++;
				}
			}
			
			$reptd_topic_ary = array();
			for($para_index = 1; $para_index < count($para_ary); $para_index++)
			{
				if($para_ary[$para_index] == $para_ary[$para_index - 1])
				{
					if($sub_ary[$para_index] != $sub_ary[$para_index - 1] || $topic_ary[$para_index] != $topic_ary[$para_index - 1])
					{
						$generated_errors .= "[row ".($para_index + 2)."] :   All questions related to same directions or para should also contain same subject and same topic.They can not be changed.;";
					}
				}
				else 
				{
					if(in_array($topic_ary[$para_index], $reptd_topic_ary))
					{
						$generated_errors .= "[".CConfig::$QUES_XLS_HEADING_ARY["Topic"].($para_index + 2)."](row ".($para_index + 2)." : column ".CConfig::$QUES_XLS_HEADING_ARY["Topic"].") :   Please change the topic. Topic entered by you is already used with different directions or para in the same sheet.;";
					}
				}
				array_push($topic_ary[$para_index], $reptd_topic_ary);
			}
			return $generated_errors;
		}
		
		private function IsMCAQuestion($ques_ary)
		{
			$retVal = false;
			
			$comma_pos = strpos($ques_ary[CConfig::$QUES_XLS_HEADING_ARY["Answer"]], ",");
			if($comma_pos !== false)
			{
				$retVal = true;
			}
			return $retVal;
		}
		
		private function InsertNormalQuestions($worksheet, $user_id, $ques_type, $tag_id, $zip_file, $is_eq = false)
		{
			$data_table      = array();
			$group_title_ary = array();
			$s_no_ary		 = array();
			$row_index       = 0;
			foreach ($worksheet->getRowIterator() as $row)
			{
				if($row->getRowIndex() > 1)
				{
					$cellIterator = $row->getCellIterator();
					
					$cell_index  = 'A';
					$data_row    = array();
					$group_title = NULL; 
					
					foreach ($cellIterator as $cell)
					{
						$cell_value = trim($cell->getValue());
						$pos = stripos($cell_value, "#@MIPCAT_Img[");
						$ea_pos = stripos($cell_value, "#@EZEEASSES_Img[");
							
						if($pos !== false)
						{
							$img = trim(substr($cell_value, 13,-1));
							$data_row[$cell_index] = $this->GetImageFromZip($zip_file,$img);
						}
						else if($ea_pos !== false)
						{
							$img = trim(substr($cell_value, 16,-1));
							$data_row[$cell_index] = $this->GetImageFromZip($zip_file,$img);
						}
						else if(strtoupper($cell_value) == CConfig::OPER_XLS_COPY || strtoupper($cell_value) == CConfig::EA_OPER_XLS_COPY)
						{
							$data_row[$cell_index] = $data_table[$row_index - 1][$cell_index];
						}
						else if(strtoupper($cell_value) == CConfig::OPER_XLS_EMPTY || strtoupper($cell_value) == CConfig::EA_OPER_XLS_EMPTY)
						{
							$data_row[$cell_index] = "";
						}
						else 
						{
							$data_row[$cell_index] = str_ireplace(CConfig::EA_OPER_CODE_END,"</div>",str_ireplace(CConfig::EA_OPER_CODE_START,"<div class='mipcat_code_ques'>",str_replace(">",">;",str_replace("<","<",str_replace("&","&amp;",str_replace("’", "'", trim($cell_value)))))));
						}
						$data_table[$row_index][$cell_index] = $data_row[$cell_index];
						$cell_index++;
					}
					$row_index++;
					
					$s_no_key = array_search($data_row[CConfig::$QUES_XLS_HEADING_ARY["S No"]], $s_no_ary);
					if($s_no_key !== false)
					{
						$group_title = $group_title_ary[$s_no_key];
					}
					
					$mca = 0;
					if(!$is_eq)
					{
						if($this->IsMCAQuestion($data_row))
						{
							$mca = 1;
						}	
					}
					
					$group_title = $this->objDB->InsertQuestion($data_row, $user_id, $mca, $ques_type, $group_title, $tag_id, NULL, $is_eq);
					
					array_push($s_no_ary, $data_row[CConfig::$QUES_XLS_HEADING_ARY["S No"]]);
					array_push($group_title_ary, $group_title);
				}
			}
			return $row_index;
		}
		
		private function InsertRCDirQuestions($worksheet, $user_id, $ques_type, $tag_id, $tag, $zip_file, $is_eq = false)
		{
			$data_table      = array();
			$group_title_ary = array();
			$s_no_ary		 = array();
			$row_index       = 0;
			$rc_dir_id       = 0;
			foreach ($worksheet->getRowIterator() as $row)
			{
				if($row->getRowIndex() > 1)
				{
					$cellIterator = $row->getCellIterator();
						
					$cell_index  = 'A';
					$data_row    = array();
					$group_title = NULL;
						
					foreach ($cellIterator as $cell)
					{
						$cell_value = trim($cell->getValue());
						$pos = stripos($cell_value, "#@MIPCAT_Img[");
						$ea_pos = stripos($cell_value, "#@EZEEASSES_Img[");
							
						if($pos !== false)
						{
							$img = trim(substr($cell_value, 13,-1));
							$data_row[$cell_index] = $this->GetImageFromZip($zip_file,$img);
						}
						else if($pos !== false)
						{
							$img = trim(substr($cell_value, 16,-1));
							$data_row[$cell_index] = $this->GetImageFromZip($zip_file,$img);
						}
						else if(strtoupper($cell_value) == CConfig::OPER_XLS_COPY || strtoupper($cell_value) == CConfig::EA_OPER_XLS_COPY)
						{
							$data_row[$cell_index] = $data_table[$row_index - 1][$cell_index];
						}
						else if(strtoupper($cell_value) == CConfig::OPER_XLS_EMPTY || strtoupper($cell_value) == CConfig::EA_OPER_XLS_EMPTY)
						{
							$data_row[$cell_index] = "";
						}
						else
						{
							$data_row[$cell_index] = str_ireplace(CConfig::EA_OPER_CODE_END,"</div>",str_ireplace(CConfig::EA_OPER_CODE_START,"<div class='mipcat_code_ques'>",str_replace(">",">",str_replace("<","<",str_replace("&","&amp;",str_replace("’", "'", trim($cell_value)))))));
						}
						$data_table[$row_index][$cell_index] = $data_row[$cell_index];
						$cell_index++;
					}
					
					if($data_table[$row_index - 1][CConfig::$QUES_XLS_HEADING_ARY["Para Description"]] != $data_row[CConfig::$QUES_XLS_HEADING_ARY["Para Description"]])
					{
						if($ques_type == CConfig::QT_READ_COMP)
						{
							$rc_dir_id = $this->objDB->InsertReadComp($data_row[CConfig::$QUES_XLS_HEADING_ARY["Para Description"]]);
						}
						else if($ques_type == CConfig::QT_DIRECTIONS)
						{
							$rc_dir_id = $this->objDB->InsertDirectionsPara($data_row[CConfig::$QUES_XLS_HEADING_ARY["Para Description"]]);
						}
					}
					
					$row_index++;

					$s_no_key = array_search($data_row[CConfig::$QUES_XLS_HEADING_ARY["S No"]], $s_no_ary);
					if($s_no_key !== false)
					{
						$group_title = $group_title_ary[$s_no_key];
					}
					
					$mca = 0;
					if(!$is_eq)
					{
						if($this->IsMCAQuestion($data_row))
						{
							$mca = 1;
						}
					}
					
					if(!empty($tag))
					{
						$data_row[CConfig::$QUES_XLS_HEADING_ARY["Topic"]] = $tag."_".$data_row[CConfig::$QUES_XLS_HEADING_ARY["Topic"]];
					}
					
					$group_title = $this->objDB->InsertQuestion($data_row, $user_id, $mca, $ques_type, $group_title, $tag_id, $rc_dir_id, $is_eq);
					
					array_push($s_no_ary, $data_row[CConfig::$QUES_XLS_HEADING_ARY["S No"]]);
					array_push($group_title_ary, $group_title);
				}
			}
			return $row_index;
		}
		
		////////////////////////////////////////
		// PUBLIC FUNCTIONS
		////////////////////////////////////////
		
		public function __construct() 
		{
			$this->objDB 	   	  = new CMcatDB();
			$this->section_ary 	  = array();
			$this->topic_ary	  = array();
			$this->topic_id_ary	  = array();
			$this->difficulty_ary = array();
			$this->max_questions  = 0;
		}
		
		public function __destruct()
		{
			unset($this->objDB);
			unset($this->section_ary);
			unset($this->topic_ary);
			unset($this->difficulty_ary);
			unset($this->max_questions);
			unset($this->test_tag_id);
			unset($this->pref_lang);
			unset($this->subject);
		}
		
		public function ValidateQuesSheet($worksheet, $ques_type, $user_id, $tag, $zip_file = NULL, $is_eq = false)
		{
			$generated_errors = "";
			
			$lang_choice = "";
			$lang_index  = 0;
			
			$prev_ans = "";
			$prev_topic = "";
			
			$current_topic = "";
			
			foreach(CConfig::$TEST_LANGUAGES as $lang)
			{
				if($lang_index < count(CConfig::$TEST_LANGUAGES)-1)
				{
					$lang_choice .= $lang.", ";
				}
				else
				{
					$lang_choice .= $lang;
				}
				$lang_index++;
			}
			
			foreach ($worksheet->getRowIterator() as $row)
			{
				if($row->getRowIndex() > 1)
				{
					$col_count = $this->CountColumn($worksheet, $row->getRowIndex());
					if($col_count < count(CConfig::$QUES_XLS_HEADING_ARY))
					{
						$generated_errors .= "[row ".$row->getRowIndex()."]   Some value has been left blank in specified row.There should be atleast two options with each question.;";
					}
					else 
					{
						$cellIterator = $row->getCellIterator();
						$cell_index = 'A';
						
						foreach ($cellIterator as $cell)
						{
							$cell_val = str_replace("’", "'", trim($cell->getValue()));
							$pos = stripos($cell_val, "#@MIPCAT_Img[");
							$ea_pos = stripos($cell_value, "#@EZEEASSES_Img[");
							$img_content = "";
							$img_type = "";
								
							if($pos !== false)
							{
								$img = trim(substr($cell_val, 13,-1));
									
								$img_content = $this->GetImageFromZip($zip_file, $img);
									
								$img_type = CUtils::getMimeType($img_content);
							}
							else if($ea_pos !== false)
							{
								$img = trim(substr($cell_val, 16,-1));
									
								$img_content = $this->GetImageFromZip($zip_file, $img);
									
								$img_type = CUtils::getMimeType($img_content);
							}
								
							if(($pos !== false || $ea_pos !== false) && $img_type == "application/octet-stream" && ($ques_type != CConfig::QT_NORMAL || $cell_index != CConfig::$QUES_XLS_HEADING_ARY["Para Description"]) && $cell_index != CConfig::$QUES_XLS_HEADING_ARY["S No"] && $cell_index != CConfig::$QUES_XLS_HEADING_ARY["Language"] && $cell_index != CConfig::$QUES_XLS_HEADING_ARY["Answer"] && $cell_index != CConfig::$QUES_XLS_HEADING_ARY["Subject"] && $cell_index != CConfig::$QUES_XLS_HEADING_ARY["Topic"] && $cell_index != CConfig::$QUES_XLS_HEADING_ARY["Difficulty"])
							{
								$generated_errors .= "[".$cell_index.$row->getRowIndex()."](row ".$row->getRowIndex(). " : column ".$cell_index.") :   ".$img_content.".;";
							}
							else if(($pos !== false || $ea_pos !== false) && ($cell_index == CConfig::$QUES_XLS_HEADING_ARY["S No"] || $cell_index == CConfig::$QUES_XLS_HEADING_ARY["Language"] || $cell_index == CConfig::$QUES_XLS_HEADING_ARY["Answer"] || $cell_index == CConfig::$QUES_XLS_HEADING_ARY["Subject"] || $cell_index == CConfig::$QUES_XLS_HEADING_ARY["Topic"] || $cell_index == CConfig::$QUES_XLS_HEADING_ARY["Difficulty"]))
							{
								$generated_errors .= "[".$cell_index.$row->getRowIndex()."](row ".$row->getRowIndex(). " : column ".$cell_index.") :   Image is not allowed in specified cell. If you do not find such issue then please check specified row for blank cells.;";
							}
							else if($row->getRowIndex() == 2 && (strtoupper($cell_val) == CConfig::OPER_XLS_COPY || strtoupper($cell_val) == CConfig::EA_OPER_XLS_COPY))
							{
								$generated_errors .= "[".$cell_index.$row->getRowIndex()."](row ".$row->getRowIndex(). " : column ".$cell_index.") :   You can not use '".$cell_val."' for first time. It should be a meaningful content.;";
							}
							else if((strcasecmp($cell_val, CConfig::OPER_XLS_NA) == 0 || strcasecmp($cell_val, CConfig::EA_OPER_XLS_NA) == 0) && $cell_index != CConfig::$QUES_XLS_HEADING_ARY["Para Description"] && $ques_type != CConfig::QT_NORMAL)
							{
								$generated_errors .= "[".$cell_index.$row->getRowIndex()."](row ".$row->getRowIndex(). " : column ".$cell_index.") :   Value should be other than '".$cell_val."'. It is not allowed here. If you do not find such issue then please check specified row for blank cells.;";
							}
							else if((strcasecmp($cell_val, CConfig::OPER_XLS_EMPTY) == 0 || strcasecmp($cell_val, CConfig::EA_OPER_XLS_EMPTY) == 0) && $cell_index != CConfig::$QUES_XLS_HEADING_ARY["Explanation"])
							{
								$generated_errors .= "[".$cell_index.$row->getRowIndex()."](row ".$row->getRowIndex(). " : column ".$cell_index.") :   Value should be other than '".$cell_val."'. It is not allowed here. If you do not find such issue then please check specified row for blank cells.;";
							}
							else if($cell_index == CConfig::$QUES_XLS_HEADING_ARY["S No"])
							{
								if(preg_match('/^\d+$/',$cell_val) == 0)
								{
									if(strtoupper($cell_val) != CConfig::OPER_XLS_COPY || strtoupper($cell_val) != CConfig::EA_OPER_XLS_COPY)
									{
										$generated_errors .= "[".$cell_index.$row->getRowIndex()."](row ".$row->getRowIndex(). " : column ".$cell_index.") :   Entered Value should be an integer value in specified cell. If you do not find such issue then please check specified row for blank cells.;";
									}
								}
							}
							else if($cell_index == CConfig::$QUES_XLS_HEADING_ARY["Para Description"])
							{
								if($ques_type == CConfig::QT_NORMAL)
								{
									if(strcasecmp($cell_val, CConfig::OPER_XLS_NA) != 0 && strcasecmp($cell_val, CConfig::EA_OPER_XLS_NA) != 0)
									{
										$generated_errors .= "[".$cell_index.$row->getRowIndex()."](row ".$row->getRowIndex(). " : column ".$cell_index.") :   Value should be ".CConfig::OPER_XLS_NA." instead of '".$cell_val."'. If you do not find such issue then please check specified row for blank cells.;";
									}
								}
								else if($ques_type == CConfig::QT_READ_COMP || $ques_type == CConfig::QT_DIRECTIONS)
								{
									if(strtoupper($cell_val) == CConfig::OPER_XLS_NA || strtoupper($cell_val) == CConfig::EA_OPER_XLS_NA)
									{
										$generated_errors .= "[".$cell_index.$row->getRowIndex()."](row ".$row->getRowIndex(). " : column ".$cell_index.") :   '".$cell_val."' is not acceptable for reading comprehension or directions description. It should be meaningful.;";
									}
								}
							}
							else if($cell_index == CConfig::$QUES_XLS_HEADING_ARY["Language"])
							{
								if(!in_array(strtolower($cell_val), CConfig::$TEST_LANGUAGES))
								{
									$generated_errors .= "[".$cell_index.$row->getRowIndex()."](row ".$row->getRowIndex(). " : column ".$cell_index.") :   '".$cell_val."' is not available in our language database. You can choose from ".$lang_choice.". If you do not find such issue then please check specified row for blank cells.;";
								}
								else 
								{
									if(!isset($this->pref_lang) || empty($this->pref_lang))
									{
										$this->pref_lang = strtolower($cell_val);
									}
								}
							}
							else if($cell_index == CConfig::$QUES_XLS_HEADING_ARY["Answer"])
							{   					
								if(strtoupper($cell_val) == CConfig::OPER_XLS_COPY || strtoupper($cell_val) == CConfig::EA_OPER_XLS_COPY)
								{
									$cell_val = $prev_ans;
								}
								else 
								{
									$prev_ans = $cell_val;
								}
								
								$comma_pos = strpos($cell_val, ",");
								if($is_eq)
								{
									if($comma_pos !== false)
									{
										$ans_ary = explode(",", $cell_val);
										//echo count($ans_ary)." ".($col_count - (count(CConfig::$QUES_XLS_HEADING_ARY) - 2))."<br />";
										if(count($ans_ary) != ($col_count - (count(CConfig::$QUES_XLS_HEADING_ARY) - 2)))
										{
											$generated_errors .= "[".$cell_index.$row->getRowIndex()."](row ".$row->getRowIndex(). " : column ".$cell_index.") :   Weightage entered by you is not correct. Please provide weightage for every option you entered. If you do not find such issue then please check specified row for blank cells.;";
										}
										else
										{
											for($ans_index = 0; $ans_index < count($ans_ary); $ans_index++)
											{
												if(preg_match('/^\d+$/',$ans_ary[$ans_index]) == 0)
												{
													$generated_errors .= "[".$cell_index.$row->getRowIndex()."](row ".$row->getRowIndex(). " : column ".$cell_index.") :   Weightage should contain only numeric values which can be comma separated. If you do not find such issue then please check specified row for blank cells.;";
												}
											}
										}
									}
									else
									{
										$generated_errors .= "[".$cell_index.$row->getRowIndex()."](row ".$row->getRowIndex(). " : column ".$cell_index.") :   Weightage entered by you is not correct. Please provide weightage for every option you entered. If you do not find such issue then please check specified row for blank cells.;";
									}
								}
								else 
								{
									if($comma_pos !== false)
									{
										$ans_ary = explode(",", $cell_val);
										if(count($ans_ary) > ($col_count - (count(CConfig::$QUES_XLS_HEADING_ARY) - 2)))
										{
											$generated_errors .= "[".$cell_index.$row->getRowIndex()."](row ".$row->getRowIndex(). " : column ".$cell_index.") :   Answer entered by you is not correct. Number of answers should not exceed limit of available options. If you do not find such issue then please check specified row for blank cells.;";
										}
										else
										{
											for($ans_index = 0; $ans_index < count($ans_ary); $ans_index++)
											{
												if(preg_match('/^\d+$/',$ans_ary[$ans_index]) == 0)
												{
													$generated_errors .= "[".$cell_index.$row->getRowIndex()."](row ".$row->getRowIndex(). " : column ".$cell_index.") :   Answer should contain only numeric values which can be comma separated. If you do not find such issue then please check specified row for blank cells.;";
												}
												else if(intval($ans_ary[$ans_index]) > ($col_count - (count(CConfig::$QUES_XLS_HEADING_ARY) - 2)) || intval($ans_ary[$ans_index]) < 1)
												{
													$generated_errors .= "[".$cell_index.$row->getRowIndex()."](row ".$row->getRowIndex(). " : column ".$cell_index.") :   Answer should only be between 1 and ".($col_count - (count(CConfig::$QUES_XLS_HEADING_ARY) - 2))." for this question. If you do not find such issue then please check specified row for blank cells.;";
												}
												else if(count(array_keys($ans_ary, $ans_ary[$ans_index])) > 1)
												{
													$generated_errors .= "[".$cell_index.$row->getRowIndex()."](row ".$row->getRowIndex(). " : column ".$cell_index.") :   Same answer is repeated for this question. Please make it correct.;";
												}
											}
										}
									}
									else
									{
										if(preg_match('/^\d+$/',$cell_val) == 0)
										{
											$generated_errors .= "[".$cell_index.$row->getRowIndex()."](row ".$row->getRowIndex(). " : column ".$cell_index.") :   '".$cell_val."' is not acceptable in this cell. Answer should be a numeric value between 1 and ".($col_count - (count(CConfig::$QUES_XLS_HEADING_ARY) - 2))." for this question. If you do not find such issue then please check specified row for blank cells.;";
										}
										else if(preg_match('/^\d+$/',$cell_val) != 0 && (intval($cell_val) < 1 || intval($cell_val) > ($col_count - (count(CConfig::$QUES_XLS_HEADING_ARY) - 2))))
										{
											$generated_errors .= "[".$cell_index.$row->getRowIndex()."](row ".$row->getRowIndex(). " : column ".$cell_index.") :   '".$cell_val."' is not acceptable in this cell. Answer should be a numeric value between 1 and ".($col_count - (count(CConfig::$QUES_XLS_HEADING_ARY) - 2))." for this question. If you do not find such issue then please check specified row for blank cells.;";
										}
									}
								}
							}
							else if($cell_index == CConfig::$QUES_XLS_HEADING_ARY["Subject"] && (!isset($this->subject) || empty($this->subject)))
							{
								$this->subject = ucfirst(strtolower($cell_val));
							}
							else if($cell_index == CConfig::$QUES_XLS_HEADING_ARY["Topic"])
							{	
								if(strtoupper($cell_val) == CConfig::OPER_XLS_COPY || strtoupper($cell_val) == CConfig::EA_OPER_XLS_COPY)
								{
									$cell_val = $prev_topic;
								}
								else
								{
									$prev_topic = $cell_val;
								}
								
								$topic_val = $cell_val;
								
								$sec_name = str_replace(" ", "_", ucfirst(strtolower($topic_val)));
								if(!in_array($sec_name, $this->section_ary))
								{
									array_push($this->section_ary, $sec_name);
								}
								
								if(!empty($tag))
								{
									$topic_val = $tag."_".$cell_val;
								}
								
								if(!in_array(ucfirst(strtolower($topic_val)), $this->topic_ary))
								{
									$current_topic = ucfirst(strtolower($topic_val));
									array_push($this->topic_ary, $current_topic);
									$this->difficulty_ary[$current_topic] = array();
									$this->difficulty_ary[$current_topic]['easy'] = 0;
									$this->difficulty_ary[$current_topic]['hard'] = 0;
									$this->difficulty_ary[$current_topic]['moderate'] = 0;
								}
								
								if($ques_type == CConfig::QT_READ_COMP || $ques_type == CConfig::QT_DIRECTIONS)
								{
									if($this->IsParaTitleExists($topic_val, $user_id))
									{
										$generated_errors .= "[".$cell_index.$row->getRowIndex()."](row ".$row->getRowIndex(). " : column ".$cell_index.") :   Please use a different topic for the given para. Topic already exists. Topic entered by you will be treated as title of the para.;";
									}
								}
								else if($ques_type == CConfig::QT_NORMAL)
								{
									if($this->IsParaTitleExists($topic_val, $user_id, CConfig::QT_NORMAL))
									{
										$generated_errors .= "[".$cell_index.$row->getRowIndex()."](row ".$row->getRowIndex(). " : column ".$cell_index.") :   Please use a different topic for this question. It is already in use as a title for some reading comprehension(RC) or directions para. Topic entered by you should be different from title of RC or directions para.;";
									}
								}
							}
							else if($cell_index == CConfig::$QUES_XLS_HEADING_ARY["Difficulty"])
							{
								if((preg_match('/^\d+$/',$cell_val) == 0 && strcasecmp($cell_val, CConfig::OPER_XLS_COPY) != 0) || (preg_match('/^\d+$/',$cell_val) != 0 && (intval($cell_val) < CConfig::DIFF_LVL_EASY || intval($cell_val) > CConfig::DIFF_LVL_HARD)))
								{
									$generated_errors .= "[".$cell_index.$row->getRowIndex()."](row ".$row->getRowIndex(). " : column ".$cell_index.") :   '".$cell_val."' is not acceptable in this cell. Difficulty level should be a numeric value between ".CConfig::DIFF_LVL_EASY." and ".CConfig::DIFF_LVL_HARD.". If you do not find such issue then please check specified row for blank cells.;";
								}
								else if($cell_val == CConfig::DIFF_LVL_EASY) 
								{
									$this->difficulty_ary[$current_topic]['easy']++;
									$this->max_questions++;
								}
								else if($cell_val == CConfig::DIFF_LVL_MODERATE)
								{
									$this->difficulty_ary[$current_topic]['moderate']++;
									$this->max_questions++;
								}
								else if($cell_val == CConfig::DIFF_LVL_HARD)
								{
									$this->difficulty_ary[$current_topic]['hard']++;
									$this->max_questions++;
								}
							}
							$cell_index++;
						}
					}
				}
			}
			
			$generated_errors .= $this->ValidateForTranslation($worksheet);
			$generated_errors .= $this->ValidateProgrammingCode($worksheet);

			if($ques_type == CConfig::QT_READ_COMP || $ques_type == CConfig::QT_DIRECTIONS)
			{
				$generated_errors .= $this->ValidateRCDirSubTopics($worksheet);
			}	
			return $generated_errors;
		}
		
		public function ValidateEQRangeAnalysisSheet($worksheet)
		{
			$generated_errors = "";
			$prev_topic = "";
			$current_topic = "";
			$lower_limit_ary  = array();
			$higher_limit_ary = array();
			
			foreach ($worksheet->getRowIterator() as $row)
			{
				if($row->getRowIndex() > 1)
				{
					
					$current_lower_limit_val = 0;
					$current_higher_limit_val = 0;
					$col_count = $this->CountColumn($worksheet, $row->getRowIndex());
					
					if($col_count != CConfig::EU_EQ_RANGE_ANALYSIS_COL_CNT && $col_count != (CConfig::EU_EQ_RANGE_ANALYSIS_COL_CNT + 1))
					{
						$generated_errors .= "[row ".$row->getRowIndex()."]   Either some value has been left blank OR Number of columns exceeded the limit in specified row.;";
					}
					else
					{
						$cellIterator = $row->getCellIterator();
						$cell_index = 'A';
					
						foreach ($cellIterator as $cell)
						{
							$cell_val = str_replace("’", "'", trim($cell->getValue()));
							
							if($row->getRowIndex() == 2 && (strtoupper($cell_val) == CConfig::OPER_XLS_COPY || strtoupper($cell_val) == CConfig::EA_OPER_XLS_COPY))
							{
								$generated_errors .= "[".$cell_index.$row->getRowIndex()."](row ".$row->getRowIndex(). " : column ".$cell_index.") :   You can not use '".$cell_val."' for first time. It should be a meaningful content.;";
							}
							
							if($cell_index == CConfig::$EQ_RANGE_ANALYSIS_HEADING_ARY["Topic Name"])
							{
								if(strtoupper($cell_val) == CConfig::OPER_XLS_COPY || strtoupper($cell_val) == CConfig::EA_OPER_XLS_COPY)
								{
									$cell_val = $prev_topic;
								}
								else
								{
									$prev_topic = $cell_val;
								}
								
								$topic_val = $cell_val;
								
								$current_topic = ucfirst(strtolower($topic_val));
								if(!isset($lower_limit_ary[$current_topic]))
								{
									$lower_limit_ary[$current_topic]["lower_limit"] = array();
									$lower_limit_ary[$current_topic]["higher_limit"] = array();
								}
								
								if(!in_array(str_replace(" ", "_", ucfirst(strtolower($topic_val))), $this->section_ary))
								{
									$generated_errors .= "[".$cell_index.$row->getRowIndex()."](row ".$row->getRowIndex(). " : column ".$cell_index.") :   Topic name '".$topic_val."' does not match with the topics you entered in the question sheet. If you do not find such issue then please check specified row for blank cells.;";
								}
							}
							else if($cell_index == CConfig::$EQ_RANGE_ANALYSIS_HEADING_ARY["Lower Limit"])
							{
								if(preg_match('/^\d+$/',$cell_val) == 0)
								{
									$generated_errors .= "[".$cell_index.$row->getRowIndex()."](row ".$row->getRowIndex(). " : column ".$cell_index.") :   '".$cell_val."' is not acceptable in this cell. Lower Limit should be a numeric value. If you do not find such issue then please check specified row for blank cells.;";
								}
								else 
								{
									if(in_array($cell_val, $lower_limit_ary[$current_topic]["lower_limit"]))
									{
										$generated_errors .= "[".$cell_index.$row->getRowIndex()."](row ".$row->getRowIndex(). " : column ".$cell_index.") :   Lower limit '".$cell_val."' already exists for the same topic. If you do not find such issue then please check specified row for blank cells.;";
									}
									else 
									{
										$bAlreadyAvailableRange = false;
										for($limit_index = 0; $limit_index < count($lower_limit_ary[$current_topic]["lower_limit"]); $limit_index++)
										{
											if($cell_val >= $lower_limit_ary[$current_topic]["lower_limit"][$limit_index] && $cell_val <= $lower_limit_ary[$current_topic]["higher_limit"][$limit_index])
											{
												$bAlreadyAvailableRange = true;
												$generated_errors .= "[".$cell_index.$row->getRowIndex()."](row ".$row->getRowIndex(). " : column ".$cell_index.") :   Lower limit '".$cell_val."' is not valid because it is already available in range(".$lower_limit_ary[$current_topic]["lower_limit"][$limit_index]." - ".$lower_limit_ary[$current_topic]["higher_limit"][$limit_index]."). If you do not find such issue then please check specified row for blank cells.;";
												break;
											}
										}
										if(!$bAlreadyAvailableRange)
										{
											array_push($lower_limit_ary[$current_topic]["lower_limit"], $cell_val);
										}
									}
									$current_lower_limit_val = $cell_val;
								}
							}
							else if($cell_index == CConfig::$EQ_RANGE_ANALYSIS_HEADING_ARY["Higher Limit"])
							{
								if(preg_match('/^\d+$/',$cell_val) == 0)
								{
									$generated_errors .= "[".$cell_index.$row->getRowIndex()."](row ".$row->getRowIndex(). " : column ".$cell_index.") :   '".$cell_val."' is not acceptable in this cell. Higher Limit should be a numeric value. If you do not find such issue then please check specified row for blank cells.;";
								}
								else 
								{
									if(in_array($cell_val, $lower_limit_ary[$current_topic]["higher_limit"]))
									{
										$generated_errors .= "[".$cell_index.$row->getRowIndex()."](row ".$row->getRowIndex(). " : column ".$cell_index.") :   Higher limit '".$cell_val."' already exists for the same topic. If you do not find such issue then please check specified row for blank cells.;";
									}
									else
									{
										$bAlreadyAvailableRange = false;
										for($limit_index = 0; $limit_index < count($lower_limit_ary[$current_topic]["lower_limit"]); $limit_index++)
										{
											if($cell_val >= $lower_limit_ary[$current_topic]["lower_limit"][$limit_index] && $cell_val <= $lower_limit_ary[$current_topic]["higher_limit"][$limit_index])
											{
												$bAlreadyAvailableRange = true;
												$generated_errors .= "[".$cell_index.$row->getRowIndex()."](row ".$row->getRowIndex(). " : column ".$cell_index.") :   Higher limit '".$cell_val."' is not valid because it is already available in range(".$lower_limit_ary[$current_topic]["lower_limit"][$limit_index]." - ".$lower_limit_ary[$current_topic]["higher_limit"][$limit_index]."). If you do not find such issue then please check specified row for blank cells.;";
												break;
											}
										}
										if(!$bAlreadyAvailableRange)
										{
											array_push($lower_limit_ary[$current_topic]["higher_limit"], $cell_val);
										}
									}
									$current_higher_limit_val = $cell_val;
									if($current_lower_limit_val >  $current_higher_limit_val)
									{
										$generated_errors .= "[".$cell_index.$row->getRowIndex()."](row ".$row->getRowIndex(). " : column ".$cell_index.") :   Higher limit '".$cell_val."' must be greater than the lower limit in same row. If you do not find such issue then please check specified row for blank cells.;";
									}
								}
							}
							$cell_index++;
						}
					}
				}
			}
			return $generated_errors;
		}
		
		public function InsertExcelQuestions($worksheet, $user_id, $ques_type, $tag, $zip_file = NULL, $is_eq = false)
		{
			$row_processed = 0;
			
			$tag_id = null;
			if(!empty($tag))
			{
				$tag_id = $this->objDB->GetTagId($tag);
			}
			
			//echo $tag." ".$tag_id."hello";
			if($ques_type == CConfig::QT_NORMAL)
			{
				$row_processed = $this->InsertNormalQuestions($worksheet, $user_id, $ques_type, $tag_id, $zip_file, $is_eq);
			}
			else if($ques_type == CConfig::QT_READ_COMP || $ques_type == CConfig::QT_DIRECTIONS)
			{
				$row_processed = $this->InsertRCDirQuestions($worksheet, $user_id, $ques_type, $tag_id, $tag, $zip_file, $is_eq);
			}
			$this->test_tag_id = $tag_id;
			return $row_processed;
		}
		
		public function InsertEQTest($user_id, $test_name, $test_duration)
		{
			$test_id = $this->objDB->InsertIntoTest($user_id, $test_name, 0, 0, -1, -1, 0, $this->pref_lang, 0, 0, $this->test_tag_id, CConfig::TT_EQ);
			
			$this->eq_test_id = $test_id;
			
			$subject_id = $this->objDB->GetSubjectId($this->subject);
			
			$section_details = "";
			$subject_in_section = "";
			$topic_in_subject = "";
			
			$sec_index = 0;
			foreach($this->section_ary as $section)
			{
				$section_updated = str_replace("-", "_", $section);
				$section_details .= $section_updated."#".($this->difficulty_ary[$this->topic_ary[$sec_index]]['easy']+$this->difficulty_ary[$this->topic_ary[$sec_index]]['moderate']+$this->difficulty_ary[$this->topic_ary[$sec_index]]['hard'])."(50,100,1,0);";
				
				$subject_in_section .= $section_updated.":".$subject_id."#".($this->difficulty_ary[$this->topic_ary[$sec_index]]['easy']+$this->difficulty_ary[$this->topic_ary[$sec_index]]['moderate']+$this->difficulty_ary[$this->topic_ary[$sec_index]]['hard']).";";
				
				$topic_id = $this->objDB->GetTopicId($this->topic_ary[$sec_index], $this->subject);
				
				$this->topic_id_ary[$section] = $topic_id;
				
				$topic_in_subject .= $section_updated.":".$subject_id."-".$topic_id."@EASY#".($this->difficulty_ary[$this->topic_ary[$sec_index]]['easy'])."&MODERATE#".($this->difficulty_ary[$this->topic_ary[$sec_index]]['moderate'])."&DIFFICULT#".($this->difficulty_ary[$this->topic_ary[$sec_index]]['hard']).";";
			
				$sec_index++;
			}
			
			$this->objDB->InsertIntoTestDynamic($test_id, $test_duration,
					$this->max_questions, CConfig::PC_CUTOFF, 50,
					100, 0, 1, 0,
					$sec_index, $ques_source, $section_details,
					$subject_in_section, $topic_in_subject, "personal", CConfig::RV_NONE);
		}
		
		public function InsertEQRangeAnalysis($worksheet)
		{
			$data_table      = array();
			$row_index       = 0;
			$values_for_insertion_ary = array();
			foreach ($worksheet->getRowIterator() as $row)
			{
				if($row->getRowIndex() > 1)
				{
					$cellIterator = $row->getCellIterator();
			
					$cell_index  = 'A';
					$data_row    = array();
			
					foreach ($cellIterator as $cell)
					{
						if($cell_index != 'A')
						{
							$cell_value = trim($cell->getValue());
							
							if(strtoupper($cell_value) == CConfig::OPER_XLS_COPY || strtoupper($cell_value) == CConfig::EA_OPER_XLS_COPY)
							{
								$data_row[$cell_index] = $data_table[$row_index - 1][$cell_index];
							}
							else if(strtoupper($cell_value) == CConfig::OPER_XLS_EMPTY || strtoupper($cell_value) == CConfig::EA_OPER_XLS_EMPTY)
							{
								$data_row[$cell_index] = "";
							}
							else
							{
								$data_row[$cell_index] = str_ireplace(CConfig::EA_OPER_CODE_END,"</div>",str_ireplace(CConfig::EA_OPER_CODE_START,"<div class='mipcat_code_ques'>",str_replace(">",">",str_replace("<","<",str_replace("&","&amp;",str_replace("’", "'", trim($cell_value)))))));
							}
							$data_table[$row_index][$cell_index] = $data_row[$cell_index];
						}
						$cell_index++;
					}
					array_push($values_for_insertion_ary, "(".$this->eq_test_id.", ".$this->topic_id_ary[str_replace(" ", "_", ucfirst(strtolower($data_row[CConfig::$EQ_RANGE_ANALYSIS_HEADING_ARY["Topic Name"]])))].", ".$data_row[CConfig::$EQ_RANGE_ANALYSIS_HEADING_ARY["Lower Limit"]].", ".$data_row[CConfig::$EQ_RANGE_ANALYSIS_HEADING_ARY["Higher Limit"]].", '".mysql_real_escape_string($data_row[CConfig::$EQ_RANGE_ANALYSIS_HEADING_ARY["Analysis"]])."', '".mysql_real_escape_string($data_row[CConfig::$EQ_RANGE_ANALYSIS_HEADING_ARY["Summary"]])."')");
					$row_index++;
				}
			}
			$this->objDB->InsertEQRangeAnalysis($values_for_insertion_ary);
		}
	}
?>
