<div class="row span6 well login-form">
	<?=form_open(current_url())?>
	  <h2 class="margin-bottom-10"><?=$cms['site_name']?> Login</h2>
	  	
			<?php if(validation_errors()) : ?>
			  <p class="control-group error"><span class="help-block">Please correct your errors below.</span></p>
			<?php endif; ?>
			
			<?php if($fail) : ?>
			  <p class="control-group error"><span class="help-block">Incorrect login. Please try again.</span></p>
			<?php endif; ?>
	  	
	  <p class="control-group <?=(form_error('UsersEmail')) ? 'error' : ''?>">
	    <?=form_label('Email', 'UsersEmail')?>
	    <?=form_input('UsersEmail', set_value('UsersEmail'))?>
	    <?=form_error('UsersEmail', '<span class="help-block">', '</span>')?>
	  </p>
	  
	  <p class="control-group <?=(form_error('UsersPassword')) ? 'error' : ''?>">
	    <?=form_label('Password', 'UsersPassword')?>
	    <?=form_password('UsersPassword')?>
	    <?=form_error('UsersPassword', '<span class="help-block">', '</span>')?>
	  </p>
	  
	  <div class="row">			  
	  	<button type="submit" class="btn btn-primary pull-right">Sign In</button>
		</div>
		
	  <?=form_hidden('submit', 'submit')?>
	<?=form_close()?>
</div>