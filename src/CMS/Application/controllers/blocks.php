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
		$this->load->model('cms_blocks_model');		
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
		if(! $this->data['data'] = $this->cms_blocks_model->get_by_id($id)) 
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
			$this->form_validation->set_rules('CMS_BlocksName', 'Name', 'required|trim|strtolower');
			$this->form_validation->set_rules('CMS_BlocksBody', 'Body', 'required|trim');
	
			if($this->form_validation->run() != FALSE)
			{
				$q['CMS_BlocksName'] = $this->input->post('CMS_BlocksName');
				$q['CMS_BlocksBody'] = $this->input->post('CMS_BlocksBody');
				
				if($update)
				{
					$this->cms_blocks_model->update($q, $update);
				} else
				{
					$this->cms_blocks_model->insert($q);
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