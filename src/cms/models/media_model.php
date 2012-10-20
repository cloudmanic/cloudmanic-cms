<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//
// Company: Cloudmanic Labs, LLC
// Website: http://cloudmanic.com
//

class Media_Model extends MY_Model 
{ 
	//
	// Set search.
	//
	function set_search($term)
	{
		$this->db->like('MediaFile', $term);
	}

	//
	// Over ride delete so we can delete the file as well.
	//
	function delete($id)
	{
		// Delete files from the file host.
		if($data = $this->get_by_id($id))
		{
			switch($data['MediaStore'])
			{
				case 'rackspace-cloud-files':
					$this->load->spark('cloudmanic-storage/1.0.2');
					$this->storage->load_driver('rackspace-cf');
					$this->storage->delete_file($this->data['cms']['cp_media_rackspace_container'], $data['MediaPath'] . $data['MediaFile']);
					
					// Delete thumb
					if($data['MediaIsImage'])
					{
						$this->storage->delete_file($this->data['cms']['cp_media_rackspace_container'], $data['MediaPathThumb'] . $data['MediaFileThumb']);
					}
				break;
				
				case 'amazon-web-services-s3':
					$this->load->spark('cloudmanic-storage/1.0.2');
					$this->storage->load_driver('amazon-s3');
					$this->storage->delete_file($this->data['cms']['cp_media_amazon_s3_container'], $data['MediaPath'] . $data['MediaFile']);
					
					// Delete thumb
					if($data['MediaIsImage'])
					{
						$this->storage->delete_file($this->data['cms']['cp_media_amazon_s3_container'], $data['MediaPathThumb'] . $data['MediaFileThumb']);
					}
				break;
				
				case 'local-files':
					@unlink($this->data['sigcms']['cp_media_local_dir'] . $data['MediaFile']);
					@unlink($this->data['sigcms']['cp_media_local_dir'] . $data['MediaFileThumb']);
				break;
			}
		}
		
		return parent::delete($id);
	}

 	//
 	// Add extra data to get request.
 	//
 	function _format_get(&$data)
 	{
		// Add some date formats.
		if(isset($data[$this->table_base . 'UpdatedAt']))
		{
			$data['DateFormat1'] = date('n/j/Y', strtotime($data[$this->table_base . 'UpdatedAt']));
		}
		
		// Make a smaller file name.
		$this->load->helper('text');
		if(isset($data['MediaFile']))
		{
			$data['FileEllipse'] = ellipsize($data['MediaFile'], 32, .5);
		}
		
 		switch($data['MediaStore'])
		{
			case 'rackspace-cloud-files':
				$data['url'] = $this->data['cms']['cp_media_rackspace_url'] . $data['MediaPath'] . $data['MediaFile'];
				$data['sslurl'] = $this->data['cms']['cp_media_rackspace_ssl_url'] . $data['MediaPath'] . $data['MediaFile'];
				$data['thumburl'] = '';
				$data['thumbsslurl'] = '';
				
				// If image build a thumbnail
				if($data['MediaIsImage'])
				{
					$data['thumburl'] = $this->data['cms']['cp_media_rackspace_url'] . $data['MediaPathThumb'] . $data['MediaFileThumb'];
					$data['thumbsslurl'] = $this->data['cms']['cp_media_rackspace_ssl_url'] . $data['MediaPathThumb'] . $data['MediaFileThumb'];
				} else
				{
					if($data['MediaType'] == 'video')
					{
						$data['thumburl'] = $data['thumbsslurl'] = $this->data['cms']['assets_base'] . '/images/v-ico.png';
					} else
					{
						$data['thumburl'] = $data['thumbsslurl'] = $this->data['cms']['assets_base'] . '/images/doc-ico.png';
					}
				}
			break;
			
			case 'amazon-web-services-s3':
				$data['url'] = $this->data['cms']['cp_media_amazon_s3_url'] . $data['MediaPath'] . $data['MediaFile'];
				$data['sslurl'] = $this->data['cms']['cp_media_amazon_s3_ssl_url'] . $data['MediaPath'] . $data['MediaFile'];
				$data['thumburl'] = '';
				$data['thumbsslurl'] = '';
				
				// If image build a thumbnail
				if($data['MediaIsImage'])
				{
					$data['thumburl'] = $this->data['cms']['cp_media_amazon_s3_url'] . $data['MediaPathThumb'] . $data['MediaFileThumb'];
					$data['thumbsslurl'] = $this->data['cms']['cp_media_amazon_s3_ssl_url'] . $data['MediaPathThumb'] . $data['MediaFileThumb'];
				} else
				{
					if($data['MediaType'] == 'video')
					{
						$data['thumburl'] = $data['thumbsslurl'] = $this->data['cms']['assets_base'] . '/images/v-ico.png';
					} else
					{
						$data['thumburl'] = $data['thumbsslurl'] = $this->data['cms']['assets_base'] . '/images/doc-ico.png';
					}
				}
			break;
			
			case 'local-files':
				$data['url'] = $this->data['cms']['cp_media_local_url'] . $data['MediaPath'] . $data['MediaFile'];
				$data['sslurl'] = $this->data['cms']['cp_media_local_ssl_url'] . $data['MediaPath'] . $data['MediaFile'];
				$data['thumburl'] = '';
				$data['thumbsslurl'] = '';
				
				// If image build a thumbnail
				if($data['MediaIsImage'])
				{
					$data['thumburl'] = $this->data['cms']['cp_media_local_url'] . $data['MediaPathThumb'] . $data['MediaFileThumb'];
					$data['thumbsslurl'] = $this->data['cms']['cp_media_local_ssl_url'] . $data['MediaPathThumb'] . $data['MediaFileThumb'];
				} else
				{
					if($data['MediaType'] == 'video')
					{
						$data['thumburl'] = $data['thumbsslurl'] = $this->data['cms']['assets_base'] . '/images/v-ico.png';
					} else
					{
						$data['thumburl'] = $data['thumbsslurl'] = $this->data['cms']['assets_base'] . '/images/doc-ico.png';
					}
				}
			break;
		}
 	
 		return $data;
 	}
}

/* End File */