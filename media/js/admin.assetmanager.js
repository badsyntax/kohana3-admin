/**
 * @filename : admin.assetmanager.js
 * @developer : badsyntax.co
 * This is the popup asset manager controller file.
 * The popup asset manager is displayed in an iFrame (window.parent.Admin should exist)
 */
(function(window, $, tinyMCEPopup){

	if (!window.Admin || !tinyMCEPopup) return;
	
	var 
		Admin = window.Admin, 
		cons = Admin.cons,
		loader = window.parent.$('.ui-dialog.ui-dialog-tinymce .ui-dialog-titlebar .ajax-loader')
	;
	
	var Tabs = {
		
		create: function(url, id, title, callback) {
			
			title = title || 'Tab';

			if (!$('#'+id).length){
				// Create a new tab
				Admin.elements.tabs.tabs('add', '#' + id, title);
			}

			Admin.elements.tabs.tabs("select" , id);

			Admin.util.ajax.loader(cons.BEGIN, loader);
					
			// Load the tab content
			$('#' + id)
			.html('<p>Loading content...</p>')
			.load(url, function(){
				$(this).ui();
				Admin.util.ajax.loader(cons.END, loader);
				Admin.util.trigger(this, callback);				
			});
		}
	};
	
	var Asset = {
		
		allowedExt: {
			image: [ 'png', 'jpg', 'jpeg', 'gif' ]
		},
		
		insert: function(path, content, type, description) {
			
			var self = this,
				win = tinyMCEPopup.getWindowArg("window"),
				ext = path.split('.').pop().toLowerCase();

			function insertIntoDialog() {

				var fieldId = tinyMCEPopup.getWindowArg('input');

				win.document.getElementById(fieldId).value = path;

				// are we an image browser?
				if (typeof(win.ImageDialog) != "undefined") {

					if ($.inArray(ext, self.allowedExt['image']) === -1) {
						Admin.util.dialog.alert('Attention', 'Please select an image to insert.');
						return;
					}
					
					// Update the description field
					win.document.getElementById('alt').value = description;

					// we are, so update image dimensions...
					(win.ImageDialog.getImageData) && win.ImageDialog.getImageData();

					// ... and preview if necessary
					(win.ImageDialog.showPreviewImage) && win.ImageDialog.showPreviewImage(path);
				}

				// close popup window
				tinyMCEPopup.close();				
			}

			function insertIntoWysiwyg() {

				if (type != 'image'){
					var ed = tinyMCEPopup.editor, 
						el = ed.selection.getNode();

					tinyMCEPopup.restoreSelection();

					// Fixes crash in Safari
					(tinymce.isWebKit) && ed.getWin().focus();
			
					ed.execCommand('mceInsertContent', false, content);
					ed.undoManager.add();

					// close popup window
					tinyMCEPopup.close();
				} else {		
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

			function insert() {
				(win)
				? insertIntoDialog()
				: insertIntoWysiwyg();
			}

			if (type === 'image') {

				Admin.util.ajax.loader(cons.BEGIN, loader);

				$('<img />')
				.load(insert)
				.error(function(){
					Admin.util.ajax.loader(cons.END, loader);
					alert('There was an error loading the image. Please try again.');
				})
				.attr('src', path);

			} else insert();
		}
	};
		
	Admin.controller.assets_popup = {
		
		before: function(){

			var self = this;
			
			// Stop the loader spinner
			Admin.util.ajax.loader(cons.END, loader);
			
			// Show the messages
			$('#messages').children().length 
				&& $('#messages').show();
	
			Admin.elements.tabs
				// Add tabs event handlers	
				.bind('tabsshow', function(event, ui) {  

					// Tab name
					var tab = $.trim($(ui.tab).text()).toLowerCase();

					// If selecting the 'browse' tab then show the pagination links, else hide them
					$('#page-links')[ tab === 'browse' ? 'show' : 'hide' ]();

					if ($('#upload-asset').data('button')){
						// Ensure the upload button is enabled
						$('#upload-asset').button('enable');
					}

					switch(tab){
						case 'upload': self.action_upload(); break;
					}	
				})
				.tabs({
					tabTemplate: '<li><a href="#{href}">#{label}</a> <span class="ui-icon ui-icon-close">Remove Tab</span></li>'
				});
				
			// close icon: removing the tab on click
			Admin.elements.tabs
				.find("span.ui-icon-close")
				.live("click", function(){
					var index = $('li', Admin.elements.tabs).index( $(this).parent() );
					Admin.elements.tabs.tabs('remove', index);
				});

			// Show loading spinner on anchors click (fake an ajax request)
			$('#page-links').find('a').click(function(event){
				Admin.util.ajax.loader(cons.BEGIN, loader);
			});
			$('#browse').find('th a').click(function(event){
				Admin.util.ajax.loader(cons.BEGIN, loader);
			});
		},

		after: function(){
			Admin.util.dialog.alert('Attention', $('#messages').html());	
		},

		action_upload: function(){
			$('#upload-form')
			.unbind('submit')
			.bind('submit', function(event){
				Admin.util.ajax.loader(cons.BEGIN, loader);
				$('#upload-asset').button('disable');
			});
		},
		
		action_index: function(){
			
			var self = this;
			
			// Asset click handler
			function preview(e) {

				e.preventDefault();

				var anchor = $(this), 
					id = anchor.data('id');
			
				Tabs.create(this.href, 'preview-' + id, 'Preview', function(){		
					self.action_view({
						id: id,
						mimetype: anchor.data('mimetype'),
						filename: anchor.data('filename')
					});					
				});	
			}
			
			$('#browse').find('tbody').delegate('a.asset', 'click', preview);
			
			$('table').tableScroll({
				height: 350, 
				width: 'auto'
			});					
		},
		
		action_view: function(param){

			if (!param.id || !param.filename || !param.mimetype) return;
					
			var win, self = this, 
				mimetype = param.mimetype.split('/');						
			
			// Click handler
			function loadResizeTab(e){
				e.preventDefault();
				Admin.util.ajax.loader(cons.BEGIN, loader);			
				$.get('/admin/assets/get_url/' + param.id, function(data){
					var url = $.trim(data);
					if (url) {
						Tabs.create('/admin/assets/popup/resize/' + param.id, 'resize-' + param.id, 'Resize', function(){
							self.action_resize({id: param.id || 0, url: url});	
						});
					}				
				});				
			}
			
			// Click handler
			function insertAsset(e){
				e.preventDefault();
				
				if (mimetype[0] == 'image') {
					
					var width = Number($.trim($('#preview-' + param.id).find('.asset-width').text())),
						height = Number($.trim($('#preview-' + param.id).find('.asset-height').text()));
						
					function insert(){
						Admin.util.ajax.loader(cons.BEGIN, loader);
						$.get('/admin/assets/get_url/' + param.id, function(data){
							var url = $.trim(data);
							Asset.insert(url, null, mimetype[0]);
						});
					}
					
					if (width > 1000 || height > 1000) {
						tinyMCEPopup.editor.windowManager.confirm('The image is larger than 1000 x 1000px, are you sure you want to insert it at this size?', function(s){
							if (s) { insert(); }
						});
					} else insert();
					
				} else {
					$.get('/admin/assets/get_url/' + param.id, function(url){
						$.get('/admin/assets/get_download_html/' + param.id, function(content){
							Asset.insert($.trim(url), $.trim(content), mimetype[0]);
						});
					});
				}
			}

			function hideSpinner(){
				Admin.util.ajax.loader(cons.END, loader);
			}
					
			(!$('#preview-' + param.id + ' .thumb img')[0].complete) &&
				Admin.util.ajax.loader(cons.BEGIN, loader);
						
			$('#preview-' + param.id)

				// Hide ajax spinner
				.find('.thumb img')
				.load(hideSpinner)
				.error(hideSpinner)
				.end()

				// Popup lightbox
				.find('.popup-ui-lightbox')
				.lightbox({
					win: window.parent,
					loader_start: function(){
						Admin.util.ajax.loader(cons.BEGIN, loader);
					},
					loader_end: function(){
						Admin.util.ajax.loader(cons.END, loader);
					}
				})
				.end()

				// Insert resized image
				.find('.resize-asset')
				.click(loadResizeTab)
				.end()

				// Insert asset
				.find('.insert-asset')
				.click(insertAsset);
		},
		
		action_resize: function(param){
		
			function getImageDimensions(width, height, percent){			
			
				var w = (width / 100) * percent,
					h = (height / 100) * ( (w / width) * 100 );		
					
				return {
					width: Math.round(w),
					height: Math.round(h)
				};
			}
	
			function init(){
				
				Admin.util.ajax.loader(cons.END, loader);

				var image = $(this), 
					id = image.data('id'),
					tab = $('#resize-' + id),
					width = this.width, 
					height = this.height,
					d = getImageDimensions(width, height, 50),
					elem = {
						wrapper:	tab.find('.resize-image-wrapper'),
						loader:		tab.find('.resize-image-loading'),
						slider:		tab.find('.resize-slider'),
						resizeWidth:	tab.find('.resize-image-dimension-width'),
						resizeHeight:	tab.find('.resize-image-dimension-height'),
						widthMax:	tab.find('.resize-image-width-max'),
						contents:	tab.find('.resize-image-contents'),
						insertResized:	$('#resize-' + id).find('.button-resize-insert')
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
						d = getImageDimensions(width, height, ui.value);														
						elem.resizeWidth.html(d.width);
						elem.resizeHeight.html(d.height);
						image.attr('width', d.width)
					}
				});

				elem.insertResized.click(function(e){
					e.preventDefault();
					Admin.util.ajax.loader(cons.BEGIN, loader);
					elem.insertResized.after(' Generating image...');
					$.get('/admin/assets/get_image_url/' + id + '/' + d.width + '/' + d.height, function(data){				
						Asset.insert($.trim(data), null, 'image');
					})
				});
			}

			// Ensure the image is loaded before building the resize container
			Admin.util.ajax.loader(cons.BEGIN, loader);
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
