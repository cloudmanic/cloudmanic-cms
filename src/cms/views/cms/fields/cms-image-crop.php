<?php
$rand = rand(0, 900000);

if(isset($data[$row->name . '_media']['MediaId']))
{
  echo '<a href="' . site_url($cms['cp_base'] . '/media/add/') . 
  			'" class="bucket-add-image cancel-link hide no-deep-true" id="image-' . $rand . '-' . $row->name . '">Add Image</a>';
  echo '<a href="#" class="cancel-link bucket-media-remove no-deep-true" id="delete-' . $rand . '-' . $row->name . '">Delete Image</a>';
  echo '<div id="mediacont-' . $rand . '" class="media-add-cont">';
  
  echo '<a href="' . $data[$row->name . '_media']['sslurl'] . '" class="no-deep-true" target="_blank">';
  echo '<img src="' . $data[$row->name . '_media']['sslurl'] . '" alt="" width="200" class="thumb-image" /></a>';
  echo '<input type="hidden" name="' . $row->name . '" value="' . $data[$row->name] . '" />';
  echo '</div>';						
} else
{
  echo '<a href="' . site_url($cms['cp_base'] . '/media/add/') . 
  			'" class="bucket-add-image cancel-link no-deep-true" id="image-' . $rand . '-' . $row->name . '">Add Image</a>';
  echo '<a href="#" class="cancel-link hide bucket-media-remove no-deep-true" id="delete-' . $rand . '-' . $row->name . '">Delete Image</a>';
  echo '<div id="mediacont-' . $rand . '" class="media-add-cont"></div>';
}