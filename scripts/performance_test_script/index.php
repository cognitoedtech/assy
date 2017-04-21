<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
		<script type="text/javascript" charset="utf-8" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/jquery.validate.min.js"></script>
	</head>
	<body style="font: 75% 'Trebuchet MS', sans-serif; margin: 50px; overflow:hidden;">
		<form action="run_script.php" name="script_form" id="script_form" method="post">
			<label><b>Number of Http Requests: </b></label>
			<input type="text" id="http_requests" name="http_requests" /><br/><br/>
							
			<label><b>Number of Database Connections per Http Request: </b></label>
			<input type="text" id="db_con" name="db_con" /><br/><br/>
			
			<label><b>Sleep Time in Seconds for a Database Connection: </b></label>
			<input type="text" id="sleep_time" name="sleep_time" /><br/><br/>
			
			<input type="submit" value="Submit" />
		</form>
	</body>
	<script type="text/javascript">
	$(document).ready(function (){
		$('#script_form').validate({
			rules: {
				http_requests: {required: true, digits: true},
				db_con: {required: true, digits: true},
				sleep_time: {required: true, digits: true}
			},
			messages: {
				http_requests:			{required: "<span style='color:red;'>Please provide number of http requests!</span>", digits: "<span style='color:red;'>Please only enter digits for http requests!</span>"},
				db_con:			{required: "<span style='color:red;'>Please provide number of database connections per request!</span>", digits: "<span style='color:red;'>Please only enter digits for database connections per request!</span>"},
				sleep_time:			{required: "<span style='color:red;'>Please provide number of seconds for a DB connection to sleep!</span>", digits: "<span style='color:red;'>Please only enter digits for number of seconds for a DB connection to sleep!</span>"},
			}
		});
	});
	</script>
</html>