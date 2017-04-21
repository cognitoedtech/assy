<?php 
	include_once(dirname(__FILE__)."/../../../test/lib/test_helper.php");
	include_once(dirname(__FILE__)."/../../../lib/session_manager.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	if(isset($_POST['test_id']))
	{
		$objTH 	 = new CTestHelper();
		$test_id = $_POST['test_id'];
		
		$objSecDetails = $objTH->GetSectionDetails($test_id);
		
		foreach($objSecDetails as $key)
		{
			if(!empty($key['questions']))
			{
?>
				<fieldset>
					<legend>Section - <?php echo($key['name']);?></legend>
										 
					<p>
						<label for="percent_<?php echo($key['name']);?>">Percent Range:</label>
					  	<input type="text" id="percent_<?php echo($key['name']);?>" style="border: 0; color: #0099ff; background-color: white; font-weight: bold;font-size :11px;" readonly/>
					</p>
					
					<div id="slider-range-percent-<?php echo($key['name']);?>" style="border:1px solid #ccc;"></div>
					
					<div style="width:40%;float:right;">
						<p>
							<label for="weight_<?php echo($key['name']);?>">Weightage(Low - High):</label>
						  	<input type="text" id="weight_<?php echo($key['name']);?>" value='1' style="border: 0; color: #0099ff; background-color: white; font-weight: bold;font-size :11px;" readonly/>
						</p>
						
						<div id="slider-range-weight-<?php echo($key['name']);?>" style="border:1px solid #ccc;"></div>
					</div><br />
				</fieldset><br /><br />
<?php 
			}
		}
	}
?>