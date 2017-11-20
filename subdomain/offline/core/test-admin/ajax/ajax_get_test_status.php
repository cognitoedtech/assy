<?php 
include_once(dirname(__FILE__)."/../../../lib/session_manager.php");
include_once(dirname(__FILE__)."/../../../database/mcat_db.php");

// - - - - - - - - - - - - - - - - -
// On Session Expire Load ROOT_URL
// - - - - - - - - - - - - - - - - -
CSessionManager::OnSessionExpire();
// - - - - - - - - - - - - - - - - -

$objDB = new CMcatDB();

$candStatusAry = $objDB->PopulateCandidatesWithTestStatus();

$notStarted = $candStatusAry['total'] - ($candStatusAry['finished'] + $candStatusAry['unfinished']);

echo json_encode(array("finished"=>$candStatusAry['finished'], "unfinished"=> $candStatusAry['unfinished'], "not_started"=>$notStarted));
?>