<?php 
	include_once(dirname(__FILE__)."/../../../test/lib/tbl_result.php");
	include_once(dirname(__FILE__)."/../../../database/config.php");
	
	$objTR = new CResult();
	
	if(isset($_POST['test_pnr']))
	{
		$ResultAry = $objTR->GetSectionwiseResultFromPNR($_POST['test_pnr']);
		
		foreach($ResultAry as $secName => $secDetails)
		{
			$tr_bg = "warning";
			if($secDetails['min_passing_marks'] != 0)
			{
				$tr_bg = ($secDetails['result'] == CConfig::RS_PASS)?"success":"error";
			}
			
			printf("<tr class='%s'>",$tr_bg);
			printf("<td>%s</td>", $secName);
			printf("<td>%s</td>", $secDetails['marks']);
			printf("<td>%s</td>", $secDetails['min_passing_marks']);
			printf("<td>%s</td>", $secDetails['max_passing_marks']);
			if($secDetails['min_passing_marks'] == 0)
			{
				printf("<td>NA</td>");
			}
			else
			{
				printf("<td>%s</td>", ($secDetails['result'] == CConfig::RS_PASS)?"Pass":"Fail");
			}
			printf("</tr>");
		}
	}
?>