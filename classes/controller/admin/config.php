<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Config extends Controller_Admin_Base {

	public function action_index()
	{
		$this->template->title = __('Admin - Config');
		$this->template->content = View::factory('admin/page/config')
			->bind('config', $config)
			->bind('errors', $errors);
		
		$db_config = ORM::factory('config')->find_all();

		!$_POST AND $default_data = array();

		$config = array();
		
		foreach($db_config as $item){
			
			!isset($config[$item->group_name]) AND $config[$item->group_name] = array();

			$config[$item->group_name][] = $item;

			// If POST is empty then set the default form data
			!$_POST AND $default_data["{$item->group_name}_{$item->config_key}"] = unserialize($item->config_value);
		}

		// Try save the config
		if (ORM::factory('config')->update_all($_POST)) {

			Activity::set(Activity::SUCCESS, __('Config saved'));	
			Message::set(Message::SUCCESS, __('Config successfully saved.'));

			// Delete the configuration data from cache
			Cache::instance()->delete(Config_Database::$_cache_key);

			// Redirect to prevent POST refresh
			$this->request->redirect($this->request->uri);
		}

		// Get the validation errors
		if ( $errors = $_POST->errors('config')){
	
			Message::set(Message::ERROR, __('Please correct the errors.'));
		}

		// If POST is empty then add the default form data to POST
		if (isset($default_data)) {
		
			$_POST = array_merge($_POST->as_array(), $default_data);
		}
	}

	// This method is used to add default the config data
	// TODO: add to migrations
	public function action_create()
	{
		// Site title
		$config = ORM::factory('config');
		$config->group_name = 'site';
		$config->config_key = 'title';
		$config->label = 'Site title';
		$config->config_value = serialize('Default title');
		$config->default = 'Default title';
		$config->rules = serialize(array
			(
				'not_empty'	  => NULL,
				'max_length'  => array(32),
			));
		$config->save();

		// Site description
		$config = ORM::factory('config');
		$config->group_name = 'site';
		$config->config_key = 'description';
		$config->label = 'Site description';
		$config->config_value = serialize('Default description');
		$config->default = 'Default description';
		$config->rules = serialize(array
			(
				'not_empty'	  => NULL,
				'max_length'	=> array(255),
			));
		$config->save();
	}

} // End Controller_Admin_Config
