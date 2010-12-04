<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Logs extends Controller_Admin_Base {

	public function action_index($file = NULL)
	{
		$this->template->title = __('Admin - Logs');
		$this->template->content = View::factory('admin/page/logs')
			->bind('directories', $directories)
			->bind('entries', $entries)
			->bind('total_files', $total_files);

		$cur_month = $cur_year = NULL;
		$total_files = 0;

		$entries = $this->get_entries($file, $cur_year, $cur_month);

		$logs = Kohana::list_files('logs');

		$directories = $this->get_directories_html($logs, $cur_year, $cur_month, $total_files);
	}

	private function get_entries(& $file = NULL, & $cur_year = NULL, & $cur_month = NULL, & $cur_day = NULL)
	{
		$entries = NULL;

		if ($file !== NULL)
		{
			// Strip extension from file name
			$file = preg_replace('/\..*?$/', '', $file);

			// Find the full path to the file
			$path = Kohana::find_file('logs', $file);

			if ($path !== FALSE)
			{
				// Get file contents
				$contents = trim(strip_tags(file_get_contents($path)));

				$entries = explode("\n", $contents);
			}

			list($cur_year, $cur_month, $cur_day) = explode(DIRECTORY_SEPARATOR, $file);
		}

		return $entries;
	}

	private function get_directories_html($logs, $cur_year = NULL, $cur_month = NULL, & $total_files = 0)
	{
		$html = '<ol>';
		foreach($logs as $year => $months){
			
			$year_trim = str_replace('logs/', '', $year); 
			$attributes = ($year_trim == $cur_year) ? array('class' => 'selected open') : NULL; 

			$html .= '<li>' .HTML::anchor('#year', $year_trim, $attributes) . '<ol'.HTML::attributes($attributes).'>';

			foreach($months as $month => $day){

				$month_trim = str_replace("logs/{$year_trim}/", '', $month);
				$month_name = date('F', mktime(0, 0, 0, $month_trim, 1, date('Y')));
				$attributes = ($month_trim == $cur_month) ? array('class' => 'selected open') : NULL;

				$html .= '<li>' . HTML::anchor('#month', $month_name, $attributes) . '<ol'.HTML::attributes($attributes).'>';

				foreach($day as $log){
					$total_files += 1;
					$html .= '<li>';
					$html .= HTML::anchor('admin/'.$month.'/'.basename($log), preg_replace('/.*?(\d+)'.EXT.'$/', '$1', $log));
					$html .= '</li>';
				}
				$html .= '</ol></li>';
			}
			$html .= '</ol></li>';
		} 
		$html .= '</ol>';

		return $html;
	}

} // End Controller_Admin_Logs
