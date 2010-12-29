<?php defined('SYSPATH') or die('No direct script access.');

class Model_Asset extends Model_Base_Asset {
	
	public function admin_upload($name, $upload_path, & $post, & $files)
	{
		$post = Validate::factory($post);
		
		$files = Validate::factory($files)
			->rules($name, $this->_rules['upload'])
			->rules('upload', array('Upload::type' => explode(',', Kohana::config('asset.allowed_upload_type'))));
						
		if (!$files->check())
			return FALSE;
		
		$assets = array();
		
		foreach($files as $file)
		{
			$fileext = trim(strrchr($file['name'], '.'), '.');
		
			$filename = $file['name'];
			
			// Try find a matching mimetype
			$mimetype = ORM::factory('mimetype')->where('extension', '=', $fileext)->find();
			
			if (!$mimetype->loaded()) continue;
		
			try {
				
				$filename = Upload::save($file, $filename, DOCROOT.$upload_path);
			}
			catch(Exception $e)
			{
				continue;
			}
			
			$asset = ORM::factory('asset');
			$asset->user_id = Auth::instance()->get_user()->id;
			$asset->mimetype_id = $mimetype->id;
			$asset->filename = basename($filename);
			$asset->filesize = (int) $file['size'];
			$asset->save();
			
			$assets[] = $asset->id;
		}
		
		return $assets;
	}
	
	public function admin_update(& $data)
	{
		$data = Validate::factory($data)
			->rules('filename', $this->_rules['update']['filename']);
		
		if (!$data->check())
			return FALSE;
		
		return TRUE;
	}
	
	public function admin_delete($id = NULL, & $data)
	{
		return parent::delete($id);		
	}
} // End Model_Asset