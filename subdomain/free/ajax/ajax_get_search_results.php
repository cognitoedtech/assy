<?php 
	include_once(dirname(__FILE__)."/../../../lib/free_user_manager.php");
	
	$objFreeUM = new CFreeUserManager();
	
	$searchResultAry = $objFreeUM->PopulateFreeTests(trim($_POST['search_text']), $_POST['search_category'], $_POST['limit_start_value']);
	
	echo(json_encode($searchResultAry));
?>