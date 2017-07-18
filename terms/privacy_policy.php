<?php
include_once (dirname ( __FILE__ ) . "/../lib/session_manager.php");
include_once (dirname ( __FILE__ ) . "/../lib/include_js_css.php");
include_once (dirname ( __FILE__ ) . "/../lib/site_config.php");
include_once (dirname ( __FILE__ ) . "/../lib/utils.php");
include_once (dirname ( __FILE__ ) . "/../database/config.php");
$page_id = CSiteConfig::HF_FAQ;
$login = CSessionManager::Get ( CSessionManager::BOOL_LOGIN );

$objIncludeJsCSS = new IncludeJSCSS ();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo(CConfig::SNC_SITE_NAME);?> : Privacy Policy</title>
		
<?php
$objIncludeJsCSS->IncludeMipcatCSS( "../" );
$objIncludeJsCSS->IncludeIconFontCSS( "../" );
$objIncludeJsCSS->CommonIncludeCSS ( "../" );
$objIncludeJsCSS->CommonIncludeJS ( "../" );
?>
	</head>
<body>
	<!-- Header -->
		<?php
		include (dirname ( __FILE__ ) . "/../lib/header.php");
		$bShowCKEditor = FALSE;
		?>
		<br />
	<br />
	<div class="container">
		<div class="text-justify">
			<h2 class="text-center">Privacy Policy</h2>
			<br />
			<br />
			<p>
				<?php echo(CConfig::SNC_SITE_NAME);?>.com (venture of Mastishka Intellisys Private Limited) respects your right to privacy and is fully committed to protecting the personal information you share on this site.
				<br />
				<br />
			
			
			<h3>How is my personal information collected and used?</h3>
			<ul>
				<li><h4>Signing Up</h4>
					In order to use certain features of this web site, you must
					first complete the sign up form. During sign up, you are required
					to give contact information, such as your name and e-mail address.
					We may use this information to contact you about services on our
					site for which you have expressed interest.<br /><br /></li>
				<li><h4>Profile</h4>
					<?php echo(CConfig::SNC_SITE_NAME); ?>.com stores information that we 
					collect through cookies to create a profile of our users. A profile 
					consists of information we store on individual users, which details 
					their viewing preferences. Consequently, collected information is 
					tied to your personally identifiable information to provide offers 
					and improve the content of the site for you. This profile is used to 
					tailor your visit to our web site and to direct pertinent marketing 
					promotions to you. We do not share your profile with third 
					parties.<br /><br /></li>
				<li><h4>Cookies</h4>
					A cookie is a piece of data stored on your computer tied to
					information about you. Usage of a cookie is in no way linked to any
					personally identifiable information while on our site. We use
					session ID cookies, which terminate once you close the browser.<br />
					<br /> By setting a cookie on our site, you will not have to sign
					in more than once, thereby saving time. If you reject the cookie,
					you may still use our site.<br /><br /></li>
				<li><h4>Log Files</h4> Like most standard web site servers,
					we use log files. This includes internet protocol (IP) addresses,
					browser type, internet service provider (ISP), entry and exit
					pages, platform type, date and time stamp, and number of clicks to
					analyze trends, administer the site, track users' movements in the
					aggregate, and gather broad demographic information for aggregate
					use. IP addresses are not linked to personally identifiable
					information.<br /><br /></li>

				<li><h4>Questions or Test related data that you upload:</h4>
					We provide a feature to have your own tests and questions. Those 
					will be your own property and <?php echo(CConfig::SNC_SITE_NAME);?>.com. 
					Will not use it for any commercial propose or share that with any other
					client. However there are chances that our other client can upload same
					data, <?php echo(CConfig::SNC_SITE_NAME);?>.com does not hold any 
					responsibility in such case for those data.<br /><br /></li>
				<li><h4>User information that you upload:</h4>
					The candidates/students information which you upload in order to conduct 
					test will be under your account only. <?php echo(CConfig::SNC_SITE_NAME);?>.com
					will not share this information publicly.<br /><br /></li>
			</ul>
			<h3>Communications from the Site</h3>
			<ul>
				<li><h4>Special Offers and Updates</h4>
				Established members will occasionally receive a newsletter and
				information on our products, services, and special deals. Out of
				respect for the privacy of our users, we present the option to not
				receive these types of communications.<br/><br/></li>
			</ul>
			<h3>Sharing</h3>
			<ul>
				<li><h4>Legal Disclaimer</h4>
				Though we make every effort to preserve your privacy, we may need 
				to disclose personal information when required by law wherein we 
				have a good-faith belief that such action is necessary to comply with
				a current judicial proceeding, court order, or legal process served
				on our web site. In the event of an inadvertent disclosure, 
				<?php echo(CConfig::SNC_SITE_NAME);?>.com will do everything commercially 
				reasonable to limit and remedy the disclosure, but we accept no liability 
				for any unintentional disclosure.<br/><br/></li>
			</ul>
			<h3>Third Party Intermediaries</h3>
			<ul>
				<li><h4>Information Use</h4>
					<?php echo(CConfig::SNC_SITE_NAME);?>.com may use the information in order
					to send you e-mail containing items that may be of interest to you. 
					<?php echo(CConfig::SNC_SITE_NAME);?>.com will not sell, share, or release 
					any information gathered on our web site to any third-parties.<br/><br/></li>
				<li><h4>Links</h4>
					This web site may contain links to other sites. Please be aware that 
					<?php echo(CConfig::SNC_SITE_NAME);?>.com is not responsible for the 
					privacy practices of such other sites. We encourage our users to be aware 
					when they leave our site and to read the privacy statements of each and 
					every web site that collects personally identifiable information. This 
					privacy statement applies solely to information collected by this web 
					site.<br/><br/></li>
				<li><h4>Chat Rooms, Message Boards, and Public Forums</h4>
					Please keep in mind that whenever you voluntarily disclose personal
					information online-for example on message boards, through e-mail,
					or in chat areas-that information can be collected and used by
					others. In short, by posting personal information online, you may
					receive unsolicited messages from other parties in return.<br/><br/></li>
				<li><h4>Security</h4>
					This web site takes every precaution to protect its users&rsquo;
					information. When users submit sensitive information via the web
					site, their information is protected both online and offline.<br />
					<br /> When users enter sensitive information, such as a credit
					card account number, that information is encrypted with SSL. While
					on a secure page, such as our order form, the lock icon on the
					bottom of web browsers such as Microsoft Internet Explorer becomes
					locked, as opposed to un-locked or open, when users are just
					&lsquo;surfing&rsquo;.<br /> <br /> While we use SSL encryption to
					protect sensitive information online, we also do everything in our
					power to protect user information offline. All of our users&rsquo;
					information-not just the sensitive information mentioned above-is
					restricted in our offices. Only employees who need the information
					to perform a specific job (for example, a billing clerk or a
					customer service representative) are granted access to personally
					identifiable information.<br /> <br />
					If users have any questions about the security at our web site, they 
					can send an e-mail to <?php echo(CConfig::OEI_SUPPORT);?>.<br/><br/></li>
				<li><h4>Correcting and Updating Personal Information</h4>
					If your personally identifiable information changes (such as zip
					code, phone, e-mail, or postal address), or if you no longer desire
					our service, we provide a way to correct or change your personally
					identifiable information. This can be done by accessing your
					personal profile in Edit Profile.<br/><br/></li>
				<li><h4>Notification of Changes</h4>
					If we decide to change our privacy policy, we will amend or update
					this.<br/><br/></li>
				<li><h4>Contact</h4>
				If you have any questions or suggestions regarding our privacy policy, 
				please contact us at: <?php echo(CConfig::OEI_SUPPORT);?><br/><br/></li>
			</ul>
			</p>
		
		</div>
		<?php 
			include(dirname ( __FILE__ )."/../lib/footer.php");
		?>
	</div>
</body>
</html>