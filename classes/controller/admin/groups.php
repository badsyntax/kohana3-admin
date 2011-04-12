<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Groups extends Controller_Admin_Base {

	public function action_add()
	{
		$this->template->title = __('Add group');

		$this->template->content = View::factory('admin/page/groups/add')
			->bind('errors', $this->errors)
			->bind('groups', $groups);
			
		$groups = ORM::factory('group')->tree_select(4, 0, array(__('None')));

		if (ORM::factory('group')->admin_create($_POST))
		{
			$this->validation_message =  __('Group successfully saved.');

			if (!$this->is_ajax)
			{
				Message::set(Message::SUCCESS, $this->validation_message);
				$this->request->redirect('admin/groups');
			}
		}

		if ($this->errors = $_POST->errors('admin/groups'))
		{
			$this->validation_message =  __('Please correct the errors.');

			if (!$this->is_ajax)
			{
				Message::set(Message::ERROR, $this->validation_message);
			}
		}

		$_POST = $_POST->as_array();
	}
	
	public function action_edit($id = 0)
	{
		$group = ORM::factory('group', (int) $id);

		// If group doesn't exist then redirect to admin home
		if (!$group->loaded())
		{
			$this->request->redirect('admin');
		}

		$this->template->title = __('Group').' '.$group->name;

		// If POST is empty then set the default form data
		if (!$_POST)
		{
			$default_data = $group->as_array();
		}

		$this->template->content = View::factory('admin/page/groups/edit')
			->bind('group', $group)
			->bind('groups', $groups)
			->bind('errors', $this->errors);
			
		$groups = ORM::factory('group')->tree_select(4, 0, array(__('None')));

		// Try update the group, if successful then reload the page
		if ($group->admin_update($_POST))
		{
			$this->validation_message = __('Group successfully updated.');

			if (!$this->is_ajax)
			{
				Message::set(Message::SUCCESS, $this->validation_message);
			 	$this->request->redirect($this->request->uri);
			}
		}

		// Get validation errors
		if ($this->errors = $_POST->errors('admin/groups'))
		{
			$this->validation_message = __('Please correct the errors.');

			if (!$this->is_ajax)
			{
				Message::set(Message::ERROR, $this->validation_message);
			}
		}

		// If POST is empty, then add the default data to POST
		isset($default_data) AND $_POST = array_merge($_POST->as_array(), $default_data);
	}
	
	public function action_tree()
	{
		$open_groups = Arr::get($_COOKIE, 'groups/index', array());
		
		if ($open_groups)
		{
			$open_groups = explode(',', $open_groups);
		}

		$tree_html = ORM::factory('group')->tree_list_html('admin/page/users/tree', 0, $open_groups);

		$this->template->content = View::factory('admin/page/users/tree')
			->set('tree_html', $tree_html);
	}

} // End Controller_Admin_Groups
