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
	// Set table.
	//
	public static function set_table($table)
	{
		self::$table = $table;
	}
	
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
	
	//
	// Set Order.
	//
	public static function set_order($order, $sort = 'asc')
	{
		if(strtolower($sort) == 'asc')
		{
			self::set_query()->order_by_asc($order);
		} else
		{
			self::set_query()->order_by_desc($order);			
		}
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
			$data[] = static::_format_get($row->as_array());
		}

		return $data;
	}
	
	//
	// Get by Id.
	//
	public static function get_by_id($id)
	{
		self::set_col(self::$table . 'Id', $id);
		$data = self::get();
		return (isset($data[0])) ? $data[0] : 0;
	}
	
	// ------------------ Helpers --------------------- //
	
 	//
 	// Add extra data to get request.
 	//
 	public static function _format_get($data)
 	{ 	
 		// Give a nicely formated version.
 		if(isset($data[self::$table. 'CreatedAt']))
 		{
 			$data['CreateDateFormat1'] = date('n/j/Y', strtotime($data[self::$table . 'CreatedAt']));
 		}
 		
 		// Format any column named Date
 		if(isset($data[self::$table . 'Date']))
 		{
 			$data['DateColFormat1'] = date('n/j/Y', strtotime($data[self::$table . 'Date']));
 			$data['DateColFormat2'] = date('n/j/Y', strtotime($data[self::$table . 'Date']));
 		}
 		
 		// Format any column named CreateAt
 		if(isset($data[self::$table . 'CreatedAt']))
 		{
 			$data['CreatedAtColFormat1'] = date('n/j/Y', strtotime($data[self::$table . 'CreatedAt']));
 		}
 		
 		return $data;
 	}
 	
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
			// We might have set this ahead of time.
			if(empty(self::$table))
			{
				$table = explode('\\', get_called_class());
				self::$table = 'CMS_' . end($table);		
			}
			
			self::$_query = ORM::for_table(self::$table);
		}
		
		return self::$_query;
	}
}

/* End File */