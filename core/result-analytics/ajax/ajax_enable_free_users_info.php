<?php 
	include_once(dirname(__FILE__)."/../../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../../lib/billing.php");
	include_once(dirname(__FILE__)."/../../../database/config.php");
	include_once(dirname(__FILE__)."/../../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/../../../lib/utils.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	
	if(isset($_POST['free_user_id']) && isset($_POST['org_id']))
	{
		$objDB = new CMcatDB();
		
		$objBilling = new CBilling();
		
		$projectedBal = $objBilling->GetProjectedBalance($user_id);
		
		$personalQuesRate = $objBilling->GetPersonalQuesRate($user_id);
		
		$updatedInfo = "";
		
		if($projectedBal >= $personalQuesRate)
		{
			$objDB->UpdateFreeUserOwnerOrg($_POST['free_user_id'], $_POST['org_id']);
			
			$objBilling->SubProjectedBalance($user_id, $personalQuesRate);
			
			$objBilling->SubBalance($user_id, $personalQuesRate);
			
			$objBilling->AddFreeUserBillingHistory($user_id, 1, $personalQuesRate);
			
			$updatedInfo = $objDB->GetFreeUsersByOrgId($_POST['org_id']);
		}
		else 
		{
			$updatedInfo = $objDB->GetFreeUsersByOrgId($_POST['org_id']);
			
			$updatedInfo['error'] = "<span style='color: red;'>* Please recharge your account. You do not have sufficient balance!</span>";
		}
		
		echo json_encode($updatedInfo);
	}
	else if(isset($_POST['bAll']) && isset($_POST['org_id']) && $_POST['bAll'] == 1)
	{
		$objDB = new CMcatDB();
		
		$objBilling = new CBilling();
		
		$projectedBal = $objBilling->GetProjectedBalance($user_id);
		
		$personalQuesRate = $objBilling->GetPersonalQuesRate($user_id);
		
		$free_user_ids = $objDB->GetDisabledFreeUserIds($_POST['org_id']);
		
		$updatedInfo = "";
		
		if(!empty($free_user_ids))
		{
			if($projectedBal >= (count($free_user_ids) * $personalQuesRate))
			{
				$objDB->UpdateFreeUserOwnerOrg(null, $_POST['org_id'], $free_user_ids, true);
				
				$objBilling->SubProjectedBalance($user_id, (count($free_user_ids) * $personalQuesRate));
					
				$objBilling->SubBalance($user_id, (count($free_user_ids) * $personalQuesRate));
				
				$objBilling->AddFreeUserBillingHistory($user_id, count($free_user_ids), (count($free_user_ids) * $personalQuesRate));
				
				$updatedInfo = $objDB->GetFreeUsersByOrgId($_POST['org_id']);
			}
			else if($projectedBal >= $personalQuesRate)
			{
				$updatedInfo = $objDB->GetFreeUsersByOrgId($_POST['org_id']);
				
				$possibleEnables = floor($projectedBal/$personalQuesRate);
				
				$updatedInfo['error'] = "<span style='color: red;'>* Please recharge your account. You do not have sufficient balance.You can only enable information of ".$possibleEnables." candidates!</span>";
			}
			else 
			{
				$updatedInfo = $objDB->GetFreeUsersByOrgId($_POST['org_id']);
				
				$updatedInfo['error'] = "<span style='color: red;'>* Please recharge your account. You do not have sufficient balance.!</span>";
			}
		}
		else 
		{
			$updatedInfo = $objDB->GetFreeUsersByOrgId($_POST['org_id']);
		}
		
		echo json_encode($updatedInfo);
	}
?>