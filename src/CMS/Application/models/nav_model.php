<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//
// Company: Cloudmanic Labs, LLC
// Website: http://cloudmanic.com
//

class Nav_Model extends MY_Model 
{ 
	//
	// Set type.
	//
	function set_type($type)
	{
		$this->db->where('NavType', $type);
	}
	
	//
	// Set parent.
	//
	function set_parent($id)
	{
		$this->db->where('NavParentId', $id);
	}
	
	//
	// Set not parents.
	//
	function set_not_parents()
	{
		$this->db->where("NavType != 'Parent'");
	}

	//
	// Return a data object of the nav all broken down.
	//
	function get_nav()
	{
		$data = array();
		$this->set_select('NavId, NavName');
		$this->set_order('NavOrder ASC');
		$this->set_type('Parent');
		$data = $this->get();
		
		foreach($data AS $key => $row)
		{
			$data[$key]['Kids'] = $this->get_kids($row['NavId']);
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
		$this->set_select('NavId, NavName, NavBucketId, NavUri, NavType, NavTarget');
		$this->set_order('NavOrder ASC');
		$this->set_not_parents();
		$data = $this->get();
		
		foreach($data AS $key => $row)
		{
			switch($row['NavType'])
			{
				case 'Bucket':
					$data[$key]['href'] = site_url($this->data['cms']['cp_base'] . '/buckets/listview/' . $row['NavBucketId']);
				break;

				case 'Internal':				
					$data[$key]['href'] = site_url($this->data['cms']['cp_base'] . $row['NavUri']);
				break;
				
				case 'External':
					$data[$key]['href'] = $row['NavUri'];
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