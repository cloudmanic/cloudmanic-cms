<?php
//
// Company: Cloudmanic Labs, LLC
// Website: http://cloudmanic.com
// Date: 10/18/2012
//

class CMS
{
	public static $env = 'production';
	private static $_db_connection = null;
	private static $_root_path = '';
	private static $_db_loaded = false;
	
	//
	// We call this when we want to access stuff outside
	// the CMS. For example we might want to access CMS
	// data from within our parent framework. We pass
	// in the path to the config file.
	//
	public static function start($file = null)
	{
		// If we passed in a file name.
		if(! is_null($file))
		{
			self::config_file($file);
		}
	
		// Load file configs.
		CMS\Libraries\Config::load_configs_from_file();
	
		// Setup database
		self::setup_database();
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
	
	//
	// Load default blocks. Blocks we want to ensure that are always 
	// loaded into the database.
	//
	public static function load_default_blocks($path)
	{
		// Make sure the file if valid.
		if(! is_file($path))
		{
			return false;
		}
	
		$blocks = require($path);
	
		// Load database.
		self::setup_database();
		
		// Make sure the Blocks table is setup
		if(! self::is_table('CMS_Blocks'))
		{
			return false;
		}
		
		// Loop through the blocks and make sure we have them in the database.
		foreach($blocks AS $key => $row)
		{
			CMS\Libraries\Blocks::create_block($key, $row);
		}
	}
	
	//
	// Load configuration for the CMS from an export file.
	//
	public static function load_configuration_from_export($file)
	{
		// Make sure the file if valid.
		if(! is_file($file))
		{
			return false;
		}
		
		$config = require $file;
		$config = json_decode($config, TRUE);
		
		// Load database.
		self::setup_database();
		
		// Make sure the database is built first.
		if(! self::is_table('CMS_Buckets'))
		{
			return false;
		}
		
		// Get the current state of the export.
		if($stmt = self::get_db()->query("SELECT * FROM CMS_State WHERE CMS_StateName = 'import-hash'"))
		{
			$entry = $stmt->fetch(PDO::FETCH_ASSOC);
		} else
		{
			$entry = false;
		}
		
		// Match the config state up with the current state to see if we have to do anything.
		if($entry)
		{
			if($config['hash'] == $entry['CMS_StateValue'])
			{
				return false;
			}
		} else
		{
			// Build the state entry.
			$q = array(
				'CMS_StateName' => 'import-hash',
				'CMS_StateValue' => '',
				'CMS_StateUpdatedAt' => date('Y-m-d G:i:s'),
				'CMS_StateCreatedAt' => date('Y-m-d G:i:s')
			);
			self::insert('CMS_State', $q);
		}
		
		// Loop through the data, delete the old data and insert the new.
		foreach($config['tables'] AS $key => $row)
		{
			self::get_db()->query("TRUNCATE TABLE $key");
			
			foreach($row AS $key2 => $row2)
			{
				self::insert($key, $row2);
			}
		}

		// Update the hash in the state table. 
		$q = self::get_db()->prepare("UPDATE CMS_State SET CMS_StateValue=? WHERE CMS_StateName = 'import-hash'");
		$q->execute(array($config['hash']));

		return true;
	}
	
	// ---------------- Public Helper Functions ------------------- //	 
	
	//
	// Return true if a table exists.
	//
	public static function is_table($name)
	{
		foreach(self::list_tables() AS $key => $row)
		{
			if($row == $name)
			{
				return true;
			}
		}
		
		return false;
	}
	
	//
	// Return a list of tables that are part of the database connection.
	//
	public static function list_tables()
	{
		$tables = array();
		$stmt = self::get_db()->query("SHOW TABLES");
		$all = $stmt->fetchAll(PDO::FETCH_ASSOC);
		foreach($all AS $key => $row)
		{
			$tables[] = current($row);
		}
		return $tables;
	}
	
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
	
	//
	// Get bucket data.
	//
	public static function bucket($table, $order = NULL, $sort = 'ASC')
	{
		CMS\Libraries\Bucket::set_table($table);
		CMS\Libraries\Bucket::set_col($table . 'Status', 'Active');
		
		if(is_null($order))
		{
			CMS\Libraries\Bucket::set_order($table . 'Order', $sort);
		} else
		{
			CMS\Libraries\Bucket::set_order($order, $sort);			
		}
		
		return CMS\Libraries\Bucket::get();
	}
	
	//
	// Return a url to media based on the media id passed in.
	//
	public static function media_url($id, $thumb = FALSE, $ssl = FALSE)
	{
		CMS\Libraries\Media::set_table('CMS_Media');
		$media = CMS\Libraries\Media::get_by_id($id);		
		if(($thumb) && ($ssl)) { return $media['thumbsslurl']; }
		if($thumb) { return $media['thumburl']; }
		if((! $thumb) && ($ssl)) { return $media['sslurl']; }
		return $media['url'];
	}
	
	//
	// Get the raw db connection.
	//
	public static function get_db()
	{
		return self::$_db_connection;
	}
	
	// ---------------- Private Helper Functions ------------------- //
	
	//
	// Setup database connection.
	//
	private static function setup_database()
	{
		if(! self::$_db_loaded)
		{
			$host = CMS\Libraries\Config::get('db_host');
			$database = CMS\Libraries\Config::get('db_database');
			$username = CMS\Libraries\Config::get('db_username');
			$password = CMS\Libraries\Config::get('db_password');
			CMS\Libraries\ORM::configure("mysql:host=$host;dbname=$database");
			CMS\Libraries\ORM::configure('username', $username);
			CMS\Libraries\ORM::configure('password', $password);
			self::$_db_loaded = true;
			
			// At this point we are not in love with our ORM so we also create our own raw 
			// connection. Some day we will do away with the ORM and just use our raw connection.
			self::$_db_connection = new PDO("mysql:host=$host;dbname=$database", $username, $password);
		}
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
	
	// ---------------------- CRUD PDO Functions ---------------- //
	
	//
	// A function to easily select from a table.
	//
	public static function get($table, $where = '', $order = '')
	{
		$data = array();
		$sql = "SELECT * FROM $table";

		// Set where		
		if(! empty($where))
		{
			$sql .= " WHERE $where";
		}
		
		// Set Order.
		if(! empty($order))
		{
			$sql .= " ORDER BY $order";
		}		
		
		$stmt = self::get_db()->query($sql);
		
		while($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$data[] = $row;
		}
	
		return $data;
	}
	
	//
	// A function to easily insert data into the database by array.
	//
	public static function insert($table, $arr = array())
	{
		// Check to make sure we pass in the correct magic. 
		if( ! is_array($arr) || ! count($arr)) 
		{
			return false;
		}

		// Your pdo connection
		$bind = ':'.implode(',:', array_keys($arr));
		$sql = 'insert into ' . $table . '(' . implode(',', array_keys($arr)) . ') '.'values (' . $bind . ')';
		$stmt = self::get_db()->prepare($sql);
		$stmt->execute(array_combine(explode(',', $bind), array_values($arr)));
	}
}

/* End File */