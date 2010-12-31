<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Wysiwyg extends Controller_Admin_Base {

	public function action_index()
	{
		$this->template->title = __('Admin - Wysiwyg');
		$this->template->content = View::factory('admin/page/wysiwyg/index');
		
		// Add wysiwyg script paths
		array_push($this->template->styles, Kohana::config('admin/media.paths.tinymce_skin'));

	}

} // End Controller_Admin_Wysiwyg
