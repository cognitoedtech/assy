<?php
	include_once("../../../database/mcat_db.php");
	include_once("../../../lib/session_manager.php");
	include_once("../../../lib/new-email.php");
	include_once("../../../lib/utils.php");
	
	session_start();
	
	$objDB = new CMcatDB();
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	$timezone = $_POST['time_zone']; 

	$pan_no = $_POST['pan_no'];
	if(!empty($pan_no))
	{
		$objDB->SetUserPANNumber($pan_no,$user_id);
	}
	
	$balance = $_SESSION['balance'];
	
	$encash_pnts = 0;
	
	if($balance > CConfig::MAX_ENCASH_PNTS)
	{
		$encash_pnts = CConfig::MAX_ENCASH_PNTS;
		$balance = $balance - $encash_pnts;
	}
	else
	{
		$remaining = $balance%5;
		$encash_pnts = $balance - $remaining;
		$balance = $remaining;
	}
	
	$objDB->UpdateContribBalance($balance, $user_id);
	$objDB->InsertContribEncashRequest($user_id,$timezone,$_SERVER['REMOTE_ADDR'],$encash_pnts,0);
	
	$contrib_email = $objDB->GetUserEmail($user_id);
	$contrib_name = $objDB->GetUserName($user_id);
	
	$objMail = new CEMail(CConfig::OEI_FINANCE, $objDB->GetPasswordFromOfficialEMail(CConfig::OEI_FINANCE));
	$objMail->PrepAndSendEncashPointsRequestMail($contrib_email, $contrib_name, $encash_pnts);
	//CEMail::PrepAndSendEncashPointsRequestMail($contrib_email, $contrib_name, $encash_pnts);
	
	unset($_SESSION['balance']);
	
	CUtils::Redirect("../encash_points.php?encashed=1");
?>