<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//
// Company: Cloudmanic Labs, LLC
// Website: http://cloudmanic.com
//

class Buckets_Model extends MY_Model 
{ 
	//
	// Return the contents of a block by the block name.
	//
	function get_by_name($name)
	{
 		$this->db->where($this->table_base . 'Name', $name);
		return $this->db->get($this->table)->row_array();
	}
	
 	//
 	// Add extra data to get request.
 	//
 	function _format_get($data)
 	{
		$data['BucketsLabels'] = (! empty($data['BucketsLabels'])) ?  json_decode($data['BucketsLabels'], TRUE) : array();
		$data['BucketsLookUps'] = (! empty($data['BucketsLookUps'])) ?  json_decode($data['BucketsLookUps'], TRUE) : array();
		$data['BucketsFields'] = (! empty($data['BucketsFields'])) ?  json_decode($data['BucketsFields'], TRUE) : array();
		$data['BucketsDisplay'] = (! empty($data['BucketsDisplay'])) ?  json_decode($data['BucketsDisplay'], TRUE) : array();
		$data['BucketsListview'] = (! empty($data['BucketsListview'])) ?  json_decode($data['BucketsListview'], TRUE) : array();
 		return $data;
 	}
}

/* End File */