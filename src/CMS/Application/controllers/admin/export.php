<?php if(! defined('BASEPATH')) exit('No direct script access allowed');

//
// Company: Cloudmanic Labs, LLC
// Website: http://cloudmanic.com
//

class Export extends MY_Controller
{	
	//
	// Landing page.
	//
	function index()
	{
		redirect($this->data['cms']['cp_base'] . '/admin/export/configs');
	}
	
	//
	// Export as a PHP blob all the configs for this CMS instance.
	//
	function configs()
	{		
		$this->data['export'] = array(
			'date' => date('Y-m-d G:i:s'),
			'hash' => '', 
			'tables' => array()
		);
		
		$tables = array('CMS_Buckets', 'CMS_Nav', 'CMS_Relations');
		
		foreach($tables AS $key => $row)
		{
			$query = $this->db->get($row);

			foreach($query->result_array() AS $key2 => $row2)
			{
				$this->data['export']['tables'][$row][] = $row2; 
			}
		}
		
		// Build table hash.
		$this->data['export']['hash'] = md5(json_encode($this->data['export']['tables']));
		$this->data['export'] = json_encode($this->data['export']);
	
		$this->load->view('cms/templates/app-header', $this->data);
		$this->load->view("cms/admin/export/configs", $this->data);
		$this->load->view('cms/templates/app-footer', $this->data);
	}
}

/* End File */