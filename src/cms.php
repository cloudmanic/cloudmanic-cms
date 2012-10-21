<?php
//
// Company: Cloudmanic Labs, LLC
// Website: http://cloudmanic.com
// Date: 10/18/2012
//

class CMS
{
	public static $env = 'production';
	private static $_config = array();
	private static $_root_path = '';

	//
	// We call this to bootstrap the CMS.
	// Typically we put this in the index.php file.
	// We pass in the vendor path so we can find 
	// our php framework and kick off the app. We
	// return the path to included from the index.php
	//
	// <code>
	// require '../vendor/autoload.php';
	// include_once CMS::boostrap('../vendor');
	// </code>
	//
	public static function boostrap($vendor = './')
	{
		// Set defines
		define('ENVIRONMENT', 'development');
		define('VENDOR_PATH', $vendor);

		// Setup paths
		self::$_root_path = getcwd();

		return $vendor . '/cloudmanic/cloudmanic-cms/src/start.php';
	}
	
	//
	// To make it so Cloudmanic CMS partners up with your 
	// custom application better we provide an easy way to 
	// tell us what framework you are using. From there we 
	// can open config files and grab config data we need 
	// to bootstrap the CMS. We pass in the framework we are 
	// using and the path to the application directory.
	//
	public static function framework($framework, $path)
	{
		// Setup paths
		self::$_root_path = getcwd();
	
		// Make sure the path is valid.
		if(! is_dir($path))
		{
			die('Sorry, no application found at ' . $path . '.');
		}
	
		// Load the framework configs.
		switch($framework)
		{
			case 'laravel3':
				self::load_laravel($path);
			break;
			
			default:
				die('Sorry, unknown framework.');
			break;
		}
	}
	
	// ---------------- Public Helper Functions ------------------- //	 
	
	//
	// Set a config value.
	//
	public static function config_set($key, $val)
	{
		self::$_config[$key] = $val;
	}
	
	//
	// Get a config value.
	//
	public static function config_get($key = '')
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
	// Detect the current environment from an environment configuration.
	//
	public static function detect_env($environments, $uri)
	{
		foreach($environments AS $key => $row)
		{
			// Essentially we just want to loop through each environment pattern
			// and determine if the current URI matches the pattern and if so
			// we will simply return the environment for that URI pattern.
			foreach($row AS $key2 => $row2)
			{
				if(CMS\Libraries\Str::is($row2, $uri))
				{
					return $key;
				}
			}
		}
	}	
	
	// ---------------- Private Helper Functions ------------------- //
	
	//
	// Checkout the config directories in laravel and set our configs.
	//
	private static function load_laravel($lar_path)
	{
		// ----------- Grab The Database Config ----------------- //
	
		// We grab the array for detecting if this is a local or production env.
		// We assume the path.php file is one directory above the applications
		// directory. If it is not you will have to configure the CMS by hand.
		require $lar_path . '/../paths.php';
		
		// The require above screws up the current working directory. So we fix.
		chdir(self::$_root_path);
		
		// Figure out what env we are in.
		if(! self::$env = self::detect_env($environments, $_SERVER['HTTP_HOST']))
		{
			self::$env = 'production';
		} 
			
		// Grab the default configs.
		$tmp = require $lar_path . '/config/database.php';
		$default = $tmp['default'];
		$database = $tmp['connections'][$default];
		
		// If we are in a local dev env we look for a local folder and merge.
		$file = $lar_path . '/config/' . self::$env . '/database.php';
		if(is_file($file))
		{
			$tmp = require $file;
			if(isset($tmp['connections'][$default]))
			{
				$database = array_merge($database, $tmp['connections'][$default]);
			}
		} 
		
		// Set the database configs we sucked out of Laravel
		self::config_set('db_host', $database['host']);
		self::config_set('db_database', $database['database']);
		self::config_set('db_username', $database['username']);
		self::config_set('db_password', $database['password']);
		
		// ----------- Grab The CMS Config ----------------- //
	}
}

/* End File */