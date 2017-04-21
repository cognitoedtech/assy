<!doctype html>
<?php 
include_once("../../lib/session_manager.php");
include('../../database/mcat_db.php');
include_once(dirname(__FILE__)."/../../lib/include_js_css.php");
include_once(dirname(__FILE__)."/../../lib/site_config.php");

$objIncludeJsCSS = new IncludeJSCSS();

$bCopiedLink = false;
$bProperURL  = true;
$test_id = NULL;
if(isset($_GET['ln']) && !empty($_GET['ln']))
{
	$bCopiedLink = true;
	$testInfoAry = explode("-", $_GET['ln']);
	
	if(preg_match('/^\d+$/', $testInfoAry[0]) != 0 && $testInfoAry[0] > 0 && count($testInfoAry) == 3 && strlen($testInfoAry[1]) == 2 && $testInfoAry[1] >= 1 && $testInfoAry[1] <= 31)
	{
		$test_id = $testInfoAry[0];
		$owner_id_hint = $testInfoAry[2];
		if(strlen($owner_id_hint) == 2)
		{
			$objDB = new CMcatDB();
			
			$isFreeTest = $objDB->IsTestPublished($test_id, $owner_id_hint);
			
			if(!$isFreeTest)
			{
				$bProperURL = false;
			}
		}
		else 
		{
			$bProperURL = false;
		}
	}
	else 
	{
		$bProperURL = false;
	}
}

$from_free = 1;
?>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="Generator" content="Mastishka Intellisys Private Limited">
<meta name="Author" content="Mastishka Intellisys Private Limited">
<meta name="Keywords" content="">
<meta name="Description" content="">
<title><?php echo(CConfig::SNC_SITE_NAME);?> : Free for Students</title>
<script type="text/javascript">
var imageUpArrowIncludeBasePath = "<?php echo(CSiteConfig::ROOT_URL);?>";
</script>
<link rel="shortcut icon" href="<?php echo(CSiteConfig::ROOT_URL);?>/favicon.ico?v=1.1">
<?php 
$objIncludeJsCSS->CommonIncludeCSS(CSiteConfig::ROOT_URL."/");
$objIncludeJsCSS->IncludeMipcatCSS(CSiteConfig::ROOT_URL."/");
$objIncludeJsCSS->Include3DCornerRibbonsCSS(CSiteConfig::ROOT_URL."/");
$objIncludeJsCSS->IncludeFuelUXCSS(CSiteConfig::ROOT_URL."/");
$objIncludeJsCSS->CommonIncludeJS(CSiteConfig::ROOT_URL."/");
$objIncludeJsCSS->IncludeScrollUpJS(CSiteConfig::ROOT_URL."/");
$objIncludeJsCSS->IncludeAngularMinJS(CSiteConfig::ROOT_URL."/");
$objIncludeJsCSS->IncludeUnderscoreMinJS(CSiteConfig::ROOT_URL."/");
$objIncludeJsCSS->IncludeTaggedInfiniteScrollJS(CSiteConfig::ROOT_URL."/");
$objIncludeJsCSS->IncludeJqueryRatyJS(CSiteConfig::ROOT_URL."/");
$objIncludeJsCSS->IncludeJqueryFormJS(CSiteConfig::ROOT_URL."/");
$objIncludeJsCSS->IncludeJqueryValidateMinJS(CSiteConfig::ROOT_URL."/");
$objIncludeJsCSS->IncludeMetroNotificationJS(CSiteConfig::ROOT_URL."/");
?>
<style type="text/css">
	#overlay { position: fixed; left: 0px; top: 0px; width: 100%; height: 100%; z-index: 100; background-color:white;}
	
	.modal1 {
			display:    none;
			position:   fixed;
			z-index:    1000;
			top:        50%;
			left:       50%;
			height:     100%;
			width:      100%;
		}
</style>
</head>
<body ng-app="Demo" ng-cloak>
	<?php 
	include_once(dirname(__FILE__)."/../../lib/header.php");
	?>
	<div id="overlay" style="display:none;">
		<iframe id="overlay_frame" src="#" width="100%" height="100%"></iframe>
	</div>
	<div style="margin-top: -30px; position: fixed;cursor:pointer;">
		<img src="<?php echo(CSiteConfig::ROOT_URL);?>/images/request_demo_corner.png" onclick="LaunchRequestedModal();"/>
	</div>
	<div style="margin-top: -18px; right: 10px; position: fixed;">
		<div class="ribbon ribbon-red" style="cursor:pointer;" onclick="LaunchFeedbackdModal();">
			<div class="banner">
				<div class="text">Your Feedback!</div>
			</div>
		</div>
	</div>
	<div id="scrollUp"></div>
	<div class="container" style="margin-top: 80px">
		<div class="fuelux modal1">
			<div class="preloader"><i></i><i></i><i></i><i></i></div>
		</div>
		<div id="demoRequest" class="modal">
  			<div class="modal-dialog">
    			<div class="modal-content">
    				<form class="form-horizontal" id="REQUESTFORM" name="REQUESTFORM" action="core/ajax/ajax_demo_req_exec.php"  method="POST">
	      				<div class="modal-header">
	       					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	        				<h4 class="modal-title">Request <?php echo(CConfig::SNC_SITE_NAME);?> Demo</h4>
	      				</div>
	      				<div class="modal-body">
	      					<div id="demo_form_content">
								<div class="form-group">
							    	<label for="NAME" class="col-lg-4 col-md-4 col-sm-4 control-label">Name<span style='color: red;'>*</span> :</label>
							    	<div class="col-lg-6 col-md-6 col-sm-6">
							    		<input class="form-control" id="NAME" name="NAME" type="text" />
							    	</div>
							    </div>
							    <div class="form-group">
							    	<label for="EMAIL" class="col-lg-4 col-md-4 col-sm-4 control-label">Your Email<span style='color: red;'>*</span> :</label>
							    	<div class="col-lg-6 col-md-6 col-sm-6">
							    		<input class="form-control" id="EMAIL" name="EMAIL" type="text" />
							    	</div>
							    </div>
							    <div class="form-group">
							    	<label for="CONTACT" class="col-lg-4 col-md-4 col-sm-4 control-label">Contact#<span style='color: red;'>*</span> :</label>
							    	<div class="col-lg-6 col-md-6 col-sm-6">
							    		<input class="form-control" id="CONTACT" name="CONTACT" type="text" />
							    	</div>
							    </div>
							    <div class="form-group">
							      <label for="ORG_TYPE" class="col-lg-4 col-md-4 col-sm-4 control-label">Organization Type<span style='color: red;'>*</span> :</label>
							      <div class="col-lg-6 col-md-6 col-sm-6">
							        <select class="form-control" name="ORG_TYPE" id="ORG_TYPE">
							        	<option value="">--Select Organization--</option>
						 				<?php 
										foreach(CConfig::$ORG_TYPE_ARY as $org_type_id=>$org_type_name)
											printf("<option value='%s'>%s</option>", $org_type_name, $org_type_name);
										?>
							        </select>
							      </div>
							    </div>
							    <div class="form-group" id="OTHER_ORG_DIV" style="display:none;">
									<label class="col-lg-4 col-md-4 col-sm-4 control-label"></label>
									<div class="col-lg-6 col-md-6 col-sm-6">
			   							<input class="form-control" name="OTHER_ORG" id="OTHER_ORG" placeholder="Please Specify Other Here" type="text"/>
									</div>
								 </div>
							    <div class="form-group">
							    	<label for="ORG_NAME" class="col-lg-4 col-md-4 col-sm-4 control-label">Organization Name<span style='color: red;'>*</span> :</label>
							    	<div class="col-lg-6 col-md-6 col-sm-6">
							    		<input class="form-control" id="ORG_NAME" name="ORG_NAME" type="text" />
							    	</div>
							    </div>
							    <div class="form-group">
							      <label for="USAGE" class="col-lg-4 col-md-4 col-sm-4 control-label">Monthly Tests<span style='color: red;'>*</span> :</label>
							      <div class="col-lg-6 col-md-6 col-sm-6">
							        <select class="form-control" name="USAGE" id="USAGE">
							        	<option value="" >--Select Usage--</option>
										<option value="Less than 500" >Less than 500</option>
										<option value="501 - 1000" >501 - 1000</option>
										<option value="1,001 - 5,000" >1,001 - 5,000</option>
										<option value="5,001 - 10,000" >5,001 - 10,000</option>
										<option value="10,000 and more" >10,000 and more</option>
							        </select>
							      </div>
							    </div>
							    <div class="form-group">
							    	<label for="SUBJECT" class="col-lg-4 col-md-4 col-sm-4 control-label">Subject :</label>
							    	<div class="col-lg-6 col-md-6 col-sm-6">
							    		<input class="form-control" id="SUBJECT" name="SUBJECT" type="text"  value='Request for <?php echo(CConfig::SNC_SITE_NAME);?>.com Demo !' readonly/>
							    	</div>
							    </div>
							    <div class="form-group">
							      <label for="MESSAGE" class="col-lg-4 col-md-4 col-sm-4 control-label">Message<span style='color: red;'>*</span> :</label>
							      <div class="col-lg-6 col-md-6 col-sm-6">
							        <textarea class="form-control" rows="3" id="MESSAGE" name="MESSAGE"></textarea>
							      </div>
							    </div>
							    <div class="form-group">
							    	<label for="VERIF_CODE" class="col-lg-4 col-md-4 col-sm-4 control-label">Verify Text<span style='color: red;'>*</span> :</label>
							    	<div class="col-lg-4 col-md-4 col-sm-4">
							    		<input class="form-control" id="VERIF_CODE" name="VERIF_CODE" type="text" />
							    	</div>
							    	<div class="col-lg-2 col-md-2 col-sm-2" style="position:relative;top:7px;">
							    		<img id="captcha_img_demo" src="">
							    	</div>
							    </div>
							    <input type="hidden" name="demo_request", value="1" />
						    </div>
						    <div id="demo_response" style="display:none;">
							</div>
	      				</div>
	      				<div class="modal-footer">
		    				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		    				<button type="submit" id="demo_submit_btn" class="btn btn-primary">Send Request</button>
	      				</div>
	      			</form>
    			</div>
  			</div>
		</div>
		
		<div id="user_feedback" class="modal">
  			<div class="modal-dialog">
    			<div class="modal-content">
    				<form class="form-horizontal" id="form_for_feedback" name="form_for_feedback" action="ajax/ajax_free_requests.php"  method="POST">
	      				<div class="modal-header">
	       					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	        				<h4 class="modal-title"><?php echo(CConfig::SNC_SITE_NAME);?> Feedback Form</h4>
	      				</div>
	      				<div class="modal-body">
	      					<div id="feedback_form_content">
								<div class="form-group">
							    	<label for="FEEDBACK_NAME" class="col-lg-4 col-md-4 col-sm-4 control-label">Name<span style='color: red;'>*</span> :</label>
							    	<div class="col-lg-6 col-md-6 col-sm-6">
							    		<input class="form-control" id="FEEDBACK_NAME" name="FEEDBACK_NAME" type="text" />
							    	</div>
							    </div>
							    <div class="form-group">
							    	<label for="FEEDBACK_EMAIL" class="col-lg-4 col-md-4 col-sm-4 control-label">Your Email<span style='color: red;'>*</span> :</label>
							    	<div class="col-lg-6 col-md-6 col-sm-6">
							    		<input class="form-control" id="FEEDBACK_EMAIL" name="FEEDBACK_EMAIL" type="text" />
							    	</div>
							    </div>
							    <div class="form-group">
							      <label for="FEEDBACK_MESSAGE" class="col-lg-4 col-md-4 col-sm-4 control-label">Message<span style='color: red;'>*</span> :</label>
							      <div class="col-lg-6 col-md-6 col-sm-6">
							        <textarea class="form-control" rows="3" id="FEEDBACK_MESSAGE" name="FEEDBACK_MESSAGE"></textarea>
							      </div>
							    </div>
							    <div class="form-group">
							    	<label for="FEEDBACK_VERIF_CODE" class="col-lg-4 col-md-4 col-sm-4 control-label">Verify Text<span style='color: red;'>*</span> :</label>
							    	<div class="col-lg-4 col-md-4 col-sm-4">
							    		<input class="form-control" id="FEEDBACK_VERIF_CODE" name="FEEDBACK_VERIF_CODE" type="text" />
							    	</div>
							    	<div class="col-lg-2 col-md-2 col-sm-2" style="position:relative;top:7px;">
							    		<img id="captcha_img_feedback" src="">
							    	</div>
							    </div>
							    <input type="hidden" value="1" name="feedback_by_user"/>
						    </div>
						    <div id="feedback_response" style="display:none;">
							</div>
	      				</div>
	      				<div class="modal-footer">
		    				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		    				<button type="submit" id="feedback_submit_btn" class="btn btn-primary">Send Feedback</button>
	      				</div>
	      			</form>
    			</div>
  			</div>
		</div>
	
		<div class="row">
			<div class="col-sm-8 col-md-8 col-lg-8 col-sm-offset-2 col-md-offset-2 col-lg-offset-2">
				<div class="drop-shadow lifted">
					<div class="row">
						<div class="col-sm-9 col-md-9 col-lg-9 col-sm-offset-3 col-md-offset-3 col-lg-offset-3">
							<p>
								<i><span style="color: CadetBlue;">Free Exam Prepration
										Market Place !</span></i>
							</p>
						</div>
					</div>
					<form id="search_form" action="<?php echo(CSiteConfig::FREE_ROOT_URL);?>" method="post">
						<div class="row">
							<div class="col-sm-8 col-md-8 col-lg-8">
								<input class="form-control" type="text" name="search_text"
									placeholder="Search Free Tests / Quizzes / Assessments" value="<?php echo(trim($_POST['search_text']));?>" />
							</div>
							<div class="col-lg-4 col-md-4 col-sm-4">
								<select name="search_category" class="form-control">
									<option value="keywords" <?php echo(($_POST['search_category'] == "keywords")?"selected='selected'":"");?>>by Keywords</option>
									<option value="test_name" <?php echo(($_POST['search_category'] == "test_name")?"selected='selected'":"");?>>by Test Name</option>
									<option value="inst_name" <?php echo(($_POST['search_category'] == "inst_name")?"selected='selected'":"");?>>by Organization Published</option>
								</select>
							</div>
						</div>
						<br />
						<div class="row">
							<div class="col-lg-2 col-md-2 col-sm-2 col-sm-offset-2 col-md-offset-2 col-lg-offset-2">
								<button type="submit" class="btn btn-primary">
									<b><span class="glyphicon glyphicon-search"></span> Search</b>
								</button>
							</div>
							<div class="col-lg-2 col-md-2 col-sm-2">
								<button type="button" class="btn btn-primary" onclick="window.location=window.location">
									<b><span class="glyphicon glyphicon-list"></span> Explore All</b>
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>

		<!-- Search Results -->
		<div ng-controller="InfiniteScrollDemoController">
	    	<div tagged-infinite-scroll="getMore()" tagged-infinite-scroll-disabled="!enabled || paginating" tagged-infinite-scroll-distance="distance">
	    		<div class="items" ng-class="{ paginating: paginating }">
	        		<div ng-repeat="item in items">
						<div class="row">
							<div class="col-lg-10 col-md-10 col-sm-10 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
								<div class="panel panel-primary">
									<div class="panel-heading">
										<h3 class="panel-title">
											<span class="glyphicon glyphicon-pencil"></span> {{ item.test_name }}
										</h3>
									</div>
									<div class="panel-body">
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-3 ezee-rborder">
												<div class="drop-shadow lifted">
													<img alt="..." ng-src='<?php echo(CSiteConfig::ROOT_URL);?>/test/lib/print_image.php?org_logo_img={{ item.org_id }}' style='width: <?php echo(CConfig::OL_WIDTH);?>px; height: <?php echo(CConfig::OL_HEIGHT);?>px;' />
												</div>
											</div>
											<div class="col-lg-6 col-md-6 col-sm-6">
												<div class="row ezee-bborder">
													<div class="col-lg-12 col-md-12 col-sm-12">
														<p>Description: {{ item.description }}</p>
													</div>
												</div>
												<div class="row">
													<div class="col-lg-12 col-md-12 col-sm-12" style="word-wrap:break-word;">
														<p>Keywords: {{ item.keywords }}</p>
													</div>
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-3 text-center ezee-lborder">
												<div class="row">
													<div class="col-lg-12 col-md-12 col-sm-12">
														<a id='{{item.test_id}}_a' href="" data="{{item.test_id}}" ng-click='ShowOverlay(item.test_id,"st_x")' class="btn btn-primary">Start Test !</a>
													</div>
												</div>
												<div class="row">
													<div class="col-lg-12 col-md-12 col-sm-12" style="padding-left:50px;">
														<br />
														<div is_rated="false" name='{{item.test_id}}_star' data-score='{{item.rating}}' id='{{item.test_id}}_star' class="star"></div>
														<br />
														<br />
														<br />
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		var demo = angular.module('Demo', ['tagged.directives.infiniteScroll']);
		var limit_start_value = 0;
		function InfiniteScrollDemoController($scope, $timeout) {
		  $scope.items = [];
		  $scope.distance = 0;
		  $scope.paginating = false;
		  $scope.enabled = true;
	
		  // This is called each time the infinite scroll directive needs to get more items.
		  // This dummy implementation simply adds fake items to `$scope.items` and limits
		  // itself to 50 items.
		  $scope.getMore = function() {
		    if (true === $scope.paginating) return;
	
		    var timeout = ($scope.items.length ? 1000 : 0);
		    $scope.paginating = true;
	
		    $.ajax({
				url: 'ajax/ajax_get_search_results.php',
				type: 'POST',
				data: {'search_text' : '<?php echo(trim($_POST['search_text']));?>', 'search_category' : '<?php echo($_POST['search_category']);?>', 'limit_start_value' : limit_start_value},
				dataType: 'json',
				async: false,
				success: function(data) {
					$.each(data, function(key, value){
						if(key == "next_limit_start_value")
						{
							limit_start_value = value;
						}
						else
						{
							$scope.items.push({
								test_name: value['test_name'],
								description: value['description'],
								keywords: value['keywords'],
								org_name: value['org_name'],
								org_id: value['org_id'],
								test_id: value['test_id'],
								rating: value['rating']
						    });
						}
					});
					$scope.paginating = false;
			
					if(limit_start_value == 0)
					{
						$("#empty_search").show();
					}
					else
					{
						$("#empty_search").hide();
					}
	
					setTimeout(function(){
					    $scope.$apply(function() {
					        // jQuery stuff here
	
					    	$("div[name$='_star']").each(function(){
						    	if($(this).attr("is_rated") === "false")
						    	{
						    		$(this).raty({
						    			readOnly  : true,
									    half      : true,
									    size      : 24,
									    score	  : $(this).attr("data-score"),
									    starHalf  : '<?php echo(CSiteConfig::ROOT_URL);?>/3rd_party/raty/demo/img/star-half-big.png',
									    starOff   : '<?php echo(CSiteConfig::ROOT_URL);?>/3rd_party/raty/demo/img/star-off-big.png',
									    starOn    : '<?php echo(CSiteConfig::ROOT_URL);?>/3rd_party/raty/demo/img/star-on-big.png'
									});
						    		$(this).attr("is_rated","true");
							    }
							});
					    }), 3000
					 });
				}
			});
			
		  };
	
		  $scope.getMore();
	
		  $scope.reset = function() {
		    $scope.items = [];
		    $scope.getMore();
		  };
	
		  $scope.ShowOverlay = function(test_id, div_id) {
			  var current_date = new Date();
			    var time_zone = -current_date.getTimezoneOffset() / 60;
	
			    var url = "<?php echo(CSiteConfig::ROOT_URL);?>/test/test.php?test_id="+test_id+"&tschd_id=<?php echo(CConfig::FEUC_TEST_SCHEDULE_ID);?>";
				var height	  = $(window).height();
				$("#overlay_frame").attr("src",url+"&time_zone="+time_zone+"&height="+height).ready(function(){
					$("#overlay").show(800);
					$("body").css("overflow", "hidden");
					$("#header").hide();
				});
		  };
		}
	
		// Here "addEventListener" is for standards-compliant web browsers and "attachEvent" is for IE Browsers.
		var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
		var eventer = window[eventMethod];
	
		// Now...
		// if 
		//    "attachEvent", then we need to select "onmessage" as the event. 
		// if 
		// 	  "addEventListener", then we need to select "message" as the event
		
		var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";
		
		//Listen to message from child IFrame window
		eventer(messageEvent, function (e) 
		{		
			if (e.origin == '<?php echo(CSiteConfig::ROOT_URL);?>') 
			{
			 	if(e.data == 'RemoveTest')
				{
				  RemoveTest();
				}
				
				if(e.data == 'HideOverlay')
				{
				  HideOverlay();
				}			
			// Do whatever you want to do with the data got from IFrame in Parent form.
			}
		   // Do whatever you want to do with the data got from IFrame in Parent form.
		}, false);    
		
		
		function ShowOverlay(test_id, div_id)
		{
			var current_date = new Date();
		    var time_zone = -current_date.getTimezoneOffset() / 60;
		    
		    var url = "<?php echo(CSiteConfig::ROOT_URL);?>/test/test.php?test_id="+test_id+"&tschd_id=<?php echo(CConfig::FEUC_TEST_SCHEDULE_ID);?>";
			var height	  = $(window).height();
			$("#overlay_frame").attr("src",url+"&time_zone="+time_zone+"&height="+height).ready(function(){
				$("#overlay").show(800);
				$("body").css("overflow", "hidden");
				$("#header").hide();
			});
			
			RemoveTest.div_id = div_id;
		}
		
		function HideOverlay()
		{
			$("#overlay").hide(500);
			$("body").css("overflow", "auto");
			$("#header").show();
		}
		
		function RemoveTest()
		{
			//window.if_platform.TestOver(RemoveTest.div_id);
		}
	
		/*$("#search_form").validate({
			errorPlacement: function(error, element) {
				$(element).css("border", "1px solid red");
			},
	    	rules: {
	        	search_text: {
	            	required:true,
	        	}
	    	},
	    	messages: {
	    		search_text: {	
	    			required:	"<span style='color:red'>* Please enter the text</span>"
	        	}
		    }
		});*/

		$("#ORG_TYPE").change(function(){
			if($("#ORG_TYPE").val() == "<?php echo(CConfig::$ORG_TYPE_ARY[CConfig::OT_OTHER]);?>")
			{
				$("#OTHER_ORG_DIV").show();
			}
			else
			{
				$("#OTHER_ORG_DIV").hide();
			}
		});

		function showResponse(responseText, statusText, xhr, form)
		{
			$(".modal1").hide();

			if($(form).attr("id") == "REQUESTFORM")
			{
				$("#demo_form_content").hide();
				$("#demo_response").html(responseText);
				$("#demo_response").show();
				$("#demo_submit_btn").hide();
				$("#demoRequest").modal();	 
			}
			else if($(form).attr("id") == "form_for_feedback")
			{
				$("#feedback_form_content").hide();
				$("#feedback_response").html(responseText);
				$("#feedback_response").show();
				$("#feedback_submit_btn").hide();
				$('#user_feedback').modal();
			}
		}

		var options = { 
	       	 	//target:        '',   // target element(s) to be updated with server response 
	       		// beforeSubmit:  showRequest,  // pre-submit callback 
	      	 	 success:       showResponse,  // post-submit callback 
	 
	        	// other available options: 
	        	url:      'ajax/ajax_free_requests.php',         // override for form's 'action' attribute 
	        	type:      'POST',       // 'get' or 'post', override for form's 'method' attribute 
	        	//dataType:  null        // 'xml', 'script', or 'json' (expected server response type) 
	        	clearForm: true        // clear all form fields after successful submit 
	        	//resetForm: true        // reset the form after successful submit 
	 
	        	// $.ajax options can be used here too, for example: 
	        	//timeout:   3000 
	    	};
		
		$(document).ready(function() {
			$("#REQUESTFORM").validate({
	    		rules: {
	        		NAME: {
	            		required:true,
	       		 		minlength: 2
	        		},
	            	EMAIL: {
	            		required: true,
	            		email: true
	        		},
	        		CONTACT:{
	        			required:true,
	           	 		number: true,
	           		},
	        		ORG_TYPE:{
	        			required:true,
	               	},
	               	OTHER_ORG:{
	        			required:true,
	               	},
	               	ORG_NAME:{
	               		required:true,
	               	},	
	            	USAGE:{
	            		required:true,
	           		 },
	            	MESSAGE:{
	                	required:true,
	             	},
	            	VERIF_CODE:"required"
	    		},
	    		messages: {
	    			NAME: {	
	    				required:	"<span style='color:red'>* Please enter your name</span>",
	    				minlength:	"<span style='color:red'>* Minimum length of name should be 2</span>"
	        		},
	        		EMAIL:{
						required:	"<span style='color:red'>* Email id is required</span>",
						email:		"<span style='color:red'>* Please enter a valid email address</span"
					},	
					CONTACT:{
						required:	"<span style='color:red;'>* Please enter your contact no.</span>",
	        	 		number:		"<span style='color:red;'>* contact number must contain digits only</span>"
					},
					ORG_TYPE:{
						required:	"<span style='color:red;'>* Please select organization Type</span>",
		            },
		            OTHER_ORG:{
						required:	"<span style='color: red;'>* Please specify the other organization type</span>",
		            },
		            ORG_NAME:{
						required:	"<span style='color:red;'>* Please enter organization name</span>",
	               	},    
	               	USAGE:{
						required:	"<span style='color:red;'>* Please select your monthly usage</span>",
					},
					MESSAGE:{
						 required:	"<span style='color:red;'>* Please provide a message</span>",
					},
					VERIF_CODE:			"<span style='color:red;'>* Please enter the code shown in image</span>"
		    	},
	    		submitHandler: function(form) {
	    			$('#demoRequest').modal('hide');
	    			$(".modal1").show();
	    			$('#REQUESTFORM').ajaxSubmit(options);
	    		}
			});

			$("#form_for_feedback").validate({
	    		rules: {
	    			FEEDBACK_NAME: {
	            		required:true,
	       		 		minlength: 2
	        		},
	        		FEEDBACK_EMAIL: {
	            		required: true,
	            		email: true
	        		},
		           	FEEDBACK_MESSAGE:{
	                	required:true,
	             	},
	             	FEEDBACK_VERIF_CODE:"required"
	    		},
	    		messages: {
	    			FEEDBACK_NAME: {	
	    				required:	"<span style='color:red'>* Please enter your name</span>",
	    				minlength:	"<span style='color:red'>* Minimum length of name should be 2</span>"
	        		},
	        		FEEDBACK_EMAIL:{
						required:	"<span style='color:red'>* Email id is required</span>",
						email:		"<span style='color:red'>* Please enter a valid email address</span"
					},
					FEEDBACK_MESSAGE:{
						 required:	"<span style='color:red;'>* Please provide a message</span>",
					},
					FEEDBACK_VERIF_CODE:		"<span style='color:red;'>* Please enter the code shown in image</span>"
		    	},
	    		submitHandler: function(form) {
	    			$('#user_feedback').modal('hide');
	    			$(".modal1").show();
	    			$('#form_for_feedback').ajaxSubmit(options);
	    		}
			});
		});

	    function LaunchRequestedModal()
	    {
	    	$("#demo_response").hide();
			$("#demo_form_content").show();
			$("#demo_submit_btn").show();
			$("#REQUESTFORM").validate().resetForm();
			$('#captcha_img_demo').attr('src','lib/captcha/captcha.php?r=' + Math.random());
			$('#demoRequest').modal();
		}

	    function LaunchFeedbackdModal()
	    {
	    	$("#feedback_response").hide();
			$("#feedback_form_content").show();
			$("#feedback_submit_btn").show();
			$('#form_for_feedback').validate().resetForm();
			$('#captcha_img_feedback').attr('src','lib/captcha/captcha.php?r=' + Math.random());
			$('#user_feedback').modal();
		}

	    <?php 
	    if($bCopiedLink && $bProperURL)
	    {
	    ?>
	    ShowOverlay(<?php echo($test_id);?>,"st_x");
	    <?php 
	    }
	    else if($bCopiedLink && !$bProperURL)
	    {
	    ?>
	    $.Notify({
			 caption: "Wrong Test URL",
			 content: "Please check the URL, we don't have any test that can be identified by <?php echo($_GET['ln']);?>!",
			 style: {background: 'green', color: '#fff'}, 
			 timeout: 5000
			 });
		<?php 
		}
		?>
	</script>
</body>
</html>
