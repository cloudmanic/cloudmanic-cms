<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//
// Company: Cloudmanic Labs, LLC
// Website: http://cloudmanic.com
//

class BucketData_Model extends MY_Model 
{ 
	//
	// Set search.
	//
	function set_search($term)
	{		
		if($this->table_base == 'Users')
		{
			$this->db->or_like($this->table_base . 'Title', $term);
			$this->db->or_like($this->table_base . 'FirstName', $term);
			$this->db->or_like($this->table_base . 'LastName', $term);
		} else
		{
			$this->db->like($this->table_base . 'Title', $term);
		}
	}

	//
	// Set Join.
	//	
	function set_join($table, $left, $right, $type = 'left')
	{
		$this->db->join($this->data['cms']['table_base'] . $table, "$left = $right", $type);
	}
	
	//
	// Set Table.
	//
	function set_table($table)
	{
		$this->table_base = $table;
		$this->table = $this->data['cms']['table_base'] . $this->table_base;
	}
	
	//
	// Set status.
	//
	function set_status($status)
	{
 		$this->db->where($this->table_base . 'Status', $status);
	}
	
	//
	// Set column.
	//
	function set_col($col, $val)
	{
 		$this->db->where($col, $val);
	}
	
	//
	// Set custom where.
	//
	function set_where($where)
	{
		$this->db->where($where);
	}
	
 	//
 	// Add extra data to get request.
 	//
 	function _format_get($data)
 	{ 	
 		// Give a nicely formated version.
 		if(isset($data[$this->table_base . 'CreatedAt']))
 		{
 			$data['CreateDateFormat1'] = date('n/j/Y', strtotime($data[$this->table_base . 'CreatedAt']));
 		}
 		
 		// Format any column named Date
 		if(isset($data[$this->table_base . 'Date']))
 		{
 			$data['DateColFormat1'] = date('n/j/Y', strtotime($data[$this->table_base . 'Date']));
 			$data['DateColFormat2'] = date('n/j/Y', strtotime($data[$this->table_base . 'Date']));
 		}
 		
 		// Format any column named CreateAt
 		if(isset($data[$this->table_base . 'CreatedAt']))
 		{
 			$data['CreatedAtColFormat1'] = date('n/j/Y', strtotime($data[$this->table_base . 'CreatedAt']));
 		}
 		
 		return $data;
 	}
}

/* End File */