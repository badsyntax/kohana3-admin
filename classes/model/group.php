<?php defined('SYSPATH') or die('No direct script access.');

class Model_Group extends Model_Base_Group {

	public function admin_create(& $data)
	{
		$data = Validate::factory($data)
			->rules('parent_id', $this->_rules['parent_id'])
			->rules('name', $this->_rules['name']);

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
		
	public function admin_tree_list_html($view_path = NULL, $start_id = 0, $list_html = '')
	{
		$start = $this->where('parent_id', '=', $start_id);

		$this->admin_recurse_tree_list_html($start->find_all(), $list_html, $view_path);

		return $list_html;
	}

	private function admin_recurse_tree_list_html($groups, & $html = '', $view_path = 'tree', & $depth = -1, $callback = NULL)
	{
		$depth++;

		$has_groups = (count($groups) > 0);

		$has_groups AND $html .= View::factory($view_path.'/list_open');

		foreach($groups as $group)
		{
			$html .= View::factory($view_path.'/item_open')->set('group', $group);
			
			$children = array();

			
			if (count($group->children))
			{

				$children = $group->children->find_all();
			
				$this->admin_recurse_tree_list_html($children, $html, $view_path, $depth);

			}

			$html .= View::factory($view_path.'/item_close');
			
			
			if (count($group->users))
			{
				foreach($group->users->find_all() as $user){
			
					$html .= View::factory($view_path.'/users_item')->set('user', $user);
				}
			}
			


		}

		if ($has_groups)
		{
			$html .= View::factory($view_path.'/list_close');
		}
	}


} // End Model_Group