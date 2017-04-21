<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	include_once(dirname(__FILE__)."/../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../lib/site_config.php");
	include_once(dirname(__FILE__)."/../../lib/utils.php");
	include_once(dirname(__FILE__)."/../../database/config.php");
	$page_id = CSiteConfig::HF_FAQ;
	$login = CSessionManager::Get(CSessionManager::BOOL_LOGIN);
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Become Business Associate</title>
		<style>
			#overlay { position: fixed; left: 0px; top: 0px; width: 100%; height: 100%; z-index: 100; background-color:white;}
			a.anchor:link {color:GhostWhite;}    /* unvisited link */
			a.anchor:visited {color:GhostWhite;} /* visited link */
			a.anchor:hover {color:GhostWhite;}   /* mouse over link */
			a.anchor:active {color:GhostWhite;}  /* selected link */
			a:focus {outline: none;}
		</style>
		<link rel="stylesheet" type="text/css" href="../../css/mipcat.css" />
		<link rel="stylesheet" type="text/css" href="../../3rd_party/bootstrap/css/bootstrap.css" />
		<script type="text/javascript" src="../../js/jquery.js"></script>
		<script type="text/javascript" src="../../3rd_party/bootstrap/js/bootstrap.js"></script>
		<script type="text/javascript" src="../../3rd_party/wizard/js/jquery.validate.min.js"></script>
	</head>
	<body style="font: 75% 'Trebuchet MS', sans-serif; margin: 5px;">
		<!-- Header -->
		<?php
			include(dirname(__FILE__)."/../../lib/header.php");
		?>
		
		<div style="margin-left:40px;margin-right:40px;font: 120% 'Trebuchet MS', sans-serif;">
			<h3 style="text-align:center;color:steelblue;">Become our Business Associate</h3><br/>
			<p>
				Please post an enquiry, our business development executive will contact you soon.<br/>
				<form id="frm_ba_req" method="post" action="ba_request_exec.php">
					<label>Contact #:</label>
					<input type="text" name="contact" style="width:300px;height:25px;"/><br/>
					<label>Email ID:</label>
					<input type="text" name="email" style="width:400px;height:25px;"/><br/>
					<label>Organization Name:</label>
					<input type="text" name="org_name" style="width:400px;height:25px;"/><br/>
					<label>Subject:</label>
					<input type="text" name="subject" style="width:400px;height:25px;"/><br/>
					<label>Message:</label>
					<textarea name="message" style="width:400px" rows="6"></textarea><br/>
					<input type="submit" value="Request"/>
				</form>
			</p>
		</div>
		
		<script type="text/javascript">
			$('#frm_ba_req').validate({
				errorPlacement: function(error, element) {
					$(error).insertAfter(element);
				}, rules: {
					'contact':		{required:true, minlength:10, digits: true},
					'email':		{required:true, email:true},
					'org_name':		{required:true, minlength:4},
					'subject':		{required:true, minlength:10},
					'message':		{required:true, minlength:40},
				}, messages: {
					'contact':		{required:  '<span style="color:red">Please enter a valid 10 digit contact number!</span>', minlength: '<span style="color:red">Please enter a valid 10 digit contact number!</span>', digits: '<span style="color:red">Please enter digits only!</span>'},
					'email':		{required:  '<span style="color:red">Please enter a valid email id!</span>', email:  '<span style="color:red">Please enter a valid email id!</span>'}, 
					'org_name':		{required:  '<span style="color:red">Please enter a valid organization name!</span>', minlength: '<span style="color:red">Please enter name atleast 4 letters long!</span>'},
					'subject':		{required:  '<span style="color:red">Please write Subject for Communication!</span>', minlength: '<span style="color:red">Please write a subject atleast 10 letters long!</span>'},
					'message':		{required:  '<span style="color:red">Please write a message to <?php echo(CConfig::SNC_SITE_NAME);?> team for enquiry!</span>', minlength: '<span style="color:red">Please write a message atleast 40 letters long!</span>'}
				},submitHandler: function(form) {
					form.submit();
				}
			});
		</script>
	</body>
</html>