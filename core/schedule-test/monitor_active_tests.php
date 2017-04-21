<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php 
	include_once(dirname(__FILE__)."/../../database/mcat_db.php");
	include_once(dirname(__FILE__)."/../../lib/utils.php");
	include_once(dirname(__FILE__)."/../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../lib/include_js_css.php");
	include_once(dirname(__FILE__)."/../../lib/site_config.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	$user_type = CSessionManager::Get(CSessionManager::INT_USER_TYPE);
	$time_zone = CSessionManager::Get(CSessionManager::FLOAT_TIME_ZONE);
	
	$objDB = new CMcatDB();
	
	
	$objIncludeJsCSS = new IncludeJSCSS();
	
	$menu_id = CSiteConfig::UAMM_SCHEDULE_TEST;
	$page_id = CSiteConfig::UAP_MONITOR_ACTIVE_TEST;
?>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="Generator" content="Mastishka Intellisys Private Limited">
<meta name="Author" content="Mastishka Intellisys Private Limited">
<meta name="Keywords" content="">
<meta name="Description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo(CConfig::SNC_SITE_NAME);?>: Monitor Active Test</title>
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
$objIncludeJsCSS->IncludeJqueryFormJS("../../");
?>
<style type="text/css">
	.modal, .modal.fade.in {
	    top: 10%;
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
<body>

	<?php 
	include_once(dirname(__FILE__)."/../../lib/header.php");
	?>

	<!-- --------------------------------------------------------------- -->
	<br />
	<br />
	<br />
	<div class='row-fluid'>
		<div class="col-lg-3 col-md-3 col-sm-3">
			<?php 
			include_once(dirname(__FILE__)."/../../lib/sidebar.php");
			?>
		</div>
		<div class="col-lg-9 col-md-9 col-sm-9" style="border-left: 1px solid #ddd; border-top: 1px solid #ddd;">
			<div class="fuelux modal1">
				<div class="preloader"><i></i><i></i><i></i><i></i></div>
			</div>
			<br />
			<div id="tab1">
			<div id='TableToolsPlacement'>
			</div><br />
		    <div class="form-inline">
		        <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
		        	<thead>
						<tr>
							<th data-class="expand"><font color="#000000">Candidate</font></th>
							<th><font color="#000000">Test Name</font></th>
							<th data-hide="phone"><font color="#000000">Scheduled Date</font></th>
							<th data-hide="phone"><font color="#000000">Last Attempted</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Attempted Questions</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Time Remaining</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Marked for Termination</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Terminate Test</font></th>
						</tr>
					</thead>
					<?php
						$objDB->PopulateActiveTestsBySchedulerId($user_id, $time_zone);
					?>
					<tfoot>
						<tr>
							<th data-class="expand"><font color="#000000">Candidate</font></th>
							<th><font color="#000000">Test Name</font></th>
							<th data-hide="phone"><font color="#000000">Scheduled Date</font></th>
							<th data-hide="phone"><font color="#000000">Last Attempted</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Attempted Questions</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Time Remaining</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Marked for Termination</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Terminate Test</font></th>
						</tr>
					</tfoot>
		        </table>
		    </div><br /><br />
			<div class="modal" id="terminate_modal">
				  <div class="modal-dialog">
				    <div class="modal-content">
				      <div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				        <h4 class="modal-title">Confirm Termination</h4>
				      </div>
				      <div class="modal-body">
				      	<p>Do you really want to conclude test? This step will end test and conclude results.</p><br />
						<label class="checkbox inline">
							<input type="checkbox" id="terminate_confirm" onclick="ConfirmTermination();"> Yes I agree to terminate test scheduled for <span id="cand_name"></span>. 
						</label>
				      	<form id="terminate_form">
				      		<input type="hidden" id="tsession_id" name="tsession_id" />
							<input type="hidden" id="user_id" name="user_id" />
							<input type="hidden" id="tschd_id" name="tschd_id" />
							<input type="hidden" id="test_id" name="test_id" />
				      	</form>
				      </div>
				      <div class="modal-footer">
				        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				        <input id="terminate" type="button" onclick="SubmitFormData();" data-dismiss="modal" class="btn btn-primary" value="Terminate" disabled/>
				      </div>
				    </div>
				  </div>
			</div>
		</div>
		<div id="process_msg" style="color: red; margin: 10px;display: none;"><b>Please do not refresh page or press back button until the test is terminated. This process will take about 30 to 45 Seconds.</b></div>
		<?php
		include_once (dirname ( __FILE__ ) . "/../../lib/footer.php");
		?>
	</div>
</div>
	<script type="text/javascript">
		function ShowConfirmation(obj)
		{
			var tsession_data = obj.id;
			var tsession_ary  = tsession_data.split(";");
			$("#tsession_id").val(tsession_ary[0]);
			$("#user_id").val(tsession_ary[1]);
			$("#tschd_id").val(tsession_ary[2]);
			$("#test_id").val(tsession_ary[3]);
	
			element = document.getElementById("name"+tsession_ary[1]);
			$("#cand_name").html("<b>"+element.innerHTML+"</b>");
			
			$("#terminate_confirm").removeAttr("checked");
			$("#terminate").attr("disabled", "disabled");

			$("#terminate_modal").modal("show");
		}
	
		function ConfirmTermination()
		{
			if ($("#terminate_confirm").is(':checked')) 
			{
			    $("#terminate").removeAttr("disabled");
			}
			else {
			    $("#terminate").attr("disabled", "disabled");
			}
		}

		TableTools.BUTTONS.custom_button = $.extend( true, TableTools.buttonBase, {
			"sNewLine": "<br>",
			"sButtonText": "Refresh",
			"fnClick": function() {
				window.location=window.location;
			}
		} );
	
		$(document).ready(function () {
			'use strict';

			var table;
			var tableElement;
			var responsiveHelper = undefined;
			var breakpointDefinition = {
			        tablet: 1024,
			        phone : 480
			    };
			$.fn.dataTable.TableTools.defaults.sSwfPath = "<?php $objIncludeJsCSS->IncludeDatatablesCopy_CSV_XLS_PDF("../../");?>";
			$(document).ready(function () {
			    tableElement = $('#example');
			    table = tableElement.dataTable({
			    	"sDom": 'T<"clear">lfrtip<"clear spacer">T',
			    	"bPaginate": true,
			    	"bFilter": true,
					"oTableTools": {
			            "aButtons": [
			            	{
								"sExtends":    "custom_button",
								"sButtonText": "Refresh",
							},
			            ]
			        },
			        autoWidth      : false,
			        //ajax           : './arrays.txt',
			        preDrawCallback: function () {
			            // Initialize the responsive datatables helper once.
			            if (!responsiveHelper) {
			                responsiveHelper = new ResponsiveDatatablesHelper(tableElement, breakpointDefinition);
			            }
			            var oTableTools = TableTools.fnGetInstance( 'example' );
			            $('#TableToolsPlacement').before( oTableTools.dom.container );
			        },
			        rowCallback    : function (nRow) {
			            responsiveHelper.createExpandIcon(nRow);
			        },
			        drawCallback   : function (oSettings) {
				        //alert("hello");
			            responsiveHelper.respond();
			        }
			    });
			});
			
		});
	
		var counter = 0;
		var timer_is_on = 0;
		var tCount;
		function TimedCount()
		{
			if (counter >= 30)
	        {
				$('#terminate_form').ajaxSubmit(end_exam_options);
	        }
			counter=counter+1;
			tCount=setTimeout(function(){TimedCount()},1000);
		}
	
		var force_kill_options = { 
	   	 	//target:        '',   // target element(s) to be updated with server response 
	   		// beforeSubmit:  showRequest,  // pre-submit callback 
	  	 	 success:       showResponse,  // post-submit callback 
	 
	    	// other available options: 
	    	url:      'ajax/ajax_monitor_active_tests.php',         // override for form's 'action' attribute 
	    	type:      'POST',       // 'get' or 'post', override for form's 'method' attribute 
	    	//dataType:  null        // 'xml', 'script', or 'json' (expected server response type) 
	    	clearForm: false        // clear all form fields after successful submit 
	    	//resetForm: true        // reset the form after successful submit 
	 
	       	// $.ajax options can be used here too, for example: 
	       	//timeout:   3000 
	    };
	
		var end_exam_options = { 
	       	//target:        '',   // target element(s) to be updated with server response 
	       	// beforeSubmit:  showRequest,  // pre-submit callback 
	     	success:       Refresh,  // post-submit callback 
		 
	       	// other available options:
	       	data:     {end_exam : "1"}, 
	       	url:      'ajax/ajax_monitor_active_tests.php',         // override for form's 'action' attribute 
	       	type:     'POST',       // 'get' or 'post', override for form's 'method' attribute 
	       	//dataType:  null        // 'xml', 'script', or 'json' (expected server response type) 
	       	clearForm: false        // clear all form fields after successful submit 
	      	//resetForm: true        // reset the form after successful submit 
		 
		   	// $.ajax options can be used here too, for example: 
		   	//timeout:   3000 
		};
	
		function SubmitFormData()
		{
			$("#terminate_modal").modal("hide");
			$(".modal1").show();
			$('#terminate_form').ajaxSubmit(force_kill_options);
			return false;
	    }
	
		function Refresh()
		{
			window.location = window.location;
		}
	
	    function showResponse()
	    {
	    	$('#tab1').hide();
	    	$("#process_msg").show();  
	    	$(".img").show();
	        TimedCount();	     
	    }
	</script>
</body>
</html>