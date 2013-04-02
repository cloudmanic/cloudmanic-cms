<?php

//
// Company: Cloudmanic Labs, LLC
// Website: http://cloudmanic.com
//

namespace CMS\Libraries;

class Plugins extends Model
{
	public $view_path = '';
	private static $_registered = array();
	
	//
	// Construct.
	//
	public function __construct()
	{
		// Setup the default view path.
		$reflector = new \ReflectionClass(get_called_class());
		$classPath = $reflector->getFileName();
		$this->view_path = dirname($classPath) . '/views';
	}
	
	// ------------------ Static Functions ------------------- //
	
	//
	// Register a plugin. Pass in an optional path so we can include.
	//
	public static function register($name, $class, $path = null)
	{	
		// Load the include file.
		if(! is_null($path) && (! self::is_registered($name)))
		{
			require_once $path;
		}
	
		self::$_registered[$name] = array('class' => $class, 'instance' => null);
	}
	
	//
	// Get plugin. Create an instance.
	//
	public static function get($name)
	{
		// Make sure we have registered this plugin.
		if(! self::is_registered($name))
		{
			return false;
		}
		
		// Have we created an instance of it yet?
		if(is_null(self::$_registered[$name]['instance']))
		{
			$c = self::$_registered[$name]['class'];
			self::$_registered[$name]['instance'] = new $c();
		}
		
		return self::$_registered[$name]['instance'];
	}
	
	//
	// Is Registered?
	//
	public static function is_registered($name)
	{
		if(isset(self::$_registered[$name]))
		{
			return true;
		}
		
		return false;
	}
	
	// ------------------ Non- Static Functions ------------------- //
	
	//
	// Return a view file.
	//
	public function view($path, $data = array())
	{
		echo $this->view_path;
	}
}

/* End File */