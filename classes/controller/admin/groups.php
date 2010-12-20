<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Groups extends Controller_Admin_Base {

	public function action_add()
	{
		$is_ajax = (bool) Request::$is_ajax;
				
		$this->template->title = __('Add group');

		$this->template->content = View::factory('admin/page/groups/add')
			->bind('errors', $errors)
			->bind('groups', $groups);
			
		$groups = ORM::factory('group')->tree_select(4, 0, array(__('None')));

		if (ORM::factory('group')->admin_create($_POST))
		{
			Message::set(Message::SUCCESS, __('Group successfully saved.'));
			$this->request->redirect('admin/groups');
		}

		if ( $errors = $_POST->errors('admin/groups'))
		{
			 Message::set(Message::ERROR, __('Please correct the errors.'));
		}

		$_POST = $_POST->as_array();
		
		if ( $is_ajax ) {

			$this->template->content = json_encode($errors);

			$this->request->headers['Content-Type'] = 'application/json';
		}
	}
	
	public function action_edit($id = 0)
	{
		$is_ajax = (bool) Request::$is_ajax;
				
		// Try get the group
		$group = ORM::factory('group', (int) $id);

		// If group doesn't exist then redirect to admin home
		! $group->loaded() AND $this->request->redirect('admin');

		$this->template->title = __('Group').' '.$group->name;

		// If POST is empty then set the default form data
		!$_POST AND $default_data = $group->as_array();

		$this->template->content = View::factory('admin/page/groups/edit')
			->bind('group', $group)
			->bind('groups', $groups)
			->bind('errors', $errors);
			
		$groups = ORM::factory('group')->tree_select(4, 0, array(__('None')));

		// Try update the group, if successful then reload the page
		if ($group->admin_update($_POST))
		{
			Message::set(Message::SUCCESS, __('Group successfully updated.'));
			 
			$this->request->redirect($this->request->uri);
		}

		// Get validation errors
		if ($errors = $_POST->errors('admin/group'))
		{
			Message::set(Message::ERROR, __('Please correct the errors.'));
		}

		// If POST is empty, then add the default data to POST
		isset($default_data) AND $_POST = array_merge($_POST->as_array(), $default_data);
		
		if ( $is_ajax ) {

			$this->template->content = json_encode($errors);

			$this->request->headers['Content-Type'] = 'application/json';
		}
	}

} // End Controller_Admin_Groups