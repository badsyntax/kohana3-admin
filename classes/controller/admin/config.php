<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Config extends Controller_Admin_Base {

	public function action_index($db_config = NULL)
	{
		$this->template->title = __('Admin - Config');
		$this->template->content = View::factory('admin/page/config/index')
			->bind('config', $config)
			->bind('errors', $errors);
		
		if ($db_config === NULL)
		{
			$db_config = ORM::factory('config')->find_all();
		}

		!$_POST AND $default_data = array();

		$config = array();
		foreach($db_config as $item)
		{
			!isset($config[$item->group_name]) AND $config[$item->group_name] = array();

			$config[$item->group_name][] = $item;

			// If POST is empty then set the default form data
			!$_POST AND $default_data["{$item->group_name}-{$item->config_key}"] = unserialize($item->config_value);
		}

		// Try save the config
		if (ORM::factory('config')->update_all($_POST))
		{
			Message::set(Message::SUCCESS, __('Config successfully saved.'));

			// Delete the configuration data from cache
			Cache::instance()->delete(Config_Database::$_cache_key);

			// Redirect to prevent POST refresh
			$this->request->redirect($this->request->uri);
		}

		// Get the validation errors
		if ( $errors = $_POST->errors('admin/config'))
		{
			Message::set(Message::ERROR, __('Please correct the errors.'));
		}

		// If POST is empty then add the default form data to POST
		if (isset($default_data))
		{
			$_POST = array_merge($_POST->as_array(), $default_data);
		}
	}
	
	public function action_group($group_name = NULL)
	{
		$db_config = ORM::factory('config')
			->where('group_name', '=', $group_name)
			->find_all();
			
		if ($db_config->count() == 0)
		{
			$this->request->redirect('admin/config');
		}
		
		$this->action_index($db_config);
	}

} // End Controller_Admin_Config