<?php defined('SYSPATH') or die('No direct script access.');

class Model_Config extends Model_Base_Config {

	public function update_all(& $data)
	{
		$data = Validate::factory($data);

		foreach($this->find_all() as $config)
		{
			$rules = unserialize($config->rules);
			
				$data->rules($config->group_name.'-'.$config->config_key, $rules);
		
		}

		if (!$data->check()) return FALSE;

		foreach($data as $name => $value)
		{			
			list($group_name, $config_key) = explode('-', $name);

			$config = ORM::factory('config')
				->where('group_name', '=', $group_name)
				->where('config_key', '=', $config_key)
				->find();

			$config->config_value = serialize($value);
			$config->config_key = $config_key;
			$config->save();
		}

		return TRUE;
	}

} // End Model_Config