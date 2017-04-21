<?php 
	include_once(dirname(__FILE__)."/../../database/mcat_db.php");
	
	if(isset($_POST['score']) && isset($_POST['test_id']) && $_POST['score'] > 0 && $_POST['score'] <= 5)
	{
		$objDB = new CMcatDB();
		
		$rated_test_id_ary = "";
		if(isset($_COOKIE["already_rated_tests"]))
		{
			$rated_test_id_ary = explode(",",$_COOKIE["already_rated_tests"]);
			
			//print_r($rated_test_id_ary);
			$bAlreadyRated = false;
			foreach($rated_test_id_ary as $rating)
			{
				$rating_ary = explode(";", $rating);
				if(in_array($_POST['test_id'], $rating_ary))
				{
					$bAlreadyRated = true;
					break;
				}
			}
			
			if(!$bAlreadyRated)
			{
				$objDB->RateTest($_POST['test_id'], $_POST['score']);
				array_push($rated_test_id_ary, $_POST['test_id'].";".$_POST['score']);
			}
		}
		else 
		{
			$objDB->RateTest($_POST['test_id'], $_POST['score']);
			
			$rated_test_id_ary = array($_POST['test_id'].";".$_POST['score']);
		}
		
		setcookie('already_rated_tests', implode(",", $rated_test_id_ary), time()+(3600*24*30), "/",".ezeeassess.com");
	}
?>