<?php
echo form_input($row->name, set_value($row->name, element($row->name, $data, '')));