<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//
// Company: Cloudmanic Labs, LLC
// Website: http://cloudmanic.com
//

class CMS_Media_Model extends MY_Model 
{ 
	//
	// Set search.
	//
	function set_search($term)
	{
		$this->db->like('CMS_MediaFile', $term);
	}

	//
	// Over ride delete so we can delete the file as well.
	//
	function delete($id)
	{
		// Delete files from the file host.
		if($data = $this->get_by_id($id))
		{
			switch($data['CMS_MediaStore'])
			{
				case 'rackspace-cloud-files':
					$this->load->spark('cloudmanic-storage/1.0.4');
					$this->storage->load_driver('rackspace-cf');
					$this->storage->delete_file($this->data['cms']['cp_media_rackspace_container'], $data['CMS_MediaPath'] . $data['CMS_MediaFile']);
					
					// Delete thumb
					if($data['CMS_MediaIsImage'])
					{
						$this->storage->delete_file($this->data['cms']['cp_media_rackspace_container'], $data['CMS_MediaPathThumb'] . $data['CMS_MediaFileThumb']);
					}
				break;
				
				case 'amazon-web-services-s3':
					$this->load->spark('cloudmanic-storage/1.0.4');
					$this->storage->load_driver('amazon-s3');
					$this->storage->delete_file($this->data['cms']['cp_media_amazon_s3_container'], $data['CMS_MediaPath'] . $data['CMS_MediaFile']);
					
					// Delete thumb
					if($data['CMS_MediaIsImage'])
					{
						$this->storage->delete_file($this->data['cms']['cp_media_amazon_s3_container'], $data['CMS_MediaPathThumb'] . $data['CMS_MediaFileThumb']);
					}
				break;
				
				case 'local-files':
					@unlink($this->data['cms']['cp_media_local_dir'] . $data['CMS_MediaFile']);
					@unlink($this->data['cms']['cp_media_local_dir'] . $data['CMS_MediaFileThumb']);
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
		if(isset($data[$this->table . 'UpdatedAt']))
		{
			$data['DateFormat1'] = date('n/j/Y', strtotime($data[$this->table . 'UpdatedAt']));
		}
		
		// Make a smaller file name.
		$this->load->helper('text');
		if(isset($data['CMS_MediaFile']))
		{
			$data['FileEllipse'] = ellipsize($data['CMS_MediaFile'], 32, .5);
		}
		
 		switch($data['CMS_MediaStore'])
		{
			case 'rackspace-cloud-files':
				$data['url'] = $this->data['cms']['cp_media_rackspace_url'] . $data['CMS_MediaPath'] . $data['CMS_MediaFile'];
				$data['sslurl'] = $this->data['cms']['cp_media_rackspace_ssl_url'] . $data['CMS_MediaPath'] . $data['CMS_MediaFile'];
				$data['thumburl'] = '';
				$data['thumbsslurl'] = '';
				
				// If image build a thumbnail
				if($data['CMS_MediaIsImage'])
				{
					$data['thumburl'] = $this->data['cms']['cp_media_rackspace_url'] . $data['CMS_MediaPathThumb'] . $data['CMS_MediaFileThumb'];
					$data['thumbsslurl'] = $this->data['cms']['cp_media_rackspace_ssl_url'] . $data['CMS_MediaPathThumb'] . $data['CMS_MediaFileThumb'];
				} else
				{
					if($data['CMS_MediaType'] == 'video')
					{
						$data['thumburl'] = $data['thumbsslurl'] = $this->data['cms']['assets_base'] . '/images/v-ico.png';
					} else
					{
						$data['thumburl'] = $data['thumbsslurl'] = $this->data['cms']['assets_base'] . '/images/doc-ico.png';
					}
				}
			break;
			
			case 'amazon-web-services-s3':
				$data['url'] = $this->data['cms']['cp_media_amazon_s3_url'] . $data['CMS_MediaPath'] . $data['CMS_MediaFile'];
				$data['sslurl'] = $this->data['cms']['cp_media_amazon_s3_ssl_url'] . $data['CMS_MediaPath'] . $data['CMS_MediaFile'];
				$data['thumburl'] = '';
				$data['thumbsslurl'] = '';
				
				// If image build a thumbnail
				if($data['CMS_MediaIsImage'])
				{
					$data['thumburl'] = $this->data['cms']['cp_media_amazon_s3_url'] . $data['CMS_MediaPathThumb'] . $data['CMS_MediaFileThumb'];
					$data['thumbsslurl'] = $this->data['cms']['cp_media_amazon_s3_ssl_url'] . $data['CMS_MediaPathThumb'] . $data['CMS_MediaFileThumb'];
				} else
				{
					if($data['CMS_MediaType'] == 'video')
					{
						$data['thumburl'] = $data['thumbsslurl'] = $this->data['cms']['assets_base'] . '/images/v-ico.png';
					} else
					{
						$data['thumburl'] = $data['thumbsslurl'] = $this->data['cms']['assets_base'] . '/images/doc-ico.png';
					}
				}
			break;
			
			case 'local-files':
				$data['url'] = $this->data['cms']['cp_media_local_url'] . $data['CMS_MediaPath'] . $data['CMS_MediaFile'];
				$data['sslurl'] = $this->data['cms']['cp_media_local_ssl_url'] . $data['CMS_MediaPath'] . $data['CMS_MediaFile'];
				$data['thumburl'] = '';
				$data['thumbsslurl'] = '';
				
				// If image build a thumbnail
				if($data['CMS_MediaIsImage'])
				{
					$data['thumburl'] = $this->data['cms']['cp_media_local_url'] . $data['CMS_MediaPathThumb'] . $data['CMS_MediaFileThumb'];
					$data['thumbsslurl'] = $this->data['cms']['cp_media_local_ssl_url'] . $data['CMS_MediaPathThumb'] . $data['CMS_MediaFileThumb'];
				} else
				{
					if($data['CMS_MediaType'] == 'video')
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