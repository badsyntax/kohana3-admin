(function wysiwyg(window, $, tinyMCE){

	if (!tinyMCE) return;

	var config = {

		// General options
		mode : "textareas",
		editor_selector :"wysiwyg",		 
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
		
		// File browser
		file_browser_callback : 'tinymce_koassets',
		file_browser_url : '<?php echo URL::site('admin/assets/popup', TRUE)?>',
	
		// Custom vars
		admin_ajax_loader: '<?php echo URL::site('modules/admin/media/img/ajax_loader.gif', TRUE)?>',
		admin_base_url: '<?php echo URL::site('admin', TRUE)?>'
	};
	
	window.tinymce_koassets = function(field_name, url, type, win) {

		if (type) {
			config.file_browser_url += '?filter=subtype:image';
		}

		tinyMCE
			.activeEditor
			.windowManager
			.open({
				file : config.file_browser_url,
				width : 680,
				height : 466,
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

})(this, this.jQuery, this.tinyMCE);