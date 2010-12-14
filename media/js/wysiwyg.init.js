(function($, window){
	
	tinyMCE.init({

		// General options
		mode : "textareas",
		theme : "advanced",
		skin : "cirkuit",
		plugins : "safari,pagebreak,advimage,advlist,iespell,media,contextmenu,paste,nonbreaking,xhtmlxtras,jqueryinlinepopups",
		// Theme options
		theme_advanced_buttons1 : "formatselect,|,bold,italic,strikethrough,|,bullist,numlist,|,justifyleft,justifycenter,justifyright,|,link,unlink,|,image,media,|,code", 
		theme_advanced_buttons2 : "",
		theme_advanced_buttons3 : "",
		theme_advanced_buttons4 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true
	});

})(this.jQuery, this);