<?php 
include_once(dirname(__FILE__)."/../../lib/session_manager.php");
include_once(dirname(__FILE__)."/../../database/mcat_db.php");

// - - - - - - - - - - - - - - - - -
// On Session Expire Load ROOT_URL
// - - - - - - - - - - - - - - - - -
CSessionManager::OnSessionExpire ();
// - - - - - - - - - - - - - - - - -

$objDB = new CMcatDB();

echo json_encode(array("is_test_started"=>$objDB->IsTestStartedByAdmin()));
?>