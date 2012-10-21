<?php
//
// Company: Cloudmanic Labs, LLC
// Website: http://cloudmanic.com
// Date: 10/18/2012
//

require '../../vendor/autoload.php';

// This is called after an insert operation into a CMS table.
CMS\Libraries\Event::listen('after.insert', function($table, $id, $data) {
	echo "Inserted data into $table and returned $id <br />";
  echo '<pre>' . print_r($data, TRUE) . '</pre>';
});

// This is called after an update operation into a CMS table.
CMS\Libraries\Event::listen('after.update', function($table, $id, $data) {
	echo "Updated data of $table with $id<br />";
  echo '<pre>' . print_r($data, TRUE) . '</pre>';
});

// This is called after a delete operation into a CMS table.
CMS\Libraries\Event::listen('after.delete', function($table, $id) {
	echo "Data data in $table with $id<br />";
});


CMS::framework('laravel3', '../../application');
require CMS::boostrap('../../vendor');