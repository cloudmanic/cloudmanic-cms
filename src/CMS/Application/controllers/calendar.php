<?php if(! defined('BASEPATH')) exit('No direct script access allowed');

//
// Company: Cloudmanic Labs, LLC
// Website: http://cloudmanic.com
//

class Calendar extends MY_Controller
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
	function calview()
	{						
		$this->load->view('cms/templates/app-header', $this->data);
		$this->load->view("cms/calendar/index", $this->data);
		$this->load->view('cms/templates/app-footer', $this->data);
	}
	
	//
	// Update pricing range.
	//
	function range_update()
	{
		// Did we post back?
		if($this->input->post('unit'))
		{
			$start = date('Y-n-d', strtotime($this->input->post('start')));
			$end = date('Y-n-d', strtotime($this->input->post('end')));			
			
			// Get pricing data.
			$this->db->where('PricingUnit', $this->input->post('unit'));
			$this->db->where('PricingDate >=', $start);
			$this->db->where('PricingDate <=', $end);		
			$data = $this->db->get('Pricing')->result_array();
			
			// Loop through the data and update the prices.
			foreach($data AS $key => $row)
			{
				// Is weekend?
				$weekDay = date('w', strtotime($row['PricingDate']));
				if(($weekDay == 0) || ($weekDay == 6))
				{
					$price = $this->input->post('weekend');
				} else
				{
					$price = $this->input->post('week');					
				}				
				
				$this->db->where('PricingId', $row['PricingId']);
				$this->db->update('Pricing',[ 
					'PricingPrice' => $price,
					'PricingTitle' => $this->input->post('unit') . ' $' . $price  
				]);				
			}
			
			// Redirect to the calendar.
			redirect('/calendar/calview/' . $this->uri->segment(3));				
		}
		
		$this->load->view('cms/templates/app-header', $this->data);
		$this->load->view("cms/calendar/range_update", $this->data);
		$this->load->view('cms/templates/app-footer', $this->data);		
	}
	
	//
	// Update pricing.
	//
	function pricing_update()
	{
		$this->db->where('PricingId', $this->input->post('id'));
		$this->db->update('Pricing',[ 
			'PricingPrice' => $this->input->post('price'),
			'PricingTitle' => $this->input->post('unit') . ' $' . $this->input->post('price')  
		]);
		echo 'success';
	}
	
	//
	// Return a json blob of the pricing.
	//
	function pricing()
	{
		$obj = [];
		
		// Get pricing data.
		$this->db->where('PricingDate >=', $this->input->get('start'));
		$this->db->where('PricingDate <=', $this->input->get('end'));		
		$data = $this->db->get('Pricing')->result_array();
		
		// Build object.
		foreach($data AS $key => $row)
		{
			$obj[] = [
				'id' => $row['PricingId'],
				'title' => $row['PricingTitle'],
				'start' => $row['PricingDate'],
				'end' => $row['PricingDate'],
				'price' => $row['PricingPrice'],
				'unit' => $row['PricingUnit'],
				'allDay' => true,
				'color' => ($row['PricingUnit'] == 'Low Gear') ? '#3a87ad' : 'rgb(248, 58, 34)'
			];
		}
		
		//echo '<pre>' . print_r($obj, TRUE) . '</pre>';
		
		echo json_encode($obj);
	}	
}

/* End File */