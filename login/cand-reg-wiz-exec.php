<?php
	/*echo("<pre>");
	print_r($_POST);
	echo("</pre>");*/
	//Start session
	include_once("../lib/session_manager.php");
	include_once("../database/config.php");
	include_once("../database/mcat_db.php");
	include_once("../lib/user_manager.php");
	include_once("../lib/utils.php");
	
	//Function to sanitize values received from the form. Prevents SQL injection
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
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	
	$objUM = new CUserManager();
	
	//Sanitize the POST values
	$owner_id 		= clean($_POST['owner_id']);
	$password 		= clean($_POST['password']);
	$question 		= clean($_POST['question']);
	$answer 		= clean($_POST['answer']);
	
	/*$qualification	= $_POST['qualification'];
	$area			= $_POST['area'];
	$stream			= $_POST['stream'];
	$percent		= $_POST['percent'];
	$institute		= $_POST['institute'];
	$board			= $_POST['board'];
	$passing_year	= $_POST['passing_year'];
	$qual_count 	= $_POST['qual_count'];*/
	
	$objUM->SetSecurityParam($user_id, $password, $question, $answer, $owner_id);
	
	$email = CSessionManager::Get(CSessionManager::STR_EMAIL_ID);
	$objUser = $objUM->GetUserByEmail($email) ;
	CSessionManager::Set(CSessionManager::STR_LOGIN_NAME, $objUser->GetLoginName());
	
	/*for ($aryIndex = 0; $aryIndex <$qual_count; $aryIndex++)
	{
		$objUM->InsertIntoUserCV($user_id, $qualification[$aryIndex], $area[$aryIndex],
								 $stream[$aryIndex], $percent[$aryIndex],
								 $institute[$aryIndex], $board[$aryIndex], $passing_year[$aryIndex]);
	}*/
	
	CUtils::Redirect("../core/dashboard.php");
?>