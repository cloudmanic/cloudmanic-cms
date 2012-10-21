<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//
// Company: Cloudmanic Labs, LLC
// Website: http://cloudmanic.com
//

class MY_Model extends CI_Model 
{
	protected $table;
	protected $table_base;
	protected $_relatedto = array();
	protected $_relatedtoignore = FALSE;
	protected $_date_cols = array();
	protected $_extra = TRUE;
	
	//
	// Constructor …
	//
  function __construct()
  {
    parent::__construct();
		$t = str_ireplace('_model', '', get_class($this));
		$this->table_base = str_ireplace('CMS_', '', $t);
		$this->table = $this->data['cms']['table_base'] . $this->table_base;
  }
  
	//
 	// One time ignore related to.
 	//
	function ignore_related()
 	{
		$this->_relatedtoignore = TRUE;
 	}
 
	//
 	// Clear related to.
 	//
	function clear_related()
 	{
		$this->_relatedto = array();
 	}
 	 	
	//
 	// Set an array of related tables. Tables you join on id's.
 	//
	function set_related($table, $left, $right)
 	{
		$this->_relatedto[] = array('Table' => $table, 'Left' => $left, 'Right' => $right);
 	}
 
 	//
 	// This function will set the query order.
 	//
 	function set_order($order)
 	{
 		$this->db->order_by($order);	
 	}
 	
 	//
 	// This function will set the select of the query.
 	//
 	function set_select($select)
 	{
 		$this->db->select($select);	
 	}

 	//
 	// This function will set the query limit.
 	//
 	function set_limit($limit, $offset = 0)
 	{
 		$this->db->limit($limit, $offset);	
 	}
 
 	//
	// Set start date.
	//
	function set_start($start)
	{
		$this->startdate = date('Y-n-j', strtotime($start));	
	}

	//
	// Set end date.
	//
	function set_end($end)
	{
		$this->enddate = date('Y-n-j', strtotime($end));	
	}
	
	//
	// Make is so the extra relations are not added to the get data.
	//
	function set_no_extra()
	{
		$this->_extra = FALSE;
	}

 	//
 	// This function will a delete all data.
 	//
 	function delete_all()
 	{
 		$this->db->delete($this->table); 	
 		
		//$this->cms_admin->clear_ci_cache_check();
 	}

 	//
 	// This function will a delete by id.
 	//
 	function delete($id)
 	{
 		$this->db->where($this->table_base . 'Id', $id);
 		$this->db->delete($this->table); 


		//$this->cms_admin->clear_ci_cache_check();
 	}
 
 	//
 	// This function will return by id.
 	//
 	function get_by_id($id)
 	{
 		$data = 0;
 		$this->_set_joins();
 		$this->db->where($this->table_base . 'Id', $id);
 		if($row = $this->db->get($this->table)->row_array())
 		{
 			$data = $this->_format_get($row);
 		}
 		return $data;
 	}
 
 	//
 	// This function will return all records
 	//
 	function get()
 	{
 		$data = array();
		$this->_set_joins();
 		foreach($this->db->get($this->table)->result_array() AS $key => $row)
 		{
 			$row = $this->_format_get($row);
 			$data[] = $row;
 		}
 		return $data;
 	}
 	
 	//
 	// This function will take the data passed in and insert it into the table
 	//
 	function insert($data)
 	{ 	
 		if(! isset($data[$this->table_base . 'CreatedAt']))
 		{
 			$data[$this->table_base . 'CreatedAt'] = date('Y-m-d G:i:s');
 		}
 		
 		$data = $this->_format_post($data);
 		$q = $this->_set_data($data);
 		
 		$this->db->insert($this->table, $q);
		$id = $this->db->insert_id();
		
		//$this->cms_admin->clear_ci_cache_check();
		
		return $id;
 	}
 
 	//
 	// This function will take the data passed in and update it in the table by id.
 	//
 	function update($data, $id)
 	{		
 		$data = $this->_format_post($data);
 		$q = $this->_set_data($data);
 		$this->db->where($this->table_base . 'Id', $id);
 		$this->db->update($this->table, $q);
		
		//$this->cms_admin->clear_ci_cache_check();
		
 		return 1;
 	}
 	
	//
	// Returns the total number of rows found.
	//
	function total()
	{
		return $this->db->count_all_results($this->table);
	}
	
	//
	// Get columns for the table. 
	//
	function get_columns()
	{
		return $this->db->list_fields($this->table);
	}
 	
 	//
 	// Get the fields in the table.
 	//
 	function get_fields()
 	{
		return $this->db->field_data($this->table);
 	}
 	
 	// -------------------- Formating Functions ------------------------- //
 	
	//
	// Set joins on get()'s functions. Based off relatedto.
	//
	function _set_joins()
	{
		if(! $this->_relatedtoignore)
		{
			foreach($this->_relatedto AS $row)
			{
				$this->db->join($row['Table'], $this->table . '.' . $row['Left'] . '=' . $row['Table'] . '.' . $row['Right']);
			}
		} 
		else
		{
			$this->_relatedtoignore = FALSE;
		}
	}
 	
 	//
 	// This will take the post data and filter out the non table cols.
 	//
 	function _set_data(&$data)
 	{
 		$q = array();
 		$fields = $this->db->list_fields($this->table);
 		foreach ($fields AS $val)
 		{ 
 			if(isset($data[$val]))
 			{ 
 				$q[$val] = $data[$val];
 			}
 		}
 		return $q;
 	}
 	
 	//
 	// Do some special formating for inserts and updates
 	//
 	function _format_post($data)
 	{	
 		return $data;	
 	}
 	
 	//
 	// Add extra data to get request.
 	//
 	function _format_get($data)
 	{ 	
		// Add some date formats.
		if(isset($data[$this->table_base . 'UpdatedAt']))
		{
			$data['DateFormat1'] = date('n/j/Y', strtotime($data[$this->table_base . 'UpdatedAt']));
		}
		
 		return $data;
 	}
}

/* End File */