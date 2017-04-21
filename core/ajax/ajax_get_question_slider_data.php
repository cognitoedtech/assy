<?php
	include_once(dirname(__FILE__)."/../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../database/config.php");
	include_once(dirname(__FILE__)."/../../lib/utils.php");
	include_once(dirname(__FILE__)."/../../test/lib/tbl_result.php");
	
	$objTR = new CResult();
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	
	/*$parsAry = parse_url(CUtils::curPageURL());
	$qry = split("[=&]", $parsAry["query"]);*/
	
	$test_pnr		= $_GET['testpnr'];
	$query 			= $_GET['query']; 		// 0: Correct, 1: Wrong, 2: Unanswered
	$difficulty 	= $_GET['difficulty'];
	$reference_0 	= $_GET['reference_0'];
	$reference_1 	= $_GET['reference_1'];
	
	$opHtml = "<pre>".print_r($_GET, true)."</pre>";
	// query -> 0: Correct, 1: Wrong, 2: Unanswered 
	switch($_GET['chart'])
	{
		case 'test_overview':
			$objTR->GetQuetionsForSlider($test_pnr, $query);
			break;
		case 'section_overview':
			$objTR->GetSectionalQuetionsForSlider($test_pnr, $reference_0, $query);
			break;
		case 'subject_overview':
			$objTR->GetSubjectQuetionsForSlider($test_pnr, $reference_0, $query);
			break;
		case 'topic_overview':
			$objTR->GetTopicQuetionsForSlider($test_pnr, $reference_0, $reference_1, $query);
			break;
		case 'topic_perf':
			$objTR->GetTopicPrefQuetionsForSlider($test_pnr, $difficulty, $reference_0, $reference_1, $query);
			break;
	}
	
	echo ($opHtml);	
?>