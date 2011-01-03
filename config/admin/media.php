<?php defined('SYSPATH') or die('No direct script access.');

return array(
	'styles' => array(
		'media/css/base.css',
		'modules/admin/media/js/jquery-ui/build/dist/jquery-ui-1.9pre/themes/base/jquery-ui.css',
		'modules/admin/media/css/jquery.ui.theme.admin.css',
		'modules/admin/media/css/admin.css'
	),
	'scripts' => array(
		'media/js/jquery-1.4.4.min.js',
		'modules/admin/media/js/jquery-ui/build/dist/jquery-ui-1.9pre/ui/jquery-ui.js',
		'modules/admin/media/js/jquery.ui.selectmenu.js',
		'modules/admin/media/js/jquery-tree/js/jquery.tree.js',
		'modules/admin/media/js/require.js',
		'modules/admin/media/js/admin.js',
		'modules/admin/media/js/admin.controllers.js',
		'modules/admin/media/js/admin.models.js',
		'modules/admin/media/js/admin.util.js',
	),
	'paths' => array(
		'base' => 'admin',
		'tinymce_skin' => 'modules/admin/media/js/tinymce/jscripts/tiny_mce/themes/advanced/skins/cirkuit/ui.css',
		'tinymce' => 'modules/admin/media/js/tinymce/jscripts/tiny_mce/tiny_mce.js',
		'tinymce_popup' => 'modules/admin/media/js/tinymce/jscripts/tiny_mce/tiny_mce_popup.js',
		'tinymce_init' => 'admin/media/js/wysiwyg.init.js'
	)		
);
