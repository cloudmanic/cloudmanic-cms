<?php

//
// Company: Cloudmanic Labs, LLC
// Website: http://cloudmanic.com
//

namespace CMS\Libraries;

class Config
{
	private static $_config = array();
	private static $_cfg_file = '';
	
	//
	// Set a config value.
	//
	public static function set($key, $val)
	{
		self::$_config[$key] = $val;
	}
	
	//
	// Get a config value.
	//
	public static function get($key = '')
	{
		if(empty($key))
		{
			return self::$_config;
		} 
		
		if(! isset(self::$_config[$key]))
		{
			return '';
		}
		
		return self::$_config[$key]; 
	}
	
	//
	// Load configs from file.
	//
	public static function load_configs_from_file()
	{
		if(! is_file(self::$_cfg_file))
		{
			return array();
		}
		
		$configs = require self::$_cfg_file;
		
		foreach($configs AS $key => $row)
		{
			self::set($key, $row);
		}
		
		return $configs;
	}
	
	//
	// Set external config file.
	//
	public static function set_config_file($file)
	{
		self::$_cfg_file = $file;
	}
	
	//
	// Returns config object.
	//
	private static function _cfg($title, $key, $value, $field, $sys = 1)
	{
		return array('ConfigsTitle' => $title, 
		  						'ConfigsKey' => $key,
		  						'ConfigsValue' => $value,
		  						'ConfigsField' => $field,
		  						'ConfigsSystem' => $sys);
	}
}

/* End File */