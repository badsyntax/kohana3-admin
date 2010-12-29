<?php defined('SYSPATH') or die('No direct script access.');

class Model_Group extends Model_Base_Group {

	public function admin_create(& $data)
	{
		$data = Validate::factory($data)
			->rules('parent_id', $this->_rules['parent_id'])
			->rules('name', $this->_rules['name']);
			
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
			->rules('parent_id', $this->_rules['parent_id'])
			->rules('name', $this->_rules['name']);

		if (!$data->check()) return FALSE;

		$this->values($data);
		$this->save();

		return $data;
	}
	
	public function admin_delete($id = NULL, & $data)
	{
		if ($id === NULL)
		{
			$data = Validate::factory($data)
				->callback('id', array($this, 'admin_check_id'));
				
			if ( !$data->check()) return FALSE;			
		}
		
		return parent::delete($id);		
	}
	
	// Don't delete id 1
	public function admin_check_id(Validate $array, $field)
	{
		if ( (int) $this->id === 1)
		{
			$array->error($field, 'delete_id_1', array($array[$field]));
		}
	}
	
} // End Model_Group