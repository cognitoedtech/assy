<?php
	include(dirname(__FILE__)."/../../../lib/utils.php");
	include(dirname(__FILE__)."/../../../database/mcat_db.php");
	
	$objDB = new CMcatDB();
	
	$resp = $objDB->AJXProcessTestRow($_POST);
	echo json_encode($resp);
?>