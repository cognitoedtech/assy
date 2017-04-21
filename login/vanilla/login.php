<?php
	include_once('auth_database.php');
	include_once(dirname(__FILE__)."/../../database/config.php");
	include_once(dirname(__FILE__)."/../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/../../lib/site_config.php");
	include_once(dirname(__FILE__)."/../../lib/session_manager.php");
	
	$objDB = new CMcatDB();
	$email = $_POST['email'];	
	$password = $_POST['passwd'];
	
	if(isset($_SESSION['email']))
	{
		session_unset();
		session_regenerate_id();
		session_destroy();
	}
	if(!empty($email) && !empty($password))
	{
		$arrUserInfo = $objADB->authenticate($email,$password);
		if(!empty($arrUserInfo))
		{
			$status = $objADB->GetStatus($arrUserInfo['user_id']);
			if($status > 0)
			{
				session_unset();
				session_regenerate_id();
				session_destroy();
				header("Location: http://www.mipcat.com/login/vanilla/login.php");
			}
			else
			{
				$objADB->SetUserSession($arrUserInfo['user_id']);
				$_SESSION['email'] = $email;
				$_SESSION['name'] = $arrUserInfo['firstname'].' '.$arrUserInfo['lastname'];
				$_SESSION['user_id'] = $arrUserInfo['user_id'];
				header("Location: http://www.mipcat.com/dashboard.php");
			}
		}
		else
		{
			header("Location: http://www.mipcat.com/login/vanilla/login.php");
		}
	}
?>
<html>
	<head>
	</head>
	<body>
		<div id="ajaxim"></div>
		<form action="login.php" method="post">
			email <input type="text" name="email" />
			password <input type="password" name="passwd" />
			<input type="submit" />
		</form>
	</body>
</html>