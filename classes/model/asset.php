<?php

class Model_Asset extends Model_Base_Asset {
	
	protected $upload_path = 'media/assets';

	public function upload_admin($name, & $post, & $files)
	{
		$post = Validate::factory($post);
		
		$files = Validate::factory($files)
			->rules($name, $this->_rules['upload']);
			
		if (!$files->check())
			return FALSE;
		
		foreach($files as $file)
		{
			$fileext = strrchr($file['name'], '.');
		
			$filename = $file['name'];
		
			try {
				
				Upload::save($file, $filename, DOCROOT . $this->upload_path);
			}
			catch(Exception $e)
			{
				continue;
			}
			
			$asset = ORM::factory('asset');
			$asset->filename = $filename;
			$asset->extension = $fileext;
			$asset->filesize = (int) $file['size'];
			$asset->save();
		}
		
		return TRUE;
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
