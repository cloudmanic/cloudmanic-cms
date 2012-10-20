<?=$this->load->view('cms/blocks/section-header')?>

<div class="row span10 well cms-form">
	<?=form_open(current_url())?>
		<?php if(validation_errors()) : ?>
			<p class="control-group error">
				<span class="help-block">Please correct your errors below.</span>
			</p>
		<?php endif; ?>
		
		<?php if($type == 'add') : ?>
			<?=form_open($cms['cp_base'] . '/blocks/add')?>
		<?php else : ?>
			<?=form_open($cms['cp_base'] . "/blocks/edit/$id")?>
		<?php endif; ?>
	
	
	  <p class="control-group <?=(form_error('BlocksName')) ? 'error' : ''?>">
	  	<?=form_label('Name:', 'BlocksName')?>
	  	<?=form_input('BlocksName', set_value('BlocksName', element('BlocksName', $data, '')))?>
			<?=form_error('BlocksName', '<span class="help-block">', '</span>')?>
	  </p>
	  
	  <p class="control-group <?=(form_error('BlocksBody')) ? 'error' : ''?>">
	  	<?=form_label('Body:', 'BlocksBody')?>
	  	<?=form_textarea(array('name' => 'BlocksBody', 'value' => set_value('BlocksBody', element('BlocksBody', $data, '')), 'cols' => '44', 'rows' => '24'))?>
			<?=form_error('BlocksBody', '<span class="help-block">', '</span>')?>
	  </p>
	  
	  <div class="row">
	  	<div class="pull-right">		  
	  		<button type="submit" class="btn btn-primary">Save</button> or
				<a href="<?=site_url($cms['cp_base'] . '/blocks')?>" class="cancel-link">Cancel</a>
			</div>	
		</div>
		
	  <?=form_hidden('submit', 'submit')?>
	<?=form_close()?>
</div>