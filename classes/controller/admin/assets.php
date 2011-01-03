<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Assets extends Controller_Admin_Base {
	
	public function after()
	{
		array_push($this->template->styles, 'modules/admin/media/css/admin.assetmanager.css');		
		array_push($this->template->scripts, 'modules/admin/media/js/admin.assetmanager.js');
		parent::after();
	}
	
	public function action_index()
	{
		$this->template->title = 'Assets';

		$this->template->content = View::factory('admin/page/assets/index')
			->bind('assets', $assets)
			->bind('total', $total)
			->bind('direction', $direction)
			->bind('order_by', $order_by)
			->bind('pagination', $pagination);

		// Get the total amount of items in the table
		$total = ORM::factory('asset')->count_all();

		// Generate the pagination values
		$pagination = Pagination::factory(array(
			'total_items' => $total,
			'items_per_page' => 18,
			'view'  => 'admin/pagination/asset_links'
		));		

		$direction = (Arr::get($_REQUEST, 'direction', 'asc') == 'asc' ? 'desc' : 'asc');
		$order_by = Arr::get($_REQUEST, 'sort', 'date');

		// Get the assets
		$assets = ORM::factory('asset')
			->select('assets.*', 'mimetypes.type')
			->join('mimetypes')
			->on('assets.mimetype_id', '=', 'mimetypes.id')
			->order_by($order_by, $direction)			
			->limit($pagination->items_per_page)
			->offset($pagination->offset)
			->find_all();
	}

	public function action_upload($view_path = 'admin/page/assets/upload', $redirect_to = NULL)
	{
		$this->template->title = __('Admin - Upload assets');
		$this->template->content = View::factory($view_path)
			->bind('errors', $errors)
			->bind('allowed_upload_type', $allowed_upload_type)
			->bind('max_file_uploads', $max_file_uploads)
			->bind('field_name', $field_name);
	
		$allowed_upload_type = str_replace(',', ', ', Kohana::config('asset.allowed_upload_type'));
		$max_file_uploads = Kohana::config('admin/asset.max_file_uploads');
		
		$field_name = 'asset';
		$assets = array();
		$errors = array();

		array_push($this->template->scripts, 'modules/admin/media/js/jquery.uploadify.min.js');
		array_push($this->template->scripts, 'modules/admin/media/js/jquery.multifile.pack.js');
		
		// Have files been uploaded?
		if ($_FILES AND isset($_FILES[$field_name]) AND is_array($_FILES[$field_name]))
		{
			// Loop through uploaded files
			foreach($_FILES[$field_name]['name'] as $c => $v)
			{			
				// Create the file upload array
				$file = array(
					$field_name => array(
						'name' 		=> $_FILES[$field_name]['name'][$c],
						'type' 		=> $_FILES[$field_name]['type'][$c],
						'tmp_name' 	=> $_FILES[$field_name]['tmp_name'][$c],
						'error'		=> $_FILES[$field_name]['error'][$c],
						'size' 		=> $_FILES[$field_name]['size'][$c]
					)
				);

				// Process the uploaded file and save data to db
				$asset = ORM::factory('asset')->admin_upload($file, $field_name);
				
				// Store the validation errors
				if ($error = $file->errors('asset'))
				{
					$errors[$field_name][] = $error;	
				}
				// Else store the asset
				else
				{
					$assets[] = $asset;
				}
			}
		}
		// Upload fail!
		if ($errors)
		{
			if (isset($errors[$field_name]) and count($errors[$field_name]))
			{
				$c = count($errors[$field_name]);
			
				$message = ($c > 1) 
					? ':errors_count assets were not uploaded.'
					: ':errors_count asset was not uploaded.';
			
				Message::set(Message::ERROR, __($message, array(':errors_count' => $c)));
			}
		}
		// Upload success!
		if ($_POST AND !$errors AND $assets)
		{
			$c = count($assets);

			$message = ($c > 1)
				? ':assets_count assets successfully uploaded.'
				: ':assets_count asset successfully uploaded.';

			Message::set(Message::SUCCESS, __($message, array(':assets_count' => $c)));
			
			$redirect_url = ($redirect_to === NULL)
				? 'admin/assets'
				: $redirect_to;
	
			$this->request->redirect($redirect_url);
		}
		
		//$_POST = $_POST->as_array();
	}
	
	public function action_edit($id = 0)	
	{
		$asset = ORM::factory('asset', (int) $id);

		if (!$asset->loaded())
		{
			Message::set(MESSAGE::ERROR, __('Asset not found.'));
			$this->request->redirect('admin/assets');
		} 
		
		!$_POST AND $default_data = $asset->as_array();

		$this->template->title = __('Admin - Edit asset');
		$this->template->content = View::factory('admin/page/assets/edit')
			->bind('asset', $asset)
			->bind('errors', $errors);
			
		if (ORM::factory('asset')->admin_update($_POST))
		{
			Message::set(Message::SUCCESS, __('Asset successfully updated.'));			
			!$this->is_ajax AND $this->request->redirect($this->request->uri);
		}
		
		if ($errors = $_POST->errors('assets'))
		{
			Messages::set(MESSAGE::ERROR, __('Please correct the errors.'));
		}

		isset($default_data) AND $_POST = array_merge($_POST->as_array(), $default_data);	
		
		$this->json_response($errors);	
	}

	public function action_download($id = 0)
	{
		$asset = ORM::factory('asset', (int) $id);
		
		if (!$asset->loaded()) exit;
		
		$file = DOCROOT.Kohana::config('admin/asset.upload_path').'/'.$asset->filename;
		
		$this->request->send_file($file);
	}
	
	public function action_delete($id = 0)
	{	
		$assets = (int) $id ? $id : Arr::get($_GET, 'assets', '');
		
		foreach($assets = explode(',', $assets) as $id)
		{
			$item = ORM::factory( $this->crud_model_singular, (int) $id);

			if (!$item->loaded()) continue;

			// Delete the asset from db and filesystem
			$data = array('id' => $id);
			if ($item->admin_delete(NULL, $data))
			{
				$file = DOCROOT.Kohana::config('admin/asset.upload_path').'/'.$item->filename;
				try
				{
					unlink($file);
				} 
				catch(Exception $e)
				{
					// Log this
				}
			}
			
			// Delete the resized image assets from db and filesystem
			foreach($item->sizes->find_all() as $resized)
			{
				$data = array('id' => $resized->id);

				if ($resized->admin_delete(NULL, $data))
				{
					$resized_file = DOCROOT.Kohana::config('admin/asset.upload_path').'/resized/'.$resized->filename;
					try
					{
						unlink($resized_file);
					}
					catch(Exception $e)
					{
						// Log this
					}
				}
			}
		}
		
		if (count($assets))
		{
			$message = ucfirst($this->crud_model).' '.__('successfully deleted.');
			Message::set(Message::SUCCESS, $message);
		}
			
		$this->request->redirect('admin/assets');
	}
	
	public function action_get_asset($id = 0, $width = NULL, $height = NULL, $crop = NULL, $filename = '')
	{	
		$this->auto_render = FALSE;
		
		// Prefix id to filename
		$filename = "{$id}_$filename";
	
		if (!(int) $id OR !(int) $width OR !(int) $height OR !$filename) exit;

		$asset = ORM::factory('asset')
			->where('id', '=', $id)
			->find();

		if (!$asset->loaded()) exit;

		// Check the image size exists
		$size = ORM::factory('asset_size')
			->where('asset_id', '=', $asset->id)
			->where('width', '=', $width)
			->where('height', '=', $height)
			->where('crop', '=', $crop)
			->find();
			
		if ($size->loaded() AND !file_exists($path))
		{			
			$path = $asset->image_path($width, $height, $crop, TRUE);

			$this->request->headers['Content-Type'] = $asset->mimetype->subtype.'/'.$asset->mimetype->type;
			
			if ($asset->mimetype->subtype === 'application' AND $asset->mimetype->type == 'pdf')
			{
				$file_in = DOCROOT.Kohana::config('admin/asset.upload_path').'/'.$asset->filename;
				
				// Generate a PNG image of the PDF
				Asset::pdfthumb($file_in, $path, $width, $height, $crop);
			}
			else
			{
				$asset->resize($path, $width, $height, $crop);
			}
		
			$size->filesize = filesize($path);
			$size->resized = 1;
			$size->save();
			
			$this->request->send_file($path, FALSE, array('inline' => true));			
		}	
		exit;
	}
	
	public function action_get_url($id = 0)
	{
		$this->auto_render = FALSE;
		
		$asset = ORM::factory('asset', $id);

		if (!$asset->loaded()) exit;
		
		echo $asset->url(TRUE);
	}
	
	public function action_get_image_url($id = 0, $width = NULL, $height = NULL)
	{
		$this->auto_render = FALSE;
		
		$asset = ORM::factory('asset', $id);

		if (!$asset->loaded() OR $asset->mimetype->subtype != 'image') exit;
		
		echo $asset->image_url($width, $height, NULL, TRUE);
	}
	
	public function action_get_download_html($id = 0)
	{
		$this->auto_render = FALSE;
		
		$asset = ORM::factory('asset', $id);

		if (!$asset->loaded()) exit;
		
		echo View::factory('admin/page/assets_popup/download_html')->set('asset', $asset);
	}	
	
	public function action_rotate($id = 0)
	{
		$asset = ORM::factory('asset', (int) $id);
		
		if (!$asset->loaded() OR $asset->mimetype->subtype !== 'image') exit;
		
		$asset->rotate(90);

		$this->request->redirect('admin/assets/edit/'.$asset->id);
	}
	
	public function action_sharpen($id = 0)
	{
		$asset = ORM::factory('asset', (int) $id);

		if (!$asset->loaded() OR $asset->mimetype->subtype !== 'image') exit;

		$file = DOCROOT.Kohana::config('admin/asset.upload_path').'/'.$asset->filename;
		
		$asset->sharpen(20);

		$this->request->redirect('admin/assets/edit/'.$asset->id);
	}
	
	public function action_flip_horizontal($id = 0)
	{
		$asset = ORM::factory('asset', (int) $id);

		if (!$asset->loaded() OR $asset->mimetype->subtype !== 'image') exit;

		$file = DOCROOT.Kohana::config('admin/asset.upload_path').'/'.$asset->filename;
		
		$asset->flip_horizontal();

		$this->request->redirect('admin/assets/edit/'.$asset->id);
	}
	
	public function action_flip_vertical($id = 0)
	{
		$asset = ORM::factory('asset', (int) $id);

		if (!$asset->loaded() OR $asset->mimetype->subtype !== 'image') exit;
		
		$asset->flip_vertical();

		$this->request->redirect('admin/assets/edit/'.$asset->id);
	}
	
} // End Controller_Admin_Assets