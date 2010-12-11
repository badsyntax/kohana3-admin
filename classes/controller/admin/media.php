<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Media extends Controller_Admin_Base {
	
	public $auto_render = FALSE;
	
	public function action_index()
	{
		// Get the file path from the request
		$file = $this->request->param('file');

		// Find the file extension
		$path = pathinfo($file);
		
		// Array ( [dirname] => css [basename] => reset.css [extension] => css [filename] => reset )
		$file = Kohana::find_file('media', $path['dirname'] . DIRECTORY_SEPARATOR . $path['filename'], $path['extension']);

		if ($file)
		{
			// Send the file content as the response
			$this->request->response = file_get_contents($file);
		}
		else
		{
			// Return a 404 status
			$this->request->status = 404;
		}

		// Set the content type for this extension
		$this->request->headers['Content-Type'] = File::mime_by_ext($path['extension']);
	}
	
} // End Controller_Admin_Notifications
