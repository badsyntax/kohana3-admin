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
			
			!isset($config[$item->group]) AND $config[$item->group] = array();

			$config[$item->group][] = $item;

			// If POST is empty then set the default form data
			!$_POST AND $default_data["{$item->group}_{$item->name}"] = $item->value;
		}

		// Try save the config
		if (ORM::factory('config')->update_all($_POST)) {

			Message::set(Message::SUCCESS, __('Config successfully saved.'));

			// TODO: clear cache

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

	// This method is used to add default config data
	private function create_config()
	{
		// Site title
		$config = ORM::factory('config');
		$config->group = 'site';
		$config->name = 'title';
		$config->label = 'Site title';
		$config->value = 'Default title';
		$config->default = 'Default title';
		$config->rules = serialize(array
        	(
                      'not_empty'   => NULL,
                      'max_length'  => array(32),
                 ));
		$config->save();

		// Site description
		$config = ORM::factory('config');
		$config->group = 'site';
		$config->name = 'description';
		$config->label = 'Site description';
		$config->value = 'Default description';
		$config->default = 'Default description';
		$config->rules = serialize(array
        	(
                      'not_empty'   => NULL,
                      'max_length'  => array(255),
                 ));
		$config->save();
	}

} // End Controller_Admin_Config
