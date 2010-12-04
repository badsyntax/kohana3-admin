<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Logs extends Controller_Admin_Base {

	public function action_index($file = NULL)
	{
		$this->template->title = __('Admin - Logs');
		$this->template->content = View::factory('admin/page/logs')
			->bind('directories', $directories)
			->bind('entries', $entries);

		$cur_month =
		$cur_year = NULL;

		$entries = $this->get_entries($file, $cur_year, $cur_month);

		$logs = Kohana::list_files('logs');

		$directories = $this->get_directories_html($logs, $cur_year, $cur_month);
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

	private function get_directories_html($logs, $cur_year = NULL, $cur_month = NULL)
	{
		$html = '<ol>';
		foreach($logs as $year => $months){

			$year_trim = str_replace('logs/', '', $year); 
			$attributes = ($year_trim == $cur_year) ? array('class' => 'selected open') : NULL; 

			$html .= HTML::anchor('#year', $year_trim, $attributes);

			if (is_array($months)){

				$html .= '<ol'.HTML::attributes($attributes).'>';
				foreach($months as $month => $day){

					$month_trim = str_replace("logs/{$year_trim}/", '', $month);
					$month_name = date('F', mktime(0, 0, 0, (int) str_replace($year.'/', '', $month_trim), 1, date('Y')));
					$attributes = ($month_trim == $cur_month) ? array('class' => 'selected open') : NULL;

					$html .= '<li>';

					$html .= HTML::anchor('#month', $month_name, $attributes);

					if (is_array($day)){

						$html .= '<ol'.HTML::attributes($attributes).'>';
						foreach($day as $log){
							$html .= '<li>';
							$html .= HTML::anchor('admin/'.$month.'/'.basename($log), preg_replace('/.*?(\d+)'.EXT.'$/', '$1', $log));
							$html .= '</li>';
						}
						$html .= '</ol>';
					}
					$html .= '</li>';
				}
				$html .= '</ol>';
			}
			$html .= '</li>';
		}
		$html .= '</ol>';

		return $html;
	}

} // End Controller_Admin_Logs
