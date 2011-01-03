<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Users extends Controller_Admin_Base {

	// Example: Set the controller crud model. Must match database table name.
	// public $crud_model = 'users';

	public function action_add()
	{
		$this->template->title = __('Add user');

		$this->template->content = View::factory('admin/page/users/add')
			->bind('roles', $roles)
			->bind('groups', $groups)
			->bind('errors', $errors);

		if (ORM::factory('user')->admin_add($_POST))
		{
			Message::set(Message::SUCCESS, __('User successfully saved.'));
			$this->request->redirect('admin/users');
		}
		
		$roles = ORM::factory('role')->find_all();
		$groups = ORM::factory('group')->find_all();
	
		if ($this->errors = $_POST->errors('auth'))
		{
			 Message::set(Message::ERROR, __('Please correct the errors.'));
		}

		$_POST = $_POST->as_array();
	}

	public function action_edit($id = 0)
	{
		$user = ORM::factory('user', (int) $id);

		! $user->loaded() AND $this->request->redirect('admin/users');
		
		$this->template->title = __('Edit user').' '.$user->username;

		// If POST is empty then set the default form data
		!$_POST AND $default_data = $user->as_array();

		// Bind user data to template
		$this->template->content = View::factory('admin/page/users/edit')
			->bind('roles', $roles)
			->bind('groups', $groups)
			->bind('user', $user)
			->bind('user_roles', $user_roles)
			->bind('user_groups', $user_groups)
			->bind('errors', $errors);

		// Find all roles
		$roles = ORM::factory('role')->find_all();
				
		// Create array of user role ids
		$user_roles = array();

		foreach($user->roles->find_all() as $role)
		{
			$user_roles[] = $role->id;
		}

		// Find all roles
		$groups = ORM::factory('group')->find_all();
				
		// Create array of user role ids
		$user_groups = array();

		foreach($user->groups->find_all() as $group)
		{
			$user_groups[] = $group->id;
		}
		
		// Try update the user, if succesful then reload the page
		if ($user->admin_update($_POST))
		{
			Message::set(Message::SUCCESS, __('User successfully updated.'));
			$this->request->redirect($this->request->uri);
		}
		
		if ($this->errors = $_POST->errors('profile'))
		{
 			Message::set(Message::ERROR, __('Please correct the errors.'));
		}

		// If POST is empty, then add the default data to POST
		isset($default_data) AND $_POST = array_merge($_POST->as_array(), $default_data);
	}

	public function action_delete($id = 0)
	{
		$id = (int) $id;

		// Don't delete user 1
		$id === 1 AND $this->request->redirect('403');

		// Try load the user
		$user = ORM::factory( inflector::singular($this->crud_model), (int) $id);

		!$user->loaded() AND $this->request->redirect('admin');

		// Remove the user's roles relationship
		foreach ($user->roles->find_all() as $role)
		{
			$user->remove('roles', $role);
		}

		// Delete the user
		$user->delete();

		Message::set(Message::SUCCESS, __('User successfully deleted.'));

		$this->request->redirect('admin/users');
	}
	
	public function action_tree()
	{
		$open_groups = Arr::get($_COOKIE, 'users/index', array());
		
		if ($open_groups)
		{
			$open_groups = explode(',', $open_groups);
		}

		$this->template->content = ORM::factory('group')->tree_list_html('admin/page/users/tree', 0, $open_groups);
	}

} // End Controller_Admin_users