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

	public function before()
	{
		// If the crud model isn't set then use the controller name (default)
		$this->crud_model === FALSE AND $this->crud_model = $this->request->controller;
		
		$this->crud_model_singular = Inflector::singular($this->crud_model);
		
		parent::before();
		
		$this->template->scripts = array();
		$this->template->styles = array();
	}
	
	public function after()
	{
		
		$this->template->styles = array_merge($this->template->styles, array(
			'media/css/base.css',
			'modules/admin/media/js/jquery-ui/build/dist/jquery-ui-1.9pre/themes/base/jquery-ui.css',
			'modules/admin/media/css/jquery.ui.theme.admin.css',
			'modules/admin/media/css/admin.css'
		));
		
		$this->template->scripts = array_merge($this->template->scripts, array(
			'media/js/jquery-1.4.4.min.js',
			'modules/admin/media/js/global.js',
			'modules/admin/media/js/jquery-ui/build/dist/jquery-ui-1.9pre/ui/jquery-ui.js'	
		));
		
		parent::after();
	}

	// A generic index action to show lists of model items
	public function action_index()
	{
		// Crud model needs to be set
		$this->crud_model === FALSE AND $this->request->redirect('admin');

		$this->template->title = __('Edit '.ucfirst($this->crud_model));

		// Bind useful data objects to the view
		$this->template->content = View::factory('admin/page/'.$this->crud_model)
			->bind($this->crud_model, $items)
			->bind('total', $total)
			->bind('page_links', $page_links);

		// Get the total amount of items in the table
		$total = ORM::factory( $this->crud_model_singular )->count_all();

		// Generate the pagination values
		$pagination = Pagination::factory(array(
				'total_items' => $total,
				'items_per_page' => $this->pagination_items_per_page
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
		Auth::instance()->logged_in() 
			AND Auth::instance()->logged_in($this->auth_required) === FALSE
			AND $this->request->redirect('403');

		parent::authenticate();
	}
	
	// A generic delete action to deletes a model item by ID
	public function action_delete($id = 0)
	{
		$item = ORM::factory( $this->crud_model_singular, (int) $id);

		! $item->loaded() AND $this->request->redirect('admin');

		$item->delete();

		$message = ucfirst($this->crud_model_singular).' '.__('successfully deleted.');
		
		Activity::set(Activity::SUCCESS, $message);
		Message::set(Message::SUCCESS, $message);

		$this->request->redirect('admin/'.$this->crud_model);
	}

} // End Controller_Admin_Base
