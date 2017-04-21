<!-- PHP Wrapper - 500 Server Error -->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><head><title>500 Server Error</title></head>
<body bgcolor=white>
<h1>500 Server Error</h1>

A misconfiguration on the server caused a hiccup.
Check the server logs, fix the problem, then try again.
<hr>

<?
echo "URL: http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]<br>\n";
$fixer = "checksuexec ".escapeshellarg($_SERVER[DOCUMENT_ROOT].$_SERVER[REQUEST_URI]);
echo `$fixer`;
?>

</body></html>
