<?php
//
// Company: Cloudmanic Labs, LLC
// Website: http://cloudmanic.com
// Date: 10/18/2012
//

namespace CMS\Core;

use CMS;

class Routes
{
	// 
	// Set the routes.
	//
	public static function set_routes()
	{
		// --------------- Blocks ----------------- //
			
		// Blocks - landing page.		
		CMS::$slim->get('/blocks', function () { 
		  $app = new CMS\Controllers\Blocks(); 
		  $app->index();
		});
		
		// Blocks - edit page.		
		CMS::$slim->get('/blocks/edit/:id', function ($id) { 
		  $app = new CMS\Controllers\Blocks(); 
		  $app->edit($id);
		});
		
		// Blocks - add page.		
		CMS::$slim->get('/blocks/add', function () { 
		  $app = new CMS\Controllers\Blocks(); 
		  $app->add();
		});
		
		// Blocks - delete page.		
		CMS::$slim->get('/blocks/delete/:id', function ($id) { 
		  $app = new CMS\Controllers\Blocks(); 
		  $app->delete($id);
		});
	}
}