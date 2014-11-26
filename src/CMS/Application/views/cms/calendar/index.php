<?=$this->load->view('cms/buckets/section-header')?>

<a href="/cp/calendar/range_update/<?=$bucket['CMS_BucketsId']?>" class="btn btn-primary pull-right" style="margin-bottom: 15px;">Update Dates</a>
<br style="clear: both;" />

<div id="calendar"></div>

<script type="text/javascript">
site.bucket_id = '<?=$bucket['CMS_BucketsId']?>';
site.calview();
</script>