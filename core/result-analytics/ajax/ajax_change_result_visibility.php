<?php 
	include_once(dirname(__FILE__)."/../../../test/lib/tbl_result.php");
	
	if(!empty($_POST['test_pnr']))
	{
		$objTR = new CResult();
		$objTR->UpdateResultVisibility($_POST['test_pnr'], $_POST['visibility']);
	}
?>