<textarea class="redactor" name="<?=$row->name?>"><?=(isset($data[$row->name])) ? $data[$row->name] : ''?></textarea>

<script type="text/javascript">
if(! RedactorPlugins) 
{
	var RedactorPlugins = {};
}

RedactorPlugins.media_picker = {
	
	init: function ()
	{
		// Add icon to formatting toolbox
		this.addBtn('media_picker', 'Insert Image From Media', function(redactor, event, button_key) {
	 
			var html = '<div id="ajax-cont"></div>';
			
			// Fire up the Modal window.
			redactor.modalInit('Select An Image To Insert', html, 820, function () {
			
				// Make an AJAX call to get the Modal's html
				$.get('/cp/media/overlay_media_select', function (html) { 
					$('#ajax-cont').html(html);
			 	
					// Track click event.
					$('.image-click').click(function () {
						var url = $(this).attr('href');
						var insert = '<a href="' + url + '"><img src="' + url + '" /></a>';
						redactor.execCommand('inserthtml', insert);
						redactor.modalClose(); 
						return false;
					});	
						
				});
			});
		});	
	} 
};	
	
$(document).ready(function () {
	$('.redactor').redactor({
		minHeight: 300,
		plugins: ['media_picker'],
		imageUpload: cp_base + 'media/upload?redactor=true',
		fileUpload: cp_base + 'media/upload?redactor=true'
	});
});
</script>