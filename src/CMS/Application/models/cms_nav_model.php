<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//
// Company: Cloudmanic Labs, LLC
// Website: http://cloudmanic.com
//

class CMS_Nav_Model extends MY_Model 
{ 
	//
	// Set type.
	//
	function set_type($type)
	{
		$this->db->where('CMS_NavType', $type);
	}
	
	//
	// Set parent.
	//
	function set_parent($id)
	{
		$this->db->where('CMS_NavParentId', $id);
	}
	
	//
	// Set not parents.
	//
	function set_not_parents()
	{
		$this->db->where("CMS_NavType != 'Parent'");
	}

	//
	// Return a data object of the nav all broken down.
	//
	function get_nav()
	{
		$data = array();
		$this->set_select('CMS_NavId, CMS_NavName');
		$this->set_order('CMS_NavOrder ASC');
		$this->set_type('Parent');
		$data = $this->get();
		
		foreach($data AS $key => $row)
		{
			$data[$key]['Kids'] = $this->get_kids($row['CMS_NavId']);
		}
		
		return $data;
	}

	//
	// Returns an array of kids by the parent id we pass in.
	//
	function get_kids($id)
	{
		$data = array();
		$this->set_parent($id);
		$this->set_select('CMS_NavId, CMS_NavName, CMS_NavBucketId, CMS_NavUri, CMS_NavType, CMS_NavTarget');
		$this->set_order('CMS_NavOrder ASC');
		$this->set_not_parents();
		$data = $this->get();
		
		foreach($data AS $key => $row)
		{
			switch($row['CMS_NavType'])
			{
				case 'Bucket':
					$data[$key]['href'] = site_url($this->data['cms']['cp_base'] . '/buckets/listview/' . $row['CMS_NavBucketId']);
				break;

				case 'Internal':				
					$data[$key]['href'] = site_url($this->data['cms']['cp_base'] . $row['CMS_NavUri']);
				break;
				
				case 'External':
					$data[$key]['href'] = $row['CMS_NavUri'];
				break;
				
				default:
					$data[$key]['href'] = '#';
				break;
			}
		}
		
		return $data;
	}

 	//
 	// Add extra data to get request.
 	//
 	function _format_get($data)
 	{
		return $data;
 	}
}

/* End File */