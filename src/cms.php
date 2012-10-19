<?php
//
// Company: Cloudmanic Labs, LLC
// Website: http://cloudmanic.com
// Date: 10/18/2012
//

class CMS
{
	public static $slim = null; 
	private static $_vendor_dir = '';

	//
	// We call this to bootstrap the CMS.
	// Typically we put this in the index.php file.
	// We pass in the vendor path so we can find 
	// our view files and other files we want to 
	// directly access.
	//
	public static function boostrap($vendor = './')
	{
		self::_set_vendor_path($vendor);
		self::_bootstrap_slim();
	}
	
	// -------------- Private Helper Functions ------------------- //
	
	//
	// Setup the Slim Framework. We use Slim to manage our routing.
	//
	private static function _bootstrap_slim()
	{
		self::$slim = new \Slim\Slim();
		
		// Set the directory where we store view files.
		self::$slim->config('templates.path', self::$_vendor_dir . '/cloudmanic/cloudcms/src/views');
		
		self::$slim->get('/hello/:name', function ($name) {
			//echo "Hello, $name";
			CMS::$slim->render('foo.php', array('name' => $name));
		});
		
		self::$slim->run();
	}
	
	//
	// Validate and set our vendor path.
	//
	private static function _set_vendor_path($path)
	{
		if(! is_dir($path))
		{
			die('Vendor path is not correct.');
		}
		
		self::$_vendor_dir = $path;
	}
}

/* End File */