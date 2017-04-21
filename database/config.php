<?php
	class CConfig
	{
		const DB_MCAT 			= "mipcat";
		const DB_MCAT_ANALYTICS = "mgooscom_mcat_analytics";
		
		const HOST 				= "localhost";
		const USER_NAME	 		= "root";
		const PASSWORD	 		= ""; //"mipcat@racks123";
		
		//const PAYPAL_URL				= "https://www.sandbox.paypal.com/cgi-bin/webscr";
		const PAYPAL_URL				= "https://www.paypal.com/cgi-bin/webscr";
		
		
		// - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// Site Name Configuration
		// - - - - - - - - - - - - - - - - - - - - - - - - - - -
		const SNC_SITE_NAME     = "EZeeAssess";
		const SNC_PUNCH_LINE    = "Assessment at a whole new level !";
		// - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		// - - - - - - - - - - - - - - - - - - - - - - - - - - -
        // Captcha Keys
        // - - - - - - - - - - - - - - - - - - - - - - - - - - -
        const CK_PUBLIC     = "6LdituASAAAAAOPY0p-t5VWx3tlrjAX9iVgc9ebt";
        const CK_PRIVATE    = "6LdituASAAAAAKbawYTgLbHdtFtlPmi5kAgNOzF1";
        // - - - - - - - - - - - - - - - - - - - - - - - - - - -
        
        // - - - - - - - - - - - - - - - - - - - - - - - - - - -
        // Subscription Plan Types
        // - - - - - - - - - - - - - - - - - - - - - - - - - - -
        const SPT_BASIC     		= 0;
        const SPT_PROFESSIONAL		= 1;
        const SPT_ENTERPRISE 		= 2;
        const SPT_PPU				= 3; // Pay Per Use (Pay as you go!)
        // - - - - - - - - - - - - - - - - - - - - - - - - - - -
        
        // - - - - - - - - - - - - - - - - - - - - - - - - - - -
        // Subscription Plan Rates (Per Tests)
        // - - - - - - - - - - - - - - - - - - - - - - - - - - -
        const SPR_MINIMUM_TESTS		= 500;
        const SPR_MINIMUM_PPU_TESTS	= 50;
        
        const SPR_BASIC     		= 0.16; // In USD
        const SPR_PROFESSIONAL		= 0.26; // In USD
        const SPR_ENTERPRISE 		= 3.0;  // In USD
        const SPR_PPU		 		= 0.35;  // In USD
        
		const SPR_BASIC_INR			= 10;	// In INR
		const SPR_PROFESSIONAL_INR	= 16;	// In INR
		const SPR_ENTERPRISE_INR	= 200;	// In INR
		const SPR_PPU_INR			= 22;	// In INR
        // - - - - - - - - - - - - - - - - - - - - - - - - - - -
        
        // - - - - - - - - - - - - - - - - - - - - - - - - - - -
        // Application Usage Types
        // - - - - - - - - - - - - - - - - - - - - - - - - - - -
        const AUT_PER_TEST     		= 0;
        const AUT_PER_MONTH			= 1;
        // - - - - - - - - - - - - - - - - - - - - - - - - - - -
        
        // - - - - - - - - - - - - - - - - - - - - - - - - - - -
        // Bill Payment Types
        // - - - - - - - - - - - - - - - - - - - - - - - - - - -
        const BPT_PREPAID     		= 0;
        const BPT_POSTPAID			= 1;
        // - - - - - - - - - - - - - - - - - - - - - - - - - - -
        
		// - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// Free EZeeAsses User Configuration
		// - - - - - - - - - - - - - - - - - - - - - - - - - - -
		const FEUC_NAME     		= "FreeUserId";
		const FEUC_TEST_OWNER		= "shankirocks1612@gmail.com";
		const FEUC_TEST_SCHEDULE_ID = -101;
		const FEUC_CAPTCHA = "FreeCaptcha";
		// - - - - - - - - - - - - - - - - - - - - - - - - - - -
        
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// Official Email-IDs
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		const OEI_FINANCE		= "finance@ezeeassess.com";
		const OEI_SALES			= "sales@ezeeassess.com";
		const OEI_SUPPORT		= "support@ezeeassess.com";
		const OEI_BUSI_ASSOC	= "business_associate@ezeeassess.com";
		const OEI_ABUSE			= "abuse@ezeeassess.com";
		const OEI_FREE			= "free@ezeeassess.com";
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// Mail Configurations
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		const MC_MTA_ONE	= "smtp.gmail.com";
		const MC_MTA_TWO	= "mail.ezeeasses.com";
		const MC_PORT		= 465;
		const MC_ENC_METHOD = "tls";
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		// - - - - - - - - - - - - - - - - - -
		// User Types
		// - - - - - - - - - - - - - - - - - -
		const UT_SUPER_ADMIN		= 0;
		const UT_INSTITUTE			= 2;
		const UT_CORPORATE			= 2;
		const UT_INDIVIDAL			= 3;
		const UT_CONTRIBUTOR		= 4;
		const UT_VERIFIER			= 5;
		const UT_BUSINESS_ASSOCIATE	= 6;
		const UT_COORDINATOR        = 7;
		// - - - - - - - - - - - - - - - - - -
		
		// - - - - - - - - - - - - - - - - - -
		// Organization Types
		// - - - - - - - - - - - - - - - - - -
		const OT_SOFTWARE_COMPANY				= 0;
		const OT_COACHING_INSTITUTE				= 1;
		const OT_EXAM_CONDUCTION_AUTHORITY 		= 2;
		const OT_BOARD_UNIVERSITY 				= 3;
		const OT_EDUCATIONAL_INSTITUTE 			= 4;
		const OT_RECRUITMENT_CONSULTANCY_FIRM 	= 5;
		const OT_MANUFACTURING_INDUSTRY 		= 6;
		const OT_OTHER	 						= 7;
		
		static $ORG_TYPE_ARY = array(self::OT_SOFTWARE_COMPANY=>"Software Industry",
									self::OT_COACHING_INSTITUTE=>"Coaching Institute",
									self::OT_EXAM_CONDUCTION_AUTHORITY=>"Examination Conduction Authority",
									self::OT_BOARD_UNIVERSITY=>"Board/University",
									self::OT_EDUCATIONAL_INSTITUTE=>"Educational Institute",
									self::OT_RECRUITMENT_CONSULTANCY_FIRM=>"Recruitment Consultancy Firm",
									self::OT_MANUFACTURING_INDUSTRY=>"Manufacturing Industry",
									self::OT_OTHER=>"Other");
		
		
		// - - - - - - - - - - - - - - - - - -
        // Test Nature
        // - - - - - - - - - - - - - - - - - -
        const TEST_NATURE_DYNAMIC	= 0;
		const TEST_NATURE_STATIC	= 1;
        // - - - - - - - - - - - - - - - - - -
        
		// - - - - - - - - - - - - - - - - - -
		// Test Type
		// - - - - - - - - - - - - - - - - - -
		const TT_DEFAULT		= 0;
		const TT_EQ				= 1;
		// - - - - - - - - - - - - - - - - - -
        
		// - - - - - - - - - - - - - - - - - -
		// Test Schedule Type
		// - - - - - - - - - - - - - - - - - -
		const TST_ONLINE		= 0;
		const TST_OFFLINE		= 1;
		const TST_OTFA_EMAIL 	= 2;
		const TST_OTFA_TPIN		= 3;
		// - - - - - - - - - - - - - - - - - -
        
		// - - - - - - - - - - - - - - - - - -
        // Question Types
        // - - - - - - - - - - - - - - - - - -
        const QT_NORMAL				= 0;
		const QT_READ_COMP			= 1;
        const QT_DIRECTIONS			= 2;
        // - - - - - - - - - - - - - - - - - -
		
        // - - - - - - - - - - - - - - - - - -
        // Question Category
        // - - - - - - - - - - - - - - - - - -
        const QUES_CTG_SCA			= 0;
		const QUES_CTG_MCA			= 1;
        //const QUES_CTG_HYBRID		= "hybrid";
        // - - - - - - - - - - - - - - - - - -
        
		// - - - - - - - - - - - - - - - - - -
		// Passing Criteria
		// - - - - - - - - - - - - - - - - - -
		const PC_CUTOFF				= 0;
		const PC_TOP_CAND			= 1;
		// - - - - - - - - - - - - - - - - - -
		
		// - - - - - - - - - - - - - - - - - -
		// Result State
		// - - - - - - - - - - - - - - - - - -
		const RS_FAIL				= 0;
		const RS_PASS				= 1;
		// - - - - - - - - - - - - - - - - - -
		
		// - - - - - - - - - - - - - - - - - -
		// Organization Logo
		// - - - - - - - - - - - - - - - - - -
		const OL_WIDTH				= 200;
		const OL_HEIGHT				= 40;
		// - - - - - - - - - - - - - - - - - -
		
		// - - - - - - - - - - - - - - - - - -
		// Forcefully Kill Test
		// - - - - - - - - - - - - - - - - - -
		const FOKI_NOTEXISTS	= -1;
		const FOKI_NO			= 0;
		const FOKI_YES			= 1;
		// - - - - - - - - - - - - - - - - - -
		
		// - - - - - - - - - - - - - - - - - -
		// Billing Currency
		// - - - - - - - - - - - - - - - - - -
		const CURRENCY_INR	= "INR";
		const CURRENCY_USD	= "USD";
		// - - - - - - - - - - - - - - - - - -
		
		// - - - - - - - - - - - - - - - - - -
		// Package Result Views
		// - - - - - - - - - - - - - - - - - -
		const PRV_DETAILED	= 1;
		const PRV_HOLISTIC	= 2;
		const PRV_IQ		= 4;
		const PRV_EQ		= 8;
		// - - - - - - - - - - - - - - - - - -
		
		// - - - - - - - - - - - - - - - - - -
		// Holistic Chart Legend
		// - - - - - - - - - - - - - - - - - -
		static $HOLISTIC_CHART_LEGEND_ARY = array("Less than 20" => "Very Poor",
												"21 - 40"=>"Poor",
												"41 - 60"=>"Average",
												"61 - 70"=>"Above Average",
												"71 - 80"=>"Good",
												"81 - 100"=>"Excellent");
		// - - - - - - - - - - - - - - - - - -
		
		// - - - - - - - - - - - - - - - - - -
		// IQ Chart Legend
		// - - - - - - - - - - - - - - - - - -
		static $IQ_CHART_LEGEND_ARY = array("69 and below" => "Extremely Low",
											"70 - 79"=>"Borderline",
											"80 - 89"=>"Below Average",
											"90 - 109"=>"Average",
											"110 - 119"=>"Above Average",
											"120 - 129"=>"Superior",
											"130 and above"=>"Very Superior");
		// - - - - - - - - - - - - - - - - - -
		
		// - - - - - - - - - - - - - - - - - -
		// Payment Modes
		// - - - - - - - - - - - - - - - - - -
		const PAYMENT_MODE_RESET_ACCOUNT	= -2;
		const PAYMENT_MODE_FREE				= -1;
		const PAYMENT_MODE_CHEQUE			= 0;
		const PAYMENT_MODE_DD				= 1;
		const PAYMENT_MODE_NEFT				= 2;
		const PAYMENT_MODE_NET_BANKING		= 3;
		const PAYMENT_MODE_GATEWAY			= 4;
		
		static $PAYMENT_MODE_TEXT_ARY = array(self::PAYMENT_MODE_RESET_ACCOUNT => "Monthly Account Reset",
											self::PAYMENT_MODE_FREE => "Free",
											self::PAYMENT_MODE_CHEQUE => "Cheque",
											self::PAYMENT_MODE_DD => "Demand Draft",
											self::PAYMENT_MODE_NEFT => "National Electronic Funds Transfer",
											self::PAYMENT_MODE_NET_BANKING => "Online &frasl; Internet Banking",
											self::PAYMENT_MODE_GATEWAY => "Online Payment Gateway");
		// - - - - - - - - - - - - - - - - - -
		
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// Tax Applied on Business Associate (In Percent)
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		static $BA_TAX_APPLIED_ARY = array("Service Tax" => 15.36,
										   "Tax Deduction at Source (TDS)" => 10);
										   
		const TDS_MIN_BRACKET = 20000;
		
		// BA Commission in Percent
		const BA_COMISSION_FIRST_RECHARGE 		= 20; 
		const BA_COMISSION_RECURRING_RECHARGE 	= 10; 
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		// - - - - - - - - - - - - - - - - - -
		// Encash Points Range
		// - - - - - - - - - - - - - - - - - -
		const MIN_ENCASH_PNTS		= 5000;
		const MAX_ENCASH_PNTS		= 25000;
		// - - - - - - - - - - - - - - - - - -

		// - - - - - - - - - - - - - - - - - -
		// Coordinator Transaction Type
		// - - - - - - - - - - - - - - - - - -
		const CTT_RECHARGE		= 0;
		const CTT_RECLAIM		= 1;
		// - - - - - - - - - - - - - - - - - -
		
		// - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// Excel Upload : Column Count & Other Settings.
		// - - - - - - - - - - - - - - - - - - - - - - - - - - -
		const EU_CAND_SHEET_COL_CNT 		=9;
		const EU_QUES_SHEET_COL_CNT 		= 11;
		const EU_EQ_RANGE_ANALYSIS_COL_CNT 	= 5;
		// - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		// - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// Candidate Default Batch
		// - - - - - - - - - - - - - - - - - - - - - - - - - - -
		const CDB_ID	 		= -1;
		const CDB_NAME	 		= "Default Batch";
		const CDB_DESCRIPTION	= "This is the default batch of the candidate when he does not belong to any batch.";
		// - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		// - - - - - - - - - - - - - - - - - - - - - - - - - - -
        // Language Supported for Test
        // - - - - - - - - - - - - - - - - - - - - - - - - - - -
        static $TEST_LANGUAGES = array( "akan",
										"amharic",
										"arabic",
										"assamese",
										"awadhi",
										"azerbaijani",
										"balochi",
										"belarusian",
										"bengali",
										"bhojpuri",
										"burmese",
										"cantonese",
										"cebuano",
										"chewa",
										"chhattisgarhi",
										"chittagonian",
										"czech",
										"deccan",
										"dhundhari",
										"dutch",
										"english",
										"french",
										"fula",
										"gan chinese",
										"german",
										"greek",
										"gujarati",
										"haitian creole",
										"hakka",
										"haryanvi",
										"hausa",
										"hiligaynon",
										"hindi",
										"hmong",
										"hungarian",
										"igbo",
										"ilokano",
										"indonesian",
										"italian",
										"japanese",
										"javanese",
										"jin",
										"kannada",
										"kazakh",
										"khmer",
										"kinyarwanda",
										"kirundi",
										"konkani",
										"korean",
										"kurdish",
										"madurese",
										"magahi",
										"maithili",
										"malagasy",
										"malay",
										"malayalam",
										"mandarin",
										"marathi",
										"marwari",
										"min bei",
										"min dong",
										"min nan",
										"mossi",
										"nepali",
										"oriya",
										"oromo",
										"pashto",
										"persian",
										"polish",
										"portuguese",
										"punjabi",
										"quechua",
										"romanian",
										"russian",
										"saraiki",
										"serbo-croatian",
										"shona",
										"sindhi",
										"sinhalese",
										"somali",
										"spanish",
										"sundanese",
										"swedish",
										"sylheti",
										"tagalog",
										"tamil",
										"telugu",
										"thai",
										"turkish",
										"ukrainian",
										"urdu",
										"uyghur",
										"uzbek",
										"vietnamese",
										"wu",
										"xhosa",
										"xiang",
										"yoruba",
										"zhuang",
										"zulu",
										);
        // - - - - - - - - - - - - - - - - - - - - - - - - - - -

		// - - - - - - - - - - - - - - - - - - - - - - - - - - -
        // Time Zone Supported
        // - - - - - - - - - - - - - - - - - - - - - - - - - - -
        static $TIME_ZONE_ARRAY = array (
										  '(GMT-12:00) International Date Line West' => 'Pacific/Wake',
										  '(GMT-11:00) Midway Island' => 'Pacific/Apia',
										  '(GMT-11:00) Samoa' => 'Pacific/Apia',
										  '(GMT-10:00) Hawaii' => 'Pacific/Honolulu',
										  '(GMT-09:00) Alaska' => 'America/Anchorage',
										  '(GMT-08:00) Pacific Time (US &amp; Canada); Tijuana' => 'America/Los_Angeles',
										  '(GMT-07:00) Arizona' => 'America/Phoenix',
										  '(GMT-07:00) Chihuahua' => 'America/Chihuahua',
										  '(GMT-07:00) La Paz' => 'America/Chihuahua',
										  '(GMT-07:00) Mazatlan' => 'America/Chihuahua',
										  '(GMT-07:00) Mountain Time (US &amp; Canada)' => 'America/Denver',
										  '(GMT-06:00) Central America' => 'America/Managua',
										  '(GMT-06:00) Central Time (US &amp; Canada)' => 'America/Chicago',
										  '(GMT-06:00) Guadalajara' => 'America/Mexico_City',
										  '(GMT-06:00) Mexico City' => 'America/Mexico_City',
										  '(GMT-06:00) Monterrey' => 'America/Mexico_City',
										  '(GMT-06:00) Saskatchewan' => 'America/Regina',
										  '(GMT-05:00) Bogota' => 'America/Bogota',
										  '(GMT-05:00) Eastern Time (US &amp; Canada)' => 'America/New_York',
										  '(GMT-05:00) Indiana (East)' => 'America/Indiana/Indianapolis',
										  '(GMT-05:00) Lima' => 'America/Bogota',
										  '(GMT-05:00) Quito' => 'America/Bogota',
										  '(GMT-04:00) Atlantic Time (Canada)' => 'America/Halifax',
										  '(GMT-04:00) Caracas' => 'America/Caracas',
										  '(GMT-04:00) La Paz' => 'America/Caracas',
										  '(GMT-04:00) Santiago' => 'America/Santiago',
										  '(GMT-03:30) Newfoundland' => 'America/St_Johns',
										  '(GMT-03:00) Brasilia' => 'America/Sao_Paulo',
										  '(GMT-03:00) Buenos Aires' => 'America/Argentina/Buenos_Aires',
										  '(GMT-03:00) Georgetown' => 'America/Argentina/Buenos_Aires',
										  '(GMT-03:00) Greenland' => 'America/Godthab',
										  '(GMT-02:00) Mid-Atlantic' => 'America/Noronha',
										  '(GMT-01:00) Azores' => 'Atlantic/Azores',
										  '(GMT-01:00) Cape Verde Is.' => 'Atlantic/Cape_Verde',
										  '(GMT) Casablanca' => 'Africa/Casablanca',
										  '(GMT) Edinburgh' => 'Europe/London',
										  '(GMT) Greenwich Mean Time : Dublin' => 'Europe/London',
										  '(GMT) Lisbon' => 'Europe/London',
										  '(GMT) London' => 'Europe/London',
										  '(GMT) Monrovia' => 'Africa/Casablanca',
										  '(GMT+01:00) Amsterdam' => 'Europe/Berlin',
										  '(GMT+01:00) Belgrade' => 'Europe/Belgrade',
										  '(GMT+01:00) Berlin' => 'Europe/Berlin',
										  '(GMT+01:00) Bern' => 'Europe/Berlin',
										  '(GMT+01:00) Bratislava' => 'Europe/Belgrade',
										  '(GMT+01:00) Brussels' => 'Europe/Paris',
										  '(GMT+01:00) Budapest' => 'Europe/Belgrade',
										  '(GMT+01:00) Copenhagen' => 'Europe/Paris',
										  '(GMT+01:00) Ljubljana' => 'Europe/Belgrade',
										  '(GMT+01:00) Madrid' => 'Europe/Paris',
										  '(GMT+01:00) Paris' => 'Europe/Paris',
										  '(GMT+01:00) Prague' => 'Europe/Belgrade',
										  '(GMT+01:00) Rome' => 'Europe/Berlin',
										  '(GMT+01:00) Sarajevo' => 'Europe/Sarajevo',
										  '(GMT+01:00) Skopje' => 'Europe/Sarajevo',
										  '(GMT+01:00) Stockholm' => 'Europe/Berlin',
										  '(GMT+01:00) Vienna' => 'Europe/Berlin',
										  '(GMT+01:00) Warsaw' => 'Europe/Sarajevo',
										  '(GMT+01:00) West Central Africa' => 'Africa/Lagos',
										  '(GMT+01:00) Zagreb' => 'Europe/Sarajevo',
										  '(GMT+02:00) Athens' => 'Europe/Istanbul',
										  '(GMT+02:00) Bucharest' => 'Europe/Bucharest',
										  '(GMT+02:00) Cairo' => 'Africa/Cairo',
										  '(GMT+02:00) Harare' => 'Africa/Johannesburg',
										  '(GMT+02:00) Helsinki' => 'Europe/Helsinki',
										  '(GMT+02:00) Istanbul' => 'Europe/Istanbul',
										  '(GMT+02:00) Jerusalem' => 'Asia/Jerusalem',
										  '(GMT+02:00) Kyiv' => 'Europe/Helsinki',
										  '(GMT+02:00) Minsk' => 'Europe/Istanbul',
										  '(GMT+02:00) Pretoria' => 'Africa/Johannesburg',
										  '(GMT+02:00) Riga' => 'Europe/Helsinki',
										  '(GMT+02:00) Sofia' => 'Europe/Helsinki',
										  '(GMT+02:00) Tallinn' => 'Europe/Helsinki',
										  '(GMT+02:00) Vilnius' => 'Europe/Helsinki',
										  '(GMT+03:00) Baghdad' => 'Asia/Baghdad',
										  '(GMT+03:00) Kuwait' => 'Asia/Riyadh',
										  '(GMT+03:00) Moscow' => 'Europe/Moscow',
										  '(GMT+03:00) Nairobi' => 'Africa/Nairobi',
										  '(GMT+03:00) Riyadh' => 'Asia/Riyadh',
										  '(GMT+03:00) St. Petersburg' => 'Europe/Moscow',
										  '(GMT+03:00) Volgograd' => 'Europe/Moscow',
										  '(GMT+03:30) Tehran' => 'Asia/Tehran',
										  '(GMT+04:00) Abu Dhabi' => 'Asia/Muscat',
										  '(GMT+04:00) Baku' => 'Asia/Tbilisi',
										  '(GMT+04:00) Muscat' => 'Asia/Muscat',
										  '(GMT+04:00) Tbilisi' => 'Asia/Tbilisi',
										  '(GMT+04:00) Yerevan' => 'Asia/Tbilisi',
										  '(GMT+04:30) Kabul' => 'Asia/Kabul',
										  '(GMT+05:00) Ekaterinburg' => 'Asia/Yekaterinburg',
										  '(GMT+05:00) Islamabad' => 'Asia/Karachi',
										  '(GMT+05:00) Karachi' => 'Asia/Karachi',
										  '(GMT+05:00) Tashkent' => 'Asia/Karachi',
										  '(GMT+05:30) Chennai' => 'Asia/Calcutta',
										  '(GMT+05:30) Kolkata' => 'Asia/Calcutta',
										  '(GMT+05:30) Mumbai' => 'Asia/Calcutta',
										  '(GMT+05:30) New Delhi' => 'Asia/Calcutta',
										  '(GMT+05:45) Kathmandu' => 'Asia/Katmandu',
										  '(GMT+06:00) Almaty' => 'Asia/Novosibirsk',
										  '(GMT+06:00) Astana' => 'Asia/Dhaka',
										  '(GMT+06:00) Dhaka' => 'Asia/Dhaka',
										  '(GMT+06:00) Novosibirsk' => 'Asia/Novosibirsk',
										  '(GMT+06:00) Sri Jayawardenepura' => 'Asia/Colombo',
										  '(GMT+06:30) Rangoon' => 'Asia/Rangoon',
										  '(GMT+07:00) Bangkok' => 'Asia/Bangkok',
										  '(GMT+07:00) Hanoi' => 'Asia/Bangkok',
										  '(GMT+07:00) Jakarta' => 'Asia/Bangkok',
										  '(GMT+07:00) Krasnoyarsk' => 'Asia/Krasnoyarsk',
										  '(GMT+08:00) Beijing' => 'Asia/Hong_Kong',
										  '(GMT+08:00) Chongqing' => 'Asia/Hong_Kong',
										  '(GMT+08:00) Hong Kong' => 'Asia/Hong_Kong',
										  '(GMT+08:00) Irkutsk' => 'Asia/Irkutsk',
										  '(GMT+08:00) Kuala Lumpur' => 'Asia/Singapore',
										  '(GMT+08:00) Perth' => 'Australia/Perth',
										  '(GMT+08:00) Singapore' => 'Asia/Singapore',
										  '(GMT+08:00) Taipei' => 'Asia/Taipei',
										  '(GMT+08:00) Ulaan Bataar' => 'Asia/Irkutsk',
										  '(GMT+08:00) Urumqi' => 'Asia/Hong_Kong',
										  '(GMT+09:00) Osaka' => 'Asia/Tokyo',
										  '(GMT+09:00) Sapporo' => 'Asia/Tokyo',
										  '(GMT+09:00) Seoul' => 'Asia/Seoul',
										  '(GMT+09:00) Tokyo' => 'Asia/Tokyo',
										  '(GMT+09:00) Yakutsk' => 'Asia/Yakutsk',
										  '(GMT+09:30) Adelaide' => 'Australia/Adelaide',
										  '(GMT+09:30) Darwin' => 'Australia/Darwin',
										  '(GMT+10:00) Brisbane' => 'Australia/Brisbane',
										  '(GMT+10:00) Canberra' => 'Australia/Sydney',
										  '(GMT+10:00) Guam' => 'Pacific/Guam',
										  '(GMT+10:00) Hobart' => 'Australia/Hobart',
										  '(GMT+10:00) Melbourne' => 'Australia/Sydney',
										  '(GMT+10:00) Port Moresby' => 'Pacific/Guam',
										  '(GMT+10:00) Sydney' => 'Australia/Sydney',
										  '(GMT+10:00) Vladivostok' => 'Asia/Vladivostok',
										  '(GMT+11:00) Magadan' => 'Asia/Magadan',
										  '(GMT+11:00) New Caledonia' => 'Asia/Magadan',
										  '(GMT+11:00) Solomon Is.' => 'Asia/Magadan',
										  '(GMT+12:00) Auckland' => 'Pacific/Auckland',
										  '(GMT+12:00) Fiji' => 'Pacific/Fiji',
										  '(GMT+12:00) Kamchatka' => 'Pacific/Fiji',
										  '(GMT+12:00) Marshall Is.' => 'Pacific/Fiji',
										  '(GMT+12:00) Wellington' => 'Pacific/Auckland',
										  '(GMT+13:00) Nuku\'alofa' => 'Pacific/Tongatapu',
										);
        // - - - - - - - - - - - - - - - - - - - - - - - - - - -
        
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        // Question Excel Upload : Column Count & Other Settings.
        // - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        const    DIFF_LVL_EASY        = 1;
        const    DIFF_LVL_MODERATE    = 2;
        const    DIFF_LVL_HARD        = 3;
        
        const OPER_XLS_COPY  	= "#@MIPCAT_COPY";
        const OPER_XLS_NA     	= "#@MIPCAT_NA";
        const OPER_XLS_EMPTY 	= "#@MIPCAT_EMPTY";
        const OPER_CODE_START 	= "#@MIPCAT_CODE_START";
        const OPER_CODE_END 	= "#@MIPCAT_CODE_END";
        
        const EA_OPER_XLS_COPY  	= "#@EZEEASSES_COPY";
        const EA_OPER_XLS_NA     	= "#@EZEEASSES_NA";
        const EA_OPER_XLS_EMPTY 	= "#@EZEEASSES_EMPTY";
        const EA_OPER_CODE_START 	= "#@EZEEASSES_CODE_START";
        const EA_OPER_CODE_END 		= "#@EZEEASSES_CODE_END";
       
        static $QUES_XLS_HEADING_ARY  = array("S No"=>'A',
        									  "Para Description"=>'B', 
        									  "Language"=>'C', 
        									  "Question"=>'D',
        									  "Answer"=>'E', 
        									  "Subject"=>'F', 
        									  "Topic"=>'G', 
        									  "Difficulty"=>'H', 
        									  "Explanation"=>'I', 
        									  "Option 1"=>'J', 
        									  "Option 2"=>'K');
        
        static $EQ_RANGE_ANALYSIS_HEADING_ARY = array("S No"=>'A',
        									  "Topic Name"=>'B', 
        									  "Lower Limit"=>'C', 
        									  "Higher Limit"=>'D',
        									  "Analysis"=>'E', 
        									  "Summary"=>'F');
        // - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        
		// - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// Rates : Test Availability for 15 or more days.
		// - - - - - - - - - - - - - - - - - - - - - - - - - - -
		const INR_RATE_15_DAYS		= 550;
		const INR_RATE_30_DAYS		= 1000;
		const INR_RATE_45_DAYS		= 1400;
		const INR_RATE_60_DAYS		= 1750;
		const INR_RATE_75_DAYS		= 0; // Not Applicable
		const INR_RATE_90_DAYS		= 2400;
		static $INR_PKG_RATE_ARY = array(self::INR_RATE_15_DAYS,
										 self::INR_RATE_30_DAYS,
										 self::INR_RATE_45_DAYS,
										 self::INR_RATE_60_DAYS,
										 self::INR_RATE_75_DAYS,
										 self::INR_RATE_90_DAYS);
		
		const USD_RATE_15_DAYS		= 15;
		const USD_RATE_30_DAYS		= 28;
		const USD_RATE_45_DAYS		= 40;
		const USD_RATE_60_DAYS		= 50;
		const USD_RATE_75_DAYS		= 0; // Not Applicable
		const USD_RATE_90_DAYS		= 70;
		static $USD_PKG_RATE_ARY = array(self::USD_RATE_15_DAYS,
										 self::USD_RATE_30_DAYS,
										 self::USD_RATE_45_DAYS,
										 self::USD_RATE_60_DAYS,
										 self::USD_RATE_75_DAYS,
										 self::USD_RATE_90_DAYS);
										 
		static $INR_SUBSCRIPTION_PLANS = array( self::UT_SUPER_ADMIN => array("MINIMUM_RECHARGE" => 19600,
																 "FREE_RECHARGE" => 2000,
																 "RATE_PERSONAL_QUESTION" => 100,
																 "RATE_MIPCAT_QUESTION" => 125
																),
												self::UT_INSTITUTE => array("MINIMUM_RECHARGE" => 1000,
																 "FREE_RECHARGE" => 1000,
																 "RATE_PERSONAL_QUESTION" => 20,
																 "RATE_MIPCAT_QUESTION" => 25
																),
									 			self::UT_CORPORATE => array("MINIMUM_RECHARGE" => 1000,
									 							 "FREE_RECHARGE" => 2000,
																 "RATE_PERSONAL_QUESTION" => 100,
																 "RATE_MIPCAT_QUESTION" => 125
																),
												self::UT_INDIVIDAL => array("MINIMUM_RECHARGE" => -1,
																 "FREE_RECHARGE" => -1,
																 "RATE_PERSONAL_QUESTION" => -1,
																 "RATE_MIPCAT_QUESTION" => -1
																)
								   			);
								   			
		static $USD_SUBSCRIPTION_PLANS = array( self::UT_SUPER_ADMIN => array("MINIMUM_RECHARGE" => 450,
																 "FREE_RECHARGE" => 45,
																 "RATE_PERSONAL_QUESTION" => 1.9,
																 "RATE_MIPCAT_QUESTION" => 2.4
																),
												self::UT_INSTITUTE => array("MINIMUM_RECHARGE"=> 250,
																 "FREE_RECHARGE" => 25,
																 "RATE_PERSONAL_QUESTION" => 0.4,
																 "RATE_MIPCAT_QUESTION" => 0.6
																),
									 			self::UT_CORPORATE => array("MINIMUM_RECHARGE" => 450,
									 							 "FREE_RECHARGE" => 45,
																 "RATE_PERSONAL_QUESTION" => 1.9,
																 "RATE_MIPCAT_QUESTION" => 2.4
																),
												self::UT_INDIVIDAL => array("MINIMUM_RECHARGE" => -1,
																 "FREE_RECHARGE" => -1,
																 "RATE_PERSONAL_QUESTION" => -1,
																 "RATE_MIPCAT_QUESTION" => -1
																)
								   			);
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		// - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// Result Visibility : Criteria
		// - - - - - - - - - - - - - - - - - - - - - - - - - - -
		const RV_NONE	 	= 0;
		const RV_MINIMAL 	= 1;
		const RV_DETAILED 	= 2;
		// - - - - - - - - - - - - - - - - - - - - - - - - - - -
		 	
		// - - - - - - - - - - - - - - - - - -
		
		// 16 bit permissions, 65535 (all bits set to 1) means ALL Permitted.
		static $PERMISSIONS = array( self::UT_INSTITUTE => array("MNG_COORDINATOR"=>64,
																 "MNG_QUES"=>32,
																 "TST_DSG_WZD"=>16,
																 "MNG_TEST"=>8,
																 "REG_CAND"=>4,
																 "SCD_TEST"=>2,
																 "RES_ANALITICS"=>1,
																 "NOT_ALWD"=>0,
																 "ALL" =>65535
																),
									 self::UT_CORPORATE => array("MNG_COORDINATOR"=>64,
																 "MNG_QUES"=>32,
																 "TST_DSG_WZD"=>16,
																 "MNG_TEST"=>8,
																 "REG_CAND"=>4,
																 "SCD_TEST"=>2,
																 "RES_ANALITICS"=>1,
																 "NOT_ALWD"=>0,
																 "ALL" =>65535
																),
									 self::UT_VERIFIER  => array("VRFY_QUES_CNTRB"=>8,
									 							 "VRFY_QUES_SUBJT"=>4,
									 							 "VERY_TEST"=>2,
									 							 "VERY_CLNT"=>1,
									 							 "NOT_ALWD" =>0,
									 							 "ALL" =>65535
									 							),
									 self::UT_COORDINATOR => array("RESULT_INSPCTN"=>512,
                                                                    "PROD_CUSTM_RESULT"=>256,
                                                                    "TST_DNA"=>128,
                                                                 	"BRIEF_RESULT"=>64,
                                                                 	"TRD_PKG"=>32,
                                                                    "SNEEK_PEEK"=>16,
                                                                 	"MNG_QUES"=>8,
                                                                 	"TST_DSG_WZD"=>4,
                                                                 	"REG_CAND"=>2,
                                                                 	"SCD_TEST"=>1,
                                                                 	"NOT_ALWD"=>0,
                                                                 	"ALL" =>65535)
								   );
	
		// On Business Associate request, send emails to these email ids.
		static $ba_req_email_receptors = array("ritesh.kanoongo@gmail.com", "manish.mastishka@gmail.com");
		
		// Reserved Email IDs
		static $reserved_emails = array("corporate@mipcat.com", "institute@mipcat.com", "individual@mipcat.com", "contributor@mipcat.com");
	}
/*
	if($PERMISSIONS[CConfig::UT_VERIFIER]["VRFY_QUES"] & $row["permissions"] != $PERMISSIONS[CConfig::UT_VERIFIER]["NOT_ALWD"])
	{
		Permitted;
	}
	else
	{
		Not Permitted
	}
*/
?>
