<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//
// Company: Cloudmanic Labs, LLC
// Website: http://cloudmanic.com
//

class CMS_Buckets_Model extends MY_Model 
{ 
	//
	// Set search.
	//
	function set_search($term)
	{
		$this->db->like('CMS_BucketsName', $term);
	}

	//
	// Return the contents of a block by the block name.
	//
	function get_by_name($name)
	{
 		$this->db->where('CMS_BucketsName', $name);
		return $this->db->get($this->table)->row_array();
	}
	
 	//
 	// Add extra data to get request.
 	//
 	function _format_get($data)
 	{
	 	$data = parent::_format_get($data);
 	
		$data['CMS_BucketsLabels'] = (! empty($data['CMS_BucketsLabels'])) ?  json_decode($data['CMS_BucketsLabels'], TRUE) : array();
		$data['CMS_BucketsLookUps'] = (! empty($data['CMS_BucketsLookUps'])) ?  json_decode($data['CMS_BucketsLookUps'], TRUE) : array();
		$data['CMS_BucketsFields'] = (! empty($data['CMS_BucketsFields'])) ?  json_decode($data['CMS_BucketsFields'], TRUE) : array();
		$data['CMS_BucketsDisplay'] = (! empty($data['CMS_BucketsDisplay'])) ?  json_decode($data['CMS_BucketsDisplay'], TRUE) : array();
		$data['CMS_BucketsListview'] = (! empty($data['CMS_BucketsListview'])) ?  json_decode($data['CMS_BucketsListview'], TRUE) : array();
 		return $data;
 	}
}

/* End File */