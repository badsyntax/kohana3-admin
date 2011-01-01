(function wysiwyg($, window){

	if (!window.tinyMCE) return;

	var config = {

		// General options
		mode : "textareas",
		theme : "advanced",
		skin : "cirkuit",
		debug : true,
		plugins : '<?php echo Kohana::config('tinymce.plugins')?>',
		
		// Theme options
		theme_advanced_buttons1 : '<?php echo Kohana::config('tinymce.toolbar1')?>', 
		theme_advanced_buttons2 : "",
		theme_advanced_buttons3 : "",
		theme_advanced_buttons4 : "",
		theme_advanced_resize_horizontal : false,
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,
		file_browser_callback : 'koassets',
		file_browser_url : '<?php echo URL::site('admin/assets/popup', TRUE)?>'
	};
	
	window.koassets = function(field_name, url, type, win) {

		tinyMCE
			.activeEditor
			.windowManager
			.open({
				file : config.file_browser_url, 
				width : 680,
				height : 462,
				resizable : "no",
				inline : "yes", 
				maximizable : "no", 
				close_previous : "no",
				popup_css : false
			}, 
			{
				window : win,
				input : field_name
			});

		return false;		
	};

	tinyMCE.init(config);

})(this.jQuery, this);