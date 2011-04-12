<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Roles extends Controller_Admin_Base {

	public function action_add()
	{
		$this->template->title = __('Add role');

		$this->template->content = View::factory('admin/page/roles/add')
			->bind('errors', $errors);

		if (ORM::factory('role')->admin_create($_POST))
		{
			Message::set(Message::SUCCESS, __('Role successfully saved.'));			
			!$this->is_ajax AND $this->request->redirect('admin/roles');
		}

		if ($this->errors = $_POST->errors('admin/roles'))
		{
			 Message::set(Message::ERROR, __('Please correct the errors.'));
		}

		$_POST = $_POST->as_array();
	}

	public function action_edit($id = 0)
	{
		// Try get the role
		$role = ORM::factory('role', (int) $id);

		// If role doesn't exist then redirect to admin home
		if (!$role->loaded())
		{
			$this->request->redirect('admin/roles');
		}

		$this->template->title = __('Role user').' '.$role->name;

		// If POST is empty then set the default form data
		if (!$_POST){
			$default_data = $role->as_array();
		}

		// Bind role data to template
		$this->template->content = View::factory('admin/page/roles/edit')
			->bind('role', $role)
			->bind('errors', $this->errors);

		// Try update the role, if successful then reload the page
		if ($role->admin_update($_POST))
		{
			$this->validation_message =  __('Role successfully updated.');

			if (!$this->is_ajax)
			{
				Message::set(Message::SUCCESS, $this->validation_message);			 
				$this->request->redirect($this->request->uri);
			}
		}

		// Errors
		if ($this->errors = $_POST->errors('admin/roles'))
		{
			$this->validation_message =  __('Please correct the errors.');

			if (!$this->is_ajax)
			{
				Message::set(Message::ERROR, $validation_message);
			}
		}

		// If POST is empty, then add the default data to POST
		if (isset($default_data))
		{
			$_POST = array_merge($_POST->as_array(), $default_data);
		}
	}

} // End Controller_Admin_Roles
