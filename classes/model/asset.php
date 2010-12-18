<?php

class Model_Asset extends Model_Base_Asset {
	
	public function upload_admin($name, $upload_path, & $post, & $files)
	{
		$post = Validate::factory($post);
		
		$files = Validate::factory($files)
			->rules($name, $this->_rules['upload']);
						
		if (!$files->check())
			return FALSE;
		
		$assets = array();
		
		foreach($files as $file)
		{
			$fileext = strrchr($file['name'], '.');
		
			$filename = $file['name'];
		
			try {
				
				$filename = Upload::save($file, $filename, DOCROOT.$upload_path);
			}
			catch(Exception $e)
			{
				continue;
			}
			
			$asset = ORM::factory('asset');
			$asset->filename = basename($filename);
			$asset->extension = $fileext;
			$asset->mimetype = File::mime_by_ext(trim($fileext, '.'));
			$asset->filesize = (int) $file['size'];
			$asset->save();
			
			$assets[] = $asset->id;
		}
		
		return $assets;
	}
	
	public function update_admin(& $data)
	{
		$data = Validate::factory($data)
			->rules('filename', $this->_rules['update']['filename']);
		
		if (!$data->check())
			return FALSE;
		
		return TRUE;
	}
} // End Model_User
