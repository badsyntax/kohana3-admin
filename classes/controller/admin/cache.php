<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Cache extends Controller_Admin_Base {

	public function action_index()
	{
		$this->template->title = __('Admin - Cache');
		$this->template->content = View::factory('admin/page/cache/index')
			->bind('cache_dir', $cache_dir)
			->bind('total_size', $total_size)
			->bind('total_files', $total_files);

		$cache_dir = Kohana::$cache_dir;
		$total_size = Text::bytes( (int) `du -sb {$cache_dir} | sed 's/\s.*$//g'`);
		$total_files = `find {$cache_dir} -type f | wc -l`;
	}

	public function action_purge()
	{
		Cache::instance()->delete_all();

		Message::set(Message::SUCCESS, __('Cache successfully deleted.'));

		$this->request->redirect('admin/cache');
	}

} // End Controller_Admin_Cache