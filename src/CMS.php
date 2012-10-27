<?php
//
// Company: Cloudmanic Labs, LLC
// Website: http://cloudmanic.com
// Date: 10/18/2012
//

class CMS
{
	public static $env = 'production';
	private static $_root_path = '';
	
	//
	// We call this when we want to access stuff outside
	// the CMS. For example we might want to access CMS
	// data from within our parent framework. We pass
	// in the path to the vendor directory if the default 
	// is not good enough.
	//
	public static function start($configs)
	{
		// Setup database
		CMS\Libraries\Config::set('db_host', $configs['host']);
		CMS\Libraries\Config::set('db_database', $configs['database']);
		CMS\Libraries\Config::set('db_username', $configs['username']);
		CMS\Libraries\Config::set('db_password', $configs['password']);
		self::setup_database();
	
		// Set Configs
		CMS\Libraries\Config::load_configs();
	}
	
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
	public static function boostrap($vendor = '../')
	{
		// Set defines
		define('ENVIRONMENT', 'development');
		define('VENDOR_PATH', $vendor);

		// Setup paths
		self::$_root_path = getcwd();
		
		// We set the default CMS prefix because the frameworks will not do this.
		CMS\Libraries\Config::set('table_base', 'CMS_');
		
		// If we passed in a file we also load that.
		// A file passed in trumps everything.
		CMS\Libraries\Config::load_configs_from_file();

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
	// Set external config file.
	//
	public static function config_file($file)
	{
		CMS\Libraries\Config::set_config_file($file);
	}
	
	//
	// Set a config value. More or less a more direct way to 
	// access the config library.
	//
	public static function config_set($key, $val)
	{
		return CMS\Libraries\Config::set($key, $val);
	}
	
	//
	// Get a config value. More or less a more direct way to 
	// access the config library.
	//
	public static function config_get($key = '')
	{
		return CMS\Libraries\Config::get($key); 
	}
	
	//
	// Get a block.
	//
	public static function block($key)
	{
		return CMS\Libraries\Blocks::get_by_name($key);
	}
	
	// ---------------- Private Helper Functions ------------------- //
	
	//
	// Setup database connection.
	//
	private static function setup_database()
	{
		$host = CMS\Libraries\Config::get('db_host');
		$database = CMS\Libraries\Config::get('db_database');
		$username = CMS\Libraries\Config::get('db_username');
		$password = CMS\Libraries\Config::get('db_password');
		CMS\Libraries\ORM::configure("mysql:host=$host;dbname=$database");
		CMS\Libraries\ORM::configure('username', $username);
		CMS\Libraries\ORM::configure('password', $password);
	}
	
	//
	// Detect the current environment from an environment configuration.
	//
	private static function detect_env($environments, $uri)
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
		CMS\Libraries\Config::set('db_host', $database['host']);
		CMS\Libraries\Config::set('db_database', $database['database']);
		CMS\Libraries\Config::set('db_username', $database['username']);
		CMS\Libraries\Config::set('db_password', $database['password']);
	}
}

/* End File */