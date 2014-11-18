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
		$this->db->like($this->table . 'Title', $term);
	}

	//
	// Set Join.
	//	
	function set_join($table, $left, $right, $type = 'left')
	{
		$this->db->join($table, "$left = $right", $type);
	}
	
	//
	// Set Table.
	//
	function set_table($table)
	{
		$this->table = $table;
	}
	
	//
	// Set status.
	//
	function set_status($status)
	{
 		$this->db->where($this->table . 'Status', $status);
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
	// Reorder the bucket.
	//
	function reorder($ids)
	{
		$c = count($ids);
		foreach($ids AS $key => $row)
		{
			$q[$this->table . 'Order'] = $c;
			$this->update($q, $row);
			$c--;
		}
	}
	
 	//
 	// Add extra data to get request.
 	//
 	function _format_get($data)
 	{ 	
 		// Give a nicely formated version.
 		if(isset($data[$this->table . 'CreatedAt']))
 		{
 			$data['CreateDateFormat1'] = date('n/j/Y', strtotime($data[$this->table . 'CreatedAt']));
 		}
 		
 		// Format any column named Date
 		if(isset($data[$this->table . 'Date']))
 		{
 			$data['DateColFormat1'] = date('n/j/Y', strtotime($data[$this->table . 'Date']));
 			$data['DateColFormat2'] = date('n/j/Y', strtotime($data[$this->table . 'Date']));
 		}
 		
 		// Format any column named CreateAt
 		if(isset($data[$this->table . 'CreatedAt']))
 		{
 			$data['CreatedAtColFormat1'] = date('n/j/Y', strtotime($data[$this->table . 'CreatedAt']));
 		}
 		
		// Hack for NCDB (should find a better way)
		if(isset($data['JobsExpireDate']))
		{
			$data['JobsExpireDateDateFormat1'] = date('n/j/Y', strtotime($data['JobsExpireDate']));
		}	 		
 		
 		return $data;
 	}
}

/* End File */