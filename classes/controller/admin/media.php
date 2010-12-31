<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Media extends Controller_Admin_Base {
	
	public $auto_render = FALSE;
	
	public function action_index()
	{
		// Get the file path from the request
		$file = $this->request->param('file');

		$ext = trim(strrchr($file, '.'), '.');

		if ($file)
		{
			// Send the file content as the response
			$this->request->response = View::factory('admin/media/'.$file);
		}
		else
		{
			// Return a 404 status
			$this->request->status = 404;
		}

		// Set the content type for this extension
		$this->request->headers['Content-Type'] = File::mime_by_ext($ext);
	}
	
} // End Controller_Admin_Media