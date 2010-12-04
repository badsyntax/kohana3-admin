<?php

class Model_Config extends Model_Base_Config {

	public $_table_name = 'config';

	public function update_all(& $data)
	{
		$data = Validate::factory($data);
		$rules = array();

		foreach($this->find_all() as $config){

			$data->rules($config->group.'_'.$config->name, (array) unserialize($config->rules));
		}

		if (!$data->check()) return FALSE;

		foreach($data as $name => $value){

			list($group, $name) = explode('_', $name);

			$config = ORM::factory('config')
				->where('name', '=', $name)
				->where('group', '=', $group)
				->find();

			$config->name = $name;
			$config->value = $value;
			$config->save();
		}

		return TRUE;
	}

} // End Model_Role
