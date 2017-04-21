<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php 
	include_once(dirname(__FILE__)."/../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../lib/billing.php");
	include_once(dirname(__FILE__).'/../../database/mcat_db.php');
	include_once(dirname(__FILE__)."/../../lib/include_js_css.php");
	include_once(dirname(__FILE__)."/../../lib/site_config.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$objDB = new CMcatDB();
	$objBilling = new CBilling();
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	$time_zone = CSessionManager::Get(CSessionManager::FLOAT_TIME_ZONE);
	
	$currencyPrefix = NULL;
	$currency = $objBilling->GetCurrencyType($user_id);
	
	$currencyPrefix = ($currency == "USD") ? '$' : 'Rs.';
	
	$objIncludeJsCSS = new IncludeJSCSS();
	
	$menu_id = CSiteConfig::UAMM_MY_ACCOUNT;
	$page_id = CSiteConfig::UAP_ACCOUONT_USAGE;
?>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="Generator" content="Mastishka Intellisys Private Limited">
<meta name="Author" content="Mastishka Intellisys Private Limited">
<meta name="Keywords" content="">
<meta name="Description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo(CConfig::SNC_SITE_NAME);?>: Account Usage</title>
<?php 
$objIncludeJsCSS->IncludeDatatablesBootstrapCSS("../../");
$objIncludeJsCSS->IncludeDatatablesResponsiveCSS("../../");
$objIncludeJsCSS->CommonIncludeCSS("../../");
$objIncludeJsCSS->IncludeIconFontCSS("../../");
$objIncludeJsCSS->IncludeFuelUXCSS ( "../../" );
$objIncludeJsCSS->CommonIncludeJS("../../");
$objIncludeJsCSS->IncludeJqueryDatatablesMinJS("../../");
$objIncludeJsCSS->IncludeDatatablesTabletoolsMinJS("../../");
$objIncludeJsCSS->IncludeDatatablesBootstrapJS("../../");
$objIncludeJsCSS->IncludeDatatablesResponsiveJS("../../");
$objIncludeJsCSS->IncludeMetroCalenderJS("../../");
$objIncludeJsCSS->IncludeMetroDatepickerJS("../../");
?>
<style type="text/css">
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
	include_once(dirname(__FILE__)."/../../lib/header.php");
	?>

	<!-- --------------------------------------------------------------- -->
	<br />
	<br />
	<br />
	<div class='row-fluid'>
		<div class="col-sm-3 col-md-3 col-lg-3">
			<?php 
			include_once(dirname(__FILE__)."/../../lib/sidebar.php");
			?>
		</div>
		<div class=" col-lg-9 col-sm-9 col-md-9" style="border-left: 1px solid #ddd; border-top: 1px solid #ddd;">
			<div class="fuelux modal1">
				<div class="preloader"><i></i><i></i><i></i><i></i></div>
			</div>
			<br />
			<div class="metro">
				<div class="row">
					<div class="col-sm-1 col-md-1 col-lg-1">
						<b style="color:darkgreen;">From:</b>
					</div>
					<div class="col-sm-3 col-md-3 col-lg-3">
						<div class="input-control text" id="datepicker1">
			    			<input id="datepicker1_val" type="text">
			    			<button class="btn-date" onclick="return false;"></button>
			    		</div>
		    		</div>
		    		<div class="col-sm-1 col-md-1 col-lg-1">
						<b style="color:darkgreen;">&nbsp;&nbsp;&nbsp;&nbsp;To:</b>
					</div>
					<div class="col-sm-3 col-md-3 col-lg-3">
						<div class="input-control text" id="datepicker2">
			    			<input id="datepicker2_val" type="text">
			    			<button class="btn-date" onclick="return false;"></button>
			    		</div>
		    		</div>
		    		<div class="col-sm-1 col-md-1 col-lg-1">
		    			<button  name="btn" class="button info" onclick="LoadUsageHistory();">Go</button>
		    		</div>
	    		</div>
	    		<div class="row">
	    			<div class="span9" style="text-align: center;" id="error_div">
					</div>
	    		</div>
    		</div>
    		<div style="float: right;">
    			<b>Available Balance	: <?php echo($currencyPrefix." ".$objBilling->GetBalance($user_id));?></b>
    		</div><br />
    		<div style="float: right;">
    			<b>Projected Balance	: <?php echo $currencyPrefix." ".$objBilling->GetProjectedBalance($user_id);?></b>
    		</div>
			<br /><br />
			<div id='TableToolsPlacement'>
			</div><br />
		    <div class="form-inline">
		        <table id="example" class="table table-striped table-bordered" cellspacing="0">
		        	<thead>
						<tr>
							<th><font color="#000000">Timestamp</font></th>
							<th data-class="expand"><font color="#000000">Date</font></th>
							<th><font color="#000000">Description</font></th>
							<th data-hide="phone"><font color="#000000">Credit Amount(<?php echo($currencyPrefix);?>)</font></th>
							<th data-hide="phone"><font color="#000000">Debit Amount(<?php echo($currencyPrefix);?>)</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Projected Balance(<?php echo($currencyPrefix);?>)</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Balance(<?php echo($currencyPrefix);?>)</font></th>
						</tr>
					</thead>
					<tbody id="usage_tbody">
					<?php 
						   $objBilling->PopulateAccountUsage($user_id, $time_zone);
					?>
					</tbody>
					<tfoot>
						<tr>
							<th><font color="#000000">Timestamp</font></th>
							<th data-class="expand"><font color="#000000">Date</font></th>
							<th><font color="#000000">Description</font></th>
							<th data-hide="phone"><font color="#000000">Credit Amount(<?php echo($currencyPrefix);?>)</font></th>
							<th data-hide="phone"><font color="#000000">Debit Amount(<?php echo($currencyPrefix);?>)</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Projected Balance(<?php echo($currencyPrefix);?>)</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Balance(<?php echo($currencyPrefix);?>)</font></th>
						</tr>
					</tfoot>
		        </table>
		    </div><br /><br />
		    <?php
			include_once (dirname ( __FILE__ ) . "/../../lib/footer.php");
			?>
		</div>
	</div>
	<script type="text/javascript">
	'use strict';

	 var table;
	 var hidingColumnAry = new Array();
	 hidingColumnAry.push(0);
	$(document).ready(function () {
		
	    var responsiveHelper = undefined;
	    var breakpointDefinition = {
	        tablet: 1024,
	        phone : 480,
	    };
	    $.fn.dataTable.TableTools.defaults.sSwfPath = "<?php $objIncludeJsCSS->IncludeDatatablesCopy_CSV_XLS_PDF("../../");?>";
	    var tableElement = $('#example');
	    var table = tableElement.dataTable({
	    	"dom": 'T<"clear">lfrtip',
	    	"bPaginate": false,
			"bFilter": false,
			"aoColumns": [ 
				{ "bVisible":    false },
				null,
				null,
				null,
				null,
				null,
				null
			],
	    	"oTableTools": {
	            "aButtons": [
	                {
					    "sExtends": "csv",
					    "mColumns": [ 1, 2, 3, 4, 5, 6 ]
					},
					{
					    "sExtends": "pdf",
					    "mColumns": [ 1, 2, 3, 4]
					}
	            ]
	        },
	        autoWidth      : false,
	        //ajax           : './arrays.txt',
	        preDrawCallback: function () {
	            // Initialize the responsive datatables helper once.
	            if (!responsiveHelper) {
	                responsiveHelper = new ResponsiveDatatablesHelper(tableElement, breakpointDefinition,{
	                	columnAryForHiding : [0]
		            });
	            }
	            var oTableTools = TableTools.fnGetInstance( 'example' );
	            $('#TableToolsPlacement').before( oTableTools.dom.container );
	        },
	        rowCallback    : function (nRow) {
	            responsiveHelper.createExpandIcon(nRow);
	        },
	        drawCallback   : function (oSettings) {
	            responsiveHelper.respond();
	        }
	    });
	});
	
	$("#datepicker1").datepicker({
		format: "dd mmmm yyyy",
		selected: function(d, d0){
			$("#error_div").empty();
		}
	});
	
	$("#datepicker2").datepicker({
		format: "dd mmmm yyyy",
		selected: function(d, d0){
			$("#error_div").empty();
		}
	});

	function LoadUsageHistory()
	{
		$('body').on({
		    ajaxStart: function() { 
		    	$(this).addClass("loading"); 
		    },
		    ajaxStop: function() { 
		    	$(this).removeClass("loading"); 
		    }    
		});
		
		if(new Date($( "#datepicker1_val" ).val()) > new Date($( "#datepicker2_val" ).val()))
		{
			$("#error_div").html("<p style='color:red;'>\"To Date\" must be later than or equal to \"From Date\"!</p>");
		}
		else if($( "#datepicker1_val" ).val() == "" || $( "#datepicker1_val" ).val() == null)
		{
			$("#error_div").html("<p style='color:red;'>Please select From Date!</p>");
		}
		else if($( "#datepicker2_val" ).val() == "" || $( "#datepicker2_val" ).val() == null)
		{
			$("#error_div").html("<p style='color:red;'>Please select To Date!</p>");
		}
		else
		{
			$(".modal1").show();
			$("#error_div").empty();
			$("#example").dataTable().fnDestroy();
			$("#usage_tbody").load("ajax/ajax_get_account_usage.php",{"from_date" : $("#datepicker1_val").val(), "to_date" : $("#datepicker2_val").val()}, function(data){
				var responsiveHelper = undefined;
			    var breakpointDefinition = {
			        tablet: 1024,
			        phone : 480,
			    };
			    $.fn.dataTable.TableTools.defaults.sSwfPath = "<?php $objIncludeJsCSS->IncludeDatatablesCopy_CSV_XLS_PDF("../../");?>";
			    var tableElement = $('#example');
			    var table = tableElement.dataTable({
			    	"dom": 'T<"clear">lfrtip',
			    	"bPaginate": true,
					"bFilter": false,
					"aoColumns": [ 
						{ "bVisible":    false },
						null,
						null,
						null,
						null,
						null,
						null
					],
			    	"oTableTools": {
			            "aButtons": [
			                {
							    "sExtends": "csv",
							    "mColumns": [ 1, 2, 3, 4, 5, 6 ]
							},
							{
							    "sExtends": "pdf",
							    "mColumns": [ 1, 2, 3, 4, 5, 6 ]
							}
			            ]
			        },
			        autoWidth      : false,
			        //ajax           : './arrays.txt',
			        preDrawCallback: function () {
			            // Initialize the responsive datatables helper once.
			            if (!responsiveHelper) {
			                responsiveHelper = new ResponsiveDatatablesHelper(tableElement, breakpointDefinition,{
			                	columnAryForHiding : [0]
				            });
			            }
			            var oTableTools = TableTools.fnGetInstance( 'example' );
			            $('#TableToolsPlacement').before( oTableTools.dom.container );
			        },
			        rowCallback    : function (nRow) {
			            responsiveHelper.createExpandIcon(nRow);
			        },
			        drawCallback   : function (oSettings) {
			            responsiveHelper.respond();
			        }
			    });
			    $(".modal1").hide();
			});
		}
	}
	</script>
</body>
</html>	