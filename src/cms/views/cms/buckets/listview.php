<?php
$add_name = (isset($bucket['BucketsDisplay']['add-button'])) ? $bucket['BucketsDisplay']['add-button'] : 'Add ' . cms_depluralize($prefix);
?>

<?=$this->load->view('cms/buckets/section-header')?>

<div class="row">
	<div class="span12">
		<div class="row">				
			<div class="span6 pull-left">
				<form class="tables-search-form" action="#" method="post">
					<input type="text" id="table-search" style="width: 220px;" value="<?=(empty($state['search'])) ? '' :  $state['search']?>" placeholder="Search" /><span class="add-on" style="vertical-align: top;">
				</form>
			</div>
			
			<div class="pull-right">
				<a href="<?=site_url($cms['cp_base'] . '/buckets/add/' . $bucket['BucketsId'])?>" class="btn btn-primary"><?=$add_name?></a>
			</div>
		</div>
	
		<table class="table table-bordered table-striped bump-up-10">
			<thead>
				<tr>
					<?php if($bucket['BucketsName'] == 'Users') : ?>
					<th>First Name</th>
					<th>Last Name</th>
					<th>Date</th>
					<?php endif; ?>
					
					<?php if(isset($bucket['BucketsListview']['columns']) && (! empty($bucket['BucketsListview']['columns']))) : ?>
						<?php foreach($bucket['BucketsListview']['columns'] AS $key => $row) : ?>
							<th><?=$row?></th>
						<?php endforeach; ?>
					<?php else : ?>
					<th>Title</th>
					<th>Date</th>					
					<?php endif; ?>
					<th>&nbsp;</th>
				</tr>
			</thead>
			
			<tbody cloud-api-url="<?=site_url('api/get?type=bucket&bucket=' . $bucket['BucketsId'] . '&order=' . $prefix . 'Order&sort=DESC&search={{search}}&format=json')?>" cloud-tmpl-cont="data-table-row" cloud-api-search="table-search"></tbody>
		</table>
		
		<?php /*
		<ul class="pager">
		  <li class="previous"><a href="#">&larr; Older</a></li>
		  <li>
		  	<div class="table-showing">
		  		Showing <span class="table-start">0</span> to <span class="table-end">0</span> of <span class="table-total">0</span> entries <span class="table-filtered-string">(filtered from <span class="table-filtered">0</span> total entries)</span>
				</div>
		  </li>
		  <li class="next"><a href="#">Newer &rarr;</a></li>
		</ul>
		*/ ?>
		
	</div>
</div>


<script id="data-table-row" type="text/cloud-tmpl">
<tr>
	<?php if($bucket['BucketsName'] == 'Users') : ?>
		<td>{{UsersFirstName}}</td>
		<td>{{UsersLastName}}</td>
		<td>{{CreateDateFormat1}}</td>
	<?php endif; ?>
		
	<?php if(isset($bucket['BucketsListview']['columns']) && (! empty($bucket['BucketsListview']['columns']))) : ?>
		<?php foreach($bucket['BucketsListview']['columns'] AS $key2 => $row2) : ?>
			<td>{{<?=$key2?>}}</td>
		<?php endforeach; ?>
	<?php else : ?>
		<td>{{<?=$bucket['BucketsName']?>Title}}</td>	
		<td>{{CreateDateFormat1}}</td>
	<?php endif; ?>
	<td>
		<a href="<?=site_url($cms['cp_base'] . '/buckets/delete/' . $bucket['BucketsId'])?>/{{<?=$bucket['BucketsName']?>Id}}" class="no-deep-false" cloud-api-delete="{{<?=$bucket['BucketsName']?>Id}}:remove-fade:confirm:tr:slow">Delete</a> |
		<a href="<?=site_url($cms['cp_base'] . '/buckets/edit/' . $bucket['BucketsId'])?>/{{<?=$bucket['BucketsName']?>Id}}">Edit</a> |
		<a href="<?=site_url($cms['cp_base'] . '/buckets/move_up/' . $bucket['BucketsId'])?>/{{<?=$bucket['BucketsName']?>Id}}">Up</a>
	</td>
</tr>
</script>

<script type="text/javascript">
site.setup_tables();
site.table_sortable();
</script>


<?php
/*
<div class="top-bump">
	<div class="left-column">
		<?php //$this->load->view('cms/buckets/side-bar')?>
		<?php // $prefix $this->load->view('cms/buckets/side-bar-help')?>
	</div>
	
	<div class="right-column">
			<div class="data-header-third">
				<p>
					Below you will find a list of <?=$prefix?>. You may edit or delete. 
				</p>
				<a href="<?=site_url($cms['cp_base'] . '/buckets/add/' . $bucket['BucketsId'])?>" class="button">Add <?=cms_depluralize($prefix)?></a>
				<br style="clear: both;" />	
			</div>
			
			<table class="data-table">
				<thead>
					<tr>
						<th>Name</th>
						<th>Status</th>
						<th>Created</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
				
				<tbody>
					<?php foreach($data AS $key => $row) : ?>
					<tr>
						<td><?=(isset($row['UsersFirstName'])) ? $row['UsersFirstName'] . ' ' . $row['UsersLastName']  : $row[$prefix . 'Title']?></td>
						<td><?=$row[$prefix . 'Status']?></td>
						<td><?=date('n/j/Y', strtotime($row[$prefix . 'CreatedAt']))?></td>
						<td>
							<?=anchor($cms['cp_base'] . '/buckets/delete/' . $bucket['BucketsId'] . '/' . $row[$prefix . 'Id'], 'Delete', 'class="confirm"')?> |
							<?=anchor($cms['cp_base'] . '/buckets/edit/' . $bucket['BucketsId'] . '/' . $row[$prefix . 'Id'], 'Edit')?> |
							<?=anchor($cms['cp_base'] . '/buckets/move_up/' . $bucket['BucketsId'] . '/' . $row[$prefix . 'Id'], 'Down')?> 
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
	</div>
	<br style="clear: both;" />
</div>
*/
?>