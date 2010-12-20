<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Assets extends Controller_Admin_Base {

	public function before()
	{
		if (Arr::get($_REQUEST, 'tinymce', FALSE) !== FALSE)
		{
			$this->template = 'admin/page/assets/popup/master_page';
		}
			
		parent::before();
	}
	
	public function after()
	{
		array_push($this->template->styles, 'modules/admin/media/css/assetmanager.css');		
		array_push($this->template->scripts, 'modules/admin/media/js/assetmanager.js');
		
		parent::after();
	}

	public function action_upload()
	{
		$this->template->title = __('Admin - Upload assets');
		$this->template->content = View::factory('admin/page/assets/upload')
			->bind('errors', $errors);
			
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
	}
	
	public function action_get_asset($id = 0, $width = NULL, $height = NULL, $crop = FALSE)
	{
		$this->auto_render = FALSE;
		
		$asset = ORM::factory('asset', (int) $id);
		
		if (!$asset->loaded()) exit;
		
		$file = DOCROOT.Kohana::config('admin/asset.upload_path').'/'.$asset->filename;
		
		$this->request->headers['Content-Type'] = $asset->mimetype;
		
		$pathinfo = pathinfo($asset->filename);
		
		$filename = $pathinfo['filename'];
		
		$path = Kohana::config('admin/asset.upload_path').'/'.$filename.$asset->extension;
		
		if ($width AND $height)
		{
			$filename .= "_{$width}_{$height}_{$crop}";		
			$path = Kohana::config('admin/asset.upload_path').'/resized/'.$filename.$asset->extension;
		}
		
		if (!file_exists(DOCROOT.$path))
		{
			$image = Image::factory( $file );
			
			if ($crop AND $width AND $height)
			{
				if ($image->width / $image->height > $width / $height)
				{
					$resized_w = ($height / $image->height) * $image->width;
					$offset_x = round(($resized_w - $width) / 2);
					$offset_y = 0;			
					$image->resize(NULL, $height);
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
		}
			
		$this->request->send_file($path, FALSE, array('inline' => true));
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
			$this->request->redirect($this->request->uri);
		}
		
		if ($errors = $_POST->errors('assets'))
		{
			Messages::set(MESSAGE::ERROR, __('Please correct the errors.'));
		}

		isset($default_data) AND $_POST = array_merge($_POST->as_array(), $default_data);		
	}
	
	public function action_download($id = 0)
	{
		$asset = ORM::factory('asset', (int) $id);
		
		if (!$asset->loaded()) exit;
		
		$file = DOCROOT.Kohana::config('admin/asset.upload_path').'/'.$asset->filename;
		
		$this->request->send_file($file);
	}

} // End Controller_Admin_Assets