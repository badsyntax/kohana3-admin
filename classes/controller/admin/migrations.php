<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Migrations extends Controller_Admin_Base {

	public function action_index()
	{
		$this->template->title = __('Admin - Migrations');
		$this->template->content = View::factory('admin/page/migrations/index');
	}
	
	public function action_save_mimetypes()
	{
		$types = `cat /etc/mime.types`;
		
		// Remove comments
		$types = preg_replace('/\#.*'.PHP_EOL.'/', '', $types);
		
		// Remove multiple new lines
		$types = preg_replace('/'.PHP_EOL.'+/s', PHP_EOL, $types);
		
		// Remove starting and ending new lines
		$types = preg_replace('/^'.PHP_EOL.'|'.PHP_EOL.'$/s', '', $types);

		// Split the types string into array
		$types = explode(PHP_EOL, $types);
		
		foreach($types as $type)
		{			
			// Convert all consecutive whitespace chars to single tab space char
			$type = preg_replace('/\s+/', '\t', $type);
			
			// Split the mimetype (expected: mimetype	extension)
			$type = explode('\t', trim($type));
			
			// The mimetype needs to have at least one extension
			if (count($type) >= 2)
			{
				$mimetype = $type[0];
				
				unset($type[0]);
				
				foreach($type as $extension)
				{
					list($subtype, $maintype) = explode('/', $mimetype);

					$type_orm = ORM::factory('mimetype', array(
						'subtype' => $subtype,
						'type' => $maintype,
						'extension' => $extension
					));
					$type_orm->subtype = $subtype;
					$type_orm->type = $maintype;
					$type_orm->extension = $extension;
					$type_orm->save();
				}
			}
		}
	}
	
	public function action_save_config()
	{
		// Site title
		$config = ORM::factory('config');
		$config->group_name = 'site';
		$config->config_key = 'title';
		$config->label = 'Site title';		
		$config->config_value = serialize('Default title');
		$config->default = serialize('Default title');
		$config->rules = serialize(array
			(
				'not_empty'	  => NULL,
				'max_length'  => array(32),
			));
		$config->save();

		// Site description
		$config = ORM::factory('config');
		$config->group_name = 'site';
		$config->config_key = 'description';
		$config->label = 'Site description';
		$config->config_value = serialize('Default description');
		$config->default = serialize('Default description');
		$config->rules = serialize(array
			(
				'not_empty'	  => NULL,
				'max_length'	=> array(255),
			));
		$config->save();
		
		// TinyMCE Plugins
		$config = ORM::factory('config');
		$config->group_name = 'tinymce';
		$config->config_key = 'plugins';
		$config->label = 'TinyMCE Plugins';
		$config->config_value = serialize('safari,pagebreak,advimage,advlist,iespell,media,contextmenu,paste,nonbreaking,xhtmlxtras,jqueryinlinepopups,koassets');
		$config->default = serialize('safari,pagebreak,advimage,advlist,iespell,media,contextmenu,paste,nonbreaking,xhtmlxtras,jqueryinlinepopups,koassets');
		$config->rules = serialize(array
			(
				'not_empty'	  => NULL,
				'max_length'	=> array(255),
			));
		$config->save();
		
		// TinyMCE Toolbar1
		$config = ORM::factory('config');
		$config->group_name = 'tinymce';
		$config->config_key = 'toolbar1';
		$config->label = 'TinyMCE Toolbar 1';
		$config->config_value = serialize('formatselect,|,bold,italic,strikethrough,|,bullist,numlist,|,justifyleft,justifycenter,justifyright,|,link,unlink,|,image,koassets,media,|,removeformat,cleanup,code');
		$config->default = serialize('formatselect,|,bold,italic,strikethrough,|,bullist,numlist,|,justifyleft,justifycenter,justifyright,|,link,unlink,|,image,koassets,media,|,removeformat,cleanup,code');
		$config->rules = serialize(array
			(
				'not_empty'	  => NULL,
				'max_length'	=> array(255),
			));
		$config->save();
		
		// Assets
		$config = ORM::factory('config');
		$config->group_name = 'asset';
		$config->config_key = 'allowed_upload_type';
		$config->label = 'Allowed upload types';
		$config->config_value = serialize('jpg,png,gif,pdf,txt,zip,tar');
		$config->default = serialize('jpg,png,gif,pdf,txt,zip,tar');
		$config->rules = serialize(array
			(
				'not_empty'	  => NULL,
				'max_length'	=> array(255),
			));
		$config->save();		
	}
	

} // End Controller_Admin_Migrations