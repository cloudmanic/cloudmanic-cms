<?=$this->load->view('cms/buckets/section-header')?>

<p>
	Below you can set a start date, end date, a unit, weekend price, and a week price. It will go through the date range and update the prices. <b>Be careful. This is powerful. Make sure you double check before hitting "Update Dates".</b> 
	
</p>

<form action="/cp/calendar/range_update/<?=$bucket['CMS_BucketsId']?>" method="post" >
	<div class="control-group">
		<label>Unit</label>
		<select name="unit">
			<option value="High Gear">High Gear</option>
			<option value="Low Gear">Low Gear</option>			
		</select>
	</div>

	<div class="control-group">
		<label>Start Date</label>
		<input type="text" name="start" value="" placeholder="xx/xx/xxxx" />	
	</div>
	
	<div class="control-group">
		<label>End Date</label>
		<input type="text" name="end" value="" placeholder="xx/xx/xxxx" />	
	</div>
	
	<div class="control-group">
		<label>Weekday Price</label>
		<input type="text" name="week" value="" placeholder="150.00" />	
	</div>
	
	<div class="control-group">
		<label>Weekend Price</label>
		<input type="text" name="weekend" value="" placeholder="150.00" />	
	</div>		
	
	<input type="submit" class="btn btn-primary" value="Update Dates" /> or <a href="/cp/calendar/calview/<?=$bucket['CMS_BucketsId']?>">cancel</a>
</form>


<script type="text/javascript">
site.bucket_id = '<?=$bucket['CMS_BucketsId']?>';
</script>