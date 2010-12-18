<?php

class Model_Page extends Model_Base_Page {

	public function add_admin(& $data)
	{
		$data = Validate::factory($data)
			->rules('parent_id', $this->_rules['parent_id'])
			->rules('title', $this->_rules['title'])
			->rules('description', $this->_rules['description'])
			->rules('uri', $this->_rules['uri'])
			->rules('body', $this->_rules['body'])
			->filter('title', 'trim')
			->filter('description', 'trim')
			->filter('uri', 'trim')
			->filter('body', 'trim');
	
		if (!$data->check()) return FALSE;

		$this->values($data);
		$this->user_id = Auth::instance()->get_user()->id;
		$this->save();

		return $data;
	}
	
	public function update_admin(& $data)
	{
		$data = Validate::factory($data)
			->rules('parent_id', $this->_rules['parent_id'])
			->rules('title', $this->_rules['title'])
			->rules('description', $this->_rules['description'])
			->rules('uri', $this->_rules['uri'])
			->rules('body', $this->_rules['body'])
			->filter('title', 'trim')
			->filter('description', 'trim')
			->filter('uri', 'trim')
			->filter('body', 'trim')
			->callback('parent_id', array($this, 'check_parent_id'));
		
		if ( !$data->check()) return FALSE;
		
		$this->values($data);
		$this->save();
				
		return $data;
	}
	
	public function check_parent_id(Validate $array, $field)
	{
		if ( ! (bool) $this->parent_id )
		{
			$array->error($field, 'root_reparent', array($array[$field]));
		}
	}
	
	// Don't delete id 1
	public function check_id(Validate $array, $field)
	{
		if ( (int) $this->id === 1)
		{
			$array->error($field, 'delete_id_1', array($array[$field]));
		}
	}
	
	public function delete_admin($id = NULL, & $data)
	{
		if ($id === NULL)
		{
			$data = Validate::factory($data)
				->callback('id', array($this, 'check_id'));
				
			if ( !$data->check()) return FALSE;			
		}
		
		return parent::delete($id);		
	}
} // End Model_User
