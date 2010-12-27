<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Logs extends Controller_Admin_Base {

	public function action_index($file = NULL)
	{
		$this->template->title = __('Admin - Logs');
		$this->template->content = View::factory('admin/page/logs/index')
			->bind('directories', $directories)
			->bind('entries', $entries)
			->bind('total_files', $total_files);

		$cur_month = $cur_year = NULL;

		$total_files = 0;

		$entries = Admin_Log::get_entries($file, $cur_year, $cur_month);

		if ($entries !== NULL)
		{
			$entries = Admin_Log::format_entries($entries);
		}

		$directories = Admin_Log::get_directories_html($cur_year, $cur_month, $total_files);
	}

	public function action_download($format = 'tar')
	{
		if ($format === 'tar')
		{
			$dir = APPPATH . 'logs';

			$time = time();

			// Build the filename
			$file = "/tmp/{$time}/site-logs.{$time}.tar.gz";

			// Generate an archive of the logs directory
			`mkdir -p /tmp/{$time} && cp -r {$dir} /tmp/{$time} && cd /tmp/{$time} && tar cfvz {$file} logs`;

			// Send the file for download and delete from filesystem
			$this->request->send_file($file, NULL, array('delete' => TRUE));
		}
	}

} // End Controller_Admin_Logs
