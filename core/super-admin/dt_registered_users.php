<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	include_once(dirname(__FILE__)."/../../lib/include_js_css.php");
	include_once(dirname(__FILE__)."/../../lib/site_config.php");
	include('../../database/mcat_db.php');
	include_once("../../lib/session_manager.php");

	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$time_zone = CSessionManager::Get(CSessionManager::FLOAT_TIME_ZONE);

	$objDB = new CMcatDB();
	
	$objIncludeJsCSS = new IncludeJSCSS();
	
	$menu_id = CSiteConfig::UAMM_SUPER_ADMIN;
	$page_id = CSiteConfig::UAP_DT_REGISTERED_USERS;
?>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="Generator" content="Mastishka Intellisys Private Limited">
<meta name="Author" content="Mastishka Intellisys Private Limited">
<meta name="Keywords" content="">
<meta name="Description" content="">
<title>Corporate Users</title>
<?php 
$objIncludeJsCSS->IncludeDatatablesBootstrapCSS("../../");
$objIncludeJsCSS->IncludeDatatablesResponsiveCSS("../../");
$objIncludeJsCSS->CommonIncludeCSS("../../");
$objIncludeJsCSS->IncludeIconFontCSS("../../");
$objIncludeJsCSS->CommonIncludeJS("../../");
$objIncludeJsCSS->IncludeJqueryDatatablesMinJS("../../");
$objIncludeJsCSS->IncludeDatatablesTabletoolsMinJS("../../");
$objIncludeJsCSS->IncludeDatatablesBootstrapJS("../../");
$objIncludeJsCSS->IncludeDatatablesResponsiveJS("../../");
?>
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
		<div class="col-lg-3">
			<?php 
			include_once(dirname(__FILE__)."/../../lib/sidebar.php");
			?>
		</div>
		<div class="col-lg-9" style="border-left: 1px solid #ddd; border-top: 1px solid #ddd;">
			<br />
			<div id='TableToolsPlacement'>
			</div><br />
		    <div class="form-inline">
		        <table id="example" class="table table-striped table-bordered" cellspacing="0">
		        	<thead>
						<tr>
							<th data-class="expand"><font color="#000000">Login Name</font></th>
							<th><font color="#000000">First Name</font></th>
							<th data-hide="phone"><font color="#000000">Last Name</font></th>
							<th data-hide="phone"><font color="#000000">Gender</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Contact</font></th>
							<th data-hide="phone,tablet"><font color="#000000">E-mail</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Location</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Organization</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Signup Date</font></th>
						</tr>
					</thead>
					<?php
						$objDB->PopulateUsersByType(CConfig::UT_CORPORATE, $time_zone);
					?>
					<tfoot>
						<tr>
							<th data-class="expand"><font color="#000000">Login Name</font></th>
							<th><font color="#000000">First Name</font></th>
							<th data-hide="phone"><font color="#000000">Last Name</font></th>
							<th data-hide="phone"><font color="#000000">Gender</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Contact</font></th>
							<th data-hide="phone,tablet"><font color="#000000">E-mail</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Location</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Organization</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Signup Date</font></th>
						</tr>
					</tfoot>
		        </table>
		    </div>
		</div>
	</div>
	<script type="text/javascript">
	'use strict';
	
	$(document).ready(function () {
		
	    var responsiveHelper = undefined;
	    var breakpointDefinition = {
	        tablet: 1024,
	        phone : 480
	    };
	    $.fn.dataTable.TableTools.defaults.aButtons = [ "csv", "pdf" ];
	    $.fn.dataTable.TableTools.defaults.sSwfPath = "<?php $objIncludeJsCSS->IncludeDatatablesCopy_CSV_XLS_PDF("../../");?>";
	    var tableElement = $('#example');
	    var table = tableElement.dataTable({
	    	"dom": 'T<"clear">lfrtip',
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
	            responsiveHelper.respond();
	        }
	    });
	});
	</script>
</body>
</html>