<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	include('../../database/mcat_db.php');
	include_once(dirname(__FILE__)."/../../lib/include_js_css.php");
	include_once(dirname(__FILE__)."/../../lib/site_config.php");

	
	$objDB = new CMcatDB();
	
	$objIncludeJsCSS = new IncludeJSCSS();
	
	$menu_id = CSiteConfig::UAMM_SUPER_ADMIN;
	$page_id = CSiteConfig::UAP_ACCOUNT_STATUS;
	
?>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="Generator" content="EditPlus">
<meta name="Author" content="">
<meta name="Keywords" content="">
<meta name="Description" content="">
<title>Account Status</title>
<?php 
$objIncludeJsCSS->CommonIncludeCSS("../../");
$objIncludeJsCSS->CommonIncludeJS("../../");
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
	<div class='container' style='width: 100%'>
		<div class='row-fluid'>
			<div class="col-lg-3">
				<?php 
				include_once(dirname(__FILE__)."/../../lib/sidebar.php");
				?>
			</div>
		</div>
	</div>
</body>
</html>
<?php 
if(false)
{
?>
<html>
	<head>
		<title> Super Admin </title>
		<style type="text/css" title="currentStyle">
			@import "../../css/redmond/jquery-ui-1.9.0.custom.min.css";
			@import "../media/css/demo_table.css";
			@import "../media/css/TableTools.css";
			@import "../media/css/dataTables.editor.css";
		</style>
		<style>
		    #overlay_box {
		
		    /* overlay is hidden before loading */
		    display:none;
		
		    /* standard decorations */
		    width:550px;
		    border:10px solid #666;
		
		    /* for modern browsers use semi-transparent color on the border. nice! */
		    border:10px solid rgba(82, 82, 82, 0.698);
		
		    /* hot CSS3 features for mozilla and webkit-based browsers (rounded borders) */
		    -moz-border-radius:8px;
		    -webkit-border-radius:8px;
		  }
		
		  #overlay_box div {
		    padding:10px;
		    border:1px solid #3B5998;
		    background-color:#fff;
		    font-family:"lucida grande",tahoma,verdana,arial,sans-serif
		  }
		
		  #overlay_box h2 {
		    margin:-11px;
		    margin-bottom:0px;
		    color:#fff;
		    background-color:#6D84B4;
		    padding:5px 10px;
		    border:1px solid #3B5998;
		    font-size:20px;
		  }
		</style>
		<link rel="stylesheet" type="text/css" href="../../css/mipcat.css" />
		<script type="text/javascript" charset="utf-8" src="../media/js/jquery.js"></script>
		<script type="text/javascript" charset="utf-8" src="../media/js/jquery.dataTables.js"></script>
		<script type="text/javascript" charset="utf-8" src="../media/js/ZeroClipboard.js"></script>
		<script type="text/javascript" charset="utf-8" src="../media/js/TableTools.js"></script>
		<script type="text/javascript" charset="utf-8" src="../media/js/dataTables.editor.js"></script>
		<script type="text/javascript" charset="utf-8" src="../../js/jquery-ui-custom.min.js"></script>
		<script type="text/javascript" charset="utf-8" src="../media/js/jquery.tools.min.js"></script>
		<script type="text/javascript" charset="utf-8" src="../../js/mipcat/utils.js"></script>
		<script type="text/javascript" charset="utf-8">
			var objTbl, edtPos;
			
			window.onload = function() 
			{
				$("#sadmin").show();
			};			
		</script>
		<style type="text/css">
			/*demo page css*/
			body{ font: 75% "Trebuchet MS", sans-serif; margin: 5px; overflow:hidden;}
		</style>
	</head>
	<body>
		<div id="page_loading_box" style="position:absolute;top:100px;left:50%;zindex:200;">
			<img src="../../images/page_loading.gif" width="32" height="32"/>
		</div>
		<div id="dt_acc_status">
			<ul>
				<li><a href="#tab1">Account Status</a></li>
			</ul>
			<div id="tab1">
				<table cellpadding="0" cellspacing="0" border="0" width="100%" class="display" id="example">
					<thead>
						<tr>
							<th><font color="#000000">Login Name</font></th>
							<th><font color="#000000">First Name</font></th>
							<th><font color="#000000">Last Name</font></th>
							<th><font color="#000000">Organization</font></th>
							<th><font color="#000000">Plan</font></th>
							<th><font color="#000000"><?php echo(CConfig::SNC_SITE_NAME);?> Ques Rate</font></th>
							<th><font color="#000000">Personal Ques Rate</font></th>
							<th><font color="#000000">Search Rate</font></th>
							<th><font color="#000000">Last Billed</font></th>
						</tr>
					</thead>
					<?php
						//$objDB->PopulateCorporateUsers();
					?>
					<tfoot>
						<tr>
							<th><font color="#000000">Login Name</font></th>
							<th><font color="#000000">First Name</font></th>
							<th><font color="#000000">Last Name</font></th>
							<th><font color="#000000">Organization</font></th>
							<th><font color="#000000">Plan</font></th>
							<th><font color="#000000"><?php echo(CConfig::SNC_SITE_NAME);?> Ques Rate</font></th>
							<th><font color="#000000">Personal Ques Rate</font></th>
							<th><font color="#000000">Search Rate</font></th>
							<th><font color="#000000">Last Billed</font></th>
						</tr>
					</tfoot>
				</table>
				<!-- Email dialog -->
				<div id="overlay_box">
					<div id="email_dlg" style="display:none">
						<h2>Send an E-mail to Client</h2>
						<form method="post" action="">
							<table>
								<tr>
									<td>Subject: </td>
									<td><input type="text" size="60" name="to"/><br/></td>
								</tr>
								<tr>
									<td style="vertical-align:top;">Body: </td>
									<td><textarea name="body" rows="10" cols="50"></textarea></td>
								</tr>
							</table>
							<p style="color:#666">
							To close, click the Close button or hit the ESC key.
							</p>
							<!-- yes/no buttons -->
							<p>
							<input type="submit" value="Send">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="close"> Close </button>
							</p>
						</form>
					</div>
					<div id="coupon_dlg" style="display:none">
						<h2>Send a coupon to client</h2>
						<p style="color:#333">
						Please select number of tests to be added to user account.
						</p>
						<form method="post" action="">
							<table>
								<tr>
									<td>Tests: </td>
									<td>
										<select name="tests">
											<option value="50">50 Tests</option>
											<option value="100">100 Tests</option>
											<option value="200">200 Tests</option>
											<option value="500">500 Tests</option>
											<option value="1000">1000 Tests</option>
											<option value="2000">2000 Tests</option>
											<option value="5000">5000 Tests</option>
										</select> 
									</td>
								</tr>
							</table>
							<p style="color:#666">
							To close, click the Close button or hit the ESC key.
							</p>
							<!-- yes/no buttons -->
							<p>
							<input type="submit" value="Send">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="close"> Close </button>
							</p>
						</form>
					</div>
					<div id="billing_dlg" style="display:none">
						<h2>Billing History</h2>
						<p style="color:#333">
						Please select number of tests to be added to user account.
						</p>
						<form method="post" action="">
							<table>
								<tr>
									<td>Tests: </td>
									<td>
										<select name="tests">
											<option value="50">50 Tests</option>
											<option value="100">100 Tests</option>
											<option value="200">200 Tests</option>
											<option value="500">500 Tests</option>
											<option value="1000">1000 Tests</option>
											<option value="2000">2000 Tests</option>
											<option value="5000">5000 Tests</option>
										</select> 
									</td>
								</tr>
							</table>
							<p style="color:#666">
							To close, click the Close button or hit the ESC key.
							</p>
							<!-- yes/no buttons -->
							<p>
							<input type="submit" value="Send">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="close"> Close </button>
							</p>
						</form>
					</div>
				</div>
			</div>
		</div>
		<script type="text/javascript">
			TableTools.BUTTONS.custom_button = $.extend( true, TableTools.buttonBase, {
				"sNewLine": "<br>",
				"sButtonText": "Email",
				"fnClick": function( nButton, oConfig ) {
					if(oConfig.sID == "email_dlg")
					{
						$("#email_dlg").show();
						$("#coupon_dlg").hide();
						$("#billing_dlg").hide();
					}
					else if(oConfig.sID == "coupon_dlg")
					{
						$("#email_dlg").hide();
						$("#coupon_dlg").show();
						$("#billing_dlg").hide();
					}
					else if(oConfig.sID == "billing_dlg")
					{
						$("#email_dlg").hide();
						$("#coupon_dlg").hide();
						$("#billing_dlg").show();
					}
					$("#overlay_box").overlay().load();
				}
			} );
			objTbl = $('#example').dataTable( {
				"sDom": 'T<"clear">lfrtip<"clear spacer">T',
				"sPaginationType": "full_numbers",
				"oTableTools": {
		            "sRowSelect": "single",
		            "aButtons": [
		            	{
							"sExtends":    "custom_button",
							"sButtonText": "Email",
							"sID": "email_dlg"
						},
						{
							"sExtends":    "custom_button",
							"sButtonText": "Generate Coupon",
							"sID": "coupon_dlg"
						},
						{
							"sExtends":    "custom_button",
							"sButtonText": "Billing History",
							"sID": "billing_dlg"
						},
		                "copy",
		                "csv",
		                "pdf"
		            ]
		        }
			} );
			
			// select the overlay element - and "make it an overlay"
			$("#overlay_box").overlay({
			
				// custom top position
				top: 50,
				
				// some mask tweaks suitable for facebox-looking dialogs
				mask: {
				
					// you might also consider a "transparent" color for the mask
					color: '#fff',
					
					// load mask a little faster
					loadSpeed: 200,
					
					// very transparent
					opacity: 0.5
					},
					
				// disable this for modal dialog-type of overlays
				closeOnClick: false,
				
				// load it immediately after the construction
				load: false
			});
		</script>
	</body>
	<script type="text/javascript">
		$(window).load(function(){
			$("#page_loading_box").hide();
			$('#dt_acc_status').show();
			$('#dt_acc_status').tabs();
				
			var page_hgt = objUtils.AdjustHeight("tab1");
			$('#platform', window.parent.document).height(page_hgt+200);
		});
	</script>
</html>
<?php 
}
?>