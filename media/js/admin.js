/*
 *
 * @filename : admin.js
 * @developer : badsyntax
 *
 */

(function(window, $){

	if (window.Admin) return;

	var Admin = {};
	
	Admin.util = Admin.controller = {};
	
	Admin.init = function(config){
		
		this.config = config;

		var self = this;
		

		/*
		 * PRIVATE METHODS
		 */
		
		// build the ui, reset ajax actions, bind common page events
		function setup(){

			self.util.ajaxLoad(true);

			self.util.ui('body');

			self.util.validate();
		}
		
		// execute a controller action		
		function bootstrap(route){
	
			if (route.controller && self.controller[route.controller]) {

				if (self.controller[route.controller]['init']) {

					self.controller[route.controller]['init']();
				}

				if (route.action && self.controller[route.controller]['action_' + route.action]) {

					self.controller[route.controller]['action_' + route.action]();
				}
			}
		}
		
		/*
		 * PRIVILEDGED METHODS
		 */
		
		setup();
		
		// begin the routing
		bootstrap(config.route);
	};
	
	Admin.util.ui = function(selector){
		
		var elem = $(selector);
		
		/* Messages */
		elem.find('#messages').children().length 
			&& $('#messages')
				.bind('show.vex', function(){
					$(this).fadeIn(1400);
				})
				.trigger('show.vex');

		/* Selectmenu */
		elem
			.find('select')
			.selectmenu();

		/* Save Button */
		elem
			.find('button.ui-button.save')
			.button({
				icons: {
					primary: "ui-icon-disk"
				}
			});

		/* Default Button */
		elem
			.find('button.default')
			.button();

		/* Tabs */
		elem
			.find('.tabs')
			.tabs();

		/* Tree */
		elem
			.find('.ui-tree ul:first')
			.tree();

		/* Button Menu */
		elem.find('.action-menu button')
		.button({
			icons: {
				primary: "ui-icon-gear",
				secondary: "ui-icon-triangle-1-s"
			}
		})
		.each(function() {

			$(this)
				.next()
				.menu({
					select: function(event, ui) {

						$(this).hide();

						if (ui.item) {
							window.location = ui.item.find('a').attr('href'); 
						}
					},
					input: $(this)
				}).hide();
		})
		.click(function(event) {

			var btn = this;

			$(this)
				.trigger('mousedown.button')
				.bind('mouseleave.admin.button', function(){

					$(this).addClass('ui-state-active');
				});

			var menu = $(this).next();

			if (menu.is(":visible")) {

				menu.hide();

				return false;
			}

			menu
				.menu("deactivate")
				.show()
				.css({top:0, left:0})
				.position({
					my: "left top",
					at: "left bottom",
					of: this
				});

			$(document).one("click", function() {
				menu.hide();
				$(btn).removeClass('ui-state-active').unbind('mouseleave.admin.button');
			});

			return false;
		});
	};
	
	Admin.util.trigger = function(scope, callback, arg){

		var type = typeof callback;
		
		arg = arg || [];
		
	 	( type === 'function' ) && callback.apply( scope, arg );
	};
	
	Admin.util.ajaxLoad = function(finished){
		
		var method = finished ? 'fadeOut' : 'fadeIn';
		
		$('#ajax-loading img')[method]('fast');
	};
	
	Admin.util.validate = function(){
		
		var form;
		
		function postSuccess(errors){
			
			Admin.util.ajaxLoad(true);

			$('.form-error, .label-error').hide();

			if (!errors.length && errors.length !== undefined) {

				Admin.util.message('success', 'Successfully updated.');

			} else {
				
				Admin.util.message('error', 'Please correct the errors.');

				$.each(errors, function(key, val){
					
					var id = $('[name="' + key + '"]').attr('id'),
						label = $('label[for="' + id + '"]');
					
					!label.find('.label-error').length 
						&& label.append('<span class="label-error"></span>');
					
					label.find('.label-error')
						.hide()
						.html(' - ' + val)
						.fadeIn('slow');
				});
			}
		}	
	
		$('.ajax-validate')
			.submit(function(e){
				
				e.preventDefault();
				Admin.util.ajaxLoad();
				form = this;

				$.ajax({
					type: 'POST',
					url: this.action + '?' + (new Date().getTime()),
					cache: false,
					data: $(this).serialize(),
					dataType: 'json',
					success: postSuccess
				});
				
				return false;
			});
	};
	
	Admin.util.message = function(type, msg){
		
		var elem = $('#messages').empty().hide(), 
			item = '<li class="'+type+'">' + msg + '</li>';
		
		elem
			.append('<ul>' + item + '</ul>')
			.fadeIn('fast').effect("highlight", {}, 800);			
	}
	
	Admin.util.dialog = {
		
		confirm : function(msg, callback){
			
			if (confirm(msg)){
				
				this.trigger(callback);
			}
		}
	};

	Admin.controller.pages = {
		
		init: function(){
			
		},
		
		action_index: function(){
			
			Admin.util.ajaxLoad();
			
			function load(){
			
				Admin.util.ajaxLoad(true);
					
				$(this).parent().ui();
				
				$('#total-pages').html( $(this).find('a').length );
				
				$('fieldset.pages-list.last').removeClass('last');
				
				$('fieldset.pages-information').addClass('last').show();
			}
			
			$('#page-tree').load(Admin.config.paths.base + '/pages/tree', load);
		},
		
		action_add: function(){
			
			// load and initiate wysiwyg
			require([Admin.config.paths.tinymce, Admin.config.paths.tinymce_init], function() {
			
			});
		},
		
		action_edit: function(){
			
			// load and initiate wysiwyg
			require([Admin.config.paths.tinymce, Admin.config.paths.tinymce_init], function() {
			
			});
		}
	};
	
	Admin.controller.users = {
	
		init: function(){

		},
		
		action_index: function(){
			
			Admin.util.ajaxLoad();
			
			function load(){
			
				Admin.util.ajaxLoad(true);
					
				$(this).parent().ui();				
			}
			
			$('#page-tree').load(Admin.config.paths.base + '/users/tree', load);
		}
	};
	
	Admin.controller.assets = {
		
	};
	
	window.Admin = Admin;
	
	$.fn.ui = function(){
		
		return this.each(function(){
			
			Admin.util.ui(this);
		})
	};
	
})(this, this.jQuery);