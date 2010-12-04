<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Home extends Controller_Admin_Base {

	public function action_index()
	{
		$this->template->title = __('Admin');
		$this->template->content = View::factory('admin/page/home')
			->bind('db_config', $db_config)
			->bind('modules', $modules)
			->bind('db_size', $db_size)
			->bind('logs', $log_messages);

		$db_config = Kohana::config('database');
		$db_config = $db_config['default'];

		$modules  = Kohana::modules();

		$db_size = DB::query(
			Database::SELECT, 
			'SELECT 
			CONCAT(ROUND(SUM(((DATA_LENGTH + INDEX_LENGTH - DATA_FREE) / 1024 / 1024)),2)," MB") 
			AS size 
			FROM INFORMATION_SCHEMA.TABLES 
			WHERE TABLE_SCHEMA LIKE "'.$db_config['connection']['database'].'"'
			)->execute()->as_array();
		$db_size = $db_size['0']['size'];

		$logs = Kohana::list_files('logs');
		$log_year = array_pop( $logs );
		$log_month = array_pop( $log_year );
		$log_messages = array();

		foreach($log_month as $day => $path){

			$log_contents = trim(preg_replace('/<\?.*?\?>/', '', file_get_contents($path)));
		
			$log_messages = array_merge($log_messages, explode("\n", $log_contents));
		}

		$log_messages = array_reverse($log_messages);
	}
}
