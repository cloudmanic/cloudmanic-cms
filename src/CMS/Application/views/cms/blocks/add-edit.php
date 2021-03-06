<?=$this->load->view('cms/blocks/section-header')?>

<?php
$raw = 'selected';
$raw_show = 'block';
$raw_name = 'CMS_BlocksBody';
$redact = '';
$redact_show = 'none';
$redact_name = 'no';
if(isset($data['CMS_BlocksEditor']) && ($data['CMS_BlocksEditor'] != 'raw'))
{
	$raw = '';
	$raw_show = 'none';
	$raw_name = 'no';
	$redact = 'selected';
	$redact_show = 'block';
	$redact_name = 'CMS_BlocksBody';
}
?>

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
	
	
	  <p class="control-group <?=(form_error('CMS_BlocksName')) ? 'error' : ''?>">
	  	<?=form_label('Name:', 'CMS_BlocksName')?>
	  	<?=form_input('CMS_BlocksName', set_value('CMS_BlocksName', element('CMS_BlocksName', $data, '')))?>
			<?=form_error('CMS_BlocksName', '<span class="help-block">', '</span>')?>
	  </p>
	
		<div class="control-group">
			<?=form_label('Editor:', 'Editor')?>
			<select id="Editor" name="CMS_BlocksEditor">
				<option value="raw" <?=$raw?>>Raw Editor</option>
				<option value="redactor" <?=$redact?>>Redactor Editor</option>				
			</select>
		</div>	
	
	  <p id="block-raw" class="control-group <?=(form_error('CMS_BlocksBody')) ? 'error' : ''?>" style="display: <?=$raw_show?>;">
	  	<?=form_label('Body:', 'CMS_BlocksBody')?>
	  	<?=form_textarea(array('name' => $raw_name, 'value' => set_value('CMS_BlocksBody', element('CMS_BlocksBody', $data, '')), 'cols' => '44', 'rows' => '24'))?>
			<?=form_error('CMS_BlocksBody', '<span class="help-block">', '</span>')?>
	  </p>
	  
		<div id="block-redactor" class="control-group <?=(form_error('CMS_BlocksBody')) ? 'error' : ''?>" style="display: <?=$redact_show?>;">
			<?=form_label('Body:', 'CMS_BlocksBody')?>
			<textarea class="redactor" name="<?=$redact_name?>"><?=element('CMS_BlocksBody', $data, '')?></textarea>	  
		</div>

		<div id="block-redactor" class="control-group <?=(form_error('CMS_BlocksNote')) ? 'error' : ''?>">
			<?=form_label('Note:', 'CMS_BlocksNote')?>
			<textarea class="redactor" name="CMS_BlocksNote" placeholder="Some text to help you remember where and how this block is used."><?=element('CMS_BlocksNote', $data, '')?></textarea>	  
		</div>
	  
	  <div class="row">
	  	<div class="pull-right">		  
	  		<button type="submit" class="btn btn-primary">Save</button> or
				<a href="<?=site_url($cms['cp_base'] . '/blocks')?>" class="cancel-link">Cancel</a>
			</div>	
		</div>
		
	  <?=form_hidden('submit', 'submit')?>
	<?=form_close()?>
</div>

<script type="text/javascript">
$(document).ready(function () {
	// Editor
	$('#Editor').change(function () {
		var val = $(this).val();

		if(val == 'raw')
		{
			$('#block-raw').show();
			$('#block-redactor').hide();
			$('#block-redactor').find('textarea').attr('name', 'no');
			$('#block-raw').find('textarea').attr('name', 'CMS_BlocksBody');			
		} else
		{
			$('#block-raw').hide();
			$('#block-redactor').show();	
			$('#block-redactor').find('textarea').attr('name', 'CMS_BlocksBody');
			$('#block-raw').find('textarea').attr('name', 'no');						
		}
	});

	// Redactor
	$('.redactor').redactor({
		minHeight: 300,
		imageUpload: cp_base + 'media/upload?redactor=true',
		fileUpload: cp_base + 'media/upload?redactor=true'
	});
});
</script>