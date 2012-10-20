<?=$this->load->view('cms/media/section-header')?>

<div class="row">
	<div class="span12">
		<div class="row">				
			<div class="span6 pull-left">
				<form class="tables-search-form" action="#" method="post">
					<input type="text" id="table-search" style="width: 220px;" value="<?=(empty($state['search'])) ? '' :  $state['search']?>" placeholder="Search" /><span class="add-on" style="vertical-align: top;">
				</form>
			</div>
			
			<div class="pull-right">
				<a href="<?=site_url($cms['cp_base'] . '/media/add/')?>" class="btn btn-primary media-colorbox no-deep-true">Add Media</a>
			</div>
		</div>
	
		<table class="table table-bordered table-striped bump-up-10">
			<thead>
				<tr>
					<th>Thumb</th>
					<th>File</th>
					<th>Size</th>
					<th>Type</th>
					<th>Created</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			
			<tbody cloud-api-url="<?=site_url('cp/api/get?type=media&order=MediaId&sort=DESC&search={{search}}&format=json')?>" cloud-tmpl-cont="data-table-row" cloud-api-search="table-search"></tbody>
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
  <td>
		<a href="{{url}}" target="_blank" class="no-deep-true">
			<img src="{{thumbsslurl}}" alt="" width="70" height="70" />
		</a>
  </td>
  <td>{{FileEllipse}}</td>
  <td>{{MediaSize}}kb</td>
	<td>{{MediaType}}</td>
  <td>{{DateFormat1}}</td>
  <td>
		<a href="<?=site_url($cms['cp_base'] . '/media/delete/')?>/{{MediaId}}" class="no-deep-false" cloud-api-delete="{{MediaId}}:remove-fade:confirm:tr:slow">Delete</a>
  </td>
</tr>
</script>

<script type="text/javascript">
site.setup_tables();
media.max_file_size = '<?=($cms['cp_media_file_max_size'] * 0.0009765625)?>mb';
media.filter = '<?=str_ireplace('|', ',', $cms['cp_media_file_types'])?>';
media.init();
</script>