<?php 
include_once("free_user.php") ;
include_once("site_config.php") ;
include_once(dirname(__FILE__)."/../database/config.php");

class CFreeUserManager
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
	
	public function AddFreeUser($objFreeUser)
	{
		$query = sprintf("insert into free_user(%s,%s,%s,%s) values('%s','%s','%s','%s')", CFreeUser::FIELD_EMAIL, CFreeUser::FIELD_PHONE, CFreeUser::FIELD_NAME, CFreeUser::FIELD_CITY, $objFreeUser->GetEmail(), $objFreeUser->GetPhone(), $objFreeUser->GetName(), $objFreeUser->GetCity());
		
		$result = mysql_query($query, $this->db_link_id) or die("Insert Free User Info Error: ".mysql_error($this->db_link_id)) ;
		
		return mysql_insert_id($this->db_link_id);
	}
	
	public function GetFreeUserByEmail($email)
	{
		$objFreeUser = new CFreeUser();
		
		$query = sprintf("select * from free_user where email='%s'", $email);
		
		$result = mysql_query($query, $this->db_link_id) or die("Get Free User By Email Error: ".mysql_error($this->db_link_id)) ;
		
		while($row = mysql_fetch_array($result, MYSQL_ASSOC))
		{
			$objFreeUser->SetFreeUserId($row[CFreeUser::FIELD_FREE_USER_ID]);
			$objFreeUser->SetEmail($row[CFreeUser::FIELD_EMAIL]);
			$objFreeUser->SetPhone($row[CFreeUser::FIELD_PHONE]);
			$objFreeUser->SetName($row[CFreeUser::FIELD_NAME]);
			$objFreeUser->SetCity($row[CFreeUser::FIELD_CITY]);
		}
		mysql_free_result($result) ;
		return $objFreeUser;
	}
	
	public function AddFreeUserTest($free_user_id, $test_id, $test_pnr, $org_id)
	{
		$query = sprintf("insert into free_user_test(free_user_id, test_id, test_pnr, organization_id) values('%s','%s','%s','%s')", $free_user_id, $test_id, $test_pnr, $org_id);
		
		$result = mysql_query($query, $this->db_link_id) or die("Add Free User Test Error: ".mysql_error($this->db_link_id)) ;
	}
	
	public function PopulateFreeTests($searchText, $searchCategory, $limit_start_value = 0)
	{
		$searchText = trim($searchText);
		$searchCategory = trim($searchCategory);
		$retArray = array();		
		
		$locateCond = "";
		
		if($searchCategory == "keywords")
		{
			$searchAry = explode(" ", $searchText);
			
			$locateCond = sprintf("and (");
			$i = 0;
			
			foreach($searchAry as $searchString)
			{
				if($i == 0)
				{
					$locateCond .= sprintf("locate('%s', test.keywords)", $searchString);
				}
				else 
				{
					$locateCond .= sprintf(" || locate('%s', test.keywords)", $searchString);
				}
				$i++;
			}
			$locateCond .= sprintf(")");
		}
		else if($searchCategory == "test_name")
		{
			$locateCond = sprintf("and locate('%s', test.test_name)", $searchText);
		}
		else if($searchCategory == "inst_name")
		{
			$locateCond = sprintf("and locate('%s', organization.organization_name)", $searchText);
		}
		
		$query = sprintf("SELECT DISTINCT test.test_name, test.test_id, test.keywords, test.description, test.final_rating, organization.organization_name, organization.organization_id FROM test join users on test.owner_id = users.user_id join organization on users.organization_id = organization.organization_id where test.is_published=1 %s order by test.final_rating desc limit %d, 10", $locateCond, $limit_start_value);
		
		$result = mysql_query($query, $this->db_link_id) or die("Populate Free Tests: ".mysql_error($this->db_link_id)) ;
		
		$rating_ary = array();
		while($row = mysql_fetch_array($result))
		{	
			$retArray[$row['test_id']]['test_name'] = $row['test_name'];
			$retArray[$row['test_id']]['description'] = $row['description'];
			$retArray[$row['test_id']]['keywords'] = $row['keywords'];
			$retArray[$row['test_id']]['org_name'] = $row['organization_name'];
			$retArray[$row['test_id']]['org_id'] = $row['organization_id'];
			$retArray[$row['test_id']]['rating'] = $row['final_rating'];
			$retArray[$row['test_id']]['test_id'] = $row['test_id'];
			$rating_ary[$row['test_id']] = $row['final_rating'];
		}
		if(!empty($retArray))
		{
			array_multisort($rating_ary, SORT_DESC, $retArray);
			$retArray['next_limit_start_value'] = $limit_start_value + 10;
		}
		return $retArray;
	}

	public function GetOrgIdByTestId($test_id)
	{
		$retVal = "";
		
		$query = sprintf("select users.organization_id from users join test on test.owner_id = users.user_id where test.test_id='%s'", $test_id);
		
		$result = mysql_query($query, $this->db_link_id) or die("Get Org Id By Test Id Error: ".mysql_error($this->db_link_id)) ;
		
		if(mysql_num_rows($result) > 0)
		{
			$row = mysql_fetch_array($result);
			$retVal = $row['organization_id'];
		}
		
		return $retVal;
	}
}
?>