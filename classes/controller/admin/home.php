<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Home extends Controller_Admin_Base {

	public function action_index()
	{
		$this->template->title = __('Admin');
		$this->template->content = View::factory('admin/page/home')
			->bind('db_config', $db_config)
			->bind('modules', $modules)
			->bind('db_size', $db_size)
			->bind('logs', $log_entries);

		// Get the database configuration
		$db_config = Kohana::config('database');
		$db_config = $db_config['default'];

		// Get an array of enabled modules
		$modules = Kohana::modules();

		// Get the total database size in MB
		$db_size = DB::query(
			Database::SELECT, 
			'SELECT 
			CONCAT(ROUND(SUM(((DATA_LENGTH + INDEX_LENGTH - DATA_FREE) / 1024 / 1024)),2)," MB") 
			AS size 
			FROM INFORMATION_SCHEMA.TABLES 
			WHERE TABLE_SCHEMA LIKE "'.$db_config['connection']['database'].'"'
			)->execute()->as_array();
		$db_size = $db_size['0']['size'];

		$log_entries = Admin_Log::latest_entries();
	}

} // End Controller_Admin_Home
