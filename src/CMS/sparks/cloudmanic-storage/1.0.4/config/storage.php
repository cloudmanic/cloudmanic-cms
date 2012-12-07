<?php defined('BASEPATH') OR exit('No direct script access allowed');

// Amazon S3
$config['storage']['s3_access_key'] = get_instance()->data['cms']['cp_media_amazon_s3_access_key'];
$config['storage']['s3_secret_key'] = get_instance()->data['cms']['cp_media_amazon_s3_secret_key'];

// Rackspace Cloud Files 
$config['storage']['cf_username'] = get_instance()->data['cms']['cp_media_rackspace_username'];
$config['storage']['cf_api_key'] = get_instance()->data['cms']['cp_media_rackspace_key'];
$config['storage']['cf_auth_url'] = array('library' => '', 'method' => '');

/* End File */