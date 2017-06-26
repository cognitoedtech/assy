<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
include_once("new-email.php");
$objMail = new CEMail("support@ezeeassess.com", "#MIP@2014.indore");
//$val = $objMail->PrepAndSendTestScheduleMail("Banking", "manish.mastishka@gmail.com", "Manish Arora", "Cognito EdTech", "ritesh.kanoongo@gmail.com", "Ritesh Kanoongo", "", "12", "9","12/07/2017", "12", "0", "Mumbai");
$val = $objMail->Send("ritesh.kanoongo@gmail.com", "Test Email", "This is Test Email");
echo $val;
?>
