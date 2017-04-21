<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	include_once(dirname(__FILE__)."/../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../lib/include_js_css.php");
	include_once(dirname(__FILE__)."/../../lib/site_config.php");
	include_once(dirname(__FILE__)."/../../lib/utils.php");
	include_once(dirname(__FILE__)."/../../database/config.php");
	
	$page_id = CSiteConfig::HF_GS_HELP;
	$login = CSessionManager::Get(CSessionManager::BOOL_LOGIN);
	
	$top_margin = "";
	if($login)
	{
		$top_margin = "70px";
	}
	else
	{
		$top_margin = "70px";
	}
	
	$objIncludeJsCSS = new IncludeJSCSS();
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Getting Started : Institute</title>
		 	 
		
		<?php 
			$objIncludeJsCSS->CommonIncludeCSS("../../");
			$objIncludeJsCSS->IncludeBootStrapDocsCSS("../../");
			$objIncludeJsCSS->IncludeBootStrapResponsiveCSS("../../");
			$objIncludeJsCSS->IncludeFuelUXCSS ("../../");
			$objIncludeJsCSS->CommonIncludeJS("../../");
			$objIncludeJsCSS->IncludeIconFontCSS("../../");
		?>
		<style>
			#overlay { position: fixed; left: 0px; top: 0px; width: 100%; height: 100%; z-index: 100; background-color:white;}
			a.anchor:link {color:GhostWhite;}    /* unvisited link */
			a.anchor:visited {color:GhostWhite;} /* visited link */
			a.anchor:hover {color:GhostWhite;}   /* mouse over link */
			a.anchor:active {color:GhostWhite;}  /* selected link */
			a:focus {outline: none;}
			p{font-size:16px;text-align:justify;}
			.hero-unit1 {
 				 padding: 60px;
 				 margin-bottom: 30px;
 				 font-size: 18px;
 				 font-weight: 200;
				 line-height: 30px;
 				 color: inherit;
				 background-image:url('images/bgg1.jpg');
 				 -webkit-border-radius: 6px;
    			 -moz-border-radius: 6px;
         		 border-radius: 6px;
			}
			
			.container1 {
				background-color:white;
 				 margin-right: auto;
 				 margin-left: 70px;
				position: relative;
 				 *zoom: 1;
			}
			
			.span91 {
 				 width: 75%;
			}
			
			.add {
					text-align: left;
					color: #D00000;
					background-color: #FFFFFF;
					border: 2px solid #939393;
					border-radius: 25px;
					padding: 10px 20px; 
			}
			.nav-list-new > .active > a,
			.nav-list-new > .active > a:hover {
  			color: #ffffff;
  			text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.2);
  			background-color: #0088cc;
			}
			
			.modal1 {
				display:    none;
				position:   fixed;
				z-index:    1000;
				top:        50%;
				left:       60%;
				height:     100%;
				width:      100%;
			}
							
		</style>

	</head>
	<body style=" background-color:white; margin: 5px;" data-spy="scroll" data-target=".bs-docs-sidebar">
		<!-- Header -->
			<?php
				include_once (dirname ( __FILE__ ) . "/../../lib/header.php");
			?>	
			
		<div class="fuelux modal1">
			<div class="preloader"><i></i><i></i><i></i><i></i></div>
		</div>	
		<div class="container1">
			<div class="row">
				<div class="span3 bs-docs-sidebar" style="float:left">
					<ul class="nav nav-list-new bs-docs-sidenav">
						<li><a href="#introduction">Introduction<i style="float:right" class="icon-arrow-right-5"></i> </a > </li>
						<li><a href="#dashboard"><i style="float:right" class="icon-arrow-right-5"></i> Dashboard</a></li>
						<li><a href="#sneekpeek"><i style="float:right" class="icon-arrow-right-5"></i> Sneak Peek</a></li>
					    <li><a href="#myaccount"><i style="float:right" class="icon-arrow-right-5"></i> My Account and Billing</a></li>
						<li><a href="#mycoordinators"><i style="float:right" class="icon-arrow-right-5"></i>My Coordinators</a></li>
						<li><a href="#manageques"><i style="float:right"  class="icon-arrow-right-5"></i>Manage Questions</a></li>
						<li><a href="#dmt"><i style="float:right" class="icon-arrow-right-5"></i>Design and Manage Test</a></li>
						<li><a href="#rc"><i style="float:right" class="icon-arrow-right-5"></i>Register Candidates</a></li>
						<li><a href="#st"><i style="float:right" class="icon-arrow-right-5"></i>Schedule Test</a></li>
						<li><a href="#tradepackage"><i style="float:right"  class="icon-arrow-right-5"></i>Trade Test Packages</a></li>
						<li><a href="#ra"><i style="float:right" class="icon-arrow-right-5"></i>Result Analytics</a></li>
						
					</ul>
				</div>
				<div class="span91" style="float:right">
				
				<!--	=======================INTRODUCTION===========================	-->
									
				<section id="introduction">
					<div class="page-header">
						<h1>Getting Started as Institute : Introduction</h1>
					</div>
					<p><a href="http://<?php echo(CSiteConfig::ROOT_URL);?>/index.php"><?php echo(CConfig::SNC_SITE_NAME);?>.com</a> is a complete bundle of all the modules which will help you in selecting  proper candidates by evaluation, for your organization. 
						It is simple and easy to use tool with rich set of features to conduct online tests, powered with detailed result analytics of candidates, all in just one mouse click. Test practices and detailed result analytics of students help you and your students to figure out specific area in which students are weak or need more attention. With the help of <a href="http://<?php echo(CSiteConfig::ROOT_URL);?>/index.php"><?php echo(CConfig::SNC_SITE_NAME);?>.com</a> overall performance of your students will increase exponentially and they will be ready to face Exams/Campus Tests with full confidence.<br><br> 
						<?php echo(CConfig::SNC_SITE_NAME);?> increases the chances of students to crack the Exam/Campus Selection by many folds. The process flow at <?php echo(CConfig::SNC_SITE_NAME);?> is very simple. You will have to register candidates, schedule tests for them, get their status and when they finish - have the complete result analytics within no time. In order to schedule tests all you need is to design the test with the help of <?php echo(CConfig::SNC_SITE_NAME);?> Test Design Wizard. It just needs a question source, which can be either from <?php echo(CConfig::SNC_SITE_NAME);?> or your own personal ones. Next sections of this tutorial will fully guide you about <?php echo(CConfig::SNC_SITE_NAME);?>.   
 					</p>
 					 
 				</section>
 					
					<!--	======================= DASHBOARD===========================	-->
 					
 				<section id="dashboard">
					<div class="page-header">
						<h1>Dashboard</h1>
					</div>
					<p> Dashboard is the first page that you will see after login. It shows all your recent activities, like the tests you have scheduled.</p>
						<div class="hero-unit1" style="text-align: center;">
							<a href="images/dashboard.jpg" target="_blank"><img src="images/dashboard.jpg" height="600" width="700" /></a>
						</div>
				</section>
					
					<!--	======================SNEAK PEEK============================	-->
					
				<section id="sneekpeek">
					<div class="page-header">
						<h1>Sneak Peek </h1>
					</div>
					<p>This section provides you brief information about the "Available Questions" uploaded by <?php echo(CConfig::SNC_SITE_NAME);?> or by the user(personal). This is helpful for the user while designing a proper test.</p>
					      	
							<!--	====SNEAKPEEK(mip cat knowledge base)====	-->
						    			
					<h3  style="color:black">Sneak Peek(<?php echo(CConfig::SNC_SITE_NAME);?> Knowledge Base)</h3>
					<p>This page provides you the list of various questions available at <?php echo(CConfig::SNC_SITE_NAME);?> database.</p>
						<div class="hero-unit1" style="text-align: center;">
							<a href="images/sneek_kb.jpg" target="_blank"><img src="images/sneek_kb.jpg" height="600" width="700" /></a>
						</div>     	
							
							<!-- 	====SNEAKPEEK(Personal base)====	-->
						     	
					<h3  style="color:black">Sneak Peek(Personal Knowledge Base)</h3>
					<p> This page displays the list of various questions uploaded by user. This will also help the user to verify the available questions at the time of test design.</p>
						<div class="hero-unit1" style="text-align: center;">
							<a href="images/sneek_pb.jpg" target="_blank"><img src="images/sneek_pb.jpg" height="600" width="700" /></a>
						</div>     	
				</section>
					
					<!--	=======================MY ACCOUNT AND BILLING===========================	-->
					
				<section id="myaccount">
					<div class="page-header">
						<h1>My Account and Billing</h1>
					</div>
					<p> This section is related to your personal data. From this section you can edit your personal details, change your account password, edit details about your organization etc.
						Your billing information is also displayed here along with the facility of recharging your account. 
					</p>
											
							<!--	====Personal Details====	-->
							 	 			
					<h3  style="color:black">Personal Details</h3>
					<p> Your personal details are displayed here. Clicking on Edit button at the bottom will take you to another form where you can edit your details.  
						Once you finish, save your details and it will get updated. You can reach here using - <strong>My Account <i class="icon-arrow-right"></i> Personal Details.</strong>
					</p>
						<h5  style="color:black"> Viewing personal Details:</h5>
							<div class="hero-unit1" style="text-align: center;">
								<a href="images/view_personal_detail.jpg" target="_blank"><img src="images/view_personal_detail.jpg" height="600" width="700" /></a>
							</div>     	
										 		
						<h5  style="color:black"> Editing personal Details:</h5>
							<div class="hero-unit1" style="text-align: center;">
								<a href="images/edit_personal_detail.jpg" target="_blank"><img src="images/edit_personal_detail.jpg" height="600" width="700" /></a>
							</div>     	
									 	
							<!--	====Account Security (Password)====	-->
										
					<h3  style="color:black">Account Security (Password)</h3>
					<p>You can change your security question and password using this option.You can reach here using - <br> 
						<strong>My Account <i class="icon-arrow-right"></i> Account Security(Password).</strong>
					</p>
						<h5  style="color:black"> Viewing Account Security:</h5>
							<div class="hero-unit1" style="text-align: center;">
								<a href="images/view_acc_security.jpg" target="_blank"><img src="images/view_acc_security.jpg" height="600" width="700" /></a>
							</div>     	
											
						<h5  style="color:black"> Editing Account Security:</h5>
						<div class="hero-unit1" style="text-align: center;">
							<a href="images/edit_acc_security.jpg" target="_blank"><img src="images/edit_acc_security.jpg" height="600" width="700" /></a>
						</div>     	
											
							<!--	====About Organization====	-->
											
					<h3  style="color:black">About Organization</h3>
					<p>You can view/edit your organization details from this option.You can reach here using -  
						<strong>My Account <i class="icon-arrow-right"></i> About Organization.</strong>
					</p>
						<h5>View Organization Details:</h5>
							<div class="hero-unit1" style="text-align: center;">
								<a href="images/view_org_details.jpg" target="_blank"><img src="images/view_org_details.jpg" height="600" width="700" /></a>
							</div>     	
										
						<h5  style="color:black">Edit Organization Details:</h5>
						<p>You can also add your organization logo or simply display a plain text.</p><br>
							<div class="hero-unit1" style="text-align: center;">
								<a href="images/edit_org_details.jpg" target="_blank"><img src="images/edit_org_details.jpg" height="600" width="700" /></a>
							</div> 
							<h5>Logo Type - Image</h5>
							<div class="hero-unit1" style="text-align: center;">
								<a href="images/org_img.jpg" target="_blank"><img src="images/org_img.jpg" height="300" width="400" /></a>
							</div>     	    	
									
							<!--	====Billing Information====	-->
									
					<h3  style="color:black">Billing Information</h3>
					<p>This option shows you the complete billing information related to your account. Important points are described below. You can reach here using -  
						<strong>My Account <i class="icon-arrow-right"></i> Billing Information.</strong>
					</p>
						<ol>
							<li style="font-size:16px"><strong><?php echo(CConfig::SNC_SITE_NAME);?> Question Source Rate:</strong> It is the rate per test for a single candidate if you choose <?php echo(CConfig::SNC_SITE_NAME);?> question source to conduct your test.</li><br>
							<li style="font-size:16px"><strong>Personal Question Source Rate:</strong> It is the rate per test for a single candidate if you choose to conduct your test using your personal question source .</li><br>
							<li style="font-size:16px"><strong>Projected Balance:</strong> It is the net available balance remaining in your account, it's calculation is based on all the test you have scheduled, whether candidate has attempted the test or not.</li><br>
							<li style="font-size:16px"><strong>Balance:</strong> It is net balance remaining in your account. It's calculation is based on all the tests which the candidates have attempted. 
								It is always greater than or equal to the projected balance.</li><br>
							<li style="font-size:16px"><strong>Last Billed:</strong> Date of your last bill.</li><br>
							<li style="font-size:16px"><strong>Business Associate:</strong> Name of our business associate through which you have opted <?php echo(CConfig::SNC_SITE_NAME);?>.</li><br>
						</ol>
									
							<!--	====Billing History====	-->
										
					<h3  style="color:black">Billing History</h3>
					<p>This page also shows you the account billing history, in tabular format. It shows following information.
					</p>
						<ol>
							<li style="font-size:16px"><strong>Transaction ID:</strong> Unique identification of your last transaction.</li><br>
							<li style="font-size:16px"><strong>Recharge Amount:</strong> Amount of Recharge.</li><br>
							<li style="font-size:16px"><strong>Payment Mode:</strong> Mode of payment, (Cheque, DD or NEFT).</li><br>
							<li style="font-size:16px"><strong>Payment Agent:</strong> Agent through with you have submitted payment.</li><br>
							<li style="font-size:16px"><strong>Payment Ordinal:</strong> It is Cheque / Demand Draft number or NEFT Transaction ID through which payment has been deposited.</li><br>
							<li style="font-size:16px"><strong>Payment Date:</strong> Date on which you have deposited payment.</li><br>	
							<li style="font-size:16px"><strong>Realization Date:</strong> Date on which payment has been actually transferred to our account.</li><br>									
						</ol>
							<div class="hero-unit1" style="text-align: center;">
								<a href="images/billing_info.jpg" target="_blank"><img src="images/billing_info.jpg" height="600" width="700" /></a>
							</div>     	
										
							<!--	====Account Recharge====	-->
											
					<h3  style="color:black">Account Recharge</h3>
					<p>This page shows you options to recharge your account. It has following options:
					</p>
						<ol>
							<li style="font-size:16px"><strong>U.S. Dollars:</strong></li>	
							<p>If you are corporate whose registered office is out of India, you have to choose this option. 
								It shows you the payment gateway via Paypal. You can proceed with this option and deposit your amount.
								Once realized, balance will be shown in your billing info option.
							</p>			
								<div class="hero-unit1" style="text-align: center;">
									<a href="images/account_rec_usdollar.jpg" target="_blank"><img src="images/account_rec_usdollar.jpg" height="600" width="700" /></a>
								</div> <br><br>
												
							<li style="font-size:16px"><strong>Indian Rupees:</strong></li>	
							<p>If you are a corporate whose registered office is in India, you have to choose this option.You can deposit your payment via Cheque, DD, or NEFT.
							</p>
							
								<ul type="disc">
									<li style="font-size:16px"><strong>Pay Via Cheque: </strong>  If you choose to pay via Cheque, keep your cheque ready,	this page shows you to fill the details of your cheque. 
										Page shows to fill the options for</li>
										
										<ul type="circle"><br>
											<li style="font-size:16px"><strong>Amount:</strong> Total recharge amount.</li>
											<li style="font-size:16px"><strong>Cheque Number:</strong> Cheque Number mentioned on Cheque.</li>
											<li style="font-size:16px"><strong>Date on Cheque:</strong> Date mentioned on Cheque.</li>
											<li style="font-size:16px"><strong>Drawn-On Bank:</strong> Bank Name.</li><br>
											<p><strong><em>NOTE:</strong>Once you have filled all the details you have to click process,once processed successfully, you will be provided a transaction id,
												Note that transaction ID.You need to write down that id behind cheque before sending to us.</em>
											</p>	 			
												<div class="hero-unit1" style="text-align: center;">
													<a href="images/account_rec_cheque.jpg" target="_blank"><img src="images/account_rec_cheque.jpg" height="600" width="700" /></a>
												</div>     	
										</ul><br>
									<li style="font-size:16px"><strong>Pay Via Demand Draft: </strong>If you choose to pay via DD (Demand Draft), keep your DD ready, 
										this page shows you to fill the details of your cheque.Page shows to fill the options for</li>
																		
										<ul type="circle"><br>
											<li style="font-size:16px"><strong>Amount:</strong> Total recharge amount.</li>
											<li style="font-size:16px"><strong>DD Number:</strong> DD Number mentioned on Demand Draft.</li>
											<li style="font-size:16px"><strong>Date on DD:</strong> Date on Demand Draft.</li>
											<li style="font-size:16px"><strong>Drawn-On Bank:</strong> Bank Name.</li><br>
											<p><strong><em>NOTE:</strong>Once you have filled all the details you have to click process, once processed successfully,you will be provided a transaction id,Note that transaction ID.
												You need to write down that id behind DD before sending to us. </em>
											</p>
												<div class="hero-unit1" style="text-align: center;">
													<a href="images/account_rec_dd.jpg" target="_blank"><img src="images/account_rec_dd.jpg" height="600" width="700" /></a>
												</div>     	
										</ul><br>
									<li style="font-size:16px"><strong>Payment Instructions for Check/DD Payment:</strong>Please read and follow following payment instructions carefully.</li>					
																			
										<ul type="circle"><br>
											<li style="font-size:16px">Payment should always made in favor of "Mastishka Intellisys Private Limited", Cheque / Demand Draft should be payable at Indore (M.P.).</li>
											<li style="font-size:16px">Write down your registered Email-ID behind Cheque / Demand Draft.</li>
											<li style="font-size:16px">Post the Cheque / Demand Draft at following (registered) address.</li><br>
												<div class="add">
													Mastishka Intellisys Private Limited<br>
													95, Veena Nagar<br>
													Opposite Bombay Hospital<br>
													Ring Road, Indore 452010, India<br>
													Ph: +91 98266 00457, +91 90395 79039
												</div>																			
										</ul><br>
									<li style="font-size:16px"><strong>Pay via NEFT (National Electronic Funds Transfer):</strong>If you have paid amount via NEFT, 
										then enter following NEFT details.</li>
										
										<ul type="circle"><br>
											<li style="font-size:16px"><strong>Amount:</strong> Recharge Amount.</li>
											<li style="font-size:16px"><strong>NEFT Transaction ID:</strong> Unique ID generated when you did NEFT transfer.</li>
											<li style="font-size:16px"><strong>Date of Payment:</strong> Payment Date.</li>
											<li style="font-size:16px"><strong>Bank:</strong> Bank, through which you have processed the payment.</li><br>
												<div class="hero-unit1" style="text-align: center;">
													<a href="images/account_rec_neft.jpg" target="_blank"><img src="images/account_rec_neft.jpg" height="600" width="700" /></a>
												</div>     	
										</ul><br>
									<li style="font-size:16px"><strong>NEFT Payment Instructions:</strong> Please Read and follow 
										following NEFT payment instructions carefully.</li>
																						
										<ul type="circle"><br>
											<li style="font-size:16px"><strong>Payment should always made in favor of,</strong></li><br>
												<div class="add">
													Account Name: "Mastishka Intellisys Private Limited"<br>
													Account Number: 04 0420 0000 4883<br>
													IFSC Code: HDFC0000404<br>
													Bank: HDFC Bank
												</div><br>
																														
											<p><strong><em>NOTE:</strong>Once you process the NEFT details, a Transaction ID will be generated by <?php echo(CConfig::SNC_SITE_NAME);?>.Use that id for your future reference.</em></p>
																					
										</ul>
								</ul>											
						</ol>
				</section>							
					
					<!--	=======================MY COORDINATORS===========================	-->
									
				<section id="mycoordinators">
					<div class="page-header">
						<h1>My Coordinators</h1>
					</div>	
					<p>This section will provide you to add coordinator. Coorporate user can register or manage <b>"Coordinators"</b> in his survilience with same functionalities except account recharge.</p>
				
							<!--	====Register Coordinator====	-->
							
					<h3 style="color:black">Register Coordinator</h3>
					<p>In this section you have to enter all the details of coordinator that has to be registered. Account of coordinator can be recharged by transferring amount to his/her account.
						<div class="hero-unit1" style="text-align: center;">
							<a href="images/mycoor_register.jpg" target="_blank"><img src="images/mycoor_register.jpg" height="600" width="700" /></a>
						</div>     	

							<!--	====Manage Coordinator====	-->
					
					<h3 style="color:black">Manage Coordinator</h3>	
					<p>In this section you can manage coordinators by clicking "edit" option alongside.</p>
						<ol>
							<li style="font-size:16px"><strong>Name :</strong> This field shows the name of the registered coordinators.</li><br>
							<li style="font-size:16px"><strong>Contact Number :</strong> This field shows the contact number to particular coordinator.</li><br>
							<li style="font-size:16px"><strong>E-mail ID :</strong> This field displays the E-mail ID of the coordinator.</li><br>
							<li style="font-size:16px"><strong>Location :</strong> This field displays the city or state to which the coordinator belongs.</li><br>
							<li style="font-size:16px"><strong>Department :</strong> The department of coordinator is displayed in this field.</li><br>
							<li style="font-size:16px"><strong>Balance :</strong> It is net balance remaining in your account.  It's calculation is based on all the tests which the candidates have attempted. </li><br>
							<li style="font-size:16px"><strong>Projected Balance :</strong> It is the net available balance remaining in your account, it's calculation is based on all the test you have scheduled, whether candidate has attempted the test or not.</li><br>
							<li style="font-size:16px"><strong>Edit Account Details :</strong> Edit account details facilitates you to <strong> Reclaim </strong> your amount from the coordinator's account or <strong> Recharge </strong> coordinator's account.</li><br>
						</ol>
						<div class="hero-unit1" style="text-align: center;">
							<a href="images/mycoor_manage.jpg" target="_blank"><img src="images/mycoor_manage.jpg" height="600" width="700" /></a>
						</div>     	
				
				</section>			
					
					<!--	=======================MANAGE QUESTIONS===========================	-->			
			
				<section id="manageques">
					<div class="page-header">
						<h1>Manage Questions</h1>
					</div>	
					<p>This section allows user to manage (upload) questions.
					</p>
						
							<!--	====Submit Question====	-->
						
					<h3 style="color:black">Submit Question</h3>	
					<p> This block allows the user to submit questions one by one. The general options provided are described below-
					</p>
						<ol>
							<li style="font-size:16px"><strong>Normal Questions : </strong></li><br>	
								<ul type="disc">
									<li style="font-size:16px"><strong>Select Language :</strong> This option facilitates the user to define the language of the questions.</li><br>
									<li style="font-size:16px"><strong>Subject :</strong> User can define the name of the subject to which the question belongs.</li><br>
									<li style="font-size:16px"><strong>Topic :</strong> Here user should mention the topic to which the question belongs.<br>
										<em>NOTE: Topic should be different from RC or directions para title already submitted.</em></li><br>
									<li style="font-size:16px"><strong>Question Format :</strong> This option will determine the question pattern. Questions can be uploaded either in<strong> text </strong>or <strong>image.</strong></li><br>
									<li style="font-size:16px"><strong>Options :</strong> User is provided with the facility of allowing multiple number of options (minimum two) according to the requirement. Additional options can be included by selecting <strong>"Add Option"</strong>
										  and can be removed by selecting<strong>"Remove Option".</strong>Answers can be provided in either <strong>text</strong> or <strong>image.</strong></li><br>
									<li style="font-size:16px"><strong>Choose Correct Options:</strong> The correct answer of particular question is selected here. It will provide a list containing all options given above , 
										user can select single or multiple correct answers from the list.</li><br>
									<li style="font-size:16px"><strong>Levels :</strong> Here user can define the difficulty level of questions. It can be selected from given options.</li>
										<ul type="circle">
											<li style="font-size:16px">Easy</li>
											<li style="font-size:16px">Moderate</li>
											<li style="font-size:16px">Hard</li>
										</ul><br>
									<li style="font-size:16px"><strong>Explanation :</strong> This field is optional where you can insert description for correct answer. It can be either text or image.</li><br>
									<li style="font-size:16px"><strong> Notations : </strong></li><br>
										<ul type="circle">
											<li style="font-size:16px"><strong> #@<?php echo(strtolower(CConfig::SNC_SITE_NAME));?>_code_start :</strong> If user wants to submit a code, he/she should mention this notation at the beginning of that code.</li><br> 
											<li style="font-size:16px"><strong> #@<?php echo(strtolower(CConfig::SNC_SITE_NAME));?>_code_end :</strong> This notation is used at the end of the code.</li><br>	
										</ul>
								</ul>
									<div class="add">
										 <strong style="color:blue">#@<?php echo(strtolower(CConfig::SNC_SITE_NAME));?>_code_start</strong><br>
										 $x=5;<br>
										 $y=10;<br>
											&nbsp;&nbsp;&nbsp;&nbsp;function myTest()<br> 
											&nbsp;&nbsp;&nbsp;&nbsp;{<br> 
												&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;global $x,$y;<br>
												&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$y=$x+$y;<br>
											&nbsp;&nbsp;&nbsp;&nbsp;}<br>
										myTest();<br>
										echo $y;<br>
									<strong style="color:blue">#@<?php echo(strtolower(CConfig::SNC_SITE_NAME));?>_code_end</strong>
									</div><br>
									<div class="hero-unit1" style="text-align: center;">
										<a href="images/submitques_normal.jpg" target="_blank"><img src="images/submitques_normal.jpg" height="600" width="700" /></a>
									</div>     	
							
							<li style="font-size:16px"><strong>Reading Comprehension(RC)/ Directions : </strong></li><br>
							<p> Apart from the fields provided in Normal Questions, Reading Comprehension/ Directions varies with three additional fields which are mentioned below.
							</p><br>
								<ul type="disc">
									<li style="font-size:16px"><strong>Use Existing Para:</strong> This option provides you with two functions where user can either choose a paragraph which already exists or enter new paragraph.</li><br>  
									<li style="font-size:16px"><strong>Title :</strong> User can specify the title of the paragraph here.<br>
										<em>NOTE: Title should be unique i.e should not be similar to that of subject.</em></li><br>
									<li style="font-size:16px"><strong>Reading Comprehension Para :</strong> In this field paragraph is provided. It can be either in text or image. </li><br>
									<li style="font-size:16px"><strong> Notations : </strong></li><br>
										<ul type="circle">
											<li style="font-size:16px"><strong> #@<?php echo(strtolower(CConfig::SNC_SITE_NAME));?>_code_start :</strong> If user wants to submit a code, he/she should mention this notation at the beginning of that code.</li><br> 
											<li style="font-size:16px"><strong> #@<?php echo(strtolower(CConfig::SNC_SITE_NAME));?>_code_end :</strong> This notation is used at the end of the code.</li><br>	
										</ul>
								</ul><br>
									<div class="hero-unit1" style="text-align: center;">
										<a href="images/submitques_rc.jpg" target="_blank"><img src="images/submitques_rc.jpg" height="600" width="700" /></a>
									</div>     
						</ol>
							
							<!--	====Bulk Upload====	-->
					
					<h3 style="color:black">Bulk Upload</h3>
					<p> This section will allow the user to add multiple questions simultaneously.</p>
					<ol>
						<li style="font-size:16px"><strong>Question(Excel) File</strong></li>
						<p> This option will have specific format for uploading Excel file. Description about Excel file format is explained below -</p>
							<ul type="disc">
								<li style="font-size:16px"><strong> Sheet Pattern : </strong></li><br>
									<ol>
										<li style="font-size:16px"><strong>Serial Number :</strong> This field should describe the serail number of particular question.
											The questions which are to be provided in multiple languages should have same serial number .</li><br>
										<li style="font-size:16px"><strong>Para Discription :</strong> This field should contain paragraph for the question. This field is used for "Reading comprehension/Directions" type questions.</li><br>
										<li style="font-size:16px"><strong>Language :</strong> This field specifies the language of the question in which user wants to insert question.</li><br>
										<li style="font-size:16px"><strong>Question :</strong> User can add question in this section. Question may be asked in either text or image format.</li><br>
										<li style="font-size:16px"><strong>Answer :</strong> This field should contain the right answer of question. The answer will be "INTEGER VALUE". 
											For multiple correct answers integer values can be separated by "COMMA(,)".</li><br>
										<li style="font-size:16px"><strong>Subject :</strong> This field should have the name of subject to which the topic belongs.</li><br>
										<li style="font-size:16px"><strong>Topic :</strong> This field should contain the name of the topic to which the question belongs. It should not conflict with the heading of the paragraph.</li><br>
										<li style="font-size:16px"><strong>Difficulty :</strong> This field should contain three"INTEGER VALUE"- 1,2,3 describing the difficulty levels as easy , moderate and hard respectively. </li><br>
										<li style="font-size:16px"><strong>Explanation :</strong> This field is optional where you can insert description for correct answer. It can be either text or image. </li><br>
										<li style="font-size:16px"><strong>Options :</strong> This field should contain all possible options of question that the user wants to provide. </li><br> 
									</ol>
								<li style="font-size:16px"><strong> Notations : </strong></li><br>
									<ol>
										<li style="font-size:16px"><strong> #@<?php echo(strtolower(CConfig::SNC_SITE_NAME));?>_na : </strong> This notation is applicable for "NORMAL QUESTIONS" where no paragraph is applicable.</li><br>
										<li style="font-size:16px"><strong> #@<?php echo(strtolower(CConfig::SNC_SITE_NAME));?>_img[name of image with extension(.png,.jpg,.gif)] :</strong> If user wants to link an image with the file then this notation is used as-  #@<?php echo(strtolower(CConfig::SNC_SITE_NAME));?>_img[demo.jpg].
											 It is applicable for <strong>Explanation,Question, Options</strong> and <strong>Paragraph Description</strong> also.</li><br>
										<li style="font-size:16px"><strong> #@<?php echo(strtolower(CConfig::SNC_SITE_NAME));?>_empty :</strong> This notation is used only when <strong>Explanation </strong>is not available.</li><br>
										<li style="font-size:16px"><strong> #@<?php echo(strtolower(CConfig::SNC_SITE_NAME));?>_copy :</strong> This notation is used when user wants to copy the above cell data.</li><br>
										<li style="font-size:16px"><strong> #@<?php echo(strtolower(CConfig::SNC_SITE_NAME));?>_code_start :</strong> If user wants to submit a code, he/she should mention this notation at the beginning of that code.</li><br> 
										<li style="font-size:16px"><strong> #@<?php echo(strtolower(CConfig::SNC_SITE_NAME));?>_code_end :</strong> This notation is used at the end of the code.</li><br>
							</ul>
								<h5  style="color:black"> Bulk Upload for Normal Questions:</h5>
								<div class="hero-unit1" style="text-align: center;">
										<a href="images/bulkupload_normal.jpg" target="_blank"><img src="images/bulkupload_normal.jpg" height="600" width="700" /></a>
								</div>     	
								
								<h5  style="color:black"> Bulk Upload for Reading Comprehension/Directions:</h5>
								<div class="hero-unit1" style="text-align: center;">
										<a href="images/bulkupload_rc.jpg" target="_blank"><img src="images/bulkupload_rc.jpg" height="600" width="700" /></a>
									</div>     
					</ol>
							
							<!--	====Reconcile Questions====	-->
								
						<h3 style="color:black">Reconcile Questions</h3>
						<p> Using this section user will be able to review all the questions uploaded by him/her.</p>
					</section>			
			
					<!--	=======================DESIGN AND MANAGE TEST===========================	-->	
					
				<section id="dmt">
					<div class="page-header">
						<h1>Design And Manage Test</h1>
					</div>										
					<p> Test Design is the heart of <?php echo(CConfig::SNC_SITE_NAME);?>. It is so generalized, that you can almost design all patterns of the tests you can think of. 
						If you are going to design test using your question source then just make sure that you have uploaded proper number of questions in your question source.
					</p>
							
							<!--	====Test Design Wizard====	-->
							
					<h3 style="color:black">Test Design Wizard</h3>
					<p> The Test Design Wizard provides the choicest interface to the user for designing the tests.</p>
					
						<h4 style="color:black">Test Details</h4><br>
							<ol>
								<li style="font-size:16px"><strong> Test Name :</strong> Its a unique name to identify the test. By default it is automatically generated, but you can change name to follow your own naming convention.
									 Wizard notifies you if test with name already exists. This is a required option. You will see an error at the bottom if required options are not filled.
									  You can't go to next section until there is an error in the previous inserted data.</li><br>
									<div class="hero-unit1" style="text-align: center;">
										<a href="images/dmt_testname.jpg" target="_blank"><img src="images/dmt_testname.jpg" height="600" width="700" /></a>
									</div>  
									<h5  style="color:black"> Error Message Generated </h5>
									<div class="hero-unit1" style="text-align: center;">
										<a href="images/dmt_testname1.jpg" target="_blank"><img src="images/dmt_testname1.jpg" height="600" width="700" /></a>
									</div>       
								<li style="font-size:16px"><strong> Test Duration : </strong> You can specify the duration, in minutes, for the test.</li><br>
								<li style="font-size:16px"><strong> Total Number of Questions :</strong> You can define the number of questions you want to include in test (should not be more than 200).</li><br>			
								<li style="font-size:16px"><strong> Minimum/Maximum Cut-Off : </strong> User can set the minimum/maximum cut-off value (in percentage) for the test.</li><br> 
									<div class="hero-unit1" style="text-align: center;">
										<a href="images/dmt_cutoff.jpg" target="_blank"><img src="images/dmt_cutoff.jpg" height="300" width="400" /></a>
									</div> 
								<li style="font-size:16px"><strong> Number of Sections :</strong> You can divide the test into sections. You just need to provide the number.</li><br>
								<li style="font-size:16px"><strong> Marking Scheme : </strong> The Test Design Wizard provides two types of marking schemes.</li>
									<ul type="disc">
										<li  style="font-size:16px"><strong> Consistent : </strong> You can specify marks for the complete test , along with negative marking (if any).</li>
										<li  style="font-size:16px"><strong> Section Wise : </strong> You can specify the marking scheme for individual sections within the test. </li><br>
									</ul>
								<li style="font-size:16px"><strong> Question Source : </strong> Question Source can be either from <?php echo(CConfig::SNC_SITE_NAME);?> question bank or your personal database.</li><br>
								<li style="font-size:16px"><strong> Test Attribute : </strong> The Test Attribute section provides the user with the feasibility of rational resoning for candidate's performance.
									There are three options for displaying result to the candidates as follows.</li>
									<ul type="disc">
										<li style="font-size:16px"><strong> None :</strong>  After exam completion, candidate will only be able to see question attempted and questions unanswered.</li>
										<li style="font-size:16px"><strong> Minimal :</strong> After exam completion candidate will be able to see number of right, wrong and unanswered questions.</li>
										<li style="font-size:16px"><strong> Detailed : </strong> After exam completion candidate will be able to see detailed performance analysis in result analytics section of his login.</li><br>
									</ul>
								<li style="font-size:16px"><strong> Question Type :</strong> The "Question Type" refers to the question pattern where candidate is provided with either a single correct answer or multiple correct answers.</li><br>
								<li style="font-size:16px"><strong> Preferred Language :</strong> User can set the default language of questions through this option.</li> <br>
								<li style="font-size:16px"><strong> Translation Chioce:</strong> Using this option preferrence to opt different languages can be provided to the candidate. In case if the question is not available in the selected language, 
									the question will be displayed in the default "Preferred Language".</li><br>
									<div class="hero-unit1" style="text-align: center;">
										<a href="images/dmt_testattr.jpg" target="_blank"><img src="images/dmt_testattr.jpg" height="600" width="700" /></a>
									</div>  
							</ol>
							
					<h4 style="color:black">Custom Instructions</h4>
					<p>	This section helps you to provide instructions to the candidates about the complete test.</p><br>
						<ol>
							<li style="font-size:16px"><strong> Instruction Language :</strong> This option provides the feasibility of selecting the language in which the instructions are to be displayed.</li><br> 
							<li style="font-size:16px"><strong> Add Custom Test Instructions : </strong> This is the place where user can fill up the instructions for the test.</li><br>
								<div class="hero-unit1" style="text-align: center;">
									<a href="images/dmt_custominst.jpg" target="_blank"><img src="images/dmt_custominst.jpg" height="600" width="700" /></a>
								</div> 
					<h4 style="color:black">Test Security</h4><br>
					<p> Security is one among the most important aspects of any service. <?php echo(CConfig::SNC_SITE_NAME);?> ensures security to your tests by providing the following functionalities.</p><br>
						<ol>
							<li style="font-size:16px"><strong> Test Expiration : </strong> This field specifies that, once the test has been sheduled, then for how much time the test will be visible and valid in candidate's login. The following options are available.</li>
								<ul type="disc">
									<li style="font-size:16px"><strong> Never : </strong> The test will be visible forever in candidates login.
									<li style="font-size:16px"><strong> Hours : </strong> 6 HRS, 12 HRS.
									<li style="font-size:16px"><strong> Days : </strong>  1 Day to 7 Days.
								</ul><br>
							<li style="font-size:16px"><strong> Number of Attempts : </strong>  This field specifies that once the test has started, then how many "Test Resume" attempts are available to candidate. It has following options.</li>
								<ul type="disc">
									<li style="font-size:16px"><strong> Options : </strong>  Unlimited, 1, 2, 5, 10, 15, 20, 30, 40 & 50.</li>	
								</ul><br>
							<li style="font-size:16px"><strong> Flash Question (MCPA Security Parameter): </strong> If selected yes, the question seen in adaptive test but not answered by candidate - will be changed from question of same topic and difficulty level. 
								If you are going to choose this parameter make sure that you have sufficient questions of same difficulty level. You should have at-least double questions than the numbers you have scheduled.</li><br>
							<li style="font-size:16px"><strong> Lock Question (MCPA Security Parameter) : </strong>  If selected yes, the question answered by candidate will be locked - i.e. once answered candidate will not be able to change the answer.</li>
						</ol><br>	
							<div class="hero-unit1" style="text-align: center;">
								<a href="images/dmt_testsecurity.jpg" target="_blank"><img src="images/dmt_testsecurity.jpg" height="600" width="700" /></a>
							</div> 
					<h4 style="color:black">Section Details</h4><br>
					<p>In this tab, you have to provide the Section Name and Number of questions to be asked in that section. 
						The entry fields are auto generated based on the Number of Section you specified in Test Details tab.</p><br>
						<ol>
							<li style="font-size:16px"><strong> Section Name : </strong> Name of the section you would like to have.</li><br>
							<li style="font-size:16px"><strong> Section Questions :</strong> How many questions from total number of question you would like to allocate for this particular section.</li><br>
							<li style="font-size:16px"><strong> Section Min/Max Cut-Off :</strong> User can set the minimum/maximum cut-off value for each section of the test.</li><br>
							<li style="font-size:16px"><strong> Section Marks For Correct Answer :</strong> You can specify the marks for each section.</li><br>
							<li style="font-size:16px"><strong> Section Negetive Marking :</strong> You can also specify the negative marks for neach section</li><br>
							<li style="font-size:16px"><strong> Questions Remaining :</strong> This is auto updated field, once you will specify question to particular section, it shows how many question are remaining for allocating in next sections. Note that question should be properly distributed to make this number '0'. 
								Wizard will not go further until proper distribution is done.</li><br>
								<div class="hero-unit1" style="text-align: center;">
									<a href="images/dmt_sectiondetails.jpg" target="_blank"><img src="images/dmt_sectiondetails.jpg" height="600" width="700" /></a>
								</div> 
					<h4 style="color:black">Select Subjects</h4><br>
					<p>In this tab you have to select subject under each section. The wizard will show you all available subjects. You have to click on subject in left pane and press "Add" button, it will add that subject to right and remove it from available subject pane in left. 
						Once you added subjects, you need to distribute section questions properly in that each subjects you added. Once you distributed all questions properly the left pane shows question remaining '0'.</p><br>
						<div class="hero-unit1" style="text-align: center;">
							<a href="images/dmt_selectsubj.jpg" target="_blank"><img src="images/dmt_selectsubj.jpg" height="600" width="700" /></a>
						</div><br>
					<h4 style="color:black">Select Topics</h4><br> 
					<p>Each subject has different topics. Wizard helps you out to select what topics you want to ask in the specific subject. You specify number of questions for each topic. 
						Questions should be properly distributed under each topic, so that number of questions remaining should be 0 for that subject.<br>
						Left pane declares topic name and total question available under that topic with details of how many questions are available in each difficulty level. It specifies Topic, listing something like this.<br><br>
						<strong>Inheritance (Total: 13, E: 3, M: 10, H: 0)</strong><br>
						<strong>Classes and Object (Total: 37, E: 33, M: 4, H: 0)</strong><br>
							<div class="add"><strong> E <i class="icon-arrow-right"></i> Easy , M <i class="icon-arrow-right"></i> Moderate , H <i class="icon-arrow-right"></i> Hard</strong></div><br>
							<p>You can specify number of Easy questions, Moderate questions and Hard Questions under each topic.</p><br>
							<p>Candidate can identify the question pattern with the help of three colours. Description of question can be visible to user on "Double Click".</p>
							<ul type="disc">
							<li style="font-size:16px"><strong>Black</strong> colour implies that the question belongs to the "Normal Questions".</li>
							<li style="font-size:16px"><strong style="color:blue">Blue</strong> colour implies that the question belongs to the "Directions".</li>
							<li style="font-size:16px"><strong style="color:green">Green</strong> colour implies that the question belongs to the "Reading Comprehension(RC)".</li><br>
								<div class="hero-unit1" style="text-align: center;">
									<a href="images/dmt_select_topic.jpg" target="_blank"><img src="images/dmt_select_topic.jpg" height="600" width="700" /></a>
								</div><br>
					<h4 style="color:black">Save</h4><br>
					<p>Last tab shows you the preview of test you designed. It shows each and every details of the test you specified in each step. From this point, you can go back and change any parameter by clicking on Back Button. 
						If you click on "Finish" test will be saved under your login.</p><br>
						<div class="hero-unit1" style="text-align: center;">
							<a href="images/dmt_save.jpg" target="_blank"><img src="images/dmt_save.jpg" height="600" width="700" /></a>
						</div><br>
						<p>You will get a confirmation message after test template is saved.</p><br>
						<div class="hero-unit1" style="text-align: center;">
							<a href="images/dmt_saveconformation.jpg" target="_blank"><img src="images/dmt_saveconformation.jpg" height="600" width="700" /></a>
						</div><br>
			
							<!--	====Manage Tests====	-->			
			
					<h3 style="color:black">Manage Test</h3>
					<p>You can manage tests from Design and Manage Test <i class="icon-arrow-right"></i> Manage Test. Manage Tests page shows you the complete listing of all the tests you have designed.</p>
						<ol>
							<li style="font-size:16px"><strong>Delete Test :</strong> You can select any test by clicking on the row and then you may delete that test by pressing Delete Button provided at the top left.</li><br>
								<div class="hero-unit1" style="text-align: center;">
									<a href="images/managetest_delete.jpg" target="_blank"><img src="images/managetest_delete.jpg" height="600" width="700" /></a>
								</div><br>
							<li style="font-size:16px"><strong>Test Details :</strong> When you click on test details button, a popup window shows up showing all the properties of that test. It helps you out to review the test parameters you set.</li><br>
								<div class="hero-unit1" style="text-align: center;">
									<a href="images/managetest_details.jpg" target="_blank"><img src="images/managetest_details.jpg" height="600" width="700" /></a>
								</div><br>
							<li style="font-size:16px"><strong>Preview Test :</strong> This link will provide you an interface to attempt the test as a candidate. 
								Thus you will be able to analyze how test will go through when that test will be taken by actual candidate.</li><br>
								<div class="hero-unit1" style="text-align: center;">
									<a href="images/managetest_preview.jpg" target="_blank"><img src="images/managetest_preview.jpg" height="600" width="700" /></a>
								</div><br>
							<p>Following page will be shown when <strong style="ul">Preview Test </strong> link will be clicked.</p><br>
								<div class="hero-unit1" style="text-align: center;">
									<a href="images/managetest_previewpage1.jpg" target="_blank"><img src="images/managetest_previewpage1.jpg" height="600" width="700" /></a>
								</div><br>
							<p>It has "Test Instructions" at the very first page. The Instructions are generated automatically based on the parameters you have provided while designing the test template. You can close test by clicking on Top Right if you don't want to attempt the test. 
								As soon as you click on Start Test, test will be started. Once test is started you have to finish the test.</p><br>
								<div class="hero-unit1" style="text-align: center;">
									<a href="images/managetest_previewpage2.jpg" target="_blank"><img src="images/managetest_previewpage2.jpg" height="600" width="700" /></a>
								</div><br>
							<p>If unexpectedly web-browser window get closed or machine shuts down due to power failure, test can be resumed from same point. Using Resume Test Button.</p><br>
								<div class="hero-unit1" style="text-align: center;">
									<a href="images/managetest_resume.jpg" target="_blank"><img src="images/managetest_resume.jpg" height="600" width="700" /></a>
								</div><br>
							<p>Exam can be ended at any moment by pressing End Exam button on the upper right of window. 
								Exam will be automatically ended if candidate has consumed all time-duration (provided timer expires).</p><br>
								<div class="hero-unit1" style="text-align: center;">
									<a href="images/managetest_endexam.jpg" target="_blank"><img src="images/managetest_endexam.jpg" height="600" width="700" /></a>
								</div><br>
							<p>Test summary will be shown after exam got ended (depending on result visibility setting, set during test design).</p><br>
								<div class="hero-unit1" style="text-align: center;">
									<a href="images/managetest_result.jpg" target="_blank"><img src="images/managetest_result.jpg" height="600" width="700" /></a>
								</div><br>
						</ol>
				</section>
		
					<!--	=======================REGISTER CANDIDATE===========================	-->	
					
				<section id="rc">
					<div class="page-header">
						<h1>Register Candidate</h1>
					</div>
					
							<!--	====Upload User Details====	-->	
					
					<h4 style="color:black">Upload User Details</h4><br>
					<p>Registration option is provided in a left Menu Register Candidates<i class="icon-arrow-right"></i> Upload User Details</p>	
						<div class="hero-unit1" style="text-align: center;">
							<a href="images/register_candidates_uploaddetails.jpg" target="_blank"><img src="images/register_candidates_uploaddetails.jpg" height="600" width="700" /></a>
						</div><br>
					<p><?php echo(CConfig::SNC_SITE_NAME);?> supports two modes for candidate registration. They are explained below.</p><br>
					<ol>
						<li style="font-size:16px"><strong>Bulk Registration through upload</strong></li>
						<p>If you have the following necessary details of user, as specified below, you can upload and register candidates all in one click. You can download candidate registration template from the tab "Upload User Details". 
							Following information is required to fill the details, </p>
							<ol>
								<li style="font-size:16px">First Name</li>
								<li style="font-size:16px">Last Name</li>
								<li style="font-size:16px">Gender(0:Female & 1:Male)</li>
								<li style="font-size:16px">Date of Birth (YYYYMMDD)</li>
								<li style="font-size:16px">Contact #</li>
								<li style="font-size:16px">E-Mail</li>
								<li style="font-size:16px">City</li>
								<li style="font-size:16px">State</li>
								<li style="font-size:16px">Country<br>
								<em><strong>NOTE:</strong> In Gender Column you will have to fill "0" for Female, "1" for Male.</li></em><br>
							</ol>
							
							<h4 style="color:black"> Procedure for Candidate Registration(in bulk)</h4>
								<ul type="disc">
									<li style="font-size:16px">The downloaded file will be in "xls" (template) format, all you have to do is to fill the template and save it as "xls" file. 
										Have a look at screen shot,</li><br>
										<div class="hero-unit1" style="text-align: center;">
											<a href="images/save_user_template.jpg" target="_blank"><img src="images/save_user_template.jpg" height="600" width="700" /></a>
										</div>
											<p>Once you have prepared this sheet, all you need to do is to upload it from Upload User Details Tab using Choose File <i class="icon-arrow-right"></i>Submit after selection of file you prepared.</p><br>
									<li style="font-size:16px">After successful upload of candidates, you will see the message at the lower part of page. It will display details and errors if any.</li><br>
										<div class="hero-unit1" style="text-align: center;">
											<a href="images/save_user_bulk.jpg" target="_blank"><img src="images/save_user_bulk.jpg" height="600" width="700" /></a>
										</div>
										<p>Once you will upload the sheet, an activation email will be automatically sent to the user on their corresponding email id. They need to activate themselves in order to get registered.</p>
									<li style="font-size:16px">Once user gets registered it will be shown using Register Candidates<i class="icon-arrow-right"></i> View Registered User link.
								</ul><br>
						<li style="font-size:16px"><strong>Registration by candidates themselves using link provided by <?php echo(CConfig::SNC_SITE_NAME);?></strong></li>
						<p>In the Upload User Details Tab, you can find link on right side within a box. All you need to do is to send this link to user on email and they will register themselves.</p>
							<div class="hero-unit1" style="text-align: center;">
								<a href="images/user_upload_link.jpg" target="_blank"><img src="images/user_upload_link.jpg" height="600" width="700" /></a>
							</div>
								<p>Once you will email this link, following registration page will open once user will click on link.</p><br>
									<div class="hero-unit1" style="text-align: center;">
										<a href="images/user_reg_link.jpg" target="_blank"><img src="images/user_reg_link.jpg" height="600" width="700" /></a>
									</div>
								<p>It is simple information collection registration, page. User will have to fill required details. 
									Once registration filled and account activation email will be sent on his email address and as soon as he activates his account you should be able to view him as your registered candidate in Registered Candidate<i class="icon-arrow-right"></i> View Registered Users Menu.</p><br>
					</ol>
					
							<!--	====View Registered Users====	-->	
						
					<h4 style="color:black">Upload User Details</h4><br>
					<p>You can view all registered candidates by Register Candidates <i class="icon-arrow-right"></i>View Registered Users left menu. This information will be shown on View Registered User tab. On this tab you have option to Delete Users, Have CSV or PDF copy.<br>
						<div class="hero-unit1" style="text-align: center;">
							<a href="images/user_view_regestered.jpg" target="_blank"><img src="images/user_view_regestered.jpg" height="600" width="700" /></a>
						</div><br>
					<p>Activation Column shows that whether candidate has done email verification or not. If verification is done, status is "Activated" otherwise it is "Pending".</p>
				</section>						
					
						<!--	=======================SCHEDULE TEST===========================	-->				
			
				<section id="st">
					<div class="page-header">
						<h1>Schedule Test</h1>
					</div>
					<p>User can schedule tests designed for all the registered candidates.<br></p>
							
							<!--	====Schedule Test====	-->
					
					<h3  style="color:black">Schedule Test</h3>			
					<p>You can select this page by clicking Schedule Test <i class="icon-arrow-right"></i>Schedule Test left menu.It will display schedule test page. This page has following sections to help you out in scheduling tests.</p><br>
					<ol>
						<li style="font-size:16px"><strong>Select Test :</strong> You can select the tests from the available list with the help of this option.</li><br>
						<li style="font-size:16px"><strong>Test Details :</strong> You can view the details of the selected test from here.</li><br>
							<div class="hero-unit1" style="text-align: center;">
								<a href="images/test_detail.jpg" target="_blank"><img src="images/test_detail.jpg" height="600" width="700" /></a>
							</div><br>
						<li style="font-size:16px"><strong>Scheduled on :</strong> You can select the date on  which the test is to be scheduled.</li><br>
						<li style="font-size:16px"><strong>Register Candidate List :</strong> This block provides the list of active registered candidates, you can add or remove candidates for the scheduled test to the next block.
							 This list only shows "Active" candidates (Those who completed email verification). You can select multiple candidates in one time by pressing CTRL key and then selecting using mouse.</li>
					</ol>
						<div class="hero-unit1" style="text-align: center;">
							<a href="images/schedule.jpg" target="_blank"><img src="images/schedule.jpg" height="600" width="700" /></a>
						</div><br>
					
							<!--	====Managed Schedule Test====	-->					
					
					<h3  style="color:black">Manage Schedule Test</h3>	
					<p>This section helps you to manage previously scheduled tests.<br><br>
						<?php echo(CConfig::SNC_SITE_NAME);?> provides a unique functionality to its users. In case of low balance in your account <?php echo(CConfig::SNC_SITE_NAME);?> provides the feasibility to remove candidates from the scheduled test provided the candidate should not begin the test. </p><br>
						<div class="hero-unit1" style="text-align: center;">
							<a href="images/manage_sh_test.jpg" target="_blank"><img src="images/manage_sh_test.jpg" height="600" width="700" /></a>
						</div><br>
							
							<!--	====Monitor Active Tests====	-->
							
					<h3 style="color:black">Monitor Active Test</h3>
					<p>With the help of this option you can supervise the ongoing test. In case of any conflict (attempt to cheat) test can be terminated.<br></p>	
						<div class="hero-unit1" style="text-align: center;">
							<a href="images/monitor_test.jpg" target="_blank"><img src="images/monitor_test.jpg" height="600" width="700" /></a>
						</div><br>
							
							<!--	====View Scheduled Test====	-->
					
					<h3 style="color:black">View Scheduled Test</h3>
					<p>This section provides you details of scheduled test like - Test name, Scheduled date, Time Zone, List of Scheduled Students etc. 
						You can go to this page by Schedule Test <i class="icon-arrow-right"></i> View Schedule Tests link. It shows you records of all the tests which you have scheduled. It shows following colums, </p>
						<ol>
							<li style="font-size:16px"><strong>Test Name :</strong>  Name of test scheduled.</li><br>
							<li style="font-size:16px"><strong>Scheduled on :</strong> This is the date for which test is scheduled for candidates.</li><br>
							<li style="font-size:16px"><strong>Time Zone :</strong> Time zone.</li><br>
							<li style="font-size:16px"><strong>Schedule Created :</strong>  Date on which you have created this scheduled test.</li><br>
							<li style="font-size:16px"><strong>Candidates Scheduled :</strong>  List of candidates for whom test has been scheduled.</li><br>
							<li style="font-size:16px"><strong>Candidate Finished :</strong>  List of candidates who have finished the test.</li><br>
						</ol><br>
						<div class="hero-unit1" style="text-align: center;">
							<a href="images/view_sh_test.jpg" target="_blank"><img src="images/view_sh_test.jpg" height="600" width="700" /></a>
						</div><br>
			</section>
			
						<!--	=======================TRADE TEST PACKAGES===========================	-->								
						
				<section id="tradepackage">
					<div class="page-header">
						<h1>Trade Test Packages</h1>		
					</div>	
					
							<!--	====Trade Test Packages====	-->
					
					<h3 style="color:black">Trade Test Packages</h3>
					<p>If you are a coaching or training institute and want to sell out your test packages online, you should use this option. 
						You can reach at this option by<strong> Trade Test Packages</strong><i class="icon-arrow-right"></i> <strong>Trade Test Package </strong>left menu option. This is time &frasl; days bound system with unlimited number of tests a student &frasl; candidate can attempt. Test packages can be designed using tests which are prepared using your personal question source only. Following screen shot illustrates trading a test package. Remember that you should have already designed tests (using Test Design Wizard) to include in the Test Package. 
							All of the tests you designed using personal question source will be available here.</p>
						<div class="hero-unit1" style="text-align: center;">
							<a href="images/inst_trade_test.jpg" target="_blank"><img src="images/inst_trade_test.jpg" height="600" width="700" /></a>
						</div><br>
					<p>Following details are required to design Test Package,</p><br>
						<ol>
							<li  style="font-size:16px"><strong>Test Package Name :</strong>  Name of test package you want to assign.</li><br>
							<li  style="font-size:16px"><strong>Select Candidate :</strong>  Student's &frasl; Candidate's Email-ID for which package is to be scheduled.</li><br>
							<li  style="font-size:16px"><strong>Provisioned From :</strong>  Date from which Package will be activated.</li><br>
							<li  style="font-size:16px"><strong>Expired In (Days) :</strong> Test package expiration period. Charges are as per number of days and accordingly deducted from your account. These are the service charges which <?php echo(CConfig::SNC_SITE_NAME);?> ownes from you. You can charge your students as per your business model.</li><br>
						<p><strong>If you are an Indian organization following are the tariffs for you,</strong></p><br>
							<ul type="disc">
								<li style="font-size:16px"><strong>15 Days :</strong>  Package charges are Rs. 550 (INR) to you.</li>
								<li style="font-size:16px"><strong>30 Days :</strong>  Package charges are Rs. 1000 (INR) to you.</li>
								<li style="font-size:16px"><strong>45 Days :</strong>  Package charges are Rs. 1400 (INR) to you.</li>
								<li style="font-size:16px"><strong>60 Days :</strong> Package charges are Rs. 1750 (INR) to you.</li>
								<li style="font-size:16px"><strong>90 Days :</strong> Package charges are Rs. 2400 (INR) to you.</li>
							</ul><br>
						<p><strong>If you are a overseas organization following are the tariffs for you,</strong></p>
							<ul type="disc">
								<li style="font-size:16px"><strong>15 Days :</strong>  Package charges are $ 15 (USD) to you.</li>
								<li style="font-size:16px"><strong>30 Days :</strong>  Package charges are $ 28 (USD) to you.</li>
								<li style="font-size:16px"><strong>45 Days :</strong>  Package charges are $ 40 (USD) to you.</li>
								<li style="font-size:16px"><strong>60 Days :</strong>  Package charges are $ 50 (USD) to you.</li>
								<li style="font-size:16px"><strong>90 Days :</strong>  Package charges are $ 70 (USD) to you.</li>
							</ul><br>
							<li  style="font-size:16px"><strong>Amount Sold (Tax Inclusive) :</strong> Total Amount of test package you have charged from your student. Suppose you have selected 1 month package and want to charge Rs. 2500 &frasl;- then you need to fill 2500 as amount sold. It is optional and used to generate and email invoice to student &frasl; candidate.</li><br>
							<li  style="font-size:16px"><strong>Email Invoice :</strong>  If you check this option, an invoice with the Amount Sold will be generated and sent to the student.</li><br>
							<li  style="font-size:16px"><strong>Registered Test List :</strong> Total number of available tests which you have designed. It is the list from where you can choose the tests to include in the test package.</li><br>						
						</ol>					
						<p><strong>After filling above option you can choose the available test from left pane, and add them using Add button. You can also view Test Detail by selecting individual test and then clicking on Test Details Button.</strong></p>
							<ol>
								<li style="font-size:16px"><strong>Viewing Test Details :</strong></li><br>
									<div class="hero-unit1" style="text-align: center;">
										<a href="images/testpackage1.jpg" target="_blank"><img src="images/testpackage1.jpg" height="600" width="700" /></a>
									</div><br>
								<li style="font-size:16px"><strong>Selection of Tests :</strong></li><br>
									<div class="hero-unit1" style="text-align: center;">
										<a href="images/testpackage2.jpg" target="_blank"><img src="images/testpackage2.jpg" height="600" width="700" /></a>
									</div><br>
								<li style="font-size:16px"><strong>You can click Refresh button to Reset or Click Provision button to provision test package. After successful provisioning, you will get a confirmation message as follows :</strong></li><br>
									<div class="hero-unit1" style="text-align: center;">
										<a href="images/testpackage3.jpg" target="_blank"><img src="images/testpackage3.jpg" height="600" width="700" /></a>
									</div><br>
							</ol>
				
							<!--	====View Sold Test Packages====	-->				
				
					<h3 style="color:black">View Sold Test Packages</h3>
					<p>This option shows you all the test packages which you have scheduled. Following are the details which you can view,</p><br>
						<ol>
							<li style="font-size:16px"><strong>Candidate Name :</strong>  Name of candidate for which you have scheduled the test package.</li>
							<li style="font-size:16px"><strong>Package Name :</strong>  Name of test package.</li>
							<li style="font-size:16px"><strong>Tests in Package :</strong>  Name of all the tests included in this package.</li>
							<li style="font-size:16px"><strong>Package Create : </strong>  Package Creation Date.</li>
							<li style="font-size:16px"><strong>Assigned From :</strong>  Date from which package is assigned and student can start practicing test.</li>
							<li style="font-size:16px"><strong>Valid For :</strong>	 Period till package is valid.</li>
						</ol><br>
						<div class="hero-unit1" style="text-align: center;">
							<a href="images/testpackage4.jpg" target="_blank"><img src="images/testpackage4.jpg" height="600" width="700" /></a>
						</div><br>
				
				</section>	
						
						<!--	=======================RESULT ANALYTICS===========================	-->
			
				<section id="ra">
					<div class="page-header">
						<h1>Result Analytics</h1>
					</div>			
					<p>In this section, you can see results of all the tests given by candidates. It supports three types of analysis.</p>
				
							<!--	====Brief Result====	-->
				
					<h3 style="color:black">Brief Result</h3>
					<p>This page provides listing of the tests you have conducted with necessary information. This page is displayed using Result Analytics <i class="icon-arrow-right"></i> Brief Result and it displays following detail,</p>
						<ol>
							<li style="font-size:16px"><strong>Test Name :</strong> Name of the test you scheduled.</li><br>
							<li style="font-size:16px"><strong>Scheduled On :</strong> Date on which, you have scheduled the test.</li><br>
							<li style="font-size:16px"><strong>Completed On :</strong> Date on which, candidate has finished the test.</li><br>
							<li style="font-size:16px"><strong>Candidate Name :</strong> Name of the candidate.</li><br>
							<li style="font-size:16px"><strong>Marks Obtained :</strong> Marks obtained by candidate out of full marks are displayed in this section.</li><br>
							<li style="font-size:16px"><strong>Result/Rank :</strong> Result, whether student is pass or fail, or rank if top candidates has been chosen as selection criteria.</li><br>
							<li style="font-size:16px"><strong>Time Taken :</strong> Time taken by candidate to finish test.</li><br>
							<li style="font-size:16px"><strong>Visibility :</strong> With the help of this option you can manage the way result should be displayed to candidates.</li><br>
							<li style="font-size:16px"><strong>Activity Log :</strong> If the test gets interrupted, that particular reason is shown in this block like- power failure, browser crash, network or connectivity issue etc.</li><br>
						</ol>
							<div class="hero-unit1" style="text-align: center;">
								<a href="images/ra_briefresult.jpg" target="_blank"><img src="images/ra_briefresult.jpg" height="600" width="700" /></a>
							</div><br>	
							
							<!--	====Produce Custom Results====	-->					
					
					<h3 style="color:black">Produce Custom Results</h3>
					<p>This option helps you out to generate custom results or shortlisting of candidates as per your requirements. This feature is so powerful that you can generate one consolidated merit list even if you scheduled same test on various dates. The result generation user interface is very simple & slider based; you need to follow these simple steps to produce custom results,
					</p><br>
					
					<h4 style="color:black">Select Test</h4><br>
					<p>In this step you need to select the test for which you need to generate custom results.</p>
						<div class="hero-unit1" style="text-align: center;">
							<a href="images/custom_select_test.jpg" target="_blank"><img src="images/custom_select_test.jpg" height="600" width="700" /></a>
						</div><br>
						<p>Once you select any test from dropdown box, a (test) date selection pane will be appeared (xID is unique ID to identify test scheduled); this pane shows that - at how many different dates the selected test has been scheduled. 
							This selection pane enables the consolidated result generation. You can select multiple dates using CTRL/SHIFT KEY and then consolidated result short listing (based on cropped criteria in Step 2) for all of the candidates (who have completed test on different scheduled dates) will be produced.</p>
							<div class="hero-unit1" style="text-align: center;">
								<a href="images/result3.jpg" target="_blank"><img src="images/result3.jpg" height="600" width="700" /></a>
							</div><br>
					
					<h4 style="color:black">Range Selection</h4><br>
					<p>This step helps you to select ranges, with this you can filter out / in candidates. Following options are available,</p>
						<ol>
							<li style="font-size:16px"><strong>Marks Range :</strong>  Using this option you can select marks range using slider, as you slides (min / max) Mark Range the Percent Range and Candidate within particular marks range will be automatically adjusted.</li><br>
								<div class="hero-unit1" style="text-align: center;">
									<a href="images/result4.jpg" target="_blank"><img src="images/result4.jpg" height="600" width="700" /></a>
								</div><br>
							
							<li style="font-size:16px"><strong>Percent Range :</strong>  Using this option you can select percentage range using slider, as you slides Percent Range the Marks Range and Candidates within particular marks range will be automatically adjusted,</li><br>
								<div class="hero-unit1" style="text-align: center;">
									<a href="images/result6.jpg" target="_blank"><img src="images/result6.jpg" height="600" width="700" /></a>
								</div><br>
							<li style="font-size:16px"><strong>Section Range :</strong> After conducting the test selection of candidates can be done by two ways.</li><br>
								<ul type="disc">
									<li style="font-size:16px"><strong>Percentage Range :</strong> You can specify the minimum and maximum range(in percent) using slider for individual sections.</li>
									<li style="font-size:16px"><strong>Weightage Selection :</strong> You can also provide weightage to individual sections which can help during rank calculation.</li><br>
								<div class="hero-unit1" style="text-align: center;">
									<a href="images/result5.jpg" target="_blank"><img src="images/result5.jpg" height="600" width="700" /></a>
								</div><br>
							
							<li style="font-size:16px"><strong>Top Candidates :</strong> You can select top candidates (those who scored most in test) here, Marks and Percentage Range will be automatically changed and illustrate minimum and maximum cut off,</li><br>
								<div class="hero-unit1" style="text-align: center;">
									<a href="images/result7.jpg" target="_blank"><img src="images/result7.jpg" height="600" width="700" /></a>
								</div><br>
						</ol>
					
				<h4 style="color:black">Final List</h4><br>
				<p> As you click next you will have the (filtered) list of all candidates who fits in the criteria you selected. You can click on CSV button to save a csv copy of the selected candidates.</p>
					<div class="hero-unit1" style="text-align: center;">
						<a href="images/result8.jpg" target="_blank"><img src="images/result8.jpg" height="600" width="700" /></a>
					</div><br>

						<!--	====Test DNA Analysis====	-->	
				
				<h3 style="color:black">Test DNA Analysis</h3>
				<p>This is among the key features of <?php echo(CConfig::SNC_SITE_NAME);?>. You can find out all minute level details of candidate's performance in test. This helps a lot to evaluate the candidate and choose the right one.<br>
					In order to see the DNA Analysis, you have to select the Test, then Date, and then candidate. As you choose the test, it will show you the date selection combo box, and as you select the date, it will show you candidate selection combo, choose the candidate and his complete Test DNA analysis will be in front of you.<br>
					The DNA analysis shows candidates overall performance, performance in each section, in every subject of section and in every topic of subject. The Analysis is shown in Pie Chart and Bar Charts to easily understand the result analysis.</p><br>
					
						<div class="hero-unit1" style="text-align: center;">
							<a href="images/test_DNA_1.jpg" target="_blank"><img src="images/test_DNA_1.jpg" height="400" width="500" /></a>
						</div><br>
						
						<h5  style="color:black"> Choose Date </h5>
						<div class="hero-unit1" style="text-align: center;">
							<a href="images/test_DNA_2.jpg" target="_blank"><img src="images/test_DNA_2.jpg" height="400" width="500" /></a>
						</div><br>
					
						<h5  style="color:black"> Choose Candidate </h5>
						<div class="hero-unit1" style="text-align: center;">
							<a href="images/test_DNA_3.jpg" target="_blank"><img src="images/test_DNA_3.jpg" height="600" width="700" /></a>
						</div><br>
									
						<h5  style="color:black"> Overall Performance Overview-Graph </h5>
						<div class="hero-unit1" style="text-align: center;">
							<a href="images/test_DNA_4.jpg" target="_blank"><img src="images/test_DNA_4.jpg" height="600" width="700" /></a>
						</div><br>
									
						<h5  style="color:black"> Sectional Overview-Graph </h5>
						<div class="hero-unit1" style="text-align: center;">
							<a href="images/test_DNA_5.jpg" target="_blank"><img src="images/test_DNA_5.jpg" height="600" width="700" /></a>
						</div><br>
									
						<h5  style="color:black"> Subject Overview-Graph </h5>
						<div class="hero-unit1" style="text-align: center;">
							<a href="images/test_DNA_6.jpg" target="_blank"><img src="images/test_DNA_6.jpg" height="600" width="700" /></a>
						</div><br>
									
						<h5  style="color:black"> Performance in Subject-Graph </h5>
						<div class="hero-unit1" style="text-align: center;">
							<a href="images/test_DNA_7.jpg" target="_blank"><img src="images/test_DNA_7.jpg" height="600" width="700" /></a>
						</div><br>
									
						<h5  style="color:black"> Performance in Topic-Graph </h5>
						<div class="hero-unit1" style="text-align: center;">
							<a href="images/test_DNA_8.jpg" target="_blank"><img src="images/test_DNA_8.jpg" height="600" width="700" /></a>
						</div><br>
				
							<!--	====Result Inspection====	-->					
				
				<h3 style="color:black">Result Inspection</h3>
				<p>This feature allows you to inspect the candidate's question paper, it shows all the questions appeared in the test. Candidate's attempted which questions and what answer they have chosen, It also shows correct answer. If candidate has given wrong answer, question is shown in red, if he has given right answer, question is shown in green. 
					If he has left the question, question is shown in blue.</p><br>
					<div class="hero-unit1" style="text-align: center;">
						<a href="images/result9.jpg" target="_blank"><img src="images/result9.jpg" height="600" width="700" /></a>
					</div><br>
					
					<h5  style="color:black">Result Inspection - Reading Comprehension </h5>
					<div class="hero-unit1" style="text-align: center;">
						<a href="images/result10.jpg" target="_blank"><img src="images/result10.jpg" height="600" width="700" /></a>
					</div><br>
				</section>
			
			</div>
				</div>
					</div>
					<div class="col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
					<?php
					include_once (dirname ( __FILE__ ) . "/../../lib/footer.php");
					?>
					</div>
		
		<script type="text/javascript">

		$(".modal1").show();

		window.onload = function () {
			$(".modal1").hide();

			$(".bs-docs-sidebar").css({position:"fixed", top:"<?php echo($top_margin); ?>"});
			/*$(function() {
				//$('.bs-docs-sidebar').sticky({topSpacing:0});
			  var a = function() {
				  	var b = $(window).scrollTop();
				    var d = $(".row").offset().top;
				    var c=$(".bs-docs-sidebar");

				    if (b<d)
                    {
                        c.css({position:"fixed",top:"<?php echo($top_margin); ?>"});
                    }
                    else
                    {
                        c.css({position:"fixed",top:"0px"});
                    }
				    
				    //$("#dashboard").offset().top;
				    if(b<=$("#dashboard").offset().top)
				   	{
				    	//alert(d);
				    	$("#dashboard").show( "fade", 2000 );
				    }
				    if(b<=$("#sneekpeek").offset().top)
				   	{
				    	$("#sneekpeek").show( "fade", 2000 );
					}
				    if(b<=$("#myaccount").offset().top)
				    {
				    	$("#myaccount").show( "fade", 2000 );
					}
				    if(b<=$("#mycoordinators").offset().top)
			    	{
			    		$("#mycoordinators").show( "fade", 2000 );
					} 
				    if(b<=$("#dmt").offset().top)
			    	{
			    		$("#dmt").show( "fade", 2000 );
					} 
				    if(b<=$("#rc").offset().top)
			    	{
			    		$("#rc").show( "fade", 2000 );
					}
				    if(b<=$("#st").offset().top)
			    	{
			    		$("#st").show( "fade", 2000 );
					}  
				    if(b<=$("#tradepackage").offset().top)
			    	{
			    		$("#tradepackage").show( "fade", 2000 );
					} 
				    if(b<=$("#ra").offset().top)
			    	{
			    		$("#ra").show( "fade", 2000 );
					}  
				 };
			  	$(window).scroll(a);a();
			});*/
		}
			
		</script>
	</body>
</html>