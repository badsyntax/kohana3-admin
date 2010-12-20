<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Pages extends Controller_Admin_Base {

	public function action_index()
	{
		$this->template->title = __('Pages');
		$this->template->content = View::factory('admin/page/pages/index');
	}

	public function action_add($parent_id = 0)
	{
		$this->template->title = __('Add page');
		
		$this->template->content = View::factory('admin/page/pages/add')
			->bind('pages', $pages)
			->set('parent_id', Arr::get($_POST, 'parent_id', $parent_id))
			->bind('errors', $errors);

		$pages = ORM::factory('page')->tree_select(4, 0, array(__('None')));

		array_push($this->template->styles, Kohana::config('admin/media.paths.tinymce_skin'));
		
		if ($page = ORM::factory('page')->admin_add($_POST))
		{
			Message::set(Message::SUCCESS, __('Page saved.'));
			$this->request->redirect('admin/pages');
		}
		
		if ($errors = $_POST->errors('admin'))
		{
			 Message::set(Message::ERROR, __('Please correct the errors.'));
		}
		
		$_POST = $_POST->as_array();
		
		if ( Request::$is_ajax ) {

			$this->template->content = json_encode($errors);

			$this->request->headers['Content-Type'] = 'application/json';
		}
	}
	
	public function action_tree()
	{
		$this->template->content = ORM::factory('page')->tree_list_html('admin/page/pages/tree');
	}
	
	public function action_edit($id = 0)
	{
		$is_ajax = (bool) Request::$is_ajax;
				
		$page = ORM::factory('page', (int) $id);

		!$page->loaded() AND $this->request->redirect('admin');

		$this->template->title = __('Edit page');

		// If POST is empty then set the default form data
		!$_POST AND $default_data = $page->as_array();

		// Bind page data to template
		$this->template->content = View::factory('admin/page/pages/edit')
			->bind('page', $page)
			->bind('pages', $pages)
			->bind('errors', $errors);
			
		$pages = ORM::factory('page')->tree_select(4, 0, array(__('None')));
		
		array_push($this->template->styles, Kohana::config('admin/media.paths.tinymce_skin'));
		
		if ($page->admin_update($_POST))
		{
			Message::set(Message::SUCCESS, __('Page successfully updated.'));
			
			$this->request->redirect($this->request->uri);
		}
		
		if ($errors = $_POST->errors('pages'))
		{
 			Message::set(Message::ERROR, __('Please correct the errors.'));
		}

		// Add the default data to POST
		isset($default_data) AND $_POST = array_merge($_POST->as_array(), $default_data);

		if ( $is_ajax ) {

			$this->template->content = json_encode($errors);

			$this->request->headers['Content-Type'] = 'application/json';
		}
	}

} // End Controller_Admin_Pages