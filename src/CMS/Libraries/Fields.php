<?php

//
// Company: Cloudmanic Labs, LLC
// Website: http://cloudmanic.com
//

namespace CMS\Libraries;

class Fields extends Model
{
	private static $_custom_file = array();
	
	//
	// Get all custom fields.
	//
	public static function get_all_custom_fields()
	{
		return self::$_custom_file;
	}
	
	//
	// Add a new custom field file path. 
	//
	public static function set_custom_path($name, $path)
	{
		self::$_custom_file[$name] = $path;
	}
	
	//
	// Get path to custom field.
	//
	public static function get_custom_path($name)
	{
		return (isset(self::$_custom_file[$name])) ? self::$_custom_file[$name] : '';
	}
}

/* End File */