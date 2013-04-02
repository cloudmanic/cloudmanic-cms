<?php if(! defined('BASEPATH')) exit('No direct script access allowed');

//
// Company: Cloudmanic Labs, LLC
// Website: http://cloudmanic.com
//

class Plugins extends MY_Controller
{	
	//
	// Remap function.
	//
	public function _remap($method, $params = array())
	{
		// First we see if this is a registered plugin.
		if(! CMS\Libraries\Plugins::is_registered($method))
		{
			show_404();
		}
		
		// If there is no method we assume it is the index method.
		if(! isset($params[0]))
		{
			$params[0] = 'index';
		}

		// Get an instance of the class
		$instance = CMS\Libraries\Plugins::get($method);
		if(! $instance)
		{
			show_404();
		} 

		// Setup args.
		$args = $params;
		unset($args[0]);
		
		// Hand the request of to another class / function.	
    if(method_exists($instance, $params[0]))
    {
	    // Do we want to include the CMS template.
	    if(isset($instance->layout) && ($instance->layout == false))
	    {
		    $output = call_user_func_array(array($instance, $params[0]), $args);
		    $this->output->set_output($output);
		    return true;
	    }
    
	    // We default to showing the CMS layout.
	    $output = $this->load->view('cms/templates/app-header', $this->data, true);
	    $output .= call_user_func_array(array($instance, $params[0]), $args);
	    $output .= $this->load->view('cms/templates/app-footer', $this->data, true);
	    $this->output->set_output($output);
    } else
    {
	    show_404();
	  }
	}
}

/* End File */