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

		// Recursively get a list of log files
		$logs = Kohana::list_files('logs');

		// Find the month directory name
		$log_year = array_pop( $logs );
		$log_month = array_pop( $log_year );

		// Build an array of log entries
		$log_entries = array();
		foreach($log_month as $day => $path){

			// Get log file contents and strip PHP tags
			$log_contents = trim(preg_replace('/<\?.*?\?>/', '', file_get_contents($path)));
		
			// Create array of log entries and append to messages array
			$log_entries = array_merge($log_entries, explode("\n", $log_contents));
		}

		$log_entries = array_reverse($log_entries);
	}

} // End Controller_Admin_Home
