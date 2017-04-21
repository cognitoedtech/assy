<?php
	/*
	echo("<pre>");	
	print_r($_POST);
	echo("</pre>");
	//exit(0);
	*/
	
	include_once(dirname(__FILE__)."/../../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../../database/config.php");
	include_once(dirname(__FILE__)."/../../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/../../../lib/utils.php");
	include_once(dirname(__FILE__)."/../../../lib/billing.php");
	
	function clean($str) 
	{
		/*if(!get_magic_quotes_gpc()) 
		{
			$str = trim(mysql_real_escape_string($str));
		}
		else */
		{
			$str = trim($str);
		}

		return $str;
	}
	
	$objDB = new CMcatDB();
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	
	$pkg_name 			    = clean($_POST['pkg_name']);
    $candidate_id		    = $_POST['candidate_id'];
    $provisioned_from_ary	= explode("00:00:00",$_POST['provisioned_from']);
    $time_zone 			    = $_POST['time_zone'];
    $expire  			    = $_POST['expire'];
    $amnt_sold 			    = clean($_POST['amnt_sold']);
    $test_list 			    = $_POST['test_list'];
    
    $index = ($expire/15) - 1;
    
    $rate = CConfig::$INR_PKG_RATE_ARY[$index];
    
    /*echo "<pre>";
    print_r($_POST);
    echo "</pre>";*/
	
	if(!empty($test_list))
	{
		$objDB->InsertIntoTestPackage($pkg_name, $candidate_id, $user_id, trim($provisioned_from_ary[0]), $time_zone, $expire, $rate, $amnt_sold, $test_list);
		
		$objBilling = new CBilling();
		$objBilling->SubBalance($user_id, $rate);
		$objBilling->SubProjectedBalance($user_id, $rate);
		
		$objDB->EmailTestPackageNotification($user_id, $pkg_name, $candidate_id, $provisioned_from, $expire, $amnt_sold);
	}
	
	CUtils::Redirect("../trade_test_pkg.php?pkg_name=".urlencode($pkg_name));
?>