<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Logs extends Controller_Admin_Base {

	public function action_index()
	{
		$this->template->title = __('Admin - Logs');
		$this->template->content = View::factory('admin/page/logs')
			->bind('logs', $logs);

		$logs = Kohana::list_files('logs');
	}
}
