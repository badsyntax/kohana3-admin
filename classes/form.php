<?php defined('SYSPATH') or die('No direct script access.');

class Form extends Base_Form {

	private static function admin_attributes($name, & $attributes = NULL, $errors = NULL)
	{
		// set the id attribute
		! isset($attributes['id']) AND $attributes['id'] = $name;

		// set the error classname
		$errors !== NULL AND isset($errors[$name]) AND
			$attributes['class'] = trim( (string) @$attributes['class'].' error-field');
	}

	public static function input($name, $value = NULL, array $attributes = NULL, array $errors = NULL)
	{
		static::admin_attributes($name, $attributes, $errors);

		return parent::input($name, $value, $attributes);
	}

	public static function select($name, array $options = NULL, $selected = NULL, array $attributes = NULL, array $errors = NULL)
	{
		static::admin_attributes($name, $attributes, $errors);

		return parent::select($name, $options, $selected, $attributes);
	}

} // End Form 
