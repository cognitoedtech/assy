<!doctype html>
<?php
include_once (dirname ( __FILE__ ) . "/../lib/include_js_css.php");
include_once (dirname ( __FILE__ ) . "/../lib/session_manager.php");
include_once (dirname ( __FILE__ ) . "/../lib/site_config.php");
include_once (dirname ( __FILE__ ) . "/../database/mcat_db.php");
include_once (dirname ( __FILE__ ) . "/../lib/billing.php");

// - - - - - - - - - - - - - - - - -
// On Session Expire Load ROOT_URL
// - - - - - - - - - - - - - - - - -
CSessionManager::OnSessionExpire ();
// - - - - - - - - - - - - - - - - -

$objDB = new CMcatDB ();
$objBilling = new CBilling ();

$user_id = CSessionManager::Get ( CSessionManager::STR_USER_ID );

$user_type = CSessionManager::Get ( CSessionManager::INT_USER_TYPE );

$objIncludeJsCSS = new IncludeJSCSS ();

$menu_id = CSiteConfig::UAMM_DASHBOARD;
?>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="Generator" content="Mastishka Intellisys Private Limited">
<meta name="Author" content="Mastishka Intellisys Private Limited">
<meta name="Keywords" content="">
<meta name="Description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo(CConfig::SNC_SITE_NAME);?>: Dashboard</title>
<style type="text/css">
.column {
	width: 300px;
	float: left;
	padding-bottom: 100px;
}

.portlet {
	margin: 0 1em 1em 0;
}

.portlet-header {
	margin: 0.3em;
	padding-bottom: 4px;
	padding-left: 0.2em;
}

.portlet-header .ui-icon {
	float: right;
}

.portlet-content {
	padding: 0.4em;
}

.ui-sortable-placeholder {
	border: 1px dotted black;
	visibility: visible !important;
	height: 50px !important;
}

.ui-sortable-placeholder * {
	visibility: hidden;
}
</style>
<?php
$objIncludeJsCSS->CommonIncludeCSS ( "../" );
$objIncludeJsCSS->IncludeIconFontCSS ( "../" );
$objIncludeJsCSS->IncludeFuelUXCSS ( "../" );
$objIncludeJsCSS->CommonIncludeJS ("../");

if($user_type != CConfig::UT_INDIVIDAL)
{
	$objIncludeJsCSS->IncludeMetroCalenderJS ("../");
	$objIncludeJsCSS->IncludeMetroDatepickerJS("../");
	$objIncludeJsCSS->IncludeCanvasMinJS ("../");
}
else 
{
	$objIncludeJsCSS->IncludeMetroAccordionJS("../");
}
?>
<style type="text/css">
	#overlay { position: fixed; left: 0px; top: 0px; width: 100%; height: 100%; z-index: 100; background-color:white;}
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
<body>
	
	<?php 
	if($user_type == CConfig::UT_INDIVIDAL)
	{
	?>
	<div id="overlay" style="display:none">
		<iframe id="overlay_frame" src="#" width="100%" height="100%"></iframe>
	</div>
	<?php 
	}
	?>

	<?php
	include_once (dirname ( __FILE__ ) . "/../lib/header.php");
	?>

	<!-- --------------------------------------------------------------- -->
	<br />
	<br />
	<br />
	<div class='row-fluid'>
		<div class="col-lg-3 col-md-3 col-sm-3">
			<?php
			include_once (dirname ( __FILE__ ) . "/../lib/sidebar.php");
			?>
		</div>
		<div class="col-lg-9 col-md-9 col-sm-9" style="border-left: 1px solid #ddd; border-top: 1px solid #ddd;">
			<div class="fuelux modal1">
				<div class="preloader"><i></i><i></i><i></i><i></i></div>
			</div>
			<br />
			<?php 
			if($user_type != CConfig::UT_INDIVIDAL)
			{
			?>
			<fieldset>
				<legend><h3>Select Date Range</h3></legend>
				<div class="row fluid">
					<div style="text-align: center;" id="error_placement"></div>
				</div>
				<div class="row fluid">
					<div class="col-lg-3 col-md-3 col-sm-3 col-lg-offset-1 col-md-offset-1 col-sm-offset-1">
						<label for="test_id"><b style="color:darkgreen;">Select Test:</b></label>
						<select class="form-control input-sm" id="test_id" name="test_id">
						<?php
							$objDB->PrepareScheduledTestCombo($user_id);
						?>
						</select>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-3">
						<label for="datepicker1_val"><b style="color:darkgreen;">From:</b></label>
						<div class="metro">
							<div class="input-control text" id="datepicker1">
				    			<input id="datepicker1_val" type="text">
				    			<button class="btn-date" onclick="return false;"></button>
				    		</div>
						</div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-3">
						<label for="datepicker2_val"><b style="color:darkgreen;">To:</b></label>
						<div class="metro">
							<div class="input-control text" id="datepicker2">
				    			<input id="datepicker2_val" type="text">
				    			<button class="btn-date" onclick="return false;"></button>
				    		</div>
						</div>
					</div>
					<div class="col-lg-2 col-md-2 col-sm-2">
						<input type="button" style="margin-top: 25px;" id="apply_btn" class="btn btn-primary btn-sm" onclick="GetScheduledTestAnalytics();"  value="Apply!"/>
					</div>
				</div>
				<div class="row fluid">
					<div class="col-lg-10 col-md-10 col-sm-10  col-lg-offset-1 col-md-offset-1 col-sm-offset-1">
						<br />
						<div id="stats-chart" style="height:400px;"></div>
					</div>
				</div>
			</fieldset>
			<?php 
			}
			else 
			{
			?>
			<div class="row fluid">
				<div class="metro">
					<div class="accordion with-marker col-lg-4 col-md-4 col-sm-4" id="accordion" data-role="accordion">
						<div class="accordion-frame">
							<a class="heading bg-lightBlue fg-white active" style='font-size: 15px;' href='#'><b>Scheduled Tests</b></a>
							<div class="content">
								<?php 
								$objDB->PopultateScheduledTest($user_id);
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
			}
			?>
			<?php
			include_once (dirname ( __FILE__ ) . "/../lib/footer.php");
			?>
		</div>
	</div>
	
	<script type='text/javascript'>
		<?php 
		if($user_type != CConfig::UT_INDIVIDAL)
		{
		?>
		var lastMonthDate = new Date(); 
		lastMonthDate.setMonth(lastMonthDate.getMonth() - 1); 

		var from_date = "";
		var to_date   = "";
		$("#datepicker1").datepicker({
			 date: lastMonthDate,
			format: "dd mmmm yyyy"
		});
		
		$("#datepicker2").datepicker({
			date: new Date(),
			format: "dd mmmm yyyy"
		});
		
		$(document).ready(function(){
			GetScheduledTestAnalytics();
		});

		function GetScheduledTestAnalytics()
		{
			from_date = (new Date($("#datepicker1_val").val())).format("yyyy-mm-dd");
			to_date = (new Date($("#datepicker2_val").val())).format("yyyy-mm-dd");

			if((new Date(from_date) > new Date()) || (new Date(to_date) > new Date()))
			{
				$("#error_placement").html("<p style='color: red;'>* Date range should not be in future!</p>");
			}
			else if(new Date(from_date) > new Date(to_date))
			{
				$("#error_placement").html("<p style='color: red;'>* From date should be later than to date!</p>");
			}
			else {
				$("#error_placement").html("");
				$(".modal1").show();
				$.post("ajax/ajax_scheduled_test_analytics.php",{"from_date" : from_date, "to_date" : to_date, "test_id": $("#test_id").val()}, function(responseData){
					var chart_title = "Scheduled Test Analytics";
					if(responseData == null || responseData == "")
					{
						chart_title = "No tests scheduled in selected date range!";
					}
					RenderChart(responseData, chart_title);
					$(".modal1").hide();
				},"json");
			}
		}

		function RenderChart(chartData, chart_title)
		{
			var attempted_data_points = new Array();
			var scheduled_data_points = new Array();
			var max_y = 0;
			var bar_height = 0;
			$.each(chartData, function(key, value){
				$.each(value, function(data_point_type, data_point_value) {
					if(data_point_type == "attempted")
					{
						attempted_data_points.push({y : parseInt(data_point_value), label : key});
						bar_height += parseInt(data_point_value);
					}
					else if(data_point_type == "scheduled")
					{
						scheduled_data_points.push({y : parseInt(data_point_value), label : key});
						bar_height += parseInt(data_point_value);
					}
				});
				if(bar_height > max_y)
				{
					max_y = bar_height;
				}
				bar_height = 0;
			});
			
			CanvasJS.addColorSet("customColors",
					[//colorSet Array

		                "#958c12",
		                "#953579"               
		            ]);
			var chart = new CanvasJS.Chart("stats-chart",
				    {
				 	  theme: "theme3",
				  	  colorSet: "customColors",
					  title:{
			               text: chart_title,
			               fontColor: "#317eac"              
			          },
				      axisY:{
				    	  maximum: max_y
				      },
				      axisX:{
						  labelAngle: 150,
						  labelFontSize: 15
					  },
				      data: [
					  {
					    type: "stackedColumn",
					    legendText: "Test Scheduled",
					    showInLegend: "true",
					    dataPoints: scheduled_data_points
					  },
				      {
				        type: "stackedColumn",
				        legendText: "Attempted",
				        showInLegend: "true",
				        dataPoints: attempted_data_points
				      }
				      ]
				    });

				    chart.render();
		}
		<?php 
		}
		else 
		{
		?>
		$('#accordion').accordion();

		var bTestStarted = false;
		function ShowOverlay(url, div_id)
		{
			$("#sidebar").hide();
			$("#header").hide();
			
			var current_date = new Date();
		    var time_zone = -current_date.getTimezoneOffset() / 60;
		    
			var height	  = $(window).height();
			bTestStarted = true;
			$("#overlay_frame").attr("src",url+"&time_zone="+time_zone+"&height="+height).ready(function(){
				$("#overlay").show(800);
				$("body").css("overflow", "hidden");
			});
			
			RemoveTest.div_id = div_id;
		}
		
		function HideOverlay()
		{
			$("#overlay").hide(500);
			$("#sidebar").show();
			$("#header").show();
			$("body").css("overflow", "auto");
			window.location = window.location;
		}
		
		function RemoveTest()
		{
			TestOver(RemoveTest.div_id);
		}


		function TestOver(div_id)
		{
			//window.location = window.location;
		}

		setInterval(function(){
			if(!bTestStarted)
			{
				$(".modal1").show();
				$.ajax({
					url: "ajax/ajax_refresh_cand_dashboard.php",
					async: false,
					success: function(data){
						$(".accordion-frame").empty();
						$('#accordion').accordion("destroy");
						$(".accordion-frame").append("<a class='heading bg-lightBlue fg-white active' style='font-size: 15px;' href='#'><b>Scheduled Tests</b></a>");
						$(".accordion-frame").append("<div class='content'></div>");
						$(".content").html(data);
						$('#accordion').accordion();
						$(".modal1").hide();
					}
					
				});
			}
		}, 60000);
		<?php 
		}
		?>
	</script>
</body>
</html>
