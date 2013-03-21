<?=$this->load->view('cms/admin/buckets/section-header')?>

<div class="row">
	<div class="span12">
		<div class="row">				
			<div class="span6 pull-left">
				<form class="tables-search-form" action="<?=current_url()?>" method="post">
					<input type="text" id="table-search" style="width: 220px;" value="<?=(empty($state['search'])) ? '' :  $state['search']?>" placeholder="Search" /><span class="add-on" style="vertical-align: top;">
				</form>
			</div>
			
			<div class="pull-right">
				<a href="<?=site_url($cms['cp_base'] . '/admin/buckets/add')?>" class="btn btn-primary">Add Bucket</a>
			</div>
		</div>
	
		<table class="table table-bordered table-striped bump-up-10">
			<thead>
				<tr>
					<th>Name</th>
					<th>Date</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			
			<tbody cloud-api-url="<?=site_url('api/get?type=buckets&search={{search}}&format=json&order=CMS_BucketsName&sort=ASC')?>" cloud-tmpl-cont="data-table-row" cloud-api-search="table-search"></tbody>
		</table>
				
	</div>
</div>


<script id="data-table-row" type="text/cloud-tmpl">
<tr>
	<td>{{CMS_BucketsName}}</td>
	<td>{{DateFormat1}}</td>
	<td>
		<a href="<?=site_url($cms['cp_base'] . '/admin/buckets/delete/id/')?>/{{CMS_BucketsId}}" class="no-deep-false" cloud-api-delete="{{CMS_BlocksId}}:remove-fade:confirm:tr:slow">Delete</a> |
		<a href="<?=site_url($cms['cp_base'] . '/admin/buckets/edit')?>/{{CMS_BucketsId}}">Edit</a> |
		<a href="<?=site_url($cms['cp_base'] . '/buckets/listview/')?>/{{CMS_BucketsId}}">Manage</a>
	</td>
</tr>
</script>

<script type="text/javascript">
site.setup_tables();
</script>