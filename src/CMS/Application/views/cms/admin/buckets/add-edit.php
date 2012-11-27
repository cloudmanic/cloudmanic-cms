<?=$this->load->view('cms/admin/buckets/section-header')?>

<div class="row span10 well cms-form">
	<?=form_open(current_url())?>
		<?php if(validation_errors()) : ?>
			<p class="control-group error">
				<span class="help-block">Please correct your errors below.</span>
			</p>
		<?php endif; ?>
		
		<?php if($type == 'add') : ?>
			<?=form_open($cms['cp_base'] . '/admin/buckets/add')?>
		<?php else : ?>
			<?=form_open($cms['cp_base'] . "/admin/buckets/edit/$id")?>
		<?php endif; ?>
		
	  <p class="control-group <?=(form_error('CMS_BucketsName')) ? 'error' : ''?>">
	  	<?=form_label('Name:', 'CMS_BucketsName')?>
	  	<?=form_input('CMS_BucketsName', set_value('CMS_BucketsName', element('CMS_BucketsName', $data, '')))?>
			<?=form_error('CMS_BucketsName', '<span class="help-block">', '</span>')?>
	  </p>
	
		<?php if($type == 'add') : ?>
		  <p class="control-group <?=(form_error('CMS_BucketsTable')) ? 'error' : ''?>">
		  	<?=form_label('Table:', 'CMS_BucketsTable')?>
		  	<?=form_dropdown('CMS_BucketsTable', $tables, set_value('CMS_BucketsTable', element('CMS_BucketsTable', $data, '')))?>
				<?=form_error('CMS_BucketsTable', '<span class="help-block">', '</span>')?>
		  </p>
	  <?php endif; ?>
	  
	  <?php if($type == 'edit') : ?>
	  
		  <p class="control-group <?=(form_error('CMS_BucketsHeadline')) ? 'error' : ''?>">
		  	<?=form_label('Headline:', 'CMS_BucketsHeadline')?>
		  	<?=form_input('CMS_BucketsHeadline', set_value('CMS_BucketsHeadline', element('CMS_BucketsHeadline', $data, '')))?>
				<?=form_error('CMS_BucketsHeadline', '<span class="help-block">', '</span>')?>
		  </p>
		  
		  <p class="control-group <?=(form_error('CMS_BucketsDescription')) ? 'error' : ''?>">
		  	<?=form_label('Description:', 'CMS_BucketsDescription')?>
		  	<?=form_textarea(array('name' => 'CMS_BucketsDescription', 'value' => set_value('CMS_BucketsDescription', element('CMS_BucketsDescription', $data, '')), 'cols' => '44', 'rows' => '24'))?>
				<?=form_error('CMS_BucketsDescription', '<span class="help-block">', '</span>')?>
		  </p>
		  
		  <div class="fields">		  
			  <table class="table table-striped table-bordered">
				  <thead>
				  	<tr>
				  		<th>Field</th>
				  		<th>Type</th>
				  	</tr>
				  </thead>
				  
				  <tbody>
					 	<?php 
					 		foreach($fields AS $key => $row) : 
						 		$value = (isset($data['CMS_BucketsFields'][$row]['type'])) ? $data['CMS_BucketsFields'][$row]['type'] : 'default';
					 	?>
					 	<tr>
				  		<td><b><?=$row?></b></td>
				  		<td>
				  			<select name="Fields[<?=$row?>][type]">
				  				<?php 
				  					foreach($field_types AS $key2 => $row2) : 
				  						$selected = ($value == $key2) ? 'selected' : '';
				  				?>
				  					<option value="<?=$key2?>" <?=$selected?>><?=$row2?></option>
				  				<?php endforeach; ?>
				  			</select>
				  	</tr>
				  	<?php endforeach; ?>
				  </tbody>
			  </table>
		  </div>
	  
	  <?php endif; ?>
	  
	  <div class="row">
	  	<div class="pull-right">		  
	  		<button type="submit" class="btn btn-primary">Save</button> or
				<a href="<?=site_url($cms['cp_base'] . '/admin/buckets')?>" class="cancel-link">Cancel</a>
			</div>	
		</div>
		
	  <?=form_hidden('submit', 'submit')?>
	<?=form_close()?>
</div>