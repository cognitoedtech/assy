<?php 
	 class CFreeUser
	 {
	 	// -+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-
	 	// -+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-
	 	// Free_User Table
	 	// -+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-
	 	
	 	const FIELD_FREE_USER_ID = "free_user_id";
	 	const FIELD_EMAIL		 = "email";
	 	const FIELD_PHONE		 = "phone";
	 	const FIELD_NAME		 = "name";
	 	const FIELD_CITY		 = "city";
	 	
	 	// Class Members.
	 	private $free_user_id;
	 	private $email;
	 	private $phone;
	 	private $name;
	 	private $city;
	 	
	 	function __construct()
	 	{
	 			
	 	}
	 	public function __destruct()
	 	{
	 			
	 	}
	 	
	 	public function GetFreeUserId()
	 	{
	 		return $this->free_user_id;
	 	}
	 	public function SetFreeUserId($free_user_id)
	 	{
	 		$this->free_user_id = $free_user_id;
	 	}
	 	
	 	public function GetEmail()
	 	{
	 		return $this->email ;
	 	}
	 	public function SetEmail($email)
	 	{
	 		$this->email = $email ;
	 	}
	 	
	 	public function GetPhone()
	 	{
	 		return $this->phone ;
	 	}
	 	public function SetPhone($phone)
	 	{
	 		$this->phone = $phone ;
	 	}
	 	
	 	public function GetName()
	 	{
	 		return $this->name ;
	 	}
	 	public function SetName($name)
	 	{
	 		$this->name = $name ;
	 	}
	 	
	 	public function GetCity()
	 	{
	 		return $this->city ;
	 	}
	 	public function SetCity($city)
	 	{
	 		$this->city = $city ;
	 	}
	 }
?>