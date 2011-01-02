<?php defined('SYSPATH') or die('No direct script access.');

class Admin_Log {

	// Returns a list of logs ordered by date in reverse
	public static function latest_entries()
	{
		// Recursively get a list of log files
		$logs = Kohana::list_files('logs');

		// Find the month directory name
		$log_year = array_pop( $logs );
		$log_month = array_pop( $log_year );

		// Build an array of log entries
		$entries = array();
		foreach($log_month as $day => $path)
		{
			$path = str_replace(APPPATH.'logs/', '', $path);

			// Create array of log entries and merge it in
			$entries = array_merge($entries, self::get_entries($path));
		}

		return static::format_entries($entries);
	}

	public static function format_entries($entries = array())
	{
		$entries = array_reverse($entries);

		// Split the entry details into useful arrays
		foreach($entries as $key => $entry)
		{
			if (!strstr($entry, '---')) continue;
				
			list($date, $log) = explode('---', $entry);
			list($date, $time) = explode(' ', $date);
			list($year, $month, $day) = explode('-', $date);
			list($hour, $minute, $second) = explode(':', $time);

			$entries[$key] = array(
				'date'		=> trim($date),
				'log'		=> trim($log),
				'path'		=> "{$year}/{$month}/{$day}.php",
				'timestamp'	=> (string) mktime($hour, $minute, $second, $month, $day, $year)
			);
		}

		return $entries;
	}

	// Returns a list of log entries for a specified day ($file)
	public static function get_entries(& $file = NULL, & $cur_year = NULL, & $cur_month = NULL, & $cur_day = NULL)
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

	// Returns a HTML list of the log directories
	public static function get_directories_html($cur_year = NULL, $cur_month = NULL, & $total_files = 0)
	{
		$logs = Kohana::list_files('logs');

		$html = '<ol>';
		foreach($logs as $year => $months)
		{
			$year_trim = str_replace('logs/', '', $year);
			$attributes = ($year_trim == $cur_year) ? array('class' => 'selected open') : NULL;

			$html .= '<li>' .HTML::anchor('#year', $year_trim, $attributes) . '<ol'.HTML::attributes($attributes).'>';

			foreach($months as $month => $day)
			{
				$month_trim = str_replace("logs/{$year_trim}/", '', $month);
				$month_name = date('F', mktime(0, 0, 0, $month_trim, 1, date('Y')));
				$attributes = ($month_trim == $cur_month) ? array('class' => 'selected open') : NULL;

				$html .= '<li>' . HTML::anchor('#month', $month_name, $attributes) . '<ol'.HTML::attributes($attributes).'>';

				foreach($day as $log)
				{
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

} // End Admin_Log 
