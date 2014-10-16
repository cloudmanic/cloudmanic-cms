<script type="text/javascript">
<?php foreach($state AS $key => $row) : ?> 
	site.state['<?=$key?>'] = '<?=$row?>';
<?php endforeach; ?>
site.on_new_page();
</script>

<?php if($this->input->is_ajax_request()) : ?>
<script type="text/javascript">
$('title').text('<?=$page_title?>');
</script>
<?php endif; ?>

<?php if(! $this->input->is_ajax_request()) : ?>
			</div>
			<footer class="footer"><p>&copy; Cloudmanic Labs, LLC 2014 - Version <?=CMS::get_version()?></p></footer>
    </div>
	<?=CMS\Libraries\Config::get('app-footer-body')?>
	
	<?php 
		foreach(CMS\Libraries\Config::get('app-footer-files') AS $key => $row)
		{
			include($row);
		}
	?>	
  </body>
</html>
<?php endif; ?>
