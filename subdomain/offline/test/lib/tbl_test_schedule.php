<?php
	class CTestSchedule
	{
		private $objDBLink;
		
		public function __construct($objDBLink)
		{
			$this->objDBLink = $objDBLink;
		}
		
		public function __destruct()
		{
			
		}
		// - - - - - - - - - - - - - - - - - - - - - - - - -
		// Private Functions
		// - - - - - - - - - - - - - - - - - - - - - - - - -
		private function PreparePNRList($user_id, $test_id, $pnr)
		{
			$sPNRList = null;
			$query = sprintf("select * from test_schedule where test_id='%s'", $test_id);
			//echo $query."<br/>";
			
			$result = mysql_query($query, $this->objDBLink) or die('Prepare PNR List error : ' . mysql_error());
			
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				
				$aryUserID 	= explode(";", $row['user_list']);
				$aryPNR		= explode(";", $row['pnr_list']);
				
				foreach($aryUserID as $key => $UID)
				{
					if($user_id == $UID)
					{
						$aryPNR[$key] = $pnr;
						break;
					}
				}
				
				$sPNRList = implode(";", $aryPNR);
				
				//echo $sPNRList."<br/>";
			}
			
			return $sPNRList;
		}
		
		// - - - - - - - - - - - - - - - - - - - - - - - - -
		// Public Functions
		// - - - - - - - - - - - - - - - - - - - - - - - - -
		public function AddTestPNRForUser($user_id, $test_id, $tschd_id, $pnr)
		{
			//$sPNRList = $this->PreparePNRList($user_id, $test_id, $pnr);
			
			$query = sprintf("update test_schedule set pnr_list=concat(pnr_list, '%s;') where test_id='%s' and locate('%s', user_list) and schd_id='%s'", $pnr, $test_id, $user_id, $tschd_id);
			//echo $query."<br/>";
			
			$result = mysql_query($query, $this->objDBLink) or die('Add Test PNR For User error : ' . mysql_error());
			
			return $result;
		}
	}
?>