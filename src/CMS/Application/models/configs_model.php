<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//
// Company: Cloudmanic Labs, LLC
// Website: http://cloudmanic.com
//

class Configs_Model extends MY_Model 
{ 
	//
	// Set search.
	//
	function set_search($term)
	{
		$this->db->like('ConfigsName', $term);
	}

	//
	// Return the contents of a config by name.
	//
	function get_by_name($name)
	{
 		$this->db->where($this->table_base . 'Name', $name);
		return $this->db->get($this->table)->row_array();
	}
}

/* End File */