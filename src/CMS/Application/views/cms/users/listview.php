<?=$this->load->view('cms/users/section-header')?>

<div class="row">
	<div class="span12">
		<div class="row">				
			<div class="span6 pull-left">
				<form class="tables-search-form" action="#" method="post">
					<input type="text" id="table-search" style="width: 220px;" value="<?=(empty($state['search'])) ? '' :  $state['search']?>" placeholder="Search" /><span class="add-on" style="vertical-align: top;">
				</form>
			</div>
			
			<div class="pull-right">
				<a href="<?=site_url($cms['cp_base'] . '/admin/users/add')?>" class="btn btn-primary">Add User</a>
			</div>
		</div>
	
		<table class="table table-bordered table-striped bump-up-10">
			<thead>
				<tr>
					<th>Name</th>
					<th>Email</th>
					<th>Date</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			
			<tbody cloud-api-url="<?=site_url('api/get?type=users&search={{search}}&format=json&order=CMS_UsersLastName&sort=ASC')?>" cloud-tmpl-cont="data-table-row" cloud-api-search="table-search"></tbody>
		</table>
	</div>
</div>


<script id="data-table-row" type="text/cloud-tmpl">
<tr>
	<td>{{CMS_UsersFirstName}} {{CMS_UsersLastName}}</td>
	<td>{{CMS_UsersEmail}}</td>
	<td>{{DateFormat1}}</td>
	<td>
		<a href="<?=site_url($cms['cp_base'] . '/admin/users/delete/id/')?>/{{CMS_UsersId}}" class="no-deep-false" cloud-api-delete="{{CMS_BlocksId}}:remove-fade:confirm:tr:slow">Delete</a> |
		<a href="<?=site_url($cms['cp_base'] . '/admin/users/edit')?>/{{CMS_UsersId}}">Edit</a>
	</td>
</tr>
</script>

<script type="text/javascript">
site.setup_tables();
</script>