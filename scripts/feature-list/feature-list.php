<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="imagetoolbar" content="no" />
	<title>MIp-CAT Plans</title>
	<style type="text/css" title="currentStyle">
		@import "../core/media/css/ui-lightness/jquery-ui-1.8.21.custom.css";
	</style>
	<link rel="stylesheet" href="style.css" />
	<script type="text/javascript" src="jquery.min.js"></script>
	<script type="text/javascript" charset="utf-8" src="../core/media/js/jquery-ui-1.8.21.custom.min.js"></script>
	<script type="text/javascript" src="jquery.featureList-1.0.0.js"></script>
	<style type="text/css">
	
		h3 {
			margin: 0;	
			padding: 7px 0 0 0;
			font-size: 16px;
			text-transform: uppercase;
		}

		div#feature_list {
			width: 1000px;
			height: 400px;
			overflow: hidden;
			position: relative;
		}

		div#feature_list ul {
			position: absolute;
			top: 0;
			list-style: none;	
			padding: 0;
			margin: 0;
		}

		ul#tabs1, ul#tabs2, ul#tabs3 {
			left: 0;
			z-index: 2;
			width: 320px;
		}

		ul#tabs1 li, ul#tabs2 li, ul#tabs3 li {
			font-size: 12px;
			font-family: Arial;
		}
		
		ul#tabs1 li img, ul#tabs2 li img, ul#tabs3 li img {
			padding: 5px;
			border: none;
			float: left;
			margin: 10px 10px 0 0;
		}

		ul#tabs1 li a, ul#tabs2 li a, ul#tabs3 li a {
			color: #222;
			text-decoration: none;	
			display: block;
			padding: 10px;
			height: 60px;
			outline: none;
		}

		ul#tabs1 li a:hover, ul#tabs2 li a:hover, ul#tabs3 li a:hover {
			text-decoration: underline;
		}

		ul#tabs1 li a.current, ul#tabs2 li a.current, ul#tabs3 li a.current {
			background:  url('feature-tab-current.png');
			color: #FFF;
		}

		ul#tabs1 li a.current:hover, ul#tabs2 li a.current:hover, ul#tabs3 li a.current:hover {
			text-decoration: none;
			cursor: default;
		}

		ul#output1, ul#output2, ul#output3 {
			right: 0;
			width: 713px;
			height: 400px;
			position: relative;
		}

		ul#output1 li, ul#output2 li, ul#output3 li {
			position: absolute;
			width: 713px;
			height: 400px;
		}

		ul#output1 li a, ul#output2 li a, ul#output3 li a {
			position: absolute;
			bottom: 60px;
			right: 80px;
			padding: 8px 12px;
			text-decoration: none;
			font-size: 11px;
			color: #FFF;
			background: #000;
			-moz-border-radius: 5px;
		}
		
		ul#output1 li a:hover, ul#output2 li a:hover, ul#output3 li a:hover {
			background: #D33431;
		}
	</style>
	<script language="javascript">
		$(window["document"])["ready"](function(){
						$["featureList"](
							$("#tabs1 li a"),
							$("#output1 li"),
							{start_item	:	0 
							});
						$["featureList"](
							$("#tabs2 li a"),
							$("#output2 li"),
							{start_item	:	0 
							});
						$["featureList"](
							$("#tabs3 li a"),
							$("#output3 li"),
							{start_item	:	0 
							});
						});
	</script>
</head>
<body>
	<div id="content">
		<h1>MIp-CAT Subscription Plans</h1>

		<div id="sadmin">
			<ul>
				<li><a href="#tab1">Plans for Corporates</a></li>
				<li><a href="#tab2">Plans for Institutes</a></li>
				<li><a href="#tab3">Plans for Individual</a></li>
			</ul>
			<div id="tab1">
				<div id="feature_list">
					<ul id="tabs1">
						<li>
							<a href="javascript:;">
								<img src="services.png" />
								<h3>Corporate - Silver</h3>
								<span>Evaluate MIp-CAT for free!</span>
							</a>
						</li>
						<!--<li>
							<a href="javascript:;">
								<img src="services.png" />
								<h3>Corporate - Gold</h3>
								<span>All features for just @ Rs. 20,000/-</span>
							</a>
						</li>
						<li>
							<a href="javascript:;">
								<img src="services.png" />
								<h3>Corporate - Platinum</h3>
								<span>All features for just @ Rs. 30,000/-</span>
							</a>
						</li>-->
					</ul>
					<ul id="output1">
						<li>
							<img src="corp_silver.png" />
							<a href="../login/register-org.php?sub=corp&plan=silver">Proceed with Registration &gt;&gt;</a>
						</li>
						<!--<li>
							<img src="corp_gold.png" />
							<a href="../login/register-org.php?sub=corp&plan=gold">Proceed with Registration &gt;&gt;</a>
						</li>
						<li>
							<img src="corp_platinum.png" />
							<a href="../login/register-org.php?sub=corp&plan=platinum">Proceed with Registration &gt;&gt;</a>
						</li>-->
					</ul>
				</div>
			</div>
			<div id="tab2">
				<div id="feature_list">
					<ul id="tabs2">
						<li>
							<a href="javascript:;">
								<img src="services.png" />
								<h3>Institute - Silver</h3>
								<span>Evaluate MIp-CAT for free!</span>
							</a>
						</li>
						<!--<li>
							<a href="javascript:;">
								<img src="services.png" />
								<h3>Institute - Gold</h3>
								<span>All featuresfor just @ Rs. 10,000/-</span>
							</a>
						</li>
						<li>
							<a href="javascript:;">
								<img src="services.png" />
								<h3>Institute - Platinum</h3>
								<span>All featuresfor just @ Rs. 15,000/-</span>
							</a>
						</li>-->
					</ul>
					<ul id="output2">
						<li>
							<img src="inst_silver.png" />
							<a href="../login/register-org.php?sub=inst&plan=silver">Proceed with Registration &gt;&gt;</a>
						</li>
						<!--<li>
							<img src="inst_gold.png" />
							<a href="../login/register-org.php?sub=inst&plan=gold">Proceed with Registration &gt;&gt;</a>
						</li>
						<li>
							<img src="inst_platinum.png" />
							<a href="../login/register-org.php?sub=inst&plan=platinum">Proceed with Registration &gt;&gt;</a>
						</li>-->
					</ul>
				</div>
			</div>
			<div id="tab3">
				<div id="feature_list">
					<ul id="tabs3">
						<li>
							<a href="javascript:;">
								<img src="services.png" />
								<h3>Individual - Silver</h3>
								<span>Looking for your free evaluation?</span>
							</a>
						</li>
						<!--<li>
							<a href="javascript:;">
								<img src="services.png" />
								<h3>Individual - Gold</h3>
								<span>Placement assistance just @ Rs. 250/-</span>
							</a>
						</li>
						<li>
							<a href="javascript:;">
								<img src="services.png" />
								<h3>Individual - Platinum</h3>
								<span>Placement assistance just @ Rs. 500/-</span>
							</a>
						</li>-->
					</ul>
					<ul id="output3">
						<li>
							<img src="indv_silver.png" />
							<a href="../login/register-cand.php?plan=silver">Proceed with Registration &gt;&gt;</a>
						</li>
						<!--<li>
							<img src="indv_gold.png" />
							<a href="../login/register-cand.php?plan=gold">Proceed with Registration &gt;&gt;</a>
						</li>
						<li>
							<img src="indv_platinum.png" />
							<a href="../login/register-cand.php?plan=platinum">Proceed with Registration &gt;&gt;</a>
						</li>-->
					</ul>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		$('#sadmin').tabs();
	</script>
</body>
</html>