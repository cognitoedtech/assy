<?php
	class CSiteConfig
	{
		/*----------------------------------------------*/
		// Host root path/url settings.
		/*----------------------------------------------*/
		//const ROOT_URL = "http://www.ezeeassess.com" ;
		//const ROOT_URL = "http://mcat.mastishka.com" ;
		//const ROOT_URL = "http://localhost/mipcat" ;
		//const ROOT_URL = "http://:5087" ;
		//const ROOT_URL = "http://".CSettings::IP.":5087";
		const ROOT_URL = "http://localhost/offline" ;
		const FREE_ROOT_URL = "http://free.localhost";
		
		const DEBUG_SITE		= false;
		/*----------------------------------------------*/
		// Header/Footer Link IDs.
		/*----------------------------------------------*/
		const HF_NONE			 =	 -1  ;
		const HF_HOME_ID		 =	 0  ;
		const HF_ABT_US_ID		 =	 1  ;
		const HF_LOGIN_ID		 =	 2  ;
		const HF_CONTACT_US_ID	 =	 3  ;
        const HF_DASHBOARD	 	 =	 4  ;
		const HF_TOS_ID			 =	 5  ;
		const HF_FAQ			 =	 6  ;
		const HF_GS_HELP		 =	 7  ;
		const HF_PLANS			 =	 8  ;
		const HF_REGISTER_ID	 =	 9  ;
		const HF_INDEX_ID		 =	 10  ;
		
		/*----------------------------------------------*/
		// User Account Main Menu 
		/*----------------------------------------------*/
		const UAMM_MANAGE_TEST 			= 0;
		const UAMM_MONITOR_ACTIVE_TESTS = 1;
		const UAMM_EXPORT_TEST_RESULT   = 2;
	}
?>