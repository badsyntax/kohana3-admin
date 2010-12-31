<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Assets extends Controller_Admin_Base {
	
	public function after()
	{
		array_push($this->template->styles, 'modules/admin/media/css/assetmanager.css');		
		array_push($this->template->scripts, 'modules/admin/media/js/assetmanager.js');
		parent::after();
	}
	
	public function action_index()
	{
		$this->template->title = 'Assets';

		// Bind useful data objects to the view
		$this->template->content = View::factory('admin/page/assets/index')
			->bind('assets', $assets)
			->bind('total', $total)
			->bind('page_links', $page_links);

		// Get the total amount of items in the table
		$total = ORM::factory('asset')->count_all();

		// Generate the pagination values
		$pagination = Pagination::factory(array(
			'total_items' => $total,
			'items_per_page' => 18
		));

		// Get the items
		$assets = ORM::factory('asset')
			->limit($pagination->items_per_page)
			->offset($pagination->offset)
			->order_by('date', 'DESC')
			->find_all();

		// Generate the pagination links
		$page_links = $pagination->render();
	}

	public function action_upload()
	{
		$this->template->title = __('Admin - Upload assets');
		$this->template->content = View::factory('admin/page/assets/upload')
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
		
		// Have any files been uploaded?
		if ($_FILES AND isset($_FILES[$field_name]) AND is_array($_FILES[$field_name]))
		{
			// Loop through upload files
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
				$asset = ORM::factory('asset')->upload($file, $field_name);
				
				// Store the upload validation errors
				if ($error = $file->errors('asset'))
				{
					$errors[] = $error;	
				}
				
				// Store the asset
				$assets[] = $asset;
			}
		}		
		if ($errors)
		{
			die(print_r($errors));
			Message::set(Message::ERROR, __('Please correct the errors.'));
		}

		//$_POST = $_POST->as_array();
	}
	
	public function action_edit($id = 0)
	{
		$asset = ORM::factory('asset', (int) $id);

		if (!$asset->loaded())
		{
			Message::set(MESSAGE::ERROR, __('Asset not found.'));
			$this->request->redirect('admin');
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
			if ( $item->admin_delete(NULL, $data))
			{
				$file = DOCROOT.Kohana::config('admin/asset.upload_path').'/'.$item->filename;

				@unlink($file);				
			}
			
			// Delete the resized image assets from db and filesystem
			foreach($item->sizes->find_all() as $resized)
			{
				$data = array('id' => $resized->id);

				if ( $resized->admin_delete(NULL, $data))
				{
					$resized_file = DOCROOT.Kohana::config('admin/asset.upload_path').'/resized/'.$resized->filename;

					@unlink($resized_file);
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

		$path = $asset->image_url($width, $height, $crop, TRUE);

		$this->request->headers['Content-Type'] = $asset->mimetype->subtype.'/'.$asset->mimetype->type;

		if (!file_exists($path))
		{
			if ($asset->mimetype->subtype === 'application' AND $asset->mimetype->type == 'pdf')
			{
				$file_in = DOCROOT.Kohana::config('admin/asset.upload_path').'/'.$asset->filename;
				
				Asset::pdfthumb($file_in, DOCROOT.$path, $width, $height, $crop);
			}
			else
			{
				$asset->resize($path, $width, $height, $crop);
			}
		}

		$this->request->send_file($path, FALSE, array('inline' => true));
	}
	
	public function action_rotate($id = 0)
	{
		$asset = ORM::factory('asset', (int) $id);
		
		if (!$asset->loaded() OR $asset->mimetype->subtype !== 'image') exit;
		
		$file = DOCROOT.Kohana::config('admin/asset.upload_path').'/'.$asset->filename;
		$image = Image::factory( $file );
		$image->rotate(90);
		$image->save();

		$this->request->redirect('admin/assets/edit/'.$asset->id);
	}
	
	public function action_sharpen($id = 0)
	{
		$asset = ORM::factory('asset', (int) $id);

		if (!$asset->loaded() OR $asset->mimetype->subtype !== 'image') exit;

		$file = DOCROOT.Kohana::config('admin/asset.upload_path').'/'.$asset->filename;
		$image = Image::factory( $file );
		$image->sharpen(20);
		$image->save();

		$this->request->redirect('admin/assets/edit/'.$asset->id);
	}
	
	public function action_flip_horizontal($id = 0)
	{
		$asset = ORM::factory('asset', (int) $id);

		if (!$asset->loaded() OR $asset->mimetype->subtype !== 'image') exit;

		$file = DOCROOT.Kohana::config('admin/asset.upload_path').'/'.$asset->filename;
		$image = Image::factory( $file );
		$image->flip(Image::HORIZONTAL);
		$image->save();

		$this->request->redirect('admin/assets/edit/'.$asset->id);
	}
	
	public function action_flip_vertical($id = 0)
	{
		$asset = ORM::factory('asset', (int) $id);

		if (!$asset->loaded() OR $asset->mimetype->subtype !== 'image') exit;

		$file = DOCROOT.Kohana::config('admin/asset.upload_path').'/'.$asset->filename;
		$image = Image::factory( $file );
		$image->flip(Image::VERTICAL);
		$image->save();

		$this->request->redirect('admin/assets/edit/'.$asset->id);
	}
	
	public function action_check_exists()
	{
		echo 0;
		exit;
	}

} // End Controller_Admin_Assets