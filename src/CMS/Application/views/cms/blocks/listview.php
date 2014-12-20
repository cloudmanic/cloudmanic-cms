<?php $search = (isset($_GET['search'])) ? $_GET['search'] : ''; ?>
<?=$this->load->view('cms/blocks/section-header')?>

<div class="row">
	<div class="span12">
		<div class="row">				
			<div class="span6 pull-left">
				<form class="tables-search-form" action="<?=current_url()?>"  method="get">
					<input type="text" id="table-search" name="search" style="width: 220px;" value="<?=$search?>" placeholder="Search" /><span class="add-on" style="vertical-align: top;">
				</form>
			</div>
			
			<div class="pull-right">
				<a href="<?=site_url($cms['cp_base'] . '/blocks/add')?>" class="btn btn-primary">Add Block</a>
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
			
			<tbody cloud-api-url="<?=site_url('api/get?type=blocks&search=' . $search . '&format=json&order=CMS_BlocksName&sort=ASC')?>" cloud-tmpl-cont="data-table-row" cloud-api-search="table-search"></tbody>
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
	<td>{{CMS_BlocksName}}</td>
	<td>{{DateFormat1}}</td>
	<td>
		<a href="<?=site_url($cms['cp_base'] . '/blocks/delete/id/')?>/{{CMS_BlocksId}}" class="no-deep-false" cloud-api-delete="{{CMS_BlocksId}}:remove-fade:confirm:tr:slow">Delete</a> |
		<a href="<?=site_url($cms['cp_base'] . '/blocks/edit')?>/{{CMS_BlocksId}}">Edit</a>
	</td>
</tr>
</script>

<script type="text/javascript">
site.setup_tables();
</script>