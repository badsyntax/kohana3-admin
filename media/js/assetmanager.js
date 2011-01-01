(function(window, $){
	
	if (!window.Admin) return;
	
	var Admin = window.Admin;
	
	Admin.controller.assets_popup = {
		
		before: function(){

			Admin.util.dialog.alert('Error', $('#messages').html());
		}
	};
		
})(this, this.jQuery);