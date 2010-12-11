<?php

class Model_Page extends Model_Base_Page {

	public function add_admin(& $data)
	{
		$data = Validate::factory($data)
			->rules('parent_id', $this->_rules['parent_id'])
			->rules('title', $this->_rules['title'])
			->rules('description', $this->_rules['description'])
			->rules('uri', $this->_rules['uri'])
			->rules('body', $this->_rules['body']);
	
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
			->rules('body', $this->_rules['body']);
		
		if ( !$data->check()) return FALSE;

		$this->values($data);
		$this->save();
				
		return $data;
	}
	
} // End Model_User
