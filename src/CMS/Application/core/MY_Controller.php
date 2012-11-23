<?php if(! defined('BASEPATH')) exit('No direct script access allowed');

//
// Company: Cloudmanic Labs, LLC
// Website: http://cloudmanic.com
//

class MY_Controller extends CI_Controller
{
	public $data = array('data' => array(), 'cms' => array());

	//
	// Constructor …
	//
	function __construct()
	{	
		parent::__construct();
		
		// Bootstrap
		$this->_load_configs();
		$this->_check_auth();
		$this->load->model('cms_nav_model');
		
		// Set system wide vars.
		$this->data['state'] = array();
		$this->data['page_title'] = $this->data['cms']['site_name'] . ' // Admin Only';
		$this->data['nav'] = $this->cms_nav_model->get_nav();
		$this->_cont_init();

		// Setup segments based on what the cp base is.
		$this->data['seg1'] = $this->uri->segment($this->data['cms']['cp_base_seg'] + 1);
		$this->data['seg2'] = $this->uri->segment($this->data['cms']['cp_base_seg'] + 2);
		$this->data['seg3'] = $this->uri->segment($this->data['cms']['cp_base_seg'] + 3);
		$this->data['seg4'] = $this->uri->segment($this->data['cms']['cp_base_seg'] + 4);
		$this->data['seg5'] = $this->uri->segment($this->data['cms']['cp_base_seg'] + 5);
		
		// Check if we have to force SSL for control panel access.
		if($this->data['cms']['cp_force_ssl'] && ($_SERVER['SERVER_PORT'] == '80'))
		{
			$url = site_url($this->data['cms']['cp_base']);
			redirect(str_ireplace('http://', 'https://', $url));
		}
	}
	
	//
	// Generic listview....
	//
	function index()
	{
		$view = str_ireplace('cms_', '', strtolower(get_class($this)));
		$model = 'cms_' . strtolower(get_class($this)) . '_model';
		$this->load->model($model);
		$this->data['data'] = $this->{$model}->get();
		$this->load->view('cms/templates/app-header', $this->data);
		$this->load->view("cms/$view/listview", $this->data);
		$this->load->view('cms/templates/app-footer', $this->data);
	}
	
	//
	// Generic delete operations.
	//
	function delete()
	{	
		$model = 'cms_' . strtolower(get_class($this)) . '_model';
		$base = str_ireplace('cms_', '', strtolower(get_class($this)));
		$this->load->model($model);		
		$this->{$model}->delete(end($this->uri->segment_array()));
		$this->output->set_output('success');
		//redirect($this->data['cms']['cp_base'] . '/' . $base);
	}
	
	//
	// If we have cache clearing in place clear the cache dir.
	//
	function clear_ci_cache_check()
 	{
		// If we set CI page caching to clear we clear it.	
		if($this->data['cms']['cp_clear_ci_page_cache'])
		{
			$this->load->helper('file');
			delete_files($this->config->item('cache_path'));
			write_file($this->config->item('cache_path') . 'index.html', $this->_get_contents_no_listing());
		}
 	}
				
	// --------------- Private Helper Functions ------------ //
	
	//
	// Load configs. (this functionality should go away when we move away from CI)
	//
	private function _load_configs()
	{	
		// Get bootstrap configs.
		$this->load->config('cms', TRUE);
		$this->data['cms'] = $this->config->item('cms');
		
		// Load configs from database.
		$this->load->model('configs_model');
		$cfg = $this->configs_model->get();
		
		// Set the config data.
		foreach($cfg AS $key => $row)
		{
			$this->data['cms'][$row['ConfigsKey']] = $row['ConfigsValue'];
		}
		
		// Load configs from file passed in.
		$configs = CMS\Libraries\Config::load_configs_from_file();
		
		foreach($configs AS $key => $row)
		{
			$this->data['cms'][$key] = $row;
		}
		
		// Make sure our tables are built
		$this->load->library('CMS_Tables');
	}
	
 	//
 	// Get the contents of a no listing file.
 	//
 	private function _get_contents_no_listing()
 	{
	 	return '<html>
	 		<head>
	 			<title>403 Forbidden</title>
	 		</head>
	 		<body>
	 		
	 		<p>Directory access is forbidden.</p>
	 		
	 		</body>
	 		</html>';
 	}
	
	//
	// Make sure the user is logged in. If not kick the user out.
	//
	private function _check_auth()
	{
		if(! $user = $this->session->userdata('CmsLoggedIn'))
		{
			redirect($this->data['cms']['cp_base']);
		}
		
		// Refresh the session.
		$this->load->model('cms_users_model');
		$this->data['me'] = $this->cms_users_model->get_by_id($user['CMS_UsersId']);
	}
	
	//
	// Controller Init.
	//
	private function _cont_init()
	{
		// Set defaults.
		//$this->data['state']['limit'] = ($this->input->get('limit')) ? $this->input->get('limit') : 200;
		//$this->data['state']['offset'] = ($this->input->get('offset')) ? $this->input->get('offset') : 0;
		$this->data['state']['search'] = ($this->input->get('search')) ? $this->input->get('search') : '';
		//$this->data['state']['order'] = ($this->input->get('order')) ? $this->input->get('order') : '';
		//$this->data['state']['sort'] = ($this->input->get('sort')) ? $this->input->get('sort') : 'asc';
	}
}

/* End File */