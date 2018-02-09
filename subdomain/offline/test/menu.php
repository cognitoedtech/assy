<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	include_once("../lib/session_manager.php");
	include_once("../lib/utils.php") ;
	include_once("lib/test_helper.php");
	
	// - - - - - - - - - - - - - - - - -
	// On Session Expire Load ROOT_URL
	// - - - - - - - - - - - - - - - - -
	//CSessionManager::OnSessionExpire();
	// - - - - - - - - - - - - - - - - -
	
	$bFreeEZeeAssesUser = CSessionManager::Get(CSessionManager::BOOL_FREE_EZEEASSESS_USER);
	
	$sUserID = "";
	if($bFreeEZeeAssesUser == 1)
	{
		$sUserID = $_COOKIE[CConfig::FEUC_NAME];
	}
	else 
	{
		$sUserID = CSessionManager::Get(CSessionManager::STR_USER_ID);
	}
	
	$objTH = new CTestHelper();
	
	$parsAry = parse_url(CUtils::curPageURL());
	$qry = split("[=&]", $parsAry["query"]);
	
	$nTestID = null;
	if($qry[0] == "test_id")
	{
		$nTestID = $qry[1];
	}
	
	$tschd_id = null;
	if($qry[2] == "tschd_id")
	{
		$tschd_id = $qry[3];
	}
?>
<html>
 	<head>
  	<title> Menu </title>
  	<link rel="stylesheet" type="text/css" href="../css/mipcat.css" />
  	<link rel="stylesheet" type="text/css" href="../css/glossymenu.css" />
  	<link rel="stylesheet" type="text/css" href="../3rd_party/bootstrap/css/bootstrap.css" />
  	<script type="text/javascript" src="../js/jquery.js"></script>
	<script type="text/javascript" src="../js/ddaccordion.js"></script>
  	<script type="text/javascript">
		var _gaq = _gaq || [];
	  	_gaq.push(['_setAccount', 'UA-2246912-13']);
	  	_gaq.push(['_trackPageview']);
	
		(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
	</script>
	</head>
	<body>
		<h3>Sections</h3><hr/>
		
		<div class="glossymenu" style="float:left">
		<?php
			$arySection = $objTH->GetSectionDetails($nTestID);
			
			/*echo ("<pre>");
			print_r($arySection);
			echo ("</pre>");*/
			
			$secIndex = 0;
			foreach($arySection as $key => $Section)
			{
				if(!empty($Section['name']))
				{
					printf("<a class='menuitem submenuheader' href='javascript:'>%s</a>\n", $Section['name']);
					echo ("\t<div class='submenu'>\n");
					echo ("\t\t<ul>\n");
					
					for($ques = 0; $ques < $Section['questions']; $ques++)
					{
						printf("\t\t\t<li style='background-color:white;'><a href='javascript:' onClick='LoadQuestion(%d, %d, %d, %d);' id='%d' target='display'><b>%d</b></a></li>\n", $nTestID, $tschd_id, $ques, $secIndex, (($secIndex+1)*1000)+($ques+1), ($ques+1));
					}
					
					echo ("\t\t</ul>\n");
					echo ("\t</div>\n");
				}
				$secIndex++;
			}
		?>
		</div>
		<br/><br/><br/><br/>
		<script>
			function LoadQuestion(test_id, tschd_id, ques, sec)
			{
				if(parent.GetBPageLoad())
				{
					parent.SetBPageLoad(false);
					//alert(parent.display.document.getElementById("timer"));
					if(parent.display.document.getElementById("timer") == null)
					{
						//alert ("mipcat.php?test_id="+test_id+"&sec="+sec+"&ques="+ques);
						parent.display.location = "mipcat.php?test_id="+test_id+"&tschd_id="+tschd_id+"&sec="+sec+"&ques="+ques;
					}
					else
					{
						//alert ("mipcat.php?test_id="+test_id+"&sec="+sec+"&ques="+ques+"&curtime="+encodeURIComponent(parent.display.TestTimer.CurTime));
						var nCurTime = parent.display.TestTimer.CurTime;
						if( !( nCurTime) )
						{
							$.getJSON("ajax/ajax_get_elapsed_time.php?test_id="+test_id+"&tschd_id="+tschd_id, function(data) {
								if(data['TestCurTime'])
								{
									nCurTime = data['TestCurTime'];
								}
							});
						}
						
						parent.display.location = "mipcat.php?test_id="+test_id+"&tschd_id="+tschd_id+"&sec="+sec+"&ques="+ques+"&curtime="+encodeURIComponent(nCurTime);
					}
				}
			}
		
			ddaccordion.init({
				headerclass: "submenuheader", //Shared CSS class name of headers group
				contentclass: "submenu", //Shared CSS class name of contents group
				revealtype: "click", //Reveal content when user clicks or onmouseover the header? Valid value: "click", "clickgo", or "mouseover"
				mouseoverdelay: 200, //if revealtype="mouseover", set delay in milliseconds before header expands onMouseover
				collapseprev: true, //Collapse previous content (so only one open at any time)? true/false 
				defaultexpanded: [], //index of content(s) open by default [index1, index2, etc] [] denotes no content
				onemustopen: false, //Specify whether at least one header should be open always (so never all headers closed)
				animatedefault: false, //Should contents open by default be animated into view?
				persiststate: true, //persist state of opened contents within browser session?
				toggleclass: ["", ""], //Two CSS classes to be applied to the header when it's collapsed and expanded, respectively ["class1", "class2"]
				togglehtml: ["suffix", "<img src='../images/acc_vmenu/plus.png' class='statusicon' />", "<img src='../images/acc_vmenu/minus.png' class='statusicon' />"], //Additional HTML added to the header when it's collapsed and expanded, respectively  ["position", "html1", "html2"] (see docs)
				animatespeed: "normal", //speed of animation: integer in milliseconds (ie: 200), or keywords "fast", "normal", or "slow"
				oninit:function(headers, expandedindices){ //custom code to run when headers have initalized
					
				},
				onopenclose:function(header, index, state, isuseractivated){ //custom code to run whenever a header is opened or closed
					
				}
			});
	  	</script>
	</body>
</html>
