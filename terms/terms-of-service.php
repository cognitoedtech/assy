<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
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
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo(CConfig::SNC_SITE_NAME);?> : Terms of Service</title>
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
			<h2 class="text-center">Terms of Service</h2>
			<br /><br />
			<p>
				Welcome to the <?php echo(CConfig::SNC_SITE_NAME);?>.com. We maintain this web application/site as a service to our customers. By using our application/site, you are agreeing to comply with and be bound by the following terms of services. Please review the following terms carefully. If you do not agree to these terms, you should not review information or use services provided by this application/site.
				<br /><br />
			
			
			<h4>01. Acceptance of Agreement.</h4>
				You agree to the terms and conditions outlined in this Terms of Use Agreement (&ldquo;Agreement&rdquo;) with respect to our web product (the &ldquo;<?php echo(CConfig::SNC_SITE_NAME);?>.com&rdquo;). This Agreement constitutes the entire and only agreement between us and you, and supersedes all prior or contemporaneous agreements, representations, warranties and understandings with respect to the <?php echo(CConfig::SNC_SITE_NAME);?>.com, the content, or services provided by or through the <?php echo(CConfig::SNC_SITE_NAME);?>.com, and the subject matter of this Agreement. This Agreement may be amended at any time by us from time to time without specific notice to you. The latest Agreement will be posted on the <?php echo(CConfig::SNC_SITE_NAME);?>.com, and you should review this Agreement prior to using the <?php echo(CConfig::SNC_SITE_NAME);?>.com. 
				<h4>02. Intellectual Property; Limited License to Users</h4>
				The Materials and Services on <?php echo(CConfig::SNC_SITE_NAME);?>.com, as well as their selection and arrangement, are protected by copyright, trademark, patent, and/or other intellectual property laws, and any unauthorized use of the Materials or Services at <?php echo(CConfig::SNC_SITE_NAME);?>.com may violate such laws and the Terms of Use. Except as expressly provided herein, <?php echo(CConfig::SNC_SITE_NAME);?>.com does not grant any express or implied rights to use the Materials and Services. You agree not to copy, republish, frame, download, transmit, modify, rent, lease, loan, sell, assign, distribute, license, sublicense, reverse engineer, or create derivative works based on the Site, its Materials, or its Services or their selection and arrangement, except as expressly authorized herein. In addition, you agree not to use any data mining, robots, or similar data gathering and extraction methods in connection with the <?php echo(CConfig::SNC_SITE_NAME);?>.com. 
				<h4>03. Copyright.</h4>
				The content, organization, graphics, design, audio, animation, video, compilation, magnetic translation, digital conversion and other matters related to <?php echo(CConfig::SNC_SITE_NAME);?>.com are protected under applicable copyrights, trademarks and other proprietary (including but not limited to intellectual property) rights. The copying, redistribution, use or publication by you of any such matters or any part of the <?php echo(CConfig::SNC_SITE_NAME);?>.com, except as allowed by Section 4, is strictly prohibited. You do not acquire ownership rights to any content, document or other materials viewed through the Site. The posting of information or materials on the Site does not constitute a waiver of any right in such information and materials. 
				
				
				<h4>04. Trademarks.</h4>
				The trademarks, logos, and service marks ("Marks") displayed on this Site are the property of Mastishka Intellisys Private Limited, or other third parties. You are not permitted to use the Marks without the prior written consent of Mastishka Intellisys Private Limited, or such third party that may own the Marks. <?php echo(CConfig::SNC_SITE_NAME);?> &trade; , is the trademarks or registered trademarks of Mastishka Intellisys Private Limited, in India and/or other countries. 
				<h4>05. Patents</h4>
				Certain <?php echo(CConfig::SNC_SITE_NAME);?>.com technologies and algorithms included in the <?php echo(CConfig::SNC_SITE_NAME);?>.com are protected by the patent law.
				<h4>06. Limited Right to Use.</h4>
				The viewing, printing or downloading of any content, graphic, form or document from the Site grants you only a limited, nonexclusive license for use solely by you for your own personal use and not for republication, distribution, assignment, sublicense, sale, preparation of derivative works or other use. No part of any content, form or document may be reproduced in any form or incorporated into any information retrieval system, electronic or mechanical, other than for your personal use (but not for resale or redistribution). <?php echo(CConfig::SNC_SITE_NAME);?>.com,  reserves the right to revoke the authorization to view, download, and print the Content and User Content available on this Site at any time, and any such use shall be discontinued immediately upon notice from <?php echo(CConfig::SNC_SITE_NAME);?>.com. The rights granted to you constitute a license and not a transfer of title. 
				<h4>08. Editing, Deleting and Modification.</h4>
				We reserve the right in our sole discretion to edit or delete any documents, information or other content appearing on <?php echo(CConfig::SNC_SITE_NAME);?>.com. 
				<h4>09. Indemnification.</h4>
				You agree to indemnify, defend and hold us and our partners, attorneys, staff and affiliates (collectively, &ldquo;Affiliated Parties&rdquo;) harmless from any liability, loss, claim and expense, including reasonable attorney&rsquo;s fees, related to your violation of this Agreement or use of <?php echo(CConfig::SNC_SITE_NAME);?>.com. 
				<h4>10. Nontransferable.</h4>
			Your right to use the Site is not transferable. Any password or right
			given to you to obtain information or documents is not transferable.

			<h4>11. Disclaimer and Limits.</h4>
			THE INFORMATION FROM OR THROUGH THE SITE ARE PROVIDED
			&ldquo;AS-IS,&rdquo; &ldquo;AS AVAILABLE,&rdquo; AND ALL WARRANTIES,
			EXPRESS OR IMPLIED, ARE DISCLAIMED (INCLUDING BUT NOT LIMITED TO THE
			DISCLAIMER OF ANY IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
			FOR A PARTICULAR PURPOSE). THE INFORMATION AND SERVICES MAY CONTAIN
			BUGS, ERRORS, PROBLEMS OR OTHER LIMITATIONS. WE AND OUR AFFILIATED
			PARTIES HAVE NO LIABILITY WHATSOEVER FOR YOUR USE OF ANY INFORMATION
			OR SERVICE. IN PARTICULAR, BUT NOT AS A LIMITATION THEREOF, WE AND
			OUR AFFILIATED PARTIES ARE NOT LIABLE FOR ANY INDIRECT, SPECIAL,
			INCIDENTAL OR CONSEQUENTIAL DAMAGES (INCLUDING DAMAGES FOR LOSS OF
			BUSINESS, LOSS OF PROFITS, LITIGATION, OR THE LIKE), WHETHER BASED ON
			BREACH OF CONTRACT, BREACH OF WARRANTY, TORT (INCLUDING NEGLIGENCE),
			PRODUCT LIABILITY OR OTHERWISE, EVEN IF ADVISED OF THE POSSIBILITY OF
			SUCH DAMAGES. THE NEGATION OF DAMAGES SET FORTH ABOVE ARE FUNDAMENTAL
			ELEMENTS OF THE BASIS OF THE BARGAIN BETWEEN US AND YOU. THIS SITE
			AND THE INFORMATION WOULD NOT BE PROVIDED WITHOUT SUCH LIMITATIONS.
			NO ADVICE OR INFORMATION, WHETHER ORAL OR WRITTEN, OBTAINED BY YOU
			FROM US THROUGH THE SITE SHALL CREATE ANY WARRANTY, REPRESENTATION OR
			GUARANTEE NOT EXPRESSLY STATED IN THIS AGREEMENT. <br />
			<br /> ALL RESPONSIBILITY OR LIABILITY FOR ANY DAMAGES CAUSED BY
			VIRUSES CONTAINED WITHIN THE ELECTRONIC FILE CONTAINING THE FORM OR
			DOCUMENT IS DISCLAIMED. WE WILL NOT BE LIABLE TO YOU FOR ANY
			INCIDENTAL, SPECIAL OR CONSEQUENTIAL DAMAGES OF ANY KIND THAT MAY
			RESULT FROM USE OF OR INABILITY TO USE OUR SITE. OUR MAXIMUM
			LIABILITY TO YOU UNDER ALL CIRCUMSTANCES WILL BE EQUAL TO THE
			PURCHASE PRICE YOU PAY FOR ANY GOODS, SERVICES OR INFORMATION.
			<h4>12. Use of Information.</h4>
				We reserve the right, and you authorize us, to the use and assignment of all information regarding <?php echo(CConfig::SNC_SITE_NAME);?>.com uses by you and all information provided by you in any manner consistent with our Privacy Policy. 
				<h4>13. Privacy Policy.</h4>
			Our Privacy Policy, as it may change from time to time, is a part of
			this Agreement.
			<h4>14. Links to other Web Sites.</h4>
			The Site may contain links to other Web sites. We are not responsible
			for the content, accuracy or opinions express in such Web sites, and
			such Web sites are not investigated, monitored or checked for
			accuracy or completeness by us. Inclusion of any linked Web site on
			our Application/Site does not imply approval or endorsement of the linked Web
			site by us. If you decide to leave our Site and access these
			third-party sites, you do so at your own risk.
			<h4>15. Information and Press Releases.</h4>
			The Site contains information and press releases about us. While this
			information was believed to be accurate as of the date prepared, we
			disclaim any duty or obligation to update this information or any
			press releases. Information about companies other than ours contained
			in the press release or otherwise, should not be relied upon as being
			provided or endorsed by us.
			<h4>16. Miscellaneous.</h4>
			This Agreement shall be treated as though it were executed and
			performed in Indore, Madhya Pradesh, and shall be governed by and
			construed in accordance with the laws of the State of Madhya
			Pradesh(without regard to conflict of law principles). Any cause of
			action by you with respect to the Site (and/or any information,
			products or services related thereto) must be instituted within one
			(1) year after the cause of action arose or be forever waived and
			barred. All actions shall be subject to the limitations set forth in
			Section 8 and Section 10. The language in this Agreement shall be
			interpreted as to its fair meaning and not strictly for or against
			either party. All legal proceedings arising out of or in connection
			with this Agreement shall be brought solely in Indore, Madhya
			Pradesh. You expressly submit to the exclusive jurisdiction of said
			courts and consents to extra-territorial service of process. Should
			any part of this Agreement be held invalid or unenforceable, that
			portion shall be construed consistent with applicable law and the
			remaining portions shall remain in full force and effect. To the
			extent that anything in or associated with the Site is in conflict or
			inconsistent with this Agreement, this Agreement shall take
			precedence. Our failure to enforce any provision of this Agreement
			shall not be deemed a waiver of such provision nor of the right to
			enforce such provision.

			</p>
		</div>
		<?php 
			include(dirname ( __FILE__ )."/../lib/footer.php");
		?>
	</div>
</body>
</html>