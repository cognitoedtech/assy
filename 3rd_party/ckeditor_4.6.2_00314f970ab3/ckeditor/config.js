/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For complete reference see:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config

	// The toolbar groups arrangement, optimized for two toolbar rows.
	config.toolbarGroups = [
		{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
		{ name: 'links' },
		{ name: 'insert' },
		{ name: 'forms' },
		{ name: 'tools' },
		{ name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'others' },
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
		{ name: 'styles' },
		{ name: 'colors' },
		{ name: 'about' }
	];

	// Remove some buttons provided by the standard plugins, which are
	// not needed in the Standard(s) toolbar.
	//config.removeButtons = 'Underline,Subscript,Superscript';

	// Set the most common block elements.
	config.format_tags = 'p;h1;h2;h3;pre';

	// Simplify the dialog windows.
	config.removeDialogTabs = 'image:advanced;link:advanced';

	config.extraPlugins = 'mathjax';
	config.mathJaxLib	= 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=TeX-AMS_HTML';
	config.language_list = ['af:Afrikaans',
							'sq:Albanian',
							'ar:Arabic',
							'az:Azerbaijani',
							'eu:Basque',
							'bn:Bengali/Bangla',
							'bs:Bosnian',
							'bg:Bulgarian',
							'ca:Catalan',
							'zh:Chinese Traditional',
							'hr:Croatian',
							'cs:Czech',
							'da:Danish',
							'nl:Dutch',
							'en:English',
							'eo:Esperanto',
							'et:Estonian',
							'fo:Faroese',
							'fi:Finnish',
							'fr:French',
							'gl:Galician',
							'ka:Georgian',
							'de:German',
							'el:Greek',
							'gu:Gujarati',
							'he:Hebrew',
							'hi:Hindi',
							'hu:Hungarian',
							'is:Icelandic',
							'id:Indonesian',
							'it:Italian',
							'ja:Japanese',
							'km:Khmer',
							'ko:Korean',
							'ku:Kurdish',
							'lv:Latvian',
							'lt:Lithuanian',
							'mk:Macedonian',
							'ms:Malay',
							'mn:Mongolian',
							'no:Norwegian',
							'nb:Norwegian Bokmal',
							'oc:Occitan',
							'fa:Persian',
							'pl:Polish',
							'pt:Portuguese',
							'ro:Romanian',
							'ru:Russian',
							'sr:Serbian',
							'si:Sinhala',
							'sk:Slovak',
							'sl:Slovenian',
							'es:Spanish',
							'sv:Swedish',
							'tt:Tatar',
							'th:Thai',
							'tr:Turkish',
							'ug:Uighur',
							'uk:Ukrainian',
							'vi:Vietnamese',
							'cy:Welsh' ];
};
