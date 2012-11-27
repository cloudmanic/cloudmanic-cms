<?php if(! defined('BASEPATH')) exit('No direct script access allowed');

//
// Company: Cloudmanic Labs, LLC
// Website: http://cloudmanic.com
//

class Users extends MY_Controller
{	
	//
	// Constructor …
	//
	function __construct()
	{
		parent::__construct();
		$this->load->helper('array');
		$this->load->helper('form');
		$this->load->model('cms_users_model');	
		$this->data['data'] = array();
	}
	
	//
	// List view of users.
	//
	function index()
	{
		$this->load->view('cms/templates/app-header', $this->data);
		$this->load->view("cms/users/listview", $this->data);
		$this->load->view('cms/templates/app-footer', $this->data);
	}
	
	//
	// Add user.
	//
	function add()
	{
		$this->data['type'] = 'add';	
		$this->_add_edit_shared_func();	
	}
	
	//
	// Edit user.
	//
	function edit($id)
	{
		$this->data['type'] = 'edit';	
		$this->data['data'] = $this->cms_users_model->get_by_id($id);
		$this->_add_edit_shared_func($id);	
	}
	
	// ------------------ Internal Helper Functions ---------------- //
	
	//
	// Shared functionality between add / edit.
	//
	private function _add_edit_shared_func($update = FALSE)
	{
		// Manage posted data.
		if($this->input->post('submit'))
		{
			$this->load->library('form_validation');
			$this->form_validation->set_rules('CMS_UsersFirstName', 'First Name', 'required|trim');
			$this->form_validation->set_rules('CMS_UsersLastName', 'Last Name', 'required|trim');
	
			// Is this a new account?
			if(! $update)
			{
				$this->form_validation->set_rules('CMS_UsersEmail', 'Email', 'required|valid_email|is_unique[CMS_Users.CMS_UsersEmail]|trim');
				$q['CMS_UsersEmail'] = $this->input->post('CMS_UsersEmail');
			}
	
			// Manage the password.
			if((! $update) || ($this->input->post('CMS_UsersPassword')))
			{
				$this->form_validation->set_rules('CMS_UsersPassword', 'Password', 'required|trim');
				$this->form_validation->set_rules('again', 'Password Confirm', 'required|matches[CMS_UsersPassword]|trim');
			}
	
			if($this->form_validation->run() != FALSE)
			{
				$q['CMS_UsersFirstName'] = $this->input->post('CMS_UsersFirstName');
				$q['CMS_UsersLastName'] = $this->input->post('CMS_UsersLastName');
				
				if($this->input->post('CMS_UsersPassword'))
				{
					$q['CMS_UsersSalt'] = random_string('alnum', 15);
					$q['CMS_UsersPassword'] = md5($this->input->post('CMS_UsersPassword') . $q['CMS_UsersSalt']);
				}
				
				if($update)
				{
					$this->cms_users_model->update($q, $update);
				} else
				{
					$this->cms_users_model->insert($q);
				}
				
				redirect($this->data['cms']['cp_base'] . '/admin/users');
			}
		}
		
		$this->load->view('cms/templates/app-header', $this->data);
		$this->load->view('cms/users/add-edit', $this->data);
		$this->load->view('cms/templates/app-footer', $this->data);
	}
}

/* End File */