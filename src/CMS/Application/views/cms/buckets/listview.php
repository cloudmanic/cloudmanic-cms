<?php
$add_name = (isset($bucket['CMS_BucketsDisplay']['add-button'])) ? $bucket['CMS_BucketsDisplay']['add-button'] : 'Add ' . cms_depluralize($table);

$search = (empty($state['search'])) ? '' :  $state['search'];

$order = (isset($bucket['CMS_BucketsListview']['order'])) ? $bucket['CMS_BucketsListview']['order'] :  $table . 'Order';

$sort = (isset($bucket['CMS_BucketsListview']['sort'])) ? $bucket['CMS_BucketsListview']['sort'] :  'desc';

$sortable = (isset($bucket['CMS_BucketsListview']['sortable'])) ? $bucket['CMS_BucketsListview']['sortable'] :  'Yes'; 

$url_extra = (isset($bucket['CMS_BucketsListview']['url_extra'])) ? $bucket['CMS_BucketsListview']['url_extra'] :  ''; 

?>

<?=$this->load->view('cms/buckets/section-header')?>

<div class="row">
	<div class="span12">
		<div class="row">				
			<div class="span6 pull-left">
				<form class="tables-search-form" action="<?=current_url()?>" method="get">
					<input type="text" id="table-search" name="search" style="width: 220px;" value="<?=(empty($state['search'])) ? '' :  $state['search']?>" placeholder="Search" /><span class="add-on" style="vertical-align: top;">
				</form>
			</div>
			
			<div class="pull-right">
				<a href="<?=site_url($cms['cp_base'] . '/buckets/add/' . $bucket['CMS_BucketsId'])?>" class="btn btn-primary"><?=$add_name?></a>
			</div>
		</div>
	
		<table class="table table-bordered table-striped bump-up-10">
			<thead>
				<tr>
					<?php if($bucket['CMS_BucketsName'] == 'Users') : ?>
					<th>First Name</th>
					<th>Last Name</th>
					<th>Date</th>
					<?php endif; ?>
					
					<?php if(isset($bucket['CMS_BucketsListview']['columns']) && (! empty($bucket['CMS_BucketsListview']['columns']))) : ?>
						<?php foreach($bucket['CMS_BucketsListview']['columns'] AS $key => $row) : ?>
							<th><?=$row?></th>
						<?php endforeach; ?>
					<?php else : ?>
					<th>Title</th>
					<th>Created</th>	
					<th>Updated</th>										
					<?php endif; ?>
					<th>&nbsp;</th>
				</tr>
			</thead>
			
			<tbody cloud-api-url="<?=site_url('api/get?type=bucket&bucket=' . $bucket['CMS_BucketsId'] . '&order=' . $order . '&sort=' . $sort . '&search=' . $search . '&limit={{limit}}&offset={{offset}}&format=json&base=' . current_url())?><?=$url_extra?>" cloud-tmpl-cont="data-table-row" cloud-api-search="table-search" cloud-api-offset="<?=(empty($state['offset'])) ? '0' :  $state['offset']?>" cloud-api-limit="<?=(empty($state['limit'])) ? '50' :  $state['limit']?>"></tbody>
		</table>
		
		<div id="paging"></div>		
	</div>
</div>


<script id="data-table-row" type="text/cloud-tmpl">
<tr data-id="{{<?=$bucket['CMS_BucketsTable']?>Id}}">
		
	<?php if(isset($bucket['CMS_BucketsListview']['columns']) && (! empty($bucket['CMS_BucketsListview']['columns']))) : ?>
		<?php foreach($bucket['CMS_BucketsListview']['columns'] AS $key2 => $row2) : ?>
			<td>{{<?=$key2?>}}</td>
		<?php endforeach; ?>
	<?php else : ?>
		<td>{{<?=$bucket['CMS_BucketsTable']?>Title}}</td>	
		<td>{{CreateDateFormat1}}</td>
		<td>{{UpdatedAtColFormat1}}</td>		
	<?php endif; ?>
	<td <?=(! empty($bucket['CMS_BucketsViewUrl'])) ? 'style="width: 120px;"' : 'style="width: 80px;"'?>>
		<a href="<?=site_url($cms['cp_base'] . '/buckets/delete/' . $bucket['CMS_BucketsId'])?>/{{<?=$bucket['CMS_BucketsTable']?>Id}}" class="no-deep-false" cloud-api-delete="{{<?=$bucket['CMS_BucketsTable']?>Id}}:remove-fade:confirm:tr:slow">Delete</a> |
		
		<a href="<?=site_url($cms['cp_base'] . '/buckets/edit/' . $bucket['CMS_BucketsId'])?>/{{<?=$bucket['CMS_BucketsTable']?>Id}}">Edit</a>
		
		<?php 
  		if(! empty($bucket['CMS_BucketsViewUrl'])) : 
        $url = $bucket['CMS_BucketsViewUrl'];
        
        // Replace tags
        $url = str_replace('{id}', '{{' . $bucket['CMS_BucketsTable'] . 'Id}}', $url);
        $url = str_replace('{slug}', '{{TitleSlug}}', $url); 
        $url = str_replace('{groupid}', '{{' . $bucket['CMS_BucketsTable'] . 'GroupId}}', $url);                 
    ?>
    | <a href="<?=$url?>" target="_blank">View</a>
    <?php endif; ?>
	</td>
</tr>
</script>

<script type="text/javascript">
site.bucket_id = '<?=$bucket['CMS_BucketsId']?>';
site.setup_tables();

<?php if($sortable != 'No') : ?>
site.table_sortable();
<?php endif; ?>

site.listview();
</script>