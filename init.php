<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Admin routes 
 */

// Admin home
Route::set('admin-home', 'admin')
	->defaults(array(
		'directory' => 'admin',
		'controller' => 'home',
		'action' => 'index'
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
