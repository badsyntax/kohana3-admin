/*
 *
 * @filename : admin.js
 * @developer : badsyntax
 *
 */

(function(window, $){

	if (window.VEX) return;

	var VEX = {};
	
	VEX.util = VEX.controller = {};
	
	VEX.init = function(config){
		
		this.config = config;
	
		this.util.ui('body');
		
		this.util.validate();
			
		this.bootstrap(config.route);
	};
	
	VEX.bootstrap = function(route){
		
		if (route.controller && this.controller[route.controller]) {
			
			if (this.controller[route.controller]['init']) {
			
				this.controller[route.controller].init();
			}
			
			if (route.action && this.controller[route.controller][route.action]) {
			
				this.controller[route.controller][route.action]();
			}
		}
	};
	
	VEX.util.ui = function(selector){
		
		var elem = $(selector);
		
		elem.find('#messages').children().length 
			&& $('#messages')
				.bind('show.vex', function(){
					$(this).fadeIn(1400);
				})
				.trigger('show.vex');

		elem.find('select').selectmenu();

		elem.find('button.ui-button.save').button({
			icons: {
				primary: "ui-icon-disk"
			}
		});

		elem.find('button.default').button();

		elem.find('.tabs').tabs();

		elem.find('.ui-tree ul:first').tree({
				expanded: 'li:first'
			});

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

			$(this).trigger('mousedown.button').bind('mouseleave.admin.button', function(){

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
	
	VEX.util.trigger = function(scope, callback, arg){

		var type = typeof callback;
		arg = arg || [];
	 	( type === 'function' ) && callback.apply( scope, arg );
	};
	
	VEX.util.validate = function(){
		
		var form;
		
		function postSuccess(errors){

			$('.form-error, .label-error').hide();

			if (!errors.length && errors.length !== undefined) {

				VEX.util.message('success', 'Successfully updated.');

			} else {
				
				VEX.util.message('error', 'Please correct the errors.');

				$.each(errors, function(key, val){
					
					var 
						id = $('[name="' + key + '"]').attr('id'),
						label = $('label[for="' + id + '"]');
					
					! label.find('.label-error').length && label.append('<span class="label-error"></span>');
					
					label.find('.label-error')
						.hide()
						.html(' - ' + val)
						.fadeIn('slow');
				});
			}
		}	
	
		$('.ajax-validate')
			.submit(function(e){
				
				form = this;

				e.preventDefault();

				$.ajax({
					type: 'POST',
					url: this.action,
					data: $(this).serialize(),
					dataType: 'json',
					success: postSuccess
				});
				
				return false;
			});
	};
	
	VEX.util.message = function(type, msg){
		
		var elem = $('#messages').empty().hide(), 
			item = '<li class="'+type+'">' + msg + '</li>';
		
		elem
			.append('<ul>' + item + '</ul>')
			.effect("highlight", {}, 600);
		  
	}
	
	VEX.util.dialog = {
		
		confirm : function(msg, callback){
			
			if (confirm(msg)){
				
				this.trigger(callback);
			}
		}
	};
	
	VEX.controller.pages = {
		
		init: function(){
			
		},
		
		index: function(){
			$('#page-tree')
				.html('Loading tree...')
				.load(VEX.config.paths.base + '/pages/tree', function(){
					
					$(this)
						.parent().ui().end()
						.hide().fadeIn('fast');
				});
		},
		
		add: function(){
			// load in wysiwyg init
			require([VEX.config.paths.tinymce, VEX.config.paths.tinymce_init], function() {
			
			});
		},
		
		edit: function(){
			// load in wysiwyg init
			require([VEX.config.paths.tinymce, VEX.config.paths.tinymce_init], function() {
			
			});
		}
	};
	
	VEX.controller.assets = {
		
	};
	
	window.VEX = VEX;
	
	$.fn.ui = function(){
		
		return this.each(function(){
			
			VEX.util.ui(this);
		})
	};
	
})(this, this.jQuery);