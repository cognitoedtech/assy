<?php 
	include_once(dirname(__FILE__)."/../../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../../database/mcat_db.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	if(isset($_POST['schd_id']) && isset($_POST['schedule_type']))
	{
		$objDB = new CMcatDB();
		
		$candInfoAry = $objDB->GetScheduledCandidateDetails($_POST['schd_id']);
		
		if($_POST['schedule_type'] == CConfig::TST_ONLINE)
		{
			$i = 1;
			foreach ($candInfoAry as $user_id => $cand_info)
			{
				printf("<tr>");
				printf("<td>%s</td>", $i++);
				printf("<td>%s</td>", $cand_info['name']);
				printf("<td>%s</td>", $cand_info['email']);
				printf("<td>%s</td>", ($cand_info['test_finished'] == 1)?"Finished":"Unfinished");
				printf("</tr>");
			}
		}
		else if($_POST['schedule_type'] == CConfig::TST_OFFLINE)
		{	
			$i = 1;
			foreach ($candInfoAry as $user_id => $cand_info)
			{
				printf("<tr>");
				printf("<td>%s</td>", $i++);
				printf("<td>%s</td>", $cand_info['name']);
				printf("<td>%s</td>", $cand_info['email']);
				printf("<td>%s</td>", $cand_info['login_name']);
				printf("</tr>");
			}
		}
	}
?>