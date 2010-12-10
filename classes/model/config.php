<?php

class Model_Config extends Model_Base_Config {

	public function update_all(& $data)
	{
		$data = Validate::factory($data);
		$rules = array();

		foreach($this->find_all() as $config){

			$data->rules($config->group_name.'_'.$config->config_key, (array) unserialize($config->rules));
		}

		if (!$data->check()) return FALSE;

		foreach($data as $name => $value){
			
			list($group_name, $config_key) = explode('_', $name);

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

} // End Model_Role
