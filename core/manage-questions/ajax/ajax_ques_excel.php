<?php
	include_once(dirname(__FILE__)."/../../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../../lib/ques_excel.php");
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	$user_type = CSessionManager::Get(CSessionManager::INT_USER_TYPE);
	
	/** PHPExcel */
	require_once dirname(__FILE__)."/../../../3rd_party/php_excel_classes/PHPExcel.php";
	
	/** PHPExcel_IOFactory */
	require_once dirname(__FILE__)."/../../../3rd_party/php_excel_classes/PHPExcel/IOFactory.php";
	require_once dirname(__FILE__)."/../../../3rd_party/php_excel_classes/PHPExcel/Reader/Excel2007.php";
	include_once dirname(__FILE__)."/../../../database/mcat_db.php";
	include_once dirname(__FILE__)."/../../../lib/utils.php";
	
	$objReader = null;
	
	if($_FILES['csv']['size'] > 0)
	{
		if(isset($_POST['eq_choice']) && $_POST['eq_choice'] == "yes" && $user_type == CConfig::UT_SUPER_ADMIN)
		{
			//get the xls file name
			if($_FILES['range_analysis_csv']['size'] > 0)
			{
				$file = $_FILES['csv']['tmp_name'];
				
				$range_analysis_file = $_FILES['range_analysis_csv']['tmp_name'];
				
				$ques_file_type 	 = PHPExcel_IOFactory::identify($file);
				
				$range_analysis_file_type = PHPExcel_IOFactory::identify($range_analysis_file);
				
				if($ques_file_type == "Excel2007")
				{
					$objQuestionReader = new PHPExcel_Reader_Excel2007();
				}
				else
				{
					$objQuestionReader = new PHPExcel_Reader_Excel5();
				}
				
				if($range_analysis_file_type == "Excel2007")
				{
					$objRangeAnalysisReader = new PHPExcel_Reader_Excel2007();
				}
				else
				{
					$objRangeAnalysisReader = new PHPExcel_Reader_Excel5();
				}
				
				$objQuestionReader->setReadDataOnly(true);
				
				$objRangeAnalysisReader->setReadDataOnly(true);
				
				$objPHPQuesExcel = $objQuestionReader->load($file);
				
				$objPHPRangeAnalysisExcel = $objRangeAnalysisReader->load($range_analysis_file);
				
				$tag = NULL;
				if(!empty($_POST['ques_tag']))
				{
					$tag = trim($_POST['ques_tag']);
				}
				
				$zip_file = NULL;
				if($_FILES['zip']['size'] > 0)
				{
					$zip_file = $_FILES['zip'];
				}
				
				$ques_type = $_POST['ques_type'];
				
				$worksheet = $objPHPQuesExcel->getSheet(0);
				
				$rangeAnalysisWorksheet = $objPHPRangeAnalysisExcel->getSheet(0);
				
				$objQuesExcel = new CQuesExcel();
				
				$generated_errors = $objQuesExcel->ValidateQuesSheet($worksheet, $ques_type, $user_id, $tag, $zip_file, true);
				
				$range_analysis_generated_errors = $objQuesExcel->ValidateEQRangeAnalysisSheet($rangeAnalysisWorksheet);
				
				if(empty($generated_errors) && empty($range_analysis_generated_errors))
				{
					$row_processed = $objQuesExcel->InsertExcelQuestions($worksheet, $user_id, $ques_type, $tag, $zip_file, true);
					
					$objQuesExcel->InsertEQTest($user_id, trim($_POST['test_name']), trim($_POST['duration']));
						
					echo "<p style='color: green;'> Processed ".$row_processed." rows successfully...</p>";
				}
				else if(!empty($generated_errors))
				{
					$errors_ary = explode(";",$generated_errors);
				
					echo "<h3>Question Sheet Errors :</h3>";
					for($error_index = 0; $error_index < (count($errors_ary) - 1); $error_index++)
					{
						echo "<p style='color: red;'>".$errors_ary[$error_index]."</p>";
					}
				}
				
				if(empty($generated_errors) && empty($range_analysis_generated_errors))
				{
					$objQuesExcel->InsertEQRangeAnalysis($rangeAnalysisWorksheet);
				}
				else if(!empty($range_analysis_generated_errors))
				{
					$errors_ary = explode(";",$range_analysis_generated_errors);
				
					echo "<h3>Range Analysis Sheet Errors :</h3>";
					for($error_index = 0; $error_index < (count($errors_ary) - 1); $error_index++)
					{
						echo "<p style='color: red;'>".$errors_ary[$error_index]."</p>";
					}
				}
			}
			else 
			{
				echo "<p style='color: red;'>Please choose a proper MS Excel Range Analysis file to submit.</p>";
			}
		}
		else 
		{
			//get the xls file name
			$file = $_FILES['csv']['tmp_name'];
			
			$file_type 	 = PHPExcel_IOFactory::identify($file);
			
			if($file_type == "Excel2007")
			{
				$objReader = new PHPExcel_Reader_Excel2007();
			}
			else
			{
				$objReader = new PHPExcel_Reader_Excel5();
			}
			
			$objReader->setReadDataOnly(true);
			
			$objPHPExcel = $objReader->load($file);
			
			$tag = NULL;
			if(!empty($_POST['ques_tag']))
			{
				$tag = trim($_POST['ques_tag']);
			}
			
			$zip_file = NULL;
			if($_FILES['zip']['size'] > 0)
			{
				$zip_file = $_FILES['zip'];
			}
			
			$ques_type = $_POST['ques_type'];
			
			$worksheet = $objPHPExcel->getSheet(0);
			
			$objQuesExcel = new CQuesExcel();
			
			$generated_errors = $objQuesExcel->ValidateQuesSheet($worksheet, $ques_type, $user_id, $tag, $zip_file);
			
			if(empty($generated_errors))
			{
				$row_processed = $objQuesExcel->InsertExcelQuestions($worksheet, $user_id, $ques_type, $tag, $zip_file);
					
				echo "<p style='color: green;'> Processed ".$row_processed." rows successfully...</p>";
			}
			else
			{
				$errors_ary = explode(";",$generated_errors);
			
				for($error_index = 0; $error_index < (count($errors_ary) - 1); $error_index++)
				{
					echo "<p style='color: red;'>".$errors_ary[$error_index]."</p>";
				}
			}	
		}
	}
	else 
	{
		echo "<p style='color: red;'>Please choose a proper MS Excel Question file to submit.</p>";
	}
?>