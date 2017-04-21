<?php
	include("../../../lib/utils.php");
	include("../../../database/mcat_db.php");
	
	$objDB = new CMcatDB();
	
	echo json_encode($objDB->AJXProcessQuestionRow($_POST));
?>