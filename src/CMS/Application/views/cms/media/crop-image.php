<div class="page-header crop-overlay-page-header">
	<h1><small style="margin-left: 50px; font-size: 21px;">Please, crop your image below.</small></h1>
</div>

<div class="image-crop-cont">
  <img src="<?=$up_media['url']?>" width="400" alt="crop me" id="crop-image" />
	<div class="crop-loader">
		<img src="/cms/img/ajax-loader.gif" alt="" /><br />
		<p>Cropping Image...</p>
	</div>
</div>

<div class="row">
  <div class="pull-right">	
  	<a href="#" class="btn btn-primary" id="crop-now">Crop Now</a>
  </div>
</div>

<form action="<?=site_url($cms['cp_base'] . '/media/crop_now')?>" method="post" id="crop-form">
	<input type="hidden" id="x1" name="x1" />
	<input type="hidden" id="y1" name="y1" />
	<input type="hidden" id="x2" name="x2" />
	<input type="hidden" id="y2" name="y2" />
	<input type="hidden" id="w" name="w" />
	<input type="hidden" id="h" name="h" />
	<input type="hidden" name="MediaId" value="<?=$up_media['id']?>" />
	<input type="hidden" name="target_aspect" value="<?=$aspect?>" />
	<input type="hidden" name="target_width" value="<?=$width?>" />
	<input type="hidden" name="target_height" value="<?=$height?>" />
	<input type="hidden" name="view_height" value="" />
	<input type="hidden" name="view_width" value="" />
</form>

<script type="text/javascript">
var height = ((<?=$up_media['image_height']?> / <?=$up_media['image_width']?>) * 400) + 175;
media.crop_image_view('440px', height + 'px', '<?=$aspect?>', '<?=$height?>', '<?=$width?>');
</script>

<?php
//echo '<pre>' . print_r($media, TRUE) . '</pre>';
//echo '<pre>' . print_r($up_media, TRUE) . '</pre>';
?>