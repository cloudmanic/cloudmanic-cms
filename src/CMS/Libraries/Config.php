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
	// Load configs from the database.
	//
	public static function load_configs()
	{	
		$d = ORM::for_table(self::get('table_base') . 'Configs')->find_many();
		
		foreach($d AS $key => $row)
		{
			self::set($row->ConfigsKey, $row->ConfigsValue);
		}
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
			self::_cfg('Website Title', 'site_name', 'Website Title', 'text'),
			self::_cfg('Assets Base', 'assets_base', site_url() . 'assets', 'text'),
			self::_cfg('Force SSL', 'cp_force_ssl', (ENVIRONMENT == 'development') ? 0 : 1, 'select'),
			self::_cfg('CP Login Type', 'cp_login_type', 'default', 'text'),
			self::_cfg('CP Home', 'cp_home', 'blocks', 'text'),													
			self::_cfg('Thumb Width', 'cp_thumb_width', '100', 'text'),
			self::_cfg('Thumb Height', 'cp_thumb_height', '100', 'text'),
			self::_cfg('Clear Cache', 'cp_clear_ci_page_cache', 0, 'select'),
			self::_cfg('Media Driver', 'cp_media_driver', 'local-files', 'text'),
			self::_cfg('Media Types', 'cp_media_file_types', 'gif|jpg|jpeg|png|pdf|mov|avi|mp4', 'text'),
			self::_cfg('Max File Size', 'cp_media_file_max_size', '102400', 'text'),	
			self::_cfg('Upload URL', 'cp_media_local_path', 'uploads/', 'text'),													
			self::_cfg('Upload Local', 'cp_media_local_dir', './uploads/', 'text'),
			self::_cfg('Local Dir', 'cp_media_local_url', base_url(), 'text'),
			self::_cfg('SSL URL', 'cp_media_local_ssl_url', base_url(), 'text'),
			self::_cfg('AWS S3 Container', 'cp_media_amazon_s3_container', '', 'text'),
			self::_cfg('AWS S3 Path', 'cp_media_amazon_s3_path', 'cms/', 'text'),
			self::_cfg('AWS S3 URL', 'cp_media_amazon_s3_url', '', 'text'), // trailing slash
			self::_cfg('AWS S3 SSL URL', 'cp_media_amazon_s3_ssl_url', '', 'text'), // trailing slash
			self::_cfg('Rackspace Container', 'cp_media_rackspace_container', '', 'text'),
			self::_cfg('Rackspace Path', 'cp_media_rackspace_path', 'cms/', 'text'),
			self::_cfg('Rackspace URL', 'cp_media_rackspace_url', '', 'text'), // trailing slash
			self::_cfg('Rackspace SSL URL', 'cp_media_rackspace_ssl_url', '', 'text') // trailing slash
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