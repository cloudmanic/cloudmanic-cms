<?php
	$o = array('none' => 'none', 'auto' => 'auto');
	echo form_textarea(array('name' => $row->name, 'value' => set_value($row->name, element($row->name, $data, '')), 'cols' => '44', 'rows' => '24'));
	echo '<div class="fr">';
	echo '<b>Format: </b>';
	echo form_dropdown($row->name . 'Format', $o, set_value($row->name . 'Format', element($row->name . 'Format',  $data, 'none')));
	echo '</div>';
	echo '<br style="clear:both;" />';