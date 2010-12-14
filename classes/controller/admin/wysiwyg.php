<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Wysiwyg extends Controller_Admin_Base {

	public function action_index()
	{
		$this->template->title = __('Admin - Wysiwyg');
		$this->template->content = View::factory('admin/page/wysiwyg');
		
		// Add wysiwyg script paths
		array_push($this->template->scripts, 'modules/admin/media/js/tinymce/jscripts/tiny_mce/tiny_mce.js');
		array_push($this->template->scripts, 'modules/admin/media/js/wysiwyg.init.js');
	}

} // End Controller_Admin_Wysiwyg
