<?php

//
// Company: Cloudmanic Labs, LLC
// Website: http://cloudmanic.com
//

namespace CMS\Libraries;

class Model
{
	public static $table = '';
	private static $_query = null;

	// -------------------- Setters -------------------- //
	
	//
	// Set col.
	//
	public static function set_col($key, $val)
	{
		self::set_query()->where($key, $val);	
	}

	//
	// Set select.
	//
	public static function set_select($col)
	{
		self::set_query()->select($col);
	}

	// -------------------- CRUD ----------------------- //

	//
	// Get.
	//
	public static function get()
	{
		$data = array();
		
		ORM::configure('id_column', self::$table . 'Id');
		$d = self::set_query()->find_many();
		self::clear_query();
		
		foreach($d AS $key => $row)
		{
			$data[] = $row->as_array();
		}

		return $data;
	}
	
	// ------------------ Helpers --------------------- //
	
	//
	// Clear query.
	//
	protected static function clear_query()
	{
		self::$_query = null;
	}
	
	//
	// Setup the query.
	//
	protected static function set_query()
	{
		if(is_null(self::$_query))
		{
			$table = explode('\\', get_called_class());
			self::$table = end($table);		
			self::$_query = ORM::for_table(Config::get('table_base') . self::$table);
		}
		
		return self::$_query;
	}
}

/* End File */