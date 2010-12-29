<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Assets extends Controller_Admin_Base {
	
	protected $is_popup = FALSE;

	public function before()
	{
		if (Arr::get($this->request->param(), 'popup', FALSE) !== FALSE)
		{
			$this->template = 'admin/page/assets/popup/master_page';
			
			$this->is_popup = TRUE;
		}					
		parent::before();
	}
	
	public function after()
	{
		array_push($this->template->styles, 'modules/admin/media/css/assetmanager.css');		
		array_push($this->template->scripts, 'modules/admin/media/js/assetmanager.js');
		parent::after();
	}
	
	public function action_index()
	{
		$this->view_path = $this->is_popup
			? 'admin/page/'.$this->crud_model.'/popup/index'
			: 'admin/page/'.$this->crud_model.'/index';
		
		return parent::action_index();
	}

	public function action_upload()
	{
		Kohana::$log->add(Kohana::DEBUG, 'UPLOAD:'.print_r($_REQUEST, TRUE));
		
		$this->template->title = __('Admin - Upload assets');
		$this->template->content = View::factory( 'admin/page/assets/'.($this->is_popup ? 'popup/' : '').'upload')
			->bind('errors', $errors)
			->set('allowed_upload_type', Kohana::config('asset.allowed_upload_type'));
	
		$upload_path = Kohana::config('admin/asset.upload_path');

		if ($assets = ORM::factory('asset')->admin_upload('asset', $upload_path, $_POST, $_FILES))
		{
			Message::set(Message::SUCCESS, __('Asset'.(count($assets)?'s':'').' successfully saved.'));
			$this->request->redirect('admin/assets/edit/'.implode(',', $assets));
		}
	
		$errors = array_merge($_POST->errors('assets'), $_FILES->errors('assets'));
			
		if ($errors)
		{
			Message::set(Message::ERROR, __('Please correct the errors.'));
		}

		$_POST = $_POST->as_array();			
		
		array_push($this->template->scripts, 'modules/admin/media/js/jquery.uploadify.min.js');
	}
	
	public function action_get_asset($id = 0, $width = NULL, $height = NULL, $crop = FALSE)
	{
		$this->auto_render = FALSE;
		
		$asset = ORM::factory('asset', (int) $id);
		
		if (!$asset->loaded()) exit;
		
		$file = DOCROOT.Kohana::config('admin/asset.upload_path').'/'.$asset->filename;
		
		$this->request->headers['Content-Type'] = $asset->mimetype->subtype.'/'.$asset->mimetype->type;
		
		$pathinfo = pathinfo($asset->filename);
		
		$filename = $pathinfo['filename'];
		
		$path = Kohana::config('admin/asset.upload_path').'/'.$filename.'.'.$asset->mimetype->extension;
		
		if ($width AND $height)
		{
			$filename .= "_{$width}_{$height}_{$crop}";		
			$path = Kohana::config('admin/asset.upload_path').'/resized/'.$filename.'.'.$asset->mimetype->extension;
		}
		
		if (!file_exists(DOCROOT.$path) AND $width !== NULL AND $height !== NULL)
		{
			$image = Image::factory( $file );
			
			if ($crop AND $width AND $height)
			{
				if ($image->width / $image->height > $width / $height)
				{
					$resized_w = ($height / $image->height) * $image->width;
					$offset_x = round(($resized_w - $width) / 2);
					$offset_y = 0;			
					$image->resize($width, NULL);
				}
				else
				{
					$resized_h = ($width / $image->width) * $image->height;
					$offset_x = 0;
					$offset_y = round(($resized_h - $height) / 2);			
					$image->resize(NULL, $height);				
				}
								
				$image->crop($width, $height, $offset_x, $offset_y);	
			}
			else 
			{
				$image->resize($width, $height);
			}
							
			$image->save($path);
			
			// Save new asset version
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
	
	public function action_edit($id = 0)
	{
		$asset = ORM::factory('asset', (int) $id);

		if (!$asset->loaded())
		{
			Message::set(MESSAGE::ERROR, __('Asset not found.'));
			$this->response->redirect('admin');
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

			$data = array('id' => $id);

			if ( $item->admin_delete(NULL, $data))
			{
				$file = DOCROOT.Kohana::config('admin/asset.upload_path').'/'.$item->filename;

				@unlink($file);				
			}
		}
		
		if (count($assets))
		{
			$message = ucfirst($this->crud_model).' '.__('successfully deleted.');
			Message::set(Message::SUCCESS, $message);
		}
			
		$this->request->redirect('admin/assets');
	}
	
	public function action_check_exists()
	{
		echo 0;
		exit;
	}

} // End Controller_Admin_Assets