<?php

//
// Company: Cloudmanic Labs, LLC
// Website: http://cloudmanic.com
//

namespace CMS\Libraries;

class Blocks extends Model
{
	public static $table = 'CMS_Blocks';
	
	//
	// Create block.
	//
	public static function create_block($key, $val = '')
	{
		// Query. We have to use this instead of get_by_name because a block can be empty
		// which means it returns false.
		self::set_table('CMS_Blocks');
		self::set_select('CMS_BlocksBody');
		self::set_col('CMS_BlocksName', $key);
		$data = self::get();
	
		if(! isset($data[0]['CMS_BlocksBody']))
		{
			self::set_table('CMS_Blocks');
			self::insert(array('CMS_BlocksName' => $key, 'CMS_BlocksBody' => $val));
		}
	}
	
	//
	// Return a block by key.
	//
	public static function get_by_name($key)
	{
		self::set_table('CMS_Blocks');
		self::set_select('CMS_BlocksBody');
		self::set_col('CMS_BlocksName', $key);
		$data = self::get();
		return (isset($data[0]['CMS_BlocksBody'])) ? $data[0]['CMS_BlocksBody'] : '';
	}
}

/* End File */