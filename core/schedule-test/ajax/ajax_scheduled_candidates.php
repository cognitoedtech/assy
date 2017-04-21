<?php
	include_once(dirname(__FILE__)."/../../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/../../../lib/utils.php");
	
	$parsAry = parse_url(CUtils::curPageURL());
	$qry = explode("=", $parsAry["query"]);
	
	if($qry[0] == "tschd_id")
	{
		$objDB = new CMcatDB();
		
		$objDB->PrepareScheduledCandidatesCombo($qry[1]);
	}
?>