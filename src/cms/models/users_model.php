<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//
// Company: Cloudmanic Labs, LLC
// Website: http://cloudmanic.com
//

class CMS_Users_Model extends MY_Model 
{  
	//
	// Return a user by their email.
	//
	function get_by_email($email)
	{
		$this->db->where($this->table_base . 'Email', $email);
		$u = $this->get();
		return (isset($u[0])) ? $u[0] : 0;
	}
}

/* End File */