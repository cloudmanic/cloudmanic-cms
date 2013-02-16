<?php 
	$d = array();
	
	Cloudmanic\Database\DB::set_select('DISTINCT ' . $field['LookAhead_Column']);
	foreach(Cloudmanic\Database\DB::set_table($field['LookAhead_Table'])->get() AS $key2 => $row2)
	{
		if(! empty($row2[$field['LookAhead_Column']]))
		{
			$d[] = $row2[$field['LookAhead_Column']];
		}
	}
	
	$array = json_encode($d);
?>

<input type="text" data-provide="typeahead" name="<?=$row->name?>" value="<?=set_value($row->name, element($row->name, $data, ''))?>" data-source='<?=$array?>' />