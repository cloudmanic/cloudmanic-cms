<?=$this->load->view('cms/buckets/section-header')?>

<div class="row span10 well cms-form">
	<?=form_open(current_url())?>
	
		<?php if(validation_errors()) : ?>
			<p class="control-group error">
				<span class="help-block">Please correct your errors below.</span>
			</p>
		<?php endif; ?>

		<?php 
			foreach($fields AS $key => $row) : 
				if(in_array(str_ireplace($prefix, '', $row->name), $skip)) 
				{ 
					continue; 
				}
				
				// We skip anything with the key word format.
				if(stripos($row->name, 'Format'))
				{
					continue;
				}
				
				// If this is a users bucket strip out certain fields.
				$uf = array('UsersSalt', 'UsersLastIn', 'UsersLastActivity');
				if(($bucket['BucketsName'] == 'Users') && (in_array($row->name, $uf)))
				{
					continue;
				}
				
				// See if this is a custom field. 
				if(isset($bucket['BucketsFields'][$row->name]))
				{
					$row->type = $bucket['BucketsFields'][$row->name]['type'];
				}
				
				// Strip out password
				if(($bucket['BucketsName'] == 'Users') && ($type == 'edit') && ($row->name == 'UsersPassword'))
				{
					$data[$row->name] = 'cms-edit';
				}
				
				// Users make password field.
				if(($bucket['BucketsName'] == 'Users') && ($row->name == 'UsersPassword'))
				{
					$row->type = 'password';
				}
				
				// Set label. We either have a custom label or a label from the db col name.
				if(isset($bucket['BucketsLabels'][$row->name]))
				{
					$label = $bucket['BucketsLabels'][$row->name] . ':';
				} else
				{
					$label = str_ireplace($prefix, '', $row->name . ':');
				}
		?>
	
		<?php if($row->type != 'none') : ?>
		  <p class="control-group <?=(form_error($row->name)) ? 'error' : ''?>">
		  	<?=form_label($label, $row->name)?>
		  	
		  	<?php 
		  		switch($row->type)
		  		{ 
						case 'decimal':
						case 'string':
						case 'varchar':
							echo form_input($row->name, set_value($row->name, element($row->name, $data, '')));
						break;
						
						case 'password':
							echo form_password($row->name, set_value($row->name, element($row->name, $data, '')));
						break;
						
						case 'text':
						case 'blob':
							echo form_textarea(array('name' => $row->name, 'value' => set_value($row->name, element($row->name, $data, '')), 'cols' => '44', 'rows' => '24'));
						break;
						
						case 'date':
							echo form_input($row->name, set_value($row->name, date('n/j/Y', strtotime(element($row->name, $data, date('n/j/Y'))))), 'class="datepicker"');
							echo form_hidden('dates[]', $row->name);
						break;
						
						case 'enum':
							echo form_dropdown($row->name, $row->enums, set_value($row->name, element($row->name, $data, '')));
							echo form_error($row->name, '<span class="help-block">', '</span>');
						break;
						
						case 'int':
							// This is a lookup to another table.
							if(isset($row->select_options) && is_array($row->select_options))
							{
								echo form_dropdown($row->name, $row->select_options, set_value($row->name, element($row->name, $data, '')));
								echo form_error($row->name, '<span class="help-block">', '</span>');
							}
						break;
						
						case 'cms-image':
							echo $this->load->view('cms/fields/cms-image', array('row' => $row, 'data' => $data, 'bucket' => $bucket));
						break;
						
						case 'cms-image-crop':
							echo $this->load->view('cms/fields/cms-image', array('row' => $row, 'data' => $data, 'bucket' => $bucket));
						break;
						
						case 'cms-system-textarea':
							echo $this->load->view('cms/fields/cms-system-textarea', array('row' => $row, 'data' => $data, 'bucket' => $bucket));
						break;
					}
				?>
				
				<?=form_error($row->name, '<span class="help-block">', '</span>')?>
		  </p>
		<?php endif; ?>
	  
		<?php endforeach; ?>
		
		<?php foreach($relations AS $key => $row) : ?>
		<p class="control-group">
			<?=form_label($row['name'] . ':')?>
			<?php if($row['type'] == 'checked') : ?>
				<ul>
					<?php foreach($row['options'] AS $key2 => $row2) : ?>
						<li>
							<input type="checkbox" name="<?=$row['table']?>[]" value="<?=$key2?>" <?=set_checkbox($row['table'], $key2, (in_array($key2, $row['selected'])))?> />
							<?=$row2?>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
			
			<?php if($row['type'] == 'tags') : ?>
				<ul id="tagit-<?=$row['table']?>">
					<?php if(! isset($_POST['tags'][$row['table']])) : ?>
						<?php foreach($row['options'] AS $key2 => $row2) : ?>
							<?php if(in_array($key2, $row['selected'])) : ?>
								<li><?=$row2?></li>
							<?php endif; ?>
						<?php endforeach; ?>
					<?php else : ?>
						<?php foreach($_POST['tags'][$row['table']] AS $key2 => $row2) : ?>
							<li><?=$row2?></li>
						<?php endforeach; ?>
					<?php endif; ?>
				</ul>
				<script type="text/javascript">
				$(document).ready(function () {
					$('#tagit-<?=$row['table']?>').tagit({
						availableTags: <?=json_encode($row['tags'])?>,
						itemName: "tags",
						fieldName: "<?=$row['table']?>",
						allowSpaces: true,
						caseSensitive: false
					});
				});
				</script>
			<?php endif; ?>
		</p>
		<?php endforeach; ?>
		
		<?php if(isset($bucket['BucketsFields'][$prefix . 'Status']) && ($bucket['BucketsFields'][$prefix . 'Status'] != 'none')) : ?>
			<?=form_hidden($prefix . 'Status', 'Active')?>
		<?php else : ?>
			<p class="control-group <?=(form_error($prefix . 'Status')) ? 'error' : ''?>">
		  	<?=form_label('Status:', $prefix . 'Status')?>
				<?=form_dropdown($prefix . 'Status', array('Active' => 'Active', 'Disabled' => 'Disabled'), set_value($prefix . 'Status', element($prefix . 'Status', $data, '')))?>
				<?=form_error($prefix . 'Status', '<span class="help-block">', '</span>')?>	
			</p>
		<?php endif; ?>
	  
		<div class="row">
		  <div class="pull-right">		  
		  	<button type="submit" class="btn btn-primary">Save</button> or
		  	<a href="<?=site_url($cms['cp_base'] . '/buckets/listview/' . $bucket['BucketsId'])?>" class="cancel-link">Cancel</a>
		  </div>	
		</div>
	  
	  <?=form_hidden('submit', 'submit')?>
	<?=form_close()?>
</div>

<script type="text/javascript">
media.max_file_size = '<?=($cms['cp_media_file_max_size'] * 0.0009765625)?>mb';
media.filter = '<?=str_ireplace('|', ',', $cms['cp_media_file_types'])?>';
media.init();
</script>