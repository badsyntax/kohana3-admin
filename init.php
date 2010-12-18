<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Admin routes 
 */

// Admin media
Route::set('admin/media', 'admin/media(/<file>)', array('file' => '.+'))
	->defaults(array(
		'controller' => 'media',
		'directory'	=> 'admin',
		'file'       => NULL,
	));
	
// Assets
Route::set('admin-assets', 'admin/assets/get_asset(/<id>)(/<width>)(/<height>)(/<crop>)')
	->defaults(array(
		'action' => 'get_asset',
		'directory' => 'admin',
		'controller' => 'assets'
	));
// Admin Actions
Route::set('admin', 'admin/<controller>(/<action>)(/<id>)')
	->defaults(array(
		'action' => 'index',
		'directory' => 'admin'
	));
	
// Logs
Route::set('admin-logs', 'admin/logs(/<file>)', array('file' => '.+'))
	->defaults(array(
		'controller' => 'admin_logs',
		'action'     => 'index',	
		'file'	     => NULL
	));
	
	
// Admin home
Route::set('admin-home', 'admin')
	->defaults(array(
		'directory' => 'admin',
		'controller' => 'home',
		'action' => 'index'
	));