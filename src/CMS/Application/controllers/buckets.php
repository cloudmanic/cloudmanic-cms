<?php if(! defined('BASEPATH')) exit('No direct script access allowed');

//
// Company: Cloudmanic Labs, LLC
// Website: http://cloudmanic.com
//

class Buckets extends MY_Controller
{	
	//
	// Constructor …
	//
	function __construct()
	{
		parent::__construct();
		$this->load->helper('array');
		$this->load->helper('form');
		$this->load->model('cms_media_model');
		$this->load->model('bucketdata_model');
		$this->load->model('cms_buckets_model');
		
		// Load bucket
		if(! $bucket = $this->cms_buckets_model->get_by_id($this->uri->segment(3)))
		{
			show_404();
		}
		$this->data['bucket'] = $bucket;
		
		// Make sure the bucket table exists
		$this->data['table'] = ucfirst($bucket['CMS_BucketsTable']);
		if(! $this->db->table_exists($this->data['table']))
		{
			show_404();
		} 
	}
	
	//
	// List view for a bucket.
	//
	function listview()
	{					
		// Did we register a plugin to override this?
		if($url = \CMS\Libraries\Plugins::has_redirect('buckets', 'listview', $this->uri->segment(3)))
		{
			if(! $this->input->is_ajax_request())
			{
				redirect($this->data['cms']['cp_base'] . str_ireplace('/cp', '', $url));
			} else
			{
				echo "<script>cloudjs.history.pushState('', '', '$url'); </script>";			
			}
			return false;
		}
	
		$this->load->view('cms/templates/app-header', $this->data);
		$this->load->view("cms/buckets/listview", $this->data);
		$this->load->view('cms/templates/app-footer', $this->data);
	}
	
	//
	// Delete.
	//
	function delete($bucket, $id)
	{	
		$this->db->where($this->data['table'] . 'Id', $id);
		$data = $this->db->get($this->data['table'])->row_array();
		
		$this->db->where($this->data['table'] . 'Id', $id);
		$this->db->delete($this->data['table']);
		$this->_delete_relations($id, NULL, $this->data['bucket']['CMS_BucketsName']);
		$this->_delete_relations(NULL, $this->data['bucket']['CMS_BucketsName'], NULL, $id);
		
		// Fire after event.		
		CMS\Libraries\Event::fire('after.delete', array($this->data['table'], $id, 'data' => $data));
		
		redirect($this->data['cms']['cp_base'] . '/buckets/listview/' . $bucket);
	}
	
	//
	// Add a bucket entry.
	//
	function add()
	{			
		$this->data['widgettext'] = 'Add New ' . cms_depluralize($this->data['table']);
		$this->data['helpertext'] = 'To add a new ' . cms_depluralize($this->data['table']) . ' fill out the field below and click "save"';
		$this->data['type'] = 'add';
		
		$this->_add_edit_shared_func();
	}
	
	//
	// Edit a bucket entry.
	//
	function edit($bucket, $id)
	{
		$this->data['widgettext'] = 'Edit ' . cms_depluralize($this->data['table']);
		$this->data['helpertext'] = 'To edit a the ' . cms_depluralize($this->data['table']) . ' fill out the field below and click "save"';
		$this->data['type'] = 'edit';
		
		// Get data
		$this->db->where($this->data['table'] . 'Id', $id);
		$this->data['data'] = $this->db->get($this->data['table'])->row_array();
		
		// Add formating to the data.
		if(isset($this->data['data'][$this->data['table'] . 'Extra']))
		{
			$this->data['data'][$this->data['table'] . 'Extra'] = json_decode($this->data['data'][$this->data['table'] . 'Extra'], TRUE);
		} else
		{
			$this->data['data'][$this->data['table'] . 'Extra'] = array();
		}
		
		// See if we need to include any media info as well
		foreach($this->data['bucket']['CMS_BucketsFields'] AS $key => $row)
		{
			if(isset($this->data['data'][$key]) && 
					(($row['type'] == 'cms-image') || ($row['type'] == 'cms-image-crop')))
			{
				$this->data['data'][$key . '_media'] = $this->cms_media_model->get_by_id($this->data['data'][$key]);
			}
		}
		
		$this->_add_edit_shared_func(true);
	}
	
	// ------------------ Internal Helper Functions ---------------- //
	
	//
	// Shared functionality between add / edit.
	//
	private function _add_edit_shared_func($update = FALSE)
	{
		// Get the fields for table.
		$this->data['fields'] = $this->db->field_data($this->data['table']);
		$this->data['skip'] = array('Id', 'UpdatedAt', 'CreatedAt', 'Status', 'Order');
		$this->data['relations'] = array();
	
		// Detect Enums.
		foreach($this->data['fields'] AS $key => $row)
		{
			// Deal with look ups. 
			if(isset($this->data['bucket']['CMS_BucketsLookUps'][$row->name]))
			{
				$this->_do_looksup($this->data['bucket']['CMS_BucketsLookUps'][$row->name], $row, $key);
			}
			
			// Deal with Enums
			if(($row->type == 'enum') && ($row->name != $this->data['table'] . 'Status'))
			{
				$sql = "SHOW COLUMNS FROM " . $this->data['table'] . " WHERE Field = '" . $row->name . "'";
				$d = $this->db->query($sql)->row_array();
				$e = $d['Type'];
				$e = str_ireplace('enum(', '', $e);
				$e = str_ireplace(')', '', $e);
				$e = str_ireplace("'", '', $e);
				$e = explode(',', $e);
				$this->data['fields'][$key]->enums = array();
				foreach($e AS $key2 => $row2)
				{
					$this->data['fields'][$key]->enums[$row2] = $row2; 
				}
			}
		}
	
		// Manage bucket relations.
		$relations = $this->data['bucket']['CMS_BucketsRelations'];
		if(! empty($relations))
		{
			$this->data['relations'] = json_decode($relations, TRUE);
			foreach($this->data['relations'] AS $key => $row)
			{
				// Get options to relationship.
				$this->data['relations'][$key]['options'] = array();
				$this->db->order_by($row['table'] . 'Title');
				$o = $this->db->get($row['table'])->result_array();
				foreach($o AS $key2 => $row2)
				{ 
					$this->data['relations'][$key]['options'][$row2[$row['table'] . 'Id']] = $row2[$row['table'] . 'Title'];
					$this->data['relations'][$key]['tags'][] = $row2[$row['table'] . 'Title'];
				}
				
				// Get selected
				$this->data['relations'][$key]['selected'] = array(); 
				if($this->data['type'] == 'edit')
				{
					$this->load->model('cms_relations_model');
					$this->cms_relations_model->set_bucket($this->data['bucket']['CMS_BucketsName']);
					$this->cms_relations_model->set_table($row['table']);
					$this->cms_relations_model->set_entry($this->uri->segment(4));
					$d = $this->cms_relations_model->get();
					
					foreach($d AS $key2 => $row2)
					{
						$this->data['relations'][$key]['selected'][] = $row2['CMS_RelationsTableId'];
					}
				}
			}
		}
	
		// Manage posted data.
		if($this->input->post('submit'))
		{
			$this->load->library('form_validation');
			
			// Set validation
			foreach($this->data['fields'] AS $key => $row)
			{	
				if(in_array(str_ireplace($this->data['table'], '', $row->name), $this->data['skip'])) { continue; }
				
				if($this->input->post($row->name))
				{
					$q[$row->name] = $this->input->post($row->name);
				}
				
				if($row->name == $this->data['table'] . 'Title')
				{
					$this->form_validation->set_rules($row->name, str_ireplace($this->data['table'], '', $row->name), 'trim|required');
				} else
				{
					$this->form_validation->set_rules($row->name, str_ireplace($this->data['table'], '', $row->name), 'trim');
				}
			}
				
			// Set validation on any relations.
			foreach($this->data['relations'] AS $key => $row)
			{
			  $this->form_validation->set_rules($row['table'], $row['name'], '');
			}
			
			$this->form_validation->set_rules($this->data['table'] . 'Status', 'Status', 'trim|required');
			
			// Deal with any date options.
			if($this->input->post('dates') && is_array($_POST['dates']))
			{
			  foreach($_POST['dates'] AS $key => $row)
			  {
			  	if(! isset($_POST[$row]))
			  	{
			  		continue;
			  	}
			  	
			  	$q[$row] = date('Y-m-d', strtotime($_POST[$row]));
			  }
			}
			
			// Deal with any datetimes options.
			if($this->input->post('datetimes') && is_array($_POST['datetimes']))
			{
			  foreach($_POST['datetimes'] AS $key => $row)
			  {
			  	if(! isset($_POST[$row]))
			  	{
			  		continue;
			  	}
			  	
			  	$q[$row] = date('Y-m-d G:i:s', strtotime($_POST[$row]));
			  }
			}				
			
			// Deal with any pre validation formatting. 
			$q = $this->_do_pre_validation_formatting($q);
			
			// Validate the post.
			if($this->form_validation->run() != FALSE)
			{		
				$q[$this->data['table'] . 'Status'] = $this->input->post($this->data['table'] . 'Status');
										
				// Deal with an extra col. Make it Json.
				if(isset($q[$this->data['table'] . 'Extra']))
				{
					$q[$this->data['table'] . 'Extra'] = json_encode($q[$this->data['table'] . 'Extra']);
				}
					
				if($update)
				{
					$this->db->where($this->data['table'] . 'Id', $this->uri->segment(4));
					$this->db->update($this->data['table'], $q);
					$this->_do_relation($this->uri->segment(4));
					$this->_do_tags($this->uri->segment(4));

					// Fire after event.		
					CMS\Libraries\Event::fire('after.update', array($this->data['table'], $this->uri->segment(4), 'data' => $q));
				} else
				{
					$this->db->select_max($this->data['table'] . 'Order', 'max');
					$m = $this->db->get($this->data['table'])->result_array();
					$q[$this->data['table'] . 'Order'] = (isset($m[0]['max'])) ? $m[0]['max'] + 1 : 0;
					$q[$this->data['table'] . 'CreatedAt'] = date('Y-m-d G:i:s');
					$q[$this->data['table'] . 'UpdatedAt'] = date('Y-m-d G:i:s');
					
					// Hook just before insert.
					if(isset($this->data['cms']['cp_hooks']['bucket_before_insert']))
					{
						if(! empty($this->data['cms']['cp_hooks']['bucket_before_insert']['library']))
						{
							$this->load->library($this->data['cms']['cp_hooks']['bucket_before_insert']['library']);
							$q = $this->{strtolower($this->data['cms']['cp_hooks']['bucket_before_insert']['library'])}->{$this->data['cms']['cp_hooks']['bucket_before_insert']['method']}($this->data['table'], $q);
						}
					}
					
					$this->db->insert($this->data['table'], $q);
					$id = $this->db->insert_id();
					$this->_do_relation($id);
					$this->_do_tags($id);
					
					// Fire after event.		
					CMS\Libraries\Event::fire('after.insert', array($this->data['table'], $id, 'data' => $q));
				}
				
				redirect($this->data['cms']['cp_base'] . '/buckets/listview/' . $this->uri->segment(3));
			}
		}
		
		$this->load->view('cms/templates/app-header', $this->data);
		$this->load->view('cms/buckets/add-edit', $this->data);
		$this->load->view('cms/templates/app-footer', $this->data);
	}

	//
	// Deal with any relations that are tags.
	//
	private function _do_tags($id)
	{
		foreach($this->data['relations'] AS $key => $row)
		{
		  // Delete old relations.
		  if(isset($_POST['tags'][$row['table']]))
		  {
				$this->_delete_relations($id, $row['table'], $this->data['bucket']['CMS_BucketsName']);
			}
			
			// Make sure we have a post.
		  if((! isset($_POST['tags'][$row['table']])) || (! is_array($_POST['tags'][$row['table']]))) 
		  { 
		  	continue; 
		  }
		  
		  // Insert relations.
		  foreach($_POST['tags'][$row['table']] AS $key2 => $row2)
		  {
				// See if the tag is already in the system.
				$this->db->where($row['table'] . 'Title', $row2);
				$t = $this->db->get($row['table'])->row_array();
				if(! $t)
				{
					$p = array();
					$p[$row['table'] . 'Title'] = $row2;
					$p[$row['table'] . 'CreatedAt'] = date('Y-m-d G:i:s');
					$this->db->insert($row['table'], $p);
					$tagid = $this->db->insert_id();
				}	else
				{
					$tagid = $t[$row['table'] . 'Id'];
				}				

		  	$r['CMS_RelationsBucket'] = $this->data['bucket']['CMS_BucketsName'];
		  	$r['CMS_RelationsTable'] = $row['table'];
		  	$r['CMS_RelationsTableId'] = $tagid; 
		  	$r['CMS_RelationsEntryId'] = $id;
		  	$this->cms_relations_model->insert($r);
		  }
		}
	}
	
	//
	// Deal with any relations.
	//
	private function _do_relation($id)
	{
		foreach($this->data['relations'] AS $key => $row)
		{
		  // Delete old relations.
			if(isset($_POST[$row['table']]))
			{
				$this->_delete_relations($id, $row['table'], $this->data['bucket']['BucketsName']);
			}
			
			// Make sure we have a post.
		  if((! isset($_POST[$row['table']])) || (! is_array($_POST[$row['table']]))) 
		  { 
		  	continue; 
		  }
		  
		  // Insert relations.
		  foreach($_POST[$row['table']] AS $key2 => $row2)
		  {
		  	$r['CMS_RelationsBucket'] = $this->data['bucket']['BucketsName'];
		  	$r['CMS_RelationsTable'] = $row['table'];
		  	$r['CMS_RelationsTableId'] = $row2; 
		  	$r['CMS_RelationsEntryId'] = $id;
		  	$this->cms_relations_model->insert($r);
		  }
		}
	}
	
	//
	// Delete relations.
	//
	private function _delete_relations($id = NULL, $table = NULL, $bucket = NULL, $tableid = NULL)
	{
		$this->load->model('cms_relations_model');
		
		if(! is_null($bucket))
		{
			$this->cms_relations_model->set_bucket($bucket);
		}
		
		if(! is_null($table))
		{
			$this->cms_relations_model->set_table($table);
		}
		
		if(! is_null($id))
		{
			$this->cms_relations_model->set_entry($id);
		}
		
		if(! is_null($tableid))
		{
			$this->cms_relations_model->set_table_id($tableid);
		}
		
		$this->cms_relations_model->delete_all();
	}
	
	//
	// Manage lookups. 
	//
	private function _do_looksup($r, $row, $key)
	{		
		// Set name
		$this->db->select($r['tablevalue'] . " AS value", false);
		$this->db->select($r['tablename'] . " AS name", false);
		
		// Set where
		if(! empty($r['tablewhere']))
		{
		  $this->db->where($r['tablewhere']);
		}
		
		// Set order
		if(! empty($r['tableorder']))
		{
		  $this->db->order_by($r['tableorder']);
		}
		
		// Set group by
		if(! empty($r['tablegroup']))
		{
		  $this->db->group_by($r['tablegroup']);
		}
	
		// Setup start.
		$this->data['fields'][$key]->select_options = array();
		if(isset($r['start']) && (! empty($r['start'])))
		{
			$this->data['fields'][$key]->select_options[0] = $r['start'];
		}
		
		// Make query and get look up array.
		$d = $this->db->get($r['table'])->result_array();
		foreach($d AS $key2 => $row2)
		{
		  $this->data['fields'][$key]->select_options[$row2['value']] = $row2['name'];
		}
	}
	
	//
	// Manage pre-validation custom formatting before inserting or updating data.
	//
	private function _do_pre_validation_formatting($data)
	{
		// Make sure the title is trimmed.
		$data[$this->data['table'] . 'Title'] =	trim($data[$this->data['table'] . 'Title']);		
		
		// Loop through the fields and do extra formatting.
		foreach($data AS $key => $row)
		{
		  if(isset($data[$key . 'Format']) && ($data[$key . 'Format'] == 'auto'))
		  {
		  	$this->load->library('typography');
		  	$data[$key] = $this->typography->auto_typography($data[$key]);
		  }
		}
		
		return $data;
	}
}

/* End File */