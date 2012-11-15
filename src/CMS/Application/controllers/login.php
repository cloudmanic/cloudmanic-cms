<?php if(! defined('BASEPATH')) exit('No direct script access allowed');

//
// Company: Cloudmanic Labs, LLC
// Website: http://cloudmanic.com
//

class Login extends CI_Controller
{	
	//
	// Construct.
	//
	function __construct()
	{
		parent::__construct();
		$this->_load_configs();
		
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('session');
	}

	//
	// Index page.
	//
	function index()
	{
		$this->data['fail'] = 0;
		
		// Check to see if the user is logged in first so we can redirect them.
		if($this->session->userdata('CmsLoggedIn')) 
		{
			redirect($this->data['cms']['cp_home']);
		}

		// Are we posting a login?
		if($this->input->post('CMS_UsersEmail')) 
		{
			$this->form_validation->set_rules('CMS_UsersEmail', 'Email Address', 'required|valid_email|min_length[4]|max_length[32]');
			$this->form_validation->set_rules('CMS_UsersPassword', 'Password', 'required|min_length[4]|max_length[32]');
			
			// If we validated now see if there is really an account.
			if($this->form_validation->run() == TRUE)
			{
				$this->load->model('cms_users_model');
				if($user = $this->cms_users_model->get_by_email($this->input->post('CMS_UsersEmail')))
				{
					if($user['CMS_UsersPassword'] == md5($this->input->post('CMS_UsersPassword') . $user['CMS_UsersSalt']))
					{
						// Success! Create session and reload the page to redirect.
						unset($user['CMS_UsersPassword']);
						unset($user['CMS_UsersSalt']);
						$this->session->set_userdata('CmsLoggedIn', $user);
						redirect(current_url());
					}
				}
				
				// We failed to login.			
				$this->data['fail'] = 1;
			}
		}
		
		$this->load->view('cms/templates/login-header', $this->data);
		$this->load->view('cms/login/login-auth', $this->data);
		$this->load->view('cms/templates/login-footer', $this->data);
	}
	
	//
	// This function will log a user out.
	//
	function logout()
	{
		$this->session->unset_userdata('CmsLoggedIn');
		redirect($this->data['cms']['cp_base']);
	}
	
	//
	// Load configs. (this functionality should go away when we move away from CI)
	//
	private function _load_configs()
	{	
		// Get bootstrap configs.
		$this->load->config('cms', TRUE);
		$this->data['cms'] = $this->config->item('cms');
	
		// Load configs from file passed in.
		$configs = CMS\Libraries\Config::load_configs_from_file();
		
		foreach($configs AS $key => $row)
		{
			$this->data['cms'][$key] = $row;
		}
	
		// Make sure our tables are built
		$this->load->library('CMS_Tables');
		
		// Load configs from database.
		$this->load->model('configs_model');
		$cfg = $this->configs_model->get();
		
		// Set the config data.
		foreach($cfg AS $key => $row)
		{
			$this->data['cms'][$row['ConfigsKey']] = $row['ConfigsValue'];
		}
	}
}

/* End File */