<?php
	include_once(dirname(__FILE__)."/../../../lib/session_manager.php");
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	
	/** Error reporting */
	//error_reporting(E_ALL);
	
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
		//get the xls file name
		$file = $_FILES['csv']['tmp_name'];
		
		$file_type 	 = PHPExcel_IOFactory::identify($file);
		
		// Create new PHPExcel object
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
		
		$objDB = new CMcatDB();

		$worksheet = $objPHPExcel->getSheet(0);
		$row_index = 0;
		$invalid_index = 0;
		$dirty_row = 0;
		$aryInvalidRow = array();
		
		foreach ($worksheet->getRowIterator() as $row) 
		{
			$dataRow = array();
		
			if($row->getRowIndex() > 1)
			{
				$cellIterator = $row->getCellIterator();
				$i = 0;
				$column = 'A';
				$bValidRow = true;
				$bOwner    = false;
				foreach ($cellIterator as $cell)
				{
					$err_msg = "";
					$cell_value = trim($cell->getValue());
					if($objDB->ValidateCellValue($cell_value, $i, $user_id, $err_msg, $bOwner, intval($_POST['excel_batch'])) != false)
					{
						$dataRow[$i] = $cell_value;
					}
					else if($bOwner != true)
					{
						$bValidRow = false;
						$aryInvalidRow[$invalid_index] = array();
						$aryInvalidRow[$invalid_index]['row'] = $row_index + 1;
						$aryInvalidRow[$invalid_index]['column'] = $column;
						$aryInvalidRow[$invalid_index]['error'] = $err_msg;
						$invalid_index++;
					}
					$i++;
					$column++;
				}
				
				if($i == 0)
				{
					$bValidRow = false;
					$aryInvalidRow[$invalid_index] = array();
					$aryInvalidRow[$invalid_index]['row'] = $row_index + 1;
					$aryInvalidRow[$invalid_index]['column'] = $column;
					$aryInvalidRow[$invalid_index]['error'] = "Row is empty (Row cells does not contain values)";
					$invalid_index++;
				}
				else if(($i != CConfig::EU_CAND_SHEET_COL_CNT) && ($i != CConfig::EU_CAND_SHEET_COL_CNT+1))
				{
					$bValidRow = false;
					$aryInvalidRow[$invalid_index] = array();
					$aryInvalidRow[$invalid_index]['row'] = $row_index + 1;
					$aryInvalidRow[$invalid_index]['column'] = "{}";
					$aryInvalidRow[$invalid_index]['error'] = "Empty cells found in row, this may lead confusing cell verification error. Please correct ";
					$invalid_index++;
				}
				else
				{
					/*echo "<pre>";
					print_r($dataRow);
					echo "</pre>";*/
				}
				
				if($bValidRow == true && !empty($dataRow) && $bOwner != true)
				{
					if(!empty($dataRow[9]))
					{
						$dataRow[10] = $dataRow[9];
					}
					if($_POST['excel_batch'] == CConfig::CDB_ID)
					{
						$dataRow[9] = json_encode(array(intval($_POST['excel_batch'])));
					}
					else 
					{
						$dataRow[9] = json_encode(array(CConfig::CDB_ID, intval($_POST['excel_batch'])));
					}
					$password = $objDB->InsertCandidate($dataRow, $user_id);
					$cand_name = $dataRow[0]." ".$dataRow[1];
					$email = $dataRow[5];
					
					$objDB->EmailRegNotification($user_id, $cand_name, $email, $password);
				}
				else if($bOwner != true)
				{
					$dirty_row++;
				}
			}
			
			$row_index++;
			
			unset($dataRow);
		}
		
		echo "<h2>Processed ".($row_index - 1)." rows [Inserted : ".($row_index - 1 - $dirty_row)." - Dirty : ".$dirty_row."]</h2>";
		
		if($invalid_index > 0)
		{
			echo "<p>Found ".$invalid_index." invalid &lsquo;Row or Cell&rsquo; entries (&lsquo;Row or Cell&rsquo; is empty or corrupted). Following are the details about invalid rows,</p>";
			
			foreach ($aryInvalidRow as $invalid_row)
			{
				echo "[Row : ".$invalid_row['row']."] Column : ".$invalid_row['column']." - ".$invalid_row['error']."<br/>";
			}
		}
		
		//echo "<script>parent.document.getElementById('candidates_table').src = parent.document.getElementById('candidates_table').src</script>";
	}
?>