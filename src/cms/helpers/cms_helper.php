<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//
// Company: Cloudmanic Labs, LLC
// Website: http://cloudmanic.com
//

//
// Pass in a media id and return a FQDN url.
//
if(! function_exists('cms_media_url'))
{
	function cms_media_url($id, $thumb = FALSE, $ssl = FALSE)
	{
		get_instance()->load->model('cms_media_model');
		$media = get_instance()->cms_media_model->get_by_id($id);

		if(($thumb) && ($ssl)) { return $media['thumbsslurl']; }
		if($thumb) { return $media['thumburl']; }
		if((! $thumb) && ($ssl)) { return $media['sslurl']; }
		return $media['url'];
	}
}

//
// By calling this function and passing an a name a we can
// reference some block content from a view. When we create a 
// block in the control panel we give it a name. We pass that name
// in and this function returns the body of that block.
// If the block is not found it will return blank instead of erroring.
//
if(! function_exists('cms_block'))
{
	function cms_block($name)
	{
		get_instance()->load->model('cms_blocks_model');
		$block = get_instance()->cms_blocks_model->get_by_name($name);
		
		if(isset($block['BlocksBody']))
		{
			return $block['BlocksBody'];
		}
		
		return '';
	}
}

//
// Depluralize a word.
//
if(! function_exists('cms_depluralize'))
{
	function cms_depluralize($word)
	{
		$rules = array( 
		    'ss' => false, 
		    'os' => 'o', 
		    'ies' => 'y', 
		    'xes' => 'x', 
		    'oes' => 'o', 
		    'ies' => 'y', 
		    'ves' => 'f', 
		    's' => '');
		
		foreach(array_keys($rules) as $key)
		{
			if(substr($word, (strlen($key) * -1)) != $key) { continue; }
			if($key === false) { return $word; }
			return substr($word, 0, strlen($word) - strlen($key)) . $rules[$key]; 
		}
		
		return $word;
	}
}

/* End File */