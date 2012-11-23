<?php
$name = (isset($bucket['CMS_BucketsDisplay']['name'])) ? $bucket['CMS_BucketsDisplay']['name'] : $bucket['CMS_BucketsName'];
?>

<div class="page-header">
	<h1>
		<?=$name?> 
		<small data-content="<?=$bucket['CMS_BucketsDescription']?>" data-original-title="Bucket Description" rel="popover"><?=$bucket['CMS_BucketsHeadline']?></small>
	</h1>
</div>