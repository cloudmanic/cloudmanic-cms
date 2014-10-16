<?php
require '../../vendor/autoload.php';

// Detect ENV.
if(isset($_SERVER['HTTP_HOST']) && ($_SERVER['HTTP_HOST'] == 'example.dev'))
{
	CMS::set_env('local');
}

CMS::config_file('cms.php');
CMS::framework('laravel4', '../../app');
CMS::load_configuration_from_export('./config.php');
require CMS::boostrap('../../vendor');