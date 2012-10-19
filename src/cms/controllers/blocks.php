<?php
//
// Company: Cloudmanic Labs, LLC
// Website: http://cloudmanic.com
// Date: 10/18/2012
//

namespace CMS\Controllers;

use CMS;

class Blocks extends CMS\Core\AdminController
{	
	//
	// Landing Page.
	//
	function index()
	{
		$this->view('templates/app-header.php', $this->data);
		$this->view('blocks/landing.php', $this->data);
		$this->view('templates/app-footer.php', $this->data);
	}
	
	//
	// Add Page.
	//
	function Add()
	{
		echo "Add";
	}
	
	//
	// Edit Page.
	//
	function edit($id)
	{
		echo "Edit - $id";
	}
	
	//
	// Delete Page.
	//
	function delete($id)
	{
		echo "Delete - $id";
	}
}

/* End File */