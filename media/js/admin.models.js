/*
 *
 * @filename : admin.models.js
 * @developer : badsyntax.co
 *
 */
(function(window, $, Admin){
	
	if (!Admin) return;
	
	var cons = Admin.cons;
		
	// You need to define the controller. The model
	// uses this controller to collect the data.

	Admin.model.page = {
		
		controller: 'pages'
	};
	
	Admin.model.user = {
		
		controller: 'users'
	};
	
	Admin.model.group = {
	
		controller: 'groups'
	};
		
})(this, this.jQuery, this.Admin);