<?php if(! defined('BASEPATH')) exit('No direct script access allowed');

//
// Company: Cloudmanic Labs, LLC
// Website: http://cloudmanic.com
//

class CMS_Tables
{
	private $_ci;
	private $_cp_base;
	private $_table_prefix;
	
	//
	// Constuctor ....
	//
	function __construct()
	{
		$this->_ci =& get_instance();
		
		// Tables to check.
		$this->_users_check();
		$this->_blocks_check();
		$this->_bucket_check();
		$this->_media_check();
		$this->_relations_check();
		$this->_nav_check();
		$this->_configs_check();
	}
	
	// ----------------- Manage All The DB Tables --------------------- //

	//
	// Configs table check.
	//
	private function _configs_check()
	{
		// Setup Configs Table
		if(! $this->_ci->db->table_exists($this->_ci->data['cms']['table_base'] . 'Configs')) 
		{
			$this->_ci->load->dbforge();
			
			$cols = array(
				'ConfigsId' => array('type' => 'INT', 'constraint' => 9, 'unsigned' => TRUE, 'auto_increment' => TRUE),			
				'ConfigsTitle' => array('type' => 'VARCHAR', 'constraint' => '100', 'null' => FALSE),
				'ConfigsKey' => array('type' => 'VARCHAR', 'constraint' => '100', 'null' => FALSE),
				'ConfigsValue' => array('type' => 'TEXT', 'null' => FALSE),
				'ConfigsField' => array('type' => "enum('text','textarea','select')", 'null' => FALSE, 'default' => 'text'),
				'ConfigsSystem' => array('type' => 'INT', 'constraint' => 1, 'unsigned' => TRUE, 'default' => 0)
			);
			
			$this->_ci->dbforge->add_key('ConfigsId', TRUE);
			$this->_ci->dbforge->add_key('ConfigsKey');
    	$this->_ci->dbforge->add_field($cols);
    	$this->_ci->dbforge->add_field("ConfigsUpdatedAt TIMESTAMP DEFAULT now() ON UPDATE now()");
    	$this->_ci->dbforge->add_field("ConfigsCreatedAt TIMESTAMP DEFAULT '0000-00-00 00:00:00'");
    	$this->_ci->dbforge->create_table($this->_ci->data['cms']['table_base'] . 'Configs', TRUE);
    	
    	// Load default settings.
    	foreach(CMS\Libraries\Config::get_defaults() AS $key => $row)
    	{
				$q = array();
				$q['ConfigsTitle'] = $row['ConfigsTitle'];
				$q['ConfigsKey'] = $row['ConfigsKey'];
				$q['ConfigsValue'] = $row['ConfigsValue'];
				$q['ConfigsField'] = $row['ConfigsField'];
				$q['ConfigsSystem'] = $row['ConfigsSystem'];
				$q['ConfigsUpdatedAt'] = date('Y-m-d G:i:s');
				$q['ConfigsCreatedAt'] = date('Y-m-d G:i:s');
				$this->_ci->db->insert($this->_ci->data['cms']['table_base'] . 'Configs', $q);
			}
		}
	}

	//
	// Build the Control Panel nav table if it is not installed already.
	//
	private function _nav_check()
	{	
		// Setup Blocks Table
		if(! $this->_ci->db->table_exists($this->_ci->data['cms']['table_base'] . 'Nav')) 
		{
			$this->_ci->load->dbforge();
			
			$cols = array(
				'NavId' => array('type' => 'INT', 'constraint' => 9, 'unsigned' => TRUE, 'auto_increment' => TRUE),			
				'NavName' => array('type' => 'VARCHAR', 'constraint' => '50', 'null' => FALSE),
				'NavType' => array('type' => "enum('Bucket','Internal','External','Parent')", 'null' => FALSE, 'default' => 'External'),
				'NavTarget' => array('type' => 'VARCHAR', 'constraint' => '50', 'null' => FALSE),
				'NavUri' => array('type' => 'TEXT', 'null' => FALSE),
				'NavParentId' => array('type' => 'INT', 'constraint' => 9, 'unsigned' => TRUE, 'default' => 0),
				'NavBucketId' => array('type' => 'INT', 'constraint' => 9, 'unsigned' => TRUE, 'default' => 0),
				'NavOrder' => array('type' => 'INT', 'constraint' => 9, 'null' => FALSE)
			);
			
			$this->_ci->dbforge->add_key('NavId', TRUE);
			$this->_ci->dbforge->add_key('NavParentId');
    	$this->_ci->dbforge->add_field($cols);
    	$this->_ci->dbforge->add_field("NavUpdatedAt TIMESTAMP DEFAULT now() ON UPDATE now()");
    	$this->_ci->dbforge->add_field("NavCreatedAt TIMESTAMP DEFAULT '0000-00-00 00:00:00'");
    	$this->_ci->dbforge->create_table($this->_ci->data['cms']['table_base'] . 'Nav', TRUE);
    	
			// Insert top level for Admin.
			$q = array();
			$q['NavName'] = 'Admin';
			$q['NavType'] = 'Parent';
			$q['NavOrder'] = '0';
			$q['NavUpdatedAt'] = date('Y-m-d G:i:s');
			$q['NavCreatedAt'] = date('Y-m-d G:i:s');
			$this->_ci->db->insert($this->_ci->data['cms']['table_base'] . 'Nav', $q);
			
			// Insert users under admin.
			$q = array();
			$q['NavName'] = 'Users';
			$q['NavType'] = 'Bucket';
			$q['NavBucketId'] = '1';
			$q['NavParentId'] = '1';
			$q['NavOrder'] = '0';
			$q['NavUpdatedAt'] = date('Y-m-d G:i:s');
			$q['NavCreatedAt'] = date('Y-m-d G:i:s');
			$this->_ci->db->insert($this->_ci->data['cms']['table_base'] . 'Nav', $q);
			
			// Insert top level for Site.
			$q = array();
			$q['NavName'] = 'Site';
			$q['NavType'] = 'Parent';
			$q['NavOrder'] = '1';
			$q['NavUpdatedAt'] = date('Y-m-d G:i:s');
			$q['NavCreatedAt'] = date('Y-m-d G:i:s');
			$this->_ci->db->insert($this->_ci->data['cms']['table_base'] . 'Nav', $q);
			
			// Insert users under site.
			$q = array();
			$q['NavName'] = 'Blocks';
			$q['NavType'] = 'Internal';
			$q['NavUri'] = '/blocks';
			$q['NavParentId'] = '3';
			$q['NavOrder'] = '0';
			$q['NavUpdatedAt'] = date('Y-m-d G:i:s');
			$q['NavCreatedAt'] = date('Y-m-d G:i:s');
			$this->_ci->db->insert($this->_ci->data['cms']['table_base'] . 'Nav', $q);
			
			// Insert media under site.
			$q = array();
			$q['NavName'] = 'Media';
			$q['NavType'] = 'Internal';
			$q['NavUri'] = '/media';
			$q['NavParentId'] = '3';
			$q['NavOrder'] = '2';
			$q['NavUpdatedAt'] = date('Y-m-d G:i:s');
			$q['NavCreatedAt'] = date('Y-m-d G:i:s');
			$this->_ci->db->insert($this->_ci->data['cms']['table_base'] . 'Nav', $q);
		}
	}

	//
	// Build the LookUps table if it is not installed already.
	//
	private function _relations_check()
	{	
		// Setup Blocks Table
		if(! $this->_ci->db->table_exists($this->_ci->data['cms']['table_base'] . 'Relations')) 
		{
			$this->_ci->load->dbforge();
			
			$cols = array(
				'RelationsId' => array('type' => 'INT', 'constraint' => 9, 'unsigned' => TRUE, 'auto_increment' => TRUE),			
				'RelationsBucket' => array('type' => 'VARCHAR', 'constraint' => '200', 'null' => FALSE),
				'RelationsTable' => array('type' => 'VARCHAR', 'constraint' => '200', 'null' => FALSE),
				'RelationsTableId' => array('type' => 'INT', 'constraint' => 9, 'unsigned' => TRUE),
				'RelationsEntryId' => array('type' => 'INT', 'constraint' => 9, 'unsigned' => TRUE)
			);
			
			$this->_ci->dbforge->add_key('RelationsId', TRUE);
			$this->_ci->dbforge->add_key('RelationsBucket');
			$this->_ci->dbforge->add_key('RelationsTable');
    	$this->_ci->dbforge->add_field($cols);
    	$this->_ci->dbforge->add_field("RelationsUpdatedAt TIMESTAMP DEFAULT now() ON UPDATE now()");
    	$this->_ci->dbforge->add_field("RelationsCreatedAt TIMESTAMP DEFAULT '0000-00-00 00:00:00'");
    	$this->_ci->dbforge->create_table($this->_ci->data['cms']['table_base'] . 'Relations', TRUE);
		}
	}

	//
	// Build the media table if it is not installed already.
	//
	private function _media_check()
	{	
		// Setup Media Table
		if(! $this->_ci->db->table_exists($this->_ci->data['cms']['table_base'] . 'Media')) 
		{
			$this->_ci->load->dbforge();
			
			$cols = array(
				'MediaId' => array('type' => 'INT', 'constraint' => 9, 'unsigned' => TRUE, 'auto_increment' => TRUE),
				'MediaFile' => array('type' => 'TEXT','null' => FALSE),
				'MediaPath' => array('type' => 'TEXT','null' => FALSE),
				'MediaIsImage' => array('type' => 'INT', 'constraint' => 1, 'unsigned' => TRUE),
				'MediaFileThumb' => array('type' => 'TEXT','null' => FALSE),
				'MediaPathThumb' => array('type' => 'TEXT','null' => FALSE),
				'MediaType' => array('type' => 'VARCHAR', 'constraint' => '200', 'null' => FALSE),
				'MediaStore' => array('type' => 'VARCHAR', 'constraint' => '50', 'null' => FALSE),
				'MediaSize' => array('type' => 'decimal(9,2)', 'null' => FALSE),
				'MediaHash' => array('type' => 'VARCHAR', 'constraint' => '200', 'null' => FALSE)
			);
			
			$this->_ci->dbforge->add_key('MediaId', TRUE);
			$this->_ci->dbforge->add_key('MediaStore');
			$this->_ci->dbforge->add_key('MediaHash');
    	$this->_ci->dbforge->add_field($cols);
    	$this->_ci->dbforge->add_field("MediaUpdatedAt TIMESTAMP DEFAULT now() ON UPDATE now()");
    	$this->_ci->dbforge->add_field("MediaCreatedAt TIMESTAMP DEFAULT '0000-00-00 00:00:00'");
    	$this->_ci->dbforge->create_table($this->_ci->data['cms']['table_base'] . 'Media', TRUE);
		}
	}

	//
	// Build the Buckets table if it is not installed already.
	//
	private function _bucket_check()
	{	
		// Setup Buckets Table
		if(! $this->_ci->db->table_exists($this->_ci->data['cms']['table_base'] . 'Buckets')) 
		{
			$this->_ci->load->dbforge();
			
			$cols = array(
				'BucketsId' => array('type' => 'INT', 'constraint' => 9, 'unsigned' => TRUE, 'auto_increment' => TRUE),
				'BucketsName' => array('type' => 'VARCHAR', 'constraint' => '200', 'null' => FALSE),
				'BucketsHeadline' => array('type' => 'VARCHAR', 'constraint' => '50', 'null' => FALSE),
				'BucketsDescription' => array('type' => 'TEXT', 'null' => FALSE),
				'BucketsLabels' => array('type' => 'TEXT', 'null' => FALSE),
				'BucketsLookUps' => array('type' => 'TEXT', 'null' => FALSE),
				'BucketsRelations' => array('type' => 'TEXT', 'null' => FALSE),
				'BucketsFields' => array('type' => 'TEXT', 'null' => FALSE),
				'BucketsDisplay' => array('type' => 'TEXT', 'null' => FALSE),
				'BucketsListview' => array('type' => 'TEXT', 'null' => FALSE)
			);
			
			$this->_ci->dbforge->add_key('BucketsId', TRUE);
    	$this->_ci->dbforge->add_field($cols);
    	$this->_ci->dbforge->add_field("BucketsUpdatedAt TIMESTAMP DEFAULT now() ON UPDATE now()");
    	$this->_ci->dbforge->add_field("BucketsCreatedAt TIMESTAMP DEFAULT '0000-00-00 00:00:00'");
    	$this->_ci->dbforge->create_table($this->_ci->data['cms']['table_base'] . 'Buckets', TRUE);
    	
			// Insert user bucket.
			$q['BucketsName'] = 'Users';
			$q['BucketsDescription'] = 'A user is someone you grant access to this CMS admin area. In order to grant a user access you enter their first name, last name, and email address. They may then login.';
			$q['BucketsHeadline'] = 'The people that can use this CMS';
			$q['BucketsUpdatedAt'] = date('Y-m-d G:i:s');
			$q['BucketsCreatedAt'] = date('Y-m-d G:i:s');
			$this->_ci->db->insert($this->_ci->data['cms']['table_base'] . 'Buckets', $q);
		}
	}
	
	//
	// Build the users table if it is not installed already.
	//
	private function _users_check()
	{	
		// Setup Users Table
		if(! $this->_ci->db->table_exists($this->_ci->data['cms']['table_base'] . 'Users')) 
		{
			$this->_ci->load->dbforge();
			
			$cols = array(
				'UsersId' => array('type' => 'INT', 'constraint' => 9, 'unsigned' => TRUE, 'auto_increment' => TRUE),
				'UsersTitle' => array('type' => 'VARCHAR', 'constraint' => '200', 'null' => FALSE),
				'UsersDisplayName' => array('type' => 'VARCHAR', 'constraint' => '200', 'null' => FALSE),
				'UsersFirstName' => array('type' => 'VARCHAR', 'constraint' => '200', 'null' => FALSE),
				'UsersLastName' => array('type' => 'VARCHAR', 'constraint' => '200', 'null' => FALSE),
				'UsersEmail' => array('type' => 'VARCHAR', 'constraint' => '500', 'null' => FALSE),
				'UsersUrl' => array('type' => 'VARCHAR', 'constraint' => '1000', 'null' => FALSE),
				'UsersImageId' => array('type' => 'INT', 'constraint' => 9, 'null' => FALSE),
				'UsersPassword' => array('type' => 'VARCHAR', 'constraint' => '500', 'null' => FALSE),
				'UsersSalt' => array('type' => 'VARCHAR', 'constraint' => '20', 'null' => FALSE),
				'UsersOrder' => array('type' => 'INT', 'constraint' => 9, 'null' => FALSE),
				'UsersStatus' => array('type' => "enum('Active','Disabled')", 'null' => FALSE, 'default' => 'Active'),
				'UsersLastIn' => array('type' => 'DATETIME', 'null' => FALSE),
				'UsersLastActivity' => array('type' => 'DATETIME', 'null' => FALSE)
			);
			
			$this->_ci->dbforge->add_key('UsersId', TRUE);
			$this->_ci->dbforge->add_key('UsersEmail');
    	$this->_ci->dbforge->add_field($cols);
    	$this->_ci->dbforge->add_field("UsersUpdatedAt TIMESTAMP DEFAULT now() ON UPDATE now()");
    	$this->_ci->dbforge->add_field("UsersCreatedAt TIMESTAMP DEFAULT '0000-00-00 00:00:00'");
    	$this->_ci->dbforge->create_table($this->_ci->data['cms']['table_base'] . 'Users', TRUE);
		}
	}
	
	//
	// Build the blocks table if it is not installed already.
	//
	private function _blocks_check()
	{	
		// Setup Blocks Table
		if(! $this->_ci->db->table_exists($this->_ci->data['cms']['table_base'] . 'Blocks')) 
		{
			$this->_ci->load->dbforge();
			
			$cols = array(
				'BlocksId' => array('type' => 'INT', 'constraint' => 9, 'unsigned' => TRUE, 'auto_increment' => TRUE),			
				'BlocksName' => array('type' => 'VARCHAR', 'constraint' => '500', 'null' => FALSE),
				'BlocksBody' => array('type' => 'TEXT', 'null' => FALSE)
			);
			
			$this->_ci->dbforge->add_key('BlocksId', TRUE);
			$this->_ci->dbforge->add_key('BlocksName');
    	$this->_ci->dbforge->add_field($cols);
    	$this->_ci->dbforge->add_field("BlocksUpdatedAt TIMESTAMP DEFAULT now() ON UPDATE now()");
    	$this->_ci->dbforge->add_field("BlocksCreatedAt TIMESTAMP DEFAULT '0000-00-00 00:00:00'");
    	$this->_ci->dbforge->create_table($this->_ci->data['cms']['table_base'] . 'Blocks', TRUE);
		}
	}
}

/* End File */