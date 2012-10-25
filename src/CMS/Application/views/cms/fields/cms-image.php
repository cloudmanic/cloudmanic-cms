<?php
// See if this is crop or not.
$class = '';
$width = '';
$height = '';
$aspect = '';
if(isset($bucket['BucketsFields'][$row->name]['type']) && 
		($bucket['BucketsFields'][$row->name]['type'] == 'cms-image-crop'))
{
	$class = 'crop';
	$width = $bucket['BucketsFields'][$row->name]['target-width'];
	$height = $bucket['BucketsFields'][$row->name]['target-height'];
	$aspect = $bucket['BucketsFields'][$row->name]['target-aspect'];
}

$rand = rand(0, 900000);

if(isset($data[$row->name . '_media']['MediaId']))
{
  echo '<a href="' . site_url($cms['cp_base'] . '/media/add/') . 
  			'" class="bucket-add-image cancel-link hide no-deep-true ' . $class . '" id="image-' . $rand . '-' . $row->name . '" target-width="' . $width . '" target-height="' . $height . '" target-aspect="' . $aspect . '">Add Image</a>';
  echo '<a href="#" class="cancel-link bucket-media-remove no-deep-true" id="delete-' . $rand . '-' . $row->name . '">Delete Image</a>';
  echo '<div id="mediacont-' . $rand . '" class="media-add-cont">';
  
  echo '<a href="' . $data[$row->name . '_media']['sslurl'] . '" class="no-deep-true" target="_blank">';
  echo '<img src="' . $data[$row->name . '_media']['sslurl'] . '" alt="" width="200" class="thumb-image" /></a>';
  echo '<input type="hidden" name="' . $row->name . '" value="' . $data[$row->name] . '" />';
  echo '</div>';						
} else
{
  echo '<a href="' . site_url($cms['cp_base'] . '/media/add/') . 
  			'" class="bucket-add-image cancel-link no-deep-true ' . $class . '" id="image-' . $rand . '-' . $row->name . '" target-width="' . $width . '" target-height="' . $height . '" target-aspect="' . $aspect . '">Add Image</a>';
  echo '<a href="#" class="cancel-link hide bucket-media-remove no-deep-true" id="delete-' . $rand . '-' . $row->name . '">Delete Image</a>';
  echo '<div id="mediacont-' . $rand . '" class="media-add-cont"></div>';
}