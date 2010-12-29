/*
 *
 * @filename : admin.js
 * @developer : badsyntax.co
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
		
		, BEGIN = 1
		, END = 0
		, RESET = -1
		, DEVELOPMENT = 'development'
		, PRODUCTION = 'production'
		
		// Our main global Admin object
		, Admin = {}
	;

	Admin.model = {};
	Admin.view = {};
	Admin.controller = {};
	Admin.util = {};
	
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
				
		function setup(){

			// Build the models
			$.each(Admin.model, function(key, val){
				
				Admin.model[key] = $.extend({}, new Model(key), this);
			});

			// Reset the ajax loader
			self.util.ajax.loader(RESET);

			// Build the interface and bind interaction handlers
			self.util.ui('body');
		}
		
		// Execute a controller action		
		function bootstrap(route){
			
			if (!route.action) return;

			var controller = self.controller[route.controller];
			
			// Extend the base controller with controller methods
			controller = $.extend({}, new Controller(route.controller), controller);

			// Execute before method
			controller.before && controller.before();

			// Execute action methods
			controller['action_' + route.action] && controller['action_' + route.action]();
			
			// Execute after method
			controller.after && controller.after();			
		}
		
		// Global Ajax event handlers
		$.ajaxSetup({

			error: function(xhr, textStatus, error, callback) {
				
				Admin.util.ajax.loader(END);

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
		
		// Start the benchmark
		benchmark(BEGIN);
		
		// Build page elements and init interactions
		setup();
		
		// Begin the routing
		bootstrap(config.route);
		
		// Stop the benchmark
		benchmark(END);
		
		// If environment mode is DEVELOPMENT then log the benchmark results
		this.config.environment == DEVELOPMENT && log('executed in: ' + benchmark_time + 'ms');
	};
	
	// Base controller constructor
	function Controller(name){
		
		this.controller = name || 'controller';
		
		this.elements = {};
		
		// base init stuff here
		
		// Bind the submit validation handler to validation forms 
		Admin.util.validate();		
	}

	Admin.controller.pages = {
		
		action_index: function(){
			
			Admin.model.page.getTree('#page-tree');
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
		}
	};
	
	Admin.controller.groups = {
	
		action_index: function(){
			
			Admin.model.group.getTree('#groups-tree');
		}
	};
	
	Admin.controller.assets = {
		
		action_index: function(){
		
			$('#delete-assets').click(function(){
				
				var ids = [];
				$('input[name^=asset-]:checked').each(function(){
					ids.push(this.value);
				});

				if (ids.length) {
					
					window.location = this.href + '?assets=' + ids.join(',');
					
				} else {
					
					alert('Please select some assets');
				}
				
				return false;
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
			    uploader  		: '/modules/admin/media/flash/uploadify.swf',
			    script    		: '/admin/assets/upload',
			    cancelImg 		: '/modules/admin/media/img/uploadify-cancel.png',
			    auto      		: true,
				debug			: true,
				fileDataName 	: 'asset'
			});			
		}		
		
	}; // End Controller Assets
	
	
	// Base model
	function Model(name){
		
		this.controller = '';
		
		this.getTree = function(elem){
	
			var self = this, uri = [
				Admin.config.paths.base,
				self.controller,
				'tree'
			];
			Admin.util.ajax.loader(BEGIN);

			function load(){

				Admin.util.ajax.loader(END);

				// Build the tree widget
				$(this).parent().ui();

				var total = $(this).find('a').length;

				$('#total-' + self.controller).html( total.toString() );

				$('fieldset.' + self.controller +'-list.last').removeClass('last');

				$('fieldset.' + self.controller + '-information').addClass('last').show();
			}
			
			$(elem).load(uri.join('/'), load);
		};		
	}
	
	// Set the models here
	// You need to define the controller var. The model
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
	
	
	
	/* UTILS METHODS */
	

	// Build the interface on an element
	Admin.util.ui = function(selector){
		
		var elem = $(selector);

		// Messages
		elem.find('#messages').children().length 
			
			&& $('#messages').bind('show', function(){
					$(this).fadeIn(1400);
				}).trigger('show');

		// Selectmenu
		elem.find('select').selectmenu();

		// Save Button
		elem.find('button.ui-button.save')
			.button({
				icons: { primary: "ui-icon-disk" }
			});

		// Default Button
		elem.find('button.default').button();

		// Tabs
		elem.find('.tabs').tabs();

		// Tree
		function setTreeCookie(event){

			var id = $(this).data('id'), 
				name = (Admin.config.route.controller + '/' + Admin.config.route.action),
				ids = Admin.util.cookie.get(name);
				
			if (!id) return;

			ids = (ids)
				? ids.split(',')
				: [];
				
			if (event.type == 'expand') {

				if ( $.inArray( id.toString(), ids ) !== -1 ) return;

				ids.push(id.toString());

				Admin.util.cookie.set(name, ids.join(','));
				
			} else if (event.type == 'collapse') {
				
				for (var i in ids) {
					
					( ids[i] == id ) && ids.splice( i, 1 );
				}

				Admin.util.cookie.set(name, ids.join(','));
			}
		}
		elem.find('.ui-tree ul:first').tree({
			onExpand: setTreeCookie,
			onCollapse: setTreeCookie
		})
		// expand the open tree nodes
		.find('.tree-open').trigger('expand', [0]);
		
		// Button Menu
		elem.find('.action-menu button').button({
			icons: {
				primary: "ui-icon-gear",
				secondary: "ui-icon-triangle-1-s"
			}
		})
		.each(function() {

			$(this).next()
				.menu({
					select: function(event, ui) {
						$(this).hide();
						if (ui.item) { window.location = ui.item.find('a').attr('href'); }
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
			
			Admin.util.ajax.loader(END);

			$('.form-error, .label-error').hide();
			
			if (data.status && data.redirect_url) {

				window.location = data.redirect_url;

			} else if (!data.status && data.errors){
				
				Admin.util.message('error', 'Please correct the errors.');

				$.each(data.errors, function(key, val){
					
					var id = $('[name="' + key + '"]').attr('id'),
						label = $('label[for="' + id + '"]');
					
					!label.find('.label-error').length && label.append('<span class="label-error"></span>');
					
					label.find('.label-error').hide()
						.html(' - ' + val)
						.fadeIn('slow');
				});
			}
		}	
	
		$('.ajax-validate')
			.submit(function(e){
				
				e.preventDefault();
				Admin.util.ajax.loader(BEGIN);
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
	
	Admin.util.cookie = {

		config : {
			expiredays: 1,
			path: '/'
		},

		_save : function(name, val, expiredays){
			
			expiredays = expiredays || this.config.expiredays || null;

			var expiredate = new Date();

			expiredate.setDate(expiredate.getDate() + expiredays);

			document.cookie = 
				name + '=' 
				+ escape(val) 
				+ ((expiredays === null) 
						? '' 
						: ';expires=' + expiredate.toGMTString()) 
				+ ';path=' + this.config.path;
		},

		get : function(name){

			if (!document.cookie.length) return;

			var start = document.cookie.indexOf(name + '=');

			if (start === -1) return '';

			start = start + name.length + 1;

			var end = document.cookie.indexOf(';', start);

			if (end === -1) end = document.cookie.length;

			return unescape(document.cookie.substring(start, end));
		},

		set : function(name, val, expiredays){
			
			this._save(name, val, expiredays);
		}
	};
	
	Admin.util.events = {

		callbacks : {},

		register : function(eventname, namespace, vars){

			namespace = namespace || 'default';
			vars = vars || {};

			var self = this;

			(this.callbacks[eventname] && this.callbacks[eventname][namespace]) &&

				$.each(this.callbacks[eventname][namespace], function(i){
					
					(this.callback && this.callback.constructor == Function) && this.callback(vars);

					(this.fireonce) && delete self.callbacks[eventname][namespace][i];
				});
		}
	};

	Admin.util.hooks = {

		register : function(eventname, namespace, callback, fireonce) {

			namespace = namespace || 'default';
			fireonce = fireonce || false;

			if (!$.sledge.events.callbacks[eventname]) {

				Admin.util.events.callbacks[eventname] = [];

				Admin.util.events.callbacks[eventname][namespace] = [];
			}

			Admin.util.events.callbacks[eventname][namespace].push({
				callback: callback,
				fireonce: fireonce
			});
		}
	};
		
	$.fn.ui = function(){
		
		return this.each(function(){
			
			Admin.util.ui(this);
		})
	};
	
	window.Admin = Admin;
		
})(this, this.jQuery);