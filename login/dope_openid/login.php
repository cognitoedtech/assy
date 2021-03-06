<?php
/**
*	This file is part of Dope OpenID.
*   Author: Steve Love (http://www.stevelove.org)
*   
*   Some code has been modified from Simple OpenID:
*   http://www.phpclasses.org/browse/package/3290.html
*
*   Yadis Library provided by JanRain:
*   http://www.openidenabled.com/php-openid/
*
*	Dope OpenID is free software: you can redistribute it and/or modify
*	it under the terms of the GNU General Public License as published by
*	the Free Software Foundation, either version 3 of the License, or
*	(at your option) any later version.
*
*	Dope OpenID is distributed in the hope that it will be useful,
*	but WITHOUT ANY WARRANTY; without even the implied warranty of
*	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
*	GNU General Public License for more details.
*
*	You should have received a copy of the GNU General Public License
*	along with Dope OpenID. If not, see <http://www.gnu.org/licenses/>.
**/

/*
* Example uses default PHP sessions.
* Feel free to use whatever session management you prefer.
*/
//session_start();
/*
* Include our example functions. Your actual functions may differ.
*/
require 'functions.php';
include_once(dirname(__FILE__)."/../../lib/session_manager.php") ;
include_once(dirname(__FILE__)."/../../lib/utils.php");
include_once(dirname(__FILE__)."/../../database/config.php");
include_once(dirname(__FILE__)."/../../lib/site_config.php");

/*
* Is $_SESSION['loggedin'] set and does it evaluate to TRUE?
* If yes, user is already logged in. Redirect them somewhere else.
*/
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'])
{
	header("Location:index.php");
	exit;
}



/*
* If $_POST['process'] is set, begin OpenID login form processing.
*/

	/*
	* Include the Dope OpenID class file.
	*/
	require 'class.dopeopenid.php';
	
	/*
	* URL input is expected here.
	*/
	//$openid_url = trim($_POST['openid_identifier']);
	
	/*
	* If running PHP 5, use the built-in URL validator.
	* Else use something like the following regex to validate input.
	*/
	/*if(function_exists('filter_input')) {
		if( ! filter_input(INPUT_POST, "openid_identifier", FILTER_VALIDATE_URL)) {
			$error = "Error: OpenID Identifier is not in proper format.";
		}
	}
	else 
	{
		// Found this on Google. Seems to match most valid URLs. Feel free to modify or replace.
		if( ! eregi("^((https?)://)?(((www\.)?[^ ]+\.[com|org|net|edu|gov|us]))([^ ]+)?$",$openid_url)) {
			$error = "Error: OpenID Identifier is not in proper format.";
		}
	}*/
	$openid_url="https://www.google.com/accounts/o8/id";
	// Proceed if we made it through without setting $error
	if(isset($_GET['from']) && $_GET['from']==1)
	{
		if ( ! isset($error)) 
		{
			/*
			* Store the user's submitted OpenID Identity for later use.
			*/
			$_SESSION['openid_url'] = $openid_url;

			/*
			* Create a new Dope_OpenID object
			*/
			$openid = new Dope_OpenID($openid_url);
			/*
			* YOU MUST EDIT THIS LINE.
			* The user's OpenID provider will return them to the URL that you provide here.
			* It could be a separate verify.php script, or just pass a parameter to tell a
			* single processing script what to do (like I've done with this file you're reading).
			*/
			$link=CSiteConfig::ROOT_URL.'/login/dope_openid/login.php?action=verify';
			$openid->setReturnURL($link);
		
			/*
			* YOU MUST EDIT THIS LINE
			* Set the trust root. This is the URL or set of URLs the user will be asked
			* to trust when signing in with their OpenID Provider. It could be your base
			* URL or a subdirectory thereof. Up to you.
			*/
			$root_link=CSiteConfig::ROOT_URL;
			$openid->SetTrustRoot($root_link);
		
			/*
			* EDIT THIS LINE (OPTIONAL)
			* When the user signs in with their OpenID Provider, these are
			* the details you would like sent back for your own use.
			* Dope OpenID attempts to get this information using both Simple Registration
			* and Attribute Exchange protocols. The type that is returned depends on the
			* user's Provider. Each provider chooses what they wish to provide and all 
			* defined attributes may not be available. To see where these two types of
			*  attributes intersect, see the following: http://www.axschema.org/types/
			*/
			$openid->setOptionalInfo(array('dob','nickname','country','language','email','firstname','lastname','gender'));
			
			/*
			* EDIT THIS LINE (OPTIONAL)
			* This is the same as above, except much stricter. By using this method, you
			* are telling the OpenID Provider you *must* have this information. If the Provider
			* will not give you the information the transaction should logically fail, either 
			* at the Provider's end or yours. No info, no sign in. Uncomment to use it.
			*/
			//$openid->setRequiredInfo(array('dob','nickname','country','language','email'));
			
			/*
			* EDIT THIS LINE (OPTIONAL)
			* PAPE Policies help protect users and you against phishing and other authentication
			* forgeries. It's an optional extension, so not all OpenID Providers will be using it.
			* Uncomment to use it.
			* More info and possible policy values here: http://openid.net/specs/openid-provider-authentication-policy-extension-1_0-01.html
			*/
			//$openid->setPapePolicies('http://schemas.openid.net/pape/policies/2007/06/phishing-resistant ');
			
			/*
			* EDIT THIS LINE (OPTIONAL)
			* Also part of the PAPE extension, you can set a time limit for users to
			* authenticate themselves with their OpenID Provider. If it takes too long,
			* authentication will fail and the user will not be allowed access to your site.
			* Uncomment and set a value in seconds to use.
			*/
			//$openid->setPapeMaxAuthAge(120);
			
			/*
			* Attempt to discover the user's OpenID provider endpoint
			*/
			$endpoint_url = $openid->getOpenIDEndpoint();
			if($endpoint_url)
			{
				// If we find the endpoint, you might want to store it for later use.
				$_SESSION['openid_endpoint_url'] = $endpoint_url;
				// Redirect the user to their OpenID Provider
				$openid->redirect();
				// Call exit so the script stops executing while we wait to redirect.
				exit;
			}
			else
			{
				/*
				* Else we couldn't find an OpenID Provider endpoint for the user.
				* You can report this error any way you like, but just for demonstration
				* purposes we'll get the error as reported by Dope OpenID. It will be
				* displayed farther down in this file with the HTML.
				*/
				$the_error = $openid->getError();
				$error = "Error Code: {$the_error['code']}<br />";
				$error .= "Error Description: {$the_error['description']}<br />";
			}
		}	
	}


/*
* Begin the verification process.
* Note: This is the script that should execute at your return URL, 
* in case you choose to put it in a separate file.
*/
if(strcmp($_GET['openid_mode'],"cancel") == 0)
{
	$error = "OpenID authorization canceled by user.";
	echo "<script> top.location.href='../../index.php'</script>";
}
else if(isset($_GET['action']) && $_GET['action']=="verify" && $_GET['openid_mode'] != "cancel")
{
	/*
	* Include the Dope OpenID class file
	*/
	require_once 'class.dopeopenid.php';
	
	
	$db_link_id = mysql_connect(CConfig::HOST, CConfig::USER_NAME , CConfig::PASSWORD) or die("Could not connect: " . mysql_error());
	
	if(!mysql_select_db(CConfig::DB_MCAT, $db_link_id))
	{
		die ('Can\'t use '.CConfig::DB_MCAT.' : ' . mysql_error());
	}
	
	// Get the user's OpenID Identity as returned to us from the OpenID Provider
	
	$openid_url = $_GET['openid_identity'];
	
	/*
	* Create a new Dope_OpenID object.
	*/
	$openid = new Dope_OpenID($openid_url);
	
	/*
	* All the data we received from the OpenID Provider must now be sent back
	* to validate it and verify that nothing has been tampered with in the process.
	*/
	$validate_result = $openid->validateWithServer();
	if ($validate_result === TRUE) 
	{
		
		$userinfo = $openid->filterUserInfo($_GET);
		$userid=CUtils::uuid();
		$final=$userinfo['email'];
		$fname=$userinfo['firstname'];
		$lname=$userinfo['lastname'];
		mysql_query("insert ignore into users (user_id,email,firstname,lastname) values ('$userid','$final','$fname','$lname')",$db_link_id) or die ("Could not insert into users: " . mysql_error());
		$link=mysql_query("select user_id from users where email like '$final'",$db_link_id);
		$value=mysql_fetch_object($link);
		CSessionManager::SetUserId($value->user_id);
		CSessionManager::SetEmailId($email);
		CSessionManager::SetLoggedIn(true) ;
		CSessionManager::SetReferredFrom(CSessionManager::REF_FROM_MY_MGOOG_PHP);
		echo "<script> top.location.href='../../dashboard.php'</script>";
	}
	
}

?>

