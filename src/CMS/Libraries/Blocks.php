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