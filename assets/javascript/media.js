//
// Company: Cloudmanic Labs, LLC
// Website: http://cloudmanic.com
//

var media = {
	filter: '',
	max_file_size: '10mb',
	bucket: false,
	onefile: false,
	mediafiles: [],
	bucketid: 0,
	buckettype: 'image',
	bucketfield: '',
	crop: false,
	target_width: '',
	target_height: '',
	target_aspect: ''	
}

//
// Load up media.
//
media.init = function ()
{
	// Setup colorbox to add media.
	$('.media-colorbox').colorbox({ width: '80%', height: '395px'});
	
	// Delete media from a bucket add / edit page.
	$('.bucket-media-remove').live('click', function () {
		var con = confirm('Are you sure you want to remove this media file?');
		
		if(con)
		{
			var info = $(this).attr('id').split('-');
			var href = $(this).attr('href');
			var html = '<input type="hidden" name="' + info[2] + '" value="0" />';
			$('#mediacont-' + info[1]).html(html);
			$('#image-' + info[1] + '-' + info[2]).show();
			$('#delete-' + info[1] + '-' + info[2]).hide();
		}
		
		return false;
	});
	
	// Add bucket add image click.
	$('.bucket-add-image').click(function () {
		var info = $(this).attr('id').split('-');
		var href = $(this).attr('href');
		media.buckettype = info[0];
		media.bucketid = info[1];
		media.bucketfield = info[2];
		media.onefile = true;
		media.bucket = true;
		media.target_width = $(this).attr('target-width');
		media.target_height = $(this).attr('target-height');
		media.target_aspect = $(this).attr('target-aspect');
		media.crop = false;
		media.mediafiles = [];
		
		// See if this is a crop acction.
		if($(this).hasClass('crop'))
		{
			media.crop = true;
		}
		
		switch(media.buckettype)
		{
			case 'image':
				media.filter = 'gif,jpg,jpeg,png';
			break;
		}
		
		$.colorbox({ href: href, width: '80%', height: '395px'});
		return false;
	});
}

//
// Crop an image view.
//
media.crop_image_view = function (width, height, targetaspect, targetheight, targetwidth)
{ 	
	// Resize window
	$.colorbox.resize({ width: width, height: height });
	
	// Set the submit event for the cordinate form.
	$('#crop-form').submit(function () {
		$('[name="view_height"]').val($('#crop-image').height()); 
		$('[name="view_width"]').val($('#crop-image').width()); 
		var data = $(this).serialize();
		var url = $(this).attr('action');
		
		$.post(url, data, function (json) {
			media.return_image_to_view(json.data);
			$.colorbox.close();
		}, 'json');
		
		return false; 
	});
	
	// Setup Jcrop
	var settings = {
		onChange: media.crop_box_move,
		setSelect: [ 0, 0, 300, 300 ],
		bgOpacity: .3
	}
	
	// If we pass in an aspect make sure we enforce it.
	if(targetaspect.length > 0)
	{
		var width = 200 * targetaspect;
		settings.aspectRatio = targetaspect;
		settings.setSelect = [ 0, 0, 200, width ];
	}
	
	$('#crop-image').Jcrop(settings);
	
	// Crop Image.
	$('#crop-now').click(function () {
		$(this).hide();
		$('#crop-image').hide();
		$('.jcrop-holder').hide();
		$('.crop-loader').show();
		$('.image-crop-cont').css('border', 'none');
		$.colorbox.resize({width: '440px', height: '380px'});
		$('#crop-form').submit();
		return false;
	});
}

// 
// We call this whenever someone moves the crop box.
//
media.crop_box_move = function (c)
{
	$('#x1').val(c.x);
	$('#y1').val(c.y);
	$('#x2').val(c.x2);
	$('#y2').val(c.y2);
	$('#w').val(c.w);
	$('#h').val(c.h);
}

//
// Print the html to the view for an uploaded image.
// 
media.return_image_to_view = function (json)
{
	var html = '<a href="' + json.sslurl + '" class="no-deep-true" target="_blank">';
	html += '<img src="' + json.sslurl + '" alt="" width="200" class="thumb-image" /></a>';
	html += '<input type="hidden" name="' + media.bucketfield + '" value="' + json.id + '" />';
	$('#mediacont-' + media.bucketid).html(html);
	$('#image-' + media.bucketid + '-' + media.bucketfield).hide();
	$('#delete-' + media.bucketid + '-' + media.bucketfield).show();
}

//
// We call this to setup the add / edit page 
// for uploading media.
//
media.add_edit_init = function ()
{
	var error = false; 
	
	// Setup pluploader
	$("#uploader").pluploadQueue({
	  runtimes: 'html5,flash',
	  max_file_size: media.max_file_size,
	  url: cp_base + '/media/upload',
	  flash_swf_url: assets_base + '/javascript/plupload/plupload.flash.swf',
		filters : [
			{title: "Allowed Files", extensions: media.filter},
		],

		init: {
			// On Error
			Error: function(up, ErrorObj) {
				$('#media-error').append('<p>Error occurred uploading file ' + ErrorObj.file.name + '</p>');
				$.colorbox.resize();
				error = true;
			},
			
			// On File Uploaded
			FileUploaded: function(Up, File, Response) {
				var res = jQuery.parseJSON(Response.response);
				if(! res.status)
				{
					error = true;
					$.each(res.errors, function (index, row) {
						$('#media-error').append('<p>' + File.name + ': ' + res.errors[index] + '</p>');
					});
					$.colorbox.resize();
				} else
				{
					media.mediafiles.push(res.data);
				}
			},
			
			// On Upload complete
			UploadComplete: function(up, files) {
				if((! error) && (! media.bucket))
				{
					window.location.href = window.location.href;
				}
				
				// Show image on add / edit page.
				if((! error) && (media.bucket))
				{
					if(media.crop)
					{
						console.log(media.mediafiles[0]);
						// Make an ajax call and bring up the cropper.
						$.post(cp_base + '/media/crop/', { media: media.mediafiles[0], width: media.target_width, height: media.target_height, aspect: media.target_aspect }, function(html) {
							$('#colorbox-body').html(html);
						}, 'html');
					} else
					{
						$.each(media.mediafiles, function(index, row) {
							media.return_image_to_view(media.mediafiles[index]);
/*
							var html = '<a href="' + media.mediafiles[index].sslurl + '" class="no-deep-true" target="_blank">';
							html += '<img src="' + media.mediafiles[index].sslurl + '" alt="" width="200" class="thumb-image" /></a>';
							html += '<input type="hidden" name="' + media.bucketfield + '" value="' + media.mediafiles[index].id + '" />';
							$('#mediacont-' + media.bucketid).html(html);
							$('#image-' + media.bucketid + '-' + media.bucketfield).hide();
							$('#delete-' + media.bucketid + '-' + media.bucketfield).show();
*/
						});
						$.colorbox.close();
					}
				}
			},
			
			// Make it so we only allow a user to upload one file. 
			FilesAdded: function(up, files) {
				if(media.onefile)
				{
					while(up.files.length > 1) 
					{
						up.removeFile(up.files[0]);
					}
					up.refresh();
				}
			}
		}
	});
}