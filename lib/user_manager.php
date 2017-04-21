<?php
	include_once("user.php") ;
	
	class CUserManager
	{
		private $db_link_id ;
		
		public function __construct()
		{
			// Server Name, UserName, Password, Database Name		
			$this->db_link_id = mysql_connect(CConfig::HOST, CConfig::USER_NAME , CConfig::PASSWORD) or
				    die("Could not connect: " . mysql_error());
			mysql_select_db(CConfig::DB_MCAT, $this->db_link_id);
		}
		
		public function __destruct()
		{
			mysql_close($this->db_link_id) ;
		}
		
		public function GetUserById($id)
		{
			$objUser = new CUser() ;
			
			$result = mysql_query("select * from users where ".CUser::FIELD_USER_ID."='".$id."';", $this->db_link_id) or die("Get User Info Error: ".mysql_error($this->db_link_id)) ;
			while($row = mysql_fetch_array($result, MYSQL_ASSOC))
			{
				$objUser->SetCity($row[CUser::FIELD_CITY]) ;
				$objUser->SetUserType($row[CUser::FIELD_USER_TYPE]) ;
				$objUser->SetLoginName($row[CUser::FIELD_LOGIN_NAME]) ;
				$objUser->SetOrganizationId($row[CUser::FIELD_ORGANIZATION_ID]) ;
				$objUser->SetCountry($row[CUser::FIELD_COUNTRY ]) ;
				$objUser->SetDOB($row[CUser::FIELD_DOB]) ;
				$objUser->SetEmail($row[CUser::FIELD_EMAIL]) ;
				$objUser->SetFirstName($row[CUser::FIELD_FIRST_NAME]) ;
				$objUser->SetLastName($row[CUser::FIELD_LAST_NAME]) ;
				$objUser->SetGender($row[CUser::FIELD_GENDER]) ;
				$objUser->SetPassword($row[CUser::FIELD_PASSWORD]) ;
				$objUser->SetContactNo($row[CUser::FIELD_CONTACT_NO]) ;
				$objUser->SetAddress($row[CUser::FIELD_ADDRESS]) ;
				$objUser->SetRegStatus($row[CUser::FIELD_REG_STATUS]) ;
				$objUser->SetSecAns($row[CUser::FIELD_SECURITY_ANS]) ;
				$objUser->SetSecQues($row[CUser::FIELD_SECURITY_QUES]) ;
				$objUser->SetSignupDate($row[CUser::FIELD_SIGNUP_DATE]) ;
				$objUser->SetState($row[CUser::FIELD_STATE]) ;
				$objUser->SetUserID($row[CUser::FIELD_USER_ID]) ;
			}
			mysql_free_result($result) ;
			
			return $objUser ;
		}
		
		public function GetUserByEmail($email)
		{
			$objUser = new CUser() ;
			
			$result = mysql_query("select * from users where ".CUser::FIELD_EMAIL."='".$email."';", $this->db_link_id) or die("Get User Info Error: ".mysql_error($this->db_link_id)) ;
			while($row = mysql_fetch_array($result, MYSQL_ASSOC))
			{
				$objUser->SetCity($row[CUser::FIELD_CITY]) ;
				$objUser->SetUserType($row[CUser::FIELD_USER_TYPE]) ;
				$objUser->SetLoginName($row[CUser::FIELD_LOGIN_NAME]) ;
				$objUser->SetOrganizationId($row[CUser::FIELD_ORGANIZATION_ID]) ;
				$objUser->SetCountry($row[CUser::FIELD_COUNTRY ]) ;
				$objUser->SetDOB($row[CUser::FIELD_DOB]) ;
				$objUser->SetEmail($row[CUser::FIELD_EMAIL]) ;
				$objUser->SetFirstName($row[CUser::FIELD_FIRST_NAME]) ;
				$objUser->SetLastName($row[CUser::FIELD_LAST_NAME]) ;
				$objUser->SetGender($row[CUser::FIELD_GENDER]) ;
				$objUser->SetPassword($row[CUser::FIELD_PASSWORD]) ; 
				$objUser->SetContactNo($row[CUser::FIELD_CONTACT_NO]) ;   
				$objUser->SetAddress($row[CUser::FIELD_ADDRESS]) ;			
				$objUser->SetRegStatus($row[CUser::FIELD_REG_STATUS]) ;
				$objUser->SetSecAns($row[CUser::FIELD_SECURITY_ANS]) ;
				$objUser->SetSecQues($row[CUser::FIELD_SECURITY_QUES]) ;
				$objUser->SetSignupDate($row[CUser::FIELD_SIGNUP_DATE]) ;
				$objUser->SetState($row[CUser::FIELD_STATE]) ;
				$objUser->SetUserID($row[CUser::FIELD_USER_ID]) ;
			}
			mysql_free_result($result) ;
			
			return $objUser ;
		}
		
		public function GetFieldValueByID($id, $field)
		{
			$value = "" ;
			
			$result = mysql_query("select ".$field." from users where user_id='".$id."'", $this->db_link_id) or die("Get User Info Error: ".mysql_error($this->db_link_id)) ;
			
			if(mysql_num_rows($result) == 1)
			{
				$row = mysql_fetch_array($result, MYSQL_ASSOC) ;
				$value = $row[$field] ;
				if(strcasecmp($field,"organization_id") == 0)
				{
					$result = mysql_query("select organization_name from organization where organization_id='".$value."';", $this->db_link_id) or die("Get User Info Next Error: ".mysql_error($this->db_link_id)) ;
					
					if(mysql_num_rows($result) == 1)
					{
						$row = mysql_fetch_array($result, MYSQL_ASSOC) ;
						$value = $row['organization_name'] ;
					}
				}
			}
			
			mysql_free_result($result) ;
			
			return $value ;
		}
		
		public function GetFieldValueByEmail($email, $field)
		{
			$value = -1 ;
			
			$result = mysql_query("select ".$field." from users where ".CUser::FIELD_EMAIL."='".$email."';", $this->db_link_id) or die("Get User Info Error: ".mysql_error($this->db_link_id)) ;
			
			if(mysql_num_rows($result) == 1)
			{
				$row = mysql_fetch_array($result, MYSQL_ASSOC) ;
				$value = $row[$field] ;
			}
			mysql_free_result($result) ;
			
			return $value ;
		}
		
		public function ClearFieldValueById($id, $field)
		{
			$bRet = mysql_query("update users set ".$field."='' where ".CUser::FIELD_USER_ID."='".$id."';", $this->db_link_id) or die("Get User Info Error: ".mysql_error($this->db_link_id)) ;
						
			return $bRet ;
		}
		
		public function ClearFieldValueByEmail($email, $field)
		{
			$bRet = mysql_query("update users set ".$field."='' where ".CUser::FIELD_EMAIL."='".$email."';", $this->db_link_id) or die("Get User Info Error: ".mysql_error($this->db_link_id)) ;
			
			return $bRet ;
		}
		
		/*
		  return value = 0 --> invalid password
		  return value = 1 --> password is right but user has not activated account through email link.
		  return value = 2 --> successful login
		  return value = 3 --> partial registration from corporate/super user (excel upload).
		*/
		public function VerifyUser($email, $password)
		{
			$bResult = 0;
			$val_pass = $this->GetFieldValueByEmail($email, "passwd") ;
			if($val_pass != -1)
			{
				$val_reg_status = $this->GetFieldValueByEmail($email, "reg_status") ;
				if(empty($val_pass))
				{
					$val_login_name = $this->GetFieldValueByEmail($email, "login_name") ;
					if(strcasecmp($password, $val_login_name) == 0)
					{
						$bResult = 3;
					}
				}
				else if(strcasecmp(md5($password), $val_pass) == 0)
				{
					if($val_reg_status == 1)
						$bResult = 2;
					else
						$bResult = 1;
				}
			}
			return $bResult ;
		}
		
		public function IsAnyEmpty(CUser $objUser)
		{
			$nRet = false;
			
			if($objUser->GetUserType() == '')
			{
				//echo "Test 1 <br/>";
				$nRet = true;
			}
			else if($objUser->GetLoginName() == '')
			{
				//echo "Test 2 <br/>";
				$nRet = true;
			}
			else if($objUser->GetOrganizationId() == '')
			{
				if($objUser->GetUserType() != CConfig::UT_INDIVIDAL)
				{
					//echo "Test 3 <br/>";
					$nRet = true;
				}
			}
			else if($objUser->GetFirstName() == '')
			{
				//echo "Test 4 <br/>";
				$nRet = true;
			}
			else if($objUser->GetLastName() == '')
			{
				//echo "Test 5 <br/>";
				$nRet = true;
			}
			else if($objUser->GetPassword() == '')
			{
				//echo "Test 6 <br/>";
				$nRet = true;
			}
			else if($objUser->GetEmail() == '')
			{
				//echo "Test 7 <br/>";
				$nRet = true;
			}
			else if($objUser->GetGender() == '')
			{
				//echo "Test 8 <br/>";
				$nRet = true;
			}
			else if($objUser->GetCity() == '')
			{
				//echo "Test 9 <br/>";
				$nRet = true;
			}
			else if($objUser->GetState() == '')
			{
				//echo "Test 10 <br/>";
				$nRet = true;
			}
			else if($objUser->GetCountry() == '')
			{
				//echo "Test 11 <br/>";
				$nRet = true;
			}
			else if($objUser->GetDOB() == '')
			{
				//echo "Test 12 <br/>";
				$nRet = true;
			}
			else if($objUser->GetSecQues() == '')
			{
				//echo "Test 13 <br/>";
				$nRet = true;
			}
			else if($objUser->GetContactNo() == '') 
			{
				//echo "Test 14 <br/>";
				$nRet = true;
			}
			else if($objUser->GetSecAns() == '')
			{
				//echo "Test 16 <br/>";
				$nRet = true;
			}

			return $nRet;
		}
		
		public function AddUser(CUser $objUser)
		{
			$bResult = false ;
			
			$batch = $objUser->GetBatch();
			if(empty($batch))
			{
				$batch = NULL;
			}
			
			$user_id = CUtils::uuid() ;
			$query = sprintf("INSERT INTO users(".CUser::FIELD_USER_ID.", ".CUser::FIELD_USER_TYPE.", ".CUser::FIELD_LOGIN_NAME.", ".CUser::FIELD_ORGANIZATION_ID.", ".CUser::FIELD_PAN_NO.", ".CUser::FIELD_FIRST_NAME.", ".CUser::FIELD_LAST_NAME.", ".CUser::FIELD_PASSWORD.", ".CUser::FIELD_CONTACT_NO .", ".CUser::FIELD_EMAIL.", ".CUser::FIELD_GENDER.", ".CUser::FIELD_ADDRESS.", ".CUser::FIELD_CITY.", ".CUser::FIELD_STATE.", ".CUser::FIELD_COUNTRY.", ".CUser::FIELD_DOB.", ".CUser::FIELD_SECURITY_QUES .", ".CUser::FIELD_SECURITY_ANS.", ".CUser::FIELD_BUSS_ASSOC_ID.", ".CUser::FIELD_OWNER_ID.", ".CUser::FIELD_BATCH.") VALUES('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s') ;", 
							$user_id, $objUser->GetUserType(), $objUser->GetLoginName(), $objUser->GetOrganizationId(), $objUser->GetPANNo(), $objUser->GetFirstName(), $objUser->GetLastName(), md5($objUser->GetPassword()), $objUser->GetContactNo(), $objUser->GetEmail(), $objUser->GetGender(), $objUser->GetAddress(), $objUser->GetCity(), $objUser->GetState(), $objUser->GetCountry(), $objUser->GetDOB(), $objUser->GetSecQues(), $objUser->GetSecAns(), $objUser->GetBusinessAssociateId(), $objUser->GetOwnerID(), mysql_real_escape_string($batch)) ;
			
			//echo  $query."<br/>";
			
			$result = mysql_query($query, $this->db_link_id) or die("Insert User Info Error: ".mysql_error($this->db_link_id)) ;
			if(mysql_affected_rows($this->db_link_id) > 0)
			{
				$bResult = $user_id ;
			}
			
			return $bResult ;
		}
		
		public function AddOfflineOTFACandidate(CUser $objUser)
		{
			$bResult = false ;
				
			$batch = $objUser->GetBatch();
	
			$query = sprintf("INSERT INTO users(".CUser::FIELD_USER_ID.", ".CUser::FIELD_USER_TYPE.", ".CUser::FIELD_LOGIN_NAME.", ".CUser::FIELD_ORGANIZATION_ID.", ".CUser::FIELD_PAN_NO.", ".CUser::FIELD_FIRST_NAME.", ".CUser::FIELD_LAST_NAME.", ".CUser::FIELD_PASSWORD.", ".CUser::FIELD_CONTACT_NO .", ".CUser::FIELD_EMAIL.", ".CUser::FIELD_GENDER.", ".CUser::FIELD_ADDRESS.", ".CUser::FIELD_CITY.", ".CUser::FIELD_STATE.", ".CUser::FIELD_COUNTRY.", ".CUser::FIELD_DOB.", ".CUser::FIELD_SECURITY_QUES .", ".CUser::FIELD_SECURITY_ANS.", ".CUser::FIELD_BUSS_ASSOC_ID.", ".CUser::FIELD_OWNER_ID.", ".CUser::FIELD_BATCH.") VALUES('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s') ;",
					$objUser->GetUserID(), $objUser->GetUserType(), $objUser->GetLoginName(), $objUser->GetOrganizationId(), $objUser->GetPANNo(), $objUser->GetFirstName(), $objUser->GetLastName(), $objUser->GetPassword(), $objUser->GetContactNo(), $objUser->GetEmail(), $objUser->GetGender(), $objUser->GetAddress(), $objUser->GetCity(), $objUser->GetState(), $objUser->GetCountry(), $objUser->GetDOB(), $objUser->GetSecQues(), $objUser->GetSecAns(), $objUser->GetBusinessAssociateId(), $objUser->GetOwnerID(), mysql_real_escape_string($batch)) ;
				
			//echo  $query."<br/>";
				
			$result = mysql_query($query, $this->db_link_id) or die("Add Offline OTFA Candidate Error: ".mysql_error($this->db_link_id)) ;
			if(mysql_affected_rows($this->db_link_id) > 0)
			{
				$bResult = $objUser->GetUserID() ;
			}
				
			return $bResult ;
		}

		public function AddOrganization($org_name, $org_type, $org_size, $org_url, $showcast_public)
		{
			$retID = false ;
			
			$query = "Select * from organization where ".CUser::FIELD_ORGANIZATION_NAME."='".$org_name."'";
			$result = mysql_query($query, $this->db_link_id) or die("Insert User Info Error: ".mysql_error($this->db_link_id)) ;
			
			if(mysql_num_rows($result) == 0)
			{
				$org_id = CUtils::uuid() ;
				$query = sprintf("INSERT INTO organization(".CUser::FIELD_ORGANIZATION_ID.", ".CUser::FIELD_ORGANIZATION_NAME.", ".CUser::FIELD_ORGANIZATION_TYPE.", ".CUser::FIELD_ORGANIZATION_URL.", ".CUser::FIELD_SHOWCAST_PUBLIC.", ".CUser::FIELD_ORGANIZATION_SIZE.") VALUES('%s','%s','%s','%s','%s', '%s') ;", 
								$org_id, $org_name, mysql_real_escape_string($org_type), $org_url, $showcast_public, $org_size) ;
				
				$result = mysql_query($query, $this->db_link_id) or die("Insert User Info Error: ".mysql_error($this->db_link_id)) ;
				if(mysql_affected_rows($this->db_link_id) > 0)
				{
					$retID = $org_id;
				}
			}
			else 
			{
				$row = mysql_fetch_array($result, MYSQL_ASSOC) ;
				$retID = $row[CUser::FIELD_ORGANIZATION_ID];
			}
			
			return $retID ;
		}
		
		public function IsUserExists($email)
		{
			$bResult = false ;
			
			$query = sprintf("select * from users where ".CUser::FIELD_EMAIL."='".$email."' ;") ;
			$result = mysql_query($query, $this->db_link_id) or die("Search For Duplicate Email Error: ".mysql_error($this->db_link_id)) ;
						
			if (mysql_num_rows($result) > 0)
			{
				$bResult = true ;
			}
			
			return $bResult ;
		}
		
		public function UpdatePassword($cand_id, $md5_pwd, $password)
		{
			$bResult = false ;
			
			$query = sprintf("update users set ".CUser::FIELD_PASSWORD."=md5('".$password."') where ".CUser::FIELD_USER_ID."='%s' and ".CUser::FIELD_PASSWORD."='%s';", $cand_id, $md5_pwd) ;
			
			//echo $query."<br/>";
			
			$result = mysql_query($query, $this->db_link_id) or die("Update Password Error: ".mysql_error($this->db_link_id)) ;
			
			if (mysql_affected_rows($this->db_link_id))
			{
				$bResult = true ;
			}
			
			return $bResult ;
		}

		public function SetOnline($id)
		{
			$loginResult= false;
			
			$query = sprintf("update users set ".CUser::FIELD_ONLINE."= 1 where ".CUser::FIELD_USER_ID ."='%s';", $id);

			$result = mysql_query($query,$this->db_link_id) or die ("Error during setting field online:".mysql_error($this->db_link_id));

			if(mysql_affected_rows($this->db_link_id))
			{
				$loginResult = true;
			}
			
			return $loginResult;
		}
		
		public function ResetOnline($id)
		{
			$loginResult= false;

			$query = sprintf("update users set ".CUser::FIELD_ONLINE."= 0 where ".CUser::FIELD_USER_ID."='%s';", $id);

			$result = mysql_query($query,$this->db_link_id) or die ("Error during setting field online:".mysql_error($this->db_link_id));

			if(mysql_affected_rows($this->db_link_id))
			{
				$loginResult = true;
			}
			
			return $loginResult;
		}

		public function ListCountryOption($selected=-1)
		{
			$query  = "select * from countries order by name;";
			$result = mysql_query($query, $this->db_link_id) or die("List Country Option Error: ".mysql_error($this->db_link_id));
			printf(" <option value=\"91\" selected=\"selected\">India</option>");
			while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			{	
				printf("<option value=\"%d\" %s>%s</option>", $row["code"], ($selected==$row["code"])?"selected='selected'":"", $row["name"]);
			}
			mysql_free_result($result) ;
		}

		public function ListDateOption($selected=-1)
		{
			for($index = 1; $index <= 31; $index++)
			{
				printf("<option value=\"%02d\" %s>%d</option>", $index, ($selected==$index)?"selected='selected'":"", $index);
			}
		}

		public function ListMonthOption($selected=-1)
		{
			$monthAry = array(1=>"January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
			
			for($index = 1; $index <= 12; $index++)
			{	
				printf("<option value=\"%02d\" %s>%s</option>", $index, ($selected==$index)?"selected='selected'":"", $monthAry[$index]);
			}
		}
		
		public function ListYearOption($selected=-1)
		{
			$year = date("Y");
			$year =$year-12;
		
			for($count=1;$count<=80;$count++)
			{
				printf("<option value=\"%d\" %s>%d</option>",$year,($selected==$year)?"selected='selected'":"",$year);
				$year--;
			}
		}
		
		public function ListOrgSizeOption($selected=-1)
		{
			$orgSizeAry = array("Less than 100", "100 - 200", "201 - 500", "501 - 1000", "1,001 - 2,000", "2,001 - 5,000", "5,001 - 10,000", "10,000 and more");
			
			for($index = 0; $index < count($orgSizeAry); $index++)
			{	
				printf("<option value=\"%s\" %s>%s</option>", $orgSizeAry[$index], ($selected==$orgSizeAry[$index])?"selected='selected'":"", $orgSizeAry[$index]);
			}
		}
		
		public function GetOrgInfo($org_id)
		{
			$query = sprintf("select * from organization where organization_id = '%s'",$org_id);
		  	$result = mysql_query($query, $this->db_link_id);//,$this->db_link_id) or die ("Error during getting org info :".mysql_error($this->db_link_id));
		  	$row = mysql_fetch_array($result,MYSQL_ASSOC);
		  	
		  	return $row;
		}
		
		public function GetOrgInfoFromLoginName($login_name)
		{
			$RetVal = -1;
			$query = sprintf("select * from organization join users on users.login_name='%s' and organization.organization_id=users.organization_id",$login_name);
			
		  	$result = mysql_query($query, $this->db_link_id);//,$this->db_link_id) or die ("Error during getting org info :".mysql_error($this->db_link_id));
		  	
		  	if(mysql_num_rows($result) > 0)
		  	{
		  		$RetVal = mysql_fetch_array($result,MYSQL_ASSOC);
		  	}
		  	
		  	return $RetVal;
		}
		
		public function GetUsersLoginCount()
		{
			$count = 0 ;
			
			$result = mysql_query("select count(*) as count from users where online='1';", $this->db_link_id) or die("Get Users Login Count: ".mysql_error($this->db_link_id)) ;
			while($row = mysql_fetch_array($result, MYSQL_ASSOC))
			{
				$count = $row["count"] ;
			}
			mysql_free_result($result) ;
			
			return $count ;
		}
		
		public function ActivateAccount($md5_email)
		{
			$ret_val = '' ;
			$query = sprintf("select %s from users where md5(email)='%s';", CUser::FIELD_USER_ID, $md5_email);
			
			$result = mysql_query($query);
			
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result, MYSQL_ASSOC);
				
				mysql_query("update users set reg_status = 1 where user_id='".$row["user_id"]."' ;");
				$ret_val = $row["user_id"];
			}
			else 
			{
				$ret_val = false ;
			}
			
			return $ret_val;
		}
		
		public function AddBusinessAssociate($ba_id,$pref_pmnt_mode,$payment_cycle,$acc_name,$acc_num,$acc_type,$bank_name,$bank_ifsc)
		{
			$query = sprintf("insert into business_associate(ba_id,pref_pmnt_mode,payment_cycle,beneficiary_account_name,beneficiary_account_number,beneficiary_account_type,beneficiary_bank_name,beneficiary_bank_ifsc_code) values('%s','%s','%s','%s','%s','%s','%s','%s')",$ba_id,$pref_pmnt_mode,$payment_cycle,$acc_name,$acc_num,$acc_type,$bank_name,$bank_ifsc);
		
			$result = mysql_query($query,$this->db_link_id) or die ("Error during set business associate :".mysql_error($this->db_link_id));
			
			return $result;
		}
		
		public function Update_GenInfo($user_id, CUser $objCUser)
		{
		   $updateresult = false;
		   $query = sprintf("update users set firstname = '%s', lastname ='%s', city = '%s', state = '%s',country = '%s' where user_id = '%s'",$objCUser->GetFirstName(),$objCUser->GetLastName(),$objCUser->GetCity(),$objCUser->GetState(),$objCUser->GetCountry(),$user_id);
		   $result = mysql_query($query,$this->db_link_id) or die ("Error during updation :".mysql_error($this->db_link_id));
		   if(mysql_affected_rows($this->db_link_id))
		   {
		  	 $updateresult  = true;
		   }
		   return $updateresult ;
		}
		
		public function  Update_SecInfo($user_id,$objCUser,$oldpw)
		{
		   $updateresult = 0;
           $sqlquery = sprintf("select security_ques, security_ans,passwd from users where user_id = '%s'", $user_id);
		   $result = mysql_query($sqlquery, $this->db_link_id) or die ("Error during updation :".mysql_error($this->db_link_id));
		   $row = mysql_fetch_array($result, MYSQL_ASSOC);
		   $secQuestion = $row["security_ques"];
		   $secAns = $row["security_ans"];		   
		   $password = $row["passwd"];		  
		   if(md5($oldpw)!= $password)
		   {
		   		$updateresult = 1;
				return $updateresult;
		   }
		   /*$sa = $objCUser->GetSecAns();
		   $sa = mb_convert_case($sa, MB_CASE_UPPER, "UTF-8");
		   $secAns = mb_convert_case($secAns, MB_CASE_UPPER, "UTF-8");		   
		   if($secAns != $sa)
		   {
		     return $updateresult;
		   }*/
 	
		   //$query = sprintf("update users set passwd = '%s' where security_ques = '%s' and security_ans = '%s'  and user_id = '%s' ",md5($objCUser->GetPassword()),$secQuestion,$secAns,$user_id);
		   $query = sprintf("update users set passwd = '%s' where user_id = '%s' ",md5($objCUser->GetPassword()),$user_id);		   
		   $result = mysql_query($query,$this->db_link_id) or die ("Error during updation :".mysql_error($this->db_link_id));
		   if(mysql_affected_rows($this->db_link_id))
		   {
		  	 $updateresult = 2;
		   }
		   return $updateresult ;
		}		
		
		public function GetCountryText($country_id)
		{
		  $ret_val = '' ;
		  $query = sprintf("select name from countries where code = '%s'",$country_id);
		  $result = mysql_query($query, $this->db_link_id);//,$this->db_link_id) or die ("Error during getting country name :".mysql_error($this->db_link_id));
		  $row = mysql_fetch_array($result,MYSQL_ASSOC);
		  $ret_val  =  $row["name"];
			return $ret_val; 
		}
		
		public function SetSecurityParam($user_id, $password, $sec_ques, $sec_ans, $owner_to_append)
		{
			$query = "";
			
			if(empty($owner_to_append))
			{
				$query = sprintf("update users set reg_status='1', passwd=MD5('%s'), security_ques='%s', security_ans='%s' where user_id='%s'",
								$password,
								$sec_ques,
								$sec_ans,
								$user_id);
			}
			else 
			{
				$query = sprintf("update users set reg_status='1', passwd=MD5('%s'), security_ques='%s', security_ans='%s', owner_id=concat(owner_id,'|','%s'), owners_in_waiting=replace(owners_in_waiting, '%s', '') where user_id='%s'",
								$password,
								$sec_ques,
								$sec_ans,
								$owner_to_append,
								$owner_to_append,
								$user_id);
			}
			
			//echo $query."<br/>";
			$bRet = mysql_query($query,$this->db_link_id) or die ("Error during updation :".mysql_error($this->db_link_id));
			
			return $bRet;
		}
		
		public function IsQualificationAlreadyExist($user_id, $edu_type)
		{
			$retAry = array();
			
			$query = sprintf("select * from user_cv where user_id='%s' and edu_type='%s'", $user_id, $edu_type);
			
			$result = mysql_query($query,$this->db_link_id) or die ("Is Qualification Already Exist :".mysql_error($this->db_link_id));
			
			if(mysql_num_rows($result) > 0)
			{
				$retAry = mysql_fetch_assoc($result);
			}
			return $retAry;
		}

		public function UpdateQualification($user_id, $edu_type, $area, $stream, $percent, $institute, $board, $passing_year)
		{
			$query = sprintf("update user_cv set area='%s', stream='%s', percent_cgpa='%s', school_institute='%s', board_university='%s', passing_year='%s' where user_id='%s' and edu_type='%s'", $area, $stream, $percent, $institute, $board, $passing_year, $user_id, $edu_type);
			
			$result = mysql_query($query,$this->db_link_id) or die ("Update Qualification :".mysql_error($this->db_link_id));
		}
		
		public function InsertIntoUserCV($user_id, $edu_type, $area, $stream, $percent, $institute, $board, $passing_year)
		{
			$query = sprintf("insert into user_cv values('%s','%s','%s','%s','%s','%s', '%s', '%s')", 
							$user_id, $edu_type, $area, $stream, $percent, $institute, $board, $passing_year);
			
			//echo $query."<br/>";
			$bRet = mysql_query($query,$this->db_link_id) or die ("Error during insertion :".mysql_error($this->db_link_id));
			
			return $bRet;
		}
		
		/*public function ApplyPlan($user_id, $plan, $bUpgrade=0)
		{
			$query = "";
			$planDes = array(1=>"corp:silver",
							 2=>"corp:gold",
							 3=>"corp:platinum",
							 4=>"inst:silver",
							 5=>"inst:gold",
							 6=>"inst:platinum",
							 7=>"indv:silver",
							 8=>"indv:gold",
							 9=>"indv:platinum");

			// If rate(-1) means Not applicable.
			$rate_mipcat = array(1=>100, 2=>80,	3=>60,
								4=>25, 5=>20, 6=>15,
								7=>-1, 8=>-1, 9=>-1);
			
			$rate_personal = array(1=>80, 2=>60, 3=>40,
								4=>20, 5=>15, 6=>10,
								7=>-1, 8=>-1, 9=>-1);
								
			$rate_search = array(1=>350, 2=>300, 3=>250,
								4=>-1, 5=>200, 6=>150,
								7=>-1, 8=>250, 9=>500);
								
			$amount = array(1=>10000, 2=>20000, 3=>30000,
							4=>5000, 5=>10000, 6=>15000,
							7=>0, 8=>250, 9=>500);

			$last_billed = date('Ymd H:i:s', time());
			if($bUpgrade == 0)
			{
				$query = sprintf("insert into billing(user_id, rate_mipcat_ques, rate_personal_ques, rate_cand_search, subscription_plan, balance, last_billed) values('%s','%s','%s','%s','%s','%s','%s')",
								$user_id, $rate_mipcat[$plan], $rate_personal[$plan], $rate_search[$plan],
								$planDes[$plan], $amount[$plan], $last_billed);
			}
			else 
			{
				$query = sprintf("update billing set rate_mipcat_ques='%s',rate_personal_ques='%s',rate_cand_search='%s',balance='%s',subscription_plan='%s',last_billed='%s',billing_history=concat(billing_history,'#',last_billed) where user_id='%s')",
								$rate_mipcat[$plan], $rate_personal[$plan], $rate_search[$plan],
								$amount[$plan], $planDes[$plan], $last_billed, "", $user_id);
			}
			
			//echo $query;
			$bRet = mysql_query($query,$this->db_link_id) or die ("Error during applying plan :".mysql_error($this->db_link_id));
			
			return $bRet;
		}*/
		
		public function UpdateUser($user_id, $user_data, $bMD5Pass = true)
		{
			$query = "update users set ";
			$count = count($user_data);
			
			foreach ($user_data as $key => $value)
			{
				if($count != 1)
				{
					if($key == CUser::FIELD_PASSWORD)
					{
						$query .= $key."=MD5('".$value."'), ";
					}
					else 
					{
						$query .= $key."='".$value."', ";
					}
				}
				else
				{
					if($key == CUser::FIELD_PASSWORD && $bMD5Pass)
					{
						$query .= $key."=MD5('".$value."') where user_id='".$user_id."'";
					}
					else if($key == CUser::FIELD_PASSWORD && !$bMD5Pass)
					{
						$query .= $key."=".$value." where user_id='".$user_id."'";
					}
					else 
					{
						$query .= $key."='".$value."' where user_id='".$user_id."'";
					}
				}
				$count--;
			}
			
			//echo $query;
			
			$bRet = mysql_query($query,$this->db_link_id) or die ("Error during update user :".mysql_error($this->db_link_id));
			
			return $bRet;
		}
		
		public function UpdateOrg($user_id, $org_id, $user_data, $login_name)
		{
			/*
				1). Search for org name in table, if exists update size.
				2). If not found insert org details into DB.
				3). After insertion update org id in user table.
			*/
			$bRet = false;
			$query = "select * from organization where organization_id='".$org_id."' AND organization_name='".$user_data[CUser::FIELD_ORGANIZATION_NAME]."'";
			
			$result = mysql_query($query,$this->db_link_id) or die ("Error getting org details :".mysql_error($this->db_link_id));
			if(mysql_num_rows($result) > 0)
			{
				$query = sprintf("update organization set organization_url='%s', organization_size='%s', logo_image='%s', logo_name='%s', punch_line='%s' where organization_id='%s'", $user_data[CUser::FIELD_ORGANIZATION_URL], $user_data[CUser::FIELD_ORGANIZATION_SIZE], base64_encode($user_data[CUser::FIELD_LOGO_IMAGE]), $user_data[CUser::FIELD_LOGO_NAME], $user_data[CUser::FIELD_PUNCH_LINE], $org_id);
					
				$bRet = mysql_query($query,$this->db_link_id) or die ("Error setting org details :".mysql_error($this->db_link_id));
			}
			else 
			{
				$new_org_id = CUtils::uuid() ;
				$query = sprintf("insert into organization (organization_id, organization_name, organization_url, organization_size, logo_image, logo_name, punch_line) values('%s','%s','%s','%s','%s','%s','%s')", $new_org_id, $user_data[CUser::FIELD_ORGANIZATION_NAME], $user_data[CUser::FIELD_ORGANIZATION_URL], $user_data[CUser::FIELD_ORGANIZATION_SIZE], base64_encode($user_data[CUser::FIELD_LOGO_IMAGE]), $user_data[CUser::FIELD_LOGO_NAME], $user_data[CUser::FIELD_PUNCH_LINE]);
				
				$bRet = mysql_query($query,$this->db_link_id) or die ("Error setting org details :".mysql_error($this->db_link_id));
				if($bRet)
				{
					$query = "update users set organization_id='".$new_org_id."' where user_id='".$user_id."'";
					$bRet = mysql_query($query,$this->db_link_id) or die ("Error setting org details :".mysql_error($this->db_link_id));
				}
			}
			
			$query = sprintf("update users set login_name='%s' where user_id='%s'", $login_name, $user_id);
			
			$bRet = mysql_query($query,$this->db_link_id) or die ("Error setting login name :".mysql_error($this->db_link_id));
			
			return $bRet;
		}
		
		public function GetBillingInfo($user_id)
		{
			$query = sprintf("select * from billing where user_id = '%s'",$user_id);
		  	$result = mysql_query($query, $this->db_link_id) or die ("Error during billing info :".mysql_error($this->db_link_id));
		  	$row = mysql_fetch_array($result, MYSQL_ASSOC);
		  	
		  	return $row;
		}
		
		public function PopulateQualification($user_id)
		{
			$query = sprintf("select * from user_cv where user_id = '%s'",$user_id);
		  	$result = mysql_query($query, $this->db_link_id) or die ("Error getting user cv :".mysql_error($this->db_link_id));
		  	
		  	$qualAry = array(1 => "Higher Secondary - 10th",
								 "Senior Secondary - 12th", 
								 "Diploma", 
								 "Graduation", 
								 "Post Graduation", 
								 "Doctor of Phylosophy (PhD)");
			$fields = array("qualification", "stream", "area", "percent", "institute", "board", "passing_year");
			
			$index = 0;
		  	while($row = mysql_fetch_array($result, MYSQL_ASSOC))
			{
				/*echo "<pre>";
				print_r ($row);
				echo "</pre>";*/
				
				$sPanel = "<h3><a href='#'>" . $qualAry[$row['edu_type']] . "<img width='16' height='16' src='../images/close.png' style='position:absolute;right:5px;' onClick='RemoveAcc(this);'/></a></h3>";
				$sPanel .= "<div>";
				$sPanel .= "<input type='hidden' name='qualification[".$index."]' value='".($row['edu_type'])."'/><br/><br/>";
				$sPanel .= "<lable>Stream:</lable>";
				$sPanel .= "<input style='position:absolute;right:5px;' readonly='readonly' size='50' type='text' name='stream[".$index."]' value='".$row['stream']."'/><br/><br/>";
				
				$sPanel .= "<lable>Area:</lable>";
				$sPanel .= "<input style='position:absolute;right:5px;' readonly='readonly' size='50' type='text' name='area[".$index."]' value='".$row['area']."'/><br/><br/>";
				
				$sPanel .= "<lable>Percent:</lable>";
				$sPanel .= "<input style='position:absolute;right:5px;' readonly='readonly' size='50' type='text' name='percent[".$index."]' value='".$row['percent_cgpa']."'/><br/><br/>";
				
				$sPanel .= "<lable>Institute:</lable>";
				$sPanel .= "<input style='position:absolute;right:5px;' readonly='readonly' size='50' type='text' name='institute[".$index."]' value='".$row['school_institute']."'/><br/><br/>";
				
				$sPanel .= "<lable>Board:</lable>";
				$sPanel .= "<input style='position:absolute;right:5px;' readonly='readonly' size='50' type='text' name='board[".$index."]' value='".$row['board_university']."'/><br/><br/>";
				
				$sPanel .= "<lable>Passing Year:</lable>";
				$sPanel .= "<input style='position:absolute;right:5px;' readonly='readonly' size='50' type='text' name='passing_year[".$index."]' value='".$row['passing_year']."'/><br/><br/>";
				
				$sPanel .= "</div>";
				
				echo $sPanel;
				$index++;
			}
			
			return $index;
		}
		
		public function PurgeUserCV($user_id)
		{
			$query = "delete from user_cv where user_id='".$user_id."'";
			
			$bRet = mysql_query($query, $this->db_link_id) or die ("Error Purging User CV :".mysql_error($this->db_link_id));
			
			return $bRet;
		}
		
		public function AddOwnerId($user_id,$owner_id)
        {
            $query = sprintf("update users set ".CUser::FIELD_OWNER_ID." ='%s' where ".CUser::FIELD_USER_ID."='%s'",$owner_id, $user_id);
            $result = mysql_query($query, $this->db_link_id) or die("Update OwnerId Error: ".mysql_error($this->db_link_id)) ;
           
            return $result;
        }
        
        public function AddCoordinator($coordinator_id,$department)
        {
            $query = sprintf("INSERT INTO coordinator(".CUser::FIELD_COORDINATOR_ID.",".CUser::FIELD_DEPARTMENT.")VALUES('%s','%s');",$coordinator_id,$department);
            
            $result = mysql_query($query, $this->db_link_id) or die("Add Coordinator Error: ".mysql_error($this->db_link_id)) ;
            
            return $result;
        }
    
        public function AddCoordinatorPermissions($permissions_array,$coordinator_id)
        {
            $value = 0;
       
            for($index = 0; $index < count($permissions_array); $index++)
            {
           
                $value = $value + $permissions_array[$index];
            }
            
            $query = sprintf("update users set ".CUser::FIELD_PERMISSIONS."='%s' where ".CUser::FIELD_USER_ID ."='%s';", $value,$coordinator_id);
            
            $result = mysql_query($query, $this->db_link_id) or die("Insert coordinator permissions Info Error: ".mysql_error($this->db_link_id)) ;
            
            return $result;
        }
        
        public function UpdateCoordinatorPermissions($permissions_array,$coordinator_id,$permitted_all)
        {
            $value = 0;
            $bResult = false ;
            
            if($permitted_all == true)
            {
                $value = 65535;
                $query = sprintf("update users set ".CUser::FIELD_PERMISSIONS."='%s' where ".CUser::FIELD_USER_ID ."='%s';", $value,$coordinator_id);
                $result = mysql_query($query, $this->db_link_id) or die("Update coordinator permissions Info Error: ".mysql_error($this->db_link_id)) ;
                   
            }
            else
            {
                for($index = 0; $index < count($permissions_array); $index++)
                {
                    $value = $value + $permissions_array[$index];
                }
                $query = sprintf("update users set ".CUser::FIELD_PERMISSIONS."='%s' where ".CUser::FIELD_USER_ID ."='%s';", $value,$coordinator_id);
                $result = mysql_query($query, $this->db_link_id) or die("Insert User Info Error: ".mysql_error($this->db_link_id));
            }
            
            if (mysql_affected_rows($this->db_link_id))
            {
                $bResult = true;
            }
               
            return $bResult;
           
        }
	}
?>