<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//
// Company: Cloudmanic Labs, LLC
// Website: http://cloudmanic.com
//

class CMS_Relations_Model extends MY_Model 
{  
	//
	// set the table id.
	//
	function set_table_id($id)
	{
		$this->db->where('CMS_RelationsTableId', $id);	
	}

	//
	// set the entry id.
	//
	function set_entry($id)
	{
		$this->db->where('CMS_RelationsEntryId', $id);	
	}

	//
	// Set the bucket name.
	//
	function set_bucket($name)
	{
		$this->db->where('CMS_RelationsBucket', $name);
	}
	
	//
	// Set the table name.
	//
	function set_table($name)
	{
		$this->db->where('CMS_RelationsTable', $name);
	}
}

/* End File */