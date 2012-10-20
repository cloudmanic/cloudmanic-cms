var site = {
	state: {}
}

//
// Setup generic search tables.
//
site.setup_tables = function (that)
{
	// On search form submit.
	$('.tables-search-form').submit(function () {	
		site.state.search = $('#table-search').val();
		site.load_state_page(cur_url);		
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
		containment: 'table'
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