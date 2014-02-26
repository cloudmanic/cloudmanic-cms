<?php if(! defined('BASEPATH')) exit('No direct script access allowed');

//
// Company: Cloudmanic Labs, LLC
// Website: http://cloudmanic.com
//

class Api extends MY_Controller
{	
	//
	// Constructor …
	//
	function __construct()
	{
		parent::__construct();
	}
	
	//
	// Returns data based on what we pass in.
	//
	function get()
	{
		$model = '';
		
		// Set which model we are going to get data from.
		switch($this->input->get('type'))
		{
			case 'users':
				$this->load->model('cms_users_model');
				$model = 'cms_users_model';
			break;

			case 'blocks':
				$this->load->model('cms_blocks_model');
				$model = 'cms_blocks_model';
			break;
			
			case 'buckets':
				$this->load->model('cms_buckets_model');
				$model = 'cms_buckets_model';
			break;
			
			case 'bucket-reorder':
				$this->load->model('cms_buckets_model');
				$bucket = $this->cms_buckets_model->get_by_id($this->input->get('bucket'));
				$this->load->model('bucketdata_model');
				$this->bucketdata_model->set_table($bucket['CMS_BucketsTable']);
				$this->bucketdata_model->reorder($this->input->post('ids'));
				$this->_return_data(array());
				return;
			break;
			
			case 'bucket':
				// See if we have assigned a custom function to handle this data return.
				$cust = CMS\Libraries\Plugins::run_custom_api_call('get', $this->input->get_post('bucket'));
				if(! is_null($cust))
				{	
					$this->_return_data($cust);
					return;
				}
			
				// Default API return.
				$this->load->model('bucketdata_model');
				$this->load->model('cms_buckets_model');
						
				if(! $bucket = $this->cms_buckets_model->get_by_id($this->input->get_post('bucket')))
				{
					die('Nothing to see here.');
				}
				
				$this->bucketdata_model->set_table($bucket['CMS_BucketsTable']);
		
				// See if we have any relations to add.
				if(isset($bucket['CMS_BucketsListview']['joins']) && 
						(is_array($bucket['CMS_BucketsListview']['joins'])))
				{
				  foreach($bucket['CMS_BucketsListview']['joins'] AS $key => $row)
				  {
				  	$this->bucketdata_model->set_join($row['table'], $row['left'], $row['right'], $row['type']);	
				  }
				}
				
				$model = 'bucketdata_model';			
			break;
			
			case 'media':
				$this->load->model('cms_media_model');
				$model = 'cms_media_model';			
			break;
			
			default:
				die('Nothing to see here.');
			break;
		}
		
		// Set order
		if($this->input->get_post('order'))
		{
			if($this->input->get_post('sort'))
			{
				$this->{$model}->set_order($this->input->get_post('order') . ' ' . $this->input->get_post('sort'));
			} else
			{
				$this->{$model}->set_order($this->input->get_post('order'));				
			}
		}
		
		// Set limit / offset
		if($this->input->get_post('limit'))
		{
			if($this->input->get_post('offset'))
			{
				$this->{$model}->set_limit($this->input->get_post('limit'), $this->input->get_post('offset'));
			} else
			{
				$this->{$model}->set_limit($this->input->get_post('limit'));
			}
		}
		
		// Set search
		if($this->input->get_post('search'))
		{
			$this->{$model}->set_search($this->input->get_post('search'));
		}

		// Return data.
		$data = $this->{$model}->get();

		// Get a total count.
		if($this->input->get_post('search'))
		{
			$this->{$model}->set_search($this->input->get_post('search'));
		}
		$d = $this->{$model}->get();
		$count = count($d);

		$this->_return_data($data, $count); 
	}
	
	// ------------------- Private Helper Functions --------------------- //
	
	//
	// Format the data and return it.
	//
	private function _return_data($return, $filtered = 0)
	{
		$data = array();
		$data['status'] = 1;
		$data['count'] = count($return);
		$data['filtered'] = $filtered;
		$data['data'] = $return;
		
		// Create paging.
		if($this->input->get_post('limit') && $this->input->get_post('base'))
		{
			// hack.
			$base = $this->input->get_post('base');
			$base = str_ireplace('offset=', 'offsetold=', $base);
		
			$this->load->library('pagination');
			$config['base_url'] = '';
			$config['total_rows'] = $filtered;
			$config['per_page'] = $this->input->get_post('limit'); 
			
			// Bootstrap.
			$config['uri_segment'] = 3;
			$config['num_links'] = 20;
			$config['page_query_string'] = TRUE;
			$config['query_string_segment'] = 'offset';
			 
			$config['full_tag_open'] = '<div class="pagination"><ul>';
			$config['full_tag_close'] = '</ul></div><!--pagination-->';
			 
			$config['first_link'] = '&laquo; First';
			$config['first_tag_open'] = '<li class="prev page">';
			$config['first_tag_close'] = '</li>';
			 
			$config['last_link'] = 'Last &raquo;';
			$config['last_tag_open'] = '<li class="next page">';
			$config['last_tag_close'] = '</li>';
			 
			$config['next_link'] = 'Next &rarr;';
			$config['next_tag_open'] = '<li class="next page">';
			$config['next_tag_close'] = '</li>';
			 
			$config['prev_link'] = '&larr; Previous';
			$config['prev_tag_open'] = '<li class="prev page">';
			$config['prev_tag_close'] = '</li>';
			 
			$config['cur_tag_open'] = '<li class="active"><a href="">';
			$config['cur_tag_close'] = '</a></li>';
			 
			$config['num_tag_open'] = '<li class="page">';
			$config['num_tag_close'] = '</li>';
			
			$this->pagination->initialize($config); 
			$data['paging'] = $this->pagination->create_links();
		}
		
		if($this->input->get('format') == 'php')
		{
			echo '<pre>' . print_r($data, TRUE) . '</pre>';
		} else
		{
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode($data));
		}
	}
}

/* End File */