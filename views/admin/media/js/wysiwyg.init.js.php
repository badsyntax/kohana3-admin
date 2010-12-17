(function($, window){
	
	tinyMCE.init({

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
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,
		file_browser_callback : '<?php echo URL::site(TRUE, TRUE)?>'
		
	});

})(this.jQuery, this);