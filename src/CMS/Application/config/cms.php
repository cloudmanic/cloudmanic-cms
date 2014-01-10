<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['site_name'] = 'Your Site Name';
$config['assets_base'] = site_url() . 'assets';
$config['cp_base'] = '';
$config['cp_base_seg'] = 1; 
$config['cp_force_ssl'] = FALSE;
$config['cp_login_type'] = 'default';
$config['cp_home'] = 'blocks';
$config['cp_tmp_dir'] = '/tmp'; // no trailing slash.
$config['cp_thumb_width'] = '300';
$config['cp_thumb_height'] = '300';
$config['cp_clear_ci_page_cache'] = TRUE;
$config['app-header-head'] = '';
$config['app-footer-body'] = '';
$config['app-header-files'] = array();
$config['app-footer-files'] = array();
$config['status-options'] = array('Active' => 'Active', 'Disabled' => 'Disabled');		

// Uploading media.
$config['cp_media_driver'] = 'local-files'; // local-files / amazon-web-services-s3 / rackspace-cloud-files;
$config['cp_media_file_types'] = 'gif|jpg|jpeg|png|pdf|mov|avi|mp4';
$config['cp_media_file_max_size'] = 102400; // kilobytes
$config['cp_image_resize'] = '1200';

// Local file config.
$config['cp_media_local_path'] = 'uploads/';
$config['cp_media_local_dir'] = 'uploads/'; // must be from document root
$config['cp_media_local_url'] = base_url(); 
$config['cp_media_local_ssl_url'] = base_url(); 

// Amazon config
$config['cp_media_amazon_s3_access_key'] = '';
$config['cp_media_amazon_s3_secret_key'] = '';
$config['cp_media_amazon_s3_container'] = '';
$config['cp_media_amazon_s3_path'] = 'cms/';
$config['cp_media_amazon_s3_url'] = ''; // trailing slash
$config['cp_media_amazon_s3_ssl_url'] = ''; // trailing slash

// Rackspace config
$config['cp_media_rackspace_username'] = '';
$config['cp_media_rackspace_key'] = '';
$config['cp_media_rackspace_container'] = '';
$config['cp_media_rackspace_region'] = 'DFW'; 
$config['cp_media_rackspace_path'] = 'cms/';
$config['cp_media_rackspace_url'] = ''; // trailing slash
$config['cp_media_rackspace_ssl_url'] = ''; // trailing slash

/* End File */