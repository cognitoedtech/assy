<?php 
	include_once("../../database/mcat_db.php");
	
	if(isset($_POST['test_id']))
	{
		$objDB = new CMcatDB();
		
		$customInstrAry	= $objDB->GetTestInstructions($_POST['test_id']);
		
		foreach($customInstrAry as $instLang=>$instr)
		{
			if($instLang == $_POST['language'])
			{
				echo($instr);
			}
		}
	}
?>