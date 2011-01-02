/*
 *
 * @filename : assetmanager.js
 * @developer : badsyntax.co
 *
 */
(function(window, $, tinyMCEPopup){
	
	if (!window.Admin || !tinyMCEPopup) return;
	
	var Admin = window.Admin;
	
	var Tabs = {
		
		create: function(url, id, title, callback){
			
			title = title || 'Default tab';

			// Create and select a new tab
			Admin.elements.tabs
				.tabs('add', '#' + id, title)
				.tabs("select" , id);

			// Load the tab contents
			$('#' + id).load(url, function(){

				// Build the UI
				$(this).ui();

				// Trigger the callback function
				Admin.util.trigger(this, callback);				
			});
		}
	};
	
	var PDF = {
	
		insert: function(path, content){
			
			var win = tinyMCEPopup.getWindowArg("window");			

			// The assetmanager was initiated by a dialog window 
			if (win){
				
				var fieldId = tinyMCEPopup.getWindowArg('input');
				win.document.getElementById(fieldId).value = path;

				// close popup window
				tinyMCEPopup.close();
			}			
			// The assetmanager was initiated by a toolbar button
			else {

				var ed = tinyMCEPopup.editor, 
					el = ed.selection.getNode();

				tinyMCEPopup.restoreSelection();

				// Fixes crash in Safari
				(tinymce.isWebKit) && ed.getWin().focus();
				
				ed.execCommand('mceInsertContent', false, content);
				ed.undoManager.add();

				// close popup window
				tinyMCEPopup.close();		
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
		}	
	};	
	
	Admin.controller.assets_popup = {
		
		before: function(){

			$('#messages').children().length 

				&& $('#messages').show();
					
			Admin.elements.tabs
				.bind('tabsshow', function(event, ui) {
			  
					// If selecting the 'browse' tab then show the pagination links, else hide them
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
					
					var index = $('li', Admin.elements.tabs).index( $(this).parent() );
						
					Admin.elements.tabs.tabs('remove', index);
				});
		},

		after: function(){

			Admin.util.dialog.alert('Attention', $('#messages').html());	
		},
		
		action_index: function(){
			
			var self = this;
			
			function preview(event) {

				event.preventDefault();

				var anchor = $(this), id = anchor.data('id');
				
				Tabs.create(this.href, 'preview-' + id, 'Preview', function(){
		
					self.action_view({
						id: id,
						mimetype: anchor.data('mimetype'),
						filename: anchor.data('filename')
					});					
				});				
			}
			
			$('#browse').delegate('a', 'click', preview);						
		},
		
		action_view: function(param){
		
			if (!param.id || !param.filename || !param.mimetype) return;
			
			var win, self = this, 
				mimetype = param.mimetype.split('/');						
			
			function loadResizeTab(){
				
				$.get('/admin/assets/get_url/' + param.id, function(data){

					var url = $.trim(data);

					if (url) {

						Tabs.create('/admin/assets/popup/resize/' + param.id, 'resize-' + param.id, 'Resize', function(){

							self.action_resize({id: param.id || 0, url: url});	
						});
					}				
				});				
			}
				
			$('#preview-' + param.id)
				.find('.resize-insert')
				.click(function(){
				
					loadResizeTab();				
					return false;				
				})
				.end()
				.find('.insert-asset')
				.click(function(e){
				
					e.preventDefault();
				
					if (mimetype[0] == 'image') {
					
						$.get('/admin/assets/get_url/' + param.id, function(data){
							var url = $.trim(data);
							Image.insert(url);
						});
					}
					else
					{
						if (mimetype[0] == 'application' && mimetype[1] == 'pdf') {
							
							$.get('/admin/assets/get_url/' + param.id, function(data){
								
								var url = $.trim(data);
								
								$.get('/admin/assets/get_download_html/' + param.id, function(data){
									
									var content = $.trim(data);

									PDF.insert(url, content);
								});
							});
						}
					}
				});
		},
		
		action_resize: function(param){
			
			function init(){

				var image = $(this), 
					id = image.data('id'),
					tab = $('#resize-' + id),
					width = this.width, 
					height = this.height,
					d = Image.getDimensions(width, height, 50),
					elem = {
						wrapper:		tab.find('.resize-image-wrapper'),
						loader: 		tab.find('.resize-image-loading'),
						slider: 		tab.find('.resize-slider'),
						resizeWidth: 	tab.find('.resize-image-dimension-width'),
						resizeHeight: 	tab.find('.resize-image-dimension-height'),
						widthMax: 		tab.find('.resize-image-width-max'),
						contents: 		tab.find('.resize-image-contents'),
						insertResized: 	$('#resize-' + id).find('.button-resize-insert')
					};

				this.width = d.width;
				elem.resizeWidth.html(d.width);
				elem.resizeHeight.html(d.height);

				elem.loader.hide();			
				elem.widthMax.html(width + 'px');			
				elem.contents.show();										
				elem.wrapper
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

				elem.insertResized.click(function(e){

					e.preventDefault();
					$.get('/admin/assets/get_image_url/' + id + '/' + d.width + '/' + d.height, function(data){				
						Image.insert($.trim(data));
					})
				});
			}

			$('<img />', {
				id: 'resize-image-' + param.id,
				'data-id': param.id
			})
			.load(init)
			.error(function(){			
				alert('There was an error loading the image.');
			})
			.attr('src', param.url);
		}	
	}
		
})(this, this.jQuery, this.tinyMCEPopup);