(function($, document){

	// document ready
	$(function(){

		$('select').selectmenu();
		
		//$('input[type="submit"], .button').button();
		$('button.ui-button.save').button({
			icons: {
				primary: "ui-icon-disk"
			}
		});
		
		$('button.default').button();
		
		$('.tabs').tabs();
		
		$('.action-menu button')
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
						
						window.location = ui.item.find('a').attr('href'); 
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
		
	});

})(this.jQuery, document);
