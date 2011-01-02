<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_UI extends Controller_Admin_Base {

	public function action_index()
	{
		$this->template->title = __('Admin - UI');
		$this->template->content = View::factory('admin/page/ui/index');		
	}

} // End Controller_Admin_UI
