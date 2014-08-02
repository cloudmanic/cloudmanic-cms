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
				if(in_array(str_ireplace($table, '', $row->name), $skip)) 
				{ 
					continue; 
				}
				
				// See if this is a custom field. 
				if(isset($bucket['CMS_BucketsFields'][$row->name]) && 
					($bucket['CMS_BucketsFields'][$row->name]['type'] != 'default'))
				{
					$row->type = $bucket['CMS_BucketsFields'][$row->name]['type'];
					$row->field = $bucket['CMS_BucketsFields'][$row->name];
				} else
				{
					$row->field = array();
				}
				
				// Set label. We either have a custom label or a label from the db col name.
				if(isset($bucket['CMS_BucketsLabels'][$row->name]))
				{
					$label = $bucket['CMS_BucketsLabels'][$row->name] . ':';
				} else
				{
					$label = str_ireplace($table, '', $row->name . ':');
				}
		?>
	
		<?php if($row->type != 'none') : ?>
		  <p class="control-group <?=(form_error($row->name)) ? 'error' : ''?>">		  	
		  	<?php 
		  		switch($row->type)
		  		{ 
						case 'decimal':
						case 'string':
						case 'varchar':
							echo form_label($label, $row->name);
							echo form_input($row->name, set_value($row->name, element($row->name, $data, '')));
						break;
						
						case 'password':
							echo form_label($label, $row->name);
							echo form_password($row->name, set_value($row->name, element($row->name, $data, '')));
						break;
						
						case 'mediumtext':
						case 'text':
						case 'blob':
							echo form_label($label, $row->name);
							echo form_textarea(array('name' => $row->name, 'value' => set_value($row->name, element($row->name, $data, '')), 'cols' => '44', 'rows' => '24'));
						break;
						
						case 'disabled-text':
							echo form_label($label, $row->name);
							echo '<input name="' . $row->name . '" value="' . set_value($row->name, element($row->name, $data, '')) . '" disabled="disabled" />';
						break;
						
						case 'datetime':
						case 'date':
							echo form_label($label, $row->name);
							echo form_input($row->name, set_value($row->name, date('n/j/Y', strtotime(element($row->name, $data, date('n/j/Y'))))), 'class="datepicker"');
							echo form_hidden('dates[]', $row->name);
						break;
						
						case 'enum':
							echo form_label($label, $row->name);
							echo form_dropdown($row->name, $row->enums, set_value($row->name, element($row->name, $data, '')));
							echo form_error($row->name, '<span class="help-block">', '</span>');
						break;
						
						case 'int':
							echo form_label($label, $row->name);
							// This is a lookup to another table.
							if(isset($row->select_options) && is_array($row->select_options))
							{
								echo form_dropdown($row->name, $row->select_options, set_value($row->name, element($row->name, $data, '')));
								echo form_error($row->name, '<span class="help-block">', '</span>');
							} else
							{
								echo form_input($row->name, set_value($row->name, element($row->name, $data, '')));
							}
						break;
						
						case 'timestamp':
							echo form_label($label, $row->name);
							echo $this->load->view('cms/fields/cms-timestamp', array('row' => $row, 'data' => $data, 'bucket' => $bucket));						
						break;
						
						case 'tinyint':
							echo form_label($label, $row->name);
							echo $this->load->view('cms/fields/cms-tinyint', array('row' => $row, 'data' => $data, 'bucket' => $bucket));						
						break;	
						
						case 'cms-image':
							echo form_label($label, $row->name);
							echo $this->load->view('cms/fields/cms-image', array('row' => $row, 'data' => $data, 'bucket' => $bucket));
						break;
						
						case 'cms-redactor':
							echo form_label($label, $row->name);
							echo $this->load->view('cms/fields/cms-redactor', array('row' => $row, 'data' => $data, 'bucket' => $bucket));
						break;
						
						case 'cms-image-crop':
							echo form_label($label, $row->name);
							echo $this->load->view('cms/fields/cms-image', array('row' => $row, 'data' => $data, 'bucket' => $bucket));
						break;
						
						case 'cms-system-textarea':
							echo form_label($label, $row->name);
							echo $this->load->view('cms/fields/cms-system-textarea', array('row' => $row, 'data' => $data, 'bucket' => $bucket));
						break;
						
						case 'cms-type-ahead':
							echo form_label($label, $row->name);
							echo $this->load->view('cms/fields/cms-type-ahead', array('row' => $row, 'data' => $data, 'bucket' => $bucket, 'field' => $row->field));
						break;
						
						case 'ignore':
							continue;
						break;
					}	
					
					// Do we have a custom field?
					if($path = CMS\Libraries\Fields::get_custom_path($row->type))
					{
						require($path);
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
				<?php 
				if(! isset($row['tags']))
				{
					$row['tags'] = array();
				}
				?>
				
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
		
		<?php if(isset($bucket['CMS_BucketsFields'][$table . 'Status']) && ($bucket['CMS_BucketsFields'][$table . 'Status'] != 'none')) : ?>
			<?=form_hidden($table . 'Status', 'Active')?>
		<?php else : ?>
			<p class="control-group <?=(form_error($table . 'Status')) ? 'error' : ''?>">
		  	<?=form_label('Status:', $table . 'Status')?>
				<?=form_dropdown($table . 'Status', CMS\Libraries\Config::get('status-options'), set_value($table . 'Status', element($table . 'Status', $data, CMS\Libraries\Config::get('status-default'))))?>
				<?=form_error($table . 'Status', '<span class="help-block">', '</span>')?>	
			</p>
		<?php endif; ?>
	  
		<div class="row">
			<?php if(isset($data[$table . 'Id'])) : ?>
			<div class="pull-left margin-left-20">
				<a href="<?=site_url($cms['cp_base'] . '/buckets/delete/' . $bucket['CMS_BucketsId'] . '/' . $data[$table . 'Id'])?>" data-action="entry-delete">Delete Entry</a>
			</div>
			<?php endif; ?>
		  
		  <div class="pull-right">		  
		  	<button type="submit" class="btn btn-primary" name="btn" value="save">Save</button> or
		  	
		  	<?php if($this->uri->segment(2) == 'edit') : ?>
		  	
		  		<?php if(CMS\Libraries\Config::get('edit-continue-button') == 'Save_And_Continue') : ?>
		  			<input type="hidden" name="redirect_url" value="" id="redirect_url" />
						<button type="submit" class="btn btn-primary" name="btn" value="save_continue" id="save_cont">Save &amp; Continue</button>
					<?php endif; ?>
					
					<?php if(CMS\Libraries\Config::get('edit-continue-button') == 'Save_And_Continue_Editing') : ?>
						<button type="submit" class="btn btn-primary" name="btn" value="save_continue_editing" id="save_cont_editing">Save &amp; Continue Editing</button>					
					<?php endif; ?>
					
				<?php else: ?>
				
					<?php if(CMS\Libraries\Config::get('edit-continue-button') == 'Save_And_Continue') : ?>
						<input type="hidden" name="redirect_url" value="<?=current_url()?>" id="redirect_url" />
						<button type="submit" class="btn btn-primary" name="btn" value="save_add" id="save_add">Save &amp; Add Another</button>
					<?php endif; ?>
					
					<?php if(CMS\Libraries\Config::get('edit-continue-button') == 'Save_And_Continue_Editing') : ?>
						<button type="submit" class="btn btn-primary" name="btn" value="save_continue_editing" id="save_cont_editing">Save &amp; Continue Editing</button>					
					<?php endif; ?>					
		  	<?php endif; ?>
		  	
		  	<a href="<?=site_url($cms['cp_base'] . '/buckets/listview/' . $bucket['CMS_BucketsId'])?>" class="cancel-link no-deep-true">Cancel</a>
		  </div>	
		</div>
	  
	  <?=form_hidden('submit', 'submit')?>
	<?=form_close()?>
</div>

<script type="text/javascript">

<?php if(isset($data[$table . 'Id'])) : ?>
site.add_edit_init('<?=$this->uri->segment(2)?>', <?=$bucket['CMS_BucketsId']?>, <?=$data[$table . 'Id']?>);
<?php else : ?>
site.add_edit_init('<?=$this->uri->segment(2)?>', <?=$bucket['CMS_BucketsId']?>, null);
<?php endif; ?>


media.max_file_size = '<?=($cms['cp_media_file_max_size'] * 0.0009765625)?>mb';
media.filter = '<?=str_ireplace('|', ',', $cms['cp_media_file_types'])?>';
media.init();
</script>
