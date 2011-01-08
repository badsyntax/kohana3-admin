/*
 *
 * @filename : admin.controllers.js
 * @developer : badsyntax.co
 *
 */
(function(window, $, Admin){
	
	if (!Admin) return;
	
	var cons = Admin.cons;
	
	Admin.controller.pages = {
		
		action_index: function(){

			Admin.model.page.getTree('#page-tree');
		},
		
		action_add: function(){
	
			this.getWysiwyg();
			
			Admin.util.validate({
				redirect_url: window.location.toString()
			});
		},
		
		action_edit: function(){

			this.getWysiwyg();
			
			Admin.util.validate({
				redirect_url: window.location.toString()
			});
		},
		
		getWysiwyg: function(){

			// load and initiate wysiwyg
			require([Admin.config.paths.tinymce, Admin.config.paths.tinymce_init], function() {
			
			});
		},
	};
	
	Admin.controller.wysiwyg = {
	
		action_index: function(){
			
			// load and initiate wysiwyg
			require([Admin.config.paths.tinymce, Admin.config.paths.tinymce_init], function() {

			});
		}
	};

	Admin.controller.users = {
	
		action_index: function(){
			
			Admin.model.user.getTree('#page-tree');
		},
		
		action_add: function(){
			
			Admin.model.group.getTree('#groups-tree', function(){
				$('#groups-tree ul:first').tree({
					checkbox: true,
					checkboxName: 'groups'
				});
			});
		}
	};
	
	Admin.controller.groups = {
	
		action_index: function(){
			
			Admin.model.group.getTree('#groups-tree');
		},
		
		action_add: function(){
			
			Admin.util.validate({
				redirect_url: '/admin/groups'
			});
		},
		
		action_edit: function(){
			
			Admin.util.validate({
				redirect_url: window.location.toString()
			});
		}
	};
	
	Admin.controller.roles = {
	
		action_add: function(){
			
			Admin.util.validate({
				redirect_url: '/admin/roles'
			});
		},
		
		action_edit: function(){
			
			Admin.util.validate({
				redirect_url: window.location.toString()
			});
		}
	};
	
	Admin.controller.ui = {
		
	};
	
	Admin.controller.assets = {
		
		action_index: function(){
		
			$('#delete-assets').click(function(){
				
				var ids = [];
				$('input[name^=asset-]:checked').each(function(){
					ids.push(this.value);
				});
				
				if (ids.length) {
					
					if (confirm('Are you sure you want to delete the selected ' + ids.length + ' assets?')){
					
						window.location = this.href + '?assets=' + ids.join(',');
					}
					
				} else {
					
					alert('Please select some assets');
				}
				
				return false;
			});
			
			$('#select-all').click(function(event){
				event.preventDefault();
				$('input[name^="asset-"]:checkbox').attr('checked', true);
			});
			$('#select-none').click(function(event){
				event.preventDefault();
				$('input[name^="asset-"]:checkbox').removeAttr('checked');
			});
		},
	
		action_edit: function(){

			$('#filename')
			.focus(function(){
	
				if (!$.data(this, 'extension')){

					$.data(this, 'extension', this.value.replace(/^.*(\..*?)$/, '$1'));
				}				
				this.value = this.value.replace(new RegExp($.data(this, 'extension') + '$'), '');
			})
			.blur(function(){
				this.value += $.data(this, 'extension');				
			})
		},
		
		action_upload: function(){
			
			return;
			$('#asset').uploadify({
				uploader		: '/modules/admin/media/flash/uploadify.swf',
				script			: '/admin/assets/upload',
				cancelImg		: '/modules/admin/media/img/uploadify-cancel.png',
				auto			: true,
				debug			: true,
				fileDataName	: 'asset'
			});			
		}		
		
	}; // End Controller Assets
		
})(this, this.jQuery, this.Admin);
