/*
 *
 * @filename : admin.js
 * @developer : badsyntax.co
 *
 */
(function(window, $){
	
	// add 'js' classname to html tag
	//window.document.documentElement.className += ' js';

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
		
		// Constants
		, cons = {
			BEGIN: 1,
			END: 0,
			RESET: -1,
			DEVELOPMENT: 'development',
			PRODUCTION: 'production'
		}
		
		// Our main global Admin object
		, Admin = {}
	;

	Admin.model = {};
	Admin.view = {};
	Admin.controller = {};
	Admin.util = {};
	Admin.elements = {};
	Admin.cons = cons;
	
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
				case cons.BEGIN:
					benchmark_start = (new Date).getTime();
					break;
				case cons.END:
					benchmark_end = (new Date).getTime();
					benchmark_time = benchmark_end - benchmark_start;
			}
		}
				
		function setup(){
			
			// Build the models
			$.each(Admin.model, function(key, val){
				
				Admin.model[key] = $.extend({}, new Model(key), this);
			});

			// Reset the ajax loader
			self.util.ajax.loader(cons.RESET);
		}
		
		function after(){

			Admin.util.ui('body');
			
			$('nav').find('.ui-tabs-nav li').bind('mouseenter mouseleave', function(){
				$(this).toggleClass('ui-state-hover');
			});

		}

		// Execute a controller action		
		function bootstrap(route, param){
			
			// Check the route
			if (!route.controller || !route.action) return;

			// Get the controller
			var controller = self.controller[route.controller];

			// Does this controller exist?
			if (!controller) return;

			// Extend the base controller with controller methods
			controller = $.extend({}, new Controller(route.controller), controller);

			// Execute before controller init method
			controller.before && controller.before();
			
			controller.init();

			// Execute action methods
			controller['action_' + route.action] && controller['action_' + route.action].call(controller, param);
			
			// Execute after method
			controller.after && controller.after();			
		}
		
		// Global Ajax event handlers
		$.ajaxSetup({

			error: function(xhr, textStatus, error, callback) {
				
				Admin.util.ajax.loader(cons.END);

				setTimeout(function(){

					Admin.util.events.register('error', 'ajax');

					alert('Sorry, an unexpected error occured. Please try again.');

					(callback) && callback.apply();
				});
			}
		});		
		
		// Expose the benchmark results
		this.benchmark = function(){
			
			return { benchmark_start: benchmark_start }			
		};
		
		// Get the page elements		
		Admin.elements = {
			tabs: $('.tabs')
		};	
		
		// Start the benchmark
		benchmark(cons.BEGIN);
		
		// Build page elements and init interactions
		setup();
	
		// Begin the routing
		bootstrap(config.route, config.param);

		after();
		
		// Stop the benchmark
		benchmark(cons.END);
		
		// If environment mode is DEVELOPMENT then log the benchmark results
		this.config.environment == cons.DEVELOPMENT && log('executed in: ' + benchmark_time + 'ms');
	};
	
	// Base controller constructor
	function Controller(name){
		
		this.controller = name || 'controller';
		
		this.init = function(){
			
			// Build the interface and bind interaction handlers
		};
		
		// base init stuff here
		
		(function profiler(){
			
			$('a[href="#profiler"]').click(function(){
		
				// show/hide the profiler
				$('#profiler-container').toggle();
			
				// toggle the show/hide icon
				$(this).find('span').toggle();
			});
		})();
	}

	// Base model
	function Model(name){
		
		this.controller = '';
		
		this.getTree = function(elem, callback){
	
			var self = this, uri = [
				Admin.config.paths.base,
				self.controller,
				'tree'
			];
			Admin.util.ajax.loader(cons.BEGIN);

			function load(){

				Admin.util.ajax.loader(cons.END);

				// Build the tree widget
				$(this).parent().ui();

				var total = $(this).find('a').length;

				$('#total-' + self.controller).html( total.toString() );

				$('fieldset.' + self.controller +'-list.last').removeClass('last');

				$('fieldset.' + self.controller + '-information').addClass('last').show();
				
				Admin.util.trigger(this, callback);
			}
			
			$(elem).load(uri.join('/'), load);
		};		
	}
	
	window.Admin = Admin;
		
})(this, this.jQuery);
