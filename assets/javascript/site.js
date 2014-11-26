var site = {
	state: {},
	bucket_id: '0'
}

//
// Deal with the calendar view.
//
site.calview = function ()
{
	// Setup the calendar view.
	$('#calendar').fullCalendar({
		events: '/cp/calendar/pricing/' + site.bucket_id,
    
    eventClick: function(calEvent, jsEvent, view) { 
			var price = prompt('Enter price for this date.', calEvent.price);
			
			if(price != null) 
			{
				calEvent.price = price;
				calEvent.title = calEvent.unit + ' $' + price;
				$('#calendar').fullCalendar('updateEvent', calEvent);
				
				// Send Ajax call to server to update price.
				$.post('/cp/calendar/pricing_update/' + site.bucket_id, { id: calEvent.id, price: price, unit: calEvent.unit }, function (json) {
					//console.log(json);
				});
				
			}
    }    
	});
}

//
// List view page.
//
site.listview = function ()
{
	// Setup paging.
	cloudjs.api.after_api = function (json) {
		if(json.paging)
		{
			$('#paging').html(json.paging);
		
			$('#paging a').click(function () {
				var url = $('.tables-search-form').attr('action');
				site.state.search = $('#table-search').val();
				site.state.offset = $(this).attr('href').replace("&offset=", '');
				site.load_state_page(url);
				return false;
			});
		}
	}
}

//
// Setup generic search tables.
//
site.setup_tables = function (that)
{
	// On search form submit.
	$('.tables-search-form').submit(function () {	
		var url = $(this).attr('action');
		site.state.search = $('#table-search').val();
		site.state.offset = $('#table-offset').val();
		site.load_state_page(url);		
		cloudjs.focus = 'table-search';
		return false;
	});
}

//
// Make a table sortable.
//
site.table_sortable = function ()
{
	$('tbody').sortable({
		containment: 'table',
		stop: function(event, ui) {
			var ids = [];
			$('tbody tr').each(function () {
				var id = $(this).attr('data-id');
				ids.push(id);
			});			
			
			$.post(site_url + 'api/get?type=bucket-reorder&bucket=' + site.bucket_id, { ids: ids }, function (json) {
				//alert(json);
			});
		}
	});
}

//
// Build the get state params and load the new page.
//
site.load_state_page = function (base)
{
	var value = base.replace(/\/+$/, '') + '/?' + $.param(site.state);	
	cloudjs.history.pushState('', cloudjs.page_title, value);
}

//
// We call this on every page load.
//
site.on_new_page = function ()
{
	// Globel Datepicker
	$('.datepicker').datepicker({
		dateFormat: 'm/d/yy'
	});

	// Sidewide popovers.
	$('[rel="popover"]').popover({ 
		placement: 'bottom',
		delay: { show: 500, hide: 100 } 
	});
}

//
// Setup a add / edit page.
//
site.add_edit_init = function (type, bucket_id, entry_id)
{		
	// Make Ajax call to get the next bucket entry (if any).
	if(type == 'edit')
	{
		$.get(site_url + 'api/get?type=bucket-next&format=json&bucket=' + bucket_id + '&current=' + entry_id, function (json) {
			if(json.data.Id)
			{
				var url = site_url + 'buckets/edit/' + bucket_id + '/' + json.data.Id;
				$('#redirect_url').val(url);
				$('#save_cont').show();
			} else
			{
				$('#save_cont').hide();			
			}
		});
	}
	
	// Save and continue editing
	$('#save_cont_editing').click(function () {
		var url = $('form').attr('action');
		
		$.post(url, $('form').serialize(), function (json) {		
			if(json.Id)
			{
				$('.control-group').removeClass('error');
				$('.help-block').html('');
				$('#saved').fadeIn('slow', function () { $(this).fadeOut('slow'); });
				
				if(type == 'add')
				{
					var newurl = site_url + 'buckets/edit/' + bucket_id + '/' + json.Id;
					$('form').attr('action', newurl);
				}
			} else
			{
				$('#cloud-body').html(json);
			}
		});
		
		return false;
	});

	// Delete entry.
	$('[data-action="entry-delete"]').click(function () {
		var url = $(this).attr('href');
		var cc = confirm('Are you sure you want to delete this entry?');
		
		if(cc)
		{
			window.location = url;
		}
		
		return false;
	});
}