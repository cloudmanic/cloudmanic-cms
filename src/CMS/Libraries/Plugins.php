<?php

//
// Company: Cloudmanic Labs, LLC
// Website: http://cloudmanic.com
//

namespace CMS\Libraries;

class Plugins
{
	public $view_path = '';
	private static $_redirects = array();
	private static $_registered = array();
	private static $_api_calls = array();	
	
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
	// Set a custom api return function.
	//	
	public static function set_custom_api_call($type, $bucketid, $function)
	{
		static::$_api_calls[$bucketid][$type] = $function;
	}
	
	//
	// Run custom function for an API call.
	//
	public static function run_custom_api_call($type, $bucketid)
	{	
		if(! isset(static::$_api_calls[$bucketid][$type]))
		{
			return null;
		}
	
		$fuct = static::$_api_calls[$bucketid][$type];
		return $fuct();
	}
	
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
		
		// Create an instance of this plugin so we can call the 
		// instructor on first list. 
		self::get($name);
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
	
	//
	// Register a redirect. This is usefull for when you want to override the default
	// behavior of the CMS. 
	//
	public static function set_redirect($type, $page, $id, $url)
	{
		self::$_registered[$type][$page][$id] = $url;
	}
	
	//
	// Has redirect?
	//
	public static function has_redirect($type, $page, $id)
	{
		return (isset(self::$_registered[$type][$page][$id])) ? self::$_registered[$type][$page][$id] : false;
	}
	
	
	// ------------------ Non- Static Functions ------------------- //
	
	//
	// Return a view file.
	//
	public function view($path, $data = array())
	{
		$file = $this->view_path . '/' . $path . '.php';
	
		ob_start() and extract($data, EXTR_SKIP);
		
		include($file);
		
		return ob_get_clean();
	}
}

/* End File */