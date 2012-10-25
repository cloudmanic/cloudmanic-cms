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
			<footer class="footer"><p>&copy; Cloudmanic Labs, LLC 2012</p></footer>
    </div>
  </body>
</html>
<?php endif; ?>