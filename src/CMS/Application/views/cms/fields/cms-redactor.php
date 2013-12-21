<textarea class="redactor" name="<?=$row->name?>"><?=(isset($data[$row->name])) ? $data[$row->name] : ''?></textarea>

<script type="text/javascript">
$(document).ready(function () {
	$('.redactor').redactor({
		minHeight: 300,
		imageUpload: cp_base + 'media/upload?redactor=true',
		fileUpload: cp_base + 'media/upload?redactor=true'
	});
});
</script>