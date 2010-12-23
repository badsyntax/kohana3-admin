/*
 *
 * @filename : admin.js
 * @developer : badsyntax
 *
 */

(function(window, $){

	if (window.Admin) return;

	var 
		// Defaults
		config = {
			environment: 'development',
			route: {
				controller: 'default',
				action: 'index'
			}
		}
		
		// Constants */
		  BEGIN = 1
		, END = 0
		, RESET = -1
		
		// Our main global admin Admin object
		, Admin = {}
	;

	Admin.model = Admin.view = Admin.controller = Admin.util = {};
	
	function log(msg){	
			
		(window.console && window.console.log)
		
			&& window.console.log(msg);
	}
	
	Admin.init = function(options){
		
		this.config = $.extend(config, options);
		var 
			self = this
			, benchmark_start
			, benchmark_end
			, benchmark_time
		;			
		
		function benchmark(action){
			
			switch(action) {
				case BEGIN:
					benchmark_start = (new Date).getTime();
					break;
				case END:
					benchmark_end = (new Date).getTime();
					benchmark_time = benchmark_end - benchmark_start;
			}
		}
				
		// build the ui, reset ajax actions, bind common page events
		function setup(){

			self.util.ajax.loader(RESET);

			self.util.ui('body');
		}
		
		// Execute a controller action		
		function bootstrap(route){
			
			var controller = self.controller[route.controller];
			
			// TODO: If controller doesn't exist then try load it in via require.js
			
			if (!route.action && route.controller && controller) return;
			
			var base = new Controller(route.controller);
			
			controller = $.extend({}, base, controller);

			controller.before && controller.before();

			controller['action_' + route.action] && controller['action_' + route.action]();
					
			controller.after && controller.after();			
		}
		
		// Allow access to the benchmark results
		this.benchmark = function(){
			
			return {
				
				benchmark_start: benchmark_start
			}			
		};		
		
		benchmark(BEGIN);
		
		// build page elements and init interactions
		setup();
		
		// begin the routing
		bootstrap(config.route);
		
		benchmark(END);
		
		// If environment mode is DEVELOPMENT then log the benchmark results
	};
	
	// Build the interface on an element
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
	
	Admin.util.ajax = {
		
		loader : function(action){
			
			var method;
			switch(action){
				case END:
				case RESET:
					method = 'fadeOut';
					break;
				default:
					method = 'fadeIn';
			}
		
			$('#ajax-loading img')[method]('fast');
		}
	};
	
	Admin.util.validate = function(){
		
		var form;
		
		function postSuccess(data){
			
			Admin.util.ajax.loader(BEGIN);

			$('.form-error, .label-error').hide();

			if (data.status && (data.errors.length !== undefined && !data.errors.length)) {

				Admin.util.message('success', 'Successfully updated.');

			} else {
				
				Admin.util.message('error', 'Please correct the errors.');

				$.each(data.errors, function(key, val){
					
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
				Admin.util.ajax.loader(END);
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
	
	/* CONTROLLERS */
	
	// base controller
	function Controller(name){
		
		this.controller = name || 'controller';
		
		this.elements = {};
		
		
		// base init stuff here
	}

	Admin.controller.pages = {
		
		action_index: function(){
			
			this.getPageTree();
		},
		
		action_add: function(){
	
			this.getWysiwyg();
		},
		
		action_edit: function(){

			this.getWysiwyg();
		},
		
		getWysiwyg: function(){

			// load and initiate wysiwyg
			require([Admin.config.paths.tinymce, Admin.config.paths.tinymce_init], function() {
			
			});
		},

		getPageTree: function(){

			Admin.util.ajax.loader(BEGIN);
			
			function load(){
			
				Admin.util.ajax.loader(END);
					
				$(this).parent().ui();
				
				$('#total-pages').html( $(this).find('a').length );
				
				$('fieldset.pages-list.last').removeClass('last');
				
				$('fieldset.pages-information').addClass('last').show();
			}
			
			$('#page-tree').load(Admin.config.paths.base + '/pages/tree', load);			
		}
	};

	Admin.controller.users = {
	
		action_index: function(){
			
			Admin.util.ajax.loader(BEGIN);
			
			function load(){
			
				Admin.util.ajax.loader(END);
					
				$(this).parent().ui();				
			}
			
			$('#page-tree').load(Admin.config.paths.base + '/users/tree', load);
		}
	};
	
	$.fn.ui = function(){
		
		return this.each(function(){
			
			Admin.util.ui(this);
		})
	};
	
	window.Admin = Admin;
		
})(this, this.jQuery);