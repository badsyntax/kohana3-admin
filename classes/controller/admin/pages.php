<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Pages extends Controller_Admin_Base {

	public function action_index()
	{
		$this->template->title = __('Pages');
		$this->template->content = View::factory('admin/page/pages')
			->bind('page_tree', $page_tree);
		
		$page_tree = ORM::factory('page')->tree_list_html('admin/page/pages/tree');
	}

	public function action_add($parent_id = 0)
	{
		$this->template->title = __('Add page');
		
	
		$this->template->content = View::factory('admin/page/pages/add')
			->bind('pages', $pages)
			->set('parent_id', Arr::get($_POST, 'parent_id', $parent_id))
			->bind('errors', $errors);

		// Add tinymce script path
		array_push($this->template->scripts, 'modules/admin/media/js/tinymce/jscripts/tiny_mce/tiny_mce.js');
		
		$pages = ORM::factory('page')->tree_select();
		
		array_unshift($pages, __('None'));
	
		if ($page = ORM::factory('page')->add_admin($_POST))
		{
			Message::set(Message::SUCCESS, __('Page saved.'));
			
			$this->request->redirect('admin/pages');
		}
		
		if ($errors = $_POST->errors('admin'))
		{
			 Message::set(Message::ERROR, __('Please correct the errors.'));
		}

		$_POST = $_POST->as_array();
	}
	
	public function action_edit($id = 0)
	{
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
			
		// Add tinymce script path
		array_push($this->template->scripts, 'modules/admin/media/js/tinymce/jscripts/tiny_mce/tiny_mce.js');
		
		$pages = ORM::factory('page')->tree_select();
	
		array_unshift($pages, __('None'));
			
		if ($page->update_admin($_POST))
		{
			Activity::set(Activity::SUCCESS, __('Page updated: :title', array(':title' => $_POST['title'])));	
			Message::set(Message::SUCCESS, __('Page successfully updated.'));
			
			$this->request->redirect($this->request->uri);
		}
		
		if ($errors = $_POST->errors('pages'))
		{
 			Message::set(Message::ERROR, __('Please correct the errors.'));
		}

		// Add the default data to POST
		isset($default_data) AND $_POST = array_merge($_POST->as_array(), $default_data);
	}

} // End Controller_Admin_Pages