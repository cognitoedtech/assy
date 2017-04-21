<?php
	include_once("../../../database/mcat_db.php");
	include_once("../../../lib/utils.php");

	$parsAry = parse_url((CUtils::curPageURL()));
	$qry = split("[=&]", $parsAry["query"]);
	
	$objDB = new CMcatDB();
	if ($qry[0] == "term")
	{
		echo json_encode($objDB->GetPromotionalEmails($qry[1]));
	}
?>