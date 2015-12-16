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
  // Set No end Expire.
  //
  function set_no_end_expired()
  {
    $this->db->where("(" . $this->table . 'End >= "' . date('Y-m-d 23:59:59') . '" OR ' . $this->table . 'End = "0000-00-00 00:00:00")');
  }

  //
  // Set Only end Expire.
  //
  function set_only_end_expired()
  {
    $this->db->where($this->table . 'End <=', date('Y-m-d 23:59:59'));
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
    $this->load->helper('url');   	
   	
		// Build Slug
		if(isset($data[$this->table . 'Title']))
		{
			$data['TitleSlug'] = url_title($data[$this->table . 'Title'], '-', true);
		}	   	
   	
 		// Give a nicely formated version.
 		if(isset($data[$this->table . 'CreatedAt']))
 		{
 			$data['CreateDateFormat1'] = date('n/j/Y', strtotime($data[$this->table . 'CreatedAt']));
 		}
 		
 		// Format any column named UpdatedAt
 		if(isset($data[$this->table . 'UpdatedAt']))
 		{
 			$data['UpdatedAtColFormat1'] = date('n/j/Y', strtotime($data[$this->table . 'UpdatedAt']));
 		} 		
 		
 		// Format any column named Date
 		if(isset($data[$this->table . 'Date']))
 		{
 			$data['DateColFormat1'] = date('n/j/Y', strtotime($data[$this->table . 'Date']));
 			$data['DateColFormat2'] = date('n/j/Y', strtotime($data[$this->table . 'Date']));
 		}
 		
 		// Format any column named Start
 		if(isset($data[$this->table . 'Start']))
 		{
   		if($data[$this->table . 'Start'] == '0000-00-00 00:00:00')
   		{
        $data['StartColFormat1'] = '---';
   		} else
   		{
        $data['StartColFormat1'] = date('n/j/Y', strtotime($data[$this->table . 'Start']));
      }
 		} 		

 		// Format any column named End
 		if(isset($data[$this->table . 'End']))
 		{
   		if($data[$this->table . 'End'] == '0000-00-00 00:00:00')
   		{
        $data['EndColFormat1'] = '---';
   		} else
   		{
        $data['EndColFormat1'] = date('n/j/Y', strtotime($data[$this->table . 'End']));
 			}
 			
 			// A little hack to see if end dates have passed
 			if(time() > strtotime($data[$this->table . 'End']))
 			{
   			$data['EndColExpired'] = 'Yes';
 			} else
 			{
   			$data['EndColExpired'] = 'No';   			
 			}
 		} 
 		
		// Format End Dates With not Time. - NCDB Special Case
		if(isset($data['EventsEnd']))
		{
  		if($data['EventsEnd'] == '0000-00-00 00:00:00')
  		{
			  $data['EventsEnd'] = '---';
      }
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