$("document").ready(function() { 
	cloudjs.error_page = site_url + '/templates/page_not_found';
	cloudjs.page_title = page_title;	
	cloudjs.init(); 
});

//
// Call on deep link click.
//
cloudjs.history_click = function (that)
{
	// Set activate tab.
	if(that.closest('ul').hasClass('nav'))
	{
	  $('.nav li').removeClass('active');
	  that.closest('li').addClass('active');
	}
	
	return true;
} 