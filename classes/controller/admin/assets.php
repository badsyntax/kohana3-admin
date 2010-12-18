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

	public function action_upload()
	{
		$this->template->title = __('Admin - Upload assets');
		$this->template->content = View::factory('admin/page/assets/upload')
			->bind('errors', $errors);
			
		if (ORM::factory('asset')->upload_admin('asset', $_POST, $_FILES))
		{
			Message::set(Message::SUCCESS, __('Asset successfully saved.'));

			$this->request->redirect('admin/assets');
		}
	
		$errors = array_merge($_POST->errors('assets'), $_FILES->errors('assets'));
			
		if ($errors)
		{
			Message::set(Message::ERROR, __('Please correct the errors.'));
		}

		$_POST = $_POST->as_array();			
	}
	
	public function action_edit($id = 0)
	{
		$asset = ORM::factory('asset', (int) $id);

		if (!$asset->loaded())
		{
			Message::set(MESSAGE::ERROR, __('Asset not found.'));

			$this->response->redirect('admin');
		} 
		
		// If POST is empty then set the default form data
		!$_POST AND $default_data = $asset->as_array();

		$this->template->title = __('Admin - Edit asset');
		$this->template->content = View::factory('admin/page/assets/edit')
			->bind('asset', $asset)
			->bind('errors', $errors);
			
		if (ORM::factory('asset')->update_admin($_POST))
		{
			Message::set(Message::SUCCESS, __('Asset successfully updated.'));
			
			$this->request->redirect($this->request->uri);
		}
		
		if ($errors = $_POST->errors('assets'))
		{
			Messages::set(MESSAGE::ERROR, __('Please correct the errors.'));
		}

		// If POST is empty, then add the default data to POST
		isset($default_data) AND $_POST = array_merge($_POST->as_array(), $default_data);
		
	}

} // End Controller_Admin_Assets