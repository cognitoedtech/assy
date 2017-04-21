 <?php
	/*echo("<pre>");	
	print_r($_POST);
	echo("</pre>");
	exit(0);*/
	
	//Start session
	include_once(dirname(__FILE__)."/../../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../../database/config.php");
	include_once(dirname(__FILE__)."/../../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/../../../lib/user_manager.php");
	include_once(dirname(__FILE__)."/../../../lib/utils.php");
	include_once(dirname(__FILE__)."/../../../lib/billing.php");
	include_once(dirname(__FILE__)."/../../../lib/otfa_tpin_pdf.php");
	
	/** PHPExcel */
	require_once dirname(__FILE__)."/../../../3rd_party/php_excel_classes/PHPExcel.php";
	
	/** PHPExcel_IOFactory */
	require_once dirname(__FILE__)."/../../../3rd_party/php_excel_classes/PHPExcel/IOFactory.php";
	require_once dirname(__FILE__)."/../../../3rd_party/php_excel_classes/PHPExcel/Reader/Excel2007.php";
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	
	//Function to sanitize values received from the form. Prevents SQL injection
	function clean($str) 
	{
		/*if(!get_magic_quotes_gpc()) 
		{
			$str = trim(mysql_real_escape_string($str));
		}
		else */
		{
			$str = trim($str);
		}

		return $str;
	}
	
	//$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	
	$objDB = new CMcatDB();
	
	// Sanitize the POST values
	$test_id 		= $_POST['test_id'];
	$schd_type		= $_POST['schd_type'];
	$scheduled_on 	= $_POST['scheduled_on'];
	$hours		 	= $_POST['hours'];
	$minutes	 	= $_POST['minutes'];
	$expire_on		= $_POST['expire_on'];
	$expire_hours 	= $_POST['expire_hours'];
	$expire_minutes	= $_POST['expire_minutes'];
	$candidate_list = $_POST['candidate_list'];
	$pnr_list		= $_POST['pnr_list'];
	$time_zone		= $_POST['select_time_zone'];
	$cost			= $_POST['cost'];
	
	
	$test_name = "";
	$schd_id = "";
	if(isset($_POST['schd_type']) && $schd_type == CConfig::TST_OFFLINE)
	{
		$objBilling = new CBilling();
		
		$ques_source = $objBilling->GetQuesSource($test_id);
		
		$isTestFromAssignedPackage = $objDB->IsTestAssignedFromPackage($test_id, $user_id);
		
		$offlineVersionRate = $objBilling->GetOfflineVersionRate($user_id);
		
		$totalCandidates = count(explode(";", $candidate_list)) - 1;
		
		$assignedPackageTestRate = 0;
		if($isTestFromAssignedPackage)
		{
			$assignedPackageTestRate = $objBilling->GetAssignedPackageTestRate($test_id, $user_id);
		}
		
		if($ques_source == "personal" || $isTestFromAssignedPackage)
		{
			$test_name = $objDB->InsertOfflineTestSchedule($test_id, $user_id, $candidate_list);
			
			$objBilling->SubProjectedBalance($user_id, ($cost + ($assignedPackageTestRate * $totalCandidates)+ ($offlineVersionRate * $totalCandidates)));
		}
	}
	else if(!empty($candidate_list) && $schd_type == CConfig::TST_ONLINE)
	{
		$test_name = $objDB->InsertIntoTestSchedule($test_id, $user_id, $scheduled_on, $hours, $minutes, $expire_on, $expire_hours, $expire_minutes, $candidate_list, $time_zone, $schd_type, $schd_id);
		
		$objBilling = new CBilling();
		
		$assignedPackageTestRate = 0;
		if($isTestFromAssignedPackage)
		{
			$assignedPackageTestRate = $objBilling->GetAssignedPackageTestRate($test_id, $user_id);
		}
		
		$totalCandidates = count(explode(";", $candidate_list)) - 1;
		
		$objBilling->SubProjectedBalance($user_id, ($cost + ($assignedPackageTestRate * $totalCandidates)));
		if(!empty($expire_on))
		{
			$objDB->EmailTestScheduleNotification($user_id, $test_name, $candidate_list, $scheduled_on, $hours, $minutes,$expire_on, $expire_hours, $expire_minutes, $time_zone);
		}
		else
		{
			$objDB->EmailTestScheduleNotification($user_id, $test_name, $candidate_list, $scheduled_on, $hours, $minutes,"","","",$time_zone);
		}
	}
	else if($schd_type == CConfig::TST_OTFA_EMAIL && $_FILES['email_csv']['size'] > 0)
	{
		//print_r($_POST);
		$email_csv = $_FILES['email_csv']['tmp_name'];
		
		$error_ary = array();
		
		$email_ary = array();
		
		$objReader = null;
		
		$email_csv_file_type = PHPExcel_IOFactory::identify($email_csv);
		
		if($email_csv_file_type == "Excel2007")
		{
			$objReader = new PHPExcel_Reader_Excel2007();
		}
		else
		{
			$objReader = new PHPExcel_Reader_Excel5();
		}
		
		$objReader->setReadDataOnly(true);
		
		$objPHPExcel = $objReader->load($email_csv);
		
		$worksheet = $objPHPExcel->getSheet(0);
		
		foreach ($worksheet->getRowIterator() as $row)
		{
			if($row->getRowIndex() > 1)
			{
				$cellIterator = $row->getCellIterator();
				$cell_index  = 'A';
				foreach ($cellIterator as $cell)
				{
					$cell_value = trim($cell->getValue());
					
					if (!filter_var($cell_value, FILTER_VALIDATE_EMAIL) && !empty($cell_value)) 
					{
						array_push($error_ary, "<p style='color: red;'>[".$cell_index.$row->getRowIndex()."](row ".$row->getRowIndex(). " : column ".$cell_index.") : ".$cell_value." is not a valid email.</p>");
					}
					else 
					{
						$cand_user_type = $objDB->GetUserTypeByEmail($cell_value);
						if($cand_user_type == CConfig::UT_CORPORATE)
						{
							array_push($error_ary, "<p style='color: red;'>[".$cell_index.$row->getRowIndex()."](row ".$row->getRowIndex(). " : column ".$cell_index.") : ".$cell_value." is already registered as a corporate user.</p>");
						}
						else if($cand_user_type == CConfig::UT_COORDINATOR)
						{
							array_push($error_ary, "<p style='color: red;'>[".$cell_index.$row->getRowIndex()."](row ".$row->getRowIndex(). " : column ".$cell_index.") : ".$cell_value." is already registered as a coordinator of some organization.</p>");
						}
						else 
						{
							array_push($email_ary, $cell_value);
						}	
					}
					
					$cell_index++;
				}
			}
		}
		
		if(!empty($error_ary))
		{
			CSessionManager::SetErrorMsg(implode("<br />", $error_ary));
			CUtils::Redirect("../schedule_new_test.php?schd_type=".$schd_type."&test_id=".$test_id);
			exit();
		}
		else 
		{
			$objBilling = new CBilling();
			
			$ques_source = $objBilling->GetQuesSource($test_id);
			
			$isTestFromAssignedPackage = $objDB->IsTestAssignedFromPackage($test_id, $user_id);
			
			$assignedPackageTestRate = 0;
			if($isTestFromAssignedPackage)
			{
				$assignedPackageTestRate = $objBilling->GetAssignedPackageTestRate($test_id, $user_id);
			}
			
			$rate_applied = 0;
			if($ques_source == "mipcat")
			{
				$rate_applied = $objBilling->GetMIpCATQuesRate($user_id);
			}
			else if($ques_source == "personal")
			{
				$rate_applied = $objBilling->GetPersonalQuesRate($user_id);
			}
			
			$total_cost = ($assignedPackageTestRate + $rate_applied) * count($email_ary);
			
			$projected_balance = $objBilling->GetProjectedBalance($user_id);
			
			if(($projected_balance - $total_cost) <= 0)
			{
				CUtils::Redirect("../schedule_new_test.php?low_balance=1");
				exit();
			}
			else 
			{
				$batch_id = null;
				
				$time_zone = CSessionManager::Get(CSessionManager::FLOAT_TIME_ZONE);
				
				$dtzone = new DateTimeZone($objDB->tzOffsetToName($time_zone));
				
				$objDateTime  = new DateTime();
				$objDateTime->setTimezone($dtzone);
				
				if(isset($_POST['batch_choice']) && $_POST['batch_choice'] == 0)
				{
					$batch_id = $objDB->GetBatchId(trim($_POST['test_batch_name']), $user_id);
					
					if(empty($batch_id))
					{
						$batch_id = $objDB->InsertBatch($_POST['test_batch_name'], $user_id, "Not Available");
					}
				}
				else 
				{
					$batch_id = $_POST["otfa_batch_id"];
				}
				$cand_id_ary = $objDB->InsertOTFACandidatesWithEmail($email_ary, $user_id, $batch_id);
				
				$test_name = $objDB->InsertIntoTestSchedule($test_id, $user_id, $objDateTime->format("Y-m-d"), $hours, $minutes, "", "", "", implode(";", $cand_id_ary).";", $time_zone, $schd_type, $schd_id);
			
				$objDB->InsertOTFAUserForm($test_id, $schd_id, $batch_id, $_POST);
				
				$objBilling->SubProjectedBalance($user_id, $total_cost);
				
				$objDB->EmailOTFARegLink($email_ary, $user_id, $test_name, $schd_id);
			}
		}
	}
	else if($schd_type == CConfig::TST_OTFA_TPIN && isset($_POST['tpin_choice']))
	{
		$objBilling = new CBilling();
			
		$ques_source = $objBilling->GetQuesSource($test_id);
			
		$isTestFromAssignedPackage = $objDB->IsTestAssignedFromPackage($test_id, $user_id);
			
		$assignedPackageTestRate = 0;
		if($isTestFromAssignedPackage)
		{
			$assignedPackageTestRate = $objBilling->GetAssignedPackageTestRate($test_id, $user_id);
		}
			
		$rate_applied = 0;
		if($ques_source == "mipcat")
		{
			$rate_applied = $objBilling->GetMIpCATQuesRate($user_id);
		}
		else if($ques_source == "personal")
		{
			$rate_applied = $objBilling->GetPersonalQuesRate($user_id);
		}
				
		$projected_balance = $objBilling->GetProjectedBalance($user_id);
		
		if($_POST['tpin_choice'] == 0)
		{
			$numOfTPINs = $_POST["tpin_cand_count"];
			
			$total_cost = ($assignedPackageTestRate + $rate_applied) * $numOfTPINs;
			
			if(($projected_balance - $total_cost) <= 0)
			{
				CUtils::Redirect("../schedule_new_test.php?low_balance=1");
				exit();
			}
			else 
			{
				$batch_id = null;
				
				$time_zone = CSessionManager::Get(CSessionManager::FLOAT_TIME_ZONE);
				
				$dtzone = new DateTimeZone($objDB->tzOffsetToName($time_zone));
				
				$objDateTime  = new DateTime();
				$objDateTime->setTimezone($dtzone);
				
				if(isset($_POST['batch_choice']) && $_POST['batch_choice'] == 0)
				{
					$batch_id = $objDB->GetBatchId(trim($_POST['test_batch_name']), $user_id);
						
					if(empty($batch_id))
					{
						$batch_id = $objDB->InsertBatch($_POST['test_batch_name'], $user_id, "Not Available");
					}
				}
				else
				{
					$batch_id = $_POST["otfa_batch_id"];
				}
				
				$test_name = $objDB->GetTestName($test_id);
				
				$TPINAry = array();
				for($i = 0; $i < $numOfTPINs; $i++)
				{
					$TPIN = uniqid();
					array_push($TPINAry, $TPIN);
				}
				
				$objDB->InsertIntoTestSchedule($test_id, $user_id, $objDateTime->format("Y-m-d"), $hours, $minutes, "", "", "", implode(";", $TPINAry).";", $time_zone, $schd_type, $schd_id);
					
				$pdf = new COTFATTPIN("P", "mm", "A4", $test_name, $schd_id);
				
				$pdf->GenerateTPINByRequiredCount($TPINAry);
				
				$objDB->InsertOTFAUserForm($test_id, $schd_id, $batch_id, $_POST);
				
				$objBilling->SubProjectedBalance($user_id, $total_cost);
				
				$pdf->OutputPDF();
				
				exit();
			}
		}
		else if($_POST['tpin_choice'] == 1 && $_FILES['tpin_cand_csv']['size'] > 0)
		{
			$tpin_cand_csv = $_FILES['tpin_cand_csv']['tmp_name'];
			
			$error_ary = array();
			
			$first_name_ary = array();
			
			$last_name_ary = array();
			
			$fathers_name_ary = array();
			
			$dob_ary = array();
			
			$objReader = null;
			
			$tpin_cand_csv_file_type = PHPExcel_IOFactory::identify($tpin_cand_csv);
			
			if($tpin_cand_csv_file_type == "Excel2007")
			{
				$objReader = new PHPExcel_Reader_Excel2007();
			}
			else
			{
				$objReader = new PHPExcel_Reader_Excel5();
			}
			
			$objReader->setReadDataOnly(true);
			
			$objPHPExcel = $objReader->load($tpin_cand_csv);
			
			$worksheet = $objPHPExcel->getSheet(0);
			
			foreach ($worksheet->getRowIterator() as $row)
			{
				if($row->getRowIndex() > 1)
				{
					$cellIterator = $row->getCellIterator();
			
					$cell_index  = 'A';
					
					$col_count = 0;
					foreach ($cellIterator as $cell)
					{
						$cell_value = trim($cell->getValue());
							
						if($cell_index == 'D')
						{
							$dateAry = date_parse($cell_value);
							if($dateAry == false)
							{
								array_push($error_ary, "<p style='color: red;'>[".$cell_index.$row->getRowIndex()."](row ".$row->getRowIndex(). " : column ".$cell_index.") : Invalid date value, should be in YYYYMMDD format.</p>");
							}
							else if(checkdate($dateAry['month'], $dateAry['day'], $dateAry['year']) == false)
							{
								array_push($error_ary, "<p style='color: red;'>[".$cell_index.$row->getRowIndex()."](row ".$row->getRowIndex(). " : column ".$cell_index.") : Invalid date value, should be in YYYYMMDD format.</p>");
							}
							else 
							{
								array_push($dob_ary, $cell_value);
							}	
						}
						else if($cell_index == 'A')
						{
							if(empty($cell_value) || !ctype_alpha(str_replace(' ', '', $cell_value)))
							{
								array_push($error_ary, "<p style='color: red;'>[".$cell_index.$row->getRowIndex()."](row ".$row->getRowIndex(). " : column ".$cell_index.") : Expecting alphabetic value (letters).</p>");
							}
							else
							{
								array_push($first_name_ary, $cell_value);
							}
						}
						else if($cell_index == 'B')
						{
							if(empty($cell_value) || !ctype_alpha(str_replace(' ', '', $cell_value)))
							{
								array_push($error_ary, "<p style='color: red;'>[".$cell_index.$row->getRowIndex()."](row ".$row->getRowIndex(). " : column ".$cell_index.") : Expecting alphabetic value (letters).</p>");
							}
							else
							{
								array_push($last_name_ary, $cell_value);
							}
						}
						else if($cell_index == 'C')
						{
							if(empty($cell_value) || !ctype_alpha(str_replace(' ', '', $cell_value)))
							{
								array_push($error_ary, "<p style='color: red;'>[".$cell_index.$row->getRowIndex()."](row ".$row->getRowIndex(). " : column ".$cell_index.") : Expecting alphabetic value (letters).</p>");
							}
							else
							{
								array_push($fathers_name_ary, $cell_value);
							}	
						}
								
						$cell_index++;
						$col_count++;
					}
					
					if($col_count < 4)
					{
						array_push($error_ary, "<p style='color: red;'>(row ".$row->getRowIndex(). ") : Empty cells found in row, this may lead confusing cell verification error. Please correct.</p>");
					}
					else if($col_count > 4)
					{
						array_push($error_ary, "<p style='color: red;'>(row ".$row->getRowIndex(). ") : More than required cells found in row, this may lead confusing cell verification error. Please correct.</p>");
					}
				}
			}
			
			if(!empty($error_ary))
			{
				CSessionManager::SetErrorMsg(implode("<br />", $error_ary));
				CUtils::Redirect("../schedule_new_test.php?schd_type=".$schd_type."&test_id=".$test_id);
				exit();
			}
			else 
			{
				$total_cost = ($assignedPackageTestRate + $rate_applied) * count($first_name_ary);
				
				if(($projected_balance - $total_cost) <= 0)
				{
					CUtils::Redirect("../schedule_new_test.php?low_balance=1");
					exit();
				}
				else 
				{
					$batch_id = null;
					
					$time_zone = CSessionManager::Get(CSessionManager::FLOAT_TIME_ZONE);
					
					$dtzone = new DateTimeZone($objDB->tzOffsetToName($time_zone));
					
					$objDateTime  = new DateTime();
					$objDateTime->setTimezone($dtzone);
					
					if(isset($_POST['batch_choice']) && $_POST['batch_choice'] == 0)
					{
						$batch_id = $objDB->GetBatchId(trim($_POST['test_batch_name']), $user_id);
					
						if(empty($batch_id))
						{
							$batch_id = $objDB->InsertBatch($_POST['test_batch_name'], $user_id, "Not Available");
						}
					}
					else
					{
						$batch_id = $_POST["otfa_batch_id"];
					}
					
					$test_name = $objDB->GetTestName($test_id);
					
					$TPINAry = array();
					for($i = 0; $i < count($first_name_ary); $i++)
					{
						$TPIN = uniqid();
						array_push($TPINAry, $TPIN);
					}
					
					$objDB->InsertIntoTestSchedule($test_id, $user_id, $objDateTime->format("Y-m-d"), $hours, $minutes, "", "", "", implode(";", $TPINAry).";", $time_zone, $schd_type, $schd_id);
						
					$pdf = new COTFATTPIN("P", "mm", "A4", $test_name, $schd_id);
					
					$TPINUserInfoAry = $pdf->GenerateTPINWithCandInfo($TPINAry, $first_name_ary, $last_name_ary, $fathers_name_ary, $dob_ary);
					
					$objDB->InsertOTFAUserForm($test_id, $schd_id, $batch_id, $_POST, json_encode($TPINUserInfoAry));
					
					$objBilling->SubProjectedBalance($user_id, $total_cost);
					
					$pdf->OutputPDF();
					
					exit();
				}
			}
		}
	}
	
 	CUtils::Redirect("../schedule_new_test.php?test_name=".urlencode($test_name));
?>