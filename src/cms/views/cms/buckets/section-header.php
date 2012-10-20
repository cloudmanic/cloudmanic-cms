<?php
$name = (isset($bucket['BucketsDisplay']['name'])) ? $bucket['BucketsDisplay']['name'] : $bucket['BucketsName'];
?>

<div class="page-header">
	<h1>
		<?=$name?> 
		<small data-content="<?=$bucket['BucketsDescription']?>" data-original-title="Bucket Description" rel="popover"><?=$bucket['BucketsHeadline']?></small>
	</h1>
</div>