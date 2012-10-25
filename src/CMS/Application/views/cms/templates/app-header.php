<?php if(! $this->input->is_ajax_request()) : ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="robots" content="noindex,nofollow" />
	<title><?=$page_title?></title>
	<link href="<?=$cms['assets_base']?>/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="<?=$cms['assets_base']?>/css/smoothness/jquery-ui-1.8.13.custom.css" rel="stylesheet" type="text/css" />
	<link href="<?=$cms['assets_base']?>/javascript/colorbox/example5/colorbox.css" rel="stylesheet" type="text/css" />
	<link href="<?=$cms['assets_base']?>/css/jquery.tagit.css" rel="stylesheet" type="text/css" />
	<link href="<?=$cms['assets_base']?>/css/jquery.Jcrop.min.css" rel="stylesheet" type="text/css" />
	<link href="<?=$cms['assets_base']?>/javascript/plupload/jquery.plupload.queue/css/jquery.plupload.queue.css" rel="stylesheet" type="text/css" />
	<link href="<?=$cms['assets_base']?>/css/site.css" rel="stylesheet" type="text/css" />
	
	<script type="text/javascript"> 
	  var base_url = '<?=base_url()?>';
	  var site_url = '<?=site_url()?>';
	  var assets_base = '<?=$cms['assets_base']?>';
	  var cp_base = '<?=site_url($cms['cp_base'])?>';
		var page_title = '<?=$page_title?>';
		var cur_url = '<?=current_url()?>';
	</script> 
	
	<script type="text/javascript" src="<?=$cms['assets_base']?>/javascript/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="<?=$cms['assets_base']?>/javascript/jquery-ui-1.8.13.custom.min.js"></script>
	<script type="text/javascript" src="<?=$cms['assets_base']?>/javascript/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?=$cms['assets_base']?>/javascript/colorbox/jquery.colorbox-min.js"></script>
	<script type="text/javascript" src="<?=$cms['assets_base']?>/javascript/tag-it.js"></script>
	<script type="text/javascript" src="<?=$cms['assets_base']?>/javascript/plupload/plupload.js"></script>
	<script type="text/javascript" src="<?=$cms['assets_base']?>/javascript/plupload/plupload.html5.js"></script>
	<script type="text/javascript" src="<?=$cms['assets_base']?>/javascript/plupload/plupload.flash.js"></script>
	<script type="text/javascript" src="<?=$cms['assets_base']?>/javascript/plupload/jquery.plupload.queue/jquery.plupload.queue.js"></script>
	<script type="text/javascript" src="<?=$cms['assets_base']?>/javascript/jquery.Jcrop.js"></script>
	<script type="text/javascript" src="<?=$cms['assets_base']?>/javascript/cloudjs.2012.4.20.min.js"></script>
	<script type="text/javascript" src="<?=$cms['assets_base']?>/javascript/cloudjs-config.js"></script>
	<script type="text/javascript" src="<?=$cms['assets_base']?>/javascript/site.js"></script>
	<script type="text/javascript" src="<?=$cms['assets_base']?>/javascript/media.js"></script>
</head>

<body>
	<div class="navbar topbar">
	  <div class="navbar-inner">
	    <div class="container">
	  		<a class="brand" href="<?=site_url($cms['cp_base'] . '/blocks')?>"><?=$cms['site_name']?></a>

				<ul class="nav">
					<?php foreach($nav AS $key => $row) : ?>
						<li class="dropdown">
					    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?=$row['NavName']?> <b class="caret"></b></a>
					    <ul class="dropdown-menu">
								<?php foreach($row['Kids'] AS $key2 => $row2) : ?>
					      	<li>
					      		<a href="<?=$row2['href']?>" <?=(! empty($row2['NavTarget'])) ? 'target="' . $row2['NavTarget'] . '" class="no-deep-true"' : ''?>>
					      			<?=$row2['NavName']?>
										</a>
									</li>
								<?php endforeach; ?>
							</ul>
						</li>
					<?php endforeach; ?>
				</ul>		
	  		
				<ul class="nav pull-right">
					<li class="dropdown">
				    <a href="#" class="dropdown-toggle" data-toggle="dropdown">My Account <b class="caret"></b></a>
				    <ul class="dropdown-menu">
				      <li><a href="<?=site_url($cms['cp_base'] . '/login/logout')?>" class="no-deep-true">Logout</a></li>
						</ul>
					</li>
				</ul>
	    </div>
	  </div>
	</div>
	
	<div class="container">
		<div class="content" id="cloud-body">
<?php endif; ?>