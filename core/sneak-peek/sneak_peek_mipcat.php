<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	include_once(dirname(__FILE__)."/../../lib/include_js_css.php");
	include_once(dirname(__FILE__)."/../../lib/site_config.php");
	include_once("../../lib/session_manager.php");
	include_once("../../test/lib/tbl_question.php");
	include_once('../../database/mcat_db.php');
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$objDB = new CMcatDB();
	
	$user_id = CSessionManager::Get(CSessionManager::STR_USER_ID);
	$objIncludeJsCSS = new IncludeJSCSS();
	
	$menu_id = CSiteConfig::UAMM_SNEAK_PEEK;
	$page_id = CSiteConfig::UAP_SNEAK_PEEK_MIPCAT;
?>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="Generator" content="Mastishka Intellisys Private Limited">
<meta name="Author" content="Mastishka Intellisys Private Limited">
<meta name="Keywords" content="">
<meta name="Description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo(CConfig::SNC_SITE_NAME);?>: <?php echo(CConfig::SNC_SITE_NAME);?> Knowledge Base </title>
<?php 
$objIncludeJsCSS->IncludeDatatablesBootstrapCSS("../../");
$objIncludeJsCSS->IncludeDatatablesResponsiveCSS("../../");
$objIncludeJsCSS->CommonIncludeCSS("../../");
$objIncludeJsCSS->IncludeIconFontCSS("../../");
$objIncludeJsCSS->IncludeMipcatCSS("../../");
$objIncludeJsCSS->CommonIncludeJS("../../");
$objIncludeJsCSS->IncludeJqueryDatatablesMinJS("../../");
$objIncludeJsCSS->IncludeDatatablesTabletoolsMinJS("../../");
$objIncludeJsCSS->IncludeDatatablesBootstrapJS("../../");
$objIncludeJsCSS->IncludeDatatablesResponsiveJS("../../");
$objIncludeJsCSS->IncludeJqueryDatatablesRowGroupingJS("../../");
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
		<div class="col-lg-3 col-md-3 col-sm-3">
			<?php 
			include_once(dirname(__FILE__)."/../../lib/sidebar.php");
			?>
		</div>
		<div class="col-lg-9 col-md-9 col-sm-9" style="border-left: 1px solid #ddd; border-top: 1px solid #ddd;height: 700px;">
			<br />
			<div id='TableToolsPlacement'>
			</div><br />
		    <div class="form-inline">
		        <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
		        	<thead>
						<tr>
							<th data-class="expand"><font color="#000000">Subject</font></th>
							<th><font color="#000000">Topic</font></th>
							<th data-hide="phone"><font color="#000000">Language</font></th>
							<th data-hide="phone"><font color="#000000">Easy Questions</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Moderate Questions</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Hard Questions</font></th>
						</tr>
					</thead>
					<?php
						$QuesInfoAry = $objDB->GetQuestionsInfo();
						
						/*echo("<pre>");
						print_r($QuesInfoAry);
						echo("</pre>");*/
						
						foreach ($QuesInfoAry as $subject_id => $topics)
						{
							foreach($topics as $topic_id => $languages)
							{
								foreach($languages as $lang => $questions)
								{
									$subject_name = ucwords($objDB->GetSubjectName($subject_id));
									$topic_name   = ucwords($objDB->GetTopicName($topic_id));
									$language	  = ucwords($lang);
									if(!empty($subject_name) && !empty($topic_name))
									{
										echo("<tr>");
										printf("<td>%s</td> <td>%s</td> <td>%s</td> <td>%s</td> <td>%s</td> <td>%s</td>", ucwords($objDB->GetSubjectName($subject_id)), ucwords($objDB->GetTopicName($topic_id)), $language, empty($questions[1]) ? 0 : $questions[1], 
																	empty($questions[2]) ? 0 : $questions[2],
																	empty($questions[3]) ? 0 : $questions[3]);
										echo("</tr>\n");
									}
								}
							}
						}
					?>
					<tfoot>
						<tr>
							<th data-class="expand"><font color="#000000">Subject</font></th>
							<th><font color="#000000">Topic</font></th>
							<th data-hide="phone"><font color="#000000">Language</font></th>
							<th data-hide="phone"><font color="#000000">Easy Questions</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Moderate Questions</font></th>
							<th data-hide="phone,tablet"><font color="#000000">Hard Questions</font></th>
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
	var tableElement;
	var responsiveHelper = undefined;
	var breakpointDefinition = {
	        tablet: 1024,
	        phone : 480
	    };
	$.fn.dataTable.TableTools.defaults.aButtons = [];
	$.fn.dataTable.TableTools.defaults.sSwfPath = "<?php $objIncludeJsCSS->IncludeDatatablesCopy_CSV_XLS_PDF("../../");?>";
	$(document).ready(function () {
	    tableElement = $('#example');
	    table = tableElement.dataTable({
	    	"dom": 'T<"clear">lfrtip',
			"iDisplayLength": -1,
			"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
			"oTableTools": {
	            "sRowSelect": "single"
	        },
	        "columnDefs": [ {
	            "targets": 0,
	            "searchable": false
	          } ],
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
	    }).rowGrouping({bExpandableGrouping: true, bHideGroupingColumn: false,
			 asExpandedGroups: [""]
		});
	});
	</script>
</body>
</html>