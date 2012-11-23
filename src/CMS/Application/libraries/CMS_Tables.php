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
		if(! $this->_ci->db->table_exists('CMS_Nav')) 
		{
			$this->_ci->load->dbforge();
			
			$cols = array(
				'CMS_NavId' => array('type' => 'INT', 'constraint' => 9, 'unsigned' => TRUE, 'auto_increment' => TRUE),			
				'CMS_NavName' => array('type' => 'VARCHAR', 'constraint' => '50', 'null' => FALSE),
				'CMS_NavType' => array('type' => "enum('Bucket','Internal','External','Parent')", 'null' => FALSE, 'default' => 'External'),
				'CMS_NavTarget' => array('type' => 'VARCHAR', 'constraint' => '50', 'null' => FALSE),
				'CMS_NavUri' => array('type' => 'TEXT', 'null' => FALSE),
				'CMS_NavParentId' => array('type' => 'INT', 'constraint' => 9, 'unsigned' => TRUE, 'default' => 0),
				'CMS_NavBucketId' => array('type' => 'INT', 'constraint' => 9, 'unsigned' => TRUE, 'default' => 0),
				'CMS_NavOrder' => array('type' => 'INT', 'constraint' => 9, 'null' => FALSE)
			);
			
			$this->_ci->dbforge->add_key('CMS_NavId', TRUE);
			$this->_ci->dbforge->add_key('CMS_NavParentId');
    	$this->_ci->dbforge->add_field($cols);
    	$this->_ci->dbforge->add_field("CMS_NavUpdatedAt TIMESTAMP DEFAULT now() ON UPDATE now()");
    	$this->_ci->dbforge->add_field("CMS_NavCreatedAt TIMESTAMP DEFAULT '0000-00-00 00:00:00'");
    	$this->_ci->dbforge->create_table('CMS_Nav', TRUE);
    	
			// Insert top level for Admin.
			$q = array();
			$q['CMS_NavName'] = 'Admin';
			$q['CMS_NavType'] = 'Parent';
			$q['CMS_NavOrder'] = '0';
			$q['CMS_NavUpdatedAt'] = date('Y-m-d G:i:s');
			$q['CMS_NavCreatedAt'] = date('Y-m-d G:i:s');
			$this->_ci->db->insert('CMS_Nav', $q);
			
			// Insert users under admin.
			$q = array();
			$q['CMS_NavName'] = 'Users';
			$q['CMS_NavType'] = 'Internal';
			$q['CMS_NavUri'] = '/users';
			$q['CMS_NavParentId'] = '1';
			$q['CMS_NavOrder'] = '0';
			$q['CMS_NavUpdatedAt'] = date('Y-m-d G:i:s');
			$q['CMS_NavCreatedAt'] = date('Y-m-d G:i:s');
			$this->_ci->db->insert('CMS_Nav', $q);
			
			// Insert top level for Site.
			$q = array();
			$q['CMS_NavName'] = 'Site';
			$q['CMS_NavType'] = 'Parent';
			$q['CMS_NavOrder'] = '1';
			$q['CMS_NavUpdatedAt'] = date('Y-m-d G:i:s');
			$q['CMS_NavCreatedAt'] = date('Y-m-d G:i:s');
			$this->_ci->db->insert('CMS_Nav', $q);
			
			// Insert users under site.
			$q = array();
			$q['CMS_NavName'] = 'Blocks';
			$q['CMS_NavType'] = 'Internal';
			$q['CMS_NavUri'] = '/blocks';
			$q['CMS_NavParentId'] = '3';
			$q['CMS_NavOrder'] = '0';
			$q['CMS_NavUpdatedAt'] = date('Y-m-d G:i:s');
			$q['CMS_NavCreatedAt'] = date('Y-m-d G:i:s');
			$this->_ci->db->insert('CMS_Nav', $q);
			
			// Insert media under site.
			$q = array();
			$q['CMS_NavName'] = 'Media';
			$q['CMS_NavType'] = 'Internal';
			$q['CMS_NavUri'] = '/media';
			$q['CMS_NavParentId'] = '3';
			$q['CMS_NavOrder'] = '2';
			$q['CMS_NavUpdatedAt'] = date('Y-m-d G:i:s');
			$q['CMS_NavCreatedAt'] = date('Y-m-d G:i:s');
			$this->_ci->db->insert('CMS_Nav', $q);
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
		if(! $this->_ci->db->table_exists('CMS_Buckets')) 
		{
			$this->_ci->load->dbforge();
			
			$cols = array(
				'CMS_BucketsId' => array('type' => 'INT', 'constraint' => 9, 'unsigned' => TRUE, 'auto_increment' => TRUE),
				'CMS_BucketsName' => array('type' => 'VARCHAR', 'constraint' => '200', 'null' => FALSE),
				'CMS_BucketsHeadline' => array('type' => 'VARCHAR', 'constraint' => '50', 'null' => FALSE),
				'CMS_BucketsDescription' => array('type' => 'TEXT', 'null' => FALSE),
				'CMS_BucketsLabels' => array('type' => 'TEXT', 'null' => FALSE),
				'CMS_BucketsLookUps' => array('type' => 'TEXT', 'null' => FALSE),
				'CMS_BucketsRelations' => array('type' => 'TEXT', 'null' => FALSE),
				'CMS_BucketsFields' => array('type' => 'TEXT', 'null' => FALSE),
				'CMS_BucketsDisplay' => array('type' => 'TEXT', 'null' => FALSE),
				'CMS_BucketsListview' => array('type' => 'TEXT', 'null' => FALSE)
			);
			
			$this->_ci->dbforge->add_key('CMS_BucketsId', TRUE);
    	$this->_ci->dbforge->add_field($cols);
    	$this->_ci->dbforge->add_field("CMS_BucketsUpdatedAt TIMESTAMP DEFAULT now() ON UPDATE now()");
    	$this->_ci->dbforge->add_field("CMS_BucketsCreatedAt TIMESTAMP DEFAULT '0000-00-00 00:00:00'");
    	$this->_ci->dbforge->create_table('CMS_Buckets', TRUE);
			$this->_ci->db->insert('CMS_Buckets', $q);
		}
	}
	
	//
	// Build the users table if it is not installed already.
	//
	private function _users_check()
	{	
		// Setup Users Table
		if(! $this->_ci->db->table_exists($this->_ci->data['cms']['table_base'] . 'CMS_Users')) 
		{
			$this->_ci->load->dbforge();
			
			$cols = array(
				'CMS_UsersId' => array('type' => 'INT', 'constraint' => 9, 'unsigned' => TRUE, 'auto_increment' => TRUE),
				'CMS_UsersTitle' => array('type' => 'VARCHAR', 'constraint' => '200', 'null' => FALSE),
				'CMS_UsersDisplayName' => array('type' => 'VARCHAR', 'constraint' => '200', 'null' => FALSE),
				'CMS_UsersFirstName' => array('type' => 'VARCHAR', 'constraint' => '200', 'null' => FALSE),
				'CMS_UsersLastName' => array('type' => 'VARCHAR', 'constraint' => '200', 'null' => FALSE),
				'CMS_UsersEmail' => array('type' => 'VARCHAR', 'constraint' => '500', 'null' => FALSE),
				'CMS_UsersUrl' => array('type' => 'VARCHAR', 'constraint' => '1000', 'null' => FALSE),
				'CMS_UsersImageId' => array('type' => 'INT', 'constraint' => 9, 'null' => FALSE),
				'CMS_UsersPassword' => array('type' => 'VARCHAR', 'constraint' => '500', 'null' => FALSE),
				'CMS_UsersSalt' => array('type' => 'VARCHAR', 'constraint' => '20', 'null' => FALSE),
				'CMS_UsersOrder' => array('type' => 'INT', 'constraint' => 9, 'null' => FALSE),
				'CMS_UsersStatus' => array('type' => "enum('Active','Disabled')", 'null' => FALSE, 'default' => 'Active'),
				'CMS_UsersLastIn' => array('type' => 'DATETIME', 'null' => FALSE),
				'CMS_UsersLastActivity' => array('type' => 'DATETIME', 'null' => FALSE)
			);
			
			$this->_ci->dbforge->add_key('CMS_UsersId', TRUE);
			$this->_ci->dbforge->add_key('CMS_UsersEmail');
    	$this->_ci->dbforge->add_field($cols);
    	$this->_ci->dbforge->add_field("CMS_UsersUpdatedAt TIMESTAMP DEFAULT now() ON UPDATE now()");
    	$this->_ci->dbforge->add_field("CMS_UsersCreatedAt TIMESTAMP DEFAULT '0000-00-00 00:00:00'");
    	$this->_ci->dbforge->create_table($this->_ci->data['cms']['table_base'] . 'CMS_Users', TRUE);
    	
			// Insert first user.
			$q = array();
			$q['CMS_UsersTitle'] = 'Delete Me';
			$q['CMS_UsersDisplayName'] = 'Delete Me User';
			$q['CMS_UsersFirstName'] = 'Delete';
			$q['CMS_UsersLastName'] = 'Me';
			$q['CMS_UsersEmail'] = 'delete@me.com';
			$q['CMS_UsersPassword'] = 'cf3eb00dfff3144acfda0a0f4fd8e43f';
			$q['CMS_UsersSalt'] = 'f42Fr&';
			$q['CMS_UsersOrder'] = '0';
			$q['CMS_UsersStatus'] = 'Active';
			$q['CMS_UsersUpdatedAt'] = date('Y-m-d G:i:s');
			$q['CMS_UsersCreatedAt'] = date('Y-m-d G:i:s');
			$this->_ci->db->insert($this->_ci->data['cms']['table_base'] . 'CMS_Users', $q);
		}
	}
	
	//
	// Build the blocks table if it is not installed already.
	//
	private function _blocks_check()
	{	
		// Setup Blocks Table
		if(! $this->_ci->db->table_exists('CMS_Blocks')) 
		{
			$this->_ci->load->dbforge();
			
			$cols = array(
				'CMS_BlocksId' => array('type' => 'INT', 'constraint' => 9, 'unsigned' => TRUE, 'auto_increment' => TRUE),			
				'CMS_BlocksName' => array('type' => 'VARCHAR', 'constraint' => '500', 'null' => FALSE),
				'CMS_BlocksBody' => array('type' => 'TEXT', 'null' => FALSE)
			);
			
			$this->_ci->dbforge->add_key('CMS_BlocksId', TRUE);
			$this->_ci->dbforge->add_key('CMS_BlocksName');
    	$this->_ci->dbforge->add_field($cols);
    	$this->_ci->dbforge->add_field("CMS_BlocksUpdatedAt TIMESTAMP DEFAULT now() ON UPDATE now()");
    	$this->_ci->dbforge->add_field("CMS_BlocksCreatedAt TIMESTAMP DEFAULT '0000-00-00 00:00:00'");
    	$this->_ci->dbforge->create_table('CMS_Blocks', TRUE);
		}
	}
}

/* End File */