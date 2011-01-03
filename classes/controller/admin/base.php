<?php defined('SYSPATH') or die('No direct script access.');
  
abstract class Controller_Admin_Base extends Controller_Base {
 
	// Set the admin master page
	public $template = 'admin/page/master_page';

	// Set the auto-crud model (optional)
	// Controller name must match this value
	public $crud_model = FALSE;

	// Items per page when listing items in model table
	public $pagination_items_per_page = 10;

	// Only users with role 'admin' can view this controller
	protected $auth_required = 'admin';
	
	protected $view_path = NULL;
	
	// Validation errors
	protected $errors = NULL;
	
	public function before()
	{
		$this->is_ajax = (bool) Request::$is_ajax;
		
		// If the crud model isn't set then use the controller name (default)
		$this->crud_model === FALSE AND $this->crud_model = $this->request->controller;
		
		$this->crud_model_singular = Inflector::singular($this->crud_model);
		
		parent::before();

		if ($this->auto_render)
		{
			$this->template->paths = array();
			$this->template->scripts = array();
			$this->template->styles = array();
		}
	}
	
	public function after()
	{
		if ($this->auto_render)
		{
			$this->template->styles = array_merge(Kohana::config('admin/media.styles'), $this->template->styles);
			$this->template->scripts = array_merge(Kohana::config('admin/media.scripts'), $this->template->scripts);
			$this->template->paths = json_encode(array_map('URL::site', array_merge(Kohana::config('admin/media.paths', $this->template->paths))));
			$this->template->param = json_encode($this->request->param());
			$this->template->set_global('breadcrumbs', $this->get_breadcrumbs());
		}
		
		if ($this->is_ajax AND $this->errors !== NULL)
		{			
			$this->json_response($this->errors);
		} 
				
		parent::after();
	}

	// A generic index action to show lists of model items
	public function action_index()
	{
		if (!$this->template->content)
		{
			$this->template->content = View::factory('admin/page/'.$this->request->controller.'/index');
		}
		
		// Crud model needs to be set
		$this->crud_model === FALSE AND $this->request->redirect('admin');

		$this->template->title = __(ucfirst($this->crud_model));

		// Bind useful data objects to the view
		$this->template->content
			->bind($this->crud_model, $items)
			->bind('total', $total)
			->bind('page_links', $page_links);

		// Get the total amount of items in the table
		$total = ORM::factory( $this->crud_model_singular )->count_all();

		// Generate the pagination values
		$pagination = Pagination::factory(array(
			'total_items' => $total,
			'items_per_page' => $this->pagination_items_per_page,
			'view' => 'admin/pagination/asset_links'
		));

		// Get the items
		$items = ORM::factory( $this->crud_model_singular )
			->limit($pagination->items_per_page)
			->offset($pagination->offset)
			->find_all();

		// Generate the pagination links
		$page_links = $pagination->render();
	}

	public function authenticate()
	{
		// The user may be logged in but not have the correct permissions to view this controller and/or action, 
		// so instead of redirecting to signin page we redirect to 403 Forbidden
		if ( Auth::instance()->logged_in() AND Auth::instance()->logged_in($this->auth_required) === FALSE)
		{
			if (!$this->is_ajax)
			{
				$this->request->redirect('403');
			}
			else
			{
				exit(__('No permission.'));
			}
		}

		parent::authenticate();
	}
	
	// A generic delete action to delete a model item by ID
	public function action_delete($id = 0)
	{
		$item = ORM::factory( $this->crud_model_singular, (int) $id);

		!$item->loaded() AND $this->request->redirect('admin');
		
		$data = array('id' => $id);

		if ( $item->admin_delete(NULL, $data))
		{
			$message = ucfirst($this->crud_model_singular).' '.__('successfully deleted.');			
			Message::set(Message::SUCCESS, $message);
			
			$this->request->redirect('admin/'.$this->crud_model);
		}
		
		if ($errors = $data->errors('admin/pages'))
		{
			throw new Exception(implode("\n", $errors));
		}		
	}
	
	public function get_breadcrumbs($pages = array())
	{
		foreach($segments = explode('/', $this->request->uri) as $key => $page)
		{
			$pages[] = array(
				'title' => $page,
				'url'	=> URL::site(join('/', array_slice($segments, 0, ($key + 1))))
			);
		}
		
		return View::factory('admin/page/fragments/breadcrumbs')->set('pages', $pages);
	}
	
	public function json_response($data=array(), $data_type='errors')
	{
		if ($this->is_ajax)
		{			
			$data = ($data)
				? array(
					'status' => FALSE,
					$data_type => $data
				)
				: array(
					'status' => TRUE,
					'redirect_url' => URL::site('admin/'.$this->request->controller)
				);
				
			$this->template->content = json_encode($data);

			$this->request->headers['Content-Type'] = 'application/json';
		}
	}

} // End Controller_Admin_Base