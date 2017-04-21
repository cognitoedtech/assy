<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	include_once(dirname(__FILE__)."/../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../lib/include_js_css.php");
	include_once(dirname(__FILE__)."/../lib/site_config.php");
	include_once(dirname(__FILE__)."/../lib/utils.php");
	include_once(dirname(__FILE__)."/../lib/new-email.php");
	include_once(dirname(__FILE__)."/../lib/user_manager.php");
	include_once(dirname(__FILE__)."/../database/config.php");
	include_once(dirname(__FILE__)."/../database/mcat_db.php");
	
	//$page_id = CSiteConfig::HF_FAQ;
	$login = CSessionManager::Get(CSessionManager::BOOL_LOGIN);
	
	$parsAry = parse_url(CUtils::curPageURL());
	$qry = split("[=&]", $parsAry["query"]);
	
	$objIncludeJsCSS = new IncludeJSCSS();
	
	// - - - - - - - - - - - - - - - - - - - - -
	// Password Change Form.
	// - - - - - - - - - - - - - - - - - - - - -
	function ChngPwdForm($pwd, $uid, $email)
	{
		$sPwdForm = "<div class='container'><div class='row'>Enter new password...</div></div><br />";
		$sPwdForm .= sprintf("<div class='container'><div class='row'><form class='form-horizontal' id='frm_pwd' method='post' action='forgot_done.php?status=1'>
				<div class='form-group'>
					<label for='password' class='col-lg-2 col-md-2 col-sm-2' control-label'>Password :</label>
					<div class='col-lg-4 col-md-4 col-sm-4'>
						<input class='form-control' name='password' id='password' type='password' />
					</div>
				</div>
				<div class='form-group'>
					<label for='cpassword' class='col-lg-2 col-md-2 col-sm-2' control-label'>Confirm Password :</label>
					<div class='col-lg-4 col-md-4 col-sm-4'>
						<input class='form-control' name='cpassword' id='cpassword' type='password' />
					</div>
				</div>
				<div class='form-group'>
					<div class='col-lg-10 col-md-10 col-sm-10  col-lg-offset-2 col-md-offset-2 col-sm-offset-2'>
						<button type='submit' class='btn btn-primary'>Submit</button>
					</div>
				</div>
				<input type='hidden' name='loc' value='%s'>
				<input type='hidden' name='offset' value='%s'>
				<input type='hidden' name='rand' value='%s'>
			</form></div></div>", $pwd, $uid, $email);
		
		return $sPwdForm;
	}
	// - - - - - - - - - - - - - - - - - - - - -
	
	$sMsg = "";
	if($qry[0] == "loc")
	{
		$md5_pwd 	= $qry[1] ; // loc
		$cand_id	= $qry[3] ; // offset
		$md5_email 	= $qry[5] ; // rand
		
		$objUM = new  CUserManager();
		$objUser = $objUM->GetUserById($cand_id);
		
		$sMsg .= sprintf("<div class='container'><div class='row'>Welcome <b>%s %s</b>, to %s&rsquo;s password retrieval zone.</div></div><br />",$objUser->GetFirstName(),$objUser->GetLastName(), CConfig::SNC_SITE_NAME);
		$sMsg .= ChngPwdForm($md5_pwd, $cand_id, $md5_email);
	}
	else if($qry[0] == "status")
	{
		$password	= $_POST['password'] ; 	// password
		$cpassword	= $_POST['cpassword'] ; // cpassword
		$md5_pwd 	= $_POST['loc'] ; 		// loc
		$cand_id	= $_POST['offset'] ; 	// offset
		$md5_email 	= $_POST['rand'] ; 		// rand
		
		$objUM  = new CUserManager();
		$objUser = $objUM->GetUserById($cand_id);
		$result  = $objUM->UpdatePassword($cand_id, $md5_pwd, $password);
	
		if ($result)
		{
			$ip_addr = $_SERVER['REMOTE_ADDR'];
			$objDB = new CMcatDB();
			$objMail = new CEMail(CConfig::OEI_SUPPORT, $objDB->GetPasswordFromOfficialEMail(CConfig::OEI_SUPPORT));
			$objMail->PrepAndSendPwdChangedAckMail($objUser->GetEmail(), $objUser->GetFirstName()." ".$objUser->GetLastName(), $ip_addr);
			//CEMail::PrepAndSendPwdChangedAckMail($objUser->GetEmail(), $objUser->GetFirstName()." ".$objUser->GetLastName(), $ip_addr);
			$sMsg .= sprintf("<p style='color:blue;'>Dear <b>%s %s</b>,<br/><br/>Your password is changed, you will be redirected to login page in a moment.</p>",$objUser->GetFirstName(),$objUser->GetLastName());
			$sMsg .= sprintf("<script>setTimeout(function() {location.href='../index.php';}, 5000);</script>");
		}
		else
		{
			$sMsg .= sprintf("<p style='color:red'>Dear User Your password change process is failed, you can't enter recently used password. Please Re-Enter the Password</p>");
			
			$sMsg .= ChngPwdForm($md5_pwd, $cand_id, $md5_email);
		}
	}
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo(CConfig::SNC_SITE_NAME);?>: Change Password</title>
		<style>
			a.anchor:link {color:GhostWhite;}    /* unvisited link */
			a.anchor:visited {color:GhostWhite;} /* visited link */
			a.anchor:hover {color:GhostWhite;}   /* mouse over link */
			a.anchor:active {color:GhostWhite;}  /* selected link */
			a:focus {outline: none;}
		</style>
		<?php 
			$objIncludeJsCSS->CommonIncludeCSS("../");
			$objIncludeJsCSS->IncludeMipcatCSS("../");
			$objIncludeJsCSS->CommonIncludeJS("../");
			$objIncludeJsCSS->IncludeJqueryValidateMinJS("../")
		?>
		<script type="text/javascript" src="../3rd_party/wizard/js/jquery.validate.min.js"></script>
	</head>
	<body>
		<!-- Header -->
		<?php
			include(dirname(__FILE__)."/../lib/header.php");
		?>
		<br />
		<br />
		<div class="container">
			<h3 class="text-center" style="color:steelblue;">Restore Password</h3><br/>
			<div class="drop-shadow raised">
				<?php
					echo ("<fieldset>".$sMsg."</fieldset>");
				?>
			</div>
			<div class=' col-lg-offset-1 col-md-offset-1 col-sm-offset-1'>
				<?php
					include_once (dirname ( __FILE__ ) . "/../lib/footer.php");
				?>
			</div>
		</div>
		
		<script type="text/javascript">
			$('#frm_pwd').validate({
				errorPlacement: function(error, element) {
					$(error).insertAfter(element);
					//$('#acadamic_details div.reg-error').append(error);
				}, rules: {
					'password':		{required:true, minlength:8},
					'cpassword':	{required:true, equalTo: "#password"}
				}, messages: {
					'password':		{required:  '<span style="color:red">Please enter valid password!</span>', minlength: '<span style="color:red">Password length should be minimum 8 letters!</span>' },
					'cpassword':	{required:  '<span style="color:red">Please enter password again!</span>', equalTo: '<span style="color:red">Confirm password should be same as password!</span>' }
				},submitHandler: function(form) {
					form.submit();
				}
			});
		</script>
	</body>
</html>

		
	