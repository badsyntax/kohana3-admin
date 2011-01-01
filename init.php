<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Admin routes 
 */

// Admin media
Route::set('admin/media', 'admin/media(/<file>)', array('file' => '.+'))
	->defaults(array(
		'controller' 	=> 'media',
		'directory'		=> 'admin',
		'file'       	=> NULL,
	));
	
// Admin Assets - get asset
Route::set('admin/get-asset', 'admin/assets/get_asset(/<id>)(/<width>)(/<height>)(/<crop>)')
	->defaults(array(
		'action' 		=> 'get_asset',
		'directory' 	=> 'admin',
		'controller' 	=> 'assets'
	));
	
// Admin Assets - get image url
Route::set('admin/get-asset', 'admin/assets/get_image_url/(<id>)(/<width>)(/<height>)')
	->defaults(array(
		'action' 		=> 'get_image_url',
		'directory' 	=> 'admin',
		'controller' 	=> 'assets'
	));

// Global media assets
Route::set('media/assets', 'media/assets/resized/(<id>_<width>_<height>_<crop>_<filename>)', array(
		'id' 			=> '\d+',
		'width' 		=> '\d+',
		'height' 		=> '\d+',
		'crop'			=> '\d+',
		'filename' 		=> '.+'
	))
	->defaults(array(
		'directory'		=> 'admin',
		'controller'	=> 'assets',
		'action'		=> 'get_asset',
		'id'			=> 0,
		'width'			=> NULL,
		'height'		=> NULL,
		'crop'			=> NULL,	
		'filename'			=> NULL,
	));
	
// Admin popup assets
Route::set('admin/popup-assets', 'admin/assets/popup(/<action>)(/<id>)')
	->defaults(array(
		'controller' 	=> 'assets_popup',
		'directory' 	=> 'admin',
	));
	
// Admin Actions
Route::set('admin', 'admin/<controller>(/<action>)(/<id>)')
	->defaults(array(
		'action' 		=> 'index',
		'directory' 	=> 'admin'
	));
	
// Admin logs
Route::set('admin/logs', 'admin/logs(/<file>)', array('file' => '.+'))
	->defaults(array(
		'controller' 	=> 'admin_logs',
		'action'     	=> 'index',	
		'file'	     	=> NULL
	));
	
// Admin home
Route::set('admin-home', 'admin')
	->defaults(array(
		'directory' 	=> 'admin',
		'controller' 	=> 'home',
		'action' 		=> 'index'
	));