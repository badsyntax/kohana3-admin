<?php defined('SYSPATH') or die('No direct script access.');

class Model_Role extends Model_Base_Role {

	public function admin_create(& $data)
	{
		$data = Validate::factory($data)
				->rules('name', $this->_rules['name'])
				->rules('description', $this->_rules['description']);
				
		foreach($this->_callbacks['name'] as $callback)
        {
			$data->callback('name', array($this, $callback));
		}

		if (!$data->check()) return FALSE;

		$this->values($data);
		$this->save();

		return $data;
	}

	public function admin_update(& $data)
	{
		$data = Validate::factory($data)
				->rules('name', $this->_rules['name'])
				->rules('description', $this->_rules['description']);
				
		foreach($this->_callbacks['name'] as $callback)
	    {
			$data->callback('name', array($this, $callback));
		}

		if (!$data->check()) return FALSE;

		$this->values($data);
		$this->save();

		return $data;
	}
	
	public function admin_delete()
	{
		return parent::delete();
	}

} // End Model_Role