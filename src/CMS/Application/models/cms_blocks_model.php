<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//
// Company: Cloudmanic Labs, LLC
// Website: http://cloudmanic.com
//

class CMS_Blocks_Model extends MY_Model 
{ 
	//
	// Set search.
	//
	function set_search($term)
	{
		$this->db->like('CMS_BlocksName', $term);
	}

	//
	// Return the contents of a block by the block name.
	//
	function get_by_name($name)
	{
 		$this->db->where('CMS_BlocksName', $name);
		return $this->db->get($this->table)->row_array();
	}
}

/* End File */