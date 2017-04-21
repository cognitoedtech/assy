<?php
	session_start();
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
	<HEAD>
		<TITLE>Please wait...</TITLE>
		<SCRIPT LANGUAGE="JavaScript">
			function CheckForParent()
			{
				window.parent.location = "../dashboard.php" ;
			}
		//-->
		</SCRIPT>
	</HEAD>
	
	<BODY>
		<B>Verifying Login Details.<BR/>Please Wait...<BR/></B>
		<IMG SRC="../images/updating.gif" WIDTH="16" HEIGHT="16" BORDER="0" ALT="">
		<?php
			echo("<SCRIPT LANGUAGE=\"JavaScript\">") ;
			echo("session_id='".session_id()."';") ;
			echo("CheckForParent();") ;
			echo("</SCRIPT>") ;
		?>
	</BODY>
</HTML>
