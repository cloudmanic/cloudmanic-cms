<?php if(! defined('BASEPATH')) exit('No direct script access allowed');

//
// Company: Cloudmanic Labs, LLC
// Website: http://cloudmanic.com
//

class Blocks extends MY_Controller
{	
	//
	// Constructor …
	//
	function __construct()
	{
		parent::__construct();
		$this->load->helper('array');
		$this->load->helper('form');
		$this->load->model('blocks_model');
	}
	
	//
	// Add a post ...
	//
	function add()
	{
		$this->data['widgettext'] = 'Add New Block';
		$this->data['helpertext'] = 'To add a new block fill out the field below and click "save"';
		$this->data['type'] = 'add';
		
		$this->_add_edit_shared_func();	
	}

	//
	// Edit a post ...
	//
	function edit($id)
	{
		$this->data['widgettext'] = 'Edit Block';
		$this->data['helpertext'] = 'To edit this block fill out the field below and click "save"';
		$this->data['type'] = 'edit';
		
		// Get data
		$this->data['id'] = $id;
		if(! $this->data['data'] = $this->blocks_model->get_by_id($id)) 
		{
			redirect($this->config->item('cb_cp_url_base') . '/blocks');
		}	
		
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
			$this->form_validation->set_rules('BlocksName', 'Name', 'required|trim|strtolower');
			$this->form_validation->set_rules('BlocksBody', 'Body', 'required|trim');
	
			if($this->form_validation->run() != FALSE)
			{
				$q['BlocksName'] = $this->input->post('BlocksName');
				$q['BlocksBody'] = $this->input->post('BlocksBody');
				
				if($update)
				{
					$this->blocks_model->update($q, $update);
				} else
				{
					$this->blocks_model->insert($q);
				}
				
				redirect($this->data['cms']['cp_base'] . '/blocks');
			}
		}
		
		$this->load->view('cms/templates/app-header', $this->data);
		$this->load->view('cms/blocks/add-edit', $this->data);
		$this->load->view('cms/templates/app-footer', $this->data);
	}
}

/* End File */