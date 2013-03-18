<?php

$options = array('0' => 'No', '1' => 'Yes');

echo form_dropdown($row->name, $options, set_value($row->name, element($row->name, $data, '')));
echo form_error($row->name, '<span class="help-block">', '</span>');