<?php 
	include_once("../../../database/mcat_db.php");
	include_once("../../../lib/utils.php");
	include_once("../../../lib/session_manager.php");
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$objDB = new CMcatDB();
	
	$user_id     = CSessionManager::Get(CSessionManager::STR_USER_ID);
	$user_type   = $objDB->GetUserType($user_id);
	
	function clean($str)
	{
		/*if(!get_magic_quotes_gpc())
		{
			$str = trim(mysql_real_escape_string($str));
		}
		else*/
		{
			$str = trim($str);
		}
	
		return $str;
	}
	
	//Sanitize the POST values
	$coordinator_id			= clean($_GET['user_id']);
	$permissions        = $objDB->GetPermissions($coordinator_id);
	$permitted_all 		= false;
	$MNG_QUES			= false;
	$TST_DSG_WZD		= false;
	$REG_CAND			= false;
	$SCD_TEST			= false;
	$TRD_PKG			= false;
	$BRIEF_RESULT		= false;
	$TST_DNA			= false;
	$PROD_CUSTM_RESULT	= false;
	$SNEEK_PEEK			= false;
	$RESULT_INSPCTN     = false;
	$RESULT_ANALYTICS	= false;
	
	if((CConfig::$PERMISSIONS[CConfig::UT_COORDINATOR]["ALL"]) == $permissions)
	{
		$permitted_all = true;
	}
	else
	{
		if((CConfig::$PERMISSIONS[CConfig::UT_COORDINATOR]["MNG_QUES"] & $permissions) != CConfig::$PERMISSIONS[CConfig::UT_COORDINATOR]["NOT_ALWD"])
		{
			$MNG_QUES = true;
		}
		if((CConfig::$PERMISSIONS[CConfig::UT_COORDINATOR]["TST_DSG_WZD"] & $permissions) != CConfig::$PERMISSIONS[CConfig::UT_COORDINATOR]["NOT_ALWD"])
		{
			$TST_DSG_WZD = true;
		}
		if((CConfig::$PERMISSIONS[CConfig::UT_COORDINATOR]["SCD_TEST"] & $permissions) != CConfig::$PERMISSIONS[CConfig::UT_COORDINATOR]["NOT_ALWD"])
		{
			$SCD_TEST = true;
		}
		if((CConfig::$PERMISSIONS[CConfig::UT_COORDINATOR]["REG_CAND"] & $permissions) != CConfig::$PERMISSIONS[CConfig::UT_COORDINATOR]["NOT_ALWD"])
		{
			$REG_CAND = true;
			//echo "hello";
		}
		if((CConfig::$PERMISSIONS[CConfig::UT_COORDINATOR]["TRD_PKG"] & $permissions) != CConfig::$PERMISSIONS[CConfig::UT_COORDINATOR]["NOT_ALWD"] & $owner_type == CConfig::UT_INSTITUTE )
		{
			$TRD_PKG = true;
		}
		if((CConfig::$PERMISSIONS[CConfig::UT_COORDINATOR]["BRIEF_RESULT"] & $permissions) != CConfig::$PERMISSIONS[CConfig::UT_COORDINATOR]["NOT_ALWD"])
		{
			$BRIEF_RESULT = true;
		}
		if((CConfig::$PERMISSIONS[CConfig::UT_COORDINATOR]["TST_DNA"] & $permissions) != CConfig::$PERMISSIONS[CConfig::UT_COORDINATOR]["NOT_ALWD"])
		{
			$TST_DNA = true;
		}
		if((CConfig::$PERMISSIONS[CConfig::UT_COORDINATOR]["PROD_CUSTM_RESULT"] & $permissions) != CConfig::$PERMISSIONS[CConfig::UT_COORDINATOR]["NOT_ALWD"])
		{
			$PROD_CUSTM_RESULT = true;
		}
		if((CConfig::$PERMISSIONS[CConfig::UT_COORDINATOR]["SNEEK_PEEK"] & $permissions) != CConfig::$PERMISSIONS[CConfig::UT_COORDINATOR]["NOT_ALWD"])
		{
			$SNEEK_PEEK = true;
		}
		if((CConfig::$PERMISSIONS[CConfig::UT_COORDINATOR]["RESULT_INSPCTN"] & $permissions) != CConfig::$PERMISSIONS[CConfig::UT_COORDINATOR]["NOT_ALWD"])
		{
			$RESULT_INSPCTN = true;
		}
		if(($RESULT_INSPCTN == true) || ($PROD_CUSTM_RESULT == true) || ($TST_DNA == true) || ($BRIEF_RESULT == true))
		{
			$RESULT_ANALYTICS = true;
		}
	
	}
?> 
<table>
	<tr>
		<td> 
			<table>
				<tr>
	     			<td> 
						<input id="PERMIT_ALL" name="PERMIT_ALL" value="true" type="checkbox" <?php echo($permitted_all?"checked":"");?>/>
	        			<label for="PERMIT_ALL" class="checkbox inline">Select all</label>
	        		</td>
	        	</tr>
	        	<tr>
	        	   	<td>
						<input id="test_schu" name="PERMISSIONS[]" value="1" class="case"  type="checkbox" <?php echo(($SCD_TEST || $permitted_all)?"checked":"");?>/>
	        			<label for="test_schu" class="checkbox inline">Schedule Test</label>
	        		</td>
	        		<td> 
						<input id="sneek_peek" name="PERMISSIONS[]" class="case"  value="16" type="checkbox" <?php echo (($SNEEK_PEEK ||$permitted_all)?"checked":"");?>/>
	        			<label for="sneek_peek" class="checkbox inline">Sneek Peek</label>
	        		</td>
	        	</tr>
	        	<tr>
	        		<td>
	        			<input id="td_wizard" name="PERMISSIONS[]" class="case"  value="4" type="checkbox" <?php echo (($TST_DSG_WZD || $permitted_all)?"checked":"");?>/>
	        			<label for="td_wizard"class="checkbox inline">Test Design Wizard</label>
	        		</td>
	        		<td>
					   	<input id="brief_result" name="PERMISSIONS[]" class="case"  value="64" type="checkbox" <?php echo (($BRIEF_RESULT || $permitted_all)?"checked":"");?>/>
	        		 	<label for="brief_result" class="checkbox inline">Brief Result</label>
	        		</td>
	        	</tr>
	        	<tr>
	        		<td>
	        	    	<input id="man_que" name="PERMISSIONS[]" class="case"  value="8" type="checkbox" <?php echo (($MNG_QUES || $permitted_all)?"checked":"");?>/>
	        		 	<label for="man_que"class="checkbox inline">Manage Questions</label>
	        		</td>
	        		<td>
	        		 	<input id="reg_candi" name="PERMISSIONS[]" value="2" class="case"  type="checkbox" <?php echo (($REG_CAND || $permitted_all)?"checked":"");?>/>
					   	<label for="reg_candi" class="checkbox inline">Register candidates</label><br/>
					</td>
				</tr>
				<tr>
					<td>
						<input id="tst_dna" name="PERMISSIONS[]" class="case"  value="128" type="checkbox" <?php echo (($TST_DNA || $permitted_all)?"checked":"");?>/>
	        			<label for="tst_dna" class="checkbox inline">Test DNA Analysis</label>
	        		</td>
	        		<td>
	        			<input id="pro_cstm_result" name="PERMISSIONS[]" class="case"  value="256" type="checkbox" <?php echo (($PROD_CUSTM_RESULT || $permitted_all)?"checked":"");?>/>
	        			<label for="pro_cstm_result" class="checkbox inline">Produce Custom Result</label>
	        		</td>
	        	</tr>
	        	<tr>
	        		<td>
	        			<input id="result_inpe" name="PERMISSIONS[]" class="case"  value="512" type="checkbox" <?php echo (($RESULT_INSPCTN || $permitted_all)?"checked":"");?>/>
	        			<label for="result_inpe" class="checkbox inline"> Result inpectiont</label>
	        		</td>			  	 
	        		<?php
					if($user_type == CConfig::UT_INSTITUTE )
					{
					?>					
					<td>
						<input id="trd_pkg" name="PERMISSIONS[]" class="case" value="32" type="checkbox" <?php echo (($TRD_PKG || $permitted_all)?"checked":"");?>/>
	        			<label for="trd_pkg"class="checkbox inline"> Trade Test Pakage</label></br>
	        		</td>
	        	</tr>					  
	        		<?php
					}
					?>
			</table>
        </td>
	</tr>			
</table>
<p > <FONT ID="Permissions_MSG" SIZE="" ALIGN=\"CENTRE\" COLOR="BLUE">* please choose at least a permission for coordinator</FONT></p>
								
<script type="text/javascript">
	$(function(){
		$("#PERMIT_ALL").click(function () {
			$('.case').attr('checked', this.checked);
		});

		$(".case").click(function(){

			if($(".case").length == $(".case:checked").length)
			{
				$("#PERMIT_ALL").attr("checked", "checked");
			}
			 else 
			{
				$("#PERMIT_ALL").removeAttr("checked");
			}

			if($(".case:checked").length > 0)
			{
				bValid = true;
				var objMsg = document.getElementById("Permissions_MSG");
				objMsg.color = "blue";
			}
			else
			{
				bValid = false;
				var objMsg = document.getElementById("Permissions_MSG");
				objMsg.color = "red";		
			}
		});
	});
				
	$("#PERMIT_ALL").click(function () {
		if($("#PERMIT_ALL").is(':checked'))
		{
			bValid = true;	
			var objMsg = document.getElementById("Permissions_MSG");
			objMsg.color = "blue";
		}
		else
		{
			bValid = false;
			var objMsg = document.getElementById("Permissions_MSG");
			objMsg.color = "RED";			
		}
	});
</script>
					