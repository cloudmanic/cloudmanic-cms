<?php
//
// Company: Cloudmanic Labs, LLC
// Website: http://cloudmanic.com
// Date: 10/18/2012
//

namespace CMS\Core;

use CMS;

class Controller
{
	//
	// Render a view to the screen.
	//
	public function view($path, $data = array())
	{
		CMS::$slim->render($path, $data);
	}
}

/* End File */