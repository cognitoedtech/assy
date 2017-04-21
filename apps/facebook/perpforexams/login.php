<?php
	include_once("lib/session_manager.php");
	include_once("lib/db_queries.php");
	include_once("lib/utils.php");
	include_once("config/app_config.php");
	include_once("lib/fb_wrapper.php");
	
	$objDB = new CDBQueries();
	$objFB = new CFBWrapper();
	
	$info = null;
	$bLoggedIn = $objFB->CheckLoginStatus($info);
	if($bLoggedIn)
	{
		/*
		echo("<pre>");
		print_r($info);
		echo("</pre>");
		*/
		
		$objDB->RegisterUser($info);
		
		// Init Session Variables
		CSessionManager::Set(CSessionManager::STR_USER_ID, $info['id']);
		CSessionManager::Set(CSessionManager::STR_USER_NAME, $info['name']);
		CSessionManager::Set(CSessionManager::STR_EMAIL_ID, $info['email']);
		CSessionManager::Set(CSessionManager::BOOL_LOGIN, true);
		
		CUtils::Redirect("fb_app.php");
		exit();
	}
	else
	{
?>
<html>
	<body>
		<div id="fb-root"></div>
	    <script src="//connect.facebook.net/en_US/all.js"></script>
	    
	    <script type="text/javascript">
	      
	      // Initialize the Facebook JavaScript SDK
	      FB.init({
	        appId: '535079056529152',
	        xfbml: true,
	        status: true,
	        cookie: true,
	      });
	      
	      // Check if the current user is logged in and has authorized the app
	      FB.getLoginStatus(checkLoginStatus);
	      
	      // Login in the current user via Facebook and ask for email permission
	      function authUser() {
	        FB.login(checkLoginStatus, {scope:'email, read_friendlists, publish_actions, user_birthday, user_education_history, user_location, user_hometown, publish_actions, read_stream, friends_likes'});
	      }
	      
	      // Check the result of the user status and display login button if necessary
	      function checkLoginStatus(response) 
	      {
	        if(response && response.status == 'connected') 
	        {
	          //alert('User is authorized');
	          window.location = "fb_app.php";
	        } 
	        else 
	        {
	          //alert('User is not authorized');
	          
	          authUser();
	        }
	      }
	     
	    </script>
	</body>
</html>
<?php
	
	    //echo("<script> top.location.href='" . $info . "'</script>");
	}
?>