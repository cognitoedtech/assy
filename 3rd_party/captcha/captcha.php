<?php 
	include_once(dirname(__FILE__)."/../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../database/config.php");
	
	$text = rand(10000,99999);
	CSessionManager::Set(CSessionManager::INT_CAPTCH_VALUE, $text);
	$image = imagecreatefromjpeg("images/bg.jpg");
	$txtColor = imagecolorallocate($image, 0, 0, 0);
	imagestring($image, 5, 5, 5, $text, $txtColor);
	header("Content-type: image/jpeg");
	imagejpeg($image);
?>
