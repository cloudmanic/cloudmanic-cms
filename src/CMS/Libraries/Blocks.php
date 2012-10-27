<?php

//
// Company: Cloudmanic Labs, LLC
// Website: http://cloudmanic.com
//

namespace CMS\Libraries;

class Blocks extends Model
{
	//
	// Return a block by key.
	//
	public static function get_by_name($key)
	{
		self::set_select('BlocksBody');
		self::set_col('BlocksName', $key);
		$data = self::get();
		return (isset($data[0]['BlocksBody'])) ? $data[0]['BlocksBody'] : '';
	}
}

/* End File */