<?php
	class IncludeJSCSS
	{
		//const BOOTSTRAP_JS = 1;
		//const BOOTSTRAP_CSS = 2;
		//const BOOTSTRAP_VER = 3;
		
		const DATATABLES_COPY_CSV_XLS_PDF_SWF	= "3rd_party/datatables-1.10.0/extensions/TableTools/swf/copy_csv_xls_pdf.swf";
		
		const FONT_AWESOME_CSS					= "3rd_party/font-awesome-4.7.0/css/font-awesome.min.css";
		const BOOTSTRAP3_1_1_PLUS_1_CSS 		= "3rd_party/bootswatch-3.1.1+1/css/bootstrap.min.css";
		const BOOTSWATCH3_1_1_PLUS_1_LESS_CSS 	= "3rd_party/bootswatch-3.1.1+1/css/bootswatch.less";
		const BOOTSTRAP_FILEUPLOAD_MIN_CSS      = "3rd_party/bootstrap/css/bootstrap-fileupload.min.css";
		const METRO_BOOTSTRAP_CSS				= "3rd_party/Metro-UI-CSS-master/Metro-UI-CSS-master/css/metro-bootstrap.css";
		const BOOTSTRAP_THEME_MIN_CSS			= "3rd_party/bootstrap-3.1.1/css/bootstrap-theme.min.css";
		const DATATABLES_BOOTSTRAP_CSS			= "3rd_party/datatables-1.10.0/media/css/dataTables.bootstrap.css";
		const DATATABLES_RESPONSIVE_CSS			= "3rd_party/datatables-1.10.0/datatables-responsive-master/files/1.10/css/datatables.responsive.css";
		const TV_CSS							= "3rd_party/tv/tv.css";
		const MIPCAT_CSS						= "css/mipcat.css";
		const MAIN_CSS							= "css/main.css";
		const JQUERY_SNIPPET_CSS				= "css/jquery.snippet.css";
		const PRICING_CSS						= "3rd_party/pricing/pricing.css";
		const ICONFONT_CSS						= "3rd_party/Metro-UI-CSS-master/Metro-UI-CSS-master/min/iconFont.min.css";
		const JQUERY_STEPS_CSS					= "3rd_party/jquery.steps-1.0.7/css/jquery.steps.css";
		const JQUERY_JQPLOT_CSS					= "3rd_party/jquery.jqplot/jqplot/data/css/jquery.jqplot.min.css";
		const JQUERY_STEPY_CSS					= "3rd_party/wizard/css/jquery.stepy.css";
		const JQUERY_NOUISLIDER_CSS				= "3rd_party/nouislider/jquery.nouislider.css";
		const JQUERY_UI_1_12_1_CSS				= "3rd_party/jquery-ui-1.12.1.custom/jquery-ui.min.css";
		const FUELUX_CSS						= "3rd_party/fuelux-master/fuelux-master/dist/css/fuelux.css";
		const BOOTSTRAP_DOCS_CSS				= "3rd_party/bootstrap/css/bootstrap-docs.css";
		const BOOTSTRAP_RESPONSIVE_CSS 			= "3rd_party/bootstrap/css/bootstrap-responsive.css";
		const BOOTSTRAP_WYSIHTML5_CSS			= "3rd_party/wysiwyg/bootstrap-wysihtml5.css";
		const THREE_D_CORNER_RIBBONS_CSS		= "3rd_party/corner-ribbon/css/3d-corner-ribbons.css";
		const SR_SIDE_NAV_BAR					= "css/sr-navbar.css";
		const TCS_BUTTON_LEGEND					= "css/aec-instructions.css";

		const JQUERY_JS							= "js/jquery.js";
		const JQUERY_2_1_1_JS					= "js/jquery-2.1.1.js";
		const JQUERY_3_2_0_JS					= "js/jquery-3.2.0.js";
		const JQUERY_FORM_JS					= "js/jquery.form.js";
		const JQUERY_SNIPPET_JS					= "js/jquery.snippet.js";
		const UTILS_JS							= "js/mipcat/utils.js";
		const RESULT_ANALYTICS_JS				= "js/mipcat/result_analytics.js";
		const JQUERY_UI_1_12_1_JS				= "3rd_party/jquery-ui-1.12.1.custom/jquery-ui.js";
		const MONEY_JS							= "3rd_party/money.js/money.min.js";
		const TWITTER_BOOTSTRAP_WIZARD_JS		= "3rd_party/twitter-bootstrap-wizard/jquery.bootstrap.wizard.min.js";
		const JQUERY_WIDGET_MIN_JS				= "3rd_party/Metro-UI-CSS-master/Metro-UI-CSS-master/jquery.widget.min.js";
		const JQUERY_VALIDATE_MIN_JS			= "3rd_party/wizard/js/jquery.validate.min.js";
		const JQUERY_VALIDATE_MIN_1_16_0_JS		= "3rd_party/jquery-validation-1.16.0/dist/jquery.validate.min.js";
		const JQUERY_DATATABLES_MIN_JS			= "3rd_party/datatables-1.10.0/media/js/jquery.dataTables.min.js";
		const DATATABLES_TABLETOOLS_MIN_JS		= "3rd_party/datatables-1.10.0/extensions/TableTools/js/dataTables.tableTools.min.js";
		const DATATABLES_BOOTSTRAP_JS			= "3rd_party/datatables-1.10.0/media/js/dataTables.bootstrap.js";
		const JQUERY_DATATABLES_ROW_GROUPING_JS	= "3rd_party/datatables-1.10.0/media/js/jquery.dataTables.rowGrouping.js";
		const DATATABLES_RESPONSIVE_JS			= "3rd_party/datatables-1.10.0/datatables-responsive-master/files/1.10/js/datatables.responsive.js";
		const BOOTSTRAP_JS						= "3rd_party/bootstrap/js/bootstrap.js";
		const BOOTSTRAP_FILEUPLOAD_MIN_JS       = "3rd_party/bootstrap/js/bootstrap-fileupload.min.js";
		const BOOTSTRAP3_TYPEHEAD_MIN_JS		= "3rd_party/Bootstrap-3-Typeahead-master/bootstrap3-typeahead.min.js";
		const METRO_MIN_JS						= "3rd_party/Metro-UI-CSS-master/Metro-UI-CSS-master/min/metro.min.js";
		const METRO_DROPDOWN_JS					= "3rd_party/Metro-UI-CSS-master/Metro-UI-CSS-master/js/metro-dropdown.js";
		const METRO_CALENDER_JS					= "3rd_party/Metro-UI-CSS-master/Metro-UI-CSS-master/js/metro-calendar.js";
		const METRO_DATEPICKER_JS				= "3rd_party/Metro-UI-CSS-master/Metro-UI-CSS-master/js/metro-datepicker.js";
		const METRO_NOTIFICATION_JS             = "3rd_party/Metro-UI-CSS-master/Metro-UI-CSS-master/js/metro-notify.js";
		const METRO_INPUT_CONTROL_JS			= "3rd_party/Metro-UI-CSS-master/Metro-UI-CSS-master/js/metro-input-control.js";
		const METRO_ACCORDION_JS				= "3rd_party/Metro-UI-CSS-master/Metro-UI-CSS-master/js/metro-accordion.js";
		const JQUERY_STEPS_JS					= "3rd_party/jquery.steps-1.0.7/jquery.steps.js";
		const CANVAS_MIN_JS						= "3rd_party/canvasjs-1.4.1/canvasjs.min.js";
		const JQUERY_STEPY_MIN_JS				= "3rd_party/wizard/js/jquery.stepy.min.js";
		const JQUERY_NOUISLIDER_MIN_JS			= "3rd_party/nouislider/jquery.nouislider.min.js";
		const SCROLLUP_JS						= "3rd_party/scrollup/scrollup.js";
		const ANGULAR_MIN_JS					= "3rd_party/angularjs-1.2.x/angular.min.js";
		const UNDERSCORE_MIN_JS					= "3rd_party/underscore-js/underscore-min.js";
		const TAGGED_INFINITE_SCROLL_JS			= "3rd_party/tagged-infinite-scroll-js/taggedInfiniteScroll.js";
		const JQUERY_RATY_JS					= "3rd_party/raty/lib/jquery.raty.js";
		const CLIPBOARD_JS						= "3rd_party/clipboard-js/clipboard.min.js";
		const CK_EDITOR_JS						= "3rd_party/ckeditor_4.6.2_00314f970ab3/ckeditor/ckeditor.js";//"3rd_party/ckeditor_4.6.2_7883c55d92b3/ckeditor/ckeditor.js";
		const MATH_JAX_JS						= "3rd_party/MathJax-2.7/MathJax.js?config=TeX-AMS_HTML";
		const BOOTSTRAP_NUMBER_SPINNER			= "js/bootstrap-number-input.js";
		
		//---------------------------------
		// Nivo-slider-theams CSS
		//---------------------------------
		const NIVO_SLIDER_DEFAULT_CSS 			= "css/nivo-slider-themes/default/default.css";
		const NIVO_SLIDER_LIGHT_CSS				= "css/nivo-slider-themes/light/light.css";  
		const NIVO_SLIDER_DARK_CSS  			= "css/nivo-slider-themes/dark/dark.css";
		const NIVO_SLIDER_BRA_CSS  				= "css/nivo-slider-themes/bar/bar.css"; 
		const NIVO_SLIDER_NIVO_SLID_CSS 		= "css/nivo-slider-themes/nivo-slider.css";  
		const NIVO_SLIDER_STYLE_CSS  			= "css/nivo-slider-themes/style.css"; 

		const NIVO_SLIDER_JQUERY_NIVO_SLID_JS	= "js/jquery.nivo.slider.js";
		
		//---------------------------------

		
		// --------------------------------
		// JQPlot related JS
		// --------------------------------
		const JQUERY_JQPLOT_JS								= "3rd_party/jquery.jqplot/jqplot/plugins/js/jquery.jqplot.min.js";
		const JQUERY_JQPLOT_BAR_RENDERER_JS					= "3rd_party/jquery.jqplot/jqplot/plugins/jqplot.barRenderer.min.js";
		const JQUERY_JQPLOT_CATEGORY_AXIS_RENDERER_JS 		= "3rd_party/jquery.jqplot/jqplot/plugins/jqplot.categoryAxisRenderer.min.js";
		const JQUERY_JQPLOT_POINT_LABELS_JS 				= "3rd_party/jquery.jqplot/jqplot/plugins/jqplot.pointLabels.min.js";
		const JQUERY_JQPLOT_CANVAS_TEXT_RENDERER_JS			= "3rd_party/jquery.jqplot/jqplot/plugins/jqplot.canvasTextRenderer.min.js";
		const JQUERY_JQPLOT_CANVAS_AXIS_TICK_RENDERER_JS 	= "3rd_party/jquery.jqplot/jqplot/plugins/jqplot.canvasAxisTickRenderer.min.js";
		const JQUERY_JQPLOT_PIE_RENDERER_JS 				= "3rd_party/jquery.jqplot/jqplot/plugins/jqplot.pieRenderer.min.js";
		// --------------------------------
		
		// --------------------------------
		// Bootstrap WYSI HTML Editor JS
		// --------------------------------		
		const WYSIHTML_JS				= "3rd_party/wysiwyg/wysihtml5-master/dist/wysihtml5-0.3.0.js";
		const BOOTSTRAP_WYSIHTML_JS		= "3rd_party/wysiwyg/bootstrap-wysihtml5.js";
		// --------------------------------
		
		// --------------------------------
		// Highcharts JS
		// --------------------------------
		const HIGHCHARTS_JS						= "3rd_party/highcharts/js/highcharts.js";
		const HIGHCHARTS_MORE_JS				= "3rd_party/highcharts/js/highcharts-more.js";
		const SOLID_GUAGE_SRC_JS				= "3rd_party/highcharts/js/modules/solid-gauge.src.js";
		const HIGHCHARTS_EXPORTING_MODULE_JS	= "3rd_party/highcharts/js/modules/exporting.js";
		// --------------------------------
		
		public function IncludeDatatablesCopy_CSV_XLS_PDF($include_base_path)
		{
			printf($include_base_path.self::DATATABLES_COPY_CSV_XLS_PDF_SWF);
		}
		
		public function IncludeZeroClipboardSWF($include_base_path)
		{
			printf($include_base_path.self::ZERO_CLIPBOARD_SWF);
		}
		
		public function IncludeSRNavBarCSS($include_base_path)
		{
			printf("<link rel='stylesheet' type='text/css' href='%s' />\n", $include_base_path.self::SR_SIDE_NAV_BAR);
		}

		public function IncludeTCSButtonsCSS($include_base_path)
		{
			printf("<link rel='stylesheet' type='text/css' href='%s' />\n", $include_base_path.self::TCS_BUTTON_LEGEND);
		}
		
		public function IncludeBootstrapFontAwesomeCSS($include_base_path)
		{
			printf("<link rel='stylesheet' type='text/css' href='%s' />\n", $include_base_path.self::FONT_AWESOME_CSS);
		}
		
		public function IncludeBootstrap3_1_1Plus1CSS($include_base_path)
		{
			printf("<link rel='stylesheet' type='text/css' href='%s' />\n", $include_base_path.self::BOOTSTRAP3_1_1_PLUS_1_CSS);
		}
		
		public function IncludeBootStrapFileUploadCSS($include_base_path)
		{
			printf("<link rel='stylesheet' type='text/css' href='%s' />\n", $include_base_path.self::BOOTSTRAP_FILEUPLOAD_MIN_CSS);
		}
		
		public function IncludeBootswatch3_1_1Plus1LessCSS($include_base_path)
		{
			printf("<link rel='stylesheet' type='text/css' href='%s' />\n", $include_base_path.self::BOOTSWATCH3_1_1_PLUS_1_LESS_CSS);
		}
		
		public function IncludeMetroBootstrapCSS($include_base_path)
		{
			printf("<link rel='stylesheet' type='text/css' href='%s' />\n", $include_base_path.self::METRO_BOOTSTRAP_CSS);
		}
		
		public function IncludeBootstrapThemeMinCSS($include_base_path)
		{
			printf("<link rel='stylesheet' type='text/css' href='%s' />\n", $include_base_path.self::BOOTSTRAP_THEME_MIN_CSS);
		}
		
		public function IncludeDatatablesBootstrapCSS($include_base_path)
		{
			printf("<link rel='stylesheet' type='text/css' href='%s' />\n", $include_base_path.self::DATATABLES_BOOTSTRAP_CSS);
		}
		
		public function IncludeDatatablesResponsiveCSS($include_base_path)
		{
			printf("<link rel='stylesheet' type='text/css' href='%s' />\n", $include_base_path.self::DATATABLES_RESPONSIVE_CSS);
		}
		
		public function IncludeTVCSS($include_base_path)
		{
			printf("<link rel='stylesheet' type='text/css' href='%s' />\n", $include_base_path.self::TV_CSS);
		}
		
		public function IncludeMipcatCSS($include_base_path)
		{
			printf("<link rel='stylesheet' type='text/css' href='%s' />\n", $include_base_path.self::MIPCAT_CSS);
		}
		
		public function IncludeMainCSS($include_base_path)
		{
			printf("<link rel='stylesheet' type='text/css' href='%s' />\n", $include_base_path.self::MAIN_CSS);
		}			
		
		public function IncludeJquerySnippetCSS($include_base_path)
		{
			printf("<link rel='stylesheet' type='text/css' href='%s' />\n", $include_base_path.self::JQUERY_SNIPPET_CSS);
		}
		
		public function IncludePricingCSS($include_base_path)
		{
			printf("<link rel='stylesheet' type='text/css' href='%s' />\n", $include_base_path.self::PRICING_CSS);
		}
		
		public function IncludeIconFontCSS($include_base_path)
		{
			printf("<link rel='stylesheet' type='text/css' href='%s' />\n", $include_base_path.self::ICONFONT_CSS);
		}
		
		public function IncludeJQueryStepsCSS($include_base_path)
		{
			printf("<link rel='stylesheet' type='text/css' href='%s' />\n", $include_base_path.self::JQUERY_STEPS_CSS);
		}
		
		public function IncludeJQueryJQPlotCSS($include_base_path)
		{
			printf("<link rel='stylesheet' type='text/css' href='%s' />\n", $include_base_path.self::JQUERY_JQPLOT_CSS);
		}
		
		public function IncludeJqueryStepyCSS($include_base_path)
		{
			printf("<link rel='stylesheet' type='text/css' href='%s' />\n", $include_base_path.self::JQUERY_STEPY_CSS);
		}
		
		public function IncludeJqueryNouisliderCSS($include_base_path)
		{
			printf("<link rel='stylesheet' type='text/css' href='%s' />\n", $include_base_path.self::JQUERY_NOUISLIDER_CSS);
		}
		
		public function IncludeJqueryUI_1_12_1_CSS($include_base_path)
		{
			printf("<link rel='stylesheet' type='text/css' href='%s' />\n", $include_base_path.self::JQUERY_UI_1_12_1_CSS);
		}

		public function IncludeFuelUXCSS($include_base_path)
		{
			printf("<link rel='stylesheet' type='text/css' href='%s' />\n", $include_base_path.self::FUELUX_CSS);
		}
		
		public function IncludeBootStrapDocsCSS($include_base_path)
		{
			printf("<link rel='stylesheet' type='text/css' href='%s' />\n", $include_base_path.self::BOOTSTRAP_DOCS_CSS);
		}
		
		public function IncludeBootStrapResponsiveCSS($include_base_path)
		{
			printf("<link rel='stylesheet' type='text/css' href='%s' />\n", $include_base_path.self::BOOTSTRAP_RESPONSIVE_CSS);
		}
		
		public function IncludeBootStrapWYSIHTML5CSS($include_base_path)
		{
			printf("<link rel='stylesheet' type='text/css' href='%s' />\n", $include_base_path.self::BOOTSTRAP_WYSIHTML5_CSS);
		}
		
		public function Include3DCornerRibbonsCSS($include_base_path)
		{
			printf("<link rel='stylesheet' type='text/css' href='%s' />\n", $include_base_path.self::THREE_D_CORNER_RIBBONS_CSS);
		}
		
		/* ------------------------------------------------------------ */
		public function IncludeJqueryJS($include_base_path)
		{
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::JQUERY_JS);
		}
		
		public function IncludeJquery2_1_1JS($include_base_path)
		{
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::JQUERY_2_1_1_JS);
		}
		
		public function IncludeJquery3_2_0JS($include_base_path)
		{
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::JQUERY_3_2_0_JS);
		}
		
		public function IncludeJqueryFormJS($include_base_path)
		{
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::JQUERY_FORM_JS);
		}
		
		public function IncludeTwitterBootstrapWizardJS($include_base_path)
		{
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::TWITTER_BOOTSTRAP_WIZARD_JS);
		}
		
		public function IncludeJquerySnippetJS($include_base_path)
		{
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::JQUERY_SNIPPET_JS);
		}
		
		public function IncludeJqueryWidgetMinJS($include_base_path)
		{
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::JQUERY_WIDGET_MIN_JS);
		}
		
		public function IncludeJqueryValidateMinJS($include_base_path, $version = "1.9.0")
		{
			if($version == "1.9.0")
			{
				printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::JQUERY_VALIDATE_MIN_JS);
			}
			else 
			{
				printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::JQUERY_VALIDATE_MIN_1_16_0_JS);
			}
		}

		public function IncludeJqueryDatatablesMinJS($include_base_path)
		{
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::JQUERY_DATATABLES_MIN_JS);
		}
		
		public function IncludeDatatablesTabletoolsMinJS($include_base_path)
		{
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::DATATABLES_TABLETOOLS_MIN_JS);
		}
		
		public function IncludeDatatablesBootstrapJS($include_base_path)
		{
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::DATATABLES_BOOTSTRAP_JS);
		}
		
		public function IncludeJqueryDatatablesRowGroupingJS($include_base_path)
		{
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::JQUERY_DATATABLES_ROW_GROUPING_JS);
		}
		
		public function IncludeDatatablesResponsiveJS($include_base_path)
		{
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::DATATABLES_RESPONSIVE_JS);
		}
		
		public function IncludeBootstrapJS($include_base_path)
		{
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::BOOTSTRAP_JS);
		}
		
		public function IncludeBootStrapFileUploadMinJS($include_base_path)
		{
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::BOOTSTRAP_FILEUPLOAD_MIN_JS);
		}
		
		public function IncludeBootStrap3TypeHeadMinJS($include_base_path)
		{
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::BOOTSTRAP3_TYPEHEAD_MIN_JS);
		}
		
		public function IncludeMetroMinJS($include_base_path)
		{
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::METRO_MIN_JS);
		}
		
		public function IncludeJqueryUI_1_12_1_JS($include_base_path)
		{
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::JQUERY_UI_1_12_1_JS);
		}
		
		public function IncludeMetroDropdownJS($include_base_path)
		{
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::METRO_DROPDOWN_JS);
		} 
		
		public function IncludeMetroCalenderJS($include_base_path)
		{
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::METRO_CALENDER_JS);
		}
		
		public function IncludeMetroDatepickerJS($include_base_path)
		{
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::METRO_DATEPICKER_JS);
		}
		
		public function IncludeMetroNotificationJS($include_base_path)
		{
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::METRO_NOTIFICATION_JS);
		}
		
		public function IncludeMetroInputControlJS($include_base_path)
		{
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::METRO_INPUT_CONTROL_JS);
		}
		
		public function IncludeMetroAccordionJS($include_base_path)
		{
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::METRO_ACCORDION_JS);
		}
		
		public function IncludeJQueryStepyJS($include_base_path)
		{
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::JQUERY_STEPS_JS);
		}
		
		public function IncludeCanvasMinJS($include_base_path)
		{
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::CANVAS_MIN_JS);
		}
		
		public function IncludeUtilsJS($include_base_path)
		{
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::UTILS_JS);
		}
		
		public function IncludeResultAnalyticsJS($include_base_path)
		{
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::RESULT_ANALYTICS_JS);
		}
		
		public function IncludeMoneyJS($include_base_path)
		{
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::MONEY_JS);
		}
		
		public function IncludeJQueryStepyMinJS($include_base_path)
		{
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::JQUERY_STEPY_MIN_JS);
		}
		
		public function IncludeJQueryNouisliderMinJS($include_base_path)
		{
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::JQUERY_NOUISLIDER_MIN_JS);
		}
		
		public function IncludeScrollUpJS($include_base_path)
		{
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::SCROLLUP_JS);
		}
		
		public function IncludeAngularMinJS($include_base_path)
		{
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::ANGULAR_MIN_JS);
		}
		
		public function IncludeUnderscoreMinJS($include_base_path)
		{
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::UNDERSCORE_MIN_JS);
		}
		
		public function IncludeTaggedInfiniteScrollJS($include_base_path)
		{
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::TAGGED_INFINITE_SCROLL_JS);
		}
		
		public function IncludeJqueryRatyJS($include_base_path)
		{
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::JQUERY_RATY_JS);
		}
		
		public function IncludeClipboardJS($include_base_path)
		{
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::CLIPBOARD_JS);
		}
		
		public function IncludeCKEditorJS($include_base_path)
		{
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::CK_EDITOR_JS);
		}
		
		public function IncludeMathJAXJS($include_base_path)
		{
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::MATH_JAX_JS);
		}
		
		public function IncludeBootstrapNumberSpinnerJS($include_base_path)
		{
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::BOOTSTRAP_NUMBER_SPINNER);
		}
		
		public function CommonIncludeCSS($include_base_path)
		{
			$this->IncludeBootstrap3_1_1Plus1CSS($include_base_path);
			$this->IncludeBootswatch3_1_1Plus1LessCSS($include_base_path);
			$this->IncludeBootstrapFontAwesomeCSS( $include_base_path );
			$this->IncludeMainCSS($include_base_path);
			$this->IncludeMetroBootstrapCSS($include_base_path);
		}
		
		public function CommonIncludeJS($include_base_path, $version="3.2.0")
		{
			if($version != "3.2.0")
			{
				$this->IncludeJqueryJS($include_base_path);
			}
			else
			{
				$this->IncludeJquery3_2_0JS($include_base_path);
			}
			$this->IncludeJqueryWidgetMinJS($include_base_path);
			$this->IncludeBootstrapJS($include_base_path);
			$this->IncludeMetroMinJS($include_base_path);
			$this->IncludeMetroDropdownJS($include_base_path);
			$this->IncludeCKEditorJS($include_base_path);
		}
		
		public function CommonIncludeNivoSliderCSS($include_base_path)
		{
			printf("<link rel='stylesheet' type='text/css' href='%s' />\n", $include_base_path.self::NIVO_SLIDER_DEFAULT_CSS);
			printf("<link rel='stylesheet' type='text/css' href='%s' />\n", $include_base_path.self:: NIVO_SLIDER_LIGHT_CSS);
			printf("<link rel='stylesheet' type='text/css' href='%s' />\n", $include_base_path.self::NIVO_SLIDER_DARK_CSS);
			printf("<link rel='stylesheet' type='text/css' href='%s' />\n", $include_base_path.self:: NIVO_SLIDER_BRA_CSS);
			printf("<link rel='stylesheet' type='text/css' href='%s' />\n", $include_base_path.self::NIVO_SLIDER_NIVO_SLID_CSS);
			printf("<link rel='stylesheet' type='text/css' href='%s' />\n", $include_base_path.self:: NIVO_SLIDER_STYLE_CSS);	
		}
		
		public function IncludeNivoSliderJS($include_base_path)
		{
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::NIVO_SLIDER_JQUERY_NIVO_SLID_JS);
		}
		
		public function CommonIncludeJQPlot($include_base_path)
		{
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::JQUERY_JQPLOT_JS);
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::JQUERY_JQPLOT_BAR_RENDERER_JS);
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::JQUERY_JQPLOT_CATEGORY_AXIS_RENDERER_JS);
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::JQUERY_JQPLOT_POINT_LABELS_JS);
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::JQUERY_JQPLOT_CANVAS_TEXT_RENDERER_JS);
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::JQUERY_JQPLOT_CANVAS_AXIS_TICK_RENDERER_JS);
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::JQUERY_JQPLOT_PIE_RENDERER_JS);
		}
		
		public function CommonIncludeWYSIHTMLJS($include_base_path)
		{
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::WYSIHTML_JS);
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::BOOTSTRAP_WYSIHTML_JS);
		}
		
		public function CommonIncludeHighchartsJS($include_base_path)
		{
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::HIGHCHARTS_JS);
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::HIGHCHARTS_MORE_JS);
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::SOLID_GUAGE_SRC_JS);
			printf("<script type='text/javascript' src='%s'></script>\n", $include_base_path.self::HIGHCHARTS_EXPORTING_MODULE_JS);
		}
	}
?>