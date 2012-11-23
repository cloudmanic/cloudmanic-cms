<?=$this->load->view('cms/users/section-header')?>

<div class="row span10 well cms-form">
	<?=form_open(current_url())?>
		<?php if(validation_errors()) : ?>
			<p class="control-group error">
				<span class="help-block">Please correct your errors below.</span>
			</p>
		<?php endif; ?>
		
		<?php if($type == 'add') : ?>
			<?=form_open($cms['cp_base'] . '/users/add')?>
		<?php else : ?>
			<?=form_open($cms['cp_base'] . '/users/edit/' . $data['CMS_UsersId'])?>
		<?php endif; ?>
	
	
	  <p class="control-group <?=(form_error('CMS_UsersFirstName')) ? 'error' : ''?>">
	  	<?=form_label('First Name:', 'CMS_UsersFirstName')?>
	  	<?=form_input('CMS_UsersFirstName', set_value('CMS_UsersFirstName', element('CMS_UsersFirstName', $data, '')))?>
			<?=form_error('CMS_UsersFirstName', '<span class="help-block">', '</span>')?>
	  </p>
	  
	  <p class="control-group <?=(form_error('CMS_UsersLastName')) ? 'error' : ''?>">
	  	<?=form_label('Last Name:', 'CMS_UsersLastName')?>
	  	<?=form_input('CMS_UsersLastName', set_value('CMS_UsersLastName', element('CMS_UsersLastName', $data, '')))?>
			<?=form_error('CMS_UsersLastName', '<span class="help-block">', '</span>')?>
	  </p>
	  
	  <?php if($type == 'add') : ?>
	  <p class="control-group <?=(form_error('CMS_UsersEmail')) ? 'error' : ''?>">
	  	<?=form_label('Email:', 'CMS_UsersEmail')?>
	  	<?=form_input('CMS_UsersEmail', set_value('CMS_UsersEmail', element('CMS_UsersEmail', $data, '')))?>
			<?=form_error('CMS_UsersEmail', '<span class="help-block">', '</span>')?>
	  </p>
	  <?php endif; ?>
	  
	  <p class="control-group <?=(form_error('CMS_UsersPassword')) ? 'error' : ''?>">
	  	<?=form_label('Password:', 'CMS_UsersPassword')?>
	  	<?=form_password('CMS_UsersPassword', '')?>
			<?=form_error('CMS_UsersPassword', '<span class="help-block">', '</span>')?>
	  </p>
	  
	  
	  <p class="control-group <?=(form_error('again')) ? 'error' : ''?>">
	  	<?=form_label('Password Again:', 'again')?>
	  	<?=form_password('again', '')?>
			<?=form_error('again', '<span class="help-block">', '</span>')?>
	  </p>
	  
	  
	  <div class="row">
	  	<div class="pull-right">		  
	  		<button type="submit" class="btn btn-primary">Save</button> or
				<a href="<?=site_url($cms['cp_base'] . '/users')?>" class="cancel-link">Cancel</a>
			</div>	
		</div>
		
	  <?=form_hidden('submit', 'submit')?>
	<?=form_close()?>
</div>