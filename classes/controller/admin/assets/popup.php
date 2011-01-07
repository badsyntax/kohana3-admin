<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Assets_Popup extends Controller_Admin_Assets {
	
	public $crud_model = 'assets';
	
	public $template = 'admin/page/assets_popup/master_page';

	public function action_index()
	{		
		$this->template->title = 'Asset Manager';

		// Bind useful data objects to the view
		$this->template->content = View::factory('admin/page/assets_popup/index')
			->bind('assets', $assets)
			->bind('pagination', $pagination)
			->bind('browse_html', $browse_html)
			->bind('upload_html', $upload_html)
			->bind('total', $total)
			->bind('direction', $direction)
			->bind('order_by', $order_by)
			->bind('pagination', $pagination);
			
	
		$browse_html = View::factory('admin/page/assets_popup/browse')
			->bind('assets', $assets);

		$upload_html = Request::factory('admin/assets/popup/upload')->execute()->response;
			
		$direction = (Arr::get($_REQUEST, 'direction', 'asc') == 'asc' ? 'desc' : 'asc');
		$order_by = Arr::get($_REQUEST, 'sort', 'date');
		$filter = Arr::get($_REQUEST, 'filter', NULL);

		// Get the total amount of items in the table
		$total = ORM::factory('asset')
			->join('mimetypes')
			->on('assets.mimetype_id', '=', 'mimetypes.id');

		if ($filter)
		{
			list($name, $value) = explode(':', $filter);
			$values = explode('|', $value);
			foreach($values as $value)
			{
				$total->or_where($name, '=', $value);
			}
		}

		$total = $total->count_all();

		// Generate the pagination values
		$pagination = Pagination::factory(array(
			'total_items' => $total,
			'items_per_page' => 18,
			'view'  => 'admin/pagination/asset_links'
		));

		// Get the assets
		$assets = ORM::factory('asset')
			->join('mimetypes')
			->on('assets.mimetype_id', '=', 'mimetypes.id')
			->order_by($order_by, $direction)			
			->limit($pagination->items_per_page)
			->offset($pagination->offset);

		if ($filter)
		{
			list($name, $value) = explode(':', $filter);			
			$values = explode('|', $value);
			foreach($values as $value)
			{
				$assets->or_where($name, '=', $value);
			}
		}

		$assets = $assets->find_all();
		
		array_push($this->template->scripts, 'modules/admin/media/js/jquery.uploadify.min.js');
		array_push($this->template->scripts, 'modules/admin/media/js/jquery.multifile.pack.js');		
		array_push($this->template->scripts, Kohana::config('admin/media.paths.tinymce_popup'));
	}
	
	public function action_upload()
	{
		parent::action_upload('admin/page/assets_popup/upload', 'admin/assets/popup#browse');
	}
	
	public function action_resize($id = 0)
	{
		$asset = ORM::factory('asset', (int) $id);

		if (!$asset->loaded())
		{
			$this->request->redirect('admin/assets/popup');
		}
		
		$this->template->title = __('Resize Asset');
		$this->template->content = View::factory('admin/page/assets_popup/resize')
			->bind('asset', $asset);
	}
	
	public function action_view($id = 0)
	{
		$asset = ORM::factory('asset', (int) $id);
		
		if (!$asset->loaded())
		{
			$this->request->redirect('admin/assets/popup');
		}
		
		$this->template->title = __('View Asset');
		$this->template->content = View::factory('admin/page/assets_popup/view')
			->bind('asset', $asset);
	}

} // End Controller_Admin_Assets_Popup