<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Assets extends Controller_Admin_Base {

	public function before()
	{
			if (Arr::get($_REQUEST, 'tinymce', FALSE) !== FALSE)
			{
				$this->template = 'admin/page/assets/popup/master_page';
			}
			
			parent::before();
	}
	
	public function after()
	{
		array_push($this->template->styles, 'modules/admin/media/css/assetmanager.css');		
		array_push($this->template->scripts, 'modules/admin/media/js/assetmanager.js');
		
		parent::after();
	}

	public function action_index()
	{
		$this->template->title = __('Admin - Assets');
		$this->template->content = View::factory('admin/page/assets');
	}

} // End Controller_Admin_Config
