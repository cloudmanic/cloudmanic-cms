<div class="media-selector">
	
	<ul>
		<?php 
			foreach($media AS $key => $row) : 
				if(($row['CMS_MediaType'] != 'jpeg') &&
						($row['CMS_MediaType'] != 'jpg') &&
						($row['CMS_MediaType'] != 'gif') &&
						($row['CMS_MediaType'] != 'png'))
				{
					echo $row['CMS_MediaType'];
					continue;
				} 	
		?>
		<li>
			<a href="<?=$row['sslurl']?>" class="image-click">
				<img src="<?=$row['thumbsslurl']?>" width="150" />
			</a>
		</li>
		<?php endforeach; ?>
	</ul>
	<br style="clear: both;" />
	
</div>