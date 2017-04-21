<?php 
	Class CUser
	{
		// Const Members.
		// -+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-
		// User Types
		// -+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-
		const USER_TYPE_SU 			= 0 ; // Super User
		const USER_TYPE_CORPORATE 	= 1 ; // Corporate
		const USER_TYPE_CANDIDATE	= 2 ; // Candidate
		
		// -+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-
		// Subscription plans
		// -+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-
		const PLAN_TYPE_CORP_SILVER		= 1 ; // Corporate Silver
		const PLAN_TYPE_CORP_GOLD		= 2 ; // Corporate Gold
		const PLAN_TYPE_CORP_PLATINUM	= 3 ; // Corporate Platinum
		const PLAN_TYPE_INST_SILVER		= 4 ; // Institute Silver
		const PLAN_TYPE_INST_GOLD		= 5 ; // Institute Gold
		const PLAN_TYPE_INST_PLATINUM	= 6 ; // Institute Platinum
		const PLAN_TYPE_INDV_SILVER		= 7 ; // Individual Silver
		const PLAN_TYPE_INDV_GOLD		= 8 ; // Individual Gold
		const PLAN_TYPE_INDV_PLATINUM	= 9 ; // Individual Platinum

		// -+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-
		// -+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-
		// User Table
		// -+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-
		const FIELD_USER_ID 		= "user_id" ;
		const FIELD_OWNER_ID 		= "owner_id" ;
		const FIELD_BATCH	 		= "batch" ;
		const FIELD_BUSS_ASSOC_ID	= "buss_assoc_id" ;
		const FIELD_USER_TYPE 		= "user_type" ;
		const FIELD_LOGIN_NAME 		= "login_name" ;
		const FIELD_ORGANIZATION_ID	= "organization_id" ;
		const FIELD_FIRST_NAME 		= "firstname" ;
		const FIELD_LAST_NAME	 	= "lastname" ;
		const FIELD_PASSWORD 		= "passwd" ;
		const FIELD_CONTACT_NO		= "contact_no" ;
		const FIELD_ADDRESS			= "address" ;
		const FIELD_PAN_NO			= "pan_no" ;
		const FIELD_EMAIL 			= "email" ;
		const FIELD_GENDER			= "gender" ;
		const FIELD_CITY			= "city" ;
		const FIELD_STATE			= "state" ;
		const FIELD_COUNTRY			= "country" ;
		const FIELD_DOB				= "dob" ;
		const FIELD_SECURITY_QUES 	= "security_ques" ;
		const FIELD_SECURITY_ANS	= "security_ans" ;
		const FIELD_SIGNUP_DATE		= "signup_date" ;
		const FIELD_REG_STATUS		= "reg_status" ;
		const FIELD_ONLINE			= "online" ;
		const FIELD_PERMISSIONS     = "permissions";
		// -+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-
		// Organization table holds supplymentary information about user's organization.
		// -+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-
		const FIELD_ORGANIZATION_NAME 		= "organization_name";
		const FIELD_ORGANIZATION_TYPE		= "organization_type";
		const FIELD_ORGANIZATION_SIZE 		= "organization_size";
		const FIELD_ORGANIZATION_URL		= "organization_url";
		const FIELD_LOGO_IMAGE				= "logo_image";
		const FIELD_LOGO_NAME				= "logo_name";
		const FIELD_PUNCH_LINE				= "punch_line";
		const FIELD_SHOWCAST_PUBLIC			= "showcast_public";
		// -+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-
		// Coordinator Table
        // -+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-
        const FIELD_COORDINATOR_ID        = "coordinator_id";
        const FIELD_DEPARTMENT            = "department";
        // -+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-
        
		// Class Members.
		private $user_id ;
		private $owner_id;
		private $batch;
		private $user_type;
		private $login_name;
		private $plan;
		private $buss_assoc_id;
		private $organization_id;
		private $first_name ;
  		private $last_name ;
  		private $password ;
  		private $contact_no ;
  		private $email ;
  		private $gender ;
  		private $pan_no ;
  		private $address ;
  		private $city ;
  		private $state ;
  		private $country ;
  		private $dob ;
  		private $security_question ;
  		private $security_answer ;
  		private $signup_date ;
  		private $reg_status ;
  		private $online ;

		private $organization_name;
		private $organization_size;
		private $org_url;
		private $logo_name;
		private $punch_line;
		private $showcast_public;
	    
		function __construct()
		{
			
		}
		public function __destruct()
		{
			
		}
		
	    public function GetUserID()
	    {
			return $this->user_id ;
	    }
	    public function SetUserID($user_id)
	    {
	    	$this->user_id = $user_id;
	    }
	    
	    public function GetOwnerID()
	    {
			return $this->owner_id ;
	    }
	    public function SetOwnerID($owner_id)
	    {
	    	$this->owner_id = $owner_id;
	    }
	    
	    public function GetBatch()
	    {
	    	return $this->batch ;
	    }
	    public function SetBatch($batch)
	    {
	    	$this->batch = $batch;
	    }
		
	    public function GetPlan()
	    {
			return $this->plan ;
	    }
	    public function SetPlan($plan)
	    {
	    	$this->user_id = $plan;
	    }
		
		public function GetBusinessAssociateId()
		{
			return $this->buss_assoc_id;
		}
		public function SetBusinessAssociateId($buss_assoc_id)
		{
			$this->buss_assoc_id = $buss_assoc_id;
		}
	    
		public function GetUserType()
	    {
			return $this->user_type ;
	    }
	    public function SetUserType($user_type)
	    {
	    	$this->user_type = $user_type;
	    }

		public function GetLoginName()
	    {
			return $this->login_name ;
	    }
	    public function SetLoginName($login_name)
	    {
	    	$this->login_name = $login_name;
	    }

		public function GetOrganizationId()
	    {
			return $this->organization_id ;
	    }
	    public function SetOrganizationId($organization_id)
	    {
	    	$this->organization_id = $organization_id;
	    }

	    public function GetFirstName()
	    {	    	
	    	return $this->first_name ;
	    }
		public function SetFirstName($first_name)
		{
			$this->first_name = $first_name ;
		}
		
		public function GetLastName()
	    {	    	
	    	return $this->last_name ;
	    }
		public function SetLastName($last_name)
		{
			$this->last_name = $last_name ;
		}
		
	    public function GetPassword()
	    {
	    	return $this->password ;
	    }
	    public function SetPassword($password)
	    {
	    	$this->password = $password ;
	    }
	    
	    public function GetContactNo()
	    {
	    	return $this->contact_no ;
	    }
	    public function SetContactNo($contact_no)
	    {
	    	$this->contact_no = $contact_no ;
	    }
	    
	    public function GetAddress()
	    {
	    	return $this->address;
	    }
	    public function SetAddress($address)
	    {
	    	$this->address = $address;
	    }
	    
	    public function GetPANNo()
	    {
	    	return $this->pan_no;
	    }
		public function SetPANNo($pan_no)
		{
			$this->pan_no = $pan_no;
		}
		
	    public function GetEmail()
	    {
	    	return $this->email ;
	    }
	    public function SetEmail($email)
	    {
	    	$this->email = $email ;
	    }
	    
	    public function GetGender()
	    {
	    	return $this->gender ;
	    }
	    public function SetGender($gender)
	    {
	    	$this->gender = $gender ;
	    }
	    
	    public function GetCity()
	    {
	    	return $this->city ;
	    }
	    public function SetCity($city)
	    {
			$this->city = $city ;
	    }
	    
  		public function GetState()
  		{
  			return $this->state ;
  		}
  		public function SetState($state)
  		{
  			$this->state = $state ;
  		}
  		
  		public function GetCountry()
  		{
  			return $this->country ;
  		}
  		public function SetCountry($country)
  		{
  			$this->country = $country ;
  		}
  		
  		public function GetDOB($parse=false)
  		{
  			if($parse == true)
  			{
  				return date_parse($this->dob);
  			}
  			return $this->dob ;
  		}
  		public function SetDOB($dob)
  		{
  			$this->dob = $dob ;
  		}
  		
  		public function GetSecQues()
  		{
  			return $this->security_question ;
  		}
  		public function SetSecQues($security_question)
  		{
			$this->security_question = $security_question ;
  		}
  		
  		public function GetSecAns()
  		{
  			return $this->security_answer ;
  		}
  		public function SetSecAns($security_answer)
  		{
			$this->security_answer = $security_answer ;
  		}
	    
	    public function GetSignupDate()
	    {
	    	return $this->signup_date ;
	    }
	    public function SetSignupDate($signup_date)
	    {
	    	$this->signup_date = $signup_date;
	    }
	    
	    public function GetRegStatus()
	    {
	    	return $this->reg_status ;
	    }
	    public function SetRegStatus($reg_status)
	    {
	    	$this->reg_status = $reg_status ;
	    }
	    
	    public function GetOnline()
	    {
	    	return $this->online ;
	    }
	    public function SetOnline($online)
	    {
	    	$this->online = $online ;
	    }

		public function GetOrganizationName()
	    {
	    	return $this->organization_name ;
	    }
	    public function SetOrganizationName($organization_name)
	    {
	    	$this->organization_name = $organization_name ;
	    }

		public function GetOrganizationSize()
	    {
	    	return $this->organization_size ;
	    }
	    public function SetOrganizationSize($organization_size)
	    {
	    	$this->organization_size = $organization_size ;
	    }
	    
		public function GetOrganizationURL()
		{
			return $this->org_url;
		}
		public function SetOrganizationURL($org_url)
		{
			$this->org_url = $org_url;
		}
		
		public function GetLogoName()
		{
			return $this->logo_name;
		}
		public function SetLogoName($logo_name)
		{
			$this->logo_name = $logo_name;
		}
		
		public function GetPunchLine()
		{
			return $this->punch_line;
		}
		public function SetPunchLine($punch_line)
		{
			$this->punch_line = $punch_line;
		}
		
		public function GetShowCastPublic()
		{
			return $this->showcast_public;
		}
		public function SetShowCastPublic($showcast_public)
		{
			$this->showcast_public = $showcast_public;
		}
	}
?>