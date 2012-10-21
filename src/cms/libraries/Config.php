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
	// load defaults.
	//
	public static function load_defaults()
	{
		$cfg = self::get_defaults();
		
		foreach($cfg AS $key => $row)
		{
			self::set($row['ConfigsKey'], $row['ConfigsValue']);
		}
	}
	
	//
	// Returns default configs.
	//
	public static function get_defaults()
	{
		// Default settings.
		$cfg = array(
			self::_cfg('Website Title', 'site-name', 'Website Title', 'text'),
			self::_cfg('Assets Base', 'assets-base', site_url() . 'assets', 'text'),
			self::_cfg('Force SSL', 'cp-force-ssl', (ENVIRONMENT == 'development') ? 0 : 1, 'select'),
			self::_cfg('CP Login Type', 'cp-login-type', 'default', 'text'),
			self::_cfg('CP Home', 'cp-home', 'blocks', 'text'),													
			self::_cfg('Thumb Width', 'cp-thumb-width', '100', 'text'),
			self::_cfg('Thumb Height', 'cp-thumb-height', '100', 'text'),
			self::_cfg('Clear Cache', 'cp-clear-ci-page-cache', 0, 'select'),
			self::_cfg('Media Driver', 'cp-media-driver', 'local-files', 'text'),
			self::_cfg('Media Types', 'cp-media-file-types', 'gif|jpg|jpeg|png|pdf|mov|avi|mp4', 'text'),
			self::_cfg('Max File Size', 'cp-media-file-max-size', '102400', 'text'),	
			self::_cfg('Upload URL', 'cp-media-local-path', 'uploads/', 'text'),													
			self::_cfg('Upload Local', 'cp-media-local-dir', './uploads/', 'text'),
			self::_cfg('Local Dir', 'cp-media-local-url', base_url(), 'text'),
			self::_cfg('SSL URL', 'cp-media-local-ssl-url', base_url(), 'text'),
			self::_cfg('AWS S3 Container', 'cp-media-amazon-s3-container', '', 'text'),
			self::_cfg('AWS S3 Path', 'cp-media-amazon-s3-path', 'cms/', 'text'),
			self::_cfg('AWS S3 URL', 'cp-media-amazon-s3-url', '', 'text'), // trailing slash
			self::_cfg('AWS S3 SSL URL', 'cp-media-amazon-s3-ssl-url', '', 'text'), // trailing slash
			self::_cfg('Rackspace Container', 'cp-media-rackspace-container', '', 'text'),
			self::_cfg('Rackspace Path', 'cp-media-rackspace-path', 'cms/', 'text'),
			self::_cfg('Rackspace URL', 'cp-media-rackspace-url', '', 'text'), // trailing slash
			self::_cfg('Rackspace SSL URL', 'cp-media-rackspace-ssl_url', '', 'text') // trailing slash
		);
		
		return $cfg;
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