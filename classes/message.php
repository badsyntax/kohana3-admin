<?php defined('SYSPATH') or die('No direct script access.');

class Message extends Kohana_Message {

	public static function set($type, $text, array $options = NULL)
	{
		// Dont save the message in session if request is Ajax	
		if (!Request::$is_ajax) {
			
			return parent::set($type, $text, $options);
		}
	}

}