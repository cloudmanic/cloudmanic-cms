<?php

//
// Company: Cloudmanic Labs, LLC
// Website: http://cloudmanic.com
//

namespace CMS\Libraries;

class Media extends Model
{
	public static $table = 'CMS_Media';
	
 	//
 	// Add extra data to get request.
 	//
 	public static function _format_get($data)
 	{
	 	$data = parent::_format_get($data);
		
 		switch($data['CMS_MediaStore'])
		{
/*
			case 'rackspace-cloud-files':
				$data['url'] = Config::get('cp_media_rackspace_url'] . $data['CMS_MediaPath'] . $data['CMS_MediaFile'];
				$data['sslurl'] = Config::get('cp_media_rackspace_ssl_url'] . $data['CMS_MediaPath'] . $data['CMS_MediaFile'];
				$data['thumburl'] = '';
				$data['thumbsslurl'] = '';
				
				// If image build a thumbnail
				if($data['CMS_MediaIsImage'])
				{
					$data['thumburl'] = Config::get('cp_media_rackspace_url'] . $data['CMS_MediaPathThumb'] . $data['CMS_MediaFileThumb'];
					$data['thumbsslurl'] = Config::get('cp_media_rackspace_ssl_url'] . $data['CMS_MediaPathThumb'] . $data['CMS_MediaFileThumb'];
				} else
				{
					if($data['CMS_MediaType'] == 'video')
					{
						$data['thumburl'] = $data['thumbsslurl'] = Config::get('assets_base'] . '/images/v-ico.png';
					} else
					{
						$data['thumburl'] = $data['thumbsslurl'] = Config::get('assets_base'] . '/images/doc-ico.png';
					}
				}
			break;
			
			case 'amazon-web-services-s3':
				$data['url'] = Config::get('cp_media_amazon_s3_url'] . $data['CMS_MediaPath'] . $data['CMS_MediaFile'];
				$data['sslurl'] = Config::get('cp_media_amazon_s3_ssl_url'] . $data['CMS_MediaPath'] . $data['CMS_MediaFile'];
				$data['thumburl'] = '';
				$data['thumbsslurl'] = '';
				
				// If image build a thumbnail
				if($data['CMS_MediaIsImage'])
				{
					$data['thumburl'] = Config::get('cp_media_amazon_s3_url'] . $data['CMS_MediaPathThumb'] . $data['CMS_MediaFileThumb'];
					$data['thumbsslurl'] = Config::get('cp_media_amazon_s3_ssl_url'] . $data['CMS_MediaPathThumb'] . $data['CMS_MediaFileThumb'];
				} else
				{
					if($data['CMS_MediaType'] == 'video')
					{
						$data['thumburl'] = $data['thumbsslurl'] = Config::get('assets_base'] . '/images/v-ico.png';
					} else
					{
						$data['thumburl'] = $data['thumbsslurl'] = Config::get('assets_base'] . '/images/doc-ico.png';
					}
				}
			break;
*/
			
			case 'local-files':
				$data['url'] = Config::get('cp_media_local_url') . $data['CMS_MediaPath'] . $data['CMS_MediaFile'];
				$data['sslurl'] = Config::get('cp_media_local_ssl_url') . $data['CMS_MediaPath'] . $data['CMS_MediaFile'];
				$data['thumburl'] = '';
				$data['thumbsslurl'] = '';
				
				// If image build a thumbnail
				if($data['CMS_MediaIsImage'])
				{
					$data['thumburl'] = Config::get('cp_media_local_url') . $data['CMS_MediaPathThumb'] . $data['CMS_MediaFileThumb'];
					$data['thumbsslurl'] = Config::get('cp_media_local_ssl_url') . $data['CMS_MediaPathThumb'] . $data['CMS_MediaFileThumb'];
				} else
				{
					if($data['CMS_MediaType'] == 'video')
					{
						$data['thumburl'] = $data['thumbsslurl'] = Config::get('assets_base') . '/images/v-ico.png';
					} else
					{
						$data['thumburl'] = $data['thumbsslurl'] = Config::get('assets_base') . '/images/doc-ico.png';
					}
				}
			break;
		}
 	
 		return $data;
 	}
}

/* End File */