/*
 *
 * @filename : assetmanager.js
 * @developer : badsyntax.co
 *
 */
(function(window, $, tinyMCEPopup){
	
	if (!window.Admin || !tinyMCEPopup) return;
	
	var Admin = window.Admin;
	
	var PDF = {
	
		insert: function(id){
			
			var win = tinyMCEPopup.getWindowArg("window");			

			// The assetmanager was initiated by a dialog window 
			if (win){

			
			}			
			// The assetmanager was initiated by a toolbar button
			else {

				var ed = tinyMCEPopup.editor, 
					el = ed.selection.getNode();

				tinyMCEPopup.restoreSelection();

				// Fixes crash in Safari
				(tinymce.isWebKit) && ed.getWin().focus();
				
				$.get('/admin/assets/get_download_html/' + id, function(data){
					
					ed.execCommand('mceInsertContent', false, $.trim(data));
					ed.undoManager.add();

					// close popup window
					tinyMCEPopup.close();				
				});
			}
		}
	};
		
	var Image = {
		
		getDimensions: function(width, height, value){
			var w = (width / 100) * value;
			return {
				width: Math.round(w),
				height: Math.round((height / 100) * ((w / width) * 100))
			};
		},
		
		insert: function(path){
						
			var win = tinyMCEPopup.getWindowArg("window");			
			
			// The assetmanager was initiated by a dialog window 
			if (win){
		    	
				var fieldId = tinyMCEPopup.getWindowArg('input');
				win.document.getElementById(fieldId).value = path;

				// are we an image browser?
				if (typeof(win.ImageDialog) != "undefined") {

					// we are, so update image dimensions...
					(win.ImageDialog.getImageData) && win.ImageDialog.getImageData();

					// ... and preview if necessary
					(win.ImageDialog.showPreviewImage) && win.ImageDialog.showPreviewImage(path);
				}

				// close popup window
				tinyMCEPopup.close();				
			}			
			// The assetmanager was initiated by a toolbar button
			else {
		
				var ed = tinyMCEPopup.editor, 
					el = ed.selection.getNode(),
					args = {
						src : path,
						alt : ''
					};
					
				tinyMCEPopup.restoreSelection();

				// Fixes crash in Safari
				(tinymce.isWebKit) && ed.getWin().focus();

				if (el && el.nodeName == 'IMG') {
					// update the selected image
					ed.dom.setAttribs(el, args);
				} else {
					// insert a new image
					ed.execCommand('mceInsertContent', false, '<img id="__mce_tmp" />', {skip_undo : 1});
					ed.dom.setAttribs('__mce_tmp', args);
					ed.dom.setAttrib('__mce_tmp', 'id', '');
					ed.undoManager.add();
				}
				
				// close popup window
				tinyMCEPopup.close();
			}
		},
		
		load: function(){
			
			var elem = {
					loader: $('#resize-image-loading'),
					slider: $('#resize-slider'),
					resizeWidth: $('#resize-image-dimension-width'),
					resizeHeight: $('#resize-image-dimension-height')
				},
				image = $(this), 
				id = image.data('id'),
				width = this.width, 
				height = this.height, 
				d = Image.getDimensions(width, height, 50);
			
			this.width = d.width;
			elem.resizeWidth.html(d.width);
			elem.resizeHeight.html(d.height);
								
			elem.loader.hide();			
			$('#resize-asset-width-max').html(width + 'px');			
			$('#resize-image-contents').show();	
										
			$('#resize-image-wrapper')
				.find('img')
				.remove()
				.end()
				.append(this);
			
			elem.slider.slider({
				value: 50,
			 	slide: function(event, ui) {	
					d = Image.getDimensions(width, height, ui.value);														
					elem.resizeWidth.html(d.width);
					elem.resizeHeight.html(d.height);
					image.attr('width', d.width)
				}
			});
			
			$('#resize-insert').click(function(){
				
				$.get('/admin/assets/get_image_url/' + id + '/' + d.width + '/' + d.height, function(data){				
					Image.insert($.trim(data));
				})
			});
		},				

		loadImage: function(path, id) {
			
			$('<img />', {
				id: 'resize-image-' + id,
				'data-id': id
			})
			.load(this.load)
			.error(function(){			
				alert('There was an error loading the image.');
			})
			.attr('src', path);
		}
	};	
	
	Admin.controller.assets_popup = {
		
		before: function(){

			$('#messages').children().length 

				&& $('#messages').show();
					
			Admin.elements.tabs
				.bind('tabsshow', function(event, ui) {
			  
					var method = ( $(ui.tab).text().toLowerCase() == 'browse' )
						? 'show'
						: 'hide';					
					$('#page-links')[method]();
				})
				.tabs({
					tabTemplate: "<li><a href='#{href}'>#{label}</a> <span class='ui-icon ui-icon-close'>Remove Tab</span></li>",
					add: function( event, ui ) {
				
						$( ui.panel ).append( "<p>Loading content...</p>" );
					}					
				});
				
				// close icon: removing the tab on click
				Admin.elements.tabs
					.find("span.ui-icon-close")
					.live( "click", function(){
					
						var index = $( "li", Admin.elements.tabs ).index( $( this ).parent() );
						
						Admin.elements.tabs.tabs( "remove", index );
					});
					
			$('a[href="#resize"]').parent().hide();
		},

		after: function(){

			Admin.util.dialog.alert('Attention', $('#messages').html());	
		},
		
		action_index: function(){
			
			var self = this;
			
			function preview(event) {

				event.preventDefault();

				var anchor = $(this), 
					id = anchor.data('id'), 
					mimetype = anchor.data('mimetype'), 
					filename = anchor.data('filename');

				if (!$('a[href="#preview-'+id+'"]').length) {

					var tab_title = 'Preview';

					Admin.elements.tabs.tabs( "add", "#preview-" + id, tab_title );
				}

				Admin.elements.tabs.tabs( "select" , 'preview-' + id );

				$('#preview-' + id).load(this.href, function(){

					$(this).ui();
					
					self.action_view({
						id: id,
						mimetype: mimetype,
						filename: filename
					});
				});				
			}
			
			$('#browse').delegate('a', 'click', preview);						
		},
		
		action_view: function(param){
		
			if (!param.id || !param.filename || !param.mimetype) return;
			
			var self = this, 
				mimetype = param.mimetype.split('/'),
				win = tinyMCEPopup.getWindowArg("window");
				
			$('#insert-asset').click(function(){
				
				if (mimetype[0] == 'image')
				{
					// Get full size (original) image url
					$.get('/admin/assets/get_url/' + (param.id || 0), function(data){

						var url = $.trim(data);
						if (url) {

							// Show the resize tab
							Admin.elements.tabs.tabs('select' , 'resize');
							$('a[href="#resize"]').parent().show();

							// Load the resize image and build/show resize controls
							Image.loadImage(url, param.id);
						}				
					});
				}
				else
				{
					if (win) {
						
						// check window type
						
					} else {
												
						if (mimetype[0] == 'application' && mimetype[1] == 'pdf') {
							
							PDF.insert(param.id);							
						}
					}
				}
			});
		}		
	}
		
})(this, this.jQuery, this.tinyMCEPopup);