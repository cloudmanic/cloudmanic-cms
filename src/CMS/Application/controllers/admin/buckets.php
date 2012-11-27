<?php if(! defined('BASEPATH')) exit('No direct script access allowed');

//
// Company: Cloudmanic Labs, LLC
// Website: http://cloudmanic.com
//

class Buckets extends MY_Controller
{	
	//
	// Construct.
	//
	function __construct()
	{
		parent::__construct();
		$tables = $this->db->list_tables();
		
		// Show non-cms tables.
		$this->data['tables'] = array();
		foreach($tables AS $key => $row)
		{
			if(stripos($row, 'CMS_') === false)
			{
				$this->data['tables'][$row] = $row;
			}
		}
		
		// Setup field types.
		$this->data['field_types'] = array(
			'default' => 'Default',
			'cms-image' => 'Image'
		);
	}

	//
	// Landing page.
	//
	function index()
	{
		$this->load->view('cms/templates/app-header', $this->data);
		$this->load->view("cms/admin/buckets/listview", $this->data);
		$this->load->view('cms/templates/app-footer', $this->data);
	}
	
	//
	// Add Bucket.
	//
	function add()
	{
		$this->data['type'] = 'add';
		
		if($this->input->post('submit'))
		{
			$this->load->library('form_validation');
			$this->form_validation->set_rules('CMS_BucketsName', 'Name', 'required|trim');
			$this->form_validation->set_rules('CMS_BucketsTable', 'Table', 'required|trim');
	
			if($this->form_validation->run() != FALSE)
			{
				$this->load->model('cms_buckets_model');
				$this->cms_buckets_model->insert($_POST);
				redirect($this->data['cms']['cp_base'] . '/admin/buckets');
			}
		}
		
		$this->load->view('cms/templates/app-header', $this->data);
		$this->load->view("cms/admin/buckets/add-edit", $this->data);
		$this->load->view('cms/templates/app-footer', $this->data);
	}
	
	//
	// Edit Bucket.
	//
	function edit($id)
	{
		$this->data['type'] = 'edit';
		$this->data['id'] = $id;
		
		// Get data
		$this->load->model('cms_buckets_model');
		if(! $this->data['data'] = $this->cms_buckets_model->get_by_id($id))
		{
			redirect($this->data['cms']['cp_base'] . '/admin/buckets');
		}
		
		// Get the fields of the bucket. 
		$table = $this->data['data']['CMS_BucketsTable'];
		$fields = $this->db->list_fields($table);
		$this->data['fields'] = array();
		$ignore = array(
			$table . 'Id',
			$table . 'Title',
			$table . 'Order',
			$table . 'Status',
			$table . 'UpdatedAt',
			$table . 'CreatedAt'
		);
		
		foreach($fields AS $key => $row)
		{
			if(in_array($row, $ignore))
			{
				continue;
			}
			
			$this->data['fields'][] = $row;
		}
		
		// Is this a post back?
		if($this->input->post('submit'))
		{
			$this->load->library('form_validation');
			$this->form_validation->set_rules('CMS_BucketsName', 'Name', 'required|trim');
			$this->form_validation->set_rules('CMS_BucketsHeadline', 'Headline', 'trim');
			$this->form_validation->set_rules('CMS_BucketsDescription', 'Description', 'trim');
	
			if($this->form_validation->run() != FALSE)
			{
				// Clean up the fields. 
				if($this->input->post('Fields'))
				{
					$_POST['CMS_BucketsFields'] = json_encode($this->input->post('Fields'));
				}
			
				$this->cms_buckets_model->update($_POST, $id);
				redirect($this->data['cms']['cp_base'] . '/admin/buckets');
			}
		}
		
		$this->load->view('cms/templates/app-header', $this->data);
		$this->load->view("cms/admin/buckets/add-edit", $this->data);
		$this->load->view('cms/templates/app-footer', $this->data);	
	}
}

/* End File */