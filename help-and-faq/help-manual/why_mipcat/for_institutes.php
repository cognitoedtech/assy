<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	include_once(dirname(__FILE__)."/../../../lib/session_manager.php");
	include_once(dirname(__FILE__)."/../../../lib/site_config.php");
	include_once(dirname(__FILE__)."/../../../lib/utils.php");
	$page_id = CSiteConfig::HF_GS_HELP;
	$login = CSessionManager::Get(CSessionManager::BOOL_LOGIN);
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Getting Started : Individuals</title>
		<style>
			#overlay { position: fixed; left: 0px; top: 0px; width: 100%; height: 100%; z-index: 100; background-color:white;}
			a.anchor:link {color:GhostWhite;}    /* unvisited link */
			a.anchor:visited {color:GhostWhite;} /* visited link */
			a.anchor:hover {color:GhostWhite;}   /* mouse over link */
			a.anchor:active {color:GhostWhite;}  /* selected link */
			a:focus {outline: none;}
		</style>
		<link rel="stylesheet" type="text/css" href="../../../css/mipcat.css" />
		<link rel="stylesheet" type="text/css" href="../../../3rd_party/bootstrap/css/bootstrap.css" />
		<link rel="stylesheet" type="text/css" href="../../../3rd_party/bootstrap/css/bootstrap-responsive.css">
		<link rel="stylesheet" type="text/css" href="../../../3rd_party/bootstrap/css/bootstrap-docs.css" />
		<script type="text/javascript" src="../../../js/jquery.js"></script>
		<script type="text/javascript" src="../../../3rd_party/bootstrap/js/bootstrap.js"></script>
		 <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
	</head>
	<body style="font: 75% 'Trebuchet MS', sans-serif; margin: 5px;" data-spy="scroll" data-target=".bs-docs-sidebar">
		<!-- Header -->
		<header class="subhead">
  			<?php
				include(dirname(__FILE__)."/../../../lib/header.php");
			?>
		</header>
		
		<div class="container">
			<div class="row">
				<div class="span3 bs-docs-sidebar" style="float:left">
					<ul class="nav nav-list bs-docs-sidenav">
						<li><a href="#overview"><i class="icon-chevron-right"></i> Overview</a></li>
						<li><a href="#transitions"><i class="icon-chevron-right"></i> Transitions</a></li>
						<li><a href="#modals"><i class="icon-chevron-right"></i> Modal</a></li>
						<li><a href="#dropdowns"><i class="icon-chevron-right"></i> Dropdown</a></li>
						<li><a href="#scrollspy"><i class="icon-chevron-right"></i> Scrollspy</a></li>
						<li><a href="#tabs"><i class="icon-chevron-right"></i> Tab</a></li>
						<li><a href="#tooltips"><i class="icon-chevron-right"></i> Tooltip</a></li>
						<li><a href="#popovers"><i class="icon-chevron-right"></i> Popover</a></li>
						<li><a href="#alerts"><i class="icon-chevron-right"></i> Alert</a></li>
						<li><a href="#buttons"><i class="icon-chevron-right"></i> Button</a></li>
						<li><a href="#collapse"><i class="icon-chevron-right"></i> Collapse</a></li>
						<li><a href="#carousel"><i class="icon-chevron-right"></i> Carousel</a></li>
						<li><a href="#typeahead"><i class="icon-chevron-right"></i> Typeahead</a></li>
						<li><a href="#affix"><i class="icon-chevron-right"></i> Affix</a></li>
					</ul>
				</div>
				<div class="span9" style="float:right">
				
					<section id="overview">
						<div class="page-header">
							<h1>JavaScript in Bootstrap</h1>
						</div>
						
						<h3 id="indv_com">Individual or compiled</h3>
		          <p>Plugins can be included individually (though some have required dependencies), or all at once. Both <strong>bootstrap.js</strong> and <strong>bootstrap.min.js</strong> contain all plugins in a single file.</p>
		
		          <h3>Data attributes</h3>
		          <p>You can use all Bootstrap plugins purely through the markup API without writing a single line of JavaScript. This is Bootstrap's first class API and should be your first consideration when using a plugin.</p>
		
		          <p>That said, in some situations it may be desirable to turn this functionality off. Therefore, we also provide the ability to disable the data attribute API by unbinding all events on the body namespaced with `'data-api'`. This looks like this:
		          <pre class="prettyprint linenums">$('body').off('.data-api')</pre>
		
		          <p>Alternatively, to target a specific plugin, just include the plugin's name as a namespace along with the data-api namespace like this:</p>
		          <pre class="prettyprint linenums">$('body').off('.alert.data-api')</pre>
		
		          <h3>Programmatic API</h3>
		          <p>We also believe you should be able to use all Bootstrap plugins purely through the JavaScript API. All public APIs are single, chainable methods, and return the collection acted upon.</p>
		          <pre class="prettyprint linenums">$(".btn.danger").button("toggle").addClass("fat")</pre>
		          <p>All methods should accept an optional options object, a string which targets a particular method, or nothing (which initiates a plugin with default behavior):</p>
		<pre class="prettyprint linenums">
		$("#myModal").modal()                       // initialized with defaults
		$("#myModal").modal({ keyboard: false })   // initialized with no keyboard
		$("#myModal").modal('show')                // initializes and invokes show immediately</p>
		</pre>
		          <p>Each plugin also exposes its raw constructor on a `Constructor` property: <code>$.fn.popover.Constructor</code>. If you'd like to get a particular plugin instance, retrieve it directly from an element: <code>$('[rel=popover]').data('popover')</code>.</p>
		
		          <h3>No Conflict</h3>
		          <p>Sometimes it is necessary to use Bootstrap plugins with other UI frameworks. In these circumstances,  namespace collisions can occasionally occur. If this happens, you may call <code>.noConflict</code> on the plugin you wish to revert the value of.</p>
		
		<pre class="prettyprint linenums">
		var bootstrapButton = $.fn.button.noConflict() // return $.fn.button to previously assigned value
		$.fn.bootstrapBtn = bootstrapButton            // give $().bootstrapBtn the bootstrap functionality
		</pre>
		
		          <h3>Events</h3>
		          <p>Bootstrap provides custom events for most plugin's unique actions. Generally, these come in an infinitive and past participle form - where the infinitive (ex. <code>show</code>) is triggered at the start of an event, and its past participle form (ex. <code>shown</code>) is trigger on the completion of an action.</p>
		          <p>All infinitive events provide preventDefault functionality. This provides the ability to stop the execution of an action before it starts.</p>
		<pre class="prettyprint linenums">
		$('#myModal').on('show', function (e) {
		    if (!data) return e.preventDefault() // stops modal from being shown
		})
		</pre>
					</section>
					<section id="transitions" style="display:none">
						<div class="page-header">
							<h1>Transitions <small>bootstrap-transition.js</small></h1>
						</div>
					</section>
				</div>
			</div>
		</div>
		
		<script type="text/javascript">
			$(function() {
			  var a = function() {
				  	var b = $(window).scrollTop();
				    var d = $(".row").offset().top;
				    var c=$(".bs-docs-sidebar");
				    if (b>d) 
				    {
				    	c.css({position:"fixed",top:"0px"});
				    }
				    else if (b<=d) 
				    {
				        c.css({position:"relative",top:""});
				    }
				    
				    d = $("#transitions").offset().top;
				    if(b<=d)
				    {
				    	//alert(d);
				    	$("#transitions").show( "fade", 8000 );
				    }
			  	};
			  	$(window).scroll(a);a();
			});
		</script>
	</body>
</html>