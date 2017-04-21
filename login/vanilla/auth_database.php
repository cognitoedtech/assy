<?php
	class AuthDatabase
	{
		public function __construct()
		{
			$this->dbLink = mysql_connect("localhost","root","");
			if (!$this->dbLink)
			{
				die('Could not connect to Database: ' . mysql_error());
			}
	
			if (!mysql_select_db("mgooscom_newmcat", $this->dbLink)) 
			{
				die ('Can\'t select Database : ' . mysql_error());
			}
		}
		
		public function __destruct()
		{
			mysql_close($this->dbLink);
		}
		
		public function authenticate($email,$password)
		{
			$query = sprintf("select * from users where email='%s' AND passwd=md5('%s')",$email,$password);
			$result = mysql_query($query, $this->dbLink) or die('select from users error: ' . mysql_error());
			$row = mysql_fetch_array($result);
			return $row;
		}
	}
?>
