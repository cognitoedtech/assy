<?php 
	include_once("config.php");
	
	$link_array = array();
	
	for($i = 0; $i < $_GET['db_con']; $i++) {
		
		$link_array[$i] = mysql_connect(CConfig::HOST, CConfig::USER_NAME, CConfig::PASSWORD);
		
		if (mysql_select_db(CConfig::DB_MCAT, $link_array[$i])) {
			echo "connected".($i+1).";";
		}
		sleep($_GET['sleep_time']);
	}
	
?>