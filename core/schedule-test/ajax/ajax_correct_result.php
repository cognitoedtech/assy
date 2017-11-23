<?php
	include_once(dirname(__FILE__)."/../../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/../../../lib/utils.php");
	include_once(dirname(__FILE__)."/../../../test/lib/tbl_test_session.php");
	
	$parsAry = parse_url(CUtils::curPageURL());
	$qry = explode("=", $parsAry["query"]);
	
	 
	if(isset($_POST) && $_POST["schd_id"] !="")
	
	{
		$objTS = new CTestSession();
		
		$total_update = $objTS->updateResult($_POST["schd_id"]);
		
		echo "Result Updated for " .$total_update. " Candidates";
		
		//$objDB->PrepareScheduledCandidatesCombo($qry[1]);
	}
?>