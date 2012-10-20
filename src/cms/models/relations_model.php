<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//
// Company: Cloudmanic Labs, LLC
// Website: http://cloudmanic.com
//

class CMS_Relations_Model extends CMS_Model 
{  
	//
	// set the table id.
	//
	function set_table_id($id)
	{
		$this->db->where('RelationsTableId', $id);	
	}

	//
	// set the entry id.
	//
	function set_entry($id)
	{
		$this->db->where('RelationsEntryId', $id);	
	}

	//
	// Set the bucket name.
	//
	function set_bucket($name)
	{
		$this->db->where('RelationsBucket', $name);
	}
	
	//
	// Set the table name.
	//
	function set_table($name)
	{
		$this->db->where('RelationsTable', $name);
	}
}

/* End File */